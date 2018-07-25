<link rel="stylesheet" href="uploadfiles/css/jquery.fileupload.css">
<form action="<?php echo site_url('notes/edit/' . $note->note_id)?>" class="form parsley-form" id="frmedit" method="post" enctype="multipart/form-data" name="frmedit">
	<h2 class="portlet-title">Edit Note: <?php echo $note->subject?></h2>
	 <?php

echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>','</div> <!-- /.alert -->');
           ?>
	<div class="panel-group accordion-panel" id="accordion-paneled">
		<div class="panel panel-default open">
			<div class="panel-heading">
				<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Note Info</a></h4>
			</div><!-- /.panel-heading -->

			<div class="panel-collapse collapse in" id="collapseOne">
				<div class="panel-body">

					<div class="form-group">
						<label for="subject">Subject</label>
						<input type="text" name="subject" id="subject" class="form-control"  placeholder="Enter full name" value="<?php echo $note->subject?>">
					</div>

					<div class="row">
					  <div class="form-group col-sm-6">
						<label for="phone_fax">Assign Users</label>
					  	<?php echo form_dropdown('assigned_user_id', $assignedusers1, $note->assigned_user_id, "class='form-control' id='assigned_user_id'"); ?>

					 </div>
					  <div class="form-group col-sm-6">

						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="company_id">Company</label>
							<input type="text" name="company_viewer" placeholder="Start typing here" id="company_viewer" value="<?php echo $company_name;?>" class="form-control"/>
							<input type="hidden" name="company_id" id="company_id" value="<?php echo $note->company_id;?>" />
						</div>
						<div class="form-group col-sm-6">
							<label for="people_id">Person</label>
							<input type="text" name="person_viewer" placeholder="Start typing here" id="person_viewer" class="form-control" value="<?php echo $person_name;?>"/>
							<input type="hidden" name="people_id" id="people_id" value="<?php echo $note->people_id?>" />
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-12">
							<label for="description">Comments/Description</label>
							<textarea class="form-control" id="description" name="description" placeholder="Enter comments/descriptions" rows="5"><?php echo $note->description?></textarea>
						</div>
					</div>

					<?php if($note->filename_original != ''){ ?>
					<div class="row">
						<div class="form-group col-sm-6">
							<i class="fa fa-paperclip"></i>
							<a href="<?php echo site_url('download?q=' . $note->filename_original . '&type=' . $note->filename_mimetype); ?>" target="_blank"><?php echo $note->filename_original;?></a>
						</div>
					</div>
					<?php } ?>


					<!---- custom_field_start ----->
						<div class="row">
										<?php
										if ($is_custom_fields == 1)
										{
										$i = 1;
										$custom_field_company = $_SESSION['custom_field']['121'];
										foreach($custom_field_company as $custom)
										{
										$file_name = $custom['cf_name'];

										if( $i == 2)
										{
										$dropname = eval('return $'.$custom['cf_name'].';');

										?>

											<div class="form-group col-sm-6">

												<label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label'];?></label>
													<?php if($custom['cf_type'] == "Textbox") {?>
												<input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control" id="<?php echo $custom['cf_name']; ?>" value="<?php echo $dropname ?>">
												<?php }
												else if($custom['cf_type'] == "Dropdown") {
												$dropval = eval('return $custom_'.$custom['cf_name'].';');
												 echo form_dropdown($custom['cf_name'], $dropname, $dropval,"class='form-control' id='".$custom['cf_name']."'");
										 }?>
										</div>

										</div>
										<div class="row">

										<?php
										$i = 1;
										}
										else
										{

										//if ($custom_field->$file_name != "")
										{ $dropname = eval('return $'.$custom['cf_name'].';');
										?>
										<div class="form-group col-sm-6">

										<label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>

										<?php if($custom['cf_type'] == "Textbox") {?>
										<input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control" id="<?php echo $custom['cf_name']; ?>" value="<?php echo $dropname; ?>">
										<?php }
												else if($custom['cf_type'] == "Dropdown") {
												$dropval = eval('return $custom_'.$custom['cf_name'].';');
												 echo form_dropdown($custom['cf_name'], $dropname, $dropval,"class='form-control' id='".$custom['cf_name']."'");
										 }?>
										</div>

										<?php
										}
										$i++;
										}

										}
										}?>

					</div>

						<!--------- custom_field_start ------------>


					<div class="row">
						<div class="form-group col-sm-6">

							<!-- The fileinput-button span is used to style the file input field as button -->
							<span class="btn btn-success fileinput-button">
								<i class="glyphicon glyphicon-plus"></i>
								<span>Attach file...</span>
								<!-- The file input field used as target for the file upload widget -->
								<input id="attach_file" type="file" name="attach_file">
								<input type="hidden" name="note_attach_valid" id="note_attach_valid" value="0" />
							</span><span id="file_name_display"></span>
							<br>
							<br>
							<?php if($note->filename_original != ''){ ?>
							<strong>Warning!</strong> This will overwrite any attachments you added before.
							<?php } ?>

						</div>
					</div>




				</div><!-- /.panel-body -->
			</div><!-- /.panel-collapse -->
		</div><!-- /.panel -->
	</div><!-- /.accordion -->

	<div class="form-actions">
		<button class="btn btn-primary" type="submit">Save</button>
		<button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
	</div>
	<input name="act" type="hidden" value="save">
</form>

<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('notes')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmedit").validate({
			ignore: "",
			rules: {
				subject: "required"
			},
			messages: {
				subject: "Enter subject"

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
		//autocomplete for task
		$( "#project_viewer" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/ajax/tasksAutocomplete",
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

		//autocomplete for persons
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


		$("#attach_file").change(function (e) {
			$("#note_attach_valid").val('1');
			var filename = $(this).val();
			var lastIndex = filename.lastIndexOf("\\");
			if (lastIndex >= 0) {
				filename = filename.substring(lastIndex + 1);
			}
			$('#file_name_display').text(filename);
		});
	});
</script>