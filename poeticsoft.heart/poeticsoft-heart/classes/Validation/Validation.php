<?php

namespace Poeticsoft\Heart\Validation;

use WP_Error;

class Validation {

	public function sanitize($value, $type = 'text') {
		if (is_array($value)) {
			return array_map(function($item) use ($type) {
				return $this->sanitize($item, $type);
			}, $value);
		}

		switch ($type) {
			case 'email':
				return sanitize_email($value);
			case 'url':
				return esc_url_raw($value);
			case 'int':
				return intval($value);
			case 'float':
				return floatval($value);
			case 'textarea':
				return sanitize_textarea_field($value);
			case 'key':
				return sanitize_key($value);
			case 'text':
			default:
				return sanitize_text_field($value);
		}
	}

	public function validate($value, $rule) {
		if (empty($value) && 'required' === $rule) {
			return false;
		}

		if (empty($value)) {
			return true;
		}

		switch ($rule) {
			case 'email':
				return is_email($value);
			case 'url':
				return filter_var($value, FILTER_VALIDATE_URL) !== false;
			case 'numeric':
				return is_numeric($value);
			default:
				return true;
		}
	}

	public function validate_schema($data, $schema) {
		$sanitized = [];
		$errors    = new WP_Error();

		foreach ($schema as $field => $rules) {
			$value = $data[$field] ?? null;

			if (!empty($rules['required']) && empty($value)) {
				$errors->add('missing_field', "Field '{$field}' is required.");
				continue;
			}

			if (!empty($rules['rule']) && !$this->validate($value, $rules['rule'])) {
				$errors->add('invalid_field', "Field '{$field}' is invalid.");
				continue;
			}

			$type = $rules['type'] ?? 'text';
			$sanitized[$field] = $this->sanitize($value, $type);
		}

		if ($errors->has_errors()) {
			return $errors;
		}

		return $sanitized;
	}
}
