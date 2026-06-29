<?php

namespace Poeticsoft\Heart\ORG\Rest;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Rest\Endpoints\User;
use Poeticsoft\Heart\ORG\Rest\Endpoints\Page;
use Poeticsoft\Heart\ORG\Rest\AbstractEndpoint;

class Rest {

	private $namespace;

	private $sections = [
		User::class,
		Page::class,
	];

	public function __construct() {
		$this->namespace = ORG::API_NAMESPACE;
	}

	public function init() {
		foreach ($this->sections as $section_class) {
			$this->register_section($section_class);
		}
	}

	private function register_section($section_class) {
		$instance = ORG::get($section_class);
		$routes   = $instance->get_routes();

		foreach ($routes as $route => $config) {
			register_rest_route(
				$this->namespace,
				$route,
				[
					'methods'             => $config['methods'],
					'callback'            => [$instance, $config['callback']],
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
