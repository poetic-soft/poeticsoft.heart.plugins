<?php

namespace Poeticsoft\Heart\Prompts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Markdown {

	public function __construct() {
		add_action( 'save_post_psh_prompt', [ $this, 'generate_markdown_on_save' ], 10, 3 );
	}

	/**
	 * Hook que se ejecuta al guardar un post de tipo psh_prompt.
	 */
	public function generate_markdown_on_save( $post_id, $post, $update ) {
		// Evitar ejecuciones innecesarias (autosaves, revisiones)
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Obtener los bloques del contenido
		$blocks = parse_blocks( $post->post_content );
		$markdown = '';

		foreach ( $blocks as $block ) {
			$markdown .= $this->convert_block_to_md( $block );
		}

		// Guardar el resultado en el postmeta con el prefijo estandarizado
		update_post_meta( $post_id, 'psh_md_content', trim( $markdown ) );
	}

	/**
	 * Convierte un bloque individual a su representación en Markdown.
	 */
	private function convert_block_to_md( $block ) {
		$name = $block['blockName'];
		$content = isset( $block['innerHTML'] ) ? $this->clean_html( $block['innerHTML'] ) : '';
		
		if ( empty( $name ) ) {
			return ""; // Ignorar bloques vacíos o saltos de línea fuera de bloques
		}

		switch ( $name ) {
			case 'core/heading':
				$level = isset( $block['attrs']['level'] ) ? $block['attrs']['level'] : 2;
				return str_repeat( '#', $level ) . ' ' . $content . "\n\n";

			case 'core/paragraph':
				return $content . "\n\n";

			case 'core/list':
				$is_ordered = isset( $block['attrs']['ordered'] ) && $block['attrs']['ordered'];
				$list_md = "";
				
				// Para las listas, necesitamos procesar los bloques internos (core/list-item)
				if ( ! empty( $block['innerBlocks'] ) ) {
					$counter = 1;
					foreach ( $block['innerBlocks'] as $item ) {
						$item_text = $this->clean_html( $item['innerHTML'] );
						$prefix = $is_ordered ? "{$counter}." : "-";
						$list_md .= "{$prefix} {$item_text}\n";
						$counter++;
					}
				}
				return $list_md . "\n";

			case 'core/code':
				// Intentar obtener el contenido del bloque directamente si innerHTML no es suficiente
				$code_content = $content;
				// El bloque core/code suele envolver el texto en <pre><code>...</code></pre>
				return "```\n" . $code_content . "\n```\n\n";

			case 'core/quote':
				return "> " . $content . "\n\n";

			case 'core/separator':
				return "---\n\n";

			default:
				return "";
		}
	}

	/**
	 * Limpia etiquetas HTML básicas dejando solo el texto plano.
	 */
	private function clean_html( $html ) {
		// Eliminar etiquetas HTML pero mantener el contenido
		$text = wp_strip_all_tags( $html );
		// Decodificar entidades HTML (ej: &amp; -> &)
		return html_entity_decode( $text );
	}
}
