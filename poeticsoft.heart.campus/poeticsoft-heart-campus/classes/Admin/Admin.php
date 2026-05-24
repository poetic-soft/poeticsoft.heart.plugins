<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Admin\AdminAssets;
use Poeticsoft\Heart\Admin\MenuController;
use Poeticsoft\Heart\Admin\PostEditorController;

/**
 * Admin Controller.
 * Handles admin-specific logic.
 */
class Admin
{

    /**
     * Initialize admin hooks.
     */
    public function init()
    {
        // 1. Initialize Admin Assets (always loaded in admin).
        Campus::get(Assets::class)->init();

        // 2. Register Modular Menus.
        Campus::get(Menu::class)->init();

        // 3. Conditional Sub-Routing for other logic (metaboxes, etc).
        add_action('current_screen', [$this, 'route_sub_controllers']);
    }

    /**
     * Sub-Router: Only instantiates logic for screens that are NOT main settings pages
     * (since settings pages are now managed by MenuController).
     */
    public function route_sub_controllers()
    {
        $screen = get_current_screen();

        if (! $screen) {
            return;
        }

        // A. Check if we are in the Editor (Post/Page/CPT).
        if ('post' === $screen->base) {
            Campus::get(PostEditor::class)->init();
        }
    }
}
