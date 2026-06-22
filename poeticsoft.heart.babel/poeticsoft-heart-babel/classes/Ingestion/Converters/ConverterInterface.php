<?php

namespace Poeticsoft\Heart\Babel\Ingestion\Converters;

/**
 * Interface ConverterInterface.
 * Standardizes file converters for document ingestion.
 */
interface ConverterInterface
{
    /**
     * Check if this converter supports the given file extension.
     *
     * @param string $extension File extension (e.g., 'pdf', 'docx', 'md').
     * @return bool
     */
    public function supports(string $extension): bool;

    /**
     * Convert a raw file into a clean Markdown formatted string.
     *
     * @param string $file_path Absolute path to the file.
     * @return string Markdown formatted content.
     */
    public function convert(string $file_path): string;
}
