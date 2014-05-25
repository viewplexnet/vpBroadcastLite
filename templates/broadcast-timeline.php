<?php
/**
 * vpBroadcast Lite - timeline single broadcast content template
 *
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $vpBroadcast_Lite, $post;
$vp_bcl_data = get_post_meta( $post->ID, $vpBroadcast_Lite->var_prefix . 'content', true );
if ( $vp_bcl_data && is_array($vp_bcl_data) ) {
	$vp_bcl_data = array_reverse($vp_bcl_data);
?>
<div class="vp_broadcast_data timeline vp_bc_<?php echo $post->ID; ?>"><?php
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
<script type="text/javascript">
	(function($) {
		$(window).load(function() {
			
		});
	})(jQuery);
</script>
</div>
<?php } ?>