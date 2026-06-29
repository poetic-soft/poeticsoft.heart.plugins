<?php

namespace Poeticsoft\Heart\Base\Admin;

use Poeticsoft\Heart\Base\Admin\Pages\Settings;

class Admin {

	private $pages = [
		Settings::class,
	];

	public function get_pages() {
		
		return $this->pages;
	}
}
