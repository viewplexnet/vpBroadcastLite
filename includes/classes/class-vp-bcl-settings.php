<?php
/**
 * Viewplex Post Types - Settings
 *
 *
 * @class 		vp_pt_Settings
 * @version		1.0
 * @package		Viewplex Post Types
 * @category	Class
 * @author 		Viewplex
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class vp_Broadcast_Lite_Settings {

	public $settings_name = 'vp_bl_settings';

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'add_options_page' ) );	
		add_action( 'admin_init', array( $this, 'options_fields_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'include_assets' ) );		
		
	}

	/**
	 * Include settings page assets
	 *
	 * @return void
	 * @since 1.0
	 */

	public function include_assets() {
		if ( isset($_GET['page']) && $this->settings_name == $_GET['page'] ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'vp_setting_scripts', VP_BROADCAST_LITE_URL . '/assets/js/admin.settings.js', array('jquery', 'wp-color-picker'), '1.0', true );
		}
	}


	/**
	 * Add plugin options page
	 *
	 * @return void
	 * @since 1.0
	 */
	public function add_options_page() {

		add_options_page( __( 'vpBroadcast Lite Settings', 'vp_broadcast_lite' ), __( 'vpBroadcast Lite', 'vp_broadcast_lite' ), 'manage_options', $this->settings_name, array( $this, 'show_options_page' ) );
	}

	/**
	 * Show Settings Page
	 *
	 * @return void
	 * @since 1.0
	 */
	public function show_options_page() {
		
		echo "<div class='wrap'>";

			echo "<h2>" . __( 'Viewplex Post Types Options', 'vp_broadcast_lite' ) . "</h2>";

			echo "<form action='options.php' method='POST'>";
				
				echo "<div class='vpt-tab-group'>";  
			        echo "<div class='vpt-tab-nav'></div>";
			        echo "<div class='vpt-tab-content'>";
				
						do_settings_sections( $this->settings_name );
						settings_fields( $this->settings_name );
						echo get_submit_button();

					echo "</div>";
				
				echo "</div>";

			echo "</form>";

		echo "</div>";

	}

	/**
	 * Set options fields array
	 *
	 * @return void
	 * @since 1.0
	 */
	public function set_options_array() {

		$options = array();
		
		$options[] = array( 
			"name"		=> __( "General", "vp_broadcast_lite" ),
			"type"		=> "heading"
		);

		$options['vp_bl_page_wrap_open'] = array(
			"name"		=> __( "Add HTML markup for opening page wrappers", "vp_broadcast_lite" ),
			"desc"		=> __( "Enter opening elements for main page content wrappers for your current theme (by default markup seted for Twenty Fourteen WP theme)", "vp_broadcast_lite" ),
			"id"		=> "vp_bl_page_wrap_open",
			"type"		=> "textarea",
			"std"		=> "<div id='primary' class='content-area'><div id='content' class='site-content' role='main'>"
		);
		$options['vp_bl_page_wrap_close'] = array(
			"name"		=> __( "Add HTML markup for closing page wrappers", "vp_broadcast_lite" ),
			"desc"		=> __( "Enter closing elements for main page content wrappers for your current theme (by default markup seted for Twenty Fourteen WP theme)", "vp_broadcast_lite" ),
			"id"		=> "vp_bl_page_wrap_close",
			"type"		=> "textarea",
			"std"		=> "</div></div>"
		);
		$options['vp_bl_show_sidebar'] = array(
			"name"		=> __( "Show sidebar on broadcast pages", "vp_broadcast_lite" ),
			"desc"		=> __( "Show or hide sidebar on broadcast pages (single and archive)", "vp_broadcast_lite" ),
			"id"		=> "vp_bl_show_sidebar",
			"type"		=> "radio",
			"std"		=> "show",
			"options"	=> array(
				"show"	=> __( "Show", "vp_broadcast_lite" ),
				"hide"	=> __( "Hide", "vp_broadcast_lite" )
			)
		);

		$options[] = array( 
			"name"		=> __( "Style", "vp_broadcast_lite" ),
			"type"		=> "heading"
		);

		$options['vp_bl_corners_style'] = array(
			"name"		=> __( "Select elements corners style", "vp_broadcast_lite" ),
			"desc"		=> '',
			"id"		=> "vp_bl_corners_style",
			"type"		=> "select",
			"std"		=> "square",
			"options"	=> array(
				"square"     => __( "Square corners", "vp_broadcast_lite" ),
				"rounded_5"  => __( "Rounded corners (border-radius: 5px)", "vp_broadcast_lite" ),
				"rounded_10" => __( "Rounded corners (border-radius: 10px)", "vp_broadcast_lite" ),
				"rounded_15" => __( "Rounded corners (border-radius: 15px)", "vp_broadcast_lite" )
			)
		);

		$options['vp_bl_borders_color'] = array(
			"name"		=> __( "Borders color", "vp_broadcast_lite" ),
			"desc"		=> '',
			"id"		=> "vp_bl_borders_color",
			"type"		=> "color",
			"std"		=> "#999999"
		);

		$options['vp_bl_item_bg'] = array(
			"name"		=> __( "Broadcast item background color", "vp_broadcast_lite" ),
			"desc"		=> '',
			"id"		=> "vp_bl_item_bg",
			"type"		=> "color",
			"std"		=> "#ffffff"
		);

		$options['vp_bl_import_item_bg'] = array(
			"name"		=> __( "Broadcast important item background color", "vp_broadcast_lite" ),
			"desc"		=> '',
			"id"		=> "vp_bl_import_item_bg",
			"type"		=> "color",
			"std"		=> "#eeeeee"
		);

		return apply_filters( 'vp_bl_options_array', $options );

	}

	/**
	 * Options output init
	 *
	 * @return void
	 * @since 1.0
	 */

	public function options_fields_init() {

		register_setting( $this->settings_name, $this->settings_name, array( $this, 'settings_sanitize' ) );

		$options = $this->set_options_array();
		$groups_counter = 0;
		$current_section = '';

		foreach ( $options as $option_key => $option_data ) {

			if ( 'heading' == $option_data['type'] ) {

				$section_id = str_replace( " ", "-", strtolower( $option_data['name'] ) );
				add_settings_section( $section_id, $option_data['name'], '__return_false', $this->settings_name );

			} else {

				$args = array(
					'type' => $option_data['type'],
					'data' => $option_data
				);
				add_settings_field( $option_data['id'], $option_data['name'], array( $this, 'options_fields_interface' ), $this->settings_name, $section_id, $args );

			}

		}

	}

	/**
	 * Options output init
	 *
	 * @return void
	 * @since 1.0
	 */

	public function options_fields_interface( $args ) {

		global $allowedtags;
		$option_data = $args['data'];
		$values = get_option( $this->settings_name );

		if ( isset( $values[$option_data['id']] ) ) {
			$value = $values[$option_data['id']];
		} else {
			$value = $option_data['std'];
		}

		$css_class = '';
		if ( isset( $option_data['custom_class'] ) ) {
			$css_class = $option_data['custom_class'];
		} elseif ( isset( $option_data['class'] ) ) {
			$css_class = $option_data['class'];
		}

		$explain_value = '';
		if ( isset( $option_data['desc'] ) ) {
			$explain_value = $option_data['desc'];
		}

		echo "<div class='" . $css_class . "'>";
		switch ($args['type']) {

			// Textarea
			case 'textarea':
				
				echo "<textarea id='" . esc_attr( $option_data['id'] ) . "' name='" . esc_attr( $this->settings_name . "[" . $option_data['id'] . "]" ) . "'>" . esc_html( $value ) . "</textarea>";
				echo "<div class='vpt-option-item-desc'>" . $option_data['desc'] . "</div>";
				break;

			// Text
			case 'text':
				
				echo "<input type='text' id='" . esc_attr( $option_data['id'] ) . "' name='" . esc_attr( $this->settings_name . "[" . $option_data['id'] . "]" ) . "' value='" . esc_html( $value ) . "'>";
				echo "<div class='vpt-option-item-desc'>" . $option_data['desc'] . "</div>";
				break;
			
			// Select Box
			case 'select':
				echo '<select name="' . esc_attr( $this->settings_name . "[" . $option_data['id'] . "]" ) . '" id="' . esc_attr( $option_data['id'] ) . '">';

				foreach ($option_data['options'] as $key => $option ) {
					echo '<option value="' . esc_attr( $key ) . '" '. selected( $value, $key, false ) .'>' . esc_html( $option ) . '</option>';
				}
				unset( $key, $option );
				$output .= '</select>';
				break;


			// Radio Box
			case "radio":
				foreach ($option_data['options'] as $key => $option) {
					echo '<div>';
						echo '<input type="radio" name="' . esc_attr( $this->settings_name . "[" . $option_data['id'] . "]" ) . '" id="' . esc_attr( $option_data['id'] . '-' . $key ) . '" value="'. esc_attr( $key ) . '" '. checked( $value, $key, false) .' />';
						echo '<label for="' . esc_attr( $option_data['id'] . '-' . $key ) . '">' . esc_html( $option ) . '</label>';
					echo '</div>';
				}
				unset( $key, $option );
				break;

			// Checkbox
			case "checkbox":
				echo '<input id="' . esc_attr( $option_data['id'] ) . '" type="checkbox" name="' . esc_attr( $this->settings_name . "[" . $option_data['id'] . "]" ) . '" '. checked( $value, 1, false) .' />';
				echo '<label class="explain" for="' . esc_attr( $value['id'] ) . '">' . wp_kses( $explain_value, $allowedtags) . '</label>';
				break;

			// Checkbox
			case "color":
				echo '<input id="' . esc_attr( $option_data['id'] ) . '" type="text"  name="' . esc_attr( $this->settings_name . "[" . $option_data['id'] . "]" ) . '"  value="' . esc_attr( $value ) . '" class="wp-color-picker-field" data-default-color="' . $option_data['std'] . '" />';
				echo '<label class="explain" for="' . esc_attr( $value['id'] ) . '">' . wp_kses( $explain_value, $allowedtags) . '</label>';
				break;

			// Multicheck
			case "multicheck":
				echo "<ul class='sortable_multicheck' id='sortable-" . $option_data['id'] . "'>";
				$saved_data_default = $value;
				$saved_data = array();
				if ( '' != $saved_data_default ) {
					foreach ($saved_data_default as $saved_key => $saved_value) {
						$saved_data[$saved_key] = get_the_title( $saved_key );
					}
					$saved_data = $saved_data + $option_data['options'];
				} else {
					$saved_data = $option_data['options'];
				}

				foreach ($saved_data as $key => $option) {
					$checked = '';
					$label = $option;
					$option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

					$id = $option_data['id'] . '-'. $option;
					$name = $this->settings_name . '[' . $option_data['id'] . '][' . $option .']';

					if ( isset($value[$key]) ) {
						$checked = checked($value[$key], $key, false);
					}
					$thumb = '';
					echo '<li><input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" value="' . $key . '" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . $thumb . '</label></li>';
				}
				echo "</ul>";
				echo "<script type='text/javascript'>
				jQuery(function() {
					jQuery( '#sortable-" . $option_data['id'] . "' ).sortable({ cursor: 'move' });
				});
				</script>";
				break;

			default:
				// Output custom control
				add_action( 'vp_bl_show_setting_' . $args['type'], $option_data );
				break;

		}
		echo "</div>";

	}

	/**
	 * Sanitize settings input
	 *
	 * @return void
	 * @since 1.0
	 */
	public function settings_sanitize( $value ) {
		
		global $allowedposttags;
		$value['vp_bl_page_wrap_open']  = wp_kses( $value['vp_bl_page_wrap_open'], $allowedposttags );
		$value['vp_bl_page_wrap_close'] = wp_kses( $value['vp_bl_page_wrap_close'], $allowedposttags );
		$value['vp_bl_show_sidebar']    = in_array($value['vp_bl_show_sidebar'], array('show', 'hide')) ? $value['vp_bl_show_sidebar'] : 'show';

		apply_filters( 'vp_bl_sanitize_custom_settings', $value );

		return $value;

	}

	/**
	 * Show Settings Page
	 *
	 * @return void
	 * @since 1.0
	 */
	public function get_option( $option = null ) {

		$all_options = get_option( $this->settings_name );

		if ( $option ) {

			if ( isset( $all_options[$option] ) ) {
				return $all_options[$option];
			} else {
				$std_options = $this->set_options_array();
				if ( isset($std_options[$option]['std']) ) {
					return $std_options[$option]['std'];
				} else {
					return '';
				}
			}

		} else {
			return $all_options;
		}

	}

}

$GLOBALS['vpBroadcast_Settings'] = new vp_Broadcast_Lite_Settings();

?>