<?php
/* Template Name: Customer Edit*/
$uri = explode("/", $_SERVER['REQUEST_URI']);

if(count($uri) == 5){
	header('Location:'.home_url().'/customers/');
}

?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	$customer = get_post($uri[4]);
	$current_customer_type = wp_get_post_terms($customer->ID, 'customertype');
	$fullname = get_field('full_name', $customer->ID);
	$email = get_field('email', $customer->ID);
	$address = get_field('address', $customer->ID);
	$phone = get_field('phone', $customer->ID);
	$fax = get_field('fax', $customer->ID);
	$contract = get_field('contract_expiration_date', $customer->ID);
?>

<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">    
    <form id="addCustomer" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Customer Information</h3>
	    	<div class="form-group">
			    <label for="role" class="col-sm-2 control-label">Customer Type <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <?php $customerType = get_terms('customertype', array('hide_empty' => false));?>
			      <select id="customertype" name="customertype" class="standard-dropdown" data-placeholder="Select Customer Type">
			      	<option value=""></option>
			      	<?php foreach($customerType as $type):?>
			      		<?php $selected = ($current_customer_type[0]->slug == $type->slug) ? 'selected="selected"' : '';?>
			      		<option value="<?php echo $type->slug;?>" <?php echo $selected;?>><?php echo $type->name;?></option>
			      	<?php endforeach;?>
			      </select>
			    </div>
		  	</div>
		  	<?php $show = $current_customer_type[0]->slug == "monthly" ? "block;" : "none;";?>
		  	<div class="form-group" id="contract-exp" style="display:<?php echo $show;?>">
			    <label for="contractexp" class="col-sm-2 control-label">Contract Expiration Date <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control datepicker-future" id="contractexp" name="contractexp" placeholder="" value="<?php echo date('d-m-Y', strtotime($contract));?>">
			    </div>
		  	</div>
	    	<div class="form-group">
			    <label for="company" class="col-sm-2 control-label">Company Name <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="company" name="company" placeholder="Company Name" value="<?php echo $customer->post_title;?>">
			      <span class="description">Letters and numbers only.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="fullname" class="col-sm-2 control-label">Contact Name <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" value="<?php echo $fullname;?>">
			      <span class="description">Letters and numbers only</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="email" class="col-sm-2 control-label">Email <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" value="<?php echo $email;?>">
			      <span class="description">Example: mail@example.com.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="address" class="col-sm-2 control-label">Address <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <textarea class="form-control" name="address" id="address"><?php echo $address;?></textarea>
			      <span class="description">Complete address.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="phone" class="col-sm-2 control-label">Phone <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="<?php echo $phone;?>">
			      <span class="description">Phone number. For multiple entry seperated by coma.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="fax" class="col-sm-2 control-label">Fax</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="fax" name="fax" placeholder="Fax Number" value="<?php echo $fax;?>">
			      <span class="description">Fax number. For multiple entry seperated by coma.</span>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="note" class="col-sm-2 control-label">Other Note</label>
			    <div class="col-sm-10">
			      <textarea class="form-control" name="note" id="note"><?php echo $customer->post_content;?></textarea>
			    </div>
		  	</div>		  	
		</div>
		
	  	<div class="form-group">
		    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>
		    <div class="col-sm-10">
		    	<input type="hidden" name="action" value="addEditCustomer"/>
		    	<input type="hidden" name="isEdit" value="1"/>
		    	<input type="hidden" name="postId" value="<?php echo $customer->ID;?>"/>
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