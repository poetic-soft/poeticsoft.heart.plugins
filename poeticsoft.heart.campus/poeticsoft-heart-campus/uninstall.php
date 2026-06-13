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

/**
 * Use the Database Orchestrator to clean up.
 */
try {
    \Poeticsoft\Heart\Campus::get(\Poeticsoft\Heart\Database\Database::class)->uninstall();
} catch (\Exception $e) {
    // Silent fail in uninstall to avoid breaking WordPress deletion.
}
