<?php

namespace Poeticsoft\Heart\Rest;

if (!defined('ABSPATH')) {
	exit;
}

use Poeticsoft\Heart\Rest\Security\RateLimiter;
use WP_REST_Response;
use WP_Error;

/**
 * Clase Endpoint_Base
 */
abstract class Endpoint {

	/**
	 * Namespace base de la API.
	 *
	 * @var string
	 */
	protected $namespace = 'poeticsoft/heart';

	/**
	 * Versión de la API.
	 *
	 * @var string
	 */
	protected $version = 'v1';

	/**
	 * Base de la ruta REST.
	 *
	 * @var string
	 */
	protected $rest_base;
	/**
	 * Método obligatorio para registrar las rutas del controlador.
	 */
	abstract public function register_routes();

	/**
	 * Obtiene el namespace completo (ej. poeticsoft/heart/v1).
	 *
	 * @return string
	 */
	protected function get_full_namespace() {
		return $this->namespace . '/' . $this->version;
	}

	/**
	 * Método auxiliar para crear respuestas estandarizadas.
	 *
	 * @param bool   $success Si la operación fue exitosa.
	 * @param string $message Mensaje de respuesta.
	 * @param array  $data    Datos adicionales.
	 * @param int    $status  Código HTTP.
	 *
	 * @return WP_REST_Response
	 */
	protected function response($success, $message = '', $data = [], $status = 200) {
		return new WP_REST_Response(
			[
				'success' => $success,
				'message' => $message,
				'data'    => $data,
				'time'    => current_time('mysql'),
			],
			$status
		);
	}

	/**
	 * Verifica si una petición pública es segura (Origen + Rate Limit).
	 *
	 * @param WP_REST_Request $request      Objeto de la petición.
	 * @param string           $action       Nombre de la acción.
	 * @param int              $max_requests Límite de peticiones.
	 * @param int              $window       Ventana de tiempo.
	 *
	 * @return bool|WP_Error
	 */
	protected function is_public_request_safe($request, $action, $max_requests = 10, $window = 60) {
		// 1. Validar Origen (Referer o Origin)
		$referer = $request->get_header('referer');
		$origin  = $request->get_header('origin');
		$site_url = site_url();

		$is_valid_origin = false;
		if (!empty($referer) && strpos($referer, $site_url) !== false) {
			$is_valid_origin = true;
		} elseif (!empty($origin) && strpos($origin, $site_url) !== false) {
			$is_valid_origin = true;
		}

		if (!$is_valid_origin) {
			return new WP_Error(
				'rest_forbidden_origin',
				__('Origen de petición no permitido.', 'poeticsoft-heart-ai'),
				['status' => 403]
			);
		}

		// 2. Aplicar Rate Limiting
		return RateLimiter::check_limit($action, $max_requests, $window);
	}
}
