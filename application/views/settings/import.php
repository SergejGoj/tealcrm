<h2 class="portlet-title">Sync People with MailChimp</h2>	
<form name="frmadd" id="frmadd" action="<?php echo site_url('settings/syncMailChimp') . "/". $key . "/" . ($step + 1); ?>" method="post" class="form parsley-form">
    <div class="panel-group accordion-panel" id="accordion-paneled">
        <div class="panel panel-default open">
            <div class="panel-heading">
				<?php if($step == 1 || $step == 2){ ?>
                	<h4 class="panel-title"><a class="accordion-toggle"	data-parent="#accordion-paneled" data-toggle="collapse" href="#collapseOne">Step <?php echo $step;?>/2</a></h4>
				<?php }?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-collapse collapse in" id="collapseOne">
                <div class="panel-body">
                    <div class="form-group">
						<?php echo $people; ?>
                    </div>
                </div>
                <!-- /.panel-collapse -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.accordion -->
    <div class="form-actions">
		<?php echo $nav_buttons;?>
    </div>
    <input type="hidden" name="act" value="save">
</form>
<script type="text/javascript">
	cancel=function(elm){
		window.location.href = '<?php echo site_url('settings')?>';
		return false;
	}
	skip=function(elm){
		window.location.href = '<?php echo site_url('settings/syncMailChimp') . "/". $key . "/" . ($step + 1) . "/skip"; ?>';
		return false;
	}
	
	function validateForm(class_name, error_msg){
		$(class_name).each(function(){
			var count = $(this).parent().parent().parent().find(".countRows").val();
			//console.log(count);
			//check only the records that the user didn't check "Ignore This Contact"
			if( ! $("#ignore_"+count).prop("checked") ){
				if($(this).val() == ""){
					$(this).addClass("error");
					if($(this).parent().find('em').length == 0)
						$("<em for='" + $(this).attr("id") + "' class='error'>" + error_msg + "</em>").insertAfter($(this).parent().find('label:first'));
					$(this).focus();
					return false;
				}else{
					$(this).removeClass("error");
					$(this).parent().remove('em');				
				}
				$(this).prop("required", true);
			}else{
				//remove required attr from ignored records
				$(this).prop("required", false);
			}
		});
		return true;
	}
	
	// document ready
	$(document).ready(function(){
		
		//validate fields before finish
		$("#import_finish").click(function(){
			console.log("validate id");
			if(! validateForm(".lead_source_id", "Enter lead source")) return false;

			console.log("validate job_title");
			if(! validateForm(".job_title", "Enter job")) return false;

			console.log("validate first_name");
			if(! validateForm(".first_name", "Enter First Name")) return false;
		});
		
		// picker
		jQuery('.datetime').datetimepicker({
			format: 'm/d/Y',
			mask: true,
			timepicker: false
		});
	});
</script>