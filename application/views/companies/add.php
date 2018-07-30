<h3 class="content-title">Add New Company</h3>

<?php
echo validation_errors('<div class="alert alert-danger">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>', '</div> <!-- /.alert -->');

$attributes = array('id' => 'frmprofile', 'name' => 'frmprofile');

echo form_open('companies/add', $attributes);
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
                        <input type="text" name="company_name" id="company_name" class="form-control"
                               value="<?php echo set_value('company_name'); ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="assigned_user_id">Assigned User</label>
                        <?php
                            $selected_option = ($this->input->post('assigned_user_id')) ? $this->input->post('assigned_user_id') : $_SESSION['user']['uacc_uid'];
                            echo form_dropdown('assigned_user_id', $assignedusers1, $selected_option, "class='form-control' id='assigned_user_id'");
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="phone_work">Work Phone</label>
                        <input name="phone_work" type="text" class="form-control" id="phone_work"
                               placeholder="Work phone" value="<?php echo set_value('phone_work'); ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="company_type">Company Type</label>
                        <?php
                            $selected_option = ($this->input->post('company_type')) ? $this->input->post('company_type') : '';
                            echo form_dropdown('company_type', $company_type, $selected_option, "class='form-control' id='company_type'");
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="email1">Email 1</label>
                        <input name="email1" type="text" class="form-control" id="email1" placeholder="Primary Email"
                               value="<?php echo set_value('email1'); ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="email2">Email 2</label>
                        <input name="email2" type="text" class="form-control" id="email2" placeholder="Secondary Email"
                               value="<?php echo set_value('email2'); ?>">
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
                        <input name="address1" type="text" class="form-control" id="address1" placeholder="Street name"
                               value="<?php echo set_value('address1'); ?>">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="address2">Address 2</label>
                        <input name="address2" type="text" class="form-control" id="address2"
                               placeholder="Other address info (apt, unit)"
                               value="<?php echo set_value('address2'); ?>">
                    </div>
                </div><!--/row-->

                <div class="row">

                    <div class="form-group col-sm-6">
                        <label for="city">City</label>
                        <input name="city" type="text" class="form-control" id="city" placeholder="City"
                               value="<?php echo set_value('city'); ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="province">Province/State</label>
                        <input name="province" type="text" class="form-control" id="postal" placeholder="Province/State"
                               value="<?php echo set_value('province'); ?>">
                    </div>

                </div><!--/row-->
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="postal_code">Postal/Zip Code</label>
                        <input name="postal_code" type="text" class="form-control" id="postal_code"
                               placeholder="Postal/Zip Code" value="<?php echo set_value('postal_code'); ?>">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="country">Country</label>
                        <input name="country" type="text" class="form-control" id="country" placeholder="Country name"
                               value="<?php echo set_value('country'); ?>">
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
                            $selected_option = ($this->input->post('lead_status_id')) ? $this->input->post('lead_status_id') : '';
                            echo form_dropdown('lead_status_id', $lead_status_id, $selected_option, "class='form-control' id='lead_status_id'");
                        ?>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="lead_sources_id">Lead Source</label>
                        <?php
                            $selected_option = ($this->input->post('lead_source_id')) ? $this->input->post('lead_source_id') : '';
                            echo form_dropdown('lead_source_id', $lead_source_id, $selected_option, "class='form-control' id='lead_source_id'");
                        ?>
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-sm-6">
                        <label for="webpage">Webpage</label>
                        <input name="webpage" type="text" class="form-control" id="webpage"
                               placeholder="http://www.website.com" value="<?php echo set_value('webpage'); ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="industry">Industry</label>
                        <?php
                            $selected_option = ($this->input->post('industry')) ? $this->input->post('industry') : '';
                            echo form_dropdown('industry', $industry, $selected_option, "class='form-control' id='industry'");
                        ?>
                    </div>

                </div>


                <div class="row">

                    <div class="form-group col-sm-6">
                        <label for="phone_fax">Fax Number</label>
                        <input name="phone_fax" type="text" class="form-control" id="phone_fax"
                               value="<?php echo set_value('phone_fax'); ?>" placeholder="(800) 123-4567">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5"
                                  placeholder="Enter comments/descriptions"><?php echo set_value('description'); ?></textarea>

                    </div>
                </div>
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
                        <label for="email_opt_out">Email Opt Out</label>
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

                <!---- custom_field_start ----->
                <div class="row">
                    <?php
                    if ($is_custom_fields == 1)
                    {
                    $i = 1;
                    $custom_field_company = $_SESSION['custom_field']['118'];
                    foreach ($custom_field_company as $custom)
                    {
                    if ($i == 2)
                    { ?>
                    <div class="form-group col-sm-6">

                        <label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>

                        <?php if ($custom['cf_type'] == "Textbox") {
                            $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : ''; ?>
                            <input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                   id="<?php echo $custom['cf_name']; ?>"
                                   value="<?php echo $form_entry; ?>">
                        <?php } else if ($custom['cf_type'] == "Dropdown") {
                            $dropval = eval('return $' . $custom['cf_name'] . ';');
                            $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : '';
                            echo form_dropdown($custom['cf_name'], $dropval, $selected_option, "class='form-control' id='" . $custom['cf_name'] . "'");
                        } ?>
                    </div>
                </div>
                <div class="row">

                    <?php
                    $i = 1;
                    }
                    else { ?>
                        <div class="form-group col-sm-6">

                            <label for="<?php echo $custom['cf_name']; ?>"><?php echo $custom['cf_label']; ?></label>
                            <?php if ($custom['cf_type'] == "Textbox") {
                                $form_entry = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : ''; ?>
                                <input name="<?php echo $custom['cf_name']; ?>" type="text" class="form-control"
                                       id="<?php echo $custom['cf_name']; ?>"
                                       value="<?php echo $form_entry; ?>">
                            <?php } else if ($custom['cf_type'] == "Dropdown") {
                                $dropval = eval('return $' . $custom['cf_name'] . ';');
                                $selected_option = ($this->input->post($custom['cf_name'])) ? $this->input->post($custom['cf_name']) : '';
                                echo form_dropdown($custom['cf_name'], $dropval, $selected_option, "class='form-control' id='" . $custom['cf_name'] . "'");
                            } ?>
                        </div>

                        <?php
                        $i++;
                    }

                    }
                    } ?>

                </div>

                <!--------- custom_field_start ------------>


            </div> <!-- /.panel-body -->
        </div> <!-- /.panel-collapse -->

    </div> <!-- /.panel -->


</div> <!-- /.accordion -->

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Add Company</button>
    <button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
</div>
<input type="hidden" name="act" value="save">
</form>

<script type="text/javascript">
    cancel = function (elm) {
        window.location.href = '<?php echo site_url('companies')?>';
        return false;
    }
</script>

<script type="text/javascript">
    cancel = function (elm) {
        window.location.href = '<?php echo site_url('companies')?>';
        return false;
    }

    // document ready
    jQuery(document).ready(function () {
        var validator = jQuery("#frmprofile").validate({
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