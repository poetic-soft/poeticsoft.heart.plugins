<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\View\View;
use Poeticsoft\Heart\Validation\Validation;

abstract class AbstractPage {

	protected $slug;
	protected $menu_title;
	protected $capability = 'manage_options';
	protected $settings = [];

	public $page_title;

	public function __construct() {
		$this->define_page_props();
	}

	abstract protected function define_page_props();

	public function init() {
		add_action('admin_init', [$this, 'register_settings']);
	    add_action('admin_init', [$this, 'maybe_handle_action']);
	}

	public function register_settings() {
		if (empty($this->settings)) {
			return;
		}

		$sections = [];

		foreach ($this->settings as $setting) {
			$option_name = Heart::PLUGIN_PREFIX . $setting['key'];

			register_setting(
				$this->slug, 
				$option_name, 
				[
					'type' => $setting['type'] ?? 'text', // string | boolean | integer | number | array | object
					'sanitize_callback' => function($value) use ($setting) {
						return Heart::get(Validation::class)->sanitize($value, $setting['type'] ?? 'text');
					},
					'default' => $setting['value'] ?? '',
				]
			);

			$section_id = $setting['section'] ?? 'default';
			if (!in_array($section_id, $sections)) {

				$section_title = $setting['section_title'] ?? ucwords(str_replace(['_', '-'], ' ', $section_id));
				add_settings_section(
					$section_id,
					'default' === $section_id ? '' : $section_title,
					null,
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

	public function render_field_callback($args) {
		$field_data = [
			'option_name' => Heart::PLUGIN_PREFIX . $args['key'],
			'value'       => get_option(Heart::PLUGIN_PREFIX . $args['key'], $args['value'] ?? ''),
			'field_type'  => $args['field_type'] ?? 'text', // Input type
			'type'        => $args['type'] ?? 'text',
			'description' => $args['description'] ?? '',
			'options'     => $args['options'] ?? [],
		];

		$this->render_view('admin/form-field', $field_data);
	}

    public function maybe_handle_action()
    {
        $action = $_REQUEST['action'] ?? '';

        if (empty($action) || !$this->is_current_page()) {
            return;
        }

        $this->check_security();
        $this->handle_action($action);
    }

    protected function handle_action($action)
    {
    }

	public function get_slug() {
		return $this->slug;
	}

	public function get_page_title() {
		return $this->page_title;
	}

	public function get_menu_title() {
		return $this->menu_title;
	}

	public function get_capability() {
		return $this->capability;
	}

	protected function is_current_page() {
		return isset($_GET['page']) && $_GET['page'] === $this->slug;
	}

	protected function check_security() {
		if (!current_user_can($this->capability)) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', Heart::TEXT_DOMAIN));
		}

		if (!empty($_REQUEST['action'])) {
			check_admin_referer($this->get_nonce_action(), $this->get_nonce_name());
		}
	}

	protected function get_nonce_action() {
		return $this->slug . '-action';
	}

	protected function get_nonce_name() {
		return '_' . Heart::PLUGIN_PREFIX . 'nonce';
	}

	public function nonce_field($echo = true) {
		return wp_nonce_field($this->get_nonce_action(), $this->get_nonce_name(), true, $echo);
	}

	public function render_content() {
		if (!empty($this->settings)) {
			$this->render_view('admin/generic-settings');
		}
	}

	protected function render_view($template_name, $data = []) {
		$data['page'] = $this;
		Heart::get(View::class)->render($template_name, $data);
	}

	public function render() {
		if (!current_user_can($this->capability)) {
			wp_die(esc_html__('You do not have sufficient permissions to access this page.', Heart::TEXT_DOMAIN));
		}

		$this->render_view('admin/layout');
	}
}
