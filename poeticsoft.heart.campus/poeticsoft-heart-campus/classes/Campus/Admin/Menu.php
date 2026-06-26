<?php

namespace Poeticsoft\Heart\Campus\Admin;

use Poeticsoft\Heart\Campus\Campus;
use Poeticsoft\Heart\Campus\Admin\Pages\Access;
use Poeticsoft\Heart\Campus\Admin\Pages\Dashboard;
use Poeticsoft\Heart\Campus\Admin\Pages\Smtp;
use Poeticsoft\Heart\Campus\Admin\Pages\Hall;

class Menu
{
    public function get_parent_slug()
    {
        return Campus::PLUGIN_SLUG;
    }

    private $pages = [
        Dashboard::class,
        Smtp::class,
        Hall::class,
        Access::class,
    ];

    public function init()
    {
        add_action('admin_menu', [$this, 'register_menus']);
    }

    public function register_menus()
    {
        $parent_slug = $this->get_parent_slug();

        add_menu_page(
            Campus::PLUGIN_NAME,
            Campus::PLUGIN_NAME,
            'manage_options',
            $parent_slug,
            [Campus::get(Dashboard::class), 'render'],
            'dashicons-heart',
            30
        );

        foreach ($this->pages as $page_class) {
            $page = Campus::get($page_class);

            if ($page->get_slug() === $parent_slug) {
                $page->init();
                continue;
            }

            add_submenu_page(
                $parent_slug,
                $page->get_page_title(),
                $page->get_menu_title(),
                $page->get_capability(),
                $page->get_slug(),
                [$page, 'render']
            );

            $page->init();
        }
    }
}
