<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! function_exists( 'write_log' ) ) {
  function write_log( $data ) {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
      $log_file = WP_CONTENT_DIR . '/debug.log';

      // Ensure timezone
      $timezone = new DateTimeZone('Europe/Helsinki');
      $date     = new DateTime('now', $timezone);
      $datetime = $date->format('Y-m-d H:i:s');

      if ( is_array( $data ) || is_object( $data ) ) {
				$log_message = '[' . $datetime . '] ' . print_r( $data, true ) . PHP_EOL;
			} else {
				$log_message = '[' . $datetime . '] ' . $data . PHP_EOL;
			}

			error_log( $log_message, 3, $log_file );
    }
  }
}