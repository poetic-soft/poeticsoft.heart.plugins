<?php

namespace Poeticsoft\Heart;

use Poeticsoft\Heart\Updater;
use Poeticsoft\Heart\Admin\Admin;
use Poeticsoft\Heart\Frontend\Frontend;
use Poeticsoft\Heart\Rest\Rest;
use Poeticsoft\Heart\Database\Database;
use Poeticsoft\Heart\Languages\Languages;
use Poeticsoft\Heart\Validation\Validation;

/**
 * Main Plugin Class (Campus).
 * Acts as a Service Container and Context Router.
 */
final class Campus
{

    /**
     * Stores service instances.
     *
     * @var array
     */
    private static $services = [];
    /**
     * Plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Plugin Identity Constants.
     * Modify these when cloning the scaffolding for a new plugin.
     */
    const PLUGIN_ID     = 'campus'; // Unique identifier for this plugin (e.g., 'base', 'ai-agent', 'crm').
    const PLUGIN_NAME   = 'Poeticsoft Heart Campus';
    const PLUGIN_SLUG   = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const TEXT_DOMAIN   = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const PREFIX        = 'poeticsoft_heart_' . self::PLUGIN_ID . '_';
    const API_NAMESPACE = 'poeticsoft/heart/' . self::PLUGIN_ID . '/v1';

    /**
     * Plugin instance.
     *
     * @var Campus
     */
    private static $instance = null;

    /**
     * Get class instance (Singleton).
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            // self::log(self::PLUGIN_NAME . ' Initialized', 'success');
            self::$instance->init();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {}

    /**
     * Service Container: Get or Instantiate a service.
     *
     * @param string $class FQDN of the class.
     * @return object
     */
    public static function get($class)
    {
        if (! isset(self::$services[$class])) {
            self::$services[$class] = new $class();
        }
        return self::$services[$class];
    }

    /**
     * Get absolute path to plugin folder or file.
     *
     * @param string $relative_path Path relative to plugin root.
     * @return string
     */
    public static function path($relative_path = '')
    {
        return plugin_dir_path(dirname(__DIR__) . '/' . self::PLUGIN_SLUG . '.php') . ltrim($relative_path, '/');
    }

    /**
     * Get public URL to plugin folder or file.
     *
     * @param string $relative_path Path relative to plugin root.
     * @return string
     */
    public static function url($relative_path = '')
    {
        return plugin_dir_url(dirname(__DIR__) . '/' . self::PLUGIN_SLUG . '.php') . ltrim($relative_path, '/');
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
     * Initialize the plugin based on context.
     */
    private function init()
    {
        // 1. Global Hooks (Always loaded)
        $this->init_global();

        // 2. Admin Context
        if (is_admin()) {
            $this->init_admin();
        }

        // 3. REST API Context
        // This only adds a hook to 'rest_api_init', so it's safe to call always.
        $this->init_rest();

        // 4. Frontend Context
        // We initialize frontend ONLY if it's not admin and not a REST request.
        if (! is_admin() && ! $this->is_rest()) {
            $this->init_frontend();
        }
    }

    /**
     * Check if the current request is a REST API request.
     * Useful for early detection before WordPress defines REST_REQUEST.
     *
     * @return bool
     */
    private function is_rest()
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
     * Load Admin components.
     */
    private function init_admin()
    {                  
        self::get(Updater::class)->init();
        self::get(Admin::class)->init();
    }

    /**
     * Load Frontend components.
     */
    private function init_frontend()
    {        
        self::get(Frontend::class)->init();
    }

    /**
     * Load REST API components.
     */
    private function init_rest()
    {
        add_action('rest_api_init', function () {
            self::get(Rest::class)->init();
        });
    }

    /**
     * Load Global components.
     */
    private function init_global()
    {
        // Initialize Languages Manager.
        self::get(Languages::class)->init();

        // Initialize Database Manager.
        self::get(Database::class)->init();
    }
}
