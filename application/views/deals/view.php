<div class="layout layout-main-right layout-stack-sm">

	<div class="col-md-2 layout-sidebar">
<li class="fa fa-dollar" style="color:#c0c0c0;font-family:'FontAwesome'"> Deal</li>
 <h2><?php echo $deal->name?></h2>
			

		<div class="btn-group">
			<a href="<?php echo site_url('deals/edit') . "/" . $deal->deal_id; ?>" class="btn btn-tertiary">Edit Deal</a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>

			<ul class="dropdown-menu">

				<li>
					<a href="javascript:delete_one( '<?php echo $deal->deal_id?>' );">Delete Deal</a>
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
            </li>


          </ul>		
	
     </div> <!-- /.col -->

<?php
if(!empty($deal->deal_id)){
	$query = $deal->deal_id."/".$deal->deal_id;
}
else{
	$query = "null/".$deal->deal_id;
}
?>

	<div class="col-md-6 layout-main">
	
		<div class="row"> <!-- begin related records -->
			<div class="col-md-12 text-right">
				
	              <div class="btn-group demo-element">
	              <!--  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
	                Add Related Record
	                </button> -->
	

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
								<div class="col-md-12">
									<strong>Deal Name</strong><br/>
									<?php echo $deal->name?><br/><br/>
								</div>								
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>Assigned User</strong><br/>
									<?php
										$first_name = $_SESSION['user_accounts'][$deal->assigned_user_id]['upro_first_name'];
										$last_name = $_SESSION['user_accounts'][$deal->assigned_user_id]['upro_last_name'];
										if(($first_name != NULL) && ($last_name != NULL)) {
											echo $first_name." ".$last_name;
										} else if($first_name != NULL) {
											echo $first_name;
										} else if($last_name != NULL) {
											echo $last_name;
										} else {
											echo $_SESSION['user_accounts'][$deal->assigned_user_id]['uacc_username'];
										}
									?>
                                </div>
								<div class="col-md-6">
									<strong>Close Date</strong><br/>
															<?php
									if($deal->expected_close_date == null)
						
									{  echo "Not set";
									 }
									else
									{ ?>
										<?php echo date( config_item('date_display_short'), strtotime($deal->expected_close_date.' UTC') );
									}
									?>
										<br/><br/>
								</div>									
							</div>
							<div class="row">
								<div class="col-md-6">
									<strong>Company</strong><br/>
									<a href="<?php echo site_url('companies/view')?>/<?php echo $deal->company_id;?>"><?php echo $deal->company_name;?></a><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Deal Value</strong><br/>
									$<?php echo $deal->value;?><br/><br/>
								</div>									
							</div>	
							<div class="row">
								<div class="col-md-6">
									<strong>Person</strong><br/>
									<a href="<?php echo site_url('people/view')?>/<?php echo $deal->people_id;?>"><?php echo $deal->person_name. ' '.$deal->person_last;?></a><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Probability %</strong><br/>
									<?php echo $deal->probability?><br/><br/>
								</div>									
							</div>	
							<div class="row">
								<div class="col-md-6">
									<strong>Sales Stage</strong><br/>
									<?php echo $_SESSION['drop_down_options'][$deal->sales_stage_id]['name']?><br/><br/>
								</div>
								<div class="col-md-6">
									<strong>Next Step</strong><br/>
											<?php echo $deal->next_step?><br/><br/>
								</div>									
							</div>	
							<div class="row">
								<div class="col-md-12">
									<strong>Description</strong><br/>
									<?php echo $deal->description?><br/><br/>
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
			
												<div class="col-md-6"><strong><?php echo $custom['cf_label'].$cf_row;?></strong><br/>
			
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
						</div> <!-- end panel body -->
					</div> <!-- end panel -->

			  		<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Record Info</h3>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<strong>Created By</strong><br/>
<?php if (!empty($deal->created_by)){ echo date( 'm/d/Y h:ia', strtotime($deal->date_entered.' UTC'))?> by<br/>
				<?php echo $_SESSION['user_accounts'][$deal->created_by]['upro_first_name']." ".$_SESSION['user_accounts'][$deal->created_by]['upro_last_name']; } ?>								</div>
								<div class="col-md-6">
									<strong>Modified By</strong><br/>
									<?php if (!empty($deal->modified_user_id)){ ?>
													<?php echo date( 'm/d/Y h:ia', strtotime($deal->date_modified.' UTC'))?> by<br/>
													<?php echo $_SESSION['user_accounts'][$deal->modified_user_id]['upro_first_name']." ".$_SESSION['user_accounts'][$deal->modified_user_id]['upro_last_name'];} ?>								
								</div>									
							</div>	 <!-- end row -->						
						</div> <!-- end panel body -->
			  		</div> <!-- end panel -->

            </div> <!-- /.tab-pane -->
    						








</div>

<!-- END DATA -->
	
	
	</div> <!-- /.col -->

	<div class="col-md-4 layout-main">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Messages</h3>
			</div>

			<div class="panel-body" id="deal_feed_body">
				<div class="share-widget clearfix">
					<textarea class="form-control share-widget-textarea" placeholder="Add Comment..." rows="3" tabindex="1"></textarea>

					<div class="share-widget-actions">

						<div class="pull-right">
							<button class="btn btn-primary btn-sm" id="add_note_deals" tabindex="2">Add Comment</button>
						</div>
					</div><!-- /.share-widget-actions -->
				</div><!-- /.share-widget -->
				<!-- begin feed -->
				<?php echo $feed_list;?><!-- end feed -->
			</div>
		</div>
			
	</div>

</div>

<script type="text/javascript">
	 delete_one=function( deal_id ){
		Messi.ask('Do you really want to delete the record?', function(val) { 
			if( val == 'Y' ){
				window.location.href="<?php echo site_url('deals/delete')?>/" + deal_id;
			}
		}, {modal: true, title: 'Confirm Delete'});		
	}

  cancel=function(elm){
    window.location.href = '<?php echo site_url('deals')?>';
    return false;
  }

  edit=function(elm, id){
    window.location.href = '<?php echo site_url('deals/edit')?>/' + id;
    return false;
  }

$(document).ready(function(){
	$("#add_note_deals").on("click", function(){
		var desc = $.trim($("#deal_feed_body textarea").val());
		if(desc.length < 1) return false;

		$.ajax({
			url: '/feeds/add',  //server script to process data
			type: 'POST',
			async: true,
			data: {id:'<?php echo $deal->deal_id?>', description:desc, cat:3},
			success: function(result) {
				//$("#deal_feed_body").append(result);
				$("#deal_feed_body>.share-widget").after(result);
				$("#deal_feed_body textarea").val('');
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
			data: {id:'<?php echo $deal->deal_id?>', last:lastFetchedFeed, cat:3},
			success: function(result) {
				$(".panel .feed-more").text("Load More ").removeClass("active").find('i').remove();
				var resultObject = $.parseJSON(result);
				lastFetchedFeed = resultObject.last;
				$(resultObject.value).insertBefore($("#deal_feed_body .feed-more"));

			}
		});
	});
});

</script>

