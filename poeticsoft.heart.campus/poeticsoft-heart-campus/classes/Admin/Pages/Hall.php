<?php

namespace Poeticsoft\Heart\Admin\Pages;

use Poeticsoft\Heart\Admin\AdminPage;
use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

/**
 * SMTP Settings Page.
 */
class Hall extends AdminPage
{

    protected function define_page_props()
    {
        $this->slug       = Campus::PREFIX . 'hall';
        $this->menu_title = __('Campus Hall', Campus::TEXT_DOMAIN);
        $this->page_title = __('Campus Hall Configuration', Campus::TEXT_DOMAIN);

        $this->settings = [
            
            // --- Performance ---

            [
                'key'         => 'block_cache_enabled',
                'title'       => __('Enable Block Caching', Campus::TEXT_DOMAIN),
                'field_type'  => 'boolean',
                'section'     => 'performance',
                'section_title' => __('Performance Settings', Campus::TEXT_DOMAIN),
                'value'       => true,
                'type'        => 'checkbox',
                'description' => __('When enabled, complex blocks like Related Content will cache their results indefinitely to improve loading speed.', Campus::TEXT_DOMAIN),
            ],
            
            // --- Magick Link Duration ---

            [
                'key'         => 'magick_link_duration',
                'title'       => __('Validez', Campus::TEXT_DOMAIN),
                'field_type'  => 'number',
                'section'     => 'magick_link',
                'section_title' => __('Link acceso', Campus::TEXT_DOMAIN),
                'value'       => 7,
                'type'        => 'number',
                'description' => __('Duración en días de la validez del link de acceso al campus', Campus::TEXT_DOMAIN),
                'width'       => 80
            ],

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
                'key' => 'directus_access_url',
                'field_type' => 'string',
                'title' => 'Sincronizacion de Humanos/Páginas',
                'description' => 'Sincronizacion de Humanos/Páginas',
                'value' => 'https://matriz.reconectar.org/items/magia?fields=humano_id.correo,wp_post_ids&filter[wp_post_ids][_nnull]=true&limit=-1',
                'section' => 'directus'
            ],

            [
                'key' => 'directus_access_token',
                'field_type' => 'string',
                'title' => 'Token para Sincronizacion de Humanos/Páginas',
                'description' => 'Token para Sincronizacion de Humanos/Páginas',
                'value' => 'dF_ISg6A0P2ugMAkVbBxQFXag9dBSFtQ',
                'section' => 'directus'
            ],
            
            [
                'key' => 'directus_log_access_url',
                'field_type' => 'string',
                'title' => 'Url registro en log de accesos',
                'description' => 'Url registro en log de accesos',
                'value' => 'https://matriz.reconectar.org/items/log_accesos',
                'section' => 'directus'
            ],

            [
                'key' => 'directus_log_access_token',
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

    /**
     * Handle custom actions securely.
     */
    protected function handle_action($action)
    {
        if ('clear_block_cache' === $action) {
            
            global $wpdb;
            $prefix = 'poeticsoft_heart_campus_';
            $wpdb->query( $wpdb->prepare(
                "DELETE FROM $wpdb->options WHERE option_name LIKE %s OR option_name LIKE %s",
                '_transient_' . $prefix . '%',
                '_transient_timeout_' . $prefix . '%'
            ) );

            // Utils::log('Block cache manually cleared by user.', 'info');

            add_action('admin_notices', function () {
                $this->render_view('admin/notice', [
                    'type'    => 'success',
                    'message' => __('All block caches have been cleared successfully.', Campus::TEXT_DOMAIN),
                ]);
            });
        }
    }

    /**
     * Render page content.
     */
    protected function render_content()
    {
        $data = [
            'clear_cache_url' => wp_nonce_url(
                add_query_arg(['action' => 'clear_block_cache'], menu_page_url($this->slug, false)),
                $this->get_nonce_action(),
                $this->get_nonce_name()
            ),
        ];

        // 1. Render settings form (default)
        $this->render_view('admin/generic-settings');

        // 2. Add the clear cache button
        ?>
        <div class="card">
            <h2><?php _e('Cache Management', Campus::TEXT_DOMAIN); ?></h2>
            <p><?php _e('Use this button to manually clear all cached block content.', Campus::TEXT_DOMAIN); ?></p>
            <p>
                <a href="<?php echo esc_url($data['clear_cache_url']); ?>" class="button button-secondary">
                    <?php _e('Clear All Block Caches', Campus::TEXT_DOMAIN); ?>
                </a>
            </p>
        </div>
        <?php
    }
}
