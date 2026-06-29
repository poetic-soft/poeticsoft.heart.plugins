<?php

namespace Poeticsoft\Heart;

use Poeticsoft\Heart\Admin\Admin;

class Heart {

	private static $instance = null;
	private static $services = [];
	
	public $plugin_classes = [];
	
	const VERSION = '0.0.0';

	const PLUGIN_ID      = 'heart'; 
	const PLUGIN_NAME    = 'Poeticsoft Heart';
	const PLUGIN_SLUG    = 'poeticsoft-' . self::PLUGIN_ID;
	const TEXT_DOMAIN    = self::PLUGIN_SLUG;
	const PLUGIN_PREFIX  = 'poeticsoft_' . self::PLUGIN_ID . '_';
	const API_NAMESPACE  = 'poeticsoft/' . self::PLUGIN_ID . '/v1';	
    const PLUGIN_DIR     =  WP_PLUGIN_DIR . '/' . self::PLUGIN_SLUG;
    const PLUGIN_URL     =  WP_PLUGIN_URL . '/' . self::PLUGIN_SLUG ; 

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	private function __construct() {

		do_action('poeticsoft_heart_register', $this); // Sync plugins

		$this->init();
	}

	public static function get($class) {
		if (!isset(self::$services[$class])) {
			self::$services[$class] = new $class();
		}
		return self::$services[$class];
	}

	public static function log($message, $level = 'info') {

		$log_file = WP_CONTENT_DIR . '/' . 'heart.log';
		$timestamp = date('Y-m-d H:i:s');

		if (!is_string($message)) {
			$message = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
		}

		$formatted_message = sprintf("[%s] [%s]: %s\n", $timestamp, strtoupper($level), $message);

		file_put_contents($log_file, $formatted_message, FILE_APPEND);
	}

	public function add_plugin($plugin_class) {
		$this->plugin_classes[] = $plugin_class;
	}

	private function init() {		

		if (is_admin()) {
			$this->init_admin();
		}
	}

	private function init_admin() {

		self::get(Admin::class)->init();
	}
}
