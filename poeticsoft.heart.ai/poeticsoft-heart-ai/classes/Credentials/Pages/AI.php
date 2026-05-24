<?php

namespace Poeticsoft\Heart\Credentials\Pages;

use Poeticsoft\Heart\Credentials\Admin;
use Poeticsoft\Heart\Credentials\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Page_AI
 */
class AI extends Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->page_slug    = 'psh-ai';
		$this->page_title   = __( 'Agentes y Modelos IA', 'poeticsoft-heart-credentials' );
		$this->option_group = 'psh_ai_group';
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
			__( 'Agentes e IA', 'poeticsoft-heart-credentials' ),
			'manage_options',
			$this->page_slug,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Registra los ajustes.
	 */
	public function register_settings() {
		register_setting( $this->option_group, 'psh_ai_active_provider' );
		register_setting( $this->option_group, 'psh_ai_openai_key' );
		register_setting( $this->option_group, 'psh_ai_gemini_key' );
		register_setting( $this->option_group, 'psh_ai_gemini_model' );
		register_setting( $this->option_group, 'psh_ai_anthropic_key' );
		register_setting( $this->option_group, 'psh_ai_cache_ttl' );

		add_settings_section(
			'psh_ai_section',
			__( 'API Keys y Configuración de Modelos', 'poeticsoft-heart-credentials' ),
			null,
			$this->page_slug
		);

		$fields = [
			'psh_ai_active_provider' => __( 'Proveedor de IA Activo', 'poeticsoft-heart-credentials' ),
			'psh_ai_openai_key'    => __( 'OpenAI API Key', 'poeticsoft-heart-credentials' ),
			'psh_ai_gemini_key'    => __( 'Gemini API Key', 'poeticsoft-heart-credentials' ),
			'psh_ai_gemini_model'  => __( 'Gemini Model', 'poeticsoft-heart-credentials' ),
			'psh_ai_anthropic_key' => __( 'Anthropic API Key', 'poeticsoft-heart-credentials' ),
			'psh_ai_cache_ttl'     => __( 'Context Cache TTL (segundos, 0 para desactivar)', 'poeticsoft-heart-credentials' ),
		];

		foreach ( $fields as $id => $title ) {
			add_settings_field(
				$id,
				$title,
				[ $this, 'render_field_router' ],
				$this->page_slug,
				'psh_ai_section',
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
	public function render_field_router( $args ) {
		$id = $args['id'];
		$value = get_option( $id );

		if ( $id === 'psh_ai_active_provider' ) {
			?>
			<select name="psh_ai_active_provider" id="psh_ai_active_provider">
				<option value="gemini" <?php selected( $value, 'gemini' ); ?>>Google Gemini</option>
				<option value="claude" <?php selected( $value, 'claude' ); ?>>Anthropic Claude</option>
				<option value="openai" <?php selected( $value, 'openai' ); ?>>OpenAI</option>
			</select>
			<p class="description"><?php _e( 'Selecciona el proveedor de IA principal que utilizará el motor AICore.', 'poeticsoft-heart-credentials' ); ?></p>
			<?php
		} elseif ( $id === 'psh_ai_gemini_model' ) {
			?>
			<select name="psh_ai_gemini_model" id="psh_ai_gemini_model">
				<option value="gemini-3.1-flash-lite" <?php selected( $value, 'gemini-3.1-flash-lite' ); ?>>Gemini Flash-Lite Latest (3.1)</option>
				<option value="gemini-3.1-pro-latest" <?php selected( $value, 'gemini-3.1-pro-latest' ); ?>>Gemini Pro Latest (3.1)</option>
			</select>
			<?php
		} else {
			$type = ( strpos($id, 'ttl') !== false ) ? 'number' : 'text';			?>
			<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
			<?php
		}
	}
}
