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
		
<h3 class="content-title"><?php if(isset($_SESSION['search']['people']['search_type'])){ echo "Showing Search Results: ";}?>People</h3>

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

if(isset($_SESSION['saved_searches_index']['people'])){
	if(count($_SESSION['saved_searches_index']['people']) > 0){
		echo '<li class="dropdown">
	            <a href="javascript:;" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">
	              Saved Searches <b class="caret"></b>
	            </a>
	         <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">';
		foreach($_SESSION['saved_searches_index']['people'] as $key => $value){
			echo '<li class=""><a href="'.site_url('people/search/'.$key).'">'.$value.'</a></li>';
		}
		echo '</ul>';
		echo '</li>';
	}
}
?>



        </ul>

<div id="myTab1Content" class="tab-content">

          <div class="tab-pane fade <?php if($search_tab == "basic"){ echo 'active in';}?>" id="search">
           <form name="frmedit" id="frmedit" action="<?php echo site_url('people/search');?>" method="post" class="form parsley-form">
			<div class="input-group">
				<label for="search_box" class="sr-only">Search</label>
				<input type="search" class="form-control" id="search_box" placeholder="Search by first and last name" name="full_name" value="<?php if(isset($_SESSION['search']['people']['full_name'])){echo $_SESSION['search']['people']['full_name'];}?>">
				<div class="input-group-btn">
					  	<input type="submit" name="search_go" class="btn btn-success" value="Search">
					  	<input type="submit" name="clear" class="btn btn-success" value="Clear">
				</div><!-- /input-group-btn -->
			</div>
			 </form>
          </div> <!-- /.tab-pane -->

          <div class="tab-pane fade <?php if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active in';}?>" id="advanced">

 <form name="frmedit" id="frmedit" action="<?php echo site_url('people/search');?>" method="post" class="form parsley-form">
               <table class="table table-striped table-bordered" style="font-size:11px;">
                  <tbody>
					  <tr valign="middle">
					  	<td width="25%"><span><strong>First Name</strong>
						  <input type="text" class="form-control" name="first_name" id = "first_name" value="<?php if(isset($_SESSION['search']['people']['first_name'])){echo $_SESSION['search']['people']['first_name'];}?>">
					  	</td>
					  	<td width="25%"><span><strong>Last Name</strong>
					  	<input type="text" class="form-control" name="last_name" id = "last_name" value="<?php if(isset($_SESSION['search']['people']['last_name'])){echo $_SESSION['search']['people']['last_name'];}?>">
					  	</td>
					  	<td width="25%"><span><strong>Job Title</strong>
						  <input type="text" class="form-control" name="job_title" id = "job_title" value="<?php if(isset($_SESSION['search']['people']['job_title'])){echo $_SESSION['search']['people']['job_title'];}?>">

					  	</td>
					  	<td width="25%"><span><strong>Contact Type</strong><br>
					  	<select name="contact_type[]" id = "contact_type" multiple="true" multiple class="form-control chosen-select" >
					  	<option value=""></option>
						<?php
						$company_types = lookupDropDownValues("account_type");
						foreach($company_types as $option){
						?>
						<option value="<?php echo $option;?>" <?php if(isset($_SESSION['search']['people']['contact_type'])){ foreach($_SESSION['search']['people']['contact_type'] as $opn) { if($opn==$option){echo 'selected';};} }?>><?php echo $_SESSION['drop_down_options'][$option]['name'];?></option>
						<?php	}	?>
						</select>

					  	</td>
					  </tr>
					  <tr>
					  	<td><span><strong>City</strong>
						  <input type="text" class="form-control" id = "city" name="city" value="<?php if(isset($_SESSION['search']['people']['city'])){echo $_SESSION['search']['people']['city'];}?>">

					  	</td>
					  	<td><span><strong>State/Province</strong>
						  <input type="text" class="form-control" id = "province" name="province" value="<?php if(isset($_SESSION['search']['people']['province'])){echo $_SESSION['search']['people']['province'];}?>">

					  	</td>
					  	<td><span><strong>Country</strong>
						  <input type="text" class="form-control" id = "country" name="country" value="<?php if(isset($_SESSION['search']['people']['country'])){echo $_SESSION['search']['people']['country'];}?>">

					  	</td>
					  	<td><span><strong>Postal Code</strong>
						  <input type="text" class="form-control" id = "postal_code" name="postal_code" value="<?php if(isset($_SESSION['search']['people']['postal_code'])){echo $_SESSION['search']['people']['postal_code'];}?>">

					  	</td>
					  </tr>
					  <tr valign="middle">
					  	<td width="25%">
					  	<span><strong>Assigned User</strong></span><br/>
					  	<select name="assigned_user_id[]" id = "assigned_user_id" multiple="true" multiple class="form-control chosen-select">
					  	<option value=""></option>
						<?php
						foreach($_SESSION['user_accounts'] as $user){
						?>
						<option value="<?php echo $user['uacc_uid'];?>"
						<?php if(isset($_SESSION['search']['people']['assigned_user_id'])){ foreach($_SESSION['search']['people']['assigned_user_id'] as $opn) { if($opn==$user['uacc_uid']){echo 'selected';};} } ?>><?php echo $user['upro_first_name'].' '.$user['upro_last_name'];?></option>
						<?php	}	?>
						</select>
					  	</td>
					  	<td width="25%">
						  	<?php
							  	// disabled for now
							 ?>
					  	<span><strong>Related Company</strong>
							<input type="text" name="company_viewer" id="company_viewer" class="form-control" value="<?php if(isset($company->company_name)){echo $company->company_name;}?>" />
							<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($_SESSION['search']['people']['company_id'])){echo $_SESSION['search']['people']['company_id'];}?>" />
					  	</td>
					  	<td width="25%">
					  	<span><strong>Lead Source</strong><br>
					  	<select name="lead_source_id[]" id = "lead_source_id" multiple="true" multiple class="form-control chosen-select">
					  	<option value=""></option>
						<?php
						$company_types = lookupDropDownValues("lead_source");
						foreach($company_types as $option){
						?>
						<option value="<?php echo $option;?>" <?php if(isset($_SESSION['search']['people']['lead_source_id'])){ foreach($_SESSION['search']['people']['lead_source_id'] as $opn) { if($opn==$option){echo 'selected';};} }?>><?php echo $_SESSION['drop_down_options'][$option]['name'];?></option>
						<?php	}	?>
						</select>
					  	</td>
					  	<td width="25%">
					  	<span><strong>Lead Status</strong><br>
					  	<select name="lead_status_id[]" id = "lead_status_id" multiple="true" multiple class="form-control chosen-select">
					  	<option value=""></option>
						<?php
						$company_types = lookupDropDownValues("lead_status");
						foreach($company_types as $option){
						?>
						<option value="<?php echo $option;?>" <?php if(isset($_SESSION['search']['people']['lead_status_id'])){ foreach($_SESSION['search']['people']['lead_status_id'] as $opn) { if($opn==$option){echo 'selected';};} } ?>><?php echo $_SESSION['drop_down_options'][$option]['name'];?></option>
						<?php	}	?>
						</select>
					  	</td>
					  </tr>
					  <tr>
					  	<td><span><strong>Date Entered Between</strong></span>
					  	<input class="form-control datetime" id="date_entered_start" name="date_entered_start" type="text" value="<?php if(isset($_SESSION['search']['people']['date_entered_start'])){echo $_SESSION['search']['people']['date_entered_start'];}?>">
					  	<input class="form-control datetime" id="date_entered_end" name="date_entered_end" type="text" value="<?php if(isset($_SESSION['search']['people']['date_entered_end'])){echo $_SESSION['search']['people']['date_entered_end'];}?>">
					  	</td>
					  	<td><span><strong>Date Modified Between</strong></span>
					  	<input class="form-control datetime" id="date_modified_start" name="date_modified_start" type="text" value="<?php if(isset($_SESSION['search']['people']['date_modified_start'])){echo $_SESSION['search']['people']['date_modified_start'];}?>">
					  	<input class="form-control datetime" id="date_entered_end_1" name="date_entered_end" type="text" value="<?php if(isset($_SESSION['search']['people']['date_entered_end'])){echo $_SESSION['search']['people']['date_entered_end'];}?>">

					  	</td>
					  	<td colspan="2" align="right">

					  	<input type="submit" name="adv_search_go" class="btn btn-success" value="Search">
<?php
if($search_tab == "saved"){
?>
<input type="button" name="clear" onclick="window.location.href='<?php echo site_url('people/search/' . $_SESSION['search_id'] . '/delete/')?>'" class="btn btn-danger" name='DeleteSearch' value="Delete Saved Search">
<?php
}
else{
?>
					  	<input type="submit" data-toggle="modal" data-target="#save-modal" name="adv_search_save" id = "adv_search_save" class="btn btn-warning" value="Search & Save" onclick="javascript: $('#searchLabel').text('Save Advanced Search'); $('#module').val('people'); return false;">
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


          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('people')?>" method="post">
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
							<th><input type="checkbox" name="select_all" value="ids[]"></th>
							<?php if(isset($people_updated_fields))
								foreach($people_updated_fields as $field_list){ ?>
								<th><?php echo $field_label[$field_list->field_name]["label_name"]; ?></th>
							<?php }?>
							<th>Actions</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if( ! $people->exists() ) :?>
                      <tr>
                          <td colspan="8" align="center">No People Found</td>
                      </tr>
                      <?php else: foreach($people as $person) :?>
							<tr>
								<td><input type="checkbox" name="ids[]" value="<?php echo $person->people_id?>"></td>
								<?php if(isset($people_updated_fields)) {
							foreach($people_updated_fields as $field_list) {
								$field_name = $field_list->field_name;
								if($field_name == "last_name" || $field_name == "first_name") {
								?>
								<td><a href="<?php echo site_url('people/view/' . $person->people_id); ?>"><?php echo $person->$field_name; ?></a> </td>
							<?php } else {
									if($field_name == "email1" || $field_name == "email2") {
										?>
									<td><?php if($_SESSION['user']['email_sending_option'] == 0){
										echo "<a href='".site_url('messages/send/people/' . $person->people_id)."'>";
										echo $person->$field_name;
										echo "</a>";									


									}
									else{
																	echo "<a href='mailto:".$person->email1."'>";
										echo $person->$field_name;
										echo "</a>";
									} ?> </td>
									<?php } else {
									if($field_label[$field_list->field_name]["field_type"] == "people_text_field"){
																			?> 
								<td><?php echo $person->$field_name; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "people_date_field") { ?>
								<td><?php if(!is_null($person->$field_name)) { echo date('m/d/y h:ia',strtotime($person->$field_name.' UTC')); }?></td>
							<?php }
								else if($field_label[$field_list->field_name]["field_type"] == "people_drop_field") {
								?>
								<td><?php echo $_SESSION['drop_down_options'][$person->$field_name]['name']; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "people_special_field") {
							
							 ?> 
								<td>
									<?php
										$first_name = $_SESSION['user_accounts'][$person->$field_name]['upro_first_name'];
										$last_name = $_SESSION['user_accounts'][$person->$field_name]['upro_last_name'];
										if(($first_name != NULL) && ($last_name != NULL)) {
											echo $first_name." ".$last_name;
										} else if($first_name != NULL) {
											echo $first_name;
										} else if($last_name != NULL) {
											echo $last_name;
										} else {
											echo $_SESSION['user_accounts'][$person->$field_name]['uacc_username'];
										}
									?>
								</td>
							<?php } else if($field_label[$field_name]["field_type"] == "people_relate_field") { $relate_name = str_replace("id","name",$field_name);?>
								<td><a href="<?php echo site_url($field_label[$field_name]["relate_path"] . $person->$field_name)?>"><?php echo $person->$relate_name; ?></a></td>
							<?php } else if($field_label[$field_name]["field_type"] == "custom_text_field") { ?>
								<td><?php echo $custom_values[$field_name][$person->people_id]; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "custom_drop_field") { $value = $custom_values[$field_name][$person->people_id]; ?>
								<td><?php echo $_SESSION['drop_down_options'][$value]['name']; ?></td>
							<?php  } } } } }?>
                          <td class="valign-middle">
                              <a href="<?php echo site_url('people/edit/' . $person->people_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                              &nbsp;
                              <a href="javascript:delete_one( '<?php echo $person->people_id?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>

                          </td>
							</tr>
                      <?php endforeach; endif;?>
                  </tbody>
              </table>
              <div>
                  <div class="list-footer-left">
                      <button type="button" class="btn btn-danger" onclick="delete_all()">Delete</button>
                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('people/add')?>'">Add New</button>
					  <button type="button" class="btn btn-success" style="background-color: #0B82F6 !important; border-color: #0B82F6 !important" onclick="return export_selected();">Export CSV</button>
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
	
	
	// delete single record
	delete_one=function( person_id ){
		Messi.ask('Do you really want to delete the record?', function(val) {
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('people/delete')?>/" + person_id;
			}
		}, {modal: true, title: 'Confirm Delete'});
	}

export_selected=function( ){
  size = jQuery(":input[name='ids[]']:checked").length();

  if( size == 0 )
  {

      window.location.href='<?php echo site_url('people/export')?>';
    }

   else
   {

        jQuery('#frmlist').prop('action', '<?php echo site_url('people/export_all')?>');
        jQuery(":input[name='act']").val('export');
        jQuery('#frmlist').submit();
   }

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
				jQuery('#frmlist').prop('action', '<?php echo site_url('people/delete_all')?>');
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

		//autocomplete for companies
		$( "#search_box" ).autocomplete({
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
				//$("#company").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
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

		//clear search box and search result and show original content
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

					//init autocomplete if the selected is Company ID
					if( filter_val == "company_id" ){

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
								//console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
								$("#filtervalue1").val(ui.item.id);
							},
							open: function() {
								$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
							},
							close: function() {
								$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
							}
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



			// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y',
			mask: true,
			timepicker: false
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

    $('#first_name').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
          checkempty();
        }
	})

	  $('#contact_type').chosen().change(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
           checkempty();
        }
	})
	 $('#last_name').keyup(function(){

        if($(this).val() != 0 ){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
           checkempty();
        }
	})

	$('#job_title').keyup(function(){

        if($(this).val() != 0 ){
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

	$('#city').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#province').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#country').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
          checkempty();
        }
	})
	$('#postal_code').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
           checkempty();
        }
	})
	$('#assigned_user_id').chosen().change(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#lead_source_id').chosen().change(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
           checkempty();
        }
	})
	$('#lead_status_id').chosen().change(function(){
		
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

	$('#date_entered_end_1').click(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
          checkempty();
        }
	})
	 checkempty = function() {
	 if($('#first_name').val().length !=0){
     }else if($('#contact_type').val().length !=0){
	 }else if($('#company_id').val().length !=0){
	 }else if($('#job_title').val().length !=0){
	 }else if($('#city').val().length !=0){
	 }else if($('#province').val().length !=0){
	 }else if($('#country').val().length !=0){
	 }else if($('#postal_code').val().length !=0){
	 }else if($('#assigned_user_id').val().length !=0){
	 }else if($('#last_name').val().length !=0){
	 }else if($('#lead_source_id').val().length !=0){
	 }else if($('#lead_status_id').val().length !=0){
	 }else if($('#date_entered_start').val() !="__/__/____"){
	 }else if($('#date_entered_end').val() !='__/__/____'){
	 }else if($('#date_modified_start').val() !='__/__/____'){
	 }else if($('#date_entered_end_1').val() !='__/__/____'){
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
