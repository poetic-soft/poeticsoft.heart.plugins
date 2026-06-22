<?php

/**
 * Uninstall Plugin.
 *
 * This file is called when the user clicks "Delete" in the WordPress plugins panel.
 * It cleans up all data stored by the plugin.
 */

// If uninstall not called from WordPress, exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Load Autoloader if exists.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Database\Database;

/**
 * Use the Database Orchestrator to clean up.
 */
try {
    Campus::get(Database::class)->uninstall();
} catch (\Exception $e) {
    // Silent fail in uninstall to avoid breaking WordPress deletion.
}
