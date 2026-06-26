<?php

namespace Poeticsoft\Heart\Base\Frontend;

use Poeticsoft\Heart\Base\Base;

/**
 * Frontend Assets Handler.
 * Conditional loading for public assets.
 */
class UI {

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	/**
	 * Enqueue scripts based on frontend conditions.
	 */
	public function enqueue_scripts() {
		
		$prefix = Base::PREFIX . 'front';

		
		// Global frontend assets.
		$cssfile = 'ui/front/main.css';
		wp_register_style(
			$prefix,
			Base::url($cssfile),
			[],
			filemtime(Base::path($cssfile))
		);

		// Conditional: Only on specific pages or post types.
		if (is_singular()) {
			wp_enqueue_style($prefix);
		}

		// Example: Only on a specific page slug.
		if (is_page('contact')) {
			$jsfile = 'ui/front/front.js';
			wp_enqueue_script(
				$prefix,
				Base::url($jsfile),
				[],
				filemtime(Base::path($jsfile)),
				true
			);
		}
	}
}
