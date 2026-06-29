<?php

namespace Poeticsoft\Heart\Base\Languages;

use Poeticsoft\Heart\Base\Base;

class Languages {

	public function init() {
		add_action('init', [$this, 'load_textdomain']);
	}

	public function load_textdomain() {
		load_plugin_textdomain(
			$this->get_domain(),
			false,
			Base::path('languages')
		);
	}

	public function get_domain() {
		return Base::TEXT_DOMAIN;
	}
}
