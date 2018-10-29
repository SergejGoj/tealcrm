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

      </ul>

    </div> <!-- /.col -->

  <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <div class="tab-pane fade in active" id="update-profile">

        <h3 class="content-title">Create New User</h3>

		<div id="infoMessage"><?php echo $message;?></div>
		
		<?php echo form_open("auth/create_user", array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'));?>

          <div class="form-group">

            <label class="col-md-3">Profile Photo</label>

            <div class="col-md-7">
              <div class="fileupload fileupload-new" data-provides="fileupload">
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

            <label class="col-md-3">First Name</label>

            <div class="col-md-7">
              <?php echo form_input($first_name);?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group --> 
 
           <div class="form-group">

            <label class="col-md-3">Last Name</label>

            <div class="col-md-7">
              <?php echo form_input($last_name);?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group --> 
 
           <div class="form-group">

            <label class="col-md-3">Username</label>

            <div class="col-md-7">
              <?php echo form_input($identity);?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->  


           <div class="form-group">

            <label class="col-md-3">Email</label>

            <div class="col-md-7">
              <?php echo form_input($email);?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->  

           <div class="form-group">

            <label class="col-md-3">Phone</label>

            <div class="col-md-7">
              <?php echo form_input($phone);?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->  
          
           <div class="form-group">

            <label class="col-md-3">Password</label>

            <div class="col-md-7">
              <?php echo form_input($password);?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->  		

           <div class="form-group">

            <label class="col-md-3">Confirm Password</label>

            <div class="col-md-7">
              <?php echo form_input($password_confirm);?>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->  

		
		
		      <p><?php echo form_submit('submit', lang('create_user_submit_btn'));?></p>
		
		<?php echo form_close();?>

     </div> <!-- /.tab-pane -->

    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->	