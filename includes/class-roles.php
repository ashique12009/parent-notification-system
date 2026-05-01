<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * User Roles Class
 */
class PNS_Roles {

	/**
	 * Plugin activate হলে run হবে
	 */
	public static function activate() {

		/**
		 * Parent Role Create
		 */
		add_role(
			'parent', // role key
			'Parent', // display name
			array(
				'read' => true, // login করতে পারবে
			)
		);

		/**
		 * Child Role Create
		 */
		add_role(
			'child',
			'Child',
			array(
				'read' => true,
			)
		);
	}

	/**
	 * Plugin uninstall/deactivate এ remove করতে চাইলে use করা যাবে
	 */
	public static function deactivate() {

		// চাইলে remove করতে পারো
		// remove_role('parent');
		// remove_role('child');
	}
}