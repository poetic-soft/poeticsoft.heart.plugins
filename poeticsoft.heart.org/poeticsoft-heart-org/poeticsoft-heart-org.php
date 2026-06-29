<?php
/**
 * Plugin Name: Poeticsoft Heart ORG
 * Plugin URI:  https://poeticsoft.com
 * Description: High-performance WordPress boilerplate with conditional loading and modern architecture.
 * Version:     1.0.0
 * Author:      Poeticsoft
 * Author URI:  https://poeticsoft.com
 * License:     GPL2
 * Text Domain: poeticsoft-heart-org
 * Domain Path: /languages
 */

if (! defined('ABSPATH')) {
	exit;
}

// Load Composer Autoloader.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Database\Database;

/**
 * Initialize the plugin.
 */
add_action('plugins_loaded', function() {
	ORG::instance();
});

/**
 * Activation Hook.
 */
register_activation_hook(__FILE__, function() {
	ORG::get(Database::class)->install();
});
