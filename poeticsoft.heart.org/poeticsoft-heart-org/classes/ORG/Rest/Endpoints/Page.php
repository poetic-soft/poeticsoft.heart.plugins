<?php

namespace Poeticsoft\Heart\ORG\Rest\Endpoints;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Rest\AbstractEndpoint;
use Poeticsoft\Heart\ORG\Admin\Page\Page as AdminPage;
use WP_Error;

class Page extends AbstractEndpoint {

	public function get_routes() {
		return [
			'/page/get' => [
				'methods'  => 'GET',
				'callback' => 'get',
				'auth'     => self::AUTH_USER,
			],
			'/page/create-user-root-page' => [
				'methods'  => 'POST',
				'callback' => 'create_user_root_page',
				'auth'     => self::AUTH_USER,
			]
		];
	}

	public function get($request) {
		return $this->send_success([
			'page' => 'page',
			'time'   => current_time('mysql'),
		]);
	}

	public function create_user_root_page($request) {

		return $this->send_error(
			new WP_Error(
				'error_crear_pagina',
				'No se pudo crear la página, falta autor o titulo.'
			)
		);

		$params = $request->get_params();

		if(!isset($params['userId']) || !isset($params['title'])) {

			return $this->send_error(
				'cannot_create_user_root_page',
				'Falta ID de usuario o Título',
				500
			);
		}

		$page_id = ORG::get(AdminPage::class)->create_user_root_page(
			$params['userId'],
			$params['title'],
			$params['slug'] ?? null,
			$params['status'] ?? null
		);

		if (is_wp_error($page_id)) {

			return $this->send_error(
				'cannot_create_user_root_page',
				'Ha habido un error creando la página principal del usuario',
				500
			);
		}

		return $this->send_success([
			'page_id' => $page_id,
			'time'   => current_time('mysql'),
		]);
	}
}
