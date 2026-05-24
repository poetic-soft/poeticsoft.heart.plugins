<?php

namespace Poeticsoft\Heart\Credentials\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase SMTP_Configurator
 */
class SMTP {

	/**
	 * Almacena el último error para depuración.
	 */
	public static $last_error = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'phpmailer_init', [ $this, 'configure_smtp' ] );
		add_action( 'wp_mail_failed', [ $this, 'capture_error' ] );
	}

	/**
	 * Captura errores de envío.
	 */
	public function capture_error( $wp_error ) {
		self::$last_error = $wp_error->get_error_message();
		error_log( 'Poeticsoft SMTP Error: ' . self::$last_error );
	}

	/**
	 * Configura PHPMailer con los valores de la base de datos.
	 */
	public function configure_smtp( $phpmailer ) {
		$host     = get_option( 'psh_smtp_host' );
		$user     = get_option( 'psh_smtp_user' );
		$pass     = get_option( 'psh_smtp_pass' );
		
		// Si no hay configuración mínima, no hacemos nada.
		if ( empty( $host ) || empty( $user ) || empty( $pass ) ) {
			return;
		}

		$phpmailer->isSMTP();
        $phpmailer->SMTPDebug = 2;
		$phpmailer->Host       = $host;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = get_option( 'psh_smtp_port', 587 );
		$phpmailer->Username   = $user;
		$phpmailer->Password   = $pass;
		$phpmailer->SMTPSecure = get_option( 'psh_smtp_secure', 'tls' );
		$phpmailer->From       = get_option( 'psh_smtp_from_email', $user );
		$phpmailer->FromName   = get_option( 'psh_smtp_from_name', 'Poeticsoft Heart' );

		// Timeout de seguridad.
		$phpmailer->Timeout = 10;
	}
}
