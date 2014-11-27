<?php
/* Template Name: Customers*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	$customer_type = get_terms('customertype', array('hide_empty'=>false));
?>

<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?> <a href="#" class="uibutton large special" title="Delete Multiple Customer" id="btn-delete-all" rel="customer"> Delete Multiple Customer</a> <a href="<?php echo bloginfo('url');?>/customers/add/" class="uibutton large special" title="Add Customer">Add Customer</a></h1>

<div class="main-content">
	<div class="filter" id="customer-filter">
		<form rol="form" class="form-inline" id="filterForm">
			<div class="form-group col-md-2 col-md-offset-8">
			    <div class="input-group">
					<input type="text" class="form-control" name="keyword" id="keyword" placeholder="Keyword">
			      	<span class="input-group-btn" id="reset-filter">
			        	<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-remove"></span></button>
			      	</span>		      
			    </div><!-- /input-group -->
			</div>
			<div class="form-group col-md-2 last">
			    <label class="sr-only" for="customertype">&nbsp;</label>
			    <select id="customertype" name="customertype" class="standard-dropdown" data-placeholder="Select Type">
			      	<option value=""></option>
	            	<?php foreach($customer_type as $type):?>
			      	<option value="<?php echo $type->slug;?>"><?php echo $type->name;?></option> 
			      	<?php endforeach;?>
            	</select>
			</div>			
			<input type="hidden" name="itemType" value="customer">
			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		</form>
	</div>
	<table class="table table-bordered" id="customer-list">
		<thead>
			<tr>
				<th class="center"><input type="checkbox" id="checkall"/></th>
				<th>Company</th>
				<th>Contact Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Fax</th>
				<th>Type</th>
				<th>Contract Exp</th>
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