<?php

namespace Poeticsoft\Heart\Babel\Rest\Endpoints;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Rest\AbstractEndpoint;
use Poeticsoft\Heart\Babel\Ingestion\Ingestor;

/**
 * REST Endpoint for uploading files and folders asynchronously.
 */
class UploadEndpoint extends AbstractEndpoint
{
    /**
     * Define routes for this section.
     */
    public function get_routes()
    {
        return [
            '/upload' => [
                'methods'  => 'POST',
                'callback' => 'handle_upload',
                'auth'     => self::AUTH_ADMIN,
            ],
            '/ingest-file' => [
                'methods'  => 'POST',
                'callback' => 'handle_ingest_file',
                'auth'     => self::AUTH_ADMIN,
            ],
            '/sync-posts' => [
                'methods'  => 'POST',
                'callback' => 'handle_sync_posts',
                'auth'     => self::AUTH_ADMIN,
            ],
        ];
    }

    /**
     * Handle file/folder upload securely.
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function handle_upload($request)
    {
        // Ensure files are present.
        if (empty($_FILES['file'])) {
            return $this->send_error('no_file_uploaded', 'No file was uploaded.', 400);
        }

        $file = $_FILES['file'];

        if (UPLOAD_ERR_OK !== $file['error']) {
            return $this->send_error('upload_error_code', 'Upload error code: ' . $file['error'], 400);
        }

        // Retrieve and sanitize relative path to preserve folder structure.
        $relative_path = $request->get_param('relative_path');
        if (empty($relative_path)) {
            $relative_path = basename($file['name']);
        } else {
            $relative_path = sanitize_text_field($relative_path);
            // Force strict security to strip any directory traversal payload.
            $relative_path = str_replace(['../', '..\\', '..'], '', $relative_path);
            $relative_path = trim($relative_path, '/');
        }

        if (empty($relative_path)) {
            return $this->send_error('invalid_relative_path', 'The provided relative path is invalid.', 400);
        }

        // Validate extension limit (we only parse md, txt, pdf, and docx).
        $ext = strtolower(pathinfo($relative_path, PATHINFO_EXTENSION));
        $allowed_extensions = ['md', 'txt', 'pdf', 'docx'];

        if (! in_array($ext, $allowed_extensions, true)) {
            return $this->send_error('unsupported_extension', sprintf('File extension .%s is not supported.', $ext), 400);
        }

        $ingestor = Babel::get(Ingestor::class);
        $target_raw_path = $ingestor->get_raw_dir() . '/' . $relative_path;

        // Ensure the exact directory structure exists recursively inside raw/
        $sub_dir = dirname($target_raw_path);
        if (! file_exists($sub_dir)) {
            wp_mkdir_p($sub_dir);
        }

        // Handle replacement if the file already exists.
        if (file_exists($target_raw_path)) {
            @unlink($target_raw_path);
        }

        // Move file from php temp folder to raw/ destination.
        $moved = move_uploaded_file($file['tmp_name'], $target_raw_path);

        if (! $moved) {
            return $this->send_error('move_failed', 'Could not save the uploaded file to target path.', 500);
        }

        // Set proper permissions for the file.
        @chmod($target_raw_path, 0664);

        return $this->send_success([
            'file'          => basename($relative_path),
            'relative_path' => $relative_path,
            'saved_path'    => $target_raw_path,
        ]);
    }

    /**
     * Handle file ingestion asynchronously on a single-file basis.
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function handle_ingest_file($request)
    {
        $relative_path = $request->get_param('file');

        if (empty($relative_path)) {
            return $this->send_error('missing_file_param', 'The "file" parameter is required.', 400);
        }

        $relative_path = sanitize_text_field($relative_path);
        $relative_path = str_replace(['../', '..\\', '..'], '', $relative_path);
        $relative_path = trim($relative_path, '/');

        $ingestor = Babel::get(Ingestor::class);
        $result   = $ingestor->ingest_file($relative_path);

        if (is_wp_error($result)) {
            return $this->send_error($result->get_error_code(), $result->get_error_message(), 500);
        }

        return $this->send_success([
            'file'           => basename($relative_path),
            'relative_path'  => $relative_path,
            'converted_path' => $result,
        ]);
    }

    /**
     * Handle WordPress content sync.
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function handle_sync_posts($request)
    {
        $post_ingestor = Babel::get(\Poeticsoft\Heart\Babel\Ingestion\PostIngestor::class);
        $results       = $post_ingestor->sync_all_tagged_posts();

        return $this->send_success($results);
    }
}
