<?php

namespace Poeticsoft\Heart\Base;

use Poeticsoft\Heart\Base\Admin\Admin;
use Poeticsoft\Heart\Base\Frontend\Frontend;
use Poeticsoft\Heart\Base\Rest\Rest;
use Poeticsoft\Heart\Base\Database\Database;
use Poeticsoft\Heart\Base\Languages\Languages;

/**
 * Main Plugin Class (Base).
 * Acts as a Service Container and Context Router.
 */
class Base {

	/**
	 * Stores service instances.
	 *
	 * @var array
	 */
	private static $services = [];
	/**
	 * Plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Plugin Identity Constants.
	 * Modify these when cloning the scaffolding for a new plugin.
	 */
	const PLUGIN_ID      = 'base'; // Unique identifier for this plugin (e.g., 'base', 'ai-agent', 'crm').
	const PLUGIN_NAME    = 'Poeticsoft Heart Base';
	const PLUGIN_SLUG    = 'poeticsoft-heart-' . self::PLUGIN_ID;
	const TEXT_DOMAIN    = 'poeticsoft-heart-' . self::PLUGIN_ID;
	const PREFIX         = 'poeticsoft_heart_' . self::PLUGIN_ID . '_';
	const API_NAMESPACE  = 'poeticsoft/heart/' . self::PLUGIN_ID . '/v1';
    const PLUGIN_DIR     =  WP_PLUGIN_DIR . '/' . self::PLUGIN_SLUG;
    const PLUGIN_URL     =  WP_PLUGIN_URL . '/' . self::PLUGIN_SLUG ; 

	/**
	 * Plugin instance.
	 *
	 * @var Base
	 */
	private static $instance = null;

	/**
	 * Get class instance (Singleton).
	 */
	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
			self::log(self::PLUGIN_NAME . ' Initialized', 'success');
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {}

	/**
	 * Service Container: Get or Instantiate a service.
	 *
	 * @param string $class FQDN of the class.
	 * @return object
	 */
	public static function get($class) {
		if (! isset(self::$services[$class])) {
			self::$services[$class] = new $class();
		}
		return self::$services[$class];
	}

	/**
	 * Get absolute path to plugin folder or file.
	 *
	 * @param string $relative_path Path relative to plugin root.
	 * @return string
	 */
	public static function path($relative_path = '') {
		return Base::PLUGIN_DIR . ($relative_path ? '/' . $relative_path : '');
	}

	/**
	 * Get public URL to plugin folder or file.
	 *
	 * @param string $relative_path Path relative to plugin root.
	 * @return string
	 */
	public static function url($relative_path = '') {
		return Base::PLUGIN_URL . ($relative_path ? '/' . $relative_path : '');
	}

	/**
	 * Custom Logger.
	 * Writes to a debug.log file in the plugin root for development purposes.
	 *
	 * @param mixed  $message The message or data to log.
	 * @param string $level   Log level (info, debug, error, success).
	 */
	public static function log($message, $level = 'info') {
		$log_file = self::path('debug.log');
		$timestamp = date('Y-m-d H:i:s');

		if (! is_string($message)) {
			$message = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
		}

		$formatted_message = sprintf("[%s] [%s]: %s\n", $timestamp, strtoupper($level), $message);

		file_put_contents($log_file, $formatted_message, FILE_APPEND);
	}

	/**
	 * Initialize the plugin based on context.
	 */
	private function init() {
		// 1. Admin Context
		if (is_admin()) {
			$this->init_admin();
		}

		// 2. REST API Context
		if (defined('REST_REQUEST') && REST_REQUEST) {
			$this->init_rest();
		}

		// 3. Frontend Context (Always loaded if not admin/rest, but also handles common hooks)
		if (! is_admin() && ! (defined('REST_REQUEST') && REST_REQUEST)) {
			$this->init_frontend();
		}

		// 4. Global Hooks (Always loaded)
		$this->init_global();
	}

	/**
	 * Load Admin components.
	 */
	private function init_admin() {
		add_action('init', function() {
			self::get(Admin::class)->init();
		});

		// Lazy-loaded Updater: only in admin and on admin_init.
		add_action('admin_init', function() {
			self::get(Updater::class)->init();
		});
	}

	/**
	 * Load Frontend components.
	 */
	private function init_frontend() {
		add_action('init', function() {
			self::get(Frontend::class)->init();
		});
	}

	/**
	 * Load REST API components.
	 */
	private function init_rest() {
		add_action('rest_api_init', function() {
			self::get(Rest::class)->init();
		});
	}

	/**
	 * Load Global components.
	 */
	private function init_global() {
		// Initialize Languages Manager.
		self::get(Languages::class)->init();

		// Initialize Database Manager.
		self::get(Database::class)->init();
	}
}

