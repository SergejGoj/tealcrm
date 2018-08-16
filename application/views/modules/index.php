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
                <?php $name_value = display_name($this->module['name'],'',true);?>
				<input type="search" class="form-control" id="search_box" placeholder="<?php echo $_SESSION['language']['global']['enter_search_here'];?>" name="<?php echo $name_value; ?>" value="<?php if(isset($_SESSION['search'][$module_name][$name_value])){echo $_SESSION['search'][$module_name][$name_value];}?>">
				<div class="input-group-btn">
					  	<input type="submit" name="search_go" class="btn btn-success" value="<?php echo $_SESSION['language']['global']['search'];?>">
					  	<input type="submit" name="clear" class="btn btn-success" value="<?php echo $_SESSION['language']['global']['clear'];?>">
				</div><!-- /input-group-btn -->
			</div>
			
			
			 </form>
          </div> <!-- /.tab-pane -->

          <div class="tab-pane fade <?php if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active in';}?>" id="advanced">

          <form name="frmedit" id="frmedit" action="<?php echo site_url($module_name . '/search');?>" method="post" class="form parsley-form">
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
                    <tr>
                    	<td colspan="4" align="right">
                        <input type="submit" name="adv_search_go" class="btn btn-success" value="<?php echo $_SESSION['language']['global']['search'];?>">
                        <?php
                        if($search_tab == "saved"){
                        ?>
                        <input type="button" name="clear" onclick="window.location.href='<?php echo site_url($module_name . '/search/' . $_SESSION['search_id'] . '/delete/1')?>'" class="btn btn-danger" name='DeleteSearch' value="<?php echo $_SESSION['language']['global']['delete_saved_search'];?>">
                        <?php
                        }
                        else{
                        ?>
                        <input type="submit" data-toggle="modal" data-target="#save-modal" name="adv_search_save" id = "adv_search_save" class="btn btn-warning" value="<?php echo $_SESSION['language']['global']['search_and_save'];?>" onclick="javascript: $('#searchLabel').text('<?php echo $_SESSION['language']['global']['close'];?>'); $('#module').val('companies'); return false;">
                        <?php
                        }
                        ?>
                        <input type="submit" name="clear" class="btn btn-success" value="<?php echo $_SESSION['language']['global']['clear'];?>">

                        </td>
                        </tr>
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

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url($module_name)?>" method="post">
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
                          <th><input type="checkbox" name="select_all" value="ids[]"></th>
                          <?php if(isset($list_layout))
	                        foreach($list_layout as $field_list){ ?>
						  <th><?php echo $_SESSION['language'][$module_name][$field_list];?></th>
						  <?php }?>
						  <th class="text-center" width="10%"><?php echo $_SESSION['language']['global']['actions'];?></th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if( $records->num_rows() <= 0 ) {?>
                      <tr>
                          <td colspan="6" align="center"><?php echo $_SESSION['language']['global']['no_records'];?></td>
                      </tr>
                      <?php 
                      } 
                      else{

                        foreach($records->result() as $record){

                            $rec_id_name = $_SESSION['modules'][$this->module['name']]['db_key'];

                            ?>
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="<?php echo $record->{$rec_id_name}?>"></td>
                                <?php // output the fields list
                                    foreach($list_layout as $item){

                                        // check if this is our KEY or not for a link
                                        if ( $_SESSION['field_dictionary'][$module_name][$item]['name_value'] == 1 ){
                                            ?><td><a href="<?php echo site_url($module_name . '/view/' . $record->{$rec_id_name}); ?>"><?php echo format_field($module_name,$_SESSION['field_dictionary'][$module_name][$item]['field_name'],$record->$item); ?></a></td><?php
                                        }
                                        else{
                                            ?><td><?php echo format_field($module_name,$_SESSION['field_dictionary'][$module_name][$item]['field_name'],$record->$item); ?></td><?php
                                        }

                                    } // end list of layout items
                                ?>
                                    <td class="valign-middle">
                                    <a href="<?php echo site_url($module_name . '/edit/' . $record->{$rec_id_name}); ?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                                    &nbsp;
                                    <a href="javascript:delete_one( '<?php echo $record->{$rec_id_name}; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
                                    </td>
                                </tr>
                            <?php
                        } // end foreach
                      } // end if num rows is smaller than 0
                      ?>
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
  delete_one=function( record_id ){
    // confirm
    Messi.ask('<?php echo $_SESSION['language']['global']['are_you_sure_delete'];?>', function(val) {
      // confirmed
      if( val == 'Y' ){
        window.location.href="<?php echo site_url($module_name . '/delete')?>/" + record_id;
      }
    }, {modal: true, title: '<?php echo $_SESSION['language']['global']['confirm_delete'];?>'});
  }

  export_selected=function( ){
  size = jQuery(":input[name='ids[]']:checked").length;

  if( size == 0 )
  {

      window.location.href='<?php echo site_url($module_name . '/export')?>';
    }

   else
   {

        jQuery('#frmlist').prop('action', '<?php echo site_url($module_name . '/export_all')?>');
        jQuery(":input[name='act']").val('export');
        jQuery('#frmlist').submit();
   }

  }

  // delete all(selected) record
  delete_all=function( ){
    size = jQuery(":input[name='ids[]']:checked").length;
    // none selected
    if( size == 0 ){
      Messi.alert('<?php echo $_SESSION['language']['global']['please_select_record_delete'];?>',function(){

      }, {modal: true, title: '<?php echo $_SESSION['language']['global']['confirm_delete'];?>'});

      return;
    }
    // confirm
    Messi.ask('<?php echo $_SESSION['language']['global']['are_you_sure_delete_multiple'];?>', function(val) {
      // confirmed
      if( val == 'Y' ){
        jQuery('#frmlist').prop('action', '<?php echo site_url($module_name . '/delete_all')?>');
        jQuery(":input[name='act']").val('delete');
        jQuery('#frmlist').submit();
      }
    }, {modal: true, title: 'Confirm Delete111'});
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
                        console.log('hi');
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