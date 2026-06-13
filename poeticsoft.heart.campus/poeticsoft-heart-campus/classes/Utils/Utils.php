<?php

namespace Poeticsoft\Heart\Utils;

use Poeticsoft\Heart\Campus;

class Utils
{
    public static function get_request_ip()
    {

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


    public static function path($relative_path = '')
    {
        return plugin_dir_path(dirname(__DIR__, 2) . '/' . Campus::PLUGIN_SLUG . '.php') . ltrim($relative_path, '/');
    }


    public static function url($relative_path = '')
    {
        return plugin_dir_url(dirname(__DIR__, 2) . '/' . Campus::PLUGIN_SLUG . '.php') . ltrim($relative_path, '/');
    }


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


    public static function is_rest()
    {
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return true;
        }

        if (! isset($_SERVER['REQUEST_URI'])) {
            return false;
        }


        return strpos($_SERVER['REQUEST_URI'], '/wp-json/') !== false;
    }


    public static function sanitize($value, $type = 'text')
    {
        if (is_array($value)) {
            return array_map(function ($item) use ($type) {
                return self::sanitize($item, $type);
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
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'text':
            default:
                return sanitize_text_field($value);
        }
    }


    public static function validate($value, $rule)
    {
        if (( is_null($value) || $value === '' ) && 'required' === $rule) {
            return false;
        }

        if (is_null($value) || $value === '') {
            return true;
        }

        switch ($rule) {
            case 'email':
                return is_email($value);
            case 'url':
                return filter_var($value, FILTER_VALIDATE_URL) !== false;
            case 'numeric':
                return is_numeric($value);
            case 'boolean':
                return is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
            default:
                return true;
        }
    }


    public static function validate_schema($data, $schema)
    {
        $sanitized = [];
        $errors    = new \WP_Error();

        foreach ($schema as $field => $rules) {
            $value = $data[ $field ] ?? null;


            if (! empty($rules['required']) && empty($value)) {
                $errors->add('missing_field', "Field '{$field}' is required.");
                continue;
            }


            if (! empty($rules['rule']) && ! self::validate($value, $rules['rule'])) {
                $errors->add('invalid_field', "Field '{$field}' is invalid.");
                continue;
            }


            $type = $rules['type'] ?? 'text';
            $sanitized[ $field ] = self::sanitize($value, $type);
        }

        if ($errors->has_errors()) {
            return $errors;
        }

        return $sanitized;
    }


    public static function send_success($data, $status = 200)
    {
        return new \WP_REST_Response([
            'success' => true,
            'data'    => $data,
        ], $status);
    }


    public static function send_error($code, $message, $status = 400)
    {
        return new \WP_REST_Response([
            'success' => false,
            'error'   => [
                'code'    => $code,
                'message' => $message,
            ],
        ], $status);
    }


    public static function map_field_type_to_wp($type)
    {
        $map = [
            'int'   => 'integer',
            'float' => 'number',
            'bool'  => 'boolean',
        ];
        return $map[$type] ?? 'string';
    }


    public static function get_campus_root_id()
    {

        $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
        $campus_root_id = get_option($campus_root_id_option_name);

        return $campus_root_id ? intval($campus_root_id) : null;
    }


    public static function get_allow_admin()
    {

        $allow_admin_option_name = sprintf('%sadmin_access', Campus::PREFIX);
        $allow_admin = get_option($allow_admin_option_name);

        return (bool) $allow_admin;
    }


    public static function post_is_in_campus($post_id)
    {

        if ($post_id) {
            $campus_root_id = self::get_campus_root_id();
            $ancestors = get_post_ancestors($post_id);

            if (
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
