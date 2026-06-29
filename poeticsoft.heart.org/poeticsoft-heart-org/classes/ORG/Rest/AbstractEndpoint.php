<?php

namespace Poeticsoft\Heart\ORG\Rest;

use Poeticsoft\Heart\ORG\ORG;
use WP_REST_Response;
use WP_Error;

abstract class AbstractEndpoint {

	const AUTH_PUBLIC = 'public';
	const AUTH_USER   = 'user';
	const AUTH_ADMIN  = 'admin';

	abstract public function get_routes();

	public function send_success($data, $status = 200) {
		return new WP_REST_Response([
			'success' => true,
			'data'    => $data,
		], $status);
	}

	public function send_error(WP_Error $error, $status = 400) {

		return new WP_REST_Response([
			'success' => false,
			'data' => $error, 
		], $status);
	}
}
