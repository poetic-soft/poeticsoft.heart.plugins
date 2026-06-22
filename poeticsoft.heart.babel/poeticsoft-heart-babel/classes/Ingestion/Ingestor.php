<?php

namespace Poeticsoft\Heart\Babel\Ingestion;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Ingestion\Converters\MarkdownConverter;
use Poeticsoft\Heart\Babel\Ingestion\Converters\PdfConverter;
use Poeticsoft\Heart\Babel\Ingestion\Converters\DocxConverter;
use Poeticsoft\Heart\Babel\Indexing\IndexManager;

/**
 * Main Ingestor class.
 * Coordinates document scanning, conversion to markdown, and state management recursively.
 * Safely archives corrupt or unextractable files into a 'failed' directory.
 */
class Ingestor
{
    /**
     * Base uploads directory for Babel.
     *
     * @var string
     */
    private $base_dir;

    /**
     * Registered converters.
     *
     * @var array
     */
    private $converters = [];

    /**
     * Initialize paths and register converters.
     */
    public function __construct()
    {
        $upload_dir     = wp_upload_dir();
        $this->base_dir = trailingslashit($upload_dir['basedir']) . 'babel/';

        // Create folder structure if it doesn't exist.
        $this->ensure_directories();

        // Register default strategy converters.
        $this->register_converter(Babel::get(MarkdownConverter::class));
        $this->register_converter(Babel::get(PdfConverter::class));
        $this->register_converter(Babel::get(DocxConverter::class));
    }

    /**
     * Hooks initialization. Registers and schedules the autonomous raw scanning cron.
     */
    public function init()
    {
        // Register the custom action with the Ingestor's processor.
        add_action(Babel::PREFIX . 'scan_raw_cron', [$this, 'ingest_all_pending']);

        // Schedule the hourly event if it's not already scheduled.
        if (! wp_next_scheduled(Babel::PREFIX . 'scan_raw_cron')) {
            wp_schedule_event(time(), 'hourly', Babel::PREFIX . 'scan_raw_cron');
        }
    }

    /**
     * Register a converter implementing ConverterInterface.
     *
     * @param object $converter Converter instance.
     */
    public function register_converter($converter)
    {
        $this->converters[] = $converter;
    }

    /**
     * Ensure required folders exist inside uploads/babel/
     */
    private function ensure_directories()
    {
        $directories = [
            $this->base_dir,
            $this->base_dir . 'raw',
            $this->base_dir . 'converted',
            $this->base_dir . 'processed',
            $this->base_dir . 'failed',
        ];

        foreach ($directories as $dir) {
            if (! file_exists($dir)) {
                wp_mkdir_p($dir);
            }
        }
    }

    /**
     * Get paths of raw directory.
     */
    public function get_raw_dir()
    {
        return $this->base_dir . 'raw';
    }

    /**
     * Get paths of converted directory.
     */
    public function get_converted_dir()
    {
        return $this->base_dir . 'converted';
    }

    /**
     * Get paths of processed directory.
     */
    public function get_processed_dir()
    {
        return $this->base_dir . 'processed';
    }

    /**
     * Get paths of failed/corrupt directory.
     */
    public function get_failed_dir()
    {
        return $this->base_dir . 'failed';
    }

    /**
     * Scan 'raw/' directory recursively for pending documents.
     *
     * @return array List of pending file paths relative to raw/ directory.
     */
    public function scan_raw_dir()
    {
        $dir   = $this->get_raw_dir();
        $files = [];

        if (! is_dir($dir)) {
            return $files;
        }

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file_info) {
                if ($file_info->isFile()) {
                    // Get the path relative to the raw/ directory.
                    $real_path = $file_info->getRealPath();
                    $relative_path = ltrim(str_replace(realpath($dir), '', $real_path), DIRECTORY_SEPARATOR);
                    $files[] = str_replace('\\', '/', $relative_path); // Normalize slashes to Unix format.
                }
            }
        } catch (\Exception $e) {
            Babel::log('Recursive scan of raw/ failed: ' . $e->getMessage(), 'error');
        }

        // Sort files alphabetically.
        sort($files);

        return $files;
    }

    /**
     * Ingest a specific raw file, preserving its subdirectory structure.
     * Converts it to .md and moves the raw file to processed/
     * If the file is corrupt or unextractable, it is safely moved to failed/
     *
     * @param string $relative_path File path relative to raw/ folder.
     * @return string|(\WP_Error) Path to the converted .md file or WP_Error on failure.
     */
    public function ingest_file($relative_path)
    {
        // Normalize slashes for consistency.
        $relative_path = str_replace('\\', '/', $relative_path);
        $raw_path      = $this->get_raw_dir() . '/' . $relative_path;

        if (! file_exists($raw_path) || ! is_readable($raw_path)) {
            return new \WP_Error('file_not_found', sprintf(__('File %s does not exist or is not readable.', Babel::TEXT_DOMAIN), $relative_path));
        }

        $ext = pathinfo($raw_path, PATHINFO_EXTENSION);
        $converter_found = null;

        foreach ($this->converters as $converter) {
            if ($converter->supports($ext)) {
                $converter_found = $converter;
                break;
            }
        }

        if (! $converter_found) {
            return new \WP_Error('unsupported_format', sprintf(__('No converter available for .%s extension.', Babel::TEXT_DOMAIN), $ext));
        }

        // 1. Convert to Markdown.
        Babel::log('Starting conversion of raw file: ' . $relative_path, 'info');
        $markdown_content = $converter_found->convert($raw_path);

        // Force valid UTF-8 encoding to prevent downstream regex, chunking, or database failures.
        $markdown_content = mb_convert_encoding($markdown_content, 'UTF-8', 'UTF-8');

        // 2. Handle empty content or conversion failures (scanned PDFs, corrupt files, empty docs)
        if (empty($markdown_content)) {
            Babel::log('Extraction failed (corrupt or image-only). Safely archiving file in failed/ folder: ' . $relative_path, 'warning');

            // Archive in failed/ preserving structure
            $sub_dir          = dirname($relative_path);
            $target_failed_dir = $this->get_failed_dir();

            if ('.' !== $sub_dir && '' !== $sub_dir) {
                $target_failed_dir .= '/' . $sub_dir;
                wp_mkdir_p($target_failed_dir);
            }

            $failed_path = $target_failed_dir . '/' . basename($relative_path);

            if (file_exists($failed_path)) {
                @unlink($failed_path);
            }

            $moved = rename($raw_path, $failed_path);

            if (! $moved) {
                Babel::log('Failed to move corrupt file to failed: ' . $relative_path, 'error');
            } else {
                @chmod($failed_path, 0664);
                // Clean up empty folders in raw/
                $this->clean_empty_parent_directories($this->get_raw_dir() . '/' . $sub_dir);
            }

            return new \WP_Error(
                'conversion_failed_archived',
                sprintf(__('Failed to extract text or convert %s (possibly a scanned image PDF, empty, or corrupt). File was safely moved to the failed/ folder.', Babel::TEXT_DOMAIN), $relative_path)
            );
        }

        // 3. Prepare target directories and paths for successful conversions.
        $sub_dir        = dirname($relative_path);
        $base_name      = pathinfo($relative_path, PATHINFO_FILENAME);
        $converted_name = $base_name . '.md';

        $target_converted_dir = $this->get_converted_dir();
        $target_processed_dir = $this->get_processed_dir();

        if ('.' !== $sub_dir && '' !== $sub_dir) {
            $target_converted_dir .= '/' . $sub_dir;
            $target_processed_dir .= '/' . $sub_dir;

            // Ensure subdirectories exist.
            wp_mkdir_p($target_converted_dir);
            wp_mkdir_p($target_processed_dir);
        }

        $converted_path = $target_converted_dir . '/' . $converted_name;
        $processed_path = $target_processed_dir . '/' . basename($relative_path);

        // 3b. Wrap with unified YAML Front Matter headers.
        $file_id       = md5($relative_path);
        $file_date     = date('Y-m-d H:i:s', filemtime($raw_path));
        $yaml_headers  = sprintf(
            "---\nid: \"%s\"\ntitle: \"%s\"\ntype: \"%s\"\nstatus: \"publish\"\nauthor: \"System\"\ndate: \"%s\"\nurl: \"\"\nsource: \"file\"\npath: \"%s\"\n---\n# %s\n\n",
            $file_id,
            str_replace('"', '\"', $base_name),
            $ext,
            $file_date,
            $relative_path,
            $base_name
        );
        $final_markdown = $yaml_headers . $markdown_content;

        // 4. Save .md file.
        $saved = file_put_contents($converted_path, $final_markdown);

        if (false === $saved) {
            return new \WP_Error('write_failed', sprintf(__('Could not save converted file for %s.', Babel::TEXT_DOMAIN), $relative_path));
        }

        // Set proper permissions for the converted file.
        @chmod($converted_path, 0664);

        Babel::log('Successfully converted and saved: ' . $relative_path . ' -> ' . $converted_name, 'success');

        // Index the file vectorially.
        $index_manager = Babel::get(IndexManager::class);
        $indexed       = $index_manager->index_file($converted_path);

        if (is_wp_error($indexed)) {
            Babel::log("Converted file saved but indexing failed for: {$relative_path}. Error: " . $indexed->get_error_message(), 'error');
        }

        // 5. Move the raw file to processed/ to prevent duplicate scanning.
        if (file_exists($processed_path)) {
            @unlink($processed_path);
        }

        $moved = rename($raw_path, $processed_path);

        if (! $moved) {
            Babel::log('Failed to move raw file to processed: ' . $relative_path, 'error');
        } else {
            @chmod($processed_path, 0664);

            // Clean up empty parent directories in raw/ if any.
            $this->clean_empty_parent_directories($this->get_raw_dir() . '/' . $sub_dir);
        }

        return $converted_path;
    }

    /**
     * Clean up empty directories recursively up to the raw/ base limit.
     *
     * @param string $dir The directory to inspect and clean.
     */
    private function clean_empty_parent_directories($dir)
    {
        $raw_dir = rtrim($this->get_raw_dir(), '/');
        $dir     = rtrim($dir, '/');

        // Don't delete the raw root directory or anything outside.
        if ($dir === $raw_dir || strlen($dir) <= strlen($raw_dir)) {
            return;
        }

        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
            if (empty($files)) {
                if (@rmdir($dir)) {
                    $this->clean_empty_parent_directories(dirname($dir));
                }
            }
        }
    }

    /**
     * Ingest all pending files recursively in the raw/ directory.
     *
     * @return array Ingestion stats (success counts, failed counts/messages).
     */
    public function ingest_all_pending()
    {
        $pending_files = $this->scan_raw_dir();
        $results = [
            'success' => [],
            'failed'  => [],
        ];

        if (empty($pending_files)) {
            return $results;
        }

        foreach ($pending_files as $file) {
            $ingest_result = $this->ingest_file($file);

            if (is_wp_error($ingest_result)) {
                $results['failed'][] = [
                    'file'    => $file,
                    'message' => $ingest_result->get_error_message(),
                ];
                Babel::log('Ingestion failed for file: ' . $file . '. Error: ' . $ingest_result->get_error_message(), 'error');
            } else {
                $results['success'][] = [
                    'file'           => $file,
                    'converted_path' => $ingest_result,
                ];
            }
        }

        return $results;
    }
}
