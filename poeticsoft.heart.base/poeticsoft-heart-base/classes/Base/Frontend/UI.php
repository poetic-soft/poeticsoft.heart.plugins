<?php

namespace Poeticsoft\Heart\Base\Frontend;

use Poeticsoft\Heart\Base\Base;

class UI {

	public function init() {
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	public function enqueue_scripts() {
		
		$prefix = Base::PREFIX . 'front';

		$cssfile = 'ui/front/main.css';
		wp_register_style(
			$prefix,
			Base::url($cssfile),
			[],
			filemtime(Base::path($cssfile))
		);

		if (is_singular()) {
			wp_enqueue_style($prefix);
		}

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
