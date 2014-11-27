<?php
/* Template Name: Vehicle Edit*/
$uri = explode("/", $_SERVER['REQUEST_URI']);

if(count($uri) == 5){
	header('Location:'.home_url().'/vehicles/');
}

?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>
<?php
	$vehicle = get_post($uri[4]);
    $img = wp_get_attachment_image_src( get_post_thumbnail_id(  $vehicle->ID ), "small" );
    $img_link = $img[0];
    $vehicePhoto = $img_link ? '<img src="'.$img_link.'" alt="'.get_the_title().'" width="200" height="125">' : '';

?>
<h1 class="page-title"><span class="glyphicon glyphicon-cog"></span> <?php echo $vehicle->post_title;?></h1>

<div class="main-content">    
    <form id="addVehicle" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Vehicle Information</h3>
	    	<div class="form-group">
			    <label for="vehicleNumber" class="col-sm-2 control-label">Vehicle Number <span class="required">*</span></label>
			    <div class="col-sm-10">
				    <input type="text" class="form-control" id="vehicleNumber" name="vehicleNumber" placeholder="Vehicle Number" value="<?php echo $vehicle->post_title;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="profile_photo" class="col-sm-2 control-label">Photo</label>
			    <div class="col-sm-10">
			    	<?php if($vehicePhoto):?>
			    		<?php echo $vehicePhoto;?>
			    	<?php endif;?>
			    	<input type="file" name="vehicle_photo">
			    	<span class="description">Maximum file size: 500KB. Supported image type: PNG, JPG, JPEG, GIF</span>
			    </div>
		  	</div>
		  	
		</div>
		
	  	<div class="form-group">
		    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>
		    <div class="col-sm-10">
		    	<input type="hidden" name="action" value="addEditVehicle"/>
		    	<input type="hidden" name="isEdit" value="1"/>
		    	<input type="hidden" name="id" value="<?php echo $vehicle->ID;?>"/>
		    	<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		    	<button type="submit" class="btn btn-primary btn" value="_continue">
		    		<span class="glyphicon glyphicon-ok-sign"></span> Save &amp; Continue Editing
		    	</button>
		    	<button type="submit" class="btn btn-primary" value="_addAnother">
		    		<span class="glyphicon glyphicon-ok-sign"></span> Save &amp; Add Another
		    	</button>
		    	<button type="submit" class="btn btn-primary" value="_save">
		    		<span class="glyphicon glyphicon-ok-sign"></span> Save
		    	</button>
		    </div>
	  	</div>

    </form>
</div>
<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>