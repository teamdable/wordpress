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
		wp_enqueue_style( 'dable-for-wordpress', plugin_dir_url( __FILE__ ) . 'assets/style-admin.css', array(), DABLE_PLUGIN_VERSION );
		wp_enqueue_script( 'dable-for-wordpress', plugin_dir_url( __FILE__ ) . 'assets/admin.js', array( 'jquery' ), DABLE_PLUGIN_VERSION );

		// Default Settings
		register_setting(
			'dable-settings-group',
			'dable-settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' )
			)
		);
		add_settings_section(
			'default-section',
			__('Default Settings', 'dable'),
			array( $this, 'print_default_section_info' ),
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
			'wrap_content',
			__('Content Wrapper Setting', 'dable'),
			array( $this, 'print_content_wrapper_option' ),
			'dable-for-wordpress',
			'default-section'
		);

		register_setting(
			'dable-settings-group',
			'dable-target-post-types'
		);
		add_settings_field(
			'target_post_types',
			__('Target Post Types', 'dable'),
			array( $this, 'print_post_type_option' ),
			'dable-for-wordpress',
			'default-section'
		);

		// Open Graph Settings
		register_setting(
			'dable-settings-group',
			'dable-og-settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' )
			)
		);
		add_settings_section(
			'og-section',
			__('Open Graph', 'dable'),
			null,
			'dable-for-wordpress'
		);

		add_settings_field(
			'print_og_tag',
			__('Meta Tags', 'dable'),
			array( $this, 'print_og_tag_option' ),
			'dable-for-wordpress',
			'og-section'
		);

		add_settings_field(
			'og_thumbnail_size',
			__('Thumbnail Size', 'dable'),
			array( $this, 'pring_thumbnail_size_option' ),
			"dable-for-wordpress",
			"og-section"
		);

		// Widget Settings
		register_setting(
			'dable-settings-group',
			'dable-widget-settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize' )
			)
		);
		add_settings_section(
			'widget-section',
			__('Widget Settings', 'dable'),
			null,
			'dable-for-wordpress'
		);
		add_settings_field(
			'display_widget',
			__('Widget', 'dable'),
			array( $this, 'print_widget_option' ),
			'dable-for-wordpress',
			'widget-section'
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
			// Default settings
			'service_name' => 'string',
			'service_name_mobile' => 'string',
			'wrap_content' => 'bool',
			'target_post_types' => 'array',

			// Open Graph settings
			'print_og_tag' => 'bool',

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
			}

			$new_input[ $key ] = $value;
		}

		return $new_input;
	}

	/**
	 * Print default section information
	 */
	public function print_default_section_info() {
		echo __('Please enter the required default settings.', 'dable');
	}

	/**
	 * Generate OpenGraph options
	 */
	public function print_og_tag_option() {
		$print_og_tag = $this->get_option( 'print_og_tag' );
?>
		<p>
			<input type="radio" id="print_og_tag_yes" name="dable-og-settings[print_og_tag]" <?php echo $print_og_tag ? 'checked' : ''; ?> value="true">
			<label for="print_og_tag_yes">
				<?php
					echo wp_kses(
						__('Create <a href="http://ogp.me/">Open Graph</a> meta tags.', 'dable'),
						array('a' => array('href' => array() ) )
					);
				?>
			</label>
		</p>
		<p>
			<input type="radio" id="print_og_tag_no" name="dable-og-settings[print_og_tag]" <?php echo $print_og_tag ? '' : 'checked'; ?> value="">
			<label for="print_og_tag_no">
				<?php esc_html_e('Do not generate Open Graph meta tags. Select this option if you are using a plugin that already has the same functionality.', 'dable'); ?>
			</label>
		</p>
<?php
	}

	public function pring_thumbnail_size_option() {
		$min_width =$this->get_option( 'thumbnail_width', 250 );
		$min_height =$this->get_option( 'thumbnail_height', 250 );
?>
		<fieldset>
			<legend class="screen-reader-text"><?php esc_html_e( 'Open Graph thunbmail size' ); ?></legend>
			<label for="dable_thumbnail_width"><?php esc_html_e( 'Max Width' ); ?></label>
			<input type="text" id="dable_thumbnail_width" name="dable-og-settings[thumbnail_width]" size="5" value="<?php echo $min_width; ?>">
			<br>
			<label for="dable_thumbnail_height"><?php esc_html_e( 'Max Height' ); ?></label>
			<input type="text" id="dable_thumbnail_height" name="dable-og-settings[thumbnail_height]" size="5" value="<?php echo $min_width; ?>">
		</fieldset>
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
			<label for="service_name" class="dable-input-tag__tag"><?php esc_html_e( 'Desktop', 'dable' ); ?></label>
			<input
				type="text"
				id="service_name"
				name="dable-settings[service_name]"
				value="<?php echo esc_attr( $service_name ) ?>"
				class="regular-text dable-input-tag__input">
		</p>
		<p class="dable-input-tag">
			<label for="service_name_mobile" class="dable-input-tag__tag"><?php esc_html_e( 'Mobile', 'dable' ); ?></label>
			<input
				type="text"
				id="service_name_mobile"
				name="dable-settings[service_name_mobile]"
				value="<?php echo esc_attr( $service_mobile ); ?>"
				class="regular-text dable-input-tag__input">
		</p>
		<p><?php esc_html_e('Service Name for Dable Script.', 'dable'); ?></p>
<?php
	}

	/**
	 * Generate widget code fields.
	 *
	 * @param string $widget_type Widget type.
	 * @param string $platform Platform type.
	 */
	protected function print_widget_code_field( $widget_type, $platform = '' ) {
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
					name="dable-widget-settings[display_widget_<?php echo esc_attr( $key ); ?>]"
					<?php echo $this->get_option( 'display_widget_' . $key ) ? 'checked' : '' ?>
					value="true">
				<label for="display_widget_<?php echo esc_attr( $key ); ?>">
					<?php echo $platform ? esc_html( $platform . ' ' ) : '' ?>
					<?php echo esc_html( $text ); ?>
				</label>
			</p>
			<p>
				<textarea
					placeholder="<?php esc_attr_e( 'Enter widget script.', 'dable' ); ?>"
					name="dable-widget-settings[widget_code_<?php echo esc_attr( $key ); ?>]"
					class="large-text"
					rows="4"
				><?php echo esc_html( $this->get_option( 'widget_code_' . $key ) ); ?></textarea>
			</p>
<?php
		endforeach;
	}

	/**
	 * Print target post types option
	 */
	public function print_post_type_option() {
		$post_types = get_option( 'dable-target-post-types', array( 'post' ) );
		$registered_post_types = get_post_types( array('public'=>true), 'objects' );

		foreach ( $registered_post_types as $key => $type ) :
?>
		<input
			type="checkbox"
			id="target_post_type_<?php echo esc_attr( $key ); ?>"
			name="dable-target-post-types[]"
			value="<?php echo esc_attr( $type->name ); ?>"
			<?php echo in_array( $key, $post_types, true ) ? 'checked' : '' ?>
		/>
		<label for="target_post_type_<?php echo esc_attr( $key ); ?>" class="dable-inline-checkbox">
			<?php echo esc_html( $type->label ); ?>
		</label>
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
			<input type="radio" id="widget_type_responsive" name="dable-widget-settings[widget_type]" <?php echo 'responsive' === $widget_type ? 'checked' : '' ?> value="responsive">
			<label for="widget_type_responsive"><?php echo __('Script for responsive web', 'dable'); ?></label>
			<input type="radio" id="widget_type_platform" name="dable-widget-settings[widget_type]" <?php echo 'platform' === $widget_type ? 'checked' : '' ?> value="platform">
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
