<?php

namespace Poeticsoft\Heart\AIAgent;

use Gemini as GeminiAgent;
use Gemini\Data\Content;
use Poeticsoft\Heart\AIAgent\Provider;
use Exception;

class Gemini implements Provider {
	private $client;
	private $config = [];

	/**
	 * Configura el cliente.
	 */
	public function set_config( array $config ) {
		$this->config = $config;
		if ( ! empty( $config['api_key'] ) ) {
			$this->client = GeminiAgent::client( $config['api_key'] );
		}
	}

	public function get_name(): string {
		return 'gemini';
	}

	/**
	 * Ejecuta la petición utilizando Context Caching si es posible.
	 */
	public function execute( array $params, callable $callback ) {
		if ( ! $this->client ) {
			throw new Exception( "Cliente Gemini no configurado." );
		}

		$model_name    = $this->config['model'] ?? 'gemini-1.5-flash';
		$ttl           = (int) ( $this->config['cache_ttl'] ?? 0 );
		$cache_name    = $params['cache_name'] ?? null;
		$user_prompt   = $params['user_prompt'] ?? '';
		$system_prompt = $params['system_prompt'] ?? '';
		$context_parts = $params['context_parts'] ?? [];

		// Caso 1: Usar caché existente
		if ( $ttl > 0 && ! empty( $cache_name ) ) {
			$model = $this->client->generativeModel( model: $model_name )
				->withCachedContent( $cache_name );
		}
		// Caso 2: Crear nuevo caché (si hay TTL y contenido suficiente)
		elseif ( $ttl > 0 && ( ! empty( $system_prompt ) || ! empty( $context_parts ) ) ) {
			
			$cache = $this->client->cachedContents()->create(
				model: 'models/' . $model_name,
				systemInstruction: Content::parse( $system_prompt ),
				parts: array_map( fn($p) => Content::parse($p), $context_parts ),
				ttl: $ttl . 's',
				displayName: 'PSH Dynamic Cache'
			);
			
			$cache_name = $cache->name;
			$model = $this->client->generativeModel( model: $model_name )
				->withCachedContent( $cache_name );
		}
		// Caso 3: Petición normal sin caché
		else {
			$model = $this->client->generativeModel( model: $model_name );
			if ( ! empty( $system_prompt ) ) {
				$model = $model->withSystemInstruction( Content::parse( $system_prompt ) );
			}
		}

		// Configurar esquema de salida si existe
		$request_options = [];
		if ( ! empty( $params['output_schema'] ) ) {
			$request_options = [
				'responseMimeType' => 'application/json',
				'responseSchema'   => $params['output_schema'],
			];
		}

		$stream = $model->streamGenerateContent( $user_prompt, $request_options );

		foreach ( $stream as $response ) {
			$text = $response->text();
			if ( $text ) {
				$callback( $text );
			}
		}

		return $cache_name;
	}

	/**
	 * Métodos legacy eliminados para cumplir con la nueva interfaz simple.
	 */
	public function set_system_instruction( $instruction ) {}
	public function generate_content( string $prompt ): string { return ''; }
	public function stream_chat( string $prompt, array $context_data, callable $callback ) {}
}
