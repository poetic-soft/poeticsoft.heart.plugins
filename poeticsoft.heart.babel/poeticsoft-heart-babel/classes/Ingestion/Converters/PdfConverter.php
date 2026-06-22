<?php

namespace Poeticsoft\Heart\Babel\Ingestion\Converters;

use Smalot\PdfParser\Parser;
use Poeticsoft\Heart\Babel\Babel;

/**
 * PDF File Converter using Smalot PDF Parser.
 */
class PdfConverter implements ConverterInterface
{
    /**
     * Check if this converter supports the given file extension.
     */
    public function supports(string $extension): bool
    {
        return 'pdf' === strtolower($extension);
    }

    /**
     * Parse PDF file and extract plain text.
     */
    public function convert(string $file_path): string
    {
        if (! file_exists($file_path) || ! is_readable($file_path)) {
            return '';
        }

        try {
            $parser = new Parser();
            $pdf    = $parser->parseFile($file_path);
            $text   = $pdf->getText();

            if (empty($text)) {
                return '';
            }

            // Normalize line endings and whitespace.
            $text = str_replace(["\r\n", "\r"], "\n", $text);

            // Clean up consecutive empty lines (more than 2 consecutive LFs become 2 LFs).
            $text = preg_replace("/\n{3,}/", "\n\n", $text);

            // Reconstruct paragraphs: Merge single newlines inside paragraphs (fixes column layout wraps).
            $paragraphs               = explode("\n\n", $text);
            $reconstructed_paragraphs = [];

            foreach ($paragraphs as $paragraph) {
                $paragraph = trim($paragraph);
                if (empty($paragraph)) {
                    continue;
                }

                // Handle hyphens at the end of a line (e.g., "en- \n cuentre" -> "encuentre")
                $paragraph = preg_replace('/-\s*\n\s*/u', '', $paragraph);

                // Replace single newlines inside the paragraph with a single space.
                $paragraph = preg_replace('/\s*\n\s*/u', ' ', $paragraph);

                // Clean up consecutive multiple spaces.
                $paragraph = preg_replace('/\s+/u', ' ', $paragraph);

                $reconstructed_paragraphs[] = $paragraph;
            }

            $text = implode("\n\n", $reconstructed_paragraphs);

            $trimmed_text = trim($text);
            if (empty($trimmed_text)) {
                return '';
            }

            // HEURISTIC: Detect scanned, blank, or poor-quality text extractions.
            // Scanned image-only PDFs or corrupt encodings often return empty pages
            // with just control chars, page numbers, or single characters.
            // We strip non-alphabetic characters (preserving Spanish letters) to count letters.
            $letters_only = preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚñÑ]/u', '', $trimmed_text);
            $letter_count = mb_strlen($letters_only, 'UTF-8');
            $total_length = mb_strlen($trimmed_text, 'UTF-8');
            $ratio        = $total_length > 0 ? ($letter_count / $total_length) : 0;

            // REJECTION CONDITIONS:
            // 1. Extreme lack of text: less than 100 total alphabetic characters.
            // 2. High noise/scanned ratio: If the document is of substantial length (> 1000 chars),
            //    but letters constitute less than 15% of the content. This accurately identifies
            //    scanned PDFs containing mostly white space, form-feeds, and random font glyph noise.
            if ($letter_count < 100 || ($total_length > 1000 && $ratio < 0.15)) {
                if (class_exists(Babel::class)) {
                    $log_msg = sprintf(
                        'PDF extraction rejected (Letters: %d, Length: %d, Ratio: %.2f%%). ' .
                        'Likely a scanned image-only PDF or corrupt encoding: %s',
                        $letter_count,
                        $total_length,
                        $ratio * 100,
                        basename($file_path)
                    );
                    Babel::log($log_msg, 'warning');
                }
                return '';
            }

            return $trimmed_text;
        } catch (\Exception $e) {
            if (class_exists(Babel::class)) {
                Babel::log('PDF parsing failed for ' . basename($file_path) . ': ' . $e->getMessage(), 'error');
            }
            return '';
        }
    }
}
