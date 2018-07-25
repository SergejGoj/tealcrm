<link rel="stylesheet" href="uploadfiles/css/jquery.fileupload.css">
<div class="row">

  <div >

    <div class="portlet">

      <h2 class="portlet-title">
        Add New Note
      </h2>
	 <?php

echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>','</div> <!-- /.alert -->');
           ?>
      <div class="portlet-body">

        <form name="frmadd" id="frmadd" action="<?php echo site_url('notes/add')?>" method="post" enctype="multipart/form-data" class="form parsley-form">

  			<div class="form-group">
				<label for="subject">Subject</label>
				<input type="text" name="subject" id="subject" class="form-control"  placeholder="Enter subject">
			</div>

			 <div class="row">
			   <div class="form-group col-sm-6">
				<label for="phone_fax">Assign Users</label>
<?php 	 echo form_dropdown('assigned_user_id', $assignedusers1, $_SESSION['user']['uacc_uid'],"class='form-control'"); ?>
			  </div>
			   <div class="form-group col-sm-6">


				</div>
   		    </div>

			<div class="row">
				<div class="form-group col-sm-6">
					<label for="company_id">Company</label>
					<input type="text" name="company_viewer" id="company_viewer" placeholder="Start typing here" class="form-control"  value="<?php if(isset($company)) echo $company->company_name;?>"/>
					<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($company)) echo $company->company_id;?>"/>

				</div>
				<div class="form-group col-sm-6">
					<label for="people_id">Person</label>
					<input type="text" name="person_viewer" id="person_viewer" placeholder="Start typing here" class="form-control"value="<?php if(isset($person)) echo $person->first_name.' '.$person->last_name;?>"/>
					<input type="hidden" name="people_id" id="people_id" value="<?php if(isset($people_id)) echo $people_id;?>"/>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-12">
					<label for="description">Comments/Description</label>
					<textarea name="description" id="description" class="form-control" rows="5"  placeholder="Enter comments/descriptions"></textarea>
				</div>
			</div>

			<!--//custom-field    -->

                   <div class="row">
										<?php
										if ($is_custom_fields == 1)
										{
										$i = 1;
										$custom_field_company = $_SESSION['custom_field']['121'];
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
					<!-- The global progress bar
					<div id="progress" class="progress">
						<div class="progress-bar progress-bar-success"></div>
					</div>-->

				</div>
			</div>




			<div class="form-actions">
				<input type="hidden" id="file_attachment" name="file_attachment">
				<button type="submit" id="submit_btn" class="btn btn-primary">Save</button>
				<button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
			</div>
			<input type="hidden" name="act" value="save">
        </form>

      </div> <!-- /.portlet-body -->

    </div> <!-- /.portlet -->

  </div> <!-- /.col -->

</div> <!-- /.row -->
<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('notes')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){

		var invalidFieldsCount = 0;
		var validator = jQuery("#frmadd").validate({
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
