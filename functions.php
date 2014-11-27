<?php

require( get_template_directory() . '/framework/functions.php' );

function the_slug() {
    $post_data = get_post($post->ID, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug; 
}

if(is_admin()){
    add_action('wp_ajax_updateProfile', 'updateProfile');
    add_action('wp_ajax_nopriv_updateProfile', 'updateProfile');

    add_action('wp_ajax_addEditStaff', 'addEditStaff');
    add_action('wp_ajax_nopriv_addEditStaff', 'addEditStaff');

    add_action('wp_ajax_addEditCustomer', 'addEditCustomer');
    add_action('wp_ajax_nopriv_addEditCustomer', 'addEditCustomer');
    add_action('wp_ajax_getCustomerInfo', 'getCustomerInfo');
    add_action('wp_ajax_nopriv_getCustomerInfo', 'getCustomerInfo');
    add_action('wp_ajax_newCustomerPopup', 'newCustomerPopup');
    add_action('wp_ajax_nopriv_newCustomerPopup', 'newCustomerPopup');    

    add_action('wp_ajax_addEditVehicle', 'addEditVehicle');
    add_action('wp_ajax_nopriv_addEditVehicle', 'addEditVehicle');

    add_action('wp_ajax_addEditTeam', 'addEditTeam');
    add_action('wp_ajax_nopriv_addEditTeam', 'addEditTeam');

    add_action('wp_ajax_addEditQuotation', 'addEditQuotation');
    add_action('wp_ajax_nopriv_addEditQuotation', 'addEditQuotation');

    add_action('wp_ajax_getRecommendation', 'getRecommendation');
    add_action('wp_ajax_nopriv_getRecommendation', 'getRecommendation');

    add_action('wp_ajax_saveEvent', 'saveEvent');
    add_action('wp_ajax_nopriv_saveEvent', 'saveEvent');
    add_action('wp_ajax_updateEvent', 'updateEvent');
    add_action('wp_ajax_nopriv_updateEvent', 'updateEvent');
    add_action('wp_ajax_deleteEvent', 'deleteEvent');
    add_action('wp_ajax_nopriv_deleteEvent', 'deleteEvent');
    add_action('wp_ajax_getCalendar', 'getCalendar');
    add_action('wp_ajax_nopriv_getCalendar', 'getCalendar'); 

    add_action('wp_ajax_setEventDone', 'setEventDone');
    add_action('wp_ajax_nopriv_setEventDone', 'setEventDone');

    add_action('wp_ajax_getTrackedEvents', 'getTrackedEvents');
    add_action('wp_ajax_nopriv_getTrackedEvents', 'getTrackedEvents');

    add_action('wp_ajax_deleteItem', 'deleteItem');
    add_action('wp_ajax_nopriv_deleteItem', 'deleteItem');

    add_action('wp_ajax_ajaxPagination', 'ajaxPagination');
    add_action('wp_ajax_nopriv_ajaxPagination', 'ajaxPagination');
}


function getTrackedEvents(){

	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$current_date = current_time('mysql');

		$dateRequested = $_POST['dateRequested'];

		$args = array(
			"post_type" => "tracker",
			"post_status" => "publish",
			"posts_per_page" => -1,
			"order" => "ASC",
			"orderby" => "meta_value",
			"meta_key" => "time_in",
			"meta_query" => array(
				"relation" => "AND",
				array(
					"key" => "tracked_date",
					"value" => date('d-m-Y', strtotime($current_date))
				)
			)
		);

		$tracker = new WP_Query($args);

		$htmlBody = '';

		if($tracker->have_posts()){

			while($tracker->have_posts()) : $tracker->the_post();
				$customerId = get_field('customer');
				$customer = get_post($customerId);
				$teamId = get_field('team');
				$team = get_post($teamId);
				$members = get_field('team_member', $teamId);
				$memberText = '';
				$counter = 1;

				foreach($members as $member){
					if($counter == count($members)){
						$memberText .= $member['display_name'];
					}else{
						$memberText .= $member['display_name'].', ';
					}
				}

				$timeIn = get_field('time_in');
				$time_out =  get_field('time_out');
				
				$duration = duration(hour_minute_to_second($timeOut) - hour_minute_to_second($timeIn));

				$vehicleId = get_field('vehicle');
				$vehicle = get_post($vehicleId);

				$customerAddress = get_field('address', $customerId);

				$htmlBody .= '<tr class="row-'.get_the_ID().'">
								<td>'.$vehicle->post_title.'</td>
								<td>'.$team->post_title.'</td>
								<td>'.$memberText.'</td>
								<td class="success center">'.$timeIn.'</td>
								<td class="info center">'.$timeOut.'</td>
								<td class="warning center">'.$duration.'</td>
								<td>'.$customer->post_title.'</td>
								<td>'.$customerAddress.'</td>
							</tr>';


			endwhile;

			wp_send_json( array(
		    	"success" => true,
		    	"data" => $htmlBody
		    ));

		}else{
			wp_send_json( array(
		    	"success" => false
		    ));
		}

	}else{
		die('Busted!');
	}
}

function setEventDone(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$current_date = current_time('mysql');

		$eventId = $_POST['eventId'];
		$actualTime = $_POST['actualTime'];
		$note = $_POST['note'];

		//set event status
		wp_update_post(array("ID"=>$eventId,"post_type"=>"event","post_content"=>$note));

		wp_set_object_terms($eventId, 'done', 'calendarstatus');		
		update_post_meta($eventId, 'actual_end_time', $actualTime);

		//put event tp tracker histories
		$args = array(
			"post_type" => "tracker",
			"post_status" => "publish",
			"post_title" => md5(strtotime(date('d-m-Y G:i:s')))
		);

		$trackerId = wp_insert_post($args);

		$teamId = get_field('team_id', $eventId);
		$vehicleId = get_field('vehicle', $eventId);
		$startTime = get_field('start_time', $eventId);
		$duration = duration(hour_minute_to_second($actualTime) - hour_minute_to_second($startTime));

		$customerId = get_field('customer', $eventId);

		if($trackerId){

			add_post_meta($trackerId, 'event_id', $eventId);
			update_field('field_5406bf1ffa1de', $customerId, $trackerId);
			update_field('field_54028cbf143b9', $teamId, $trackerId);
			update_field('field_54028f78f4bfe', $vehicleId, $trackerId);
			add_post_meta($trackerId, 'time_in', $startTime);
			add_post_meta($trackerId, 'time_out', $actualTime);
			add_post_meta($trackerId, 'duration', $duration);
			add_post_meta($trackerId, 'tracked_date', date('Ymd', strtotime($current_date)));

			$arrResult = array(
				"actual_time" => $actualTime,
				"duration" => $duration
			);

			wp_send_json( array(
		    	"success" => true,
		    	"message" => "New team has been added",
		    	"data" => $arrResult
		    ));

		}else{
			wp_send_json( array(
		    	"success" => false,
		    	"message" => "Can not set this event status or status already set to done!"
		    ));
		}


	}else{
		die('Busted!');
	}
}

function addEditTeam(){
	if ( isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

		$isEdit = $_POST['isEdit'];
		$teamName = $_POST['teamName'];
		$customer = $_POST['customer'];
		$members = $_POST['members'];

		if($isEdit == 0){
			$postData = array(
				"post_type" => "team",
				"post_status" => "publish",
				"post_title" => $teamName
			);

			$teamId = wp_insert_post($postData);

			if($teamId){
				update_field('field_5402893295f69', $customer, $teamId);
				update_field('field_5402897095f6a', $members, $teamId);

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/teams/';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/teams/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/teams/edit/'.$teamId;
						break;
				}

				wp_send_json( array(
			    	"success" => true,
			    	"message" => "New team has been added",
			    	"returnURL" => $returnURL
			    ));

			}else{
				wp_send_json( array(
			    	"success" => false,
			    	"message" => "Can not add new team, please try again later"
			    ));
			}

		}else{

			$team_id = $_POST['teamId'];

			$postData = array(
				"ID" => $team_id,
				"post_type" => "team",
				"post_status" => "publish",
				"post_title" => $teamName,
				"post_name" => sanitize_title($teamName)
			);

			$teamId = wp_update_post($postData);

			if($teamId){

				update_field('field_5402893295f69', $customer, $teamId);
				update_field('field_5402897095f6a', $members, $teamId);

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/teams/';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/teams/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/teams/edit/'.$teamId;
						break;
				}

				wp_send_json( array(
			    	"success" => true,
			    	"message" => "Team has been updated",
			    	"returnURL" => $returnURL
			    ));

			}else{
				wp_send_json( array(
			    	"success" => false,
			    	"message" => "Can not add new team, please try again later"
			    ));
			}
		}

		

	}else{
		die('Busted!');
	}
}

function newCustomerPopup(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$customerType = $_POST['customerType'];
		$company = $_POST['company'];
		$contractExp = $_POST['contractExp'];
		$fullname = $_POST['fullname'];
		$email = $_POST['email'];
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$notes = $_POST['notes'];

		$postData = array(
			"post_type" => "customer",
			"post_status" => "publish",
			"post_title" => $company,
			"post_content" => $notes
		);

		$customerId = wp_insert_post($postData);

		if($customerId){

			add_post_meta($customerId, 'full_name', $fullname);
			add_post_meta($customerId, 'email', $email);
			add_post_meta($customerId, 'address', $address);
			add_post_meta($customerId, 'phone', $phone);
			add_post_meta($customerId, 'fax', $fax);
			add_post_meta($customerId, 'contract_expiration_date', $contractExp);

			wp_set_object_terms($customerId, $customerType, 'customertype');

			$arrResult = array();

			$arrResult['customerId'] = $customerId;
			$arrResult['customerTypeName'] = $customerType;
			$arrResult['customerTypeSlug'] = sanitize_title($customerType);
			$arrResult['customerName'] = $company;
			$arrResult['customerContactPerson'] = $fullname;
			$arrResult['customerPhone'] = $phone;
			$arrResult['customerAddress'] = $address;

			wp_send_json( array(
		    	"success" => true,
		    	"data" => $arrResult
		    ));

		}else{
			wp_send_json( array(
		    	"success" => false,
		    	"message" => "Something wrong. No data has been added."
		    ));
		}

	}else{
		die('Busted!');
	}
}

function getRecommendation(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$quotationId = $_POST['quotationId'];		
		$quotation = get_post($quotationId);

		$customerId = get_field('customer_id', $quotation->ID);
		$customer = get_post($customerId);

		$customerType = wp_get_post_terms($quotationId, 'customertype');
		$quotationStatus = wp_get_post_terms($quotationId, 'quotationstatus');
		$paymentStatus = wp_get_post_terms($quotationId, 'paymentstatus');

		$arrResult = array();

		$arrResult['quotationId'] = $quotationId;
		$arrResult['customerId'] = $customerId;
		$arrResult['customerTypeName'] = $customerType[0]->name;
		$arrResult['customerTypeSlug'] = $customerType[0]->slug;
		$arrResult['customerName'] = $customer->post_title;
		$arrResult['customerContactPerson'] = get_field('full_name', $customerId);
		$arrResult['customerPhone'] = get_field('phone', $customerId);
		$arrResult['customerAddress'] = get_field('address', $customerId);
		$arrResult['notes'] = $quotation->post_content;
		$arrResult['paymentStatus'] = $paymentStatus[0]->slug; 
		$arrResult['quotationStatus'] = $quotationStatus[0]->slug;
		$arrResult['price'] = get_field('total_price', $quotationId);
		$arrResult['priceGst'] = get_field('gst', $quotationId);
		$arrResult['totalPrice'] = get_field('total_price_gst', $quotationId);

		wp_send_json( array(
			"success" => true,
			"data" => $arrResult
		));

	}else{
		die('Busted!');
	}
}

function addEditQuotation(){
	if ( isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

		$isEdit = $_POST['isEdit'];
		$quotationNumber = $_POST['quotationnumber'];
		$customerId = $_POST['customer'];
		$customerType = sanitize_title($_POST['customertype']);
		$price = $_POST['totalprice'];
		$gst = $_POST['gst'];
		$totalprice = $_POST['totalpricegst'];
		$notes = $_POST['notes'];

		if($isEdit == 0){
			$postData = array(
				"post_type" => "quotation",
				"post_status" => "publish",
				"post_title" => $quotationNumber,
				"post_content" => $notes
			);

			$quotationId = wp_insert_post($postData);

			if($quotationId){

				add_post_meta($quotationId, 'customer_id', $customerId);
				add_post_meta($quotationId, 'total_price', $price);
				add_post_meta($quotationId, 'gst', $gst);
				add_post_meta($quotationId, 'total_price_gst', $totalprice);
				wp_set_object_terms($quotationId, $customerType, 'customertype');
				wp_set_object_terms($quotationId, 'unpaid', 'paymentstatus');
				wp_set_object_terms($quotationId, 'uncompleted', 'quotationstatus');

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/quotations/';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/quotations/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/quotations/edit/'.$quotationId;
						break;
					case "_invoice":
						$returnURL = get_bloginfo('url').'/quotations/invoices/'.$quotationId;
						break;
				}

			// Redirect for Actions: EDIT, INVOICE, DELETE

				wp_send_json( array(
					"success" => true,
					"message" => "New quotation has been added",
					"returnURL" => $returnURL
				));
			}else{
				wp_send_json( array(
					"success" => false,
					"message" => "Can not add new quotation, please try again later."
				));
			}

		}else{

			$quotationId = $_POST['quotationId'];
			$paymentStatus = $_POST['paymentstatus'];
			$quotationStatus = $_POST['quotationstatus'];

			$postData = array(
				"ID" => $quotationId,
				"post_type" => "quotation"
			);

			$quotationId = wp_update_post($postData);

			if($quotationId){

				update_post_meta($quotationId, 'customer_id', $customerId);
				update_post_meta($quotationId, 'total_price', $price);
				update_post_meta($quotationId, 'gst', $gst);
				update_post_meta($quotationId, 'total_price_gst', $totalprice);
				wp_set_object_terms($quotationId, $customerType, 'customertype');
				wp_set_object_terms($quotationId, $paymentStatus, 'paymentstatus');
				wp_set_object_terms($quotationId, $quotationStatus, 'quotationstatus');

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/quotations/';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/quotations/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/quotations/edit/'.$quotationId;
						break;
				}

				wp_send_json( array(
					"success" => true,
					"message" => "Quotation has been updated",
					"returnURL" => $returnURL
				));
			}else{
				wp_send_json( array(
					"success" => false,
					"message" => "Can not update quotation, please try again later."
				));
			}
		}

	}else{
		die('Busted!');
	}
}

function getCustomerInfo(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$customerId = $_POST['customerId'];

		$customer = get_post($customerId);
		$type = wp_get_post_terms($customer->ID, 'customertype');
		$name = get_field('full_name', $customer->ID) ? get_field('full_name', $customer->ID) : '-';
		$phone = get_field('phone', $customer->ID) ? get_field('phone', $customer->ID) : '-';
		$address = get_field('address', $customer->ID) ? get_field('address', $customer->ID) : '-';

		if($customer){
			$arrResult = array(
				"name" => $customer->post_title,
				"type" => $type[0]->name,
				"typeSlug" => $type[0]->slug,
				"phone" => $phone,
				"contactname" => $name,
				"address" => $address
			);

			wp_send_json( array(
				"success" => true,
				"data" => $arrResult
			));

		}else{
			wp_send_json( array(
				"success" => false,
				"message" => 'Customer not found or already deleted'
			));
		}

	}else{
		die('Busted!');
	}
}

function addEditVehicle(){
	if ( isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

		$isEdit = $_POST['isEdit'];
		$vehicleNumber = $_POST['vehicleNumber'];

		if($isEdit == 0){
			$vehicle_data = array(
				"post_type" => "vehicle",
				"post_status" => "publish",
				"post_title" => $vehicleNumber,
				"post_name" => sanitize_title($vehicleNumber)
			);
			$vehicleId = wp_insert_post($vehicle_data);
		}else{

			$vehicle_id = $_POST['id'];

			$vehicle_data = array(
				"ID" => $vehicle_id,
				"post_type" => "vehicle",
				"post_status" => "publish",
				"post_title" => $vehicleNumber,
				"post_name" => sanitize_title($vehicleNumber)
			);
			$vehicleId = wp_update_post($vehicle_data);
		}		

		if($vehicleId){

			switch($_POST['redirectAction']){
				case "_save":
					$returnURL = get_bloginfo('url').'/vehicles/';
					break;
				case "_addAnother":
					$returnURL = get_bloginfo('url').'/vehicles/add/';
					break;
				case "_continue":
					$returnURL = get_bloginfo('url').'/vehicles/edit/'.$vehicleId;
					break;
			}

			if(!empty($_FILES)){
				$attchmentId = insert_attachment('vehicle_photo', $vehicleId, true);

				if($attchmentId){
					if($isEdit == 0){
						wp_send_json( array(
							"success" => true,
							"message" => 'New vehicle has been added',
							"returnURL" => $returnURL
						));
					}

					wp_send_json( array(
						"success" => true,
						"message" => 'Vehicle has been updated',
						"returnURL" => $returnURL
					));

				}else{
					wp_send_json( array(
						"success" => false,
						"message" => 'Can not upload file, try again later'
					));
				}
			}

			if($isEdit == 0){
				wp_send_json( array(
					"success" => true,
					"message" => 'New vehicle has been added',
					"returnURL" => $returnURL
				));
			}else{
				wp_send_json( array(
					"success" => true,
					"message" => 'Vehicle has been updated',
					"returnURL" => $returnURL
				));
			}				

		}else{
			wp_send_json( array(
				"success" => false,
				"message" => 'Something wrong! no data has been updated.'
			));
		}		
	

	}else{
		die('Busted');
	}
}

function updateEvent(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$id = $_POST['id'];
		$newDate = $_POST['newDate'];
		
		$postId = wp_update_post(array("ID"=>$id,"post_type"=>"event"));

		if($postId){

			update_post_meta($postId, 'event_date', $newDate);

			wp_send_json( array(
		    	"success" => true,
		    	"message" => "Event has been moved"
		    ));

		}else{
			wp_send_json( array(
		    	"success" => false,
		    	"message" => "Something wrong. No data has been updated."
		    ));
		}

	}else{
		die('Busted!');
	}
}

function deleteEvent(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$id = $_POST['id'];

		$current_date = date('Ymd', strtotime(current_time('mysql')));

		$event = get_post('id');
		$eventDate = get_field('event_date', $id);

		if($current_date > $eventDate){
			wp_send_json( array(
		    	"success" => false,
		    	"message" => "Past event can not be deleted"
		    ));
		}

		if(wp_delete_post($id)){

			wp_send_json( array(
		    	"success" => true,
		    	"message" => "Event has been deleted"
		    ));

		}else{
			wp_send_json( array(
		    	"success" => false,
		    	"message" => "Something wrong. No data deleted."
		    ));
		}

	}else{
		die('Busted!');
	}
}

function getCalendar(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$current_date = date('Ymd', strtotime(current_time('mysql')));

		$startDate = date('Ymd', $_POST['start']);
		$endDate = date('Ymd', $_POST['end']);

		$args = array(
			"post_type" => "event",
			"post_status" => "publish",
			"posts_per_page" => -1,
			"order" => "ASC",
			"orderby" => "meta_value",
			"meta_key" => "event_date",
			"meta_query" => array(
				"relation" => "AND",
				array(
					"key" => "event_date",
					"value" => array($startDate, $endDate),
					"compare" => "BETWEEN",
					"type" => "date"
				)
			)
		);

		$event = new WP_Query($args);

		$arrEvents = array();

		if($event->have_posts()){

			while($event->have_posts()): $event->the_post();

				$arrTemp = array();
				$arrTemp['id'] = get_the_ID();
				$arrTemp['title'] = get_field('title');

				$eventDate = get_field('event_date');
				$startTime = get_field('start_time');
				$endTime = get_field('end_time');

				$arrTemp['start_time'] = $startTime;
				$arrTemp['end_time'] = $endTime;

				$arrTemp['start'] = date('Y-m-d', strtotime($eventDate)).' '.$startTime;

				if($endTime == ""){
					$arrTemp['allDay'] = true;
				}else{
					$arrTemp['allDay'] = false;
					$arrTemp['end'] = date('Y-m-d', strtotime($eventDate)).' '.$endTime;

				}

				$arrTemp['team_id'] = get_field('team_id');
				$eventMembers = get_field('team_member');
				$vehicleId = get_field('vehicle');
				$vehicle = get_post($vehicleId);
				$customerId = get_field('customer');
				$customer = get_post($customerId);

				$evtMemberTmp = array();

				foreach ($eventMembers as $evtMember) {
					$evtMemberTmp[] = $evtMember['ID'];
				}

				$arrTemp['members'] = $evtMemberTmp;

				$arrTemp['vehicle'] =  array(
					"ID"=>$vehicle->ID,
					"name" => $vehicle->post_title
				);

				$arrTemp['customer'] =  array(
					"ID"=>$customer->ID,
					"name" => $customer->post_title
				);

				$arrTemp['isPastEvent'] = date('d-m-Y', strtotime($current_date)) > date('d-m-Y', strtotime($eventDate)) ? true : false;

				array_push($arrEvents, $arrTemp);

			endwhile;

			wp_send_json( array(
		    	"success" => true,
		    	"events" => $arrEvents
		    ));

		}else{
			wp_send_json( array(
		    	"success" => false,
		    	"message" => "No record found!"
		    ));
		}		

	}else{
		die('Busted!');
	}
}

function saveEvent(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$isEdit = $_POST['isEdit'];
		$teamId = $_POST['team_id'];
		$title = $_POST['title'];
		$eventDate = date('Ymd', strtotime($_POST['eventDate']));
		$startTime = $_POST['start_time'];
		$endTime = $_POST['end_time'];
		$members = $_POST['members'];
		$vehicle = $_POST['vehicle'];
		$customer = $_POST['customer'];

		if($isEdit == 0){
			$postData = array(
	            'post_type' => 'event',
	            'post_title' => md5(strtotime(date('d-m-Y G:i:s'))),
	            'post_content' => '',
	            'post_status' => 'publish'
	        );

			 $eventId = wp_insert_post($postData);

			 if($eventId){

			 	add_post_meta($eventId, 'title', $title);
			 	add_post_meta($eventId, 'event_date', $eventDate);
			 	add_post_meta($eventId, 'start_time', $startTime);
			 	add_post_meta($eventId, 'end_time', $endTime);
			 	add_post_meta($eventId, 'team_id', $teamId);
			 	update_field('field_54016b26a1c3e', $members, $eventId);
			 	add_post_meta($eventId, 'vehicle', $vehicle['ID']);
			 	add_post_meta($eventId, 'customer', $customer['ID']);

			 	wp_send_json( array(
			    	"success" => true,
			    	"message" => ""
			    ));

			 }else{
			 	wp_send_json( array(
			    	"success" => false,
			    	"message" => "Something wrong. No data has been added."
			    ));
			 }

		}else{

			$postData = array(
				'ID' => $_POST['id'],
	            'post_type' => 'event'
	        );

			 $eventId = wp_update_post($postData);

			 if($eventId){

			 	update_post_meta($eventId, 'title', $title);
			 	update_post_meta($eventId, 'event_date', $eventDate);
			 	update_post_meta($eventId, 'start_time', $startTime);
			 	update_post_meta($eventId, 'end_time', $endTime);
			 	update_post_meta($eventId, 'team_id', $teamId);
			 	update_field('field_54016b26a1c3e', $members, $eventId);
			 	update_post_meta($eventId, 'vehicle', $vehicle['ID']);
			 	update_post_meta($eventId, 'customer', $customer['ID']);

			 	wp_send_json( array(
			    	"success" => true,
			    	"message" => ""
			    ));

			 }else{
			 	wp_send_json( array(
			    	"success" => false,
			    	"message" => "Something wrong. No data has been added."
			    ));
			 }
		}


		

	}else{
		die('Busted!');
	}
}

function addEditCustomer(){
	if ( isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

		$isEdit = $_POST['isEdit'];

		if($isEdit == 0){

			$company = $_POST['company'];
			$fullname = $_POST['fullname'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$phone = $_POST['phone'];
			$fax = $_POST['fax'];
			$note = $_POST['note'];
			$contractExp = $_POST['contractexp'];

			$customerType = $_POST['customertype'];

			$postData = array(
				"post_type" => "customer",
				"post_status" => "publish",
				"post_title" => $company,
				"post_content" => $note
			);

			$postId = wp_insert_post($postData);

			if($postId){

				add_post_meta($postId, 'full_name', $fullname);
				add_post_meta($postId, 'email', $email);
				add_post_meta($postId, 'address', $address);
				add_post_meta($postId, 'phone', $phone);
				add_post_meta($postId, 'fax', $fax);
				add_post_meta($postId, 'contract_expiration_date', $contractExp);

				wp_set_object_terms($postId, $customerType, 'customertype');

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/customers/';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/customers/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/customers/edit/'.$postId;
						break;
				}

				wp_send_json( array(
			    	"success" => true,
			    	"message" => "New customer has been added.",
			    	"returnURL" => $returnURL
			    ));

			}else{
				wp_send_json( array(
			    	"success" => false,
			    	"message" => "Something wrong. No data has been added."
			    ));
			}

		}else{

			$post_id = $_POST['postId'];

			$company = $_POST['company'];
			$fullname = $_POST['fullname'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$phone = $_POST['phone'];
			$fax = $_POST['fax'];
			$note = $_POST['note'];

			$contractExp = $_POST['contractexp'];

			$customerType = $_POST['customertype'];

			$postData = array(
				"ID" => $post_id,
				"post_type" => "customer",
				"post_status" => "publish",
				"post_title" => $company,
				"post_content" => $note,
				"post_name" => sanitize_title($company)
			);

			$postId = wp_update_post($postData);

			if($postId){

				update_post_meta($postId, 'full_name', $fullname);
				update_post_meta($postId, 'email', $email);
				update_post_meta($postId, 'address', $address);
				update_post_meta($postId, 'phone', $phone);
				update_post_meta($postId, 'fax', $fax);
				update_post_meta($postId, 'contract_expiration_date', $contractExp);

				wp_set_object_terms($postId, $customerType, 'customertype');

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/customers/';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/customers/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/customers/edit/'.$postId;
						break;
				}

				wp_send_json( array(
			    	"success" => true,
			    	"message" => "Customer has been added.",
			    	"returnURL" => $returnURL
			    ));

			}else{
				wp_send_json( array(
			    	"success" => false,
			    	"message" => "Something wrong. No data has been added."
			    ));
			}
		}

	}else{
		die('Busted!');
	}
}

function ajaxPagination(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$type = $_POST['itemType'];	

		switch ($type) {
			case 'user':
				getUserPagination();
				break;
			case 'customer':
				getCustomerPagination();
				break;
			case 'vehicle':
				getVehiclePagination();
				break;
			case 'quotation':
				getQuotationPagination();
				break;
			case 'team':
				getTeamPagination();
				break;
			
		}


	}else{
		die('Busted!');
	}
}

function getTeamPagination(){
	$paged = $_POST['paged'];
	$perpage = $_POST['perpage'];
	$offset = $_POST['offset'];
	$keyword = $_POST['keyword'];
	$customer = $_POST['customer'];

	if($paged == 1){
		$offset = 0;
	}else{
		$offset= ($paged-1)*$perpage;
	}

	$args = array(
		"post_type" => "team",
		"post_status" => "publish",
		"posts_per_page" => $perpage,
		"paged" => $paged,
		"order" => "ASC",
		"orderby" => "title",
		"s" => $keyword
	);

	if($customer){
		$args["meta_query"] = array(
			"relation" => "AND",
			array(
				'key' => 'customers',
	            'value' => $customer
			)
		);
	}

	$team = new WP_Query($args);

	if($team->have_posts()){
		$htmlBody = '';
		while($team->have_posts()): $team->the_post();

			$customer = get_field('customers');
			$members = get_field('assigned_to');

			$memberText = '';$counter = 1;

			foreach($members as $member){
				if($counter == count($members)){
					$memberText .= $member['display_name'];
				}else{
					$memberText .= $member['display_name'].", ";
				}
				$counter++;
			}

			$htmlBody .= '<tr class="row-'.get_the_ID().'">
							<td class="center"><input type="checkbox" name="checkbox_item[]" value="'.get_the_ID().'" class="check-item"></td>
							<td>'.get_the_title().'</td>
							<td>'.$customer->post_title.'</td>
							<td>'.$memberText.'</td>						
							<td><a href="'.get_bloginfo('url').'/teams/edit/'.get_the_ID().'" class="label label-info" title="Edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a> <a href="#" class="label label-danger btn-delete" title="Delete" rel="'.get_the_ID().'|team"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
						</tr>';

		endwhile;

		$total_user  = $team->found_posts;  
		$total_pages = ceil($total_user/$perpage);

		$htmlPagination = '';

		if($total_pages > 1){

			for($i = 0; $i < $total_pages; $i++){

				if($paged == ($i+1)){
					$htmlPagination .= '<span class="page-numbers current">'.($i+1).'</span>';
				}else{
					$offset = $offset == 0 ? $perpage : $offset*$i;
					$htmlPagination .= '<a class="page-numbers" href="#" rel="'.($i+1).'|'.$offset.'">'.($i+1).'</a>';
				}
				
			}
		}

    	wp_send_json( array(
	    	"dataBody" => $htmlBody,
	    	"dataPagination" => '<div class="pagination">'.$htmlPagination.'</div>'
	    ));

	}else{
		wp_send_json( array(
	    	"dataBody" => "",
	    	"dataPagination" => ""
	    ));
	}

}

function getQuotationPagination(){
	$paged = $_POST['paged'];
	$perpage = $_POST['perpage'];
	$offset = $_POST['offset'];
	$keyword = $_POST['keyword'];
	$customerType = $_POST['customertype'];
	$paymentStatus = $_POST['paymentstatus'];
	$quotationStatus = $_POST['quotationstatus'];

	if($paged == 1){
		$offset = 0;
	}else{
		$offset= ($paged-1)*$perpage;
	}

	$args = array(
		"post_type" => "quotation",
		"post_status" => "publish",
		"posts_per_page" => $perpage,
		"paged" => $paged,
		"order" => "ASC",
		"orderby" => "title",
		"s" => $keyword
	);

	if($paymentStatus && $quotationStatus && $customerType){
		$args["tax_query"] = array(
			"relation" => "AND",
			array(
				'taxonomy' => 'paymentstatus',
	            'field' => 'slug',
	            'terms' => $paymentstatus
			),
			array(
				'taxonomy' => 'quotationstatus',
	            'field' => 'slug',
	            'terms' => $quotationStatus
			),
			array(
				'taxonomy' => 'customertype',
	            'field' => 'slug',
	            'terms' => $customerType
			)
		);
	}elseif($paymentstatus){
		$args["tax_query"] = array(
			"relation" => "AND",
			array(
				'taxonomy' => 'paymentstatus',
	            'field' => 'slug',
	            'terms' => $paymentStatus
			)
		);
	}elseif($quotationstatus){
		$args["tax_query"] = array(
			"relation" => "AND",
			array(
				'taxonomy' => 'quotationstatus',
	            'field' => 'slug',
	            'terms' => $quotationStatus
			)
		);
	}elseif($customerType){
		$args["tax_query"] = array(
			"relation" => "AND",
			array(
				'taxonomy' => 'customertype',
	            'field' => 'slug',
	            'terms' => $customerType
			)
		);
	}

	$quotation = new WP_Query($args);

	if($quotation->have_posts()){
		$htmlBody = '';
		while($quotation->have_posts()): $quotation->the_post();

			$customerId = get_field('customer_id');
			$customer = get_post($customerId);			
			$price = get_field('total_price');
			$gst = get_field('gst');
			$totalPrice = get_field('total_price_gst');
			$customerType = wp_get_post_terms(get_the_ID(), 'customertype');
			$paymentStatus = wp_get_post_terms(get_the_ID(),'paymentstatus');
			$quotationStatus = wp_get_post_terms(get_the_ID(), 'quotationstatus');

			$htmlBody .= '<tr class="row-'.get_the_ID().'">
							<td class="center"><input type="checkbox" name="checkbox_item[]" value="'.get_the_ID().'" class="check-item"></td>
							<td>'.strtoupper(get_the_title()).'</td>
							<td>'.$customerType[0]->name.'</td>
							<td>'.$customer->post_title.'</td>
							<td>'.$totalPrice.'</td>
							<td>'.$paymentStatus[0]->name.'</td>
							<td>'.$quotationStatus[0]->name.'</td>						
							<td>

								<a href="'.get_bloginfo('url').'/quotations/edit/'.get_the_ID().'" class="label label-info" title="Edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a> 

								<a href="'.get_bloginfo('url').'/quotations/invoices/'.get_the_ID().'" class="label label-info" title="Invoice"><span class=""></span> Invoice</a>

								<a href="#" class="label label-danger btn-delete" title="Delete" rel="'.get_the_ID().'|quotation"><span class="glyphicon glyphicon-remove"></span> Delete</a>



								</td>
						</tr>';

		endwhile;

		$total_user  = $quotation->found_posts;  
		$total_pages = ceil($total_user/$perpage);

		$htmlPagination = '';

		if($total_pages > 1){

			for($i = 0; $i < $total_pages; $i++){

				if($paged == ($i+1)){
					$htmlPagination .= '<span class="page-numbers current">'.($i+1).'</span>';
				}else{
					$offset = $offset == 0 ? $perpage : $offset*$i;
					$htmlPagination .= '<a class="page-numbers" href="#" rel="'.($i+1).'|'.$offset.'">'.($i+1).'</a>';
				}
				
			}
		}

    	wp_send_json( array(
	    	"dataBody" => $htmlBody,
	    	"dataPagination" => '<div class="pagination">'.$htmlPagination.'</div>',
	    	"data" => $quotation->found_posts
	    ));

	}else{
		wp_send_json( array(
	    	"dataBody" => "",
	    	"dataPagination" => ""
	    ));
	}

}

function getVehiclePagination(){
	$paged = $_POST['paged'];
	$perpage = $_POST['perpage'];
	$offset = $_POST['offset'];
	$keyword = $_POST['keyword'];

	if($paged == 1){
		$offset = 0;
	}else{
		$offset= ($paged-1)*$perpage;
	}

	$args = array(
		"post_type" => "vehicle",
		"post_status" => "publish",
		"posts_per_page" => $perpage,
		"paged" => $paged,
		"order" => "ASC",
		"orderby" => "title",
		"s" => $keyword
	);

	$vehicle = new WP_Query($args);

	if($vehicle->have_posts()){
		$htmlBody = '';
		while($vehicle->have_posts()): $vehicle->the_post();
			$img_link = '';$vehicePhoto = '';

			if ( has_post_thumbnail() ) {
                $img = wp_get_attachment_image_src( get_post_thumbnail_id(  $vehicle->ID ), "small" );
                $img_link = $img[0];
                $vehicePhoto = '<img src="'.$img_link.'" alt="'.get_the_title().'" width="200" height="125">';
            }

			$htmlBody .= '<tr class="row-'.get_the_ID().'">
							<td class="center"><input type="checkbox" name="checkbox_item[]" value="'.get_the_ID().'" class="check-item"></td>
							<td>'.get_the_title().'</td>
							<td>'.$vehicePhoto.'</td>							
							<td><a href="'.get_bloginfo('url').'/vehicles/edit/'.get_the_ID().'" class="label label-info" title="Edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a> <a href="#" class="label label-danger btn-delete" title="Delete" rel="'.get_the_ID().'|vehicle"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
						</tr>';

		endwhile;

		$total_user  = $vehicle->found_posts;  
		$total_pages = ceil($total_user/$perpage);

		$htmlPagination = '';

		if($total_pages > 1){

			for($i = 0; $i < $total_pages; $i++){

				if($paged == ($i+1)){
					$htmlPagination .= '<span class="page-numbers current">'.($i+1).'</span>';
				}else{
					$offset = $offset == 0 ? $perpage : $offset*$i;
					$htmlPagination .= '<a class="page-numbers" href="#" rel="'.($i+1).'|'.$offset.'">'.($i+1).'</a>';
				}
				
			}
		}

    	wp_send_json( array(
	    	"dataBody" => $htmlBody,
	    	"dataPagination" => '<div class="pagination">'.$htmlPagination.'</div>',
	    	"data" => $vehicle->found_posts
	    ));

	}else{
		wp_send_json( array(
	    	"dataBody" => "",
	    	"dataPagination" => ""
	    ));
	}

}


function getCustomerPagination(){
	$paged = $_POST['paged'];
	$perpage = $_POST['perpage'];
	$offset = $_POST['offset'];
	$keyword = $_POST['keyword'];
	$customerType = $_POST['customerType'];

	if($paged == 1){
		$offset = 0;
	}else{
		$offset= ($paged-1)*$perpage;
	}

	$args = array(
		"post_type" => "customer",
		"post_status" => "publish",
		"posts_per_page" => $perpage,
		"paged" => $paged,
		"order" => "ASC",
		"orderby" => "title",
		"s" => $keyword
	);

	if($customerType){
		$args["tax_query"] = array(
			"relation" => "AND",
			array(
				'taxonomy' => 'customertype',
	            'field' => 'slug',
	            'terms' => $customerType
			)
		);
	}

	$customer = new WP_Query($args);

	if($customer->have_posts()){
		$htmlBody = '';
		while($customer->have_posts()): $customer->the_post();

			$company_name = get_the_title();
			$fullname = get_field('full_name');
			$email = get_field('email');
			$phone = get_field('phone');
			$fax = get_field('fax');
			$contractExp = (get_field('contract_expiration_date')) ? date('d-m-Y', strtotime(get_field('contract_expiration_date'))) : '-';
			$type = wp_get_post_terms(get_the_ID(), 'customertype');

			$htmlBody .= '<tr class="row-'.get_the_ID().'">
							<td class="center"><input type="checkbox" name="checkbox_item[]" value="'.get_the_ID().'" class="check-item"></td>
							<td>'.$company_name.'</td>
							<td>'.$fullname.'</td>
							<td>'.$email.'</td>
							<td>'.$phone.'</td>
							<td>'.$fax.'</td>
							<td>'.$type[0]->name.'</td>
							<td>'.$contractExp.'</td>
							<td><a href="'.get_bloginfo('url').'/customers/edit/'.get_the_ID().'" class="label label-info" title="Edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a> <a href="#" class="label label-danger btn-delete" title="Delete" rel="'.get_the_ID().'|customer"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
						</tr>';

		endwhile;

		$total_user  = $customer->found_posts;  
		$total_pages = ceil($total_user/$perpage);

		$htmlPagination = '';

		if($total_pages > 1){

			for($i = 0; $i < $total_pages; $i++){

				if($paged == ($i+1)){
					$htmlPagination .= '<span class="page-numbers current">'.($i+1).'</span>';
				}else{
					$offset = $offset == 0 ? $perpage : $offset*$i;
					$htmlPagination .= '<a class="page-numbers" href="#" rel="'.($i+1).'|'.$offset.'">'.($i+1).'</a>';
				}
				
			}
		}

    	wp_send_json( array(
	    	"dataBody" => $htmlBody,
	    	"dataPagination" => '<div class="pagination">'.$htmlPagination.'</div>',
	    	"data" => $customer->found_posts
	    ));

	}else{
		wp_send_json( array(
	    	"dataBody" => "",
	    	"dataPagination" => ""
	    ));
	}

}

function getUserPagination(){

	global $wpdb;
	global $wp_roles;

	$roles = $wp_roles->get_names();

	$paged = $_POST['paged'];
	$perpage = $_POST['perpage'];
	$offset = $_POST['offset'];
	$keyword = $_POST['keyword'];
	$role = $_POST['role'];
	$position = $_POST['position'];
	$status = $_POST['status'];	

	if($paged == 1){
		$offset = 0;
	}else{
		$offset= ($paged-1)*$perpage;
	}


	$args = array(
        'number' => $perpage, 
        'offset' => $offset,
        'order' => 'ASC',
        'orderby' => 'meta_value',
        'meta_key' => 'first_name',
		'meta_query' => array(
	        'relation' => 'AND',
	        array(
	            'key' => 'first_name',
	            'value' => $keyword,
	            'compare' => 'LIKE'
	        ),
	        array(
	            'key' => 'position',
	            'value' => $position,
	            'compare' => 'LIKE'
	        ),
	        array(
	            'key' => 'employment_status',
	            'value' => $status,
	            'compare' => 'LIKE'
	        )	        
		)
    );

    if($role){
    	array_push($args['meta_query'], array(
            'key' => $wpdb->prefix . 'capabilities',
            'value' => $role,
            'compare' => 'LIKE'
        ));
    }else{
    	array_push($args['meta_query'], array(
            'key' => $wpdb->prefix . 'capabilities',
            'value' => 'administrator',
            'compare' => 'NOT LIKE'
        ));
    }

    $users = new  WP_User_Query ( $args );

    if($users->results){

    	$htmlBody = '';

    	foreach($users->results as $user){
    		$sex = get_the_author_meta('gender', $user->ID);
			$current_date = new DateTime('today');
			$birthday = new DateTime(get_the_author_meta('birthday', $user->ID));
			$age = $birthday->diff($current_date)->y;
			$position = get_the_author_meta('position', $user->ID);
			$status = get_the_author_meta('employment_status', $user->ID);
			$license = get_the_author_meta('driving_license', $user->ID) ? 'Yes' : 'No';

			$htmlBody .= '<tr class="row-'.$user->ID.'">
							<td class="center"><input type="checkbox" name="checkbox_item[]" value="'.$user->ID.'" class="check-item"></td>
							<td>'.$user->first_name . ' ' . $user->last_name.'</td>
							<td class="center">'.ucwords(strtolower($sex)).'</td>
							<td class="center">'.$age.'</td>
							<td>'.ucwords(strtolower($position)).'</td>
							<td>'.$roles[$user->roles[0]].'</td>
							<td>'.ucwords(strtolower($status)).'</td>
							<!--<td class="center">'.$license.'</td>-->
							<td><a href="'.get_bloginfo('url').'/staff/edit/'.$user->ID.'" class="label label-info" title="Edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a> <a href="#" class="label label-danger btn-delete" title="Delete" rel="'.$user->ID.'|user"><span class="glyphicon glyphicon-remove"></span> Delete</a></td>
						</tr>';
    	}

    	$total_user  = $users->total_users;  
		$total_pages = ceil($total_user/$perpage);

		$htmlPagination = '';

		if($total_pages > 1){

			for($i = 0; $i < $total_pages; $i++){

				if($paged == ($i+1)){
					$htmlPagination .= '<span class="page-numbers current">'.($i+1).'</span>';
				}else{
					$offset = $offset == 0 ? $perpage : $offset*$i;
					$htmlPagination .= '<a class="page-numbers" href="#" rel="'.($i+1).'|'.$offset.'">'.($i+1).'</a>';
				}
				
			}
		}

    	wp_send_json( array(
	    	"dataBody" => $htmlBody,
	    	"dataPagination" => '<div class="pagination">'.$htmlPagination.'</div>'
	    ));

    }else{
    	wp_send_json( array(
	    	"dataBody" => "",
	    	"dataPagination" => ""
	    ));
    }

    
}

function deleteItem(){
	if ( isset( $_POST['nonce'] ) && check_ajax_referer( '157c6e3c6c6e7b93686e26de9aa1a156', 'nonce' ) ) {

		$itemId = $_POST['itemId'];
		$itemType = $_POST['itemType'];

		$error = 0;

		$itemId = (!is_array($itemId)) ? array($itemId) : $itemId;

		switch($itemType){
			case 'user':

				foreach($itemId as $id){
					if(!wp_delete_user($id)){
						$error++;
					}
				}

				break;
			default:

				foreach($itemId as $id){
					if(!wp_delete_post($id)){
						$error++;
					}
				}

				break;
		}

		if($error == 0){
			wp_send_json( array(
				"success" => true,
				"deletedIds" => $itemId,
				"message" => ucwords($itemType).' has been deleted'
			));
		}else{
			wp_send_json( array(
				"success" => false,
				"message" => 'Something wrong. No data has been deleted',
				"data" => $itemId
			));
		}


	}else{
		die('Busted!');
	}
}

function addEditStaff(){
	if ( isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

		$isEdit = $_POST['isEdit'];

		$username = $_POST['username'];
		$email = $_POST['email'];
		$pass = $_POST['pass1'];
		$role = $_POST['role'];
		$nric = $_POST['nric'];
		$firstName = $_POST['first_name'];
		$lastName = $_POST['last_name'];
		$gender = $_POST['gender'];
		$address = $_POST['address'];
		$postalcode = $_POST['postalcode'];
		$phone = $_POST['phone'];
		$kin = $_POST['kin'];
		$remarks = $_POST['remarks'];
		$position = $_POST['position'];
		$employment_status = $_POST['employment_status'];
		$driving_license = $_POST['driving_license'];
		$birthday = $_POST['birthday'];

		if($isEdit == 0){

			//check if username and email already exist
			$user = username_exists( $username );
			if ( $user or email_exists($email) == true ) {
				wp_send_json( array(
					"success" => false,
					"message" => 'Username or Email already exists.'
				));
			}

			if($pass){
				$user_data = array(
					"user_login" => $username,
					"user_email" => $email,
					"user_pass" => $pass,
					"first_name" => $firstName,
					"last_name" => $lastName,
					"role" => $role
				);
			}else{
				$user_data = array(
					"user_login" => $username,
					"user_email" => $email,
					"first_name" => $firstName,
					"last_name" => $lastName,
					"role" => $role
				);
			}

			$userId = wp_insert_user($user_data);

			if($userId){
				update_user_meta($userId, 'nric', $nric);
				update_user_meta($userId, 'position', $position);
				update_user_meta($userId, 'employment_status', $employment_status);
				update_user_meta($userId, 'driving_license', $driving_license);
				update_user_meta($userId, 'birthday', $birthday);
				update_user_meta($userId, 'gender', $gender);
				update_user_meta($userId, 'address', $address);
				update_user_meta($userId, 'postalcode', $postalcode);
				update_user_meta($userId, 'phone', $phone);
				update_user_meta($userId, 'kin', $kin);
				update_user_meta($userId, 'remarks', $remarks);

				if(!empty($_FILES)){
					$upload_dir = wp_upload_dir();		
					$uploadfile = $upload_dir['basedir'].'/profile/'.basename($_FILES['profile_photo']['name']);
					$fileURL = $upload_dir['baseurl'].'/profile/'.basename($_FILES['profile_photo']['name']);
					
					if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadfile)) {
					
						$current_photo = get_the_author_meta('profile_photo', $current_user->ID);
						
						if(isset($current_photo) && $current_photo != ""){
							$delete_photo = str_replace($upload_dir['baseurl'], "", $current_photo);
							unlink($upload_dir['basedir'].$delete_photo);
						}
					
						update_user_meta($current_user->ID, 'profile_photo', $fileURL);		

					}else{					
						wp_send_json( array(
							"success" => false,
							"message" => 'Something wrong when uploading your photo. Please try again.'
						));
					}
				}

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/staff';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/staff/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/staff/edit/'.$userId;
						break;
				}

				wp_send_json( array(
					"success" => true,
					"returnURL" => $returnURL,
					"message" => 'New staff has been added.'
				));

			}else{
				wp_send_json( array(
					"success" => false,
					"message" => 'Something wrong! no data has been updated.'
				));
			}

		}else{

			$userID = $_POST['userId'];

			$current_user_data = get_user_by('id', $userID);

			if($current_user_data->user_email != $email and email_exists($email) == true ) {
				wp_send_json( array(
					"success" => false,
					"message" => $current_user_data->user_email
				));
			}

			if($pass){
				$user_data = array(
					"ID" => $userID,
					"user_email" => $email, 
					"user_pass" => $pass,
					"first_name" => $firstName,
					"last_name" => $lastName,
					"role" => $role
				);
			}else{
				$user_data = array(
					"ID" => $userID,
					"user_email" => $email,
					"first_name" => $firstName,
					"last_name" => $lastName,
					"role" => $role
				);
			}

			$userId = wp_update_user($user_data);

			if($userId){
				update_user_meta($userId, 'nric', $nric);
				update_user_meta($userId, 'position', $position);
				update_user_meta($userId, 'employment_status', $employment_status);
				update_user_meta($userId, 'driving_license', $driving_license);
				update_user_meta($userId, 'birthday', $birthday);
				update_user_meta($userId, 'gender', $gender);
				update_user_meta($userId, 'address', $address);
				update_user_meta($userId, 'postalcode', $postalcode);
				update_user_meta($userId, 'phone', $phone);
				update_user_meta($userId, 'kin', $kin);
				update_user_meta($userId, 'remarks', $remarks);

				if(!empty($_FILES)){
					$upload_dir = wp_upload_dir();		
					$uploadfile = $upload_dir['basedir'].'/profile/'.basename($_FILES['profile_photo']['name']);
					$fileURL = $upload_dir['baseurl'].'/profile/'.basename($_FILES['profile_photo']['name']);
					
					if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadfile)) {
					
						$current_photo = get_the_author_meta('profile_photo', $current_user->ID);
						
						if(isset($current_photo) && $current_photo != ""){
							$delete_photo = str_replace($upload_dir['baseurl'], "", $current_photo);
							unlink($upload_dir['basedir'].$delete_photo);
						}
					
						update_user_meta($current_user->ID, 'profile_photo', $fileURL);		

					}else{					
						wp_send_json( array(
							"success" => false,
							"message" => 'Something wrong when uploading your photo. Please try again.'
						));
					}
				}

				switch($_POST['redirectAction']){
					case "_save":
						$returnURL = get_bloginfo('url').'/staff';
						break;
					case "_addAnother":
						$returnURL = get_bloginfo('url').'/staff/add/';
						break;
					case "_continue":
						$returnURL = get_bloginfo('url').'/staff/edit/'.$userId;
						break;
				}

				wp_send_json( array(
					"success" => true,
					"returnURL" => $returnURL,
					"message" => 'Staff has been updated.'
				));

			}else{
				wp_send_json( array(
					"success" => false,
					"message" => 'Something wrong! no data has been updated.'
				));
			}
		}


	}else{
		die('Busted!');
	}
} 

function updateProfile(){
	if ( isset( $_POST['post_nonce_field'] ) && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' ) ) {

		global $current_user;

		$email = $_POST['email'];
		$pass = $_POST['pass1'];
		$nric = $_POST['nric'];
		$firstName = $_POST['first_name'];
		$lastName = $_POST['last_name'];
		$gender = $_POST['gender'];
		$address = $_POST['address'];
		$postalcode = $_POST['postalcode'];
		$phone = $_POST['phone'];
		$kin = $_POST['kin'];
		$remarks = $_POST['remarks'];
		$birthday = $_POST['birthday'];

		if($current_user->user_email != $email and email_exists($email) == true ) {
			wp_send_json( array(
				"success" => false,
				"message" => 'Email already exists.'
			));
		}


		if($pass){
			$user_data = array(
				"ID" => $current_user->ID,
				"user_email" => $email,
				"user_pass" => $pass,
				"first_name" => $firstName,
				"last_name" => $lastName,
			);
		}else{
			$user_data = array(
				"ID" => $current_user->ID,
				"user_email" => $email,
				"first_name" => $firstName,
				"last_name" => $lastName,
			);
		}

		$userId = wp_update_user($user_data);

		if($userId){

			update_user_meta($userId, 'nric', $nric);
			update_user_meta($userId, 'birthday', $birthday);
			update_user_meta($userId, 'gender', $gender);
			update_user_meta($userId, 'address', $address);
			update_user_meta($userId, 'postalcode', $postalcode);
			update_user_meta($userId, 'phone', $phone);
			update_user_meta($userId, 'kin', $kin);
			update_user_meta($userId, 'remarks', $remarks);

			if(!empty($_FILES)){
				$upload_dir = wp_upload_dir();		
				$uploadfile = $upload_dir['basedir'].'/profile/'.basename($_FILES['profile_photo']['name']);
				$fileURL = $upload_dir['baseurl'].'/profile/'.basename($_FILES['profile_photo']['name']);
				
				if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadfile)) {
				
					$current_photo = get_the_author_meta('profile_photo', $current_user->ID);
					
					if(isset($current_photo) && $current_photo != ""){
						$delete_photo = str_replace($upload_dir['baseurl'], "", $current_photo);
						unlink($upload_dir['basedir'].$delete_photo);
					}
				
					update_user_meta($current_user->ID, 'profile_photo', $fileURL);		

				}else{					
					wp_send_json( array(
						"success" => false,
						"message" => 'Something wrong when uploading your photo. Please try again.'
					));
				}
			}

			wp_send_json( array(
				"success" => true,
				"message" => 'Your profile has been updated'
			));

		}else{
			wp_send_json( array(
				"success" => false,
				"message" => 'Something wrong! no data has been updated.'
			));
		}
		
		
	

	}else{
		die('Busted');
	}
}


/***
 * CUSTOM USER PROFILE
 */
add_action( 'show_user_profile', 'custom_user_profile' );
add_action( 'edit_user_profile', 'custom_user_profile' );


function custom_user_profile($user){

?>

<h3>Additional Information</h3>

<table class="form-table">

	<tr>
        <th><label for="nric">NRIC/FIN No</label></th>     
        <td>
            <input type="text" name="nric" id="nric" value="<?php echo esc_attr( get_the_author_meta( 'nric', $user->ID ) ); ?>" class="regular-text"/>
            <br><span class="description">Please type your NRIC/FIN No.</span>
        </td>
    </tr>
    <tr>
        <th><label for="birthday">Birthday</label></th>     
        <td>
            <input type="text" name="birthday" id="birthday" value="<?php echo esc_attr( get_the_author_meta( 'birthday', $user->ID ) ); ?>" class="regular-text"/>
            <br><span class="description">Please type your birthday.</span>
        </td>
    </tr>
	<tr>
        <th><label for="gender">Gender</label></th>     
        <td>
        	<?php 
        		$gender = ( get_the_author_meta( 'gender', $user->ID ) ) ;

        		if($gender == "female"){
        			$female = "selected='selected'";
        			$male = "";
        		}else{
        			$male = "selected='selected'";
        			$female = "";
        		}

        	;?>
            <select id="gender" name="gender">
            	<option value="female" <?php echo $female;?>>Female</option>
            	<option value="male" <?php echo $male;?>>Male</option>
            </select>
            <br><span class="description">Please select gender.</span>
        </td>
    </tr>
    <tr>
        <th><label for="position">Position</label></th>     
        <td>
        	<?php 
        		$position = ( get_the_author_meta( 'position', $user->ID ) ) ;

        		switch ($position) {
        			case 'manager':
        				$manager = 'selected="selected"';
        				$supervisor = '';
        				$teamleader = '';
        				$staff = '';
        				break;
        			case 'supervisor':
        				$manager = '';
        				$supervisor = 'selected="selected"';
        				$teamleader = '';
        				$staff = '';
        				break;
        			case 'teamleader':
        				$manager = '';
        				$supervisor = '';
        				$teamleader = 'selected="selected"';
        				$staff = '';
        				break;
        			case 'staff':
        				$manager = '';
        				$supervisor = '';
        				$teamleader = '';
        				$staff = 'selected="selected"';
        				break;
        			default:
        				$manager = '';
        				$supervisor = '';
        				$teamleader = '';
        				$staff = '';
        				break;

        		}

        	;?>
            <select id="position" name="position">
            	<option value="manager" <?php echo $manager;?>>Manager</option>
            	<option value="supervisor" <?php echo $supervisor;?>>Supervisor</option>
            	<option value="teamleader" <?php echo $teamleader;?>>Team Leader</option>
            	<option value="worker" <?php echo $staff;?>>Worker</option>
            </select>
            <br><span class="description">Please select position.</span>
        </td>
    </tr>
    <tr>
        <th><label for="employment_status">Status</label></th>     
        <td>
        	<?php 
        		$status = ( get_the_author_meta( 'employment_status', $user->ID ) ) ;

        		switch ($status) {
        			case 'temporary':
        				$temporary = 'selected="selected"';
        				$permanent = '';
        				break;
        			case 'permanent':
        				$temporary = '';
        				$permanent = 'selected="selected"';
        				break;
        			default:
        				$temporary = '';
        				$permanent = '';
        				break;
        		}

        	;?>
            <select id="employment_status" name="employment_status" rel="<?php echo $status;?>">
            	<option value="temporary" <?php echo $temporary;?>>Temporary</option>
            	<option value="permanent" <?php echo $permanent;?>>Permanent</option>
            </select>
            <br><span class="description">Please select employement status.</span>
        </td>
    </tr>
    <tr>
        <th><label for="driving_license">Has Driving License</label></th>     
        <td>
        	<?php
        		$license = ( get_the_author_meta( 'driving_license', $user->ID ) ) ;
        		$checked = $license ? 'checked="checked"' : '';
        	?>
            <label for="driving_license"><input type="checkbox" name="driving_license" id="driving_license" value="true" <?php echo $checked;?>> Yes</label>
            <br><span class="description">Please select position.</span>
        </td>
    </tr>
	<tr>
        <th><label for="address">Address</label></th>     
        <td>
            <input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" class="regular-text"/>
            <br><span class="description">Please type your address.</span>
        </td>
    </tr>
	<tr>
        <th><label for="postalcode">Postal Code</label></th>     
        <td>
            <input type="text" name="postalcode" id="postalcode" value="<?php echo esc_attr( get_the_author_meta( 'postalcode', $user->ID ) ); ?>" class="regular-text"/>
            <br><span class="description">Please type your postal code.</span>
        </td>
    </tr>
    <tr>
        <th><label for="phone">Phone Number</label></th>     
        <td>
            <input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" class="regular-text" />
            <br><span class="description">Please type your phone number.</span>
        </td>
    </tr>
    <tr>
        <th><label for="kin">Next of Kin</label></th>     
        <td>
            <input type="text" name="kin" id="kin" value="<?php echo esc_attr( get_the_author_meta( 'kin', $user->ID ) ); ?>" class="regular-text" />
            <br><span class="description">Please type your next of kin.</span>
        </td>
    </tr>
    <tr>
        <th><label for="remarks">Remarks</label></th>     
        <td>
            <textarea name="remarks" id="remarks" rows="5" cols="30"><?php echo esc_attr( get_the_author_meta( 'remarks', $user->ID ) ); ?></textarea>
            <br><span class="description">Please type remarks if needed.</span>
        </td>
    </tr>
    <tr>
        <th><label for="profile_photo">Profile Photo</label></th>
        <td>
            <img src="<?php echo esc_attr( get_the_author_meta( 'profile_photo', $user->ID ) ); ?>" style="height:140px;">
            <p style="clear:both;display:block;">
            <input type="text" name="profile_photo" id="profile_photo" value="<?php echo esc_attr( get_the_author_meta( 'profile_photo', $user->ID ) ); ?>" class="regular-text" />  <input type='button' class="button-primary" value="Upload Photo" id="uploadPhoto"/><br /></p>
            <span class="description" style="clear:both;display:block;">Please upload a photo for your profile.</span>
        </td>
    </tr>	 
</table>
<br/><br/>

<?php
}

function profile_upload_js() {
?>
<script type="text/javascript">
    jQuery(document).ready(function() 
    {
        jQuery(document).find("input[id^='uploadPhoto']").on('click', function()
        {
            //var num = this.id.split('-')[1];
            formfield = jQuery('#profile_photo').attr('name');
            tb_show('', 'media-upload.php?type=image&TB_iframe=true');

            window.send_to_editor = function(html) 
            {
                imgurl = jQuery('img',html).attr('src');
                jQuery('#profile_photo').val(imgurl);
                tb_remove();
            }

            return false;
        });
    });
</script>
<?php
}

add_action('admin_head','profile_upload_js');
 
function custom_profile_enque_scripts_init(){
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

add_action('init', 'custom_profile_enque_scripts_init');

add_action( 'personal_options_update', 'save_custom_profile' );
add_action( 'edit_user_profile_update', 'save_custom_profile' );
 
function save_custom_profile( $user_id ) {
 
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    
	update_usermeta( $user_id, 'nric', $_POST['nric'] );
	update_usermeta( $user_id, 'birthday', $_POST['birthday'] );
    update_usermeta( $user_id, 'gender', $_POST['gender'] );
    update_usermeta( $user_id, 'position', $_POST['position'] );
    update_usermeta( $user_id, 'employment_status', $_POST['employment_status'] );
    update_usermeta( $user_id, 'driving_license', $_POST['driving_license'] );
	update_usermeta( $user_id, 'address', $_POST['address'] );
	update_usermeta( $user_id, 'remarks', $_POST['remarks'] );
    update_usermeta( $user_id, 'postalcode', $_POST['postalcode'] );
    update_usermeta( $user_id, 'phone', $_POST['phone'] );
    update_usermeta( $user_id, 'kin', $_POST['kin'] );
    update_usermeta( $user_id, 'profile_photo', $_POST['profile_photo'] );

}

/***
 * HELPERS
 */
 
function array_to_csv_download($array, $filename = "result.csv", $delimiter=";") {

    $wp_upload = wp_upload_dir();

    $csv_folder     = $wp_upload['path'];
    $CSVFileName    = $csv_folder.'/'.$filename.'.csv';
    $FileHandle     = fopen($CSVFileName, 'w') or die("can't open file");
    fclose($FileHandle);
    $fp = fopen($CSVFileName, 'w');
    foreach ($array as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);

    return  $wp_upload['url'].'/'.$filename.'.csv';
}


function generatePDF($dataHTML, $filename){
    //echo $dataHTML;
    require_once('tcpdf/tcpdf.php');

    $wp_upload = wp_upload_dir();

    class MYPDF extends TCPDF {
        public function Footer() {
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('');
    $pdf->SetTitle('');

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    $pdf->SetMargins(10,10,10,10);

    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


    $pdf->SetDisplayMode('fullpage', 'UseNone');

    $pdf->AddPage('P', 'A4');
    $pdf->Cell(0, 0, '', 0, 1);

    $pdf->writeHTML($dataHTML, true, false, false, false, '');

    $pdf->lastPage();

    $pdf->Output($wp_upload['path']."/".$filename, 'F');


}

function insert_attachment($file_handler, $post_id, $setthumb = false) {

  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK){
        wp_send_json( array(
            "success" => false,
            "message" => $_FILES[$file_handler]['error']
        ));
        die();
  }
 
  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
 
  $attach_id = media_handle_upload( $file_handler, $post_id );
 
  if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
  return $attach_id;
}

function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function hour_minute_to_second($time) {
	 $strTime = explode(":", $time);

	 $hour = (int) $strTime[0];
	 $hour = $hour*3600;

	 $min = (int) $strTime[1];
	 $min = $min*60;

	 return $hour + $min;
}

function duration($seconds){

	if($seconds < 3600){

		$return = $seconds/60;

		return $return.' m';
	}elseif($seconds > 3600){

		$hour = floor($seconds/3600);

		$min = floor(((1/60) * $seconds) - ($hour*60));

		return $hour.' h '.$min.' m';

	}else{
		return $return.' h';
	}
	
}

function signature($img){

  $sig = filter_input(INPUT_POST, 'output', FILTER_UNSAFE_RAW);
  $img = sigJsonToImage($sig);

  imagepng($img, 'signature.png');

  imagedestroy($img);

}





?>