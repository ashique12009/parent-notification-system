<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap pns-dashboard">

	<h1>Parent Notification Dashboard</h1>

  <?php $stats = $this->get_dashboard_stats(); ?>

  <div class="pns-chart-row mb-25">

		<div class="pns-chart-box">
			<h3>Parent vs Child Emails</h3>
			<canvas id="roleChart"></canvas>
		</div>

		<div class="pns-chart-box">
			<h3>Email Status</h3>
			<canvas id="statusChart"></canvas>
		</div>

	</div>

	<div class="pns-grid">

		<div class="pns-card">
			<h2><?php echo esc_html( $stats['total_notices'] ); ?></h2>
			<p>Total Notices</p>
		</div>

		<div class="pns-card">
			<h2><?php echo esc_html( $stats['today_notices'] ); ?></h2>
			<p>Today's Notices</p>
		</div>

    <div class="pns-card">
			<h2><?php echo esc_html( $stats['total_parents'] ); ?></h2>
			<p>Total Parents</p>
		</div>

    <div class="pns-card">
      <h2><?php echo esc_html( $stats['total_children'] ); ?></h2>
      <p>Total Children</p>
    </div>

		<div class="pns-card">
			<h2><?php echo esc_html( $stats['total_emails'] ); ?></h2>
			<p>Total Emails Sent</p>
		</div>

		<div class="pns-card">
			<h2><?php echo esc_html( $stats['parent_emails'] ); ?></h2>
			<p>Parent Emails</p>
		</div>

		<div class="pns-card">
			<h2><?php echo esc_html( $stats['child_emails'] ); ?></h2>
			<p>Child Emails</p>
		</div>

	</div>

</div>