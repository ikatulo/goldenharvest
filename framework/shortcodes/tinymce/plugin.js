// closure to avoid namespace collision
(function () {
	// create the plugin

	tinymce.PluginManager.add('wysiwyg_column', function(editor, url) {
		var self = this;

		editor.addButton('wysiwyg_column_button', {
			type: 'menubutton',
			text: 'WYSIWYG Column',
			icon: false,
			//image: byronShortCodes.plugin_folder +"/tinymce/images/icon1.png",
			menu: [
                {
                	text: 'Column Left', 
                	onclick: function() {
                		editor.insertContent('[wysiwyg-left  style="wysiwyg-left"]<br>Content goes here...<br>[/wysiwyg-left]');
                	}
                },
                {
                	text: 'Column Right', 
                	onclick: function() {
                		editor.insertContent('[wysiwyg-right  style="wysiwyg-right"]<br>Content goes here...<br>[/wysiwyg-right]');
                	}
                }
            ]
		});
	});

})();