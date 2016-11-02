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
	 * @var     \uix\ui\page
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
	 * Flag to determine if dashboard widget is active
	 *
	 * @since   1.0.2
	 *
	 * @var     bool
	 */
	private $dashboard = false;

	/**
	 * Holds the magic tags object
	 *
	 * @since   1.0.2
	 *
	 * @var     \evenote\magictag
	 */
	private $magic;

	/**
	 * Holds the current hook arguments being processed
	 *
	 * @since   1.0.2
	 *
	 * @var     array
	 */
	private $args;

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
		// add dashboard setup
		add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard' ) );
		// add ajax
		add_action( 'wp_ajax_dashboard_notifications', array( $this, 'render_dashboard' ) );
		// filter aguments
		add_filter( 'caldera_magic_tag-args', array( $this, 'arguments_magic_tag' ) );

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
	 * Registered the dashboard metabox if notifiers are set for it.
	 *
	 * @since 1.0.0
	 *
	 */
	public function register_dashboard() {
		if ( true === $this->dashboard ) {
			// enqueu baldrick
			wp_enqueue_script( 'baldrick', EVENT_NOTIFY_URL . 'assets/js/jquery.baldrick.min.js', array( 'jquery' ) );

			// if has dashboard
			wp_add_dashboard_widget(
				'event_notifier',
				__( 'Event Notifier', 'event-notifier' ),
				array( $this, 'dashboard' )
			);
		}
	}

	/**
	 * render widget
	 *
	 * @since 1.0.2
	 *
	 */
	public function dashboard() {
		echo '<style>#event_notifier_dashboard_form > div.loading {opacity: 0.6;}</style><form data-before="evenote_pre_dashboard_log" id="event_notifier_dashboard_form" method="POST"class="baldrick" data-request="' . esc_attr( admin_url( 'admin-ajax.php' ) ) . '" data-poll="15000" data-action="dashboard_notifications" data-target="#event_notifier_dashboard" data-autoload="true">';
		echo '<div id="event_notifier_dashboard"></div>';
		echo '<label style="display: inline-block; margin: 2px 0px 0px;"><input type="checkbox" name="full" value="1" data-for="#event_notifier_dashboard_form" data-event="change"> ' . __( 'Full List', 'event-notifier' ) . '</label>';
		echo '<input type="submit" name="clear" class="button button-small clear-log" style="float: right; margin-top:3px; margin-left: 6px;" value="' . esc_attr__( 'Clear Log', 'event-notifier' ) . '">';
		echo '<button type="submit" class="button button-small" style="float: right; margin-top:3px;">' . __( 'Refresh', 'event-notifier' ) . '</button>';
		echo '</form>';
		?>
		<script>
			function evenote_pre_dashboard_log(el, ev) {
				jQuery(el).removeData('clear');
				if (ev.originalEvent) {
					if (jQuery(ev.originalEvent.explicitOriginalTarget).hasClass('clear-log')) {
						if (confirm("<?php esc_attr_e( 'Are you sure you want to clear the logs?', 'event-notifier' ); ?>")) {
							jQuery(el).data('clear', true);
						} else {
							ev.preventDefault();
							return false;
						}
					}
				}
			}
		</script>
		<?php
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
	 *
	 * @param array $event array config of event.
	 *
	 * @return null|string
	 */
	public function is_email_config_valid( $event ) {
		$message = null;
		if ( ! empty( $event['notice']['enable'] ) ) {
			if ( empty( $event['notice']['email'] ) || ! is_email( $event['notice']['email'] ) ) {
				$message = esc_html__( 'A Valid Email address is required.', 'event-notifier' );
			}
		}

		return $message;
	}

	/**
	 * Verifies required fields are entered for Slack notification
	 *
	 * @since 1.0.0
	 *
	 * @param array $event array config of event.
	 *
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
			'base_color' => '#E91E63',
			'parent'     => 'tools.php',
			'full_width' => true,
			'attributes' => array(
				'data-autosave' => true,
			),
			'header'     => array(
				'id'          => 'admin_header',
				'label'       => __( 'Event Notifier', 'event-notifier' ),
				'description' => EVENT_NOTIFY_VER,
				'control'     => array(
					array(
						'type' => 'separator',
					),
				),
				'modal'       => array(
					'id'          => 'about',
					'base_color' => '#3d7fa3',
					'label'       => __( 'About', 'event-notifier' ),
					'description' => __( 'About', 'event-notifier' ),
					'width'       => 450,
					'height'      => 570,
					'attributes'  => array(
						'class' => 'page-title-action',
					),
					'top_tabs'    => true,
					'section'     => array(
						'about' => array(
							'label'   => 'Event Notifier',
							'control' => array(
								'about_text' => array(
									'type'     => 'template',
									'template' => EVENT_NOTIFY_PATH . 'includes/about-template.php',
								),
							),
						),
					),
				),
			),
			'style'      => array(
				'admin' => EVENT_NOTIFY_URL . 'assets/css/admin.css',
			),
			'section'    => array(
				'id'      => 'event',
				'control' => array(
					'id'         => 'config',
					'type'       => 'item',
					'base_color' => '#7CB342',
					'config'     => array(
						'label'       => __( 'Create New Notifier', 'event-notifier' ),
						'description' => __( 'Configure Event Notification', 'event-notifier' ),
						'width'       => 680,
						'height'      => 580,
						'template'    => EVENT_NOTIFY_PATH . 'includes/admin-template.php',
						'top_tabs'    => true,
						'section'     => array(
							'general'   => include EVENT_NOTIFY_PATH . 'includes/general-config.php',
							'dashboard' => include EVENT_NOTIFY_PATH . 'includes/dashboard-config.php',
							'notice'    => include EVENT_NOTIFY_PATH . 'includes/email-config.php',
							'slack'     => include EVENT_NOTIFY_PATH . 'includes/slack-config.php',
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
			// init magictag
			$this->magic = new \evenote\magictag();

			foreach ( $config as $event ) {
				$this->register_notification( $event );
				// is this a dashboard notifier
				if ( ! empty( $event['dashboard']['enable'] ) ) {
					$this->dashboard = true;
				}
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
			// set current arguments
			$this->args = $arguments;
			// recurrence
			$history = $this->get_history( $event );
			if ( empty( $event['general']['recurrence'] ) || count( $history ) >= $event['general']['recurrence'] ) {
				$event['general']['content'] = implode( "\r\n------------------\r\n", $history );
				$this->do_email( $event );
				$this->do_slack( $event );
				$this->do_dashboard( $event );
			}
		}

		return array_shift( $arguments );
	}

	/**
	 * get the recurrence history of an event notification
	 *
	 * @since 1.1.0
	 *
	 * @param array $event The event config to register
	 *
	 * @return the full history of the event.
	 */
	public function get_history( $event ) {
		// compatibility for before the recurrence was added
		if ( ! isset( $event['general']['recurrence'] ) ) {
			$event['general']['recurrence'] = 1;
		}

		// key of the setup
		$key     = md5( json_encode( $event ) );
		$history = get_transient( $key );
		if ( empty( $history ) ) {
			$history = array();
		}
		$line = date( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), current_time( 'timestamp' ) ) . "\r\n";
		$line .= $this->magic->do_magic_tag( $event['general']['content'] );
		$history[] = $line;
		if ( count( $history ) >= $event['general']['recurrence'] ) {
			delete_transient( $key );
		} else {
			set_transient( $key, $history );
		}

		return $history;
	}

	/**
	 * process email notification
	 *
	 * @since 1.0.0
	 *
	 * @param array $event The event config
	 */
	public function do_email( $event ) {
		if ( empty( $event['notice']['email'] ) || empty( $event['notice']['enable'] ) ) {
			return;
		}

		if ( empty( $event['notice']['subject'] ) ) {
			$event['notice']['subject'] = __( 'Event Notifier', 'event-notifier' );
		}

		wp_mail( $event['notice']['email'], $event['notice']['subject'], $event['general']['content'] );

	}

	/**
	 * process slack notification
	 *
	 * @since 1.0.0
	 *
	 * @param array $event The event config
	 */
	public function do_slack( $event ) {

		if ( empty( $event['slack']['url'] ) || empty( $event['slack']['enable'] ) ) {
			return;
		}

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

		$payload['attachments'] = array(
			array(
				'color'  => $event['slack']['color'],
				'fields' => array(
					array(
						'title' => __( 'Message', 'event-notifier' ),
						'value' => $event['general']['content'],
						'short' => false,
					),
				),
			),
		);

		if ( ! empty( $event['slack']['label'] ) ) {
			$payload['text'] .= ': ' . $event['slack']['label'];
		}

		$args = array(
			'body' => array(
				'payload' => json_encode( $payload ),
			),
		);

		$response = wp_remote_post( $event['slack']['url'], $args );

	}

	/**
	 * process dashboard notification
	 *
	 * @since 1.2.0
	 *
	 * @param array $event The event config
	 */
	public function do_dashboard( $event ) {
		if ( empty( $event['dashboard']['enable'] ) ) {
			return;
		}
		$log   = get_option( '_event_notifier_log', array() );
		$log[] = array(
			'event'   => $event['general']['event'],
			'details' => $event['general']['content'],
		);
		update_option( '_event_notifier_log', $log );

	}

	/**
	 * convert arguments.
	 *
	 * @since 1.1.0
	 *
	 * @param string $params The param tag to be converted
	 *
	 * @return string The converted string.
	 */
	public function arguments_magic_tag( $params ) {

		if ( isset( $this->args ) ) {
			return $this->args[ $params ];
		}

		return $params;
	}

	/**
	 * render widget
	 *
	 * @since 1.2.0
	 *
	 */
	public function render_dashboard() {

		if ( ! empty( $_POST['clear'] ) ) {
			delete_option( '_event_notifier_log' );
		}

		$log = get_option( '_event_notifier_log', array() );
		if ( empty( $log ) ) {
			echo '<p class="description" style="background: #f6f6f6 none repeat scroll 0 0;margin: -11px -12px 6px;padding: 12px;border-bottom:1px solid #efefef;">' . esc_html__( 'Event Log is empty', 'event-notifier' ) . '</p>';
			exit;
		}
		$log = array_reverse( $log );

		if ( empty( $_POST['full'] ) ) {
			$log = array_chunk( $log, 10 );
			$log = $log[0];
		}

		echo '<table class="wp-list-table widefat striped">';
		echo '<thead><tr>';
		echo '<th>' . __( 'Event', 'event-notifier' ) . '</th>';
		echo '<th>' . __( 'Details', 'event-notifier' ) . '</th>';
		echo '</tr></thead><tbody id="the-list">';
		foreach ( $log as $entry ) {
			echo '<tr>';

			echo '<td>' . $entry['event'] . '</td>';
			echo '<td>' . nl2br( $entry['details'] ) . '</td>';

			echo '</tr>';
		}
		echo '</tbody></table>';

		exit;
	}
}

// init
Event_Notifier::init();
