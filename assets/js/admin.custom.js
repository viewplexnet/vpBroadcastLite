jQuery(document).ready(function($) {

	// Remove item
	$(document).on('click', '.broadcast_item_remove', function(event) {
		
		event.preventDefault();
		
		var $container = $('.broadcast_content_box');
			$parent = $(this).parents('.broadcast_item')
			removed_index = parseInt($parent.attr('data-item'));
			current_count = parseInt($container.attr('data-items')) - 1;
		
		$parent.remove();
		$container.attr('data-items', current_count);
		
		$container.find('.broadcast_item').each(function(index, el) {
			var this_index = parseInt($(this).attr('data-item'));
			if ( this_index > removed_index ) {
				$(this).attr('data-item', this_index - 1);
				$(this).attr( 'id','vp_bcl_item_' + (this_index - 1) );
			}
		});

	});

	$('.vp_update_broadcast').click(function(e) {
	    e.preventDefault();
	    //$(this).after('<input type="hidden" name="active_item" value="' + $('.broadcast_content_box').attr('data-items') + '">');
	    $('#publish').click();
	});

});