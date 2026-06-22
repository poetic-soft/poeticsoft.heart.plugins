<?php

namespace Poeticsoft\Heart\Babel\Ingestion\Converters;

/**
 * Markdown and Text File Converter.
 */
class MarkdownConverter implements ConverterInterface
{
    /**
     * Check if this converter supports the given file extension.
     */
    public function supports(string $extension): bool
    {
        return in_array(strtolower($extension), ['md', 'txt'], true);
    }

    /**
     * Read and normalize plain text/markdown file content.
     */
    public function convert(string $file_path): string
    {
        if (! file_exists($file_path) || ! is_readable($file_path)) {
            return '';
        }

        $content = file_get_contents($file_path);

        if (false === $content) {
            return '';
        }

        // Convert to UTF-8 if it's not already.
        if (! mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content) ?: 'UTF-8');
        }

        // Normalize line endings to LF (\n).
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        return trim($content);
    }
}
