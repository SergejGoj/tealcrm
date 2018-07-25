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

        
        <li class="active">
          <a href="<?php echo site_url('settings')?>/#drop-down-editor" data-toggle="tab">
          <i class="fa fa-archive"></i> 
          &nbsp;&nbsp;Deals Settings
          </a>
        </li>

      </ul>

    </div> <!-- /.col -->


  <div class="col-md-9 col-sm-8 layout-main">


        <h3 class="content-title">Deals Settings</h3>

This utility allows you to customize your Deals management settings.
 <br/><br/>

<h3>Sales Stage Ordering</h3>


</div> <!-- /.row -->

<script type="text/javascript">
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

</script>
