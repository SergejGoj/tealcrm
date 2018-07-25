
<h3 class="content-title"><?php echo $rows;?> Search Results Found</h3>

  <div class="row">

    <div class="col-md-12">

      <div class="table-responsive">
		<form action="<?php echo site_url('search')?>" id="frmedit-search" method="post" name="frmedit-search" class="form-group" role="search">
			<div class="input-group">
				<label for="search_box" class="sr-only">Search</label>
				<input type="text" class="form-control" id="search_box" name="term" value="<?php echo $term;?>" placeholder="">
				<div class="input-group-btn">
					<button type="submit" class="btn btn-default" id="search_go">Search</button>&nbsp;
				</div><!-- /input-group-btn -->
			</div>
			<div>
				Filter by:
				<label class="checkbox-inline">
					<input type="checkbox" id="filter0" value="all" checked>All
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" id="filter1" value="tr_accounts" checked>Companies
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" id="filter2" value="tr_people" checked>People
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" id="filter3" value="tr_deals" checked>Deals
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" id="filter4" value="tr_notes" checked>Notes
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" id="filter5" value="tr_tasks" checked>Tasks
				</label>
				<label class="checkbox-inline">
					<input type="checkbox" id="filter6" value="tr_meetings" checked>Meetings
				</label>
			</div>
        </form>

          <form class="form-horizontal" name="frmlist" id="frmlist" action="#" method="post">
              <table class="table table-striped table-bordered thumbnail-table">
                  <thead>
                      <tr>
							<th class="text-center">Module</th>
							<th class="text-center">Details</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if( $rows == 0 ) :?>
                      <tr>
                          <td colspan="6" align="center">No Results</td>
                      </tr>
                      <?php else: echo $values; endif;?>
                  </tbody>
              </table>
          </form>

      </div> <!-- /.table-responsive -->

  </div> <!-- /.col -->



</div> <!-- /.row -->

<br /><br>
<script type="text/javascript">
$(document).ready(function(){
	$(".checkbox-inline").find("input").change(function(){
		//clicked on All
		if( $(this).attr("id") == "filter0" ){
			if( $(this).prop("checked") ){
				$(".checkbox-inline").find("input").prop("checked", true);
				$(".search_result_tr").show();
			}else{
				$(".checkbox-inline").find("input").prop("checked", false);
				$(".search_result_tr").hide();
			}
		}else{
			//on uncheck of any checkbox, uncheck All
			if( $(this).prop("checked") == false ){
				$("#filter0").prop("checked", false);
				$( "." + $(this).val() ).hide();
			}else{
				$( "." + $(this).val() ).show();
			}
		}

		var checkedMod = 0;
		$(".checkbox-inline").each(function(a,b){
			if( $(this).find("input").prop("checked") == true && $(this).find("input").attr("id") != "filter0" )
				checkedMod++;
		});
		if(checkedMod == 6){
			$("#filter0").prop("checked", true);
		}
	});
});
</script>
