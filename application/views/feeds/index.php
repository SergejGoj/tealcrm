<h3 class="content-title">Notes</h3>

  <div class="row">

    <div class="col-md-12">

      <div class="table-responsive">

          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('notes')?>" method="post">
	 		 <table class="table table-bordered table-striped table-condensed">
				  <thead>
					  <tr>
						  <th><input type="checkbox" name="select_all" value="ids[]"></th>	
						  <th>Subject</th>							 
						  <th>Note Id</th>							 
						  <th>Date Created</th>
						  <th>Actions</th>                                          
					  </tr>
				  </thead>   
				  <tbody>
					<?php if( ! $notes->exists() ) :?>
					<tr>
						<td colspan="6" align="center">No Notes</td>
					</tr>	
					<?php else: foreach($notes as $note) :?>
					<tr>
						<td><input type="checkbox" name="ids[]" value="<?php echo $note->id?>"></td>
						<td><?php echo $note->subject?></td>							
						<td><?php echo $note->note_id?></td>							
						<td><?php echo date( config_item('date_display_short'), strtotime($note->date_entered.' UTC'))?></td>    
						<td>
							<div class="btn-group">
								<button type="button" class="btn btn-default">Select Action</button>
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?php echo site_url('notes/edit/' . $note->id)?>">Edit</a></li>
									<li><a href="<?php echo site_url('notes/view/' . $note->id)?>">View</a></li>
									<li><a href="javascript:delete_one( '<?php echo $note->id?>' )">Delete</a></li>								        
								</ul>
							</div><!-- /btn-group -->
						</td>                                   
					</tr>        
							
					<?php endforeach; endif;?>					
													  
				  </tbody>
			  </table> 
              <div>
                  <div class="list-footer-left"> 
                      <button type="button" class="btn btn-danger" onclick="delete_all()">Delete</button>
                      <button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('notes/add')?>'">Add New</button>
                  </div>
                  <div class="list-footer-right"> 
                      <?php echo $pager_links?>
                      <!-- <ul class="pagination">
                      <li><a href="table.html#">Prev</a></li>
                      <li class="active">
                      <a href="table.html#">1</a>
                      </li>
                      <li><a href="table.html#">2</a></li>
                      <li><a href="table.html#">3</a></li>
                      <li><a href="table.html#">4</a></li>
                      <li><a href="table.html#">Next</a></li>
                      </ul>  -->
                  </div>  
              </div>
              <input type="hidden" name="act" value="">
          </form>

      </div> <!-- /.table-responsive -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

<br /><br>
<script type="text/javascript">
	delete_one=function( id ){
		Messi.ask('Do you really want to delete the record?', function(val) { 
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('notes/delete')?>/" + id;
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
		Messi.ask('Do you really want to delete selected records?', function(val) { 
			// confirmed
			if( val == 'Y' ){
				jQuery('#frmlist').prop('action', '<?php echo site_url('notes/delete_all')?>');
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