<?php

namespace Poeticsoft\Heart\Babel\VectorStore;

/**
 * Interface VectorDatabaseInterface.
 * Standardizes how different vector databases (ChromaDB, pgvector, Pinecone) are queried and managed.
 */
interface VectorDatabaseInterface
{
    /**
     * Verify connection with the vector database server.
     *
     * @return bool True if connected and responsive, false otherwise.
     */
    public function ping(): bool;

    /**
     * Create or ensure that a vector collection exists.
     *
     * @param string $name Name of the collection.
     * @return bool True on success, false on failure.
     */
    public function create_collection(string $name): bool;

    /**
     * Delete a vector collection entirely.
     *
     * @param string $name Name of the collection.
     * @return bool True on success, false on failure.
     */
    public function delete_collection(string $name): bool;

    /**
     * Add a batch of documents, their embeddings, and metadata to a collection.
     *
     * @param string $collection Name of the collection.
     * @param array  $ids        Array of unique chunk IDs (e.g. ['post-125_chunk_0', ...]).
     * @param array  $embeddings Array of numerical vectors corresponding to each chunk.
     * @param array  $documents  Array of raw text chunks.
     * @param array  $metadatas  Array of metadata associative arrays for each chunk.
     * @return bool True on success, false on failure.
     */
    public function add_documents(
        string $collection,
        array $ids,
        array $embeddings,
        array $documents,
        array $metadatas
    ): bool;

    /**
     * Delete documents/chunks matching specific metadata filters.
     * Crucial to clear pre-existing chunks for an ID or file path before re-indexing (idempotence).
     *
     * @param string $collection Name of the collection.
     * @param array  $where      Metadata filter (e.g., ['id' => '125'] or ['path' => 'doc.pdf']).
     * @return bool True on success, false on failure.
     */
    public function delete_documents_by_metadata(string $collection, array $where): bool;

    /**
     * Query the collection for similarity search.
     *
     * @param string $collection      Name of the collection.
     * @param array  $query_embedding Numerical vector of the search query.
     * @param int    $limit           Maximum number of matching documents to retrieve.
     * @param array  $where           Optional metadata filters to narrow down the query.
     * @return array Array of matching chunks with their documents, IDs, distances, and metadatas.
     */
    public function query_collection(
        string $collection,
        array $query_embedding,
        int $limit = 5,
        array $where = []
    ): array;

    /**
     * Get the number of items/embeddings currently stored inside a collection.
     *
     * @param string $collection Name of the collection.
     * @return int Number of records.
     */
    public function get_collection_count(string $collection): int;
}
