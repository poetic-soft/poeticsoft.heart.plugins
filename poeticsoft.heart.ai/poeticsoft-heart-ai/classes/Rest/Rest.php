<?php

namespace Poeticsoft\Heart\Rest;

use Poeticsoft\Heart\AI;
use Poeticsoft\Heart\Rest\Endpoints\System;
use Poeticsoft\Heart\Rest\Endpoints\Mail;
use Poeticsoft\Heart\Rest\Endpoints\Content;
use Poeticsoft\Heart\Rest\Endpoints\Voice;
use Poeticsoft\Heart\Rest\Endpoints\Prompts;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Clase Rest (formerly API)
 */
class Rest {

	/**
	 * Initialize the REST endpoints.
	 */
	public function init() {
		$this->init_endpoints();
	}

	/**
	 * Instancia y registra todos los controladores de endpoints.
	 */
	public function init_endpoints() {
		$controllers = [
			AI::get(System::class),
			AI::get(Mail::class),
			AI::get(Content::class),
			AI::get(Voice::class),
			AI::get(Prompts::class),
		];
		foreach ($controllers as $controller) {
			$controller->register_routes();
		}
	}
}
