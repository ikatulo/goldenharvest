<?php
/* Template Name: Staff*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	global $wp_roles;
	$roles = $wp_roles->get_names();
?>

<h1 class="page-title"><span class="glyphicon glyphicon-user"></span> <?php echo $post->post_title;?> <a href="#" class="uibutton large special" title="Delete Multiple Staff" id="btn-delete-all" rel="user"> Delete Multiple Staff</a> <a href="<?php echo bloginfo('url');?>/staff/add/" class="uibutton large special" title="Add Staff">Add Staff</a></h1>

<div class="main-content">
	<div class="filter" id="user-filter">
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
			    <label class="sr-only" for="position">&nbsp;</label>
			    <?php
		    		$arrPosition = array(
		    			"manager" => "Manager",
		    			"supervisor" => "Supervisor",
		    			"teamleader" => "Team Leader",
		    			"worker" => "Worker"
		    		);
		    	?>
			    <select id="position" name="position" class="standard-dropdown" data-placeholder="Select Position">
			      	<option value=""></option>
	            	<?php foreach($arrPosition as $key => $value):?>
			      	<option value="<?php echo $key;?>"><?php echo $value;?></option> 
			      	<?php endforeach;?>
            	</select>
			</div>
			<div class="form-group col-md-2">
				<label class="sr-only" for="role">&nbsp;</label>
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
			<div class="form-group col-md-2 last">
			    <label class="sr-only" for="status">&nbsp;</label>
			    <?php
		    		$arrStatus = array(
		    			"temporary" => "Temporary",
		    			"permanent" => "Permanent"
		    		);
		    	?>
			    <select id="status" name="status" class="standard-dropdown" data-placeholder="Select Employment Status">
			      	<option value=""></option>
	            	<?php foreach($arrStatus as $key => $value):?>
			      	<option value="<?php echo $key;?>"><?php echo $value;?></option> 
			      	<?php endforeach;?>
            	</select>
			</div>
			<input type="hidden" name="itemType" value="user">
			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		</form>
	</div>
	<table class="table table-bordered" id="user-list">
		<thead>
			<tr>
				<th><input type="checkbox" id="checkall"/></th>
				<th>Name</th>
				<th>Sex</th>
				<th>Age</th>
				<th>Position</th>
				<th>Role</th>
				<th>Employment Status</th>
				<!--<th>Has License</th>-->
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