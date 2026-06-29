<?php

namespace Poeticsoft\Heart\ORG\Database;

use Poeticsoft\Heart\ORG\ORG;

class Database {

	public function get_db_version_option() {
		return ORG::PREFIX . 'db_version';
	}

	const TARGET_VERSION = '1.0.0';

	public function init() {

		add_action('admin_init', [$this, 'check_version']);
	}

	public function check_version() {
		$current_version = get_option($this->get_db_version_option(), '0.0.0');

		if (version_compare($current_version, self::TARGET_VERSION, '<')) {
			$this->install();
		}
	}

	public function install() {

		return; /* Check for table use */
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();
		$tables          = $this->get_schema($charset_collate);

		foreach ($tables as $sql) {
			dbDelta($sql);
		}

		update_option($this->get_db_version_option(), self::TARGET_VERSION);

		ORG::log('Database migrated to version ' . self::TARGET_VERSION, 'success');
	}

	private function get_schema($charset_collate) {
		global $wpdb;

		$tables = [];

		$table_name = $wpdb->prefix . ORG::PREFIX . 'logs';
		$tables[] = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			timestamp datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			level varchar(20) NOT NULL,
			message text NOT NULL,
			PRIMARY KEY  (id),
			KEY level (level)
		) $charset_collate;";

		return $tables;
	}
}
