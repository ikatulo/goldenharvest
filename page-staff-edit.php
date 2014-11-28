<?php
/* Template Name: Staff Edit*/

$uri = explode("/", $_SERVER['REQUEST_URI']);

if(count($uri) == 5){
	header('Location:'.home_url().'/staff/');
}

?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	global $current_user;
	global $wp_roles;

	$roles = $wp_roles->get_names();

	$user = get_user_by('id', $uri[4]);

	$nric = get_the_author_meta('nric', $user->ID);
	$gender = get_the_author_meta('gender', $user->ID);
	$position = get_the_author_meta('position', $user->ID);
	$status = get_the_author_meta('employment_status', $user->ID);
	$driving_license = get_the_author_meta('driving_license', $user->ID);
	$birthday = get_the_author_meta('birthday', $user->ID);
	$address = get_the_author_meta('address', $user->ID);
	$postalcode = get_the_author_meta('postalcode', $user->ID);
	$phone = get_the_author_meta('phone', $user->ID);
	$kin = get_the_author_meta('kin', $user->ID);
	$remarks = get_the_author_meta('remarks', $user->ID);
	$photo = (get_the_author_meta('profile_photo', $user->ID) != "") ? get_the_author_meta('profile_photo', $user->ID) : get_template_directory_uri().'/images/no-photo.jpg';
?>
<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">    
    <form id="addStaff" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Account Information</h3>
	    	<div class="form-group">
			    <label for="username" class="col-sm-2 control-label">Username <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $user->user_login;?>" readonly>
			      <span class="description">30 characters maximum. Letters, numbers, underscores and dashes only. <b style="color:red;">Can not be changed.</b></span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="email" class="col-sm-2 control-label">Email <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?php echo $user->user_email;?>">
			      <span class="description">Example: mail@example.com.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="pass1" class="col-sm-2 control-label">New Password <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" id="pass1" name="pass1" placeholder="" value="">
			      <span class="description">8 characters minimum.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="pass2" class="col-sm-2 control-label">Repeat New Password <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" id="pass2" name="pass2" placeholder="" value="">
			      <span class="description">Must match with password.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="role" class="col-sm-2 control-label">Role <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <select id="role" name="role" class="standard-dropdown" data-placeholder="Select Role">
			      	<option value=""></option>
			      	<?php foreach($roles as $key => $value):?>
			      		<?php if($key != "administrator"):?>
			      		<?php $selected = ($user->roles[0] == $key) ? 'selected="selected"' : '';?>
			      		<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
			      		<?php endif;?>
			      	<?php endforeach;?>
			      </select>
			    </div>
		  	</div>
		</div>
		<div class="form-section">
		  	<h3><span class="glyphicon glyphicon-th-list"></span> Position</h3>
		  	<div class="form-group">
			    <label for="nric" class="col-sm-2 control-label">Position <span class="required">*</span></label>
			    <div class="col-sm-10">
			      	<select id="position" name="position" class="standard-dropdown" data-placeholder="Select Position">
				<option value=""></option>
		            	<option value="director">Director</option>
		            	<option value="HRmanager">HR & ACC Manager</option>
		            	<option value="admin">Admin</option>
		            	<option value="supervisor">Supervisor</option>
		            	<option value="teamleader">Teamleader</option>
		            	<option value="technician">Technician</option>
	            	        </select>
			      <span class="description">Please select position.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="status" class="col-sm-2 control-label">Employment Status <span class="required">*</span></label>
			    <div class="col-sm-10">
			    	<?php
			    		$arrStatus = array(
			    			"temporary" => "Temporary",
			    			"permanent" => "Permanent"
			    		);
			    	?>
			      	<select id="employment_status" name="employment_status" class="standard-dropdown" data-placeholder="Select Employment Status">
				      	<option value=""></option>
		            	<?php foreach($arrStatus as $key => $value):?>
				      	<?php $selected = ($status == $key) ? 'selected="selected"' : '';?>
				      	<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option> 
				      	<?php endforeach;?>
	            	</select>
			      <span class="description">Please select employment status.</span>
			    </div>
		  	</div>
		</div>
		<div class="form-section">
		  	<h3><span class="glyphicon glyphicon-th-list"></span> Personal Information</h3>
		  	<div class="form-group">
			    <label for="nric" class="col-sm-2 control-label">NRIC/FIN No <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="nric" name="nric" placeholder="NRIC/FIN number" value="<?php echo $nric;?>">
			      <span class="description">Please fill correct NRIC/FIN number format.</span>
			    </div>
		  	</div>		  	
		  	<div class="form-group">
			    <label for="first_name" class="col-sm-2 control-label">First Name <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo $user->first_name;?>">
			      <span class="description">Can not contain space or special characters.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="last_name" class="col-sm-2 control-label">Last Name</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $user->last_name;?>">
			      <span class="description">Can not contain space or special characters.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="birthday" class="col-sm-2 control-label">Birthday <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control datepicker-past" id="birthday" name="birthday" placeholder="" value="<?php echo $birthday;?>" autocomplete="off">
			    </div>
		  	</div>
		  	<div class="form-group">
		  		<label for="gender" class="col-sm-2 control-label">Gender <span class="required">*</span></label>
		  		<div class="col-sm-10">
		  			<?php
		  				if($gender == "male"){
		  					$male = 'checked="checked"';
		  					$female = '';
		  				}else{
		  					$female = 'checked="checked"';
		  					$male = '';
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
			    <label for="address" class="col-sm-2 control-label">Address</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo $address;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="postalcode" class="col-sm-2 control-label">Postal Code</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="postalcode" name="postalcode" placeholder="Postal Code" value="<?php echo $postalcode;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="phone" class="col-sm-2 control-label">Phone Number</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="<?php echo $phone;?>">
			      <span class="description">Example: +65 123456789 or 123456789</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="kin" class="col-sm-2 control-label">Next of Kin</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="kin" name="kin" placeholder="Next of kin" value="<?php echo $kin;?>">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="remarks" class="col-sm-2 control-label">Remarks</label>
			    <div class="col-sm-10">
			    	<textarea class="form-control" name="remarks" id="remarks"><?php echo $remarks;?></textarea>
			    </div>
		  	</div>
		  	<div class="form-group">
		  		<label for="driving_license" class="col-sm-2 control-label">Has Driving License</label>
		  		<div class="col-sm-10">
		  			<?php $drv_checked = ($driving_license) ? 'checked="checked"' : '';?> 
			  		<label class="checkbox-inline">
					  <input type="checkbox" name="driving_license" id="driving_license" value="Yes" <?php echo $drv_checked;?>> Yes
					</label>
				</div>
		  	</div>
		  	<div class="form-group">
			    <label for="profile_photo" class="col-sm-2 control-label">Photo</label>
			    <div class="col-sm-10">
			    	<img src="<?php echo $photo;?>" width="64" height="64" style="float:left;margin-right:10px;">
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
			    	<input type="hidden" name="action" value="addEditStaff"/>
			    	<input type="hidden" name="isEdit" value="1"/>
			    	<input type="hidden" name="userId" value="<?php echo $uri[4];?>"/>
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
		</div>
    </form>
</div>
<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>
