<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

trait PNS_Dashboard_Stats {
  public function get_dashboard_stats() {
    global $wpdb;

    $table = $wpdb->prefix . 'pns_email_queue';

    $total_emails = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table} WHERE status = %s",
        'sent'
      )
    );

    $total_notices = wp_count_posts( 'notice_board' )->publish;

    $today_notices = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->posts}
				WHERE post_type = %s
				AND post_status = %s
				AND DATE(post_date) = CURDATE()",
        'notice_board',
        'publish'
      )
    );

    // Total parents count
    $total_parents = count_users()['avail_roles']['parent'] ?? 0;

    // Total children count
    $total_children = count_users()['avail_roles']['child'] ?? 0;

    $parent_emails = $this->count_role_sent_emails( 'parent' );
    $child_emails = $this->count_role_sent_emails( 'child' );

    return [
      'total_notices' => (int) $total_notices,
      'today_notices' => (int) $today_notices,
      'total_emails'  => (int) $total_emails,
      'parent_emails' => (int) $parent_emails,
      'child_emails'  => (int) $child_emails,
      'total_parents' => (int) $total_parents,
      'total_children' => (int) $total_children,
    ];
  }

  public function count_role_sent_emails( $role ) {
    global $wpdb;

    $table = $wpdb->prefix . 'pns_email_queue';

    $meta_exists = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*)
				FROM {$wpdb->postmeta}
				WHERE meta_key = %s
				AND meta_value LIKE %s",
        '_pns_roles',
        '%' . $wpdb->esc_like( $role ) . '%'
      )
    );

    if ( ! $meta_exists ) {
      return 0;
    }

    $users = get_users(
      [
        'role'   => $role,
        'fields' => ['user_email'],
      ]
    );

    if ( empty( $users ) ) {
      return 0;
    }

    $emails = wp_list_pluck( $users, 'user_email' );

    $placeholders = implode( ',', array_fill( 0, count( $emails ), '%s' ) );

    $params = array_merge(
      ['sent'],
      $emails
    );

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