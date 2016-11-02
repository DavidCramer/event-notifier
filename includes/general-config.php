<?php
/**
 * Config Array for General
 *
 * @package   evenotebuilder
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */

return array(
	'label'   => __( 'General', 'event-notifier' ),
	'grid'    => array(
		'id'  => 'email_config',
		'row' => array(
			array(
				'column' => array(
					array(
						'size'    => 'col-xs-6',
						'control' => array(
							'event'       => array(
								'label'       => __( 'Event Hook', 'event-notifier' ),
								'description' => __( 'The name of the filter / action to be notified of', 'event-notifier' ),
								'type'        => 'text',
							),
						),
					),
					array(
						'size'    => 'col-xs-6',
						'control' => array(
							'description' => array(
								'label'       => __( 'Description', 'event-notifier' ),
								'description' => __( 'Admin note on the purpose of this notifier.', 'event-notifier' ),
								'type'        => 'text',
							),
						),
					),
					array(
						'size'    => 'col-xs-12',
						'control' => array(
							'content' => array(
								'label'       => __( 'Content', 'event-notifier' ),
								'description' => __( 'The content to send. Magic tags enabled.', 'event-notifier' ) . ' <br><a target="_blank" href="https://github.com/Desertsnowman/magic-tags">https://github.com/Desertsnowman/magic-tags</a>',
								'type'        => 'textarea',
								'rows'        => 6,
								'value'       => __( 'Event Notification Details: {{details}}', 'event-notifier' ),
							),
							'recurrence'  => array(
								'label'       => __( 'Recurrence', 'event-notifier' ),
								'description' => __( 'How many times this hook must fire before the notifier is sent.', 'event-notifier' ),
								'type'        => 'number',
								'value'       => '1',
								'attributes'  => array(
									'style' => 'width:90px;',
								),
							),
						),
					),
				),
			),
		),
	),
);
