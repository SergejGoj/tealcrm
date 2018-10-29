
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">
    
    <base href="<?php echo site_url();?>">

    <title>TealCRM</title>
    
    <!-- Bootstrap core CSS -->
    <link href="<?php echo site_url('/asset/');?>/css/bootstrap.min.css" rel="stylesheet" >

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo site_url('/asset/');?>/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo site_url('/asset/');?>/css/custom.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo site_url('/assets/');?>/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo site_url('/assets/');?>/core/js/plugins/messi/messi.min.css" >
	<link rel="stylesheet" href="assets/css/uploadfile.css">

	<!-- Calender CSS -->
	<link rel="stylesheet" href="assets/css/calender/jquery-ui.css">
	<link rel="stylesheet" href="assets/core/js/plugins/jquery.datetimepicker/jquery.datetimepicker.css" >
  
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo site_url('/asset/');?>/js/jquery-3.1.1.min.js"></script>
    <script src="<?php echo site_url('/asset/');?>/js/jquery-ui.min.js"></script>
    <script src="<?php echo site_url('/asset/');?>/js/bootstrap.min.js"></script>
    <script src="<?php echo site_url('/asset/');?>/js/ct-navbar.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo site_url('/asset/');?>/js/ie10-viewport-bug-workaround.js"></script>
    <link href="<?php echo site_url('/asset/');?>/css/pe-icon-7-stroke.css" rel="stylesheet" />
    <link href="<?php echo site_url('/asset/');?>/css/ct-navbar.css" rel="stylesheet" />
    
	<!--validate-->
	<script src="assets/core/js/plugins/jquery.validate/jquery.validate.min.js"></script>
	<script src="assets/core/js/plugins/messi/messi.min.js"></script>
	<script src="assets/core/js/plugins/jquery.datetimepicker/jquery.datetimepicker.js"></script>
  </head>

  <body>

    <!-- Static navbar -->
	<nav class="navbar navbar-ct-blue navbar-fixed-top navbar-transparent" role="navigation">
      <div class="container" style="height:100px">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand navbar-brand-logo" href="<?php echo site_url('');?>">
                <div class="brand" style="padding-top:0;">
                    <img src="<?php echo site_url('/assets/');?>/img/tealcrm.png" height="45">
                </div>
          </a>

        </div>
    </nav>
    


    <div class="container" style="margin-top:175px;">
		<!-- Teal Application Content -->
        <?php display_notify(); ?>

        <?php echo $content_for_layout; ?>
        <!-- end Teal Application Content -->

    </div> <!-- /container -->

<center><a href="http://www.tealcrm.com" style="font-size:9px">TealCRM Version 1.1 - Open Source</a></center>

  </body>
</html>










