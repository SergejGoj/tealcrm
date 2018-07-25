<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
  <title>TealCRM</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <base href="<?php echo site_url('/assets');?>/" target="_parent" />

  <!-- Google Font: Open Sans -->
  <link rel="stylesheet" href="css/googleapis1.css">
  <link rel="stylesheet" href="css/googleapis2.css">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="css/font-awesome.min.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <!-- Calender CSS -->
  <link rel="stylesheet" href="css/calender/jquery-ui.css">
  <link rel="stylesheet" href="css/calender/calender-style.css">
  <link rel="stylesheet" href="core/js/plugins/jquery.datetimepicker/jquery.datetimepicker.css" >

  <!-- Dashboard CSS -->
	<link rel="stylesheet" href="css/morris.css">


  <!-- App CSS -->
  <link rel="stylesheet" href="css/mvpready-admin.css">
  <link rel="stylesheet" href="css/mvpready-flat.css">
  <link rel="stylesheet" href="css/custom.css" >
  <link rel="stylesheet" href="core/js/plugins/messi/messi.min.css" >

  <!-- Favicon -->
  <link rel="shortcut icon" href="favicon.ico">

  <!-- Core JS -->
  <script src="js/jquery.js"></script>
  <script src="js/libs/bootstrap.min.js"></script>
  <!-- Calender -->
  <script src="js/libs/jquery-ui.js"></script>

  <!--validate-->
  <script src="core/js/plugins/jquery.validate/jquery.validate.min.js"></script>
  <script src="core/js/plugins/messi/messi.min.js"></script>
  <script src="core/js/plugins/jquery.datetimepicker/jquery.datetimepicker.js"></script>

  <link rel="stylesheet" href="css/teal_shared.css" >

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>

<body>

<div id="wrapper">

  <header class="navbar navbar-inverse" role="banner">

    <div class="container">

      <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-cog"></i>
        </button>

        <a href="<?php echo site_url('users/dashboard')?>" class="navbar-brand navbar-brand-img">
          <img src="img/tealcrm.png" alt="TealCRM">
        </a>
      </div> <!-- /.navbar-header -->


      <nav class="collapse navbar-collapse" role="navigation">

        <ul class="nav navbar-nav noticebar navbar-left">


<?php /*
DISABLED NOTIFICATIONS FOR PHASE 1
          <li class="dropdown">
            <a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell"></i>
              <span class="navbar-visible-collapsed">&nbsp;Notifications&nbsp;</span>
              <span class="badge badge-primary">3</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">
              <li class="nav-header">
                <div class="pull-left">
                  Notifications
                </div>

                <div class="pull-right">
                  <a href="javascript:;">Mark as Read</a>
                </div>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <i class="fa fa-cloud-upload text-success"></i>
                  </span>
                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">Templates Synced</strong>
                    <span class="noticebar-item-text">20 Templates have been synced to the Mashon Demo instance.</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 12 minutes ago</span>
                  </span>
                </a>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <i class="fa fa-ban text-danger"></i>
                  </span>
                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">Sync Error</strong>
                    <span class="noticebar-item-text">5 Designs have been failed to be synced to the Mashon Demo instance.</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 20 minutes ago</span>
                  </span>
                </a>
              </li>

              <li class="noticebar-menu-view-all">
                <a href="./page-notifications.html">View All Notifications</a>
              </li>
            </ul>
          </li>
*/
?>

          <li class="dropdown">
            <a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope"></i>
              <span class="navbar-visible-collapsed">&nbsp;Messages&nbsp;</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">
              <li class="nav-header">
                <div class="pull-left">
                  Messages
                </div>

                <div class="pull-right">
                  <a href="javascript:;">New Message</a>
                </div>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <img src="./img/avatars/avatar-1-md.jpg" style="width: 50px" alt="">
                  </span>

                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">New Message</strong>
                    <span class="noticebar-item-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 20 minutes ago</span>
                  </span>
                </a>
              </li>

              <li>
                <a href="./page-notifications.html" class="noticebar-item">
                  <span class="noticebar-item-image">
                    <img src="./img/avatars/avatar-2-md.jpg" style="width: 50px" alt="">
                  </span>

                  <span class="noticebar-item-body">
                    <strong class="noticebar-item-title">New Message</strong>
                    <span class="noticebar-item-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit...</span>
                    <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> 5 hours ago</span>
                  </span>
                </a>
              </li>

              <li class="noticebar-menu-view-all">
                <a href="./page-notifications.html">View All Messages</a>
              </li>
            </ul>
          </li>
<?php
/*
DISABLED ALERTS FOR PHASE 1
          <li class="dropdown">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-exclamation-triangle"></i>
              <span class="navbar-visible-collapsed">&nbsp;Alerts&nbsp;</span>
            </a>

            <ul class="dropdown-menu noticebar-menu noticebar-hoverable" role="menu">
              <li class="nav-header">
                <div class="pull-left">
                  Alerts
                </div>
              </li>

              <li class="noticebar-empty">
                <h4 class="noticebar-empty-title">No alerts here.</h4>
                <p class="noticebar-empty-text">Check out what other makers are doing on Explore!</p>
              </li>
            </ul>
          </li>
*/
?>

        </ul>

        <ul class="nav navbar-nav navbar-right">

          <li>
            <a href="http://support.tealrcrm.com" target="_blank"><strong>Support</strong></a>
          </li>

          <li>
            <a href="http://university.tealcrm.com"><strong>Teal University</strong></a>
          </li>

          <li class="dropdown navbar-profile">
            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
				<?php

				// loads the profile image for the logged in user

				$user = $this->flexi_auth->get_user_by_id_query($this->flexi_auth->get_user_id())->row_array();
				$profile_image = new SimpleImage();
				$profile_image->load('../attachments/'.$user['upro_filename_original'],IMAGETYPE_JPEG);
				$profile_image->resizeToHeight(30);
				$profile_image->resizeToWidth(30);
				echo '<img src="'.$profile_image->show().'" alt="" />';

				?>
              <i class="fa fa-caret-down"></i>
            </a>

            <ul class="dropdown-menu" role="menu">

			  <li>
                  <span class="progress-stat-label" style="width:100%"><?php echo $user['upro_first_name'].' '.$user['upro_last_name'];?></span>
                  <br/>
			  </li>

              <li>
                <a href="<?php echo site_url('users/settings')?>">
                  <i class="fa fa-user"></i>
                  &nbsp;&nbsp;&nbsp;User Settings
                </a>
              </li>


              <?php if ($this->flexi_auth->in_group('Master Admin')): ?>
               <li>
                <a href="<?php echo site_url('settings')?>">
                  <i class="fa fa-cogs"></i>
                  &nbsp;&nbsp;CRM Settings
                </a>
              </li>
              <?php endif;?>

              <?php if ( $this->flexi_auth->is_logged_in()): ?>
              <li class="divider"></li>

              <li>
                <a href="<?php echo site_url('auth/logout')?>">
                  <i class="fa fa-sign-out"></i>
                  &nbsp;&nbsp;Logout
                </a>
              </li>
              <?php endif;?>

            </ul>

          </li>

        </ul>

      </nav>

    </div> <!-- /.container -->

  </header>


  <div class="mainnav tealbg">

    <div class="container">

      <a class="mainnav-toggle" data-toggle="collapse" data-target=".mainnav-collapse">
        <span class="sr-only">Toggle navigation</span>
        <i class="fa fa-bars"></i>
      </a>

      <nav class="collapse mainnav-collapse" role="navigation">
		<form action="<?php echo site_url('search')?>" id="frmedit-search" method="post" name="frmedit-search" class="mainnav-form pull-right" role="search">
          <input type="text" name="term" class="form-control input-md mainnav-search-query" placeholder="Search">
          <button class="btn btn-sm mainnav-form-btn"><i class="fa fa-search"></i></button>
        </form>

        <ul class="mainnav-menu ">

          <li class="dropdown <?php echo is_active_item('companies') ? 'active' : '';?>">

            <a href="<?php echo site_url('companies')?>" data-hover="dropdown">
            Companies
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">

              <li>
                <a href="<?php echo site_url('companies')?>">
                <i class="fa fa-building-o"></i>
                &nbsp;&nbsp;Manage Companies
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('companies/add')?>">
                <i class="fa fa-plus-circle"></i>
                &nbsp;&nbsp;Add New Company
                </a>
              </li>

              <li>
                <a href="#" data-toggle="modal" data-target="#dialog-import" onclick="javascript: $('#importModalLabel').text('Import Companies'); $('#module').val('companies'); $('#module').parent().find('a').attr('href', 'extras/Companies.csv'); return false;">
                <i class="fa fa-download"></i>
                &nbsp;&nbsp;Import Companies
                </a>
              </li>

            </ul>
          </li>



          <li class="dropdown <?php echo is_active_item('contacts') ? 'active' : '';?>">

            <a href="<?php echo site_url('contacts')?>" data-hover="dropdown">
            Contacts
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="<?php echo site_url('contacts')?>">
                <i class="fa fa-group"></i>
                &nbsp;&nbsp;Manage Contacts
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('contacts/add')?>">
                <i class="fa fa-plus-circle"></i>
                &nbsp;&nbsp;Add New Contact
                </a>
              </li>

              <li>
                <a href="#" data-toggle="modal" data-target="#dialog-import" onclick="javascript: $('#importModalLabel').text('Import Contacts'); $('#module').val('contacts'); $('#module').parent().find('a').attr('href', 'extras/Contacts.csv'); return false;">
                <i class="fa fa-download"></i>
                &nbsp;&nbsp;Import Contacts
                </a>
              </li>

            </ul>
          </li>

          <li class="dropdown <?php echo is_active_item('class') ? 'active' : '';?>">

            <a href="<?php echo site_url('deals')?>" data-hover="dropdown">
            Deals
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="<?php echo site_url('deals')?>">
                <i class="fa fa-dollar"></i>
                &nbsp;&nbsp;Manage Deals
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('deals/add')?>">
                <i class="fa fa-plus-circle"></i>
                &nbsp;&nbsp;Add New Deal
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('deals/pipeline')?>">
                <i class="fa fa-plus-circle"></i>
                &nbsp;&nbsp;Visual Pipeline
                </a>
              </li>

              <li>
                <a href="#" data-toggle="modal" data-target="#dialog-import" onclick="javascript: $('#importModalLabel').text('Import Deals'); $('#module').val('deals'); $('#module').parent().find('a').attr('href', 'extras/Deals.csv'); return false;">
                <i class="fa fa-download"></i>
                &nbsp;&nbsp;Import Deals
                </a>
              </li>

            </ul>
          </li>


           <li class="dropdown <?php echo is_active_item('notes') ? 'active' : '';?>">

            <a href="<?php echo site_url('notes')?>" data-hover="dropdown">
            Notes
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="<?php echo site_url('notes')?>">
                <i class="fa fa-paperclip"></i>
                &nbsp;&nbsp;Manage Notes
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('notes/add')?>">
                <i class="fa fa-plus-circle"></i>
                &nbsp;&nbsp;Add New Note
                </a>
              </li>

              <li>
                <a href="#" data-toggle="modal" data-target="#dialog-import" onclick="javascript: $('#importModalLabel').text('Import Notes'); $('#module').val('notes'); $('#module').parent().find('a').attr('href', 'extras/Notes.csv'); return false;">
                <i class="fa fa-download"></i>
                &nbsp;&nbsp;Import Notes
                </a>
              </li>

            </ul>
          </li>


          <li class="dropdown <?php echo is_active_item('tasks') ? 'active' : '';?>">

            <a href="<?php echo site_url('tasks')?>" data-hover="dropdown">
            Tasks
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="<?php echo site_url('tasks')?>">
                <i class="fa fa-check-square-o"></i>
                &nbsp;&nbsp;Manage Tasks
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('tasks/add')?>">
                <i class="fa fa-plus-circle"></i>
                &nbsp;&nbsp;Add New Task
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('tasks/project')?>">
                <i class="fa fa-sitemap"></i>
                &nbsp;&nbsp;Manage Projects
                </a>
              </li>

              <li>
                <a href="#" data-toggle="modal" data-target="#dialog-import" onclick="javascript: $('#importModalLabel').text('Import Tasks'); $('#module').val('tasks'); $('#module').parent().find('a').attr('href', 'extras/Tasks.csv'); return false;">
                <i class="fa fa-download"></i>
                &nbsp;&nbsp;Import Tasks
                </a>
              </li>

            </ul>
          </li>

          <li class="dropdown <?php echo is_active_item('meetings') ? 'active' : '';?>">

            <a href="<?php echo site_url('meetings')?>" data-hover="dropdown">
            Meetings
            <i class="mainnav-caret"></i>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="<?php echo site_url('meetings')?>">
                <i class="fa fa-calendar-o"></i>
                &nbsp;&nbsp;Manage Meetings
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('meetings/add')?>">
                <i class="fa fa-plus-circle"></i>
                &nbsp;&nbsp;Add New Meeting
                </a>
              </li>

              <li>
                <a href="<?php echo site_url('meetings/calendar')?>">
                <i class="fa fa-calendar"></i>
                &nbsp;&nbsp;Calendar
                </a>
              </li>

              <li>
                <a href="#" data-toggle="modal" data-target="#dialog-import" onclick="javascript: $('#importModalLabel').text('Import Meetings'); $('#module').val('meetings'); $('#module').parent().find('a').attr('href', 'extras/Meetings.csv'); return false;">
                <i class="fa fa-download"></i>
                &nbsp;&nbsp;Import Meetings
                </a>
              </li>

            </ul>
          </li>

        </ul>

      </nav>

    </div> <!-- /.container -->

  </div> <!-- /.mainnav -->


  <div class="content">

    <div class="container">

        <?php display_notify() ?>

        <?php echo $content_for_layout ?>

    </div> <!-- /.container -->

  </div> <!-- .content -->

</div> <!-- /#wrapper -->

<footer class="footer">
  <div class="container">
    <p class="pull-left">Copyright &copy; <?php echo date('Y')?> Eligeo CRM Inc.  Source code is released under the MIT License (MIT)</p>
  </div>
</footer>


<style>
.modal-body ul{

}
.modal-body li{
	list-style:none;
}
</style>



<!-- Bootstrap core JavaScript
================================================== -->

<!-- App JS -->
<script src="js/mvpready-core.js"></script>
<script src="js/mvpready-admin.js"></script>
<script src="js/teal_handler.js"></script>
<script>
$(document).ready(function(){


});

</script>

</body>
</html>