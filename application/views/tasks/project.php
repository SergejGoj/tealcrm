<h2 class="portlet-title">Add Project Tasks</h2>
<form name="frmadd" id="frmadd" action="<?php echo site_url('tasks/project')?>" method="post" class="form parsley-form">
    <div class="panel-group accordion-panel" id="accordion-paneled">
        <div class="panel panel-default open">
            <div class="panel-heading">
                	<h4 class="panel-title"><a class="accordion-toggle"	data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Task Info</a></h4>

            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse in" id="collapseOne">
                <div class="panel-body">

					<div class="form-group">
						<label for="subject">Subject</label>
						<input type="text" name="subject" id="subject" class="form-control"  placeholder="Enter subject">
					</div>
					<label for="task1">Task Checklist</label>
					<div class="row" id="tasks_box">
						<div class="form-group col-sm-10" style="margin-bottom: 5px;">
							<div class="input-group">
								<div class="input-group-addon">1</div>
								<input type="text" name="tasks[]" id="task0" class="form-control"  placeholder="">
							</div>
						</div>
					<!--	<div class="form-group col-sm-10" style="margin-bottom: 5px;">
							<input type="hidden" name="tasks_list" id="tasks_list">
							<div class="input-group">
								<div class="input-group-addon">2</div>
								<input type="text" name="tasks[]" id="task1" class="form-control"  placeholder="">
							</div>
						</div> -->
						<div class="form-group col-sm-2">
							<button type="button" id="add_more_tasks" class="form-control btn btn-default">Add More</button>
						</div>
					</div>

					 <div class="row">
						<div class="form-group col-sm-6">
						<label for="phone_fax">Assign Users</label>
							<?php 	 echo form_dropdown('assigned_user_id', $assignedusers, $_SESSION['user']['id'],"class='form-control'"); ?>
						</div>
						<div class="form-group col-sm-6">
							<label for="due_date">Due Date</label>
							<input type="text" name="due_date" id="due_date" class="form-control datetime"  placeholder="Enter due date">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="company_id">Company</label>
							<input type="text" name="company_viewer" id="company_viewer" class="form-control"/>
							<input type="hidden" name="company_id" id="company_id" />
							<!--<input type="text" name="company_id" id="company_id" class="form-control"  placeholder="Enter company no">-->
					    	<?php //echo form_dropdown('company_id', $account_names, '',"class='form-control' id='company_id'"); ?>
						</div>
						<div class="form-group col-sm-6">
							<label for="company_id">Person</label>
							<!--<input type="text" name="people_id"  id="people_id"  class="form-control" placeholder="Enter person no">-->
							<input type="text" name="person_viewer" id="person_viewer" class="form-control"/>
							<input type="hidden" name="people_id" id="people_id" />
					    	<?php //echo form_dropdown('people_id', $person_names, '',"class='form-control' id='people_id'"); ?>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="priority_id">Priority</label>
							<?php echo form_dropdown('priority_id', $priority_ids, '',"id='priority_id' class='form-control'"); ?>
						</div>
						<div class="form-group col-sm-6">
							<label for="status_id">Status</label>
							<?php echo form_dropdown('status_id', $status_ids, '100',"id='status_id' class='form-control'"); ?>
						</div>
					</div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel-collapse -->
        </div>
        <!-- /.panel -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseTwo">Other Info</a></h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse" id="collapseTwo">
                <div class="panel-body form-group">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="description">Comments/Description</label>
                            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter comments/descriptions"></textarea>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel-collapse -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.accordion -->
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
        <button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
    </div>
    <input type="hidden" name="act" value="save">
</form>
<script type="text/javascript">
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
				$("#company_id").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});

		//autocomplete for people
		$( "#person_viewer" ).autocomplete({
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
				$("#people_id").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
		//autocomplete for people
		$( "#deal_viewer" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/ajax/dealsAutocomplete",
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
				$("#deal_id").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});
	cancel=function(elm){
		window.location.href = '<?php echo site_url('tasks')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd").validate({
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

		var num_tasks = 0;
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

		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y H:i',
			mask: true
		});
	});
</script>