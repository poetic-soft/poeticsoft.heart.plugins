<?php

namespace Poeticsoft\Heart\Babel\Admin\Pages;

use Poeticsoft\Heart\Babel\Admin\AbstractPage;
use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Ingestion\Ingestor;
use Poeticsoft\Heart\Babel\Ingestion\PostIngestor;
use Poeticsoft\Heart\Babel\View\View;

/**
 * Ingestion Page.
 * Handles both file/folder ingestion and WordPress content (posts/pages/CPTs) ingestion.
 */
class IngestionPage extends AbstractPage
{
    protected function define_page_props()
    {
        $this->slug       = Babel::PREFIX . 'ingestion';
        $this->menu_title = __('Ingestor', Babel::TEXT_DOMAIN);
        $this->page_title = __('Advanced Document & Content Ingestor', Babel::TEXT_DOMAIN);
    }

    /**
     * Handle custom actions (Buzón Ingestion & WordPress Sync).
     */
    protected function handle_action($action)
    {
        // 1. Process files from /uploads/babel/raw/ folder
        if ('process_ingestion' === $action) {
            $ingestor = Babel::get(Ingestor::class);
            $results  = $ingestor->ingest_all_pending();

            $success_count = count($results['success']);
            $failed_count  = count($results['failed']);

            add_action('admin_notices', function () use ($success_count, $failed_count, $results) {
                if ($success_count > 0) {
                    Babel::get(View::class)->render('admin/notice', [
                        'type'    => 'success',
                        'message' => sprintf(_n('Successfully processed %d document.', 'Successfully processed %d documents.', $success_count, Babel::TEXT_DOMAIN), $success_count),
                    ]);
                }

                if ($failed_count > 0) {
                    $error_messages = array_map(function ($item) {
                        return esc_html($item['file'] . ': ' . $item['message']);
                    }, $results['failed']);

                    Babel::get(View::class)->render('admin/notice', [
                        'type'    => 'error',
                        'message' => sprintf(__('Failed to process %d documents:<br>%s', Babel::TEXT_DOMAIN), $failed_count, implode('<br>', $error_messages)),
                    ]);
                }
            });
        }

        // 2. Process tagged WordPress content
        if ('sync_wp_posts' === $action) {
            $post_ingestor = Babel::get(PostIngestor::class);
            $results       = $post_ingestor->sync_all_tagged_posts();

            add_action('admin_notices', function () use ($results) {
                if ($results['success'] > 0) {
                    Babel::get(View::class)->render('admin/notice', [
                        'type'    => 'success',
                        'message' => sprintf(_n('Successfully synchronized %d WordPress post.', 'Successfully synchronized %d WordPress posts.', $results['success'], Babel::TEXT_DOMAIN), $results['success']),
                    ]);
                }

                if (! empty($results['failed'])) {
                    $error_messages = array_map(function ($item) {
                        return esc_html('Post ID ' . $item['id'] . ': ' . $item['message']);
                    }, $results['failed']);

                    Babel::get(View::class)->render('admin/notice', [
                        'type'    => 'error',
                        'message' => sprintf(__('Failed to synchronize %d WordPress posts:<br>%s', Babel::TEXT_DOMAIN), count($results['failed']), implode('<br>', $error_messages)),
                    ]);
                }

                if (0 === $results['success'] && empty($results['failed'])) {
                    Babel::get(View::class)->render('admin/notice', [
                        'type'    => 'info',
                        'message' => __('No WordPress posts found with the "babel" tag to synchronize.', Babel::TEXT_DOMAIN),
                    ]);
                }
            });
        }
    }

    protected function render_content()
    {
        $ingestor      = Babel::get(Ingestor::class);
        $post_ingestor = Babel::get(PostIngestor::class);

        // 1. Gather files pending in raw inbox
        $pending_files = $ingestor->scan_raw_dir();

        // 2. Count tagged WordPress posts pending synchronization or modified
        $tagged_posts_count = count($post_ingestor->get_pending_posts());

        $data = [
            // Files Ingestion
            'pending_files'      => $pending_files,
            'raw_dir_path'       => $ingestor->get_raw_dir(),
            'api_upload_url'     => get_rest_url(null, Babel::API_NAMESPACE . '/upload'),
            'nonce'              => wp_create_nonce('wp_rest'),

            // WordPress Content Ingestion
            'tagged_posts_count' => $tagged_posts_count,
            'converted_posts_dir' => $post_ingestor->get_converted_posts_dir(),
        ];

        $this->render_view('admin/ingestion', $data);
    }
}
