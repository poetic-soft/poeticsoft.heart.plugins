<?php

namespace Poeticsoft\Heart\Campus;

class Updater
{
    private $remote_url = 'https://poeticsoft.com/plugins/poeticsoft-heart-campus/poeticsoft-heart-campus.json';

    public function init()
    {
        add_filter('site_transient_update_plugins', [$this, 'check_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 20, 3);
    }

    public function check_update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $remote = $this->get_remote_data();

        if ($remote && version_compare(Campus::VERSION, $remote->version, '<')) {
            $res = new \stdClass();
            $res->slug        = Campus::PLUGIN_SLUG;
            $res->plugin      = Campus::PLUGIN_SLUG . '/' . Campus::PLUGIN_SLUG . '.php';
            $res->new_version = $remote->version;
            $res->tested      = $remote->tested;
            $res->package     = $remote->download_url;
            $transient->response[$res->plugin] = $res;
        }

        return $transient;
    }

    public function plugin_info($res, $action, $args)
    {
        if ('plugin_information' !== $action) {
            return $res;
        }

        if (Campus::PLUGIN_SLUG !== $args->slug) {
            return $res;
        }

        $remote = $this->get_remote_data();

        if (! $remote) {
            return $res;
        }

        $res = new \stdClass();
        $res->name           = Campus::PLUGIN_NAME;
        $res->slug           = Campus::PLUGIN_SLUG;
        $res->version        = $remote->version;
        $res->tested         = $remote->tested;
        $res->requires       = $remote->requires;
        $res->author         = 'Poeticsoft';
        $res->download_link  = $remote->download_url;
        $res->trunk          = $remote->download_url;
        $res->last_updated   = $remote->last_updated;
        $res->sections       = (array) $remote->sections;

        return $res;
    }

    private function get_remote_data()
    {
        $remote = get_transient(Campus::PREFIX . 'update');

        if (false === $remote) {
            $response = wp_remote_get($this->remote_url, [
                'timeout' => 10,
                'headers' => ['Accept' => 'application/json'],
            ]);

            if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
                return false;
            }

            $remote = json_decode(wp_remote_retrieve_body($response));

            if (null === $remote) {
                return false;
            }

            set_transient(Campus::PREFIX . 'update', $remote, HOUR_IN_SECONDS);
        }

        return $remote;
    }
}
