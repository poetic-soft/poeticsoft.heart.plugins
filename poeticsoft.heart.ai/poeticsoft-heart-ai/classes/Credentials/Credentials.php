<?php

namespace Poeticsoft\Heart\Credentials;

use Poeticsoft\Heart\Credentials\Admin;
use Poeticsoft\Heart\Credentials\Pages\Communications;
use Poeticsoft\Heart\Credentials\Pages\AI;
use Poeticsoft\Heart\Credentials\Pages\Instagram;
use Poeticsoft\Heart\Credentials\Integrations\SMTP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Clase Credentials_Manager
 */
class Credentials {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_admin();
		$this->init_pages();
		$this->init_integrations();
	}

	/**
	 * Inicializa la interfaz de administración.
	 */
	private function init_admin() {
		new Admin();
	}

	/**
	 * Inicializa las pages.
	 */
	private function init_pages() {        
		new Communications();
		new AI();
		new Instagram();
	}

	/**
	 * Inicializa las integraciones (SMTP, etc).
	 */
	private function init_integrations() {
		new SMTP();
	}
}
