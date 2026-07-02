<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Rest\AbstractEndpoint;
use WP_Error;

class Mail extends AbstractEndpoint {

	public function get_routes() {
		return [
			'/v1/mail/test' => [
				'methods'  => 'GET',
				'callback' => [$this, 'test'],
				'auth'     => self::AUTH_PUBLIC,
			]
		];
	}

	public function test($request) {

		$e = new WP_Error();		

		$email = $request->get_param('email');

		if(!$email) {
			
			$e->add(
				'not_email_provided',
				'No se ha proporcionado email'
			);
		}

        $smtp_use_option_name = Heart::PLUGIN_PREFIX . 'smtp_use';
		$smtp_use = get_option($smtp_use_option_name);

		if(!$smtp_use) {
			
			$e->add(
				'not_using_smtp',
				'No estamos usando el SMTP propio'
			);
		}

        $smtp_test_recipient_option_name = Heart::PLUGIN_PREFIX . 'smtp_test_recipient';
		$smtp_test_recipient = get_option($smtp_test_recipient_option_name);

		if(
			!$smtp_test_recipient
			||
			empty($smtp_test_recipient)
		) {
			
			$e->add(
				'not_test_email_provided',
				'No hay email de test configurado'
			);
		}

		if(
			!$email
			&&
			(
				!$smtp_test_recipient
				||
				empty($smtp_test_recipient)
			)	
		) {	

			return $this->send_error(
				$e,
				400,
			);
		}	

		if($smtp_test_recipient) {
			
			$email = $smtp_test_recipient;
		}

		

		$mail_sent = wp_mail(
			$email,
			sprintf(__('%1$s - Prueba del servicio SMTP', Heart::TEXT_DOMAIN), Heart::PLUGIN_NAME),
			sprintf(__('Correo electrónico de %1$s enviado el %2$s', Heart::TEXT_DOMAIN), Heart::PLUGIN_NAME, current_time('mysql'))
		);

		if($mail_sent) {

			return $this->send_success([
				'result' => 'sent',
			]);

		} else {

			$e->add(
				'email_not_sent',
				'No se ha enviado el mail'
			);

			return $this->send_error(
				$e,
				400,
			);
		}
	}
}