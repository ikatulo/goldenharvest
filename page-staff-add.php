<?php
/* Template Name: Staff Add*/
?>

<?php if(is_user_logged_in()):?>

<?php 
	global $current_user;
	global $wp_roles;

	$roles = $wp_roles->get_names();

?>

<?php get_header();?>
<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">    
    <form id="addStaff" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Account Information</h3>
	    	<div class="form-group">
			    <label for="username" class="col-sm-2 control-label">Username <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="">
			      <span class="description">30 characters maximum. Letters, numbers, underscores and dashes only. <b style="color:red;">Can not be changed.</b></span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="email" class="col-sm-2 control-label">Email <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="">
			      <span class="description">Example: mail@example.com.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="pass1" class="col-sm-2 control-label">Password <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="password" class="form-control" id="pass1" name="pass1" placeholder="" value="">
			      <span class="description">8 characters minimum.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="pass2" class="col-sm-2 control-label">Repeat Password <span class="required">*</span></label>
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
			      		<option value="<?php echo $key;?>"><?php echo $value;?></option>
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
			      	<select id="employment_status" name="employment_status" class="standard-dropdown" data-placeholder="Select Employment Status">
				      	<option value=""></option>
		            	<option value="temporary">Temporary</option>
		            	<option value="permanent">Permanent</option>
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
			      <input type="text" class="form-control" id="nric" name="nric" placeholder="NRIC/FIN number" value="">
			      <span class="description">Please fill correct NRIC/FIN number format.</span>
			    </div>
		  	</div>		  	
		  	<div class="form-group">
			    <label for="first_name" class="col-sm-2 control-label">First Name <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="">
			      <span class="description">Can not contain space or special characters.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="last_name" class="col-sm-2 control-label">Last Name</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="">
			      <span class="description">Can not contain space or special characters.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="birthday" class="col-sm-2 control-label">Birthday <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control datepicker-past" id="birthday" name="birthday" placeholder="" value="" autocomplete="off">
			    </div>
		  	</div>
		  	<div class="form-group">
		  		<label for="gender" class="col-sm-2 control-label">Gender <span class="required">*</span></label>
		  		<div class="col-sm-10">
			  		<label class="radio-inline">
					  <input type="radio" name="gender" id="male" value="male"> Male
					</label>
					<label class="radio-inline">
					  <input type="radio" name="gender" id="female" value="female"> Female
					</label>
				</div>
		  	</div>
		  	<div class="form-group">
			    <label for="address" class="col-sm-2 control-label">Address</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="postalcode" class="col-sm-2 control-label">Postal Code</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="postalcode" name="postalcode" placeholder="Postal Code" value="">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="phone" class="col-sm-2 control-label">Phone Number</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="">
			      <span class="description">Example: +65 123456789 or 123456789</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="kin" class="col-sm-2 control-label">Next of Kin</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="kin" name="kin" placeholder="Next of kin" value="">
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="remarks" class="col-sm-2 control-label">Remarks</label>
			    <div class="col-sm-10">
			    	<textarea class="form-control" name="remarks" id="remarks"></textarea>
			    </div>
		  	</div>
		  	<div class="form-group">
		  		<label for="driving_license" class="col-sm-2 control-label">Has Driving License</label>
		  		<div class="col-sm-10">
			  		<label class="checkbox-inline">
					  <input type="checkbox" name="driving_license" id="driving_license" value="Yes"> Yes
					</label>
				</div>
		  	</div>
		  	<div class="form-group">
			    <label for="profile_photo" class="col-sm-2 control-label">Photo</label>
			    <div class="col-sm-10">
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
		</div>
    </form>
</div>
<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>