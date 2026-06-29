<?php
/**
 * Plugin Name: Poeticsoft Heart Base
 * Plugin URI:  https://poeticsoft.com
 * Description: Base Satelite WordPress plugin for the Poeticsoft Heart Ecosystem.
 * Version:     0.0.0
 * Author:      Poeticsoft
 * Author URI:  https://poeticsoft.com
 * License:     GPL2
 * Text Domain: poeticsoft-heart-base
 * Domain Path: /languages
 */

if (! defined('ABSPATH')) {
	exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use Poeticsoft\Heart\Base\Base;

add_action(
	'poeticsoft_heart_register', 
	function($heart_class) {
		Base::instance($heart_class);
	}
);