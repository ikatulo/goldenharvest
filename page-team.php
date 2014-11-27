<?php
/* Template Name: Team*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php 
	$args = array(
		"post_type" => "customer",
		"post_status" => "publish",
		"posts_per_page" => -1
	);

	$customers = new WP_Query($args);
?>

<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?> <a href="#" class="uibutton large special" title="Delete Multiple Team" id="btn-delete-all" rel="team"> Delete Multiple Team</a> <a href="<?php echo bloginfo('url');?>/teams/add/" class="uibutton large special" title="Add Team">Add Team</a></h1>

<div class="main-content">
	<div class="filter" id="team-filter">
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
			      <select id="customer" name="customer" class="standard-dropdown" data-placeholder="Select Customer">
			      	<option value=""></option>
			      	<?php while($customers->have_posts()): $customers->the_post();?>
			      		<option value="<?php echo get_the_ID();?>"><?php echo the_title();?></option>
			      	<?php endwhile;?>
			      </select>
		  	</div>		
			<input type="hidden" name="itemType" value="team">
			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		</form>
	</div>
	<table class="table table-bordered" id="team-list">
		<thead>
			<tr>
				<th class="center"><input type="checkbox" id="checkall"/></th>
				<th>Team Name</th>
				<th>Customer</th>
				<th>Member</th>
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