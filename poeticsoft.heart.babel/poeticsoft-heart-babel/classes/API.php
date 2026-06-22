<?php

namespace Poeticsoft\Heart\Babel;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Embeddings\OllamaGenerator;
use Poeticsoft\Heart\Babel\VectorStore\ChromaDatabase;

/**
 * Public API class for Poeticsoft Heart Babel.
 * Exposes clean, static methods for other plugins to query the sovereign RAG store.
 */
class API
{
    /**
     * Query the sovereign RAG database to retrieve relevant context.
     *
     * @param string $query_text Plain text search query (e.g. "Configurar proxy Nginx").
     * @param array  $args       {
     *     @type int   $limit    Number of results to return (default: 3).
     *     @type array $where    Metadata filter array (e.g. ['source' => 'wordpress', 'author' => 'Alberto']).
     * }
     * @return array|(\WP_Error) Array of results with text and metadata or WP_Error on failure.
     */
    public static function get_context(string $query_text, array $args = [])
    {
        $limit = $args['limit'] ?? 3;
        $where = $args['where'] ?? [];

        $generator  = Babel::get(OllamaGenerator::class);
        $vector_db  = Babel::get(ChromaDatabase::class);
        $collection = get_option(Babel::PREFIX . 'chromadb_collection', Babel::PLUGIN_SLUG);

        // 1. Vectorize the user's query.
        $embedding = $generator->get_embedding($query_text);
        if (empty($embedding)) {
            return new \WP_Error('embedding_failed', 'Could not generate vector embedding for search.');
        }

        // 2. Query ChromaDB v2 applying limits and metadata filters.
        $raw_results = $vector_db->query_collection($collection, $embedding, $limit, $where);

        return $raw_results;
    }
}
