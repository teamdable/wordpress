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

		/** 예제 시작. 실제 데이터 페치 되는 거 확인한 후 제거할 것 */
		$data = array(
			'list' => array(
				array(
					'w_news_id' => 1,
					'title' => 'Do you know dable is the best partner for you? #1',
					'description' => 'This is decription for you guys #1',
					'link' => 'http://dable.io',
					'thumbnail_link' => 'https://placekitten.com/300/300',
					'order_weight' => null,
					'c_time' => '2018-11-13T01:15:58.000Z',
					'm_time' => '2018-11-13T01:15:58.000Z'
				),
				array(
					'w_news_id' => 2,
					'title' => 'Do you know dable is the best partner for you? #2',
					'description' => 'This is decription for you guys #2. Very long long long long long long long long long super long long long long long long long title. The content of this paragraph should overflows its container.',
					'link' => 'http://dable.io',
					'thumbnail_link' => 'https://placekitten.com/300/300',
					'order_weight' => null,
					'c_time' => '2018-11-13T01:15:58.000Z',
					'm_time' => '2018-11-13T01:15:58.000Z'
				),
				array(
					'w_news_id' => 3,
					'title' => 'Do you know dable is the best partner for you? #3',
					'description' => '한국어 컨텐츠 테스트. 아주 긴 글도 잘 나와야하고 말줄임표를 통해 잘 잘려야 합니다. 어떻게 보일까요, 어떻게 보일까요, 어떻게 보일까요. 지금부터 작성하는 문장은 중간에 생략되어 말줄임표와 함께 나타날 것입니다.',
					'link' => 'http://dable.io',
					'thumbnail_link' => 'https://placekitten.com/300/300',
					'order_weight' => null,
					'c_time' => '2018-11-13T01:15:58.000Z',
					'm_time' => '2018-11-13T01:15:58.000Z'
				),
			),
		);

		return new WP_REST_Response( $data, 200 );
		/** 예제 끝. 여기까지 제거 */

		if ( is_wp_error( $res ) ) {
			return $res;
		}

		return new WP_REST_Response( $res, 200 );
	}
}
