<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">

      <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
        <li>
          <a href="<?php echo site_url('settings')?>">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;Back to CRM Settings
          </a>
        </li>

        <li class="active">
          <a href="<?php echo site_url('settings/users')?>">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;User Settings
          </a>
        </li>

        <li>
          <a href="#change-password" data-toggle="tab">
          <i class="fa fa-lock"></i>
          &nbsp;&nbsp;Change Password
          </a>
        </li>

      </ul>

    </div> <!-- /.col -->



  <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <div class="tab-pane fade in active" id="update-profile">

        <h3 class="content-title">Editing User: <?php echo $user_info->first_name;?> <?php echo $user_info->last_name;?></h3>

        <?php display_notify('settings_update_profile') ?>

        <p></p>

        <br><br>

		<?php echo form_open_multipart('auth/edit_user/' . $user_info->id,array ('class' => 'form-horizontal'), array ('id' => $user_info->id));?>

          <div class="form-group">

            <label class="col-md-3">Profile Photo</label>

            <div class="col-md-7">
              <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;">
					<?php echo '<img id="profile_img" name="profile_img" src="'. site_url('mask/maskImg?f=' . $user_info->picture) . '" alt="Profile Photo" />'; ?>
                </div>
				<div>
					<input type="hidden" name="profile_img_valid" id="profile_img_valid" value="0" />
					<input type="file" name="profile_img_file" id="profile_img_file" /><br/>
					<p class="alert bg-info">Maximum of 5mb, jpg, png.</p>
					<p class="alert alert-danger" style="display:none;">Maximum of 5mb, jpg, png.</p>
				</div>

              </div> <!-- /.fileupload -->

            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Role</label>

            <div class="col-md-7">
				<select id="groups" name="groups[]" class="form-control" size="1">
				<?php

				foreach($groups as $group){
					if($group['id'] == $user_info->group)
						echo "<option value='" . $group['id'] . "' selected>" . $group['description'] . "</option>";
					else
						echo "<option value='" . $group['id'] . "'>" . $group['description'] . "</option>";
				}
				?>
				</select>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

          <div class="form-group">

            <label class="col-md-3">Username</label>

            <div class="col-md-7">
              <input type="text" name="username" value="<?php echo $user_info->username; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
			<div class = "form-group" >
			
			<label class = "col-md-3" > User Status</label>
			<?php
					if ($user_info->active == 0){$status = 178;} else {$status = 179;}?>
			<div class = "col-md-7" >
			
			  <?php 	 echo form_dropdown('user_status', array ("1" => "Active", "0" => "Inactive"),$user_info->active,"class='form-control' id='user_status'"); ?>
			
			<input type = "hidden" name = "status" value = "<?php echo $status; ?>" class = "form-control" />
			</div>
			
			</div>


          <div class="form-group">

            <label class="col-md-3">First Name</label>

            <div class="col-md-7">
              <input type="text" name="first_name" value="<?php echo $user_info->first_name; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Last Name</label>

            <div class="col-md-7">
              <input type="text" name="last_name" value="<?php echo $user_info->last_name; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->




          <div class="form-group">

            <label class="col-md-3">Phone Number</label>

            <div class="col-md-7">
              <input type="text" name="phone" value="<?php echo $user_info->phone; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Email Address</label>

            <div class="col-md-7">
              <input type="text" name="email" value="<?php echo $user_info->email; ?>" class="form-control" />
              <input type="hidden" name="orig_email" value="<?php echo $user_info->email; ?>" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">
            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default" onclick="document.location.href = '<?php echo site_url('settings')?>'">Cancel</button>
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->
           <input type="hidden" name="uid" value="<?php echo $user_info->id; ?>" />
           <input type="hidden" name="act" value="update-user" />
        </form>


      </div> <!-- /.tab-pane -->



      <div class="tab-pane fade" id="change-password">

        <h3 class="content-title">Change Password</h3>

        <?php display_notify('settings_change_password') ?>

        <p></p>

        <br><br>

<?php echo form_open('auth/change_password_by_admin/' . $user_info->id,array ('class' => 'form-horizontal'), array ('id' => $user_info->id));?>

          <div class="form-group">

            <label class="col-md-3">New Password</label>

            <div class="col-md-7">
              <input type="password" name="new" id="new" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-group">

            <label class="col-md-3">New Password Confirm</label>

            <div class="col-md-7">
              <input type="password" name="new_confirm" id="new_confirm" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-group">

            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default">Cancel</button>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
           <input type="hidden" name="id" value="<?php echo $user_info->id; ?>" />
	       <input type="hidden" name="act" value="change-password" />

<?php echo form_close();?>

      </div> <!-- /.tab-pane -->


    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->
<script>

//get the file size, image height and width before upload
//This will work with modern browsers as from HTML 5 and the File API
// var url = window.URL || window.webkitURL; // alternate use

function readImage(file) {

    var reader = new FileReader();
    var image  = new Image();

    reader.readAsDataURL(file);
    reader.onload = function(_file) {
        image.src    = _file.target.result;              // url.createObjectURL(file);
        image.onload = function() {
            var w = this.width,
                h = this.height,
                t = file.type,                           // ext only: // file.type.split('/')[1],
                n = file.name,
                s = ~~(file.size/1024) +'KB';
            //$('#uploadPreview').append('<img src="'+ this.src +'"> '+w+'x'+h+' '+s+' '+t+' '+n+'<br>');

			//if width or height > 250px OR file size > 5mb
			if(file.size > 5242880){
				//if error message is not available in the DOM then show it
				$("#profile_img_file").parent().find(".alert-danger").show();

				//boolean to confirm file valid or not, 0=not valid, 1=valid
				$("#profile_img_valid").val("0");
			}else{
				//remove error message
				$("#profile_img_file").parent().find(".alert-danger").hide();
				$('#profile_img').attr("src", this.src);

				//boolean to confirm file valid or not, 0=not valid, 1=valid
				$("#profile_img_valid").val("1");
			}
        };

		//if the file selected is not image alert
        image.onerror= function() {
            alert('Invalid file type: '+ file.type);
        };
    };
}

//on user select image capture image information (width, height, name, size ..)
$("#profile_img_file").change(function (e) {
    if(this.disabled) return alert('File upload not supported!');
    var F = this.files;
    if(F && F[0]) for(var i=0; i<F.length; i++) readImage( F[i] );
});
</script>




