<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * REST API Class
 */
class PNS_API {
  /**
   * Constructor
   */
  public function __construct() {
    /**
     * REST API route register
     */
    add_action( 'rest_api_init', [$this, 'register_routes'] );
  }

  /**
   * Register API routes
   */
  public function register_routes() {
    register_rest_route(
      'pns/v1',
      '/notices',
      [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => [$this, 'get_latest_notices'],
        'permission_callback' => '__return_true', // public access
      ]
    );

    register_rest_route(
			'pns/v1',
			'/dashboard-stats',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'dashboard_stats' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
  }

  /**
   * Get latest 3 notices
   */
  public function get_latest_notices( $request ) {
    $args = [
      'post_type'      => 'notice_board',
      'post_status'    => 'publish',
      'posts_per_page' => 3,
      'orderby'        => 'date',
      'order'          => 'DESC',
    ];

    $query = new WP_Query( $args );

    $data = [];

    if ( $query->have_posts() ) {
      while ( $query->have_posts() ) {
        $query->the_post();

        $data[] = [
          'id'      => get_the_ID(),
          'title'   => get_the_title(),
          'content' => wp_strip_all_tags( get_the_content() ),
          'date'    => get_the_date( 'Y-m-d H:i:s' ),
        ];
      }

      wp_reset_postdata();
    }

    return rest_ensure_response( $data );
  }

  public function dashboard_stats() {

		global $wpdb;

		$table = $wpdb->prefix . 'pns_email_queue';

		$parent = $this->count_role_sent_emails( 'parent' );
		$child  = $this->count_role_sent_emails( 'child' );

		$sent = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$table} WHERE status = %s",
				'sent'
			)
		);

		$failed = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$table} WHERE status = %s",
				'failed'
			)
		);

		return rest_ensure_response(
			array(
				'parent' => (int) $parent,
				'child'  => (int) $child,
				'sent'   => (int) $sent,
				'failed' => (int) $failed,
			)
		);
	}

  private function count_role_sent_emails( $role ) {

		global $wpdb;

		$table = $wpdb->prefix . 'pns_email_queue';

		$users = get_users(
			array(
				'role'   => $role,
				'fields' => array( 'user_email' ),
			)
		);

		if ( empty( $users ) ) {
			return 0;
		}

		$emails = wp_list_pluck( $users, 'user_email' );

		$placeholders = implode( ',', array_fill( 0, count( $emails ), '%s' ) );

		$params = array_merge( array( 'sent' ), $emails );

		$sql = "
			SELECT COUNT(*)
			FROM {$table}
			WHERE status = %s
			AND email IN ($placeholders)
		";

		return (int) $wpdb->get_var(
			$wpdb->prepare( $sql, $params )
		);
	}
}