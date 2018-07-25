 
 <!-- header breadcrumb -->
<div class="row view-header">
 	<div class="col-md-12">
	 	<span class="title"><a href="">Companies</a></span><br/>
	 	<span class="record-name">ABC Company</span>
 	</div>
</div>

<div class="row view-content">
 	
 	<div class="col-md-3">
	 	<!-- BEGIN LINKED MODULES -->
Blah Blah
		<!-- ./ END LINKED MODULES -->
 	</div>
 	<div class="col-md-6">
 		
	<ul class="tabs">
		<li class="tab-link current" data-tab="tab-1">Quick Note</li>
		<li class="tab-link" data-tab="tab-2">Send Message</li>
	</ul>

	<div id="tab-1" class="tab-content current">
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
	</div>
	<div id="tab-2" class="tab-content">
		 Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
	</div>
	<div id="tab-3" class="tab-content">
		Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
	</div>
	<div id="tab-4" class="tab-content">
		Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
	</div>

 	</div>
 	<div class="col-md-3">
		<div class="panel panel-success">
		  <div class="panel-heading">
			  <h3 class="panel-title pull-left">People</h3>
			  <button class="btn btn-default pull-right">New</button>
			  <div class="clearfix"></div>
		  </div>
		  <div class="panel-body">Panel Content</div>
		
	    <div class="panel-footer clearfix">
	        <div class="pull-right">
	            <a href="#" class="btn btn-primary">Learn More</a>
	            <a href="#" class="btn btn-default">Go Back</a>
	        </div>
	    </div>		</div>
 	</div>
 	
</div>

  <script>
$(document).ready(function(){
	
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})

})
  </script>