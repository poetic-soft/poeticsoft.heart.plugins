<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Admin\Menu;
use Poeticsoft\Heart\Admin\Editor;

class Admin {

	public function init() {
		Heart::get(Menu::class)->init();
		Heart::get(Editor::class)->init();
	}
}
