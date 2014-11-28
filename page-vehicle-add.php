<?php
/* Template Name: Vehicle Add*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>
<h1 class="page-title"><span class="glyphicon glyphicon-cog"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">    
    <form id="addVehicle" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Vehicle Information</h3>
	    	<div class="form-group">
			    <label for="vehicleNumber" class="col-sm-2 control-label">Vehicle Number <span class="required">*</span></label>
			    <div class="col-sm-10">
				    <input type="text" class="form-control" id="vehicleNumber" name="vehicleNumber" placeholder="Vehicle Number" value="">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="profile_photo" class="col-sm-2 control-label">Photo</label>
			    <div class="col-sm-10">
			    	<input type="file" name="vehicle_photo">
			    	<span class="description">Maximum file size: 500KB. Supported image type: PNG, JPG, JPEG, GIF</span>
			    </div>
		  	</div>
		  	
		</div>
		
	  	<div class="form-group">
		    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>
		    <div class="col-sm-10">
		    	<input type="hidden" name="action" value="addEditVehicle"/>
		    	<input type="hidden" name="isEdit" value="0"/>
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