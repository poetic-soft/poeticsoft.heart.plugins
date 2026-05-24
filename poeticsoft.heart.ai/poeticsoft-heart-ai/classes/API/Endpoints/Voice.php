<?php

namespace Poeticsoft\Heart\API\Endpoints;

use WP_REST_Request;
use WP_REST_Response;
use Exception;

class Voice extends Endpoint {

    public function __construct() {
        $this->namespace = 'psh/v1';
    }

    public function register_routes() {
        register_rest_route( $this->namespace, '/voice/stream', [
            'methods'             => 'POST',
            'callback'            => [ $this, 'stream_response' ],
            'permission_callback' => [ $this, 'check_public_permissions' ],
        ]);
    }

    /**
     * Valida permisos para el acceso público al stream de voz.
     */
    public function check_public_permissions( WP_REST_Request $request ) {
        // Permitir acceso total a administradores/editores
        if ( current_user_can( 'edit_posts' ) ) {
            return true;
        }

        // Para el público general, aplicar límites estrictos:
        // 5 peticiones cada 60 segundos.
        return $this->is_public_request_safe( $request, 'voice_stream', 5, 60 );
    }

    public function stream_response( WP_REST_Request $request ) {
        $input_text = $request->get_param( 'text' );

        // Configurar cabeceras para Streaming
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        global $psh_ai_manager;
        
        if ( ! $psh_ai_manager ) {
            echo "data: " . json_encode(['text' => 'Error: AI Manager no inicializado.']) . "\n\n";
            echo "data: [DONE]\n\n";
            exit;
        }

        // Placeholder context since the old store is gone. 
        // This will be replaced when the dedicated Voice plugin is built.
        $params = [
            'system_prompt' => 'Eres un asistente de voz amable y conciso.',
            'context_parts' => [ 'Esta es una interfaz de voz web de baja latencia.' ],
            'user_prompt'   => $input_text,
            'cache_name'    => $request->get_param( 'cache_name' ) // Support for future voice session caching
        ];

        try {
            $cache_name = $psh_ai_manager->execute( $params, function( $chunk ) {
                // Formato SSE requerido para el streaming de voz
                echo "data: " . json_encode(['text' => $chunk]) . "\n\n";
                if ( ob_get_level() > 0 ) {
                    ob_flush();
                }
                flush();
            });
            
            // Enviar un evento especial con el cache_name para que el frontend lo guarde
            if ( $cache_name ) {
                echo "data: " . json_encode(['cache_name' => $cache_name]) . "\n\n";
            }
        } catch ( Exception $e ) {
            echo "data: " . json_encode(['text' => 'Error de IA: ' . $e->getMessage()]) . "\n\n";
        }

        echo "data: [DONE]\n\n";
        exit;
    }
}
