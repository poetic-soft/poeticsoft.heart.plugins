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
            [$this, 'save_data']
        );
    }
    
    public function manage_pages_columns($columns) {
        
        $columns['access'] = 'Acceso';
        return $columns;
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
            sprintf(__('%s Options', Campus::TEXT_DOMAIN), Campus::PLUGIN_NAME),
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
        echo '<p>' . esc_html__('Custom options for this content.', Campus::TEXT_DOMAIN) . '</p>';
    }

    /**
     * Save quick edit and bulk edit data.
     */
    public function save_data($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (get_post_type($post_id) !== 'page') {
            return;
        }

        if (!current_user_can('edit_page', $post_id)) {
            return;
        }

        $meta_key = Campus::PREFIX . 'access';
        
        if (isset($_REQUEST[$meta_key])) {
            
            $value = $_REQUEST[$meta_key];

            // For bulk edit, an empty string means "No Change".
            if (
                $value === '' 
                && 
                isset($_REQUEST['action']) 
                && 
                (
                    $_REQUEST['action'] === 'bulk-edit' 
                    || 
                    isset($_REQUEST['bulk_edit'])
                )
            ) {
                return;
            }
            
            // Nonce verification for quick edit.
            if (
                isset($_REQUEST['action']) 
                && 
                $_REQUEST['action'] === 'inline-save'
            ) {
                $nonce_action = Campus::PREFIX . 'status_nonce';
                $nonce_name = $nonce_action . '_field';
                if (
                    !isset($_REQUEST[$nonce_name]) 
                    || 
                    !wp_verify_nonce($_REQUEST[$nonce_name], $nonce_action)
                ) {
                    Utils::log("Nonce verification failed for quick edit.");
                    return;
                }
            }

            // Convert to boolean for update_post_meta.
            $bool_value = ($value === '1' || $value === 'true' || $value === true);
            $updated = update_post_meta($post_id, $meta_key, $bool_value);
            
        } else {
            Utils::log("Meta key $meta_key not found in REQUEST.");
        }
    }
}

