<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Rest\Endpoints\System;
use Poeticsoft\Heart\Rest\Endpoints\Page;

/**
 * REST API Controller (Orchestrator).
 * Manages registration and security for all API sections.
 */
class Rest
{

    /**
     * API Namespace.
     */
    private $namespace;

    /**
     * Registered Endpoint Sections.
     * 
     * @var array
     */
    private $sections = [
        System::class,
        Page::class,
        // Add more endpoint classes here.
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->namespace = Campus::API_NAMESPACE;
    }

    /**
     * Initialize REST registration.
     */
    public function init()
    {
        foreach ($this->sections as $section_class) {
            $this->register_section($section_class);
        }
    }

    /**
     * Register an endpoint section and apply centralized security.
     *
     * @param string $section_class FQDN of the class.
     */
    private function register_section($section_class)
    {
        $instance = Campus::get($section_class);
        $routes   = $instance->get_routes();   

        foreach ($routes as $route => $config) {
            register_rest_route($this->namespace, $route, [
                'methods'             => $config['methods'],
                'callback'            => [$instance, $config['callback']],
                'permission_callback' => function ($request) use ($config) {
                    return $this->check_auth($config['auth'] ?? Endpoint::AUTH_ADMIN);
                },
            ]);
        }
    }

    /**
     * Centralized Authentication Logic.
     *
     * @param string $level The required security level.
     * @return bool|\WP_Error
     */
    private function check_auth($level)
    {
        switch ($level) {
            case Endpoint::AUTH_PUBLIC:
                return true;

            case Endpoint::AUTH_USER:
                return is_user_logged_in();

            case Endpoint::AUTH_ADMIN:
                return current_user_can('manage_options');

            default:
                return false;
        }
    }
}
