<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Admin\Pages\Dashboard;
use Poeticsoft\Heart\Admin\Pages\Smtp;

class Menu {

    public function get_parent_slug()
    {
        return Heart::PLUGIN_SLUG;
    }

    private $pages = [
        Dashboard::class,
        Smtp::class,
    ];

	public function init() {
		add_action(
			'admin_menu', 
			[$this, 'register_menus']
		);
	}

    private function add_page($page_class) {

		$parent_slug = $this->get_parent_slug();

        $page = Heart::get($page_class);

        if ($page->get_slug() === $parent_slug) {
            $page->init();
            return;
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

	public function register_menus() {

		$parent_slug = $this->get_parent_slug();

		$primary_page = Heart::get(Dashboard::class);

		add_menu_page(
			Heart::PLUGIN_NAME,
			Heart::PLUGIN_NAME,
			'manage_options',
			$parent_slug,
			[$primary_page, 'render'],
			'dashicons-heart',
			30
		);

        foreach ($this->pages as $page_class) {

            $this->add_page($page_class);
        }
        
        $heart = Heart::instance();

        foreach($heart->plugin_classes as $plugin_class) {

            $plugin = Heart::get($plugin_class);
            $plugin_admin_pages = $plugin->admin->get_pages();

            foreach ($plugin_admin_pages as $page_class) {

                $this->add_page($page_class);
            }
        }
	}
}
