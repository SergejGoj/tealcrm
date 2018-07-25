<style>
/*style for the search box, move it to css files later*/
#search_result_tr, #filter_val_box, #filter_box_btn{
	display:none;
}
</style>

<h3 class="content-title">Sales Stage Order Editor</h3>

  <div class="row">

    <div class="col-md-12">

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('settings/sales_stage_order_update')?>" method="post">
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th>Name</th>
							<th>Order By</th>
							                                  
						</tr>
					</thead>   
					<tbody>
						<?php //print_r($sales_stage_order); ?>
						<?php foreach($sales_stage_order as $ss) :?>
						<tr>
							
							<td><?php echo $ss->name;?></td>
							<td><input type="text" class="form-control" name="<?php echo $ss->drop_down_id; ?>" id="<?php echo $ss->drop_down_id; ?>" value="<?php echo $ss->order_by;?>"></td>                                 
						</tr> 		
						<?php endforeach;?>				  
					</tbody>
				</table>
              <div>
                  <div class="list-footer-left"> 
                     <button type="submit" class="btn btn-primary">Update</button>
                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('settings/drop_down_editor')?>'">Cancel</button>
                  </div>
                  <div class="list-footer-right"> 
                      <?php //echo $pager_links?>
                  </div>  
              </div>
              <input type="hidden" name="act" value="Update">
          </form>

      </div> <!-- /.table-responsive -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

<br /><br>

<script type="text/javascript">
  // delete single record
  delete_one=function( product_id ){
    // confirm
    Messi.ask('Do you really want to delete the product?', function(val) { 
      // confirmed
      if( val == 'Y' ){
        window.location.href="<?php echo site_url('settings/products/delete')?>/" + product_id;
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
		Messi.ask('Do you really want to delete selected record(s)?', function(val) { 
			// confirmed
			if( val == 'Y' ){
				jQuery('#frmlist').prop('action', '<?php echo site_url('settings/products_delete_all')?>');
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
 </script>

