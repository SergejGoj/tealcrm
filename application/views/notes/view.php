<div class="row">
    <div class="col-md-3 col-sm-5 panel panel-default panel-body">

        <div class="btn-group">
            <a href="<?php echo site_url('notes/edit') . "/" . $note->note_id; ?>" class="btn btn-tertiary">Edit
                Note</a>
            <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span
                    class="caret"></span></button>

            <ul class="dropdown-menu">
                <li>
                    <a href="javascript:delete_one( '<?php echo $note->note_id; ?>' );">Delete Note</a>
                </li>
            </ul>
        </div><!-- /.btn-gruop -->
        <br/></br>


        <ul id="myTab1" class="nav nav-tabs">
            <li class="active">
                <a href="#details" data-toggle="tab">Details</a>
            </li>

        </ul>
        <div id="myTab1Content" class="tab-content">

            <div class="tab-pane fade active in" id="details">
                <ul class="icons-list">
                    <?php if (!empty($note->assigned_user_id)) { ?>
                        <li>
                            <div>
                                <i class="icon-li fa fa-user"></i> <strong>assigned user</strong>
                            </div>
                            <?php
                                $first_name = $_SESSION['user_accounts'][$note->assigned_user_id]['upro_first_name'];
                                $last_name = $_SESSION['user_accounts'][$note->assigned_user_id]['upro_last_name'];
                                if(($first_name != NULL) && ($last_name != NULL)) {
                                    echo $first_name." ".$last_name;
                                } else if($first_name != NULL) {
                                    echo $first_name;
                                } else if($last_name != NULL) {
                                    echo $last_name;
                                } else {
                                    echo $_SESSION['user_accounts'][$note->assigned_user_id]['uacc_username'];
                                }
                            ?>
                        </li>
                    <?php } ?>

                    <?php if (!empty($note->created_by)) { ?>
                        <li>
                            <div>
                                <i class="icon-li fa fa-user"></i> <strong>created</strong>
                            </div><?php echo date('m/d/Y h:ia', strtotime($note->date_entered . ' UTC')) ?> by<br/>
                            <?php echo $_SESSION['user_accounts'][$note->created_by]['upro_first_name'] . " " . $_SESSION['user_accounts'][$note->created_by]['upro_last_name']; ?>
                        </li>
                    <?php } ?>
                    <?php if (!empty($note->modified_user_id)) { ?>
                        <li>
                            <div>
                                <i class="icon-li fa fa-user"></i> <strong>modified</strong>
                            </div><?php echo date('m/d/Y h:ia', strtotime($note->date_modified . ' UTC')) ?> by<br/>
                            <?php echo $_SESSION['user_accounts'][$note->modified_user_id]['upro_first_name'] . " " . $_SESSION['user_accounts'][$note->modified_user_id]['upro_last_name']; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>

        </div>


    </div><!-- /.col -->

    <div class="col-md-6 col-sm-7">

        <h2 class="text-left"><strong><?php echo $note->subject ?></strong></h2>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Description</h3>
            </div>

            <div class="panel-body" id="note_feed_body">
                <div class="share-widget clearfix">

                    <?php echo $note->description ?>
                </div>
            </div>
        </div>
        <br class="visible-xs">
        <br class="visible-xs">

        <!--------------------------custom_tab_start-------------------------------------------->
        <?php if ($more_info == 1) { ?>

            <div class="tab-pane fade in active" id="profile-tab">
                <div class="row">
                    <div class="col-md-12">

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
        <?php } ?>
    </div><!-- /.col -->

    <div class="col-md-3" style="background-color: #EEEEEE; padding-top: 25px;">

        <?php if ($note->filename_original != '') { ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Attachment</h4>
                </div><!-- /.panel-heading -->

                <div class="panel-collapse collapse in" id="collapseOne">
                    <div class="panel-body">
                        <i class="fa fa-paperclip"></i>
                        <a href="<?php echo site_url('notes/download_note/' . $note->note_id); ?>" target="_blank">Download
                            Attachment</a></div><!-- /.panel-body -->
                </div><!-- /.panel-collapse -->
            </div><!-- /.panel -->

        <?php } ?>


        <?php
        if (!empty($note->company_name)) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Company</h4>
                </div><!-- /.panel-heading -->

                <div class="panel-collapse collapse in" id="collapseOne">
                    <div class="panel-body">
                        <a href="<?php echo site_url('companies/view') . "/" . $note->company_id; ?>"><?php echo $note->company_name; ?></a>
                    </div><!-- /.panel-body -->
                </div><!-- /.panel-collapse -->
            </div><!-- /.panel -->
        <?php } // end if company name ?>

        <?php
        if (!empty($note->person_name)) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Person</h4>
                </div><!-- /.panel-heading -->

                <div class="panel-collapse collapse in" id="collapseOne">
                    <div class="panel-body">
                        <a href="<?php echo site_url('people/view') . "/" . $note->people_id; ?>"><?php echo $note->person_name . ' ' . $note->person_last; ?></a>
                    </div><!-- /.panel-body -->
                </div><!-- /.panel-collapse -->
            </div><!-- /.panel -->
        <?php } // end if company name ?>


    </div><!-- /.col -->


    <!--------------------------custom_tab_end-------------------------------------------->

</div><!-- /.row -->


<script type="text/javascript">
    delete_one = function (note_id) {
        // confirm
        Messi.ask('Do you really want to delete the record?', function (val) {
            // confirmed
            if (val == 'Y') {
                window.location.href = "<?php echo site_url('notes/delete')?>/" + note_id;
            }
        }, {modal: true, title: 'Confirm Delete'});
    }


    cancel = function (elm) {
        window.location.href = '<?php echo site_url('notes')?>';
        return false;
    }

    edit = function (elm, note_id) {
        window.location.href = '<?php echo site_url('notes/edit')?>/' + note_id;
        return false;
    }

    $(document).ready(function () {
        $("#add_note_note").on("click", function () {
            var desc = $.trim($("#note_feed_body textarea").val());
            if (desc.length < 1) return false;

            $.ajax({
                url: '/feeds/add',  //server script to process data
                type: 'POST',
                async: true,
                data: {id: '<?php echo $note->note_id?>', description: desc, cat: 4},
                success: function (result) {
                    //$("#note_feed_body").append(result);
                    $("#note_feed_body>.share-widget").after(result);
                    $("#note_feed_body textarea").val('');
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
                data: {id: '<?php echo $note->note_id?>', last: lastFetchedFeed, cat: 4},
                success: function (result) {
                    $(".panel .feed-more").text("Load More ").removeClass("active").find('i').remove();
                    var resultObject = $.parseJSON(result);
                    lastFetchedFeed = resultObject.last;
                    $(resultObject.value).insertBefore($("#note_feed_body .feed-more"));

                }
            });
        });
    });

</script>