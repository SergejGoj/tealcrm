<h2 class="portlet-title">Add New Task</h2>
	 <?php

echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>','</div> <!-- /.alert -->');
           ?>
<form name="frmadd" id="frmadd" action="<?php echo site_url('tasks/add')?>" method="post" class="form parsley-form">
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
					<div class="form-group">
							<label for="project_id">Project</label>
							<input type="text" name="project_viewer" id="project_viewer" placeholder="Start typing here" class="form-control" value="<?php if(isset($project)) echo $project->project_name;?>"/>
							<input type="hidden" name="project_id" id="project_id" value="<?php if(isset($project_id)) echo $project_id;?>" />
						</div>
					 <div class="row">
						<div class="form-group col-sm-6">
						<label for="phone_fax">Assign Users</label>
<?php 	 echo form_dropdown('assigned_user_id', $assignedusers1, $_SESSION['user']['uacc_uid'],"class='form-control'"); ?>
						</div>
						<div class="form-group col-sm-6">
							<label for="due_date">Due Date</label>
							<input type="text" name="due_date" id="due_date" class="form-control datetime"  placeholder="Enter due date">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="company_id">Company</label>
							<input type="text" name="company_viewer" id="company_viewer" placeholder="Start typing here" class="form-control" value="<?php if(isset($company)) echo $company->company_name;?>"/>
							<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($company_id)) echo $company_id;?>" />
						</div>
						<div class="form-group col-sm-6">
							<label for="people_id">Person</label>
							<input type="text" name="person_viewer" id="person_viewer" placeholder="Start typing here" class="form-control" value="<?php if(isset($person)) echo $person->first_name.' '.$person->last_name;?>"/>
							<input type="hidden" name="people_id" id="people_id" value="<?php if(isset($people_id)) echo $people_id;?>" />
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="priority_id">Priority</label>
					    	<?php echo form_dropdown('priority_id', $priority_ids, '',"id='priority_id' class='form-control'"); ?>
						</div>
						<div class="form-group col-sm-6">
							<label for="status_id">Status</label>
					    	<?php echo form_dropdown('status_id', $status_ids, '',"id='status_id' class='form-control'"); ?>
						</div>
					</div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="description">Comments/Description</label>
                            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter comments/descriptions"></textarea>
                        </div>
                    </div>

					<!--//custom-field    -->

                   <div class="row">
										<?php
										if ($is_custom_fields == 1)
										{
										$i = 1;
										$custom_field_company = $_SESSION['custom_field']['123'];
										foreach($custom_field_company as $custom)
										{
										if( $i == 2)
										{ ?>
											<div class="form-group col-sm-6">

												<label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label'];?></label>

												<?php if($custom['cf_type'] == "Textbox") {?>

												<input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control" id="<?php echo $custom['cf_name']; ?>" value="">
												<?php }
												else if($custom['cf_type'] == "Dropdown") {
												$dropval = eval('return $'.$custom['cf_name'].';');
												 echo form_dropdown($custom['cf_name'], $dropval, '',"class='form-control' id='".$custom['cf_name']."'");
										 }?>
										</div>
										</div>
										<div class="row">

										<?php
										$i = 1;
										}
										else
										{	?>
										<div class="form-group col-sm-6">

										<label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>

										<?php if($custom['cf_type'] == "Textbox"){?>
										<input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control" id="<?php echo $custom['cf_name']; ?>" value="">
										<?php }
										else if($custom['cf_type'] == "Dropdown"){
										$dropval = eval('return $'.$custom['cf_name'].';');
										 echo form_dropdown($custom['cf_name'], $dropval, '',"class='form-control' id='".$custom['cf_name']."'");
										 }?>
										</div>

										<?php
										$i++;
										}

										}
										}?>

					</div>

                            <!--//custom-field    -->


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
	cancel=function(elm){
		window.location.href = '<?php echo site_url('tasks')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd").validate({
			ignore: "",
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
			invalidHandler: function(form, validator) {
				//manually highlight the main accordion
				$(".panel").removeClass("is-open");

				//manually close all accordions except collapseOne
				$(".panel-collapse").each(function(e){
					if( $(this).attr("id") != "collapseOne" )
						$(this).removeClass('in');
				});

				//manually highlight the header of collapseOne
				if( $("#collapseOne").parent().hasClass("is-open") == false )
					$('#collapseOne').parent().addClass('is-open');

				//check if collapseOne is open or not, if not then open it
				if( $("#collapseOne").hasClass("in") == false )
					$('#collapseOne').collapse('show');
			},
			errorElement: 'em'
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

		//autocomplete for projects
		$( "#project_viewer" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/ajax/projectsAutocomplete",
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
				$("#project_id").val(ui.item.id);
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