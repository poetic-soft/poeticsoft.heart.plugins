<?php

namespace Poeticsoft\Heart\Base\Admin;

use Poeticsoft\Heart\Base\Base;

/**
 * Admin Assets Handler.
 * Ensures assets are loaded only when and where they are needed.
 */
class UI {

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	/**
	 * Enqueue scripts conditionally.
	 *
	 * @param string $hook_suffix The current admin page suffix.
	 */
	public function enqueue_scripts($hook_suffix) {
		$screen = get_current_screen();

		$prefix = Base::PREFIX . 'admin';

		// Only load on our specific plugin page.
		if ('toplevel_page_' . Base::PLUGIN_SLUG === $hook_suffix) {
			$cssfile = 'ui/admin/main.css';
			wp_enqueue_style(
				$prefix,
				Base::url($cssfile),
				[],
				filemtime(Base::path($cssfile))
			);

			$jsfile = 'ui/admin/main.css';
			wp_enqueue_script(
				$prefix,
				Base::url($jsfile),
				['jquery'],
				filemtime(Base::path($jsfile)),
				true
			);
		}

		// Example: Only load on 'post' or 'page' editor.
		if ($screen && 'post' === $screen->base) {
			// Enqueue editor-specific assets here.
		}
	}
}
