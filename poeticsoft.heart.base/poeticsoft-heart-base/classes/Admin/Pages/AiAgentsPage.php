<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\AbstractPage;
use Poeticsoft\Heart\Base;

/**
 * AI Agents Settings Page.
 */
class AiAgentsPage extends AbstractPage {

	protected function define_page_props() {
		$this->slug       = Base::PREFIX . 'ai';
		$this->menu_title = __( 'AI Agents', Base::TEXT_DOMAIN );
		$this->page_title = __( 'AI Agents Credentials', Base::TEXT_DOMAIN );

		$this->settings = [
			// --- Gemini Configuration ---
			[
				'key'         => 'gemini_key',
				'field_type'  => 'text',
				'title'       => __( 'Gemini API Key', Base::TEXT_DOMAIN ),
				'description' => __( 'Your secret API key for Google Gemini integrations.', Base::TEXT_DOMAIN ),
				'value'       => '',
				'type'        => 'password',
				'section'     => 'gemini_settings',
				'section_title' => __( 'Google Gemini Configuration', Base::TEXT_DOMAIN ),
			],
			[
				'key'         => 'gemini_model',
				'field_type'  => 'text',
				'title'       => __( 'Default Model', Base::TEXT_DOMAIN ),
				'value'       => 'gemini-1.5-flash',
				'type'        => 'select',
				'section'     => 'gemini_settings',
				'options'     => [
					[ 'label' => 'Gemini 1.5 Flash', 'value' => 'gemini-1.5-flash' ],
					[ 'label' => 'Gemini 1.5 Pro', 'value' => 'gemini-1.5-pro' ],
				],
			],

			// --- Cache Configuration ---
			[
				'key'         => 'ai_cache_ttl',
				'field_type'  => 'int',
				'title'       => __( 'Cache Expiration (TTL)', Base::TEXT_DOMAIN ),
				'description' => __( 'Time in minutes to keep AI responses in cache.', Base::TEXT_DOMAIN ),
				'value'       => 60,
				'type'        => 'number',
				'section'     => 'cache_settings',
				'section_title' => __( 'AI Performance & Cache', Base::TEXT_DOMAIN ),
			],
		];
	}
}
