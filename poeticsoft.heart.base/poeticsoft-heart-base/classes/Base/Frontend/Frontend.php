<?php

namespace Poeticsoft\Heart\Base\Frontend;

use Poeticsoft\Heart\Base\Base;
use Poeticsoft\Heart\Base\Frontend\UI;

class Frontend {

	public function init() {
		
		Base::get(UI::class)->init();

		add_shortcode(Base::PLUGIN_SLUG . '_info', [$this, 'render_shortcode']);
	}

	public function render_shortcode() {
		return sprintf(
			'<div class="%sfrontend">%s is active.</div>', 
			esc_attr(Base::PREFIX), 
			esc_html(Base::PLUGIN_NAME) 
		);
	}
}
