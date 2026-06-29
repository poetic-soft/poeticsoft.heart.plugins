<?php

namespace Poeticsoft\Heart\ORG\Admin;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Admin\Pages\Dashboard;

class Menu {

	public function get_parent_slug() {
		return ORG::PLUGIN_SLUG;
	}

	private $pages = [
		Dashboard::class,
	];

	public function init() {
		add_action('admin_menu', [$this, 'register_menus']);
	}

	public function register_menus() {
		$parent_slug = $this->get_parent_slug();

		add_menu_page(
			ORG::PLUGIN_NAME,
			ORG::PLUGIN_NAME,
			'manage_options',
			$parent_slug,
			[ORG::get(Dashboard::class), 'render'],
			'dashicons-heart',
			30
		);

		foreach ($this->pages as $page_class) {
			$page = ORG::get($page_class);

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
