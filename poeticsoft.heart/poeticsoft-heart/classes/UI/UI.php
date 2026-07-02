<?php

namespace Poeticsoft\Heart\UI;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\UI\Admin;
use Poeticsoft\Heart\UI\Front;
use Poeticsoft\Heart\UI\Blocks;

class UI {

	public function init() {
		
		if (is_admin()) {

			add_action(
				'admin_enqueue_scripts', 
				[$this, 'admin_enqueue_scripts']
			);

		} else {

			add_action(
				'wp_enqueue_scripts', 
				[$this, 'wp_enqueue_scripts']
			);
		}

		Heart::get(Blocks::class)->init();
	}	

	public function admin_enqueue_scripts($hook_suffix) {

		$admin_assets = Heart::get(Admin::class)->get_admin_assets();

		// Plugins admin assets +

		foreach($admin_assets as $asset_data) {

            $this->enqueue($asset_data);
        }
	}	

	public function wp_enqueue_scripts($hook_suffix) {

		$front_assets = Heart::get(Front::class)->get_front_assets();

		// Plugins admin assets +

		foreach($front_assets as $asset_data) {

            $this->enqueue($asset_data);
        }
	}

	public function enqueue($enqueue_data) {

		$class = $enqueue_data['class'];
		$id = $class::PLUGIN_SLUG . str_replace('/', '-', $enqueue_data['ui_path']);
		$dir = $class::PLUGIN_DIR;
		$url = $class::PLUGIN_URL;

		wp_enqueue_script(
			$id,
			$url . $enqueue_data['ui_path'] . '/main.js',
			$enqueue_data['js_deps'],
			filemtime($dir . $enqueue_data['ui_path'] . '/main.js'),
			true
		);

		wp_enqueue_style(
			$id,
			$url . $enqueue_data['ui_path'] . '/main.css',
			$enqueue_data['css_deps'],
			filemtime($dir . $enqueue_data['ui_path'] . '/main.css')
		);
	}
}
