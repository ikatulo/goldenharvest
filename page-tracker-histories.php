<?php
/* Template Name: Tracker Histories*/
?>

<?php if(is_user_logged_in()):?>

<?php get_header();?>

<?php
	$current_date = current_time('mysql');	
?>

<h1 class="page-title"><span class="glyphicon glyphicon-road"></span> <?php echo $post->post_title;?></h1>

<div class="main-content">
	<div class="filter" id="tracker-filter">
		<form role="form" class="form-inline" id="filterForm">
			<div class="form-group col-md-2 col-md-offset-10 last">
			    <div class="input-group">
					<input type="text" class="form-control standard-datepicker" name="tracked_date" id="tracked_date" value="<?php echo date('d-m-Y', strtotime($current_date));?>">
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
			      			      
			    </div><!-- /input-group -->
			</div>
			<?php wp_nonce_field( 'post_nonce', 'post_nonce_field' ); ?>
		</form>
	</div>

	<table class="table table-bordered" id="tracker-list">
		<thead>
			<tr>
				<th>Vehicle</th>
				<th>Team</th>				
				<th>Team Member</th>
				<th class="center">Start Time</th>
				<th class="center">Actual Time</th>
				<th class="center">Duration</th>
				<th>Customer</th>
				<th>Address</th>
			</tr>
		</thead>
		<tbody></tbody>
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