<div class="row">

  <div >

    <div class="portlet">

      <h2 class="portlet-title">
        <u>Notes :: Add new</u>
      </h2>

      <div class="portlet-body">

        <form name="frmadd" id="frmadd" action="<?php echo site_url('notes/add')?>" method="post" class="form parsley-form">

  			<div class="form-group">
				<label for="subject">Subject</label>
				<input type="text" name="subject" id="subject" class="form-control"  placeholder="Enter subject">
			</div>

			 <div class="row">
			   <div class="form-group col-sm-6">
				<label for="phone_fax">Assign Users</label>
				<select id="assigned_user_id" name="assigned_user_id" class="form-control" size="1">
					<option value="0">Please select</option>
					<?php foreach($users as $user) :?>
					<option value="<?php echo $user['uacc_uid'];?>"><?php echo $user['name'];?></option>
					<?php endforeach; ?>
				</select>
			  </div>
			  <!--<div class="form-group col-sm-6">
				<label for="phone_fax">Phone Fax</label>
				<input type="text" name="phone_fax"  id="phone_fax"  class="form-control" placeholder="Enter fax phone">
			  </div>-->
   		    </div>

			<div class="row">
				<div class="form-group col-sm-6">
					<label for="company_id">Company</label>
					<input type="text" name="company_id" id="company_id" class="form-control"  placeholder="Enter company no">
				</div>
				<div class="form-group col-sm-6">
					<label for="people_id">Person</label>
					<input type="text" name="people_id"  id="people_id"  class="form-control" placeholder="Enter person name">
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-6">
					<label for="description">Comments/Description</label>
					<textarea name="description" id="description" class="form-control" rows="5"  placeholder="Enter comments/descriptions"></textarea>
				</div>
				<!--<div class="form-group col-sm-6">
					<label for="email2">Address 2</label>
					<textarea name="address2" id="address2" class="form-control"  placeholder="Enter address 2"></textarea>
				</div>-->
			</div>

            <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
          </div>
          <input type="hidden" name="act" value="save">

        </form>

      </div> <!-- /.portlet-body -->

    </div> <!-- /.portlet -->

  </div> <!-- /.col -->

</div> <!-- /.row -->
<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('notes')?>';
		return false;
	}

	// document ready
	jQuery(document).ready(function(){
		var validator = jQuery("#frmadd").validate({
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
</script>