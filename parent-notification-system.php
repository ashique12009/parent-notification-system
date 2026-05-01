<?php
/**
 * Plugin Name: Parent Notification System
 * Plugin URI: #
 * Description: Send notices to parents and children via email.
 * Version: 1.0.0
 * Author: Ashique Mahamud
 * Text Domain: pns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin root path define
 * later include file load easy হবে
 */
define( 'PNS_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Roles class load
 */
require_once PNS_PATH . 'includes/class-roles.php';

/**
 * Loader class load
 */
require_once PNS_PATH . 'includes/class-loader.php';

/**
 * Activation Hook
 */
register_activation_hook( __FILE__, array( 'PNS_Roles', 'activate' ) );

/**
 * Deactivation Hook
 */
register_deactivation_hook( __FILE__, array( 'PNS_Roles', 'deactivate' ) );

/**
 * Plugin start
 */
function pns_run_plugin() {
	$plugin = new PNS_Loader();
	$plugin->run();
}

pns_run_plugin();