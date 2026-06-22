<?php

namespace Poeticsoft\Heart\Babel\Admin\Pages;

use Poeticsoft\Heart\Babel\Admin\AbstractPage;
use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\View\View;
use Poeticsoft\Heart\Babel\Embeddings\OllamaGenerator;
use Poeticsoft\Heart\Babel\VectorStore\ChromaDatabase;
use Poeticsoft\Heart\Babel\API;

/**
 * Dashboard Page.
 */
class DashboardPage extends AbstractPage
{
    /**
     * Test search results.
     *
     * @var array|null
     */
    protected $search_results = null;

    protected function define_page_props()
    {
        $this->slug       = Babel::PLUGIN_SLUG;
        $this->menu_title = __('Dashboard', Babel::TEXT_DOMAIN);
        $this->page_title = sprintf(__('%s Dashboard', Babel::TEXT_DOMAIN), Babel::PLUGIN_NAME);
    }

    /**
     * Handle custom actions securely.
     */
    protected function handle_action($action)
    {
        if ('refresh_status' === $action) {
            Babel::log('System status refreshed by user.', 'info');

            // Add a notice using the View engine.
            add_action('admin_notices', function () {
                Babel::get(View::class)->render('admin/notice', [
                    'type'    => 'success',
                    'message' => __('System status refreshed successfully.', Babel::TEXT_DOMAIN),
                ]);
            });
        } elseif ('test_search' === $action) {
            $query_text = sanitize_text_field($_POST['test_query'] ?? '');

            if (! empty($query_text)) {
                $results = API::get_context($query_text, ['limit' => 3]);

                if (is_wp_error($results)) {
                    add_action('admin_notices', function () use ($results) {
                        Babel::get(View::class)->render('admin/notice', [
                            'type'    => 'error',
                            'message' => sprintf(__('Search test failed: %s', Babel::TEXT_DOMAIN), $results->get_error_message()),
                        ]);
                    });
                } else {
                    $this->search_results = $results;
                    add_action('admin_notices', function () {
                        Babel::get(View::class)->render('admin/notice', [
                            'type'    => 'success',
                            'message' => __('Search test executed successfully.', Babel::TEXT_DOMAIN),
                        ]);
                    });
                }
            }
        } elseif ('reset_system' === $action) {
            Babel::log('System reset requested by user.', 'warning');

            // 1. Resolve Ingestors and Vector Store
            $ingestor      = Babel::get(\Poeticsoft\Heart\Babel\Ingestion\Ingestor::class);
            $post_ingestor = Babel::get(\Poeticsoft\Heart\Babel\Ingestion\PostIngestor::class);
            $chroma        = Babel::get(ChromaDatabase::class);
            $collection    = get_option(Babel::PREFIX . 'chromadb_collection', Babel::PLUGIN_SLUG);

            // 2. Clear all local files in uploads directories
            $this->delete_directory_contents($ingestor->get_raw_dir());
            $this->delete_directory_contents($ingestor->get_converted_dir());
            $this->delete_directory_contents($ingestor->get_processed_dir());
            $this->delete_directory_contents($ingestor->get_failed_dir());
            $this->delete_directory_contents($post_ingestor->get_converted_posts_dir());

            // 3. Clear ChromaDB collection
            if ($chroma->ping()) {
                $chroma->delete_collection($collection);
                $chroma->create_collection($collection);
                Babel::log("ChromaDB collection '{$collection}' has been recreated and emptied.", 'success');
            } else {
                Babel::log("Failed to clear ChromaDB collection because the server is offline.", 'error');
            }

            add_action('admin_notices', function () {
                Babel::get(View::class)->render('admin/notice', [
                    'type'    => 'success',
                    'message' => __('All files and vector databases have been reset and emptied successfully.', Babel::TEXT_DOMAIN),
                ]);
            });
        }
    }

    protected function render_content()
    {
        $chroma       = Babel::get(ChromaDatabase::class);
        $ollama       = Babel::get(OllamaGenerator::class);
        $collection   = get_option(Babel::PREFIX . 'chromadb_collection', Babel::PLUGIN_SLUG);
        $active_model = get_option(Babel::PREFIX . 'ollama_model', 'nomic-embed-text');

        $chroma_connected = $chroma->ping();
        $chroma_count     = $chroma_connected ? $chroma->get_collection_count($collection) : 0;

        $ollama_connected = $ollama->ping();
        $ollama_models    = $ollama_connected ? $ollama->get_installed_models() : [];

        $data = [
            'welcome_message'   => sprintf(__('Welcome to the main dashboard of %s.', Babel::TEXT_DOMAIN), Babel::PLUGIN_NAME),
            'chroma_connected'  => $chroma_connected,
            'chroma_collection' => $collection,
            'chroma_count'      => $chroma_count,
            'ollama_connected'  => $ollama_connected,
            'ollama_models'     => $ollama_models,
            'active_model'      => $active_model,
            'search_results'    => $this->search_results,
        ];

        $this->render_view('admin/dashboard', $data);
    }

    /**
     * Recursively delete all files and subfolders within a directory.
     *
     * @param string $dir_path
     */
    private function delete_directory_contents(string $dir_path)
    {
        if (! is_dir($dir_path)) {
            return;
        }

        $files = array_diff(scandir($dir_path), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir_path . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->delete_directory_contents($path);
                @rmdir($path);
            } else {
                @unlink($path);
            }
        }
    }
}
