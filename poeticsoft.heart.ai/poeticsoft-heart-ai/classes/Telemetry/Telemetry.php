<?php

namespace Poeticsoft\Heart\Telemetry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Telemetry {

	/**
	 * Directorio de logs.
	 *
	 * @var string
	 */
	private static $log_dir;

	/**
	 * Inicializa el logger.
	 */
	public static function init() {
		self::$log_dir = dirname( __DIR__ ) . '/logs';

		if ( ! file_exists( self::$log_dir ) ) {
			wp_mkdir_p( self::$log_dir );
		}

		// Asegurar que haya un index.php o .htaccess para proteger el directorio
		if ( ! file_exists( self::$log_dir . '/index.php' ) ) {
			file_put_contents( self::$log_dir . '/index.php', '<?php // Silence is golden' );
		}
	}

	/**
	 * Escribe un mensaje en el log.
	 *
	 * @param string $message Mensaje a loguear.
	 * @param string $level   Nivel de log (info, error, warning, debug).
	 * @param array  $context Datos adicionales de contexto.
	 * @param string $file    Nombre del archivo de log (opcional).
	 */
	public static function log( $message, $level = 'info', $context = [], $file = 'system' ) {
		if ( empty( self::$log_dir ) ) {
			self::init();
		}

		$date     = current_time( 'Y-m-d' );
		$time     = current_time( 'H:i:s' );
		$filename = sprintf( '%s-%s.log', $file, $date );
		$filepath = self::$log_dir . '/' . $filename;

		$log_entry = sprintf(
			"[%s %s] [%s]: %s %s\n",
			$date,
			$time,
			strtoupper( $level ),
			$message,
			! empty( $context ) ? json_encode( $context, JSON_UNESCAPED_UNICODE ) : ''
		);

		error_log( $log_entry, 3, $filepath );
	}

	/**
	 * Métodos de conveniencia.
	 */
	public static function info( $message, $context = [], $file = 'system' ) {
		self::log( $message, 'info', $context, $file );
	}

	public static function error( $message, $context = [], $file = 'system' ) {
		self::log( $message, 'error', $context, $file );
	}

	public static function debug( $message, $context = [], $file = 'system' ) {
		self::log( $message, 'debug', $context, $file );
	}

	public static function warning( $message, $context = [], $file = 'system' ) {
		self::log( $message, 'warning', $context, $file );
	}
}
