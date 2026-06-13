<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Utils\Utils
;

class Access extends Endpoint
{
    public function get_routes()
    {
        return [
            '/access' => [
                'methods'  => 'POST',
                'callback' => 'access',
                'auth'     => self::AUTH_PUBLIC,
            ]
        ];
    }

    public function access($request)
    {

        $directus_log_access_url_option_name = Campus::PREFIX . 'directus_log_access_url';
        $directus_log_access_url = get_option($directus_log_access_url_option_name);
        $directus_log_access_token_option_name = Campus::PREFIX . 'directus_log_access_token';
        $directus_log_access_token = get_option($directus_log_access_token_option_name);

        $body = $request->get_params();

        $args = [
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => false,
        'headers'     => [
          'Content-Type'  => 'application/json',
          'Authorization' => 'Bearer ' . $directus_log_access_token,
        ],
        'body'        => json_encode($body),
        'cookies'     => [],
        ];



        $response = wp_remote_post(
            $directus_log_access_url,
            $args
        );

        Utils::log($response);
    }
}
