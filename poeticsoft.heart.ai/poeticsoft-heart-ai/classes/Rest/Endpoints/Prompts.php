<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\AI;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Prompts\Optimizer;
use WP_REST_Request;
use WP_REST_Response;
use Exception;
use WP_Error;

class Prompts extends Endpoint {

	public function __construct() {
		$this->namespace = 'psh/v1';
	}

	public function register_routes() {
		register_rest_route($this->namespace, '/prompts/optimize', [
			'methods'             => 'POST',
			'callback'            => [$this, 'optimize_prompt'],
			'permission_callback' => [$this, 'check_editor_permissions'],
		]);
	}

	/**
	 * Valida permisos para la optimización de prompts.
	 */
	public function check_editor_permissions(WP_REST_Request $request) {
		return current_user_can('edit_posts');
	}

	public function optimize_prompt(WP_REST_Request $request) {
		$data = [
			'title'      => sanitize_text_field($request->get_param('title')),
			'content'    => $request->get_param('content'), // Puede contener Markdown/HTML, no sanitizar estrictamente aquí
			'branch'     => sanitize_text_field($request->get_param('branch')),
			'cache_name' => sanitize_text_field($request->get_param('cache_name')),
		];

		try {
			$service = AI::get(Optimizer::class);
			$result  = $service->optimize($data);
			return rest_ensure_response($result);
		} catch (Exception $e) {
			return new WP_Error('optimization_failed', $e->getMessage(), ['status' => 500]);
		}
	}
}
