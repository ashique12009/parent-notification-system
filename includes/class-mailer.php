<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class My_Plugin_Mailer {
  public static function init() {
    add_action('phpmailer_init', [__CLASS__, 'mailtrap_config']);
  }

  public static function mailtrap_config($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 2525;
    $phpmailer->Username = '8c47d5a7abace5';
    $phpmailer->Password = '****e37e';
  }
}