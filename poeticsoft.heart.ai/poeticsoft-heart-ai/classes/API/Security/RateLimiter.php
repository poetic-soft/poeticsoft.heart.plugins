<?php

namespace Poeticsoft\Heart\API\Security;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Error;

class RateLimiter {

	/**
	 * Comprueba si una petición excede los límites establecidos.
	 *
	 * @param string          Nombre de la acción (ej. 'voice_stream').
	 * @param int       Número máximo de peticiones permitidas.
	 * @param int     Ventana de tiempo en segundos.
	 *
	 * @return bool|WP_Error True si es seguro, WP_Error si se excede el límite.
	 */
	public static function check_limit( $action, $max_requests, $window_seconds ) {
		$ip = self::get_client_ip();
		$key = 'psh_rl_' . $action . '_' . md5( $ip );

		$current_count = get_transient( $key );

		if ( false === $current_count ) {
			// Primera petición en esta ventana
			set_transient( $key, 1, $window_seconds );
			return true;
		}

		if ( $current_count >= $max_requests ) {
			return new WP_Error(
				'rest_too_many_requests',
				__( 'Has excedido el límite de peticiones. Por favor, espera un momento.', 'poeticsoft-heart' ),
				[ 'status' => 429 ]
			);
		}

		// Incrementar contador manualmente (Transients no tienen incremento atómico nativo, 
		// pero para este caso de uso es suficiente)
		set_transient( $key, $current_count + 1, $window_seconds );

		return true;
	}

	/**
	 * Obtiene la dirección IP real del cliente.
	 *
	 * @return string
	 */
	private static function get_client_ip() {
		if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ips = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
			return trim( $ips[0] );
		}
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}
}
