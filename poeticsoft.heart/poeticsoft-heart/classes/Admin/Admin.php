<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Admin\Menu;

class Admin {

	public function init() {
		Heart::get(Menu::class)->init();
	}
}
