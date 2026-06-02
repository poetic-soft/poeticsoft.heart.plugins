<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\Utils\Utils;

/**
 * Abstract Endpoint Base Class.
 * Provides consistent response methods and security constants.
 */
abstract class Endpoint {

	/**
	 * Security Levels.
	 */
	const AUTH_PUBLIC = 'public';
	const AUTH_USER   = 'user';
	const AUTH_ADMIN  = 'admin';

	/**
	 * Every endpoint class must return its routes configuration.
	 * 
	 * Format:
	 * [
	 *    '/path' => [
	 *        'methods'  => 'GET',
	 *        'callback' => 'method_name',
	 *        'auth'     => self::AUTH_ADMIN
	 *    ]
	 * ]
	 *
	 * @return array
	 */
	abstract public function get_routes();

	/**
	 * Standardized Success Response.
	 *
	 * @param mixed $data   Data to return.
	 * @param int   $status HTTP Status code.
	 * @return \WP_REST_Response
	 */
	public function send_success( $data, $status = 200 ) {
		return Utils::send_success( $data, $status );
	}

	/**
	 * Standardized Error Response.
	 *
	 * @param string $code    Machine-readable error code.
	 * @param string $message Human-readable message.
	 * @param int    $status  HTTP Status code.
	 * @return \WP_REST_Response
	 */
	public function send_error( $code, $message, $status = 400 ) {
		return Utils::send_error( $code, $message, $status );
	}
}
