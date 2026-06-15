<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Utils\Utils;

class Access extends Endpoint
{
    public function get_routes()
    {
        return [
            '/access' => [
                'methods'  => 'POST',
                'callback' => 'access',
                'auth'     => self::AUTH_PUBLIC,
            ],
            // '/update' => [ // Migracion de Estado de acceso a páginas
            //     'methods'  => 'GET',
            //     'callback' => 'update',
            //     'auth'     => self::AUTH_PUBLIC,
            // ]
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
    }

    public function update($request)
    {

        $access_meta_key = Campus::PREFIX . 'access';
        $campus_page_ids = Utils::all_pages_in_campus_ids();

        $campus_page_access = array_map(
            function ($page_id) use ($access_meta_key, $campus_page_ids) {

                $changed = false;

                $page_price_type = $type = get_post_meta(
                    $page_id,
                    'poeticsoft_content_payment_assign_price_type',
                    true
                );

                $new_meta_value = $page_price_type == 'free' ? 'abierta' : 'restringida';
                $changed = update_post_meta(
                    $page_id,
                    $access_meta_key,
                    $new_meta_value
                );

                return [
                    'id' => $page_id,
                    'price_type' => $page_price_type,
                    'new_value' => $new_meta_value,
                    'changed' => $changed
                ];
            },
            $campus_page_ids
        );

        return $this->send_success([
            'campus_page_access' => $campus_page_access,
            'time' => current_time('mysql'),
        ]);
    }
}
