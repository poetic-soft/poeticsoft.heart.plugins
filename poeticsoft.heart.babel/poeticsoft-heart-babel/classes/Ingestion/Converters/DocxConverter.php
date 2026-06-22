<?php

namespace Poeticsoft\Heart\Babel\Ingestion\Converters;

use Poeticsoft\Heart\Babel\Babel;

/**
 * DOCX File Converter using PHP ZipArchive and SimpleXMLElement.
 */
class DocxConverter implements ConverterInterface
{
    /**
     * Check if this converter supports the given file extension.
     */
    public function supports(string $extension): bool
    {
        return 'docx' === strtolower($extension);
    }

    /**
     * Read DOCX and extract plain text.
     */
    public function convert(string $file_path): string
    {
        if (! file_exists($file_path) || ! is_readable($file_path) || ! class_exists('\ZipArchive')) {
            return '';
        }

        $zip = new \ZipArchive();

        if (true !== $zip->open($file_path)) {
            return '';
        }

        // In DOCX, the main text content is stored in word/document.xml.
        $xml_index = $zip->locateName('word/document.xml');
        if (false === $xml_index) {
            $zip->close();
            return '';
        }

        $xml_content = $zip->getFromIndex($xml_index);
        $zip->close();

        if (empty($xml_content)) {
            return '';
        }

        try {
            // Use SimpleXMLElement to parse the XML.
            // We register the "w" namespace because Word XML utilizes it extensively.
            $xml = new \SimpleXMLElement($xml_content);
            $xml->registerXPathNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

            // Extract all paragraphs (<w:p>).
            $paragraphs = $xml->xpath('//w:p');
            $lines = [];

            foreach ($paragraphs as $p) {
                // Extract all text nodes (<w:t>) inside this paragraph.
                $texts = $p->xpath('.//w:t');
                $p_text = '';
                foreach ($texts as $t) {
                    $p_text .= (string) $t;
                }
                if (! empty($p_text)) {
                    $lines[] = $p_text;
                }
            }

            // Join paragraphs with double line breaks.
            $text = implode("\n\n", $lines);

            return trim($text);
        } catch (\Exception $e) {
            if (class_exists(Babel::class)) {
                Babel::log('DOCX parsing failed for ' . basename($file_path) . ': ' . $e->getMessage(), 'error');
            }
            return '';
        }
    }
}
