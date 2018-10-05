<?php
function dable_migrate_from_1_to_2() {
	$sites = is_multisite() ? get_sites() : array( true );

	foreach ( $sites as $site ) {
		if ( is_multisite() ) {
			switch_to_blog( $site->blog_id );
		}

		$settings = get_option( 'dable-settings', array() );

		$og_settings = array();
		$widget_settings = array();

		foreach( $settings as $key => $value ) {
			if ( 'print_og_tag' === $key ) {
				$og_settings[$key] = $value;
			} elseif ( false !== strpos( $key, 'widget' ) ) {
				$widget_settings[$key] = $value;
			}
		}

		if ( ! empty( $og_settings ) ) {
			add_option( 'dable-og-settings', $og_settings );
		}
		if ( ! empty( $widget_settings ) ) {
			add_option( 'dable-widget-settings', $widget_settings );
		}

		if ( is_multisite() ) {
			restore_current_blog();
		}
	}

	return true;
}
function dable_migrate_from_2_to_3() {
	$sites = is_multisite() ? get_sites() : array( true );

	foreach( $sites as $site ) {
		if ( is_multisite() ) {
			switch_to_blog( $site->blog_id );
		}

		$og_settings = get_option( 'dable-og-settings', array() );

		if ( empty( $og_settings['thumbnail_size'] ) ) {
			$size = 200;
			if ( isset( $og_settings['thumbnail_width'] ) ) {
				$width = intval( $og_settings['thumbnail_width'] );
				if ( $width >= 600 ) {
					$size = 600;
				} elseif ( $width >= 300 ) {
					$size = 400;
				}
			}
			$og_settings['thumbnail_size'] = $size;
		}

		unset( $og_settings['thumbnail_width'], $og_settings['thumbnail_height'] );

		update_option( 'dable-og-settings', $og_settings );

		if ( is_multisite() ) {
			restore_current_blog();
		}
	}

	return true;
}
