
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

			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>From Name</strong><br/><?php echo $Gmail[0]->from_name; ?>
				</div>
			</li>

			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>From Email</strong><br/>
					<?php echo $Gmail[0]->from_email; ?>
				</div>
			</li>
			
			<li>
				<div>
					<i class = "icon-li fa fa-pencil"></i> <strong>Received Date</strong><br/>
					<?php echo $Gmail[0]->received_date; ?>
				</div>
			</li>
			
			<li>
				<div>
					<i class="icon-li fa fa-user"></i> <strong>Category</strong>
					<br/><?php echo $Gmail[0]->category; ?>
				</div>
			</li>
 </ul>
 </div>

        </div>
	</div>
	
	<div class="col-md-9 col-sm-7">

	<h2 class="text-left"><strong><?php echo $Gmail[0]->subject; ?></strong></h2>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Message</h3>
			</div>

			<div class="panel-body" id="note_feed_body">
				<div class="share-widget clearfix">
				<?php echo $Gmail[0]->message_body; ?>
				</div>
			</div>
		</div><br class="visible-xs">
		<br class="visible-xs">
	</div>
</div>