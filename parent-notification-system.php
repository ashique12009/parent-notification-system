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
define( 'PNS_URL', plugin_dir_url( __FILE__ ) );

// Roles class load
require_once PNS_PATH . 'includes/class-roles.php';

// Loader class load
require_once PNS_PATH . 'includes/class-loader.php';

// Email Queue Table class load
require_once PNS_PATH . 'db/email-queue-process-table.php';

// Activation Hook
register_activation_hook( __FILE__, array( 'PNS_Roles', 'activate' ) );

// Create email queue table on plugin activation
register_activation_hook( __FILE__, array( 'PNS_Email_Queue_Table', 'create_table' ) );

// Email Queue Schedule Setup
register_activation_hook( __FILE__, array( 'PNS_Email_Queue_Schedule_Setup', 'schedule_email_queue_event' ) );

// Deactivation Hook
register_deactivation_hook( __FILE__, array( 'PNS_Roles', 'deactivate' ) );

/**
 * Plugin start
 */
function pns_run_plugin() {
	$plugin = new PNS_Loader();
	$plugin->run();
}

pns_run_plugin();