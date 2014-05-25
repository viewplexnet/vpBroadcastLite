<?php
/**
 * vpBroadcast Lite - default single broadcast template
 *
 * @package vpBroadcast Lite
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $vpBroadcast_Lite;

get_header(); ?>
	
	<?php 
		/**
		 * vp_bl_before_page_content hook
		 *
		 * Use this hook for adding custom elements between header and main page content, or open divs for your content wrappers
		 */
		do_action( 'vp_bl_before_page_content' );
	?>
	<div class="vp_wrapper vp_single_wrapper">
		<?php
			// Load stndart ot timeline styled broadcast content
			$vpBroadcast_Lite->get_template( 'content-broadcast.php' );
		?>
	</div>
	<?php
		/**
		 * vp_bl_after_page_content hook
		 *
		 * Use this hook for adding custom elements between main page content and footer, or close divs for your content wrappers
		 */
		do_action( 'vp_bl_after_page_content' );
	?>
<?php $vpBroadcast_Lite->get_sidebar(); ?>
<?php get_footer(); ?>