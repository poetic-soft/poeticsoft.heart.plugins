<?php

namespace Poeticsoft\Heart\Credentials;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Admin_Menu
 */
class Admin {

	/**
	 * Slug del menú principal.
	 */
	const PARENT_SLUG = 'poeticsoft-heart';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
	}

	/**
	 * Añade la página de menú principal.
	 */
	public function add_menu_page() {
		add_menu_page(
			__( 'Poeticsoft Heart', 'poeticsoft-heart-credentials' ),
			__( 'Poeticsoft Heart', 'poeticsoft-heart-credentials' ),
			'manage_options',
			self::PARENT_SLUG,
			[ $this, 'render_main_page' ],
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
			<h1><?php _e( 'Poeticsoft Heart', 'poeticsoft-heart-credentials' ); ?></h1>
			<p><?php _e( 'Bienvenido al centro de configuración de Poeticsoft Heart.', 'poeticsoft-heart-credentials' ); ?></p>
		</div>
		<?php
	}
}
