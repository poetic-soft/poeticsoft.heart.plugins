<?php

namespace Poeticsoft\Heart\ORG\Admin\User;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Admin\Page\Page;
use WP_Error;

class User {

	function create_new_user($email, $commercial_name) {

		$e = new WP_Error();

		if (!is_email($email)) {
			$e->add(
				'email_invalid', 
				'El correo electrónico no es válido.'
			);
		}
		if (email_exists($email)) {
			$e->add(
				'email_exists', 
				'El correo electrónico ya está registrado.'
			);
		}
		if (empty($commercial_name)) {
			$e->add(
				'commercial_name_empty',
				'El nombre comercial no puede estar vacío.'
			);
		}

		if(
			!is_email($email)
			||
			email_exists($email)
			||
			empty($commercial_name)
		) {

			return $e;
		}

		$slug_comercial = sanitize_title($commercial_name);

		$temp_login = 'tmp_' . time() . '_' . wp_generate_password(5, false);

		$user_id = wp_insert_user([
			'user_login'   => $temp_login,
			'user_email'   => $email,
			'display_name' => $commercial_name,
			'user_nicename'=> $slug_comercial,
			'role'         => 'editor',
		]);

		if (is_wp_error($user_id)) {

			$user_id->add(
				'user_not_created', 
				'No se ha podido crear el usuario.'
			);
			return $user_id;
		}

		$definitive_login = "poeticsoft_org_user_{$user_id}";
		global $wpdb;
		$wpdb->update(
			$wpdb->users,
			['user_login' => $definitive_login],
			['ID' => $user_id]
		);
		clean_user_cache($user_id);

		$home_page_id = ORG::get(Page::class)->create_user_root_page(
			$user_id,
			$commercial_name,
			$slug_comercial
		);

		if (is_wp_error($home_page_id)) {

			$home_page_id->add(
				'user_root_page_not_created', 
				'No se ha podido crear la página principal del usuario.'
			);

			wp_delete_user($user_id);
			return $home_page_id;
		}
		
		if($home_page_id === 0) {

			$e->add(
				'user_root_page_not_created', 
				'No se ha podido crear la página principal del usuario.'
			);

			wp_delete_user($user_id);
			return $home_page_id;
		}

		$user_root_page_id_meta_key = ORG::PREFIX . 'root_page_id';
		update_user_meta($user_id, $user_root_page_id_meta_key, $home_page_id);

		wp_new_user_notification($user_id, null, 'both');

		return $user_id;
	}
}
