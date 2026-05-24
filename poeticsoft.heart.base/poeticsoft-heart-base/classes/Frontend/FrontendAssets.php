<?php

namespace Poeticsoft\Heart\Frontend;

use Poeticsoft\Heart\Base;

/**
 * Frontend Assets Handler.
 * Conditional loading for public assets.
 */
class FrontendAssets {

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts based on frontend conditions.
	 */
	public function enqueue_scripts() {
		// Global frontend assets.
		wp_register_style(
			Base::PREFIX . 'main',
			Base::url( 'assets/front/style.css' ),
			[],
			Base::VERSION
		);

		// Conditional: Only on specific pages or post types.
		if ( is_singular() ) {
			wp_enqueue_style( Base::PREFIX . 'main' );
		}

		// Example: Only on a specific page slug.
		if ( is_page( 'contact' ) ) {
			wp_enqueue_script(
				Base::PREFIX . 'contact',
				Base::url( 'assets/front/contact.js' ),
				[],
				Base::VERSION,
				true
			);
		}
	}
}
