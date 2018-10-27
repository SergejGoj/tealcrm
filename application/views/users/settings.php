<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">


      <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
        <li <?php if( 'update-profile' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('users/settings')?>/#update-profile" data-toggle="tab">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;Profile Settings
          </a>
        </li>

        <li <?php if( 'change-password' == $section ):?>class="active"<?php endif;?>>
          <a href="<?php echo site_url('users/settings')?>/#change-password" data-toggle="tab">
          <i class="fa fa-lock"></i>
          &nbsp;&nbsp;Change Password
          </a>
        </li>
      </ul>

    </div> <!-- /.col -->



  <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <div class="tab-pane fade <?php if( 'update-profile' == $section ):?>in active<?php endif;?>" id="update-profile">

        <h3 class="content-title">Edit Profile</h3>

        <?php display_notify('settings_update_profile') ?>

        <p></p>

        <br><br>

        <form name="frmprofile" id="frmprofile" action="<?php echo site_url('users/settings/update-profile')?>" method="post" class="form-horizontal" enctype="multipart/form-data">

          <div class="form-group">

            <label class="col-md-3">Profile Photo</label>

            <div class="col-md-7">
              <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;">
				<?php

				// loads the profile image for the logged in user

				$user = $_SESSION['user'];
				$profile_image = new SimpleImage();
								
				if(file_exists('./../application/attachments/'.$user->picture) && !empty($user->picture))
					$profile_image->load('./../application/attachments/'.$user->picture,IMAGETYPE_JPEG);
				else
					$profile_image->load('./../application/attachments/default.png',IMAGETYPE_JPEG);
				$profile_image->resizeToHeight(180);
				$profile_image->resizeToWidth(180);
				echo '<img src="'.$profile_image->show().'" alt="" />';

				?>                </div>
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

            <label class="col-md-3">Username</label>

            <div class="col-md-7">
              <input type="text" name="username" value="<?php echo $user->username?>" class="form-control" disabled />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">First Name</label>

            <div class="col-md-7">
              <input type="text" name="first_name" value="<?php echo $user->first_name?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Last Name</label>

            <div class="col-md-7">
              <input type="text" name="last_name" value="<?php echo $user->last_name?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Email Address</label>

            <div class="col-md-7">
              <input type="text" name="email" value="<?php echo $user->email?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-group">
            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default">Cancel</button>
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->
           <input type="hidden" name="act" value="update-profile" />
        </form>


      </div> <!-- /.tab-pane -->



      <div class="tab-pane fade <?php if( 'change-password' == $section ):?>in active<?php endif;?>" id="change-password">

        <h3 class="content-title">Change Password</h3>

        <?php display_notify('settings_change_password') ?>

        <p></p>

        <br><br>

        <form name="frmpass" id="frmpass" action="<?php echo site_url('users/settings/change-password')?>" method="post" class="form-horizontal">

          <div class="form-group">

            <label class="col-md-3">Old Password</label>

            <div class="col-md-7">
              <input type="password" name="old_password" id="old_password" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <hr>


          <div class="form-group">

            <label class="col-md-3">New Password</label>

            <div class="col-md-7">
              <input type="password" name="new_password" id="new_password" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-group">

            <label class="col-md-3">New Password Confirm</label>

            <div class="col-md-7">
              <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-group">

            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default">Cancel</button>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
	       <input type="hidden" name="act" value="change-password" />
        </form>

        <script type="text/javascript">
          // document ready
          jQuery(document).ready(function(){
            var validator = jQuery("#frmpass").validate({
              rules: {
                old_password: {required: true},
                new_password: {required: true, rangelength: [8,16]},
                confirm_new_password: {required : true, equalTo: '#new_password'},
              },
              messages: {
                 old_password: {required: "Enter old assword"},
                new_password: {required: "Enter new password", rangelength: $.validator.format("Please enter a password between {0} and {1} characters long.")},
                confirm_new_password: {required :"Confirm new password", equalTo: 'Passwords do not match'},
              },
              errorPlacement: function(error, element) {
                error.insertAfter(element.parent().parent().find('label:first'));
              },
              errorElement: 'em',
              errorClass: 'login_error'
            });
          });
        </script>
      </div> <!-- /.tab-pane -->




    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->
<script>

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

