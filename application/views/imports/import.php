
<h2 class="portlet-title">Match Fields</h2>	
<form name="frmadd" id="frmadd" action="<?php echo site_url('imports/import')?>" form="return validateReq()" method="post" class="form parsley-form">
    <div class="panel-group accordion-panel" id="accordion-paneled">
        <div class="panel panel-default open">
            <div class="panel-heading">
				<?php if($step == 1 || $step == 2){ ?>
                	<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Step <?php echo $step;?>/2</a></h4>
<?php } ?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse in" id="collapseOne">
                <div class="panel-body">
                    <div class="form-group">
						<?php echo $people; ?>
                    </div>
                </div>
                <!-- /.panel-collapse -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.accordion -->
    <div class="form-actions">
		<?php echo $nav_buttons;?>
    </div>
    <input type="hidden" name="act" value="save">
	<input type="hidden" name="module" value="<?php echo $module;?>">
	<input type="hidden" name="link_accounts" value="<?php echo $link_account_checkbox; ?>">
	<input type="hidden" name="create_accounts" value="<?php echo $create_accounts_checkbox; ?>">
</form>
<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('people')?>';
		return false;
	}
	/*
			rules: {
				lead_source_id: "required",	
				job_title: "required",				
				first_name: "required",	
				birthdate: "required",				
				email1: {required: true, email: true},
				email2: {required: false, email: true},
				postal_code: "required",
				country: "required"
			},
			messages: {
				lead_source_id: "Enter lead source",
				job_title: "Enter job",				
			  	first_name: "Enter name",
				birthdate: "Enter date of birth",
				email1: {required: "Enter email address", email: "Enter a valid email address i.e. me@somewhere.com"},
				email2: {email: "Enter a valid email address i.e. me@somewhere.com"},
				postal_code: "Enter valid postal code",
				country: "Select country"
			},
			errorPlacement: function(error, element) {		        
		        error.insertAfter(element.parent().find('label:first'));		        
			},
			errorElement: 'em'
			*/
	function validateForm(class_name, error_msg){
		$(class_name).each(function(){
			var count = $(this).parent().parent().parent().find(".countRows").val();
			//console.log(count);
			//check only the records that the user didn't check "Ignore This Contact"
			if( ! $("#ignore_"+count).prop("checked") ){
				if($(this).val() == ""){
					$(this).addClass("error");
					if($(this).parent().find('em').length == 0)
						$("<em for='" + $(this).attr("id") + "' class='error'>" + error_msg + "</em>").insertAfter($(this).parent().find('label:first'));
					$(this).focus();
					return false;
				}else{
					$(this).removeClass("error");
					$(this).parent().remove('em');				
				}
				$(this).prop("required", true);
			}else{
				//remove required attr from ignored records
				$(this).prop("required", false);
			}
		});
		return true;
	}
	
	// document ready
	$(document).ready(function(){
		
		//validate fields before finish
		$("#import_finish").click(function(){
			console.log("validate id");
			if(! validateForm(".lead_source_id", "Enter lead source")) return false;

			console.log("validate job_title");
			if(! validateForm(".job_title", "Enter job")) return false;

			console.log("validate first_name");
			if(! validateForm(".first_name", "Enter First Name")) return false;
		});
		
		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y',
			mask: true,
			timepicker: false
		});
	});
	

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

function defaultDupeCheck(name) {
	var dd = document.getElementById(name);
	var checkBoxName = "duplicate_" + name.toString();
	var checkBox = document.getElementById(checkBoxName);
	var field_name = dd.options[dd.selectedIndex].value.toString();
	var dd_JSON = $("#default_duplicate_fields").val();
	
	var default_duplicates = $.parseJSON(dd_JSON);


	if (inArray(field_name,default_duplicates)) {
		checkBox.checked = true;
	}
	else {
		checkBox.checked = false;
	}
}
//check if all required fields are passed
function validateReq(){
    var str = $("#req_fields").val();
    var res = str.split(", ");
    var number_required_fields = res.length;
    $("select option:selected").each(function(e){
        if(inArray($(this).val(), res)) number_required_fields -= 1;
    });
    if( number_required_fields === 0 ) return true;
	return false;
    //alert(number_required_fields);    
}
</script>