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
 * Loader file include
 */
require_once PNS_PATH . 'includes/class-loader.php';

/**
 * Plugin start
 */
function pns_run_plugin() {
	$plugin = new PNS_Loader();
	$plugin->run();
}

pns_run_plugin();