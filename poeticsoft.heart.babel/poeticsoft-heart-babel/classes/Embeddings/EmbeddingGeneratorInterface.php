<?php

namespace Poeticsoft\Heart\Babel\Embeddings;

/**
 * Interface EmbeddingGeneratorInterface.
 * Standardizes how different AI providers generate vector embeddings from text.
 */
interface EmbeddingGeneratorInterface
{
    /**
     * Generate a vector embedding for a single text block.
     *
     * @param string $text The text block to embed.
     * @return array The numerical vector representation (e.g., [0.123, -0.456, ...]).
     */
    public function get_embedding(string $text): array;

    /**
     * Generate vector embeddings in batch for multiple text blocks.
     * This leverages parallel API capability for much faster execution.
     *
     * @param array $texts List of text blocks.
     * @return array List of numerical vectors corresponding to each text block.
     */
    public function get_embeddings(array $texts): array;

    /**
     * Get the output vector dimension (e.g., 768 for nomic-embed-text, 1536 for OpenAI text-embedding-3-small).
     *
     * @return int Vector dimension size.
     */
    public function get_dimension(): int;
}
