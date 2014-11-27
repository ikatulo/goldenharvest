<?php
/* Template Name: Events Add*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	$current_date = current_time('mysql');
	$current_year = date('Y');
	$current_month = date('n');
	$firstDay = date('Y-m-01');
	$lastDay = date('Y-m-t', strtotime($current_date));
?>

<h1 class="page-title"><span class="glyphicon glyphicon-calendar"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">
	<div class="row">
		<div class="col-md-10">
			<div id="calendar-future"></div>
		</div>
		<div class="col-md-2">
		<?php
			$args = array(
				"post_type" => "team",
				"post_status" => "publish",
				"posts_per_page" => -1
			);

			$team = new WP_Query($args);
			$arrMembers = array();			
		?>
		<div class="panel panel-default">			
		  <div class="panel-heading">Select Team</div>
		  <div class="panel-body">
		    <p>To add an event, please drag and drop the team name below to the proper date on calendar. To add new team <a href="../../teams/add">click here</a></p>
		    <?php if($team->have_posts()):?>
		    <ul class="list-group" id="dragable">
		      <?php while($team->have_posts()) : $team->the_post();?>
		      <?php 
		      		$team_members = get_field('assigned_to');
		      		$teamMembers = array();$tmpMember = array();
		      		foreach($team_members as $member){
		      			$teamMembers[] = $member['ID'];
		      			$tmpMember[] = array(
		      				"id" => $member['ID'],
		      				"name" => $member['display_name']
		      			);
		      		}

		      		$arrMembers[get_the_ID()] = $tmpMember;
		      ?>
			  <li class="list-group-item fc-event" rel="<?php echo get_the_ID();?>" data-member='<?php echo json_encode($teamMembers);?>'><?php echo the_title();?><span class="glyphicon glyphicon-th pull-right"></span></li>
			  <?php endwhile;?>
			  <?php wp_reset_postdata();?>
			</ul>
			<?php endif;?>
		  </div>
		</div>
		</div>
	</div>
</div>

<?php
	$args = array(
		"post_type" => "customer",
		"post_status" => "publish",
		"posts_per_page" => -1
	);

	$customer = new WP_Query($args);

	$arrCustomers = array();

	while($customer->have_posts()) : $customer->the_post();
		array_push($arrCustomers, array(
			"cust_id" => get_the_ID(),
			"name" => get_the_title()
		));
	endwhile;
	wp_reset_postdata();
?>

<script type="text/javascript">
	var objTeamMembers = <?php echo json_encode($arrMembers);?>;
	var objCustomers = <?php echo json_encode($arrCustomers);?>;
</script>

<div id="event-popup" class="white-popup mfp-hide">
	<form role="form" id="formEvent">
	<div id="popup-header"><span class="glyphicon glyphicon-calendar"></span> Event Detail</div>
	<div id="popup-body">		
		  <div class="form-group">
		    <label for="members">Team Member</label>
		    <select id="members" name="members" class="multiple-dropdown" multiple disabled>
		    	<option></option>
		    </select>
		  </div>
		  <div class="form-group">
		    <label for="startTime">Start Time <span class="required">*</span></label>
		    <input type="text" name="startTime" id="startTime" class="form-control timepicker">
		  </div>
		  <div class="form-group">
		    <label for="endTime">Estimation End Time</label>
		    <input type="text" name="endTime" id="endTime" class="form-control timepicker">
		  </div>
		  <div class="form-group">
		    <label for="customer">Customer/Client <span class="required">*</span></label>
		    <select id="customer" name="customer" class="standard-dropdown" data-placeholder="Select Customer">
		    	<option></option>
		    	<?php
	    		while($customer->have_posts()) : $customer->the_post();
	    		echo '<option value="'.get_the_ID().'">'.get_the_title().'</option>';
				endwhile;
		    	?>
		    </select>
		  </div>
		  <div class="form-group">
		    <label for="exampleInputEmail1">Vehicle <span class="required">*</span></label>
		    <?php
		    	$args = array(
		    		"post_type" => "vehicle",
		    		"post_status" => "publish",
		    		"posts_per_page" => -1
		    	);

		    	$vehicle = new WP_Query($args);
		    ?>
		    <?php if($vehicle->have_posts()):?>
		    <select id="vehicle" name="vehicle" class="standard-dropdown" data-placeholder="Select Vehicle">
		    	<option></option>
		    	<?php while($vehicle->have_posts()) : $vehicle->the_post();?>
		    	<option value="<?php echo get_the_ID();?>"><?php echo the_title();?></option>
		    	<?php endwhile;?>
		    </select>
			<?php endif;?>
		  </div>
	</div>
	<div id="popup-footer" class="clearfix">
		<a class="btn btn-danger pull-left" id="deleteEvent" href="#" style="display:none;">Delete</a>
		<a class="btn btn-primary pull-right" style="margin-left:10px;" id="saveEvent">Save</a>
		<a class="popup-modal-dismiss btn btn-default pull-right" href="#">Cancel</a>
	</div>
	</form>
</div>

<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>