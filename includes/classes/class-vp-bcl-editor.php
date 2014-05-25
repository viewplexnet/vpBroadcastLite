<?php
/**
 * vpBroadcast Lite - admin editor addons
 *
 *
 * @class   vp_Broadcast_Lite_Meta
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if 

class vp_Broadcast_Lite_Editor {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'add_shortcode_button' ) );
		add_filter( 'tiny_mce_version', array( $this, 'refresh_mce' ) );
	}

	/**
	 * Add a button for shortcodes to the WP editor.
	 */
	public function add_shortcode_button() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
		if ( get_user_option('rich_editing') == 'true') {
			add_filter( 'mce_external_plugins', array( $this, 'add_shortcode_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_shortcode_button' ) );
		}
	}

	/**
	 * Register the shortcode button.
	 *
	 * @param array $buttons
	 * @return array
	 */
	public function register_shortcode_button( $buttons ) {
		array_push( $buttons, "|", "vp_bcl_shortcodes_button" );
		return $buttons;
	}

	/**
	 * Add the shortcode button to TinyMCE
	 *
	 * @param array $plugin_array
	 * @return array
	 */
	public function add_shortcode_tinymce_plugin( $plugin_array ) {
		$plugin_array['vpBroadcastLiteShortcodes'] = VP_BROADCAST_LITE_URL . '/assets/js/admin.editor_plugin.js';
		return $plugin_array;
	}

	/**
	 * Force TinyMCE to refresh.
	 *
	 * @param int $ver
	 * @return int
	 */
	public function refresh_mce( $ver ) {
		$ver += 3;
		return $ver;
	}

}

new vp_Broadcast_Lite_Editor();

?>