<?php

namespace Poeticsoft\Heart\Prompts;

use WP_Error;
use Poeticsoft\Heart\AI;

if (!defined('ABSPATH')) {
	exit;
}

class Prompts {

	public function init() {
		add_action('init', [$this, 'register_cpt']);
		add_action('init', [$this, 'register_taxonomy']);
		add_action('init', [$this, 'ensure_default_terms'], 20);
		add_filter('allowed_block_types_all', [$this, 'restrict_blocks'], 10, 2);
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);

		// Un solo hook maestro para abortar la creación del término antes de que se inserte
		add_filter('pre_insert_term', [$this, 'prevent_new_root_terms'], 10, 2);

		// Initialize Markdown helper
		AI::get(Markdown::class)->init();
	}

	/**
	 * Evitar la creación de nuevas categorías raíz (top-level).
	 */
	public function prevent_new_root_terms($term, $taxonomy) {
		if (AI::PREFIX . 'prompt_type' !== $taxonomy) {
			return $term;
		}

		// Permitir la creación inicial de las ramas maestras
		if (in_array($term, ['System', 'User'], true)) {
			return $term;
		}

		$parent = -1; // Valor por defecto que indica "no definido"

		// 1. Detección en entorno clásico (wp-admin/edit-tags.php o wp-admin/post.php)
		if (isset($_POST['parent'])) {
			$parent = (int) $_POST['parent'];
		} elseif (isset($_POST['tax_input'][$taxonomy])) {
			// A veces se envía en un array si es desde el quick edit o similar, pero típicamente parent no va ahí.
		}

		// 2. Detección en entorno REST API (Gutenberg)
		if (defined('REST_REQUEST') && REST_REQUEST) {
			// En peticiones REST de creación de términos, el parent suele venir en el body JSON
			$json_data = json_decode(file_get_contents('php://input'), true);
			if (is_array($json_data) && isset($json_data['parent'])) {
				$parent = (int) $json_data['parent'];
			} elseif (isset($_REQUEST['parent'])) {
				$parent = (int) $_REQUEST['parent'];
			}
		}

		// 3. Evaluación final
		// Si parent es 0 (explícitamente raíz en REST) o -1 (explícitamente raíz en clásico o no encontrado)
		if (0 === $parent || -1 === $parent) {
			return new WP_Error(
				'invalid_term_hierarchy', 
				__('No se pueden crear nuevas categorías de primer nivel. Deben ser subcategorías de "System" o "User".', 'poeticsoft-heart-ai') 
			);
		}

		return $term;
	}

	/**
	 * Encolar scripts para el editor de bloques.
	 */
	public function enqueue_editor_assets() {
		$screen = get_current_screen();
		if (!$screen || AI::PREFIX . 'prompt' !== $screen->post_type) {
			return;
		}

		$plugin_url = plugin_dir_url(dirname(__FILE__));
		
		wp_enqueue_script(
			AI::PLUGIN_SLUG . '-exclusive-taxonomy',
			$plugin_url . 'assets/js/exclusive-taxonomy.js',
			['wp-data', 'wp-editor', 'wp-i18n'],
			'1.0.0',
			true
		);

		wp_enqueue_script(
			AI::PLUGIN_SLUG . '-prompt-optimizer',
			$plugin_url . 'assets/js/prompt-optimizer.js',
			['wp-plugins', 'wp-edit-post', 'wp-components', 'wp-element', 'wp-data', 'wp-i18n', 'wp-blocks'],
			'1.0.0',
			true
		);

		// Pasar configuración de ramas al JS
		wp_localize_script(AI::PLUGIN_SLUG . '-exclusive-taxonomy', 'pshPromptConfig', [
			'system_id' => $this->get_term_id_by_name('System'),
			'user_id'   => $this->get_term_id_by_name('User'),
			'term_map'  => $this->get_taxonomy_branch_map(),
		]);
	}

	/**
	 * Obtener ID de un término por su nombre.
	 */
	private function get_term_id_by_name($name) {
		$term = get_term_by('name', $name, AI::PREFIX . 'prompt_type');
		return $term ? $term->term_id : 0;
	}

	/**
	 * Generar un mapa de cada término ID a su raíz correspondiente (System o User).
	 */
	private function get_taxonomy_branch_map() {
		$terms = get_terms([
			'taxonomy'   => AI::PREFIX . 'prompt_type',
			'hide_empty' => false,
		]);

		$map = [];
		$system_root = $this->get_term_id_by_name('System');
		$user_root   = $this->get_term_id_by_name('User');

		foreach ($terms as $term) {
			$root = $this->find_root_parent($term->term_id);
			if ($root === $system_root || $root === $user_root) {
				$map[$term->term_id] = $root;
			}
		}

		return $map;
	}

	/**
	 * Buscar el ancestro raíz de un término.
	 */
	private function find_root_parent($term_id) {
		$ancestors = get_ancestors($term_id, AI::PREFIX . 'prompt_type');
		if (empty($ancestors)) {
			return $term_id; // Es una raíz
		}
		return end($ancestors); // El último ancestro es la raíz
	}

	/**
	 * Registrar el Custom Post Type 'psh_prompt'.
	 */
	public function register_cpt() {
		$labels = [
			'name'               => 'Prompts',
			'singular_name'      => 'Prompt',
			'menu_name'          => 'AI Prompts',
			'add_new'            => 'Añadir Nuevo',
			'add_new_item'       => 'Añadir Nuevo Prompt',
			'edit_item'          => 'Editar Prompt',
			'new_item'           => 'Nuevo Prompt',
			'view_item'          => 'Ver Prompt',
			'search_items'       => 'Buscar Prompts',
			'not_found'          => 'No se han encontrado prompts',
			'not_found_in_trash' => 'No hay prompts en la papelera',
		];

		$args = [
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true, // Obligatorio para Gutenberg
			'query_var'           => true,
			'rewrite'             => ['slug' => 'prompt'],
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_icon'           => 'dashicons-format-aside',
			'supports'            => ['title', 'editor', 'revisions'],
			'taxonomies'          => [AI::PREFIX . 'prompt_type'],
		];

		register_post_type('prompt', $args);
	}

	/**
	 * Registrar la taxonomía 'psh_prompt_type'.
	 */
	public function register_taxonomy() {
		$labels = [
			'name'              => 'Tipos de Prompt',
			'singular_name'     => 'Tipo de Prompt',
			'search_items'      => 'Buscar Tipos',
			'all_items'         => 'Todos los Tipos',
			'parent_item'       => 'Tipo Padre',
			'parent_item_colon' => 'Tipo Padre:',
			'edit_item'         => 'Editar Tipo',
			'update_item'       => 'Actualizar Tipo',
			'add_new_item'      => 'Añadir Nuevo Tipo',
			'new_item_name'     => 'Nombre del Nuevo Tipo',
			'menu_name'         => 'Tipos de Prompt',
		];

		$args = [
			'hierarchical'      => true, // Comportamiento de categoría
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_rest'      => true,
			'rewrite'           => ['slug' => 'prompt-type'],
		];

		register_taxonomy(AI::PREFIX . 'prompt_type', [AI::PREFIX . 'prompt'], $args);
	}

	/**
	 * Asegurar que existen los términos 'System' y 'User'.
	 */
	public function ensure_default_terms() {
		if (!taxonomy_exists(AI::PREFIX . 'prompt_type')) {
			return;
		}

		$terms = ['System', 'User'];

		foreach ($terms as $term) {
			if (!term_exists($term, AI::PREFIX . 'prompt_type')) {
				wp_insert_term($term, AI::PREFIX . 'prompt_type');
			}
		}
	}

	/**
	 * Restringir los tipos de bloques permitidos en el editor para el CPT psh_prompt.
	 */
	public function restrict_blocks($allowed_block_types, $editor_context) {
		// Verificar si el post actual es de tipo psh_prompt
		if (AI::PREFIX . 'prompt' === $editor_context->post->post_type) {
			return [
				'core/paragraph',
				'core/heading',
				'core/list',
				'core/list-item',
				'core/code',
				'core/quote',
				'core/separator',
			];
		}

		return $allowed_block_types;
	}
}
