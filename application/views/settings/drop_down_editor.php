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
          &nbsp;&nbsp;Drop Down Editor
          </a>
        </li>

        <li id="sales">
           <a href="<?php echo site_url('settings')?>/sales_stage_order_editor">
           <i class="fa fa-building"></i>
          &nbsp;&nbsp;Sales Stage Order
          </a>
        </li>

      </ul>

    </div> <!-- /.col -->


  <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <div class="tab-pane fade in active" id="drop-down-editor">

        <h3 class="content-title">Drop Down Editor</h3>


        <div class="row">

        	<div class="col-sm-4">
        	<h4>Companies</h4>
        	<?php
        	// companies
        	$drop_down_names = Array();
        	foreach($drop_downs['Companies'] as $drop_down) {
        		$drop_down_names[$drop_down->related_field_name] = $drop_down->related_field_name;
        	}
        	foreach($drop_down_names as $key => $value) {
				?> <a href="<?php echo site_url('settings/drop_down_editor/'.$key)?>"><?php echo $key;?></a><br/> <?php
			}
        	?>
        	<br/>
        	<h4>People</h4>
        	<?php
        	// companies
        	$drop_down_names = Array();
        	if(array_key_exists('People',$drop_downs)) {
	        	foreach($drop_downs['People'] as $drop_down) {
	        		$drop_down_names[$drop_down->related_field_name] = $drop_down->related_field_name;
	        	}
	        	foreach($drop_down_names as $key => $value) {
					?> <a href="<?php echo site_url('settings/drop_down_editor/'.$key)?>"><?php echo $key;?></a><br/> <?php
				}
			}
        	?>
        	<br/><h4>Deals</h4>
        	<?php
        	// companies
        	$drop_down_names = Array();
        	foreach($drop_downs['Deals'] as $drop_down) {
        		$drop_down_names[$drop_down->related_field_name] = $drop_down->related_field_name;
        	}
        	foreach($drop_down_names as $key => $value) {
				?> <a href="<?php echo site_url('settings/drop_down_editor/'.$key)?>"><?php echo $key;?></a><br/> <?php
			}
        	?>
        	<br/><h4>Tasks</h4>
        	<?php
        	// companies
        	$drop_down_names = Array();
        	foreach($drop_downs['Tasks'] as $drop_down) {
        		$drop_down_names[$drop_down->related_field_name] = $drop_down->related_field_name;
        	}
        	foreach($drop_down_names as $key => $value) {
				?> <a href="<?php echo site_url('settings/drop_down_editor/'.$key)?>"><?php echo $key;?></a><br/> <?php
			}
        	?>
        	<br/><h4>Meetings</h4>
        	<?php
        	// companies
        	$drop_down_names = Array();
        	foreach($drop_downs['Meetings'] as $drop_down) {
        		$drop_down_names[$drop_down->related_field_name] = $drop_down->related_field_name;
        	}
        	foreach($drop_down_names as $key => $value) {
				?> <a href="<?php echo site_url('settings/drop_down_editor/'.$key)?>"><?php echo $key;?></a><br/> <?php
			}
        	?>
        	<br/><h4>Products</h4>
        	<?php
        	// companies
        	$drop_down_names = Array();
        	foreach($drop_downs['Products'] as $drop_down) {
        		$drop_down_names[$drop_down->related_field_name] = $drop_down->related_field_name;
        	}
        	foreach($drop_down_names as $key => $value) {
				?> <a href="<?php echo site_url('settings/drop_down_editor/'.$key)?>"><?php echo $key;?></a><br/> <?php
			}
        	?>

        	</div>
        	<div class="col-sm-8">
           	<?php
           	if($show_editor){
        	?>

        	<h4 style="color:#1D7374">Editing: <?php echo $drop_down_item;?></h4>

        	<form action="<?php echo site_url('settings/drop_down_editor/'.$drop_down_item)?>" class="form parsley-form" onsubmit="return checkDuplicats()" id="frmedit" method="post" name="frmddedit">
				<div class="form-group">
					<label for="edit_field">Label to Edit</label>
					<select class="form-control" id="edit_field" name="edit_field">
					<option value='add_new_field'>-- ADD NEW --</option>
					<?php
						foreach($drop_downs as $obj=>$row){
							foreach($row as $key=>$value){
								if($value->related_field_name == $drop_down_item){
									echo "<option value='" . $value->drop_down_id . "'>" . $value->name . "</option>";
								}
							}
						}
					?>
					</select>
				</div>
				<div class="form-group">
					<label for="new_edit_field">New Label</label>
					<input type="text" class="form-control" id="new_edit_field" name="new_edit_field" />
				</div>
				<div class="form-actions">
					<input name="act" type="hidden" value="save">
					<button class="btn btn-primary" type="submit">Save</button> <button type="button" class="btn btn-danger pull-right" onclick="return delete_one(this)">Delete</button>
				</div>

			</form>
	        <?php
			}else{ ?>
			Select a drop down item from the left to edit it.
			<?php } ?>
        	</div>

        </div>

      </div> <!-- /.tab-pane -->


    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->


</div> <!-- /.row -->

<script type="text/javascript">
	// delete single record
	delete_one=function(e){
		Messi.ask('Do you really want to delete the record?', function(val) {
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('settings/drop_down_editor/delete')?>/" + $("#edit_field").val();
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
