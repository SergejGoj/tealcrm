<?php
// TEALCRM
// DROP DOWN EDITOR


?>

<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">
      
      <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
        <li>
          <a href="<?php echo site_url('settings')?>/#crm-settings">
          <i class="fa fa-user"></i> 
          &nbsp;&nbsp;Back to CRM Settings
          </a>
        </li>
		
      
		<li id="sales"  class="active">
           <a href="<?php echo site_url('settings')?>/#edit-custom-fields" data-toggle="tab">
           <i class="fa fa-building"></i> 
          &nbsp;&nbsp;Edit Existing Field 
          </a>           
        </li>

      </ul>

    </div> <!-- /.col -->


 <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <div class="tab-pane fade in active">

        <h3 class="content-title">Edit Existing Field</h3>

        <div><?php display_notify('settings_crm_settings') ?> </div>

        

<?php 

if (!empty($success)) : ?>
<div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><?php echo $success;?></div>
<?php endif;

echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>', '</div>'); 
            
$attributes = array('class' => 'form-horizontal', 'id' => 'frmprofile', 'name' => 'frmprofile');            

echo form_open('settings/custom_edit/', $attributes);
   foreach($custom_field as $usr){

 ?><form id="frmedit" >
          <div class="form-group">
              <label class="col-md-3">Module</label>	
             <div class="col-md-7"> 
           <?php 	 echo form_dropdown('module', $modules, $usr->cf_module,"class='form-control' id='module'"); ?>
          </div>
	    </div>

          <div class="form-group">

		  
            <label class="col-md-3">Field Name</label>

            <div class="col-md-7">
              <input type="text" name="field_name" value="<?php echo $usr->cf_name; ?>" class="form-control" disabled/>
			  <input type="hidden" name="old_field" value="<?php echo $usr->cf_name; ?>" />
			  <input type="hidden" name="type" value="<?php echo $usr->cf_type;?>" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
		  
		  <div class="form-group">

            <label class="col-md-3">Label Name</label>

            <div class="col-md-7">
              <input type="text" name="label_name" value="<?php echo $usr->cf_label; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
          
         <div class="form-group">
              <label class="col-md-3">Type</label>	
             <div class="col-md-7"> 
	             <select name="datatype" id="datatype" class="form-control" disabled/>
	             <option value="Textbox" <?php if ($usr->cf_type == "Textbox") { echo "selected";}?>>Textbox</option>
	             <option value="Dropdown" <?php if ($usr->cf_type == "Dropdown") { echo "selected";}?>>Dropdown</option>
	             </select>          </div>
	    </div>
		
		
			<div class="form-group" id="dropdownvaluediv">

            <label class="col-md-3" >Data</label>

            <div class="col-md-7">
              <input type="text" name="data" value="<?php echo $usr->cf_data; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-actions">
            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Update Field</button>
              &nbsp;
              <button type="reset" class="btn btn-default" onclick="return cancel(this)">Cancel</button>
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->
           <input type="hidden" name="act" value="custom-edit" />
		   <input type="hidden" name="act1" value="<?php echo $usr->cf_id; ?>" />
        </form>
		
<?php } ?>

      </div> <!-- /.tab-pane -->


     
			  

		   <!-- /.col -->

		  

           <!-- /.row --> 

       <!-- /.tab-pane -->

    <!-- /.tab-content -->

   <!-- /.col -->

</div><!--.col -->


</div> <!-- /.row -->

<script type="text/javascript">
cancel=function(elm){
		window.location.href = '<?php echo site_url('settings/#edit-custom-fields')?>';
		return false;
	}
	// delete single record
	delete_one=function(e){
		Messi.ask('Do you really want to delete the record?', function(val) { 
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('drop_down_editor/delete')?>/" + $("#edit_field").val();
			}
		}, {modal: true, title: 'Confirm Delete'});		
	}

	//prevent submitting the value if there is one already existing
	function checkDuplicats(){
		var pass = false;
		var val = $.trim($("#new_edit_field").val().toLowerCase());
		if(val !== ""){
			pass = true;
			$("#edit_field option").each(function(){
				if($(this).text().toLowerCase() == val) 
					pass = false;
			});
		}
		
		if(pass){
			return true;
		}else{
			alert("The Label " + $("#new_edit_field").val() + " already exists!");
			return false;
		}
	}
	
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
            var ret = ((keyCode >= 48 && keyCode <= 57) || (keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
            document.getElementById("error").style.display = ret ? "none" : "inline";
            return ret;
        }
		
$("#datatype").change(function(){
var dropdownvalue = $("#datatype").val(); 
if (dropdownvalue == "Dropdown")
{
 $("#dropdownvaluediv").css("display", "block");
}
else
{
$("#dropdownvaluediv").hide();

} 
})				

 jQuery(document).ready(function(){
var dropdownvalue = $("#datatype").val(); 
if (dropdownvalue == "Dropdown")
{
 $("#dropdownvaluediv").css("display", "block");
}
else
{
$("#dropdownvaluediv").hide();

} 
})				

 jQuery(document).ready(function(){
              var validator = jQuery("#frmprofile").validate({
			    rules: {
                field_name: {required: true},
                label_name: {required : true},
				data :{required: true},
              },
              messages: {
                field_name: {required: "Required"},
                label_name: {required :"Required"},
				data :{required: "Required"},
              },
              errorPlacement: function(error, element) {            
                error.insertAfter(element.parent().parent().find('label:first'));             
              },
              errorElement: 'em',
              errorClass: 'login_error'
            });
          });	
</script>
