<?php

namespace Poeticsoft\Heart\Credentials;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Settings_Page_Base
 */
abstract class Page {

	/**
	 * Slug de la página.
	 * @var string
	 */
	protected $page_slug;

	/**
	 * Título de la página.
	 * @var string
	 */
	protected $page_title;

	/**
	 * Grupo de opciones.
	 * @var string
	 */
	protected $option_group;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Registra los ajustes, secciones y campos.
	 */
	abstract public function register_settings();

	/**
	 * Renderiza la página de ajustes.
	 */
	public function render_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( $this->page_title ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( $this->option_group );
				do_settings_sections( $this->page_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Helper para renderizar campos de texto.
	 */
	public function render_text_field( $args ) {
		$option = get_option( $args['label_for'] );
		$type   = isset( $args['type'] ) ? $args['type'] : 'text';
		?>
		<input 
			type="<?php echo esc_attr( $type ); ?>" 
			id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			name="<?php echo esc_attr( $args['label_for'] ); ?>" 
			value="<?php echo esc_attr( $option ); ?>" 
			class="regular-text"
		>
		<?php
		if ( isset( $args['description'] ) ) {
			echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
		}
	}
}
