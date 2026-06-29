<?php

namespace Poeticsoft\Heart\ORG\Admin\Page;

use Poeticsoft\Heart\ORG\ORG;
use WP_Error;

class Page {

	function create_user_root_page(
		$author_id,
		$title,
		$slug = null,
		$status = 'draft'
	) {

        if(!$author_id || !$title) {

            return new WP_Error(
				'error_crear_pagina',
				'No se pudo crear la página, falta autor o titulo.'
			);
        }

		$blog_id = get_current_blog_id();

		if (!is_user_member_of_blog($author_id, $blog_id)) {

            return new WP_Error(
				'error_crear_pagina',
				'El usuario no existe o no pertenece a este sitio.'
			);
		}

        $slug = $slug ? $slug : sanitize_title($title);

		$content = '<!-- wp:paragraph -->
			<p>Bienvenido a la home de ' . $title . '</p>
		<!-- /wp:paragraph -->';

		$page_id = wp_insert_post([
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => $status,
			'post_type'    => 'page',
			'post_author'  => $author_id,
			'post_content' => $content,
		]);

		if (is_wp_error($page_id) || $page_id === 0) {
			return new WP_Error('error_crear_pagina', 'No se pudo crear la página Home del cliente.');
		}

		return $page_id;
	}
}
