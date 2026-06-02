<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Frontend\Assets;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Blocks\PostContent;

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
        
        // Ensure Access validation hooks are active.
        Campus::get(Access::class);
        
        // Initialize Frontend Assets.
        Campus::get(Assets::class)->init();
        
        // Campus Post Content Manager.
        Campus::get(PostContent::class);
        
        // On debug disable Speculation Rules
        remove_action('wp_head', 'wp_enqueue_speculation_rules', 20);
    }
}
