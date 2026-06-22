<?php

namespace Poeticsoft\Heart\Prompts;

use Poeticsoft\Heart\AI;

if (!defined('ABSPATH')) {
	exit;
}

class Markdown {

	/**
	 * Initialize Markdown hooks.
	 */
	public function init() {
		add_action('save_post_' . AI::PREFIX . 'prompt', [$this, 'generate_markdown_on_save'], 10, 3);
	}

	/**
	 * Hook que se ejecuta al guardar un post de tipo psh_prompt.
	 */
	public function generate_markdown_on_save($post_id, $post, $update) {
		// Evitar ejecuciones innecesarias (autosaves, revisiones)
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (wp_is_post_revision($post_id)) {
			return;
		}

		// Obtener los bloques del contenido
		$blocks = parse_blocks($post->post_content);
		$markdown = '';

		foreach ($blocks as $block) {
			$markdown .= $this->convert_block_to_md($block);
		}

		// Guardar el resultado en el postmeta con el prefijo estandarizado
		update_post_meta($post_id, AI::PREFIX . 'md_content', trim($markdown));
	}

	/**
	 * Convierte un bloque individual a su representación en Markdown.
	 */
	private function convert_block_to_md($block) {
		$name = $block['blockName'];
		$content = isset($block['innerHTML']) ? $this->clean_html($block['innerHTML']) : '';
		
		if (empty($name)) {
			return ""; // Ignorar bloques vacíos o saltos de línea fuera de bloques
		}

		switch ($name) {
			case 'core/paragraph':
				return $content . "\n\n";
			case 'core/heading':
				$level = isset($block['attrs']['level']) ? $block['attrs']['level'] : 2;
				return str_repeat('#', $level) . ' ' . $content . "\n\n";
			case 'core/list':
				return $this->parse_list_block($block) . "\n\n";
			case 'core/code':
				$language = isset($block['attrs']['language']) ? $block['attrs']['language'] : '';
				return "```" . $language . "\n" . $content . "\n```\n\n";
			case 'core/quote':
				return "> " . str_replace("\n", "\n> ", $content) . "\n\n";
			case 'core/separator':
				return "---\n\n";
			default:
				return $content . "\n\n"; // Fallback para bloques desconocidos
		}
	}

	/**
	 * Convierte un bloque de lista de Gutenberg a Markdown.
	 */
	private function parse_list_block($block) {
		$is_ordered = isset($block['attrs']['ordered']) && $block['attrs']['ordered'];
		$inner_blocks = $block['innerBlocks'];
		$markdown = '';
		$counter = 1;

		foreach ($inner_blocks as $li) {
			if ('core/list-item' === $li['blockName']) {
				$item_content = $this->clean_html($li['innerHTML']);
				if ($is_ordered) {
					$markdown .= $counter . ". " . $item_content . "\n";
					$counter++;
				} else {
					$markdown .= "- " . $item_content . "\n";
				}
			}
		}

		return trim($markdown);
	}

	/**
	 * Limpia etiquetas HTML básicas dejando el texto crudo.
	 */
	private function clean_html($html) {
		// Decodificar entidades HTML comunes que puedan venir del editor
		$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
		// Eliminar etiquetas HTML
		return trim(strip_tags($html));
	}
}
