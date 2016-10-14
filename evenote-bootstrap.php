<?php
/**
 * EVENT_NOTIFY Bootstrapper / WordPress Plugin
 *
 * @package   evenote
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 *
 * @wordpress-plugin
 * Plugin Name: EVENT_NOTIFY
 * Plugin URI:  http://cramer.co.za
 * Description: UI Framework for WordPress Plugins.
 * Version:     1.0.0
 * Author:      David Cramer
 * Author URI:  http://cramer.co.za
 * Text Domain: evenote
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */
// If this file is called directly, abort.
if ( defined( 'WPINC' ) ) {

	if ( ! defined( 'EVENT_NOTIFY_CORE' ) ) {
		define( 'EVENT_NOTIFY_CORE', __FILE__ );
		define( 'EVENT_NOTIFY_PATH', plugin_dir_path( __FILE__ ) );
		/**
		 *
		 */
		define( 'EVENT_NOTIFY_URL', plugin_dir_url( __FILE__ ) );
		define( 'EVENT_NOTIFY_VER', '1.0.0' );
	}
	if ( ! defined( 'EVENT_NOTIFY_ASSET_DEBUG' ) ) {
		if ( ! defined( 'DEBUG_SCRIPTS' ) ) {
			define( 'EVENT_NOTIFY_ASSET_DEBUG', '.min' );
		} else {
			define( 'EVENT_NOTIFY_ASSET_DEBUG', '' );
		}
	}


	// include evenote helper functions and autoloader.
	require_once( EVENT_NOTIFY_PATH . 'includes/functions.php' );

	// register evenote autoloader
	spl_autoload_register( 'evenote_autoload_class', true, false );

	// bootstrap plugin load
	add_action( 'plugins_loaded', 'evenote' );

}
