<?php

namespace Poeticsoft\Heart\Babel\Indexing;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Chunking\ContentChunker;
use Poeticsoft\Heart\Babel\Embeddings\OllamaGenerator;
use Poeticsoft\Heart\Babel\VectorStore\ChromaDatabase;

/**
 * RAG Indexing Manager.
 * Orchestrates document chunking, batch vector embedding generation, and idempotent vector store syncing.
 */
class IndexManager
{
    /**
     * Target vector store collection name.
     *
     * @var string
     */
    private $collection;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->collection = get_option(Babel::PREFIX . 'chromadb_collection', Babel::PLUGIN_SLUG);
    }

    /**
     * Index a single Markdown file (containing YAML Front Matter) into the vector store.
     * Ensures idempotence by deleting any pre-existing chunks for the document's ID before insertion.
     *
     * @param string $file_path Absolute path to the Markdown file in converted/ directory.
     * @return bool|(\WP_Error) True on successful indexing, WP_Error on failure.
     */
    public function index_file(string $file_path)
    {
        if (! file_exists($file_path) || ! is_readable($file_path)) {
            return new \WP_Error('file_not_found', "Markdown file {$file_path} is missing or unreadable.");
        }

        $content = file_get_contents($file_path);
        if (false === $content) {
            return new \WP_Error('read_failed', "Could not read content from {$file_path}.");
        }

        // 1. Process and chunk the document using ContentChunker.
        $chunker = Babel::get(ContentChunker::class);
        $chunks  = $chunker->process_document($content);

        if (empty($chunks)) {
            // Empty document body, nothing to index.
            return true;
        }

        // Extract document-level metadata (same across all chunks of this file).
        $first_chunk_meta = $chunks[0]['metadata'];
        $doc_id           = $first_chunk_meta['id'] ?? null;

        if (empty($doc_id)) {
            return new \WP_Error('missing_id', "YAML Front Matter is missing the mandatory 'id' field.");
        }

        // 2. Resolve Active Embedding Generator and Vector Database (Interface Abstraction compliance).
        // Default: Ollama and ChromaDB.
        $generator = Babel::get(OllamaGenerator::class);
        $vector_db = Babel::get(ChromaDatabase::class);

        // 3. Ensure target collection exists in Vector Store.
        $vector_db->create_collection($this->collection);

        // 4. Achieve IDEMPOTENCY: Delete any pre-existing chunks for this document ID.
        Babel::log("Achieving idempotence: Clearing pre-existing chunks in '{$this->collection}' for Doc ID '{$doc_id}'...", 'info');
        $vector_db->delete_documents_by_metadata($this->collection, ['id' => $doc_id]);

        // 5. Extract texts and generate vector embeddings in a single Batch.
        Babel::log(sprintf('Generating batch embeddings for %d chunks of Doc ID %s...', count($chunks), $doc_id), 'info');
        $texts      = array_column($chunks, 'text');
        $embeddings = $generator->get_embeddings($texts);

        if (empty($embeddings) || count($embeddings) !== count($chunks)) {
            return new \WP_Error('embeddings_generation_failed', 'Embedding generator failed to return matching vectors for the chunks.');
        }

        // 6. Build parameters for the Vector Store.
        $ids       = [];
        $documents = [];
        $metadatas = [];

        foreach ($chunks as $index => $chunk) {
            $ids[]       = $doc_id . '_chunk_' . $index;
            $documents[] = $chunk['text'];
            // Standardize metadata fields (force string values in metadatas for ChromaDB compatibility).
            $metadatas[] = array_map('strval', $chunk['metadata']);
        }

        // 7. Write to Vector Database.
        Babel::log(sprintf("Writing %d vectors to collection '%s' in ChromaDB...", count($ids), $this->collection), 'info');
        $inserted = $vector_db->add_documents(
            $this->collection,
            $ids,
            $embeddings,
            $documents,
            $metadatas
        );

        if (! $inserted) {
            return new \WP_Error('vector_store_write_failed', 'Failed to index vectors inside the Vector Store.');
        }

        Babel::log("Successfully indexed Doc ID '{$doc_id}' with %d chunks in the Vector Store.", 'success');
        return true;
    }
}
