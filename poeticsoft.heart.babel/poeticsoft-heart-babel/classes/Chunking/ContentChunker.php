<?php

namespace Poeticsoft\Heart\Babel\Chunking;

use Poeticsoft\Heart\Babel\Babel;

/**
 * Content Chunker Utility.
 * Extracts YAML Front Matter and splits Markdown content into optimal semantic chunks with overlap.
 */
class ContentChunker
{
    /**
     * Parse standard YAML Front Matter from the top of a Markdown document.
     * Returns extracted metadata array and the stripped clean Markdown body.
     *
     * @param string $content Raw Markdown content with potential YAML header.
     * @return array Array containing 'metadata' (assoc array) and 'body' (string).
     */
    public function parse_yaml_metadata(string $content): array
    {
        $metadata = [];
        $body     = $content;

        // Normalize line breaks to Unix LF.
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        // Match starting --- blocks.
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
            $yaml_block = $matches[1];
            $body       = $matches[2];

            // Parse YAML key-value pairs line by line.
            $lines = explode("\n", $yaml_block);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }

                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $key   = trim($parts[0]);
                    $value = trim($parts[1]);

                    // Strip surrounding quotes.
                    $value = trim($value, '"\'');
                    // Unescape double quotes.
                    $value = str_replace('\"', '"', $value);

                    $metadata[$key] = $value;
                }
            }
        }

        return [
            'metadata' => $metadata,
            'body'     => $body,
        ];
    }

    /**
     * Split text into semantic chunks of roughly $chunk_size with $chunk_overlap.
     * Attempts to split recursively at paragraphs, lines, sentences, or word boundaries.
     *
     * @param string $text          The raw text body.
     * @param int    $chunk_size    Target chunk size in characters.
     * @param int    $chunk_overlap Overlap size in characters.
     * @return array Array of plain text chunks.
     */
    public function chunk_text(string $text, int $chunk_size = 1000, int $chunk_overlap = 200): array
    {
        if (empty($text)) {
            return [];
        }

        // Sane constraints to prevent overlapping loops.
        if ($chunk_overlap >= $chunk_size) {
            $chunk_overlap = (int) ($chunk_size * 0.2);
        }

        $chunks      = [];
        $text_length = strlen($text);
        $start       = 0;

        while ($start < $text_length) {
            // If remaining content fits, grab it and finish.
            if ($start + $chunk_size >= $text_length) {
                $chunks[] = trim(substr($text, $start));
                break;
            }

            $end = $start + $chunk_size;

            // 1. Look back for Paragraph boundaries (\n\n) near the target size.
            $split_point = $this->find_split_point($text, $start, $end, "\n\n", (int) ($chunk_size * 0.35));

            // 2. Look back for Line breaks (\n).
            if (false === $split_point) {
                $split_point = $this->find_split_point($text, $start, $end, "\n", (int) ($chunk_size * 0.25));
            }

            // 3. Look back for Sentence endings (. ).
            if (false === $split_point) {
                $split_point = $this->find_split_point($text, $start, $end, ". ", (int) ($chunk_size * 0.20));
            }

            // 4. Look back for Word spaces ( ).
            if (false === $split_point) {
                $split_point = $this->find_split_point($text, $start, $end, " ", (int) ($chunk_size * 0.15));
            }

            // Fallback: If no boundary found, split strictly at target size.
            if (false === $split_point) {
                $split_point = $end;
            }

            $chunks[] = trim(substr($text, $start, $split_point - $start));

            // Advance the sliding window with overlap.
            $start = $split_point - $chunk_overlap;

            // Safety check to guarantee loop progression and avoid infinite stalls.
            if ($start >= $split_point) {
                $start = $split_point;
            }
        }

        return array_filter($chunks);
    }

    /**
     * Locate a boundary string within a specific lookback search range from the end limit.
     */
    private function find_split_point(string $text, int $start, int $end, string $separator, int $search_range): int|false
    {
        $search_start = max($start, $end - $search_range);

        // Get the string segment.
        $segment = substr($text, $start, $end - $start);
        $pos     = strrpos($segment, $separator);

        if (false !== $pos && ($start + $pos) >= $search_start) {
            return $start + $pos + strlen($separator);
        }

        return false;
    }

    /**
     * Process a Markdown document, extracting YAML metadata and splitting it into chunk structures.
     *
     * @param string $content       Full raw Markdown content.
     * @param int    $chunk_size    Target chunk size in characters.
     * @param int    $chunk_overlap Overlap size in characters.
     * @return array List of structured chunks: [['text' => '...', 'metadata' => [...] ], ...].
     */
    public function process_document(string $content, int $chunk_size = 1000, int $chunk_overlap = 200): array
    {
        // 1. Extract metadata.
        $parsed = $this->parse_yaml_metadata($content);
        $metadata = $parsed['metadata'];
        $body     = $parsed['body'];

        // 2. Fragment body text.
        $text_chunks = $this->chunk_text($body, $chunk_size, $chunk_overlap);
        $structured  = [];
        $total_chunks = count($text_chunks);

        // 3. Construct structured chunks with merged metadata.
        foreach ($text_chunks as $index => $chunk_text) {
            $chunk_metadata = $metadata;
            $chunk_metadata['chunk_index']  = $index;
            $chunk_metadata['total_chunks'] = $total_chunks;

            $structured[] = [
                'text'     => $chunk_text,
                'metadata' => $chunk_metadata,
            ];
        }

        return $structured;
    }
}
