<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Base;

/**
 * Admin Assets Handler.
 * Ensures assets are loaded only when and where they are needed.
 */
class AdminAssets {

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts conditionally.
	 *
	 * @param string $hook_suffix The current admin page suffix.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		$screen = get_current_screen();

		// Only load on our specific plugin page.
		if ( 'toplevel_page_' . Base::PLUGIN_SLUG === $hook_suffix ) {
			wp_enqueue_style(
				Base::PREFIX . 'admin-css',
				Base::url( 'assets/admin/admin-style.css' ),
				[],
				Base::VERSION
			);

			wp_enqueue_script(
				Base::PREFIX . 'admin-js',
				Base::url( 'assets/admin/admin-script.js' ),
				[ 'jquery' ],
				Base::VERSION,
				true
			);
		}

		// Example: Only load on 'post' or 'page' editor.
		if ( $screen && 'post' === $screen->base ) {
			// Enqueue editor-specific assets here.
		}
	}
}
