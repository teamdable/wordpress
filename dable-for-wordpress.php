<?php
/*
Plugin Name: Dable for WordPress
Description: Adds meta tags for Dable and SEO.
Author: Dable
Text Domain: dable
Version: 1.3.4
Author URI: http://dable.io
Domain Path: /languages
*/
/**
 * @package dable
 * @version 1.3.4
 */

define( 'DABLE_PLUGIN_VERSION', '1.3.4' );
define( 'DABLE_PLUGIN_BASENAME', plugin_basename(__FILE__) );
define( 'DABLE_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once DABLE_PLUGIN_DIR . 'lib/functions.php';
require_once DABLE_PLUGIN_DIR . 'class.dable.php';

new Dable();

if ( is_admin() ) {
	require_once DABLE_PLUGIN_DIR . 'class.dable-admin.php';
	new DableAdmin();
}

