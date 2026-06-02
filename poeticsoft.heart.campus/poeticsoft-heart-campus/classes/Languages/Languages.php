<?php

namespace Poeticsoft\Heart\Languages;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

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
            Utils::path('languages')
        );
    }

    /**
     * Get the text domain dynamically from Campus.
     * 
     * @return string
     */
    public function get_domain()
    {
        return Campus::TEXT_DOMAIN;
    }
}
