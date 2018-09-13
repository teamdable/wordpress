<?php
/*
Plugin Name: Dable for WordPress
Description: Adds meta tags for Dable and SEO.
Author: Dable
Text Domain: dable
Version: 2.0.1
Author URI: http://dable.io
Domain Path: /languages
*/
/**
 * @package dable
 * @version 2.0.1
 */

define( 'DABLE_PLUGIN_VERSION', '2.0.1' );
define( 'DABLE_PLUGIN_BASENAME', plugin_basename(__FILE__) );
define( 'DABLE_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once DABLE_PLUGIN_DIR . 'lib/functions.php';
require_once DABLE_PLUGIN_DIR . 'class.dable.php';
require_once DABLE_PLUGIN_DIR . 'class.dable-widget.php';

new Dable();

if ( is_admin() ) {
	require_once DABLE_PLUGIN_DIR . 'class.dable-admin.php';
	new DableAdmin();
}
