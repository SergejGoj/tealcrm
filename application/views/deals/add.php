<h2 class="portlet-title">Add New Deal</h2>
<form name="frmadd" id="frmadd" action="<?php echo site_url('deals/add')?>" method="post" class="form parsley-form">
	 <?php

echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>','</div> <!-- /.alert -->');
           ?>
    <div class="panel-group accordion-panel" id="accordion-paneled">
        <div class="panel panel-default open">
            <div class="panel-heading">
                	<h4 class="panel-title"><a class="accordion-toggle"	data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Deal Info</a></h4>

            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse in" id="collapseOne">
                <div class="panel-body">

					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" name="name" id="name" class="form-control"  placeholder="Enter full name">
					</div>

					 <div class="row">
					 <div class="form-group col-sm-6">
						<label for="phone_fax">Assign Users</label>
						<?php echo form_dropdown('assigned_user_id', $assignedusers1, $_SESSION['user']['uacc_uid'], "class='form-control' id='assigned_user_id'"); ?>
					</div>
					<div class="form-group col-sm-6">
						<label for="phone_fax">Close Date</label>
						<input type="text" name="expected_close_date" id="expected_close_date" class="form-control datetime" placeholder="Expected close date">
					</div>
				   </div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="company_id">Company</label>
							<input type="text" name="company_viewer" placeholder="Start typing here" id="company_viewer" class="form-control" value="<?php if(isset($company)) echo $company->company_name;?>" />
							<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($company_id)) echo $company_id;?>" />

						</div>
						<div class="form-group col-sm-6">
							<label for="value">Value</label>
							<input type="text" name="value"  id="value"  class="form-control" placeholder="Enter value">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="people_id">Person</label>
							<input type="text" name="person_viewer" placeholder="Start typing here" id="person_viewer" class="form-control" value="<?php if(isset($person)) echo $person->first_name.' '.$person->last_name;?>" />
							<input type="hidden" name="people_id" id="people_id" value="<?php if(isset($people_id)) echo $people_id;?>" />
						</div>
						<div class="form-group col-sm-6">
							<label for="probability">Probability</label>
							<input type="text" name="probability" id="probability" class="form-control" placeholder="Enter probability(numeric)">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label for="sales_stage_id">Sales Stage Id</label>
							<!--<input type="text" name="sales_stage_id" id="sales_stage_id" class="form-control"  placeholder="Enter sales stage(numeric)">-->
					    	<?php echo form_dropdown('sales_stage_id', $sales_stage, '',"class='form-control' id='sales_stage_id'"); ?>

						</div>
						<div class="form-group col-sm-6">
							<label for="next_step">Next Step</label>
							<input type="text" name="next_step" id="next_step" class="form-control" placeholder="Enter next step">
						</div>
					</div>

					  <div class="row">
                        <div class="form-group col-sm-12">
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
										$custom_field_company = $_SESSION['custom_field']['120'];
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
		window.location.href = '<?php echo site_url('deals')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd").validate({
			ignore: "",
			rules: {
				name: "required",
				value: {required: true, number: true},
				expected_close_date: "required",
			},
			messages: {
				name: "Enter name",
				value: {required: "Enter value", number: "Enter number"},
				expected_close_date: "Enter expected close date",
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

		//autocomplete for peoples
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

		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y',
			mask: true,
			timepicker: false
		});
	});
</script>