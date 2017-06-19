<?php
/*
Plugin Name: Dable for WordPress
Description: Adds meta tags for Dable and SEO.
Author: Dable
Text Domain: dable
Version: 1.3.2
Author URI: https://dable.io
Domain Path: /languages
*/
/**
 * @package dable
 * @version 1.3.2
 */

define( 'DABLE_PLUGIN_BASENAME', plugin_basename(__FILE__) );

function dable_textdomain() {
  load_plugin_textdomain( 'dable', false, dirname(plugin_basename(__FILE__)) . '/languages' );
}
add_action( 'plugins_loaded', 'dable_textdomain' );

require __DIR__ . '/class.dable.php';
new Dable();

if ( is_admin() ) {
	require __DIR__ . '/class.dable-admin.php';
	new DableAdmin();
}

