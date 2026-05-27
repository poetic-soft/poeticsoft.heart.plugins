<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\View\View;
use Poeticsoft\Heart\Validation\Validation;

/**
 * Abstract Page Base Class.
 * Standardizes how admin pages are defined and rendered.
 */
abstract class Page
{

    /**
     * Page identification.
     */
    protected $slug;
    protected $menu_title;
    protected $page_title;
    protected $capability = 'manage_options';

    /**
     * Dynamic settings definition.
     * 
     * @var array
     */
    protected $settings = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->define_page_props();
    }

    /**
     * Every page must define its basic properties.
     */
    abstract protected function define_page_props();

    /**
     * Initialize page-specific hooks (settings registration, etc.)
     */
    public function init()
    {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'maybe_handle_action']);
    }

    /**
     * Dynamic registration of settings, sections, and fields.
     */
    public function register_settings()
    {
        if (empty($this->settings)) {
            return;
        }

        $sections = [];

        foreach ($this->settings as $setting) {
            $option_name = Campus::PREFIX . $setting['key'];

            // 1. Register Setting with automatic sanitization.
            register_setting($this->slug, $option_name, [
                'type'              => $this->map_field_type_to_wp($setting['field_type'] ?? 'text'),
                'sanitize_callback' => function ($value) use ($setting) {
                    return Campus::get(Validation::class)->sanitize($value, $setting['field_type'] ?? 'text');
                },
                'default'           => $setting['value'] ?? '',
            ]);

            // 2. Register Section if not registered.
            $section_id = $setting['section'] ?? 'default';
            if (! in_array($section_id, $sections)) {
                $section_title = $setting['section_title'] ?? ucwords(str_replace(['_', '-'], ' ', $section_id));
                add_settings_section(
                    $section_id,
                    'default' === $section_id ? '' : $section_title,
                    null,
                    $this->slug
                );
                $sections[] = $section_id;
            }

            // 3. Add Field.
            add_settings_field(
                $option_name,
                $setting['title'],
                [$this, 'render_field_callback'],
                $this->slug,
                $section_id,
                $setting
            );
        }
    }

    /**
     * Internal helper to map custom field types to WP register_setting types.
     */
    private function map_field_type_to_wp($type)
    {
        $map = [
            'int'   => 'integer',
            'float' => 'number',
            'bool'  => 'boolean',
        ];
        return $map[$type] ?? 'string';
    }

    /**
     * Callback to render settings fields using the View engine.
     */
    public function render_field_callback($args)
    {
        $field_data = [
            'option_name' => Campus::PREFIX . $args['key'],
            'value'       => get_option(Campus::PREFIX . $args['key'], $args['value'] ?? ''),
            'type'        => $args['type'] ?? 'text',
            'description' => $args['description'] ?? '',
            'options'     => $args['options'] ?? [],
            'width'       => $args['width'] ?? null,
        ];

        $this->render_view('admin/form-field', $field_data);
    }

    /**
     * Security layer: Check if an action needs to be handled.
     * This runs on admin_init, before the page is rendered.
     */
    public function maybe_handle_action()
    {
        $action = $_REQUEST['action'] ?? '';

        if (empty($action) || ! $this->is_current_page()) {
            return;
        }

        // Centralized security check.
        $this->check_security();

        // Delegate to child class.
        $this->handle_action($action);
    }

    /**
     * Every page can implement its specific action handler.
     * 
     * @param string $action The action being performed.
     */
    protected function handle_action($action)
    {
        // Override in child classes.
    }

    /**
     * Check if we are on the current page context.
     */
    protected function is_current_page()
    {
        return isset($_GET['page']) && $_GET['page'] === $this->slug;
    }

    /**
     * Centralized security validation.
     */
    protected function check_security()
    {
        if (! current_user_can($this->capability)) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', Campus::TEXT_DOMAIN));
        }

        // Only check nonce if it's a POST request or if an action is present.
        if (! empty($_REQUEST['action'])) {
            check_admin_referer($this->get_nonce_action(), $this->get_nonce_name());
        }
    }

    /**
     * Generate a standard nonce action name for the page.
     */
    protected function get_nonce_action()
    {
        return $this->slug . '-action';
    }

    /**
     * Generate a standard nonce field name.
     */
    protected function get_nonce_name()
    {
        return '_' . Campus::PREFIX . 'nonce';
    }

    /**
     * Render a hidden nonce field.
     * 
     * @param bool $echo Whether to echo or return the field.
     * @return string|void
     */
    public function nonce_field($echo = true)
    {
        return wp_nonce_field($this->get_nonce_action(), $this->get_nonce_name(), true, $echo);
    }

    /**
     * Helper to render a view with common data (like the page instance).
     * 
     * @param string $template_name
     * @param array  $data
     */
    protected function render_view($template_name, $data = [])
    {
        $data['page'] = $this;
        Campus::get(View::class)->render($template_name, $data);
    }

    /**
     * Main entry point for rendering. Uses the Master Layout.
     */
    public function render()
    {
        if (! current_user_can($this->capability)) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', Campus::TEXT_DOMAIN));
        }

        $this->render_view('admin/layout');
    }

    /**
     * Public wrapper for the content, used by the layout view.
     */
    public function render_content_internal()
    {
        $this->render_content();
    }

    /**
     * Default implementation: Renders generic settings view if settings are defined.
     * Override in child classes for custom page content.
     */
    protected function render_content()
    {
        if (! empty($this->settings)) {
            $this->render_view('admin/generic-settings');
        }
    }

    /**
     * Getter for slug.
     */
    public function get_slug()
    {
        return $this->slug;
    }

    /**
     * Getter for menu title.
     */
    public function get_menu_title()
    {
        return $this->menu_title;
    }

    /**
     * Getter for page title.
     */
    public function get_page_title()
    {
        return $this->page_title;
    }

    /**
     * Getter for capability.
     */
    public function get_capability()
    {
        return $this->capability;
    }
}
