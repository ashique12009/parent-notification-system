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
}