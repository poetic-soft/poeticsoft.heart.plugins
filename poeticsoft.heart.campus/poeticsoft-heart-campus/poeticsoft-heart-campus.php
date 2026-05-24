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
// composer dump-autoload -o
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Initialize the plugin.
 */
add_action('plugins_loaded', function () {
    \Poeticsoft\Heart\Campus::instance();
});

/**
 * Activation Hook.
 */
register_activation_hook(__FILE__, function () {
    \Poeticsoft\Heart\Campus::get(\Poeticsoft\Heart\Database\Database::class)->install();
});
