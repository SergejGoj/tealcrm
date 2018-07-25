<style>
  .ui-autocomplete-loading {
    background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
  }
</style>

        <form name="frmedit" id="frmedit" action="<?php echo site_url('people/edit/' . $contact->contact_id)?>" method="post" class="form parsley-form">
				<h2 class="portlet-title">Edit Contact: <?php echo $contact->first_name;?> <?php echo $contact->last_name;?></h2>

				<div class="panel-group accordion-panel" id="accordion-paneled">
					<div class="panel panel-default open">
						<div class="panel-heading">
							<h4 class="panel-title"><a class="accordion-toggle"	data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Basic Info</a></h4>
						</div><!-- /.panel-heading -->

						<div class="panel-collapse collapse in" id="collapseOne">
							<div class="panel-body">

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="first_name">First Name</label> <input class="form-control" id="first_name" name="first_name" placeholder="Enter first name" type="text" value="<?php echo $contact->first_name?>">
									</div>

									<div class="form-group col-sm-6">
										<label for="last_name">Last Name</label> <input class="form-control" id="last_name" name="last_name" placeholder="Enter last name" type="text" value="<?php echo $contact->last_name?>">
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="company">Company</label>
										<input type="text" name="company_viewer" id="company_viewer" value="<?php echo $contact->company_name;?>" class="form-control"/>
										<input type="hidden" name="company" id="company" value="<?php echo $contact->company;?>" />

									</div>

									<div class="form-group col-sm-6">
										<label for="job_title">Job Title</label> <input class="form-control" id="job_title" name="job_title" placeholder="Enter job title" type="text" value="<?php echo $contact->job_title?>">
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="assigned_user_id">Assigned User</label>
								    	<?php echo form_dropdown('assigned_user_id', $assignedusers, $contact->assigned_user_id, "class='form-control' id='assigned_user_id'"); ?>
									</div>
									<div class="form-group col-sm-6">
										<label for="lead_source_id">Lead Source</label>
										<!--<input class="form-control" id="lead_source_id" name="lead_source_id" placeholder="Enter lead source" type="text" value="<?php echo $contact->lead_source_id?>">-->
										<?php echo form_dropdown('lead_source_id', $lead_sources, $contact->lead_source_id,"class='form-control'"); ?>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="phone_work">Phone Work</label> <input class="form-control" id="phone_work" name="phone_work" placeholder="Enter work phone" type="text" value="<?php echo $contact->phone_work?>">
									</div>

									<div class="form-group col-sm-6">
										<label for="phone_home">Phone Home</label> <input class="form-control" id="phone_home" name="phone_home" placeholder="Enter home phone" type="text" value="<?php echo $contact->phone_home?>">
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="phone_mobile">Phone Mobile</label> <input class="form-control" id="phone_mobile" name="phone_mobile" placeholder="Enter mobile no" type="text" value="<?php echo $contact->phone_mobile?>">
									</div>

									<div class="form-group col-sm-6">
										<label for="phone_fax">Birthdate</label> <input class="form-control datetime" id="birthdate" name="birthdate" placeholder="Expected dob" type="text" value="<?php echo date('m/d/Y', strtotime($contact->birthdate))?>">
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="email1">Email 1</label>
										<input class="form-control" id="email1" name="email1" placeholder="Enter email 1" type="text" value="<?php echo $contact->email1?>">
									</div>

									<div class="form-group col-sm-6">
										<label for="email2">Email 2</label>
										<input class="form-control" id="email2" name="email2" placeholder="Enter email 2" type="text" value="<?php echo $contact->email2?>">
									</div>
								</div><!--/row-->
							</div><!-- /.panel-collapse -->
						</div><!-- /.panel -->
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse"	href="#collapseTwo">Address Info</a></h4>
						</div><!-- /.panel-heading -->

						<div class="panel-collapse collapse" id="collapseTwo">
							<div class="panel-body">
								<div class="row">
									<div class="form-group col-sm-6">
										<label for="address1">Address 1</label>
										<input name="address1" type="text" value="<?php echo $contact->address1;?>" class="form-control" id="street-w1" placeholder="Street name">
									</div>

									<div class="form-group col-sm-6">
										<label for="address2">Address 2</label>
										<input name="address2" type="text" value="<?php echo $contact->address2;?>" class="form-control" id="street-w1" placeholder="Street name">
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="city">City</label>
										<input class="form-control" id="city" name="city" placeholder="Enter city" type="text" value="<?php echo $contact->city?>">
									</div>

									<div class="form-group col-sm-6">
										<label for="province">Province</label>
										<input class="form-control" id="province" name="province" placeholder="Enter province" type="text" value="<?php echo $contact->province?>">
									</div>
								</div>

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="postal_code">Postal Code</label>
										<input class="form-control" id="postal_code" name="postal_code" placeholder="Enter postal code" type="text" value="<?php echo $contact->postal_code?>">
									</div>

									<div class="form-group col-sm-6">
										<label for="country">Country</label>
										<input class="form-control" id="country" name="country" placeholder="Enter country name" type="text" value="<?php echo $contact->country?>">
									</div>
								</div>
							</div><!-- /.panel-body -->
						</div><!-- /.panel-collapse -->
					</div><!-- /.panel -->

					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseThree">Other Info</a></h4>
						</div><!-- /.panel-heading -->

						<div class="panel-collapse collapse" id="collapseThree">
							<div class="panel-body">

								<div class="row">
									<div class="form-group col-sm-6">
										<label for="city">Do Not Call</label>

										<div class="controls">
											<label class="radio"><input id="do_not_call_1" name="do_not_call" type="radio" value="Y" <?php if( $contact->do_not_call == 'Y'):?>checked="checked"<?php endif;?>> Yes</label>

											<div style="clear:both"></div>

											<label class="radio"><input id="do_not_call_2" name="do_not_call" type="radio" value="N" <?php if( $contact->do_not_call == 'N'):?>checked="checked"<?php endif;?>> No</label>
										</div>
									</div>

									<div class="form-group col-sm-6">
										<label for="province">Email Opt Out</label>

										<div class="controls">
											<label class="radio"><input id="email_opt_out_1" name="email_opt_out" type="radio" value="Y"<?php if( $contact->email_opt_out == 'Y'):?>checked="checked"<?php endif;?>> Yes</label>

											<div style="clear:both"></div>

											<label class="radio"><input id="email_opt_out_2" name="email_opt_out" type="radio" value="N"<?php if( $contact->email_opt_out == 'N'):?>checked="checked"<?php endif;?>> No</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="description">Comments/Description</label>
									<textarea class="form-control" id="description" name="description" placeholder="Enter comments/descriptions" rows="5"><?php echo $contact->description?>
									</textarea>
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
	cancel = function(elm) {
		window.location.href = '<?php echo site_url('contacts')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function() {
		var validator = jQuery("#frmedit").validate({
			ignore: "",
			rules: {
				//lead_source_id: "required",
				job_title: "required",
				last_name: "required",
				email1: {
					required: true,
					email: true
				}/*,
				email2: {
					required: false,
					email: true
				},
				postal_code: "required",
				country: "required"*/
			},
			messages: {
				//lead_source_id: "Enter lead source",
				job_title: "Enter job",
				last_name: "Enter name",
				email1: {
					required: "Enter email address",
					email: "Enter a valid email address i.e. me@somewhere.com"
				}/*,
				email2: {
					email: "Enter a valid email address i.e. me@somewhere.com"
				},
				postal_code: "Enter valid postal code",
				country: "Select country"*/
			},
			errorPlacement: function(error, element) {
				error.insertAfter(element.parent().find('label:first'));
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
				$("#company").val(ui.item.id);
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