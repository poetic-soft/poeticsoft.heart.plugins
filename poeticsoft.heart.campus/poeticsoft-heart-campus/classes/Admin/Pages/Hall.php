<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\Page;
use Poeticsoft\Heart\Campus;

/**
 * SMTP Settings Page.
 */
class Hall extends Page
{

    protected function define_page_props()
    {
        $this->slug       = Campus::PREFIX . 'hall';
        $this->menu_title = __('Campus Hall', Campus::TEXT_DOMAIN);
        $this->page_title = __('Campus Hall Configuration', Campus::TEXT_DOMAIN);

        $this->settings = [
            // --- Campus ---
            [
                'key' => 'campus_access_by',
                'field_type' => 'string',
                'title' => 'Origen accesos de alumnos',
                'description' => 'Fuente de validación del acceso a páginas del campus',
                'value' => false,
                'type' => 'select',
                'options' => [
                    ['label' => 'Mail Relay Suscriptors', 'value' => 'mailrelay'],
                    ['label' => 'Directus Access', 'value' => 'directus']
                ],
                'section' => 'campus_settings'
            ],
            [
                'key' => 'campus_admin_access',
                'field_type' => 'boolean',
                'title' => 'Acceso administradores',
                'description' => 'Permitir acceso total a administradores',
                'value' => false,
                'type' => 'checkbox',
                'section' => 'campus_settings'
            ],
            [
                'key' => 'campus_root_post_id',
                'field_type' => 'number',
                'title' => 'Campus Root Post Id',
                'description' => 'Página Principal del Campus',
                'value' => 0,
                'width' => 80,
                'section' => 'campus_settings'
            ],
        ];
    }
}
