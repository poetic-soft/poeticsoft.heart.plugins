<?php
/**
 * Plugin Name: Poeticsoft Heart Base
 * Plugin URI:  https://poeticsoft.com
 * Description: High-performance WordPress boilerplate with conditional loading and modern architecture.
 * Version:     1.0.0
 * Author:      Poeticsoft
 * Author URI:  https://poeticsoft.com
 * License:     GPL2
 * Text Domain: poeticsoft-heart-base
 * Domain Path: /languages
 */

if (! defined('ABSPATH')) {
	exit;
}

// Load Composer Autoloader.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use Poeticsoft\Heart\Base\Base;
use Poeticsoft\Heart\Base\Database\Database;

/**
 * Initialize the plugin.
 */
add_action('plugins_loaded', function() {
	Base::instance();
});

/**
 * Activation Hook.
 */
register_activation_hook(__FILE__, function() {
	Base::get(Database::class)->install();
});
