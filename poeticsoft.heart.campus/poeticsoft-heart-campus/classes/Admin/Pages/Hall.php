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
                'key' => 'access_by',
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
                'key' => 'root_post_id',
                'field_type' => 'number',
                'title' => 'Campus Root Post Id',
                'description' => 'Página Principal del Campus',
                'value' => 0,
                'width' => 80,
                'section' => 'campus_settings'
            ],
            [
                'key' => 'admin_access',
                'field_type' => 'boolean',
                'title' => 'Acceso administradores',
                'description' => 'Permitir acceso total a administradores',
                'value' => false,
                'type' => 'checkbox',
                'section' => 'campus_settings'
            ],
            [
                'key' => 'page_utils',
                'field_type' => 'boolean',
                'title' => 'Listado de páginas',
                'description' => 'Activar páginas desplegables',
                'value' => true,
                'type' => 'checkbox',
                'section' => 'campus_settings'
            ],
            
            // --- Directus ---            
            
            [
                'key' => 'access_url',
                'field_type' => 'string',
                'title' => 'Sincronizacion de Humanos/Páginas',
                'description' => 'Sincronizacion de Humanos/Páginas',
                'value' => 'https://matriz.reconectar.org/items/magia?fields=humano_id.correo,wp_post_ids&filter[wp_post_ids][_nnull]=true&limit=-1',
                'section' => 'directus'
            ],

            [
                'key' => 'access_token',
                'field_type' => 'string',
                'title' => 'Token para Sincronizacion de Humanos/Páginas',
                'description' => 'Token para Sincronizacion de Humanos/Páginas',
                'value' => 'dF_ISg6A0P2ugMAkVbBxQFXag9dBSFtQ',
                'section' => 'directus'
            ],
            
            [
                'key' => 'log_access_url',
                'field_type' => 'string',
                'title' => 'Url registro en log de accesos',
                'description' => 'Url registro en log de accesos',
                'value' => 'https://matriz.reconectar.org/items/log_accesos',
                'section' => 'directus'
            ],

            [
                'key' => 'log_access_token',
                'field_type' => 'string',
                'title' => 'Token registro en log de accesos',
                'description' => 'Token registro en log de accesos',
                'value' => 'MMqlGZF1UJOQ0hg5meq2MKqGwMxRnRXE',
                'section' => 'directus'
            ],
            
            // --- Mail Relay --- 
            
            [
                'key' => 'mailrelay_api_url',
                'field_type' => 'string',
                'title' => 'Api URL',
                'description' => 'Api Url',
                'value' => '',
                'section' => 'mailrelay'
            ],

            [
                'key' => 'mailrelay_api_key',
                'field_type' => 'string',
                'title' => 'Api KEY',
                'description' => 'Api Url',
                'value' => '',
                'section' => 'mailrelay'
            ],
        ];
    }
}
