<?php

$this->load->helper('view_helper');

?>
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

<h3 class="content-title"><?php if(!empty($_SESSION['search'][$module_name])){ echo  $_SESSION['language']['global']['showing_search_results'] . ": ";}?><?php ucfirst($module_name);?></h3>

  <div class="row">

    <div class="col-md-12">

      <div class="table-responsive">
		<div class="form-group">

        <ul id="myTab1" class="nav nav-tabs">
          <li class="<?php if($search_tab == "basic"){ echo 'active';}?>">
            <a href="#search" data-toggle="tab"><?php echo $_SESSION['language']['global']['search'];?></a>
          </li>

          <li class="<?php if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
            <a href="#advanced" data-toggle="tab"><?php echo $_SESSION['language']['global']['advanced_search'];?></a>
          </li>

<?php

if(isset($_SESSION['saved_searches_index'][$module_name])){
	if(count($_SESSION['saved_searches_index'][$module_name]) > 0){
		echo '<li class="dropdown">
	            <a href="javascript:;" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">
	              Saved Searches <b class="caret"></b>
	            </a>
	         <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">';
		foreach($_SESSION['saved_searches_index'][$module_name] as $key => $value){
			echo '<li class=""><a href="'.site_url($module_name.'/search/'.$key).'">'.$value.'</a></li>';
		}
		echo '</ul>';
		echo '</li>';
	}
}
?>


        </ul>

<div id="myTab1Content" class="tab-content">

          <div class="tab-pane fade <?php if($search_tab == "basic"){ echo 'active in';}?>" id="search">
           <form name="frmedit" id="frmedit" action="<?php echo site_url($module_name.'/search');?>" method="post" class="form parsley-form">
			<div class="input-group">
				<label for="search_box" class="sr-only"><?php echo $_SESSION['language']['global']['search'];?></label>
				<input type="search" class="form-control" id="search_box" placeholder="NEED TO SORT THIS SECTION OUT" name="company_name" value="<?php if(isset($_SESSION['search']['companies']['company_name'])){echo $_SESSION['search']['companies']['company_name'];}?>">
				<div class="input-group-btn">
					  	<input type="submit" name="search_go" class="btn btn-success" value="<?php echo $_SESSION['language']['global']['search'];?>">
					  	<input type="submit" name="clear" class="btn btn-success" value="<?php echo $_SESSION['language']['global']['clear'];?>">
				</div><!-- /input-group-btn -->
			</div>
			
			
			 </form>
          </div> <!-- /.tab-pane -->

          <div class="tab-pane fade <?php if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active in';}?>" id="advanced">

 <form name="frmedit" id="frmedit" action="<?php echo site_url('companies/search');?>" method="post" class="form parsley-form">
               <table class="table table-striped table-bordered" style="font-size:11px;">
                  <tbody>
                    <?php 
                    /* output all of the available search options for this module */
                    
                    $max_options = count($search_options);
                    $option_col = 1;
                    for($col = 0; $col < 4; $col++){
                        if($col == 0){
                            echo '<tr valign="middle">';
                        }
                        ?>
                        <td width="25%">
                            <?php if (isset($search_options[$option_col]) && $option_col <= $max_options){
                            ?>
                                <span><strong><?php echo $_SESSION['language'][$module_name][$search_options[$option_col]];?></strong><br/>
                                <?php 
                                // check if we have data to display
                                $data = null;
                                if(isset($_SESSION['search'][$module_name][$search_options[$option_col]]))
                                {   
                                    $data = $_SESSION['search'][$module_name][$search_options[$option_col]];
                                }
                                echo format_editable_field($module_name, $search_options[$option_col], $data, true);
                                } // end if isset
                                ?>
                        </td>
                        <?php
                        

                        if($max_options <= $option_col && $col == 3){
                            echo '</tr>';
                        }
                        elseif($col == 3){
                            $col = -1;
                            echo '</tr>';
                        }

                        $option_col++;
                    }
                    ?>
                  </tbody>
               </table>


          </div> <!-- / .tab-pane -->

        </div> <!-- /.tab-content -->
        <!-- Save Advanced Search Modal -->
<div class="modal fade" id="save-modal" tabindex="1" role="dialog" aria-labelledby="searchLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $_SESSION['language']['global']['close'];?></span></button>
        <h4 class="modal-title" id="searchLabel"></h4>
      </div>
      <div class="modal-body">
      <?php echo $_SESSION['language']['global']['saved_search_message'];?><br/>

<input type="search" name="saved_search_name" class="form-control"/><br/>

<input type="submit" tabindex="1" class="btn btn-warning" name="saved_search_result"  value="<?php echo $_SESSION['language']['global']['save_search'];?>">

      </div>
    </div>
  </div>
</div>
<!-- End Modal -->
</form>
		</div>

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('companies')?>" method="post">
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
                          <th><input type="checkbox" name="select_all" value="ids[]"></th>
                          <?php if(isset($company_updated_fields))
	foreach($company_updated_fields as $field_list){ ?>
						  <th><?php echo $field_label[$field_list->field_name]["label_name"]; ?></th>
						  <?php }?>
						  <th class="text-center" width="10%"><?php echo $_SESSION['language']['global']['actions'];?></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if( ! $companies->num_rows() > 0 ) :?>
                      <tr>
                          <td colspan="6" align="center"><?php echo $_SESSION['language']['global']['no_records'];?></td>
                      </tr>
                      <?php else: foreach($companies->result() as $company) :?>
                      <tr>
                          <td><input type="checkbox" name="ids[]" value="<?php echo $company->company_id?>"></td>
                          <?php if(isset($company_updated_fields)) {
				foreach($company_updated_fields as $field_list) {
					$field_name = $field_list->field_name;
					if($field_list->field_name == "company_name") {
?>
								<td><a href="<?php echo site_url('companies/view/' . $company->company_id); ?>"><?php echo $company->$field_name; ?></a> </td>
							<?php } else {
						if($field_label[$field_name]["field_type"] == "company_text_field"){

?>
								<td><?php echo $company->$field_name; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "company_drop_field") { ?>
								<td><?php echo $_SESSION['drop_down_options'][$company->$field_name]['name']; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "company_date_field") { ?>
								<td><?php if(!is_null($company->$field_name)) { echo date('m/d/y h:ia',strtotime($company->$field_name.' UTC')); }?></td>
							<?php }
								else if($field_label[$field_name]["field_type"] == "company_special_field") {

?>
							<td>
                                <?php
                                    $first_name = $_SESSION['user_accounts'][$company->assigned_user_id]['upro_first_name'];
                                    $last_name = $_SESSION['user_accounts'][$company->assigned_user_id]['upro_last_name'];
                                    if(($first_name != NULL) && ($last_name != NULL)) {
                                        echo $first_name." ".$last_name;
                                    } else if($first_name != NULL) {
                                        echo $first_name;
                                    } else if($last_name != NULL) {
                                        echo $last_name;
                                    } else {
                                        echo $_SESSION['user_accounts'][$company->assigned_user_id]['uacc_username'];
                                    }
                                ?>
                            </td>
							<?php }  else if($field_label[$field_name]["field_type"] == "custom_text_field") { ?>
								<td><?php echo $custom_values[$field_name][$company->company_id]; ?></td>
							<?php } else if($field_label[$field_name]["field_type"] == "custom_drop_field") { $value = $custom_values[$field_name][$company->company_id]; ?>
								<td><?php echo $_SESSION['drop_down_options'][$value]['name']; ?></td>
							<?php } } } } ?>

                          <td class="valign-middle">
                              <a href="<?php echo site_url($module_name . '/edit/' . $company->company_id); ?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                              &nbsp;
                              <a href="javascript:delete_one( '<?php echo $company->company_id; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
                          </td>
                      </tr>
                      <?php endforeach; endif;?>
                  </tbody>
              </table>
              <div>
                  <div class="list-footer-left">
                      <button type="button" class="btn btn-danger" onclick="delete_all()"><?php echo $_SESSION['language']['global']['delete'];?></button>

                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url($module_name . '/add')?>'"><?php echo $_SESSION['language']['global']['add_new'];?></button>

					  <!--<a href='data/toExcel'>Export Data</a>-->

					  <button type="button" class="btn btn-success" style="background-color: #0B82F6 !important; border-color: #0B82F6 !important" onclick="return export_selected();"><?php echo $_SESSION['language']['global']['export_csv'];?></button>
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
  delete_one=function( company_id ){
    // confirm
    Messi.ask('Do you really want to delete the record?', function(val) {
      // confirmed
      if( val == 'Y' ){
        window.location.href="<?php echo site_url('companies/delete')?>/" + company_id;
      }
    }, {modal: true, title: 'Confirm Delete'});
  }

  export_selected=function( ){
  size = jQuery(":input[name='ids[]']:checked").length;

  if( size == 0 )
  {

      window.location.href='<?php echo site_url('companies/export')?>';
    }

   else
   {

        jQuery('#frmlist').prop('action', '<?php echo site_url('companies/export_all')?>');
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
        jQuery('#frmlist').prop('action', '<?php echo site_url('companies/delete_all')?>');
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


		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y',
			mask: true,
			timepicker: false
		});
});

$(document).ready(function(){
    $('#adv_search_save').attr('disabled',true);

    $('#company_name').keyup(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
		checkempty();
        }
	})

	  $('#company_type').chosen().change(function(){
        if($(this).val().length !=0){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	 $('#email_opt_out_1').click(function(){

        if($(this).val() != 0 ){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
           checkempty();
        }
	})

	$('#email_opt_out_2').click(function(){

        if($(this).val() != 0 ){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#email_opt_out_new_1').click(function(){

        if($(this).val() != 0 ){
            $('#adv_search_save').attr('disabled', false);
        }
        else
        {
            checkempty();
        }
	})
	$('#email_opt_out_new_2').click(function(){

        if($(this).val() != 0 ){
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
	$('#industry').chosen().change(function(){
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

     if($('#company_name').val().length !=0){
	 }else if($('#company_type').val().length !=0){
	 }else if($('#email_opt_out_1').is(':checked')){
	 }else if($('#email_opt_out_2').is(':checked')){
	 }else if($('#email_opt_out_new_1').is(':checked')){
	 }else if($('#email_opt_out_new_2').is(':checked')){
	 }else if($('#city').val().length !=0){
	 }else if($('#province').val().length !=0){
	 }else if($('#country').val().length !=0){
	 }else if($('#postal_code').val().length !=0){
	 }else if($('#assigned_user_id').val().length !=0){
	 }else if($('#industry').val().length !=0){
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