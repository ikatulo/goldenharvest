<?php

	add_action( 'admin_head', 'wysiwyg_column_add_tinymce' );

	function wysiwyg_column_add_tinymce(){
		global $typenow;
	
		if( ! in_array( $typenow, array( 'post', 'page' ) ) )
			return ;
		
		add_filter( 'mce_external_plugins', 'wysiwyg_column_add_tinymce_plugin' );
		// Add to line 1 form WP TinyMCE
		add_filter( 'mce_buttons', 'wysiwyg_column_add_tinymce_button' );
	 
	}

	function wysiwyg_column_add_tinymce_plugin($plugin_array){
		$plugin_array['wysiwyg_column'] = get_template_directory_uri()."/framework/shortcodes/tinymce/plugin.js";
		return $plugin_array;
	}

	function wysiwyg_column_add_tinymce_button($buttons){
		array_push( $buttons, 'wysiwyg_column_button' );
		return $buttons;
	}
?>