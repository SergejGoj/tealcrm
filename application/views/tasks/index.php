<link href='css/mobileadvancedsearch.css' rel='stylesheet'/>
<style>
    /*style for the search box, move it to css files later*/
    #search_result_tr, #filter_val_box, #filter_box_btn {
        display: none;
    }
</style>
<script src="/assets/js/plugins/chosen/js/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="/assets/js/plugins/chosen/css/chosen.css">
<h3 class="content-title">Tasks</h3>

<div class="row page-todo">

    <div class="col-md-12">
        <div class="table-responsive">

            <div class="form-group">


                <ul id="myTab1" class="nav nav-tabs">
                    <li class="<?php if ($search_tab == "basic") {
                        echo 'active';
                    } ?>">
                        <a href="#search" data-toggle="tab">Search</a>
                    </li>

                    <li class="<?php if ($search_tab == "advanced" || $search_tab == "saved") {
                        echo 'active';
                    } ?>">
                        <a href="#advanced" data-toggle="tab">Advanced Search</a>
                    </li>

                    <?php
                    if (isset($_SESSION['saved_searches_index']['tasks'])) {
                        if (count($_SESSION['saved_searches_index']['tasks']) > 0) {
                            echo '          <li class="dropdown">
		            <a href="javascript:;" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">
		              Saved Searches <b class="caret"></b>
		            </a><ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">';
                            foreach ($_SESSION['saved_searches_index']['tasks'] as $key => $value) {
                                echo '<li class=""><a href="' . site_url('tasks/search/' . $key) . '">' . $value . '</a></li>';
                            }
                            echo '</ul>';
                        }
                    }
                    ?>
                    </li>


                </ul>

                <div id="myTab1Content" class="tab-content">

                    <div class="tab-pane fade <?php if ($search_tab == "basic") {
                        echo 'active in';
                    } ?>" id="search">
                        <form name="frmedit" id="frmedit" action="<?php echo site_url('tasks/search'); ?>" method="post"
                              class="form parsley-form">
                            <div class="input-group">
                                <label for="search_box" class="sr-only">Search</label>
                                <input type="search" class="form-control" id="search_box"
                                       placeholder="Search by subject" name="subject"
                                       value="<?php if (isset($_SESSION['search']['tasks']['subject'])) {
                                           echo $_SESSION['search']['tasks']['subject'];
                                       } ?>">
                                <div class="input-group-btn">
                                    <input type="submit" name="search_go" class="btn btn-success" value="Search">
                                    <input type="submit" name="clear" class="btn btn-success" value="Clear">
                                </div><!-- /input-group-btn -->
                            </div>
                        </form>
                    </div> <!-- /.tab-pane -->

                    <div class="tab-pane fade <?php if ($search_tab == "advanced" || $search_tab == "saved") {
                        echo 'active in';
                    } ?>" id="advanced">

                        <form name="frmedit" id="frmedit" action="<?php echo site_url('tasks/search'); ?>" method="post"
                              class="form parsley-form">
                            <table class="table table-striped table-bordered" style="font-size:11px;">
                                <tbody>
                                <tr valign="middle">
                                    <td width="25%"><span><strong>Subject</strong>
						  <input type="text" class="form-control" name="subject" id="subject"
                                 value="<?php if (isset($_SESSION['search']['tasks']['subject'])) {
                                     echo $_SESSION['search']['tasks']['subject'];
                                 } ?>">
                                    </td>
                                    <td width="25%"><span><strong>Status</strong><br>
					  	<select name="status_id[]" id="status_id" multiple="true" multiple
                                class="form-control chosen-select">
                            <option value=""></option>
                            <?php
                            $status_types = lookupDropDownValues("status_id");
                            foreach ($status_types as $option) {
                                ?>
                                <option
                                    value="<?php echo $option; ?>" <?php if (isset($_SESSION['search']['tasks']['status_id'])) {
                                    foreach ($_SESSION['search']['tasks']['status_id'] as $opn) {
                                        if ($opn == $option) {
                                            echo 'selected';
                                        };
                                    }
                                } ?>><?php echo $_SESSION['drop_down_options'][$option]['name']; ?></option>
                            <?php } ?>
                        </select>
                                    </td>
                                    <td width="25%"><span><strong>Priority</strong><br>
					  	<select name="priority_id[]" id="priority_id" multiple="true" multiple
                                class="form-control chosen-select">
                            <option value=""></option>
                            <?php
                            $priority_types = lookupDropDownValues("priority_id");
                            foreach ($priority_types as $option) {
                                ?>
                                <option
                                    value="<?php echo $option; ?>" <?php if (isset($_SESSION['search']['tasks']['priority_id'])) {
                                    foreach ($_SESSION['search']['tasks']['priority_id'] as $opn) {
                                        if ($opn == $option) {
                                            echo 'selected';
                                        };
                                    }
                                } ?>><?php echo $_SESSION['drop_down_options'][$option]['name']; ?></option>
                            <?php } ?>
                        </select>

                                    </td>


                                    <!--  PROJECTS ONLY SEARCH OPTION REMOVED 6/15/15
						<td width="25%">
					  	<span><strong>Projects Only</strong> 
					  	<select name="parent_id" id = "parent_id" class="form-control" >
					  	<option value=""></option>
					  	<option value="<?php echo "Yes"; ?>" <?php if (isset($_SESSION['search']['tasks']['parent_id'])) {
                                        if ($_SESSION['search']['tasks']['parent_id'] == "Yes") {
                                            echo 'selected';
                                        };
                                    } ?>>Yes</option>
					  	<option value="<?php echo "No"; ?>" <?php if (isset($_SESSION['search']['tasks']['parent_id'])) {
                                        if ($_SESSION['search']['tasks']['parent_id'] == "No") {
                                            echo 'selected';
                                        };
                                    } ?>>No</option>
						</select>
						</td>
						-->
                                    <td width="25%">
                                        <span><strong>Assigned User</strong></span><br/>
                                        <select name="assigned_user_id[]" id="assigned_user_id" multiple="true" multiple
                                                class="form-control chosen-select">
                                            <option value=""></option>
                                            <?php
                                            foreach ($_SESSION['user_accounts'] as $user) {
                                                ?>
                                                <option value="<?php echo $user['id']; ?>"
                                                    <?php if (isset($_SESSION['search']['tasks']['assigned_user_id'])) {
                                                        foreach ($_SESSION['search']['tasks']['assigned_user_id'] as $opn) if ($opn == $user['id']) {
                                                            echo 'selected';
                                                        };
                                                    } ?>><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="middle">

                                    <td width="25%">
					  	<span><strong>Related Company</strong>
							<input type="text" name="company_viewer" id="company_viewer" class="form-control"
                                   value="<?php if (isset($company->company_name)) {
                                       echo $company->company_name;
                                   } ?>"/>
							<input type="hidden" name="company_id" id="company_id"
                                   value="<?php if (isset($_SESSION['search']['people']['company_id'])) {
                                       echo $_SESSION['search']['people']['company_id'];
                                   } ?>"/>
                                    </td>
                                    <td width="25%">
					  	<span><strong>Related Person</strong>
<input type="text" name="person_viewer" id="person_viewer" class="form-control"
       value="<?php if (isset($person->last_name)) {
           echo $person->first_name . " " . $person->last_name;
       } ?>"/>
							<input type="hidden" name="people_id" id="people_id"
                                   value="<?php if (isset($_SESSION['search']['tasks']['people_id'])) {
                                       echo $_SESSION['search']['tasks']['people_id'];
                                   } ?>"/>
                                    </td>
                                    <td width="25%"><span><strong>Due Date Between</strong></span>
                                        <input class="form-control datetime" id="due_date_start" name="due_date_start"
                                               type="text"
                                               value="<?php if (isset($_SESSION['search']['tasks']['due_date_start'])) {
                                                   echo $_SESSION['search']['tasks']['due_date_start'];
                                               } ?>">
                                        <input class="form-control datetime" id="due_date_end" name="due_date_end"
                                               type="text"
                                               value="<?php if (isset($_SESSION['search']['tasks']['due_date_end'])) {
                                                   echo $_SESSION['search']['tasks']['due_date_end'];
                                               } ?>">
                                    </td>
                                    <td width="25%"><span><strong>Date Entered Between</strong></span>
                                        <input class="form-control datetime" id="date_entered_start"
                                               name="date_entered_start" type="text"
                                               value="<?php if (isset($_SESSION['search']['tasks']['date_entered_start'])) {
                                                   echo $_SESSION['search']['tasks']['date_entered_start'];
                                               } ?>">
                                        <input class="form-control datetime" id="date_entered_end"
                                               name="date_entered_end" type="text"
                                               value="<?php if (isset($_SESSION['search']['tasks']['date_entered_end'])) {
                                                   echo $_SESSION['search']['tasks']['date_entered_end'];
                                               } ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="25%"><span><strong>Date Modified Between</strong></span>
                                        <input class="form-control datetime" id="date_modified_start"
                                               name="date_modified_start" type="text"
                                               value="<?php if (isset($_SESSION['search']['tasks']['date_modified_start'])) {
                                                   echo $_SESSION['search']['tasks']['date_modified_start'];
                                               } ?>">
                                        <input class="form-control datetime" id="date_modified_end"
                                               name="date_modified_end" type="text"
                                               value="<?php if (isset($_SESSION['search']['tasks']['date_modified_end'])) {
                                                   echo $_SESSION['search']['tasks']['date_modified_end'];
                                               } ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right">

                                        <input type="submit" name="adv_search_go" class="btn btn-success"
                                               value="Search">
                                        <?php
                                        if ($search_tab == "saved") {
                                            ?>
                                            <input type="button" name="clear"
                                                   onclick="window.location.href='<?php echo site_url('tasks/search/' . $_SESSION['search_id'] . '/delete/') ?>'"
                                                   class="btn btn-danger" name='DeleteSearch'
                                                   value="Delete Saved Search">
                                            <?php
                                        } else {
                                            ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" data-toggle="modal"
                                                                           data-target="#save-modal"
                                                                           name="adv_search_save" id="adv_search_save"
                                                                           class="btn btn-warning" value="Search & Save"
                                                                           onclick="javascript: $('#searchLabel').text('Save Advanced Search'); $('#module').val('tasks'); return false;">
                                            <?php
                                        }
                                        ?>

                                        &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="clear"
                                                                       class="btn btn-success" value="Clear">

                                    </td>
                                </tr>
                                </tbody>
                            </table>


                    </div> <!-- / .tab-pane -->

                </div> <!-- /.tab-content -->
                <!-- Save Advanced Search Modal -->
                <div class="modal fade" id="save-modal" tabindex="-1" role="dialog" aria-labelledby="searchLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title" id="searchLabel"></h4>
                            </div>
                            <div class="modal-body">
                                Please provide an easy to remember name for this saved search<br/>

                                <input type="text" name="saved_search_name" class="form-control"/><br/>
                                <input type="submit" class="btn btn-warning" name="saved_search_result"
                                       value="Save Search">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
                </form>
            </div>


            <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('tasks') ?>"
                  method="post">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="select_all" value="ids[]"></th>
                        <?php if (isset($task_updated_fields))
                            foreach ($task_updated_fields as $field_list) { ?>
                                <th><?php echo $field_label[$field_list->field_name]["label_name"]; ?></th>
                            <?php } ?>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!$tasks->exists()) :?>
                        <tr>
                            <td colspan="8" align="center">No Tasks</td>
                        </tr>
                    <?php else: foreach ($tasks as $task) : ?>
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="<?php echo $task->task_id ?>"></td>
                            <?php if (isset($task_updated_fields)) {
                                foreach ($task_updated_fields as $field_list) {
                                    $field_name = $field_list->field_name;
                                    if ($field_name == "subject") { ?>
                                        <td>
                                            <a href="<?php echo site_url('tasks/view/' . $task->task_id); ?>"><?php echo $task->$field_name; ?></a>
                                        </td>
                                    <?php } else {
                                        if ($field_label[$field_list->field_name]["field_type"] == "task_text_field") { ?>
                                            <td><?php echo $task->$field_name; ?></td>
                                        <?php } else if ($field_label[$field_name]["field_type"] == "task_date_field") { ?>
                                            <td><?php if (!is_null($task->$field_name)) {
                                                    echo date('m/d/y h:ia', strtotime($task->$field_name . ' UTC'));
                                                } ?></td>
                                        <?php } else if ($field_label[$field_name]["field_type"] == "task_drop_field") { ?>
                                            <td><?php echo $_SESSION['drop_down_options'][$task->$field_name]['name']; ?></td>
                                        <?php } else if ($field_label[$field_name]["field_type"] == "task_special_field") { ?>
                                            <td>
                                                <?php
                                                    $first_name = $_SESSION['user_accounts'][$task->$field_name]['first_name'];
                                                    $last_name = $_SESSION['user_accounts'][$task->$field_name]['last_name'];
                                                    if(($first_name != NULL) && ($last_name != NULL)) {
                                                        echo $first_name." ".$last_name;
                                                    } else if($first_name != NULL) {
                                                        echo $first_name;
                                                    } else if($last_name != NULL) {
                                                        echo $last_name;
                                                    } else {
                                                        echo $_SESSION['user_accounts'][$task->$field_name]['username'];
                                                    }
                                                ?>
                                            </td>
                                        <?php } else if ($field_label[$field_name]["field_type"] == "task_relate_field") {
                                            $relate_name = str_replace("id", "name", $field_name); ?>
                                            <td>
                                                <a href="<?php echo site_url($field_label[$field_name]["relate_path"] . $task->$field_name); ?>"><?php echo $task->$relate_name; ?></a>
                                            </td>
                                        <?php } else if ($field_label[$field_name]["field_type"] == "custom_text_field") { ?>
                                            <td><?php echo $custom_values[$field_name][$task->task_id]; ?></td>
                                        <?php } else if ($field_label[$field_name]["field_type"] == "custom_drop_field") {
                                            $value = $custom_values[$field_name][$task->task_id]; ?>
                                            <td><?php echo $_SESSION['drop_down_options'][$value]['name']; ?></td>
                                        <?php }
                                    }
                                }
                            } ?>
                            <td><a href="<?php echo site_url('tasks/edit/' . $task->task_id) ?>"><i
                                        class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                                &nbsp;
                                <a href="javascript:delete_one( '<?php echo $task->task_id ?>' )"><i
                                        class="btn btn-xs btn-secondary fa fa-times"></i></a>

                            </td>
                        </tr>

                    <?php endforeach; endif; ?>

                    </tbody>
                </table>
                <div>
                    <div class="list-footer-left">
                        <button type="button" class="btn btn-danger" onclick="delete_all()">Delete</button>
                        <button type="button" class="btn btn-success"
                                onclick="window.location.href='<?php echo site_url('tasks/add') ?>'">Add New
                        </button>
                    </div>
                    <div class="list-footer-right">
                        <?php echo $pager_links ?>
                    </div>
                </div>
                <input type="hidden" name="act" value="">
            </form>

        </div> <!-- /.table-responsive -->
    </div>


</div>
</div>


<br/><br>
<script type="text/javascript">
    delete_one = function (task_id) {
        Messi.ask('Do you really want to delete the record?', function (val) {
            if (val == 'Y') {
                window.location.href = "<?php echo site_url('tasks/delete')?>/" + task_id;
            }
        }, {modal: true, title: 'Confirm Delete'});
    }

    // delete all(selected) record
    delete_all = function () {
        size = jQuery(":input[name='ids[]']:checked").length;
        // none selected
        if (size == 0) {
            Messi.alert('Please select a record to delete', function () {

            }, {modal: true, title: 'Confirm Delete'});

            return;
        }
        // confirm
        Messi.ask('Do you really want to delete selected records?', function (val) {
            // confirmed
            if (val == 'Y') {
                jQuery('#frmlist').prop('action', '<?php echo site_url('tasks/delete_all')?>');
                jQuery(":input[name='act']").val('delete');
                jQuery('#frmlist').submit();
            }
        }, {modal: true, title: 'Confirm Delete'});
    }

    // document ready
    jQuery(document).ready(function () {
        jQuery(":input[name='select_all']").bind('click', function () {
            jQuery(":input[name='" + jQuery(this).val() + "']").prop('checked', jQuery(this).prop('checked'));
        });

        /*code for the search box*/
        $("#search_go").click(function () {
            if ($("#search_box").val().length < 1) return false;

            //reset filters
            filtervalue1 = "";
            filtervalue2 = "";
            startSearch();
        });

        $("#search_box").keyup(function (e) {
            if (e.keyCode == 13) {
                if ($(this).val().length < 1) return false;

                //reset filters
                filtervalue1 = "";
                filtervalue2 = "";
                startSearch();
            }
        });

        //clear search box and search result
        $("#search_clear").click(function () {
            $(".search_result_tr").remove();
            $("#search_box").val('').focus();
            $("#frmlist tbody tr").show();
            $(".pagination").show();
        });

        $("#filter_by").change(function () {
            $("#filter_val_box").hide();
            $("#filter_box_btn").hide();
            var filter_type = $("option:selected", this).attr("data-type");
            var filter_val = $(this).val();
            $.ajax({
                url: '/ajax/getFilterElement',  //server script to process data
                type: 'POST',
                async: true,
                data: {type: filter_type, val: filter_val},
                success: function (result) {
                    if (result != "") {
                        $("#filter_val_box").html(result).show();
                        $("#filter_box_btn").show();
                    }
                    if (filter_type == "datetime") {
                        jQuery('.datetime').datetimepicker({
                            format: 'm/d/Y',
                            mask: false
                        });
                    }
                }
            });
        });

        $("#filter_btn").click(function () {
            //reset filters
            filtervalue1 = "";
            filtervalue2 = "";
            if ($("#filtervalue1").length > 0) filtervalue1 = $("#filtervalue1").val();
            if ($("#filtervalue2").length > 0) filtervalue2 = $("#filtervalue2").val();

            if (filtervalue1 == "") return false;
            startSearch();
        });
    });


    var filtervalue1 = "";
    var filtervalue2 = "";


    // picker
    jQuery('.datetime').datetimepicker({
        format: 'm/d/Y',
        mask: true,
        timepicker: false
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
            $("#company_id").val(ui.item.id);
        },
        open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });

    //autocomplete for people
    $("#person_viewer").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/ajax/personsAutocomplete",
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
            $("#people_id").val(ui.item.id);
        },
        open: function () {
            $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function () {
            $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });
    $("#company_viewer").focusout(function () {
        var company_name = $("#company_viewer").val();
        var company_id = $("#company_id").val();
        if (company_name != "") {
            if (company_id == "") {
                alert("Please choose a valid company from the list.");
                $('#company_viewer').val('');
                $('#company_viewer').focus();
            }
        }
    });

    $("#person_viewer").focusout(function () {
        var person_name = $("#person_viewer").val();
        var people_id = $("#people_id").val();
        if (person_name != "") {
            if (people_id == "") {
                alert("Please choose a valid person from the list.");
                $('#person_viewer').val('');
                $('#person_viewer').focus();
            }
        }
    });

    $(document).ready(function () {
        $('#adv_search_save').attr('disabled', true);

        $('#subject').keyup(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })

        $('#priority_id').chosen().change(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        $('#status_id').chosen().change(function () {

            if ($(this).val() != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })

        $('#parent_id').click(function () {

            if ($(this).val() != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        $('#company_viewer').focusout(function () {

            if ($('#company_id').val() != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })

        $('#person_viewer').focusout(function () {
            if ($('#people_id').val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })

        $('#assigned_user_id').chosen().change(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        $('#date_entered_start').click(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        $('#date_entered_end').click(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        $('#date_modified_start').click(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })

        $('#date_modified_end').click(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        $('#due_date_start').click(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        $('#due_date_end').click(function () {
            if ($(this).val().length != 0) {
                $('#adv_search_save').attr('disabled', false);
            }
            else {
                checkempty();
            }
        })
        checkempty = function () {

            if ($('#subject').val().length != 0) {
            } else if ($('#company_id').val().length != 0) {
            } else if ($('#priority_id').val().length != 0) {
            } else if ($('#status_id').val().length != 0) {
            } else if ($('#parent_id').val().length != 0) {
            } else if ($('#people_id').val().length != 0) {
            } else if ($('#assigned_user_id').val().length != 0) {
            } else if ($('#date_entered_start').val() != "__/__/____") {
            } else if ($('#date_entered_end').val() != '__/__/____') {
            } else if ($('#date_modified_start').val() != '__/__/____') {
            } else if ($('#date_modified_end').val() != '__/__/____') {
            } else if ($('#due_date_start').val() != '__/__/____') {
            } else if ($('#due_date_end').val() != '__/__/____') {
            } else {
                $('#adv_search_save').attr('disabled', true);
            }
        }
    });

    jQuery(document).ready(function () {

        var config = {
            '.chosen-select': {},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "95%"}
        }
        for (var selector in config) {

            $(selector).chosen(config[selector]);
        }
    });
</script>
