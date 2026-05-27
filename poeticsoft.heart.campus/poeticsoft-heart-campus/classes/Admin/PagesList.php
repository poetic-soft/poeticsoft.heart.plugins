<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Access;

/**
 * Pages List
 */
class PagesList
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
    }
    
    public function manage_pages_columns($columns) {
        
        $columns['status'] = '¿Abierto?';
        return $columns;
    }
    
    /**
     * Quick edit.
     */
    public function quick_edit($column_name, $post_type)
    {
        if ($post_type !== 'page' || $column_name !== 'status') {
            return;
        }
        
        $is_bulk = current_filter() === 'bulk_edit_custom_box';
        
        if (!$is_bulk) {
            
            $nonce_action = Campus::PREFIX . 'status_nonce';
            $nonce_name = $nonce_action . '_field';
            
            wp_nonce_field($nonce_action, $nonce_name);
        }
        
        $meta_key = Campus::PREFIX . 'status';
        
        echo '<fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <label class="inline-edit-group">
                    <span class="title">¿Abierta?</span>
                    <select name="' . $meta_key . '" class="mi-meta-bool-select">' .
                        ( $is_bulk ? '<option value="">— Sin cambios —</option>' : '' ) .
                        '<option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </label>
            </div>
        </fieldset>';
    }
}

