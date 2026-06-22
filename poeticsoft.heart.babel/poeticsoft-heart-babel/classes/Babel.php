<?php

namespace Poeticsoft\Heart\Babel;

use Poeticsoft\Heart\Babel\Admin\Admin;
use Poeticsoft\Heart\Babel\Frontend\Frontend;
use Poeticsoft\Heart\Babel\Rest\Rest;
use Poeticsoft\Heart\Babel\Database\Database;
use Poeticsoft\Heart\Babel\Languages\Languages;
use Poeticsoft\Heart\Babel\Validation\Validation;
use Poeticsoft\Heart\Babel\Ingestion\Ingestor;
use Poeticsoft\Heart\Babel\Ingestion\PostIngestor;
use Poeticsoft\Heart\Babel\Admin\PostEditorController;
use Poeticsoft\Heart\Babel\UI\Ui;

/**
 * Main Plugin Class (Babel).
 * Acts as a Service Container and Context Router.
 */
final class Babel
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
    const VERSION = '1.0.1';

    /**
     * Plugin Identity Constants.
     */
    const PLUGIN_ID     = 'babel';
    const PLUGIN_NAME   = 'Poeticsoft Heart Babel';
    const PLUGIN_SLUG   = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const TEXT_DOMAIN   = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const PREFIX        = 'poeticsoft_heart_' . self::PLUGIN_ID . '_';
    const API_NAMESPACE = 'poeticsoft/heart/' . self::PLUGIN_ID . '/v1';

    /**
     * Plugin instance.
     *
     * @var Babel
     */
    private static $instance = null;

    /**
     * Get class instance (Singleton).
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::log(self::PLUGIN_NAME . ' Initialized', 'success');
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
        // 1. Admin Context
        if (is_admin()) {
            $this->init_admin();
        }

        // 2. REST API Context (Unconditionally register hook, WP fires it only on REST requests)
        $this->init_rest();

        // 3. Frontend Context (Always loaded if not admin/rest, but also handles common hooks)
        if (! is_admin() && ! (defined('REST_REQUEST') && REST_REQUEST)) {
            $this->init_frontend();
        }

        // 4. Global Hooks (Always loaded)
        $this->init_global();
    }

    /**
     * Load Admin components.
     */
    private function init_admin()
    {
        add_action('init', function () {
            self::get(Admin::class)->init();
        });

        // Lazy-loaded Updater: only in admin and on admin_init.
        add_action('admin_init', function () {
            self::get(Updater::class)->init();
        });
    }

    /**
     * Load Frontend components.
     */
    private function init_frontend()
    {
        add_action('init', function () {
            self::get(Frontend::class)->init();
        });
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

        // Initialize autonomous Ingestor (Cron services).
        self::get(Ingestor::class)->init();

        // Initialize WordPress Post Ingestor background hook services.
        self::get(PostIngestor::class)->init();

        // Initialize Post Editor synchronization hooks globally (supports Gutenberg REST updates).
        self::get(PostEditorController::class)->init();

        // Initialize unified UI assets routing globally (Admin, Frontend, Blocks).
        self::get(Ui::class)->init();

        // Register taxonomy support (tags) for pages.
        add_action('init', [$this, 'enable_tags_for_pages']);
    }

    /**
     * Enable standard post tags for pages to allow marking pages for Babel.
     */
    public function enable_tags_for_pages()
    {
        register_taxonomy_for_object_type('post_tag', 'page');
    }
}
