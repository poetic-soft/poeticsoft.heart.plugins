<?php

namespace Poeticsoft\Heart\Base;

use Poeticsoft\Heart\Base\Base;

class UI {

	public function init() {
		add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	public function enqueue_scripts($hook_suffix) {
		$screen = get_current_screen();

		$prefix = Base::PREFIX . 'admin';

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

		if ($screen && 'post' === $screen->base) {
			
		}
	}
}
