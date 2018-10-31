<link href='assets/css/fullcalendar.css' rel='stylesheet' />
<script src='assets/js/moment.min.js'></script>
<script src='assets/js/fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {
		$('#dialog_new_meeting').modal({ show: false});
		$('#dialog_view_meeting').modal({ show: false});
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			//defaultDate: '2014-06-12',
			defaultDate: $('#calendar').fullCalendar('getDate'),
			firstDay: 1,
			selectHelper: true,

			/*** Future: allow users to add events via clicking on calendar
			selectable: true,
			select: function(start, end) {
				var title = prompt('Event Title:');
				var eventData;
				if (title) {
					eventData = {
						title: title,
						start: start,
						end: end
					};
					$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
					$.ajax({
						url: '/meetings/addEventMeeting',  //server script to process data
						type: 'POST',
						async: true,
						data: {t:title, s:start, e:end},
						success: function(result) {
							console.log("event added");
						}
					});

					console.log(title);
				}
				$('#calendar').fullCalendar('unselect');
			},
			*/
			selectable: true,
			select: function(start, end) {
				console.log(start + " <> " + end);
				$("#date_start").val(start.format());
				$("#date_end").val(end.format());
				$( "#dialog_new_meeting" ).modal( "show" );



				/*
				var title = prompt('Event Title:');
				var eventData;
				if (title) {
					eventData = {
						title: title,
						start: start,
						end: end
					};
					$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
					$.ajax({
						url: '/meetings/addEventMeeting',  //server script to process data
						type: 'POST',
						async: true,
						data: {t:title, s:start, e:end},
						success: function(result) {
							console.log("event added");
						}
					});

					console.log(title);
				}
				$('#calendar').fullCalendar('unselect');
				*/

			},

			//http://arshaw.com/fullcalendar/docs/event_ui/eventDrop/
			//allow users to edit events by drag-n-drop or by resizing
			editable: true,
			eventResize: function(event, delta, revertFunc) {
				//alert(event.title + " end is now " + event.end.format());
				$.ajax({
					url: '/meetings/editEventMeeting',  //server script to process data
					type: 'POST',
					async: true,
					data: {i:$(this).attr("data-id"), s:event.start.format(), e:event.end.format(), act:"save"},
					success: function(result) {
						if(result === '1'){
							//alert("OK");
							$('<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">x</a><strong>Well done!</strong> Successfully updated meeting schedule.</div>').insertBefore("#calendar");
							setTimeout(function(){ $(".alert-success").fadeOut("slow"); }, 2500);
						}else{
							$('<div class="alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">x</a><strong>Oops!</strong> Failed to update meeting schedule.</div>').insertBefore("#calendar");
							setTimeout(function(){ $(".alert-danger").fadeOut("slow"); }, 2500);
						}
					}
				});
			},
			eventDrop: function(event, delta, revertFunc) {
				//alert(event.title + " was dropped on " + event.start.format() + " <> " + event.start + " <> " + event.end + " <> " + $(this).attr("data-id"));
				$.ajax({
					url: '/meetings/editEventMeeting',  //server script to process data
					type: 'POST',
					async: true,
					data: {i:$(this).attr("data-id"), s:event.start.format(), e:event.end.format(), act:"save"},
					success: function(result) {
						if(result === '1'){
							$('<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">x</a><strong>Well done!</strong> Successfully updated meeting schedule.</div>').insertBefore("#calendar");
							setTimeout(function(){ $(".alert-success").fadeOut("slow"); }, 2500);
						}else{
							$('<div class="alert alert-danger"><a class="close" data-dismiss="alert" href="#" aria-hidden="true">x</a><strong>Oops!</strong> Failed to update meeting schedule.</div>').insertBefore("#calendar");
							setTimeout(function(){ $(".alert-danger").fadeOut("slow"); }, 2500);
						}
					}
				});
			},

			//render data-id into each "<div class="fc-event " tag. Only way to pass parameter
			eventAfterRender:function( event, element, view ) {
				$(element).attr("data-id",event._id);
	            //element.find('.fc-event-title').append("<br/>" + event.description);
			},

			//$events is fetched via PHP from the modal general/getCalendarEvents()
			events: [ <?php echo $events;?> ],
			eventClick: function(event){
				$.ajax({
					url: '/meetings/viewEventMeeting',
					type: 'GET',
					aync: true,
					data: {i:$(this).attr("data-id")},
					success: function(result){
						if(result != '0'){
							var jsonObj = $.parseJSON(result);
							$("#viewMeetingModalLabel").text(jsonObj.subject);
							console.log(jsonObj);
							$("#dialog_view_meeting .modal-body").html(jsonObj.body);
							$( "#dialog_view_meeting" ).modal( "show" );

						}
					}
				});

			}

		});

	});

</script>
<style>
	#calendar {
		width: 900px;
		margin: 40px auto;
	}
	#calendarLegend{
		text-align: center;
	}
	#calendarLegend div{
		text-align: left;
		display: inline-block;
		border: 1px solid #CECECE;
		padding: 5px 15px;
		font-size: 10px;
	}
	#calendarLegend i{
		margin-right: 5px;
	}
</style>

  <div class="row">

    <div class="col-md-12">

      <div class="table-responsive">

          	<div id='calendar'></div>
			<div id="calendarLegend">
				<div>
					<i class="fa fa-square" style="color: #43AD3A;"></i>Meeting <i class="fa fa-square" style="color: #3A87AD; margin-left: 20px;"></i>Task
				</div>
			</div>

      </div> <!-- /.table-responsive -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

<br/><br/>


<!-- Modal -->
<div class="modal fade" id="dialog_view_meeting" tabindex="-1" role="dialog" aria-labelledby="viewMeetingModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="viewMeetingModalLabel"></h4>
			</div>
			<div class="modal-body">

			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="dialog_new_meeting" tabindex="-1" role="dialog" aria-labelledby="newMeetingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="newMeetingModalLabel">New Meeting</h4>
      </div>
      <div class="modal-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#new_meeting" role="tab" data-toggle="tab">Add Meeting</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div class="tab-pane active" id="new_meeting">

			<?php echo form_open('meetings/add', array ('class' => 'form parsley-form')); ?>

					 <div class="row">
						<div class="form-group col-sm-6">
						<label for="subject">Subject</label>
						<input type="text" name="subject" id="subject" class="form-control"  placeholder="Enter subject">
						</div>
						<div class="form-group col-sm-6">
						<label for="phone_fax">Assign Users</label>
<?php 	 echo form_dropdown('assigned_user_id', $assignedusers, $_SESSION['user']->id,"class='form-control'"); ?>
						</div>

					</div>
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="company_id">Company</label>
							<input type="text" name="company_viewer" id="company_viewer" class="form-control"/>
							<input type="hidden" name="company_id" id="company_id" />
						</div>
						<div class="form-group col-sm-6">
							<label for="people_id">Person</label>
							<input type="text" name="person_viewer" id="person_viewer" class="form-control"/>
							<input type="hidden" name="people_id" id="person_id" />
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="location">Location</label>
							<input type="text" name="location" id="location" class="form-control"  placeholder="Enter location">
						</div>
						<div class="form-group col-sm-6">
							<label for="event_type">Event Type</label>
							<?php echo form_dropdown('event_type', $event_types, '',"class='form-control' id='event_type'"); ?>						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="date_start">Start Date</label>
							<input type="text" name="date_start" id="date_start" class="form-control datetime"  placeholder="Enter start date">
						</div>
						<div class="form-group col-sm-6">
							<label for="date_end">End Date</label>
							<input type="text" name="date_end" id="date_end" class="form-control datetime"  placeholder="Enter end date">
						</div>
					</div>


					<div class="row">
						<div class="form-group col-sm-6">
							<label for="description">Comments/Description</label>
							<textarea name="description" id="description" class="form-control" rows="5"  placeholder="Enter comments/descriptions"></textarea>
						</div>
						<!--<div class="form-group col-sm-6">
							<label for="email2">Address 2</label>
							<textarea name="address2" id="address2" class="form-control"  placeholder="Enter address 2"></textarea>
						</div>-->
					</div>

					<div class="form-actions">
					<button type="submit" class="btn btn-primary">Save</button>
					<input type = "button"  class="btn btn-default" value = "Cancel" data-dismiss="modal" >
				  </div>
				  <input type="hidden" name="act" value="save">

				</form>


			</div>


		</div>


      </div>
    </div>
  </div>
</div>
<style>
/* modal autocomplete box fix z-index */
.ui-widget-content{
	z-index: 111111;
}
</style>
<script>
    delete_one_Meeting=function( meeting_id ){
        Messi.ask('Do you really want to delete the meeting?', function(val) {
            if( val == 'Y' ){
                window.location.href="<?php echo site_url('meetings/delete')?>/" + meeting_id;
            }
        }, {modal: true, title: 'Confirm Delete'});
    }
 
     delete_one_Task=function( task_id ){
        Messi.ask('Do you really want to delete the task?', function(val) {
            if( val == 'Y' ){
                window.location.href="<?php echo site_url('tasks/delete')?>/" + task_id;
            }
        }, {modal: true, title: 'Confirm Delete'});
    }
       
	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd1").validate({
			rules: {
				subject: "required",
				location: "required",
				date_start: "required",
				date_end: "required"
			},
			messages: {
				subject: "Enter subject",
				location: "Enter meeting location",
				date_start: "Enter start date",
				date_end: "Enter end date"
			},
			errorPlacement: function(error, element) {
		        error.insertAfter(element.parent().find('label:first'));
			},
			errorElement: 'em'
		});

		var validator2 = jQuery("#frmadd2").validate({
			rules: {
				subject: "required",
				priority_id: "required",
				status_id: "required"
			},
			messages: {
				subject: "Enter name",
				priority_id: "Select priority",
				status_id: "Select status"
			},
			errorPlacement: function(error, element) {
		        error.insertAfter(element.parent().find('label:first'));
			},
			errorElement: 'em'
		});

		var validator3 = jQuery("#frmadd3").validate({
			rules: {
				subject: "required",
				priority_id: "required",
				status_id: "required"
			},
			messages: {
				subject: "Enter name",
				priority_id: "Select priority",
				status_id: "Select status"
			},
			errorPlacement: function(error, element) {
		        error.insertAfter(element.parent().find('label:first'));
			},
			errorElement: 'em'
		});

		var num_tasks = 1;
		$("#add_more_tasks").on("click", function(){
			//add new field only if there were no empty taks fields
			for(var i=0; i<=num_tasks; i++){
				if($.trim($("#task"+i).val()) == "") return false;
			}


			num_tasks += 1;
			var task_count = num_tasks + 1;
			$("<div class='form-group col-sm-10' style='margin-bottom: 5px;'><div class='input-group'><div class='input-group-addon'>" + task_count + "</div><input type='text' name='tasks[]' id='task" + num_tasks + "' class='form-control'  placeholder=''></div></div>").insertBefore( $("#add_more_tasks").parent() );
			console.log(num_tasks + " <> " + task_count);

		});


		//autocomplete for companies
		$( "#company_viewer" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/ajax/accountsAutocomplete",
					dataType: "json",
					data: {
						q: request.term
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			minLength: 3,
			select: function( event, ui ) {
				console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
				//$(this.id).parent().find("#company_id").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});

		//autocomplete for persons
		$( "#people_viewer" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/ajax/personsAutocomplete",
					dataType: "json",
					data: {
						q: request.term
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			minLength: 3,
			select: function( event, ui ) {
				console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
				$("#person_id").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});

		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y H:i',
			mask: true
		});
	});
</script>