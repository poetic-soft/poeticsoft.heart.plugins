<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Campus;

/**
 * Frontend Assets Handler.
 * Conditional loading for public assets.
 */
class Assets
{

    /**
     * Initialize hooks.
     */
    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Enqueue scripts based on frontend conditions.
     */
    public function enqueue_scripts()
    {
        // Global frontend assets.
        wp_register_style(
            Campus::PREFIX . 'main',
            Campus::url('assets/front/style.css'),
            [],
            Campus::VERSION
        );

        // Conditional: Only on specific pages or post types.
        if (is_singular()) {
            wp_enqueue_style(Campus::PREFIX . 'main');
        }

        // Example: Only on a specific page slug.
        if (is_page('contact')) {
            wp_enqueue_script(
                Campus::PREFIX . 'contact',
                Campus::url('assets/front/contact.js'),
                [],
                Campus::VERSION,
                true
            );
        }
    }
}
