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

        <li   class="active">
           <a href="<?php echo site_url('settings')?>/#custom-fields-add" data-toggle="tab">
           <i class="fa fa-building"></i>
          &nbsp;&nbsp;Create New Field
          </a>
        </li>



		<li id="sales">
           <a href="<?php echo site_url('settings')?>/#custom-fields" data-toggle="tab">
           <i class="fa fa-building"></i>
          &nbsp;&nbsp;Edit Existing Field
          </a>
        </li>

	<!--	<li id="sales">
           <a href="<?php //echo site_url('settings')?>/#custom-fields" data-toggle="tab">
           <i class="fa fa-building"></i>
          &nbsp;&nbsp;Delete
          </a>
        </li>
-->
      </ul>

    </div> <!-- /.col -->


 <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">



	 <div class="tab-pane fade in active" id="custom-fields-add">

        <h3 class="content-title">Create New Field</h3>

        <div><?php display_notify('settings_crm_settings') ?></div>



<?php

if (!empty($success)) : ?>
<div class="alert alert-success">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a><?php echo $success;?></div>
<?php endif;

/*echo validation_errors('<div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>', '</div>');
*/
$attributes = array('class' => 'form-horizontal', 'id' => 'frmprofile', 'name' => 'frmprofile');

echo form_open('settings/custom_fields', $attributes); ?>
  <form name="formadd" id="formadd" >
          <div class="form-group">
              <label class="col-md-3">Module</label>
             <div class="col-md-7">
           <?php 	 echo form_dropdown('module', $modules, '118',"class='form-control' id='module'"); ?>
          </div>
	    </div>


          <div class="form-group">


            <label class="col-md-3">Field Name</label>

            <div class="col-md-7">
              <input type="text" name="field_name" id="field_name" value="" class="form-control" onkeypress="return IsAlphaNumeric(event);" ondrop="return false;" onpaste="return false;"/>
			  <span id="error" style="color: Red; display: none"> Special Characters not allowed</span>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

		  <div class="form-group">

            <label class="col-md-3">Label Name</label>

            <div class="col-md-7">
              <input type="text" name="label_name" id="label_name" value="" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

          <div class="form-group">
              <label class="col-md-3">Type</label>
             <div class="col-md-7">
	             <select name="datatype" id="datatype" class="form-control">
	             <option value="Textbox" selected="true">Textbox</option>
	             <option value="Dropdown">Dropdown</option>
	             </select>
          </div>
	    </div>

		  <div class="form-group" id = "dropdownvaluediv" style="display: none;">

            <label class="col-md-3">Dropdown Values  <em>(Separate by comma's)</em> </label>

            <div class="col-md-7">
              <textarea type="text" name="data" value="" class="form-control" id="textvlue" ></textarea>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->




          <div class="form-actions">
            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default" onclick="return cancel(this)">Cancel</button>
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->
           <input type="hidden" name="act" value="custom-fields" />
		   <input type="hidden" name="act1" value="custom-fields" />
        </form>


      </div> <!-- /.tab-pane -->



    <div class="tab-pane fade " id="custom-fields">

        <h3 class="content-title">Edit Existing Field</h3>

       <div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<div class="form-group">
						<ul id="myTab1" class="nav nav-tabs">
							<li class="<?php  echo 'active';?>">
							<a href="#accounts_custom" data-toggle="tab">Companies</a>
							</li>

							<li class="<?php //if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
							<a href="#people_custom" data-toggle="tab">People</a>
							</li>

							<li class="<?php //if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
							<a href="#deals_custom" data-toggle="tab">Deals</a>
							</li>

							<li class="<?php //if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
							<a href="#notes_custom" data-toggle="tab">Notes</a>
							</li>


							<li class="<?php //if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
							<a href="#tasks_custom" data-toggle="tab">Tasks</a>
							</li>

							<li class="<?php //if($search_tab == "advanced" || $search_tab == "saved"){ echo 'active';}?>">
							<a href="#meetings_custom" data-toggle="tab">Meetings</a>
							</li>

							 <div class="form-group">
							 </ul>

                          <div id="myTab1Content" class="tab-content">


	<div class="tab-pane fade active in<?php //if($search_tab == "basic"){ echo 'active in';}?>" id="accounts_custom">
 <form class="form-horizontal" name="frmlist" id="frmlist" action="<?php echo site_url('people')?>" method="post">
	    <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>

									<th>Field Name</th>
									<th>Label</th>
									<th>Type</th>
									<th>Action</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($accounts_custom as $usr){?>
									<tr>
										<td><?php echo $usr->cf_name; ?></td>
										<td><?php echo $usr->cf_label;?></td>
										<td><?php echo $usr->cf_type;?></td>
										<td class="valign-middle" align="center">
											<a href="<?php echo site_url('settings/custom_edit/' . $usr->cf_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											<a href="javascript:delete_one( '<?php echo $usr->cf_id; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
											&nbsp;


										</td>
									</tr>
							  <?php } ?>
						  </tbody>
               </table>


	        </div> <!-- /.table-responsive -->
       </form>
	</div>

	<div class="tab-pane fade<?php //if($search_tab == "basic"){ echo 'active in';}?>" id="people_custom">
 <form class="form-horizontal" name="frmlist1" id="frmlist1" action="<?php echo site_url('people')?>" method="post">
	    <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>

									<th>Field Name</th>
									<th>Label</th>
									<th>Type</th>
									<th>Action</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($people_custom as $usr){?>
									<tr>
										<td><?php echo $usr->cf_name; ?></td>
										<td><?php echo $usr->cf_label;?></td>
										<td><?php echo $usr->cf_type;?></td>
										<td class="valign-middle" align="center">
											<a href="<?php echo site_url('settings/custom_edit/' . $usr->cf_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											<a href="javascript:delete_one( '<?php echo $usr->cf_id; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
											&nbsp;


										</td>
									</tr>
							  <?php } ?>
						  </tbody>
               </table>


	        </div> <!-- /.table-responsive -->
       </form>
	</div>


	<div class="tab-pane fade <?php //if($search_tab == "basic"){ echo 'active in';}?>" id="deals_custom">
 <form class="form-horizontal" name="frmlist2" id="frmlist2" action="<?php echo site_url('people')?>" method="post">
	    <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>

									<th>Field Name</th>
									<th>Label</th>
									<th>Type</th>
									<th>Action</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($deals_custom as $usr){?>
									<tr>
										<td><?php echo $usr->cf_name; ?></td>
										<td><?php echo $usr->cf_label;?></td>
										<td><?php echo $usr->cf_type;?></td>
										<td class="valign-middle" align="center">
											<a href="<?php echo site_url('settings/custom_edit/' . $usr->cf_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											<a href="javascript:delete_one( '<?php echo $usr->cf_id; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
											&nbsp;


										</td>
									</tr>
							  <?php } ?>
						  </tbody>
               </table>


	        </div> <!-- /.table-responsive -->
       </form>
	</div>


	<div class="tab-pane fade <?php //if($search_tab == "basic"){ echo 'active in';}?>" id="notes_custom">
 <form class="form-horizontal" name="frmlist3" id="frmlist3" action="<?php echo site_url('people')?>" method="post">
	    <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>

									<th>Field Name</th>
									<th>Label</th>
									<th>Type</th>
									<th>Action</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($notes_custom as $usr){?>
									<tr>
										<td><?php echo $usr->cf_name; ?></td>
										<td><?php echo $usr->cf_label;?></td>
										<td><?php echo $usr->cf_type;?></td>
										<td class="valign-middle" align="center">
											<a href="<?php echo site_url('settings/custom_edit/' . $usr->cf_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											<a href="javascript:delete_one( '<?php echo $usr->cf_id; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
											&nbsp;


										</td>
									</tr>
							  <?php } ?>
						  </tbody>
               </table>


	        </div> <!-- /.table-responsive -->
       </form>
	</div>




	<div class="tab-pane fade <?php //if($search_tab == "basic"){ echo 'active in';}?>" id="tasks_custom">
 <form class="form-horizontal" name="frmlist5" id="frmlist5" action="<?php echo site_url('people')?>" method="post">
	    <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>

									<th>Field Name</th>
									<th>Label</th>
									<th>Type</th>
									<th>Action</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($tasks_custom as $usr){?>
									<tr>
										<td><?php echo $usr->cf_name; ?></td>
										<td><?php echo $usr->cf_label;?></td>
										<td><?php echo $usr->cf_type;?></td>
										<td class="valign-middle" align="center">
											<a href="<?php echo site_url('settings/custom_edit/' . $usr->cf_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											<a href="javascript:delete_one( '<?php echo $usr->cf_id; ?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
											&nbsp;


										</td>
									</tr>
							  <?php } ?>
						  </tbody>
               </table>


	        </div> <!-- /.table-responsive -->
       </form>
	</div>

	<div class="tab-pane fade <?php //if($search_tab == "basic"){ echo 'active in';}?>" id="meetings_custom">
 <form class="form-horizontal" name="frmlist6" id="frmlist6" action="<?php echo site_url('people')?>" method="post">
	    <div class="table-responsive">
					  <table class="table table-striped table-bordered thumbnail-table">
						  <thead>
							  <tr>

									<th>Field Name</th>
									<th>Label</th>
									<th>Type</th>
									<th>Action</th>
							  </tr>
						  </thead>
						  <tbody>
							  <?php foreach($meetings_custom as $usr){?>
									<tr>
										<td><?php echo $usr->cf_name; ?></td>
										<td><?php echo $usr->cf_label;?></td>
										<td><?php echo $usr->cf_type;?></td>
										<td class="valign-middle" align="center">
											<a href="<?php echo site_url('settings/custom_edit/' . $usr->cf_id)?>"><i class="btn btn-xs btn-secondary fa fa-pencil"></i></a>
											<a href="javascript:delete_one( '<?php echo $usr->cf_id;?>' )"><i class="btn btn-xs btn-secondary fa fa-times"></i></a>
											&nbsp;


										</td>
									</tr>
							  <?php } ?>
						  </tbody>
               </table>


	        </div> <!-- /.table-responsive -->
       </form>
	</div>

</div>


   <!-- /.form-group -->
				</div> <!-- /.table-responsive -->
			</div> <!-- /.col -->
		</div> <!-- /.row -->
    </div>


      </div>



      <div class="tab-pane fade <?php if( 'drop-down-editor' == $section ):?>in active<?php endif;?>" id="drop-down-editor">


            <?php

           	if($show_editor){
        	?>
			<div class="tab-pane fade <?php if( 'custom-fields' == $section ):?>in active<?php endif;?>" id="custom-fields">

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

echo form_open('settings/custom_fields', $attributes); ?>



      </div>
			<?php }else {
                     echo"ddf";
					 echo $show_editor;
                       }			?>
        <br><br>


      </div> <!-- /.tab-pane -->




    </div> <!-- /.tab-content -->





   <!-- /.col -->

</div><!--.col -->

</div> <!-- /.row -->

</div>

<script type="text/javascript">
cancel=function(elm){
		window.location.href = '<?php echo site_url('settings')?>';
		return false;
	}


	// delete single record
delete_one=function(id){
		Messi.ask('Do you really want to delete the field?', function(val) {
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('settings/custom_delete')?>/" +id;
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

});


  jQuery(document).ready(function(){
              var validator = jQuery("#frmprofile").validate({
			    rules: {
                field_name: {required: true},
                label_name: {required: true},
				data :{required: true},


              },
              messages: {
                field_name: {required: "Required"},
                label_name: {required :"Required"},
				data: {required :"Required"},

              },
              errorPlacement: function(error, element) {
                error.insertAfter(element.parent().parent().find('label:first'));
              },
              errorElement: 'em',
              errorClass: 'login_error'
            });
          });
  
</script>
