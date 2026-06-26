<?php

namespace Poeticsoft\Heart\Campus\Admin;

use Poeticsoft\Heart\Campus\Campus;
use Poeticsoft\Heart\Campus\Admin\Meta;
use Poeticsoft\Heart\Campus\Admin\Menu;
use Poeticsoft\Heart\Campus\Admin\Pages;
use Poeticsoft\Heart\Campus\Admin\Assets;

class Admin
{
    public function init()
    {
        Campus::get(Menu::class)->init();
        Campus::get(Meta::class)->init();
        Campus::get(Pages::class)->init();
        Campus::get(Assets::class)->init();

        add_action(
            'current_screen',
            [$this, 'route_sub_controllers']
        );
    }

    public function route_sub_controllers()
    {
        $screen = get_current_screen();

        if (! $screen) {
            return;
        }

        if ($screen->post_type !== 'page') {
            return;
        }

        if (
            $screen->base === 'edit'
            ||
            $screen->base === 'post'
        ) {
        }
    }
}
