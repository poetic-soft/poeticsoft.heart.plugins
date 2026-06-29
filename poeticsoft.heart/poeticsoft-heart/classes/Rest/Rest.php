<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Rest\Endpoint;

class Rest {

	private $namespace;

	private $sections = [];

	public function __construct() {
		$this->namespace = Heart::API_NAMESPACE;
	}

	public function init() {
		foreach ($this->sections as $section_class) {
			$this->register_section($section_class);
		}
	}

	private function register_section($section_class) {
		$instance = Heart::get($section_class);
		$routes   = $instance->get_routes();

		foreach ($routes as $route => $config) {
			register_rest_route($this->namespace, $route, [
				'methods'             => $config['methods'],
				'callback'            => [$instance, $config['callback']],
				'permission_callback' => function($request) use ($config) {
					return $this->check_auth($config['auth'] ?? AbstractEndpoint::AUTH_ADMIN);
				},
			]);
		}
	}

	private function check_auth($level) {
		switch ($level) {
			case Endpoint::AUTH_PUBLIC:
				return true;

			case Endpoint::AUTH_USER:
				return is_user_logged_in();

			case Endpoint::AUTH_ADMIN:
				return current_user_can('manage_options');

			default:
				return false;
		}
	}
}
