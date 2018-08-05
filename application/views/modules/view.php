<?php
$this->load->helper('view_helper');
?>
      <div class="layout layout-main-right layout-stack-sm">

        <div class="col-md-2 layout-sidebar">
<li class="fa fa-building" style="color:#c0c0c0;font-family:'FontAwesome'"> <?php echo ucfirst($module_singular);?></li>
 <h2><?php //echo $record->name;?></h2>

		<div class="btn-group">
			<a href="<?php echo site_url($module_name . '/edit') . "/" . $record->company_id; ?>" class="btn btn-tertiary">Edit <?php echo ucfirst($module_singular);?></a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>
<hr/>
			<ul class="dropdown-menu">

				<li>
					<a href="javascript:delete_one( '<?php echo $record->company_id?>' );">Delete <?php echo ucfirst($module_singular);?></a>
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

			<?php
			// output the related modules

			foreach ($related_modules as $rel){
				?>
				<li class="inactive">
              	<a href="#<?php echo $rel['module'];?>" data-toggle="tab">
              	<i class="fa fa-list"></i>
              	&nbsp;&nbsp;<?php echo ucfirst($rel['module']);?> <?php if ($rel['total_rows']) { echo "<span class='badge'>".$rel['total_rows']."</span>"; } ?>
             	 </a>
            	</li>

				<?php
			} // end displaying links to related fields
			?>
           
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
				<?php
				foreach ($related_modules as $rel){
					?>
                    <li>
                      <a href="<?php echo site_url($rel['module'].'/add') . "/" . $id ?>"><?php echo ucfirst($rel['module']);?></a>
                    </li>
					<?php
				}
				?>
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
<?php if (!empty($record->created_by)){ echo date( 'm/d/Y h:ia', strtotime($record->date_entered.' UTC'))?> by<br/>
				<?php echo $_SESSION['user_accounts'][$record->created_by]['upro_first_name']." ".$_SESSION['user_accounts'][$record->created_by]['upro_last_name']; } ?>								</div>
								<div class="col-md-6">
									<strong>Modified By</strong><br/>
									<?php if (!empty($record->modified_user_id)){ ?>
													<?php echo date( 'm/d/Y h:ia', strtotime($record->date_modified.' UTC'))?> by<br/>
													<?php echo $_SESSION['user_accounts'][$record->modified_user_id]['upro_first_name']." ".$_SESSION['user_accounts'][$record->modified_user_id]['upro_last_name'];} ?>								</div>									
							</div>							</div>
			  		</div>

            </div> <!-- /.tab-pane -->

<?php
//output related records panels

foreach ($related_modules as $rel){
?>
            <div class="tab-pane fade in" id="<?php echo $rel['module']?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo ucfirst($rel['module']);?></h3>
					</div>
					<div class="panel-body">
							<?php
							if($rel['total_rows'] > 0){

								foreach ($rel['data'] as $rc) {

									// calls helper to format and display the data depending on the module
									echo format_related_list($rel['module'], $rel['data'], $rel['module_id']);
																		
								}

							}
							else{

								echo "<i>No ". $rel['module'] . "</i>";

							}

							?>
							<br/>
							
							<a href="<?php echo site_url($rel['module'] . '/add') . "/" . $id ?>" class="label label-success">Add New <?php echo ucfirst($rel['module']);?></a> <a href="<?php echo site_url($rel['module'] . '/related_' . $module_name . "/" . $id) ?>" class="label label-info">View More <?php echo ucfirst($rel['module'])?></a>						
					</div>
				</div>
            </div> <!-- / end panel -->
<?php
} // end output of related records
?>

                             							

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