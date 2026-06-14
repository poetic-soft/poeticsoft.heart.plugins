<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Rest\Endpoints\Page;
use Poeticsoft\Heart\Rest\Endpoints\Access;
use Poeticsoft\Heart\Rest\Endpoints\Identify;
use Poeticsoft\Heart\Rest\Endpoints\Mail;
use Poeticsoft\Heart\Utils\Utils;

class Rest
{
    private $namespace;

    private $sections = [
        Page::class,
        Access::class,
        Identify::class,
        Mail::class,
    ];

    public function __construct()
    {
        $this->namespace = Campus::API_NAMESPACE;
    }

    public function init()
    {
        foreach ($this->sections as $section_class) {
            $this->register_section($section_class);
        }
    }

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

    private function check_auth($level)
    {
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
                return current_user_can('manage_options');

            default:
                return false;
        }
    }
}
