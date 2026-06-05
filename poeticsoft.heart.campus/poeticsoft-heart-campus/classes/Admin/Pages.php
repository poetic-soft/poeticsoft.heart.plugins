<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

/**
 * Page list & edit
 */
class Pages
{

    /**
     * Initialize editor hooks.
     */
    public function init()
    {
        
        add_filter(
            'manage_pages_columns', 
            [$this, 'manage_pages_columns']
        );

        add_action(
            'manage_pages_custom_column',
            [$this, 'manage_pages_custom_column'],
            10,
            2
        );

        add_action(
            'quick_edit_custom_box', 
            [$this, 'quick_edit'],
            10, 
            2
        );

        add_action(
            'bulk_edit_custom_box', 
            [$this, 'quick_edit'],
            10, 
            2
        );

        add_action(
            'add_meta_boxes', 
            [$this, 'add_metaboxes']
        );

        add_action(
            'save_post', 
            [$this, 'save_post']
        );
    }
    
    public function manage_pages_columns($columns) {
        
        $columns['access'] = 'Acceso';
        return $columns;
    }

    public function manage_pages_custom_column($column_name, $post_id) {
        
        if (
            Utils::post_is_in_campus($post_id) 
            && 
            $column_name === 'access'
        ) {
            
           echo '<div class="' . (Campus::PLUGIN_SLUG . '-access-column') . '"></div>';
        }
    }
    
    /**
     * Quick edit.
     */
    public function quick_edit($column_name, $post_type)
    {
        if ($post_type !== 'page' || $column_name !== 'access') {
            return;
        }
        
        $is_bulk = current_filter() === 'bulk_edit_custom_box';
        
        if (!$is_bulk) {
            
            $nonce_action = Campus::PREFIX . 'status_nonce';
            $nonce_name = $nonce_action . '_field';
            
            wp_nonce_field($nonce_action, $nonce_name);
        }
        
        $meta_key = Campus::PREFIX . 'access';
        $meta_class = Campus::PLUGIN_SLUG . '-access';
        
        echo '<fieldset class="inline-edit-col-right ' .  $meta_class . '">
            <div class="inline-edit-col">
                <label class="inline-edit-group">
                    <span class="title">Acceso</span>
                    <select 
                        name="' . $meta_key . '" 
                        class="' .  $meta_class . '"
                    >' .
                        ( $is_bulk ? '<option value="">— Sin cambios —</option>' : '' ) .
                        '<option value="abierta">Abierta</option>
                         <option value="restringida">Restringida</option>
                    </select>
                </label>
            </div>
        </fieldset>';
    }

    /**
     * Add custom metabox.
     */
    public function add_metaboxes()
    {
        add_meta_box(
            Campus::PREFIX . 'metabox',
            Campus::PLUGIN_NAME,
            [$this, 'render_metabox'],
            ['post', 'page'],
            'side',
            'default'
        );
    }

    /**
     * Render metabox content.
     */
    public function render_metabox($post)
    {
        // 1. Crear el nonce de seguridad específico para la edición normal
        $nonce_action = Campus::PREFIX . 'status_nonce';
        $nonce_name = $nonce_action . '_field';
        wp_nonce_field($nonce_action, $nonce_name);

        // 2. Recuperar el valor actual del meta
        $meta_key = Campus::PREFIX . 'access';
        $current_value = get_post_meta($post->ID, $meta_key, true);
        
        // Si no hay valor guardado, puedes definir 'abierta' por defecto
        if (empty($current_value)) {
            $current_value = 'restringida';
        }

        $meta_class = Campus::PLUGIN_SLUG . '-access';

        echo '<div class="components-base-control">
            <label 
                class="components-base-control__label" 
                style="display:block; margin-bottom: 5px; font-weight:600;"
            >
                Acceso
            </label>
            <select 
                name="' . esc_attr($meta_key) . '" 
                class="' . esc_attr($meta_class) . '" 
                style="width:100%; height:30px; box-sizing:border-box;"
            >
                <option value="abierta" ' . selected($current_value, 'abierta', false) . '>
                    Abierta
                </option>
                <option value="restringida" ' . selected($current_value, 'restringida', false) . '>
                    Restringida
                </option>
            </select>
        </div>';
    }

    /**
     * Save quick edit and bulk edit data.
     */
    public function save_post($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (!current_user_can('edit_page', $post_id) && !current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        $meta_key = Campus::PREFIX . 'access';
        $nonce_action = Campus::PREFIX . 'status_nonce';
        $nonce_name = $nonce_action . '_field';

        if (isset($_POST[$nonce_name])) {
            if (!wp_verify_nonce($_POST[$nonce_name], $nonce_action)) {
                return $post_id;
            }
        } 
        elseif (!isset($_POST['action']) || $_POST['action'] !== 'inline-save') {
            return $post_id;
        }

        if (isset($_POST[$meta_key])) {
            $new_value = sanitize_text_field($_POST[$meta_key]);

            // Manejo del Bulk Edit (Edición en lote) por si acaso
            if (current_filter() === 'bulk_edit_custom_box' && $new_value === '') {
                return $post_id;
            }

            update_post_meta($post_id, $meta_key, $new_value);
        }
    }
}

