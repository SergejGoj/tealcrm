<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<style>
 .connectedSortable, .connectedSortable {
    border: 3px ridge #eee;
    width: 250px;
    min-height: 150px;
    list-style-type: none;
    margin: 0;
    padding: 5px 0 0 0;
    float: left;
    margin-right: 10px;
	border-radius: 10px;
  }
  .connectedSortable li, .connectedSortable li {
    margin: 5px 5px 5px 5px;
    padding: 5px;
    font-size: 1.2em;
	min-width: 120px;
	border-radius: 5px;
  }
  </style>
<div class="layout layout-main-right layout-stack-sm">

    <div class="col-md-3 col-sm-4 layout-sidebar">

      <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">
        <li>
          <a href="<?php echo site_url('settings')?>/#crm-settings">
          <i class="fa fa-user"></i>
          &nbsp;&nbsp;Back to CRM Settings
          </a>
        </li>

        <li   class="active">
           <a href="<?php echo site_url('settings')?>/#custom_list_views" data-toggle="tab">
           <i class="fa fa-list"></i>
          &nbsp;&nbsp;Edit List Views
          </a>
        </li>

		</ul>

    </div> <!-- /.col -->
	
	<div class="col-md-9 col-sm-8 layout-main">

		<div id="settings-content" class="tab-content stacked-content">
		
			<div class="tab-pane active" id="custom_list_views" >

			<h3 class="content-title">Select Module</h3>

			   <div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<div class="form-group">
								<ul id="myTab1" class="nav nav-tabs">
									<li class="active">
									<a href="#companies_list_views" data-toggle="tab">Companies</a>
									</li>

									<li class="">
									<a href="#people_list_views" data-toggle="tab">People</a>
									</li>

									<li class="">
									<a href="#deals_list_views" data-toggle="tab">Deals</a>
									</li>

									<li class="">
									<a href="#notes_list_views" data-toggle="tab">Notes</a>
									</li>

									<li class="">
									<a href="#tasks_list_views" data-toggle="tab">Tasks</a>
									</li>

									<li class="">
									<a href="#meetings_list_views" data-toggle="tab">Meetings</a>
									</li>
														
								</ul>
								
								<div id="myTab1Content" class="tab-content">
									<div class="tab-pane fade active in" id="companies_list_views">

										<div class="col-md-5">
											<h4>Select Fields From Here</h4>
											<ul  class="connectedSortable">
											
											  <?php foreach($company_fields as $cmp_field) {
											  $chk = 1;
											  if(isset($company_updated_fields)){
												  foreach($company_updated_fields as $check){
													  if($cmp_field == $check->field_name)
													  {
														$chk = 0;
													  }
												  }
											  }
											  if($chk != 0){
											  ?>
											  <li id = "<?php echo $cmp_field; ?>" class="ui-state-default"><?php echo $cmp_field; ?></li>
											  <?php } } $custom_field_values = $_SESSION['custom_field']['118'];
											  if (isset($custom_field_values))
											  foreach($custom_field_values as $cmp_cus) {
											  $chk1 = 1;
											  if(isset($company_updated_fields)){
												  foreach($company_updated_fields as $check){
													  if($cmp_cus['cf_name'] == $check->field_name)
													  {
														$chk1 = 0;
													  }
												  }
											  }
											  if($chk1 != 0){?>
											  <li id = "<?php echo $cmp_cus['cf_name']; ?>" class="ui-state-default"><?php echo $cmp_cus['cf_name']; ?></li>
											  <?php } }?>
											</ul>
										</div>
										<div class="col-md-5">
											 <h4>Drop Fields Here <span style="font-size:12px;">(Only drop 5 field)</span></h4>
											<ul id="company_list" class="connectedSortable">
											<?php if(isset($company_updated_fields)) { 
											 foreach($company_updated_fields as $cmp_up_field) {?>
											<li id = "<?php echo $cmp_up_field->field_name; ?>" class = "ui-state-default"><?php echo $cmp_up_field->field_name; ?></li>
											<?php } }?>
											</ul>
											<div class="list-footer-left">
												<button type="button" class="btn btn-success" onclick="save_custom('company_list','company');">Save</button>
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade" id="people_list_views">
										<div class="col-md-5">
										<h4>Select Fields From Here</h4>
										<ul class="connectedSortable">
										
										  <?php foreach($people_fields as $pep_field) {
										   $chk = 1;
											  if(isset($people_updated_fields)){
												  foreach($people_updated_fields as $check){
													  if($pep_field == $check->field_name)
													  {
														$chk = 0;
													  }
												  }
											  }
											  if($chk != 0){?>
										  <li id = "<?php echo $pep_field;?>" class="ui-state-default"><?php echo $pep_field; ?></li>
										  <?php } } $custom_field_values = $_SESSION['custom_field']['119'];
										  if (isset($custom_field_values))
										  foreach($custom_field_values as $pep_cus) {
										  $chk1 = 1;
											 if(isset($people_updated_fields)){
												foreach($people_updated_fields as $check){
													  if($pep_cus['cf_name'] == $check->field_name)
													  {
														$chk1 = 0;
													  }
												  }
											  }
											  if($chk1 != 0){?>
										  <li id = "<?php echo $pep_cus['cf_name'];?>" class="ui-state-default"><?php echo $pep_cus['cf_name']; ?></li>
										  <?php } }?>
										</ul>
										</div>
										<div class="col-md-5">
										 <h4>Drop Fields Here <span style="font-size:12px;">(Only drop 5 field)</span></h4>
										<ul id="people_list" class="connectedSortable">
										  <?php if(isset($people_updated_fields)) { 
											 foreach($people_updated_fields as $pep_up_field) {?>
											<li id = "<?php echo $pep_up_field->field_name; ?>" class = "ui-state-default"><?php echo $pep_up_field->field_name; ?></li>
											<?php } }?>
										</ul>
											<div class="list-footer-left">
												<button type="button" class="btn btn-success" onclick="save_custom('people_list','people');">Save</button>
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade" id="deals_list_views">
										<div class="col-md-5">
										<h4>Select Fields From Here</h4>
										<ul class="connectedSortable">
										
										  <?php foreach($deal_fields as $del_field) {
										  $chk = 1;
											  if(isset($deal_updated_fields)){
												  foreach($deal_updated_fields as $check){
													  if($del_field == $check->field_name)
													  {
														$chk = 0;
													  }
												  }
											  }
											  if($chk != 0){?>
										  <li id = "<?php echo $del_field; ?>" class="ui-state-default"><?php echo $del_field; ?></li>
										  <?php } } $custom_field_values = $_SESSION['custom_field']['120'];
										  if (isset($custom_field_values))
										  foreach($custom_field_values as $del_cus) {
										  $chk1 = 1;
											 if(isset($deal_updated_fields)){
												foreach($deal_updated_fields as $check){
													  if($del_cus['cf_name'] == $check->field_name)
													  {
														$chk1 = 0;
													  }
												  }
											  }
											  if($chk1 != 0){?>
										  <li id = "<?php echo $del_cus['cf_name'];?>" class="ui-state-default"><?php echo $del_cus['cf_name']; ?></li>
										  <?php } }?>
										</ul>
										</div>
										<div class="col-md-5">
										  <h4>Drop Fields Here <span style="font-size:12px;">(Only drop 5 field)</span></h4>
										<ul id="deal_list" class="connectedSortable">
										   <?php if(isset($deal_updated_fields)) { 
											 foreach($deal_updated_fields as $del_up_field) {?>
											<li id = "<?php echo $del_up_field->field_name; ?>" class = "ui-state-default"><?php echo $del_up_field->field_name; ?></li>
											<?php } }?>
										</ul>
											<div class="list-footer-left">
												<button type="button" class="btn btn-success" onclick="save_custom('deal_list','deal');">Save</button>
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade" id="notes_list_views">
										<div class="col-md-5">
										<h4>Select Fields From Here</h4>
										<ul class="connectedSortable">
										
										  <?php foreach($note_fields as $not_field) {
										  $chk = 1;
											  if(isset($note_updated_fields)){
												  foreach($note_updated_fields as $check){
													  if($not_field == $check->field_name)
													  {
														$chk = 0;
													  }
												  }
											  }
											  if($chk != 0){?>
										  <li id = "<?php echo $not_field; ?>" class="ui-state-default"><?php echo $not_field; ?></li>
										  <?php } } $custom_field_values = $_SESSION['custom_field']['121'];
										  if (isset($custom_field_values))
										  foreach($custom_field_values as $not_cus) {
										  $chk1 = 1;
											 if(isset($note_updated_fields)){
												foreach($note_updated_fields as $check){
													  if($not_cus['cf_name'] == $check->field_name)
													  {
														$chk1 = 0;
													  }
												  }
											  }
											  if($chk1 != 0){?>
										  <li id = "<?php echo $not_cus['cf_name']; ?>" class="ui-state-default"><?php echo $not_cus['cf_name']; ?></li>
										  <?php } }?>
										</ul>
										</div>
										<div class="col-md-5">
										  <h4>Drop Fields Here <span style="font-size:12px;">(Only drop 5 field)</span></h4>
										<ul id="note_list" class="connectedSortable">
										   <?php if(isset($note_updated_fields)) { 
											 foreach($note_updated_fields as $not_up_field) {?>
											<li id = "<?php echo $not_up_field->field_name; ?>" class = "ui-state-default"><?php echo $not_up_field->field_name; ?></li>
											<?php } }?>
										</ul>
										<div class="list-footer-left">
											<button type="button" class="btn btn-success" onclick="save_custom('note_list','note');">Save</button>
										</div>
										</div>
									</div>
									

									
									<div class="tab-pane fade" id="tasks_list_views">
										<div class="col-md-5">
										<h4>Select Fields From Here</h4>
										<ul class="connectedSortable">
										
										  <?php foreach($task_fields as $tak_field) {
										  $chk = 1;
											  if(isset($task_updated_fields)){
												  foreach($task_updated_fields as $check){
													  if($tak_field == $check->field_name)
													  {
														$chk = 0;
													  }
												  }
											  }
											  if($chk != 0){?>
										  <li id = "<?php echo $tak_field; ?>" class="ui-state-default"><?php echo $tak_field; ?></li>
										  <?php } } $custom_field_values = $_SESSION['custom_field']['123'];
										  if (isset($custom_field_values))
										  foreach($custom_field_values as $tak_cus) {
										  $chk1 = 1;
											 if(isset($task_updated_fields)){
												foreach($task_updated_fields as $check){
													  if($tak_cus['cf_name'] == $check->field_name)
													  {
														$chk1 = 0;
													  }
												  }
											  }
											  if($chk1 != 0){?>
										  <li id = "<?php echo $tak_cus['cf_name']; ?>" class="ui-state-default"><?php echo $tak_cus['cf_name']; ?></li>
										  <?php } }?>
										</ul>
										</div>
										<div class="col-md-5">
										 <h4>Drop Fields Here <span style="font-size:12px;">(Only drop 5 field)</span></h4>
										<ul id="task_list" class="connectedSortable">
										   <?php if(isset($task_updated_fields)) { 
											 foreach($task_updated_fields as $tak_up_field) {?>
											<li id = "<?php echo $tak_up_field->field_name; ?>" class = "ui-state-default"><?php echo $tak_up_field->field_name; ?></li>
											<?php } }?>
										</ul>
										<div class="list-footer-left">
											<button type="button" class="btn btn-success" onclick="save_custom('task_list','task');">Save</button>
										</div>
										</div>
									</div>
									
									<div class="tab-pane fade" id="meetings_list_views">
										<div class="col-md-5">
										<h4>Select Fields From Here</h4>
										<ul class="connectedSortable">
										
										  <?php foreach($meeting_fields as $met_field) {
										  $chk = 1;
											  if(isset($meeting_updated_fields)){
												  foreach($meeting_updated_fields as $check){
													  if($met_field == $check->field_name)
													  {
														$chk = 0;
													  }
												  }
											  }
											  if($chk != 0){?>
										  <li id = "<?php echo $met_field; ?>" class="ui-state-default"><?php echo $met_field; ?></li>
										  <?php } }$custom_field_values = $_SESSION['custom_field']['124'];
										  if (isset($custom_field_values))
										  foreach($custom_field_values as $met_cus) {
										  $chk1 = 1;
											 if(isset($meeting_updated_fields)){
												foreach($meeting_updated_fields as $check){
													  if($met_cus['cf_name'] == $check->field_name)
													  {
														$chk1 = 0;
													  }
												  }
											  }
											  if($chk1 != 0){?>
										  <li id = "<?php echo $met_cus['cf_name']; ?>"class="ui-state-default"><?php echo $met_cus['cf_name']; ?></li>
										  <?php } } ?>
										</ul>
										</div>
										<div class="col-md-5">
										  <h4>Drop Fields Here <span style="font-size:12px;">(Only drop 5 field)</span></h4>
										<ul id="meeting_list" class="connectedSortable">
										   <?php if(isset($meeting_updated_fields)) { 
											 foreach($meeting_updated_fields as $met_up_field) {?>
											<li id = "<?php echo $met_up_field->field_name; ?>" class = "ui-state-default"><?php echo $met_up_field->field_name; ?></li>
											<?php } }?>
										</ul>
										<div class="list-footer-left">
											<button type="button" class="btn btn-success" onclick="save_custom('meeting_list','meeting');">Save</button>
										</div>
										</div>
									</div>
								</div>
							</div>
						</div>	
					</div>
				</div>	
				<input type = "hidden" id = "field_list_value" name = "field_list_value" value = "">
			</div>
			
		</div>
	
	</div>

</div>	

<script>
 $(function() {
    $( ".connectedSortable, .connectedSortable" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
  });

  function save_custom(f_list,type)
  {
	var type = type;
	var list = document.getElementById (String(f_list));
    var liTags = list.getElementsByTagName ("li");
	var field_list = new Array();
    for (var i = 0; i < liTags.length; i++) 
	{
		field_list[field_list.length] = liTags[i].id;
	}
	var required = 0;
	for(var j = 0; j < liTags.length; j++)
	{
		if(field_list[j] == "company_name" || field_list[j] == "last_name" || field_list[j] == "name" || field_list[j] == "subject")
		{
			required = 1;
		}
		
	}
	var field = field_list.join();
	for(var i = 0; i < liTags.length; i++)
	{
		var field = field.replace(",","-");
	}
	if(required == 1)
	{
		if(liTags.length <= 5)
		{
			window.location.href="<?php echo site_url('settings/custom_list_views')?>/" + type + "/" + field;
		}
		else
		{
			alert("Only 5 Fields Allowed");
		}
	}
	else
	{
		alert("Please Select Required Field");
	}
	}
  </script>