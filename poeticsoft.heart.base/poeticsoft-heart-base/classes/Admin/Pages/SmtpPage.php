<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\AbstractPage;
use Poeticsoft\Heart\Base;

/**
 * SMTP Settings Page.
 */
class SmtpPage extends AbstractPage {

	protected function define_page_props() {
		$this->slug       = Base::PREFIX . 'smtp';
		$this->menu_title = __( 'Mail & SMTP', Base::TEXT_DOMAIN );
		$this->page_title = __( 'Mail & SMTP Configuration', Base::TEXT_DOMAIN );

		$this->settings = [
			// --- Server Configuration ---
			[
				'key'         => 'smtp_host',
				'field_type'  => 'text',
				'title'       => __( 'Mail Host', Base::TEXT_DOMAIN ),
				'description' => __( 'SMTP server host address.', Base::TEXT_DOMAIN ),
				'value'       => 'smtp.mail.ovh.net',
				'type'        => 'text',
				'section'     => 'server_settings',
			],
			[
				'key'         => 'smtp_port',
				'field_type'  => 'int',
				'title'       => __( 'Mail Port', Base::TEXT_DOMAIN ),
				'description' => __( 'Common ports: 25, 465, 587.', Base::TEXT_DOMAIN ),
				'value'       => 465,
				'type'        => 'number',
				'section'     => 'server_settings',
			],
			[
				'key'         => 'smtp_secure',
				'field_type'  => 'text',
				'title'       => __( 'SMTP Secure', Base::TEXT_DOMAIN ),
				'value'       => 'ssl',
				'type'        => 'select',
				'section'     => 'server_settings',
				'options'     => [
					[ 'label' => __( 'None', Base::TEXT_DOMAIN ), 'value' => 'none' ],
					[ 'label' => 'SSL', 'value' => 'ssl' ],
					[ 'label' => 'TLS', 'value' => 'tls' ],
				],
			],

			// --- Authentication ---
			[
				'key'         => 'smtp_user',
				'field_type'  => 'text',
				'title'       => __( 'Mail Username', Base::TEXT_DOMAIN ),
				'value'       => 'partners@poeticsoft.com',
				'type'        => 'text',
				'section'     => 'auth_settings',
				'section_title' => __( 'Authentication', Base::TEXT_DOMAIN ),
			],
			[
				'key'         => 'smtp_pass',
				'field_type'  => 'text', // Sanitized as text but rendered as password
				'title'       => __( 'Mail Password', Base::TEXT_DOMAIN ),
				'value'       => 'JsAU8)0987654',
				'type'        => 'password',
				'section'     => 'auth_settings',
			],

			// --- Sender Info ---
			[
				'key'         => 'smtp_from_email',
				'field_type'  => 'email',
				'title'       => __( 'Mail From', Base::TEXT_DOMAIN ),
				'value'       => 'partners@poeticsoft.com',
				'type'        => 'email',
				'section'     => 'sender_settings',
				'section_title' => __( 'Sender Configuration', Base::TEXT_DOMAIN ),
			],
			[
				'key'         => 'smtp_from_name',
				'field_type'  => 'text',
				'title'       => __( 'Mail From Name', Base::TEXT_DOMAIN ),
				'value'       => 'Poeticsoft',
				'type'        => 'text',
				'section'     => 'sender_settings',
			],
		];
	}
}
