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
			$size = 250;
			if ( isset( $og_settings['thumbnail_width'] ) ) {
				$width = intval( $og_settings['thumbnail_width'] );
				if ( $width > 500 ) {
					$size = 600;
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

function dable_migrate_from_3_to_4() {
	$sites = is_multisite() ? get_sites() : array( true );

	foreach ( $sites as $site ) {
		if ( is_multisite() ) {
			switch_to_blog( $site->blog_id );
		}

		$widget_settings = get_option( 'dable-widget-settings', array() );

		$platforms = array( 'responsive', 'pc', 'mobile' );

		foreach ( $platforms as $plat ) {
			// Migrate bottom to post only (old version only supported post)
			$old_code = "widget_code_{$plat}_bottom";
			$old_display = "display_widget_{$plat}_bottom";

			if ( isset( $widget_settings[ $old_code ] ) ) {
				$widget_settings[ "widget_code_{$plat}_post_bottom" ] = $widget_settings[ $old_code ];
				unset( $widget_settings[ $old_code ] );
			}

			if ( isset( $widget_settings[ $old_display ] ) ) {
				$widget_settings[ "display_widget_{$plat}_post_bottom" ] = $widget_settings[ $old_display ];
				unset( $widget_settings[ $old_display ] );
			}

			// Migrate left/right to post category
			foreach ( array( 'left', 'right' ) as $pos ) {
				$old_code = "widget_code_{$plat}_{$pos}";
				$old_display = "display_widget_{$plat}_{$pos}";

				if ( isset( $widget_settings[ $old_code ] ) ) {
					$widget_settings[ "widget_code_{$plat}_post_{$pos}" ] = $widget_settings[ $old_code ];
					unset( $widget_settings[ $old_code ] );
				}

				if ( isset( $widget_settings[ $old_display ] ) ) {
					$widget_settings[ "display_widget_{$plat}_post_{$pos}" ] = $widget_settings[ $old_display ];
					unset( $widget_settings[ $old_display ] );
				}
			}
		}

		update_option( 'dable-widget-settings', $widget_settings );

		if ( is_multisite() ) {
			restore_current_blog();
		}
	}

	return true;
}
