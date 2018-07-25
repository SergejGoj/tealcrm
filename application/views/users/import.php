<h2 class="portlet-title">Import Data to TealCRM</h2>

You can import any CSV file to TealCRM.  A CSV file can be created using any spreadsheet program including Excel and Google Sheets.
<br/><br/>

<?php echo form_open_multipart('imports/import', array('id'=>'import_form', 'name'=>'import_form'));?>
<div class="row">
	<div class="col-md-3">
		<label for="csv_file">Select your CSV File</label>
		<div id="fileuploader">Choose File</div>
		<input type="hidden" class="form-control" name="step" value="1" />
<br/>
	</div>

	<div class="col-md-6">

	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<label for="file_delimiter">Delimiter</label>
		<input type="text" class="form-control" name="file_delimiter" id="file_delimiter" value="," />
		<div id="acc_checkboxes" style="display: none;"><input type="checkbox" id="link_accounts" name="link_accounts"> Link Created Contact to Existing Company, if a match is found.<br /><input type="checkbox" id="create_accounts" name="create_accounts"> Create a new Company if a matching record is not found.</div>
	</div>

	<div class="col-md-6">
		<strong>Select Type of Data</strong><br/>
		<input type="hidden" id="dataType" value="companies" name="dataType">
		<input type="radio" id="module" name="module" value="companies" checked="checked" onclick="togglePeopleOptions(this);"/> Companies<br/>
		<input type="radio" id="module" name="module" value="people" onclick="togglePeopleOptions(this);"/> People <br/>
		<input type="radio" id="module" name="module" value="deals" onclick="togglePeopleOptions(this);"/> Deals<br/>
		<input type="radio" id="module" name="module" value="tasks" onclick="togglePeopleOptions(this);"/> Tasks<br/>
		<input type="radio" id="module" name="module" value="notes" onclick="togglePeopleOptions(this);"/> Notes<br/>
		<input type="radio" id="module" name="module" value="meetings" onclick="togglePeopleOptions(this);"/> Meetings<br/>
	</div>
</div>
<br/>

		<input type="button" name="sub" id="startUpload" value="Import File" class="btn btn-primary" /><label for="startUpload" style="color:white;">Submit</label>

</form>

<script src="assets/js/jquery.uploadfile.js"></script>
  <script src="assets/core/js/plugins/jquery.validate/jquery.validate.min.js"></script>

<script>
function togglePeopleOptions(dt) {
	$("#dataType").val(dt.value);

	 if (dt.value == "people") {
	 	$("#acc_checkboxes").show();
	 }
	 else {
	 $("#acc_checkboxes").hide();	
	 }
	
}

$(document).ready(function(){
	var uploadObj = $("#fileuploader").uploadFile({
		url:"/imports/import'",
		fileName:"csv_file",
		autoSubmit :false,
		maxFileCount:1,
		multiple:false,
		dragDropStr: "",
		maxFileSize: 10000000, //3Mb
		dynamicFormData: function(){
			var data ={ file_delimiter:",", module:$("#dataType").val(), link_accounts:$("#link_accounts").prop('checked'), create_accounts:$("#create_accounts").prop('checked')}
			return data;
		},
		onSuccess:function(files,data,xhr){
			//files: list of files
			//data: response from server
			//xhr : jquer xhr object
			var resultObject = $.parseJSON(data);
			if(resultObject.value == "")
				alert("Something went wrong");
			else{
				if(resultObject.value == 0)
					alert("Import permission denied!");
				if(resultObject.value == 1){
					//alert("Import Success");
					$("#import_form").submit();
				}
			}

		},
		onError:function(files,status,errMsg){
			//files: list of files
			//data: response from server
			//xhr : jquer xhr object
			alert(errMsg);
		}
	});

	$("#startUpload").click(function(){
		
		uploadObj.startUpload();
	});
});

</script>
