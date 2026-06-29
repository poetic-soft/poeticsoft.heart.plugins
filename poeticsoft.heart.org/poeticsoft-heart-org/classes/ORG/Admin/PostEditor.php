<?php

namespace Poeticsoft\Heart\ORG\Admin;

use Poeticsoft\Heart\ORG\ORG;

class PostEditor {

	public function init() {
		ORG::log('PostEditorController initialized', 'debug');

		add_action('add_meta_boxes', [$this, 'add_metaboxes']);
		add_action('save_post', [$this, 'save_post_data']);
	}

	public function add_metaboxes() {
		add_meta_box(
			ORG::PREFIX . 'metabox',
			sprintf(__('%s Options', ORG::TEXT_DOMAIN), ORG::PLUGIN_NAME),
			[$this, 'render_metabox'],
			['post', 'page'],
			'side',
			'default'
		);
	}

	public function render_metabox($post) {
		echo '<p>' . esc_html__('Custom options for this content.', ORG::TEXT_DOMAIN) . '</p>';
	}

	public function save_post_data($post_id) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	}
}
