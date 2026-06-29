<?php

namespace Poeticsoft\Heart\ORG\Frontend;

use Poeticsoft\Heart\ORG\ORG;

class UI {

	public function init() {
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
	}

	public function enqueue_scripts() {

		$prefix = ORG::PREFIX . 'front';

		$cssfile = 'ui/front/main.css';
		wp_register_style(
			$prefix,
			ORG::url($cssfile),
			[],
			filemtime(ORG::path($cssfile))
		);

		if (is_singular()) {
			wp_enqueue_style($prefix);
		}

		if (is_page('contact')) {
			$jsfile = 'ui/front/front.js';
			wp_enqueue_script(
				$prefix,
				ORG::url($jsfile),
				[],
				filemtime(ORG::path($jsfile)),
				true
			);
		}
	}
}
