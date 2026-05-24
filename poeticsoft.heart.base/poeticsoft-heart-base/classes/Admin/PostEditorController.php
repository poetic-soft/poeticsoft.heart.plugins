<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Base;

/**
 * Post Editor Controller.
 * Handles logic specifically for the post/page editor screen.
 */
class PostEditorController {

	/**
	 * Initialize editor hooks.
	 */
	public function init() {
		Base::log( 'PostEditorController initialized', 'debug' );

		// Register metaboxes, blocks, or editor-specific scripts.
		add_action( 'add_meta_boxes', [ $this, 'add_metaboxes' ] );
		add_action( 'save_post', [ $this, 'save_post_data' ] );
	}

	/**
	 * Add custom metabox.
	 */
	public function add_metaboxes() {
		add_meta_box(
			Base::PREFIX . 'metabox',
			sprintf( __( '%s Options', Base::TEXT_DOMAIN ), Base::PLUGIN_NAME ),
			[ $this, 'render_metabox' ],
			[ 'post', 'page' ],
			'side',
			'default'
		);
	}

	/**
	 * Render metabox content.
	 */
	public function render_metabox( $post ) {
		echo '<p>' . esc_html__( 'Custom options for this content.', Base::TEXT_DOMAIN ) . '</p>';
	}

	/**
	 * Save metabox data.
	 */
	public function save_post_data( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		// Save logic here.
	}
}
