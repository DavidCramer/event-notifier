<?php

/**
 * Event_Notifier Main Class
 *
 * @package   evenotebuilder
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */
class Event_Notifier {

	/**
	 * Holds instance of the class
	 *
	 * @since   1.0.0
	 *
	 * @var     Event_Notifier
	 */
	private static $instance;

	/**
	 * Holds the admin page object
	 *
	 * @since   1.0.0
	 *
	 * @var     \evenote\ui\page
	 */
	private $admin_page;

	/**
	 * Holds the list of active events
	 *
	 * @since   1.0.0
	 *
	 * @var     array
	 */
	private $events = array();

	/**
	 * Event_Notifier constructor.
	 */
	public function __construct() {

		// create admin objects
		add_action( 'plugins_loaded', array( $this, 'register_admin' ) );
		// setup notifications
		add_action( 'init', array( $this, 'setup' ) );
		// add required fields check
		add_action( 'evenote_control_item_submit_config', array( $this, 'verify_config' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return  Event_Notifier  A single instance
	 */
	public static function init() {

		// If the single instance hasn't been set, set it now.
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Verifies required fields are entered when adding an event notifier
	 *
	 * @since 1.0.0
	 *
	 */
	public function verify_config( $data ) {

		$message = array();
		// check if a hook is set.
		if ( empty( $data['general']['event'] ) ) {
			$message[] = esc_html__( 'An Event Hook is required.', 'event-notifier' );
		}
		// check email enabled and has address
		$message[] = $this->is_email_config_valid( $data );

		// check slack enabled and has url
		$message[] = $this->is_slack_config_valid( $data );

		$message = array_filter( $message );

		if ( ! empty( $message ) ) {
			wp_send_json_error( implode( '<br>', $message ) );
		}


	}

	/**
	 * Verifies required fields are entered for email notification
	 *
	 * @since 1.0.0
	 * @param array $event array config of event.
	 * @return null|string
	 */
	public function is_email_config_valid( $data ) {
		$message = null;
		if ( ! empty( $data['notice']['enable'] ) ) {
			if ( empty( $data['notice']['email'] ) || ! is_email( $data['notice']['email'] ) ) {
				$message = esc_html__( 'A Valid Email address is required.', 'event-notifier' );
			}
		}
		return $message;
	}

	/**
	 * Verifies required fields are entered for Slack notification
	 *
	 * @since 1.0.0
	 * @param array $event array config of event.
	 * @return null|string
	 */
	public function is_slack_config_valid( $data ) {
		$message = null;
		if ( ! empty( $data['slack']['enable'] ) ) {
			if ( empty( $data['slack']['url'] ) || ! filter_var( $data['slack']['url'], FILTER_VALIDATE_URL ) ) {
				$message = esc_html__( 'A Valid Webhook URL is required.', 'event-notifier' );
			}
		}
		return $message;
	}

	/**
	 * Register the admin pages
	 *
	 * @since 1.0.0
	 */
	public function register_admin() {

		$this->admin_page = evenote()->add( 'page', 'event-notifier', $this->admin_core_page() );

	}

	/**
	 * @return evenotebuilder
	 *
	 * @since 1.0.0
	 */
	public function admin_core_page() {

		$structure = array(
			'page_title' => __( 'Event Notifier Admin', 'event-notifier' ),
			'menu_title' => __( 'Event Notifier', 'event-notifier' ),
			'base_color' => '#D81B60',
			'parent'     => 'tools.php',
			'full_width' => true,
			'attributes' => array(
				'data-autosave' => true,
			),
			'header'     => array(
				'id'          => 'admin_header',
				'label'       => __( 'Event Notifier', 'event-notifier' ),
				'description' => __( '1.0.0', 'event-notifier' ),
				'control'     => array(
					array(
						'type' => 'separator',
					),
				),
				'modal'       => array(
					'id'          => 'about',
					'label'       => __( 'About', 'event-notifier' ),
					'description' => __( 'About Event Notifier', 'event-notifier' ),
					'width'       => 450,
					'height'      => 570,
					'attributes'  => array(
						'class' => 'page-title-action',
					),
					'control'     => array(
						'id'       => 'about_text',
						'type'     => 'template',
						'template' => EVENT_NOTIFY_PATH . 'includes/about-template.php',
					),
				),
			),
			'style'      => array(
				'admin' => EVENT_NOTIFY_URL . 'assets/css/admin.css',
			),
			'section'    => array(
				'id'      => 'event',
				'control' => array(
					'id'     => 'config',
					'type'   => 'item',
					'config' => array(
						'label'       => __( 'Create New Notifier', 'event-notifier' ),
						'description' => __( 'Configure Event Notification', 'event-notifier' ),
						'width'       => 500,
						'height'      => 580,
						'template'    => EVENT_NOTIFY_PATH . 'includes/admin-template.php',
						'top_tabs'    => true,
						'section'     => array(
							'general' => include EVENT_NOTIFY_PATH . 'includes/general-config.php',
							'notice'  => include EVENT_NOTIFY_PATH . 'includes/email-config.php',
							'slack'   => include EVENT_NOTIFY_PATH . 'includes/slack-config.php',
						),
						'footer'      => array(
							'id'      => 'status',
							'control' => array(
								'add_item'    => array(
									'label'      => __( 'Create Notifier', 'evenote' ),
									'type'       => 'button',
									'attributes' => array(
										'type'       => 'submit',
										'data-state' => 'add',
									),
								),
								'update_item' => array(
									'label'      => __( 'Update Notifier', 'evenote' ),
									'type'       => 'button',
									'attributes' => array(
										'type'       => 'submit',
										'data-state' => 'update',
									),
								),
							),
						),
					),
				),
			),
		);

		return $structure;
	}

	/**
	 * Setup hooks
	 *
	 * @since 1.0.0
	 */
	public function setup() {

		$data   = $this->admin_page->load_data();
		$config = json_decode( $data['event']['config'], ARRAY_A );
		if ( ! empty( $config ) ) {
			foreach ( $config as $event ) {
				$this->register_notification( $event );
			}
		}
	}

	/**
	 * register notification
	 *
	 * @since 1.0.0
	 *
	 * @param array $event The event config to register
	 */
	public function register_notification( $event ) {
		if ( empty( $event['general']['event'] ) ) {
			return; // no event hook
		}

		// add to active events
		$this->events[ $event['general']['event'] ][] = $event;

		// set args to 10 but might need more to be safe. perhaps a setting would be wise.
		add_action( $event['general']['event'], array( $this, $event['general']['event'] ), 1000, 10 );
	}

	/**
	 * process event notification
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The event being called
	 * @param array $arguments , the arguments being passed to the hook
	 */
	public function __call( $name, $arguments ) {

		foreach ( $this->events[ $name ] as $event ) {
			if ( ! empty( $event['notice']['email'] ) && ! empty( $event['notice']['enable'] ) ) {
				$this->do_email( $event, $arguments );
			}
			if ( ! empty( $event['slack']['url'] ) && ! empty( $event['slack']['enable'] ) ) {
				$this->do_slack( $event, $arguments );
			}
		}
	}

	/**
	 * process email notification
	 *
	 * @since 1.0.0
	 *
	 * @param array $event The event config
	 * @param array $arguments the event message will use
	 */
	public function do_email( $event, $arguments ) {
		if ( empty( $event['notice']['subject'] ) ) {
			$event['notice']['subject'] = __( 'Event Notifier', 'event-notifier' );
		}
		if ( empty( $event['notice']['message'] ) ) {
			$event['notice']['message'] = '{{details}}';
		}
		//create a var_dump of the arguments passed.
		ob_start();
		var_dump( $arguments );
		$message = str_replace( '{{details}}', ob_get_clean(), $event['notice']['message'] );
		wp_mail( $event['notice']['email'], $event['notice']['subject'], $message );

	}

	/**
	 * process slack notification
	 *
	 * @since 1.0.0
	 *
	 * @param array $event The event config
	 * @param array $arguments the event message will use
	 */
	public function do_slack( $event, $arguments ) {

		$payload = array(
			'text' => $event['general']['event'],
		);

		if ( ! empty( $event['slack']['name'] ) ) {
			$payload['username'] = $event['slack']['name'];
		}

		if ( ! empty( $event['slack']['icon'] ) ) {
			$payload['icon_url'] = $event['slack']['icon'];
		}

		if ( ! empty( $event['slack']['channel'] ) ) {
			$payload['channel'] = $event['slack']['channel'];
		}

		// attach if setup
		$arguments = array_filter( $arguments );

		if ( ! empty( $arguments ) ) {
			$fields = array();
			foreach ( $arguments as $key => $value ) {
				if ( is_array( $value ) || is_object( $value ) ) {
					$value = json_encode( $value );
				}
				$fields[] = array(
					'title' => __( 'Argument: ', 'event-notifier' ) . ' ' . ( $key + 1 ),
					'value' => strip_tags( $value ),
					'short' => ( strlen( $value ) < 100 ? true : false ),
				);
			}

			$payload['attachments'] = array(
				array(
					'color'  => $event['slack']['color'],
					'fields' => $fields,
				),
			);
		}

		if ( ! empty( $event['slack']['label'] ) ) {
			$payload['text'] = $event['slack']['label'];
		}

		$args = array(
			'body' => array(
				'payload' => json_encode( $payload ),
			),
		);

		$response = wp_remote_post( $event['slack']['url'], $args );

	}
}
// init
Event_Notifier::init();
