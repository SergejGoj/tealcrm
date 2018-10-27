<?php session_start(); ?>
<style>
    .ui-dialog.time-dialog {
        font-family: Verdana, Arial, sans-serif;
        font-size: .8em;
        color: #ffffff;
    }

    .ui-widget-header {
        background: #227979;
        color: #ffffff;
    }

    .ui-dialog-titlebar-close {
        display: none;
    }
</style>

<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">
        <li class="fa fa-check-square-o" style="color:#c0c0c0;font-family:'FontAwesome'"> Task</li>
        <h2><?php echo $task->subject ?></h2>
        <?php
        // check if this is a project child, if it is then we show a link back to the parent

        if (!empty($task->parent_id) || $task->parent_id != 0) {
            ?>

            <div class="list-group">

                <a href="<?php echo site_url('tasks/view') . "/" . $task->parent_id; ?>" class="list-group-item">
                    <h3 class="pull-right"><i class="fa fa-book text-warning"></i></h3>
                    <h4 class="list-group-item-heading"><?php echo $task->parent_name; ?></h4>
                    <!-- <p class="list-group-item-text"><?php echo $task->parent_name; ?></p> !-->
                </a>
            </div>
        <?php }


        ?>
        <?php
        // check if this record is overdue or not, if it is then show overdue or DUE today

        $due_date = strtotime(date('m/d/Y', strtotime($task->due_date.' UTC')));
        $todays_date = strtotime(date('m/d/Y'));

        // check due date
        if ($due_date < $todays_date && $task->status_id != 103 && $task->due_date != null) {
            ?>
            <div class="alert alert-danger">Overdue</div>
            <?php
        }

        // check for due today
        if ($due_date == $todays_date && $task->status_id != 103) {
            ?>
            <div class="alert alert-warning">Due Today!</div>
            <?php
        }
        ?>
        <?php
        if ($task->status_id == 103) {
            ?>
            <div class="alert alert-success">Completed</div>
        <?php } ?>
        <div class="btn-group">
            <a href="<?php echo site_url('tasks/edit') . "/" . $task->task_id; ?>" class="btn btn-tertiary">Edit
                Task</a>
            <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span
                    class="caret"></span></button>

            <ul class="dropdown-menu">
                <?php if ($task->status_id != 103) {
                    ?>
                    <li>
                        <a onClick="markCompleted('<?php echo $task->task_id; ?>');">Mark Completed</a>
                        <!-- <a href="<?php echo site_url('tasks/mark_completed'); ?>/<?php echo $task->task_id; ?>">Mark Completed</a> -->
                    </li>
                <?php } ?>
                <?php
                // check if this is a PROJECT or not
                // also check if it's a sub task, do not allow a sub task to be a project
                // if 0, do not show this
                if ($task->parent_id == '0')
                {
                //echo $task->parent_id;
                ?><!--
					<li>
						<a href="#" data-toggle="modal" data-target="#add-task" onclick="javascript: $('#AddTask').text('Add Project Tasks');return false;">Add Project Tasks</a>
					</li>-->
            <?php
            }
            ?>
                <li>
                    <a href="javascript:delete_one( '<?php echo $task->task_id ?>' )">Delete Task</a>
                </li>
            </ul>
        </div><!-- /.btn-gruop --><br/><br/>
        <div class="modal fade" id="add-task" tabindex="-1" role="dialog" aria-labelledby="AddTask" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="AddTask"></h4>
                    </div>
                    <div class="modal-body">

                        <form name="frmadd" id="frmadd"
                              action="<?php echo site_url('tasks/add_more_tasks/' . $task->task_id) ?>" method="post"
                              class="form parsley-form">
                            <div class="row" id="tasks_box">
                                <div class="form-group col-sm-12" style="margin-bottom: 5px;">
                                    <label for="task1">Task Checklist</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">1</div>
                                        <input type="text" name="tasks[]" id="task0" class="form-control"
                                               placeholder="">
                                    </div>
                                </div>
                                <!--	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                                        <input type="hidden" name="tasks_list" id="tasks_list">
                                        <div class="input-group">
                                            <div class="input-group-addon">2</div>
                                            <input type="text" name="tasks[]" id="task1" class="form-control"  placeholder="">
                                        </div>
                                    </div> -->
                                <div class="form-group col-sm-3">
                                    <button type="button" id="add_more_tasks" class="form-control btn btn-default">Add
                                        More
                                    </button>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <input type="hidden" id="parent_project_id" value="<?php echo $task->task_id; ?>">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">

            <li class="active">
                <a href="#profile-tab" data-toggle="tab">
                    <i class="fa fa-user"></i>
                    &nbsp;&nbsp;Overview
                </a>
            </li>

            <?php if ($more_info == 1) { ?>
                <li class="inactive">
                    <a href="#profile-tab2" data-toggle="tab">
                        <i class="fa fa-list"></i>
                        &nbsp;&nbsp;More Info
                    </a>
                </li>
            <?php } ?>




            <?php if (!empty($total_tasks)) { ?>
                <li>
                    <a href="#tasks-tab" data-toggle="tab">
                        <i class="fa fa-sitemap"></i>
                        &nbsp;&nbsp;Project Tasks
                    </a>
                </li>
            <?php } ?>
        </ul>


        <ul class="icons-list">
            <!-- If this has a project connected to it, display the name as a link to the project page. If there's no project associated, unset the project tab variable for "Cancel" behaviour -->
            <?php if (!$this->general->getProjectForTask($task->task_id, 'name')) { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-pencil"></i> <strong>project</strong>
                        <?php $_SESSION["project_tab"] = $this->general->getProjectForTask($task->task_id, 'id'); ?>
                    </div>
                    <a href="<?php echo site_url('projects') ?>"><?php echo $this->general->getProjectForTask($task->task_id, 'name'); ?></a>
                </li>
            <?php }  // end if var exists ?>


            <li>
                <?php
                if ($task->due_date == null) { ?>
                    <div>
                        <i class="icon-li fa fa-pencil"></i> <strong>due date</strong>
                    </div><?php echo "Not set"; ?>
                <?php } else { ?>
                    <div>
                        <i class="icon-li fa fa-pencil"></i> <strong>due date</strong>
                    </div><?php echo date('F d, Y', strtotime($project_due_date)) ?>
                <?php }
                ?>


            </li>


            <?php if (!empty($priority)) { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-pencil"></i> <strong>priority</strong>
                    </div><?php echo $priority; ?>
                </li>
            <?php } // end if var exists ?>

            <?php if (!empty($status)) { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-pencil"></i> <strong>status</strong>
                    </div><?php echo $status ?>
                </li>
            <?php } // end if var exists ?>
            <li>
                <div>
                    <i class="icon-li fa fa-user"></i> <strong>assigned user</strong>
                </div>
                <?php
                    $first_name = $_SESSION['user_accounts'][$task->assigned_user_id]['first_name'];
                    $last_name = $_SESSION['user_accounts'][$task->assigned_user_id]['last_name'];
                    if(($first_name != NULL) && ($last_name != NULL)) {
                        echo $first_name." ".$last_name;
                    } else if($first_name != NULL) {
                        echo $first_name;
                    } else if($last_name != NULL) {
                        echo $last_name;
                    } else {
                        echo $_SESSION['user_accounts'][$task->assigned_user_id]['username'];
                    }
                ?>
            </li>

            <?php if (!empty($task->created_by)) { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-user"></i> <strong>created</strong>
                    </div><?php echo date('m/d/Y h:ia', strtotime($task->date_entered . ' UTC')) ?> by<br/>
                    <?php echo $_SESSION['user_accounts'][$task->created_by]['first_name'] . " " . $_SESSION['user_accounts'][$task->created_by]['last_name']; ?>
                </li>
            <?php } ?>
            <?php if (!empty($task->modified_user_id)) { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-user"></i> <strong>modified</strong>
                    </div><?php echo date('m/d/Y h:ia', strtotime($task->date_modified . ' UTC')) ?> by<br/>
                    <?php echo $_SESSION['user_accounts'][$task->modified_user_id]['first_name'] . " " . $_SESSION['user_accounts'][$task->modified_user_id]['last_name']; ?>
                </li>
            <?php } ?>
        </ul>
    </div> <!-- /.col -->


    <div class="col-md-9 col-sm-8 layout-main">


        <div id="settings-content" class="tab-content stacked-content">


            <div class="tab-pane fade in active" id="profile-tab">
                <h3 class="content-title">Overview</h3>


                <div class="row">


                    <div class="col-md-6">


                        <?php if ($task->company_id != '' && $task->company_id != '0') { ?>
                            <div class="panel-group" id="accordion-paneled">
                                <div class="panel panel-default open">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-building-o text-white"></i> &nbsp;&nbsp;Company
                                        </h4>
                                    </div><!-- /.panel-heading -->

                                    <div class="panel-collapse collapse in" id="collapseNotes">
                                        <div class="panel-body">
                                            <a href="<?php echo site_url('companies/view') ?>/<?php echo $task->company_id; ?>"><?php echo $task->company_name; ?></a>
                                        </div><!-- /.panel-body -->
                                    </div><!-- /.panel-collapse -->
                                </div><!-- /.panel -->
                            </div>
                        <?php } ?>

                        <?php if ($task->people_id != "" && $task->people_id != '0') { ?>
                            <div class="panel-group" id="accordion-paneled">
                                <div class="panel panel-default open">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-group text-white"></i> &nbsp;&nbsp;Person
                                        </h4>
                                    </div><!-- /.panel-heading -->

                                    <div class="panel-collapse collapse in" id="collapseMeetings">
                                        <div class="panel-body">
                                            <a href="<?php echo site_url('people/view') ?>/<?php echo $task->people_id; ?>"><?php echo $task->person_name; ?></a>
                                        </div><!-- /.panel-body -->
                                    </div><!-- /.panel-collapse -->
                                </div><!-- /.panel -->
                            </div>
                        <?php } ?>

                        <?php
                        if (!empty($task->description)) { ?>

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Description</h3>
                                </div>

                                <div class="panel-body">
                                    <?php echo $task->description; ?>
                                </div> <!-- end panel body -->
                            </div> <!-- end panel -->

                        <?php } ?>


                    </div>
                    <div class="col-md-6">


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Messages</h3>
                            </div>

                            <div class="panel-body" id="tasks_feed_body">
                                <div class="share-widget clearfix">
                                    <textarea class="form-control share-widget-textarea" placeholder="Add Comment..."
                                              rows="3" tabindex="1"></textarea>

                                    <div class="share-widget-actions">

                                        <div class="pull-right">
                                            <button class="btn btn-primary btn-sm" id="add_note_tasks" tabindex="2">Add
                                                Comment
                                            </button>
                                        </div>
                                    </div><!-- /.share-widget-actions -->
                                </div><!-- /.share-widget -->
                                <!-- begin feed -->
                                <?php echo $feed_list; ?>
                                <!-- end feed -->
                            </div> <!-- end panel body -->
                        </div> <!-- end panel -->

                    </div>

                </div>

            </div> <!-- /.tab-pane -->


            <!--------------------------custom_tab_start-------------------------------------------->
            <div class="tab-pane fade inactive" id="profile-tab2">
                <div class="row">
                    <div class="col-md-6">

                        <div class="panel-group accordion-panel" id="accordion-paneled">
                            <div class="panel panel-default open">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled"
                                                               data-toggle="collapse" href="#collapseCustomField"><i
                                                class="fa fa-calendar-o text-white"></i> &nbsp;&nbsp;More Info

                                        </a></h4>
                                </div>
                                <div class="panel-collapse collapse in" id="collapseCustomField">
                                    <div class="panel-body">
                                        <div class="list-group">
                                            <ul class="icons-list">
                                                <?php
                                                if ($is_custom_fields == 1) {
                                                    foreach ($custom_field_values as $custom) {
                                                        $check_empty = eval('return $' . $custom['cf_name'] . ';');
                                                        if ($check_empty != " ") {
                                                            if ($check_empty != "") {
                                                                ?>

                                                                <li>
                                                                    <div>
                                                                        <i class="fa fa-list"></i>
                                                                        <strong><?php echo $custom['cf_label']; ?></strong>
                                                                    </div>
                                                                    <?php if ($custom['cf_type'] == "Textbox") {
                                                                        echo eval('return $' . $custom['cf_name'] . ';');
                                                                    } else {
                                                                        $dropval = eval('return $' . $custom['cf_name'] . ';');
                                                                        echo $_SESSION['drop_down_options'][$dropval]['name'];
                                                                    } ?>

                                                                </li>

                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--------------------------custom_tab_end-------------------------------------------->


            <div class="tab-pane fade" id="tasks-tab">
                <h3 class="content-title">Project Tasks</h3>
                <div class="row">
                    <div class="col-md-12">
                        <?php

                        // check if this is a PROJECT or not
                        // if 0, do not show this

                        if ($total_tasks > 0) {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Project Tasks (By Due Date)</h3>
                                </div>

                                <div class="panel-body" style="padding-top: 0px;">
                                    <div class="row" style="background-color:#c0c0c0; padding:5px;">
                                        <div class="col-md-1">
                                            <strong>Close</strong>
                                        </div>
                                        <div class="col-md-8">
                                            <strong>Subject</strong>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Due Date</strong>
                                        </div>
                                    </div>
                                    <?php
                                    if ($total_tasks > 0) {
                                        $completed_tasks = 0;
                                        foreach ($lists as $list) {
                                            $done = '';
                                            if ($list->status_id == 103)
                                                $done = 'checked="checked"';

                                            echo '
										<div class="row" style="padding:5px;">
											<div class="col-md-1">
												<div style="padding-left:20px;">
													<input type="checkbox" class="task_list_checkbox" data-task="' . $list->task_id . '" ' . $done . '>
												</div>
											</div>
											<div class="col-md-8">
												<a href="' . site_url('tasks/view/' . $list->task_id) . '">' . $list->subject . '</a>
											</div>
											<div class="col-md-3">
												' . date("m/j/Y g:i a", strtotime($list->due_date . ' UTC')) . '
											</div>
										</div>

											';
                                        }


                                        echo '<div class="progress"><div class="progress-bar tasks-progress-bar" role="progressbar" aria-valuenow="' . $percent_complete . '" aria-valuemin="0" aria-valuemax="100" style="width:' . $percent_complete . '%;" data-total="' . $total_tasks . '" data-done="' . $done_tasks . '">' . $percent_complete . '%</div></div>';
                                    }
                                    ?>
                                </div> <!-- end panel body -->

                            </div> <!-- end panel -->
                        <?php } // ends check if this is a project or not ?>

                    </div> <!-- end col -->
                </div>        <!-- end row -->

            </div> <!-- end tab pane -->


        </div> <!-- /.tab-content -->

    </div> <!-- /.col -->

</div> <!-- /.row -->

<div id="timeDialog" style="display: none;">
    <center>
        Please enter the time (in minutes) spent on completing this task:<br><br>
        <input type=text name="timeOnTask" id="timeOnTask" value="0">
    </center>
</div>


<?php
/*
<style>
#add_project_tasks_box{
display:none;
}
</style>
<h2 class="text-left"><strong><?php echo $task->subject?></strong></h2>


<div class="row">

<div class="col-md-3" style="background-color: #EEEEEE; padding-top: 25px;">

        <div class="btn-group">
            <a href="<?php echo site_url('tasks/edit') . "/" . $task->task_id; ?>" class="btn btn-tertiary">Edit Task</a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>

            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo site_url('tasks/mark_completed');?>/<?php echo $task->task_id;?>">Mark Completed</a>
                </li>
            <?php
                // check if this is a PROJECT or not
                // also check if it's a sub task, do not allow a sub task to be a project
                // if 0, do not show this

                if($total_tasks <= 0 || !empty($task->parent_id) || $task->parent_id != 0){
                ?>
                <li>
                    <a href="javascript: $('#add_project_tasks_box').show(); ;">Create Project</a>
                </li>
                <?php
                }else{
                    ?>
                <li>
                    <a href="javascript: $('#add_project_tasks_box').show(); ;">Add Project Tasks</a>
                </li>
                    <?php
                }
                ?>
                <li>
                    <a href="javascript:delete_one( '<?php echo $task->task_id?>' )">Delete Task</a>
                </li>
            </ul>
        </div><!-- /.btn-gruop -->
        <br/><br/>






    <div class="panel-group" id="accordion-paneled">

        <div class="panel panel-default open">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-info-circle text-white"></i> &nbsp;&nbsp;Task Details</h4>
            </div><!-- /.panel-heading -->

            <div class="panel-collapse collapse in" id="collapseNotes">
                <div class="panel-body">

                </div><!-- /.panel-body -->
            </div><!-- /.panel-collapse -->
        </div><!-- /.panel -->

    </div>




</div><!-- /col -->

<div class="col-md-9">








    <br class="visible-xs">
    <br class="visible-xs">
</div><!-- /.col -->



</div><!-- /.row -->
*/
?>
<script type="text/javascript">


    function markCompleted(task_id) {
        var project_id = '<?php echo $this->general->getProjectForTask($task->task_id, "id"); ?>';

        if (project_id.length > 10) {

            updateUrl = '<?php echo site_url('projects/updatetask')?>/' + task_id + '/' + project_id + '/' + $("#timeOnTask").val();
        }
        else {
            updateUrl = '<?php echo site_url('projects/updatetask')?>/' + task_id + '/undefined/' + $("#timeOnTask").val() + '/tasks';
        }

        $("#timeDialog").dialog({
            autoOpen: false,
            height: 150,
            width: 400,
            modal: true,
            title: "Add Time to Completed Task",
            buttons: {
                "Log Time": function () {
                    window.location.href = updateUrl;
                    $(this).dialog("close");
                },
                Cancel: function () {
                    $("input:checkbox").prop('checked', 0);
                    $(this).dialog("close");
                }
            },
            dialogClass: 'no-close time-dialog'

        });

        $("#timeDialog").dialog("open");
        return false;
    }


    // delete single record
    delete_one = function (task_id) {
        Messi.ask('Do you really want to delete the record?', function (val) {
            if (val == 'Y') {
                window.location.href = "<?php echo site_url('tasks/delete')?>/" + task_id;
            }
        }, {modal: true, title: 'Confirm Delete'});
    }

    cancel = function (elm) {
        window.location.href = '<?php echo site_url('tasks')?>';
        return false;
    }

    edit = function (elm, task_id) {
        window.location.href = '<?php echo site_url('tasks/edit')?>/' + task_id;
        return false;
    }

    $(document).ready(function () {
        $("#add_note_tasks").on("click", function () {
            var desc = $.trim($("#tasks_feed_body textarea").val());
            if (desc.length < 1) return false;

            $.ajax({
                url: '/feeds/add',  //server script to process data
                type: 'POST',
                async: true,
                data: {id: '<?php echo $task->task_id?>', description: desc, cat: 5},
                success: function (result) {
                    //$("#deal_feed_body").append(result);
                    $("#tasks_feed_body>.share-widget").after(result);
                    $("#tasks_feed_body textarea").val('');
                }
            });
        });

        var num_tasks = 0;
        $("#add_more_tasks").on("click", function () {
            //add new field only if there were no empty taks fields
            for (var i = 0; i <= num_tasks; i++) {
                if ($.trim($("#task" + i).val()) == "") return false;
            }


            num_tasks += 1;
            var task_count = num_tasks + 1;
            //	$("<div class='form-group col-sm-12' style='margin-bottom: 5px;'><div class='col-sm-9'><div class='input-group'><div class='input-group-addon'>" + task_count + "</div><input type='text' name='tasks[]' id='task" + num_tasks + "' class='form-control additional_task'  placeholder=''></div></div><div class='form-group col-sm-3'><input type='text' name='due_dates[]' id='due_date" + num_tasks + "' class='form-control datetime additional_date'  placeholder=''></div></div>").insertBefore( $("#add_more_tasks").parent() );

            $("<div class='form-group col-sm-12' style='margin-bottom: 5px;'><div class='input-group'><div class='input-group-addon'>" + task_count + "</div><input type='text' name='tasks[]' id='task" + num_tasks + "' class='form-control'  placeholder=''></div></div>").insertBefore($("#add_more_tasks").parent());
            //console.log(num_tasks + " <> " + task_count);


            // picker
            jQuery('.datetime').datetimepicker({
                format: 'm/d/Y H:i',
                mask: false
            });
        });

        var lastFetchedFeed = 5;
        $(".panel .feed-more").on("click", function () {
            $(this).text("Loading ").addClass("active").append(' <i class="fa fa-gear fa-spin"></i>');
            $.ajax({
                url: '/ajax/more',  //server script to process data
                type: 'POST',
                async: true,
                data: {id: '<?php echo $task->task_id?>', last: lastFetchedFeed, cat: 5},
                success: function (result) {
                    $(".panel .feed-more").text("Load More ").removeClass("active").find('i').remove();
                    var resultObject = $.parseJSON(result);
                    lastFetchedFeed = resultObject.last;
                    $(resultObject.value).insertBefore($("#tasks_feed_body .feed-more"));

                }
            });
        });

        $("#save_more_tasks").click(function () {
            var tsks = [];
            $(".additional_task").each(function (e) {
                tsks.push($(this).val());
            });
            var dts = [];
            $(".additional_date").each(function (e) {
                dts.push($(this).val());
            });
            console.log(tsks);
            console.log(dts);
            $.ajax({
                url: '/ajax/additionalTasks',  //server script to process data
                type: 'POST',
                async: true,
                data: {pid: '<?php echo $task->task_id?>', ntsk: tsks, ddate: dts},
                success: function (result) {
                    if (result == 0) {
                        location.reload();
                    }
                }
            });
        });

        $(".task_list_checkbox").change(function () {
            var child_id = $(this).attr("data-task");
            if ($(this).is(":checked")) {
                var done_tasks = parseInt($(".tasks-progress-bar").attr("data-done")) + 1;
                $(".tasks-progress-bar").attr("data-done", done_tasks);

                var total_tasks = parseInt($(".tasks-progress-bar").attr("data-total"));
                var new_percent = (done_tasks / total_tasks) * 100;
                $(".tasks-progress-bar").attr("aria-valuenow", new_percent).css("width", new_percent + "%").text(Math.round(new_percent) + "%");
                var status_id = 103; //done
            } else {
                var done_tasks = parseInt($(".tasks-progress-bar").attr("data-done")) - 1;
                $(".tasks-progress-bar").attr("data-done", done_tasks);

                var total_tasks = parseInt($(".tasks-progress-bar").attr("data-total"));
                var new_percent = (done_tasks / total_tasks) * 100;
                $(".tasks-progress-bar").attr("aria-valuenow", new_percent).css("width", new_percent + "%").text(Math.round(new_percent) + "%");
                var status_id = 85; //not done
            }

            $.ajax({
                url: '/ajax/projectTaskStatusUpdate',  //server script to process data
                type: 'POST',
                async: true,
                data: {pid: '<?php echo $task->task_id?>', tid: child_id, stat: status_id},
                success: function (result) {
                }
            });

        });


        // picker
        jQuery('.datetime').datetimepicker({
            format: 'm/d/Y H:i',
            mask: false
        });
    });


</script>