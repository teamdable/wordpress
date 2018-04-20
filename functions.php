<?php

// Load localized strings
function dable_textdomain() {
	load_plugin_textdomain( 'dable', false, DABLE_PLUGIN_DIR . 'languages' );
}
add_action( 'plugins_loaded', 'dable_textdomain' );

function dable_migrate() {
	$installed_major_version = intval( get_site_option('dable_plugin_version', '1.3.4') );
	$current_major_version = intval( DABLE_PLUGIN_VERSION );
	error_log( $installed_major_version );

	if ( $installed_major_version !== $current_major_version ) {
		require_once DABLE_PLUGIN_DIR . 'migration.php';
	}

	for( $i = $installed_major_version; $i < $current_major_version; $i++ ) {
		$migrate_function = 'dable_migrate_from_' . $i . '_to_' . ( $i + 1 );

		if ( ! function_exists( $migrate_function ) ) {
			continue;
		}

		$result = call_user_func( $migrate_function );
		if ( false === $result ) {
			error_log( 'An error occured while migrating Dable plugin from ' . $i . '.x to ' . ( $i + 1 ) . '.x' );
			break;
		}
	}
}
add_action( 'plugin_loaded', 'dable_migrate' );
