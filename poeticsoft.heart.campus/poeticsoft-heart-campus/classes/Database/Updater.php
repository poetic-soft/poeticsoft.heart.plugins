<?php

namespace Poeticsoft\Heart\Database;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Access;

class Updater
{
    public function get_formatted_access_data()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $actual_data = $wpdb->get_results("SELECT * FROM $table_name");

        $resultado = [];

        foreach ($actual_data as $access) {
            $user_mail = $access->user_mail;
            $access_data = [
                'page_id' => $access->post_id,
                'page_path' => Campus::get(Access::class)->campus_page_path($access->post_id)
            ];

            if (isset($resultado[$user_mail])) {
                $resultado[$user_mail][] = $access_data;
            } else {
                $resultado[$user_mail] = [$access_data];
            }
        }

        return $resultado;
    }

    public function refresh_access_data()
    {
        global $wpdb;

        $access_data = $this->get_access_data();
        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $wpdb->query("TRUNCATE TABLE $table_name");
        foreach ($access_data as $access) {
            $wpdb->insert(
                $table_name,
                $access,
                [
                    '%s',
                    '%d',
                ]
            );
        }

        return $access_data;
    }

    public function get_access_data()
    {
        $access_by_option_name = CAMPUS::PREFIX . 'access_by';
        $access_by = get_option($access_by_option_name);
        $data = [];

        switch ($access_by) {
            case 'directus':
                $data = $this->get_directus_access_data();

                break;

            case 'mailrelay':
                break;
        }

        return $data;
    }

    public function get_directus_access_data()
    {
        $directus_access_url_option_name = CAMPUS::PREFIX . 'directus_access_url';
        $directus_access_url = get_option($directus_access_url_option_name);
        $directus_access_token_option_name = CAMPUS::PREFIX . 'directus_access_token';
        $directus_access_token = get_option($directus_access_token_option_name);

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $directus_access_token,
                'Content-Type'  => 'application/json',
            ],
            'timeout' => 30,
        ];

        $response = wp_remote_get(
            $directus_access_url,
            $args
        );

        $data = [];

        if (!is_wp_error($response)) {
            $http_code = wp_remote_retrieve_response_code($response);
            if ($http_code !== 200) {
                $data = [];
            } else {
                $body = wp_remote_retrieve_body($response);
                $directus_data = json_decode($body);

                foreach ($directus_data->data as $row) {
                    $email_value = sanitize_email(trim($row->humano_id->correo));
                    $post_ids_value = trim($row->wp_post_ids);
                    $post_ids = $post_ids_value == '' ?
                        []
                        :
                        explode(' ', $post_ids_value);
                    $post_ids = array_map(
                        function ($post_id) {
                            return trim($post_id);
                        },
                        $post_ids
                    );

                    if (count($post_ids)) {
                        foreach ($post_ids as $post_id) {
                            $access = [
                                'user_mail' => $email_value,
                                'post_id' => $post_id
                            ];

                            $data[] = $access;
                        }
                    } else {
                        $access = [
                            'user_mail' => $email_value,
                            'post_id' => 0
                        ];

                        $data[] = $access;
                    }
                }
            }
        }

        return $data;
    }
}
