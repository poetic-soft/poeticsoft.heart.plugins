<?php

namespace Poeticsoft\Heart\Admin;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Clase MainMenu
 */
class MainMenu {

	/**
	 * Slug del menú principal.
	 */
	const PARENT_SLUG = 'poeticsoft-heart';

	/**
	 * Initialize main menu hooks.
	 */
	public function init() {
		add_action('admin_menu', [$this, 'add_menu_page']);
	}

	/**
	 * Añade la página de menú principal.
	 */
	public function add_menu_page() {
		add_menu_page(
			__('Poeticsoft Heart AI', 'poeticsoft-heart-ai'),
			__('Poeticsoft Heart AI', 'poeticsoft-heart-ai'),
			'manage_options',
			self::PARENT_SLUG,
			[$this, 'render_main_page'],
			'dashicons-heart',
			30
		);
	}

	/**
	 * Renderizado de la página principal (Bienvenida/Estado).
	 */
	public function render_main_page() {
		?>
		<div class="wrap">
			<h1><?php _e('Poeticsoft Heart', 'poeticsoft-heart-ai'); ?></h1>
			<p><?php _e('Bienvenido al centro de configuración de Poeticsoft Heart.', 'poeticsoft-heart-ai'); ?></p>
		</div>
		<?php
	}
}
