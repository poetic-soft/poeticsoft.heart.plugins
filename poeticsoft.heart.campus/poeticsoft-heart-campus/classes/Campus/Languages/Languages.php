<?php

namespace Poeticsoft\Heart\Campus\Languages;

use Poeticsoft\Heart\Campus\Campus;
use Poeticsoft\Heart\Campus\Utils\Utils;

class Languages
{
    public function init()
    {
        add_action('init', [$this, 'load_textdomain']);
    }

    public function load_textdomain()
    {
        load_plugin_textdomain(
            $this->get_domain(),
            false,
            Utils::path('languages')
        );
    }

    public function get_domain()
    {
        return Campus::TEXT_DOMAIN;
    }
}
