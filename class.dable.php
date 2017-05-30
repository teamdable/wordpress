<?php
class Dable
{
	private $options = array();

	public function __construct()
	{
		$this->options = Dable::get_option();

		add_action( 'wp_head', array( $this, 'print_header' ) );
		add_filter( 'the_content',  array( $this, 'add_content_wrapper' ) );
	}

	public static function get_option()
	{
		$defaults = array(
			'print_og_tag' => true,
			'wrap_content' => true,
			'service_name' => ''
		);
		$options = get_option('dable-settings', $defaults);

		return $options;
	}

	public function print_header()
	{
		if ( ! is_single() ) return;

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

		$categories = get_the_category( $post->ID );
		foreach ( $categories as $idx=>$category ) {
			$meta[ 'article:section' . ( $idx > 0 ? $idx + 1 : '' ) ] = $category->name;
		}

		rewind_posts();

		// Print meta tags
		foreach( $meta as $property=>$content ) {
			echo '<meta property="' . $property  . '" content="' . esc_attr( $content ) . '">';
		}

		// Print Dable JavaScript
		$this->print_script();
	}

	public function print_script()
	{
		$service_name = '';
		if ( wp_is_mobile() && ! empty( $this->options['service_name_mobile'] ) ) {
			$service_name = $this->options['service_name_mobile'];
		} elseif ( ! empty( $this->options['service_name']) ) {
			$service_name = $this->options['service_name'];
		}

		// Do not print the dable script if the service name is undefined.
		if ( $service_name === '' ) {
			return;
		}
?>
	<!-- Dable 스크립트 시작 / 문의 support@dable.io -->
	<script>
	(function(d,a,b,l,e,_) { d[b]=d[b]||function(){(d[b].q=d[b].q||[]).push(arguments)};e=a.createElement(l);e.async=1;e.charset='utf-8';e.src='//static.dable.io/dist/plugin.min.js';_=a.getElementsByTagName(l)[0];_.parentNode.insertBefore(e,_);})(window,document,'dable','script');
	dable('setService', '<?php echo $service_name ?>');
	dable('sendLog');
	</script>
	<!-- Dable 스크립트 종료 / 문의 support@dable.io -->
<?php
	}

	public function get_thumbnail( $post )
	{
		if ( has_post_thumbnail( $post ) ) {
			return get_the_post_thumbnail_url( $post, 'medium' );
		}
		
		$content = get_the_content();

		if ( preg_match( '#<img[^>]*src=(["\'])(?P<url>.+?)\1[^>]*>#i', $content, $match ) > 0 ) {
			return $match['url'];
		}

		return null;
	}

	public function add_content_wrapper( $content )
	{
		if ( ! empty( $this->options['wrap_content'] ) ) {
			$content = '<div itemprop="articleBody" class="dable-content-wrapper">' . $content . '</div>';
		}

		if ( ! empty( $this->options['display_widget'] ) && ! empty( $this->options['widget_code']) ) {
			$content .= '<!-- Dable widget: generated by Dable for Wordpress -->' . $this->options['widget_code'];
		}

		return $content;
	}
}
