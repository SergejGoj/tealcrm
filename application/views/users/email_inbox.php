<html lang="en">
<h1>UNDER DEVELOPMENT...</h1>
<head>
	<meta charset="utf-8" />
	<title>Color Admin | Inbox (10)</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/plugins/jquery-ui-1.10.4/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-3.1.1/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
</head>
<body>


<div class="email-btn-row hidden-xs">
                        <a href="<?php echo site_url('users/email_inbox')?>" class="btn btn-sm btn-default"><i class="fa fa-plus m-r-5"></i> New</a>
                        <a href="<?php echo site_url('users/email_inbox')?>" class="btn btn-sm btn-default">Reply</a>
                        <a href="<?php echo site_url('users/email_inbox')?>" class="btn btn-sm btn-default">Delete</a>
						
                        
                    </div>
<div class="email-content">
                        <table class="table table-email">
                            <thead>
                                <tr>
                                    <th class="email-select"><a href="<?php echo site_url('users/email_inbox')?>" data-click="email-select-all"><i class="fa fa-square-o fa-fw"></i></a></th>
                                    <th colspan="2">
                                        <div class="dropdown">
                                            <a href="<?php echo site_url('users/email_inbox')?>" class="email-header-link" data-toggle="dropdown">View All <i class="fa fa-angle-down m-l-5"></i></a>
                                            <ul class="dropdown-menu">
                                                <li class="active"><a href="<?php echo site_url('users/email_inbox')?>">All</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Unread</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Contacts</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Groups</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Newsletters</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Social updates</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Everything else</a></li>
                                            </ul>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="dropdown">
                                            <a href="<?php echo site_url('users/email_inbox')?>" class="email-header-link" data-toggle="dropdown">Arrange by <i class="fa fa-angle-down m-l-5"></i></a>
                                            <ul class="dropdown-menu">
                                                <li class="active"><a href="<?php echo site_url('users/email_inbox')?>">Date</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">From</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Subject</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Size</a></li>
                                                <li><a href="<?php echo site_url('users/email_inbox')?>">Conversation</a></li>
                                            </ul>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <td class="email-select"><a href="<?php echo site_url('users/email_inbox')?>" data-click="email-select-single"><i class="fa fa-square-o fa-fw"></i></a></td>
                                    <td class="email-sender">
                                        Sample Record
                                    </td>
                                    <td class="email-subject">
                                        <a href="<?php echo site_url('users/email_inbox')?>" class="email-btn" data-click="email-archive"><i class="fa fa-folder-open"></i></a>
                                        <a href="<?php echo site_url('users/email_inbox')?>" class="email-btn" data-click="email-remove"><i class="fa fa-trash-o"></i></a>
                                        <a href="<?php echo site_url('users/email_inbox')?>" class="email-btn" data-click="email-highlight"><i class="fa fa-flag"></i></a> 
                                        Sample Mail.
                                    </td>
                                    <td class="email-date">11/4/2014</td>
                                </tr>
                            </tbody>
                        </table>
                        
			        </div>
					</body>
					</html>