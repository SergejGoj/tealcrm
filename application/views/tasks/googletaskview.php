
<div class="row">
	<div class="col-md-3 col-sm-5 panel panel-default panel-body">

		        <ul id="myTab1" class="nav nav-tabs">
          <li class="active">
            <a href="#details" data-toggle="tab">Details</a>
          </li>

        </ul>
        <div id="myTab1Content" class="tab-content">

 <div class="tab-pane fade active in" id="details">
 <ul class="icons-list">
 				
 <?php if (!empty($Gtask[0]->created_by)){ ?>
			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>created</strong>
				</div><?php echo date( 'm/d/Y h:sa', strtotime($Gtask[0]->date_entered))?> by<br/>
				<?php echo $_SESSION['user_accounts'][$Gtask[0]->created_by]['upro_first_name']." ".$_SESSION['user_accounts'][$Gtask[0]->created_by]['upro_last_name'];?>
			
			</li>
<?php } ?>
<?php if(!empty($Gtask[0]->due_date)) { ?>
			<li>
			<div>
				<i class = "icon-li fa fa-pencil"></i> <strong>Due Date</strong><br/>
				<?php print_r(date('m/d/Y',strtotime($Gtask[0]->due_date))); ?>
				
			</div>
			</li>
<?php } ?>		

<?php if(!empty($Gtask[0]->status)) { ?>
			<li>
			<div>
				<i class = "icon-li fa fa-pencil"></i> <strong>Status</strong><br/>
				<?php print_r($Gtask[0]->status); ?>
				
			</div>
			</li>
<?php } ?>

 </ul>
 </div>

        </div>
	</div>
	
	<div class="col-md-6 col-sm-7">

	<h2 class="text-left"><strong><?php echo $Gtask[0]->subject; ?></strong></h2>


		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Description</h3>
			</div>

			<div class="panel-body" id="note_feed_body">
				<div class="share-widget clearfix">

<?php echo $Gtask[0]->description; ?>
				</div>
			</div>
		</div><br class="visible-xs">
		<br class="visible-xs">
	</div>
</div>