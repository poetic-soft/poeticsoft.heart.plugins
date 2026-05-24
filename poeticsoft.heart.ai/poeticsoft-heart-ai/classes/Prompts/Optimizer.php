<?php

namespace Poeticsoft\Heart\Prompts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;

class Optimizer {

	/**
	 * Ruta base del store interno del optimizador.
	 *
	 * @var string
	 */
	private $store_path;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->store_path = dirname( __DIR__ ) . '/ai-store/optimizer/';
	}

	/**
	 * Ejecuta la optimización de un prompt.
	 *
	 * @param array $data {
	 *     @type string $title      Título actual del prompt.
	 *     @type string $content    Contenido actual (Gutenberg blocks o Markdown).
	 *     @type string $branch     Nombre de la rama principal ('System' o 'User').
	 *     @type string $cache_name Nombre de la caché existente (opcional, para feedback loop).
	 * }
	 * @return array Respuesta estructurada del optimizador, incluyendo el cache_name.
	 * @throws Exception Si falta el AICore o falla la generación.
	 */
	public function optimize( $data ) {
		global $psh_ai_manager;

		// Verificar que el motor AICore está disponible
		if ( ! isset( $psh_ai_manager ) || ! is_object( $psh_ai_manager ) ) {
			// Intentar instanciarlo si la clase existe (fallback)
			if ( class_exists( '\Poeticsoft\Heart\AICore\AI_Manager' ) ) {
				$psh_ai_manager = new \Poeticsoft\Heart\AICore\AI_Manager();
			} else {
				throw new Exception( 'El plugin Poeticsoft Heart AICore no está activo.' );
			}
		}

		$params = $this->build_payload( $data );

		// Variable para almacenar la respuesta completa (sin streaming)
		$full_response = '';
		
		// Llamamos al motor. Nos devuelve el ID del caché generado o utilizado.
		$returned_cache_name = $psh_ai_manager->execute( $params, function( $chunk ) use ( &$full_response ) {
			$full_response .= $chunk;
		} );

		if ( empty( $full_response ) ) {
			throw new Exception( 'La IA no devolvió ninguna respuesta.' );
		}

		// Intentar limpiar la respuesta (extraer el JSON de entre cualquier texto extra)
		$start_pos = strpos( $full_response, '{' );
		$end_pos   = strrpos( $full_response, '}' );

		if ( $start_pos !== false && $end_pos !== false ) {
			$clean_json = substr( $full_response, $start_pos, $end_pos - $start_pos + 1 );
		} else {
			$clean_json = trim( $full_response );
		}
		
		$parsed = json_decode( $clean_json, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			throw new Exception( 'Error al parsear el JSON de la IA: ' . json_last_error_msg() . '. Respuesta cruda: ' . substr($full_response, 0, 100) );
		}

		// Inyectamos el cache_name en la respuesta para que el frontend lo guarde
		$parsed['cache_name'] = $returned_cache_name;

		return $parsed;
	}

	/**
	 * Construye el payload leyendo los archivos del store.
	 *
	 * @param array $data Datos del usuario.
	 * @return array Payload listo para el AICore.
	 */
	private function build_payload( $data ) {
		$payload = [
			'system_prompt' => '',
			'context_parts' => [],
			'user_prompt'   => '',
			'output_schema' => []
		];

		// Si tenemos cache_name, lo añadimos al payload
		$has_cache = ! empty( $data['cache_name'] );
		if ( $has_cache ) {
			$payload['cache_name'] = $data['cache_name'];
		}

		// Solo cargamos el peso (System y Parts) si NO hay caché
		if ( ! $has_cache ) {
			// 1. Cargar System Prompt
			$sys_file = $this->store_path . 'SYSTEM_PROMPT.md';
			if ( file_exists( $sys_file ) ) {
				$payload['system_prompt'] = file_get_contents( $sys_file );
			}

			// 2. Cargar Parts (Contexto)
			$parts_dir = $this->store_path . 'parts/';
			if ( is_dir( $parts_dir ) ) {
				$files = glob( $parts_dir . '*.md' );
				foreach ( $files as $file ) {
					$payload['context_parts'][] = file_get_contents( $file );
				}
			}
		}

		// 3. Cargar Output Schema (Siempre se necesita para que la salida siga siendo JSON estructurado)
		$schema_file = $this->store_path . 'OUTPUT_SCHEMA.json';
		if ( file_exists( $schema_file ) ) {
			$schema_json = file_get_contents( $schema_file );
			$payload['output_schema'] = json_decode( $schema_json, true ) ?: [];
		}

		// 4. Cargar y preparar User Prompt
		// Si estamos en un loop (con caché), podríamos usar un prompt de continuación,
		// pero por simplicidad de la plantilla, usamos el mismo e inyectamos el nuevo contenido.
		$user_file = $this->store_path . 'USER_PROMPT.md';
		if ( file_exists( $user_file ) ) {
			$user_template = file_get_contents( $user_file );
			
			// Reemplazar marcadores
			$replacements = [
				'{{prompt_title}}'   => $data['title'] ?? 'Sin título',
				'{{prompt_content}}' => $data['content'] ?? '',
				'{{prompt_branch}}'  => $data['branch'] ?? 'Desconocida',
			];
			
			$payload['user_prompt'] = str_replace( 
				array_keys( $replacements ), 
				array_values( $replacements ), 
				$user_template 
			);
		}

		return $payload;
	}
}
