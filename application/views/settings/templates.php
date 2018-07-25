<style>
/*style for the search box, move it to css files later*/
#search_result_tr, #filter_val_box, #filter_box_btn{
	display:none;
}
</style>

<h3 class="content-title">Proposal Templates</h3>

  <div class="row">

    <div class="col-md-12">

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('settings/templates')?>" method="post">
				<table class="table table-bordered table-striped table-condensed">
					<thead>
						<tr>
							<th>Template Name</th>	
							<th>Actions</th>                                          
						</tr>
					</thead>   
					<tbody>
						<?php if( ! $templates->exists() ) :?>
						<tr>
							<td colspan="8" align="center">No Templates</td>
						</tr>	
						<?php else: foreach($templates as $template) :?>
						<tr>
							<td><a href="<?php echo site_url('/settings/templates/edit/' . $template->template_id)?>"><?php echo $template->name;?></a></td>
							<td>
                              <a href="<?php echo site_url('/settings/templates/edit/' . $template->template_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                              &nbsp;
                              <a href="javascript:delete_one( '<?php echo $template->template_id?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a> 
							</td>                                   
						</tr> 		
						<?php endforeach; endif;?>				  
					</tbody>
				</table>
              <div>
                  <div class="list-footer-left"> 
                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('settings/templates/add')?>'">Add New</button>
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
  delete_one=function( template_id ){
    // confirm
    Messi.ask('Do you really want to delete the proposal?', function(val) { 
      // confirmed
      if( val == 'Y' ){
        window.location.href="<?php echo site_url('settings/templates/delete')?>/" + template_id;
      }
    }, {modal: true, title: 'Confirm Delete'});   
  }
 </script>