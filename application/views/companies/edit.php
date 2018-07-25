<h3 class="content-title">Edit Company: <?php echo $company->company_name; ?></h3>

<?php

echo validation_errors('<div class="alert alert-danger">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>', '</div> <!-- /.alert -->');

$attributes = array('id' => 'frmedit', 'name' => 'frmprofile');

echo form_open('companies/edit/' . $company->company_id, $attributes);

?>


<div class="panel-group accordion-panel" id="accordion-paneled">

    <div class="panel panel-default open">

        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-paneled" href="#collapseOne">Basic Info</a>
            </h4>
        </div> <!-- /.panel-heading -->

        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="company">Company/Company Name</label>
                        <input name="company" type="text" class="form-control" id="company" placeholder="Company name"
                               value="<?php echo set_value('company', $company->company_name); ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="assigned_user_id">Assigned User</label>
                        <?php
                            $selected_option = ($this->input->post('assigned_user_id')) ? $this->input->post('assigned_user_id') : $company->assigned_user_id;
                            echo form_dropdown('assigned_user_id', $assignedusers1, $selected_option, "class='form-control' id='assigned_user_id'");
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="phone_work">Work Phone</label>
                        <input name="phone_work" type="text" class="form-control" id="email1"
                               value="<?php echo set_value('phone_work', $company->phone_work); ?>" placeholder="Work phone">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="company_type">Company Type</label>
                        <?php
                            $selected_option = ($this->input->post('company_type')) ? $this->input->post('company_type') : $company->company_type;
                            echo form_dropdown('company_type', $company_types, $selected_option, "class='form-control' id='company_type'");
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="email1">Email 1</label>
                        <input name="email1" type="text" class="form-control" id="email1"
                               value="<?php echo set_value('email1', $company->email1); ?>" placeholder="Primary Email">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="email2">Email 2</label>
                        <input name="email2" type="text" class="form-control" id="email2"
                               value="<?php echo set_value('email2', $company->email2); ?>" placeholder="Secondary Email">
                    </div>
                </div>

            </div> <!-- /.panel-body -->
        </div> <!-- /.panel-collapse -->

    </div> <!-- /.panel -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-paneled" href="#collapseTwo">Address Info</a>
            </h4>
        </div> <!-- /.panel-heading -->

        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="address1">Address 1</label>
                        <input name="address1" type="text" value="<?php echo set_value('address1', $company->address1); ?>"
                               class="form-control" id="address1" placeholder="Street name">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="address2">Address 2</label>
                        <input name="address2" type="text" class="form-control"
                               value="<?php echo set_value('address2', $company->address2); ?>" id="address2"
                               placeholder="Other address info (apt, unit)">
                    </div>
                </div><!--/row-->

                <div class="row">

                    <div class="form-group col-sm-6">
                        <label for="city">City</label>
                        <input name="city" type="text" class="form-control" id="city"
                               value="<?php echo set_value('city', $company->city); ?>" placeholder="City">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="province">Province/State</label>
                        <input name="province" type="text" class="form-control"
                               value="<?php echo set_value('province', $company->province); ?>" id="province" placeholder="Province/State">
                    </div>

                </div><!--/row-->

                <div class="row">


                    <div class="form-group col-sm-6">
                        <label for="postal_code">Postal/Zip Code</label>
                        <input name="postal_code" type="text" class="form-control"
                               value="<?php echo set_value('postal_code', $company->postal_code); ?>" id="postal_code"
                               placeholder="Postal/Zip Code">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="country">Country</label>
                        <input name="country" type="text" class="form-control" value="<?php echo set_value('country', $company->country); ?>"
                               id="country" placeholder="Country name">
                    </div>

                </div><!--/row-->
            </div> <!-- /.panel-body -->
        </div> <!-- /.panel-collapse -->

    </div> <!-- /.panel -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-paneled" href="#collapseThree">Other Info</a>
            </h4>
        </div> <!-- /.panel-heading -->

        <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="row">

                    <div class="form-group col-sm-6">
                        <label for="lead_status_id">Lead Status</label>
                        <?php
                            $selected_option = ($this->input->post('lead_status_id')) ? $this->input->post('lead_status_id') : $company->lead_status_id;
                            echo form_dropdown('lead_status_id', $lead_statuses, $selected_option, "class='form-control' id='lead_status_id'");
                        ?>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="lead_sources_id">Lead Source</label>
                        <?php
                            $selected_option = ($this->input->post('lead_sources_id')) ? $this->input->post('lead_sources_id') : $company->lead_source_id;
                            echo form_dropdown('lead_source_id', $lead_sources, $selected_option, "class='form-control' id='lead_sources_id'");
                        ?>
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-sm-6">
                        <label for="webpage">Webpage</label>
                        <input name="webpage" type="text" class="form-control" id="webpage"
                               value="<?php echo set_value('webpage', $company->webpage); ?>" placeholder="http://www.website.com">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="industry">Industry</label>
                        <?php
                            $selected_option = ($this->input->post('industry')) ? $this->input->post('industry') : $company->industry;
                            echo form_dropdown('industry', $industry_sources, $selected_option, "class='form-control' id='industry'");
                        ?>
                    </div>


                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="phone_fax">Fax Number</label>
                        <input name="phone_fax" type="text" class="form-control" id="phone_fax"
                               value="<?php echo $company->phone_fax; ?>" placeholder="(800) 123-4567">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter comments/descriptions">
                            <?php echo set_value('description', $company->description); ?></textarea>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="city">Do Not Call</label>

                        <div class="controls">
                            <label class="radio">
                                <input id="do_not_call_1" name="do_not_call" type="radio"
                                       value="Y" <?php echo set_radio('do_not_call', 'Y', ($company->do_not_call == 'Y')); ?>>Yes
                            </label>

                            <div style="clear:both"></div>

                            <label class="radio">
                                <input id="do_not_call_2" name="do_not_call" type="radio"
                                       value="N" <?php echo set_radio('do_not_call', 'N', ($company->do_not_call == 'N')); ?>>No
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="province">Email Opt Out</label>

                        <div class="controls">
                            <label class="radio">
                                <input id="email_opt_out_1" name="email_opt_out" type="radio"
                                       value="Y" <?php echo set_radio('email_opt_out', 'Y', $company->email_opt_out == 'Y'); ?>>Yes
                            </label>

                            <div style="clear:both"></div>

                            <label class="radio">
                                <input id="email_opt_out_2" name="email_opt_out" type="radio"
                                       value="N" <?php echo set_radio('email_opt_out', 'N', $company->email_opt_out == 'N'); ?>>No
                            </label>
                        </div>
                    </div>
                </div>

                <!---- custom_field_start ----->
                <div class="row">
                    <?php
                    if ($is_custom_fields == 1)
                    {
                        $i = 1;
                        $custom_field_company = $_SESSION['custom_field']['118'];
                        foreach ($custom_field_company as $custom)
                        {
                            $file_name = $custom['cf_name'];

                            if ($i == 2)
                            {
                                $dropval = eval('return $' . $custom['cf_name'] . ';'); ?>
                                    <div class="form-group col-sm-6">

                                        <label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>
                                        <?php if ($custom['cf_type'] == "Textbox") {
                                            $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropval; ?>
                                            <input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                                   id="<?php echo $custom['cf_name']; ?>" value="<?php echo $form_entry ?>">
                                        <?php } else if ($custom['cf_type'] == "Dropdown") {
                                            $dropname = eval('return $custom_' . $custom['cf_name'] . ';');
                                            $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropname;
                                            echo form_dropdown($custom['cf_name'], $dropval, $selected_option, "class='form-control' id='" . $custom['cf_name'] . "'");
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
                                $dropval = eval('return $' . $custom['cf_name'] . ';'); ?>
                                <div class="form-group col-sm-6">

                                    <label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>
                                    <?php if ($custom['cf_type'] == "Textbox") {
                                        $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropval; ?>
                                        <input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                               id="<?php echo $custom['cf_name']; ?>" value="<?php echo $dropval; ?>">
                                    <?php } else if ($custom['cf_type'] == "Dropdown") {
                                        $dropname = eval('return $custom_' . $custom['cf_name'] . ';');
                                        $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : $dropname;
                                        echo form_dropdown($custom['cf_name'], $dropval, $selected_option, "class='form-control' id='" . $custom['cf_name'] . "'");
                                    } ?>
                                </div>
                                <?php
                                //}
                                $i++;
                            }
                        }
                    } ?>
                </div>

                <!--------- custom_field_end ------------>

            </div> <!-- /.panel-body -->
        </div> <!-- /.panel-collapse -->

    </div> <!-- /.panel -->


</div> <!-- /.accordion -->

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Update Company</button>
    <button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
</div>
<input type="hidden" name="act" value="save">
</form>

<script type="text/javascript">
    cancel = function (elm) {
        window.location.href = '<?php echo site_url('companies')?>';
        return false;
    }

    // document ready
    jQuery(document).ready(function () {
        var validator = jQuery("#frmedit").validate({
            ignore: "",
            rules: {
                company: "required",
            },
            messages: {
                company: "Enter name",
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent().find('label:first'));
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
    });
</script>