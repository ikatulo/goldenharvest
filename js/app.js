var systemDate = new Date(),
	firstDate = new Date(systemDate.getFullYear(), systemDate.getMonth(), 1),
	lastDate = new Date(systemDate.getFullYear(), systemDate.getMonth() + 1, 0),
	today = systemDate.getFullYear()+"-"+("0"+(systemDate.getMonth()+1)).slice(-2)+"-"+("0"+systemDate.getDate()).slice(-2);

var goldenHarvest = {

	pagination: {
		paged: 1,
		offset: 0
	},

	currentEvents: [],
	currentEventData: {
		isEdit: false
	},

	init: function(){
		this.createDatepicker();
		this.customDropdown();
		this.checkboxAll();
		this.updateProfile();
		this.addEditStaff();
		this.addEditTeam();
		this.addEditCustomer();
		this.addEditVehicle();
		this.addEditQuotation();
		this.deleteItem();
		this.deleteItems();
		this.userPagination();
		this.customerPagination();
		this.vehiclePagination();
		this.quotationPagination();
		this.teamPagination();
		this.createCalendar();
		this.saveCalendar();
		this.deleteCalendar();
		this.newCustomerPopup();
		this.trackerPopup();
		this.closePopup();
		this.getTrackedEvents();
	},

	getTrackedEvents: function(){

		if($('#tracker-filter').length){

			var app  = this;

			app.doGetTrackedEvents($('.standard-datepicker').val());

			$('.standard-datepicker').datepicker({
				dateFormat: 'dd-mm-yy',
				maxDate: 0,
				changeMonth: true,
	      		changeYear: true,
	      		onSelect: function(){
	      			var value = $(this).val();
	      			
	      			app.doGetTrackedEvents(value);
	      		}
			});

		}

	},

	doGetTrackedEvents: function(value){

		var app = this;

		var postData = {
			action: "getTrackedEvents",
			nonce: ajaxNonce,
			dateRequested: value
		};

		this.doAjax(postData, function(response){
			if(response.success){

				$('table#tracker-list tbody').html(response.data);

			}else{
				$('table#tracker-list tbody').html("");
			}
		});

	},

	trackerPopup: function(){

		var app = this;

		$('body').on('click', 'a#setDone', function(e){
			e.preventDefault();

			var eventId = $(this).attr("rel");

			$.magnificPopup.open({
				items:{
					src: "#trackerStatusPopup",
					type: "inline"
				},
				modal: true,
				callbacks: {
					beforeOpen: function(){
						$('#trackerStatusPopup input#eventId').val(eventId);
						$('#trackerStatusPopup input#actualTime').val("");
						$('#trackerStatusPopup textarea').val("");
					}
				}
			});			

		});


		$('body').on('click', 'a#setEventDone', function(e){
			e.preventDefault();

			var eventId = $('#trackerStatusPopup input#eventId').val(),
				actualTime = $('input#actualTime').val(),
				note = $('textarea#note').val();

			if(actualTime == ""){
				app.showNotification('Please set actual time', true);
				return false;
			}

			var postData = {
				action: "setEventDone",
				nonce: ajaxNonce,
				eventId: eventId,
				actualTime: actualTime,
				note: note
			};


			app.doAjax(postData, function(response){
				if(response.success){
					$('table#tracker-list').find('tr.row-'+eventId).children('.actual-time').html(response.data.actual_time);
					$('table#tracker-list').find('tr.row-'+eventId).children('.duration').html(response.data.duration);
					$('table#tracker-list').find('tr.row-'+eventId).children('.action').html('<label class="label label-info"><span class="glyphicon glyphicon-ok"></span> Done</label>');
					$.magnificPopup.close();
				}else{
					app.showNotification(response.message, true);
				}
			});

		});
	},

	addEditTeam: function(){
		var app = this;
	
		var options = { 
            target:'#notification', 
            url: ajaxURL.url,
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function(arr, form, options){
              
				var teamName = $('input#teamName').val(),
					customer = $('select#customer').select2('val'),
					members = $('select#members').select2('val'),
					error = 0;


				if($.trim(teamName).length == 0){
					app.showNotification('Please fill team name', true);
					return false;
				}

				if(customer.length == ""){
					app.showNotification('Please fill team name', true);
					return false;
				}

				if(members.length == 0){
					app.showNotification('Please fill team name', true);
					return false;
				}
				
				app.showLoader();
				
            },
            success:function(responseText, statusText, xhr, form){
                if(responseText.success){ 					
					app.hideLoader();
					
					app.showNotification(responseText.message, false, function(){
						window.location = responseText.returnURL;
					});
					
                }else{
					app.hideLoader();
                    app.showNotification(responseText.message, true);					
					return false;					
                }
            }
        };

		$('button[type="submit"]').click(function(e){
			//e.preventDefault();
			var value = $(this).val();
			options.data = {redirectAction: value};

			$('#addTeam').ajaxForm(options);

		});		

	},


	teamPagination: function(){
		var app = this;

		if($('table#team-list').length){

			app.doTeamPagination();
			
			$('body').on('click', '.pagination a', function(e){
				e.preventDefault();

				var rel = $(this).attr('rel').split('|');
				app.pagination.paged = parseInt(rel[0]);
				app.pagination.offset = parseInt(rel[1]);
				app.doTeamPagination();
			});
		}

		if($('#team-filter').length){

			$('#team-filter').on('change', 'select', function(e){
				e.preventDefault();
				app.doTeamPagination();
			});

			$('#team-filter input[type="text"]').on('keyup', $.debounce(500, function(){
				app.doTeamPagination();			
			}));

			$('#team-filter #reset-filter').on('click', function(e){
				e.preventDefault();
				$('#team-filter input[type="text"]').val('');
				app.doTeamPagination();
			})

		}
	},

	doTeamPagination: function(){
		var app = this;

		var formData = {
			action: "ajaxPagination",
			nonce: ajaxNonce,
			paged: app.pagination.paged,
			perpage: 25,
			offset: app.pagination.offset,
			itemType: $('input[name="itemType"]').val(),
			keyword: $('input#keyword').val(),
			customer: $('select#customer').select2('val')
		};

		app.getPaginationData(ajaxURL.url, formData, function(response){
			$('table#team-list tbody').html(response.dataBody).fadeIn('fast');
			$('table#team-list tfoot tr td').html(response.dataPagination).fadeIn('fast');
		});
	},

	newCustomerPopup: function(){

		var app = this;

		$('body').on('click', 'a#addNewCustomer', function(e){
			e.preventDefault();

			$.magnificPopup.open({
				items:{
					src:'#newCustomerPopup',
					type: 'inline'
				},
				modal: true
			});

		});

		$('body').on('click', 'a#saveNewCustomer', function(e){
			e.preventDefault();

			var company = $('#formCustomerPopup input#company').val(),
				fullname = $('#formCustomerPopup input#fullname').val(),
				email = $('#formCustomerPopup input#email').val(),
				address = $('#formCustomerPopup textarea#address').val(),
				phone  = $('#formCustomerPopup input#phone').val(),
				customerType = $('#formCustomerPopup select#customertype').select2("val"),
				contractExp = $('#formCustomerPopup input#contractexp').val(),
				fax = $('#formCustomerPopup input#fax').val(),
				notes = $('#formCustomerPopup textarea#notes').val(),
				error = 0;


			if($.trim(company).length == 0){
				app.showNotification('Please fill company name', true);
				return false;
			}

			if($.trim(fullname).length == 0){
				app.showNotification('Please fill customer full name', true);
				return false;
			}

			if($.trim(email).length == 0){
				app.showNotification('Please fill email address', true);
				return false;
			}else if(!app.isValidEmail(email)){
				app.showNotification('Please fill valid email format', true);
				return false;
			}

			if($.trim(address).length == 0){
				app.showNotification('Please fill customer address', true);
				return false;
			}

			if($.trim(phone).length == 0){
				app.showNotification('Please fill customer phone number', true);
				return false;
			}

			if($.trim(customerType).length == ""){
				app.showNotification('Please select customer type', true);
				return false;
			}else if(customerType == "monthly" && $.trim(contractExp).length == 0){
				app.showNotification('Please fill contract expiration date', true);
				return false;
			}


			app.showLoader();

			var postData = {
				action: "newCustomerPopup",
				nonce: ajaxNonce,
				customerType: customerType,
				company: company,
				contractExp: contractExp,
				fullname: fullname,
				email: email,
				phone: phone,
				address: address,
				fax: fax,
				notes: notes
			};

			app.doAjax(postData, function(response){

				app.hideLoader();

				if(response.success){
					$('input#customertype').val(response.data.customerTypeSlug);
					$('#customer-detail dl dd.type').html(response.data.customerTypeName);
					$('#customer-detail dl dd.name').html(response.data.customerName);
					$('#customer-detail dl dd.contactname').html(response.data.customerContactPerson);
					$('#customer-detail dl dd.contactnumber').html(response.data.customerPhone);
					$('#customer-detail dl dd.contactaddress').html(response.data.customerAddress);

					$('select#customer').append('<option value="'+response.data.customerId+'">'+response.data.customerName+'</option>');
					$('select#customer').select2('val', response.data.customerId);

					$('input#totalprice').val('0');
					$('input#gst').val('0');
					$('input#totalpricegst').val('0');

					$('#customer-detail').show();
					$.magnificPopup.close();
				}else{
					app.showNotification(response.message, true);
				}
			});

		});
	},

	addEditQuotation: function(){
		var app = this;
	
		var options = { 
            target:'#notification', 
            url: ajaxURL.url,
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function(arr, form, options){
              
				var customer = $('select#customer').select2('val'),
					totalPrice  = accounting.unformat($('input#totalprice').val()),
					error = 0;


				if(customer == ""){
					app.showNotification('Please select customer', true);
					return false;
				}

				if($.trim(totalprice).length == 0 || totalprice == 0){
					app.showNotification('Please fill total price or select from recommendation to use the data', true);
					return false;
				}
				
				app.showLoader();
				
            },
            success:function(responseText, statusText, xhr, form){
                if(responseText.success){ 					
					app.hideLoader();
					
					app.showNotification(responseText.message, false, function(){
						window.location = responseText.returnURL;
					});
					
                }else{
					app.hideLoader();
                    app.showNotification(responseText.message, true);					
					return false;					
                }
            }
        };

		$('button[type="submit"]').click(function(e){
			//e.preventDefault();
			var value = $(this).val();
			options.data = {redirectAction: value};

			$('#addQuotation').ajaxForm(options);

		});


		if($('select#recommendation').length){
			$('select#recommendation').on('change', function(e){
				var value = $(this).val();

				if(value != ""){
					var postData = {
						action: "getRecommendation",
						nonce: ajaxNonce,
						quotationId: value
					};

					app.doAjax(postData, function(response){
						if(response.success){
							$('input#customertype').val(response.data.customerTypeSlug);
							$('#customer-detail dl dd.type').html(response.data.customerTypeName);
							$('#customer-detail dl dd.name').html(response.data.customerName);
							$('#customer-detail dl dd.contactname').html(response.data.customerContactPerson);
							$('#customer-detail dl dd.contactnumber').html(response.data.customerPhone);
							$('#customer-detail dl dd.contactaddress').html(response.data.customerAddress);
							$('select#customer').select2('val', response.data.customerId);

							$('input#totalprice').val(response.data.price);
							$('input#gst').val(response.data.priceGst);
							$('input#totalpricegst').val(response.data.totalPrice);

							$('textarea#notes').val(response.data.notes);
							$('#customer-detail').show();
						}else{
							app.showNotification(response.message, true);
						}
					});
				}
			});
		}		
	},

	quotationPagination: function(){
		var app = this;

		if($('table#quotation-list').length){

			app.doQuotationPagination();
			
			$('body').on('click', '.pagination a', function(e){
				e.preventDefault();

				var rel = $(this).attr('rel').split('|');
				app.pagination.paged = parseInt(rel[0]);
				app.pagination.offset = parseInt(rel[1]);
				app.doQuotationPagination();
			});
		}

		if($('#quotation-filter').length){

			$('#quotation-filter').on('change', 'select', function(e){
				e.preventDefault();
				app.doQuotationPagination();
			});

			$('#quotation-filter input[type="text"]').on('keyup', $.debounce(500, function(){
				app.doQuotationPagination();			
			}));

			$('#quotation-filter #reset-filter').on('click', function(e){
				e.preventDefault();
				$('#quotation-filter input[type="text"]').val('');
				app.doQuotationPagination();
			})

		}
	},

	doQuotationPagination: function(){
		var app = this;

		var formData = {
			action: "ajaxPagination",
			nonce: ajaxNonce,
			paged: app.pagination.paged,
			perpage: 25,
			offset: app.pagination.offset,
			itemType: $('input[name="itemType"]').val(),
			keyword: $('input#keyword').val(),
			customertype: $('select#customertype').select2('val'),
			quotationStatus: $('select#quotationstatus').select2('val'),
			paymentStatus: $('select#paymentstatus').select2('val'),
		};

		app.getPaginationData(ajaxURL.url, formData, function(response){
			$('table#quotation-list tbody').html(response.dataBody).fadeIn('fast');
			$('table#quotation-list tfoot tr td').html(response.dataPagination).fadeIn('fast');
		});
	},

	addEditVehicle: function(){
		var app = this;
	
		var options = { 
            target:'#notification', 
            url: ajaxURL.url,
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function(arr, form, options){
              
				var vehicleNumber = $('input#vehicleNumber').val(),
					error = 0;


				if($.trim(vehicleNumber).length == 0){
					app.showNotification('Please fill vehicle number', true);
					return false;
				}
				
				app.showLoader();
				
            },
            success:function(responseText, statusText, xhr, form){
                if(responseText.success){ 					
					app.hideLoader();
					
					app.showNotification(responseText.message, false, function(){
						window.location = responseText.returnURL;
					});
					
                }else{
					app.hideLoader();
                    app.showNotification(responseText.message, true);					
					return false;					
                }
            }
        };

		$('button[type="submit"]').click(function(e){
			//e.preventDefault();
			var value = $(this).val();
			options.data = {redirectAction: value};

			$('#addVehicle').ajaxForm(options);

		});		

	},

	vehiclePagination: function(){
		var app = this;

		if($('table#vehicle-list').length){

			app.doVehiclePagination();
			
			$('body').on('click', '.pagination a', function(e){
				e.preventDefault();

				var rel = $(this).attr('rel').split('|');
				app.pagination.paged = parseInt(rel[0]);
				app.pagination.offset = parseInt(rel[1]);
				app.doVehiclePagination();
			});
		}

		if($('#vehicle-filter').length){

			$('#vehicle-filter input[type="text"]').on('keyup', $.debounce(500, function(){
				app.doVehiclePagination();			
			}));

			$('#vehicle-filter #reset-filter').on('click', function(e){
				e.preventDefault();
				$('#vehicle-filter input[type="text"]').val('');
				app.doVehiclePagination();
			})

		}
	},

	doVehiclePagination: function(){
		var app = this;

		var formData = {
			action: "ajaxPagination",
			nonce: ajaxNonce,
			paged: app.pagination.paged,
			perpage: 25,
			offset: app.pagination.offset,
			itemType: $('input[name="itemType"]').val(),
			keyword: $('input#keyword').val(),

		};

		app.getPaginationData(ajaxURL.url, formData, function(response){
			$('table#vehicle-list tbody').html(response.dataBody).fadeIn('fast');
			$('table#vehicle-list tfoot tr td').html(response.dataPagination).fadeIn('fast');
		});
	},

	createCalendar: function(){

		var app = this;

		var defaultCalendar = systemDate.getFullYear()+"-"+("0"+(systemDate.getMonth()+1)).slice(-2)+"-"+("0"+systemDate.getDate()).slice(-2);

		if($('#calendar').length){
			$('#calendar').fullCalendar({
				header: {
			        left: 'prev,next today',
			        center: 'title',
			        right: 'month,agendaWeek,agendaDay'    
			    },			    
			    events: function(start, end, timezone, callback){			    	

			    	var postData = {
			    		start: start.unix(),
			    		end: end.unix(),
			    		action: "getCalendar",
			    		nonce: ajaxNonce
			    	};

			    	app.doAjax(postData, function(response){
			    		if(response.success){
			    			callback(response.events);
			    		}else{
			    			//app.showNotification(response.message, true);
			    		}
			    	});

			    },
			    eventLimit: 2,
			    defaultDate: defaultCalendar,
			    eventRender: function(event, element) {

					var startTime = event.start_time;						

					if(event.end_time != ""){
						var time = "<strong>Start:</strong> " + startTime + " <strong>End:</strong> " + event.end_time;
					}else{
						var time = "All Day";
					}

					var pastEvent = event.isPastEvent ? " pastEvent" : "";

					if(event.isPastEvent) $(element).addClass('pastEvent');
					
					$(element).html('<div class="fc-content'+pastEvent+'"><span class="fc-title"><strong>Team:</strong> '+event.title+'</span><span class="fc-client"><strong>Customer:</strong> '+event.customer.name+'</span><span class="fc-date">'+time+'</span></div>');
				}
			});
		}

		//drag and drop event to calendar
		if($('#calendar-future').length){

			var headerHeight = $('.header').innerHeight(),
				pageTitleHeight = $('.page-title').innerHeight();

			$(window).scroll(function(e){
				var scrollTop = $(this).scrollTop();

				if(scrollTop >= headerHeight){
					$('.col-md-2 .panel').css({'position':'fixed','top':'150px','z-index':'999'});
				}else{
					$('.col-md-2 .panel').css({'position':'relative','top':'auto'});
				}
			});


			$('#dragable .fc-event').each(function() {

				var eventObject = {
					team_id: $(this).attr('rel'),
					title: $.trim($(this).text()),
					members: $(this).data('member')
				};

				$(this).data('eventObject', eventObject);

				$(this).draggable({
					zIndex: 999,
					revert: true,
					revertDuration: 0,
					cursor: "move"
				});

			});			

			$('#calendar-future').fullCalendar({
			    header: {
			        left: 'prev,next today',
			        center: 'title',
			        right: 'month,agendaWeek,agendaDay'    
			    },			    
			    events: function(start, end, timezone, callback){			    	

			    	var postData = {
			    		start: start.unix(),
			    		end: end.unix(),
			    		action: "getCalendar",
			    		nonce: ajaxNonce
			    	};

			    	app.doAjax(postData, function(response){
			    		if(response.success){
			    			callback(response.events);
			    		}else{
			    			//app.showNotification(response.message, true);
			    		}
			    	});

			    },
			    eventLimit: 2,
			    editable: true,
			    droppable: true,
			    defaultDate: defaultCalendar,
				drop: function(date, jsEvent, ui) {

					app.currentEventData.isEdit = 0;

					var droppedItem = this,
						postData = {},
						eventYear = date._d.getFullYear(),
						eventMonth = ("0" + (date._d.getMonth()+1)).slice(-2),
						eventDay = ("0" + date._d.getDate()).slice(-2);

					var originalEventObject = $(this).data('eventObject');
						originalEventObject.eventDate = eventYear+"-"+eventMonth+"-"+eventDay;						
					var teamId = originalEventObject.team_id;

					postData.action = "saveEvent";
					postData.nonce = ajaxNonce;
					postData.eventDate = eventYear+"-"+eventMonth+"-"+eventDay;
					postData.title = originalEventObject.title;
					postData.team_id = originalEventObject.team_id;
					postData.members = originalEventObject.members;
					postData.isEdit = 0;

					var renderEventObject = {};
					
					$.magnificPopup.open({
						items:{
							src:'#event-popup',
							type: 'inline'
						},
						callbacks:{
							beforeOpen: function() {								

							    $('#popup-header').html('Additional Information');

							    var selectMemberOptions = '';

							    /** add members **/
							    for(i=0; i < objTeamMembers[teamId].length;i++){
							    	selectMemberOptions += '<option value="'+objTeamMembers[teamId][i].id+'" selected>'+objTeamMembers[teamId][i].name+'</option>';
							    }

							    $('select#members').append(selectMemberOptions).select2();
							    
							},

							beforeClose: function(){
								if(app.currentEventData.isEdit == 0){

									if(app.currentEvents.length && app.currentEvents.hasOwnProperty(originalEventObject.eventDate)){
										alert('Event already exist for this team and date');
									}else{
										app.currentEvents[originalEventObject.eventDate] = [originalEventObject];
									}

									originalEventObject.start_time = app.currentEventData.start_time;
									originalEventObject.end_time = app.currentEventData.end_time;
									originalEventObject.vehicle = {ID:app.currentEventData.vehicleId, name:app.currentEventData.vehicleName};
									originalEventObject.customer = {ID:app.currentEventData.customerId, name: app.currentEventData.customerName};

									postData.start_time = app.currentEventData.start_time;
									postData.end_time = app.currentEventData.end_time;
									postData.vehicle = {ID:app.currentEventData.vehicleId, name:app.currentEventData.vehicleName};
									postData.customer = {ID:app.currentEventData.customerId, name: app.currentEventData.customerName};

									app.showLoader();

									/*** save to database via ajax ***/
									app.doAjax(postData, function(response){
										app.hideLoader();
										if(response.success){
											renderEventObject = $.extend({}, originalEventObject);
											renderEventObject.start = date;

											$('#calendar-future').fullCalendar('renderEvent', renderEventObject, true);
										}else{
											app.showNotification(response.message, true);
											return false;
										}
									});

									app.currentEventData = null;
									app.currentEventData = {isEdit:null};
										
								}

								
							},
							afterClose: function(){
								$('form#formEvent')[0].reset();
								$('select#customer').select2("val", "");
								$('select#vehicle').select2("val", "");
							}
						},
						modal:true
					});
										
					
				},
				eventRender: function(event, element) {

					var startTime = event.start_time;						

					if(event.end_time != ""){
						var time = "<strong>Start:</strong> " + startTime + " <strong>End:</strong> " + event.end_time;
					}else{
						var time = "All Day";
					}

					var pastEvent = event.isPastEvent ? " pastEvent" : "";

					if(event.isPastEvent) $(element).addClass('pastEvent');
					
					$(element).html('<div class="fc-content'+pastEvent+'"><span class="fc-title"><strong>Team:</strong> '+event.title+'</span><span class="fc-client"><strong>Customer:</strong> '+event.customer.name+'</span><span class="fc-date">'+time+'</span></div>');
				},
				eventClick: function(calEvent, jsEvent, view) {

					app.currentEventData.isEdit = 1;				

					var postData = {},
						originalEventObject = calEvent;
					
					var eventYear = calEvent.start._d.getFullYear(),
						eventMonth = ("0" + (calEvent.start._d.getMonth()+1)).slice(-2),
						eventDay = ("0" + calEvent.start._d.getDate()).slice(-2);

					postData.eventDate = eventYear+"-"+eventMonth+"-"+eventDay;
					postData.action = "saveEvent";
					postData.nonce = ajaxNonce;
					postData.members = calEvent.members;
					postData.id = calEvent.id;
					postData.title = calEvent.title;
					postData.team_id = calEvent.team_id;
					postData.isEdit = 1;

					var renderEventObject = {};

					/*** save to database via ajax ***/
					$.magnificPopup.open({
						items:{
							src:'#event-popup',
							type: 'inline'
						},
						callbacks:{
							beforeOpen: function() {
								
							    $('#popup-header').html('Additional Information');

							    var selectOptions = '';
							    for(i=0; i < objTeamMembers[calEvent.team_id].length;i++){							    	
							    	selectOptions += '<option value="'+objTeamMembers[calEvent.team_id][i].id+'" selected>'+objTeamMembers[calEvent.team_id][i].name+'</option>';
							    }

							    $('select#members').append(selectOptions).select2();
							    $('input#startTime').val(calEvent.start_time);
							    $('input#endTime').val(calEvent.end_time);
							    
							    $('select#vehicle').select2("val", calEvent.vehicle.ID);
							    $('select#customer').select2("val", originalEventObject.customer.ID);

							    if(calEvent.isPastEvent) {
							    	$('a#saveEvent').hide();
							    	$('a#deleteEvent').hide();
							    	$('a.popup-modal-dismiss').text('Close');
							    }else{
							    	$('a#saveEvent').show();
							    	$('a#deleteEvent').attr("rel", calEvent.id).show();
							    	$('a.popup-modal-dismiss').text('Cancel');
							    }
							},

							beforeClose: function(){

								$('a#deleteEvent').attr("rel", "").hide();

								if(app.currentEventData.isEdit == 1){

									if(app.currentEvents.length && app.currentEvents.hasOwnProperty(originalEventObject.eventDate)){
										alert('Event already exist for this team and date');
									}else{
										app.currentEvents[originalEventObject.eventDate] = [originalEventObject];
									}

									originalEventObject.start_time = app.currentEventData.start_time;
									originalEventObject.end_time = app.currentEventData.end_time;
									originalEventObject.vehicle = {ID:app.currentEventData.vehicleId, name:app.currentEventData.vehicleName};
									originalEventObject.customer = {ID:app.currentEventData.customerId, name: app.currentEventData.customerName};

									postData.start_time = app.currentEventData.start_time;
									postData.end_time = app.currentEventData.end_time;
									postData.vehicle = {ID:app.currentEventData.vehicleId, name:app.currentEventData.vehicleName};
									postData.customer = {ID:app.currentEventData.customerId, name: app.currentEventData.customerName};

									app.showLoader();

									app.doAjax(postData, function(response){
										app.hideLoader();
										if(response.success){
											renderEventObject = $.extend({}, originalEventObject);
											renderEventObject.start = calEvent.start;

											$('#calendar-future').fullCalendar('renderEvent', renderEventObject, true);
										}else{
											app.showNotification(response.message, true);
											return false;
										}
									});

									app.currentEventData = null;
									app.currentEventData = {isEdit:null};
										
								}

								
							},
							afterClose: function(){
								$('form#formEvent')[0].reset();
								$('select#customer').select2("val", "");
								$('select#vehicle').select2("val", "");
							}
						},
						modal:true
					});
			    },
			    eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ) { 

			    	var now = moment();
			    		today = moment(now).format('YYYY-MM-DD'),
			    		newDate = moment(event.start._d).format('YYYY-MM-DD');
			    	
			    	if(event.isPastEvent){
			    		revertFunc();
			    		return false;
			    	}

			    	if(newDate < today){
			    		app.showNotification('Can not move event to past date.', true);
			    		revertFunc();
			    		return false;
			    	}

			    	var postData = {
			    		action: "updateEvent",
			    		nonce: ajaxNonce,
			    		id: event.id,
			    		newDate: newDate
			    	};

			    	app.doAjax(postData, function(response){
			    		if(response.success){

			    		}else{
			    			app.showNotification(response.message, true);
			    			revertFunc();
			    			return false;
			    		}
			    	});
			    }

			});
		}
	},

	saveCalendar: function(){

		var app = this;

		$('body').on('click', 'a#saveEvent', function(e){
			e.preventDefault();

			var startTime = $('input#startTime').val(),
				endTime = $('input#endTime').val(),
				vehicleId = $('select#vehicle').select2('val'),
				vehicleName = $('select#vehicle').select2('data').text;
				customerId = $('select#customer').select2('val'),
				customerName = $('select#customer').select2('data').text;

			if($.trim(startTime).length == 0){
				app.showNotification('Please fill start time for this event', true);
				return false;
			}

			if($.trim(customerId).length == ""){
				app.showNotification('Please select customer/client', true);
				return false;
			}

			if($.trim(vehicleId).length == ""){
				app.showNotification('Please select vehicle to use', true);
				return false;
			}

			app.currentEventData.start_time = startTime;
			app.currentEventData.end_time = endTime;
			app.currentEventData.vehicleId = vehicleId;
			app.currentEventData.vehicleName = vehicleName;
			app.currentEventData.customerId = customerId;
			app.currentEventData.customerName = customerName;

			$.magnificPopup.close();

		});
	},

	deleteCalendar: function(){
		var app = this;

		$('body').on('click', 'a#deleteEvent', function(e){
			e.preventDefault();

			var id = $(this).attr("rel");

			var postData = {
				action: "deleteEvent",
				nonce: ajaxNonce,
				id:id
			};

			app.doAjax(postData, function(response){
				if(response.success){
					app.currentEventData.isEdit = null;
					$('#calendar-future').fullCalendar('removeEvents', id);
					$.magnificPopup.close();
				}else{
					app.showNotification(response.message, true);
				}
			});

		});
	},

	addEditCustomer: function(){
		var app = this;
	
		var options = { 
            target:'#notification', 
            url: ajaxURL.url,
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function(arr, form, options){
              
				var isEdit = $('input[name="isEdit"]').val(),
					company = $('input#company').val(),
					fullname = $('input#fullname').val(),
					email = $('input#email').val(),
					address = $('textarea#address').val(),
					phone  = $('input#phone').val(),
					customerType = $('select#customertype').select2("val"),
					error = 0;


				if($.trim(company).length == 0){
					app.showNotification('Please fill company name', true);
					return false;
				}

				if($.trim(fullname).length == 0){
					app.showNotification('Please fill customer full name', true);
					return false;
				}

				if($.trim(email).length == 0){
					app.showNotification('Please fill email address', true);
					return false;
				}else if(!app.isValidEmail(email)){
					app.showNotification('Please fill valid email format', true);
					return false;
				}

				if($.trim(address).length == 0){
					app.showNotification('Please fill customer address', true);
					return false;
				}

				if($.trim(phone).length == 0){
					app.showNotification('Please fill customer phone number', true);
					return false;
				}

				if($.trim(customerType).length == ""){
					app.showNotification('Please select customer type', true);
					return false;
				}else if(customerType == "monthly" && $.trim($('input#contractexp').val()).length == 0){
					app.showNotification('Please fill contract expiration date', true);
					return false;
				}
	            
				app.showLoader();
				
            },
            success:function(responseText, statusText, xhr, form){
                if(responseText.success){ 					
					app.hideLoader();
					
					app.showNotification(responseText.message, false, function(){
						window.location = responseText.returnURL;
					});
					
                }else{
					app.hideLoader();
                    app.showNotification(responseText.message, true);					
					return false;					
                }
            }
        };
		
		/*$('#addStaff').submit(function() { 
			$(this).ajaxSubmit(options); 
			return false;
		});*/

		$('button[type="submit"]').click(function(e){
			//e.preventDefault();
			var value = $(this).val();
			options.data = {redirectAction: value};

			$('#addCustomer').ajaxForm(options);

		});

		//customer type change
		if($('select#customertype').length){
			$('select#customertype').on('change', function(e){
				var value = $(this).val();

				if(value == "monthly"){
					$('#contract-exp').fadeIn(500);
				}else{
					$('#contract-exp').fadeOut(500);
				}				
			});
		}

		if($('select#customer').length){
			$('select#customer').on('change', function(e){
				var value = $(this).val();

				if(value != ""){
					app.getCustomerInfo(value);
				}else{
					$('#customer-detail').hide();
				}
			});
		}

		if($('input#totalprice').length){
			$('input#totalprice').on('keyup', function(e){
				var value = $(this).val(),
					gst = 0.07*value,
					pricegst = parseFloat(value)+parseFloat(gst);

				$('input#gst').val(accounting.formatNumber(gst,2,",","."));
				$('input#totalpricegst').val(accounting.formatNumber(pricegst,2,",","."));

			}).blur(function(){
				var value = $(this).val();
				$(this).val(accounting.formatNumber(value,2,",","."));
			}).focus(function(){
				var value = $(this).val();
				$(this).val(accounting.unformat(value));
			});
		}		

	},

	getCustomerInfo: function(customerId){

		var app = this,
			postData = {
				action:'getCustomerInfo',
				nonce:ajaxNonce,
				customerId:customerId
			};

		app.doAjax(postData, function(response){
			if(response.success){
				$('input#customertype').val(response.data.typeSlug);
				$('#customer-detail dl dd.type').html(response.data.type);
				$('#customer-detail dl dd.name').html(response.data.name);
				$('#customer-detail dl dd.contactname').html(response.data.contactname);
				$('#customer-detail dl dd.contactnumber').html(response.data.phone);
				$('#customer-detail dl dd.contactaddress').html(response.data.address);
				$('#customer-detail').show();
			}else{
				$('#customer-detail').hide();
				app.showNotification(response.message, true);
			}

		});

	},

	customerPagination: function(){
		var app = this;

		if($('table#customer-list').length){

			app.doCustomerPagination();
			
			$('body').on('click', '.pagination a', function(e){
				e.preventDefault();

				var rel = $(this).attr('rel').split('|');
				app.pagination.paged = parseInt(rel[0]);
				app.pagination.offset = parseInt(rel[1]);
				app.doCustomerPagination();
			});
		}

		if($('#customer-filter').length){

			$('#customer-filter').on('change', 'select', function(e){
				e.preventDefault();
				app.doCustomerPagination();
			});

			$('#customer-filter input[type="text"]').on('keyup', $.debounce(500, function(){

				app.doCustomerPagination();
			
			}));

			$('#customer-filter #reset-filter').on('click', function(e){
				e.preventDefault();

				$('#customer-filter input[type="text"]').val('');

				app.doCustomerPagination();
			})

		}
	},

	doCustomerPagination: function(){
		var app = this;

		var formData = {
			action: "ajaxPagination",
			nonce: ajaxNonce,
			paged: app.pagination.paged,
			perpage: 25,
			offset: app.pagination.offset,
			itemType: $('input[name="itemType"]').val(),
			keyword: $('input#keyword').val(),
			customerType: $('select#customertype').select2('val')
		};

		app.getPaginationData(ajaxURL.url, formData, function(response){
			$('table#customer-list tbody').html(response.dataBody).fadeIn('fast');
			$('table#customer-list tfoot tr td').html(response.dataPagination).fadeIn('fast');
		});
	},

	userPagination: function(){

		var app = this;

		if($('table#user-list').length){

			app.doUserPagination();
			
			$('body').on('click', '.pagination a', function(e){
				e.preventDefault();

				var rel = $(this).attr('rel').split('|');
				app.pagination.paged = parseInt(rel[0]);
				app.pagination.offset = parseInt(rel[1]);
				app.doUserPagination();
			});
		}

		if($('#user-filter').length){

			$('#user-filter').on('change', 'select', function(e){
				e.preventDefault();
				app.doUserPagination();
			});

			$('#user-filter input[type="text"]').on('keyup', $.debounce(500, function(){

				app.doUserPagination();
			
			}));

			$('#user-filter #reset-filter').on('click', function(e){
				e.preventDefault();

				$('#user-filter input[type="text"]').val('');

				app.doUserPagination();
			})

		}

	},

	doUserPagination: function(){
		var app = this;

		var formData = {
			action: "ajaxPagination",
			nonce: ajaxNonce,
			paged: app.pagination.paged,
			perpage: 25,
			offset: app.pagination.offset,
			itemType: $('input[name="itemType"]').val(),
			keyword: $('input#keyword').val(),
			role: $('select#role').select2('val'),
			position: $('select#position').select2('val'),
			status: $('select#status').select2('val')
		};

		app.getPaginationData(ajaxURL.url, formData, function(response){
			$('table#user-list tbody').html(response.dataBody).fadeIn('fast');
			$('table#user-list tfoot tr td').html(response.dataPagination).fadeIn('fast');
		});
	},

	getPaginationData: function(url, formData, callback){

		$('table tbody').fadeOut('fast', function(){
			$.post(url, formData, function(data, response, xhr) {
				if(typeof callback === "function"){
					callback(data);
				}
			});
		});

	},

	addEditStaff: function(){
		var app = this;
	
		var options = { 
            target:'#notification', 
            url: ajaxURL.url,
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function(arr, form, options){
              
				var isEdit = $('input[name="isEdit"]').val(),
					username = $('input#username').val(),
					email = $('input#email').val(),
					pass1 = $('input#pass1').val(),
					pass2 = $('input#pass2').val(),
					role = $('select#role').select2("val"),
					nric  = $('input#nric').val(),
					position = $('select#position').select2("val"),
					status = $('select#employment_status').val(),
					firstName = $('input#first_name').select2("val"),
					birthday = $('input#birthday').val(),
					address = $('input#address').val(),
					postalCode = $('input#postalcode').val(),
					phone = $('input#phone').val(),
					kin = $('input#kin').val(),
					error = 0;

				if(isEdit == 0){
					if($.trim(username).length == 0){
						app.showNotification('Please fill username', true);
						return false;
					}
				}				

				if($.trim(email).length == 0){
					app.showNotification('Please fill email address', true);
					return false;
				}else if(!app.isValidEmail(email)){
					app.showNotification('Please fill valid email format', true);
					return false;
				}

				if(isEdit == 0){

					if($.trim(pass1).length == 0){
						app.showNotification('Please fill password', true);
						return false;
					}

					if($.trim(pass2).length == 0){
						app.showNotification('Please repeat password', true);
						return false;
					}
				}

				if($.trim(pass1).length && $.trim(pass2).length){
					if(pass1 != pass2){
						app.showNotification('Password doesn\'t match', true);
						return false;
					}
				}

				if(role == ""){
					app.showNotification('Please select role', true);
					return false;
				}

				if(position == ""){
					app.showNotification('Please select position', true);
					return false;
				}

				if(status == ""){
					app.showNotification('Please select employment status', true);
					return false;
				}

				if($.trim(nric).length == 0){
					app.showNotification('Please fill NRIC/FIN No', true);
					return false;
				}

				if($.trim(firstName).length == 0){
					app.showNotification('Please fill first name', true);
					return false;
				}

				if($.trim(birthday).length == 0){
					app.showNotification('Please fill birthday', true);
					return false;
				}


				if($('input[name="gender"]:checked').length == 0){
					app.showNotification('Please select gender', true);
					return false;
				}			
	            
				app.showLoader();
				
            },
            success:function(responseText, statusText, xhr, form){
                if(responseText.success){ 					
					app.hideLoader();
					
					app.showNotification(responseText.message, false, function(){
						window.location = responseText.returnURL;
					});
					
                }else{
					app.hideLoader();
                    app.showNotification(responseText.message, true);					
					return false;					
                }
            }
        };
		
		/*$('#addStaff').submit(function() { 
			$(this).ajaxSubmit(options); 
			return false;
		});*/

		$('button[type="submit"]').click(function(e){
			//e.preventDefault();
			var value = $(this).val();
			options.data = {redirectAction: value};

			$('#addStaff').ajaxForm(options);

		});

	},

	updateProfile: function(){
		var app = this;
	
		var options = { 
            target:'#notification', 
            url: ajaxURL.url,
            type: 'POST',
            dataType: 'json',
            beforeSubmit: function(arr, form, options){
              
				var email = $('input#email').val(),
					pass1 = $('input#pass1').val(),
					pass2 = $('input#pass2').val(),
					nric  = $('input#nric').val(),
					firstName = $('input#first_name').val(),
					birthday = $('input#birthday').val(),
					address = $('input#address').val(),
					postalCode = $('input#postalcode').val(),
					phone = $('input#phone').val(),
					kin = $('input#kin').val(),
					error = 0;

				if($.trim(email).length == 0){
					app.showNotification('Please fill email address', true);
					return false;
				}else if(!app.isValidEmail(email)){
					app.showNotification('Please fill valied email format', true);
					return false;
				}

				if($.trim(nric).length == 0){
					app.showNotification('Please fill NRIC/FIN No', true);
					return false;
				}

				if($.trim(firstName).length == 0){
					app.showNotification('Please fill first name', true);
					return false;
				}

				if($.trim(birthday).length == 0){
					app.showNotification('Please fill birthday', true);
					return false;
				}

				if($.trim(address).length == 0){
					app.showNotification('Please fill address', true);
					return false;
				}

				if($('input[name="gender"]:checked').length == 0){
					app.showNotification('Please select gender', true);
					return false;
				}

				if($.trim(postalCode).length == 0){
					app.showNotification('Please postal code', true);
					return false;
				}

				if($.trim(phone).length == 0){
					app.showNotification('Please phone', true);
					return false;
				}

				if($.trim(kin).length == 0){
					app.showNotification('Please next of kin', true);
					return false;
				}

				if($.trim(pass1).length){
					if(pass1 != pass2){
						app.showNotification('Password doesn\'t match', true);
						return false;
					}
				}
	            
				app.showLoader();
				
            },
            success:function(responseText, statusText, xhr, form){
                if(responseText.success){ 					
					app.hideLoader();
					
					app.showNotification(responseText.message, false, function(){
						window.location = baseURL + '/profile/';
					});
					
                }else{
					app.hideLoader();
                    app.showNotification(responseText.message, true);					
					return false;					
                }
            }
        };
		
		$('#profile').submit(function() { 
			$(this).ajaxSubmit(options); 
			return false;
		});
	},

	deleteItem: function(){

		var app = this;

		$('body').on('click', '.btn-delete', function(e){
			e.preventDefault();

			app.showLoader();

			var _self = $(this),
				rel = $(this).attr('rel').split("|"),
				itemId = rel[0];
				itemType = rel[1];

			var postData = {
				action: 'deleteItem',
				nonce: ajaxNonce,
				itemId: itemId,
				itemType: itemType
			};

			app.doAjax(postData, function(response){
				app.hideLoader();

				if(response.success){
					_self.parent('td').parent('tr.row-'+itemId).fadeOut(500, function(){$(this).remove();});
				}else{
					app.showNotification(response.message, true);
				}
			});

		});

	},

	deleteItems: function(){

		var app = this;

		if($('#btn-delete-all').length){
			$('body').on('click', '#btn-delete-all', function(e){
				e.preventDefault();

				var _self = $(this),
					itemType = _self.attr('rel'),
					checkedItems = $('input.check-item:checked');

				if(checkedItems.length == 0){
					app.showNotification('Please check one or more item to delete', true);
					return false;
				}

				var checkedItemsIds = [];

				$(checkedItems).each(function(i,a){
					checkedItemsIds[i] = $(this).val();
				});

				app.showLoader();

				var postData = {
					"action": 'deleteItem',
					"nonce": ajaxNonce,
					"itemId[]": checkedItemsIds,
					"itemType": itemType
				};

				app.doAjax(postData, function(response){
					app.hideLoader();

					if(response.success){

						for(i = 0; i < response.deletedIds.length;i++){
							$('table').find('tr.row-'+response.deletedIds[i]).fadeOut(200, function(){$(this).remove();});
						}
						
					}else{
						app.showNotification(response.message, true);
					}
				});

			});
		}
	},

	checkboxAll: function(){

		var app = this;

		if($('#checkall').length){

			$('body').on('change', '#checkall', function(e){

				var _self = this;

				if($(_self).is(':checked')){					
					$('.check-item').each(function(i,a){
						$(this).prop("checked", _self.checked);
					});
				}else{
					$('.check-item').each(function(){
						$(this).prop("checked", _self.checked);
					});
				}
			});

			$('body').on('change', '.check-item', function(e){

				if($(this).is(':checked')){

					var totalCheckbox = $('.check-item').length,
						selectedCheckbox = $('input.check-item:checked').length;

					if(totalCheckbox == selectedCheckbox){
						$('#checkall').prop('checked', true);
					}

				}else{
					$('#checkall').prop("checked", this.checked);
				}
			});
		}
	},
	
	createDatepicker: function(){

		if($('.datepicker-past').length){
			$('.datepicker-past').datepicker({
				dateFormat: 'dd-mm-yy',
				maxDate: 0,
				changeMonth: true,
	      		changeYear: true,
	      		yearRange: '1940:1996'
			});
		}

		if($('.datepicker-future').length){
			$('.datepicker-future').datepicker({
				dateFormat: 'dd-mm-yy',
				minDate: 0,
				changeMonth: true,
	      		changeYear: true,
	      		yearRange: '2014:2020'
			});
		}

		if($('.timepicker').length){
			$('.timepicker').timepicker({
				controlType: 'select'
			});
		}
		
	},

	customDropdown: function(){
		if($('select.standard-dropdown').length){
			$('select.standard-dropdown').each(function(){
				var placeholder = $(this).data('placeholder');
				$(this).select2({
		            placeholder: placeholder,
		            allowClear: true
		        });
			});
		}

		if($('select.multiple-dropdown').length){
			$('select.multiple-dropdown').each(function(){
				var placeholder = $(this).data('placeholder');
				$(this).select2({
		            placeholder: placeholder,
		            allowClear: true
		        });
			});
		}
	},

	closePopup: function(){

		var app = this;

		$(document).on('click', '.popup-modal-dismiss', function (e) {
          e.preventDefault();
          app.currentEventData.isEdit = null;
          $.magnificPopup.close();
        });
	},
	
	doAjax: function(postData, callback){
        $.ajax({
            type : "post",
            dataType : "json",
            url : ajaxURL.url,
            data : postData,
            success: callback,
            error: function(jqXHR, textStatus, error){
                console.log(textStatus);
            }
        });
    },

	showNotification: function(message, isError, callback){
        var isError = isError || false;
        var status = isError ? "error" : "success";
        
        $('#notification').addClass(status);
        
        $('#notification p').html(message);
        $('#notification').slideDown(500, function(){
            setTimeout(function(){
                $('#notification').slideUp(500, function(){
                    $('#notification').removeClass(status);
                    if(typeof callback !== "undefined") callback();
                });
            }, 1500);
        });
    },
    showLoader: function(){
        $("#loader").show();
    },

    hideLoader: function(){
        $("#loader").hide();
    },

    isValidEmail: function(email){
    	var rxEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
        if (rxEmail.test(email) == false) {
            return false;
        }
        return true;
    }

};

$(document).ready(function(){
	goldenHarvest.init();
});