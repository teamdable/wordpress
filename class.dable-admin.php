<?php

class DableAdmin {
	const PAGE_SLUG = 'dable-for-wordpress';
	/**
	 * Stored options.
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_filter( 'plugin_action_links_' . DABLE_PLUGIN_BASENAME, array( $this, 'add_plugin_action' ) );
	}

	/**
	 * Insert a link to the Dable admin page into the Settings section
	 */
	public function add_plugin_page() {
		add_menu_page(
			'Dable',
			'Dable',
			'manage_options',
			DableAdmin::PAGE_SLUG,
			array( $this, 'create_admin_page' ),
			'none'
		);
	}

	/**
	 * Initialize the admin page
	 */
	public function page_init() {
		if ( isset( $_GET['page'] ) && DableAdmin::PAGE_SLUG === $_GET['page'] ) {
			wp_enqueue_style( 'dable-for-wordpress', DABLE_PLUGIN_URL . 'assets/style-admin.css', array(), DABLE_PLUGIN_VERSION );
			wp_enqueue_script( 'clamp-js', DABLE_PLUGIN_URL . 'assets/clamp.min.js', '0.5.1', true );
			wp_enqueue_script( 'dable-for-wordpress', DABLE_PLUGIN_URL . 'assets/admin.js', array( 'jquery' ), DABLE_PLUGIN_VERSION, true );
		}

		register_setting(
			'dable-settings-group',
			'dable-settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' )
			)
		);
		register_setting(
			'dable-settings-group',
			'dable-target-post-types'
		);
		register_setting(
			'dable-settings-group',
			'dable-og-settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' )
			)
		);
		register_setting(
			'dable-settings-group',
			'dable-widget-settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' )
			)
		);
	}

	/**
	 * Add 'Settings' link to the plugin list.
	 *
	 * @param  array $actions plugin actions for Dable.
	 * @return array
	 */
	public function add_plugin_action( $actions ) {
		$actions['settings'] = '<a href="admin.php?page=dable-for-wordpress">' . esc_html__( 'Settings' ) . '</a>';
		return $actions;
	}

	/**
	 * Get dable options easily.
	 *
	 * @param  string $name option name.
	 * @param  mixed  $default default value.
	 * @return mixed option value.
	 */
	protected function get_option( $name, $default = null ) {
		return isset( $this->options[ $name ] ) ? $this->options[ $name ] : $default;
	}

	/**
	 * Generate admin option page
	 */
	public function create_admin_page() {
		$this->options = Dable::get_options();
		include DABLE_PLUGIN_DIR . 'views/settings.php';
	}

	/**
	 * Sanitize form input values.
	 *
	 * @param  array $input input values.
	 * @return array sanitize values to save.
	 */
	public function sanitize( $input ) {
		$valid_keys = array(
			// Default settings
			'service_name' => 'string',
			'service_name_mobile' => 'string',
			'wrap_content' => 'bool',

			// Open Graph settings
			'print_og_tag' => 'bool',
			'thumbnail_size' => 'integer',

			// Widget settings
			'widget_type' => 'string',
			'widget_code_responsive_bottom' => 'string',
			'widget_code_responsive_left' => 'string',
			'widget_code_responsive_right' => 'string',
			'widget_code_pc_bottom' => 'string',
			'widget_code_pc_left' => 'string',
			'widget_code_pc_right' => 'string',
			'widget_code_mobile_bottom' => 'string',
			'widget_code_mobile_left' => 'string',
			'widget_code_mobile_right' => 'string',
			'display_widget_responsive_bottom' => 'bool',
			'display_widget_responsive_left' => 'bool',
			'display_widget_responsive_right' => 'bool',
			'display_widget_pc_bottom' => 'bool',
			'display_widget_pc_left' => 'bool',
			'display_widget_pc_right' => 'bool',
			'display_widget_mobile_bottom' => 'bool',
			'display_widget_mobile_left' => 'bool',
			'display_widget_mobile_right' => 'bool',
		);

		$new_input = array();
		foreach ( $valid_keys as $key => $type ) {
			if ( ! isset( $input[ $key ] ) ) {
				continue;
			}

			$value = $input[ $key ];
			if ( 'bool' === $type ) {
				$value = (bool) $value;
			} elseif ( 'integer' === $type ) {
				$value = intval( $value );
			}

			$new_input[ $key ] = $value;
		}

		return $new_input;
	}
}
