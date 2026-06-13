<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

class Assets
{
    public function init()
    {
        add_action(
            'wp_enqueue_scripts',
            [$this, 'enqueue_scripts']
        );
    }

    public function enqueue_scripts()
    {

        wp_register_script(
            Campus::PLUGIN_SLUG . '-api-front',
            false,
            [],
            null,
            true
        );

        wp_enqueue_script(Campus::PLUGIN_SLUG . '-api-front');

        $data_json = json_encode([
          'nonce' => wp_create_nonce('wp_rest')
        ]);
        $inline_js = "var " . Campus::PREFIX . "api = {$data_json};";
        wp_add_inline_script(
            Campus::PLUGIN_SLUG . '-api-front',
            $inline_js,
            'after'
        );

        wp_enqueue_style('dashicons');
    }
}
