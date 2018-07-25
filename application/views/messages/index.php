<style>
/*style for the search box, move it to css files later*/
.ui-autocomplete {
	z-index: 40000 !important;
}
</style>
 

 <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('/messages/index')?>" method="post">
 <h3 class="content-title">Messages</h3>
 
 <br>
	 <div class="btn-group">
		<a class="btn btn-tertiary">Archive</a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>

		<ul class="dropdown-menu">
		
			<li>
				<a href="javascript: $('#searchcompany').text('ADD COMPANY');return false;" data-target="#add-modal" data-toggle="modal">Company</a>
			</li>
			
			<li>
				<a href="javascript: $('#searchperson').text('ADD Person');return false;" data-target="#add-modal1" data-toggle="modal">Person</a>
			</li>
			
			<li>
				<a href="javascript: $('#searchdeal').text('ADD Deal');return false;" data-target="#add-modal2" data-toggle="modal">Deal</a>
			</li>
		
		</ul>
		
	</div><!-- /.btn-gruop -->
	
	<!----- MODEL WINDOW IN ADDING COMPANY ---------->
			
	<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="searchcompany" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="searchcompany">Add Company</h4>
		  </div>
		  <div class="modal-body">
			<input type="text" name="company_viewer" id="company_viewer" class="form-control" value = "" ><br/>
			<input type="hidden" name="company_id" id="company_id" value = "">
															
			<button type="button" id="addcompany"  class="btn btn-success" data-dismiss="modal" >Add Company</button>
			<button type="button" id="clearcompany"  class="btn btn-danger">Clear Company</button>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-------- MODEL WINDOW END FOR COMPANY ------->		
	
	<!--------- MODEL WINDOW IN ADDING PERSON ------>
			
	<div class="modal fade" id="add-modal1" tabindex="-1" role="dialog" aria-labelledby="searchperson" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="searchperson">Add Person</h4>
		  </div>
		  <div class="modal-body">
			<input type="text" name="person_viewer" id="person_viewer" class="form-control" value = "" ><br/>
			<input type="hidden" name="person_id" id="person_id" value = "">
															
			<button type="button" id="addperson"  class="btn btn-success" data-dismiss="modal" >Add Person</button>
			<button type="button" id="clearperson"  class="btn btn-danger">Clear Person</button>
		  </div>
		</div>
	  </div>
	</div>		
			
	<!------- MODEL WINDOW END FOR PERSON ----->		
	
	<!------ MODEL WINDOW IN ADDING DEAL ------->
			
	<div class="modal fade" id="add-modal2" tabindex="-1" role="dialog" aria-labelledby="searchdeal" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="searchdeal">Add Deal</h4>
		  </div>
		  <div class="modal-body">
			<input type="text" name="deal_viewer" id="deal_viewer" class="form-control" value = "" ><br/>
			<input type="hidden" name="deal_id" id="deal_id" value = "">
															
			<button type="button" id="adddeal"  class="btn btn-success" data-dismiss="modal" >Add Deal</button>
			<button type="button" id="cleardeal"  class="btn btn-danger">Clear Deal</button>
		  </div>
		</div>
	  </div>
	</div>	
			
	<!-------MODEL WINDOW END FOR DEAL ----->
<button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('messages/send')?>'">Send Message</button>
<br><br>
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
                          <th align="center" width="5%"><input type="checkbox" name="select_all" value="ids[]"></th>
                          <th align="center" width="35%">Subject</th>
                          <th align="center" width="15%">From</th>
                          <th align="center" width="15%">Date</th>
						  <th align="center" width="5%">Category</th>
						  <th align="center" width="5%">Status</th>
						  <th align="center" width="15%">Relate</th>
                      </tr>
                  </thead>
                  <tbody>
				  <?php 
				  if(isset($messages))
				  {
					foreach ($messages as $email) 
					{
					?>
						<tr>
                          <td class="valign-middle"><input type="checkbox" name="ids[]" value="<?php echo $start; ?>"><input type="hidden" name="message_id[]" value="<?php echo $email['message_id']; ?>"></input></td>
                          <td class="valign-middle" ><a href="<?php echo site_url('messages/view/'.$start); ?>"><?php echo $email['subject'];?></a></td>
                          <td class="valign-middle"><?php echo $email['from_name']; ?></td>
						  <td class="valign-middle"><?php echo $email['timestamp']; ?></td>
						  <td class="valign-middle"><?php echo $email['category']; ?></td>
						  <?php if($email['status'] != 'Not Archived') {?>
						  
						  <td class="valign-middle">Archived</td>
						  
						  <?php } else {?>
						  <td class="valign-middle"><?php echo $email['status']; ?></td>
						  <?php }?>
						  
						  <?php if($email['status'] != 'Not Archived') {?>
						  
						  <td class="valign-middle"><?php if($email['status'] == "Company") {?><a href = "<?php echo site_url('/companies/view/'.$email['relationship_id']);?>"><?php echo $relat_rec[$email['relationship_id']]; ?></a><?php } else if($email['status'] == "Person"){?><a href = "<?php echo site_url('/people/view/'.$email['relationship_id']);?>"><?php echo $relat_rec[$email['relationship_id']];?></a><?php } else if($email['status'] == "Deal") {?><a href = "<?php echo site_url('/deals/view/'.$email['relationship_id']); ?>"><?php echo $relat_rec[$email['relationship_id']];;?></a><?php } ?></td>
						  
						  <?php } else { ?>
						  
						  <td class="valign-middle"></td>
						  
						  <?php } ?>
						</tr>
				  <?php 
					$start++;
					}
				  }
				  else
				  {
					?>
					  <tr>
                          <td colspan="6" align="center">No Emails</td>
                      </tr>		
					<?php 
				  }
				  ?>
                  </tbody>
              </table>
			  
<div class="btn-group">
	<a class="btn btn-tertiary">Archive</a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>

	<ul class="dropdown-menu">
	
		<li>
			<a href="javascript: $('#searchcompany').text('ADD COMPANY');return false;" data-target="#add-modal" data-toggle="modal">Company</a>
		</li>
		
		<li>
			<a href="javascript: $('#searchperson').text('ADD Person');return false;" data-target="#add-modal1" data-toggle="modal">Person</a>
		</li>
		
		<li>
			<a href="javascript: $('#searchdeal').text('ADD Deal');return false;" data-target="#add-modal2" data-toggle="modal">Deal</a>
		</li>
	
	</ul>
	
</div><!-- /.btn-gruop -->

<button type="button" class="btn btn-success" onclick="window.location.href='<?php echo site_url('messages/send')?>'">Send Message</button>
<div class="list-footer-right">
           <?php echo $pager_links?>
</div>      
	  </form>

<script>
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
		minLength: 0,
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
	}).focus(function() {
			$(this).autocomplete("search", "");
		});;
	
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
		minLength: 0,
		select: function( event, ui ) {
			console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
			$("#person_id").val(ui.item.id);
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	}).focus(function() {
			$(this).autocomplete("search", "");
		});
	
	//autocomplete for DEAL
	$( "#deal_viewer" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "/ajax/dealsAutocomplete",
				dataType: "json",
				data: {
					q: request.term
				},
				success: function( data ) {
					response( data );
				}
			});
		},
		minLength: 0,
		select: function( event, ui ) {
			console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
			$("#deal_id").val(ui.item.id);
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	}).focus(function() {
			$(this).autocomplete("search", "");
		});


	//ADD RELATIONSHIP TO COMPANY
	$('#addcompany').on('click',function(e){
		
		var company_id = $('#company_id').val();
			
		size = jQuery(":input[name='ids[]']:checked").size();
		 if( size == 0 )
		{

			window.location.href='<?php echo site_url('messages/index')?>';
		}

		else
		{

			jQuery('#frmlist').prop('action', '<?php echo site_url('messages/archive_all')?>'+'/'+company_id+'/Company');
			jQuery(":input[name='act']").val('archive');
			jQuery('#frmlist').submit();
	   }
	});
			
		
		$('#clearcompany').on('click',function(e){
		$('#company_viewer').val("");
		$('#company_id').val("");
	});
	
	//ADD RELATIONSHIP TO PERSON
	$('#addperson').on('click',function(e){
		
		var person_id = $('#person_id').val();
			
		size = jQuery(":input[name='ids[]']:checked").size();
		 if( size == 0 )
		{

			window.location.href='<?php echo site_url('messages/index')?>';
		}

		else
		{

			jQuery('#frmlist').prop('action', '<?php echo site_url('messages/archive_all')?>'+'/'+person_id+'/Person');
			jQuery(":input[name='act']").val('archive');
			jQuery('#frmlist').submit();
	   }
	});
		
		$('#clearperson').on('click',function(e){
		$('#person_viewer').val("");
		$('#person_id').val("");
	});
	
	//ADD RELATIONSHIP TO DEAL
	$('#adddeal').on('click',function(e){
		
		var deal_id = $('#deal_id').val();
			
		size = jQuery(":input[name='ids[]']:checked").size();
		 if( size == 0 )
		{

			window.location.href='<?php echo site_url('messages/index')?>';
		}

		else
		{

			jQuery('#frmlist').prop('action', '<?php echo site_url('messages/archive_all')?>'+'/'+deal_id+'/Deal');
			jQuery(":input[name='act']").val('archive');
			jQuery('#frmlist').submit();
	   }
	});
		
		$('#cleardeal').on('click',function(e){
		$('#deal_viewer').val("");
		$('#deal_id').val("");
	});	
	
	
</script>	
