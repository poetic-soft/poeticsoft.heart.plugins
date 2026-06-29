<?php

namespace Poeticsoft\Heart\ORG\Rest\Endpoints;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Rest\AbstractEndpoint;
use Poeticsoft\Heart\ORG\Admin\User\User as AdminUser;
use WP_Error;

class User extends AbstractEndpoint {

	public function get_routes() {
		return [
			'/user/create-new-user' => [
				'methods'  => 'POST',
				'callback' => 'create_new_user',
				'auth'     => self::AUTH_USER,
			]
		];
	}

	public function create_new_user($request) {

		$params = $request->get_params();
		$e = new WP_Error();

		if(!isset($params['email'])) {

			$e->add(
				'mail_not_provided',
				'Es necesario el mail del nuevo usuario'
			);
		} 
		
		if(!isset($params['commercialName'])) {

			$e->add(
				'commercial_name_not _provided',
				'Es necesario el nombre comercial'
			);
		}

		if(
			!isset($params['email'])
			||
			!isset($params['commercialName'])
		) {

			return $this->send_error(
				$e,
				500
			);
		}

		$user_id = ORG::get(AdminUser::class)->create_new_user(
			$params['email'],
			$params['commercialName']
		);

		if (is_wp_error($user_id)) {

			$user_id->add(
				'cannot_create_user',
				'Ha habido un error creando el nuevo usuario'
			);

			return $this->send_error(
				$user_id,
				500
			);
		}

		return $this->send_success([
			'user' => $user_id,
			'time' => current_time('mysql'),
		]);
	}
}
