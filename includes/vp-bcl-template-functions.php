<?php
/**
 * vpBroadcast Lite - template functions
 *
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

// Page wrappers
add_action( 'vp_bl_before_page_content', 'vp_bl_default_page_wrappers_open' );
add_action( 'vp_bl_after_page_content', 'vp_bl_default_page_wrappers_close' );

// Single page/shortcode
add_action( 'vp_bl_single_header', 'vp_bl_single_title', 10 );
add_action( 'vp_bl_single_header', 'vp_bl_single_meta', 20 );
add_action( 'vp_bl_single_content', 'vp_bl_single_desciption', 5 );
add_action( 'vp_bl_single_content', 'vp_bl_single_scoreboard', 5 );
add_action( 'vp_bl_single_content', 'vp_bl_single_broadcast', 15 );
add_action( 'vp_bl_single_content', 'vp_broadcast_refresh', 15 );


/*
 * Define page wrappers
 */
function vp_bl_default_page_wrappers_open() {
	global $vpBroadcast_Settings;
	$open_wrapper = $vpBroadcast_Settings->get_option( 'vp_bl_page_wrap_open' );
	echo $open_wrapper;
}

function vp_bl_default_page_wrappers_close() {
	global $vpBroadcast_Settings;
	$close_wrapper = $vpBroadcast_Settings->get_option( 'vp_bl_page_wrap_close' );
	echo $close_wrapper;
}

/*
 * Single title ( for shortcode and single page )
 */
function vp_bl_single_title() {
	global $post;
	if ( 'vp_broadcast' == get_post_type() ) {
		$title_class = 'vp_bl_title entry-title';
		$title_tag   = 'h1';
	} else {
		$title_class = 'vp_bl_title';
		$title_tag   = 'h3';
	}
	$status = get_post_meta( $post->ID, 'vp_bcl_broadcast_status', true );
	$status_label = '';
	switch ($status) {
		case 'coming_soon':
			$status_label = __( 'Coming Soon', 'vp_broadcast_lite' );
			break;
		
		case 'live':
			$status_label = __( 'Live', 'vp_broadcast_lite' );
			break;

		case 'ended':
			$status_label = __( 'Finished', 'vp_broadcast_lite' );
			break;
	}
	echo '<' . $title_tag . ' class="' . esc_attr( $title_class ) . '">' . get_the_title() . ' <span class="vp_label ' . $status . '">' . $status_label . '</span></' . $title_tag . '>';
}

/*
 * Single meta
 */
function vp_bl_single_meta() {
	global $post;

	$date     = get_post_meta( get_the_id(), 'vp_bcl_broadcast_date', true );
	$time     = get_post_meta( get_the_id(), 'vp_bcl_broadcast_time', true );
	$timezone = get_post_meta( get_the_id(), 'vp_bcl_broadcast_timezone', true );
	$place    = get_post_meta( get_the_id(), 'vp_bcl_broadcast_place', true );

	echo '<div class="vp_broadcast_meta single_meta">';
		if ( $date ) {
			echo '<div class="vp_meta_date">' . date( get_option( 'date_format', 'd F Y' ), strtotime($date) ) . '</div>';
		}
		if ( $time ) {
			echo '<div class="vp_meta_time">';
			echo $time['hours'] . ':' . $time['mins'] . ' ' . $time['format'];
			if ($timezone) {
				echo ' ' . $timezone;
			}
			echo '</div>';
		}
		if ($place) {
			echo '<div class="vp_meta_place">' . $place . '</div>';
		}

	echo '</div>';
}

/*
 * Single description
 */
function vp_bl_single_desciption() {
	global $post;
	$description = get_post_meta( get_the_id(), 'vp_bcl_broadcast_description', true );
	
	if ( $description ) {
		echo '<div class="vp_broadcast_descr">' . $description . '</div>';
	}
}

/*
 * Single broadcast scoreboard
 */
function vp_bl_single_scoreboard() {
	global $vpBroadcast_Lite, $post;
	$team_1 = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'team_1', true );
	$team_1_score = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'team_1_score', true );
	$team_2 = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'team_2', true );
	$team_2_score = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'team_2_score', true );
	$refresh_type = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'refresh', true );
	$status = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'status', true );

	if ( ! $team_1 || ! $team_2 ) {
		return;
	}

	?>
	<div class="vp_broadcast_scoreboard">
		<div class="teams">
			<span class="team-1"><?php echo $team_1; ?></span>
			<div class="score">
				<span class="team-1-score"><?php echo $team_1_score; ?></span>
				<span class="team-2-score"><?php echo $team_2_score; ?></span>
			</div>
			<span class="team-2"><?php echo $team_2; ?></span>
		</div>
		<?php if ( 'ajax' == $refresh_type && 'live' == $status ) { ?>
		<div class="countdown-wrap">
			<div id="countdown"></div>
		</div>
		<?php } ?>
	</div>
	<?php
}
/*
 * Single broadcast content
 */
function vp_bl_single_broadcast() {
	global $vpBroadcast_Lite, $post;
	$current_style = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'style', true );
	if ( !$current_style ) {
		return;
	}
	echo '<div class="vp_bl_content_wrap">';
	switch ($current_style) {
		case 'timeline':
			$vpBroadcast_Lite->get_template( 'broadcast-timeline.php' );
			break;
		
		case 'standart':
			$vpBroadcast_Lite->get_template( 'broadcast-standart.php' );
			break;
	}
	echo '</div>';
}

/**
 * Single broadcast classes
 */
function vp_broadcast_class() {
	global $post, $vpBroadcast_Lite;
	$opt_prefix = $vpBroadcast_Lite->var_prefix;
	$bc_classes = array();
	$bc_classes[] = get_post_meta( $post->ID, $opt_prefix . 'status', true );
	$bc_classes[] = 'refresh_' . get_post_meta( $post->ID, $opt_prefix . 'refresh', true );
	$bc_classes[] = 'style_' . get_post_meta( $post->ID, $opt_prefix . 'style', true );
	$bc_classes[] = 'vp_broadcast_' . get_the_id();

	$bc_classes = apply_filters( 'vp_broadcast_classes', $bc_classes );

	$bc_classes_string = implode(' ', $bc_classes);

	echo $bc_classes_string;
}

/**
 * Refresh single broadcast
 */
function vp_broadcast_refresh() {
	global $post, $vpBroadcast_Lite;
	$opt_prefix       = $vpBroadcast_Lite->var_prefix;
	$refresh_type     = get_post_meta( $post->ID, $opt_prefix . 'refresh', true );
	$refresh_interval = get_post_meta( $post->ID, $opt_prefix . 'interval', true );
	$refresh_interval = intval($refresh_interval);
	if ( !$refresh_interval || 0 == $refresh_interval ) {
		$refresh_interval = 30;
	} elseif ( 10 > $refresh_interval ) {
		$refresh_interval = 10;
	}
	if ( 'manual' == $refresh_type ) {
		return;
	} elseif ( 'ajax' == $refresh_type ) {
		?>
		<script>
			/* <![CDATA[ */
			jQuery(document).ready(function($) {
				var post_id = <?php echo $post->ID; ?>,
					interval = <?php echo $refresh_interval; ?>;
				wpAjaxRun( post_id, interval );
			})
			/* ]]> */
		</script>
		<?php
	}
}

/**
 * Handle ajax refreshing
 */
function vp_bl_hadle_ajax() {
	global $vpBroadcast_Lite, $post;
	$post_id = $_REQUEST['post_id'];
	$result['type'] = 'success';
	ob_start();
	$vp_bcl_data = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'content', true );
	$vp_bcl_status = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'status', true );
	if ( $vp_bcl_data && is_array($vp_bcl_data) ) {
		$vp_bcl_data = array_reverse($vp_bcl_data);
	?>
	<?php
		$iter = 1;
		foreach ($vp_bcl_data as $item => $item_content) {
			$postition = 'vp_fullwidth';
			if ( isset($item_content['important']) && '1' == $item_content['important'] ) {
				$important_class = ' important';
				$iter = 1;
			} else {
				$important_class = '';
				if ( 'section' == $item_content['type'] ) {
					$iter = 1;
				} else {
					if ( $iter % 2 != 0 ) {
						$postition = 'vp_left';
					} else {
						$postition = 'vp_right';
					}
					$iter++;
				}
			}
			?>
			<div class="vp_broadcast_item <?php echo esc_attr( $item ) . ' ' . esc_attr( $item_content['type'] ) . ' ' . $postition . $important_class; ?>">
			<?php
				if ( 'section' == $item_content['type'] ) {
					echo '<h4 class="section_title">' . $item_content["label"] . '</h4>';
				 	echo '<div class="section_desc">' . $item_content["description"] . '</div>';
				}
				if ( 'timestamp' == $item_content['type'] ) {
					echo '<div class="timestamp_time">' . $item_content["timestamp"] . '</div>';
				 	echo '<div class="timestamp_event">' . $item_content["description"] . '</div>';
				}
			?>
				<div class="vp_marker"></div>
			</div>
			<?php
		}
		unset($item, $item_content);
	?>
	<?php }
	$result['content'] = ob_get_contents();
	ob_end_clean();
	$team_1_score = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'team_1_score', true );
	$team_2_score = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'team_2_score', true );
	$result['team_1_score'] = $team_1_score;
	$result['team_2_score'] = $team_2_score;
	$result['status'] = $vp_bcl_status;
	wp_send_json( $result );
}
add_action( 'wp_ajax_vp_bl_ajax_refresh', 'vp_bl_hadle_ajax' );  
add_action( 'wp_ajax_nopriv_vp_bl_ajax_refresh', 'vp_bl_hadle_ajax' );
?>