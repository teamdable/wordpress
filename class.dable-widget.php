<?php

class DableWidget extends WP_Widget {
	var $description = '???';

	static function register() {
		register_widget( 'DableWidget' );
	}

	function __construct() {
		parent::__construct(
			'dable_widget',
			esc_html__( 'Dable', 'dable' ),
			array(
				'description' => esc_html__( 'Displays a Dable banner.', 'dable' )
			)
		);
	}

	function widget( $args, $instance ) {
		if ( ! Dable::is_eligible_post_type() ) {
			return;
		}

		if ( empty( $instance['widget_id']) ) {
			echo '<!-- ' . __('Dable widget ID is not defined.', 'dable') . ' -->';
			return;
		}

		$widget_id = $instance['widget_id'];

		echo '<!-- ' . esc_html__( 'Begin Dable Script / For inquiries, support@dable.io', 'dable' ) . ' / generated by Dable for WordPress -->';
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) && empty( $instance['hide_title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}
		echo "<div id='dablewidget_{$widget_id}' data-widget_id='{$widget_id}'><script>";
		echo "(function(d,a){d[a]=d[a]||function(){(d[a].q=d[a].q||[]).push(arguments)};}(window,'dable'));dable('renderWidget', 'dablewidget_{$widget_id}');";
		echo '</script></div>';
		echo $args['after_widget'];
		echo '<!-- ' . esc_html__( 'End Dable Script / For inquiries, support@dable.io', 'dable' ) . ' -->';
	}

	function update( $new_instance, $old_instance ) {
		$instance = array(
			'title' => empty( $new_instance['title'] ) ? '' : sanitize_text_field( $new_instance['title'] ),
			'widget_id' => empty( $new_instance['title'] ) ? '' : sanitize_text_field( $new_instance['widget_id'] ),
			'hide_title' => empty( $new_instance['hide_title'] ) ? false : true
		);

		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Dable widget title' , 'dable' );
		$widget_id = isset( $instance['widget_id'] ) ? $instance['widget_id'] : '';
?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'dable' ); ?></label>
			<input
				type="text"
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				value="<?php echo esc_attr( $title ); ?>"
			>
		</p>
		<p>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $this->get_field_id( 'hide_title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'hide_title' ) ); ?>"
				value="true"
				<?php if ( isset( $instance['hide_title'] ) && $instance['hide_title']  ): ?>
				checked
				<?php endif; ?>
			>
			<label for="<?php echo esc_attr( $this->get_field_id( 'hide_title' ) ); ?>">
				<?php esc_html_e( 'Do not show title.' ); ?>
			</label>

		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'widget_id' ) ); ?>"><?php esc_html_e( 'Widget ID:', 'dable' ); ?></label>
			<input
				type="text"
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'widget_id' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'widget_id' ) ); ?>"
				value="<?php echo esc_attr( $widget_id ); ?>"
			>
			<span><?php
				echo wp_kses(
					__( 'If you are not sure about this, please contact <a href="mailto:support@dable.io">support@dable.io</a>', 'dable' ),
					array(
						'a' => array( 'href' => array() )
					)
				);
			?></span>
		</p>
<?php
	}
}

add_action( 'widgets_init', 'DableWidget::register' );
