<?php
/**
 * EVENT_NOTIFY Controls
 *
 * @package   controls
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 */
namespace evenote\ui\control;

/**
 * <hr> separator. Mainly used for formatting
 *
 * @since 1.0.0
 */
class separator extends \evenote\ui\control {

	/**
	 * The type of object
	 *
	 * @since       1.0.0
	 * @access public
	 * @var         string
	 */
	public $type = 'separator';

	/**
	 * Return null alwasy since a separator should not show up as an input.
	 * @since 1.0.0
	 * @access public
	 * @return mixed $data
	 */
	public function get_data() {
		return null;
	}

	/**
	 * Returns the main input field for rendering
	 *
	 * @since 1.0.0
	 * @see \evenote\ui\evenote
	 * @access public
	 * @return string
	 */
	public function input() {

		return '<hr class="evenote-separator" id="control-' . esc_attr( $this->id() ) . '" />';

	}

	/**
	 * Enqueues specific tabs assets for the active pages
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function set_active_styles() {
		parent::set_active_styles();
		$style = '#control-' . $this->id() . '{border-color: ' . $this->base_color() . ';}';
		$style .= '#' . $this->id() . ' span.evenote-control-label {color: ' . $this->base_color() . ';}';
		evenote_share()->set_active_styles( $style );
	}

}
