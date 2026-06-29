<?php

namespace Poeticsoft\Heart\Base;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Base\Admin\Admin;
use Poeticsoft\Heart\Base\Frontend\Frontend;
use Poeticsoft\Heart\Base\Rest\Rest;
use Poeticsoft\Heart\Base\Database\Database;
use Poeticsoft\Heart\Base\Languages\Languages;

class Base {
	
	const VERSION = '0.0.0';

	const PLUGIN_ID      = 'base'; 
	const PLUGIN_NAME    = 'Poeticsoft Heart Base';
	const PLUGIN_SLUG    = 'poeticsoft-heart-' . self::PLUGIN_ID;
	const TEXT_DOMAIN    = 'poeticsoft-heart-' . self::PLUGIN_ID;
	const PREFIX         = 'poeticsoft_heart_' . self::PLUGIN_ID . '_';
	const API_NAMESPACE  = 'poeticsoft/heart/' . self::PLUGIN_ID . '/v1';
    const PLUGIN_DIR     =  WP_PLUGIN_DIR . '/' . self::PLUGIN_SLUG;
    const PLUGIN_URL     =  WP_PLUGIN_URL . '/' . self::PLUGIN_SLUG ; 

	private static $services = [];

	public Admin $admin;
	public Frontend $frontend;
	public Rest $rest;
	public Database $database;
	public Languages $languages;

	private static $instance = null;

	public static function instance(Heart $heart_class) {
		if (is_null(self::$instance)) {
			self::$instance = new self();
			self::$instance->init($heart_class);
		}
		return self::$instance;
	}

	public function __construct() {
		
		$this->admin = new Admin;
		$this->frontend = new Frontend;
		$this->rest = new Rest;
		$this->database = new Database;
		$this->languages = new Languages;
	}

	public static function get($class) {
		if (! isset(self::$services[$class])) {
			self::$services[$class] = new $class();
		}
		return self::$services[$class];
	}

	private function init(Heart $heart_class) {
		
		$heart_class->add_plugin(self::class);
	}
}

