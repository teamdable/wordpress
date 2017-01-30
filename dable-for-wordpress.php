<?php
/*
Plugin Name: Dable for WordPress
Description: Adds meta tags for Dable and SEO.
Author: Dable
Version: 1.0
Author URI: https://dable.io
*/
/**
 * @package dable
 * @version 1.0
 */

define( 'DABLE_PLUGIN_BASENAME', plugin_basename(__FILE__) );

require __DIR__ . '/class.dable.php';
new Dable();

if ( is_admin() ) {
	require __DIR__ . '/class.dable-admin.php';
	new DableAdmin();
}

