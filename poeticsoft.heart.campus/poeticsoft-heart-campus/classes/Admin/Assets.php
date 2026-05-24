<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;

/**
 * Admin Assets Handler.
 * Ensures assets are loaded only when and where they are needed.
 */
class Assets
{

    /**
     * Initialize hooks.
     */
    public function init()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Enqueue scripts conditionally.
     *
     * @param string $hook_suffix The current admin page suffix.
     */
    public function enqueue_scripts($hook_suffix)
    {
        $screen = get_current_screen();

        // Only load on our specific plugin page.
        if ('toplevel_page_' . Campus::PLUGIN_SLUG === $hook_suffix) {
            wp_enqueue_style(
                Campus::PREFIX . 'admin-css',
                Campus::url('assets/admin/admin-style.css'),
                [],
                Campus::VERSION
            );

            wp_enqueue_script(
                Campus::PREFIX . 'admin-js',
                Campus::url('assets/admin/admin-script.js'),
                ['jquery'],
                Campus::VERSION,
                true
            );
        }

        // Example: Only load on 'post' or 'page' editor.
        if ($screen && 'post' === $screen->base) {
            // Enqueue editor-specific assets here.
        }
    }
}
