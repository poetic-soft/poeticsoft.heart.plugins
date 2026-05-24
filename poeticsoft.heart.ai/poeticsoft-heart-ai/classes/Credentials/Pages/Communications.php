<?php

namespace Poeticsoft\Heart\Credentials\Pages;

use Poeticsoft\Heart\Credentials\Admin;
use Poeticsoft\Heart\Credentials\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Page_Communications
 */
class Communications extends Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->page_slug    = 'psh-communications';
		$this->page_title   = __( 'Comunicaciones y SMTP', 'poeticsoft-heart-credentials' );
		$this->option_group = 'psh_communications_group';
		parent::__construct();
		add_action( 'admin_menu', [ $this, 'add_submenu_page' ] );
	}

	/**
	 * Añade la subpágina al menú principal.
	 */
	public function add_submenu_page() {
		add_submenu_page(
			Admin::PARENT_SLUG,
			$this->page_title,
			__( 'Comunicaciones', 'poeticsoft-heart-credentials' ),
			'manage_options',
			$this->page_slug,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Registra los ajustes.
	 */
	public function register_settings() {
		register_setting( $this->option_group, 'psh_smtp_host' );
		register_setting( $this->option_group, 'psh_smtp_port' );
		register_setting( $this->option_group, 'psh_smtp_user' );
		register_setting( $this->option_group, 'psh_smtp_pass' );
		register_setting( $this->option_group, 'psh_smtp_secure' );
		register_setting( $this->option_group, 'psh_smtp_from_email' );
		register_setting( $this->option_group, 'psh_smtp_from_name' );

		add_settings_section(
			'psh_smtp_section',
			__( 'Configuración SMTP (Envío de correos)', 'poeticsoft-heart-credentials' ),
			null,
			$this->page_slug
		);

		$fields = [
			'psh_smtp_host'       => __( 'Host SMTP', 'poeticsoft-heart-credentials' ),
			'psh_smtp_port'       => __( 'Puerto', 'poeticsoft-heart-credentials' ),
			'psh_smtp_user'       => __( 'Usuario / Email', 'poeticsoft-heart-credentials' ),
			'psh_smtp_pass'       => __( 'Contraseña', 'poeticsoft-heart-credentials' ),
			'psh_smtp_secure'     => __( 'Seguridad (tls/ssl)', 'poeticsoft-heart-credentials' ),
			'psh_smtp_from_email' => __( 'Email Remitente', 'poeticsoft-heart-credentials' ),
			'psh_smtp_from_name'  => __( 'Nombre Remitente', 'poeticsoft-heart-credentials' ),
		];

		foreach ( $fields as $id => $title ) {
			add_settings_field(
				$id,
				$title,
				[ $this, 'render_text_field' ],
				$this->page_slug,
				'psh_smtp_section',
				[ 
					'label_for' => $id,
					'type'      => 'text'				]
			);
		}
	}
}
