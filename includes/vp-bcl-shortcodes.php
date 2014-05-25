<?php
/**
 * vpBroadcast Lite - add shortcodes
 *
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Broadcasts list
 */
add_shortcode( 'broadcast_list', 'vp_bl_broadcast_list' );
function vp_bl_broadcast_list( $atts ) {
	extract(shortcode_atts( 
		array(
			'cast_status' => 'all',
			'num'         => 4,
			'display'     => 'row',
			'link'        => 'yes'
		), 
		$atts, 
		'broadcast_list' )
	);

	$num = intval($num);

	if ( !$num ) {
		$num = 4;
	}

	$bc_args = array(
		'posts_per_page' => $num,
		'post_type'      => 'vp_broadcast'
	);

	if ( $cast_status && 'all' != $cast_status ) {
		$bc_args['meta_key']   = 'vp_bcl_broadcast_status';
		$bc_args['meta_value'] = $cast_status;
	}

	$bc_query = new WP_Query($bc_args);
	$output = '';
	if ( $bc_query->have_posts() ) {
		$output .= '<ul class="vp_broadcast_list ' . esc_attr( $display ) . '">';
		while ( $bc_query->have_posts() ) {
			$bc_query->the_post();
			global $vpBroadcast_Lite;
			$post_id = get_the_id();
			$team_1 = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'team_1', true );
			$team_1_score = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'team_1_score', true );
			$team_2 = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'team_2', true );
			$team_2_score = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'team_2_score', true );
			$status_v = get_post_meta( $post_id, $vpBroadcast_Lite->var_prefix . 'status', true );
			$status_label = '';
			switch ($status_v) {
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
			$output .= '<li class="vp_broadcast_list_item">';
				if ( 'yes' == $link ) {
					$output .= '<a href="' . get_permalink() . '">';
				}
					$output .= '<span class="vp_label ' . $status_v . '">' . $status_label . '</span>';
					$output .= '<div class="vp_broadcast_list_item_content">';
						$output .= '<div class="vp_bl_team_1">' . $team_1 . '</div>';
						$output .= '<div class="vp_bl_scorebox"><b>' . $team_1_score . '</b><b>' . $team_2_score . '</b></div>';
						$output .= '<div class="vp_bl_team_2">' . $team_2 . '</div>';
					$output .= '</div>';
				if ( 'yes' == $link ) {
					$output .= '</a>';
				}
			$output .= '</li>';
		}
		$output .= '</ul>';
	}
	wp_reset_postdata();
	wp_reset_query();


	return $output;
}

/**
 * Single broadcast by ID
 */
add_shortcode( 'single_broadcast', 'vp_bl_single_broadcast_shortcode' );
function vp_bl_single_broadcast_shortcode( $atts ) {
	extract(shortcode_atts( 
		array(
			'id'      => ''
		), 
		$atts, 
		'broadcast_list' )
	);

	if ( !$id ) {
		$bc_args = array(
			'post_type'      => 'vp_broadcast',
			'posts_per_page' => 1
		);
	} else {
		$bc_args = array(
			'post_type' => 'vp_broadcast',
			'p'         => $id
		);
	}

	$bc_query = new WP_Query($bc_args);
	ob_start();
	if ( $bc_query->have_posts() ) {
		while ( $bc_query->have_posts() ) {
			$bc_query->the_post();
			global $vpBroadcast_Lite;
			$vpBroadcast_Lite->get_template( 'content-broadcast.php' );
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	$result = '<div class="vp_single_broadcast">' . ob_get_contents() . '</div>';

	ob_end_clean();

	return $result;
}
?>