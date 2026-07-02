<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Heart;

class Editor {

	public function init() {

		add_action('add_meta_boxes', [$this, 'add_metaboxes']);
		add_action('save_post', [$this, 'save_post']);
	}

	public function add_metaboxes() {

		$metaboxes = [
			[
				'id' => Heart::PLUGIN_SLUG . '-metabox',
				'title' => sprintf(__('%s Options', Heart::TEXT_DOMAIN), Heart::PLUGIN_NAME),
				'render' => [$this, 'render_metabox'],
				'screen' => ['post', 'page'],
				'context' => 'side',
				'priority' => 'default'
			]
		];

		// Plugins metaboxes +

		foreach($metaboxes as $metabox) {

			add_meta_box(
				$metabox['id'],
				$metabox['title'],
				$metabox['render'],			
				$metabox['screen'],				
				$metabox['context'],			
				$metabox['priority']
			);
		}
	}

	public function render_metabox($post) {
		echo '<p>' . esc_html__('Custom options for this content.', Heart::TEXT_DOMAIN) . '</p>';
	}

	public function save_post($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		$save_post = [

		];

		// Plugins save post
		
	}
}
