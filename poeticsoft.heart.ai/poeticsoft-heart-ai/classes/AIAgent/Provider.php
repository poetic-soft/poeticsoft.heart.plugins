<?php

namespace Poeticsoft\Heart\AIAgent;

interface Provider {
    
    /**
     * Configura el proveedor con las credenciales y modelos necesarios.
     */
    public function set_config(array $config);

    /**
     * Devuelve el identificador único del proveedor (ej: 'gemini', 'openai').
     */
    public function get_name(): string;

    /**
     * Ejecuta una petición a la IA con soporte para caché y streaming.
     *
     * @param array    $params {
     *     @type string $user_prompt      Mensaje del usuario.
     *     @type string $system_prompt    Instrucciones de sistema.
     *     @type array  $context_parts    Partes adicionales de contexto.
     *     @type string $cache_name       ID de caché existente (opcional).
     *     @type array  $output_schema    Esquema de salida JSON (opcional).
     * }
     * @param callable $callback Función para manejar el streaming.
     * @return string ID de caché (nuevo o existente).
     */
    public function execute(array $params, callable $callback);
}
