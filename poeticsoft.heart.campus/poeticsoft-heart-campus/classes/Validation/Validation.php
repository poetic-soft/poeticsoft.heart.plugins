<?php

namespace Poeticsoft\Heart\Validation;

use Poeticsoft\Heart\Utils\Utils;

/**
 * Validation Orchestrator.
 * Centralizes data sanitization and validation logic.
 */
class Validation {

	/**
	 * Sanitize an input value based on type.
	 *
	 * @param mixed  $value The value to sanitize.
	 * @param string $type  The expected type (text, email, url, int, float, textarea, key).
	 * @return mixed
	 */
	public function sanitize( $value, $type = 'text' ) {
		return Utils::sanitize( $value, $type );
	}

	/**
	 * Validate a value against specific rules.
	 *
	 * @param mixed  $value The value to validate.
	 * @param string $rule  The rule to check (required, email, url, numeric, boolean).
	 * @return bool
	 */
	public function validate( $value, $rule ) {
		return Utils::validate( $value, $rule );
	}

	/**
	 * Bulk validate and sanitize an array of data.
	 * 
	 * Example schema:
	 * [
	 *    'user_email' => ['type' => 'email', 'required' => true],
	 *    'age'        => ['type' => 'int', 'rule' => 'numeric']
	 * ]
	 *
	 * @param array $data   The input data.
	 * @param array $schema The validation/sanitization schema.
	 * @return array|(\WP_Error) Array of sanitized data or WP_Error on failure.
	 */
	public function validate_schema( $data, $schema ) {
		return Utils::validate_schema( $data, $schema );
	}
}
