(
	function(){

		tinymce.create(
			"tinymce.plugins.vpBroadcastLiteShortcodes",
			{
				init: function(d,e) {},
				createControl:function(d,e)
				{

					var ed = tinymce.activeEditor;

					if(d=="vp_bcl_shortcodes_button"){

						d=e.createMenuButton( "vp_bcl_shortcodes_button",{
							title: 'Insert Shortcode',
							icons: false
							});

							var a=this;d.onRenderMenu.add(function(c,b){
								a.addImmediate(b, 'Broadcast List', '[broadcast_list cast_status="all" num="4" display="row" link="yes"]');
								a.addImmediate(b, 'Single Broadcast', '[single_broadcast id=""]');
							});
						return d

					} // End IF Statement

					return null
				},

				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}

			}
		);

		tinymce.PluginManager.add( "vpBroadcastLiteShortcodes", tinymce.plugins.vpBroadcastLiteShortcodes);
	}
)();