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
		$svg_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMi4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAzMCAzMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzAgMzA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM5QjlFQTQ7fQ0KPC9zdHlsZT4NCjxnPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0yNi4zLDYuNWwtMS44LTFsMS4xLTEuMWMwLjUtMC41LDAuOS0xLjIsMS0xLjljMC0wLjIsMC0wLjUtMC4zLTAuNWMwLDAtMC4xLDAtMC4xLDBsLTYuMywwLjlsLTMuMy0xLjkNCgkJYy0wLjUtMC4zLTEuMS0wLjUtMS43LTAuNXMtMS4yLDAuMi0xLjcsMC41TDkuOSwyLjhMMy42LDEuOWMwLDAtMC4xLDAtMC4xLDBjLTAuMywwLTAuMywwLjItMC4zLDAuNWMwLjEsMC43LDAuNSwxLjQsMSwxLjkNCgkJbDEuMSwxLjFsLTEuOCwxQzIuNCw3LjEsMS44LDguMiwxLjgsOS40djcuOHYxLjN2Mi4xYzAsMS4yLDAuNiwyLjMsMS43LDIuOWw3LjEsNC4xbDEuMSwwLjZsMS41LDAuOWMwLjUsMC4zLDEuMSwwLjUsMS43LDAuNQ0KCQlzMS4yLTAuMiwxLjctMC41bDkuNy01LjZjMS4xLTAuNiwxLjctMS43LDEuNy0yLjlWOS40QzI4LDguMiwyNy40LDcuMSwyNi4zLDYuNXogTTMuMiwyMC42di0xLjFsNS42LDMuMmMxLDAuNiwxLjYsMS42LDEuNiwyLjgNCgkJdjAuNGwtNi4yLTMuNkMzLjYsMjIsMy4yLDIxLjMsMy4yLDIwLjZ6IE0yNi42LDIwLjZjMCwwLjctMC40LDEuMy0xLDEuN2wtOS43LDUuNmMtMC4zLDAuMi0wLjYsMC4zLTEsMC4zYy0wLjMsMC0wLjctMC4xLTEtMC4zDQoJCUwxMi40LDI3bC0wLjYtMC4zdi0xLjJjMC0xLjctMC45LTMuMi0yLjMtNGwtNi4zLTMuNnYtMC42VjkuNGMwLTAuNywwLjQtMS4zLDEtMS43bDEuOC0xbDEuNi0wLjlMNi4zLDQuNEw1LjUsMy42bDQuMSwwLjZsMC41LDAuMQ0KCQlMMTAuNiw0bDMuMy0xLjljMC4zLTAuMiwwLjYtMC4zLDEtMC4zczAuNywwLjEsMSwwLjNMMTkuMiw0bDAuNCwwLjJsMC41LTAuMWw0LjEtMC42bC0wLjgsMC44bC0xLjMsMS4zbDEuNiwwLjlsMS44LDENCgkJYzAuNiwwLjMsMSwxLDEsMS43VjIwLjZ6Ii8+DQoJPHBhdGggY2xhc3M9InN0MCIgZD0iTTExLjEsMTIuOWMwLDAuOC0wLjYsMS40LTEuNCwxLjRjLTAuOCwwLTEuNC0wLjYtMS40LTEuNGMwLTAuNiwwLjMtMSwwLjgtMS4zbC0xLjQtMC44DQoJCWMtMC43LDAuNS0xLjEsMS4yLTEuMSwyLjFjMCwxLjQsMS4xLDIuNiwyLjYsMi42YzEuMywwLDIuNC0xLDIuNS0yLjRMMTEuMSwxMi45QzExLjEsMTIuOCwxMS4xLDEyLjgsMTEuMSwxMi45eiIvPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xNC45LDEzLjVjLTAuOCwwLTEuNSwwLjctMS41LDEuNWMwLDAuMSwwLDAuMiwwLDAuMmwwLDBjMCwwLDAsMCwwLDBjMCwwLDAsMCwwLDBjMC4yLDEuMSwxLjUsMi41LDEuNSwyLjZ2MA0KCQljMCwwLDAsMCwwLDBjMCwwLDAsMCwwLDB2MGMwLDAsMS4zLTEuNSwxLjUtMi42YzAsMCwwLDAsMCwwYzAsMCwwLDAsMC0wLjFsMCwwYzAtMC4xLDAtMC4yLDAtMC4yQzE2LjQsMTQuMiwxNS43LDEzLjUsMTQuOSwxMy41eiINCgkJLz4NCgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjIuMiwxMC44bC0xLjQsMC44YzAuNSwwLjIsMC44LDAuNywwLjgsMS4zYzAsMC44LTAuNiwxLjQtMS40LDEuNGMtMC44LDAtMS40LTAuNi0xLjQtMS40YzAsMCwwLDAsMC0wLjENCgkJbC0wLjUsMC4zYzAuMSwxLjMsMS4yLDIuNCwyLjUsMi40YzEuNCwwLDIuNi0xLjEsMi42LTIuNkMyMy4zLDEyLDIyLjksMTEuMiwyMi4yLDEwLjh6Ii8+DQo8L2c+DQo8L3N2Zz4NCg==';

		add_menu_page(
			'Dable',
			'Dable',
			'manage_options',
			DableAdmin::PAGE_SLUG,
			array( $this, 'create_admin_page' ),
			$svg_icon
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
