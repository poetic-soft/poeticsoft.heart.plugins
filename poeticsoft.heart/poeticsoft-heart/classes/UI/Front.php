<?php

namespace Poeticsoft\Heart\UI;

use Poeticsoft\Heart\Heart;

class Front {

    protected $assets = [
		[
			'class' => Heart::class,
			'ui_path' => '/ui/front/theme',
			'js_deps' => [
				'jquery'
			],
			'css_deps' => [

			]
		]
	];

	public function get_front_assets() {
		return $this->assets;
	}
}
