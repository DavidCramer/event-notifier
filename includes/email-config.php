<?php
/**
 * Config Array for Email
 *
 * @package   evenotebuilder
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */

return array(
	'label'   => __( 'Email', 'event-notifier' ),
	'grid'    => array(
		'id'  => 'email_config',
		'row' => array(
			array(
				'column' => array(
					array(
						'size'    => 'col-xs-12',
						'control' => array(
							'email'   => array(
								'label'       => __( 'Email Address', 'event-notifier' ),
								'description' => __( 'The email address of to send details to.', 'event-notifier' ),
								'type'        => 'text',
							),
						),
					),
				),
			),
			array(
				'column' => array(
					array(
						'size'    => 'col-xs-12',
						'control' => array(
							'subject' => array(
								'label'       => __( 'Email Subject', 'event-notifier' ),
								'description' => __( 'The subject of the notification email.', 'event-notifier' ),
								'type'        => 'text',
								'value'       => __( 'Event Notification', 'event-notifier' ),
							),
							'enable' => array(
								'label'    => __( 'Enable Email Notifications', 'event-notifier' ),
								'type'     => 'toggle',
								'off_icon' => 'dashicons-no',
							),
						),
					),
				),
			),

		),
	),
);
