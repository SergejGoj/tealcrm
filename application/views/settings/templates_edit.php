

<div class="row">

  <div>

    <div class="portlet">

      <h2 class="portlet-title">
        <u>Update Template</u>
      </h2>

      <div class="portlet-body">

        <form name="frmpass" id="frmpass" action="<?php echo site_url('settings/templates/edit/'.$template->template_id)?>" method="post" class="form parsley-form">

  			<div class="form-group">
				<label for="subject">Name</label>
				<input type="text" name="name" id="name" class="form-control" value="<?php echo $template->name;?>" placeholder="Enter name of template">
			</div>

				<div class="row">
					<div class="col-sm-12"><label>Merge Fields</label></div>
				</div>
			<div class="row" id="merge_fields">
				<div class="col-sm-4">
				<select class="module form-control" id="module" name="module">
					<option selected>Select Module</option>
					<option value='companies'>Company</option>
					<option value='people'>People</option>
					<option value='deals'>Deal</option>
					<option value='products'>Product Display</option>
				</select>

				</div>
				<div class="col-sm-4">
				<select class="fields form-control" id="fields" name="fields">
					<option>Select Field</option>
				</select>
				</div>
				<div class="col-sm-4">
				<button id='insert_field' type="button" class="insert_field btn btn-success">Insert Field</button>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="form-group col-sm-12">
					<label for="description">HTML Template</label>
					<textarea name="html_content" id="html_content" class="html_content"><?php echo $template->html_body;?></textarea>
				</div>
			</div>

			<div class="form-actions">
				<button type="submit" id="submit_btn" class="btn btn-primary">Save</button>
				<button class="btn btn-default" onclick="return cancel(this)">Cancel</button>
			</div>
			<input type="hidden" name="act" value="save">
        </form>

      </div> <!-- /.portlet-body -->

    </div> <!-- /.portlet -->

  </div> <!-- /.col -->

</div> <!-- /.row -->

    <!-- include summernote -->
<link rel="stylesheet" href="css/summernote.css">
<script type="text/javascript" src="js/summernote.js"></script>
<script type="text/javascript" src="js/summernote-ext-fontstyle.js"></script>
  <script type="text/javascript">

 	 cancel=function(elm){
		window.location.href = '<?php echo site_url('settings/templates')?>';
		return false;
	}

	var selection;

    $(function() {
      $('.html_content').summernote({
	      toolbar: [
		          ['style', ['style']], // no style button
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['fontsize', ['fontsize']],
       ['fontname', ['fontname']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    	['height', ['height']],
    ['insert', ['link', 'picture', 'hr']],
    ['table', ['table']], // no table button
    ['view', ['fullscreen', 'codeview']]
	      ],
        height: 200,
        onfocus: function(e) {
	          	selection = document.getSelection();
  			},
  		onkeydown: function(e) {
	  			selection = document.getSelection();
  		},
  		onkeyup: function(e) {
	  		selection = document.getSelection();
  		}
      });

    });

	$(document).ready(function() {
	$("select#module").change(function() {
		var selected_module = $("select#module option:selected").attr('value');
		// alert(country_id);
		$("#fields").html("");
		$.ajax({
			type: "GET",
			url: "/ajax/TemplatesFieldList/" + selected_module,
			data: "module=" + selected_module,
			cache: false,
			beforeSend: function() {
				$('#fields').html('<img src="loader.gif" alt="" width="24" height="24">');
			},
			success: function(html) {
				$("#fields").html(html);
			}
		});
	});
});
$('.insert_field').click(function() //this will apply to all anchor tags
{
		if(selection == undefined){
		alert("Cursor not placed in HTML Template");
		}
		var cursorPos = selection.anchorOffset;
		var oldContent = selection.anchorNode.nodeValue;
		var toInsert = $( "select.fields" ).val();
		var modulename = $( "#module" ).val();
		if (modulename == 'companies')
		{
		toInsert = toInsert.replace("{","{company_");
		}
		if (modulename == 'people')
		{
		toInsert = toInsert.replace("{","{person_");
		}
		if (modulename == 'deals')
		{
		toInsert = toInsert.replace("{","{deals_");
		}
		if(cursorPos != 0){
			if(oldContent.substring(0) == 'HTML Template' ){alert("Cursor not placed in HTML Template");}
			if(oldContent.substring(0) == 'Merge Fields'){alert("Cursor not placed in HTML Template");}
			if(oldContent.substring(0) == 'Name'){alert("Cursor not placed in HTML Template");}

			if(oldContent.substring(0) != 'HTML Template' ){
			if(oldContent.substring(0) != 'Merge Fields'){
			if(oldContent.substring(0) != 'Name'){
			var newContent = oldContent.substring(0, cursorPos) + toInsert + oldContent.substring(cursorPos);
			selection.anchorNode.nodeValue = newContent;
		}}}}
		else{
			$(selection.anchorNode.parentNode).append(toInsert);
		}

});


jQuery(document).ready(function(){
            var validator = jQuery("#frmpass").validate({
              rules: {
                name: {required: true},
                },
              messages: {
                name: {required: " Required"},

              },
              errorPlacement: function(error, element) {
                error.insertAfter(element.parent().parent().find('label:first'));
              },
              errorElement: 'em',
              errorClass: 'login_error'
            });
          });
</script>

