<?php

namespace Poeticsoft\Heart\ORG\Languages;

use Poeticsoft\Heart\ORG\ORG;

class Languages {

	public function init() {
		add_action('init', [$this, 'load_textdomain']);
	}

	public function load_textdomain() {
		load_plugin_textdomain(
			$this->get_domain(),
			false,
			ORG::path('languages')
		);
	}

	public function get_domain() {
		return ORG::TEXT_DOMAIN;
	}
}
