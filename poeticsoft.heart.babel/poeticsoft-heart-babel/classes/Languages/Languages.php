<?php

namespace Poeticsoft\Heart\Babel\Languages;

use Poeticsoft\Heart\Babel\Babel;

/**
 * Languages Orchestrator.
 * Handles internationalization and text domain loading.
 */
class Languages
{
    /**
     * Initialize internationalization.
     */
    public function init()
    {
        add_action('init', [$this, 'load_textdomain']);
    }

    /**
     * Load the plugin text domain.
     */
    public function load_textdomain()
    {
        load_plugin_textdomain(
            $this->get_domain(),
            false,
            Babel::path('languages')
        );
    }

    /**
     * Get the text domain dynamically from Babel.
     *
     * @return string
     */
    public function get_domain()
    {
        return Babel::TEXT_DOMAIN;
    }
}
