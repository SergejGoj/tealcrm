<link href='css/mobileadvancedsearch.css' rel='stylesheet' />
<style>
/*style for the search box, move it to css files later*/
#search_result_tr, #filter_val_box, #filter_box_btn{
	display:none;
}
.chosen-container-multi .chosen-choices {
	width:215px !important;
}
.chosen-container .chosen-drop {
	width:215px !important;
}
</style>
<script src="/assets/js/plugins/chosen/js/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="/assets/js/plugins/chosen/css/chosen.css">

<h3 class="content-title">Meetings</h3>

  <div class="row">

    <div class="col-md-12">

      <div class="table-responsive">
		<div class="form-group">
            <ul id="myTab1" class="nav nav-tabs">
                <li class="<?php if($search_tab == "basic"){ echo 'active';}?>">
                    <a href="#search" data-toggle="tab">Search</a>
                </li>

                <li class="<?php if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
                    <a href="#advanced" data-toggle="tab">Advanced Search</a>
                </li>

                <?php
                if(isset($_SESSION['saved_searches_index']['meetings'])){
                    if(count($_SESSION['saved_searches_index']['meetings']) > 0){
                        echo '          <li class="dropdown">
		            <a href="javascript:;" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">
		              Saved Searches <b class="caret"></b>
		            </a><ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">';
                        foreach($_SESSION['saved_searches_index']['meetings'] as $key => $value){
                            echo '<li class=""><a href="'.site_url('meetings/search/'.$key).'">'.$value.'</a></li>';
                        }
                        echo '</ul>';
                    }
                }
                ?>
                </li>


            </ul>

            <div id="myTab1Content" class="tab-content">

                <div class="tab-pane fade <?php if($search_tab == "basic"){ echo 'active in';}?>" id="search">
                    <form name="frmedit" id="frmedit" action="<?php echo site_url('meetings/search');?>" method="post" class="form parsley-form">
                        <div class="input-group">
                            <label for="search_box" class="sr-only">Search</label>
                            <input type="search" class="form-control" id="search_box" placeholder="Search by subject" name="subject" value="<?php if(isset($_SESSION['search']['meetings']['subject'])){echo $_SESSION['search']['meetings']['subject'];}?>">
                            <div class="input-group-btn">
                                <input type="submit" name="search_go" class="btn btn-success" value="Search">
                                <input type="submit" name="clear" class="btn btn-success" value="Clear">
                            </div><!-- /input-group-btn -->
                        </div>
                    </form>
                </div> <!-- /.tab-pane -->

                <div class="tab-pane fade <?php if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active in';}?>" id="advanced">

                    <form name="frmedit" id="frmedit" action="<?php echo site_url('meetings/search');?>" method="post" class="form parsley-form">
                        <table class="table table-striped table-bordered" style="font-size:11px;">
                            <tbody>
                            <tr valign="middle">
                                <td width="25%"><span><strong>Subject</strong>
						  <input type="text" class="form-control" name="subject" id = "subject" value="<?php if(isset($_SESSION['search']['meetings']['subject'])){echo $_SESSION['search']['meetings']['subject'];}?>">
                                </td>
                                <td width="25%"><span><strong>Location</strong>
					   <input type="text" class="form-control" name="location" id = "location" value="<?php if(isset($_SESSION['search']['meetings']['location'])){echo $_SESSION['search']['meetings']['location'];}?>">
                                </td>
                                <td width="25%"><span><strong>Company</strong>
					  	<input type="text" name="company_viewer" id="company_viewer" class="form-control" value="<?php if(isset($company->company_id)){echo $company->company_name;}?>" />
							<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($_SESSION['search']['meetings']['company_id'])){echo $_SESSION['search']['meetings']['company_id'];}?>" />
                                </td>
                                <td width="25%"><span><strong>Event Type</strong><br>
					  	<select name="event_type[]" id = "event_type" multiple="true" multiple class="form-control chosen-select">
                            <option value=""></option>
                            <?php
                            $event_type = lookupDropDownValues("event_type");
                            foreach($event_type as $option){
                                ?>
                                <option value="<?php echo $option;?>" <?php if(isset($_SESSION['search']['meetings']['event_type'])){ foreach($_SESSION['search']['meetings']['event_type'] as $opn) if($opn==$option){echo 'selected';};}?>><?php echo $_SESSION['drop_down_options'][$option]['name'];?></option>
                            <?php	}	?>
                        </select>

                                </td>
                            </tr>

                            <tr>
                                <td><span><strong>Date Entered Between</strong></span>
                                    <input class="form-control datetime" id="date_entered_start" name="date_entered_start" type="text" value="<?php if(isset($_SESSION['search']['meetings']['date_entered_start'])){echo $_SESSION['search']['meetings']['date_entered_start'];}?>">
                                    <input class="form-control datetime" id="date_entered_end" name="date_entered_end" type="text" value="<?php if(isset($_SESSION['search']['meetings']['date_entered_end'])){echo $_SESSION['search']['meetings']['date_entered_end'];}?>">
                                </td>
                                <td><span><strong>Date Modified Between</strong></span>
                                    <input class="form-control datetime" id="date_modified_start" name="date_modified_start" type="text" value="<?php if(isset($_SESSION['search']['meetings']['date_modified_start'])){echo $_SESSION['search']['meetings']['date_modified_start'];}?>">
                                    <input class="form-control datetime" id="date_modified_end" name="date_modified_end" type="text" value="<?php if(isset($_SESSION['search']['meetings']['date_modified_end'])){echo $_SESSION['search']['meetings']['date_modified_end'];}?>">

                                </td>
                                <td colspan="2" align="right">

                                    <input type="submit" name="adv_search_go" class="btn btn-success" value="Search">
                                    <?php
                                    if($search_tab == "saved"){
                                        ?>
                                        <input type="button" name="clear" onclick="window.location.href='<?php echo site_url('meetings/search/' . $_SESSION['search_id'] . '/delete/')?>'" class="btn btn-danger" name='DeleteSearch' value="Delete Saved Search">
                                    <?php
                                    }
                                    else{
                                        ?>
                                        <input type="submit" data-toggle="modal" data-target="#save-modal" name="adv_search_save" id = "adv_search_save" class="btn btn-warning" value="Search & Save" onclick="javascript: $('#searchLabel').text('Save Advanced Search'); $('#module').val('meetings'); return false;">
                                    <?php
                                    }
                                    ?>

                                    <input type="submit" name="clear" class="btn btn-success" value="Clear">

                                </td>
                            </tr>
                            </tbody>
                        </table>


                </div> <!-- / .tab-pane -->

            </div> <!-- /.tab-content -->
            <!-- Save Advanced Search Modal -->
            <div class="modal fade" id="save-modal" tabindex="-1" role="dialog" aria-labelledby="searchLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="searchLabel"></h4>
                        </div>
                        <div class="modal-body">
                            Please provide an easy to remember name for this saved search<br/>

                            <input type="text" name="saved_search_name" class="form-control"/><br/>
                            <input type="submit" class="btn btn-warning" name="saved_search_result" value="Save Search">

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
            </form>
        </div>

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('meetings')?>" method="post">
		  	 <table class="table table-bordered table-striped table-condensed">
				  <thead>
					  <tr>
						  <th><input type="checkbox" name="select_all" value="ids[]"></th>
						   <?php if(isset($meeting_updated_fields))
						  foreach($meeting_updated_fields as $field_list){ ?>
						  <th><?php echo $field_label[$field_list->field_name]["label_name"]; ?></th>
						  <?php }?>
						  <th>Actions</th>
					  </tr>
				  </thead>
				  <tbody>
					<?php if( ! $meetings->exists() ) :?>
					<tr>
						<td colspan="10" align="center">No Meeting Schedule</td>
					</tr>
					<?php else: foreach($meetings as $meeting) :?>
					<tr>
						<td><input type="checkbox" name="ids[]" value="<?php echo $meeting->meeting_id?>"></td>
						<?php if(isset($meeting_updated_fields)) {
							foreach($meeting_updated_fields as $field_list) {
								$field_name = $field_list->field_name;
								if($field_name == "subject") { ?>
								<td><a href="<?php echo site_url('meetings/view/' . $meeting->meeting_id); ?>"><?php echo $meeting->$field_name; ?></a> </td>
							<?php } else { 
							if($field_label[$field_list->field_name]["field_type"] == "meeting_text_field"){ ?> 
								<td><?php echo $meeting->$field_name; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "meeting_date_field") { ?>
								<td><?php if(!is_null($meeting->$field_name)) {echo date('m/d/y h:ia',strtotime($meeting->$field_name.' UTC'));} ?></td>
							<?php }
								else if($field_label[$field_name]["field_type"] == "meeting_drop_field") { ?>
								<td><?php echo $_SESSION['drop_down_options'][$meeting->$field_name]['name']; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "meeting_special_field") {
							?> 
								<td>
                                    <?php
                                        $first_name = $_SESSION['user_accounts'][$meeting->$field_name]['upro_first_name'];
                                        $last_name = $_SESSION['user_accounts'][$meeting->$field_name]['upro_last_name'];
                                        if(($first_name != NULL) && ($last_name != NULL)) {
                                            echo $first_name." ".$last_name;
                                        } else if($first_name != NULL) {
                                            echo $first_name;
                                        } else if($last_name != NULL) {
                                            echo $last_name;
                                        } else {
                                            echo $_SESSION['user_accounts'][$meeting->$field_name]['uacc_username'];
                                        }
                                    ?>
                                </td>
							<?php } else if($field_label[$field_name]["field_type"] == "meeting_relate_field") { $relate_name = str_replace("id","name",$field_name);?>
								<td><a href="<?php echo site_url($field_label[$field_name]["relate_path"] . $meeting->$field_name)?>"><?php echo $meeting->$relate_name; ?></a></td>
							<?php } else if($field_label[$field_name]["field_type"] == "custom_text_field") { ?>
								<td><?php echo $custom_values[$field_name][$meeting->meeting_id]; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "custom_drop_field") { $value = $custom_values[$field_name][$meeting->meeting_id]; ?>
								<td><?php echo $_SESSION['drop_down_options'][$value]['name']; ?></td> 
							<?php } } } }?>
						<td><a href="<?php echo site_url('meetings/edit/' . $meeting->meeting_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                              &nbsp;
                              <a href="javascript:delete_one( '<?php echo $meeting->meeting_id?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
						</td>
					</tr>

					<?php endforeach; endif;?>

				  </tbody>
			  </table>
              <div>
                  <div class="list-footer-left">
                      <button type="button" class="btn btn-danger" onclick="delete_all()">Delete</button>
                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('meetings/add')?>'">Add New</button>
                  </div>
                  <div class="list-footer-right">
                      <?php echo $pager_links?>
                  </div>
              </div>
              <input type="hidden" name="act" value="">
          </form>

      </div> <!-- /.table-responsive -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

<br /><br>
<script type="text/javascript">
    delete_one=function( meeting_id ){
        Messi.ask('Do you really want to delete the record?', function(val) {
            if( val == 'Y' ){
                window.location.href="<?php echo site_url('meetings/delete')?>/" + meeting_id;
            }
        }, {modal: true, title: 'Confirm Delete'});
    }

    // delete all(selected) record
    delete_all=function( ){
        size = jQuery(":input[name='ids[]']:checked").length;
        // none selected
        if( size == 0 ){
            Messi.alert('Please select a record to delete',function(){

            }, {modal: true, title: 'Confirm Delete'});

            return;
        }
        // confirm
        Messi.ask('Do you really want to delete selected records?', function(val) {
            // confirmed
            if( val == 'Y' ){
                jQuery('#frmlist').prop('action', '<?php echo site_url('meetings/delete_all')?>');
                jQuery(":input[name='act']").val('delete');
                jQuery('#frmlist').submit();
            }
        }, {modal: true, title: 'Confirm Delete'});
    }

    // document ready
    jQuery(document).ready(function(){
        jQuery(":input[name='select_all']").bind('click', function(){
            jQuery(":input[name='" + jQuery(this).val() + "']").prop('checked', jQuery(this).prop('checked'));
        });

        /*code for the search box*/
        $("#search_go").click(function(){
            if( $("#search_box").val().length < 1 ) return false;

            //reset filters
            filtervalue1 = "";
            filtervalue2 = "";
            startSearch();
        });

        $("#search_box").keyup(function(e){
            if(e.keyCode == 13){
                if( $(this).val().length < 1 ) return false;

                //reset filters
                filtervalue1 = "";
                filtervalue2 = "";
                startSearch();
            }
        });

        //clear search box and search result
        $("#search_clear").click(function(){
            $(".search_result_tr").remove();
            $("#search_box").val('').focus();
            $("#frmlist tbody tr").show();
            $(".pagination").show();
        });

        $("#filter_by").change(function(){
            $("#filter_val_box").hide();
            $("#filter_box_btn").hide();
            var filter_type = $("option:selected", this).attr("data-type");
            var filter_val = $(this).val();
            $.ajax({
                url: '/ajax/getFilterElement',  //server script to process data
                type: 'POST',
                async: true,
                data: {type:filter_type, val:filter_val},
                success: function(result) {
                    if( result != ""){
                        $("#filter_val_box").html(result).show();
                        $("#filter_box_btn").show();
                    }
                    if(filter_type == "datetime"){
                        jQuery('.datetime').datetimepicker({
                            format: 'm/d/Y',
                            mask: false
                        });
                    }
                }
            });
        });

        $("#filter_btn").click(function(){
            //reset filters
            filtervalue1 = "";
            filtervalue2 = "";
            if( $("#filtervalue1").length > 0 ) filtervalue1 = $("#filtervalue1").val();
            if( $("#filtervalue2").length > 0 ) filtervalue2 = $("#filtervalue2").val();

            if( filtervalue1=="" ) return false;
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
            $("#company_id").val(ui.item.id);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });

    //autocomplete for persons
    $( "#person_viewer" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/ajax/personsAutocomplete",
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
            $("#people_id").val(ui.item.id);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });
	$("#company_viewer").focusout(function(){
			var company_name = $("#company_viewer").val();
			var company_id = $("#company_id").val();
			if (company_name != "")
			{
				if (company_id == "")
				{
					alert("Please choose a valid company from the list.");
				    $('#company_viewer').val('');
					$('#company_viewer').focus();
				}
			}

		});

				$(document).ready(function(){
    $('#adv_search_save').attr('disabled',true);

    $('#subject').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})

	  $('#location').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#company_viewer').focusout(function(){

        if($('#company_id').val() != 0 ){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
             checkempty();
        }
	})
	$('#event_type').chosen().change(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})

	$('#date_entered_start').click(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#date_entered_end').click(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#date_modified_start').click(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})

	$('#date_modified_end').click(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
             checkempty();
        }
	})
	checkempty = function() {

     if($('#subject').val().length !=0){
	 }else if($('#company_id').val().length !=0){
	 }else if($('#event_type').val().length !=0){
	 }else if($('#location').val().length !=0){
	 }else if($('#date_entered_start').val() !="__/__/____"){
	 }else if($('#date_entered_end').val() !='__/__/____'){
	 }else if($('#date_modified_start').val() !='__/__/____'){
	 }else if($('#date_modified_end').val() !='__/__/____'){
	 }else{
	 $('#adv_search_save').attr('disabled', true);
	 }
	 }
});

jQuery(document).ready(function(){

    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {

      $(selector).chosen(config[selector]);
    }
  });
</script>
