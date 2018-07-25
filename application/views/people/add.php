<h2 class="portlet-title">Add New Person</h2>
 <?php
    echo validation_errors('<div class="alert alert-danger">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>','</div> <!-- /.alert -->');
 ?>
<form name="frmadd" id="frmadd" action="<?php echo site_url('people/add')?>" method="post" class="form parsley-form">
    <div class="panel-group accordion-panel" id="accordion-paneled">
        <div class="panel panel-default open">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Basic Info</a></h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse in" id="collapseOne">
                <div class="panel-body">

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter first name"
								   value="<?php echo set_value('first_name'); ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter last name"
								   value="<?php echo set_value('last_name'); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="company">Company</label>
                            <?php
                                if(isset($company)) {
                                    $form_entry = set_value('company_viewer', $company->company_name);
                                    $onchange = 'onchange="wipecompanyid()"';
                                } else {
                                    $form_entry = set_value('company_viewer');
                                    $onchange = '';
                                }
                            ?>
							<input type="text" name="company_viewer" id="company_viewer" placeholder="Start typing here" class="form-control"
                                   value="<?php echo $form_entry;?>" <?php echo $onchange; ?>>
							<input type="hidden" name="company" id="company" value="<?php if(isset($company)) echo $company->company_id;?>">

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="job_title">Job Title</label>
                            <input type="text" name="job_title" id="job_title" class="form-control" placeholder="Enter job title"
                                   value="<?php echo set_value('job_title'); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="assigned_user_id">Assigned User</label>
							<?php
                                $selected_option = ($this->input->post('assigned_user_id')) ? $this->input->post('assigned_user_id') : $_SESSION['user']['uacc_uid'];
                                echo form_dropdown('assigned_user_id', $assignedusers1, $selected_option,"class='form-control' id='assigned_user_id'");
                            ?>
                        </div>
						<div class="form-group col-sm-6">
							<label for="lead_source_id">Lead Source</label>
							<?php
							    if(isset($company)) {
                                    $selected_option = ($this->input->post('lead_source_id')) ? $this->input->post('lead_source_id') : $company->lead_source_id;
                                    echo form_dropdown('lead_source_id', $lead_sources, $selected_option,"class='form-control'");
                                } else {
                                    $selected_option = ($this->input->post('lead_source_id')) ? $this->input->post('lead_source_id') : $person->lead_source_id;
                                    echo form_dropdown('lead_source_id', $lead_sources, $selected_option,"class='form-control'");
                                }
							?>
						</div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="phone_work">Phone Work</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('phone_work', $company->phone_work);
                            } else {
                                $form_entry = set_value('phone_work');
                            }
                            ?>
                            <input type="text" name="phone_work" id="phone_work" class="form-control" placeholder="Enter work phone" value="<?php echo $form_entry;?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="phone_home">Phone Home</label>
                            <input type="text" name="phone_home" id="phone_home" class="form-control" placeholder="Enter home phone"
                                   value="<?php echo set_value('phone_home');?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="phone_mobile">Phone Mobile</label>
                            <input type="text" name="phone_mobile" id="phone_mobile" class="form-control" placeholder="Enter mobile no" value="<?php echo set_value('phone_mobile');?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="birthdate">Birthdate</label>
                            <input type="text" name="birthdate" id="birthdate" class="form-control datetime" placeholder="Enter birth date" value="<?php echo set_value('birthdate');?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="email1">Email (main)</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('email1', $company->email1);
                            } else {
                                $form_entry = set_value('email1');
                            }
                            ?>
                            <input type="text" name="email1" id="email1" class="form-control" placeholder="Enter email 1" value="<?php echo $form_entry;?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="email2">Email (backup)</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('email2', $company->email2);
                            } else {
                                $form_entry = set_value('email2');
                            }
                            ?>
                            <input type="text" name="email2" id="email2" class="form-control" placeholder="Enter email 2" value="<?php echo $form_entry;?>">
                        </div>
                    </div>
					<div class="row">
                         <div class="form-group col-sm-6">
                             <label for="contact_type">Contact Type</label>
                             <?php
                                $selected_option = ($this->input->post('contact_type')) ? $this->input->post('contact_type') : '92';
                                echo form_dropdown('contact_type', $contact_type, $selected_option,"class='form-control' id='contact_type'");
                             ?>
                         </div>
                    </div>
                </div>
                <!-- /.panel-collapse -->
            </div>
            <!-- /.panel -->
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse"	href="#collapseTwo">Address Info</a></h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse" id="collapseTwo">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="address1">Address 1</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('address1', $company->address1);
                            } else {
                                $form_entry = set_value('address1');
                            }
                            ?>
                            <textarea name="address1" id="address1" class="form-control" placeholder="Enter address 1"><?php echo $form_entry;?></textarea>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="address2">Address 2</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('address2', $company->address2);
                            } else {
                                $form_entry = set_value('address2');
                            }
                            ?>
                            <textarea name="address2" id="address2" class="form-control" placeholder="Enter address 2"><?php echo $form_entry;?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="city">City</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('city', $company->city);
                            } else {
                                $form_entry = set_value('city');
                            }
                            ?>
                            <input type="text" name="city" id="city" class="form-control" placeholder="Enter city" value="<?php echo $form_entry;?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="province">Province</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('province', $company->province);
                            } else {
                                $form_entry = set_value('province');
                            }
                            ?>
                            <input type="text" name="province" id="province" class="form-control" placeholder="Enter province" value="<?php echo $form_entry;?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="postal_code">Postal Code</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('postal_code', $company->postal_code);
                            } else {
                                $form_entry = set_value('postal_code');
                            }
                            ?>
                            <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="Enter postal code" maxlength="10" value="<?php echo $form_entry;?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="country">Country</label>
                            <?php
                            if(isset($company)) {
                                $form_entry = set_value('country', $company->country);
                            } else {
                                $form_entry = set_value('country');
                            }
                            ?>
                            <input type="text" name="country" id="country" class="form-control" placeholder="Enter country name" value="<?php echo $form_entry;?>">
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
                	<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseThree">Other Info</a></h4>

            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse" id="collapseThree">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="city">Do Not Call</label>
                            <div class="controls">
                                <label class="radio">
                                    <input type="radio" name="do_not_call" id="do_not_call_1"
                                           value="Y" <?php echo set_radio('do_not_call', 'Y') ?>>Yes
                                </label>
                                <div style="clear:both"></div>
                                <label class="radio">
                                    <input type="radio" name="do_not_call" id="do_not_call_2"
                                           value="N" <?php echo set_radio('do_not_call', 'N', TRUE) ?>>No
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="province">Email Opt Out</label>
                            <div class="controls">
                                <label class="radio">
                                    <input type="radio" name="email_opt_out" id="email_opt_out_1"
                                           value="Y" <?php echo set_radio('email_opt_out', 'Y') ?>>Yes
                                </label>
                                <div style="clear:both"></div>
                                <label class="radio">
                                    <input type="radio" name="email_opt_out" id="email_opt_out_2"
                                           value="N" <?php echo set_radio('email_opt_out', 'N', TRUE) ?>>No
                                </label>
                        </div>
                    </div>
					 </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="description">Comments/Description</label>
                            <?php
                            /*if(isset($company)) {
                                $form_entry = set_value('description', $company->description);
                            } else {
                                $form_entry = set_value('description');
                            }*/
                            ?>
                            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter comments/descriptions"><?php echo set_value('description');?></textarea>
                        </div>
                    </div>

					<!--//custom-field    -->
                    <div class="row">
                        <?php
                        if ($is_custom_fields == 1)
                        {
                            $i = 1;
                            $custom_field_company = $_SESSION['custom_field']['119'];
                            foreach($custom_field_company as $custom)
                                {
                                    if( $i == 2)
                                    { ?>
										<div class="form-group col-sm-6">

                                            <label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label'];?></label>

                                            <?php if($custom['cf_type'] == "Textbox") {
                                                $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : ''; ?>
												<input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                                       id="<?php echo $custom['cf_name']; ?>"
                                                       value="<?php echo $form_entry; ?>">
                                            <?php }
                                            else if($custom['cf_type'] == "Dropdown") {
												$dropval = eval('return $'.$custom['cf_name'].';');
                                                $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : '';
                                                echo form_dropdown($custom['cf_name'], $dropval, $selected_option,"class='form-control' id='".$custom['cf_name']."'");
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

										    <?php if($custom['cf_type'] == "Textbox"){
                                                $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : ''; ?>
										        <input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                                       id="<?php echo $custom['cf_name']; ?>"
                                                       value="<?php echo $form_entry; ?>">
										    <?php }
										    else if($custom['cf_type'] == "Dropdown"){
										        $dropval = eval('return $'.$custom['cf_name'].';');
                                                $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : '';
										        echo form_dropdown($custom['cf_name'], $dropval, $selected_option,"class='form-control' id='".$custom['cf_name']."'");
                                            }?>
										</div>

										<?php
										$i++;
                                    }
                                }
                        }?>
                    <!--//custom-field    -->
					</div>
                <!-- /.panel-body -->
                </div>
            <!-- /.panel-collapse -->
            </div>
        <!-- /.panel -->
        </div>
    <!-- /.accordion -->
	</div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
    </div>
    <input type="hidden" name="act" value="save">
	<input type="hidden" name="is_company" id="is_company" value="">
</form>
<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('people')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd").validate({
			ignore: "",
			rules: {
				first_name: "required",
				last_name: "required",
			},
			messages: {
			  	first_name: "Enter first name",
				last_name: "Enter last name",
			},
			errorPlacement: function(error, element) {
		        error.insertAfter(element.parent().find('label:first'));
			},
            submitHandler: function(form) {
                checkaccount(form);
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
    function checkaccount(form) {
		var companyname = $('#company_viewer').val();
		var accountid = $('#company').val();
		var accountnamebold = companyname.bold();
		if (companyname != "") {
			if (accountid == "") {
                getcompanyid(form, companyname, accountnamebold);
			} else {
                form.submit();
			}
		} else {
            form.submit();
		}
	}

    function wipecompanyid() {
        $('#company').val('');
    }

    function getcompanyid(form, companyname, accountnamebold) {
        console.log('company name: ' + companyname);
        $.ajax({
            url: "/ajax/getCompanyIdByName",
            data: {
                q: companyname
            },
            success: function(data) {
                createorsavecompany(data, form, accountnamebold);
            }
        })
    }

    function createorsavecompany(companyid, form, accountnamebold){
        if(companyid == '') {
            Messi.ask('Do you want to Create Company ' + accountnamebold + ' for this Person?', function (val) {
                if (val == 'Y') {
                    $('#is_company').val('1');
                    form.submit();
                }
                if (val == 'N') {
                    $('#is_company').val('0');
                    form.submit();
                }
            }, {
                modal: true,
                title: 'Confirm Create'
            });
        } else {
            $("#company").val(companyid);
            form.submit();
        }
    }

</script>