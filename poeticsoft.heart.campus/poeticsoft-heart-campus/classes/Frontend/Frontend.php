<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Frontend\Assets;

/**
 * Frontend Controller.
 * Handles public-facing logic.
 */
class Frontend
{

    /**
     * Initialize frontend hooks.
     */
    public function init()
    {
        // Initialize Frontend Assets.
        Campus::get(Assets::class)->init();

        // Add shortcodes, filters for content, etc.
        add_shortcode(Campus::PLUGIN_SLUG . '_info', [$this, 'render_shortcode']);
    }

    /**
     * Example shortcode.
     */
    public function render_shortcode()
    {
        return sprintf(
            '<div class="%sfrontend">%s is active.</div>',
            esc_attr(Campus::PREFIX),
            esc_html(Campus::PLUGIN_NAME)
        );
    }
}
