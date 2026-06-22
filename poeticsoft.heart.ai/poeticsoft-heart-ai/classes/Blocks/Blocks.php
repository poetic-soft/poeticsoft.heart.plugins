<?php

namespace Poeticsoft\Heart\Blocks;

use Poeticsoft\Heart\AI;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Blocks {

	/**
	 * Initialize blocks hooks.
	 */
	public function init() {
		add_action('init', [$this, 'register_blocks'], 20);
		add_filter('block_categories_all', [$this, 'add_block_categories'], 9, 2);
	}

	/**
	 * Añade categorías personalizadas al insertador de bloques.
	 *
	 * @param array $categories Categorías existentes.
	 * @param WP_Block_Editor_Context $block_editor_context Contexto del editor.
	 */
	public function add_block_categories($categories, $block_editor_context) {
		$category = [
			'slug'  => 'poeticsoft-heart-ai',
			'title' => __('Poeticsoft Heart AI', 'poeticsoft-heart-ai'),
			'icon'  => 'admin-site',
		];

		if (!is_array($categories)) {
			return [$category];
		}

		array_unshift($categories, $category);
		
		return $categories;
	}

	/**
	 * Registra los bloques personalizados del plugin.
	 */
	public function register_blocks() {

		$blocks_dir = dirname(__DIR__, 2) . '/blocks/';

		if (!is_dir($blocks_dir)) {
			error_log('Plugin Error: Blocks directory not found in ' . $blocks_dir);
			return;
		}

		$blocks = array_diff(scandir($blocks_dir), ['..', '.']);

		foreach ($blocks as $block_name) {
			$block_path = $blocks_dir . $block_name;
			if (is_dir($block_path) && file_exists($block_path . '/block.json')) {
				$result = register_block_type($block_path);				
				if (is_wp_error($result)) {
					error_log('Plugin Error: Failed to register block ' . $block_name . ': ' . $result->get_error_message());
				} 
			}
		}
	}
}
