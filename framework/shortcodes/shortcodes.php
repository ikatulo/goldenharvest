<?php
	

	/*** wysiwyg left ***/

	add_shortcode( 'wysiwyg-left', 'tropix_wysiwyg_left_shortcodes' );
	function run_wysiwyg_left_shortcode( $content ) {
	    global $shortcode_tags;
	    $orig_shortcode_tags = $shortcode_tags;
	    remove_all_shortcodes(); 
	    add_shortcode( 'wysiwyg-left', 'tropix_wysiwyg_left_shortcodes' );	
	    $content = do_shortcode( $content );
	    $shortcode_tags = $orig_shortcode_tags; 
	    return $content;
	} 
	add_filter( 'the_content', 'run_wysiwyg_left_shortcode', 7 );

	if (!function_exists('tropix_wysiwyg_left_shortcodes')) {

		function tropix_wysiwyg_left_shortcodes( $atts, $content = null ) {
	 	    extract(shortcode_atts(array(
		   		'style' => '',
		       ), $atts));
	 	    
			$class = $style; 

			$useImage = '<div class="wysiwyg-wrapper"><div class="'.$class.'">'.$content.'</div>';
			return $useImage;
		}
	}

	/*** wysiwyg right ***/

	add_shortcode( 'wysiwyg-right', 'tropix_wysiwyg_right_shortcodes' );
	function run_wysiwyg_right_shortcode( $content ) {
	    global $shortcode_tags;
	    $orig_shortcode_tags = $shortcode_tags;
	    remove_all_shortcodes(); 
	    add_shortcode( 'wysiwyg-right', 'tropix_wysiwyg_right_shortcodes' );	
	    $content = do_shortcode( $content );
	    $shortcode_tags = $orig_shortcode_tags; 
	    return $content;
	} 
	add_filter( 'the_content', 'run_wysiwyg_right_shortcode', 7 );

	if (!function_exists('tropix_wysiwyg_right_shortcodes')) {

		function tropix_wysiwyg_right_shortcodes( $atts, $content = null ) {
	 	    extract(shortcode_atts(array(
		   		'style' => '',
		       ), $atts));
	 	    
			$class = $style; 

			$useImage = '<div class="'.$class.'">'.$content.'</div></div>';
			return $useImage;
		}
	}
?>