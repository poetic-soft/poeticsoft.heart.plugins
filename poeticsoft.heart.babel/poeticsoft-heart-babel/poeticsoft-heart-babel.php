<?php
/**
 * Plugin Name: Poeticsoft Heart Babel
 * Plugin URI:  https://poeticsoft.com
 * Description: RAG sovereign knowledge governance system inside WordPress, integrated with Ollama and ChromaDB.
 * Version:     1.0.0
 * Author:      Poeticsoft
 * Author URI:  https://poeticsoft.com
 * License:     GPL2
 * Text Domain: poeticsoft-heart-babel
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

// Load Composer Autoloader.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
        require_once __DIR__ . '/vendor/autoload.php';
}

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Database\Database;

/**
 * Initialize the plugin.
 */
add_action( 'plugins_loaded', function() {
        Babel::instance();
});

/**
 * Activation Hook.
 */
register_activation_hook( __FILE__, function() {
        Babel::get( Database::class )->install();
} );

/**
 * Deactivation Hook.
 */
register_deactivation_hook( __FILE__, function() {
        wp_clear_scheduled_hook( Babel::PREFIX . 'scan_raw_cron' );
} );
