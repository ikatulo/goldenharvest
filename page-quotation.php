<?php
/* Template Name: Quotation*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	$customerType = get_terms('customertype', array('hide_empty'=>false));
	$paymentStatus = get_terms('paymentstatus', array('hide_empty'=>false));
	$quotationStatus = get_terms('quotationstatus', array('hide_empty'=>false));
?>

<h1 class="page-title"><span class="glyphicon glyphicon-list-alt"></span> <?php echo $post->post_title;?> <a href="#" class="uibutton large special" title="Delete Multiple Quotation" id="btn-delete-all" rel="quotation"> Delete Multiple Quotation</a> <a href="<?php echo bloginfo('url');?>/quotations/add/" class="uibutton large special" title="Add Quotation">Add Quotation</a></h1>

<div class="main-content">
	<div class="filter" id="quotation-filter">
		<form rol="form" class="form-inline" id="filterForm">
			<div class="form-group col-md-2 col-md-offset-4">
			    <div class="input-group">
					<input type="text" class="form-control" name="keyword" id="keyword" placeholder="Keyword">
			      	<span class="input-group-btn" id="reset-filter">
			        	<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-remove"></span></button>
			      	</span>		      
			    </div><!-- /input-group -->
			</div>
			<div class="form-group col-md-2">
			    <label class="sr-only" for="customertype">&nbsp;</label>
			    <select id="customertype" name="customertype" class="standard-dropdown" data-placeholder="Select Customer Type">
			      	<option value=""></option>
	            	<?php foreach($customerType as $type):?>
			      	<option value="<?php echo $type->slug;?>"><?php echo $type->name;?></option> 
			      	<?php endforeach;?>
            	</select>
			</div>
			<div class="form-group col-md-2">
			    <label class="sr-only" for="paymentstatus">&nbsp;</label>
			    <select id="paymentstatus" name="paymentstatus" class="standard-dropdown" data-placeholder="Select Payment Status">
			      	<option value=""></option>
	            	<?php foreach($paymentStatus as $ps):?>
			      	<option value="<?php echo $ps->slug;?>"><?php echo $ps->name;?></option> 
			      	<?php endforeach;?>
            	</select>
			</div>
			<div class="form-group col-md-2 last">
			    <label class="sr-only" for="quotationstatus">&nbsp;</label>
			    <select id="quotationstatus" name="quotationstatus" class="standard-dropdown" data-placeholder="Select Quotation Status">
			      	<option value=""></option>
	            	<?php foreach($quotationStatus as $qs):?>
			      	<option value="<?php echo $qs->slug;?>"><?php echo $qs->name;?></option> 
			      	<?php endforeach;?>
            	</select>
			</div>			
			<input type="hidden" name="itemType" value="quotation">
			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		</form>
	</div>
	<table class="table table-bordered" id="quotation-list">
		<thead>
			<tr>
				<th class="center"><input type="checkbox" id="checkall"/></th>
				<th>Quotation ID</th>
				<th>Customer Type</th>
				<th>Customer Name</th>
				<th>Amount ($)</th>
				<th>Payment Status</th>
				<th>Quotation Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot>
			<tr>
				<td colspan="9"></td>
			</tr>
		</tfoot>
	</table>
</div>

<div class="clearfix"></div>

<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>