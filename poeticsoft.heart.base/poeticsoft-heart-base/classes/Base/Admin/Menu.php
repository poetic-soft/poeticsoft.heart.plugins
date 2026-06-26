<?php

namespace Poeticsoft\Heart\Base\Admin;

use Poeticsoft\Heart\Base\Base;
use Poeticsoft\Heart\Base\Admin\Pages\Dashboard;

/**
 * Menu Controller.
 * Orchestrates the creation of the main menu and sub-pages.
 */
class Menu {

	/**
	 * Get the parent slug dynamically from Base.
	 * 
	 * @return string
	 */
	public function get_parent_slug() {
		return Base::PLUGIN_SLUG;
	}

	/**
	 * Registered Sub-pages.
	 * 
	 * @var array
	 */
	private $pages = [
		Dashboard::class,
	];

	/**
	 * Initialize menu hooks.
	 */
	public function init() {
		add_action('admin_menu', [$this, 'register_menus']);
	}

	/**
	 * Register the Top-level menu and all sub-menus.
	 */
	public function register_menus() {
		$parent_slug = $this->get_parent_slug();

		// 1. Create the Main Top-Level Menu.
		add_menu_page(
			Base::PLUGIN_NAME,
			Base::PLUGIN_NAME,
			'manage_options',
			$parent_slug,
			[Base::get(Dashboard::class), 'render'],
			'dashicons-heart',
			30
		);

		// 2. Register all sub-pages.
		foreach ($this->pages as $page_class) {
			$page = Base::get($page_class);
			
			// Dashboard is the parent, so we skip adding it as sub-menu to avoid duplication 
			// (WP adds the first sub-menu with the same slug as the parent automatically).
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

			// Initialize page-specific logic (like registering settings).
			$page->init();
		}
	}
}
