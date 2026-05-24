<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Base;

/**
 * Frontend Controller.
 * Handles public-facing logic.
 */
class Frontend {

	/**
	 * Initialize frontend hooks.
	 */
	public function init() {
		// Initialize Frontend Assets.
		Base::get( FrontendAssets::class )->init();

		// Add shortcodes, filters for content, etc.
		add_shortcode( Base::PLUGIN_SLUG . '_info', [ $this, 'render_shortcode' ] );
	}

	/**
	 * Example shortcode.
	 */
	public function render_shortcode() {
		return sprintf( 
			'<div class="%sfrontend">%s is active.</div>', 
			esc_attr( Base::PREFIX ), 
			esc_html( Base::PLUGIN_NAME ) 
		);
	}
}
