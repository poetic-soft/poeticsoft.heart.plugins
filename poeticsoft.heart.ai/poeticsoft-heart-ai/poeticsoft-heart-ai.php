<?php
/**
 * Plugin Name: Poeticsoft Heart AI
 * Description: Poeticsoft Heart Ecosystem Core AI Plugin.
 * Version: 2.1.0
 * Author: Poeticsoft
 * Text Domain: poeticsoft-heart-ai
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * composer dump-autoload -o
 */

require_once __DIR__ . '/vendor/autoload.php';

new \Poeticsoft\Heart\AI();
