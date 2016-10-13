<?php
/**
 * EVENT_NOTIFY Helper Functions
 *
 * @package   evenote
 * @author    David Cramer
 * @license   GPL-2.0+
 * @copyright 2016 David Cramer
 */


/**
 * EVENT_NOTIFY Object class autoloader.
 * It locates and finds class via classes folder structure.
 *
 * @since 1.0.0
 *
 * @param string $class class name to be checked and autoloaded
 */
function evenote_autoload_class( $class ) {
	$parts = explode( '\\', $class );
	$name  = array_shift( $parts );
	if ( file_exists( EVENT_NOTIFY_PATH . 'classes/' . $name ) ) {
		if ( ! empty( $parts ) ) {
			$name .= '/' . implode( '/', $parts );
		}
		$class_file = EVENT_NOTIFY_PATH . 'classes/' . $name . '.php';
		if ( file_exists( $class_file ) ) {
			include_once $class_file;
		}
	}
}

/**
 * EVENT_NOTIFY Helper to minipulate the overall UI instance.
 *
 * @since 1.0.0
 */
function evenote() {
	$request_data = array(
		'post'    => $_POST,
		'get'     => $_GET,
		'files'   => $_FILES,
		'request' => $_REQUEST,
		'server'  => $_SERVER,
	);

	// init UI
	return \evenote\ui::get_instance( $request_data );
}
