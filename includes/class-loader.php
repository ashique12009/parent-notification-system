<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Loader Class
 */
class PNS_Loader {

  /**
	 * Constructor
	 * object create হলে auto run হয়
	 */
	public function __construct() {

		// Future classes include হবে এখানে
		// Example:
		// require_once PNS_PATH . 'includes/class-cpt.php';

    // CPT file include
	  require_once PNS_PATH . 'includes/class-cpt.php';

    // Helper file include
    require_once PNS_PATH . 'includes/helpers.php';

    // Mailer file include
    require_once PNS_PATH . 'includes/class-mailer.php';

    // Email Queue Schedule Setup include
    require_once PNS_PATH . 'includes/email-queue-process.php';
    
    // Email file include
    require_once PNS_PATH . 'includes/class-email.php';

    // API file include
    require_once PNS_PATH . 'includes/class-api.php';

    // Admin Menu file include
    require_once PNS_PATH . 'includes/class-admin-menu.php';
	}

  /**
	 * Plugin run function
	 */
	public function run() {

		// সব hook/action এখান থেকে call হবে
		add_action( 'init', array( $this, 'plugin_init' ) );

    // CPT object create
	  new PNS_CPT();

    // Mailer config init
    PNS_Plugin_Mailer::init();

    // Email object create
    new PNS_Email();

    // Email Queue Schedule Setup object create
    new PNS_Email_Queue_Schedule_Setup();

    // API object create
    new PNS_API();

    // Admin Menu object create
    new PNS_Admin_Menu();
	}

  /**
	 * init hook এ run হবে
	 */
	public function plugin_init() {
		// এখন শুধু test message
		// future এ CPT, roles etc run হবে
	}

}