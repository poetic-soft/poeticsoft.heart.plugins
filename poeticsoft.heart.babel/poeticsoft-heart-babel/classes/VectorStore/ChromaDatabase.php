<?php

namespace Poeticsoft\Heart\Babel\VectorStore;

use GuzzleHttp\Client;
use Poeticsoft\Heart\Babel\Babel;

/**
 * ChromaDB Vector Database Connector (API v2).
 * Implements VectorDatabaseInterface using Guzzle to communicate with local ChromaDB server (modern v0.6+).
 */
class ChromaDatabase implements VectorDatabaseInterface
{
    /**
     * HTTP Client.
     *
     * @var Client
     */
    private $client;

    /**
     * ChromaDB API URL.
     *
     * @var string
     */
    private $api_url;

    /**
     * Base path for v2 API.
     *
     * @var string
     */
    private $base_path = '/api/v2/tenants/default_tenant/databases/default_database';

    /**
     * Cached collection IDs.
     *
     * @var array
     */
    private static $collection_ids = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->api_url = get_option(Babel::PREFIX . 'chromadb_url', 'http://127.0.0.1:8000');

        $this->client  = new Client([
            'base_uri' => rtrim($this->api_url, '/'),
            'timeout'  => 15.0,
        ]);
    }

    /**
     * Verify connection with ChromaDB.
     */
    public function ping(): bool
    {
        try {
            $response = $this->client->get($this->base_path . '/collections');
            return 200 === $response->getStatusCode();
        } catch (\Exception $e) {
            Babel::log('ChromaDB connection check failed: ' . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Fetch or Create collection ID recursively.
     */
    private function get_collection_id(string $name): string|false
    {
        if (isset(self::$collection_ids[$name])) {
            return self::$collection_ids[$name];
        }

        try {
            // 1. Attempt to fetch collection details.
            $response = $this->client->get($this->base_path . '/collections/' . rawurlencode($name));
            $data     = json_decode($response->getBody()->getContents(), true);

            if (! empty($data['id'])) {
                self::$collection_ids[$name] = $data['id'];
                return $data['id'];
            }
        } catch (\Exception $e) {
            // If 404/error, proceed to creation.
        }

        // 2. Create the collection if it doesn't exist.
        try {
            $response = $this->client->post($this->base_path . '/collections', [
                'json' => [
                    'name'          => $name,
                    'get_or_create' => true,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (! empty($data['id'])) {
                self::$collection_ids[$name] = $data['id'];
                return $data['id'];
            }
        } catch (\Exception $e) {
            Babel::log("Failed to create collection '{$name}' in ChromaDB: " . $e->getMessage(), 'error');
        }

        return false;
    }

    /**
     * Create or ensure that a vector collection exists.
     */
    public function create_collection(string $name): bool
    {
        return false !== $this->get_collection_id($name);
    }

    /**
     * Delete a vector collection entirely.
     */
    public function delete_collection(string $name): bool
    {
        try {
            $response = $this->client->delete($this->base_path . '/collections/' . rawurlencode($name));
            unset(self::$collection_ids[$name]);
            return 200 === $response->getStatusCode();
        } catch (\Exception $e) {
            Babel::log("Failed to delete collection '{$name}' in ChromaDB: " . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Add a batch of documents, their embeddings, and metadata to a collection.
     */
    public function add_documents(
        string $collection,
        array $ids,
        array $embeddings,
        array $documents,
        array $metadatas
    ): bool {
        $collection_id = $this->get_collection_id($collection);

        if (! $collection_id) {
            return false;
        }

        // Sanitize documents to prevent json_encode failures on malformed UTF-8 sequences.
        $documents = array_map(function ($doc) {
            return mb_convert_encoding($doc, 'UTF-8', 'UTF-8');
        }, $documents);

        // Sanitize metadatas values to prevent json_encode failures on malformed UTF-8 sequences.
        $metadatas = array_map(function ($meta) {
            return array_map(function ($value) {
                return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }, $meta);
        }, $metadatas);

        try {
            $response = $this->client->post($this->base_path . "/collections/{$collection_id}/add", [
                'json' => [
                    'ids'        => $ids,
                    'embeddings' => $embeddings,
                    'metadatas'  => $metadatas,
                    'documents'  => $documents,
                ],
            ]);

            return 201 === $response->getStatusCode() || 200 === $response->getStatusCode();
        } catch (\Exception $e) {
            Babel::log("Failed to add documents to collection '{$collection}': " . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Delete documents/chunks matching specific metadata filters (idempotence).
     */
    public function delete_documents_by_metadata(string $collection, array $where): bool
    {
        $collection_id = $this->get_collection_id($collection);

        if (! $collection_id || empty($where)) {
            return false;
        }

        try {
            $response = $this->client->post($this->base_path . "/collections/{$collection_id}/delete", [
                'json' => [
                    'where' => $where,
                ],
            ]);

            return 200 === $response->getStatusCode();
        } catch (\Exception $e) {
            Babel::log("Failed to delete documents by metadata in collection '{$collection}': " . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Query the collection for similarity search.
     */
    public function query_collection(
        string $collection,
        array $query_embedding,
        int $limit = 5,
        array $where = []
    ): array {
        $collection_id = $this->get_collection_id($collection);

        if (! $collection_id) {
            return [];
        }

        try {
            $payload = [
                'query_embeddings' => [$query_embedding],
                'n_results'        => $limit,
            ];

            if (! empty($where)) {
                $payload['where'] = $where;
            }

            $response = $this->client->post($this->base_path . "/collections/{$collection_id}/query", [
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $results = [];

            if (! empty($data['ids'][0])) {
                foreach ($data['ids'][0] as $index => $id) {
                    $results[] = [
                        'id'       => $id,
                        'document' => $data['documents'][0][$index] ?? '',
                        'distance' => $data['distances'][0][$index] ?? 0.0,
                        'metadata' => $data['metadatas'][0][$index] ?? [],
                    ];
                }
            }

            return $results;
        } catch (\Exception $e) {
            Babel::log("Failed to query collection '{$collection}': " . $e->getMessage(), 'error');
            return [];
        }
    }

    /**
     * Get the number of items/embeddings currently stored inside a collection.
     */
    public function get_collection_count(string $collection): int
    {
        $collection_id = $this->get_collection_id($collection);

        if (! $collection_id) {
            return 0;
        }

        try {
            $response = $this->client->get($this->base_path . "/collections/{$collection_id}/count");
            $body     = $response->getBody()->getContents();
            $data     = json_decode($body, true);

            if (is_array($data) && isset($data['count'])) {
                return intval($data['count']);
            }

            if (is_numeric($body)) {
                return intval($body);
            }

            return is_int($data) ? $data : 0;
        } catch (\Exception $e) {
            Babel::log("Failed to count collection '{$collection}': " . $e->getMessage(), 'error');
            return 0;
        }
    }
}
