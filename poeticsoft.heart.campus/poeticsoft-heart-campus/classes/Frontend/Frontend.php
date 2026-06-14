<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Frontend\Assets;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Blocks\PostContent;

class Frontend
{
    public function init()
    {
        Campus::get(Access::class);
        Campus::get(Assets::class)->init();
        Campus::get(PostContent::class);
        remove_action('wp_head', 'wp_enqueue_speculation_rules', 20);
    }
}
