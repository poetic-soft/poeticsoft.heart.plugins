<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\MainMenu;
use Poeticsoft\Heart\Admin\Page;
use Poeticsoft\Heart\AI;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Clase Page_Instagram
 */
class Instagram extends Page {

	/**
	 * Initialize page properties and hooks.
	 */
	public function init() {
		$this->page_slug    = AI::PLUGIN_SLUG . '-instagram';
		$this->page_title   = __('Credenciales de Instagram', 'poeticsoft-heart-ai');
		$this->option_group = AI::PREFIX . 'instagram_group';
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
			__('Instagram', 'poeticsoft-heart-ai'),
			'manage_options',
			$this->page_slug,
			[$this, 'render_page']
		);
	}

	/**
	 * Registra los ajustes.
	 */
	public function register_settings() {
		register_setting($this->option_group, AI::PREFIX . 'instagram_app_id');
		register_setting($this->option_group, AI::PREFIX . 'instagram_business_account_id');
		register_setting($this->option_group, AI::PREFIX . 'instagram_temporal_access_token');
		register_setting($this->option_group, AI::PREFIX . 'instagram_facebook_page_id');
        
		add_settings_section(
			AI::PREFIX . 'instagram_section',
			__('Configuración de API de Instagram & Facebook', 'poeticsoft-heart-ai'),
			null,
			$this->page_slug
		);

		$fields = [
			AI::PREFIX . 'instagram_app_id'                => __('App Id', 'poeticsoft-heart-ai'),
			AI::PREFIX . 'instagram_business_account_id'   => __('Instagram Business Account ID', 'poeticsoft-heart-ai'),
			AI::PREFIX . 'instagram_temporal_access_token' => __('Temporal Access Token', 'poeticsoft-heart-ai'),
			AI::PREFIX . 'instagram_facebook_page_id'      => __('Facebook Page Id', 'poeticsoft-heart-ai'),
		];

		foreach ($fields as $id => $title) {
			add_settings_field(
				$id,
				$title,
				[$this, 'render_text_field'],
				$this->page_slug,
				AI::PREFIX . 'instagram_section',
				[
					'label_for' => $id,
					'id'        => $id,
					'type'      => 'text',
				]
			);
		}
	}
}
