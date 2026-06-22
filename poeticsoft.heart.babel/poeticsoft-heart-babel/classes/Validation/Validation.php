<?php

namespace Poeticsoft\Heart\Babel\Validation;

/**
 * Validation Orchestrator.
 * Centralizes data sanitization and validation logic.
 */
class Validation
{
    /**
     * Sanitize an input value based on type.
     *
     * @param mixed  $value The value to sanitize.
     * @param string $type  The expected type (text, email, url, int, float, textarea, key).
     * @return mixed
     */
    public function sanitize($value, $type = 'text')
    {
        if (is_array($value)) {
            return array_map(function ($item) use ($type) {
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

    /**
     * Validate a value against specific rules.
     *
     * @param mixed  $value The value to validate.
     * @param string $rule  The rule to check (required, email, url, numeric).
     * @return bool
     */
    public function validate($value, $rule)
    {
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

    /**
     * Bulk validate and sanitize an array of data.
     *
     * @param array $data   The input data.
     * @param array $schema The validation/sanitization schema.
     * @return array|(\WP_Error) Array of sanitized data or WP_Error on failure.
     */
    public function validate_schema($data, $schema)
    {
        $sanitized = [];
        $errors    = new \WP_Error();

        foreach ($schema as $field => $rules) {
            $value = $data[$field] ?? null;

            // 1. Check required.
            if (! empty($rules['required']) && empty($value)) {
                $errors->add('missing_field', "Field '{$field}' is required.");
                continue;
            }

            // 2. Validate if rule exists.
            if (! empty($rules['rule']) && ! $this->validate($value, $rules['rule'])) {
                $errors->add('invalid_field', "Field '{$field}' is invalid.");
                continue;
            }

            // 3. Sanitize.
            $type = $rules['type'] ?? 'text';
            $sanitized[$field] = $this->sanitize($value, $type);
        }

        if ($errors->has_errors()) {
            return $errors;
        }

        return $sanitized;
    }
}
