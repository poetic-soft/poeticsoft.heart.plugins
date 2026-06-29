<?php

namespace Poeticsoft\Heart\ORG\Admin;

use Poeticsoft\Heart\ORG\ORG;

class UI {

	public function init() {
		add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	public function enqueue_scripts($hook_suffix) {
		$screen = get_current_screen();

		$prefix = ORG::PREFIX . 'admin';

		if ('toplevel_page_' . ORG::PLUGIN_SLUG === $hook_suffix) {
			$cssfile = 'ui/admin/main.css';
			wp_enqueue_style(
				$prefix,
				ORG::url($cssfile),
				[],
				filemtime(ORG::path($cssfile))
			);

			$jsfile = 'ui/admin/main.css';
			wp_enqueue_script(
				$prefix,
				ORG::url($jsfile),
				['jquery'],
				filemtime(ORG::path($jsfile)),
				true
			);
		}

		if ($screen && 'post' === $screen->base) {

		}
	}
}
