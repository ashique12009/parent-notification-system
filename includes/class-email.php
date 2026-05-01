<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Email Notification Class
 */
class PNS_Email {

	public function __construct() {

		/**
		 * Meta box add
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_notice_metabox' ) );

		/**
		 * Save checkbox value
		 */
		add_action( 'save_post', array( $this, 'save_notice_roles' ) );

		/**
		 * Publish হলে email send
		 */
		add_action( 'publish_notice_board', array( $this, 'send_notice_email' ), 10, 2 );
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

			$roles = array_map( 'sanitize_text_field', $_POST['pns_roles'] );

			update_post_meta( $post_id, '_pns_roles', $roles );

		} else {

			delete_post_meta( $post_id, '_pns_roles' );
		}
	}

	/**
	 * Publish হলে email যাবে
	 */
	public function send_notice_email( $post_id, $post ) {

		$roles = get_post_meta( $post_id, '_pns_roles', true );

		if ( empty( $roles ) ) {
			return;
		}

		foreach ( $roles as $role ) {

			$users = get_users(
				array(
					'role' => $role,
				)
			);

			foreach ( $users as $user ) {

				$to      = $user->user_email;
				$subject = $post->post_title;
				$message = wp_strip_all_tags( $post->post_content );

				wp_mail( $to, $subject, $message );

        write_log( "Email sent to: {$to} for role: {$role}" );
			}
		}
	}
}