<div class="layout layout-main-right layout-stack-sm">

	<div class="col-md-2 layout-sidebar">
		<li class="fa fa-group" style="color:#c0c0c0;font-family:'FontAwesome'"> Person</li>
		<h2><?php echo $people->first_name . " " . $people->last_name;?></h2>


		<div class="btn-group">
			<a href="<?php echo site_url('people/edit') . "/" . $people->people_id; ?>" class="btn btn-tertiary">Edit Person</a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>

			<ul class="dropdown-menu">

				<li>
					<a href="javascript:delete_one( '<?php echo $people->people_id?>' );">Delete Person</a>
				</li>
			</ul>
		</div><!-- /.btn-gruop --><br/><br/>
		
          <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">

              <li class="active">
              <a href="#profile-tab" data-toggle="tab">
              <i class="fa fa-user"></i>
              &nbsp;&nbsp;Overview
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

<?php
if(!empty($people->company_id)){
	$query = $people->company_id."/".$people->people_id;
}
else{
	$query = "null/".$people->people_id;
}
?>

	<div class="col-md-6 layout-main">
	
		<div class="row"> <!-- begin related records -->
			<div class="col-md-12 text-right">
				
	              <div class="btn-group demo-element">
	                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
	                Add Related Record
	                </button>
	
	                <ul class="dropdown-menu" role="menu">
	                    <li>
	                      <a href="<?php echo site_url('deals/add') . "/" . $query; ?>">Deal</a>
	                    </li>
	                    <li>
	                      <a href="<?php echo site_url('notes/add') . "/" . $query; ?>">Note</a>
	                    </li>
	                    <li>
	                    <a href="<?php echo site_url('tasks/add') . "/" . $query; ?>">Task</a>
	                    </li>

	                    <li>
	                    <a href="<?php echo site_url('meetings/add') . "/" . $query ?>">Appointment</a>
	                    </li>
	                </ul>
	              </div> <!-- /.btn-gruop -->
	              
			</div> <!-- /. col -->
		</div><!-- /. row -->
	
	
<!-- BEGIN DATA -->


          <div id="settings-content" class="tab-content stacked-content">


            <div class="tab-pane fade in active" id="profile-tab">

					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Basic Info</h3>
						</div>
						<div class="panel-body">
							
							<div class="row">
								<div class="col-md-6">
									<strong>First Name</strong><br/>
									<?php echo $people->first_name?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Last Name</strong><br/>
									<?php echo $people->last_name;?><br/><br/>
								</div>									
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>Company</strong><br/>
									<a href="<?php echo site_url('companies/view') . "/" . $people->company_id; ?>"><?php echo $people->company_name;?></a><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Job Title</strong><br/>
									<?php echo $people->job_title;?><br/><br/>
								</div>									
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>Assigned User</strong><br/>
									<?php
										$first_name = $_SESSION['user_accounts'][$people->assigned_user_id]['upro_first_name'];
										$last_name = $_SESSION['user_accounts'][$people->assigned_user_id]['upro_last_name'];
										if(($first_name != NULL) && ($last_name != NULL)) {
											echo $first_name." ".$last_name;
										} else if($first_name != NULL) {
											echo $first_name;
										} else if($last_name != NULL) {
											echo $last_name;
										} else {
											echo $_SESSION['user_accounts'][$people->assigned_user_id]['uacc_username'];
										}
									?>
								</div>
								<div class="col-md-6">
									<strong>Lead Source</strong><br/>
									<?php echo $people->lead_source;?><br/><br/>
								</div>									
							</div>	
							<div class="row">
								<div class="col-md-6">
									<strong>Phone Work</strong><br/>
									<?php echo $people->phone_work;?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Phone Home</strong><br/>
									<?php echo $people->phone_home;?><br/><br/>
								</div>									
							</div>	
							<div class="row">
								<div class="col-md-6">
									<strong>Phone Mobile</strong><br/>
									<?php echo $people->phone_mobile;?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Birthdate</strong><br/>
												<?php
			if($people->birthdate == null)

			{  echo "Not set";
			 }
			else
			{ ?>
				<?php echo date('F jS, Y',strtotime($people->birthdate));
			}
			?><br/><br/>
								</div>									
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>Email (main)</strong><br/>
									<?php 
									
									if($_SESSION['user']['email_sending_option'] == 1){
										
										echo "<a href='mailto:".$people->email1."'>";
										echo $people->email1;
										echo "</a>";

									}
									else{
										echo "<a href='".site_url('messages/send/people/' . $people->people_id)."'>";
										echo $people->email1;
										echo "</a>";								
									}
									
									?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Email (backup)</strong><br/>
									<?php 
									
									if($_SESSION['user']['email_sending_option'] == 1){
										
										echo "<a href='mailto:".$people->email2."'>";
										echo $people->email2;
										echo "</a>";

									}
									else{
										echo "<a href='".site_url('messages/send/people/' . $people->people_id)."'>";
										echo $people->email2;
										echo "</a>";								
									}
									
									?><br/><br/>
								</div>									
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>Contact Type</strong><br/>
									<?php echo $_SESSION['drop_down_options'][$people->contact_type]['name'];?><br/>
								</div>								
							</div>							
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Address Info</h3>
						</div>
						<div class="panel-body">
							
							<div class="row">
								<div class="col-md-6">
									<strong>Address 1</strong><br/>
									<?php echo $people->address1?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Address 2</strong><br/>
									<?php echo $people->address2?><br/><br/>
								</div>									
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>City</strong><br/>
									<?php echo $people->city?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Province/State</strong><br/>
									<?php echo $people->province?><br/><br/>
								</div>									
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>Postal/Zip Code</strong><br/>
									<?php echo $people->postal_code?>
								</div>
								<div class="col-md-6">
									<strong>Country</strong><br/>
									<?php echo $people->country?>
								</div>									
							</div>	
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Other Info</h3>
						</div>
						<div class="panel-body">
							
							<div class="row">
								<div class="col-md-6">
									<strong>Do Not Call</strong><br/>
									<?php if($people->do_not_call == "Y"){ echo "Yes";} else { echo "No";};?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Email Opt Out</strong><br/>
									<?php if($people->email_opt_out == "Y"){ echo "Yes";} else { echo "No";};?><br/><br/>
								</div>									
							</div>	
							<div class="row">
								<div class="col-md-6">
									<strong>Description</strong><br/>
									<?php echo $people->description?><br/><br/>
								</div>								
							</div>	


					<?php
					// CUSTOM FIELDS DIVS
					
					$cf_row = 1;

					if ($is_custom_fields == 1)
					{
						foreach($custom_field_values as $custom) {

									// new row?
									if($cf_row == 1){
										echo '<div class="row">';
									}
									?>

									<div class="col-md-6"><strong><?php echo $custom['cf_label'];?></strong><br/>

									<?php if($custom['cf_type'] == "Textbox")
									{
										echo eval('return $'.$custom['cf_name'].';');
									}
									else
									{
										$dropval = eval('return $'.$custom['cf_name'].';');
										echo $_SESSION['drop_down_options'][$dropval]['name'];
									}
									?><br/><br/></div> <!-- end cf field -->
					
					
									<?php	
									$cf_row++;
															
									if($cf_row == 3){
										echo '</div>';
										$cf_row = 1;
									}

						}
					}
					
					if(count($custom_field_values) % 2 != 0){
						echo "</div>";
					}
					?>


			  </div>

            </div>

			  		<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Record Info</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<strong>Created By</strong><br/>
<?php if (!empty($people->created_by)){ echo date( 'm/d/Y h:ia', strtotime($people->date_entered.' UTC'))?> by<br/>
				<?php echo $_SESSION['user_accounts'][$people->created_by]['upro_first_name']." ".$_SESSION['user_accounts'][$people->created_by]['upro_last_name']; } ?>								</div>
								<div class="col-md-6">
									<strong>Modified By</strong><br/>
									<?php if (!empty($people->modified_user_id)){ ?>
													<?php echo date( 'm/d/Y h:ia', strtotime($people->date_modified.' UTC'))?> by<br/>
													<?php echo $_SESSION['user_accounts'][$people->modified_user_id]['upro_first_name']." ".$_SESSION['user_accounts'][$people->modified_user_id]['upro_last_name'];} ?>								</div>									
							</div>							</div>
			  		</div>



            </div> <!-- /.tab-pane -->
            
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
							
<a href="<?php echo site_url('tasks/add') . "/" . $query; ?>" class="label label-success">Add New Task</a> <a href="<?php echo site_url('tasks/related_people') . "/" . $people->people_id; ?>" class="label label-info">View More Tasks</a>
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
							
<a href="<?php echo site_url('deals/add') . "/" .$query; ?>" class="label label-success">Add New Deal</a> <a href="<?php echo site_url('deals/related_people') . "/" . $people->people_id; ?>" class="label label-info">View More Deals</a>
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
							
<a href="<?php echo site_url('notes/add') . "/" . $query; ?>" class="label label-success">Add New Note</a> <a href="<?php echo site_url('notes/related_people') . "/" . $people->people_id; ?>" class="label label-info">View More Notes</a>
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
							
<a href="<?php echo site_url('meetings/add') . "/" . $query; ?>" class="label label-success">Add New Meeting</a> <a href="<?php echo site_url('meetings/related_people') . "/" . $people->people_id; ?>" class="label label-info">View More Meetings</a>

					</div>
				</div>
            </div> <!-- / end panel -->	
          </div>

<!-- END DATA -->
	
	
	</div> <!-- /.col -->

	<div class="col-md-4 layout-main">
		
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Messages</h3>
				</div>
	
				<div class="panel-body" id="people_feed_body">
					<div class="share-widget clearfix">
						<textarea class="form-control share-widget-textarea" placeholder="Add Comment..." rows="3" tabindex="1"></textarea>
	
						<div class="share-widget-actions">
	
							<div class="pull-right">
								<button class="btn btn-primary btn-sm" id="add_note_people" tabindex="2">Add Comment</button>
							</div>
						</div><!-- /.share-widget-actions -->
					</div><!-- /.share-widget -->
					<!-- begin feed -->
					<?php echo $feed_list;?><!-- end feed -->
				</div>
			</div><!-- ./panel -->
	</div>

</div>


<script type="text/javascript">
  cancel=function(elm){
    window.location.href = '<?php echo site_url('people')?>';
    return false;
  }

  edit=function(elm, people_id){
    window.location.href = '<?php echo site_url('people/edit')?>/' + people_id;
    return false;
  }
	delete_one=function( people_id ){
		Messi.ask('Do you really want to delete the record?', function(val) {
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('people/delete')?>/" + people_id;
			}
		}, {modal: true, title: 'Confirm Delete'});
	}

$(document).ready(function(){
	$("#add_note_people").on("click", function(){
		var desc = $.trim($("#people_feed_body textarea").val());
		if(desc.length < 1) return false;

		$.ajax({
			url: '/feeds/add',  //server script to process data
			type: 'POST',
			async: true,
			data: {id:'<?php echo $people->people_id?>', description:desc, cat:2},
			success: function(result) {
				//$("#people_feed_body").append(result);
				$("#people_feed_body>.share-widget").after(result);
				$("#people_feed_body textarea").val('');
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
			data: {id:'<?php echo $people->people_id?>', last:lastFetchedFeed, cat:1},
			success: function(result) {
				$(".panel .feed-more").text("Load More ").removeClass("active").find('i').remove();
				var resultObject = $.parseJSON(result);
				lastFetchedFeed = resultObject.last;
				$(resultObject.value).insertBefore($("#people_feed_body .feed-more"));

			}
		});
	});
});

</script>