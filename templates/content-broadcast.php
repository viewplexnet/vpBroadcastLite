<?php
/**
 * vpBroadcast Lite - default single broadcast content template
 *
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $vpBroadcast_Lite, $post;

$vl_bl_current_type = get_post_type();
if ( 'vp_broadcast' == $vl_bl_current_type ) {
	$vp_bl_header_class  = 'entry-header';
	$vp_bl_content_class = 'entry-content';
} else {
	$vp_bl_header_class  = 'vp-item-header';
	$vp_bl_content_class = 'vp-item-content';
}

?>
<div id="<?php echo 'vp-broadcast-' . get_the_id(); ?>" class="<?php vp_broadcast_class(); ?>">
	<div class="<?php echo $vp_bl_header_class; ?>">
	<?php 
		/**
		 * vp_bl_single_header hook
		 *
		 * @hooked vp_bl_single_thumb 5
		 * @hooked vp_bl_single_header 10
		 * @hooked vp_bl_single_meta 20
		 */
		do_action( 'vp_bl_single_header' );
	?>
	</div>
	<div class="<?php echo $vp_bl_content_class; ?>">
	<?php 
		/**
		 * vp_bl_single_content hook
		 *
		 * @hooked vp_bl_single_description 5
		 * @hooked vp_bl_single_scoreboard 10
		 * @hooked vp_bl_single_broadcast 15
		 * @hooked vp_broadcast_refresh 25
		 */
		do_action( 'vp_bl_single_content' );
	?>
	</div>
</div>