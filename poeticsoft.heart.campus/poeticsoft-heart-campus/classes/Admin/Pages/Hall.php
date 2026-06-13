<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\AdminPage;
use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

class Hall extends AdminPage
{
    protected function define_page_props()
    {
        $this->slug       = Campus::PREFIX . 'hall';
        $this->menu_title = __('Hall del Campus', Campus::TEXT_DOMAIN);
        $this->page_title = __('Configuración del Hall del Campus', Campus::TEXT_DOMAIN);

        $this->settings = [



            [
                'key'         => 'block_cache_enabled',
                'title'       => __('Activar caché de bloques', Campus::TEXT_DOMAIN),
                'field_type'  => 'boolean',
                'section'     => 'performance',
                'section_title' => __('Ajustes de rendimiento', Campus::TEXT_DOMAIN),
                'value'       => true,
                'type'        => 'checkbox',
                'description' => __('Cuando está activado, los bloques complejos como Contenido Relacionado guardarán en caché sus resultados indefinidamente para mejorar la velocidad de carga.', Campus::TEXT_DOMAIN),
            ],



            [
                'key'         => 'magick_link_duration',
                'title'       => __('Validez', Campus::TEXT_DOMAIN),
                'field_type'  => 'number',
                'section'     => 'magick_link',
                'section_title' => __('Enlace de acceso', Campus::TEXT_DOMAIN),
                'value'       => 7,
                'type'        => 'number',
                'description' => __('Duración en días de la validez del link de acceso al campus', Campus::TEXT_DOMAIN),
                'width'       => 80
            ],



            [
                'key' => 'access_by',
                'field_type' => 'string',
                'title' => __('Origen accesos de alumnos', Campus::TEXT_DOMAIN),
                'description' => __('Fuente de validación del acceso a páginas del campus', Campus::TEXT_DOMAIN),
                'value' => false,
                'type' => 'select',
                'options' => [
                    ['label' => __('Mail Relay Suscriptors', Campus::TEXT_DOMAIN), 'value' => 'mailrelay'],
                    ['label' => __('Directus Access', Campus::TEXT_DOMAIN), 'value' => 'directus']
                ],
                'section' => 'campus_settings'
            ],
            [
                'key' => 'root_post_id',
                'field_type' => 'number',
                'title' => __('ID de página de inicio del campus', Campus::TEXT_DOMAIN),
                'description' => __('Página Principal del Campus', Campus::TEXT_DOMAIN),
                'value' => 0,
                'width' => 80,
                'section' => 'campus_settings'
            ],
            [
                'key' => 'admin_access',
                'field_type' => 'boolean',
                'title' => __('Acceso administradores', Campus::TEXT_DOMAIN),
                'description' => __('Permitir acceso total a administradores', Campus::TEXT_DOMAIN),
                'value' => false,
                'type' => 'checkbox',
                'section' => 'campus_settings'
            ],
            [
                'key' => 'page_utils',
                'field_type' => 'boolean',
                'title' => __('Listado de páginas', Campus::TEXT_DOMAIN),
                'description' => __('Activar páginas desplegables', Campus::TEXT_DOMAIN),
                'value' => true,
                'type' => 'checkbox',
                'section' => 'campus_settings'
            ],



            [
                'key' => 'directus_access_url',
                'field_type' => 'string',
                'title' => __('Sincronizacion de Humanos/Páginas', Campus::TEXT_DOMAIN),
                'description' => __('Sincronizacion de Humanos/Páginas', Campus::TEXT_DOMAIN),
                'value' => 'https://matriz.reconectar.org/items/magia?fields=humano_id.correo,wp_post_ids&filter[wp_post_ids][_nnull]=true&limit=-1',
                'section' => 'directus'
            ],

            [
                'key' => 'directus_access_token',
                'field_type' => 'string',
                'title' => __('Token para Sincronizacion de Humanos/Páginas', Campus::TEXT_DOMAIN),
                'description' => __('Token para Sincronizacion de Humanos/Páginas', Campus::TEXT_DOMAIN),
                'value' => 'dF_ISg6A0P2ugMAkVbBxQFXag9dBSFtQ',
                'section' => 'directus'
            ],

            [
                'key' => 'directus_log_access_url',
                'field_type' => 'string',
                'title' => __('Url registro en log de accesos', Campus::TEXT_DOMAIN),
                'description' => __('Url registro en log de accesos', Campus::TEXT_DOMAIN),
                'value' => 'https://matriz.reconectar.org/items/log_accesos',
                'section' => 'directus'
            ],

            [
                'key' => 'directus_log_access_token',
                'field_type' => 'string',
                'title' => __('Token registro en log de accesos', Campus::TEXT_DOMAIN),
                'description' => __('Token registro en log de accesos', Campus::TEXT_DOMAIN),
                'value' => 'MMqlGZF1UJOQ0hg5meq2MKqGwMxRnRXE',
                'section' => 'directus'
            ],



            [
                'key' => 'mailrelay_api_url',
                'field_type' => 'string',
                'title' => __('URL de la API', Campus::TEXT_DOMAIN),
                'description' => __('URL de la API', Campus::TEXT_DOMAIN),
                'value' => '',
                'section' => 'mailrelay'
            ],

            [
                'key' => 'mailrelay_api_key',
                'field_type' => 'string',
                'title' => __('Clave de la API (KEY)', Campus::TEXT_DOMAIN),
                'description' => __('URL de la API', Campus::TEXT_DOMAIN),
                'value' => '',
                'section' => 'mailrelay'
            ],
        ];
    }


    protected function handle_action($action)
    {
        if ('clear_block_cache' === $action) {
            global $wpdb;
            $prefix = 'poeticsoft_heart_campus_';
            $wpdb->query($wpdb->prepare(
                "DELETE FROM $wpdb->options WHERE option_name LIKE %s OR option_name LIKE %s",
                '_transient_' . $prefix . '%',
                '_transient_timeout_' . $prefix . '%'
            ));



            add_action('admin_notices', function () {
                $this->render_view('admin/notice', [
                    'type'    => 'success',
                    'message' => __('Todas las cachés de bloques han sido borradas correctamente.', Campus::TEXT_DOMAIN),
                ]);
            });
        }
    }


    protected function render_content()
    {
        $data = [
            'clear_cache_url' => wp_nonce_url(
                add_query_arg(['action' => 'clear_block_cache'], menu_page_url($this->slug, false)),
                $this->get_nonce_action(),
                $this->get_nonce_name()
            ),
        ];


        $this->render_view('admin/generic-settings');


        $this->render_view('admin/cache-management-card', $data);
    }
}
