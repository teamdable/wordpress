<?php

class DableAPI {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'add_endpoint') );
	}

	/**
	 * Add an endpoint for dable news
	 */
	public function add_endpoint() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		register_rest_route( 'dable/v1', '/news', array(
			'methods' => 'GET',
			'callback' => array( $this, 'get_dable_news' ),
			'args' => array(
				'count' => array(
					'type' => 'integer',
					'default' => 3,
					'sanitize_callback' => 'absint'
				)
			)
		) );
	}

	public function get_dable_news( $request ) {
		$api_endpoint = 'https://light-open-api.dable.io/api/v1/wordpress-plugin/news';
		$site_url = get_bloginfo('url');
		$lang = get_bloginfo('language');
		$count = $request['count'];

		$res = wp_remote_get(
			sprintf( '%s?blog=%s&lang=%s&count=%d', $api_endpoint, urlencode( $site_url ), $lang, $count ),
			array(
				'httpversion' => '1.1',
			)
		);


		if ( is_wp_error( $res ) ) {
			return $res;
		}

		$data = json_decode( $res['body'] );

		return new WP_REST_Response( $data, 200 );
	}
}
