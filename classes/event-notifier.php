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
		if ( empty( $data['general']['event'] ) ) {
			$message[] = esc_html__( 'An Event Hook is required.', 'event-notifier' );
		}

		if ( empty( $data['general']['email'] ) || ! is_email( $data['general']['email'] ) ) {
			$message[] = esc_html__( 'A valid Email Address is required.', 'event-notifier' );
		}

		if ( ! empty( $message ) ) {
			wp_send_json_error( implode( '<br>', $message ) );
		}


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
					'height'       => 570,
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
						'width'       => 400,
						'height'      => 480,
						'template'    => EVENT_NOTIFY_PATH . 'includes/admin-template.php',
						'top_tabs'    => true,
						'section'     => array(
							'general' => array(
								'label'   => __( 'General', 'event-notifier' ),
								'control' => array(
									'event'  => array(
										'label'       => __( 'Event Hook', 'event-notifier' ),
										'description' => __( 'The name of the filter / action to be notified of', 'event-notifier' ),
										'type'        => 'text',
									),
									'email'  => array(
										'label'       => __( 'Email Address', 'event-notifier' ),
										'description' => __( 'The email address of to send details to.', 'event-notifier' ),
										'type'        => 'text',
									),
									'enable' => array(
										'label'       => __( 'Notifier Status', 'event-notifier' ),
										'type'        => 'toggle',
										'off_icon'     => 'dashicons-no',
									),
								),
							),
							'notice'  => array(
								'label'   => __( 'Notification', 'event-notifier' ),
								'control' => array(
									'subject' => array(
										'label'       => __( 'Email Subject', 'event-notifier' ),
										'description' => __( 'The subject of the notification email.', 'event-notifier' ),
										'type'        => 'text',
										'value'       => __( 'Event Notification', 'event-notifier' ),
									),
									'message' => array(
										'label'       => __( 'Email Message', 'event-notifier' ),
										'description' => __( 'Content of the notification.', 'event-notifier' ),
										'type'        => 'textarea',
										'rows'        => 6,
										'value'       => __( 'Event Notification Details: {{details}}', 'event-notifier' ),
									),
								),
							),
						),
						'footer'      => array(
							'id'      => 'status',
							'control' => array(
								'add_item' => array(
									'label'      => __( 'Create Notifier', 'evenote' ),
									'type'       => 'button',
									'attributes' => array(
										'type' => 'submit',
										'data-state' => 'add',
									),
								),
								'update_item' => array(
									'label'      => __( 'Update Notifier', 'evenote' ),
									'type'       => 'button',
									'attributes' => array(
										'type' => 'submit',
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
		if ( empty( $event['general']['event'] ) || empty( $event['general']['enable'] ) ) {
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

		//create a var_dump of the arguments passed.
		// having xdebug helps alot here.
		ob_start();
		echo '<pre>';
		var_dump( $arguments );
		echo '</pre>';
		$details = ob_get_clean();

		foreach ( $this->events[ $name ] as $event ) {
			$message = str_replace( '{{details}}', $details, $event['notice']['message'] );
			wp_mail( $event['general']['email'], $event['notice']['subject'], $message );
		}
	}
}
