<div class="layout layout-main-right layout-stack-sm">


  <div class="col-md-9 col-sm-8 layout-main">

    <div id="settings-content" class="tab-content stacked-content">

      <?php
	  if (isset($email))
	  {
			foreach($email as $em)
			{
				echo $em ."<br/>";
			}
	  }
	  
	  if (isset($events))
	  {
		  foreach($events as $eve)
			{
			print_r("Gmail User Name :".$eve->creator->displayName);
			echo '<br/>';
			print_r("Gmail id : ".$eve->creator->email);
			echo '<br/>';
			print_r("Event Title : ".$eve->summary);
			echo '<br/>';
			print_r("Event time : ".$eve->start->dateTime);
			echo '<br/>';
			print_r("Event location : ".$eve->location);
			echo '<br/>';
			echo '<br/>';
		}
	  }
	  ?>

    </div> <!-- /.tab-content -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

