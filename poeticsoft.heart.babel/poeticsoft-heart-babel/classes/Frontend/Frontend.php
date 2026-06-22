<?php

namespace Poeticsoft\Heart\Babel\Frontend;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Frontend\FrontendAssets;

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
        Babel::get(FrontendAssets::class)->init();

        // Add shortcodes, filters for content, etc.
        add_shortcode(Babel::PLUGIN_SLUG . '_info', [$this, 'render_shortcode']);
    }

    /**
     * Example shortcode.
     */
    public function render_shortcode()
    {
        return sprintf(
            '<div class="%sfrontend">%s is active.</div>',
            esc_attr(Babel::PREFIX),
            esc_html(Babel::PLUGIN_NAME)
        );
    }
}
