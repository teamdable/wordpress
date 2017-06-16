<?php

class DableAdmin {
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
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_filter( 'plugin_action_links_' . DABLE_PLUGIN_BASENAME, array( $this, 'add_plugin_action' ) );
	}

	/**
	 * Insert a link to the Dable admin page into the Settings section
	 */
	public function add_plugin_page() {
		add_options_page(
			'Dable for WordPress',
			'Dable for WordPress',
			'manage_options',
			'dable-for-wordpress',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Initialize the admin page
	 */
	public function page_init() {
		wp_enqueue_style( 'dable-for-wordpress', plugin_dir_url( __FILE__ ) . 'style-admin.css' );

		wp_enqueue_script( 'dable-for-wordpress', plugin_dir_url( __FILE__ ) . 'admin.js', array( 'jquery' ) );

		register_setting(
			'dable-settings-group',
			'dable-settings',
			array( $this, 'sanitize' )
		);
		add_settings_section(
			'default-section',
			__('Default Settings', 'dable'),
			array( $this, 'print_section_info' ),
			'dable-for-wordpress'
		);
		add_settings_field(
			'service_name',
			__('Service Name', 'dable'),
			array( $this, 'print_service_name_option' ),
			'dable-for-wordpress',
			'default-section'
		);
		add_settings_field(
			'print_og_tag',
			__('Open Graph Tag Setting', 'dable'),
			array( $this, 'print_og_option' ),
			'dable-for-wordpress',
			'default-section'
		);
		add_settings_field(
			'wrap_content',
			__('Content Wrapper Setting', 'dable'),
			array( $this, 'print_content_wrapper_option' ),
			'dable-for-wordpress',
			'default-section'
		);
		add_settings_field(
			'display_widget',
			__('Widget', 'dable'),
			array( $this, 'print_widget_option' ),
			'dable-for-wordpress',
			'default-section'
		);
	}

	/**
	 * Add 'Settings' link to the plugin list.
	 *
	 * @param  array $actions plugin actions for Dable.
	 * @return array
	 */
	public function add_plugin_action( $actions ) {
		$actions['settings'] = '<a href="options-general.php?page=dable-for-wordpress">' . __( 'Settings' ) . '</a>';
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
?>
		<div class="wrap">
			<h1>Dable for WordPress</h1>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'dable-settings-group' );
				do_settings_sections( 'dable-for-wordpress' );
				submit_button();
			?>
			</form>
		</div>
<?php
	}

	/**
	 * Sanitize form input values.
	 *
	 * @param  array $input input values.
	 * @return array sanitize values to save.
	 */
	public function sanitize( $input ) {
		$valid_keys = array(
			'service_name' => 'string',
			'service_name_mobile' => 'string',
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

			// Should cast below values into boolean values.
			'print_og_tag' => 'bool',
			'wrap_content' => 'bool',
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
			}

			$new_input[ $key ] = $value;
		}

		return $new_input;
	}

	/**
	 * Print section information
	 */
	public function print_section_info() {
		echo __('Please enter the required default settings.', 'dable');
	}

	/**
	 * Generate OpenGraph options
	 */
	public function print_og_option() {
		$print_og_tag = $this->get_option( 'print_og_tag' ) ? ' checked' : '';
?>
		<input type="checkbox" id="print_og_tag" name="dable-settings[print_og_tag]" <?php echo esc_attr( $print_og_tag ); ?> value="true">
		<label for="print_og_tag"><?php
			echo wp_kses(
				__('Create <a href="http://ogp.me/">Open Graph</a> tags. If you are using a plugin that already has the same functionality, uncheck it.', 'dable'),
				array('a' => array('href' => array() ) )
			);
		?></label>
<?php
	}

	/**
	 * Generate content wrapper options
	 */
	public function print_content_wrapper_option() {
		$wrap_content = $this->get_option( 'wrap_content' ) ? ' checked' : '';
?>
		<input type="checkbox" id="wrap_content" name="dable-settings[wrap_content]" <?php echo esc_attr( $wrap_content ); ?> value="true" >
		<label for="wrap_content"><?php
			echo wp_kses(
				__('Wrap content with <code>&lt;div itemprop="articleBody"&gt;</code>. Uncheck if you do not need it.', 'dable'),
				array('code' => array() )
			);
		?></label>
<?php
	}

	/**
	 * Generate service name options
	 */
	public function print_service_name_option() {
		$service_name = $this->get_option( 'service_name', '' );
		$service_mobile = $this->get_option( 'service_name_mobile', '' );
?>
		<p class="dable-input-tag">
			<label for="service_name" class="dable-input-tag__tag">Desktop</label>
			<input
				type="text"
				id="service_name"
				name="dable-settings[service_name]"
				value="<?php echo esc_attr( $service_name ) ?>"
				class="regular-text dable-input-tag__input">
		</p>
		<p class="dable-input-tag">
			<label for="service_name_mobile" class="dable-input-tag__tag">Mobile</label>
			<input
				type="text"
				id="service_name_mobile"
				name="dable-settings[service_name_mobile]"
				value="<?php echo esc_attr( $service_mobile ); ?>"
				class="regular-text dable-input-tag__input">
		</p>
		<p><?php echo __('Service Name for Dable Script.', 'dable'); ?></p>
<?php
	}

	/**
	 * Generate widget code fields.
	 *
	 * @param string $widget_type Widget type.
	 * @param string $platform Platform type.
	 */
	protected function print_widget_code_field( $widget_type, $platform = '' ) {
		$placeholder = __('Enter widget script.', 'dable');
		$widget_pos	= array(
			'bottom' => __('Bottom of article', 'dable'),
			'left' => __('Left side of article', 'dable'),
			'right' => __('Right side of article', 'dable')
		);

		foreach ( $widget_pos as $key => $text ) :
			$key = strtolower( $platform ? $platform : $widget_type ) . '_' . $key;
?>
			<p>
				<input
					type="checkbox"
					id="display_widget_<?php echo esc_attr( $key ); ?>"
					name="dable-settings[display_widget_<?php echo esc_attr( $key ); ?>]"
					<?php echo $this->get_option( 'display_widget_' . $key ) ? 'checked' : '' ?>
					value="true">
				<label for="display_widget_<?php echo esc_attr( $key ); ?>">
					<?php echo $platform ? esc_html( $platform . ' ' ) : '' ?>
					<?php echo esc_html( $text ); ?>
				</label>
			</p>
			<p>
				<textarea
					placeholder="<?php echo esc_attr( $placeholder ); ?>"
					name="dable-settings[widget_code_<?php echo esc_attr( $key ); ?>]"
					class="large-text"
					rows="4"><?php echo esc_html( $this->get_option( 'widget_code_' . $key ) ); ?></textarea>
			</p>
<?php
		endforeach;
	}

	/**
	 * Generate widget options
	 */
	public function print_widget_option() {
		$widget_type = $this->get_option( 'widget_type', 'responsive' );
?>
		<p>
			<input type="radio" id="widget_type_responsive" name="dable-settings[widget_type]" <?php echo 'responsive' === $widget_type ? 'checked' : '' ?> value="responsive">
			<label for="widget_type_responsive"><?php echo __('Script for responsive web', 'dable'); ?></label>
			<input type="radio" id="widget_type_platform" name="dable-settings[widget_type]" <?php echo 'platform' === $widget_type ? 'checked' : '' ?> value="platform">
			<label for="widget_type_platform"><?php echo __('Script for PC/Mobile version', 'dable'); ?></label>
		</p>
		<hr />

		<fieldset class="dable-widget-responsive <?php echo 'responsive' !== $widget_type ? 'hidden' : ''; ?>">
		<?php $this->print_widget_code_field( 'responsive' ); ?>
		</fieldset>
		
		<fieldset class="dable-widget-platform <?php echo 'platform' !== $widget_type ? 'hidden' : ''; ?>">
		<?php $this->print_widget_code_field( 'platform', 'PC' ); ?>
		<?php $this->print_widget_code_field( 'platform', 'Mobile' ); ?>
		</fieldset>
<?php
	}
}
