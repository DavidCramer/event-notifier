<?php
/**
 * Config Array for Dashboard
 *
 * @package   evenotebuilder
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */

return array(
	'label'   => __( 'Dashboard', 'event-notifier' ),
	'control' => array(
		'info'   => array(
			'type'     => 'template',
			'template' => EVENT_NOTIFY_PATH . 'includes/dashboard-help.php',
		),
		'enable' => array(
			'label'    => __( 'Enabled', 'event-notifier' ),
			'type'     => 'toggle',
			'off_icon' => 'dashicons-no',
		),
	),
);
