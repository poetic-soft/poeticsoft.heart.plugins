<?php

namespace Poeticsoft\Heart\Credentials\Pages;

use Poeticsoft\Heart\Credentials\Admin;
use Poeticsoft\Heart\Credentials\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Page_Instagram
 */
class Instagram extends Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->page_slug    = 'psh-instagram';
		$this->page_title   = __( 'Credenciales de Instagram', 'poeticsoft-heart-credentials' );
		$this->option_group = 'psh_instagram_group';
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
			__( 'Instagram', 'poeticsoft-heart-credentials' ),
			'manage_options',
			$this->page_slug,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Registra los ajustes.
	 */
	public function register_settings() {
		register_setting( $this->option_group, 'psh_instagram_app_id' );
		register_setting( $this->option_group, 'psh_instagram_business_account_id' );
		register_setting( $this->option_group, 'psh_instagram_temporal_access_token' );
		register_setting( $this->option_group, 'psh_instagram_facebook_page_id');
        
		add_settings_section(
			'psh_instagram_section',
			__( 'Configuración de API de Instagram & Facebook', 'poeticsoft-heart-credentials' ),
			null,
			$this->page_slug
		);

		$fields = [
			'psh_instagram_app_id'                => __( 'App Id', 'poeticsoft-heart-credentials' ),
			'psh_instagram_business_account_id'   => __( 'Instagram Business Account ID', 'poeticsoft-heart-credentials' ),
			'psh_instagram_temporal_access_token' => __( 'Temporal Access Token', 'poeticsoft-heart-credentials' ),
			'psh_instagram_facebook_page_id'      => __( 'Facebook Page Id', 'poeticsoft-heart-credentials' ),
		];

		foreach ( $fields as $id => $title ) {
			add_settings_field(
				$id,
				$title,
				[ $this, 'render_text_field' ],
				$this->page_slug,
				'psh_instagram_section',
				[ 
					'label_for' => $id,
					'id'        => $id,
					'type'      => 'text',
				]
			);
		}
	}
}
