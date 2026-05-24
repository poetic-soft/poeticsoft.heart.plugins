<?php

namespace Poeticsoft\Heart\Database;

use Poeticsoft\Heart\Campus;

/**
 * Database Orchestrator.
 * Handles table creation and migrations using dbDelta.
 */
class Database
{

    /**
     * Get the database version option name dynamically.
     * 
     * @return string
     */
    public function get_db_version_option()
    {
        return Campus::PREFIX . 'db_version';
    }

    /**
     * Target database version.
     */
    const TARGET_VERSION = '1.0.0';

    /**
     * Initialize the database manager.
     */
    public function init()
    {
        // Check if we need to run migrations.
        add_action('admin_init', [$this, 'check_version']);
    }

    /**
     * Check if the database version is up to date.
     */
    public function check_version()
    {
        $current_version = get_option($this->get_db_version_option(), '0.0.0');

        if (version_compare($current_version, self::TARGET_VERSION, '<')) {
            $this->install();
        }
    }

    /**
     * Run the installation/migration.
     */
    public function install()
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();
        $tables          = $this->get_schema($charset_collate);

        foreach ($tables as $sql) {
            dbDelta($sql);
        }

        update_option($this->get_db_version_option(), self::TARGET_VERSION);

        Campus::log('Database migrated to version ' . self::TARGET_VERSION, 'success');
    }

    /**
     * Define the database schema.
     * 
     * @param string $charset_collate
     * @return array List of SQL statements.
     */
    private function get_schema($charset_collate)
    {
        global $wpdb;

        $tables = [];

        // Example Table: Logs (prefix with corporate identity).
        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $tables[] = "CREATE TABLE $table_name (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_mail VARCHAR(255) NOT NULL,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            last_access_date DATETIME DEFAULT NULL,
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY user_mail (user_mail)
		) $charset_collate;";

        // Add more tables here as the plugin grows.

        return $tables;
    }
}
