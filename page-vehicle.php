<?php
/* Template Name: Vehicle*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<h1 class="page-title"><span class="glyphicon glyphicon-cog"></span> <?php echo $post->post_title;?> <a href="#" class="uibutton large special" title="Delete Multiple Vehicle" id="btn-delete-all" rel="vehicle"> Delete Multiple Vehicle</a> <a href="<?php echo bloginfo('url');?>/vehicles/add/" class="uibutton large special" title="Add Vehicle">Add Vehicle</a></h1>

<div class="main-content">
	<div class="filter" id="vehicle-filter">
		<form rol="form" class="form-inline" id="filterForm">
			<div class="form-group col-md-2 col-md-offset-10 last">
			    <div class="input-group">
					<input type="text" class="form-control" name="keyword" id="keyword" placeholder="Keyword">
			      	<span class="input-group-btn" id="reset-filter">
			        	<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-remove"></span></button>
			      	</span>		      
			    </div><!-- /input-group -->
			</div>		
			<input type="hidden" name="itemType" value="vehicle">
			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		</form>
	</div>
	<table class="table table-bordered" id="vehicle-list">
		<thead>
			<tr>
				<th class="center"><input type="checkbox" id="checkall"/></th>
				<th>Number</th>
				<th>Photo</th>
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