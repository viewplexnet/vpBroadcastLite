jQuery.fn.countDown = function(settings,to) {
    settings = jQuery.extend({
        startFontSize: '28px',
        endFontSize: '14px',
        duration: 1000,
        startNumber: 10,
        endNumber: 0,
        callBack: function() { }
    }, settings);
    return this.each(function() {
        
        //where do we start?
        if(!to && to != settings.endNumber) { to = settings.startNumber; }
        
        //set the countdown to the starting number
        jQuery(this).text(to).css('fontSize',settings.startFontSize);
        
        //loopage
        jQuery(this).animate({
            'fontSize': settings.endFontSize
        },settings.duration,'',function() {
            if(to > settings.endNumber + 1) {
                jQuery(this).css('fontSize',settings.startFontSize).text(to - 1).countDown(settings,to - 1);
            }
            else
            {
                settings.callBack(this);
            }
        });
                
    });
};

function wpAjaxRun( post_id, interval ) {
    jQuery('#countdown').countDown({
        startNumber: interval,
        callBack: function(me) {
            jQuery.ajax({
                type : 'post',
                dataType : 'json',
                url : vp_bl_ajax.url,
                data : {
                    action: 'vp_bl_ajax_refresh', 
                    post_id: post_id
                },
                success: function(response) {
                    if ( response.type == 'success' ) {
                        jQuery('#vp-broadcast-' + post_id).find('.vp_broadcast_data').html(response.content);
                        jQuery('#vp-broadcast-' + post_id).find('.team-1-score').html(response.team_1_score);
                        jQuery('#vp-broadcast-' + post_id).find('.team-2-score').html(response.team_2_score);
                        if ( response.status != 'ended' ) {
                            wpAjaxRun( post_id, interval );
                        } else {
                            jQuery('#vp-broadcast-' + post_id).find('.countdown-wrap').remove();
                        }
                    }
                }
            });
        }
    });
}

/*
$('#countdown').countDown({
    startNumber: 10,
    callBack: function(me) {
        $(me).text('All done! This is where you give the reward!').css('color','#090');
    }
});
*/