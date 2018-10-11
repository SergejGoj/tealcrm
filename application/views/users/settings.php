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

				$user = $this->flexi_auth->get_user_by_id_query($this->flexi_auth->get_user_id())->row_array();
				$profile_image = new SimpleImage();
								
				if(file_exists('./../application/attachments/'.$user['upro_filename_original']) && !empty($user['upro_filename_original']))
					$profile_image->load('./../application/attachments/'.$user['upro_filename_original'],IMAGETYPE_JPEG);
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
              <input type="text" name="username" value="<?php echo $user['uacc_username']?>" class="form-control" disabled />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">First Name</label>

            <div class="col-md-7">
              <input type="text" name="first_name" value="<?php echo $user['upro_first_name']?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Last Name</label>

            <div class="col-md-7">
              <input type="text" name="last_name" value="<?php echo $user['upro_last_name']?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->



          <div class="form-group">

            <label class="col-md-3">Email Address</label>

            <div class="col-md-7">
              <input type="text" name="email" value="<?php echo $user['uacc_email']?>" class="form-control" />
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

		<div class="form-group">
		
		<?php 
		if ($user['email_sending_option'] == 0 ){
		$email_option = 116;}
		else {
		$email_option = 115;
		}

		
		?>
		<div class="col-md-7">
		
		 <?php 	 echo form_hidden('user_email_option', $user_email_option,$email_option,"class='form-control' id='user_email_option'"); ?>

		</div>
		
		</div>

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




      <div class="tab-pane fade <?php if( 'update_imap' == $section ):?>in active<?php endif;?>" id="change-gmail">

        <h3 class="content-title">Connect Email</h3>

        <?php display_notify('settings_update_imap') ?>

        <p><?php if($_SESSION['user']['imap_active'] == 1){ echo "<span class='label label-success' style='font-size:12px'>Active SMTP Connection</span>";}
        else{
	        echo "<span class='label label-danger demo-element'>SMTP Connection Not Active</span>";
        }
        
        ?></p>

        <br><br>

        <form name="frmEmail" id="frmEmail" action="<?php echo site_url('users/settings/update_imap')?>" method="post" class="form-horizontal">

         <div class="form-group">
            <label class="col-md-3">User Name</label>
            <div class="col-md-7">
              <input type="text" name="user_name" id="user_name" class="form-control" value="<?php if(isset($_SESSION['user']['username'])){echo $_SESSION['user']['username'];}?>" />
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->

          <hr>

          <div class="form-group">
            <label class="col-md-3">Password</label>
            <div class="col-md-7">
              <input type="password" name="email_password" id="email_password" class="form-control" value = "<?php echo $_SESSION['user']['password'];?>" />
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->


          <hr>

          <div class="form-group">
            <label class="col-md-3">IMAP Address</label>
            <div class="col-md-7">
              <input type="text" name="email_imap" id="email_imap" class="form-control" placeholder="imap.gmail.com" value="<?php 
	          
	          $pieces = explode("://", $_SESSION['user']['imap_address']);
	          if(count($pieces) > 1){
	          	echo $pieces[1];	
	          	$myvalue = $pieces[0];	          
	          }else{
		          echo $pieces[0];	
		          $myvalue = "none";          
	          }

	          ?>">
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->

		  <hr>

		  <div class="form-group">
            <label class="col-md-3">Encryption Options</label>
            <div class="col-md-7">
	            
	            <select name="encryption_type">
		            <option value="none" <?php if ($myvalue == "none"){ echo "selected";}?>>None</option>
		            <option value="SSL" <?php if ($myvalue == "ssl"){ echo "selected";}?>>SSL</option>
		            <option value="TLS" <?php if ($myvalue == "tls"){ echo "selected";}?>>TLS</option>
	            </select>

            </div> <!-- /.col -->
          </div> <!-- /.form-group -->

		  <hr>

          <div class="form-group">
            <label class="col-md-3">Mail Server Port</label>
            <div class="col-md-7">
              <input type="text" name="server_port" id="server_port" placeholder="465" value="<?php if(isset($_SESSION['user']['mail_server_port'])){echo $_SESSION['user']['mail_server_port'];}?>" class="form-control" />
            </div> <!-- /.col -->
          </div> <!-- /.form-group -->


          <div class="form-group">

            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Connect</button>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->
	       <input type="hidden" name="act" value="update_imap" />
        </form>

        <script type="text/javascript">
          // document ready
          jQuery(document).ready(function(){
            var validator = jQuery("#frmEmail").validate({
              rules: {
                user_name: {required: true},
                email_password: {required: true},
                email_imap: {required: true},
				server_port: {required: true},
              },
              messages: {
                user_name: {required: "Enter Email Address"},
                email_password: {required: "Enter Password"},
                email_imap: {required: "Enter IMAP Address"},
				server_port: {required: "Enter Mail Server Port"},
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

	<!--------------------------------------google app start------------------------------------------------------->
	
	 <div class="tab-pane fade inactive>" id="googleapp">

        <h3 class="content-title">Connect Google</h3>
		
		<form name="googleapp" id="googleapp" action="<?php echo site_url('users/googleapp')?>" method="post" class="form-horizontal">
		
		   <div class="form-group">

            <div class="col-md-7 col-md-push-3">
		
				<input type="button" value="Connect Google Contacts" class="btn btn-primary" onclick="contact_url();" />
		
			</div> 

          </div> 
		<div class="form-group">

            <div class="col-md-7 col-md-push-3">
			  <input type = "hidden" id = "click" name = "click" value = "submit">
				
              <button type="submit" class="btn btn-primary">Connect Google Calendar</button>
            </div> 

          </div> 
		  
		  <div class="form-group">

            <div class="col-md-7 col-md-push-3">
		
				<input type="button" value="Connect Google Task" class="btn btn-primary" onclick="task_url();" style="width: 191px;" />
		
			</div> 

          </div> 
		  
		  <div class="form-group">

            <div class="col-md-7 col-md-push-3">
		
				<input type="button" value="Connect Gmail" class="btn btn-primary" style="width: 189px;" onclick="gmail_url();"/>
		
			</div> 

          </div> 
	
		</form>
		
	</div>
	
	<!--------------------------------------google app end--------------------------------------------------------->
      <div class="tab-pane fade <?php if( 'sync-google' == $section ):?>in active<?php endif;?>" id="sync-google">

        <h3 class="content-title">Sync Google</h3>
				<div class="form-group">
					<div style="text-decoration:underline; cursor:pointer;" onclick="login();"><img src="img/google.png" style="width:45px; margin-right:10px;">Synchronize Gmail, Google Contacts, Calendar and Tasks</div>
					<div style="display:none;">
						<div id='uName'>Welcome  </div>
						<img src='' id='imgHolder'/>
					</div>
				</div>
      </div> <!-- /.tab-pane -->

      <div class="tab-pane fade <?php if( 'sync-linkedin' == $section ):?>in active<?php endif;?>" id="sync-linkedin">

        <h3 class="content-title">Sync LinkedIn</h3>
				<div class="form-group">
					<div style="text-decoration:underline; cursor:pointer;"><img src="img/linkedin.png" style="width:40px; margin-right:10px;">Sync LinkedIn</div>
				</div>

      </div> <!-- /.tab-pane -->


      <div class="tab-pane fade <?php if( 'notifications' == $section ):?>in active<?php endif;?>" id="notifications">

        <h3 class="content-title">Notification Settings</h3>

        <p></p>

        <br><br>

        <form name="frmpnotifications" id="frmpnotifications" action="<?php echo site_url('users/settings/notifications')?>" method="post" class="form form-horizontal">

          <div class="form-group">

            <label class="col-md-3">Toggle Notifications</label>

            <div class="col-md-7">

              <div class="checkbox">
                <label>
                <input value="" type="checkbox" checked>
                Send me security emails
                </label>
              </div> <!-- /.checkbox -->

              <div class="checkbox">
                <label>
                <input value="" type="checkbox" checked>
                Send system emails
                </label>
              </div> <!-- /.checkbox -->

              <div class="checkbox">
                <label>
                <input value="" type="checkbox">
                Lorem ipsum dolor sit amet
                </label>
              </div> <!-- /.checkbox -->

              <div class="checkbox">
                <label>
                <input value="" type="checkbox">
                Laborum, quam iure quibusdam
                </label>
              </div> <!-- /.checkbox -->

            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-group">

            <label class="col-md-3">Email for Notifications</label>

            <div class="col-md-7">
              <select name="email_notifications" class="form-control">
                <option value="1">john@mvpready.com</option>
                <option value="2">mary@mvpready.com</option>
                <option value="3">chris@mvpready.com</option>
              </select>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->


          <div class="form-group">

            <div class="col-md-7 col-md-push-3">
              <button type="submit" class="btn btn-primary">Save Changes</button>
              &nbsp;
              <button type="reset" class="btn btn-default">Cancel</button>
            </div> <!-- /.col -->

          </div> <!-- /.form-group -->

        </form>

      </div> <!-- /.tab-pane -->


    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->
<script>

//get the file size, image height and width before upload
//This will work with modern browsers as from HTML 5 and the File API
// var url = window.URL || window.webkitURL; // alternate use

function contact_url()
{
	document.location.href="https://accounts.google.com/o/oauth2/auth?client_id=975092978090-v1ir4dq1d5udrh2algpsnetfturo3qal.apps.googleusercontent.com&redirect_uri=http://eligeo.inboundsoftware.com/users/google_people&scope=https://www.google.com/m8/feeds/&response_type=code";
}

function task_url()
{
	document.location.href="https://accounts.google.com/o/oauth2/auth?client_id=65310217621-74sedepqvg9up6gebh3b4eis787tqdam.apps.googleusercontent.com&redirect_uri=http://eligeo.inboundsoftware.com/users/google_task/googleapp&scope=https://www.googleapis.com/auth/tasks&response_type=code";
}


function gmail_url()
{
document.location.href="https://accounts.google.com/o/oauth2/auth?client_id=299478619564-6r1dmggrc6qdvu94fkvdgmm4mv1ci1db.apps.googleusercontent.com&redirect_uri=http://eligeo.inboundsoftware.com/users/google_gmail/googleapp&scope=https://mail.google.com&response_type=code";
}


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

});
   </script>
