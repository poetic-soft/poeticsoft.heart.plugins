<?php

namespace Poeticsoft\Heart\API;

use Poeticsoft\Heart\API\Endpoints\System;
use Poeticsoft\Heart\API\Endpoints\Mail;
use Poeticsoft\Heart\API\Endpoints\Content;
use Poeticsoft\Heart\API\Endpoints\Voice;
use Poeticsoft\Heart\API\Endpoints\Prompts;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Clase API_Manager
 */
class API {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'init_endpoints' ] );
    }

    /**
     * Instancia y registra todos los controladores de endpoints.
     */
    public function init_endpoints() {
        $controllers = [
            new System(),
            new Mail(),
            new Content(),
            new Voice(),
            new Prompts(),
        ];
        foreach ( $controllers as $controller ) {
            $controller->register_routes();
        }
	}
}