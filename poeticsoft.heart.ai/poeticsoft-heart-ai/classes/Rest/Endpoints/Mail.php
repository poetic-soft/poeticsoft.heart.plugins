<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Admin\Integrations\SMTP;

if (!defined('ABSPATH')) {
	exit;
}

use Poeticsoft\Heart\Rest\Endpoint;
use WP_REST_Request;

class Mail extends Endpoint {

	/**
	 * Registra las rutas de correo.
	 */
	public function register_routes() {
		register_rest_route($this->get_full_namespace(), '/mail/test', [
			'methods'             => 'GET',
			'callback'            => [$this, 'test_mail_callback'],
			'permission_callback' => '__return_true', // En producción usar validación real.
		]);
	}

	/**
	 * Callback para el test de envío de correo.
	 */
	public function test_mail_callback(WP_REST_Request $request) {
		$to      = 'partners@poeticsoft.com';
		$subject = 'Test SMTP REST API (New Plugin)';
		$message = 'Este es un correo de prueba enviado mediante el nuevo plugin Poeticsoft Heart API.';
		$headers = ['Content-Type: text/html; charset=UTF-8'];

		$sent = wp_mail($to, $subject, $message, $headers);

		if ($sent) {
			return $this->response(true, 'Correo enviado exitosamente.');
		} else {
			// Acceder a la variable estática de SMTP en la nueva ubicación.
			$error_msg = SMTP::$last_error ?? 'Error desconocido';
			return $this->response(false, 'Error al enviar el correo.', ['debug' => $error_msg], 500);
		}
	}
}
