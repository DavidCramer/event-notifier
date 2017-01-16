<?php
// If this file is called directly, abort.
if ( defined( 'WPINC' ) ) {

	if ( ! defined( 'DEBUG_SCRIPTS' ) ) {
		define( 'EVENT_NOTIFY_ASSET_DEBUG', '.min' );
	} else {
		define( 'EVENT_NOTIFY_ASSET_DEBUG', '' );
	}

	// include evenote helper functions and autoloader.
	require_once( EVENT_NOTIFY_PATH . 'includes/functions.php' );

	// register evenote autoloader
	spl_autoload_register( 'evenote_autoload_class', true, false );

	// bootstrap plugin load
	add_action( 'plugins_loaded', 'evenote' );

}
