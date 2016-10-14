<?php
/**
 * Config Array for Slack
 *
 * @package   evenotebuilder
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */

return array(
	'label'   => __( 'Slack', 'event-notifier' ),
	'grid'    => array(
		'id'  => 'slack_config',
		'row' => array(
			array(
				'column' => array(
					array(
						'size'    => 'col-xs-8',
						'control' => array(
							'url' => array(
								'label'       => __( 'URL', 'event-notifier' ),
								'description' => __( 'Webhook URL.', 'event-notifier' ),
								'type'        => 'text',
							),
						),
					),
					array(
						'size'    => 'col-xs-4',
						'control' => array(
							'channel' => array(
								'label'       => __( 'Channel', 'event-notifier' ),
								'description' => __( 'Channel to post to.', 'event-notifier' ),
								'attributes'  => array(
									'placeholder' => __( 'Optional', 'event-notifier' ),
								),
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
							'label'   => array(
								'label'       => __( 'Descriptive Label', 'event-notifier' ),
								'description' => __( 'Use this label to provide extra context in your list of integrations', 'event-notifier' ),
								'attributes'  => array(
									'placeholder' => __( 'Optional', 'event-notifier' ),
								),
								'type'        => 'text',
							),
						),
					),
				),
			),
			array(
				'column' => array(
					array(
						'size'    => 'col-xs-8',
						'control' => array(
							'name'    => array(
								'label'       => __( 'Customize Name', 'event-notifier' ),
								'description' => __( 'Choose the username to post as.', 'event-notifier' ),
								'attributes'  => array(
									'placeholder' => __( 'Optional', 'event-notifier' ),
								),
								'type'        => 'text',
								'value'       => 'event-notifier-bot',
							),
						),
					),
					array(
						'size'    => 'col-xs-4',
						'control' => array(
							'color'   => array(
								'label'       => __( 'Color', 'event-notifier' ),
								'description' => __( 'Choose a color to be added to the notice.', 'event-notifier' ),
								'type'        => 'color',
								'value'       => '#D81B60',
							),
						),
					),
				),
			),
		),
	),
	'control' => array(
		'enable' => array(
			'label'    => __( 'Enabled', 'event-notifier' ),
			'type'     => 'toggle',
			'off_icon' => 'dashicons-no',
		),
	),
);
