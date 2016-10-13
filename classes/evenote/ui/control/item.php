<?php
/**
 * EVENT_NOTIFY Control
 *
 * @package   controls
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */
namespace evenote\ui\control;

use evenote\ui\modal;

/**
 * Modal based config items
 *
 * @since 1.0.0
 */
class item extends \evenote\ui\control {

	/**
	 * The type of object
	 *
	 * @since       1.0.0
	 * @access public
	 * @var         string
	 */
	public $type = 'item';

	/**
	 * The templates to render in the footer
	 *
	 * @since 1.0.0
	 * @access public
	 * @var      string
	 */
	public $templates = null;

	/**
	 * Autoload Children - Checks structure for nested structures
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function setup() {

		$this->struct['modal'] = array(
			'config' => array(
				'label'       => __( 'Add Item', 'evenote' ),
				'description' => __( 'Setup Item', 'evenote' ),
				'callback'    => 'wp_send_json_success',
				'attributes'  => array(
					'class' => 'page-title-action',
					'data-content' => 'evenote_item_control_modal',
				),
				'height'      => 540,
				'width'       => 380,
				'config'      => array(
					'target'        => 'evenote_item_control_modal_handler',
					'control'       => $this->id(),
				),
				'template'    => '{{json this}} <button type="button" class="evenote-item-edit button button-small">' . esc_html__( 'Edit', 'evenote' ) . '</button>',
				'footer'      => array(
					'id'      => $this->slug . '_foot',
					'control' => array(
						'add_item' => array(
							'label'      => __( 'Add', 'evenote' ),
							'type'       => 'button',
							'attributes' => array(
								'type' => 'submit',
								'data-state' => 'add',
							),
						),
						'update_item' => array(
							'label'      => __( 'Update', 'evenote' ),
							'type'       => 'button',
							'attributes' => array(
								'type' => 'submit',
								'data-state' => 'update',
							),
						),
					),
				),
			),
		);

		if ( ! empty( $this->struct['config'] ) ) {
			$this->struct['modal']['config'] = array_merge( $this->struct['modal']['config'], $this->struct['config'] );
			unset( $this->struct['config'] ); // remove so no clashing
		}

		$this->struct['modal']['config']['view'] = $this->struct['modal']['config']['template'];
		unset( $this->struct['modal']['config']['template'] );

		add_action( 'evenote_control_item_submit_' . $this->slug, $this->struct['modal']['config']['callback'] );
		parent::setup();
		$this->handle_submit();

		// set data for templates
		$data          = $this->child['config']->get_data();
		$this->child['config']->struct['attributes']['data-default'] = json_encode( $data );
		$data_template = $this->drill_in( $data );
		$this->child['config']->set_data( $data_template );

	}

	/**
	 * Handles the new item create submission
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function handle_submit() {
		if ( isset( $this->child['config'] ) && $this->child['config']->is_submitted() ) {
			$data = $this->child['config']->get_value();
			if ( isset( $data['config_foot'] ) ) {
				unset( $data['config_foot'] ); // default footer has no values. override to include values in it.
			}
			do_action( 'evenote_control_item_submit_' . $this->slug, $data );
		}
	}

	/**
	 * Define core EVENT_NOTIFY scripts - override to register core ( common scripts for evenote type )
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_assets() {


		$this->assets['script']['handlebars']          = array(
			'src' => $this->url . 'assets/js/handlebars-latest' . EVENT_NOTIFY_ASSET_DEBUG . '.js',
		);
		$this->assets['script']['baldrick-handlebars'] = array(
			'src'  => $this->url . 'assets/js/handlebars.baldrick' . EVENT_NOTIFY_ASSET_DEBUG . '.js',
			'deps' => array( 'baldrick' ),
		);
		$this->assets['script']['item']          = array(
			'src' => $this->url . 'assets/js/item' . EVENT_NOTIFY_ASSET_DEBUG . '.js',
		);
		parent::set_assets();
	}

	/**
	 * Gets the attributes for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_attributes() {

		parent::set_attributes();
		$this->attributes['class'] = 'hidden';
		$this->attributes['value'] = '{{json this}}';

	}

	/**
	 * Render the Control
	 *
	 * @since 1.0.0
	 * @see \evenote\ui\evenote
	 * @access public
	 * @return string HTML of rendered control
	 */
	public function render() {
		add_action( 'admin_footer', array( $this, 'render_footer_script' ) );
		add_action( 'wp_footer', array( $this, 'render_footer_script' ) );

		$this->templates .= $this->item_template();

		$output = $this->child['config']->render();

		$output .= '<div id="' . esc_attr( $this->id() ) . '" data-color="' . esc_attr( $this->base_color() ) . '" data-for="' . esc_attr( $this->id() ) . '-control" class="evenote-control evenote-control-' . esc_attr( $this->type ) . ' ' . esc_attr( $this->id() ) . '">';
		$output .= '</div>';
		$output .= $this->input();


		return $output;
	}

	/**
	 * Render the modal template
	 *
	 * @since 1.0.0
	 * @see \evenote\ui\evenote
	 * @access public
	 * @return string HTML of modals templates
	 */
	public function item_template() {
		$output = '<script type="text/html" id="' . esc_attr( $this->id() ) . '-tmpl">';
		$output .= '<div class="evenote-item">';
		if ( false !== strpos( $this->child['config']->struct['view'], ABSPATH ) ) {
			ob_start();
			include $this->child['config']->struct['view'];
			$output .= ob_get_clean();
		} else {
			$output .= $this->child['config']->struct['view'];
		}
		$output .= '</div>';
		$output .= '</script>';

		return $output;
	}

	/**
	 * Returns the main input field for rendering
	 *
	 * @since 1.0.0
	 * @see \evenote\ui\evenote
	 * @access public
	 * @return string Input field HTML striung
	 */
	public function input() {
		return '<input type="hidden" value="' . esc_attr( $this->get_value() ) . '" ' . $this->build_attributes() . '>';
	}

	/**
	 * Returns the label for the control
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string label of control
	 */
	public function label() {
		$output = null;
		if ( isset( $this->struct['label'] ) ) {
			$output .= '<label for="' . esc_attr( $this->id() ) . '-control" class="evenote-add-row"><span class="evenote-control-label">' . esc_html( $this->struct['label'] ) . '</span></label>';
		}

		return $output;
	}

	/**
	 * builds the handlebars based structure for template render
	 *
	 * @param array $array the dat astructure to drill into
	 * @param string $tag , the final tag to replace the data with.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array array of the data structure
	 */
	public function drill_in( $array, $tag = '{{@root' ) {
		$back = array();
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) && ! empty( $value ) ) {
				$back[ $key ] = $this->drill_in( $value, $tag . '.' . $key );
			} else {
				$back[ $key ] = $tag . '.' . $key . '}}';
			}
		}

		return $back;
	}

	/**
	 * Sets styling colors
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function enqueue_active_assets() {

		echo '<style type="text/css">';
		echo '.' . $this->id() . ' .dashicons.dashicons-plus-alt{ color: ' . $this->base_color() . ' !important;}';
		echo '.' . $this->id() . ' .column-handle{background-color: ' . $this->base_color() . ' !important;}';
		echo '.' . $this->id() . ' .evenote-component-toolbar{background-color: ' . $this->base_color() . ' !important;}';
		echo '</style>';

	}
	/**
	 * Render the script footer template
	 *
	 * @since 1.0.0
	 * @see \evenote\ui\evenote
	 * @access public
	 */
	public function render_footer_script() {
		$output = null;
		if ( ! empty( $this->templates ) ) {
			$output .= $this->templates;
		}

		echo $output;
	}
}
