<?php

namespace Poeticsoft\Heart\ORG;

use Poeticsoft\Heart\ORG\Admin\Admin;
use Poeticsoft\Heart\ORG\Frontend\Frontend;
use Poeticsoft\Heart\ORG\Rest\Rest;
use Poeticsoft\Heart\ORG\Database\Database;
use Poeticsoft\Heart\ORG\Languages\Languages;

class ORG {

	private static $services = [];

	const VERSION = '1.0.0';

	const PLUGIN_ID      = 'org';
	const PLUGIN_NAME    = 'Poeticsoft Heart ORG';
	const PLUGIN_SLUG    = 'poeticsoft-heart-' . self::PLUGIN_ID;
	const TEXT_DOMAIN    = 'poeticsoft-heart-' . self::PLUGIN_ID;
	const PREFIX         = 'poeticsoft_heart_' . self::PLUGIN_ID . '_';
	const API_NAMESPACE  = 'poeticsoft/heart/' . self::PLUGIN_ID . '/v1';
    const PLUGIN_DIR     =  WP_PLUGIN_DIR . '/' . self::PLUGIN_SLUG;
    const PLUGIN_URL     =  WP_PLUGIN_URL . '/' . self::PLUGIN_SLUG ;

	private static $instance = null;

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();

			self::$instance->init();
		}
		return self::$instance;
	}

	private function __construct() {}

	public static function get($class) {
		if (! isset(self::$services[$class])) {
			self::$services[$class] = new $class();
		}
		return self::$services[$class];
	}

	public static function path($relative_path = '') {
		return ORG::PLUGIN_DIR . ($relative_path ? '/' . $relative_path : '');
	}

	public static function url($relative_path = '') {
		return ORG::PLUGIN_URL . ($relative_path ? '/' . $relative_path : '');
	}

	public static function log($message, $level = 'info') {
		$log_file = self::path('debug.log');
		$timestamp = date('Y-m-d H:i:s');

		if (! is_string($message)) {
			$message = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
		}

		$formatted_message = sprintf("[%s] [%s]: %s\n", $timestamp, strtoupper($level), $message);

		file_put_contents($log_file, $formatted_message, FILE_APPEND);
	}

	private function init() {

		if (is_admin()) {
			$this->init_admin();
		}

		add_action(
			'rest_api_init',
			[self::get(Rest::class), 'init']
		);

		if (!is_admin()) {
			$this->init_frontend();
		}

		$this->init_global();
	}

	private function init_admin() {
		add_action('init', function() {
			self::get(Admin::class)->init();
		});

		add_action('admin_init', function() {
			self::get(Updater::class)->init();
		});
	}

	private function init_frontend() {
		add_action('init', function() {
			self::get(Frontend::class)->init();
		});
	}

	private function init_rest() {
		add_action('rest_api_init', function() {
			self::get(Rest::class)->init();
		});
	}

	private function init_global() {

		self::get(Languages::class)->init();

		self::get(Database::class)->init();
	}
}
