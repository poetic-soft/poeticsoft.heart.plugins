<?php

namespace Poeticsoft\Heart\UI;

use Poeticsoft\Heart\Heart;

class Admin {

    protected $assets = [
		[
			'class' => Heart::class,
			'ui_path' => '/ui/admin/dashboard',
			'js_deps' => [
				'jquery'
			],
			'css_deps' => [

			]
		]
	];

	public function get_admin_assets() {
		return $this->assets;
	}
}
