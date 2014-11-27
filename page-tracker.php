<?php
/* Template Name: Tracker*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php

	$current_date = current_time('mysql');

	$args = array(
		"post_type" => "event",
		"post_status" => "publish",
		"posts_per_page" => -1,
		"order" => "ASC",
		"orderby" => "meta_value",
		"meta_key" => "start_time",
		"meta_query" => array(
			"relation" => "AND",
			array(
				"key" => "event_date",
				"value" => date('Ymd', strtotime($current_date))
			)
		)
	);

	$tracker = new WP_Query($args);
?>

<h1 class="page-title"><span class="glyphicon glyphicon-road"></span> <?php echo $post->post_title.' - '.date('d-m-Y', strtotime($current_date));?></h1>

<div class="main-content">
	<table class="table table-bordered" id="tracker-list">
		<thead>
			<tr>
				<th>Vehicle</th>
				<th>Team</th>				
				<th>Team Member</th>
				<th class="center">Start Time</th>
				<th class="center">Est. End Time</th>
				<th class="center">Actual End Time</th>
				<th class="center">Duration</th>
				<th>Customer</th>
				<th>Address</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>		
		<?php while($tracker->have_posts()): $tracker->the_post();?>
			<?php
				$customerId = get_field('customer');
				$customer = get_post($customerId);
				$teamId = get_field('team_id');
				$team = get_post($teamId);
				$members = get_field('team_member');
				$memberText = '';
				$counter = 1;

				foreach($members as $member){
					if($counter == count($members)){
						$memberText .= $member['display_name'];
					}else{
						$memberText .= $member['display_name'].', ';
					}
				}

				$startTime = get_field('start_time');
				$endTime =  get_field('end_time');
				$actual_end_time =  get_field('actual_end_time');

				$duration = '-';

				if($actual_end_time){
					$xStartTime = hour_minute_to_second($startTime);
					$xActualTime = hour_minute_to_second($actual_end_time);
					$duration = $xActualTime - $xStartTime;
				}

				$customerAddress = get_field('address', $customerId);

				$status = wp_get_post_terms(get_the_ID(), 'calendarstatus');
				$vehicleId = get_field('vehicle');
				$vehicle = get_post($vehicleId);

			?>
			<tr class="row-<?php echo get_the_ID();?>">
				<td><?php echo $vehicle->post_title;?></td>
				<td><?php echo $team->post_title;?></td>
				<td><?php echo $memberText;?></td>
				<td class="center"><?php echo $startTime;?></td>
				<td class="center"><?php echo $endTime;?></td>
				<td class="actual-time success center"><?php echo $actual_end_time;?></td>
				<td class="duration warning center"><?php echo duration($duration);?></td>
				<td><?php echo $customer->post_title;?></td>
				<td><?php echo $customerAddress;?></td>
				<td class="action">
				<?php if($status):?>
					<label class="label label-info"><span class="glyphicon glyphicon-ok"></span> Done</label>
				<?php else:?>
				<a href="#" class="label label-warning" title="Set as Done" id="setDone" rel="<?php echo get_the_ID();?>"><span class="glyphicon glyphicon-ok"></span> Set as Done</a>
				<?php endif;?>
				</td>
			</tr>
		<?php endwhile;?>
		</tbody>
	</table>
</div>

<div class="clearfix"></div>

<div id="trackerStatusPopup" class="white-popup wide mfp-hide">
	<form id="formTrackerPopup" role="form" class="form-horizontal inner-form">
		<div id="popup-header">Set Event Done</div>
		<div id="popup-body">
   			<div class="form-group">
			    <label for="startTime" class="col-sm-2 control-label">Actual End Time <span class="required">*</span></label>
			    <div class="col-sm-10">
			    	<input type="text" name="actualTime" id="actualTime" class="form-control timepicker">
			    </div>
			</div>
			<div class="form-group">
			    <label for="note" class="col-sm-2 control-label">Note</label>
			    <div class="col-sm-10">
			      <textarea class="form-control" name="note" id="note"></textarea>
			    </div>
		  	</div>
		</div>
		<div id="popup-footer" class="clearfix">
			<input type="hidden" name="eventId" value="" id="eventId">
			<a class="btn btn-primary pull-right" style="margin-left:10px;" id="setEventDone">Save</a>
			<a class="popup-modal-dismiss btn btn-default pull-right" href="#">Cancel</a>
		</div>
	</form>
</div>

<?php get_footer();?>
<?php else:?>
    <?php header('Location:'.home_url());?>
<?php endif;?>