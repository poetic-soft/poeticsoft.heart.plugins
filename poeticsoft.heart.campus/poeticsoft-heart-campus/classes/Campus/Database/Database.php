<?php

namespace Poeticsoft\Heart\Campus\Database;

use Poeticsoft\Heart\Campus\Campus;

class Database
{
    public function get_db_version_option()
    {
        return Campus::PREFIX . 'db_version';
    }

    const TARGET_VERSION = '1.0.0';

    public function init()
    {
        add_action('admin_init', [$this, 'check_version']);
    }

    public function check_version()
    {
        $current_version = get_option($this->get_db_version_option(), '0.0.0');

        if (version_compare($current_version, self::TARGET_VERSION, '<')) {
            $this->install();
        }
    }

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
    }

    public function uninstall()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
        delete_option($this->get_db_version_option());
    }

    private function get_schema($charset_collate)
    {
        global $wpdb;

        $tables = [];
        $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
        $tables[] = "CREATE TABLE $table_name (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_mail VARCHAR(255) NOT NULL,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY user_mail (user_mail)
		) $charset_collate;";

        $table_name = $wpdb->prefix . Campus::PREFIX . 'last_access';
        $tables[] = "CREATE TABLE $table_name (
			id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_mail VARCHAR(255) NOT NULL,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            last_access_date DATETIME DEFAULT NULL,
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY user_mail (user_mail)
		) $charset_collate;";

        return $tables;
    }
}
