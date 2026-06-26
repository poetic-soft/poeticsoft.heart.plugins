<?php

namespace Poeticsoft\Heart\Base\Languages;

use Poeticsoft\Heart\Base\Base;

/**
 * Languages Orchestrator.
 * Handles internationalization and text domain loading.
 */
class Languages {

	/**
	 * Initialize internationalization.
	 */
	public function init() {
		add_action('init', [$this, 'load_textdomain']);
	}

	/**
	 * Load the plugin text domain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			$this->get_domain(),
			false,
			Base::path('languages')
		);
	}

	/**
	 * Get the text domain dynamically from Base.
	 * 
	 * @return string
	 */
	public function get_domain() {
		return Base::TEXT_DOMAIN;
	}
}
