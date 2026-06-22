<?php

namespace Poeticsoft\Heart\Babel\Ingestion;

/**
 * Utility class to convert HTML (classic editor or Gutenberg blocks) into clean Markdown.
 */
class HtmlToMarkdown
{
    /**
     * Convert HTML string into a clean Markdown string.
     *
     * @param string $html The raw HTML content.
     * @return string Converted markdown.
     */
    public static function convert($html)
    {
        if (empty($html)) {
            return '';
        }

        $md = $html;

        // 1. Strip Gutenberg block comments (e.g. <!-- wp:paragraph -->).
        $md = preg_replace('/<!--\s+\/?wp:[^>]*-->/', '', $md);

        // 2. Normalize line breaks.
        $md = str_replace(["\r\n", "\r"], "\n", $md);

        // 3. Convert headers <h1> to <h6>.
        for ($i = 1; $i <= 6; $i++) {
            $md = preg_replace_callback('/<h' . $i . '[^>]*>(.*?)<\/h' . $i . '>/i', function ($matches) use ($i) {
                $hashes = str_repeat('#', $i);
                return "\n\n" . $hashes . ' ' . trim(strip_tags($matches[1])) . "\n\n";
            }, $md);
        }

        // 4. Convert links <a href="url">Text</a> -> [Text](url).
        $md = preg_replace('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/i', '[$2]($1)', $md);

        // 5. Convert strong/bold formatting <strong>Text</strong> -> **Text**.
        $md = preg_replace('/<(strong|b)[^>]*>(.*?)<\/\1>/i', '**$2**', $md);

        // 6. Convert emphasis/italic <em>Text</em> -> *Text*.
        $md = preg_replace('/<(em|i)[^>]*>(.*?)<\/\1>/i', '*$2*', $md);

        // 7. Convert line breaks <br> and paragraph endings.
        $md = preg_replace('/<br\s*\/?>/i', "\n", $md);
        $md = preg_replace('/<p[^>]*>(.*?)<\/p>/i', "\n\n$1\n\n", $md);

        // 8. Convert lists: <li> inside <ul> or <ol>.
        $md = preg_replace('/<li[^>]*>(.*?)<\/li>/i', "- $1\n", $md);
        // Strip list containers.
        $md = preg_replace('/<\/?(ul|ol)[^>]*>/i', "\n", $md);

        // 9. Strip any remaining HTML tags safely and decode HTML entities.
        $md = html_entity_decode(strip_tags($md), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 10. Sane whitespace cleanup (limit consecutive line breaks to 2 max).
        $md = preg_replace("/\n{3,}/", "\n\n", $md);

        return trim($md);
    }
}
