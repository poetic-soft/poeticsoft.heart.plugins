<?php

namespace Poeticsoft\Heart\Base\Admin\Pages;

use Poeticsoft\Heart\Base\Base;
use Poeticsoft\Heart\Base\Admin\AbstractPage;
use Poeticsoft\Heart\Base\View\View;

/**
 * Dashboard Page.
 */
class Dashboard extends AbstractPage {

	protected function define_page_props() {
		$this->slug       = Base::PLUGIN_SLUG;
		$this->menu_title = __('Dashboard', Base::TEXT_DOMAIN);
		$this->page_title = sprintf(__('%s Dashboard', Base::TEXT_DOMAIN), Base::PLUGIN_NAME);
	}

	/**
	 * Handle custom actions securely.
	 */
	protected function handle_action($action) {
		if ('refresh_status' === $action) {
			// Logic to refresh status...
			Base::log('System status refreshed by user.', 'info');

			// Add a notice using the View engine.
			add_action('admin_notices', function() {
				Base::get(View::class)->render('admin/notice', [
					'type'    => 'success',
					'message' => __('System status refreshed successfully.', Base::TEXT_DOMAIN),
				]);
			});
		}
	}

	protected function render_content() {
		$data = [
			'welcome_message' => sprintf(__('Welcome to the main dashboard of %s.', Base::TEXT_DOMAIN), Base::PLUGIN_NAME),
			'status_data'     => [
				'message' => __('All systems operational.', Base::TEXT_DOMAIN),
			],
		];

		$this->render_view('admin/dashboard', $data);
	}
}

