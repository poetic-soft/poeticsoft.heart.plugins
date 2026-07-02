<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Admin\AbstractPage;
use Poeticsoft\Heart\View\View;

class Smtp extends AbstractPage
{
    protected function define_page_props()
    {
        $this->slug       = Heart::PLUGIN_SLUG . '-smtp';
        $this->menu_title = __('Correo y SMTP', Heart::TEXT_DOMAIN);
        $this->page_title = __('Configuración de Correo y SMTP', Heart::TEXT_DOMAIN);

        $this->settings = [

            [
                'key'         => 'smtp_use',
                'type'        => 'boolean',
                'field_type'  => 'checkbox',
                'value'       => false,
                'section'     => 'server_smtp',
                'section_title' => __('Servidor de SMTP', Heart::TEXT_DOMAIN),
                'title'       => __('Usar configuracion de smtp', Heart::TEXT_DOMAIN),
                'description' => __('Activar para enviar mails desde esta cuenta.', Heart::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_test_recipient',
                'type'        => 'string',
                'field_type'  => 'email',
                'value'       => '',
                'section'     => 'server_smtp',
                'title'       => __('Mail de pruebas', Heart::TEXT_DOMAIN),
                'description' => __('Los mails de pruebas se enviarán a esta dirección.', Heart::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_host',
                'type'        => 'string',
                'field_type'  => 'text',
                'value'       => 'smtp.mail.ovh.net',
                'section'     => 'server_smtp',
                'title'       => __('Servidor de Correo (Host)', Heart::TEXT_DOMAIN),
                'description' => __('Dirección del host del servidor SMTP.', Heart::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_port',
                'type'        => 'number',
                'field_type'  => 'number',
                'value'       => 465,
                'section'     => 'server_smtp',
                'title'       => __('Puerto de Correo', Heart::TEXT_DOMAIN),
                'description' => __('Puertos comunes: 25, 465, 587.', Heart::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_secure',
                'type'        => 'string',
                'field_type'  => 'select',
                'options'     => [
                    ['label' => __('Ninguna', Heart::TEXT_DOMAIN), 'value' => 'none'],
                    ['label' => 'SSL', 'value' => 'ssl'],
                    ['label' => 'TLS', 'value' => 'tls'],
                ],
                'value'       => 'ssl',
                'section'     => 'server_smtp',
                'title'       => __('Seguridad SMTP', Heart::TEXT_DOMAIN),
            ],

            [
                'key'         => 'smtp_user',
                'type'        => 'string',
                'field_type'  => 'text',
                'value'       => 'partners@poeticsoft.com',
                'section'     => 'auth_settings',
                'section_title' => __('Autenticación', Heart::TEXT_DOMAIN),
                'title'       => __('Usuario de Correo', Heart::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_pass',
                'type'        => 'string',
                'field_type'  => 'password',
                'value'       => 'JsAU8)0987654',
                'section'     => 'auth_settings',
                'title'       => __('Contraseña de Correo', Heart::TEXT_DOMAIN),
            ],

            [
                'key'         => 'smtp_from_email',
                'type'        => 'string',
                'field_type'  => 'email',
                'value'       => 'partners@poeticsoft.com',
                'section'     => 'sender_settings',
                'section_title' => __('Configuración del Remitente', Heart::TEXT_DOMAIN),
                'title'       => __('Correo Remitente (From)', Heart::TEXT_DOMAIN),
            ],
            [
                'key'         => 'smtp_from_name',
                'type'        => 'string',
                'field_type'  => 'text',
                'value'       => 'Poeticsoft Heart',
                'section'     => 'sender_settings',
                'title'       => __('Nombre del Remitente', Heart::TEXT_DOMAIN),
            ],
        ];
    }

	protected function handle_action($action) {
		if ('send_mail' === $action) {

            $mail_sent = wp_mail(
                $_REQUEST['email'],
                sprintf(__('%1$s - Prueba del servicio SMTP', Heart::TEXT_DOMAIN), Heart::PLUGIN_NAME),
                sprintf(__('Correo electrónico de %1$s enviado el %2$s', Heart::TEXT_DOMAIN), Heart::PLUGIN_NAME, current_time('mysql'))
            );

            $type = $mail_sent ?
            'success'
            :			
            'error';

            $message = $mail_sent ?
            __('Mail enviado.', Heart::TEXT_DOMAIN)
            :			
            __('Error enviando mail.', Heart::TEXT_DOMAIN);

			add_action(
				'admin_notices', 
				function() use ($type, $message){
					Heart::get(View::class)->render(
                        'admin/notice', 
                        [
                            'type'    => $type,
                            'message' => $message
                        ]
                    );
				}
			);
		}
	}

	public function render_content() {

		$this->render_view('admin/generic-settings');

        $smtp_use_option_name = Heart::PLUGIN_PREFIX . 'smtp_use';
		$smtp_use = get_option($smtp_use_option_name);
        $smtp_test_recipient_option_name = Heart::PLUGIN_PREFIX . 'smtp_test_recipient';
		$smtp_test_recipient = get_option($smtp_test_recipient_option_name);
		$data = [
			'smtp_test_recipient' => $smtp_test_recipient,
			'smtp_use' => $smtp_use
		];

		$this->render_view(
			'admin/mail', 
			$data
		);
	}
}
