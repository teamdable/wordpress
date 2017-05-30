<?php

class DableAdmin
{
	private $options = array();

	public function __construct()
	{
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_filter( 'plugin_action_links_' . DABLE_PLUGIN_BASENAME, array( $this, 'add_plugin_action' ) );
	}

	public function add_plugin_page()
	{
		add_options_page(
			'Dable for WordPress',
			'Dable for WordPress',
			'manage_options',
			'dable-for-wordpress',
			array( $this, 'create_admin_page' )
		);
	}

	public function page_init()
	{
		// add styles
		wp_enqueue_style( 'dable-for-wordpress', plugin_dir_url(__FILE__) . 'style-admin.css' );

		register_setting(
			'dable-settings-group',
			'dable-settings',
			array( $this, 'sanitize' )
		);
		add_settings_section(
			'default-section',
			'기본 설정',
			array( $this, 'print_section_info' ),
			'dable-for-wordpress'
		);
		add_settings_field(
			'service_name',
			'서비스 이름',
			array( $this, 'print_service_name_option' ),
			'dable-for-wordpress',
			'default-section'
		);
		add_settings_field(
			'print_og_tag',
			'Open Graph 태그 출력',
			array( $this, 'print_og_option' ),
			'dable-for-wordpress',
			'default-section'
		);
		add_settings_field(
			'wrap_content',
			'컨텐트 래퍼 출력',
			array( $this, 'print_content_wrapper_option' ),
			'dable-for-wordpress',
			'default-section'
		);
		add_settings_field(
			'display_widget',
			'위젯',
			array( $this, 'print_widget_option' ),
			'dable-for-wordpress',
			'default-section'
		);
	}

	public function add_plugin_action( $actions )
	{
		$actions['settings'] = '<a href="options-general.php?page=dable-for-wordpress">' . __('Settings') . '</a>';
		return $actions;
	}

	public function create_admin_page()
	{
		$this->options = Dable::get_option();
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

	public function sanitize( $input ) 
	{
		$new_input = array_intersect_key(
			$input,
			array( 'service_name' => 1, 'service_name_mobile' => 1, 'widget_code' => 1 )
		);
		if ( isset( $input['print_og_tag'] ) ) {
			$new_input['print_og_tag'] = (bool)$input['print_og_tag'];
		}
		if ( isset( $input['wrap_content'] ) ) {
			$new_input['wrap_content'] = (bool)$input['wrap_content'];
		}
		if ( isset( $input['display_widget'] ) ) {
			$new_input['display_widget'] = (bool)$input['display_widget'];
		}

		return $new_input;
	}

	public function print_section_info()
	{
		echo '필요한 기본 설정을 입력하세요.';
	}

	public function print_og_option()
	{
		$print_og_tag = isset( $this->options['print_og_tag'] ) && $this->options['print_og_tag'] ? ' checked' : '';
		echo '<input type="checkbox" id="print_og_tag" name="dable-settings[print_og_tag]" value="true"'.$print_og_tag.'> ';
		echo '<label for="print_og_tag"><a href="http://ogp.me/">Open Graph</a> 태그를 생성합니다. 이미 같은 기능을 하는 플러그인을 사용하고 있다면 체크를 해제하세요.</code></label>';
	}

	public function print_content_wrapper_option()
	{
		$wrap_content = isset( $this->options['wrap_content'] ) && $this->options['wrap_content'] ? ' checked' : '';
?>
		<input type="checkbox" id="wrap_content" name="dable-settings[wrap_content]" <?php echo $wrap_content ?> value="true" >
		<label for="wrap_content">컨텐트를 <code>&lt;div itemprop="articleBody"&gt;</code>로 감쌉니다. 필요하지 않으면 체크를 해제하세요.</label>
<?php
	}

	public function print_service_name_option()
	{
		$service_name = isset( $this->options['service_name'] ) ? esc_attr( $this->options['service_name'] ) : '';
		$service_mobile = isset( $this->options['service_name_mobile'] ) ? esc_attr( $this->options['service_name_mobile'] ) : '';
?>
		<p class="dable-input-tag">
			<label for="service_name" class="dable-input-tag__tag">Desktop</label>
			<input type="text" id="service_name" name="dable-settings[service_name]" value="<?php echo $service_name ?>" class="regular-text dable-input-tag__input">
		</p>
		<p class="dable-input-tag">
			<label for="service_name_mobile" class="dable-input-tag__tag">Mobile</label>
			<input type="text" id="service_name_mobile" name="dable-settings[service_name_mobile]" value="<?php echo $service_mobile ?>" class="regular-text dable-input-tag__input">
		</p>
		<p>Dable 스크립트에 설정할 서비스 이름입니다.</p>
<?php
	}

	public function print_widget_option()
	{
		$display_widget = isset( $this->options['display_widget'] ) && $this->options['display_widget'] ? 'checked': '';
		$widget_code = esc_html( isset( $this->options['widget_code'] ) ? $this->options['widget_code'] : '' );
		$placeholder = '위젯 코드를 입력하세요.';
?>
		<p>
			<input type="checkbox" id="display_widget" name="dable-settings[display_widget]" <?php echo $display_widget;?> value="true">
			<label for="display_widget">기사 본문 하단에 데이블 위젯을 출력합니다.</label>
		</p>
		<p>
			<textarea
				placeholder="<?php echo $placeholder; ?>"
				name="dable-settings[widget_code]"
				class="large-text"
				rows="5"><?php echo $widget_code; ?></textarea>
		</p>
<?php
	}
}

