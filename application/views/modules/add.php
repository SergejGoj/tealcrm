<?php

$this->load->helper('view_helper');

?>

<h3 class="content-title">Add New <?php echo ucfirst($_SESSION['language'][$module_name]['module_singular']);?></h3>

<?php

echo validation_errors('<div class="alert alert-danger">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>', '</div> <!-- /.alert -->');

$attributes = array('id' => 'frmprofile', 'name' => 'frmprofile');

echo form_open_multipart($module_name . '/add', $attributes);

?>


<div class="panel-group accordion-panel" id="accordion-paneled">

	<?php 
    // display each section
    $panel_count = 1;
	foreach ($framework as $section){

        if($panel_count == 1){
            echo '<div class="panel panel-default open">';
        }
        else{
            echo '<div class="panel panel-default">';
        }
	?>	
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-paneled" href="#panel<?php echo $panel_count; ?>"><?php echo key($section);?></a>
                </h4>
            </div> <!-- /.panel-heading -->

        <div id="panel<?php echo $panel_count; ?>" class="panel-collapse collapse <?php if ($panel_count == 1) { echo "in"; }?>">
            <div class="panel-body">

		<?php
		// display each row
		foreach ($section as $row){
		?>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <?php // check to see if column is empty or not
						if(!empty( $_SESSION['field_dictionary'][$module_name][$row[0]])){
						?><strong><?php echo $_SESSION['language'][$module_name][$row[0]];?></strong><br/><?php echo format_editable_field($module_name,$row[0],set_value($row[0]));?><br/><br/>
						<?php } ?>
                    </div>

                    <div class="form-group col-sm-6">
                        <?php
						// check to see if column is empty or not
						if(!empty( $_SESSION['field_dictionary'][$module_name][$row[1]])){
						?>								
						<strong><?php echo $_SESSION['language'][$module_name][$row[1]];?></strong><br/>
						<?php echo format_editable_field($module_name,$row[1], set_value($row[1]));?><br/><br/>
						<?php } ?>
                    </div>
                </div>

        <?php
        } // end display of each row
        ?>

            </div> <!-- /.panel-body -->
        </div> <!-- /.panel-collapse -->

    </div> <!-- /.panel -->

    <?php
        $panel_count++;
    } // end display of sections

    ?>

</div> <!-- /.accordion -->

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Add <?php echo ucfirst($module_singular);?></button>
    <button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
</div>
<input type="hidden" name="act" value="save">
</form>

<script type="text/javascript">
cancel = function (elm) {
    window.location.href = '<?php echo site_url($module_name)?>';
    return false;
}

// document ready
jQuery(document).ready(function () {
    // picker
    $('.date_no_time').datetimepicker({
    format: 'm/d/Y',
    mask: true,
    timepicker: false,
    });
 
     // picker
    $('.date_with_time').datetimepicker({
    format: 'm/d/Y H:i',
    mask: true,
    timepicker: true,
    });   

    var validator = jQuery("#frmedit").validate({
        ignore: "",
        rules: {
            company: "required",
        },
        messages: {
            company: "Enter name",
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent().find('label:first'));
        },
        invalidHandler: function (form, validator) {
            //manually highlight the main accordion
            $(".panel").removeClass("is-open");

            //manually close all accordions except collapseOne
            $(".panel-collapse").each(function (e) {
                if ($(this).attr("id") != "collapseOne")
                    $(this).removeClass('in');
            });

            //manually highlight the header of collapseOne
            if ($("#collapseOne").parent().hasClass("is-open") == false)
                $('#collapseOne').parent().addClass('is-open');

            //check if collapseOne is open or not, if not then open it
            if ($("#collapseOne").hasClass("in") == false)
                $('#collapseOne').collapse('show');
        },
        errorElement: 'em'
    });

    $( "#company_viewer" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/ajax/accountsAutocomplete",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    console.log('hi');
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {
            console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
            $("#company_id").val(ui.item.id);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });

    //autocomplete for peoples
    $( "#person_viewer" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/ajax/personsAutocomplete",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function( event, ui ) {
            console.log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
            $("#people_id").val(ui.item.id);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });   

    $("#attach_file").change(function (e) {
        $("#note_attach_valid").val('1');
        var filename = $(this).val();
        var lastIndex = filename.lastIndexOf("\\");
        if (lastIndex >= 0) {
            filename = filename.substring(lastIndex + 1);
        }
        $('#file_name_display').text(filename);
    }); 

});
</script>