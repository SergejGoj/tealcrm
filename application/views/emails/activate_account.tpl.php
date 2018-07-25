<html>
<body>
	<h1>Activate company for <?php echo $identity;?></h1>
	<p>Please click this link to <?php echo anchor('auth/activate_account/'. $user_id .'/'. $activation_token, 'Activate Your Company');?>.</p>
</body>
</html>