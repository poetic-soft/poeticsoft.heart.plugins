<?php

namespace Poeticsoft\Heart\Base\Rest;

use Poeticsoft\Heart\Base\Base;
use Poeticsoft\Heart\Base\Rest\Endpoints\System;
use Poeticsoft\Heart\Base\Rest\AbstractEndpoint;

/**
 * REST API Controller (Orchestrator).
 * Manages registration and security for all API sections.
 */
class Rest {

	/**
	 * API Namespace.
	 */
	private $namespace;

	/**
	 * Registered Endpoint Sections.
	 * 
	 * @var array
	 */
	private $sections = [
		System:class,
		// Add more endpoint classes here.
	];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->namespace = Base::API_NAMESPACE;
	}

	/**
	 * Initialize REST registration.
	 */
	public function init() {
		foreach ($this->sections as $section_class) {
			$this->register_section($section_class);
		}
	}

	/**
	 * Register an endpoint section and apply centralized security.
	 *
	 * @param string $section_class FQDN of the class.
	 */
	private function register_section($section_class) {
		$instance = Base::get($section_class);
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

	/**
	 * Centralized Authentication Logic.
	 *
	 * @param string $level The required security level.
	 * @return bool|\WP_Error
	 */
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
