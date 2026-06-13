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

final class Campus
{
    private static $services = [];

    const VERSION = '1.0.0';


    const PLUGIN_ID      = 'campus';
    const PLUGIN_NAME    = 'Poeticsoft Heart Campus';
    const PLUGIN_SLUG    = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const TEXT_DOMAIN    = 'poeticsoft-heart-' . self::PLUGIN_ID;
    const PREFIX         = 'poeticsoft_heart_' . self::PLUGIN_ID . '_';
    const API_NAMESPACE  = 'poeticsoft/heart/' . self::PLUGIN_ID . '/v1';


    private static $instance = null;


    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();

            self::$instance->init();
        }
        return self::$instance;
    }


    private function __construct()
    {
    }


    public static function get($class)
    {
        if (! isset(self::$services[$class])) {
            self::$services[$class] = new $class();
        }
        return self::$services[$class];
    }


    private function init()
    {

        $this->init_global();


        if (is_admin()) {
            $this->init_admin();
        }



        $this->init_rest();



        if (! is_admin() && ! Utils::is_rest()) {
            $this->init_frontend();
        }
    }


    private function init_admin()
    {
        self::get(Updater::class)->init();
        self::get(Admin::class)->init();
    }


    private function init_frontend()
    {
        self::get(Frontend::class)->init();
    }


    private function init_rest()
    {
        add_action('rest_api_init', function () {
            self::get(Rest::class)->init();
        });
    }


    private function init_global()
    {

        self::get(Languages::class)->init();


        self::get(Database::class)->init();


        self::get(Blocks::class)->init();


        self::get(Mail::class)->init();

        add_action(
            'init',
            function () {

                register_taxonomy_for_object_type('post_tag', 'page');
            }
        );
    }
}
