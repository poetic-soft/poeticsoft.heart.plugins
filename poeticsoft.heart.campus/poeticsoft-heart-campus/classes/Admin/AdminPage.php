<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\View\View;
use Poeticsoft\Heart\Utils\Utils;

abstract class AdminPage
{
    protected $slug;
    protected $menu_title;
    protected $page_title;
    protected $capability = 'manage_options';


    protected $settings = [];


    public function __construct()
    {
        $this->define_page_props();
    }


    abstract protected function define_page_props();


    public function init()
    {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'maybe_handle_action']);
    }


    public function register_settings()
    {
        if (empty($this->settings)) {
            return;
        }

        $sections = [];

        foreach ($this->settings as $setting) {
            $option_name = Campus::PREFIX . $setting['key'];


            register_setting($this->slug, $option_name, [
                'type'              => Utils::map_field_type_to_wp($setting['field_type'] ?? 'text'),
                'sanitize_callback' => function ($value) use ($setting) {
                    return Utils::sanitize($value, $setting['field_type'] ?? 'text');
                },
                'default'           => $setting['value'] ?? '',
            ]);


            $section_id = $setting['section'] ?? 'default';
            if (! in_array($section_id, $sections)) {
                $section_title = $setting['section_title'] ?? ucwords(str_replace(['_', '-'], ' ', $section_id));
                add_settings_section(
                    $section_id,
                    'default' === $section_id ? '' : $section_title,
                    '__return_empty_string',
                    $this->slug
                );
                $sections[] = $section_id;
            }


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


    public function maybe_handle_action()
    {
        $action = $_REQUEST['action'] ?? '';

        if (empty($action) || ! $this->is_current_page()) {
            return;
        }


        $this->check_security();


        $this->handle_action($action);
    }


    protected function handle_action($action)
    {
    }


    protected function is_current_page()
    {
        return isset($_GET['page']) && $_GET['page'] === $this->slug;
    }


    protected function check_security()
    {
        if (! current_user_can($this->capability)) {
            wp_die(esc_html__('No tienes suficientes permisos para acceder a esta página.', Campus::TEXT_DOMAIN));
        }


        if (! empty($_REQUEST['action'])) {
            check_admin_referer($this->get_nonce_action(), $this->get_nonce_name());
        }
    }


    protected function get_nonce_action()
    {
        return $this->slug . '-action';
    }


    protected function get_nonce_name()
    {
        return '_' . Campus::PREFIX . 'nonce';
    }


    public function nonce_field($echo = true)
    {
        return wp_nonce_field($this->get_nonce_action(), $this->get_nonce_name(), true, $echo);
    }


    protected function render_view($template_name, $data = [])
    {
        $data['page'] = $this;
        Campus::get(View::class)->render($template_name, $data);
    }


    public function render()
    {
        if (! current_user_can($this->capability)) {
            wp_die(esc_html__('No tienes suficientes permisos para acceder a esta página.', Campus::TEXT_DOMAIN));
        }

        $this->render_view('admin/layout');
    }


    public function render_content_internal()
    {
        $this->render_content();
    }


    protected function render_content()
    {
        if (! empty($this->settings)) {
            $this->render_view('admin/generic-settings');
        }
    }


    public function get_slug()
    {
        return $this->slug;
    }


    public function get_menu_title()
    {
        return $this->menu_title;
    }


    public function get_page_title()
    {
        return $this->page_title;
    }


    public function get_capability()
    {
        return $this->capability;
    }
}
