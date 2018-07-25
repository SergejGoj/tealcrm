<style>
/*style for the search box, move it to css files later*/
.ui-autocomplete {
	z-index: 40000 !important;
}
</style>
			<div class="main ">
			
			
			<div class="row inbox">
				<?php /*
				<div class="col-md-3">
					
					<div class="panel panel-default">
						
						<div class="panel-body inbox-menu">
<h4 class="portlet-title">
                <u>Message Details</u>
              </h4>
						<div class="form-group col-sm-12">
							<label for="company_id">Company</label>
							<input type="text" name="company_viewer" id="company_viewer" class="form-control" value="<?php if(isset($company)) echo $company->company_name;?>"/>
							<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($company_id)) echo $company_id;?>" />
						</div>
														
						<div class="form-group col-sm-12">
							<label for="people_id">Person</label>
							<input type="text" name="person_viewer" id="person_viewer" class="form-control" value="<?php if(isset($person)) echo $person->first_name.' '.$person->last_name;?>"/>
							<input type="hidden" name="people_id" id="people_id" value="<?php if(isset($people_id)) echo $people_id;?>" />
						</div>
							
						</div>	
						
					</div>
					
					<div class="panel panel-default">
				
						<div class="panel-body contacts">
							<h4 class="portlet-title">
                <u>Task Reminder</u>
              </h4>		
              
              						<div class="form-group">
							<label for="company_id">Create Task</label><br/><input type="checkbox" name="create_task"/> 

						</div>
						
					<div class="form-group">
						<label for="subject">Subject</label>
						<input type="text" name="subject" id="subject" class="form-control"  placeholder="Enter subject">
					</div>
					

						<div class="form-group">
							<label for="due_date">Reminder (Due) Date</label>
							<input type="text" name="due_date" id="due_date" class="form-control datetime"  placeholder="Enter due date">
						</div>

						
						</div>
					
					</div>			
					
				</div><!--/.col-->
				*/ ?>
				<form class="form-horizontal" action="<?php echo site_url();?>messages/send/people/<?php echo $person->people_id;?>" role="form" method="post">
				<div class="col-md-9">
					
					<div class="panel panel-default">
						
						<div class="panel-body message">
							<?php if(isset($person->first_name) || isset($person->last_name)) {?>
							<p class="text-center"><h2>New Message for <?php echo $person->first_name." ".$person->last_name;?></h2></p>
							<?php } else {?>
							<p class="text-center"><h2>New Message</h2></p>
							<?php }?>
							
							
								<div class="form-group">
							    	<label for="to" class="col-sm-1 control-label">Subject:</label>
							    	<div class="col-sm-11">
							      		<input type="text" class="form-control" id="subject" name="subject" placeholder="Type Subject">
							    	</div>
							  	</div>
								<div class="form-group">
							    	<label for="to" class="col-sm-1 control-label">To:</label>
							    	<div class="col-sm-11">
							      		<input type="email" class="form-control" id="to" name="to_email" placeholder="Type email" value="<?php echo $person->email1;?>">
							    	</div>
							  	</div>
								<div class="form-group">
							    	<label for="cc" class="col-sm-1 control-label">CC:</label>
							    	<div class="col-sm-11">
							      		<input type="email" class="form-control" id="cc" name="cc_email"  placeholder="Type email">
							    	</div>
							  	</div>
								<div class="form-group">
							    	<label for="bcc" class="col-sm-1 control-label" name="bcc_email" >BCC:</label>
							    	<div class="col-sm-11">
							      		<input type="email" class="form-control" id="bcc" placeholder="Type email">
							    	</div>
							  	</div>
								
	
<textarea name="html_content" id="html_content" class="html_content"></textarea>
								
<br/>
    <input type="hidden" name="act" value="true">
									<button type="submit" class="btn btn-success">Send</button>
									<?php /* <button type="submit" class="btn btn-default">Draft</button> */ ?>
									<button type="submit" class="btn btn-danger" onclick="javascript:history.back();">Cancel</button>
								
							  
							

								
							</div>	

						</div>	
						
					</div>	
					
					<div class = "col-md-3">
					<!-- ARCHIVE DROPDOWN START-->
								<div class="form-group">
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
							<!-- ARCHIVE DROPDOWN START-->
								</div>
	
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

					<div class="form-group" >
					<span id = "related_record_name" class = "related_record_name"><strong>No Record Selected</strong></span>
									
					</div>
					
				</div><!--/.col-->
					
					</form>
						

						
			</div><!--/row-->
		</div>	
			
<link rel="stylesheet" href="css/summernote.css">
<script type="text/javascript" src="js/summernote.js"></script>
  <script type="text/javascript">

	var selection;

    $(function() {
      $('.html_content').summernote({
        height: 200,
        onfocus: function(e) {
	          	selection = document.getSelection();
  			},
  		onkeydown: function(e) {
	  			selection = document.getSelection();
  		},
  		onkeyup: function(e) {
	  		selection = document.getSelection();
  		},
  		toolbar: [				 
					['style', ['bold', 'italic', 'underline', 'clear']]
				]
      });

    });


$('.note-toolbar .note-fontsize, .note-toolbar .note-color, .note-toolbar .note-para .dropdown-menu li:first, .note-toolbar .note-line-height').remove();


	$(document).ready(function() {
	$("select#module").change(function() {
		var selected_module = $("select#module option:selected").attr('value');
		// alert(country_id);
		$("#fields").html("");
		$.ajax({
			type: "GET",
			url: "/ajax/TemplatesFieldList/" + selected_module,
			data: "module=" + selected_module,
			cache: false,
			beforeSend: function() {
				$('#fields').html('<img src="loader.gif" alt="" width="24" height="24">');
			},
			success: function(html) {
				$("#fields").html(html);
			}
		});
	});
});
</script>			
			
			<script type="text/javascript">

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd").validate({
			ignore: "",
			rules: {
				subject: "required",
				priority_id: "required",
				status_id: "required"
			},
			messages: {
				subject: "Enter name",
				priority_id: "Select priority",
				status_id: "Select status"
			},
			errorPlacement: function(error, element) {
		        error.insertAfter(element.parent().find('label:first'));
			},
			invalidHandler: function(form, validator) {
				//manually highlight the main accordion
				$(".panel").removeClass("is-open");

				//manually close all accordions except collapseOne
				$(".panel-collapse").each(function(e){
					if( $(this).attr("id") != "collapseOne" )
						$(this).removeClass('in');
				});

				//manually highlight the header of collapseOne
				if( $("#collapseOne").parent().hasClass("is-open") == false )
					$('#collapseOne').parent().addClass('is-open');

				//check if collapseOne is open or not, if not then open it
				if( $("#collapseOne").hasClass("in") == false )
					$('#collapseOne').collapse('show');
			},
			errorElement: 'em'
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
				$("#deal_id").val("");
				$("#person_id").val("");
				
				document.getElementById("related_record_name").innerHTML = "<b>Company Name:  </b>"+ui.item.name;
				

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


		//autocomplete for people
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
				
				$("#company_id").val("");
				$("#deal_id").val("");
								
				document.getElementById("related_record_name").innerHTML = "<b>Person Name:  </b>"+ui.item.name;
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

		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y H:i',
			mask: true
		});
	});
	
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
			
			$("#company_id").val("");
			$("#person_id").val("");
				
			document.getElementById("related_record_name").innerHTML = "<b>Deal Name:  </b>"+ui.item.name;
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

</script>