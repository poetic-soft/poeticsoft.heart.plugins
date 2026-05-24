<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\AbstractEndpoint;

/**
 * System Endpoint Section.
 * Example of modular API implementation with security levels.
 */
class SystemEndpoint extends AbstractEndpoint
{

    /**
     * Define routes for this section.
     */
    public function get_routes()
    {
        return [
            '/status' => [
                'methods'  => 'GET',
                'callback' => 'get_status',
                'auth'     => self::AUTH_PUBLIC,
            ],
            '/info' => [
                'methods'  => 'GET',
                'callback' => 'get_info',
                'auth'     => self::AUTH_ADMIN,
            ],
        ];
    }

    /**
     * Get Status (Public).
     */
    public function get_status($request)
    {
        return $this->send_success([
            'status' => 'active',
            'time'   => current_time('mysql'),
        ]);
    }

    /**
     * Get System Info (Admin Only).
     */
    public function get_info($request)
    {
        // Simulating an error condition
        if (! defined('ABSPATH')) {
            return $this->send_error('missing_core', 'WordPress core not loaded correctly', 500);
        }

        return $this->send_success([
            'version' => Campus::VERSION,
            'php'     => PHP_VERSION,
            'wp'      => get_bloginfo('version'),
        ]);
    }
}
