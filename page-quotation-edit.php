<?php
/* Template Name: Quotation Edit*/
$uri = explode("/", $_SERVER['REQUEST_URI']);

if(count($uri) == 5){
	header('Location:'.home_url().'/quotations/');
}
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>
<?php 
	$quotation = get_post($uri[4]);
	$args = array(
		"post_type" => "customer",
		"post_status" => "publish",
		"posts_per_page" => -1
	);

	$customers = new WP_Query($args);

	$customerId = get_field('customer_id', $quotation->ID);
	$price = get_field('total_price', $quotation->ID);
	$gst = get_field('gst', $quotation->ID);
	$pricegst = get_field('total_price_gst', $quotation->ID);
	$currCustomerType = wp_get_post_terms($quotation->ID, 'customertype');
	$currPaymentStatus = wp_get_post_terms($quotation->ID, 'paymentstatus');
	$currQuotationStatus = wp_get_post_terms($quotation->ID, 'quotationstatus');
	$paymentStatus = get_terms('paymentstatus', array('hide_empty'=>false));
	$quotationStatus = get_terms('quotationstatus', array('hide_empty'=>false));
	$currCustomer = get_post($customerId);
?>

<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">    
    <form id="addQuotation" role="form" class="form-horizontal inner-form">
    	<div class="form-section">
	    	<h3><span class="glyphicon glyphicon-th-list"></span> Quotation Information</h3>
	    	<div class="form-group" id="quotationnumber">
			    <label for="quotationnumber" class="col-sm-2 control-label">Number <span class="required">*</span></label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="quotationnumber" name="quotationnumber" placeholder="" value="<?php echo $quotation->post_title;?>" readonly="">
			      <span class="description">Auto generated by system.</span>
			    </div>
		  	</div>

	    	<div class="form-group">
			    <label for="customer" class="col-sm-2 control-label">Customer <span class="required">*</span></label>
			    <div class="col-sm-10">			      
			      <select id="customer" name="customer" class="standard-dropdown" data-placeholder="Select Customer">
			      	<option value=""></option>
			      	<?php while($customers->have_posts()): $customers->the_post();?>
			      		<?php $selected = $customerId == get_the_ID() ? 'selected="selected"' : '';?>
			      		<option value="<?php echo get_the_ID();?>" <?php echo $selected;?>><?php echo the_title();?></option>
			      	<?php endwhile;?>
			      </select>
			      <span class="description">Use existing customer or <a href="#" id="addNewCustomer">click here to add new customer</a>.</span>
			    </div>
		  	</div>
		  	<div id="customer-detail" style="">
		  		<input type="hidden" name="customertype" id="customertype" value="<?php echo $currCustomerType[0]->slug;?>">
		  		<div class="form-group" id="contract-exp">
			    	<label class="col-sm-2 control-label">&nbsp;</label>
				    <div class="col-sm-10">
				    	<div class="row">
					    	<div class="col-md-2">
					    		<dl>
					    			<dt>Type:</dt>
					    			<dd class="type"><?php echo $currCustomerType[0]->name;?></dd>
					    			<dt>Name:</dt>
					    			<dd class="name"><?php echo $currCustomer->post_title;?></dd>
					    		</dl>
					    	</div>
					    	<div class="col-md-2">
					    		<dl>
					    			<dt>Contact Person:</dt>
					    			<dd class="contactname"><?php echo get_field('full_name', $currCustomer->ID);?></dd>
					    			<dt>Phone:</dt>
					    			<dd class="contactnumber"><?php echo get_field('phone', $currCustomer->ID);?></dd>				    			
					    		</dl>
					    	</div>
					    	<div class="col-md-3">
					    		<dl>
					    			<dt>Address:</dt>
					    			<dd class="contactaddress"><?php echo get_field('address', $currCustomer->ID);?></dd>			    			
					    		</dl>
					    	</div>
					    </div>
				    </div>
			  	</div>
		  	</div>		  	
		  	<div class="form-group">
		  		<label for="totalprice" class="col-sm-2 control-label">Total Price <span class="required">*</span></label>
		  		<div class="col-sm-10">
			  		<div class="input-group">
					  <span class="input-group-addon">$</span>
					  <input type="text" class="form-control" name="totalprice" id="totalprice" placeholder="0" value="<?php echo $price;?>">
					</div>
				</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="gst" class="col-sm-2 control-label">GST(7%)</label>
		  		<div class="col-sm-10">
			  		<div class="input-group">
					  <span class="input-group-addon">$</span>
					  <input type="text" class="form-control" name="gst" id="gst" placeholder="0" readonly="" value="<?php echo $gst;?>">
					</div>
				</div>
		  	</div>
		  	<div class="form-group">
		  		<label for="totalpricegst" class="col-sm-2 control-label">Total Price + GST(7%)</label>
		  		<div class="col-sm-10">
			  		<div class="input-group">
					  <span class="input-group-addon">$</span>
					  <input type="text" class="form-control" name="totalpricegst" id="totalpricegst" placeholder="0" readonly="" value="<?php echo $pricegst;?>">
					</div>
				</div>
		  	</div>
		  	<div class="form-group">
			    <label for="recommendations" class="col-sm-2 control-label">Recommendations</label>
			    <div class="col-sm-10">
			      <textarea class="form-control" name="recommendations" id="recommendations"><?php echo $quotation->post_content;?></textarea>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="paymentstatus" class="col-sm-2 control-label">Payment Status <span class="required">*</span></label>
                            <?php $paymentstatus = get_terms('paymentstatus', array('hide_empty' => false));?>
			    <div class="col-sm-10">			      
			      <select id="paymentstatus" name="paymentstatus" class="standard-dropdown" data-placeholder="Select Payment Status">
			      	<option value=""></option>
			      	<?php foreach($paymentStatus as $status):?>
			      		<?php $selected = $currPaymentStatus[0]->slug == $status->slug ? 'selected="selected"' : '';?>
			      		<option value="<?php echo $status->slug;?>" <?php echo $selected;?>><?php echo $status->name;?></option>
			      	<?php endforeach;?>
			      </select>
			    </div>
		  	</div>
		  	<div class="form-group">
			    <label for="quotationstatus" class="col-sm-2 control-label">Quotation Status <span class="required">*</span></label>
                            <?php $quotationstatus = get_terms('quotationstatus', array('hide_empty' => false));?>
			    <div class="col-sm-10">			      
			      <select id="quotationstatus" name="quotationstatus" class="standard-dropdown" data-placeholder="Select Quotation Status">
			      	<option value=""></option>
			      	<?php foreach($quotationStatus as $status):?>
			      		<?php $selected = $currQuotationStatus[0]->slug == $status->slug ? 'selected="selected"' : '';?>
			      		<option value="<?php echo $status->slug;?>" <?php echo $selected;?>><?php echo $status->name;?></option>
			      	<?php endforeach;?>
			      </select>
			    </div>
		  	</div>	  	
		</div>
		
	  	<div class="form-group">
		    <label for="remarks" class="col-sm-2 control-label">&nbsp;</label>
		    <div class="col-sm-10">
		    	<input type="hidden" name="action" value="addEditQuotation"/>
		    	<input type="hidden" name="quotationId" value="<?php echo $quotation->ID;?>"/>
		    	<input type="hidden" name="isEdit" value="1"/>
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
		    	<button type="submit" class="btn btn-primary" value="_invoice">
		    		<span class="glyphicon glyphicon-ok-sign"></span> Invoice
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