<h2 class="portlet-title">Add New Project</h2>
 

<form name="frmadd" id="frmadd" action="<?php echo site_url('projects/add')?>" method="post" class="form parsley-form">
    <div class="panel-group accordion-panel" id="accordion-paneled">
        <div class="panel panel-default open">
            <div class="panel-heading">
                <h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Basic Info</a></h4>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse in" id="collapseOne">
                <div class="panel-body">

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="project_name">Project Name</label>
                            <input type="text" name="project_name" id="project_name" class="form-control" placeholder="Enter project name">
                        </div>
                       <div class="form-group col-sm-6">
                            <label for="assigned_user_id">Assigned User</label>
							<?php 	 echo form_dropdown('assigned_user_id', $assignedusers1, $_SESSION['user']['uacc_uid'],"class='form-control'"); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="company">Company</label>
							<input type="text" name="company_viewer" id="company_viewer" placeholder="Start typing here" class="form-control" value="" />
							<input type="hidden" name="company" id="company" value="" />

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="time_estimate">Time Estimate</label>
                            <input type="text" name="time_estimate" id="time_estimate" class="form-control" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;" onpaste="return false;"  placeholder="Enter Time Estimate"> <span id="error" style="color: Red; display: none"> Enter Integer Value</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="start_date">Start Date</label>
                            <input type="text" name="start_date" id="start_date" class="form-control datetime" placeholder="Enter start date" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="end_date">End Date</label>
                            <input type="text" name="end_date" id="end_date" class="form-control datetime" placeholder="Enter end date">
                        </div>
                   </div>
				    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="description">Comments/Description</label>
                            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter comments/descriptions"></textarea>
                        </div>
                    </div>
               </div>
                <!-- /.panel-collapse -->
            </div>
            <!-- /.panel -->
        </div>
   <!-- /.accordion -->
	</div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary" >Save</button>
        <button type="button" class="btn btn-default" onclick="cancel()">Cancel</button>
    </div>
    <input type="hidden" name="act" value="save">
	
</form>
<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('projects')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd").validate({
			ignore: "",
			rules: {
				project_name: "required"
			},
			messages: {
			  	project_name: "Enter Project Name"
			},
			errorPlacement: function(error, element) {
		        error.insertAfter(element.parent().find('label:first'));
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
			minLength: 3,
			select: function( event, ui ) {
				console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
				$("#company").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		});

		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y',
			mask: true,
			timepicker: false
		});

	});
	
	
	var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        specialKeys.push(9); //Tab
        specialKeys.push(46); //Delete
        specialKeys.push(36); //Home
        specialKeys.push(35); //End
        specialKeys.push(37); //Left
        specialKeys.push(39); //Right
        function IsAlphaNumeric(e) {
            var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
            var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode <= 65 && keyCode >= 90) || (keyCode <= 97 && keyCode >= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
            document.getElementById("error").style.display = ret ? "none" : "inline";
            return ret;
        }
	
</script>