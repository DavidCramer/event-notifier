<?php
/**
 * @package   event-notifier
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 *
 * @wordpress-plugin
 * Plugin Name: Event Notifier
 * Plugin URI:  http://cramer.co.za
 * Description: Send notifications when WordPress hooks are fired.
 * Version:     1.0.0
 * Author:      David Cramer
 * Author URI:  http://cramer.co.za
 * Text Domain: evenote-demo
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Constants
define( 'EVENT_NOTIFY_PATH', plugin_dir_path( __FILE__ ) );
define( 'EVENT_NOTIFY_CORE', __FILE__ );
define( 'EVENT_NOTIFY_URL', plugin_dir_url( __FILE__ ) );
define( 'EVENT_NOTIFY_VER', '1.0.0' );

if ( ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
	if ( is_admin() ) {
		add_action( 'admin_notices', 'evenote_php_ver' );
	}
}else{
	//Includes and run
	include_once EVENT_NOTIFY_PATH . 'evenote-bootstrap.php';
	include_once EVENT_NOTIFY_PATH . 'classes/event-notifier.php';
}

function evenote_php_ver() {
	$message = __( 'Event Notifier requires PHP version 5.3 or later. We strongly recommend PHP 5.5 or later for security and performance reasons.', 'event-notifier' );
	echo '<div id="evenote_error" class="error notice notice-error"><p>' . $message . '</p></div>';
}