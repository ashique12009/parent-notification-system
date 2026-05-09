<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

require_once PNS_PATH . 'includes/trait-dashboard-query.php';

class PNS_Admin_Menu {
  use PNS_Dashboard_Stats;

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

  // Only load assets on our plugin page
  public function load_assets($hook) {
    // only our plugin page
    if (strpos($hook, 'pns-dashboard') === false) {
      return;
    }

    wp_enqueue_style(
      'pns-dashboard-css',
      PNS_URL . 'assets/css/admin-style.css',
      [],
      time()
    );

    // Chart.js CDN
    wp_enqueue_script(
      'chart-js',
      'https://cdn.jsdelivr.net/npm/chart.js',
      [],
      '4.4.0',
      true
    );

    // custom dashboard js
    wp_enqueue_script(
      'pns-dashboard-js',
      plugins_url( 'assets/js/dashboard.js', dirname(__FILE__) ),
      ['chart-js'],
      '1.0',
      true
    );

    /**
     * Pass API URL + nonce
     */
    wp_localize_script(
      'pns-dashboard-js',
      'pnsDashboard',
      [
        'apiUrl' => rest_url( 'pns/v1/dashboard-stats' ),
        'nonce'  => wp_create_nonce( 'wp_rest' ),
      ]
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
    require_once PNS_PATH . 'templates/admin/dashboard.php';
  }
}