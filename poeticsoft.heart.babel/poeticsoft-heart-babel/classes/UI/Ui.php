<?php

namespace Poeticsoft\Heart\Babel\UI;

use Poeticsoft\Heart\Babel\Babel;

/**
 * Unified UI Assets Orchestrator.
 * Dynamically and intelligently routes and enqueues JS/CSS applications across Admin, Frontend, Blocks, and Metaboxes.
 */
class Ui
{

    /**
     * Initialize all asset enqueuing hooks globally.
     */
    public function init()
    {
        // 1. Admin Area Assets Route
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        // 2. Frontend Area Assets Route
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);

        // 3. Gutenberg Block Editor Assets Route
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_assets']);
    }

    /**
     * Route and enqueue assets for the WordPress Admin Area.
     *
     * @param string $hook_suffix The current admin page suffix.
     */
    public function enqueue_admin_assets($hook_suffix)
    {
        $screen = get_current_screen();

        if (! $screen) {
            return;
        }

        // A. Route for Babel's Advanced Ingestion Page (Babel Storage Inbox)
        if (strpos($screen->id, 'babel') !== false && strpos($screen->id, 'ingestion') !== false) {
            $version = (defined('WP_DEBUG') && WP_DEBUG) ? time() : Babel::VERSION;

            wp_enqueue_style(
                Babel::PREFIX . 'admin-ingestor-css',
                Babel::url('ui/admin/ingestor/main.css'),
                [],
                $version
            );

            wp_enqueue_script(
                Babel::PREFIX . 'admin-ingestor-js',
                Babel::url('ui/admin/ingestor/main.js'),
                ['wp-element'],
                $version,
                true
            );
        }

        // B. Route for post/page edit screen (Metaboxes/Editor Extensions)
        if ('post' === $screen->base) {
            // Enqueue metabox/sidebar React extensions here in the future
        }
    }

    /**
     * Route and enqueue assets for the Public Frontend Area.
     */
    public function enqueue_frontend_assets()
    {
        // Enqueue public search/chat interfaces here in the future (e.g. Chat Widget, Semantic Search bar)
    }

    /**
     * Route and enqueue assets for the Gutenberg Block Editor.
     */
    public function enqueue_block_assets()
    {
        // Enqueue core custom blocks styles/scripts (e.g. Voice Listener, Voice Speaker, RAG Search blocks)
    }
}
