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

    // Email file include
    require_once PNS_PATH . 'includes/class-email.php';
	}

  /**
	 * Plugin run function
	 */
	public function run() {

		// সব hook/action এখান থেকে call হবে

		add_action( 'init', array( $this, 'plugin_init' ) );

    // CPT object create
	  new PNS_CPT();

    // Email object create
    new PNS_Email();
	}

  /**
	 * init hook এ run হবে
	 */
	public function plugin_init() {

		// এখন শুধু test message
		// future এ CPT, roles etc run হবে

		error_log( 'PNS Plugin Loaded Successfully' );
	}

}