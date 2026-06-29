<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\Admin\Page;
use Poeticsoft\Heart\View\View;

class Dashboard extends Page {

	protected function define_page_props() {
		$this->slug       = Heart::PLUGIN_SLUG;
		$this->menu_title = __('Dashboard', Heart::TEXT_DOMAIN);
		$this->page_title = sprintf(
			__(
				'%s Dashboard', 
				Heart::TEXT_DOMAIN
			), 
			Heart::PLUGIN_NAME
		);
	}

	protected function handle_action($action) {
		if ('refresh_status' === $action) {
			
			add_action(
				'admin_notices', 
				function() {
					Heart::get(View::class)->render('admin/notice', [
						'type'    => 'success',
						'message' => __(
							'System status refreshed successfully.', 
							Heart::TEXT_DOMAIN
						),
					]);
				}
			);
		}
	}

	public function render_content() {
		$data = [
			'welcome_message' => sprintf(
				__(
					'Welcome to the main dashboard of %s.', 
					Heart::TEXT_DOMAIN
				), 
				Heart::PLUGIN_NAME
			),
			'status_data'     => [
				'message' => __(
					'All systems operational.', 
					Heart::TEXT_DOMAIN
				),
			],
		];

		$this->render_view(
			'admin/dashboard', 
			$data
		);
	}
}

