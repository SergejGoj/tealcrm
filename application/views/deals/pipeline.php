<h2>Deals</h2>


<a data-toggle="modal" href="<?php echo site_url('deals/add') ?>" class="btn btn-primary">Add New Deal</a>
<a data-toggle="modal" href="<?php echo site_url('deals') ?>" class="btn btn-info">Show List View</a>
<br/><br/>


<style type="text/css">


    #closed_slider {
        top: center;
        bottom: center;
        right: 0px;
        display: none;
        position: fixed;
        z-index: 200;
    }

</style>


<div id="closed_slider">

    <table width="210px" border=0>
        <tr style="vertical-align: middle;">
            <td style="align: center; vertical-align: middle; background-color: #cbffa0; background-color: rgba(168,229,197,0.7); background-image:url(/assets/img/cm.png); background-repeat: no-repeat; background-position: center center; border-style: dashed; border-width: 2px; width: 210px;">
                <ul style='list-style-type: none; margin: 0 5px 5px 5px; padding: 0.5em 0.5em 0.5em 0.5em;   min-width: 240px; min-height: 210px;'
                    id="61" ss_id="61" class="connectedSortable"></ul>
            </td>
        </tr>
        <tr>
            <td style="align: center; vertical-align: middle; background-color: #ffc0bb; background-color: rgba(255,120,120,0.7); background-image:url(/assets/img/redx.png); background-repeat: no-repeat; background-position: center center; border-style: dashed; border-width: 2px; width: 210px;">
                <ul style='list-style-type: none; margin: 0 5px 5px 5px; padding: 0.5em 0.5em 0.5em 0.5em; float: left; min-width: 210px; min-height: 220px;'
                    id="62" ss_id="62" class="connectedSortable"></ul>
            </td>
        </tr>
    </table>


</div>


<div class="row">

    <div class="col-md-12">

        <div class="table-responsive">

            <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
            <link rel="stylesheet" href="/assets/css/visual_pipeline.css">
            <form id="ss_update">
                <input type="hidden" name="deal_id" id="deal_id" value="">
                <input type="hidden" name="deal_ss" id="deal_ss" value="">
            </form>


            <script type="text/javascript">

                function delay(ms) {
                    var cur_d = new Date();
                    var cur_ticks = cur_d.getTime();
                    var ms_passed = 0;
                    while (ms_passed < ms) {
                        var d = new Date();  // Possible memory leak?
                        var ticks = d.getTime();
                        ms_passed = ticks - cur_ticks;
                        // d = null;  // Prevent memory leak?
                    }
                }


                function refreshOT(old_sales_stage, new_sales_stage) {


                    var url = document.URL;
                    url2 = url.replace("deals", "deals/get_deal_totals");

                    old_sales_stage = old_sales_stage.replace(/ /g, "_");
                    old_sales_stage = old_sales_stage.replace(/\./g, "_");
                    old_sales_stage = old_sales_stage.replace(/\//g, "_");

                    new_sales_stage = new_sales_stage.replace(/ /g, "_");
                    new_sales_stage = new_sales_stage.replace(/\//g, "_");
                    new_sales_stage = new_sales_stage.replace(/\./g, "_");

                    $.ajax({
                        type: "POST",
                        url: url2,
                        data: $("#" + old_sales_stage + "_totals").serialize(), // serializes the forms elements.
                        success: function (data) {
                            var new_string = data.split("QQQQQ")[1];
                            document.getElementById("deal_total_" + old_sales_stage).innerHTML = new_string;

                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: url2,
                        data: $("#" + new_sales_stage + "_totals").serialize(), // serializes the forms elements.
                        success: function (data) {
                            var new_string = data.split("QQQQQ")[1];

                            if ((new_sales_stage != 61) && (new_sales_stage != 62))
                                document.getElementById("deal_total_" + new_sales_stage).innerHTML = new_string;

                        }
                    });


                }


            </script>


            <?php
            $sales_string = '';


            foreach ($sales_stage as $key => $value) {


                $key_edit = str_replace(" ", "_", $key);
                $key_edit = str_replace("/", "_", $key_edit);
                $key_edit = str_replace(".", "_", $key_edit);
                $sales_string = $sales_string . "#" . $key_edit . ",";

            }

            $sales_string = trim($sales_string, ",");
            $sales_string = $sales_string . ",#61,#62";


            ?>

            <style>
                <?php echo $sales_string; ?>
                {
                    list-style-type: none;
                    margin: 0 5px 5px 5px;
                    padding: 0 0 2.5em;
                    float: left;
                }
                <?php $sales_string2 = str_replace(","," li,",$sales_string); $sales_string2 = $sales_string2." li"; ?>
                <?php echo $sales_string2; ?>
                {
                    list-style-type: none;
                    margin: 0 5px 5px 5px;
                    padding: 0 0 2.5em;
                    float: left;
                    font-size: 1.2em;
                    width: 175px;
                }
                <?php $sales_string3 = $sales_string; ?>
                <?php echo $sales_string3; ?>
                {
                    list-style-type: none;
                    min-width: 150px;
                    margin: 0 5px 5px 5px;
                }
            </style>

            <script>

                $(function () {
                    var old_sales_stage;

                    function setOriginalSub(ui) {
                        old_sales_stage = $(ui.item[0]).parent().attr('ss_id');
                    }
                    <?php echo "$( \"" . $sales_string . "\" ).sortable({"; ?>
                        tolerance: "pointer",
                        cursor: "pointer",
                        connectWith:".connectedSortable",
                        helper: 'clone',
                        // start is called when the item is grabbed from its original list
                        start: function (event, ui) {
                            setOriginalSub(ui);

                            $("#closed_slider").show("slide", {direction: 'right'});
                            $("#61").sortable("refresh");
                            $("#62").sortable("refresh");
                        },
                        // sort is called when the item is picked up and dragging begins.
                        sort: function (event, ui) {
                            $("#61").sortable("refresh");
                            $("#62").sortable("refresh");
                        },
                        stop: function (event, ui) {
                            $("#61").sortable("refresh");
                            $("#62").sortable("refresh");
                            $("#closed_slider").hide("slide", {direction: 'right'}, 100);
                        },
                        // receive is called when an item makes the transition to a new sales stage successfully.
                        receive: function (event, ui) {
                            var new_sales_stage = ui.item.parent().attr("ss_id");
                            document.getElementById("deal_id").value = ui.item.attr("deal_id");
                            document.getElementById("deal_ss").value = ui.item.parent().attr("ss_id");
                            var url = document.URL;
                            url2 = url.replace("deals", "deals/update_ss");


                            $.ajax({
                                type: "POST",
                                url: url2,
                                data: $("#ss_update").serialize(), // serializes the forms elements.
                                success: function (data) {
                                    //     alert(data); // show response from the php script.
                                    //location.reload(true);
                                    refreshOT(old_sales_stage, new_sales_stage);

                                    if ((new_sales_stage == 61) || (new_sales_stage == 62)) {

                                        $("#" + ui.item.attr("deal_id")).hide('explode', 0, 1500);

                                    }

                                    $("#closed_slider").hide("slide", {direction: 'right'}, 100);

                                }
                            });
                        }
                    }).disableSelection();
                });
            </script>

            <table width="100%" border=0 cellpadding=0 cellspacing=0>

                <?php

                // **********
                // Get deals for given stage
                // **********

                // prepare query to get all deals for given sales stage
                // assumes that sales_stage ID for Close Won and Lost will NEVER change - set to 61,62
                $query = "SELECT name, value, sales_stage_id, assigned_user_id, deal_id, expected_close_date from sc_deals WHERE (deleted = 0) AND ((sales_stage_id != 61) OR (sales_stage_id != 62)) ";

                // setup query for database
                $deals_query = $this->db->query($query);

                // get number of deals in this query
                $num_deals = $this->db->affected_rows();

                // placeholder array for storing deal information
                $deals_array = $deals_query->result();


                // get deals total
                $dollar_amount_query = 0;


                $deal_total = Array();

                // cycle through array to get dollar totals without hitting database
                foreach ($deals_array as $row) {


                    // check for real number
                    if (is_numeric($row->value)) {
                        // If the array to hold the amount and count of results for this sales stage doesn't exist, create it to avoid PHP Warnings
                        if (!isset($deal_total[$row->sales_stage_id])) {
                            $deal_total[$row->sales_stage_id] = '';
                        }


                        // If the Amount variable for this sales stage isn't set, set it to 0 to avoid PHP warnings.
                        if (!isset($deal_total[$row->sales_stage_id]['amount'])) {
                            $deal_total[$row->sales_stage_id]['amount'] = 0;
                        }

                        // Update the Amount Variable based on the total deals
                        $deal_total[$row->sales_stage_id]['amount'] = $deal_total[$row->sales_stage_id]['amount'] + $row->value;


                        if (!isset($deal_total[$row->sales_stage_id]['count'])) {
                            $deal_total[$row->sales_stage_id]['count'] = 0;
                        }
                        $deal_total[$row->sales_stage_id]['count'] = $deal_total[$row->sales_stage_id]['count'] + 1;
//			$dollar_amount_query = $row->value + $dollar_amount_query;
                    }

                }


                //	$deal_total = number_format($dollar_amount_query,2);


                // cycle through each sales stage to produce a row


                foreach ($sales_stage as $sales_stage_key => $sales_stage_value) {
//		echo $sales_stage_key."!!!!!";

                    // produce first column showing the sales stage name and details
                    ?>
                    <tr>
                        <td class="vp2">
                            <?php

                            //$sales_stage = $key;

                            $key_edit = str_replace(" ", "_", $sales_stage_key);
                            $key_edit = str_replace("/", "_", $key_edit);
                            $key_edit = str_replace(".", "_", $key_edit);


                            echo "<form id='" . $key_edit . "_totals'>";
                            echo "<input type=hidden name=sales_stage_key_edit value='" . $key_edit . "'>";
                            echo "<input type=hidden name=sales_stage_key value='" . $key_edit . "'>";
                            echo "<input type=hidden name=sales_stage_value value='" . $sales_stage_value . "'>";
                            echo "</form>";

                            ?>

                            <div id="deal_total_<?php echo $key_edit; ?>"
                                 style="font-family: Arial,Helvetica,clean,sans-serif; font-size: 14px; font-weight: bold;">

                                <p class="ss_bold">

                                    <?php echo $sales_stage_value; ?></p> <br>

                                <?php

                                // if the amounts or counts for the current sales stage don't exist, set them to 0.
                                if (!isset($deal_total[$key_edit]['amount'])) {
                                    $deal_total[$key_edit]['amount'] = number_format(0, 2);
                                }
                                if (!isset($deal_total[$key_edit]['count'])) {
                                    $deal_total[$key_edit]['count'] = 0;
                                }


                                // display $ and count of deals for current sales stage
                                echo "<p class='ss_amount'>$" . number_format($deal_total[$key_edit]['amount'], 2) . "<br/><br/>" . $deal_total[$key_edit]['count'] . " Deals</p>";

                                $col_count = 0;

                                // using x to determine if we are on the first row so that we can set a top border
                                $x = 0;

                                // display appropriate td tag based on $x

                                if ($x == 0)
                                    echo '<td class="vp1st">';
                                else
                                    echo '<td class="vp1">';

                                $x == 1;

                                // display a UL for list of items
                                ?>
                                <ul style='list-style-type: none; margin: 0 5px 5px 5px; padding: 0 0 2.5em; float: left; min-width: 600px; max-width: 600px; min-height: 80px;'
                                    id="<?php
                                    echo $key_edit; ?>" ss_id="<?php echo $key_edit; ?>" class="connectedSortable">

                                    <?php

                                    // cycle through deals
                                    foreach ($deals_array as $deal) {

                                        // check to make sure the deal has the same id as the current sales stage
                                        if ($sales_stage_key == $deal->sales_stage_id) {
                                            ?>
                                            <li class="deal_item" deal_id="<?php echo $deal->deal_id; ?>"
                                                id="<?php echo $deal->deal_id; ?>">
                                                <div class="list_item" width="100%" height="100%">
                                                    <p>
                                                        <?php

                                                        echo '<p class="larger_bold" title="' . $deal->name . '">';

                                                        if (strlen($deal->name) > 13) {

                                                            $deal_name_short = substr($deal->name, 0, 12);
                                                            $deal_name_short = $deal_name_short . "...";
                                                        } else {

                                                            $deal_name_short = $deal->name;
                                                        }

                                                        echo "<a style='color: #000000;' href=/deals/view/" . $deal->deal_id . ">" . $deal_name_short . "</a>"; ?>
                                                    </p>
                                                    <br>
                                                    <p class="normal">
                                                        <?php echo "$" . number_format($deal->value, 2); ?> |
                                                        <?php
                                                        $un_query = "SELECT * from `sc_user_accounts` where (id = '" . $deal->assigned_user_id . "')";
                                                        $un_result = $this->db->query($un_query);
                                                        $un_row = $un_result->result();
                                                        foreach ($un_row as $user) {
                                                            $first_name = $_SESSION['user_accounts'][$deal->assigned_user_id]['first_name'];
                                                            $last_name = $_SESSION['user_accounts'][$deal->assigned_user_id]['last_name'];
                                                            if (($first_name != NULL) && ($last_name != NULL)) {
                                                                $user_name = $first_name . " " . $last_name;
                                                            } else if ($first_name != NULL) {
                                                                $user_name = $first_name;
                                                            } else if ($user->last_name != NULL) {
                                                                $user_name = $last_name;
                                                            } else if ($last_name != NULL) {
                                                                $user_name = $user->username;
                                                            } else {
                                                                $user_name = 'nobody';
                                                            }
                                                            echo "<font color='#6A82CC'>" . $user_name . "</font>";

                                                        }
                                                        ?>
                                                    <p class="smaller_text">Exp close
                                                        date: <?php echo $deal->expected_close_date; ?></p></div>
                                            </li>
                                            <?php
                                        } // end if sales stage key equals deal sales stage id

                                    } // end foreach of the deals cycle
                                    ?>
                                </ul>

                        </td>
                    </tr>
                    <?php

                }

                ?>

            </table>

        </div> <!-- /.table-responsive -->

    </div> <!-- /.col -->


</div> <!-- /.row -->



