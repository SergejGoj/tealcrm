<style>
/*style for the search box, move it to css files later*/
#search_result_tr, #filter_val_box, #filter_box_btn{
	display:none;
}
</style>

<h3 class="content-title">Products List</h3>

  <div class="row">

    <div class="col-md-12">

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('settings/products')?>" method="post">
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th><input type="checkbox" name="select_all" value="ids[]"></th>	
							<th>Product Name</th>	
							<th>Status</th>
							<th>Cost</th>
							<th>List Price</th>
							<th>Quantity In Stock</th>
							<th>Actions</th>                                          
						</tr>
					</thead>   
					<tbody>
						<?php if( ! $products->exists() ) :?>
						<tr>
							<td colspan="8" align="center">No Products</td>
						</tr>	
						<?php else: foreach($products as $product) :?>
						<tr>
							<td><input type="checkbox" name="ids[]" value="<?php echo $product->product_id?>"></td>
							<td><a href="<?php echo site_url('/settings/products/edit/' . $product->product_id)?>"><?php echo $product->product_name;?></a></td>
							<td><?php if($product->active == 0){ echo 'Active'; } else { echo 'Inactive';}?></td>							
							<td>$<?php echo $product->cost;?></td>
							<td>$<?php echo $product->list_price;?></td>								
							<td><?php echo $product->quantity_in_stock;?></td>								
								
							<td>
								
                              <a href="<?php echo site_url('/settings/products/edit/' . $product->product_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                              &nbsp;
                              <a href="javascript:delete_one( '<?php echo $product->product_id?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a> 
							</td>                                   
						</tr> 		
						<?php endforeach; endif;?>				  
					</tbody>
				</table>
              <div>
                  <div class="list-footer-left"> 
                     <button type="button" class="btn btn-danger" onclick="delete_all()">Delete</button>
                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('settings/products/add')?>'">Add New</button>
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
		size = jQuery(":input[name='ids[]']:checked").size();
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

