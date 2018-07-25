<style>
/*style for the search box, move it to css files later*/
.ui-autocomplete {
 z-index: 40000 !important;
}
</style>
<div class="row">
	<div class="col-md-3 col-sm-5 panel panel-default panel-body">
	
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
<br/></br>
			
			<!--MODEL WINDOW IN ADDING COMPANY START-->
			
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
																
				<button type="button" id="addcompany"  class="btn btn-success" data-dismiss="modal" onclick = "addcompany();">Add Company</button>
				<button type="button" id="clearcompany"  class="btn btn-danger">Clear Company</button>
			  </div>
			</div>
		  </div>
		</div>
					
		<!--MODEL WINDOW IN ADDING COMPANY END-->		
			
		<!-- MODEL WINDOW IN ADDING PERSON START-->
					
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
																
				<button type="button" id="addperson"  class="btn btn-success" data-dismiss="modal" onclick = "addperson();">Add Person</button>
				<button type="button" id="clearperson"  class="btn btn-danger">Clear Person</button>
			  </div>
			</div>
		  </div>
		</div>		
					
		<!-- MODEL WINDOW IN ADDING PERSON END-->	
			
		<!-- MODEL WINDOW IN ADDING DEAL START-->
					
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
																
				<button type="button" id="adddeal"  class="btn btn-success" data-dismiss="modal" onclick = "adddeal();">Add Deal</button>
				<button type="button" id="cleardeal"  class="btn btn-danger">Clear Deal</button>
			  </div>
			</div>
		  </div>
		</div>	
					
		<!-- MODEL WINDOW IN ADDING DEAL END-->		

		        <ul id="myTab1" class="nav nav-tabs">
          <li class="active">
            <a href="#details" data-toggle="tab">Details</a>
          </li>

        </ul>
		
        <div id="myTab1Content" class="tab-content">

 <div class="tab-pane fade active in" id="details">
 <ul class="icons-list">
		<?php if($Gmail['category'] != "SENT") {?>
			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>From Name</strong><br/><?php echo $Gmail['from_name']; ?>
				</div>
			</li>

			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>From Email</strong><br/>
					<?php echo $Gmail['from_email']; ?>
				</div>
			</li>
			
			<li>
				<div>
					<i class = "icon-li fa fa-pencil"></i> <strong>Received Date</strong><br/>
					<?php echo $Gmail['timestamp']; ?>
				</div>
			</li>
			
			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>Category</strong>
					<br/><?php echo $Gmail['category']; ?>
				</div>
			</li>
	<?php } else {?>
	
			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>To Email</strong><br/>
					<?php echo $Gmail['from_email']; ?>
				</div>
			</li>
			
			<li>
				<div>
					<i class = "icon-li fa fa-pencil"></i> <strong>Sent Date</strong><br/>
					<?php echo $Gmail['timestamp']; ?>
				</div>
			</li>
			
			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>Category</strong>
					<br/><?php echo $Gmail['category']; ?>
				</div>
			</li>
	<?php }?>
 </ul>
 </div>

        </div>
	</div>
	
	<div class="col-md-9 col-sm-7">

	<h2 class="text-left"><strong><?php echo $Gmail['subject']; ?></strong></h2>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Message</h3>
			</div>

			<div class="panel-body" id="note_feed_body">
				<div class="share-widget clearfix">
				<?php echo $Gmail['message']; ?>
				</div>
			</div>
		</div><br class="visible-xs">
		<!------------------------------------- RELATIONSHIP TAB START ------------------------------------------>
		
		<?php if($Gmail['status'] != 'Not Archived') {?>
		<div class="panel-group accordion-panel" id="accordion-paneled" style="width: 50%;">
		<div class="panel panel-default open">
				<div class="panel-heading">
					<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne"><i class="fa fa-group text-white"></i> &nbsp;&nbsp;<?php if($Gmail['status'] == 'Person') {?>People<?php } else if($Gmail['status'] == 'Company') {?>Company<?php } else if($Gmail['status'] == 'Deal'){?>Deal<?php }?>
					
				</a></h4>
				</div><!-- /.panel-heading -->

				<div class="panel-collapse collapse in" id="collapseOne">
					<div class="panel-body">
						<div class="list-group">

							<?php
							if($Gmail['status'] == 'Person')
							{		
								echo '<a class="list-group-item" href="';
								echo site_url('people/view/' . $relat_rec->people_id);
								echo '"><h5 class="list-group-item-heading">';
								echo $relat_rec->first_name." ".$relat_rec->last_name;
								echo '</h5>	<p class="list-group-item-text">';
								echo $relat_rec->job_title.'</p></a>';
							}
							else if($Gmail['status'] == 'Company')
							{
								echo '<a class="list-group-item" href="';
								echo site_url('companies/view/' . $relat_rec->company_id);
								echo '"><h5 class="list-group-item-heading">';
								echo $relat_rec->company_name;
								echo '</h5>	<p class="list-group-item-text">';
								echo '</p></a>';
							}
							else if($Gmail['status'] == 'Deal')
							{
								echo '<a class="list-group-item" href="';
								echo site_url('deals/view/' . $relat_rec->deal_id);
								echo '"><h5 class="list-group-item-heading">';
								echo $relat_rec->name;
								echo '</h5>	<p class="list-group-item-text">';
								echo 'Value: '.$relat_rec->value.'</p></a>';
							}
							?>
							</div><!-- /.list-group -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel-collapse -->
			</div><!-- /.panel -->
		</div>
	<?php }?>	
		
		<!------------------------------------- RELATIONSHIP TAB END ------------------------------------------>
		
		<br class="visible-xs">
	</div>
</div>

<script type="text/javascript">
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
		addcompany = function(){
			var company_id = $('#company_id').val();
			window.location.href = '<?php echo site_url('messages/addrelationship/'.$index_id)?>/' + company_id +'/Company';
		}
		
		$('#clearcompany').on('click',function(e){
		$('#company_viewer').val("");
		$('#company_id').val("");
	});
	
	//ADD RELATIONSHIP TO PERSON
	addperson = function(){
			var person_id = $('#person_id').val();
			window.location.href = '<?php echo site_url('messages/addrelationship/'.$index_id)?>/' + person_id +'/Person';
		}
		
		$('#clearperson').on('click',function(e){
		$('#person_viewer').val("");
		$('#person_id').val("");
	});
	
	//ADD RELATIONSHIP TO DEAL
	adddeal = function(){
			var deal_id = $('#deal_id').val();
			window.location.href = '<?php echo site_url('messages/addrelationship/'.$index_id)?>/' + deal_id +'/Deal';
		}
		
		$('#cleardeal').on('click',function(e){
		$('#deal_viewer').val("");
		$('#deal_id').val("");
	});
</script>