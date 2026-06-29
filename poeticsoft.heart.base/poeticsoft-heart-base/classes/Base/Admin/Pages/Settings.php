<?php

namespace Poeticsoft\Heart\Base\Admin\Pages;

use Poeticsoft\Heart\Admin\Page;

use Poeticsoft\Heart\Base\Base;

class Settings extends Page {

	protected function define_page_props() {
		$this->slug       = Base::PLUGIN_SLUG . '-settings';
		$this->menu_title = __('Settings', Base::TEXT_DOMAIN);
		$this->page_title = sprintf(__('%s Configuration', Base::TEXT_DOMAIN), Base::PLUGIN_NAME);

		$this->settings = [
			[
				'key'         => 'setting_key',
				'title'       => __('Setting title', Base::TEXT_DOMAIN),
				'field_type'  => 'checkbox', // See sanitize managed types
				'value'       => '0',
				'section'     => 'general_settings',
                'type'          => 'text', // WP Option types
				'section_title' => __('General Settings', Base::TEXT_DOMAIN),
				'description' => __('Setting description', Base::TEXT_DOMAIN),
			]
		];
	}
}
