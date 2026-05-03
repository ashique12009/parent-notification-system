<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Email Notification Class
 */
class PNS_Email {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_notice_metabox' ) );

		add_action( 'save_post_notice_board', array( $this, 'save_notice_roles' ) );
	}

	/**
	 * Add meta box
	 */
	public function add_notice_metabox() {
		add_meta_box(
			'pns_notice_roles',
			'Send Notification To',
			array( $this, 'render_metabox' ),
			'notice_board',
			'side',
			'default'
		);
	}

	/**
	 * Meta box HTML
	 */
	public function render_metabox( $post ) {

		$saved_roles = get_post_meta( $post->ID, '_pns_roles', true );

		if ( ! is_array( $saved_roles ) ) {
			$saved_roles = array();
		}

		wp_nonce_field( 'pns_save_roles', 'pns_roles_nonce' );
		?>

		<label>
			<input type="checkbox" name="pns_roles[]" value="parent"
			<?php checked( in_array( 'parent', $saved_roles ) ); ?>>
			Parent
		</label>

		<br><br>

		<label>
			<input type="checkbox" name="pns_roles[]" value="child"
			<?php checked( in_array( 'child', $saved_roles ) ); ?>>
			Child
		</label>

		<?php
	}

	/**
	 * Save checkbox value
	 */
	public function save_notice_roles( $post_id ) {
		if ( ! isset( $_POST['pns_roles_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['pns_roles_nonce'], 'pns_save_roles' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['pns_roles'] ) ) {
      // Post from post_id
      $post = get_post($post_id);

			$roles = array_map( 'sanitize_text_field', $_POST['pns_roles'] );
			update_post_meta( $post_id, '_pns_roles', $roles );

      $subject = 'New Post Published: ' . get_the_title($post->ID);
      $message = "
          <h2>{$post->post_title}</h2>
          <p>A new notice has been published on our website.</p>
          <p><a href='" . get_permalink($post->ID) . "'>Read the full notice</a></p>
      ";

      global $wpdb;
      $table = $wpdb->prefix . 'pns_email_queue';

      foreach ( $roles as $role ) {
        $users = get_users( array(
          'role' => $role,
        ) );

        foreach ( $users as $user ) {
          $email = $user->user_email;

          // Put this emails into the Queue (no wp_mail will send here!)
          $wpdb->insert(
            $table,
            [
              'email'   => $email,
              'subject' => $subject,
              'message' => $message,
              'status'  => 'pending'
            ]
          );
        }
      }
		} else {
			delete_post_meta( $post_id, '_pns_roles' );
		}
	}
}