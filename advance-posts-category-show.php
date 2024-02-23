<?php
/**
 * Plugin Name: Advance Category Posts Widget
 * Plugin URI: 
 * Description: Provides a smart widget that shows posts from the selected category using tons of options.
 * Version: 1.0.1
 * Author: saurav.rox
 * Author URI: saurabadhikari.com.np
 * License: GPLv3 or later
 * Text Domain: advance-category-posts-widget
 * Domain Path: /languages/
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ACPW_BASE_PATH', dirname( __FILE__ ) );
define( 'ACPW_FILE_URL', plugins_url( '', __FILE__ ) );


//loading main file of the plugin.
include ACPW_BASE_PATH.'/assets.php';
include ACPW_BASE_PATH.'/init.php';