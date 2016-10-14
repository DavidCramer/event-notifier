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
	'control' => array(
		'event'  => array(
			'label'       => __( 'Event Hook', 'event-notifier' ),
			'description' => __( 'The name of the filter / action to be notified of', 'event-notifier' ),
			'type'        => 'text',
		),
		'description' => array(
			'label'       => __( 'Description', 'event-notifier' ),
			'description' => __( 'Admin note on the purpose of this notifier.', 'event-notifier' ),
			'type'        => 'textarea',
		),
	),
);
