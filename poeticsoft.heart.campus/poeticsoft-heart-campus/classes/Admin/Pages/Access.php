<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\Page;
use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\View\View;
use Poeticsoft\Heart\Database\Updater;

/**
 * Dashboard Page.
 */
class Access extends Page
{

    protected function define_page_props()
    {
        $this->slug       = Campus::PREFIX . 'access';
        $this->menu_title = __('Accesos', Campus::TEXT_DOMAIN);
        $this->page_title = __('Accesos', Campus::TEXT_DOMAIN);
    }

    protected function handle_action($action)
    {
        if ('refresh_access' === $action) {
            
            Campus::get(Updater::class)->refresh_access_data(); 

            add_action(
                'admin_notices', 
                function () {
                    $this->render_view(
                        'admin/notice',
                        [
                            'type'    => 'success',
                            'message' => __('Accesos actualizados.', Campus::TEXT_DOMAIN),
                        ]
                    );
                }
            );
        }
    }

    protected function render_content()
    {
        
        $updated_data = Campus::get(Updater::class)->get_formatted_access_data();        
        $sorted_list = new \ArrayObject($updated_data);
        $sorted_list->ksort();
        $this->render_view(
            'admin/access', 
            [
                'sorted_list' => $sorted_list
            ]
        );
    }
}
