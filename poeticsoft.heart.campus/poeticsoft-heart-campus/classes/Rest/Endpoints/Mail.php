<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Utils\Utils;

class Mail extends Endpoint
{
    public function get_routes()
    {
        return [
            '/mail/test' => [
                'methods'  => 'GET',
                'callback' => 'test',
                'auth'     => self::AUTH_PUBLIC,
            ]
        ];
    }


    public function test($request)
    {
        $smtp_use = get_option(Campus::PREFIX . 'smtp_use');
        if (!$smtp_use) {
            return $this->send_error(
                'SMTP_NOT_ENABLED',
                __('La opción SMTP no está activada en la configuración.', Campus::TEXT_DOMAIN),
                400
            );
        }

        $smtp_test_recipient_option_name = Campus::PREFIX . 'smtp_test_recipient';
        $smtp_test_recipient = get_option($smtp_test_recipient_option_name);

        if (empty($smtp_test_recipient)) {
            return $this->send_error(
                'NO_RECIPIENT_SPECIFIED',
                __('No se ha configurado un destinatario de pruebas (smtp_test_recipient).', Campus::TEXT_DOMAIN),
                400
            );
        }

        Utils::log("Sending test mail to: " . $smtp_test_recipient);

        $mail_sent = wp_mail(
            $smtp_test_recipient,
            sprintf(__('%1$s - Prueba del servicio SMTP', Campus::TEXT_DOMAIN), Campus::PLUGIN_NAME),
            sprintf(__('Correo electrónico de %1$s enviado el %2$s', Campus::TEXT_DOMAIN), Campus::PLUGIN_NAME, current_time('mysql'))
        );

        if ($mail_sent) {
            return $this->send_success([
                'message'   => __('Mensaje enviado correctamente', Campus::TEXT_DOMAIN),
                'recipient' => $smtp_test_recipient,
                'time'      => current_time('mysql'),
            ]);
        } else {
            return $this->send_error(
                'MAIL_NOT_SENT',
                __('El mail no se ha podido enviar. Revisa el archivo debug.log para ver los detalles del error SMTP.', Campus::TEXT_DOMAIN),
                500
            );
        }
    }
}
