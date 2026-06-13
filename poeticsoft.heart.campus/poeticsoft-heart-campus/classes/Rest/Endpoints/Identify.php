<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Utils\Utils
;

class Identify extends Endpoint
{
    public function get_routes()
    {
        return [
            '/identify' => [
                'methods'  => 'POST',
                'callback' => 'identify',
                'auth'     => self::AUTH_USER,
            ]
        ];
    }

    public function identify($request)
    {

        $email = $request->get_param('email');
        $url = $request->get_param('url');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->send_error(
                'IDENTIFY_MAIL_INVALID',
                __('El correo no está registrado', Campus::TEXT_DOMAIN),
                $status = 404
            );
        } else {
            global $wpdb;
            $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
            $access = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT
            id,
            user_mail
            FROM {$table_name}
            WHERE user_mail = %s",
                    $email
                )
            );

            if (count($access)) {
                $link = Campus::get(Access::class)->send_magick_link($email, $url);

                return $this->send_success([

                'time'   => current_time('mysql'),
                ]);
            } else {
                return $this->send_error(
                    'IDENTIFY_MAIL_NOTFOUND',
                    __('El correo no está registrado', Campus::TEXT_DOMAIN),
                    $status = 404
                );
            }
        }
    }
}
