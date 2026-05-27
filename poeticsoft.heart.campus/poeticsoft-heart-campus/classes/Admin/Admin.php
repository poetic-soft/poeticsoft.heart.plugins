<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Admin\Meta;
use Poeticsoft\Heart\Admin\Menu;
use Poeticsoft\Heart\Admin\Assets;
use Poeticsoft\Heart\Admin\PageEditor;
use Poeticsoft\Heart\Admin\PagesList;

/**
 * Admin Controller.
 * Handles admin-specific logic.
 */
class Admin
{

    /**
     * Initialize init hooks.
     */
    public function init()
    {       
        
        Campus::get(Assets::class)->init();
        Campus::get(Menu::class)->init();
        Campus::get(Meta::class)->init();     
        
        add_action('current_screen', [$this, 'route_sub_controllers']);
    }

    /**
     * Sub-Router: Only instantiates logic for screens that are NOT main settings pages
     */
    public function route_sub_controllers()
    {
        
        $screen = get_current_screen();

        if (! $screen) {
            return;
        }
        
        $screen_id = $screen->base . '_' . $screen->id;
        
        switch($screen_id) {
            
            case 'post_page':
                
                Campus::get(PageEditor::class)->init();
                
                break;
                
            case 'edit_edit-page':
                
                Campus::get(PagesList::class)->init();
                
                break;
        }
    }
}
