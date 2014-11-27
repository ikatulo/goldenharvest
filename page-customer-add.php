<?php

/* Template Name: Customer Add*/

?>



<?php if(is_user_logged_in()):?>



<?php get_header();?>

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

		

	  	<div class="form-group">

		    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>

		    <div class="col-sm-10">

		    	<input type="hidden" name="action" value="addEditCustomer"/>

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