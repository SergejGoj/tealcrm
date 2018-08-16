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
<h3 class="content-title"><?php if(isset($_SESSION['search']['deals']['search_type'])){ echo "Showing Search Results: ";}?>Deals</h3>

  <div class="row">

    <div class="col-md-12">

      <div class="table-responsive">
		<div class="form-group">

        <ul id="myTab1" class="nav nav-tabs">
          <li class="<?php if($search_tab == "basic"){ echo 'active';}?>">
            <a href="#search" data-toggle="tab">Search</a>
          </li>

          <li class="<?php if($search_tab == "advanced"){ echo 'active';}?>">
            <a href="#advanced" data-toggle="tab">Advanced Search</a>
          </li>

<?php

if(isset($_SESSION['saved_searches_index']['deals'])){
	if(count($_SESSION['saved_searches_index']['deals']) > 0){
		echo '<li class="dropdown">
	            <a href="javascript:;" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">
	              Saved Searches <b class="caret"></b>
	            </a>
	         <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">';
		foreach($_SESSION['saved_searches_index']['deals'] as $key => $value){
			echo '<li class=""><a href="'.site_url('deals/search/'.$key).'">'.$value.'</a></li>';
		}
		echo '</ul>';
		echo '</li>';
	}
}
?>


        </ul>

<div id="myTab1Content" class="tab-content">

          <div class="tab-pane fade <?php if($search_tab == "basic"){ echo 'active in';}?>" id="search">
           <form name="frmedit" id="frmedit" action="<?php echo site_url('deals/search');?>" method="post" class="form parsley-form">
			<div class="input-group">
				<label for="search_box" class="sr-only">Search</label>
				<input type="search" class="form-control" id="search_box" placeholder="Search by deal name" name="name" value="<?php if(isset($_SESSION['search']['deals']['name'])){echo $_SESSION['search']['deals']['name'];}?>">
				<div class="input-group-btn">
					  	<input type="submit" name="search_go" class="btn btn-success" value="Search">
					  	<input type="submit" name="clear" class="btn btn-success" value="Clear">
				</div><!-- /input-group-btn -->
			</div>
			 </form>
          </div> <!-- /.tab-pane -->

          <div class="tab-pane fade <?php if($search_tab == "advanced"){ echo 'active in';}?>" id="advanced">

 <form name="frmedit" id="frmedit" action="<?php echo site_url('deals/search');?>" method="post" class="form parsley-form">
               <table class="table table-striped table-bordered" style="font-size:11px;">
                  <tbody>
					  <tr valign="middle">
					  	<td width="25%"><span><strong>Deal Name</strong>
						  <input type="text" class="form-control" name="name" id = "name" value="<?php if(isset($_SESSION['search']['deals']['name'])){echo $_SESSION['search']['deals']['name'];}?>">
					  	</td>
					  	<td width="25%"><span><strong>Company Name</strong>
							<input type="text" name="company_viewer" id="company_viewer" class="form-control" value="<?php if(isset($company->company_name)){echo $company->company_name;}?>" />
							<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($_SESSION['search']['deals']['company_id'])){echo $_SESSION['search']['deals']['company_id'];}?>" />
					  	</td>
					  	<td width="25%"><span><strong>Contact Name</strong>
<input type="text" name="contact_viewer" id="contact_viewer" class="form-control" value="<?php if(isset($contact->contact_id)){echo $contact->first_name.' '.$contact->last_name;}?>"/>
							<input type="hidden" name="contact_id" id="contact_id" value="<?php if(isset($_SESSION['search']['deals']['contact_id'])){echo $_SESSION['search']['deals']['contact_id'];}?>" />
					  	</td>
					  	<td width="25%"><span><strong>Sales Stage</strong><br>
					  	<select name="sales_stage_id[]" id = "sales_stage_id" multiple="true" multiple class="form-control chosen-select" >
					  	<option value=""></option>
						<?php
						$company_types = lookupDropDownValues("sales_stage");
						foreach($company_types as $option){
						?>
						<option value="<?php echo $option;?>" <?php if(isset($_SESSION['search']['deals']['sales_stage_id'])){ foreach($_SESSION['search']['deals']['sales_stage_id'] as $opn) { if($opn==$option){echo 'selected';};} }?>><?php echo $_SESSION['drop_down_options'][$option]['name'];?></option>
						<?php	}	?>
						</select>
					  	</td>
					  </tr>
					  <tr valign="middle">
					  	<td width="25%">
					  	<span><strong>Deal Value Between</strong>
					  	<input type="text" class="form-control" placeholder="0" name="deal_value_start" id = "deal_value_start" value="<?php if(isset($_SESSION['search']['deals']['deal_value_start'])){echo $_SESSION['search']['deals']['deal_value_start'];}?>">
					  	<input type="text" class="form-control" placeholder="100000" name="deal_value_end" id = "deal_value_end" value="<?php if(isset($_SESSION['search']['deals']['deal_value_end'])){echo $_SESSION['search']['deals']['deal_value_end'];}?>">
					  	</td>
					  	<td width="25%">
<span><strong>Expected Close Date Between</strong></span>
					  	<input class="form-control datetime" id="expected_close_date_start" name="expected_close_date_start" type="text" value="<?php if(isset($_SESSION['search']['deals']['expected_close_date_start'])){echo $_SESSION['search']['deals']['expected_close_date_start'];}?>">
					  	<input class="form-control datetime" id="expected_close_date_end" name="expected_close_date_end" type="text" value="<?php if(isset($_SESSION['search']['deals']['expected_close_date_end'])){echo $_SESSION['search']['deals']['expected_close_date_end'];}?>">
					  	</td>
					  	<td width="25%"><span><strong>Date Entered Between</strong></span>
					  	<input class="form-control datetime" id="date_entered_start" name="date_entered_start" type="text" value="<?php if(isset($_SESSION['search']['deals']['date_entered_start'])){echo $_SESSION['search']['deals']['date_entered_start'];}?>">
					  	<input class="form-control datetime" id="date_entered_end" name="date_entered_end" type="text" value="<?php if(isset($_SESSION['search']['deals']['date_entered_end'])){echo $_SESSION['search']['deals']['date_entered_end'];}?>">
					  	</td>
					  	<td width="25%">
<span><strong>Date Modified Between</strong></span>
					  	<input class="form-control datetime" id="date_modified_start" name="date_modified_start" type="text">
					  	<input class="form-control datetime" id="date_entered_end_1" name="date_entered_end" type="text">
					  	</td>
					  </tr>
					  <tr>
					  	<td><span><strong>Assigned User</strong></span><br/>
					  	<select name="assigned_user_id[]" id = "assigned_user_id" multiple="true" multiple class="form-control chosen-select">
					  	<option value=""></option>
						<?php
						foreach($_SESSION['user_accounts'] as $user){
						?>
						<option value="<?php echo $user['uacc_uid'];?>"
						<?php if(isset($_SESSION['search']['deals']['assigned_user_id'])){ foreach($_SESSION['search']['deals']['assigned_user_id'] as $opn) { if($opn==$user['uacc_uid']){echo 'selected';};} }?>><?php echo $user['upro_first_name'].' '.$user['upro_last_name'];?></option>
						<?php	}	?>
						</select>
					  	</td>
					  	<td colspan="3" align="right">

					  	<input type="submit" name="adv_search_go" class="btn btn-success" value="Search">
<?php
if($search_tab == "saved"){
?>
<input type="button" name="clear" onclick="window.location.href='<?php echo site_url('contacts/search/' . $_SESSION['search_id'] . '/delete/')?>'" class="btn btn-danger" name='DeleteSearch' value="Delete Saved Search">
<?php
}
else{
?>
					  	<input type="submit" data-toggle="modal" data-target="#save-modal" name="adv_search_save" id = "adv_search_save" class="btn btn-warning" value="Search & Save" onclick="javascript: $('#searchLabel').text('Save Advanced Search'); $('#module').val('contacts'); return false;">
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

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('deals')?>" method="post">
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th><input type="checkbox" name="select_all" value="ids[]"></th>
							<?php if(isset($deal_updated_fields))
								foreach($deal_updated_fields as $field_list){ ?>
								<th><?php echo $field_label[$field_list->field_name]["label_name"]; ?></th>
							<?php }?>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if( ! $deals->exists() ) :?>
						<tr>
							<td colspan="8" align="center">No Deals</td>
						</tr>
						<?php else: foreach($deals as $deal) :?>
						<tr>
							<td>
								<input type="checkbox" name="ids[]" value="<?php echo $deal->deal_id?>">
							</td>
							
							<?php if(isset($deal_updated_fields)) {
							foreach($deal_updated_fields as $field_list) {
								$field_name = $field_list->field_name;
								if($field_name == "name") {
								 ?>
								<td><a href="<?php echo site_url('deals/view/' . $deal->deal_id); ?>"><?php echo $deal->$field_name; ?></a> </td>
							<?php } else { 
									if($field_label[$field_list->field_name]["field_type"] == "deal_text_field"){
										
									?> 
								<td><?php echo $deal->$field_name; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "deal_date_field") { ?>
								<td><?php if(!is_null($deal->$field_name)) {echo date('m/d/y h:ia',strtotime($deal->$field_name.' UTC'));} ?></td>
							<?php }
								else if($field_label[$field_name]["field_type"] == "deal_drop_field") {
								?>
								<td><?php echo $_SESSION['drop_down_options'][$deal->$field_name]['name']; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "deal_special_field") {
							?> 
							<td>
								<?php
									$first_name = $_SESSION['user_accounts'][$deal->$field_name]['upro_first_name'];
									$last_name = $_SESSION['user_accounts'][$deal->$field_name]['upro_last_name'];
									if(($first_name != NULL) && ($last_name != NULL)) {
										echo $first_name." ".$last_name;
									} else if($first_name != NULL) {
										echo $first_name;
									} else if($last_name != NULL) {
										echo $last_name;
									} else {
										echo $_SESSION['user_accounts'][$deal->$field_name]['uacc_username'];
									}
								?>
							</td>
							<?php } else if($field_label[$field_name]["field_type"] == "deal_relate_field") { $relate_name = str_replace("id","name",$field_name);?>
								<td><a href="<?php echo site_url($field_label[$field_name]["relate_path"] . $deal->$field_name)?>"><?php echo $deal->$relate_name; ?></a></td>
								
							<?php } else if($field_label[$field_name]["field_type"] == "deal_relate_field") { $relate_name = str_replace("id","name",$field_name);?>
								<td><a href="<?php echo site_url($field_label[$field_name]["relate_path"] . $deal->$field_name)?>"><?php echo $deal->$relate_name; ?></a></td>
							
							<?php } else if($field_label[$field_name]["field_type"] == "custom_text_field") { ?>
								<td><?php echo $custom_values[$field_name][$deal->deal_id]; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "custom_drop_field") { $value = $custom_values[$field_name][$deal->deal_id]; ?>
								<td><?php echo $_SESSION['drop_down_options'][$value]['name']; ?></td>
							<?php } } } } ?>
							<td>
                              <a href="<?php echo site_url('deals/edit/' . $deal->deal_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                              &nbsp;
                              <a href="javascript:delete_one( '<?php echo $deal->deal_id?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
							</td>
						</tr>
						<?php endforeach; endif;?>
					</tbody>
				</table>
              <div>
                  <div class="list-footer-left">
                      <button type="button" class="btn btn-danger" onclick="delete_all()">Delete</button>
                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('deals/add')?>'">Add New</button>
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
	delete_one=function( deal_id ){
		Messi.ask('Do you really want to delete the record?', function(val) {
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('deals/delete')?>/" + deal_id;
			}
		}, {modal: true, title: 'Confirm Delete'});
	}


	export_selected=function( ){
  size = jQuery(":input[name='ids[]']:checked").length;

  if( size == 0 )
  {

      window.location.href='<?php echo site_url('deals/export')?>';
  }

   else
   {

        jQuery('#frmlist').prop('action', '<?php echo site_url('deals/export_all')?>');
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
				jQuery('#frmlist').prop('action', '<?php echo site_url('deals/delete_all')?>');
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


	});

		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y',
			mask: true,
			timepicker: false
		});



		//autocomplete for contacts
		$( "#contact_viewer" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/ajax/contactsAutocomplete",
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
				$("#contact_id").val(ui.item.id);
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

		$("#contact_viewer").focusout(function(){
			var contact_name = $("#contact_viewer").val();
			var contact_id = $("#contact_id").val();
			if (contact_name != "")
			{
				if (contact_id == "")
				{
					alert("Please choose a valid contact from the list.");
				    $('#contact_viewer').val('');
					$('#contact_viewer').focus();
				}
			}
		});

		$(document).ready(function(){
    $('#adv_search_save').attr('disabled',true);

    $('#name').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})

	  $('#contact_viewer').focusout(function(){
        if($('#contact_id').val().length !=0){
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
	$('#expected_close_date_start').click(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#expected_close_date_end').click(function(){
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
	$('#sales_stage_id').chosen().change(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#deal_value_start').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#deal_value_end').keyup(function(){
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

     if($('#name').val().length !=0){
	 }else if($('#company_id').val().length !=0){
	 }else if($('#contact_id').val().length !=0){
	 }else if($('#expected_close_date_start').val() !="__/__/____"){
	 }else if($('#expected_close_date_end').val() !="__/__/____"){
	 }else if($('#assigned_user_id').val().length !=0){
	 }else if($('#sales_stage_id').val().length !=0){
	 }else if($('#date_entered_start').val() !="__/__/____"){
	 }else if($('#date_entered_end').val() !='__/__/____'){
	 }else if($('#date_modified_start').val() !='__/__/____'){
	 }else if($('#date_entered_end_1').val() !='__/__/____'){
	 }else if($('#deal_value_start').val().length !=0){
	 }else if($('#deal_value_end').val().length !=0){
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