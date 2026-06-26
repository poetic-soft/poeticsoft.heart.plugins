<?php

namespace Poeticsoft\Heart\Base\Admin;

use Poeticsoft\Heart\Base\Base;
use Poeticsoft\Heart\Base\Admin\Menu;
use Poeticsoft\Heart\Base\Admin\PostEditor;
use Poeticsoft\Heart\Base\Admin\UI;

/**
 * Admin Controller.
 * Handles admin-specific logic.
 */
class Admin {

	/**
	 * Initialize admin hooks.
	 */
	public function init() {
		// 1. Initialize Admin Assets (always loaded in admin).
		Base::get(UI::class)->init();

		// 2. Register Modular Menus.
		Base::get(Menu::class)->init();

		// 3. Conditional Sub-Routing for other logic (metaboxes, etc).
		add_action('current_screen', [$this, 'route_sub_controllers']);
	}

	/**
	 * Sub-Router: Only instantiates logic for screens that are NOT main settings pages
	 * (since settings pages are now managed by MenuController).
	 */
	public function route_sub_controllers() {
		$screen = get_current_screen();

		if (! $screen) {
			return;
		}

		// A. Check if we are in the Editor (Post/Page/CPT).
		if ('post' === $screen->base) {
			Base::get(PostEditor::class)->init();
		}
	}
}
