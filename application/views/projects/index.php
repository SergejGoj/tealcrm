
<style>
/*style for the search box, move it to css files later*/
.ui-autocomplete {
	z-index: 40000 !important;
}
</style>

<style>
.ui-dialog.time-dialog {
    font-family: Verdana,Arial,sans-serif;
    font-size: .8em;
    color: #ffffff;
}

.ui-widget-header {
	background: #227979;
	color: #ffffff;
}

.ui-dialog-titlebar-close {
	display: none;
}
</style>

<h3 class="content-title">Projects</h3>

<div class="row">
  <div class="col-md-3 col-sm-5">
<button type="button" class="btn btn-success" style="width:100%" onclick="window.location.href='<?php echo site_url('projects/add');?>'">Add New Project</button>
<br/><br/>
              
    <ul id="myTab" class="nav nav-pills nav-stacked">
      <?php if(!empty($_SESSION['project_tab'])) {?>
	  <li class = "in_active">
	  <?php } else {?>
	  <li class="active">
	  <?php }?>
        <a href="#home-3" data-toggle="tab"><i class="fa fa-list"></i> 
        &nbsp;&nbsp;All Projects
        </a>
      </li>
	  <?php $i = 1; 
	  foreach($asc_project as $acproj) {
	  if(isset($_SESSION['project_tab'])){
	  if($_SESSION['project_tab'] == $acproj->project_id) {?>
		<li class="active">
		<?php } else {?>
		<li class = "in-active">
		<?php } } else {?>
		<li class = "in-active">
		<?php } ?>
        <a href="#profile-<?php echo $i; ?>"  data-toggle="tab"> 
          &nbsp;&nbsp;<?php echo $acproj->project_name; ?>
        </a>
      </li>
		<?php $i++; }?>
      <!-- <li class="">
        <a href="#profile-3" data-toggle="tab"><i class="fa fa-arrow-right"></i> 
          &nbsp;&nbsp;Project 1
        </a>
        <ul style="padding-top:5px">
	        <li style="padding-top:5px"><a href="#profile-3" data-toggle="tab"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp;Subcategory</a></li>
	        <li style="padding-top:5px"><a href="#profile-3" data-toggle="tab"><i class="fa fa-arrow-right"></i>&nbsp;&nbsp;Subcategory</a></li>
        </ul>
      </li>

      <li class="">
        <a href="#profile-3" data-toggle="tab"><i class="fa fa-arrow-right"></i> 
          &nbsp;&nbsp;Project 1
        </a>
      </li>
      
      <li class="">
        <a href="#profile-3" data-toggle="tab"><i class="fa fa-arrow-right"></i> 
          &nbsp;&nbsp;Project 1
        </a>
      </li>    -->         

    </ul>
<a href="<?php echo site_url('projects/archived') ; ?>">Archived Projects</a>
  </div> <!-- /.col -->

  <div class="col-md-9 col-sm-7">
	  
	  
 

    <div id="myTabContent" class="tab-content stacked-content">
	 <?php if(isset($_SESSION['project_tab'])) {?>
      <div class="tab-pane fade" id="home-3">
	 <?php } else {?>
		<div class="tab-pane fade active in" id="home-3">
		<?php }?>
	  <div class="row">
		  <div class="col-md-6">
			  <h3 id="project_name">All Projects</h3>
		  </div>		  
		
	  </div>
	  
	   <div class="well">
  <div class="row">
		<div class="col-md-2">
			<strong>Project:</strong><br/>
		</div>
		<div class="col-md-2">
			<strong>Time Estimate:</strong><br/>
		</div>	
		<div class="col-md-2">
			<strong>Time Spent:</strong><br/>
		</div>	
		<div class="col-md-2">
			<strong>Utilization:</strong><br/>
		</div>	
		<div class="col-md-2">
			<strong>Start Date:</strong><br/>
		</div>
		<div class="col-md-2">
			<strong>End Date:</strong><br/>
		</div>	
	</div>
  <?php foreach($asc_project as $acproj) {?>	  
	<div class="row">
		<div class="col-md-2">
			<span id="project_name"><?php echo $acproj->project_name?></span>
		</div>
		<div class="col-md-2">
			<span id="time_estimate"><?php if (!empty($acproj->time_estimate)) { echo $acproj->time_estimate; } else { echo '0'; }?></span>
		</div>
		<div class="col-md-2">
		<?php
		$time_used = $this->db->query("select sum(time_used) as time_used from sc_tasks inner join sc_project_tasks on (sc_tasks.task_id = sc_project_tasks.task_id) where (sc_project_tasks.project_id = '" . $acproj->project_id . "')")->row("time_used");
		?>
			<span id="time_used"><?php if (!empty($time_used)) { echo $time_used; } else { echo '0'; }?></span>
		</div>
		<div class="col-md-2">
		<?php
		if ($time_used > 0) {
			$time_utilization = ($time_used / $acproj->time_estimate) * 100;
			$time_utilization = number_format($time_utilization, 0, '.', '');
		}
		else {
			$time_utilization = 0;
		}
		?>
			<span id="time_utilization"><?php echo $time_utilization; ?>%</span>
		</div>	
		<div class="col-md-2">
		<?php if($acproj->start_date == "0000-00-00" || $acproj->start_date == "") {?>
		<span id="start_date">Not set</span>
		<?php } else {?>
		<span id="start_date"><?php echo $acproj->start_date?></span>
		<?php } ?>
		</div>
		<div class="col-md-2">
		<?php if($acproj->end_date == "0000-00-00" || $acproj->end_date == "") {?>
		<span id="end_date">Not set</span>
		<?php } else {?>
		<span id="end_date"><?php echo $acproj->end_date?></span>
		<?php } ?>
		</div>	
	</div>

	<?php }?>


  </div> <!-- end well -->

  <!-- All Projects Task Listing -->

  			<!-- Overdue Tasks (All) -->
			<?php $hidall = 0;

			if(isset($over_due_task_all)){
			foreach($over_due_task_all as $oall) {
			$hidall = 1;
			} } if($hidall == 1) {?>
			<div class="panel panel-default">
			<input type="hidden" id="overDueAllOpened" value=0>
				<div class="panel-heading" style="background-color:#d24f4f;color:#ffffff">
					<?php 
				$taskCount = 0;
						if (!empty($over_due_task_all)) {
							 foreach($over_due_task_all as $notimportant) {$taskCount++;}  } 
							 ?>
					<h3 class="panel-title"><p style="float: left;">Overdue Tasks</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>
				</div>
				<div class="panel-body" id="project_tasks" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
							Close
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Project
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-2">
							Due Date
						</div>
						<div class="col-md-2">
							Status
						</div>
					</div>
					<?php
					$row_count = 1;
					foreach($over_due_task_all as $oalltask) {?>
					<div class="row even" id="overDueAll<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<input type = "checkbox" onclick = "updatetask('<?php echo $oalltask->task_id; ?>');">
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$oalltask->task_id) ; ?> ><?php echo $oalltask->subject;?></a>
						</div>
						<div class="col-md-2">
						<?php 
						$project_query = $this->db->query("select sc_projects.project_name, sc_projects.project_id from sc_projects inner join sc_project_tasks on (sc_projects.project_id = sc_project_tasks.project_id) where (sc_project_tasks.task_id = '" . $oalltask->task_id . "')");
						$project_name = $project_query->row("project_name");
						$project_id = $project_query->row("project_id");
						 ?>
						<a href = <?php echo site_url('projects/view/' . $project_id) ; ?> ><?php echo $project_name; ?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$oalltask->priority_id]['name'];?>
						</div>
							
						<div class="col-md-2">
							<?php echo substr($oalltask->due_date,0,11);?>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$oalltask->status_id]['name'];?>
						</div>
					</div>	
					<?php $row_count++; } ?>
					<?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="overDueAllShowMore" onClick="showMore(\'overDueAll\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>'; }?>
				</div>
			</div>
			<?php } ?>

		

			<!-- Today's Tasks (All) -->
			<div class="panel panel-default" >
				<div class="panel-heading" style="background-color:#EC9924;color:#ffffff">
				<input type="hidden" id="todayAllOpened" value=0>
						<?php 
				$taskCount = 0;
						if (!empty($today_due_task_all)) {
							 foreach($today_due_task_all as $notimportant) {$taskCount++;}  } 
							 ?>
					<h3 class="panel-title"><p style="float: left;">Today's Tasks</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>
				</div>
				<div class="panel-body" id="people_feed_body" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
							Close
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Project
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-2">
							Due Date
						</div>
						<div class="col-md-2">
							Status
						</div>
					</div>	
					<?php if(isset($today_due_task_all)) {?>
					<?php
					$row_count = 1;
					foreach($today_due_task_all as $talltask) {?>
					<div class="row even" id="todayAll<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<input type = "checkbox" onclick = "updatetask('<?php echo $talltask->task_id; ?>');">
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$talltask->task_id) ; ?> ><?php echo $talltask->subject;?></a>
						</div>
						<div class="col-md-2">
						<?php 
						$project_query = $this->db->query("select sc_projects.project_name, sc_projects.project_id from sc_projects inner join sc_project_tasks on (sc_projects.project_id = sc_project_tasks.project_id) where (sc_project_tasks.task_id = '" . $talltask->task_id . "')");
						$project_name = $project_query->row("project_name");
						$project_id = $project_query->row("project_id");
						 ?>
						<a href = <?php echo site_url('projects/view/' . $project_id) ; ?> ><?php echo $project_name; ?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$talltask->priority_id]['name'];?>
						</div>
						<div class="col-md-2">
							<?php echo substr($talltask->due_date,0,11);?>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$talltask->status_id]['name'];?>
						</div>
					</div>	
					<?php $row_count++;} ?>
					<?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="todayAllShowMore" onClick="showMore(\'todayAll\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>'; }?>
					<?php }?>
				</div>
			</div>	

			<!-- Next 7 Days Tasks (All) -->
			<div class="panel panel-default">
			<input type="hidden" id="nextSevAllOpened" value=0>
				<div class="panel-heading" style="background-color:#5CB85C;color:#ffffff">
					<?php 
				$taskCount = 0;
						if (!empty($next_sev_due_all)) {
							 foreach($next_sev_due_all as $notimportant) {$taskCount++;}  } 
							 ?>
					<h3 class="panel-title"><p style="float: left;">Next 7 Days</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>
					
				</div>
				<div class="panel-body" id="people_feed_body" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
							Close
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Project
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-2">
							Due Date
						</div>
						<div class="col-md-2">
							Status
						</div>
					</div>	
					<?php if(isset($next_sev_due_all)) {?>
					<?php $row_count = 1; 
					    foreach($next_sev_due_all as $nalltask) {?>
					<div class="row even" id="nextSevAll<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<input type = "checkbox" onclick = "updatetask('<?php echo $nalltask->task_id; ?>');">
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$nalltask->task_id) ; ?> ><?php echo $nalltask->subject;?></a>
						</div>
						<div class="col-md-2">
						<?php 
						$project_query = $this->db->query("select sc_projects.project_name, sc_projects.project_id from sc_projects inner join sc_project_tasks on (sc_projects.project_id = sc_project_tasks.project_id) where (sc_project_tasks.task_id = '" . $nalltask->task_id . "')");
						$project_name = $project_query->row("project_name");
						$project_id = $project_query->row("project_id");
						 ?>
						<a href = <?php echo site_url('projects/view/' . $project_id) ; ?> ><?php echo $project_name; ?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$nalltask->priority_id]['name'];?>
						</div>
						<div class="col-md-2">
							<?php echo substr($nalltask->due_date,0,11);?>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$nalltask->status_id]['name'];?>
						</div>
					</div>	
					<?php $row_count++;} ?>
					<?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="nextSevAllShowMore" onClick="showMore(\'nextSevAll\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>'; }?>
					<?php }?>
				</div>
			</div>	



      </div> <!-- /.tab-pane -->
		
      	<!-- individual projects -->

		 <?php $i = 1; 
	  foreach($asc_project as $acproj) {
		if(isset($_SESSION['project_tab'])) {
		if($_SESSION['project_tab'] == $acproj->project_id){
	  ?>
		<div class="tab-pane fade active in" id="profile-<?php echo $i; ?>">
		<?php } else {?>
		<div class="tab-pane fade" id="profile-<?php echo $i; ?>">
		<?php } } else {?>
		<div class="tab-pane fade" id="profile-<?php echo $i; ?>">
		<?php }?>
		
			<div class="row">
			  <div class="col-md-6">
				  <h3 id="project_name"><?php echo $acproj->project_name; ?></h3>
			  </div>		  
			  <div class="col-md-6" style="text-align:right">
				<div class="btn-group demo-element">
					<button type="button" class="btn btn-tertiary" onclick = "location.href = '<?php echo site_url('projects/edit/'.$acproj->project_id) ; ?>'">Edit Project</button>

					<button type="button" class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown">
					  <span class="caret"></span>
					</button>

					<ul class="dropdown-menu" role="menu">
					  <li><a onclick = "addtask('<?php echo $acproj->project_id;?>');" href = "javascript:;">Add New Tasks</a></li>
					  <li class="divider"></li>
					  <!-- <li><a href="javascript:;">Download Project Report</a></li>
					  <li class="divider"></li> -->
					  <li><a href="javascript:;" onclick = "archiveproject('<?php echo $acproj->project_id;?>','<?php echo $acproj->project_name; ?>');">Archive Project</a></li>
					</ul>
				  </div>
			  </div>
		  </div>

			<div class="well">
			  <div class="row">
			  <!--
					<div class="col-md-2">
						<!--<strong>Project:</strong><br/>
						<span id="project_name"><?php echo $acproj->project_name?></span> 
					</div>
					-->
					<div class="col-md-2">
						<h4>Time Estimate</h4><br/>
						<span id="time_esimate"><h5><center><?php echo $acproj->time_estimate?></center></h5></span>
					</div>	
					<div class="col-md-2">
						<h4>Time Spent</h4><br/>
						<?php
					$time_used = $this->db->query("select sum(time_used) as time_used from sc_tasks inner join sc_project_tasks on (sc_tasks.task_id = sc_project_tasks.task_id) where (sc_project_tasks.project_id = '" . $acproj->project_id . "')")->row("time_used");
		?>
						<span id="time_esimate"><h5><center><?php echo $time_used?></center></h5></span>
					</div>	
					<div class="col-md-2">
					<?php
		if ($time_used > 0) {
			$time_utilization = ($time_used / $acproj->time_estimate) * 100;
			$time_utilization = number_format($time_utilization, 0, '.', '');
		}
		else {
			$time_utilization = 0;
		}
		?>
						<h4>Utilization:</h4><br/>

						<span id="time_esimate"><h5><center><?php echo $time_utilization?>%</center></h5></span>
					</div>	

					<div class="col-md-3">
						<h4>Start Date:</h4><br/>
						<?php if($acproj->start_date == "0000-00-00" || $acproj->start_date == "") {?>
						<span id="start_date"><h5><center>Not set</center></h5></span>
						<?php } else {?>
						<span id="start_date"><h5><center><?php echo $acproj->start_date?></center></h5></span>
						<?php } ?>
					</div>
					<div class="col-md-3">
						<h4>End Date:</h4><br/>
						<?php if($acproj->end_date == "0000-00-00" || $acproj->end_date == "") {?>
						<span id="end_date"><h5><center>Not set</center></h5></span>
						<?php } else {?>
						<span id="end_date"><h5><center><?php echo $acproj->end_date?></center></h5></span>
						<?php } ?>
					</div>	
				</div>
			</div>	

			<!-- Overdue Tasks -->	
			<?php 
			$hid = 0;
			if(isset($over_due_task[$acproj->project_id])) {
			foreach($over_due_task[$acproj->project_id] as $ov) {
			$hid = 1;
			} } if($hid == 1) {?>
				
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color:#d24f4f;color:#ffffff">
				<input type="hidden" id="overDueOpened" value=0>
				<?php 
				$taskCount = 0;
						if (!empty($over_due_task[$acproj->project_id])) {
							 foreach($over_due_task[$acproj->project_id] as $notimportant) {$taskCount++;}  } 
							 ?>
					<h3 class="panel-title"><p style="float: left;">Overdue Tasks</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>
				</div>
				<div class="panel-body" id="project_tasks" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
							Close
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-3">
							Due Date
						</div>
						<div class="col-md-2">
							Status
						</div>
					</div>		
					<?php 
					$row_count = 1;
					foreach($over_due_task[$acproj->project_id] as $otask) {?>
					<div class="row even" id="overDue<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<input type = "checkbox" onclick = "updatetask('<?php echo $otask->task_id; ?>','<?php echo $acproj->project_id ?>');">
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$otask->task_id) ; ?> ><?php echo $otask->subject;?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$otask->priority_id]['name'];?>
						</div>
						<div class="col-md-3">
							<?php echo $otask->due_date;?>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$otask->status_id]['name'];?>
						</div>
					</div>	
					<?php $row_count++;  }?>
				
				<?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="overDueShowMore" onClick="showMore(\'overDue\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>';
					}?>
				</div>
			</div>
				<?php }?>


			<!-- Today's Tasks -->
			
			<div class="panel panel-default">
				<div class="panel-heading" style="background-color:#EC9924;color:#ffffff">
				<input type="hidden" id="todayOpened" value=0>	
				<?php 
				$taskCount = 0;
						if (!empty($today_due_task[$acproj->project_id])) {
							 foreach($today_due_task[$acproj->project_id] as $notimportant) {$taskCount++;}  } 
							 ?>
					<h3 class="panel-title"><p style="float: left;">Today's Tasks</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>


				</div>
				<div class="panel-body" id="people_feed_body" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
							Close
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-3">
							Due Date
						</div>
						<div class="col-md-2">
							Status
						</div>
					</div>
					<?php if(isset($today_due_task[$acproj->project_id])) { 
						$row_count = 1;
						foreach($today_due_task[$acproj->project_id] as $ttask) {?>
					<div class="row even" id="today<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<input type = "checkbox" onclick = "updatetask('<?php echo $ttask->task_id; ?>','<?php echo $acproj->project_id ?>');">
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$ttask->task_id) ; ?> ><?php echo $ttask->subject; ?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$ttask->priority_id]['name'];?>
						</div>
						<div class="col-md-3">
							<?php echo $ttask->due_date;?>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$ttask->status_id]['name'];?>
						</div>
					</div>	
					<?php $row_count++; } }?>


					 <?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="todayShowMore" onClick="showMore(\'today\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>';
					}?>
				</div>
			</div>	
			

			<!-- Next Seven Days -->
			
			<div class="panel panel-default">
			<input type="hidden" id="nextSevOpened" value=0>	
				<?php 
				$taskCount = 0;
						if (!empty($next_sev_due[$acproj->project_id])) {
							 foreach($next_sev_due[$acproj->project_id] as $notimportant) {$taskCount++;}  } 
							 ?>
				<div class="panel-heading" style="background-color:#5CB85C;color:#ffffff">
					<h3 class="panel-title"><p style="float: left;">Next 7 Days</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>
				</div>
				<div class="panel-body" id="people_feed_body" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
							Close
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-3">
							Due Date
						</div>
						<div class="col-md-2">
							Status
						</div>
					</div>	
					
					<?php
					if(isset($next_sev_due[$acproj->project_id])) { 
						$row_count = 1;
					foreach($next_sev_due[$acproj->project_id] as $ntask) {?>
					<div class="row even" id="nextSev<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<input type = "checkbox" onclick = "updatetask('<?php echo $ntask->task_id; ?>','<?php echo $acproj->project_id ?>');">
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$ntask->task_id) ; ?> ><?php echo $ntask->subject;?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$ntask->priority_id]['name'];?>
						</div>
						<div class="col-md-3">
							<?php echo $ntask->due_date; ?>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$ntask->status_id]['name']; ?>
						</div>
					</div>	
					<?php $row_count++;} }?>

					 <?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="nextSevShowMore" onClick="showMore(\'nextSev\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>';
					}?>
				</div>
			</div>	 





					<!-- All Other Tasks -->
		 	
				<div class="panel panel-default">

		 		<input type="hidden" id="allOtherOpened" value=0>	
				<div class="panel-heading" style="background-color:#002966;color:#ffffff">
					<?php 
							$taskCount = 0;
						if (!empty($tasks_no_due_date[$acproj->project_id])) {
							 foreach($tasks_no_due_date[$acproj->project_id] as $notimportant) {$taskCount++;}  } 
							 ?>
					<h3 class="panel-title"><p style="float: left;">All Other Tasks</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>
				</div>
				<div class="panel-body" id="people_feed_body" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
							Close
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-3">
							Due Date
						</div>
						<div class="col-md-2">
							Status
						</div>
					</div>	
					
					<?php
					if(isset($tasks_no_due_date[$acproj->project_id])) { 
						$row_count = 1;
					foreach($tasks_no_due_date[$acproj->project_id] as $ntask) {?>
					<div class="row even" id="allOther<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<input type = "checkbox" onclick = "updatetask('<?php echo $ntask->task_id; ?>','<?php echo $acproj->project_id ?>');">
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$ntask->task_id) ; ?> ><?php echo $ntask->subject;?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$ntask->priority_id]['name'];?>
						</div>
						<div class="col-md-3">
							<?php echo $ntask->due_date; ?>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$ntask->status_id]['name']; ?>
						</div>
					</div>	
					<?php $row_count++;} }?>
					 <?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="allOtherShowMore" onClick="showMore(\'allOther\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>';
					}?>
				</div>
			</div>	
			<!-- end All Other Tasks -->



		 	<!-- Completed Tasks -->	
		 	<div class="panel panel-default">
		 	<?php 
				$taskCount = 0;
						if (!empty($tasks_completed[$acproj->project_id])) {
							 foreach($tasks_completed[$acproj->project_id] as $notimportant) {$taskCount++;}  } 
							 ?>
		 	<input type="hidden" id="completedOpened" value=0>	
				<div class="panel-heading" style="background-color:#9900FF;color:#ffffff">
					<h3 class="panel-title"><p style="float: left;">Completed Tasks</p><p style="float: right;"><?php echo $taskCount; ?></p></h3><br>
				</div>
				<div class="panel-body" id="people_feed_body" style="padding-top:0px;padding-bottom:0px">
					<div class="row" style="background-color:#cccccc;padding:5px;font-weight:bold">
						<div class = "col-md-1">
						</div>
						<div class="col-md-3">
							Task Name
						</div>
						<div class="col-md-2">
							Priority
						</div>
						<div class="col-md-3">
							Completed On
						</div>
						<div class="col-md-2">
						Time Used
						</div>
					</div>	
					
					<?php
					
					if(isset($tasks_completed[$acproj->project_id])) { 
					$row_count = 1;
					
					foreach($tasks_completed[$acproj->project_id] as $ntask) {?>
					<div class="row even" id="completed<?php echo $row_count; ?>" <?php if ($row_count > 10) { echo "style='display: none;'"; } ?>>
						<div class = "col-md-1">
						<!-- <input type = "checkbox" onclick = "updatetask('<?php echo $ntask->task_id; ?>','<?php echo $acproj->project_id ?>');"> -->
						</div>
						<div class="col-md-3">
							<a href = <?php echo site_url('tasks/view/'.$ntask->task_id) ; ?> ><?php echo $ntask->subject;?></a>
						</div>
						<div class="col-md-2">
							<?php echo $_SESSION['drop_down_options'][$ntask->priority_id]['name'];?>
						</div>
						<div class="col-md-3">
						<?php echo $ntask->completed_date; ?>
						</div>
						<div class="col-md-2">
						<?php echo $ntask->time_used; ?>
						</div>
					</div>	
					<?php $row_count++;} }?>
					 <?php if ($row_count > 11) {
					 	$row_count = $row_count - 1;
					echo '<div id="completedShowMore" onClick="showMore(\'completed\','. $taskCount .');"><h4 class="panel-title"><center><br>Show More (' . ($row_count - 10) . ')</center></h4></div>';
					}?>
				</div>
			</div>	 
			
			<!-- end Completed Tasks -->


	






		</div><!-- /.tab-pane -->

	<?php $i++; } ?>


		

<!---  MODAL WINDOW FOR ADD TASK START --->	
		<div class="modal fade" id="add_task" tabindex="-1" role="dialog" aria-labelledby="newTaskModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="newTaskModalLabel">New Task</h4>
			  </div>
			  <div class="modal-body">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li class="active"><a href="#new_Task" role="tab" data-toggle="tab">Add Task</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active" id="new_Task">


						<form name="frmadd1" id="frmadd1" action="<?php echo site_url('projects/task_add/')?>" method="post" class="form parsley-form">
							
							<div class="form-group">
								<label for="subject">Subject</label>
								<input type="text" name="subject" id="subject" class="form-control"  placeholder="Enter subject">
							</div>

							 <div class="row">
								<div class="form-group col-sm-6">
								<label for="phone_fax">Assign Users</label>
		<?php 	 echo form_dropdown('assigned_user_id', $assignedusers1, $_SESSION['user']['id'],"class='form-control'"); ?>
								</div>
								<div class="form-group col-sm-6">
									<label for="due_date">Due Date</label>
									<input type="text" name="due_date" id="due_date" class="form-control datetime"  placeholder="Enter due date">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label for="company_id">Company</label>
									<input type="text" name="company_viewer" id="company_viewer" placeholder="Start typing here" class="form-control" value="<?php if(isset($company)) echo $company->company_name;?>"/>
									<input type="hidden" name="company_id" id="company_id" value="<?php if(isset($company_id)) echo $company_id;?>" />
								</div>
								<div class="form-group col-sm-6">
									<label for="people_id">Person</label>
									<input type="text" name="person_viewer" id="person_viewer" placeholder="Start typing here" class="form-control" value=""/>
									<input type="hidden" name="people_id" id="people_id" value="" />
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label for="priority_id">Priority</label>
									<?php echo form_dropdown('priority_id', $priority_ids, '97',"id='priority_id' class='form-control'"); ?>
								</div>
								<div class="form-group col-sm-6">
									<label for="status_id">Status</label>
									<?php echo form_dropdown('status_id', $status_ids, '100',"id='status_id' class='form-control'"); ?>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-sm-6">
									<label for="description">Comments/Description</label>
									<textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter comments/descriptions"></textarea>
								</div>
							</div>
				   
			
			<div class="form-actions">
				<input type = "hidden" id = "project_id" name = "project_id" value = "">
				<button type="submit" class="btn btn-primary">Save</button>
				<button class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
			<input type="hidden" name="act" value="save">

						</form>
					</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
  
  <!--- MODAL WINDOW FOR ADD TASK END -----> 
  
<!-- Time Confirmation Modal -->

<div id="timeDialog" style="display: none;">
<center>
Please enter the time (in minutes) spent on completing this task:<br><br>
<input type=text name="timeOnTask" id="timeOnTask" value="0">
</center>
</div>
<!-- Time Confirmation Modal END -->


	</div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

<?php unset($_SESSION['project_tab'])?>


<script type="text/javascript">




function showMore(tableName,totalRows) {

	var rowsToShow = totalRows - 10;
	
			
		if ($("#" + tableName + "Opened").val() == 0) {
		

		for (i=1; i <= rowsToShow; i++)
		{
			var rowToShow = i + 10;
			var divName = "#" + tableName + rowToShow.toString();
			$(divName).fadeIn(400);
		}
		$("#" + tableName + "Opened").val(1);
		$("#" + tableName + "ShowMore").html("<h5 class='panel-title'><center><br>Show Less</center></h5>");

		}

		else {

		for (i=1; i <= rowsToShow; i++)
		{
			var rowToShow = i + 10;
			var divName = "#" + tableName + rowToShow.toString();
			$(divName).fadeOut(400);
		}
		$("#" + tableName + "Opened").val(0);
		$("#" + tableName + "ShowMore").html("<h5 class='panel-title'><center><br>Show More (" + rowsToShow.toString() + ")</center></h5>");

		}
	
}

 
function addtask(project_id)
{
	$('#project_id').val(project_id);
	$('#add_task').modal( "show" );
	
}

jQuery(document).ready(function(){
		
	var validator = jQuery('#frmadd1').validate({
			rules: {
				subject: "required"
			},
			messages: {
				subject: "Enter subject"
			},
			errorPlacement: function(error, element) {
				error.insertAfter(element.parent().find('label:first'));
			},
			errorElement: 'em'
		});
				
	});
	
	// picker
	jQuery('.datetime').datetimepicker({
		format: 'm/d/Y H:i',
		mask: true
	});
		
	jQuery(document).ready(function(){
	
			//autocomplete for companies
			$( "#company_viewer" ).autocomplete({
				source: function( request, response ) {
					$.ajax({
						url: "/ajax/accountsAutocomplete",
						dataType: "json",
						data: {
							q: request.term
						},
						success: function( data ) {
							response( data );
						}
					});
				},
				minLength: 3,
				select: function( event, ui ) {
					console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
					$("#company_id").val(ui.item.id);
				},
				open: function() {
					$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
				},
				close: function() {
					$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
				}
			}).focus(function() {
				$(this).autocomplete("search", "");
			});
			
			//autocomplete for persons
		$( "#person_viewer" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/ajax/personsAutocomplete",
					dataType: "json",
					data: {
						q: request.term
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			minLength: 3,
			select: function( event, ui ) {
				console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
				$("#people_id").val(ui.item.id);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		}).focus(function() {
				$(this).autocomplete("search", "");
			});
		
		});
		
		function archiveproject(project_id,project_name)
		{
			if(project_name != "")
			{
				Messi.ask('Are you sure you want to archive ' + project_name.bold() + ' ?', function(val) {
					if (val == 'Y') {
						
						window.location.href = '<?php echo site_url('projects/add_archiveproject')?>/'+project_id;
						return false;
					}
					if (val == 'N') {
						
					}
				}, {
					modal: true,
					title: 'Confirm Archive'
				});
			}
			
		}
		function updatetask(task_id,project_id)
		{
			
$("#timeDialog").dialog({
        autoOpen: false,
        height: 150,
        width: 400,
        modal: true,
        title: "Add Time to Completed Task",
        buttons: {
            "Log Time": function () {
                 window.location.href = '<?php echo site_url('projects/updatetask')?>/'+task_id+'/'+project_id+'/'+$("#timeOnTask").val();
                $(this).dialog("close");
            },
            Cancel: function () {
            	$("input:checkbox").prop('checked', 0);
                $(this).dialog("close");
            }
        },
        dialogClass: 'no-close time-dialog'
        
    });

			$("#timeDialog").dialog("open");
			//window.prompt("Add Time to Task?","0");
			
			

			return false;
		
		}
</script>
      
