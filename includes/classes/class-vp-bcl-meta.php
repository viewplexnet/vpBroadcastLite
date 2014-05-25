<?php
/**
 * vpBroadcast Lite - meta boxes manager
 *
 *
 * @class   vp_Broadcast_Lite_Meta
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if 

class vp_Broadcast_Lite_Meta {

	public $meta_atts;
	public $priority = 10;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct( $priority ) {

		global $typenow;
		if ( $this->meta_atts['page'] == $typenow ) {
			$this->priority = $priority;
			add_action( 'admin_menu', array( $this, 'add_meta_box' ), $this->priority );
			add_action( 'save_post', array( $this, 'save_meta_data' ) );
			add_action( 'wp_ajax_update_broadcast', array( $this, 'save_meta_data' ) );
		}
		
	}

	/**
	 * add meta box attributes function.
	 *
	 * @access public
	 */
	public function set_meta_atts( $atts ) {

		$defaults = array(
			'id'          => '',
			'title'       => '',
			'description' => '',
			'page'        => '',
			'context'     => '',
			'priority'    => '',
			'sections'    => '',
			'fields'      => ''
		);

		$atts = wp_parse_args( $atts, $defaults );

		if ( isset($atts['id']) && isset($atts['title']) && isset($atts['description']) && isset($atts['page']) && isset($atts['context']) && isset($atts['priority']) && isset($atts['fields']) ) {
			$this->meta_atts = $atts;
		} else {
			return false;
		}
	}

	/**
	 * show meta box function.
	 *
	 * @access public
	 */
	public function show_meta_box() {

		$post_id = get_the_id();

		echo '<p>' . $this->meta_atts['description'] . '</p>';
		// Use nonce for verification
		echo '<input type="hidden" name="my_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<div class="form-table vpt-meta-box position-' . esc_attr( $this->meta_atts['context'] ) . '">';

		$sections_content = array();

		if ( ! isset($this->meta_atts['sections']) || ! is_array($this->meta_atts['sections']) ) {
			$sections = array( 'unsectioned' => '' );
		} else {
			$sections = array_merge($this->meta_atts['sections'], array( 'unsectioned' => '' ));
		}

		foreach ( $this->meta_atts['fields'] as $field ) {
			// get current post meta data
			$meta = get_post_meta( $post_id, $field['id'], true );

			if ( '' == $meta ) {
				$value = $field['std'];
			} else {
				$value = $meta;
			}

			ob_start();
			$this->the_meta_field_control( $field, $value );

			if ( isset($field['section']) && ! empty( $field['section'] ) && array_key_exists($field['section'], $sections) ) {
				if ( isset($sections_content[$field['section']]) && ! empty($sections_content[$field['section']]) ) {
					$sections_content[$field['section']] .= ob_get_clean();
				} else {
					$sections_content[$field['section']] = ob_get_clean();
				}
			} else {
				if ( isset($sections_content['unsectioned']) && ! empty($sections_content['unsectioned']) ) {
					$sections_content['unsectioned'] .= ob_get_clean();
				} else {
					$sections_content['unsectioned'] = ob_get_clean();
				}
			}

		}
		
		foreach ( $sections as $section => $section_label ) {
			if ( isset($sections_content[$section]) && ! empty($sections_content[$section]) ) {
				echo '<div class="section-item section-' . esc_attr( strtolower( $section ) ) . '">';

				if ( '' != $section_label ) {
					echo '<h4 class="section_title">' . $section_label . '</h4>';
				}

				echo $sections_content[$section];

				echo '</div>';
			}
		}

		echo '</div>';

	}

	/**
	 * get single metabox control output
	 *
	 * @access public
	 */
	public function the_meta_field_control( $field = array(), $value = '' ) {

		if ( isset($field['class']) ) {
			$css_class = $field['class'];
		} else {
			$css_class = '';
		}

		switch ( $field['type'] ) {
				//If Text		
				case 'text':
					echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						echo '<div class="item-data"><input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '"></div>';	
					echo '</div>';	
				break;
				//If textarea		
				case 'textarea':
					echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						global $allowedtags;
						echo '<div class="item-data"><textarea name="' . $field['id'] . '" id="' . $field['id'] . '" rows="5">' . wp_kses( $value, $allowedtags ) . '</textarea></div>';
					echo '</div>';
				break;
				//If editor		
				case 'editor':
					echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading editor-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						global $allowedtags;
						wp_editor( wp_kses( $value, $allowedtags ), $field['id'], array( 'textarea_rows' => 5 ) );
					echo '</div>';
				break;
				//If Select	
				case 'select':
					echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						echo '<div class="item-data"><select id="' . $field['id'] . '" name="' . $field['id'] . '">';
							foreach ( $field['options'] as $option_val => $option_name ) {
								echo'<option value="' . $option_val . '" ' . selected( $value, $option_val, false ) . ' >' . $option_name . '</option>';
							}
							echo'</select>';
						echo '</div>';
					echo '</div>';
				break;
				//If Date
				case 'date':
				echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						echo '<div class="item-data">';
							echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $value . '" autocomplete="off">';
							echo "<script type='text/javascript'>
									jQuery(document).ready(function($) {
										\$('#" . $field['id'] . "').datepicker({
											changeMonth: true,
											changeYear: true
										});
									});
								</script>";
						echo '</div>';	
					echo '</div>';	
				break;
				//If Time
				case 'time':
				$hours   = array(1,2,3,4,5,6,7,8,9,10,11,12);
				$minutes = array('05','10','15','20','25','30','35','40','45','50','55','00');
				$formats = array('AM','PM');
				echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						echo '<div class="item-data">';
							echo '<select class="timepicker-item timepicker-hours" id="' . $field['id'] . '_hours" name="' . $field['id'] . '[hours]">';
							foreach ( $hours as $hour ) {
								echo'<option value="' . $hour . '" ' . selected( $value['hours'], $hour, false ) . ' >' . $hour . '</option>';
							}
							echo'</select>';
							echo ' : <select class="timepicker-item timepicker-mins" id="' . $field['id'] . '_mins" name="' . $field['id'] . '[mins]">';
							foreach ( $minutes as $mins ) {
								echo'<option value="' . $mins . '" ' . selected( $value['mins'], $mins, false ) . ' >' . $mins . '</option>';
							}
							echo'</select>';
							echo ' <select class="timepicker-item timepicker-format" id="' . $field['id'] . '_format" name="' . $field['id'] . '[format]">';
							foreach ( $formats as $format ) {
								echo'<option value="' . $format . '" ' . selected( $value['format'], $format, false ) . ' >' . $format . '</option>';
							}
							echo'</select>';
						echo '</div>';	
					echo '</div>';	
				break;
				//If Radio	
				case 'radio':
					echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						echo '<div class="item-data">';
					
						foreach ( $field['options'] as $option_val => $option_name ) {
							echo '<div class="item-type-radio-element"><label>';
								echo '<input type="radio" class="' . $field['id'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '-' . $option_val . '" value="' . $option_val . '" ' . checked( $value, $option_val, false ) . '>';
								echo $option_name;
							echo '</label></div>';	
						}
						echo '</div>';	

					echo '</div>';
				break;
				//If Checkbox
				case 'checkbox':
					echo '<div class="item-wrapper ' . $css_class . '">';
						echo '<div class="item-heading"><label for="' . $field['id'] . '"><strong>' . $field['name'] . '</strong><small style="display:block; color:#aaa;">' . $field['desc'] . '</small></label></div>';
						echo '<div class="item-data"><input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $field['std'] . '" ' . checked( $meta, $field['std'], false ) . '></div>';	

					echo '</div>';
				break;
				//Custom
				default:
					do_action( 'vp_bcl_meta_control_' . $field['type'], $field, $value );
				break;
			}

	}

	/**
	 * add meta box attributes function.
	 *
	 * @access public
	 */
	public function add_meta_box() {
		add_meta_box( $this->meta_atts['id'], $this->meta_atts['title'], array( $this, 'show_meta_box' ), $this->meta_atts['page'], $this->meta_atts['context'], $this->meta_atts['priority'] );
	}

	/**
	 * save meta data function.
	 *
	 * @access public
	 */
	public function save_meta_data( $post_id ) {

		global $typenow;

		if ( $this->meta_atts['page'] != $typenow ) {
			return $post_id;
		}

		// verify nonce
		if ( !isset($_POST['my_meta_box_nonce']) || !wp_verify_nonce($_POST['my_meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}
	 
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
	 
		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	 
		foreach ($this->meta_atts['fields'] as $field) {
			$old = get_post_meta($post_id, $field['id'], true);
			$new = ( isset($_POST[$field['id']]) && ! is_array($_POST[$field['id']]) ) ? $_POST[$field['id']] : '';
	 		if ( isset($_POST[$field['id']]) && is_array($_POST[$field['id']]) ) {
	 			foreach ($_POST[$field['id']] as $key => $value) {
	 				$new[$key] = $_POST[$field['id']][$key];
	 			}
	 		}
			if ($new && !is_array($new) && $new != $old) {
				update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
			} elseif (is_array($new)) {
				update_post_meta($post_id, $field['id'], $new);
			} elseif ( '0' == $new ) {
				update_post_meta($post_id, $field['id'], '0');
			}
		}

	}
}

?>