<?php

namespace Poeticsoft\Heart\Campus\Frontend;

use Poeticsoft\Heart\Campus\Campus;
use Poeticsoft\Heart\Campus\Frontend\Assets;
use Poeticsoft\Heart\Campus\Validation\Access;
use Poeticsoft\Heart\Campus\Blocks\PostContent;

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
