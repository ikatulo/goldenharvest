<?php
/* Template Name: Team Add*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>
<?php

	global $wpdb;
	global $wp_roles;

	$roles = $wp_roles->get_names();

	$args = array(
		"post_type" => "customer",
		"post_status" => "publish",
		"posts_per_page" => -1
	);

	$customers = new WP_Query($args);

	$args = array(
        'orderby' => 'meta_value',
        'meta_key' => 'first_name',
		'meta_query' => array(
	        'relation' => 'AND',	        
	        array(
	        	'key' => $wpdb->prefix . 'capabilities',
	            'value' => 'administrator',
	            'compare' => 'NOT LIKE'
	        ),
	        array(
	        	'key' => $wpdb->prefix . 'capabilities',
	            'value' => 'aamrole_53fd9441d5125',
	            'compare' => 'NOT LIKE'
	        ),
	        array(
	        	'key' => $wpdb->prefix . 'capabilities',
	            'value' => 'aamrole_53fd944a9a251',
	            'compare' => 'NOT LIKE'
	        )
		)
    );

    $users = new  WP_User_Query ( $args );
?>
<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">    
    <form id="addTeam" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Team Information</h3>
	    	<div class="form-group" id="teamName">
			    <label for="teamName" class="col-sm-2 control-label">Team Name <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="teamName" name="teamName" placeholder="Team Name" value="">
			    </div>
		  	</div>
	    	<div class="form-group">
			    <label for="customer" class="col-sm-2 control-label">Customer <span class="required">*</span></label>
			    <div class="col-sm-10">			      
			      <select id="customer" name="customer" class="standard-dropdown" data-placeholder="Select Customer">
			      	<option value=""></option>
			      	<?php while($customers->have_posts()): $customers->the_post();?>
			      		<option value="<?php echo get_the_ID();?>"><?php echo the_title();?></option>
			      	<?php endwhile;?>
			      </select>
			      <span class="description">Use existing customer or <a href="#" id="addNewCustomer">click here to add new customer</a>.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="members" class="col-sm-2 control-label">Team Member <span class="required">*</span></label>
			    <div class="col-sm-10">			      
			      <select id="members" name="members[]" class="multiple-dropdown" data-placeholder="Select Team Members" multiple>
			      	<option value=""></option>
			      	<?php foreach($users->results as $user):?>
			      		<option value="<?php echo $user->ID;?>"><?php echo $user->display_name.' ('.$roles[$user->roles[0]].')';?></option>
			      	<?php endforeach;?>
			      </select>
			      <span class="description">Select one or more usre as a team member.</span>
			    </div>
		  	</div>	  	
		  	
		</div>
		
	  	<div class="form-group">
		    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>
		    <div class="col-sm-10">
		    	<input type="hidden" name="action" value="addEditTeam"/>
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

<div id="newCustomerPopup" class="white-popup wide mfp-hide">
	<form id="formCustomerPopup" role="form" class="form-horizontal inner-form">
		<div id="popup-header">Add New Customer</div>
		<div id="popup-body">
		
	    	<div class="form-section">
		    	<div class="form-group">
				    <label for="customertype" class="col-sm-2 control-label">Customer Type <span class="required">*</span></label>
				    <div class="col-sm-10">
				      <?php $customerType = get_terms('customertype', array('hide_empty' => false));?>
				      <select id="customertype" name="customertype" class="standard-dropdown" data-placeholder="Select Customer Type">
				      	<option value=""></option>
				      	<?php foreach($customerType as $type):?>
				      		<option value="<?php echo $type->slug;?>"><?php echo $type->name;?></option>
				      	<?php endforeach;?>
				      </select>
				    </div>
			  	</div>
			  	<div class="form-group" id="contract-exp" style="display:none;">
				    <label for="contractexp" class="col-sm-2 control-label">Contract Expiration Date <span class="required">*</span></label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control datepicker-future" id="contractexp" name="contractexp" placeholder="" value="">
				    </div>
			  	</div>
		    	<div class="form-group">
				    <label for="company" class="col-sm-2 control-label">Company Name <span class="required">*</span></label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="company" name="company" placeholder="Company Name" value="">
				      <span class="description">Letters and numbers only.</span>
				    </div>
			  	</div>
			  	<div class="form-group">
				    <label for="fullname" class="col-sm-2 control-label">Contact Name <span class="required">*</span></label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" value="">
				      <span class="description">Letters and numbers only</span>
				    </div>
			  	</div>
			  	<div class="form-group">
				    <label for="email" class="col-sm-2 control-label">Email <span class="required">*</span></label>
				    <div class="col-sm-10">
				      <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="">
				      <span class="description">Example: mail@example.com.</span>
				    </div>
			  	</div>
			  	<div class="form-group">
				    <label for="address" class="col-sm-2 control-label">Address <span class="required">*</span></label>
				    <div class="col-sm-10">
				      <textarea class="form-control" name="address" id="address"></textarea>
				      <span class="description">Complete address.</span>
				    </div>
			  	</div>
			  	<div class="form-group">
				    <label for="phone" class="col-sm-2 control-label">Phone <span class="required">*</span></label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="">
				      <span class="description">Phone number. For multiple entry seperated by coma.</span>
				    </div>
			  	</div>
			  	<div class="form-group">
				    <label for="fax" class="col-sm-2 control-label">Fax</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="fax" name="fax" placeholder="Fax Number" value="">
				      <span class="description">Fax number. For multiple entry seperated by coma.</span>
				    </div>
			  	</div>
			  	<div class="form-group">
				    <label for="note" class="col-sm-2 control-label">Other Note</label>
				    <div class="col-sm-10">
				      <textarea class="form-control" name="note" id="note"></textarea>
				    </div>
			  	</div>		  	
			</div>	    
		</div>
		<div id="popup-footer" class="clearfix">
			<a class="btn btn-primary pull-right" style="margin-left:10px;" id="saveNewCustomer">Save</a>
			<a class="popup-modal-dismiss btn btn-default pull-right" href="#">Cancel</a>
		</div>
	</form>
</div>

<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>