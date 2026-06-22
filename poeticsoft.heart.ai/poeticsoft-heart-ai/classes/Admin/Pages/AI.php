<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\AI as HeartAi;
use Poeticsoft\Heart\Admin\MainMenu;
use Poeticsoft\Heart\Admin\Page;

if (!defined('ABSPATH')) {
	exit;$this->page_slug    = HeartAi::PLUGIN_SLUG . '-ai';
}

/**
 * Clase Page_AI
 */
class AI extends Page {

	/**
	 * Initialize page properties and hooks.
	 */
	public function init() {
		
		$this->page_title   = __('Agentes y Modelos IA', 'poeticsoft-heart-ai');
		$this->option_group = HeartAi::PREFIX . 'ai_group';
		parent::init();
		add_action('admin_menu', [$this, 'add_submenu_page']);
	}

	/**
	 * Añade la subpágina al menú principal.
	 */
	public function add_submenu_page() {
		add_submenu_page(
			MainMenu::PARENT_SLUG,
			$this->page_title,
			__('Agentes e IA', 'poeticsoft-heart-ai'),
			'manage_options',
			$this->page_slug,
			[$this, 'render_page']
		);
	}

	/**
	 * Registra los ajustes.
	 */
	public function register_settings() {
		register_setting($this->option_group, HeartAi::PREFIX . 'ai_active_provider');
		register_setting($this->option_group, HeartAi::PREFIX . 'ai_openai_key');
		register_setting($this->option_group, HeartAi::PREFIX . 'ai_gemini_key');
		register_setting($this->option_group, HeartAi::PREFIX . 'ai_gemini_model');
		register_setting($this->option_group, HeartAi::PREFIX . 'ai_anthropic_key');
		register_setting($this->option_group, HeartAi::PREFIX . 'ai_cache_ttl');

		add_settings_section(
			HeartAi::PREFIX . 'ai_section',
			__('API Keys y Configuración de Modelos', 'poeticsoft-heart-ai'),
			null,
			$this->page_slug
		);

		$fields = [
			HeartAi::PREFIX . 'ai_active_provider' => __('Proveedor de IA Activo', 'poeticsoft-heart-ai'),
			HeartAi::PREFIX . 'ai_openai_key'    => __('OpenAI API Key', 'poeticsoft-heart-ai'),
			HeartAi::PREFIX . 'ai_gemini_key'    => __('Gemini API Key', 'poeticsoft-heart-ai'),
			HeartAi::PREFIX . 'ai_gemini_model'  => __('Gemini Model', 'poeticsoft-heart-ai'),
			HeartAi::PREFIX . 'ai_anthropic_key' => __('Anthropic API Key', 'poeticsoft-heart-ai'),
			HeartAi::PREFIX . 'ai_cache_ttl'     => __('Context Cache TTL (segundos, 0 para desactivar)', 'poeticsoft-heart-ai'),
		];

		foreach ($fields as $id => $title) {
			add_settings_field(
				$id,
				$title,
				[$this, 'render_field_router'],
				$this->page_slug,
				HeartAi::PREFIX . 'ai_section',
				[
					'label_for' => $id,
					'id'        => $id
				]
			);
		}
	}

	/**
	 * Router para renderizar diferentes tipos de campos.
	 */
	public function render_field_router($args) {
		$id = $args['id'];
		$value = get_option($id);

		if ($id === HeartAi::PREFIX . 'ai_active_provider') {
			?>
			<select name="<?php echo esc_attr(HeartAi::PREFIX); ?>ai_active_provider" id="<?php echo esc_attr(HeartAi::PREFIX); ?>ai_active_provider">
				<option value="gemini" <?php selected($value, 'gemini'); ?>>Google Gemini</option>
				<option value="claude" <?php selected($value, 'claude'); ?>>Anthropic Claude</option>
				<option value="openai" <?php selected($value, 'openai'); ?>>OpenAI</option>
			</select>
			<p class="description"><?php _e('Selecciona el proveedor de IA principal que utilizará el motor AICore.', 'poeticsoft-heart-ai'); ?></p>
			<?php
		} elseif ($id === HeartAi::PREFIX . 'ai_gemini_model') {
			?>
			<select name="<?php echo esc_attr(HeartAi::PREFIX); ?>ai_gemini_model" id="<?php echo esc_attr(HeartAi::PREFIX); ?>ai_gemini_model">
				<option value="gemini-3.1-flash-lite" <?php selected($value, 'gemini-3.1-flash-lite'); ?>>Gemini Flash-Lite Latest (3.1)</option>
				<option value="gemini-3.1-pro-latest" <?php selected($value, 'gemini-3.1-pro-latest'); ?>>Gemini Pro Latest (3.1)</option>
			</select>
			<?php
		} else {
			$type = (strpos($id, 'ttl') !== false) ? 'number' : 'text';			?>
			<input type="<?php echo esc_attr($type); ?>" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
			<?php
		}
	}
}
