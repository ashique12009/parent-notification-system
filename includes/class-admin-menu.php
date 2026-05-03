<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PNS_Admin_Menu {
  public function __construct() {
    add_action( 'admin_menu', [$this, 'register_menu'] );
    add_action( 'admin_menu', [$this, 'reorder_submenu'], 999 );

    add_action('admin_enqueue_scripts', [$this, 'load_assets']);
  }

  /**
   * Main menu + dashboard
   */
  public function register_menu() {
    add_menu_page(
      'Parent Notification System',
      'Parent Notification',
      'manage_options',
      'pns-dashboard',
      [$this, 'dashboard_page'],
      'dashicons-megaphone',
      25
    );

    /**
     * Dashboard first item
     */
    add_submenu_page(
      'pns-dashboard',
      'Dashboard',
      'Dashboard',
      'manage_options',
      'pns-dashboard',
      [$this, 'dashboard_page']
    );
  }

  public function load_assets($hook) {
    // only our plugin page
    if (strpos($hook, 'pns-dashboard') === false) {
      return;
    }

    write_log("Loading assets for hook: $hook");

    wp_enqueue_style(
      'pns-dashboard-css',
      PNS_URL . 'assets/css/admin-style.css',
      [],
      time()
    );
  }

  /**
   * Reorder submenu items
   */
  public function reorder_submenu() {
    global $submenu;

    if ( isset( $submenu['pns-dashboard'] ) ) {
      $new_order = [];

      // Dashboard first
      foreach ( $submenu['pns-dashboard'] as $item ) {
        if ( $item[2] === 'pns-dashboard' ) {
          $new_order[] = $item;
        }
      }

      // Notice Board second
      foreach ( $submenu['pns-dashboard'] as $item ) {
        if ( $item[2] === 'edit.php?post_type=notice_board' ) {
          $new_order[] = $item;
        }
      }

      $submenu['pns-dashboard'] = $new_order;
    }
  }

  /**
   * Dashboard Page
   */
  public function dashboard_page() {
    $stats = $this->get_dashboard_stats();
    ?>

    <div class="wrap pns-dashboard">

      <h1>Parent Notification Dashboard</h1>

      <div class="pns-grid">

        <div class="pns-card">
          <h2>
            <?php echo esc_html($stats['total_notices']); ?>
          </h2>
          <p>Total Notices</p>
        </div>

        <div class="pns-card">
          <h2>
            <?php echo esc_html($stats['today_notices']); ?>
          </h2>
          <p>Today's Notices</p>
        </div>

        <div class="pns-card">
          <h2>
            <?php echo esc_html($stats['total_emails']); ?>
          </h2>
          <p>Total Emails Sent</p>
        </div>

        <div class="pns-card">
          <h2>
            <?php echo esc_html($stats['parent_emails']); ?>
          </h2>
          <p>Parent Emails</p>
        </div>

        <div class="pns-card">
          <h2>
            <?php echo esc_html($stats['child_emails']); ?>
          </h2>
          <p>Child Emails</p>
        </div>

      </div>

    </div>

    <?php
  }

  public function get_dashboard_stats() {
    global $wpdb;

    $table = $wpdb->prefix . 'pns_email_queue';

    // Total emails sent
    $total_emails = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table} WHERE status = %s",
        'sent'
      )
    );

    // total notices
    $total_notices = wp_count_posts('notice_board')->publish;

    // today notices
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

    // parent emails
    $parent_emails = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table} 
			 WHERE status = %s AND subject LIKE %s",
        'sent',
        '%parent%'
      )
    );

    // child emails
    $child_emails = $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*) FROM {$table} 
			 WHERE status = %s AND subject LIKE %s",
        'sent',
        '%child%'
      )
    );

    return [
      'total_notices' => $total_notices,
      'today_notices' => $today_notices,
      'total_emails'  => $total_emails,
      'parent_emails' => $parent_emails,
      'child_emails'  => $child_emails,
    ];
  }
}