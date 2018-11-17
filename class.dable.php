<?php

class Dable
{
	private $options = array();

	public function __construct() {
		$this->options = Dable::get_options();

		add_action( 'wp_head', array( $this, 'print_header' ) );
		add_filter( 'the_content',  array( $this, 'add_content_wrapper' ) );
		add_image_size(
			'dable-og-thumbnail',
			$this->options['thumbnail_size'],
			$this->options['thumbnail_size']
		);
	}

	public static function get_options() {
		$defaults = array(
			'print_og_tag' => true,
			'service_name' => '',
			'service_name_mobile' => '',
			'widget_type' => 'responsive',
			'thumbnail_size' => 200
		);
		$options = get_option( 'dable-settings', $defaults );

		// target post types
		$post_types = get_option( 'dable-target-post-types', array( 'post' ) );
		$options['target_post_types'] = $post_types;

		// Open Graph settings
		$og_options = get_option( 'dable-og-settings', array() );

		// widget settings
		$widget_options = get_option( 'dable-widget-settings', array() );

		$options = array_merge( $defaults, $options, $og_options, $widget_options );

		return $options;
	}

	public static function is_eligible_post_type() {
		$post_types = get_option( 'dable-target-post-types', array( 'post' ) );
		return is_singular( $post_types );
	}

	public function print_header() {
		if ( ! Dable::is_eligible_post_type() ) {
			return;
		}

		the_post();
		$post = get_post();

		$meta = array(
			'dable:item_id' => $post->ID,
			'article:published_time' => get_the_date( 'c', $post ),
		);

		$thumbnail = $this->get_thumbnail( $post );
		if ( $thumbnail ) {
			$meta['dable:image'] = $thumbnail;
			$meta['og:image'] = $thumbnail;
		}

		if ( ! empty( $this->options['print_og_tag'] ) ) {
			$meta['og:url'] = get_permalink( $post );
			$meta['og:title'] = get_the_title( $post );
			$meta['og:description'] = get_the_excerpt( $post );
		} else {
			unset( $meta['og:image'] );
		}

		if (
			get_post_status( $post->ID ) !== 'publish' ||
			post_password_required( $post ) ||
			! empty($post->post_password)
		) {
			unset( $meta['dable:item_id'] );
		}

		$categories = get_the_category( $post->ID );
		foreach ( $categories as $idx=>$category ) {
			$meta[ 'article:section' . ( $idx > 0 ? $idx + 1 : '' ) ] = $category->name;
		}

		rewind_posts();

		// Print meta tags.
		foreach ( $meta as $property => $content ) {
			echo '<meta property="' . esc_attr( $property )  . '" content="' . esc_attr( $content ) . '">';
		}

		// Print Dable JavaScript.
		$this->print_script();
	}

	public function print_script() {
		$service_name = '';
		if ( wp_is_mobile() && ! empty( $this->options['service_name_mobile'] ) ) {
			$service_name = $this->options['service_name_mobile'];
		} elseif ( ! empty( $this->options['service_name']) ) {
			$service_name = $this->options['service_name'];
		}

		// Do not print the dable script if the service name is undefined.
		if ( '' === $service_name ) {
			return;
		}
?>
  <!-- <?php echo __('Begin Dable Script / For inquiries, support@dable.io'); ?> / Generated by Dable for WordPress -->
	<script>
	(function(d,a,b,l,e,_) { d[b]=d[b]||function(){(d[b].q=d[b].q||[]).push(arguments)};e=a.createElement(l);e.async=1;e.charset='utf-8';e.src='//static.dable.io/dist/plugin.min.js';_=a.getElementsByTagName(l)[0];_.parentNode.insertBefore(e,_);})(window,document,'dable','script');
	dable('setService', '<?php echo $service_name ?>');
	dable('sendLog');
	</script>
	<!-- <?php echo __('End Dable Script / For inquiries, support@dable.io'); ?> -->
<?php
	}

	public function get_thumbnail( $post ) {
		$image_id = null;

		if ( has_post_thumbnail( $post ) ) {
			$image_id = get_post_thumbnail_id( $post );
		} elseif( ! empty( $media = get_attached_media( 'image', $post->ID ) ) ) {
			$image = current( $media );
			$image_id = $image->ID;
		}

		if ( $image_id ) {
			// Does it has dable-og-thumbnail size image?
			$attach_data = wp_get_attachment_metadata( $image_id, true );

			// If not, create a new thumbnail for og:image.
			if ( ! is_array($attach_data) || ! isset($attach_data['sizes']['dable-og-thumbnail']) ) {
				if  ( ! function_exists( 'wp_crop_image' ) ) {
					require_once ABSPATH . 'wp-admin/includes/image.php';
				}

				$filename = get_attached_file( $image_id );
				$attach_data = wp_generate_attachment_metadata( $image_id, $filename );
				wp_update_attachment_metadata( $image_id, $attach_data );
			}

			return wp_get_attachment_image_url( $image_id, 'dable-og-thumbnail' );
		}

		$content = get_the_content();

		// It must be an external resource. We can't create a thumbnail so just use it as is.
		if ( preg_match( '#<img[^>]*src=(["\'])(?P<url>.+?)\1[^>]*>#i', $content, $match ) > 0 ) {
			return $match['url'];
		}

		return null;
	}

	protected function get_widget_code( $key ) {
		if ( empty( $this->options[ 'display_widget_' . $key ] ) || empty( $this->options[ 'widget_code_' . $key ] ) ) {
			return '';
		}

		return $this->options[ 'widget_code_' . $key ];
	}

	public function add_content_wrapper( $content ) {
		if ( is_feed() ) {
			return $content;
		}

		if ( ! empty( $this->options['wrap_content'] ) ) {
			$content = '<div itemprop="articleBody" class="dable-content-wrapper">' . $content . '</div>';
		}

		$key = 'responsive';
		if ( 'platform' === $this->options['widget_type'] ) {
			$key = wp_is_mobile() ? 'mobile' : 'pc';
		}

		$content = $this->get_widget_code( "{$key}_left" ) . $content;
		$content = $this->get_widget_code( "{$key}_right" ) . $content;
		$content = $content . $this->get_widget_code( "{$key}_bottom" );

		return $content;
	}
}
