<?php
/**
 * About modal template
 */

?>
<img style="max-width: 100%;" src="<?php echo esc_attr( EVENT_NOTIFY_URL . 'assets/images/about.png' ); ?>">
<p>
	<?php esc_html_e( 'Event Notifier is a diagnostic tool, aimed at providing email notifications when WordPress hooks / event occur.', 'event-notifier' ); ?>
	<?php esc_html_e( 'It was created out of the need to be notified when "fail" hooks are triggered, thereby informing us of the event.', 'event-notifier' ); ?>
</p>
<p><?php esc_html_e( 'Since certain hooks fire frequently, the amount of notifications can be significant. So, be sure to only setup events on the exact hook that is needed.', 'event-notifier' ); ?></p>
<strong><?php esc_html_e( 'Pro Tip!', 'event-notifier' ); ?></strong>
<p><?php esc_html_e( 'If you are a developer, implement your own hooks in your plugins so that you can fire off events for specific notifications.', 'event-notifier' ); ?></p>

