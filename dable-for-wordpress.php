<?php
/*
Plugin Name: Dable for WordPress
Description: Adds meta tags for Dable and SEO.
Author: Dable
Text Domain: dable
Version: 3.0.1
Author URI: http://dable.io
Domain Path: /languages
*/
/**
 * @package dable
 * @version 3.0.1
 */

define( 'DABLE_PLUGIN_VERSION', '3.0.0' );
define( 'DABLE_PLUGIN_BASENAME', plugin_basename(__FILE__) );
define( 'DABLE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define( 'DABLE_PLUGIN_URL', plugin_dir_url(__FILE__));
define( 'DABLE_SECRET_KEY', 'SYAJYEKIPANIGULPSSERPDROWELBAD' );

require_once DABLE_PLUGIN_DIR . 'lib/functions.php';
require_once DABLE_PLUGIN_DIR . 'class.dable.php';
require_once DABLE_PLUGIN_DIR . 'class.dable-api.php';
require_once DABLE_PLUGIN_DIR . 'class.dable-widget.php';

new Dable();
new DableAPI();

if ( is_admin() ) {
	require_once DABLE_PLUGIN_DIR . 'class.dable-admin.php';
	new DableAdmin();
}
