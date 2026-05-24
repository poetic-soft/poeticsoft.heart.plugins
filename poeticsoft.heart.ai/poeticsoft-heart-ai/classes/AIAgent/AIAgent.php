<?php

namespace Poeticsoft\Heart\AIAgent;

use Poeticsoft\Heart\AIAgent\Gemini;
use Exception;

class AIAgent {
	/**
	 * Lista de proveedores registrados.
	 * @var array
	 */
	private $providers = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register_provider( new Gemini() );
	}

	/**
	 * Registra un nuevo proveedor de IA.
	 */
	public function register_provider( $provider ) {
		$this->providers[ $provider->get_name() ] = $provider;
	}

	/**
	 * Ejecuta una petición a la IA.
	 * 
	 * @param array    $params   Parámetros de la petición (deben venir ya resueltos).
	 * @param callable $callback Callback para el streaming.
	 * @return string ID del caché (si se utilizó).
	 */
	public function execute( array $params, callable $callback ) {
		$provider_name = get_option( 'psh_ai_active_provider', 'gemini' );
		$provider      = $this->get_provider( $provider_name );

		if ( ! $provider ) {
			throw new Exception( "Proveedor de IA activo no encontrado o no configurado: {$provider_name}" );
		}

		return $provider->execute( $params, $callback );
	}

	/**
	 * Obtiene un proveedor configurado con los ajustes globales.
	 */
	public function get_provider( $name ) {
		$provider = $this->providers[ $name ] ?? null;
		if ( $provider ) {
			$config = [
				'api_key'   => get_option( 'psh_ai_' . $name . '_key' ),
				'model'     => get_option( 'psh_ai_' . $name . '_model' ),
				'cache_ttl' => get_option( 'psh_ai_cache_ttl', 0 ),
			];
			$provider->set_config( $config );
		}
		return $provider;
	}
}
