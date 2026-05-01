<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PNS_Plugin_Mailer {
  public static function init() {
    add_action('phpmailer_init', [__CLASS__, 'mailtrap_config']);
  }

  public static function mailtrap_config($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    // 🔥 wp-config.php থেকে read করা হচ্ছে
    $phpmailer->Username = defined('PNS_MAILTRAP_USERNAME') ? PNS_MAILTRAP_USERNAME : '';
    $phpmailer->Password = defined('PNS_MAILTRAP_PASSWORD') ? PNS_MAILTRAP_PASSWORD : '';
  }
}