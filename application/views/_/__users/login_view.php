<!doctype html>
<head>
	<meta charset="utf-8">
	<title>Login Demo | flexi auth | A User Authentication Library for CodeIgniter</title>
</head>

<body id="login">

<div id="body_wrap">	
	
	<!-- Main Content -->
	<div class="content_wrap main_content_bg">
		<div class="content clearfix">
			<div class="col100">
				<h2>User Login</h2>

			<?php if (! empty($message)) { ?>
				<div id="message">
					<?php echo $message; ?>
				</div>
			<?php } ?>
				
				<form name="loginform" id="loginform" action="<?php //echo site_url('users/login_view')?>" method="POST" >
					<fieldset class="w50 parallel_target">
						<legend>Registered Users</legend>
						<ul>
							<li>
								<label for="identity">Email or Username:</label>
								<input type="text" id="identity" name="login_identity"  class="tooltip_parent"/>
								
							</li>
							<li>
								<label for="password">Password:</label>
								<input type="password" id="password" name="login_password" />
							</li>						
							<li>
								<label for="remember_me">Remember Me:</label>
								<input type="checkbox" id="remember_me" name="remember_me" value="1" />
							</li>
							<li>								
								<input type="submit" name="login_user" id="submit" value="Submit" class="link_button large"/>
							</li>							
						</ul>
					</fieldset>					
				</form>
			</div>
		</div>
	</div>
</body>
</html>