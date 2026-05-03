<?php 

class PNS_Email_Queue_Schedule_Setup {
  public function __construct() {
    // Add custom cron schedule
    add_filter('cron_schedules', [$this, 'add_custom_cron_schedule'] );

    // Hook for processing email queue
    add_action('pns_process_email_queue', [$this, 'process_email_queue'] );
  }

  public function add_custom_cron_schedule( $schedules ) {
    $schedules['pns_every_2_hours'] = [
      'interval' => 7200, // 2 hours = 7200 sec
      'display'  => 'Every 2 Hours'
    ];
    return $schedules;
  }

  public static function schedule_email_queue_event() {
    if (!wp_next_scheduled('pns_process_email_queue')) {
      wp_schedule_event(time() + 60, 'pns_every_2_hours', 'pns_process_email_queue');
    }
  }

  // Process Email Queue
  public function process_email_queue() {
    global $wpdb;
    $table = $wpdb->prefix . 'pns_email_queue';

    $emails = $wpdb->get_results(
      "SELECT * FROM $table WHERE status='pending' ORDER BY id ASC LIMIT 50"
    );

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    foreach ($emails as $item) {
      // Mark processing
      $wpdb->update(
        $table,
        ['status' => 'processing'],
        ['id'     => $item->id]
      );

      // Send email
      $sent = wp_mail($item->email, $item->subject, $item->message, $headers);

      // Update status
      $wpdb->update(
        $table,
        ['status' => $sent ? 'sent' : 'failed'],
        ['id'     => $item->id]
      );
    }
  }
}