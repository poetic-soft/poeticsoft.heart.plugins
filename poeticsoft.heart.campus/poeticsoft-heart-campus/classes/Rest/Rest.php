<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Rest\Endpoints\Page;
use Poeticsoft\Heart\Rest\Endpoints\Access;
use Poeticsoft\Heart\Rest\Endpoints\Identify;
use Poeticsoft\Heart\Utils\Utils;

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
        Page::class,
        Access::class,
        Identify::class,
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
            Utils::log($this->namespace . $route);
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
        // 1. Allow public endpoints immediately.
        if ($level === Endpoint::AUTH_PUBLIC) {
            return true;
        }

        $nonce = null;
        if (isset($_REQUEST['_wpnonce'])) {
            $nonce = $_REQUEST['_wpnonce'];
        } elseif (isset($_SERVER['HTTP_X_WP_NONCE'])) {
            $nonce = $_SERVER['HTTP_X_WP_NONCE'];
        }

        $is_nonce_valid = $nonce ? wp_verify_nonce($nonce, 'wp_rest') : false;

        switch ($level) {
            
            case Endpoint::AUTH_USER:
                return is_user_logged_in() || $is_nonce_valid;

            case Endpoint::AUTH_ADMIN:
                return current_user_can('manage_options') || ($is_nonce_valid && current_user_can('manage_options'));

            default:
                return false;
        }
    }
}
