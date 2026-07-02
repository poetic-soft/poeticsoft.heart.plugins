<?php
/**
 * Plugin Name: Poeticsoft Heart
 * Plugin URI:  https://poeticsoft.com
 * Description: Orquestator WordPress plugin for the Poeticsoft Heart Ecosystem.
 * Version:     0.0.0
 * Author:      Poeticsoft
 * Author URI:  https://poeticsoft.com
 * License:     GPL2
 * Text Domain: poeticsoft-heart
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
	exit;
}

// Load Composer Autoloader.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use Poeticsoft\Heart\Heart;

/**
 * Initialize the plugins network.
 */
add_action(
	'plugins_loaded', 
	function() {
		Heart::instance();
	}
);
