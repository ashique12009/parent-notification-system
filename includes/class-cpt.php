<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Post Type Class
 */
class PNS_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {

		// WordPress init hook এ CPT register হবে
		add_action( 'init', array( $this, 'register_notice_cpt' ) );
	}

	/**
	 * Register Notice Board CPT
	 */
	public function register_notice_cpt() {

		$labels = array(
			'name'               => 'Notice Board',
			'singular_name'      => 'Notice',
			'add_new'            => 'Add New Notice',
			'add_new_item'       => 'Add New Notice',
			'edit_item'          => 'Edit Notice',
			'new_item'           => 'New Notice',
			'view_item'          => 'View Notice',
			'search_items'       => 'Search Notices',
			'not_found'          => 'No Notices Found',
			'menu_name'          => 'Notice Board',
		);

		$args = array(

			'labels' => $labels,

			'public' => true, // frontend থেকেও accessible

			'show_in_menu' => 'pns-dashboard', // admin menu show করবে, 'pns-dashboard' এর submenu হিসেবে

			'menu_icon' => 'dashicons-megaphone',

			'supports' => array(
				'title',
				'editor',
				'thumbnail',
			),

			'show_in_rest' => true, // Gutenberg support

			'has_archive' => true,

			'rewrite' => array(
				'slug' => 'notice-board',
			),
		);

		register_post_type( 'notice_board', $args );
	}
}