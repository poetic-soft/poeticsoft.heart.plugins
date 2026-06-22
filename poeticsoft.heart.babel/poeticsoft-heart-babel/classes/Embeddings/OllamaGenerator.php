<?php

namespace Poeticsoft\Heart\Babel\Embeddings;

use GuzzleHttp\Client;
use Poeticsoft\Heart\Babel\Babel;

/**
 * Ollama Embeddings Generator.
 * Connects to local Ollama instance using Guzzle and generates text vectors in batch.
 */
class OllamaGenerator implements EmbeddingGeneratorInterface
{
    /**
     * HTTP Client.
     *
     * @var Client
     */
    private $client;

    /**
     * Ollama Base API URL.
     *
     * @var string
     */
    private $api_url;

    /**
     * Embeddings Model Name.
     *
     * @var string
     */
    private $model;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->api_url = get_option(Babel::PREFIX . 'ollama_url', 'http://127.0.0.1:11434');
        $this->model   = get_option(Babel::PREFIX . 'ollama_model', 'nomic-embed-text');

        $this->client  = new Client([
            'base_uri' => rtrim($this->api_url, '/'),
            'timeout'  => 120.0, // Increased default timeout for larger document processing
        ]);
    }

    /**
     * Generate a vector embedding for a single text block.
     */
    public function get_embedding(string $text): array
    {
        // For search queries with nomic models, prepend 'search_query: '
        if (str_contains(strtolower($this->model), 'nomic')) {
            $text = 'search_query: ' . $text;
        }

        $embeddings = $this->get_raw_embeddings([$text]);
        return $embeddings[0] ?? [];
    }

    /**
     * Generate vector embeddings in batch using Ollama's /api/embed endpoint.
     * Forces UTF-8 encoding on chunks and processes them in smaller sub-batches to prevent timeouts.
     */
    public function get_embeddings(array $texts): array
    {
        if (empty($texts)) {
            return [];
        }

        // For document chunks with nomic models, prepend 'search_document: '
        if (str_contains(strtolower($this->model), 'nomic')) {
            $texts = array_map(function ($text) {
                if (! str_starts_with($text, 'search_document: ')) {
                    return 'search_document: ' . $text;
                }
                return $text;
            }, $texts);
        }

        return $this->get_raw_embeddings($texts);
    }

    /**
     * Internal helper to send the actual request to Ollama API.
     */
    private function get_raw_embeddings(array $texts): array
    {
        // Force valid UTF-8 encoding on all text chunks to prevent json_encode failures.
        $cleaned_texts = array_map(function ($text) {
            return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }, $texts);

        // Process in smaller sub-batches of 50 chunks to prevent cURL timeouts
        // and out-of-memory errors on large files.
        $batch_size = 50;
        $all_embeddings = [];
        $chunks_batches = array_chunk($cleaned_texts, $batch_size);

        foreach ($chunks_batches as $batch_index => $batch) {
            try {
                $response = $this->client->post('/api/embed', [
                    'json' => [
                        'model' => $this->model,
                        'input' => $batch,
                    ],
                    'timeout' => 120.0,
                ]);

                $data = json_decode($response->getBody()->getContents(), true);

                if (! empty($data['embeddings']) && is_array($data['embeddings'])) {
                    $all_embeddings = array_merge($all_embeddings, $data['embeddings']);
                } else {
                    $err_msg = sprintf('Ollama embed failed for batch %d.', $batch_index);
                    Babel::log($err_msg, 'error');
                    return []; // Abort to keep indexing consistent
                }
            } catch (\Exception) {
                Babel::log(sprintf('Ollama batch %d connection failed.', $batch_index), 'error');
                return []; // Abort
            }
        }

        return $all_embeddings;
    }

    /**
     * Get model dimension (nomic-embed-text is 768).
     */
    public function get_dimension(): int
    {
        return 768;
    }

    /**
     * Verify connection with Ollama.
     */
    public function ping(): bool
    {
        try {
            $response = $this->client->get('/');
            return 200 === $response->getStatusCode();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get installed models on Ollama.
     */
    public function get_installed_models(): array
    {
        try {
            $response = $this->client->get('/api/tags');
            $data     = json_decode($response->getBody()->getContents(), true);

            if (! empty($data['models']) && is_array($data['models'])) {
                return array_map(function ($model) {
                    return $model['name'] ?? '';
                }, $data['models']);
            }

            return [];
        } catch (\Exception $e) {
            Babel::log('Failed to retrieve Ollama models: ' . $e->getMessage(), 'error');
            return [];
        }
    }
}
