<?php
/**
 * vpBroadcast Lite - init metaboxes on Broadcats pge
 *
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Init Broadcats meta boxes
 *
 * @return void
 * @since 1.0
 */

function vp_bcl_metbaoxes() {

	global $vpBroadcast_Lite;
	$opt_prefix = $vpBroadcast_Lite->var_prefix;

	// Status metabox
	$metabox_status_atts = array(
		'id'			=> 'vp_bcl_broadcast_status',
		'title'			=> __( "Current broadcast status", "vp_broadcast_lite" ),
		'description'	=> '',
		'page'			=> 'vp_broadcast',
		'context'		=> 'side',
		'priority'		=> 'core',
		'sections'		=> false,
		'fields'		=> array(
			array(
				'name' 		=> '',
				'desc' 		=> __( "Select current broadcast status", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'status',
				'type' 		=> 'radio',
				'std' 		=> 'live',
				'options'	=> array(
					'coming_soon' => __( "Coming Soon", "vp_broadcast_lite" ),
					'live'        => __( "Live", "vp_broadcast_lite" ),
					'ended'       => __( "Finished", "vp_broadcast_lite" ),
				)
			)	
		)
	);
	$metabox_status_atts = apply_filters( 'vp_bcl_metabox_status', $metabox_status_atts );

	$metabox_status = new vp_Broadcast_Lite_Meta(10);
	$metabox_status->set_meta_atts($metabox_status_atts);

	// Broadcast content metabox
	$metabox_broadcast_atts = array(
		'id'			=> 'vp_bcl_broadcast',
		'title'			=> __( "Broadcast", "vp_broadcast_lite" ),
		'description'	=> '',
		'page'			=> 'vp_broadcast',
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> array(
			array(
				'id'   => $opt_prefix . 'content',
				'type' => 'broadcast',
				'std'  => ''
			)
		)
	);
	$metabox_broadcast = new vp_Broadcast_Lite_Meta(20);
	$metabox_broadcast->set_meta_atts($metabox_broadcast_atts);

	// Settings metabox
	$metabox_settings_atts = array(
		'id'			=> 'vp_bcl_broadcast_settings',
		'title'			=> __( "Current broadcast settings", "vp_broadcast_lite" ),
		'description'	=> '',
		'page'			=> 'vp_broadcast',
		'context'		=> 'normal',
		'priority'		=> 'high',
		'sections'		=> array( 
			'description'       => '', 
			'time-settings'     => __( 'Date/time, place', 'vp_broadcast_lite' ), 
			'frontend-settings' => __( 'Frontend Settings', 'vp_broadcast_lite' ), 
			'social-settings'   => __( 'Social', 'vp_broadcast_lite' ) 
		),
		'fields'		=> array(
			array(
				'name' 		=> __( "Description:", "vp_broadcast_lite" ),
				'desc' 		=> '',
				'id' 		=> $opt_prefix . 'description',
				'type' 		=> 'editor',
				'std' 		=> '',
				'section'	=> 'description'
			),

			// Time settings
			array(
				'name' 		=> __( "Start Date", "vp_broadcast_lite" ),
				'desc' 		=> __( "Broadcast starts on", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'date',
				'type' 		=> 'date',
				'std' 		=> '',
				'section'	=> 'time-settings'
			),
			array(
				'name' 		=> __( "Start Time", "vp_broadcast_lite" ),
				'desc' 		=> __( "Broadcast starts at", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'time',
				'type' 		=> 'time',
				'std' 		=> '',
				'section'	=> 'time-settings'
			),
			array(
				'name' 		=> __( "Timezone", "vp_broadcast_lite" ),
				'desc' 		=> __( "Start time timezone", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'timezone',
				'type' 		=> 'text',
				'std' 		=> '',
				'section'	=> 'time-settings'
			),
			array(
				'name' 		=> __( "Place", "vp_broadcast_lite" ),
				'desc' 		=> __( "Where event will happened", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'place',
				'type' 		=> 'text',
				'std' 		=> '',
				'section'	=> 'time-settings'
			),

			// Frontend settings
			array(
				'name' 		=> __( "Refreshing type", "vp_broadcast_lite" ),
				'desc' 		=> __( "Select broadcast refreshing type on frontend", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'refresh',
				'type' 		=> 'select',
				'std' 		=> 'ajax',
				'options'   => array(
					'ajax'     => __( 'Via AJAX', 'vp_broadcast_lite' ),
					'manual'   => __( 'User refresh page manually', 'vp_broadcast_lite' )
				),
				'section'	=> 'frontend-settings'
			),
			array(
				'name' 		=> __( "Refreshing interval", "vp_broadcast_lite" ),
				'desc' 		=> __( "Set broadcast refreshing interval in seconds (min. value - 10 seconds)", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'interval',
				'type' 		=> 'text',
				'std' 		=> '30',
				'section'	=> 'frontend-settings'
			),
			array(
				'name' 		=> __( "Frontend style", "vp_broadcast_lite" ),
				'desc' 		=> __( "Select broadcast displaying style", "vp_broadcast_lite" ),
				'id' 		=> $opt_prefix . 'style',
				'type' 		=> 'select',
				'std' 		=> 'standart',
				'options'   => array(
					'standart' => __( 'Standart', 'vp_broadcast_lite' ),
					'timeline' => __( 'Timeline', 'vp_broadcast_lite' )
				),
				'section'	=> 'frontend-settings'
			)
		)
	);
	$metabox_settings_atts = apply_filters( 'vp_bcl_metabox_settings', $metabox_settings_atts );

	$metabox_settings = new vp_Broadcast_Lite_Meta(30);
	$metabox_settings->set_meta_atts($metabox_settings_atts);

	// Settings metabox
	$metabox_score_atts = array(
		'id'			=> 'vp_bcl_broadcast_scoreboard',
		'title'			=> __( "Scoreboard", "vp_broadcast_lite" ),
		'description'	=> '',
		'page'			=> 'vp_broadcast',
		'context'		=> 'normal',
		'priority'		=> 'high',
		'fields'		=> array(
			array(
				'name' 		=> __( "Team 1:", "vp_broadcast_lite" ),
				'desc' 		=> '',
				'id' 		=> $opt_prefix . 'team_1',
				'type' 		=> 'text',
				'std' 		=> ''
			),
			array(
				'name' 		=> __( "Team 1 score:", "vp_broadcast_lite" ),
				'desc' 		=> '',
				'id' 		=> $opt_prefix . 'team_1_score',
				'type' 		=> 'text',
				'std' 		=> ''
			),
			array(
				'name' 		=> __( "Team 2:", "vp_broadcast_lite" ),
				'desc' 		=> '',
				'id' 		=> $opt_prefix . 'team_2',
				'type' 		=> 'text',
				'std' 		=> ''
			),
			array(
				'name' 		=> __( "Team 2 score:", "vp_broadcast_lite" ),
				'desc' 		=> '',
				'id' 		=> $opt_prefix . 'team_2_score',
				'type' 		=> 'text',
				'std' 		=> ''
			)
		)
	);
	$metabox_score_atts = apply_filters( 'vp_bcl_metabox_scoreboard', $metabox_score_atts );

	$metabox_score = new vp_Broadcast_Lite_Meta(10);
	$metabox_score->set_meta_atts($metabox_score_atts);

}
add_action( 'init', 'vp_bcl_metbaoxes' );

add_action( 'vp_bcl_meta_control_broadcast', 'vp_bcl_broadcast_control', 10, 2 );
function vp_bcl_broadcast_control( $field, $value ) {

	global $allowedtags;

	if ( '' == $value ) {
		$value = array(
			'item_1' => array(
				'type'        => 'section',
				'label'       => __( "Let's go", "vp_broadcast_lite" ),
				'description' => __( "Our broadcast started", "vp_broadcast_lite" ),
			),
			'item_2' => array(
				'type'        => 'timestamp',
				'timestamp'   => __( "1'", "vp_broadcast_lite" ),
				'important'   => '0',
				'description' => __( "First timestamp. Starts from here", "vp_broadcast_lite" ),
			)
		);
	}
	if ( isset($value) && is_array($value) ) {

		echo '<div class="broadcast_content_wrapper">';
			$items_count = count($value);
			$counter = 1;
			echo '<div class="broadcast_content_box" data-items="' . $items_count . '">';
				foreach ($value as $key => $data) {
					if ( !isset($data['important']) ) {
						$data['important'] = 0;
					}
					echo '<div class="broadcast_item ' . $data['type'] . '" id="vp_bcl_item_' . $counter . '" data-item="' . $counter . '">';
						echo '<div class="broadcast_item_heading">';
							echo '<a href="#" class="broadcast_item_remove"><div class="dashicons dashicons-no"></div></a>';
							echo '<input type="hidden" name="' . $field['id'] . '[' . $key .']' . '[type]" value="' . $data['type'] . '">';
							if ( 'section' == $data['type'] ) {
								echo '<input type="text" name="' . $field['id'] . '[' . $key .']' . '[label]" value="' . $data['label'] . '">';
							} else {
								echo '<input type="text" name="' . $field['id'] . '[' . $key .']' . '[timestamp]" value="' . $data['timestamp'] . '">';
								echo '<label class="broadcast_item_important">' . __( 'Important event', 'vp_broadcast_lite' ) . ' <input type="checkbox" name="' . $field['id'] . '[' . $key .']' . '[important]" value="1" ' . checked( 1, $data['important'], false ) . '></label>';
							}
						echo '</div>';
						echo '<div class="broadcast_item_content">';
							echo '<textarea name="' . $field['id'] . '[' . $key .']' . '[description]" rows="5">' . wp_kses( $data['description'], $allowedtags ) . '</textarea>';
						echo '</div>';
					echo '</div>';
					$counter ++;
				}
			echo '</div>';

			echo '<div class="broadcast_actions">';
				echo '<a href="#" class="button button-primary button-large vp_update_broadcast">' . __( 'Update on frontend', 'vp_broadcast_lite' ) . '</a>';
				echo '<a href="#" class="button button-large" id="vp_add_broadcast_timestamp">' . __( 'Add timestamp', 'vp_broadcast_lite' ) . '</a>';
				echo '<a href="#" class="button button-large" id="vp_add_broadcast_section">' . __( 'Add section', 'vp_broadcast_lite' ) . '</a>';
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {

						$('#vp_add_broadcast_timestamp').on('click', function(event) {
							event.preventDefault();
							var $container    = $('.broadcast_content_box');
							var name          = '<?php echo $field["id"]; ?>';
							var current_count = parseInt($container.attr('data-items')) + 1;
							$container.attr('data-items', current_count);
	 						$container.append('<div class="broadcast_item timestamp" id="vp_bcl_item_' + current_count + '" data-item="' + current_count + '"><div class="broadcast_item_heading"></div><div class="broadcast_item_content"></div></div>');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_heading').append('<a href="#" class="broadcast_item_remove"><div class="dashicons dashicons-no"></div></a>');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_heading').append('<input type="hidden" name="' + name + '[item_' + current_count + '][type]" value="timestamp">');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_heading').append('<input type="text" name="' + name + '[item_' + current_count + '][timestamp]" value="">');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_heading').append('<label class="broadcast_item_important"><?php _e( "Important event", "vp_broadcast_lite" ); ?><input type="checkbox" name="' + name + '[item_' + current_count + '][important]" value="1"></label>');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_content').append('<textarea name="' + name + '[item_' + current_count + '][description]" rows="5"></textarea>');
						});

						$('#vp_add_broadcast_section').on('click', function(event) {
							event.preventDefault();
							var $container = $('.broadcast_content_box');
							var name = '<?php echo $field["id"]; ?>';
							var current_count = parseInt($container.attr('data-items')) + 1;
							$container.attr('data-items', current_count);
	 						$container.append('<div class="broadcast_item section" id="vp_bcl_item_' + current_count + '" data-item="' + current_count + '"><div class="broadcast_item_heading"></div><div class="broadcast_item_content"></div></div>');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_heading').append('<a href="#" class="broadcast_item_remove"><div class="dashicons dashicons-no"></div></a>');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_heading').append('<input type="hidden" name="' + name + '[item_' + current_count + '][type]" value="section">');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_heading').append('<input type="text" name="' + name + '[item_' + current_count + '][label]" value="">');
	 						$container.find('#vp_bcl_item_' + current_count + ' .broadcast_item_content').append('<textarea name="' + name + '[item_' + current_count + '][description]" rows="5"></textarea>');
						});

					});
				</script>
				<?php
			echo '</div>';
		echo '</div>';
	}
}

// Add custom columns to admin
add_filter('manage_vp_broadcast_posts_columns', 'vp_bcl_columns_head');  
add_action('manage_vp_broadcast_posts_custom_column', 'vp_bcl_columns_content', 10, 2); 
function vp_bcl_columns_head($columns) { 
	$columns['bc_id'] = 'Broadcast ID';
    return $columns;  
} 
function vp_bcl_columns_content($column_name, $post_ID) {
	if ( 'bc_id' == $column_name ) {
		echo $post_ID;
	}
}

?>