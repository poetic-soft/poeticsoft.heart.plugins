<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Rest\AbstractEndpoint;
use Poeticsoft\Heart\Rest\Endpoints\Mail;

class Rest {

	private $namespace;

	private $sections = [
		Mail::class,
	];

	public function __construct() {
		$this->namespace = Heart::API_NAMESPACE;
	}

	public function init() {
		foreach ($this->sections as $section_class) {
			$this->register_section(
				$this->namespace,
				$section_class
			);
		}

		// Plugins sections
	}

	private function register_section($namespace, $section_class) {
		$section = Heart::get($section_class);
		$routes   = $section->get_routes();

		foreach ($routes as $route => $config) {
			register_rest_route(
				$namespace, 
				$route, [
					'methods'             => $config['methods'],
					'callback'            => $config['callback'],
					'permission_callback' => function($request) use ($config) {
						return $this->check_auth($config['auth'] ?? AbstractEndpoint::AUTH_ADMIN);
					},
				]
			);
		}
	}

	private function check_auth($level) {
		switch ($level) {
			case AbstractEndpoint::AUTH_PUBLIC:
				return true;

			case AbstractEndpoint::AUTH_USER:
				return is_user_logged_in();

			case AbstractEndpoint::AUTH_ADMIN:
				return current_user_can('manage_options');

			default:
				return false;
		}
	}
}
