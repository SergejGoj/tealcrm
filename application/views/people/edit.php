<style>
    .ui-autocomplete-loading {
        background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
    }
</style>

<h2 class="portlet-title">
    <u>Edit Person: <?php echo $person->first_name; ?> <?php echo $person->last_name; ?></u>
</h2>

<?php
echo validation_errors('<div class="alert alert-danger">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>', '</div> <!-- /.alert -->');
?>

<form name="frmedit" id="frmedit" action="<?php echo site_url('people/edit/' . $person->people_id) ?>" method="post"
      class="form parsley-form">

    <div class="panel-group accordion-panel" id="accordion-paneled">
        <div class="panel panel-default open">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled"
                                           data-toggle="collapse" href="#collapseOne">Basic Info</a></h4>
            </div><!-- /.panel-heading -->

            <div class="panel-collapse collapse in" id="collapseOne">
                <div class="panel-body">

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="first_name">First Name</label> <input class="form-control" id="first_name" name="first_name"
                                                                              placeholder="Enter first name" type="text"
                                                                              value="<?php echo set_value('first_name', $person->first_name); ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="last_name">Last Name</label> <input class="form-control" id="last_name" name="last_name"
                                                                            placeholder="Enter last name" type="text"
                                                                            value="<?php echo set_value('last_name', $person->last_name); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="company">Company</label>
                            <?php
                                if(($person->company_name != NULL) && ($person->company_id != NULL)) {
                                    $onchange = 'onchange="wipecompanyid()"';
                                } else {
                                    $onchange = '';
                                }
                            ?>
                            <input type="text" name="company_viewer" id="company_viewer" placeholder="Start typing here" class="form-control"
                                   value="<?php echo set_value('company_viewer', $person->company_name); ?>" <?php echo $onchange; ?>>
                            <input type="hidden" name="company" id="company"
                                   value="<?php echo set_value('company_viewer', $person->company_id); ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="job_title">Job Title</label> <input class="form-control" id="job_title" name="job_title"
                                                                            placeholder="Enter job title" type="text"
                                                                            value="<?php echo set_value('job_title', $person->job_title); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="assigned_user_id">Assigned User</label>
                            <?php
                                $selected_option = ($this->input->post('assigned_user_id')) ? $this->input->post('assigned_user_id') : $person->assigned_user_id;
                                echo form_dropdown('assigned_user_id', $assignedusers1, $selected_option, "class='form-control' id='assigned_user_id'");
                            ?>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lead_source_id">Lead Source</label>
                            <?php
                                $selected_option = ($this->input->post('lead_source_id')) ? $this->input->post('lead_source_id') : $person->lead_source_id;
                                echo form_dropdown('lead_source_id', $lead_sources, $selected_option, "class='form-control'");
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="phone_work">Phone Work</label> <input class="form-control" id="phone_work" name="phone_work"
                                                                              placeholder="Enter work phone" type="text"
                                                                              value="<?php echo set_value('phone_work', $person->phone_work); ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="phone_home">Phone Home</label> <input class="form-control" id="phone_home" name="phone_home"
                                                                              placeholder="Enter home phone" type="text"
                                                                              value="<?php echo set_value('phone_home', $person->phone_home); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="phone_mobile">Phone Mobile</label> <input class="form-control" id="phone_mobile" name="phone_mobile"
                                                                                  placeholder="Enter mobile no" type="text"
                                                                                  value="<?php echo set_value('phone_mobile', $person->phone_mobile); ?>">
                        </div>
                        <?php
                        if ($person->birthdate == null) { ?>
                            <div class="form-group col-sm-6">
                                <label for="birthdate">Birthdate</label> <input class="form-control datetime" id="birthdate" name="birthdate"
                                                                                placeholder="Date of birth" type="text"
                                                                                value="<?php echo set_value('birthdate', "Not set"); ?>">
                            </div>
                        <?php } else { ?>
                            <div class="form-group col-sm-6">
                                <label for="phone_fax">Birthdate</label> <input class="form-control datetime" id="birthdate" name="birthdate"
                                                                                placeholder="Expected dob" type="text"
                                                                                value="<?php echo set_value('birthdate', date('m/d/Y', strtotime($person->birthdate))); ?>">
                            </div>
                        <?php }
                        ?>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="email1">Email (main)</label>
                            <input class="form-control" id="email1" name="email1" placeholder="Enter email 1"
                                   type="text" value="<?php echo set_value('email1', $person->email1); ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="email2">Email (backup)</label>
                            <input class="form-control" id="email2" name="email2" placeholder="Enter email 2"
                                   type="text" value="<?php echo set_value('email2', $person->email2); ?>">
                        </div>
                    </div><!--/row-->
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="contact_type">Contact Type</label>
                            <?php
                                $selected_option = ($this->input->post('contact_type')) ? $this->input->post('contact_type') : $person->contact_type;
                                echo form_dropdown('contact_type', $contact_type, $selected_option, "class='form-control' id='contact_type'");
                            ?>
                        </div>
                    </div>
                </div><!-- /.panel-collapse -->
            </div><!-- /.panel -->
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled"
                                           data-toggle="collapse" href="#collapseTwo">Address Info</a></h4>
            </div><!-- /.panel-heading -->

            <div class="panel-collapse collapse" id="collapseTwo">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="address1">Address 1</label>
                            <input name="address1" type="text" value="<?php echo set_value('address1', $person->address1); ?>"
                                   class="form-control" id="street-w1" placeholder="Street name">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="address2">Address 2</label>
                            <input name="address2" type="text" value="<?php echo set_value('address2', $person->address2); ?>"
                                   class="form-control" id="street-w1" placeholder="Street name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="city">City</label>
                            <input class="form-control" id="city" name="city" placeholder="Enter city" type="text"
                                   value="<?php echo set_value('city', $person->city) ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="province">Province</label>
                            <input class="form-control" id="province" name="province" placeholder="Enter province"
                                   type="text" value="<?php echo set_value('province', $person->province); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="postal_code">Postal Code</label>
                            <input class="form-control" id="postal_code" name="postal_code"
                                   placeholder="Enter postal code" type="text"
                                   value="<?php echo set_value('postal_code', $person->postal_code); ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="country">Country</label>
                            <input class="form-control" id="country" name="country" placeholder="Enter country name"
                                   type="text" value="<?php echo set_value('country', $person->country); ?>">
                        </div>
                    </div>
                </div><!-- /.panel-body -->
            </div><!-- /.panel-collapse -->
        </div><!-- /.panel -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled"
                                           data-toggle="collapse" href="#collapseThree">Other Info</a></h4>
            </div><!-- /.panel-heading -->

            <div class="panel-collapse collapse" id="collapseThree">
                <div class="panel-body">

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="city">Do Not Call</label>

                            <div class="controls">
                                <label class="radio"><input id="do_not_call_1" name="do_not_call" type="radio"
                                                            value="Y" <?php echo set_radio('do_not_call', 'Y', ($person->do_not_call == 'Y')) ?>>Yes
                                </label>

                                <div style="clear:both"></div>

                                <label class="radio"><input id="do_not_call_2" name="do_not_call" type="radio"
                                                            value="N" <?php echo set_radio('do_not_call', 'N', ($person->do_not_call == 'N')); ?>>No
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="province">Email Opt Out</label>

                            <div class="controls">
                                <label class="radio"><input id="email_opt_out_1" name="email_opt_out" type="radio"
                                                            value="Y" <?php echo set_radio('email_opt_out', 'Y', $person->email_opt_out == 'Y'); ?>>Yes
                                </label>

                                <div style="clear:both"></div>

                                <label class="radio"><input id="email_opt_out_2" name="email_opt_out" type="radio"
                                                            value="N" <?php echo set_radio('email_opt_out', 'N', $person->email_opt_out == 'N'); ?>>No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Comments/Description</label>
                        <textarea class="form-control" id="description" name="description"
                                  placeholder="Enter comments/descriptions"
                                  rows="5"><?php echo set_value('description', $person->description); ?></textarea>
                    </div>

                    <!---- custom_field_start ----->
                    <div class="row">
                        <?php
                        if ($is_custom_fields == 1)
                        {
                        $i = 1;
                        $custom_field_company = $_SESSION['custom_field']['119'];
                        foreach ($custom_field_company as $custom)
                        {
                        $file_name = $custom['cf_name'];

                        if ($i == 2)
                        {
                        $dropname = eval('return $' . $custom['cf_name'] . ';');

                        ?>

                        <div class="form-group col-sm-6">

                            <label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>
                            <?php if ($custom['cf_type'] == "Textbox") {
                                $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropname; ?>
                                <input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                       id="<?php echo $custom['cf_name']; ?>"
                                       value="<?php echo $dropname ?>">
                            <?php } else if ($custom['cf_type'] == "Dropdown") {
                                $dropval = eval('return $custom_' . $custom['cf_name'] . ';');
                                $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropval;
                                echo form_dropdown($custom['cf_name'], $dropname, $selected_option, "class='form-control' id='" . $custom['cf_name'] . "'");
                            } ?>
                        </div>

                    </div>
                    <div class="row">

                        <?php
                        $i = 1;
                        }
                        else {

                            //if ($custom_field->$file_name != "")
                            //{
                                $dropname = eval('return $' . $custom['cf_name'] . ';');
                                ?>
                                <div class="form-group col-sm-6">

                                    <label
                                        for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>

                                    <?php if ($custom['cf_type'] == "Textbox") {
                                        $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropname; ?>
                                        <input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                               id="<?php echo $custom['cf_name']; ?>" value="<?php echo $form_entry; ?>">
                                    <?php } else if ($custom['cf_type'] == "Dropdown") {
                                        $dropval = eval('return $custom_' . $custom['cf_name'] . ';');
                                        $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropval;
                                        echo form_dropdown($custom['cf_name'], $dropname, $selected_option, "class='form-control' id='" . $custom['cf_name'] . "'");
                                    } ?>
                                </div>

                                <?php
                            //}
                            $i++;
                        }

                        }
                        } ?>

                    </div>

                    <!--------- custom_field_start ------------>


                </div><!-- /.panel-body -->
            </div><!-- /.panel-collapse -->
        </div><!-- /.panel -->
    </div><!-- /.accordion -->

    <div class="form-actions">
        <button class="btn btn-primary" type="submit">Save</button>
        <button class="btn btn-default" type="button" onclick="return cancel(this)">Cancel</button>
    </div>
    <input name="act" type="hidden" value="save">
    <input type="hidden" name="is_company" id="is_company" value="">
</form>
<script type="text/javascript">
    cancel = function (elm) {
        window.location.href = '<?php echo site_url('people')?>';
        return false;
    }

    // document ready
    jQuery(document).ready(function () {
        var validator = jQuery("#frmedit").validate({
            ignore: "",
            rules: {
                first_name: "required",
                last_name: "required",
            },
            messages: {
                first_name: "Enter first name",
                last_name: "Enter last name",
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent().find('label:first'));
            },
            submitHandler: function(form) {
                checkaccount(form);
            },
            invalidHandler: function (form, validator) {
                //manually highlight the main accordion
                $(".panel").removeClass("is-open");

                //manually close all accordions except collapseOne
                $(".panel-collapse").each(function (e) {
                    if ($(this).attr("id") != "collapseOne")
                        $(this).removeClass('in');
                });

                //manually highlight the header of collapseOne
                if ($("#collapseOne").parent().hasClass("is-open") == false)
                    $('#collapseOne').parent().addClass('is-open');

                //check if collapseOne is open or not, if not then open it
                if ($("#collapseOne").hasClass("in") == false)
                    $('#collapseOne').collapse('show');
            },
            errorElement: 'em'
        });

        $(document).ready(function () {
            $("#company").ajaxChosen({
                method: 'GET',
                url: '/ajax/accountsAutocomplete',
                dataType: 'json'
            }, function (data) {
                var terms = {};
                console.log(data);
                $.each(data, function (i, val) {
                    terms[i] = ({value: val.name, text: val.label});
                    ;
                });

                return terms;
            });
        });


        //autocomplete for companies
        $("#company_viewer").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "/ajax/accountsAutocomplete",
                    dataType: "json",
                    data: {
                        q: request.term
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 3,
            select: function (event, ui) {
                console.log(ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
                $("#company").val(ui.item.id);
            },
            open: function () {
                $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
            },
            close: function () {
                $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            }
        });

        // picker
        jQuery('.datetime').datetimepicker({
            format: 'm/d/Y',
            mask: true,
            timepicker: false,
        });


    });


    function checkaccount(form) {

        var companyname = $('#company_viewer').val();
        var acccountid = $('#company').val();
        var accountnamebold = companyname.bold();
        console.log('COmpany name:' + companyname);
        if (companyname != "") {
            if (acccountid == "") {
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
        });
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