<style>
.ui-widget	{
	font-size:inherit;
}
#trailing12chart {
    width: 100%;
    height: 200px;
}

</style>

<!-- row 1 -->
<div class="row dashboard">
  <div class="col-sm-6">
	  
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <i class="glyphicon glyphicon-signal dashboard_icon" aria-hidden="true"></i><h3 class="panel-title dashboard_panel_title dashboard_icon" >High Level Outlook</h3>
	  </div>
	  <div class="panel-body">
		 <table class="table keyvalue-table">
		                  <tbody>
		                    <tr>
		                      <td class="kv-key"><i class="fa fa-dollar kv-icon kv-icon-primary"></i> Deals In Pipeline ($)</td>
		                      <td class="kv-value"><?php echo "$".number_format($total_value_pipeline,2);?></td>
		                    </tr>
		                    <tr>
		                      <td class="kv-key"><i class="fa fa-gift kv-icon kv-icon-secondary"></i> Closed Deals (<?php echo date("M");?>)</td>
		                      <td class="kv-value"><?php echo "$".number_format($total_closed_deals,2);?></td>
		                    </tr>
		                    <tr>
		                      <td class="kv-key"><i class="fa fa-exchange kv-icon kv-icon-tertiary"></i> New People (<?php echo date("M");?>)</td>
		                      <td class="kv-value"><?php echo $new_people;?></td>
		                    </tr>
		                    <tr>
		                      <td class="kv-key"><i class="fa fa-envelope-o kv-icon kv-icon-default"></i> New Companies (<?php echo date("M");?>)</td>
		                      <td class="kv-value"><?php echo $new_accounts;?></td>
		                    </tr>
		                  </tbody>
		    </table>
	  </div>
	</div>
	  
  </div>
  <div class="col-sm-6" id="shortcuts_board">
	  
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <i class="glyphicon glyphicon-phone dashboard_icon" aria-hidden="true"></i><h3 class="panel-title dashboard_panel_title dashboard_icon" >TealCRM Shortcuts</h3>
	  </div>
	  <div class="panel-body">
	  <div class="shortcuts"> <a href="<?php echo site_url('/meetings/calendar/');?>" class="shortcut"><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i><span class="shortcut-label">Calendar</span> </a>
	  <a href="<?php echo site_url('/deals/pipeline/');?>" class="shortcut"><i class="glyphicon glyphicon-blackboard"  aria-hidden="true"></i><span class="shortcut-label">Kanban</span> </a>
	  <a href="<?php echo site_url('/projects/');?>" class="shortcut"><i class="glyphicon glyphicon-tasks" aria-hidden="true"></i> <span class="shortcut-label">Projects</span> </a>
	  <a href="<?php echo site_url('/users/import/');?>" class="shortcut"> <i class="glyphicon glyphicon-arrow-up" aria-hidden="true"></i><span class="shortcut-label">Import Data</span> </a>
	  <a href="<?php echo site_url('/notes/add/');?>" class="shortcut"><i class="pe-7s-note"></i><span class="shortcut-label">Add Note</span> </a>
	  <a href="<?php echo site_url('/tasks/add/');?>" class="shortcut"><i class="pe-7s-folder"></i><span class="shortcut-label">Add Task</span> </a>
	  <a href="<?php echo site_url('/people/add/');?>" class="shortcut"><i class="pe-7s-id"></i> <span class="shortcut-label">Add Person</span> </a>
	  <a href="<?php echo site_url('/deals/add/');?>" class="shortcut"> <i class="pe-7s-calculator"></i><span class="shortcut-label">Add Deal</span> </a> </div>
	  </div>
	</div>	  
	  
  </div>
</div>

<div class="row dashboard">
  <div class="col-sm-6">
	  
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <i class="glyphicon glyphicon-phone dashboard_icon" aria-hidden="true"></i><h3 class="panel-title dashboard_panel_title dashboard_icon" >Current & Overdue Activities & Tasks</h3>
	  </div>
	  <div class="panel-body dashboard-text">

<h4 style="margin:0px">Tasks</h4>
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                  	<th width="7%"></th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                      <?php if( ! $tasks->exists() ) :?>
                      <tr>
                          <td colspan="4" align="center">No tasks! <a href="<?php echo site_url('tasks/add/');?>">Schedule one.</a></td>
                      </tr>
                      <?php else: foreach($tasks as $task) :?>
                      <tr>
                        <td align="center"><div class="fa-hover"><a href="javascript:update_task( '<?php echo $task->task_id?>' )" class="fa-primary"><li class="fa fa-times-circle-o"></li></a></div></td>
                      	<td><a href="<?php echo site_url("tasks/view/".$task->task_id);?>"><?php echo $task->subject;?>
                      	                      	<?php
                      	  	if($task->due_date <= date("Y-m-d H:i:s")){ ?>
                      	<?php } ?>
                      	</a></td>
                      	<td><?php echo date('m/d/Y g:i a', strtotime($task->due_date))?></td>
                      	<td><?php echo $_SESSION['drop_down_options'][$task->status_id]['name'];?></td>
                      </tr>
                      <?php endforeach; endif;?>
              </table>

<h4 style="margin:0px">Phone Calls</h4>
    <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                  	<th width="7%"></th>
                    <th>Subject</th>
                    <th>When</th>
                    <th>Status</th>
                  </tr>
                </thead>
                      <?php if( ! $calls->exists() ) :?>
                      <tr>
                          <td colspan="4" align="center">No phone calls! <a href="<?php echo site_url('meetings/add/');?>">Schedule one.</a></td>
                      </tr>
                      <?php else: foreach($calls as $call) :?>
                      <tr>
                        <td align="center"><div class="fa-hover"><a href="javascript:update_meeting( '<?php echo $call->meeting_id?>' )" class="fa-primary"><li class="fa fa-times-circle-o"></li></a></div></td>
                      	<td><a href="<?php echo site_url("meetings/view/".$call->meeting_id);?>"><?php echo $call->subject;?>
                      	                      	<?php
                      	  	if(date('Y-m-d H:i:s', strtotime($call->due_date.' UTC')) <= date("Y-m-d 23:59:59")){ ?>
                      		<span class="label label-danger demo-element">Overdue from <?php echo date('m/d/Y',strtotime($call->date_start.' UTC'));?></span>
                      	<?php } ?>
                      	</a></td>
                      	<td><?php echo date('g:i A', strtotime($call->date_start.' UTC'))?></td>
                      	<td><?php echo $_SESSION['drop_down_options'][$call->status_id]['name'];?></td>
                      </tr>
                      <?php endforeach; endif;?>
              </table>




<h4 style="margin:0px">Meetings</h4>
<table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                  	<th width="7%"></th>
                    <th>Subject</th>
                    <th>When</th>
                    <th>Status</th>
                  </tr>
                </thead>
                      <?php if( ! $meetings->exists() ) :?>
                      <tr>
                          <td colspan="4" align="center">No meetings! <a href="<?php echo site_url('meetings/add/');?>">Schedule one.</a></td>
                      </tr>
                      <?php else: foreach($meetings as $meeting) :?>
                      <tr>
                        <td align="center"><div class="fa-hover"><a href="javascript:update_meeting( '<?php echo $meeting->meeting_id?>' )" class="fa-primary"><li class="fa fa-times-circle-o"></li></a></div></td>
                      	<td><a href="<?php echo site_url("meetings/view/".$meeting->meeting_id);?>"><?php echo $meeting->subject;?>
                      	                      	<?php
                      	  	if(date('Y-m-d H:i:s', strtotime($meeting->date_start.' UTC')) <= date("Y-m-d 23:59:59")){ ?>
                      		<span class="label label-danger demo-element">Overdue from <?php echo date('m/d/Y',strtotime($meeting->date_start.' UTC'));?></span>
                      	<?php } ?>
                      	</a></td>
                      	<td><?php echo date('g:i A', strtotime($meeting->date_start. ' UTC'))?></td>
                      	<td><?php echo $_SESSION['drop_down_options'][$meeting->status]['name'];?></td>
                      </tr>
                      <?php endforeach; endif;?>
              </table>

	  </div>
	  
	</div>	  
	  
  </div>
    <div class="col-sm-6">
	  
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <i class="glyphicon glyphicon-signal dashboard_icon" aria-hidden="true"></i><h3 class="panel-title dashboard_panel_title dashboard_icon" >Activity Feed</h3>
	  </div>
	  <div class="panel-body">
	 		<?php 

					if(strlen($feed_list) <= 0){
						
						echo "No activity as of yet.";
					}
					else{
					
						echo $feed_list;
					
					}
					?>	  </div>
	</div>
	  
  </div>
</div>
