<?php

namespace Poeticsoft\Heart\API\Endpoints;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Poeticsoft\Heart\API\Endpoint;
use WP_REST_Request ;

class System extends Endpoint {

	/**
	 * Registra las rutas del sistema.
	 */
	public function register_routes() {
		register_rest_route( $this->get_full_namespace(), '/ping', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'ping_callback' ],
			'permission_callback' => '__return_true',
		] );
	}

	/**
	 * Callback para el test de conectividad.
	 */
	public function ping_callback( WP_REST_Request $request ) {
		return $this->response( true, '¡El sistema de API está funcionando correctamente!' );
	}
}
