<?php

/**
 * Plugin Name: Poeticsoft Heart Campus
 * Plugin URI:  https://poeticsoft.com
 * Description: High-performance Wordpress Page Based Campus.
 * Version:     1.0.0
 * Author:      Poeticsoft
 * Author URI:  https://poeticsoft.com
 * License:     GPL2
 * Text Domain: poeticsoft-heart-campus
 * Domain Path: /languages
 */

if (! defined('ABSPATH')) {
    exit;
}

// Load Composer Autoloader.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Database\Database;

/**
 * Initialize the plugin.
 */
add_action('plugins_loaded', function () {
    Campus::instance();
});

/**
 * Activation Hook.
 */
register_activation_hook(__FILE__, function () {
    Campus::get(Database::class)->install();
});

/**
 * Deactivation Hook.
 */
register_deactivation_hook(__FILE__, function () {
    // Logic to run on deactivation (e.g., flush rewrite rules).
});
