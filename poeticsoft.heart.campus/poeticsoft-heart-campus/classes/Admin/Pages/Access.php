<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\Page;
use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\View\View;

/**
 * Dashboard Page.
 */
class Access extends Page
{

    protected function define_page_props()
    {
        $this->slug       = Campus::PLUGIN_SLUG;
        $this->menu_title = __('Accesos', Campus::TEXT_DOMAIN);
        $this->page_title = __('Accesos', Campus::TEXT_DOMAIN);
    }

    /**
     * Handle custom actions securely.
     */
    protected function handle_action($action)
    {
        if ('refresh_access' === $action) {
            // Logic to refresh status...
            Campus::log('Accesos actualizados.', 'info');

            // Add a notice using the View engine.
            add_action('admin_notices', function () {
                Campus::get(View::class)->render('admin/notice', [
                    'type'    => 'success',
                    'message' => __('Accesos actualizados.', Campus::TEXT_DOMAIN),
                ]);
            });
        }
    }

    protected function render_content()
    {
        $data = [
            'welcome_message' => sprintf(__('Welcome to the main dashboard of %s.', Campus::TEXT_DOMAIN), Campus::PLUGIN_NAME),
            'status_data'     => [
                'message' => __('All systems operational.', Campus::TEXT_DOMAIN),
            ],
        ];

        $this->render_view('admin/access', $data);
    }
}
