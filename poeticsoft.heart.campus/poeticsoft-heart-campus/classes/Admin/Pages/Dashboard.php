<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\AdminPage;
use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\View\View;
use Poeticsoft\Heart\Utils\Utils;

class Dashboard extends AdminPage
{
    protected function define_page_props()
    {
        $this->slug       = Campus::PLUGIN_SLUG;
        $this->menu_title = __('Escritorio', Campus::TEXT_DOMAIN);
        $this->page_title = sprintf(__('Panel de control de %s', Campus::TEXT_DOMAIN), Campus::PLUGIN_NAME);
    }

    protected function handle_action($action)
    {
        if ('refresh_status' === $action) {
            add_action('admin_notices', function () {
                Campus::get(View::class)->render('admin/notice', [
                    'type'    => 'success',
                    'message' => __('Accesos del sistema actualizados correctamente.', Campus::TEXT_DOMAIN),
                ]);
            });
        }
    }

    protected function render_content()
    {
        $data = [
            'welcome_message' => sprintf(__('Te damos la bienvenida al panel principal de %s.', Campus::TEXT_DOMAIN), Campus::PLUGIN_NAME),
            'status_data'     => [
                'message' => __('Todos los sistemas están operativos.', Campus::TEXT_DOMAIN),
            ],
        ];

        $this->render_view('admin/dashboard', $data);
    }
}
