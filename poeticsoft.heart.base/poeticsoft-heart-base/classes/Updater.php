<?php

namespace Poeticsoft\Heart;

/**
 * Remote Updater Class.
 * Handles self-hosted updates via native WordPress filters.
 */
class Updater {

	/**
	 * Remote API URL.
	 */
	private $remote_url = 'https://api.poeticsoft.com/update.json';

	/**
	 * Initialize the updater hooks.
	 */
	public function init() {
		add_filter( 'site_transient_update_plugins', [ $this, 'check_update' ] );
		add_filter( 'plugins_api', [ $this, 'plugin_info' ], 20, 3 );
	}

	/**
	 * Check for updates against the remote JSON.
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$remote = $this->get_remote_data();

		if ( $remote && version_compare( Base::VERSION, $remote->version, '<' ) ) {
			$res = new \stdClass();
			$res->slug        = Base::PLUGIN_SLUG;
			$res->plugin      = Base::PLUGIN_SLUG . '/' . Base::PLUGIN_SLUG . '.php';
			$res->new_version = $remote->version;
			$res->tested      = $remote->tested;
			$res->package     = $remote->download_url;

			$transient->response[ $res->plugin ] = $res;
		}

		return $transient;
	}

	/**
	 * Provide plugin information for the "View details" modal.
	 */
	public function plugin_info( $res, $action, $args ) {
		if ( 'plugin_information' !== $action ) {
			return $res;
		}

		if ( Base::PLUGIN_SLUG !== $args->slug ) {
			return $res;
		}

		$remote = $this->get_remote_data();

		if ( ! $remote ) {
			return $res;
		}

		$res = new \stdClass();
		$res->name           = Base::PLUGIN_NAME;
		$res->slug           = Base::PLUGIN_SLUG;
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

	/**
	 * Fetch remote data with caching via transients.
	 */
	private function get_remote_data() {
		$remote = get_transient( Base::PREFIX . 'update' );

		if ( false === $remote ) {
			$response = wp_remote_get( $this->remote_url, [
				'timeout' => 10,
				'headers' => [ 'Accept' => 'application/json' ],
			] );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				return false;
			}

			$remote = json_decode( wp_remote_retrieve_body( $response ) );
			set_transient( Base::PREFIX . 'update', $remote, HOUR_IN_SECONDS );
		}

		return $remote;
	}
}
