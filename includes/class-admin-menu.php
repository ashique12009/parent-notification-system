<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PNS_Admin_Menu {
  public function __construct() {
    add_action( 'admin_menu', [$this, 'register_menu'] );
    add_action( 'admin_menu', [$this, 'reorder_submenu'], 999 );
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
    ?>

    <div class="wrap">
      <h1>Parent Notification Dashboard</h1>
      <p>Welcome to the Parent Notification System.</p>
    </div>

    <?php
  }
}