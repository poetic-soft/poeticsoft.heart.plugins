<?php

namespace Poeticsoft\Heart\ORG\Admin;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Admin\User\User;
use Poeticsoft\Heart\ORG\Admin\Menu;
use Poeticsoft\Heart\ORG\Admin\PostEditor;
use Poeticsoft\Heart\ORG\Admin\UI;

class Admin {

	public function init() {

		ORG::get(Menu::class)->init();

		ORG::get(UI::class)->init();

		add_action('current_screen', [$this, 'route_sub_controllers']);
	}

	public function route_sub_controllers() {
		$screen = get_current_screen();

		if (! $screen) {
			return;
		}

		if ('post' === $screen->base) {
			ORG::get(PostEditor::class)->init();
		}
	}
}
