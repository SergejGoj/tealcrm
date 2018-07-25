
<h3 class="content-title">Archive Projects</h3>

  <div class="row">

    <div class="col-md-12">

      <div class="table-responsive">
	
          <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('people')?>" method="post">
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
							<th><input type="checkbox" name="select_all" value="ids[]"></th>
							<th>Name</th>
							<th>Time Estimate</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Actions</th>
					  </tr>
                  </thead>
                  <tbody>
                     <?php if($total_count == 0) {?>
                      <tr>
                          <td colspan="8" align="center">No Projects Found</td>
                      </tr>
                      
					  <?php } else {
					  foreach($asc_project as $proj) {?>
							<tr>
							
								<td><input type="checkbox" name="ids[]" value="<?php echo $proj->project_id?>"></td>
								<td><?php echo $proj->project_name; ?></td>
								<td><?php echo $proj->time_estimate?></td>
								<td><?php echo $proj->start_date?></td>
								<td><?php echo $proj->end_date?></td>
								
                          <td class="valign-middle">
                              <a href="<?php echo site_url('projects/edit/' . $proj->project_id.'/archived')?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
                          </td>
						</tr>
							
							<?php } }?>
                  
				  </tbody>
              </table>
              <div>
                  <div class="list-footer-left">
                      <button type="button" class="btn btn-success" onclick="unarchive_all()">Unarchive</button>
                     
                  </div>
                  
              </div>
              <input type="hidden" name="act" value="">
		</form>

      </div> <!-- /.table-responsive -->

  </div> <!-- /.col -->



</div> <!-- /.row -->



<script type="text/javascript">
 
function addtask(project_id)
{
	$('#project_id').val(project_id);
	$('#add_task').modal( "show" );
	
}

// document ready
	jQuery(document).ready(function(){
		jQuery(":input[name='select_all']").bind('click', function(){
			jQuery(":input[name='" + jQuery(this).val() + "']").prop('checked', jQuery(this).prop('checked'));
		});
	});

jQuery(document).ready(function(){
		
	var validator = jQuery('#frmadd1').validate({
			rules: {
				subject: "required"
			},
			messages: {
				subject: "Enter subject"
			},
			errorPlacement: function(error, element) {
				error.insertAfter(element.parent().find('label:first'));
			},
			errorElement: 'em'
		});
				
	});
	
	// picker
	jQuery('.datetime').datetimepicker({
		format: 'm/d/Y H:i',
		mask: true
	});
		
	jQuery(document).ready(function(){
	
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
			}).focus(function() {
				$(this).autocomplete("search", "");
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
		}).focus(function() {
				$(this).autocomplete("search", "");
			});
		
		});
	
		function updatetask(task_id,project_id)
		{
				
			window.location.href = '<?php echo site_url('projects/updatetask')?>/'+task_id+'/'+project_id+'/archived';
			return false;
			
		}	
	
	// unarchive all(selected) project
	unarchive_all=function( ){
		size = jQuery(":input[name='ids[]']:checked").length;
		// none selected
		if( size == 0 ){
			Messi.alert('Please select a Project(s) to Unarchive',function(){

			}, {modal: true, title: 'Confirm Unarchive'});

			return;
		}
		// confirm
		Messi.ask('Are you sure you want to Unarchive selected Project(s)?', function(val) {
			// confirmed
			if( val == 'Y' ){
				jQuery('#frmlist').prop('action', '<?php echo site_url('projects/unarchive_all')?>');
				jQuery(":input[name='act']").val('delete');
				jQuery('#frmlist').submit();
			}
		}, {modal: true, title: 'Confirm Unarchive'});
	}
</script>
      
