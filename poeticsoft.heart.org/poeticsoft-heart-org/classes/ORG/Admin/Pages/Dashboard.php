<?php

namespace Poeticsoft\Heart\ORG\Admin\Pages;

use Poeticsoft\Heart\ORG\ORG;
use Poeticsoft\Heart\ORG\Admin\AbstractPage;
use Poeticsoft\Heart\ORG\View\View;

class Dashboard extends AbstractPage {

	protected function define_page_props() {
		$this->slug       = ORG::PLUGIN_SLUG;
		$this->menu_title = __('Dashboard', ORG::TEXT_DOMAIN);
		$this->page_title = sprintf(__('%s Dashboard', ORG::TEXT_DOMAIN), ORG::PLUGIN_NAME);
	}

	protected function handle_action($action) {
		if ('refresh_status' === $action) {

			ORG::log('System status refreshed by user.', 'info');

			add_action('admin_notices', function() {
				ORG::get(View::class)->render('admin/notice', [
					'type'    => 'success',
					'message' => __('System status refreshed successfully.', ORG::TEXT_DOMAIN),
				]);
			});
		}
	}

	protected function render_content() {
		$data = [
			'welcome_message' => sprintf(__('Welcome to the main dashboard of %s.', ORG::TEXT_DOMAIN), ORG::PLUGIN_NAME),
			'status_data'     => [
				'message' => __('All systems operational.', ORG::TEXT_DOMAIN),
			],
		];

		$this->render_view('admin/dashboard', $data);
	}
}
