<?php

namespace Poeticsoft\Heart\ORG\Frontend;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Frontend\UI;

class Frontend {

	public function init() {

		ORG::get(UI::class)->init();

		add_shortcode(ORG::PLUGIN_SLUG . '_info', [$this, 'render_shortcode']);
	}

	public function render_shortcode() {
		return sprintf(
			'<div class="%sfrontend">%s is active.</div>',
			esc_attr(ORG::PREFIX),
			esc_html(ORG::PLUGIN_NAME)
		);
	}
}
