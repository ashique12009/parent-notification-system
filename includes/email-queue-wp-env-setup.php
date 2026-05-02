<?php 

class PNS_Email_Queue_Schedule_Setup {

  public function __construct() {
    // Add custom cron schedule
    add_filter('cron_schedules', array( $this, 'add_custom_cron_schedule' ) );

    // Schedule event
    add_action('after_switch_theme', array( $this, 'schedule_email_queue_event' ) );
  }

  public function add_custom_cron_schedule( $schedules ) {
    $schedules['every_2_minute'] = [
      'interval' => 120,
      'display'  => 'Every 2 Minute'
    ];
    return $schedules;
  }

  public function schedule_email_queue_event() {
    if (!wp_next_scheduled('ghb_process_email_queue')) {
      wp_schedule_event(time() + 60, 'every_2_minute', 'ghb_process_email_queue');
    }
  }
}