<?php

namespace Poeticsoft\Heart\Campus\Admin\Pages;

use Poeticsoft\Heart\Campus\Campus;
use Poeticsoft\Heart\Campus\Admin\AdminPage;

class Smtp extends AdminPage
{
    protected function define_page_props()
    {
        $this->slug       = Campus::PREFIX . 'smtp';
        $this->menu_title = __('Correo y SMTP', Campus::TEXT_DOMAIN);
        $this->page_title = __('Configuración de Correo y SMTP', Campus::TEXT_DOMAIN);

        $this->settings = [

            [
                'key'         => 'smtp_use',
                'field_type'  => 'boolean',
                'title'       => __('Usar configuracion de smtp', Campus::TEXT_DOMAIN),
                'description' => __('Activar para enviar mails desde esta cuenta.', Campus::TEXT_DOMAIN),
                'value'       => false,
                'type'        => 'checkbox',
                'section_title' => __('Servidor de SMTP', Campus::TEXT_DOMAIN),
                'section'     => 'server_smtp',
            ],
            [
                'key'         => 'smtp_test_recipient',
                'field_type'  => 'text',
                'title'       => __('Mail de pruebas', Campus::TEXT_DOMAIN),
                'description' => __('Los mails de pruebas se enviarán a esta dirección.', Campus::TEXT_DOMAIN),
                'value'       => '',
                'type'        => 'text',
                'section'     => 'server_smtp',
            ],
            [
                'key'         => 'smtp_host',
                'field_type'  => 'text',
                'title'       => __('Servidor de Correo (Host)', Campus::TEXT_DOMAIN),
                'description' => __('Dirección del host del servidor SMTP.', Campus::TEXT_DOMAIN),
                'value'       => 'smtp.mail.ovh.net',
                'type'        => 'text',
                'section'     => 'server_smtp',
            ],
            [
                'key'         => 'smtp_port',
                'field_type'  => 'int',
                'title'       => __('Puerto de Correo', Campus::TEXT_DOMAIN),
                'description' => __('Puertos comunes: 25, 465, 587.', Campus::TEXT_DOMAIN),
                'value'       => 465,
                'type'        => 'number',
                'section'     => 'server_smtp',
            ],
            [
                'key'         => 'smtp_secure',
                'field_type'  => 'text',
                'title'       => __('Seguridad SMTP', Campus::TEXT_DOMAIN),
                'value'       => 'ssl',
                'type'        => 'select',
                'section'     => 'server_smtp',
                'options'     => [
                    ['label' => __('Ninguna', Campus::TEXT_DOMAIN), 'value' => 'none'],
                    ['label' => 'SSL', 'value' => 'ssl'],
                    ['label' => 'TLS', 'value' => 'tls'],
                ],
            ],

            [
                'key'         => 'smtp_user',
                'field_type'  => 'text',
                'title'       => __('Usuario de Correo', Campus::TEXT_DOMAIN),
                'value'       => 'partners@poeticsoft.com',
                'type'        => 'text',
                'section'     => 'auth_settings',
                'section_title' => __('Autenticación', Campus::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_pass',
                'field_type'  => 'text',
                'title'       => __('Contraseña de Correo', Campus::TEXT_DOMAIN),
                'value'       => 'JsAU8)0987654',
                'type'        => 'password',
                'section'     => 'auth_settings',
            ],

            [
                'key'         => 'smtp_from_email',
                'field_type'  => 'email',
                'title'       => __('Correo Remitente (From)', Campus::TEXT_DOMAIN),
                'value'       => 'partners@poeticsoft.com',
                'type'        => 'email',
                'section'     => 'sender_settings',
                'section_title' => __('Configuración del Remitente', Campus::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_from_name',
                'field_type'  => 'text',
                'title'       => __('Nombre del Remitente', Campus::TEXT_DOMAIN),
                'value'       => 'Poeticsoft',
                'type'        => 'text',
                'section'     => 'sender_settings',
            ],
        ];
    }
}
