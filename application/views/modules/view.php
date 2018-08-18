<?php

$this->load->helper('view_helper');

?>
      <div class="layout layout-main-right layout-stack-sm">

        <div class="col-md-2 layout-sidebar">
<li class="fa <?php echo $_SESSION['modules'][$module_name]['icon'];?>" style="color:#c0c0c0;"> <?php echo ucfirst($module_singular);?></li>
 <h2><?php echo display_name ( $module_name, $record); ?></h2>

		<div class="btn-group">
			<a href="<?php echo site_url($module_name . '/edit') . "/" . $record->{$_SESSION['modules'][$module_name]['db_key']}; ?>" class="btn btn-tertiary">Edit <?php echo ucfirst($module_singular);?></a> <button class="btn btn-tertiary dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button>
<hr/>
			<ul class="dropdown-menu">

				<li>
					<a href="javascript:delete_one( '<?php echo $record->{$_SESSION['modules'][$module_name]['db_key']}?>' );">Delete <?php echo ucfirst($module_singular);?></a>
				</li>
			</ul>
		</div><!-- /.btn-gruop -->
          <ul id="myTab" class="nav nav-layout-sidebar nav-stacked">

              <li class="active">
              <a href="#profile-tab" data-toggle="tab">
              <i class="pe-7s-wallet"></i>
              &nbsp;&nbsp;Overview
              </a>
            </li>

			<?php
			// output the related modules

			foreach ($related_modules as $rel){
				?>
				<li class="inactive">
              	<a href="#<?php echo $rel['module'];?>" data-toggle="tab">
              	<i class="fa <?php echo $_SESSION['modules'][$rel['module']]['icon'];?>"></i>
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
									<?php // check to see if column is empty or not
									if(!empty( $_SESSION['field_dictionary'][$module_name][$row[0]])){
									?><strong><?php echo $_SESSION['language'][$module_name][$row[0]];?></strong><br/><?php echo format_field($module_name,$row[0], $record->{$row[0]});?><br/><br/>
									<?php } ?>
								</div>
								<div class="col-md-6">
									<?php
									// check to see if column is empty or not
									if(!empty( $_SESSION['field_dictionary'][$module_name][$row[1]])){
									?>								
										<strong><?php echo $_SESSION['language'][$module_name][$row[1]];?></strong><br/>
										<?php echo format_field($module_name,$row[1], $record->{$row[1]});?><br/><br/>
									<?php } ?>
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
							<h3 class="panel-title"><?php echo $_SESSION['language']['global']['record_info'];?></h3>
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
						<h3 class="panel-title"><?php echo $_SESSION['language'][$rel['module']]['module_name'];?></h3>
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

								echo "<i> " . $_SESSION['language']['global']['no'] . ' ' . $rel['module'] . "</i>";

							}

							?>
							<br/>
							
							<a href="<?php echo site_url($rel['module'] . '/add') . "/" . $id ?>" class="label label-success"><?php echo $_SESSION['language']['global']['add_new'];?> <?php echo $_SESSION['language'][$rel['module']]['module_name'];?></a> <a href="<?php echo site_url($rel['module'] . '/related_' . $module_name . "/" . $id) ?>" class="label label-info"><?php echo $_SESSION['language']['global']['view_more'];?> <?php echo $_SESSION['language'][$rel['module']]['module_name'];?></a>						
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
					<h3 class="panel-title"><?php echo $_SESSION['language']['global']['messages'];?></h3>
				</div>
	
				<div class="panel-body" id="activity_feed_body">
					<div class="share-widget clearfix">
						<textarea class="form-control share-widget-textarea" placeholder="<?php echo $_SESSION['language']['global']['add_comment'];?>" rows="3" tabindex="1"></textarea>
	
						<div class="share-widget-actions">
							<div class="pull-right">
								<button class="btn btn-primary btn-sm" id="add_note_accounts" tabindex="2"><?php echo $_SESSION['language']['global']['add_comment'];?></button>
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
			data: {id:'<?php $id_single = $module_singular.'_id'; echo $record->{$id_single};?>'},
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


delete_one=function( id ){
// confirm
Messi.ask('Do you really want to delete the record11?', function(val) {
	// confirmed
	if( val == 'Y' ){
	window.location.href="<?php echo site_url($module_name . '/delete')?>/" + id;
	}
}, {modal: true, title: '<?php echo $_SESSION['language']['global']['confirm_delete'];?>'});
}


$(document).ready(function(){

	// picker
	jQuery('.datetime').datetimepicker({
		format: 'm/d/Y',
		mask: true,
		timepicker: false,
	});

	$("#add_note_accounts").on("click", function(){
		var desc = $.trim($("#activity_feed_body textarea").val());
		if(desc.length < 1) return false;


		$.ajax({
			url: '/feeds/add',  //server script to process data
			type: 'POST',
			async: true,
			data: {id:'<?php $id_single = $module_singular.'_id'; echo $record->{$id_single};?>', description:desc, cat:1},
			success: function(result) {
				//$("#activity_feed_body").append(result);
				$("#activity_feed_body>.share-widget").after(result);
				$("#activity_feed_body textarea").val('');
			}
		});
	});

	var lastFetchedFeed = 5;
	$(".panel .feed-more").on("click", function(){
		$(this).text("<?php echo $_SESSION['language']['global']['loading'];?>").addClass("active").append(' <i class="fa fa-gear fa-spin"></i>');
		$.ajax({
			url: '/ajax/more',  //server script to process data
			type: 'POST',
			async: true,
			data: {id:'<?php $id_single = $module_singular.'_id'; echo $record->{$id_single};?>', last:lastFetchedFeed, cat:1},
			success: function(result) {
				$(".panel .feed-more").text("<?php echo $_SESSION['language']['global']['load_more'];?>").removeClass("active").find('i').remove();
				var resultObject = $.parseJSON(result);
				lastFetchedFeed = resultObject.last;
				$(resultObject.value).insertBefore($("#activity_feed_body .feed-more"));

			}
		});
	});
});




</script>