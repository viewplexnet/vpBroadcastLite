jQuery(document).ready(function(){
    create_settings_tab(jQuery('.vpt-tab-nav'), jQuery('.vpt-tab-content'));
    jQuery('.wp-color-picker-field').wpColorPicker();
})

function create_settings_tab(tabNav, tabContent){
    var titles = jQuery('>h3', tabContent),
        count = titles.length,
        tabItem,
        tabBtn;
    
    for(i=0; i<count; i++ ){
        titles.eq(i).next().andSelf().wrapAll('<div class="vpt-tab">');
        jQuery('<a class="vpt-tab-btn" href="#">'+titles.eq(i).text()+'</a>').appendTo(tabNav);
    }
    
    tabItem = jQuery('.vpt-tab', tabContent);
    tabBtn = jQuery('.vpt-tab-btn', tabNav);
    
    tabItem.eq(0).addClass('active');
    tabBtn.eq(0).addClass('active');
    
    tabBtn.each(function(){
        jQuery(this).click(function(){
            index = jQuery(this).index();
            tabItem.removeClass('active');
            tabItem.eq(index).addClass('active');
            tabBtn.removeClass('active');
            tabBtn.eq(index).addClass('active');
            return false;
        })
    });
    
}