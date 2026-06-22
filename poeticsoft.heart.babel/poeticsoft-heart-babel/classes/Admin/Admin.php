<?php

namespace Poeticsoft\Heart\Babel\Admin;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Admin\MenuController;
use Poeticsoft\Heart\Babel\Admin\PostEditorController;

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
        // 1. Register Modular Menus.
        Babel::get(MenuController::class)->init();
    }
}
