<?php
/**
 * Plugin Name: vpBroadcast Lite
 * Plugin URI: https://github.com/viewplexnet/vpBroadcastLite
 * Description: This plugin provides you the opportunity to make on your site texts broadcasts of sports events or anything else events, which you want.
 * Author: Viewplex
 * Version: 1.0
 * Author URI: https://github.com/viewplexnet
 * Tags: broadcast, text broadcast, live broadcasting, online broadcasting
 * Requires at least: 3.8
 * Tested up to: 3.9.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: vp_broadcast_lite
 * Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

class vpBroadcast_Lite {

    public $var_prefix = 'vp_bcl_broadcast_';

    /**
     * Constructor for the class
     */
    public function __construct() {

    	// Installation
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

        // Define constants
        define( 'VP_BROADCAST_LITE_VERSION', '1.0.0' );
        define( 'VP_BROADCAST_LITE_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'VP_BROADCAST_LITE_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

        // Init
        add_action( 'init', array( $this, 'load_textdomain' ) );
        add_action( 'init', array( $this, 'register_post_type' ) );

        // Include frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'include_assets' ) );

        // Include admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );

        // Include required files
		$this->includes();

        // Load default templates
        add_filter( 'template_include', array( $this, 'load_templates' ) );
    }

    /**
     * Flush rewrite rules plugin activation
     */
    public function activate() {
        $this->register_post_type();
        flush_rewrite_rules();
    }

    /**
     * Load the plugin textdomain for localistion
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'vp_broadcast_lite', false, plugin_basename( dirname( __FILE__ ) ) . "/languages" );
    }

    /**
     * Register Broadcast post type
     */
    public function register_post_type() {

    	$labels = array(
		    'name'               => __( 'Broadcasts', 'vp_broadcast_lite' ),
		    'singular_name'      => __( 'Broadcast', 'vp_broadcast_lite' ),
		    'add_new'            => __( 'Add New', 'vp_broadcast_lite' ),
		    'add_new_item'       => __( 'Add New broadcast', 'vp_broadcast_lite' ),
		    'edit_item'          => __( 'Edit broadcast', 'vp_broadcast_lite' ),
		    'new_item'           => __( 'New broadcast', 'vp_broadcast_lite' ),
		    'all_items'          => __( 'All broadcasts', 'vp_broadcast_lite' ),
		    'view_item'          => __( 'View broadcast', 'vp_broadcast_lite' ),
		    'search_items'       => __( 'Search broadcasts', 'vp_broadcast_lite' ),
		    'not_found'          => __( 'No broadcasts found', 'vp_broadcast_lite' ),
		    'not_found_in_trash' => __( 'No broadcasts found in Trash', 'vp_broadcast_lite' ),
		    'parent_item_colon'  => '',
		    'menu_name'          => __( 'Broadcasts', 'vp_broadcast_lite' )
		);

		$args = array(
		    'labels'             => $labels,
		    'public'             => true,
		    'menu_icon'			 => 'dashicons-format-status',
		    'publicly_queryable' => true,
		    'show_ui'            => true,
		    'show_in_menu'       => true,
		    'query_var'          => true,
		    'rewrite'            => array( 'slug' => 'broadcasts' ),
		    'capability_type'    => 'post',
		    'has_archive'        => false,
		    'hierarchical'       => false,
		    'menu_position'      => null,
		    'supports'           => array( 
		    	'title', 
		    	'author', 
		    	'thumbnail', 
		    	'comments' 
		    )
		);

		register_post_type( 'vp_broadcast', $args );

    }

    /**
     * Include required files
     */
    public function includes() {

    	include_once( 'includes/classes/class-vp-bcl-meta.php' );
        include_once( 'includes/classes/class-vp-bcl-settings.php' );
        include_once( 'includes/classes/class-vp-bcl-editor.php' );
        include_once( 'includes/vp-bcl-widgets.php' );
        include_once( 'includes/vp-bcl-shortcodes.php' );
        include_once( 'includes/vp-bcl-admin.php' );
        include_once( 'includes/vp-bcl-template-functions.php' );

    }

    /**
     * Frontend assets
     *
     * @access public
     */
    public function include_assets() {
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style( 'vp-broadcast-lite', VP_BROADCAST_LITE_URL . '/assets/css/frontend.css', '', '1.0', 'all' );
        wp_add_inline_style( 'vp-broadcast-lite', $this->custom_css() );
        wp_enqueue_script( 'vp-bl-frontend', VP_BROADCAST_LITE_URL . '/assets/js/frontend.script.js', array('jquery'), '0.1', true );
        wp_localize_script( 'vp-bl-frontend', 'vp_bl_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
    }

    /**
	 * Admin assets
	 *
	 * @access public
	 */
	public function admin_assets() {
        global $typenow;
		// Scripts
		wp_enqueue_script( 'vp-broadcast-lite', VP_BROADCAST_LITE_URL . '/assets/js/admin.custom.js', array('jquery'), '1.0', true );
        if ( 'vp_broadcast' == $typenow ) {
           wp_enqueue_script( 'jquery-ui-datepicker' );
        }
		// CSS
		wp_enqueue_style( 'vp-broadcast-lite', VP_BROADCAST_LITE_URL . '/assets/css/admin.custom.css', '', '1.0', 'all' );
        if ( 'vp_broadcast' == $typenow ) {
           //Include css only on broadcast page
        }
	}

    /**
     * Get and include template files.
     *
     * @param mixed $template_name
     * @param array $args (default: array())
     * @param string $template_path (default: '')
     * @param string $default_path (default: '')
     */
    public function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
       
        if ( $args && is_array($args) ) {
            extract( $args );
        }

        include( $this->locate_template( $template_name, $template_path, $default_path ) );
    }

    /**
     * Locate a template and return the path for inclusion.
     *
     * This is the load order:
     *
     * yourtheme/$template_path/$template_name
     * yourtheme/vp_broadcast_lite/templates/$template_name
     * $default_path/$template_name
     *
     * @param mixed $template_name
     * @param string $template_path (default: '')
     * @param string $default_path (default: '')
     * @return string
     */
    public function locate_template( $template_name, $template_path = '', $default_path = '' ) {
            
        if ( ! $template_path )
            $template_path = 'vp_broadcast_lite/templates/';
        if ( ! $default_path )
            $default_path  = VP_BROADCAST_LITE_DIR . '/templates/';

        // Look within passed path within the theme - this is priority
        $template = locate_template(
            array(
                trailingslashit( $template_path ) . $template_name,
                $template_name
            )
        );

        // Get default template
        if ( ! $template )
            $template = $default_path . $template_name;

        // Return what we found
        return apply_filters( 'vp_broadcast_lite_locate_template', $template, $template_name, $template_path );
    }

    /**
     * Load standart templates for singel broadacst and broadcasts archive
     *
     * Templates can be overridden in your theme.
     * example for portfolio: your_theme/vp-broadcast-lite/templates/single-vp_broadcast.php
     *                        your_theme/vp-broadcast-lite/templates/archive-vp_broadcast.php
     *
     * @return string
     * @param mixed $template
     */
    public function load_templates( $template ) {
       
        $template_url = 'vp-broadcast-lite/templates/';
        $template_file = '';
        $templates_to_find = array();

        if ( is_single() ) {

            $post_type = get_post_type();

            if ( 'vp_broadcast' == $post_type ) {
                $template_file = 'single-vp_broadcast.php';
            }

            $templates_to_find[] = $template_file;
            $templates_to_find[] = $template_url . $template_file;

        } elseif ( is_archive() ) {

            $post_type = get_post_type();
            if ( 'vp_broadcast' == $post_type ) {
                $template_file = 'archive-vp_broadcast.php';
            }

            $templates_to_find[] = $template_file;
            $templates_to_find[] = $template_url . $template_file;

        }

        if ( $template_file ) {
            $template = locate_template( $templates_to_find );
            if ( ! $template ) {
                $template = VP_BROADCAST_LITE_DIR . '/templates/' . $template_file;
            }
        }

        return $template;

    }

    /**
     * Checks sidebar options and include sidebar if enabled
     *
     *
     * @return void
     */
    public function get_sidebar() {
        global $vpBroadcast_Settings;
        if ( '' == $vpBroadcast_Settings->get_option('vp_bl_show_sidebar') || 'show' == $vpBroadcast_Settings->get_option('vp_bl_show_sidebar') ) {
            get_sidebar();
        }
    }

    /**
     * Get styles from options
     */
    public function custom_css() {
        global $vpBroadcast_Settings;
        $borders_color  = $vpBroadcast_Settings->get_option('vp_bl_borders_color');
        $item_bg        = $vpBroadcast_Settings->get_option('vp_bl_item_bg');
        $import_item_bg = $vpBroadcast_Settings->get_option('vp_bl_import_item_bg');
        $corners_style  = $vpBroadcast_Settings->get_option('vp_bl_corners_style');
        
        $result = '';
        if ( $borders_color ) {
            $result .= "
                .vp_broadcast_data .vp_broadcast_item.timestamp .timestamp_time {
                    border-right-color: $borders_color;
                }
                .vp_broadcast_data .vp_broadcast_item.timestamp .timestamp_event {
                    border-left-color: $borders_color;
                }
                .vp_broadcast_data .vp_broadcast_item {
                    border-color: $borders_color;
                }
                .vp_broadcast_data.timeline:before {
                    background-color: $borders_color;
                }
                .vp_broadcast_data.timeline .vp_broadcast_item.vp_fullwidth .vp_marker {
                    border-color: $borders_color transparent transparent transparent;
                }
                .vp_broadcast_data.timeline .vp_broadcast_item.vp_left .vp_marker {
                    border-color: transparent transparent transparent $borders_color;
                }
                .vp_broadcast_data.timeline .vp_broadcast_item.vp_right .vp_marker {
                    border-color: transparent $borders_color transparent transparent;
                }
                .countdown-wrap {
                    border-color: $borders_color;
                }
            ";
        }
        if ( $item_bg ) {
            $result .= "
                .vp_broadcast_data .vp_broadcast_item {
                    background-color: $item_bg;
                }
                .vp_broadcast_data.timeline .vp_broadcast_item.vp_fullwidth .vp_marker:after {
                    border-color: $item_bg transparent transparent transparent;
                }
                .vp_broadcast_data.timeline .vp_broadcast_item.vp_left .vp_marker:after {
                    border-color: transparent transparent transparent $item_bg;
                }
                .vp_broadcast_data.timeline .vp_broadcast_item.vp_right .vp_marker:after {
                    border-color: transparent $item_bg transparent transparent;
                }
            ";
        }
        if ( $import_item_bg ) {
            $result .= "
                .vp_broadcast_data .vp_broadcast_item.important {
                    background-color: $import_item_bg;
                }
                .vp_broadcast_data.timeline .vp_broadcast_item.vp_fullwidth.important .vp_marker:after {
                    border-color: $import_item_bg transparent transparent transparent;
                }
                .vp_broadcast_list.col .vp_broadcast_list_item .vp_bl_scorebox b {
                    background-color: $import_item_bg;
                }
                .vp_broadcast_list.row .vp_broadcast_list_item .vp_broadcast_list_item_content {
                    background-color: $import_item_bg;
                }
            ";
        }

    
        switch ($corners_style) {
            case 'rounded_5':
                $border_radius = '5px';
                break;

            case 'rounded_10':
                $border_radius = '10px';
                break;

            case 'rounded_15':
                $border_radius = '15px';
                break;
            
            default:
                $border_radius = '0';
                break;
        }
        
        $result .= "
            .vp_broadcast_item {
                border-radius: $border_radius;
            }
        ";

        return $result;
    }
       
}

$GLOBALS['vpBroadcast_Lite'] = new vpBroadcast_Lite();

?>