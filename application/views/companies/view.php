<?php
$this->load->helper('view_helper');
?>
      <div class="layout layout-main-right layout-stack-sm">

        <div class="col-md-2 layout-sidebar">
<li class="fa fa-building" style="color:#c0c0c0;font-family:'FontAwesome'"> <?php echo ucfirst($module_singular);?></li>
 <h2><?php echo $record->name;?></h2>

		<div class="btn-group">
			<a href="<?php echo site_url('companies/edit') . "/" . $record->company_id; ?>" class="btn btn-tertiary">Edit Company</a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>
<hr/>
			<ul class="dropdown-menu">

				<li>
					<a href="javascript:delete_one( '<?php echo $record->company_id?>' );">Delete Company</a>
				</li>
			</ul>
		</div><!-- /.btn-gruop -->
          <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">

              <li class="active">
              <a href="#profile-tab" data-toggle="tab">
              <i class="fa fa-user"></i>
              &nbsp;&nbsp;Overview
              </a>
            </li>
			<li class="inactive">
              <a href="#people" data-toggle="tab">
              <i class="fa fa-list"></i>
              &nbsp;&nbsp;People <?php if ($rc_rows) { echo '<span class="badge">'.$rc_rows.'</span>'; } ?>
              </a>
            </li>

 			<li class="inactive">
              <a href="#tasks" data-toggle="tab">
              <i class="fa fa-check"></i>
              &nbsp;&nbsp;Tasks <?php if ($rt_rows) {	echo '<span class="badge">'.$rt_rows.'</span>';	} ?>
              </a>
            </li>
 			<li class="inactive">
              <a href="#deals" data-toggle="tab">
              <i class="fa fa-dollar"></i>
              &nbsp;&nbsp;Deals <?php if ($rd_rows) {	echo '<span class="badge">'.$rd_rows.'</span>';	} ?>
              </a>
            </li>
            <li class="inactive">
              <a href="#notes" data-toggle="tab">
              <i class="fa fa-paperclip"></i>
              &nbsp;&nbsp;Notes <?php if ($rn_rows) {	echo '<span class="badge">'.$rn_rows.'</span>';	} ?>
              </a>
            </li>
            			<li class="inactive">
              <a href="#meetings" data-toggle="tab">
              <i class="fa fa-calendar-o"></i>
              &nbsp;&nbsp;Meetings <?php if ($rm_rows) {	echo '<span class="badge">'.$rm_rows.'</span>';	} ?>
              </a>
            </li>
           
          </ul>
        </div> <!-- /.col -->



<div class="col-md-6 layout-main">
	
	
	<div class="row">
		
		<div class="col-md-12 text-right">

              <div class="btn-group demo-element">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                Add Related Record
                </button>

                <ul class="dropdown-menu" role="menu">
                    <li>
                      <a href="<?php echo site_url('people/add') . "/" . $record->company_id; ?>">Person</a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('deals/add') . "/" . $record->company_id; ?>">Deal</a>
                    </li>
                    <li>
                      <a href="<?php echo site_url('notes/add') . "/" . $record->company_id; ?>">Note</a>
                    </li>
                    <li>
                    <a href="<?php echo site_url('tasks/add') . "/" . $record->company_id; ?>">Task</a>
                    </li>
                    <li>
                    <a href="<?php echo site_url('meetings/add') . "/" . $record->company_id; ?>">Appointment</a>
                    </li>
                </ul>
              </div> <!-- /.btn-gruop -->
              
              
		</div>
	</div>

          <div id="settings-content" class="tab-content stacked-content">


<?php 

$framework = 
	array(
	  	array ( "Overview" =>	  
		  		array ("company_name", "assigned_user_id"),
				array ("phone_work", "company_type"),
				array ("email1", "email2"),  			  
		),
		array ("Address Info" =>
				array ("address1", "address2"),
				array ("city", "province"),
				array ("postal_code", "country"),  
		),
		array ("Other Info" =>
				array ("lead_status_id", "lead_source_id"),
				array ("webpage", "industry"),
				array ("phone_fax", "description"),  
				array ("do_not_call", "email_opt_out")
		)
	);
//pr($framework);
	?>
            <div class="tab-pane fade in active" id="profile-tab">

				<?php 
				// display each section
				foreach ($framework as $section){
				?>		
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo key($section);?></h3>
						</div>
						<div class="panel-body">

						<?php
						// display each row
						foreach ($section as $row){
						?>
							<div class="row">
								<div class="col-md-6">
									<strong><?php echo $_SESSION['field_dictionary'][$module_name][$row[0]]['field_label'];?></strong><br/>
									<?php echo format_field($module_name,$row[0], $record->$row[0]);?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong><?php echo $_SESSION['field_dictionary'][$module_name][$row[1]]['field_label'];?></strong><br/>
									<?php echo format_field($module_name,$row[1], $record->$row[1]);?><br/><br/>
								</div>									
							</div>
						<?php
						} // end display reach row
						?>	
						</div>
					</div>
				<?php
				}
				?>

			  		<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Record Info</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<strong>Created By</strong><br/>
<?php if (!empty($company->created_by)){ echo date( 'm/d/Y h:ia', strtotime($company->date_entered.' UTC'))?> by<br/>
				<?php echo $_SESSION['user_accounts'][$company->created_by]['upro_first_name']." ".$_SESSION['user_accounts'][$company->created_by]['upro_last_name']; } ?>								</div>
								<div class="col-md-6">
									<strong>Modified By</strong><br/>
									<?php if (!empty($company->modified_user_id)){ ?>
													<?php echo date( 'm/d/Y h:ia', strtotime($company->date_modified.' UTC'))?> by<br/>
													<?php echo $_SESSION['user_accounts'][$company->modified_user_id]['upro_first_name']." ".$_SESSION['user_accounts'][$company->modified_user_id]['upro_last_name'];} ?>								</div>									
							</div>							</div>
			  		</div>

            </div> <!-- /.tab-pane -->
            
            <div class="tab-pane fade in" id="people">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">People</h3>
					</div>
					<div class="panel-body">
							<?php

							if($rc_rows > 0){

								foreach ($related_people as $rc) {
									echo '<a class="list-group-item" href="';
									echo site_url('people/view/' . $rc->people_id);
									echo '"><h5 class="list-group-item-heading">';
									echo $rc->first_name." ".$rc->last_name;
									echo '</h5>	<p class="list-group-item-text">';
									echo $rc->job_title.'</p></a>';
								}

							}
							else{

								echo "<i>No People</i>";

							}

							?>
							<br/>
							
							<a href="<?php echo site_url('people/add') . "/" . $company->company_id; ?>" class="label label-success">Add New Person</a> <a href="<?php echo site_url('people/related_companies') . "/" . $company->company_id; ?>" class="label label-info">View More People</a>						
					</div>
				</div>
            </div> <!-- / end panel -->
							
            <div class="tab-pane fade in" id="tasks">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Tasks</h3>
					</div>
					<div class="panel-body">
						<?php
							if($rt_rows > 0){

								foreach ($related_tasks as $rt) {
									echo '<a class="list-group-item" href="';
									echo site_url('tasks/view/' . $rt->task_id);
									echo '"><h5 class="list-group-item-heading">';
									echo $rt->subject;
									echo '</h5>	<p class="list-group-item-text">';
									echo 'Due on: '.$rt->due_date.'</p></a>';
								}
							}
							else{

								echo "<i>No Tasks</i>";

							}
						?>
							<br/>
							
<a href="<?php echo site_url('tasks/add') . "/" . $company->company_id; ?>" class="label label-success">Add New Task</a> <a href="<?php echo site_url('tasks/related_companies') . "/" . $company->company_id; ?>" class="label label-info">View More Tasks</a>
					</div>
				</div>
            </div> <!-- / end panel -->							

            <div class="tab-pane fade in" id="deals">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Deals</h3>
					</div>
					<div class="panel-body">
						<?php
							if($rd_rows > 0){
								foreach ($related_deals as $rd) {
									echo '<a class="list-group-item" href="';
									echo site_url('deals/view/' . $rd->deal_id);
									echo '"><h5 class="list-group-item-heading">';
									echo $rd->name;
									echo '</h5>	<p class="list-group-item-text">';
									echo 'Value: $'.$rd->value.'</p></a>';
								}
							}
							else{

								echo "<i>No Deals</i>";

							}
						?>
							<br/>
							
<a href="<?php echo site_url('deals/add') . "/" . $company->company_id; ?>" class="label label-success">Add New Deal</a> <a href="<?php echo site_url('deals/related_companies') . "/" . $company->company_id; ?>" class="label label-info">View More Deals</a>
					</div>
				</div>
            </div> <!-- / end panel -->	
 

 
             <div class="tab-pane fade in" id="notes">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Notes</h3>
					</div>
					<div class="panel-body">
						<?php
							if($rn_rows > 0){
								foreach ($related_notes as $rn) {
									echo '<a class="list-group-item" href="';
									echo site_url('notes/view/' . $rn->note_id);
									echo '"><h5 class="list-group-item-heading">';
									echo $rn->subject . ' - '.date('Y-m-d H:i:s',strtotime($rn->date_entered.' UTC'));
									echo '</h5>	<p class="list-group-item-text">';
									echo $rn->description.'</p></a>';
								}
							}
							else{

								echo "<i>No Notes</i>";

							}
						?>
							<br/>
							
<a href="<?php echo site_url('notes/add') . "/" . $company->company_id; ?>" class="label label-success">Add New Note</a> <a href="<?php echo site_url('notes/related_companies') . "/" . $company->company_id; ?>" class="label label-info">View More Notes</a>
					</div>
				</div>
            </div> <!-- / end panel -->	

             <div class="tab-pane fade in" id="meetings">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Meetings</h3>
					</div>
					<div class="panel-body">
						<?php
							if($rm_rows > 0){
								foreach ($related_meetings as $rm) {
									echo '<a class="list-group-item" href="';
									echo site_url('meetings/view/' . $rm->meeting_id);
									echo '"><h5 class="list-group-item-heading">';
									echo $rm->subject;
									echo '</h5>	<p class="list-group-item-text">';
									echo 'Location: '.$rm->location.'<br><br>('.date('Y-m-d h:ia',strtotime($rm->date_start.' UTC')).' - '.date('Y-m-d h:ia',strtotime($rm->date_end.' UTC')).')</p></a>';
								}
							}
							else{

								echo "<i>No Meetings</i>";

							}
						?>
							<br/>
							
<a href="<?php echo site_url('meetings/add') . "/" . $company->company_id; ?>" class="label label-success">Add New Meeting</a> <a href="<?php echo site_url('meetings/related_companies') . "/" . $company->company_id; ?>" class="label label-info">View More Meetings</a>

					</div>
				</div>
            </div> <!-- / end panel -->	                                  							

          </div> <!-- /.tab-content -->

        </div> <!-- /.col -->
        
        <div class="col-md-4 layout-main" style="padding-left:0px">

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Messages</h3>
				</div>
	
				<div class="panel-body" id="activity_feed_body">
					<div class="share-widget clearfix">
						<textarea class="form-control share-widget-textarea" placeholder="Add Comment..." rows="3" tabindex="1"></textarea>
	
						<div class="share-widget-actions">
							<div class="pull-right">
								<button class="btn btn-primary btn-sm" id="add_note_accounts" tabindex="2">Add Comment</button>
							</div>
						</div><!-- /.share-widget-actions -->
					</div><!-- /.share-widget -->
					<!-- begin feed -->
					<?php echo $feed_list;?><!-- end feed -->
				</div>
			</div><br class="visible-xs">
			
        </div> <!-- ./col -->

      </div> <!-- /.row -->


<script type="text/javascript">
// HANDLE LIST VIEWS FOR SUB PANELS ON RELATED ITEMS

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

	// case statement to get and retrieve specific values from ajax
	var url = String(e.target);

	if(url.indexOf("people") > -1){
		
		$("#people_list").empty(); // empty results
		
		populate_list_view("people");
		
		$.ajax({
			url: '/ajax/people_list',  //server script to process data
			type: 'POST',
			async: true,
			data: {id:'<?php echo $company->company_id;?>'},
			success: function(result) {
				var resultObject = $.parseJSON(result);
				lastFetchedFeed = resultObject.last;
				$(resultObject.value).insertBefore($("#activity_feed_body .feed-more"));
			}
		});
		
	}






})	


function populate_list_view(module){
	
    // cache <tbody> element:
    var tbody = $('#people_list');
    
    // append header
    $('<div class="row" style="font-weight:bold"><div class="col-md-3">Name</div><div class="col-md-3">Title</div><div class="col-md-3">Work Phone</div><div class="col-md-3">Email</div></div>').appendTo(tbody);
    $('<div class="row"><div class="col-md-3">hello</div><div class="col-md-3">hello</div><div class="col-md-3">hello</div><div class="col-md-3">hello</div></div>').appendTo(tbody);



	
}
	
	
</script>

<script type="text/javascript">
  cancel=function(elm){
    window.location.href = '<?php echo site_url('companies')?>';
    return false;
  }

  edit=function(elm, id){
    window.location.href = '<?php echo site_url('companies/edit')?>/' + id;
    return false;
  }

  delete_one=function( company_id ){
    // confirm
    Messi.ask('Do you really want to delete the record?', function(val) {
      // confirmed
      if( val == 'Y' ){
        window.location.href="<?php echo site_url('companies/delete')?>/" + company_id;
      }
    }, {modal: true, title: 'Confirm Delete'});
  }


$(document).ready(function(){
	$("#add_note_accounts").on("click", function(){
		var desc = $.trim($("#activity_feed_body textarea").val());
		if(desc.length < 1) return false;


		$.ajax({
			url: '/feeds/add',  //server script to process data
			type: 'POST',
			async: true,
			data: {id:'<?php echo $company->company_id;?>', description:desc, cat:1},
			success: function(result) {
				//$("#activity_feed_body").append(result);
				$("#activity_feed_body>.share-widget").after(result);
				$("#activity_feed_body textarea").val('');
			}
		});
	});

	var lastFetchedFeed = 5;
	$(".panel .feed-more").on("click", function(){
		$(this).text("Loading ").addClass("active").append(' <i class="fa fa-gear fa-spin"></i>');
		$.ajax({
			url: '/ajax/more',  //server script to process data
			type: 'POST',
			async: true,
			data: {id:'<?php echo $company->company_id;?>', last:lastFetchedFeed, cat:1},
			success: function(result) {
				$(".panel .feed-more").text("Load More ").removeClass("active").find('i').remove();
				var resultObject = $.parseJSON(result);
				lastFetchedFeed = resultObject.last;
				$(resultObject.value).insertBefore($("#activity_feed_body .feed-more"));

			}
		});
	});
});

</script>