<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\AdminPage;
use Poeticsoft\Heart\Campus;

/**
 * SMTP Settings Page.
 */
class Smtp extends AdminPage
{

    protected function define_page_props()
    {
        $this->slug       = Campus::PREFIX . 'smtp';
        $this->menu_title = __('Mail & SMTP', Campus::TEXT_DOMAIN);
        $this->page_title = __('Mail & SMTP Configuration', Campus::TEXT_DOMAIN);

        $this->settings = [
            // --- Server Configuration ---
            [
                'key'         => 'smtp_use',
                'field_type'  => 'boolean',
                'title'       => __('Usar configuracion de smtp', Campus::TEXT_DOMAIN),
                'description' => __('Activar para enviar mails desde esta cuenta.', Campus::TEXT_DOMAIN),
                'value'       => false,
                'type'        => 'checkbox',
                'section'     => 'server_settings',
            ],
            [
                'key'         => 'smtp_test_recipient',
                'field_type'  => 'text',
                'title'       => __('Mail de pruebas', Campus::TEXT_DOMAIN),
                'description' => __('Los mails de pruebas se enviarán a esta dirección.', Campus::TEXT_DOMAIN),
                'value'       => '',
                'type'        => 'text',
                'section'     => 'server_settings',
            ],
            [
                'key'         => 'smtp_host',
                'field_type'  => 'text',
                'title'       => __('Mail Host', Campus::TEXT_DOMAIN),
                'description' => __('SMTP server host address.', Campus::TEXT_DOMAIN),
                'value'       => 'smtp.mail.ovh.net',
                'type'        => 'text',
                'section'     => 'server_settings',
            ],
            [
                'key'         => 'smtp_port',
                'field_type'  => 'int',
                'title'       => __('Mail Port', Campus::TEXT_DOMAIN),
                'description' => __('Common ports: 25, 465, 587.', Campus::TEXT_DOMAIN),
                'value'       => 465,
                'type'        => 'number',
                'section'     => 'server_settings',
            ],
            [
                'key'         => 'smtp_secure',
                'field_type'  => 'text',
                'title'       => __('SMTP Secure', Campus::TEXT_DOMAIN),
                'value'       => 'ssl',
                'type'        => 'select',
                'section'     => 'server_settings',
                'options'     => [
                    ['label' => __('None', Campus::TEXT_DOMAIN), 'value' => 'none'],
                    ['label' => 'SSL', 'value' => 'ssl'],
                    ['label' => 'TLS', 'value' => 'tls'],
                ],
            ],

            // --- Authentication ---
            [
                'key'         => 'smtp_user',
                'field_type'  => 'text',
                'title'       => __('Mail Username', Campus::TEXT_DOMAIN),
                'value'       => 'partners@poeticsoft.com',
                'type'        => 'text',
                'section'     => 'auth_settings',
                'section_title' => __('Authentication', Campus::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_pass',
                'field_type'  => 'text', // Sanitized as text but rendered as password
                'title'       => __('Mail Password', Campus::TEXT_DOMAIN),
                'value'       => 'JsAU8)0987654',
                'type'        => 'password',
                'section'     => 'auth_settings',
            ],

            // --- Sender Info ---
            [
                'key'         => 'smtp_from_email',
                'field_type'  => 'email',
                'title'       => __('Mail From', Campus::TEXT_DOMAIN),
                'value'       => 'partners@poeticsoft.com',
                'type'        => 'email',
                'section'     => 'sender_settings',
                'section_title' => __('Sender Configuration', Campus::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_from_name',
                'field_type'  => 'text',
                'title'       => __('Mail From Name', Campus::TEXT_DOMAIN),
                'value'       => 'Poeticsoft',
                'type'        => 'text',
                'section'     => 'sender_settings',
            ],
        ];
    }
}
