<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\AI;
use Poeticsoft\Heart\Admin\MainMenu;
use Poeticsoft\Heart\Admin\Pages\AI as AIPage;
use Poeticsoft\Heart\Admin\Pages\Instagram;
use Poeticsoft\Heart\Admin\Integrations\SMTP;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Clase Admin Orchestrator (formerly Credentials_Manager)
 */
class Admin {

	/**
	 * Initialize Admin hooks and sub-components.
	 */
	public function init() {
		$this->init_admin();
		$this->init_pages();
		$this->init_integrations();
	}

	/**
	 * Inicializa la interfaz de administración.
	 */
	private function init_admin() {
		AI::get(MainMenu::class)->init();
	}

	/**
	 * Inicializa las pages.
	 */
	private function init_pages() {   
		AI::get(AIPage::class)->init();
		AI::get(Instagram::class)->init();
	}

	/**
	 * Inicializa las integraciones (SMTP, etc).
	 */
	private function init_integrations() {
		AI::get(SMTP::class)->init();
	}
}
