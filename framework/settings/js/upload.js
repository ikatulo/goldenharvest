jQuery(document).ready(function() {

	jQuery('#tropix_logo_upload_button').click(function() {
	
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tropix_options\\[tropix_logo_url\\]').val(imgurl);
			tb_remove();
		}
		
		tb_show('Upload Logo Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
		return false;
	
	});
	
	jQuery('#tropix_favicon_button').click(function() {
	
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tropix_options\\[tropix_favicon\\]').val(imgurl);
			tb_remove();
		}
		
		tb_show('Upload Favicon Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
		return false;
	
	});
	
	jQuery('#tropix_apple_touch_button').click(function() {
	
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#tropix_options\\[tropix_apple_touch\\]').val(imgurl);
			tb_remove();
		}
		
		tb_show('Upload Apple Touch Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
		return false;
	
	});

	jQuery('#tropix_slideshow1_upload_button').click(function() {

        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#tropix_options\\[tropix_slideshow1_url\\]').val(imgurl);
            tb_remove();
        }

        tb_show('Upload Logo Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
        return false;

    });

    jQuery('#tropix_slideshow2_upload_button').click(function() {

        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#tropix_options\\[tropix_slideshow2_url\\]').val(imgurl);
            tb_remove();
        }

        tb_show('Upload Logo Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
        return false;

    });

    jQuery('#tropix_slideshow3_upload_button').click(function() {

        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#tropix_options\\[tropix_slideshow3_url\\]').val(imgurl);
            tb_remove();
        }

        tb_show('Upload Logo Image', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
        return false;

    });
    
    
});