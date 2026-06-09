<?php

namespace Poeticsoft\Heart;

use Poeticsoft\Heart\Updater;
use Poeticsoft\Heart\Admin\Admin;
use Poeticsoft\Heart\Admin\Mail;
use Poeticsoft\Heart\Frontend\Frontend;
use Poeticsoft\Heart\Rest\Rest;
use Poeticsoft\Heart\Database\Database;
use Poeticsoft\Heart\Languages\Languages;
use Poeticsoft\Heart\Validation\Validation;
use Poeticsoft\Heart\Blocks\Blocks;
use Poeticsoft\Heart\Validation\Access;

use Poeticsoft\Heart\Utils\Utils;

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
    const PLUGIN_ID      = 'campus'; // Unique identifier for this plugin (e.g., 'base', 'ai-agent', 'crm').
    const PLUGIN_NAME    = 'Poeticsoft Heart Campus';
    const PLUGIN_SLUG    = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const TEXT_DOMAIN    = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const PREFIX         = 'poeticsoft_heart_' . self::PLUGIN_ID . '_';
    const API_NAMESPACE  = 'poeticsoft/heart/' . self::PLUGIN_ID . '/v1';

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
            // Utils::log(self::PLUGIN_NAME . ' Initialized', 'success');
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
        if (! is_admin() && ! Utils::is_rest()) {
            $this->init_frontend();
        }
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
        
        // Initialize Blocls .
        self::get(Blocks::class)->init();

        // Initialize Mail Service.
        self::get(Mail::class)->init();  
        
        add_action(
            'init', 
            function () {
                
                register_taxonomy_for_object_type('post_tag', 'page');
            }
        );
    }
}
