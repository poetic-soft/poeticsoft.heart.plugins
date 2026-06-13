<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Admin\Meta;
use Poeticsoft\Heart\Admin\Menu;
use Poeticsoft\Heart\Admin\Assets;
use Poeticsoft\Heart\Admin\PageEditor;
use Poeticsoft\Heart\Admin\PagesList;
use Poeticsoft\Heart\Utils\Utils;

class Admin
{
    public function init()
    {
        Campus::get(Assets::class)->init();
        Campus::get(Menu::class)->init();
        Campus::get(Meta::class)->init();
        Campus::get(Pages::class)->init();

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
