<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! function_exists( 'write_log' ) ) {
  function write_log( $data ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
      $log_file = WP_CONTENT_DIR . '/debug.log';

      if ( is_array( $data ) || is_object( $data ) ) {
        error_log( print_r( $data, true ), 3, $log_file );
      } else {
        error_log( $data . PHP_EOL, 3, $log_file );
      }
    }
  }
}