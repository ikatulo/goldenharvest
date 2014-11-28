<?php
/* Template Name: Profile*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	global $current_user;
	$nric = get_the_author_meta('nric', $current_user->ID);
	$gender = get_the_author_meta('gender', $current_user->ID);
	$address = get_the_author_meta('address', $current_user->ID);
	$postalcode = get_the_author_meta('postalcode', $current_user->ID);
	$phone = get_the_author_meta('phone', $current_user->ID);
	$kin = get_the_author_meta('kin', $current_user->ID);
	$remarks = get_the_author_meta('remarks', $current_user->ID);
	$photo = (get_the_author_meta('profile_photo', $current_user->ID) != "") ? get_the_author_meta('profile_photo', $current_user->ID) : get_template_directory_uri().'/images/no-photo.jpg';
?>

<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">    
    <form id="profile" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Account Information</h3>
	    	<div class="form-group">
			    <label for="username" class="col-sm-2 control-label">Username <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="username" name="username" placeholder="" readonly="" value="<?php echo $current_user->user_login;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="email" class="col-sm-2 control-label">Email <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="email" class="form-control" id="email" name="email" placeholder="" value="<?php echo $current_user->user_email;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="pass1" class="col-sm-2 control-label">New Password</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" id="pass1" name="pass1" placeholder="" value="">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="pass2" class="col-sm-2 control-label">Repeat New Password</label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" id="pass2" name="pass2" placeholder="" value="">
			    </div>
		  	</div>
		</div>
		<div class="form-section">
		  	<h3><span class="glyphicon glyphicon-th-list"></span> Personal Information</h3>
		  	<div class="form-group">
			    <label for="nric" class="col-sm-2 control-label">NRIC/FIN No <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="nric" name="nric" placeholder="" value="<?php echo $nric;?>">
			    </div>
		  	</div>		  	
		  	<div class="form-group">
			    <label for="first_name" class="col-sm-2 control-label">First Name <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="<?php echo $current_user->first_name;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="last_name" class="col-sm-2 control-label">Last Name</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="<?php echo $current_user->last_name;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="birthday" class="col-sm-2 control-label">Birthday <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control datepicker" id="birthday" name="birthday" placeholder="" value="<?php echo $birthday;?>" autocomplete="off">
			    </div>
		  	</div>
		  	<div class="form-group">
		  		<label for="gender" class="col-sm-2 control-label">Gender <span class="required">*</span></label>
		  		<div class="col-sm-10">
		  			<?php
		  				if($gender == "male"){
		  					$male = "checked='checked'";
		  					$female = "";
		  				}else{
		  					$male = "";
		  					$female = "checked='checked'";
		  				}
		  			?>
			  		<label class="radio-inline">
					  <input type="radio" name="gender" id="male" value="male" <?php echo $male;?>> Male
					</label>
					<label class="radio-inline">
					  <input type="radio" name="gender" id="female" value="female" <?php echo $female;?>> Female
					</label>
				</div>
		  	</div>
		  	<div class="form-group">
			    <label for="address" class="col-sm-2 control-label">Address <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="address" name="address" placeholder="" value="<?php echo $address;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="postalcode" class="col-sm-2 control-label">Postal Code <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="postalcode" name="postalcode" placeholder="" value="<?php echo $postalcode;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="phone" class="col-sm-2 control-label">Phone Number <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="phone" name="phone" placeholder="" value="<?php echo $phone;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="kin" class="col-sm-2 control-label">Next of Kin <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="kin" name="kin" placeholder="" value="<?php echo $kin;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="remarks" class="col-sm-2 control-label">Remarks</label>
			    <div class="col-sm-10">
			    	<textarea class="form-control" name="remarks" id="remarks"><?php echo $remarks;?></textarea>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="profile_photo" class="col-sm-2 control-label">Photo</label>
			    <div class="col-sm-10">
			    	<?php if($photo):?>
			    		<img src="<?php echo $photo;?>" width="64" height="64" style="float:left;margin-right:10px;">
			    	<?php endif;?>
			    	<input type="file" name="profile_photo">
			    	<span class="description">Maximum file size: 500KB. Supported image type: PNG, JPG, JPEG, GIF</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>
			    <div class="col-sm-10"></div>
		  	</div>
		  	<div class="form-group">
			    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>
			    <div class="col-sm-10">
			    	<input type="hidden" name="action" value="updateProfile"/>
			    	<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
			    	<button type="submit" class="btn btn-primary">
			    		<span class="glyphicon glyphicon-ok-sign"></span> Update
			    	</button>
			    </div>
		  	</div>
		</div>
    </form>
</div>
<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>
