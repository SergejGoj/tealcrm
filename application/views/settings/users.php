<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">

      <div class="nav-layout-sidebar-skip">
        <strong>Tab Navigation</strong> / <a href="<?php echo site_url('settings/users')?>#settings-content">Skip to Content</a>
      </div>

      <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
        <li>
          <a href="<?php echo site_url('settings')?>">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;Back to CRM Settings
          </a>
        </li>

        <li class="active">
          <a href="<?php echo site_url('settings/users')?>/#update-profile" data-toggle="tab">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;User Settings
          </a>
        </li>

        <li <?php if( 'change-password' == $section ):?>class="active"<?php endif;?>>
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

        <h3 class="content-title">Editing User: <?php echo $user_info->upro_first_name;?> <?php echo $user_info->upro_last_name;?></h3>

        <?php display_notify('settings_update_profile') ?>

        <p></p>

        <br><br>

        <form name="frmprofile" id="frmprofile" action="<?php echo site_url('settings/updateUser')?>" method="post" class="form-horizontal" enctype="multipart/form-data">

          <div class="form-group">

            <label class="col-md-3">Profile Photo</label>

            <div class="col-md-7">
              <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;">
					<?php echo '<img id="profile_img" name="profile_img" src="../mask/maskImg?f=' . $hashImg . '" alt="Profile Photo" />'; ?>
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
				<select id="user_group" name="user_group" class="form-control" size="1">
				<?php
				foreach($flexi_groups as $k=>$v){
					if($k == $user_info->uacc_group_fk)
						echo "<option value='$k' selected>$v</option>";
					else
						echo "<option value='$k'>$v</option>";
				}
				?>
				</select>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Username</label>

            <div class="col-md-7">
              <input type="text" name="username" value="<?php echo $user_info->uacc_username; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
			<div class = "form-group" >
			
			<label class = "col-md-3" > User Status</label>
			<?php
					if ($user_info->uacc_active == 0){$status = 178;} else {$status = 179;}?>
			<div class = "col-md-7" >
			
			  <?php 	 echo form_dropdown('user_status', $user_status,$status,"class='form-control' id='user_status'"); ?>
			
			<input type = "hidden" name = "status" value = "<?php echo $status; ?>" class = "form-control" />
			</div>
			
			</div>



          <div class="form-group">

            <label class="col-md-3">First Name</label>

            <div class="col-md-7">
              <input type="text" name="first_name" value="<?php echo $user_info->upro_first_name; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Last Name</label>

            <div class="col-md-7">
              <input type="text" name="last_name" value="<?php echo $user_info->upro_last_name; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->




          <div class="form-group">

            <label class="col-md-3">Phone Number</label>

            <div class="col-md-7">
              <input type="text" name="phone_number" value="<?php echo $user_info->upro_phone; ?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Email Address</label>

            <div class="col-md-7">
              <input type="text" name="email" value="<?php echo $user_info->uacc_email; ?>" class="form-control" />
              <input type="hidden" name="orig_email" value="<?php echo $user_info->uacc_email; ?>" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">
            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default" onclick="document.location.href = '<?php echo site_url('settings')?>'">Cancel</button>
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->
           <input type="hidden" name="uid" value="<?php echo $user_info->uacc_uid; ?>" />
           <input type="hidden" name="act" value="update-user" />
        </form>


      </div> <!-- /.tab-pane -->



      <div class="tab-pane fade <?php if( 'change-password' == $section ):?>in active<?php endif;?>" id="change-password">

        <h3 class="content-title">Change Password</h3>

        <?php display_notify('settings_change_password') ?>

        <p></p>

        <br><br>

        <form name="frmpass" id="frmpass" action="<?php echo site_url('settings/updateUser')?>" method="post" class="form-horizontal">

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
           <input type="hidden" name="uacc_email" value="<?php echo $user_info->uacc_email; ?>" />
           <input type="hidden" name="id" value="<?php echo $user_info->uacc_id; ?>" />
           <input type="hidden" name="uid" value="<?php echo $user_info->uacc_uid; ?>" />
	       <input type="hidden" name="act" value="change-password" />
        </form>

        <script type="text/javascript">
          // document ready
          jQuery(document).ready(function(){
            var validator = jQuery("#frmpass").validate({
              rules: {
                new_password: {required: true, rangelength: [8,16]},
                confirm_new_password: {required : true, equalTo: '#new_password'},
              },
              messages: {
                new_password: {required: "Enter new password", rangelength: $.validator.format("Please enter a password between {0} and {1} characters long.")},
                confirm_new_password: {required :"Confirm new password", equalTo: 'Confirm password does not match password'},
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


    <a href="#" style="display:none" id="logoutText" target='myIFrame' onclick="myIFrame.location='https://www.google.com/companies/Logout'; startLogoutPolling();return false;"> Click here to logout </a>
    <iframe name='myIFrame' id="myIFrame" style='display:none'></iframe>

   <script>
       var OAUTHURL    =   'https://companies.google.com/o/oauth2/auth?';
       var VALIDURL    =   'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
       var SCOPE       =   'https://mail.google.com/ https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/calendar.readonly https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/userinfo.email https://www.google.com/m8/feeds/';
       var CLIENTID    =   '11296949303-bv1794vruv4q6v2oan44rs4psl2vga4p.apps.googleusercontent.com';
       var REDIRECT    =   'http://tealcrm.localhost.com/oauth2callback'
       var TYPE        =   'token';
       var _url        =   OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE;
       function login() {
           var win         =   window.open(_url, "windowname1", 'width=600, height=400');
           var pollTimer   =   window.setInterval(function() {
               try {
				console.log("login try starts ..................");
                   console.log(win.document.URL);
                   if (win.document.URL.indexOf(REDIRECT) != -1) {
                       window.clearInterval(pollTimer);
                       var url =   win.document.URL;
                       acToken =   gup(url, 'access_token');
                       tokenType = gup(url, 'token_type');
                       expiresIn = gup(url, 'expires_in');
                       win.close();
					console.log("acToken=" + acToken + ", tokenType=" + tokenType + ", expiresIn=" + expiresIn);
                       validateToken(acToken);
                   }
               } catch(e) {
               }
           }, 100);
       }
       function validateToken(token) {
		console.log("validateToken Starts ====");
		console.log(token);
           $.ajax({
               url: VALIDURL + token,
               data: null,
               success: function(responseText){
                   getUserInfo(token);
				//importGoogle(token);
               },
               dataType: "jsonp"
           });
       }
       function getUserInfo(token) {
		console.log("getUserInfo Starts ++++++");
           $.ajax({
               url: 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' + acToken,
               data: null,
               success: function(resp) {
                   user    =   resp;
                   console.log(user);
                   $('#uName').append(user.name);
                   $('#imgHolder').attr('src', user.picture);
				window.location.href = '<?php echo site_url('imports/import?t=')?>'+token+'&e='+user.email;
               },
               dataType: "jsonp"
           });
       }

       //credits: http://www.netlobo.com/url_query_string_javascript.html
       function gup(url, name) {
		console.log("gup starts ~~~~~~");
		console.log(url);
		console.log("====");
		console.log(name);
           name = name.replace(/[[]/,"\[").replace(/[]]/,"\]");
           var regexS = "[\?&#]"+name+"=([^&#]*)";
           var regex = new RegExp( regexS );
           var results = regex.exec( url );
           if( results == null )
               return "";
           else
               return results[1];
       }

   //inside script tag
   function startLogoutPolling() {
       $('#loginText').show();
       $('#logoutText').hide();
       loggedIn = false;
       $('#uName').text('Welcome ');
       $('#imgHolder').attr('src', 'none.jpg');
   }

$(document).ready(function(){
	$("#mchimp_btn_save").click(function(){

		//saving new api key
		if( $(this).text() == "Save"){

			var apikey = $.trim($("#mchimp_apikey_input").val());
			if(apikey.length < 1) return false;
			$(this).text("Updating ").append(' <i class="fa fa-gear fa-spin"></i>');

			//step one: update the api key
			$.ajax({
				url: '/ajax/updateMailChimpAPI',  //server script to process data
				type: 'POST',
				async: true,
				data: {key:apikey},
				success: function(result) {
					if(result === "0"){
						$("#mchimp_apikey_input").prop("disabled", true);
						$("#mchimp_btn_save").find('i').remove();
						$("#mchimp_btn_save").text("Updated ").append(' <i class="fa fa-check-circle-o"></i>');
						setTimeout(function(){
							$("#mchimp_btn_save").find('i').remove();
							$("#mchimp_btn_save").text("Syncing ").append(' <i class="fa fa-refresh"></i>');

							//step 2: sync company
							$.ajax({
								url: '/ajax/syncMailChimpAPI',  //server script to process data
								type: 'POST',
								async: true,
								data: {key:apikey},
								success: function(result) {
									var resultObject = $.parseJSON(result);


									//if user has more than one List on MailChimp then proceed to the next step
									if(resultObject.total !== "0"){
										$("#mchimp_btn_save").find('i').remove();
										$("#mchimp_btn_save").text("Synced ").append(' <i class="fa fa-check-circle-o"></i>');
										window.location.href = "<?php echo site_url('users/syncMailChimp')?>/"+apikey+"/1";

									//otherwise fail
									}else{
										$("#mchimp_btn_save").find('i').remove();
										$("#mchimp_btn_save").text("Failed Sync ").append(' <i class="fa fa-times"></i>');

										//allow user to try again
										setTimeout(function(){
											$("#mchimp_btn_save").find('i').remove();
											$("#mchimp_btn_save").text("Try Again!");
										}, 2000);
									}
								}
							});

						}, 1500);
					}
				}
			});

		//unlock field to update existing api key
		//or sync failed and the user wants to try again
		}else if( $(this).text() == "Update & Sync" ||  $(this).text() == "Try Again!" ){
			$(this).text("Save");
			$("#mchimp_apikey_input").prop("disabled", false);
		}
	});

});
   </script>
