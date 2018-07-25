<div class="layout layout-main-right layout-stack-sm">
    <div class="col-md-3 col-sm-4 layout-sidebar">
        <li class="fa fa-calendar-o" style="color:#c0c0c0;font-family:'FontAwesome'"> Meeting</li>
        <br/>
        <h2><?php echo $meeting->subject; ?></h2>

        <div class="btn-group">
            <a href="<?php echo site_url('meetings/edit') . "/" . $meeting->meeting_id; ?>" class='btn btn-tertiary'>Edit
                Meeting</a>
            <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span
                    class="caret"></span></button>

            <ul class="dropdown-menu">

                <li>
                    <a href="javascript:delete_one( '<?php echo $meeting->meeting_id; ?>' );">Delete Meeting</a>
                </li>
            </ul>
        </div>
        <br/><br/>
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

        </ul>
        <ul class="icons-list">

            <li>
                <div>
                    <i class="icon-li fa fa-pencil"></i> <strong>Event Type</strong>
                </div><?php echo $_SESSION['drop_down_options'][$meeting->event_type]['name']; ?>
            </li>
            <li>
                <div>
                    <i class="icon-li fa fa-map-marker"></i> <strong>Location</strong>
                </div><?php echo $meeting->location; ?>
            </li>
            <?php
            if ($meeting->date_start == '0000-00-00 00:00:00') { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-clock-o"></i> <strong>Start Date</strong>
                    </div><?php echo "Not set"; ?>
                </li>
            <?php } else { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-clock-o"></i> <strong>Start Date</strong>
                    </div><?php echo date('m/d/Y h:ia', strtotime($meeting->date_start . ' UTC')) ?>
                </li>
            <?php }
            ?>
            <?php
            if ($meeting->date_end == '0000-00-00 00:00:00') { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-clock-o"></i> <strong>End Date</strong>
                    </div><?php echo "Not set"; ?>
                </li>
            <?php } else { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-clock-o"></i> <strong>End Date</strong>
                    </div><?php echo date('m/d/Y h:ia', strtotime($meeting->date_end . ' UTC')) ?>
                </li>
            <?php }
            ?>
            <li>
                <div>
                    <i class="icon-li fa fa-user"></i> <strong>Assigned User</strong>
                </div>
                <?php
                    $first_name = $_SESSION['user_accounts'][$meeting->assigned_user_id]['upro_first_name'];
                    $last_name = $_SESSION['user_accounts'][$meeting->assigned_user_id]['upro_last_name'];
                    if(($first_name != NULL) && ($last_name != NULL)) {
                        echo $first_name." ".$last_name;
                    } else if($first_name != NULL) {
                        echo $first_name;
                    } else if($last_name != NULL) {
                        echo $last_name;
                    } else {
                        echo $_SESSION['user_accounts'][$meeting->assigned_user_id]['uacc_username'];
                    }
                ?>
            </li>
            <?php if (!empty($meeting->created_by)) { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-user"></i> <strong>Created</strong>
                    </div><?php echo date('m/d/Y h:ia', strtotime($meeting->date_entered . ' UTC')) ?> by<br/>
                    <?php echo $_SESSION['user_accounts'][$meeting->created_by]['upro_first_name'] . " " . $_SESSION['user_accounts'][$meeting->created_by]['upro_last_name']; ?>
                </li>
            <?php } ?>
            <?php if (!empty($meeting->modified_user_id)) { ?>
                <li>
                    <div>
                        <i class="icon-li fa fa-user"></i> <strong>Modified</strong>
                    </div><?php echo date('m/d/Y h:ia', strtotime($meeting->date_modified . ' UTC')) ?> by<br/>
                    <?php echo $_SESSION['user_accounts'][$meeting->modified_user_id]['upro_first_name'] . " " . $_SESSION['user_accounts'][$meeting->modified_user_id]['upro_last_name']; ?>
                </li>
            <?php } ?>
        </ul>


    </div><!-- /.col -->

    <!-- /.row -->


    <div class="col-md-9 col-sm-8 layout-main">
        <h3 class="content-title">Overview</h3>


        <div id="settings-content" class="tab-content stacked-content">
            <div class="tab-pane fade in active" id="profile-tab">


                <div class="row">
                    <!--
                                <div class="tab-pane fade in active" id="persons-tab">
                          <h3 class="content-title">persons</h3>


                                  <div class="row">


                                      <div class="col-md-7">




                        <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Related persons</h3>
                                </div>

                                <div class="panel-body">
                                    <div class="panel-body">
                                            <div class="list-group">
                                                <a class="list-group-item" href="javascript:;">
                                                <h5 class="list-group-item-heading">John Doe</h5>

                                                <p class="list-group-item-text">CEO</p></a> <a class="list-group-item" href="javascript:;">
                                                <h5 class="list-group-item-heading">Barb Smith</h5>

                                                <p class="list-group-item-text">VP</p></a>
                                            </div>
                                            <a href="javascript:;">View All persons <i class="fa fa-external-link"></i></a>
                                        </div>
                                    </div>
                                </div>


                            </div>

                                  </div>

                         </div>  -->

                    <!-- 30jan2015 ######################################################-->
                    <div class="col-md-6">
                        <?php
                        if (!empty($meeting->description)) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Description</h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $meeting->description; ?>
                                </div> <!-- end panel body -->
                            </div>
                        <?php } ?>
                        <?php
                        if (!empty($meeting->company_name)) {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Company</h4>
                                </div><!-- /.panel-heading -->

                                <div class="panel-collapse collapse in" id="collapseOne">
                                    <div class="panel-body">
                                        <a href="<?php echo site_url('companies/view') . "/" . $meeting->company_id; ?>"><?php echo $meeting->company_name; ?></a>
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel-collapse -->
                            </div><!-- /.panel -->
                        <?php } ?>
                        <?php
                        if (!empty($meeting->person_name)) {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Person</h4>
                                </div><!-- /.panel-heading -->

                                <div class="panel-collapse collapse in" id="collapseOne">
                                    <div class="panel-body">
                                        <a href="<?php echo site_url('people/view') . "/" . $meeting->people_id; ?>"><?php echo $meeting->person_name . ' ' . $meeting->person_last; ?></a>
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel-collapse -->
                            </div><!-- /.panel -->
                        <?php } // end if company name ?>

                    </div>
                    <div class="col-md-6">


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Messages</h3>
                            </div>

                            <div class="panel-body" id="meetings_feed_body">
                                <div class="share-widget clearfix">
                                    <textarea class="form-control share-widget-textarea" placeholder="Add Comment..."
                                              rows="3" tabindex="1"></textarea>

                                    <div class="share-widget-actions">

                                        <div class="pull-right">
                                            <button class="btn btn-primary btn-sm" id="add_note_meetings" tabindex="2">
                                                Add Comment
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
                    <!-- 30jan2015 ##########################################################-->

                    <!--	<div class="tab-pane fade" id="tasks-tab">
                    <h3 class="content-title">Tasks</h3>
                            <div class="row">
                                <div class="col-md-12">

                                <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Related Tasks</h3>
                            </div>

                            <div class="panel-body">
                                <div class="panel-body">

                                <p class="list-group-item-text">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>

                                    </div>
                                </div>
                            </div>


                                </div>
                            </div>

                        </div>  -->

                    <!--	<div class="tab-pane fade" id="deals-tab">
                    <h3 class="content-title">Deals</h3>
                            <div class="row">
                                <div class="col-md-12">

                                <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Related Deals</h3>
                            </div>

                            <div class="panel-body">
                                <div class="panel-body">

                                <p class="list-group-item-text">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch..</p>

                                    </div>
                                </div>
                            </div>


                                </div>
                            </div>

                        </div> -->

                    <!--	<div class="tab-pane fade" id="notes-tab">
                    <h3 class="content-title">Notes</h3>
                            <div class="row">
                                <div class="col-md-12">

                                <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Related Notes</h3>
                            </div>

                            <div class="panel-body">
                                <div class="panel-body">

                                <p class="list-group-item-text">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>

                                    </div>
                                </div>
                            </div>


                                </div>
                            </div>

                        </div>  -->

                    <!--	<div class="tab-pane fade" id="meetings-tab">
                    <h3 class="content-title">Meetings</h3>
                            <div class="row">
                                <div class="col-md-12">

                                <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Related Meetings</h3>
                            </div>

                            <div class="panel-body">
                                <div class="panel-body">

                                <p class="list-group-item-text">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>

                                    </div>
                                </div>
                            </div>


                                </div>
                            </div>

                        </div> -->


                </div> <!-- /.tab-content -->
            </div>

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


                <!--------------------------custom_tab_end-------------------------------------------->


            </div>

        </div> <!-- /.col -->


    </div> <!-- /.row -->


</div>


<script type="text/javascript">
    cancel = function (elm) {
        window.location.href = '<?php echo site_url('meetings')?>';
        return false;
    }

    edit = function (elm, id) {
        window.location.href = '<?php echo site_url('meetings/edit')?>/' + id;
        return false;
    }

    $(document).ready(function () {
        $("#add_note_meetings").on("click", function () {
            var desc = $.trim($("#meetings_feed_body textarea").val());
            if (desc.length < 1) return false;

            $.ajax({
                url: '/feeds/add',  //server script to process data
                type: 'POST',
                async: true,
                data: {id: '<?php echo $meeting->meeting_id?>', description: desc, cat: 6},
                success: function (result) {
                    //$("#meetings_feed_body").append(result);
                    $("#meetings_feed_body>.share-widget").after(result);
                    $("#meetings_feed_body textarea").val('');
                }
            });
        });

        var lastFetchedFeed = 5;
        $(".panel .feed-more").on("click", function () {
            $(this).text("Loading ").addClass("active").append(' <i class="fa fa-gear fa-spin"></i>');
            $.ajax({
                url: '/ajax/more',  //server script to process data
                type: 'POST',
                async: true,
                data: {id: '<?php echo $meeting->meeting_id?>', last: lastFetchedFeed, cat: 6},
                success: function (result) {
                    $(".panel .feed-more").text("Loading ").removeClass("active").find('i').remove();
                    var resultObject = $.parseJSON(result);
                    lastFetchedFeed = resultObject.last;
                    $(resultObject.value).insertBefore($("#meetings_feed_body .feed-more"));

                }
            });
        });

    });
    delete_one = function (meeting_id) {
        Messi.ask('Do you really want to delete the record?', function (val) {
            if (val == 'Y') {
                window.location.href = "<?php echo site_url('meetings/delete')?>/" + meeting_id;
            }
        }, {modal: true, title: 'Confirm Delete'});
    }
</script>