<?php

namespace Poeticsoft\Heart\Babel\Admin;

use Poeticsoft\Heart\Babel\Babel;
use Poeticsoft\Heart\Babel\Admin\Pages\DashboardPage;
use Poeticsoft\Heart\Babel\Admin\Pages\IngestionPage;
use Poeticsoft\Heart\Babel\Admin\Pages\SettingsPage;

/**
 * Menu Controller.
 * Orchestrates the creation of the main menu and sub-pages.
 */
class MenuController
{
    /**
     * Get the parent slug dynamically from Babel.
     *
     * @return string
     */
    public function get_parent_slug()
    {
        return Babel::PLUGIN_SLUG;
    }

    /**
     * Registered Sub-pages.
     *
     * @var array
     */
    private $pages = [
        DashboardPage::class,
        IngestionPage::class,
        SettingsPage::class,
    ];

    /**
     * Initialize menu hooks.
     */
    public function init()
    {
        add_action('admin_menu', [$this, 'register_menus']);
    }

    /**
     * Register the Top-level menu and all sub-menus.
     */
    public function register_menus()
    {
        $parent_slug = $this->get_parent_slug();

        // 1. Create the Main Top-Level Menu.
        add_menu_page(
            Babel::PLUGIN_NAME,
            Babel::PLUGIN_NAME,
            'manage_options',
            $parent_slug,
            [Babel::get(DashboardPage::class), 'render'],
            'dashicons-translation',
            30
        );

        // 2. Register all sub-pages.
        foreach ($this->pages as $page_class) {
            $page = Babel::get($page_class);

            // Dashboard is the parent, so we skip adding it as sub-menu to avoid duplication.
            if ($page->get_slug() === $parent_slug) {
                $page->init();
                continue;
            }

            add_submenu_page(
                $parent_slug,
                $page->get_page_title(),
                $page->get_menu_title(),
                $page->get_capability(),
                $page->get_slug(),
                [$page, 'render']
            );

            // Initialize page-specific logic (like registering settings).
            $page->init();
        }
    }
}
