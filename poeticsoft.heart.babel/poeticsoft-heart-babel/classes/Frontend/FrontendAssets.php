<?php

namespace Poeticsoft\Heart\Babel\Frontend;

use Poeticsoft\Heart\Babel\Babel;

/**
 * Frontend Assets Handler.
 */
class FrontendAssets
{
    /**
     * Initialize hooks.
     */
    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Enqueue frontend assets conditionally.
     */
    public function enqueue_scripts()
    {
        global $post;

        if (is_singular() && isset($post->post_content) && has_shortcode($post->post_content, Babel::PLUGIN_SLUG . '_info')) {
            wp_enqueue_style(
                Babel::PREFIX . 'front-css',
                Babel::url('assets/front/front-style.css'),
                [],
                Babel::VERSION
            );
        }
    }
}
