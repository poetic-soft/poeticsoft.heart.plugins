<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

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
        add_action(
            'wp_enqueue_scripts', 
            [$this, 'enqueue_scripts']
        );
    }

    /**
     * Enqueue scripts based on frontend conditions.
     */
    public function enqueue_scripts()
    {
        // Global frontend assets.
        // wp_enqueue_style(
        //     Campus::PREFIX . 'main',
        //     Utils::url('assets/front/style.css'),
        //     [],
        //     filemtime(Utils::path('assets/front/style.css')),
        // );

        // // Example: Only on a specific page slug.
        // if (is_page('contact')) {
        //     wp_enqueue_script(
        //         Campus::PREFIX . 'contact',
        //         Utils::url('assets/front/contact.js'),
        //         [],
        //         filemtime(Utils::path('assets/front/contact.js')),
        //         true
        //     );
        // }
    }
}
