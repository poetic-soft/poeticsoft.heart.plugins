<?php

namespace Poeticsoft\Heart\Languages;

use Poeticsoft\Heart\Heart;

class Languages {

	public function init() {
		add_action('init', [$this, 'load_textdomain']);
	}

	public function load_textdomain() {
		load_plugin_textdomain(
			$this->get_domain(),
			false,
			Heart::path('languages')
		);
	}

	public function get_domain() {
		return Heart::TEXT_DOMAIN;
	}
}
