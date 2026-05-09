<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 20px;
    }

    .wrapper {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
    }

    .button {
      display: inline-block;
      padding: 12px 20px;
      background: #0073aa;
      color: #fff !important;
      text-decoration: none;
      border-radius: 4px;
    }

    .footer {
      margin-top: 20px;
      font-size: 12px;
      color: #777;
    }
  </style>
</head>

<body>

  <div class="wrapper">

    <h2><?php echo esc_html( $post->post_title ); ?></h2>

    <p>A new notice has been published.</p>

    <p>
      <a class="button"
        href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
        Read Full Notice
      </a>
    </p>

    <div class="footer">
      Thank you.
    </div>

  </div>

</body>

</html>