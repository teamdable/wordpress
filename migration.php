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
	}

	if ( is_multisite() ) {
		restore_current_blog();
	}

	return true;
}
