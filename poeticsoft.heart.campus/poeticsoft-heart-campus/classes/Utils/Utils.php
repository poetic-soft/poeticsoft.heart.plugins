<?php

namespace Poeticsoft\Heart\Utils;

use Poeticsoft\Heart\Campus;

/**
 * Common functions
 */
class Utils {
    
    /**
     * Get the request IP address.
     * 
     * @return string
     */
    public static function get_request_ip () {
    
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        
            $ip = $_SERVER['HTTP_CLIENT_IP'];
            
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
            
        } else {
            
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }

    /**
     * Get absolute path to plugin folder or file.
     *
     * @param string $relative_path Path relative to plugin root.
     * @return string
     */
    public static function path($relative_path = '')
    {
        return plugin_dir_path(dirname(__DIR__, 2) . '/' . Campus::PLUGIN_SLUG . '.php') . ltrim($relative_path, '/');
    }

    /**
     * Get public URL to plugin folder or file.
     *
     * @param string $relative_path Path relative to plugin root.
     * @return string
     */
    public static function url($relative_path = '')
    {
        return plugin_dir_url(dirname(__DIR__, 2) . '/' . Campus::PLUGIN_SLUG . '.php') . ltrim($relative_path, '/');
    }

    /**
     * Custom Logger.
     * Writes to a debug.log file in the plugin root for development purposes.
     *
     * @param mixed  $message The message or data to log.
     * @param string $level   Log level (info, debug, error, success).
     */
    public static function log($message, $level = 'info')
    {
        $log_file = self::path('debug.log');
        $timestamp = date('Y-m-d H:i:s');

        if (! is_string($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }

        $formatted_message = sprintf("[%s] [%s]: %s\n", $timestamp, strtoupper($level), $message);

        file_put_contents($log_file, $formatted_message, FILE_APPEND);
    }

    /**
     * Check if the current request is a REST API request.
     * Useful for early detection before WordPress defines REST_REQUEST.
     *
     * @return bool
     */
    public static function is_rest()
    {
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return true;
        }

        if (! isset($_SERVER['REQUEST_URI'])) {
            return false;
        }

        // Standard WordPress REST prefix is 'wp-json'
        return strpos($_SERVER['REQUEST_URI'], '/wp-json/') !== false;
    }

    /**
     * Sanitize an input value based on type.
     *
     * @param mixed  $value The value to sanitize.
     * @param string $type  The expected type (text, email, url, int, float, textarea, key).
     * @return mixed
     */
    public static function sanitize( $value, $type = 'text' ) {
        if ( is_array( $value ) ) {
            return array_map( function( $item ) use ( $type ) {
                return self::sanitize( $item, $type );
            }, $value );
        }

        switch ( $type ) {
            case 'email':
                return sanitize_email( $value );
            case 'url':
                return esc_url_raw( $value );
            case 'int':
                return intval( $value );
            case 'float':
                return floatval( $value );
            case 'textarea':
                return sanitize_textarea_field( $value );
            case 'key':
                return sanitize_key( $value );
            case 'bool':
                return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
            case 'text':
            default:
                return sanitize_text_field( $value );
        }
    }

    /**
     * Validate a value against specific rules.
     *
     * @param mixed  $value The value to validate.
     * @param string $rule  The rule to check (required, email, url, numeric, boolean).
     * @return bool
     */
    public static function validate( $value, $rule ) {
        if ( ( is_null( $value ) || $value === '' ) && 'required' === $rule ) {
            return false;
        }

        if ( is_null( $value ) || $value === '' ) {
            return true;
        }

        switch ( $rule ) {
            case 'email':
                return is_email( $value );
            case 'url':
                return filter_var( $value, FILTER_VALIDATE_URL ) !== false;
            case 'numeric':
                return is_numeric( $value );
            case 'boolean':
                return is_bool( filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) );
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
    public static function validate_schema( $data, $schema ) {
        $sanitized = [];
        $errors    = new \WP_Error();

        foreach ( $schema as $field => $rules ) {
            $value = $data[ $field ] ?? null;

            // 1. Check required.
            if ( ! empty( $rules['required'] ) && empty( $value ) ) {
                $errors->add( 'missing_field', "Field '{$field}' is required." );
                continue;
            }

            // 2. Validate if rule exists.
            if ( ! empty( $rules['rule'] ) && ! self::validate( $value, $rules['rule'] ) ) {
                $errors->add( 'invalid_field', "Field '{$field}' is invalid." );
                continue;
            }

            // 3. Sanitize.
            $type = $rules['type'] ?? 'text';
            $sanitized[ $field ] = self::sanitize( $value, $type );
        }

        if ( $errors->has_errors() ) {
            return $errors;
        }

        return $sanitized;
    }

    /**
     * Standardized Success Response.
     *
     * @param mixed $data   Data to return.
     * @param int   $status HTTP Status code.
     * @return \WP_REST_Response
     */
    public static function send_success( $data, $status = 200 ) {
        return new \WP_REST_Response( [
            'success' => true,
            'data'    => $data,
        ], $status );
    }

    /**
     * Standardized Error Response.
     *
     * @param string $code    Machine-readable error code.
     * @param string $message Human-readable message.
     * @param int    $status  HTTP Status code.
     * @return \WP_REST_Response
     */
    public static function send_error( $code, $message, $status = 400 ) {
        return new \WP_REST_Response( [
            'success' => false,
            'error'   => [
                'code'    => $code,
                'message' => $message,
            ],
        ], $status );
    }

    /**
     * Map custom field types to WP register_setting types.
     * 
     * @param string $type
     * @return string
     */
    public static function map_field_type_to_wp($type)
    {
        $map = [
            'int'   => 'integer',
            'float' => 'number',
            'bool'  => 'boolean',
        ];
        return $map[$type] ?? 'string';
    }

    /**
     * Get the Campus Root Page ID.
     * 
     * @return int|null
     */
    public static function get_campus_root_id() {
        
        $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
        $campus_root_id = get_option($campus_root_id_option_name);
        
        return $campus_root_id ? intval($campus_root_id) : null;
    }
    
    /**
     * Check if administrators are allowed access.
     * 
     * @return bool
     */
    public static function get_allow_admin() {
        
        $allow_admin_option_name = sprintf('%sadmin_access', Campus::PREFIX);
        $allow_admin = get_option($allow_admin_option_name);
        
        return (bool) $allow_admin;
    }
    
    /**
     * Check if a post is within the Campus tree.
     * 
     * @param int $post_id
     * @return bool
     */
    public static function post_is_in_campus($post_id) {

        if($post_id) {

            $campus_root_id = self::get_campus_root_id();
            $ancestors = get_post_ancestors($post_id);

            if(
                in_array(intval($campus_root_id), $ancestors)
                ||
                $post_id == $campus_root_id
            ) {

                return true;
            }   
        }
        
        return false;
    }
}
