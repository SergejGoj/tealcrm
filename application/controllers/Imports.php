<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Imports Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Imports extends App_Controller {

	protected $dont_show_accounts = array('company_id', 'date_entered', 'date_modified', 'modified_user_id', 'created_by',  'import_datetime', 'last_viewed', 'deleted');
	protected $sc_table_companies = "sc_companies";

	protected $dont_show_people = array('people_id', 'company_id', 'date_entered', 'date_modified', 'modified_user_id', 'created_by', 'import_datetime', 'csv_file_name', 'google_id', 'google_access_token', 'mailchimp_id', 'last_viewed', 'deleted');
	protected $sc_table_people = "sc_people";

	protected $dont_show_deals = array('deal_id', 'date_entered', 'date_modified', 'modified_user_id', 'created_by', 'import_datetime', 'last_viewed', 'deleted');
	protected $sc_table_deals = "sc_deals";

	protected $dont_show_notes = array('note_id', 'date_entered', 'date_modified', 'modified_user_id', 'created_by', 'filename_original', 'filename_mimetype', 'import_datetime', 'last_viewed', 'deleted');
	protected $sc_table_notes = "sc_notes";

	protected $dont_show_tasks = array('task_id', 'date_entered', 'date_modified', 'modified_user_id', 'created_by', 'status_id', 'completed', 'completed_date', 'import_datetime', 'last_viewed', 'deleted');
	protected $sc_table_tasks = "sc_tasks";

	protected $dont_show_meetings = array('task_id', 'date_entered', 'date_modified', 'modified_user_id', 'created_by', 'status', 'import_datetime', 'last_viewed', 'deleted');
	protected $sc_table_meetings = "sc_meetings";

    /**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct();
	}

	/*
	 * this is not one of the modules; thus no need for index (no direct access without function ie: example.com/class/function/ID)
	 * dummy index to redirect user to dashboard if no function passed along with the URL
	*/
	public function index(){
		redirect('users/dashboard');
	}

	/**
	* import
	*
	* @param void
	* @return void
	*/
	public function import(){
		error_reporting(0);
		$error = $return = false;
		$drop_first_row = "";

		//go down public directory by one
		$upload_dir = './../attachments/';

		//logedin user
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

		$form_post_data = $this->input->post();
		$module = $form_post_data['module'];
		

		$delimiter = ",";
		if( $form_post_data['file_delimiter'] != "" && $form_post_data['file_delimiter'] != "," )
			$delimiter = $form_post_data['file_delimiter'];

		// get
		$get = $this->input->get(null, true);

		// check if this is a gmail import
		//$get['t'] and $get['e'] are passed from google handler under views/layout/default.php
		if( isset($get['t']) && ! empty($get['t']) && isset($get['e']) && ! empty($get['e']) ){
			$this->importGoogle($get['t'], $get['e']);

		//else csv file
		}else{

			//do step 2: show results of processing the file
			if(isset($form_post_data['step']) && $form_post_data['step']=="2"){
			$dupe_array = Array();
				// Build the Duplicate Checking Array based on Checkboxes
				foreach ($form_post_data as $key => $value) {
					if (substr($key,0,10) == "duplicate_") {
						$col_num = substr($key,10);
						$field_name = $form_post_data[$col_num];
						$dupe_array[$field_name] = "";
						//$dupe_check = array($form_post_data[$field_name]=>"");
						//array_push($dupe_array, $dupe_check);
					}

				}

				

				
				$step = 2;
				$file_name = $this->session->userdata('csv_filename_session');
				$upload_path = $upload_dir.basename($file_name);
				$link_accounts = $form_post_data['link_accounts'];
				$create_accounts = $form_post_data['create_accounts'];

				//$where_str = '`uacc_id` <> ' . $user_id;
				$where_arr  = array('uacc_id <>' => $user_id);
				// load model
				//$this->load->model('flexi_auth_model');
				$users = $this->flexi_auth->get_users_query(array("id,CONCAT(first_name, ' ', last_name) AS name", FALSE), $where_arr)->result_array();

				$return = $this->step_two_table($upload_path, $drop_first_row, $users, $module, $delimiter, $link_accounts, $create_accounts, $dupe_array);
				//var_dump($return);

			//do step 1: process first line of the file
			}elseif(isset($form_post_data['step']) && $form_post_data['step']=="1"){


				$step = 1;
				$file_name = $this->session->userdata('csv_filename_session');
				$upload_path = $upload_dir.basename($file_name);


				$table_array = $this->step_one_table($upload_path, $drop_first_row, $module, $delimiter);
				if(!$table_array) {
					$error.='<li>An error has occured, please go back and try again.</li>';
				}else{

				}

				switch($module){
					case "companies":
						$req_titles = "Company Name";
						$req_fields = "company_name";
						$default_dupes = array("email1","company name");

					break;
					case "people":
						$req_titles = "First Name, Last Name";
						$req_fields = "first_name, last_name";
						$default_dupes = array("email1","last name");

					break;
					case "deals":
						$req_titles = "Deal Name, Value, Expected Close Date";
						$req_fields = "deal_name, value, expected_close_date";
						$default_dupes = array("name","value");
					break;
					case "notes":
						$req_titles = "Subject";
						$req_fields = "subject";
						$default_dupes = array("subject");
					break;
					case "tasks":
						$req_titles = "Subject, Priority, Status";
						$req_fields = "subject, priority_id, status_id";
						$default_dupes = array("subject","priority id", "status id");
					break;
					case "meetings":
						$req_titles = "Subject, Start Date, End Date";
						$req_fields = "subject, start_date, end_date";
						$default_dupes = array("subject","date start", "date end");
					break;
				}
		
				$assignedusers = getAssignedUsers();
				$return = "<p class='bg-info' style='padding:15px;'><strong>Required Fields:</strong> " . $req_titles . "</p>";
				$return .= "<table class='table table-hover'><thead><tr><th class='text-right'>File Column Name</th><th>Save to Column</th><th>Include in Duplicate Check</th></tr></thead><tbody>";
				$col_number = 0;
				foreach($table_array as $col){
					
					if (in_array(strtolower($col['col1']),$default_dupes)) { $checked = " checked";	}
					else { $checked = "";}

					$return .= "<tr><td class='text-right' style='line-height:34px;'>" . $col['col1'] . "</td><td>" . $col['drop_down'] . "</td><td><input id='duplicate_col".$col_number."' name='duplicate_col".$col_number."' type='checkbox'".$checked."></td></tr>";
					$col_number++;
				}
				$return .= "</tbody></table>";
				$return .= "<input type='hidden' value='$req_fields' name='req_fields' id='req_fields' />";
				$return .= "<input type='hidden' value='$delimiter' name='file_delimiter' />";
				$return .= "<input type='hidden' value='2' name='step' id='step' />";
				$return .= "<input type='hidden' value='" . json_encode(str_replace(' ','_',$default_dupes)) ."' id='default_duplicate_fields' name='default_duplicate_fields'>";

			//do step 0: upload the file
			}elseif(strtolower(substr(trim($_FILES['csv_file']['name']), -3) == "csv")){
				$step = 0;
				$ext = strtolower(substr(trim($_FILES['csv_file']['name']), -3));
				if($_FILES['csv_file']['error'] > 0){
					$file_error = $_FILES['csv_file']['error'];
					$error.= "<li>" . $file_error . "</li>";
					//echo "file valid 1<br/>";
				}elseif($ext !== 'csv'){
					$error.= '<li>The file you uploaded is not a CSV file.</li>';
					//echo "file not valid 1<br/>";
				}

				if(!$error){

					$new_name = time().".csv";
					$this->session->set_userdata("csv_filename_session",$new_name);

					//move the file
					//$upload_path = "./uploads/".$user->id."/".basename($new_name);
					$upload_path = $upload_dir.basename($new_name);
					if(!is_dir("./../attachments/")){
						mkdir("./../attachments/", 0777, true);
						chmod("./../attachments/", 0777);
					}

					/*
					var_dump(new Person());
					$x = new Person();
					foreach($x->fields as $v){
						var_dump($v);
					}
					*/

					chmod("./../attachments/", 0777);
					if(move_uploaded_file($_FILES['csv_file']['tmp_name'], $upload_path)){
						$return = 1;
					}else{
						$return = 2;
					}
					chmod("./../attachments/", 0755);
				}
			}
		}

		//an error occured; go back to people page
		if( $return == false ){
			notify_set( array('status'=>'error', 'message'=>'Person import failed.') );

			// redirect
			redirect( 'people' );
		}


		// return results for uploading the file
		if( $return != false && $step == 0 ){
			echo json_encode(array("value"=>$return, "error"=>$error));
			exit;
		}

		// show matched fields with the uploaded csv file
		if( $return != false && $step == 1 ){
			// set flash
			//notify_set( array('status'=>'success', 'message'=>'Successfully imported people from CSV file.') );
			notify_set('');
			$link_accounts = $form_post_data['link_accounts'];
			$create_accounts = $form_post_data['create_accounts'];
			$data['link_account_checkbox'] = $link_accounts;
			$data['create_accounts_checkbox'] = $create_accounts;
			// set
			$data['people'] = $return;
			$data['module'] = $module;
			$data['step'] = 1;
			$data['nav_buttons'] = '<button class="btn btn-default" onclick="return cancel(this)">Cancel</button> <button type="submit" class="btn btn-primary">Next</button>';

			// load view
			$this->layout->view('/imports/import', $data);
		}


		// show all records to be imported
		if( $return != false && $step == 2 ){

			$link_accounts = $form_post_data['link_accounts'];
			$create_accounts = $form_post_data['create_accounts'];
			$data['link_account_checkbox'] = $link_accounts;
			$data['create_accounts_checkbox'] = $create_accounts;
			notify_set('');
			// set
			$data['people'] = $return;
			$data['module'] = $module;
			
			$data['step'] = 2;
			$data['nav_buttons'] = '';

			// load view
			$this->layout->view('/imports/import', $data);
		}

	}

	/*
	* fetch columns from csv and match against DB columns
	* process the uploaded csv file to show step 1/2
	* return an array(drop down list of the associated columns' names in the DB, field/column name passed from the csv file)
	*/
	private function step_one_table($upload_path, $drop_first_row, $module, $delimiter) {
error_reporting(0);
		ini_set('auto_detect_line_endings', true);
		//loop through the file and generate the step 2 form arrays
		if($handle = fopen($upload_path, "r")){
			$count = 0;
			$data_arr = array();


			if($delimiter != "") $data = fgetcsv($handle, 0, $delimiter);
			else $data = fgetcsv($handle, 0);
			//$data = fread($handle, filesize($upload_path));
			//$data = nl2br($data);

			//break lines
			//$rows = explode("<br />", $data);
			if($data){
				$num = count($data);
				for ($i=0; $i < $num; $i++) {
						//echo $data[$i] . " <br/>";
						$str_clean = str_replace("ï»¿", "", $data[$i]);
						$str_clean = str_replace("ÈÀ", "", $data[$i]);
						$data_arr[] = utf8_encode(trim($str_clean));
				}
			}

			//Build drop down menus for building association information
			$drop_downs = array();
			switch($module){
				case "companies":
					$dont_show = $this->dont_show_accounts;
					$sc_table = $this->sc_table_companies;
				break;
				case "people":
					$dont_show = $this->dont_show_people;
					$sc_table = $this->sc_table_people;
				break;
				case "deals":
					$dont_show = $this->dont_show_deals;
					$sc_table = $this->sc_table_deals;
				break;
				case "notes":
					$dont_show = $this->dont_show_notes;
					$sc_table = $this->sc_table_notes;
				break;
				case "tasks":
					$dont_show = $this->dont_show_tasks;
					$sc_table = $this->sc_table_tasks;
				break;
				case "meetings":
					$dont_show = $this->dont_show_meetings;
					$sc_table = $this->sc_table_meetings;
				break;
			}

			for($i=0; $i<sizeof($data_arr); $i++) {
				$drop_name = 'col'.$i;
				$dropdown = $this->table_drop_down($sc_table, $drop_name, $data_arr[$i], false, '-- do not import this field --', $dont_show);
				$drop_downs[] = $dropdown;
			}
			
			//Loop through data to create table rows
			for($i=0; $i<sizeof($data_arr); $i++) {
				$tmp = array('drop_down' => $drop_downs[$i], 'col1' => $data_arr[$i]);
				$result[$i] = $tmp;
			}
			
			
			
			return $result;
		} else {
			return false;
		}
	}

	/*
	* process the uploaded csv file to show step 2/2
	* return html styled results
	*/
	private function step_two_table($upload_path, $drop_first_row, $users, $module, $delimiter, $link_accounts="", $create_accounts="", $dupe_check) {
	error_reporting(0);
		$sync_total = 0;
		$sync_success = 0;
		$sync_fail = 0;
		$sync_duplicate = 0;
		$sync_fail_fields = array();
		$sync_fail_errors = array();
		$error_string = "";

		$user_id = $_SESSION['user']->id;
		//id
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

		$return = false;

		switch($module){
			case "companies":
				$uuid_v4_clmn = "company_id"; // the column that takes the new unique id
				$dont_show = $this->dont_show_accounts;
				$sc_table = $this->sc_table_companies;
				$special_fields = array("lead_source"=>16, "created_by"=>$user->id, "assigned_user_id"=>$user->id); // fields that take unique values under different modules
				$ddlist_values = array("account_type"=>"account_type","lead_source_id"=>"lead_source","lead_status_id"=>"lead_status"); // ie: sales_stage_id from sc_deals and sales_stage from sc_drop_down_options
				$requireds = array("company_name");
				$req_titles = array("Company Name"); // we use this to show error messages of the missing required fields
				$dup_check_where = array("created_by"=>"", "email1"=>"", "company_name"=>""); // the where statement array for the search query of duplicate records
					
			break;
			case "people":
				$uuid_v4_clmn = "people_id"; // the column that takes the new unique id
				$dont_show = $this->dont_show_people;
				$sc_table = $this->sc_table_people;
				$special_fields = array("created_by"=>$user->id, "assigned_user_id"=>$user->id); // fields that take unique values under different modules
				$ddlist_values = array("lead_source_id"=>"lead_source");
				$requireds = array("first_name", "last_name");
				$req_titles = array("First Name", "Last Name"); // we use this to show error messages of the missing required fields
				$dup_check_where = array("created_by"=>"", "email1"=>"", "last_name"=>""); // the where statement array for the search query of duplicate records
			
			break;
			case "deals":
				$uuid_v4_clmn = "deal_id"; // the column that takes the new unique id
				$dont_show = $this->dont_show_deals;
				$sc_table = $this->sc_table_deals;
				$special_fields = array("created_by"=>$user->id, "assigned_user_id"=>$user->id); // fields that take unique values under different modules
				$ddlist_values = array("sales_stage_id"=>"sales_stage"); // ie: sales_stage_id from sc_deals and sales_stage from sc_drop_down_options
				$requireds = array("name", "value", "expected_close_date");
				$req_titles = array("Deal Name", "Value", "Expected Close Date"); // we use this to show error messages of the missing required fields
				$dup_check_where = array("created_by"=>"", "name"=>"", "value"=>""); // the where statement array for the search query of duplicate records

			break;
			case "notes":
				$uuid_v4_clmn = "note_id"; // the column that takes the new unique id
				$dont_show = $this->dont_show_notes;
				$sc_table = $this->sc_table_notes;
				$special_fields = array("created_by"=>$user->id, "assigned_user_id"=>$user->id); // fields that take unique values under different modules
				$ddlist_values = array();
				$requireds = array("subject");
				$req_titles = array("Subject"); // we use this to show error messages of the missing required fields
				$dup_check_where = array("created_by"=>"", "subject"=>""); // the where statement array for the search query of duplicate records
			break;
			case "tasks":
				$uuid_v4_clmn = "task_id"; // the column that takes the new unique id
				$dont_show = $this->dont_show_tasks;
				$sc_table = $this->sc_table_tasks;
				$special_fields = array("created_by"=>$user->id, "assigned_user_id"=>$user->id); // fields that take unique values under different modules
				$ddlist_values = array("priority_id"=>"priority_id", "status_id"=>"status_id"); // ie: sales_stage_id from sc_deals and sales_stage from sc_drop_down_options
				$requireds = array("subject");
				$req_titles = array("Subject"); // we use this to show error messages of the missing required fields
				$dup_check_where = array("created_by"=>"", "subject"=>"", "priority_id"=>"", "status_id"=>""); // the where statement array for the search query of duplicate records
			break;
			case "meetings":
				//$uuid_v4_clmn = "task_id"; // the column that takes the new unique id
				$uuid_v4_clmn = "meeting_id";
				$dont_show = $this->dont_show_meetings;
				$sc_table = $this->sc_table_meetings;
				$special_fields = array("created_by"=>$user->id, "status"=>1, "assigned_user_id"=>$user->id); // fields that take unique values under different modules
				$ddlist_values = array("event_type"=>"event_type"); // ie: sales_stage_id from sc_deals and sales_stage from sc_drop_down_options
				$requireds = array("subject", "date_start", "date_end");
				$req_titles = array("Subject", "Start Date", "End Date"); // we use this to show error messages of the missing required fields
				$dup_check_where = array("created_by"=>"", "subject"=>"", "date_start"=>"", "date_end"=>""); // the where statement array for the search query of duplicate records
			break;
		}
		//var_dump($dont_show);
	
		//get a list of all the fields' (name,type) for the associated table
		$fields = $this->db->field_data($sc_table);
		$fields_array = array();
		$cn_obj = (object) array( "name"=>"company_name","type"=>"varchar","default"=>NULL,"max_length"=>"136","primary_key"=>0);
		$fields[] = $cn_obj;
		
		//loop through all the fields and build an associative array $fields_array = ("column_name" => "")
		//if the column_name is in $dont_show then skip that column
		foreach ($fields as $col_name){
			if(!in_array($col_name->name,$dont_show)) {
				$fields_array[$col_name->name] = "";
			}
		}
		if ($module == 'people') {
		$fields->company_name = "";

		$fields_array["company_name"] = "";
		}
		

		ini_set('auto_detect_line_endings', true);

		$values_to_be_shown = array();
		if (($handle = fopen($upload_path, "r")) !== FALSE) {

			//if($delimiter != "") $contents = fgetcsv($handle, 0, $delimiter);
			//else $contents = fgetcsv($handle, 0);

			$rows = 0;
			while($contents = fgetcsv($handle, 0, $delimiter)){
			//var_dump("OPEN SUCCESS");
			//read entire file
			//$contents = fread($handle, filesize($upload_path));
			//$contents = nl2br($contents);
				//handle the header
				if($rows == 0){
					$num = count($contents);
					for($j=0; $j<$num; $j++){
						if(isset($_POST['col'.$j]) && $_POST['col'.$j] != ""){
							$values_to_be_shown[] = $_POST['col'.$j];
						}else{
							$values_to_be_shown[] = "";
						}
					}
				}else{

					$error_string = "";
					$col_name = "";
					$error = 0;
					$fields_string = "";

					//reset values of the the array fields_array
					foreach ($fields_array as $column){
						$column = "";
					}

					//reset duplicate check array values
					//	foreach($dup_check_where as $k=>$v){
					foreach($dupe_check as $k=>$v){
						$dupe_check[$k] = "";
					}








					$num = count($contents);
					
					for($j=0; $j<$num; $j++){
						if($contents[$j] != "") $fields_string .= $contents[$j] . ", ";

						//values_to_be_shown[$j] holds the name of the column from DB ie: first_name, address1, postal_code
						/* substitute:
							switch($values_to_be_shown[$j]){
								case "first_name": $fname_val = $contents[$j]; break;
								... etc
							}
						*/
						if (array_key_exists($values_to_be_shown[$j], $fields_array)) {
							//if the key exists, then assign it the column value from the file
							$fields_array[$values_to_be_shown[$j]] = $contents[$j];
						}

					}
					if( $fields_string != "")  $fields_string = rtrim( $fields_string, ", " );
					
					$sync_fail_fields[] = $fields_string;

					//validate: if a required field is missing then sync fails for this record
					/* substitute
						if($lname_val == ""){
							$error = 1;
							$error_string = "Last Name, ";
						}
					*/
					for($j=0; $j<sizeof($requireds); $j++){
						if( $fields_array[$requireds[$j]] == "" ){
							$error = 1;
							$error_string .= $req_titles[$j] . ", ";
						}
					}

					// save if there were no errors
					if( $error == 0 ){
						// now
						$now = gmdate('Y-m-d H:i:s');
						$date = gmdate( 'Y-m-d');

						//init new object
						switch($module){
							case "companies": $obj = new Company(); break;
							case "people": $obj = new Person(); break;
							case "deals": $obj = new Deal(); break;
							case "notes": $obj = new Note(); break;
							case "tasks": $obj = new Task(); break;
							case "meetings": $obj = new Meeting(); break;
						}

						
						/*// Check if a company exists with the name provided
						$a = $this->db->select("company_id")->from("sc_companies")->where(array("company_name"=>$fields_array["company_name"], "deleted"=>0))->limit(1)->get();
						//$companyQuery = $this->db->select("company_name")->from("sc_companies")->where("company_name"=>$fields_array["company_name"])->get();
						
						if ($a->num_rows > 0) {
						$b = $a->result_array();
						$fields_array["company_id"]	 = $b[0]["company_id"];

						}	*/

										//loop thru all fields to
						foreach ($fields as $col){
						
							$col_name = $col->name;
							//substitute: 	$cont->first_name = $fname_val;
							
							
							if(array_key_exists($col_name, $fields_array)) {
								//if the key exists, then assign it the column value from the file

								$obj->$col_name = $fields_array[$col_name];

								//if the column is of type enum and the user passed a value not N nor Y, then set the value to default N
								if( $col->type == "enum" && strlen($fields_array[$col_name]) > 1)
									$obj->$col_name = "N";

								//in the case where the type is date ie: birthdate
								if( $col->type == "date")
								{
									if($fields_array[$col_name] == "")
									{
										$obj->$col_name = null;
									}
									else 
									{
										$obj->$col_name = gmdate('Y-m-d', strtotime($fields_array[$col_name]));
									}
								}
								//in the case where the type is datetime ie: meeting start date
								if( $col->type == "datetime")
									$obj->$col_name = gmdate('Y-m-d H:i', strtotime($fields_array[$col_name]));
							}

							 
							if(array_key_exists($col_name, $ddlist_values)) {
									
								$q = $this->db->select("drop_down_id")->from("sc_drop_down_options")->where(array("related_field_name"=>$ddlist_values[$col_name], "name"=>$fields_array[$col_name]))->get();
								if( $q->num_rows > 0 ){
									$dd_id = $q->result_array();
									$obj->$col_name = $dd_id[0]['drop_down_id'];
								}else{


									//if the user provided a name that does not exist in our database, then assign the variable to the first drop_down_id we have in the database table for that related_field_name
									$q = $this->db->select("drop_down_id")->from("sc_drop_down_options")->where(array("related_field_name"=>$ddlist_values[$col_name]))->get();
									if( $q->num_rows > 0 ){
										$dd_id = $q->result_array();
										$obj->$col_name = $dd_id[0]['drop_down_id'];
									}
									//print_r($obj->$col_name);
									//exit;
								}
							}
							
							 
							if(array_key_exists($col_name, $fields_array)) {
								//if the key exists, then assign it the column value from the file
								
								if($col_name=='company_name')
								{
									
								$a = $this->db->select("company_id")->from("sc_companies")->where(array("company_name"=>$fields_array[$col_name], "deleted"=>0))->get();
								$aa_name = $a->result_array();

								// Is there a match? Do we want to link them to this account?
								if ($a->num_rows > 0) {
								if ($module == "people") {
									if ($link_accounts == "on") {
									$obj->company_id = $aa_name[0]['company_id'];			
									}


								} 
									else {
									$obj->company_id = $aa_name[0]['company_id'];			
									}
								
								}
								// No Match - Do we want to create a company?
								else {
									
									// Create the new Company if Creating Company is enabled.
									if ($module == "people" && $create_accounts == "on" && !empty($fields_array["company_name"])) {
										$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();
										$user_id = $user->id;
										$company = new Company();
										$company->company_name = $fields_array["company_name"];
										$company->company_id = $this->uuid->v4();
										$company->modified_user_id = $user_id;
										$company->assigned_user_id = $user_id;
										$company->created_by = $user_id;
										$company->company_type = 0;
										$company->industry = 0;										
										$company->phone_work = $fields_array["phone_work"];
										$company->email1 = $fields_array["email1"];
										$company->address1 = $fields_array["address1"];
										$company->address2 = $fields_array["address2"];
										$company->city = $fields_array["city"];
										$company->province = $fields_array["province"];
										$company->postal_code = $fields_array["postal_code"];
										$company->country = $fields_array["country"];
										$company->import_datetime = gmdate("Y-m-d H:i:s");
										$company->save();
										$c_id = $company->company_id;
										$obj->company_id = $c_id;
										
										
										

									}
								}

								}
								else if($col_name=='people_id')
								{
								$name=explode(" ",$fields_array[$col_name]);
								$c = $this->db->select("people_id")->from("sc_people")->where(array("first_name"=>$name[0],"last_name"=>$name[1], "deleted"=>0))->get();
								$cc_name = $c->result_array();
								$obj->$col_name = $cc_name[0]['people_id'];
								}
								else if($col_name=='assigned_user_id')
								{
								$name=explode(" ",$fields_array[$col_name]);
								$uid = $this->db->select("upro_uacc_fk")->from("sc_user_profiles")->where(array("first_name"=>$name[0], "last_name"=>$name[1]))->get();
								$uid = $uid->result_array();
								$uu = $uid[0]['upro_uacc_fk'];
								$uuid = $this->db->select("id")->from("sc_user_accounts")->where(array("uacc_id"=>$uu, "uacc_active"=>1))->get();
								$uuid = $uuid->result_array();
								$obj->$col_name = $uuid[0]['id'];
								}
								elseif($col_name=='project_id')
								{
								$p = $this->db->select("task_id")->from("sc_tasks")->where(array("subject"=>$fields_array[$col_name], "deleted"=>0,"parent_id" =>0))->get();
								$pp_name = $p->result_array();
								$obj->$col_name = $pp_name[0]['task_id'];
								}
								elseif($col_name=='parent_id')
								{
								$p1 = $this->db->select("task_id")->from("sc_tasks")->where(array("subject"=>$fields_array[$col_name], "deleted"=>0,"parent_id" =>0))->get();
								$pp_name1 = $p1->result_array();
								$obj->$col_name = $pp_name1[0]['task_id'];
								}

								
							}
							 
					//if the column is in the duple check array, then assign it the value to check for duplicates before save
							if(array_key_exists($col_name, $dupe_check)){

								//$fields_array[$col_name] is empty then we skipped this field but it's a special field
								if(!empty($fields_array[$col_name]))
									$dupe_check[$col_name] = $fields_array[$col_name];
								else
									$dupe_check[$col_name] = $special_fields[$col_name];

							}

							//if the column was not shown to the user assign it 0 as default value or one of the presets based on type
							if(in_array($col_name,$dont_show)) {
								$obj->$col_name = NULL;
								if( $col->type == "datetime" )
									$obj->$col_name = $now;
								if( $col->type == "date" )
									$obj->$col_name = $date;
								if( $col->type == "enum" )
									$obj->$col_name = "N";
							}

							//special cases for each module
							//ie: module people $cont->lead_source_id = 16; //Other
							if(array_key_exists($col_name, $special_fields)) {
								$obj->$col_name = $special_fields[$col_name];
							}
							
							//if the column is our required unique key column ($uuid_v4_clmn)
							if( $col_name == $uuid_v4_clmn) {
								$obj->$col_name = $this->uuid->v4();
							}

						}

						// find duplicate
						$dupe_check["deleted"] = 0;
						if (!empty($dupe_check)){
							
						$this->db
							->select($uuid_v4_clmn)
							->from($sc_table)
							->where($dupe_check);
						$rs = $this->db->get();
						$duplicate_check = $rs->num_rows();
					}
					else { $duplicate_check = 0; }
						// Save new person
						if($duplicate_check == 0){
							// Save new user

							if( $obj->save() ){
								$sync_success++;
							}else{
								$sync_fail++;
								$error_string .= " Not able to save record!";
								$sync_fail_errors[] = $error_string;
							}
						}else{
							$sync_duplicate++;
						}

					}elseif($error == 1){
						$sync_fail++;
						$error_string = rtrim($error_string, ", ");
						$sync_fail_errors[] = $error_string;
					}

				}
				$rows++;
			}

			if($sync_success > 0)
				$return = "<p class='bg-success' style='padding:15px;'><strong>" . $sync_success . "</strong> records imported successfully.</p>";
			if($sync_duplicate > 0)
				$return .= "<p class='bg-info' style='padding:15px;'><strong>" . $sync_duplicate . "</strong> records skipped (duplicate).</p>";
			if($sync_fail > 0)
				$return .= "<p class='bg-danger' style='padding:15px;'><strong>" . $sync_fail . "</strong> records failed to import! <span class=\"ui-btn pull-right\" onclick=\"javascript:$('.table-striped').toggle(); return false;\" style=\"text-decoration: underline;color: rgb(107, 107, 223); cursor:pointer;\">Show Errors</span></p>";

			if($return != "" && sizeof($sync_fail_errors) > 0) $return .= "<table class='table table-striped' style='display:none;'><thead><tr><th>#</th><th>Values Provided</th><th>Error</th></tr></thead><tbody>";
			for($q=0; $q<sizeof($sync_fail_errors); $q++){
				$return .= "<tr><td>" . ($q+1) . "</td><td>" . $sync_fail_fields[$q] . "</td><td><strong>Missing:</strong> " . $sync_fail_errors[$q] . "</td></tr>";
			}
			if($return != "" && sizeof($sync_fail_errors) > 0) $return .= "</tbody></table>";
		}
		fclose($handle);
		return $return;
	}


	/**
	* import Google
	*
	* @param void
	* @return void
	*/
	private function importGoogle($access_token, $google_email){
		$error = $return = false;
		$delimiter = $drop_first_row = "";

		//go down public directory by one
		$upload_dir = './../attachments/'.$_SERVER['HTTP_HOST'].'/';

		//logedin user
		$user_id = $_SESSION['user']->id;


		$url = "https://www.google.com/m8/feeds/people/$google_email/full?access_token=$access_token&alt=json";
		$fetch = @file_get_contents($url);

		$result = json_decode($fetch, true);

		$google_name = $result['feed']['author']['0']['name']['$t'];

		$total_results = $result['feed']['openSearch$totalResults']['$t'];
		$current_page_result = $result['feed']['openSearch$startIndex']['$t'];

		$people_id =
		$people_name =
		$people_email1 =
		$people_email2 =
		$people_hphone =
		$people_mphone =
		$people_address =
		$people_note = array();
		for($i=0; $i<sizeof($result['feed']['entry']); $i++){
			$people = $result['feed']['entry'][$i];
			$split = explode("/", $person['id']['$t']);
			$people_id = end($split);

			$people_id[] = $people_id;

			if(isset($person['title']['$t']))
				$people_name[$people_id] = $person['title']['$t'];
			if(isset($person['gd$email']['0']['address']))
				$people_email1[$people_id] = $person['gd$email']['0']['address'];
			if(isset($person['gd$email']['1']['address']))
				$people_email2[$people_id] = $person['gd$email']['1']['address'];

			if(isset($person['gd$phoneNumber']['0']['$t']))
				$people_hphone[$people_id] = $person['gd$phoneNumber']['0']['$t'];
			if(isset($person['gd$phoneNumber']['1']['$t']))
				$people_mphone[$people_id] = $person['gd$phoneNumber']['1']['$t'];

			if(isset($person['gd$postalAddress']['0']['$t']))
				$people_address[$people_id] = preg_replace("/\s+/", " ", $person['gd$postalAddress']['0']['$t']); //replace all multiple white-spaces, tabs and new-lines with just one
			if(isset($person['content']['$t']))
				$people_note[$people_id] = preg_replace("/\s+/", " ", $person['content']['$t']); //replace all multiple white-spaces, tabs and new-lines with just one
		}

		$where_arr  = array('uacc_id <>' => $user_id);
		$users = $this->flexi_auth->get_users_query(array("id,CONCAT(first_name, ' ', last_name) AS name", FALSE), $where_arr)->result_array();

		$return = $this->process_import_gmail($user_id, $google_name, $google_email, $access_token, $people_id, $people_name, $people_email1, $people_email2, $people_hphone, $people_mphone, $people_address, $people_note, $users);
		if($return != ""){
			//save user's google info into sc_user_profiles
			$this->load->model("general");
			$gInfoSaved = $this->general->addUserGoogleInfo($user_id, $google_name, $google_email, $access_token);

			//process google calendar
			$return .= $this->process_import_google_calendar($user_id, $gInfoSaved);

		}



		//an error occured; go back to people page
		if( $return == false ){
			notify_set( array('status'=>'error', 'message'=>'Google import failed.') );

			// redirect
			redirect( 'users/settings/sync-google' );
		}

		// show all records to be imported

		notify_set('');
		// set
		$data['people'] = $return;
		$data['step'] = "gmail";
		$data['nav_buttons'] = '';

		// load view
		$this->layout->view('/imports/import', $data);

	}

	private function process_import_gmail($user_id, $google_name, $google_email, $access_token, $people_id, $people_name, $people_email1, $people_email2, $people_hphone, $people_mphone, $people_address, $people_note, $users){
		$return = "";
		$sync_total = sizeof($people_id);
		$sync_success = 0;
		$sync_fail = 0;
		$sync_duplicate = 0;
		$sync_fail_fields = array();
		$sync_fail_errors = array();

		//FOR people, REQUIRED FIELDS ARE LAST NAME AND EMAIL 1
		for($i=0; $i<sizeof($people_id); $i++){
			$error_string = "";
			$id_val = $fname_val = $lname_val = $job_val = $acc_val = $birth_val = $hphone_val = $wphone_val = $mphone_val = $email1_val = $email2_val = $add1_val = $add2_val = $city_val = $prov_val = $postal_val = $country_val = $desc_val = "";
			$error = 0;

			$id_val = $people_id[$i];
			if(isset($people_name[$id_val])){
				$fname_val = $people_name[$id_val];
				if($split = explode(" ", $people_name[$id_val])){
					$fname_val = $split[0];
					for($x=1;$x<sizeof($split);$x++){
						$lname_val = $split[$x] . " ";
					}
					if($lname_val != "")
						$lname = rtrim($lname_val, " ");
				}
			}

			if($lname_val == ""){
				$error = 1;
				$error_string = "Last Name, ";
			}

			if(isset($people_email1[$id_val])){
				$email1_val = $people_email1[$id_val];
			}else{
				$error = 1;
				$error_string .= "Email1, ";
			}

			if(isset($people_email1[$id_val]))
				$email1_val = $people_email1[$id_val];

			if(isset($people_email2[$id_val]))
				$email2_val = $people_email2[$id_val];

			if(isset($people_hphone[$id_val]))
				$hphone_val = $people_hphone[$id_val];

			if(isset($people_mphone[$id_val]))
				$mphone_val = $people_mphone[$id_val];

			if(isset($people_address[$id_val]))
				$add1_val = $people_address[$id_val];

			if(isset($people_note[$id_val]))
				$desc_val = $people_note[$id_val];


			// save if there were no errors
			if( $error == 0 ){
				// now
				$now = gmdate('Y-m-d H:i:s');

				//id
				$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

				$cont = new Person();

				// Enter values into required fields
				$cont->people_id = $this->uuid->v4();
				//$cont->date_modified = $now;
				$cont->created_by = $user->id;
				//$cont->assigned_user_id = $post['assigned_user_id'];
				$cont->lead_source_id = 16; //Other
				$cont->job_title = "NA";
				$cont->company_id = "NA";
				//$cont->birthdate = $post['birthdate'];
				$cont->first_name = $fname_val;
				$cont->last_name = $lname_val;
				//$cont->phone_work = $post['phone_work'];
				$cont->phone_home = $hphone_val;
				$cont->phone_mobile = $mphone_val;
				$cont->email1 = $email1_val;
				$cont->email2 = $email2_val;
				$cont->address1 = $add1_val;
				//$cont->address2 = $post['address2'];
				//$cont->city = $post['city'];
				//$cont->province = $post['province'];
				//$cont->do_not_call = $post['do_not_call'];
				//$cont->email_opt_out = $post['email_opt_out'];
				//$cont->postal_code = $post['postal_code'];
				//$cont->country = $post['country'];
				$cont->description = $desc_val;
				$cont->google_id = $id_val;


				// find duplicate
				$this->db
					->select('people_id')
					->from('sc_people')
					->where('created_by', $user->id)
					->where('email1', $email1_val)
					->where('first_name', $fname_val)
					->where('last_name', $lname_val);
				$rs = $this->db->get();
				$duplicate_check = $rs->num_rows();


				// Save new person
				if($duplicate_check == 0){
					// Save new user
					if( $cont->save() ){
						$sync_success++;
					}else{
						$sync_fail++;
						$error_string .= " Not able to save record!";
						$sync_fail_fields[] = $fname_val . ", " . $lname_val . ", " . $hphone_val . ", " . $mphone_val . ", " . $email1_val . ", " . $email2_val . ", " . $add1_val . ", " . $desc_val;
						$sync_fail_errors[] = $error_string;
					}
				}else{
					$sync_duplicate++;
				}
			}else{
				$sync_fail++;
				$error_string = rtrim($error_string, ", ");
				$sync_fail_fields[] = $fname_val . ", " . $lname_val . ", " . $hphone_val . ", " . $mphone_val . ", " . $email1_val . ", " . $email2_val . ", " . $add1_val . ", " . $desc_val;
				$sync_fail_errors[] = $error_string;
			}
		}

		if($sync_success > 0)
			$return .= "<p class='bg-success' style='padding:15px;'><strong>" . $sync_success . "</strong> people records imported successfully</p>";
		if($sync_duplicate > 0)
			$return .= "<p class='bg-info' style='padding:15px;'><strong>" . $sync_duplicate . "</strong> people records skipped (duplicate)</p>";
		if($sync_fail > 0)
			$return .= "<p class='bg-danger' style='padding:15px;'><strong>" . $sync_fail . "</strong> people records failed to import! <span class=\"ui-btn pull-right\" onclick=\"javascript:$('.table-striped').toggle(); return false;\" style=\"text-decoration: underline;color: rgb(107, 107, 223); cursor:pointer;\">Show Errors</span></p>";

		if($return != "" && sizeof($sync_fail_errors) > 0) $return .= "<table class='table table-striped' style='display:none;'><thead><tr><th>#</th><th>Values Provided</th><th>Error</th></tr></thead><tbody>";
		for($j=0; $j<sizeof($sync_fail_errors); $j++){
			$return .= "<tr><td>" . ($j+1) . "</td><td>" . $sync_fail_fields[$j] . "</td><td><strong>Missing:</strong> " . $sync_fail_errors[$j] . "</td></tr>";
		}
		if($return != "" && sizeof($sync_fail_errors) > 0) $return .= "</tbody></table>";
		return $return;
	}


	private function process_import_google_calendar($user_id, $json){
		$return = "";
		$sync_total = 0;
		$sync_success = 0;
		$sync_fail = 0;
		$sync_duplicate = 0;
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

		$sync_total = sizeof($json['items']);

		//var_dump($json);
		//$event_id = $create_date = $update_date = $start_date = $end_date = $description = $location = $creator_email = array();
		for($i=0; $i<sizeof($json['items']); $i++){

			//import non-cancelled events
			if($json['items'][$i]['status'] != "cancelled"){

				// new
				$meetg = new Meeting();

				// Enter values into required fields
				$meetg->task_id = $this->uuid->v4();
				$meetg->date_start = gmdate('Y-m-d H:i:s', strtotime($json['items'][$i]['start']['dateTime']));
				$meetg->date_end = gmdate('Y-m-d H:i:s', strtotime($json['items'][$i]['end']['dateTime']));
				$meetg->subject = $json['items'][$i]['summary'];
				$meetg->created_by = $user->id;
				//$meetg->assigned_user_id = $post['assigned_user_id'];
				if(isset($json['items'][$i]['location']))
					$meetg->location = $json['items'][$i]['location'];
				$meetg->event_type = 1;
				//$meetg->company_id = $post['company_id'];
				//$meetg->people_id = $post['people_id'];
				$meetg->status = 1;
				if(!isset($json['items'][$i]['description'])) $meetg->description = $json['items'][$i]['summary'];
				else $meetg->description = $json['items'][$i]['description'];

				// find duplicate
				$subject = $json['items'][$i]['summary'];
				$this->db->select('task_id')->from('sc_meetings')->where('subject', $subject);
				$rs = $this->db->get();
				$duplicate_check = $rs->num_rows();


				// Save new user
				if($duplicate_check == 0){
					if( $meetg->save() )
						$sync_success++;
					else
						$sync_fail++;
				}else{
					$sync_duplicate++;
				}
			}
		}

		if($sync_success > 0)
			$return .= "<p class='bg-success' style='padding:15px;'><strong>" . $sync_success . "</strong> calendar records imported successfully</p>";
		if($sync_duplicate > 0)
			$return .= "<p class='bg-info' style='padding:15px;'><strong>" . $sync_duplicate . "</strong> calendar records skipped (duplicates)! </p>";
		if($sync_fail > 0)
			$return .= "<p class='bg-danger' style='padding:15px;'><strong>" . $sync_fail . "</strong> calendar records failed to import! </p>";

		return $return;
	}


	//import_gmail_table($people_id, $people_name, $people_email1, $people_email2, $people_hphone, $people_mphone, $people_address, $users);
	private function import_gmail_table($user_id, $google_name, $google_email, $access_token, $people_id, $people_name, $people_email1, $people_email2, $people_hphone, $people_mphone, $people_address, $users) {
		$count = 0;
		$return = false;

		//FOR people, REQUIRED FIELDS ARE LAST NAME AND EMAIL 1
		for($i=0; $i<sizeof($people_id); $i++){
			//echo $j . ": " . $line[$j] . "<br/>";

			$id_val = $fname_val = $lname_val = $job_val = $acc_val = $birth_val = $hphone_val = $wphone_val = $mphone_val = $email1_val = $email2_val = $add1_val = $add2_val = $city_val = $prov_val = $postal_val = $country_val = $desc_val = "";

			$id_val = $people_id[$i];
			if(isset($people_name[$id_val])){
				$fname_val = "value='" . $people_name[$id_val] . "'";
				if($split = explode(" ", $people_name[$id_val])){
					$fname_val = "value='" . $split[0] . "'";
					for($x=1;$x<sizeof($split);$x++){
						$lname_val = $split[$x] . " ";
					}
					if($lname_val != ""){
						$lname_val = rtrim($lname_val, " ");
						$lname_val = "value='" . $lname_val . "'";
					}
				}
			}

			if(isset($people_email1[$id_val]))
				$email1_val = "value='" . $people_email1[$id_val] . "'";

			if(isset($people_email2[$id_val]))
				$email2_val = "value='" . $people_email2[$id_val] . "'";

			if(isset($people_email1[$id_val]))
				$email1_val = "value='" . $people_email1[$id_val] . "'";

			if(isset($people_hphone[$id_val]))
				$hphone_val = "value='" . $people_hphone[$id_val] . "'";

			if(isset($people_mphone[$id_val]))
				$mphone_val = "value='" . $people_mphone[$id_val] . "'";

			if(isset($people_address[$id_val]))
				$add1_val = $people_address[$id_val];




			$return .= "
									<div class='form-group'>
										<div class='row'>
											<div class='form-group col-sm-12'>
												<label for='lead_source_id_$i'>Lead Source</label>
												<input type='text' name='lead_source_id_$i' id='lead_source_id_$i' class='lead_source_id form-control' placeholder='Enter lead source' $id_val>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='job_title_$i'>Job Title</label>
												<input type='text' name='job_title_$i' id='job_title_$i' class='job_title form-control' placeholder='Enter job title' $job_val required>
											</div>
											<div class='form-group col-sm-6'>
												<label for='company_$i'>Company</label>
												<input type='text' name='company_$i' id='company_$i' class='company form-control' placeholder='Enter company no' $acc_val>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='assigned_user_id_$i'>Assigned User</label>
												<select id='assigned_user_id_$i' name='assigned_user_id_$i' class='assigned_user_id form-control' size='1'>
													<option value='0'>Please select</option>";
													foreach($users as $user) :
													$return .= '
													<option value="' . $user['id'] . '">
														' . $user[ 'name'] . '
													</option>';
													endforeach;
													$return .= "
												</select>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='first_name_$i'>First Name</label>
												<input type='text' name='first_name_$i' id='first_name_$i' class='first_name form-control' placeholder='Enter first name' $fname_val>
											</div>
											<div class='form-group col-sm-6'>
												<label for='last_name_$i'>Last Name</label>
												<input type='text' name='last_name_$i' id='last_name_$i' class='last_name form-control' placeholder='Enter last name' $lname_val required>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='phone_work_$i'>Phone Work</label>
												<input type='text' name='phone_work_$i' id='phone_work_$i' class='phone_work form-control' placeholder='Enter work phone' $wphone_val>
											</div>
											<div class='form-group col-sm-6'>
												<label for='phone_home_$i'>Phone Home</label>
												<input type='text' name='phone_home_$i' id='phone_home_$i' class='phone_home form-control' placeholder='Enter home phone' $hphone_val>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='phone_mobile_$i'>Phone Mobile</label>
												<input type='text' name='phone_mobile_$i' id='phone_mobile_$i' class='phone_mobile form-control' placeholder='Enter mobile no' $mphone_val>
											</div>
											<div class='form-group col-sm-6'>
												<label for='birthdate_$i'>Birthdate</label>
												<input type='text' name='birthdate_$i' id='birthdate_$i' class='birthdate form-control datetime' placeholder='Enter birth date' $birth_val>
											</div>
										</div>

									   <div class='row'>
											<div class='form-group col-sm-6'>
												<label for='email1_$i'>Email 1</label>
												<input type='text' name='email1_$i' id='email1_$i' class='form-control' placeholder='Enter email 1' $email1_val required>
											</div>
											<div class='form-group col-sm-6'>
												<label for='email2_$i'>Email 2</label>
												<input type='text' name='email2_$i' id='email2_$i' class='form-control' placeholder='Enter email 2' $email2_val>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='address1_$i'>Address 1</label>
												<textarea name='address1_$i' id='address1_$i' class='form-control' placeholder='Enter address 1'>$add1_val</textarea>
											</div>
											<div class='form-group col-sm-6'>
												<label for='address2_$i'>Address 2</label>
												<textarea name='address2_$i' id='address2_$i' class='form-control' placeholder='Enter address 2'>$add2_val</textarea>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='city_$i'>City</label>
												<input type='text' name='city_$i' id='city_$i' class='form-control' placeholder='Enter city' $city_val>
											</div>
											<div class='form-group col-sm-6'>
												<label for='province_$i'>Province</label>
												<input type='text' name='province_$i' id='province_$i' class='form-control' placeholder='Enter province' $prov_val>
											</div>
										</div>
										<div class='row'>
											<div class='form-group col-sm-6'>
												<label for='postal_code_$i'>Postal Code</label>
												<input type='text' name='postal_code_$i' id='postal_code_$i' class='form-control' placeholder='Enter postal code' maxlength='10' $postal_val>
											</div>
											<div class='form-group col-sm-6'>
												<label for='country_$i'>Country</label>
												<input type='text' name='country_$i' id='country_$i' class='form-control' placeholder='Enter country name' $country_val>
											</div>
										</div>

										<div class='row'>
											<div class='form-group col-sm-12'>
												<label for='description_$i'>Comments/Description</label>
												<textarea name='description_$i' id='description_$i' class='description form-control' rows='5' placeholder='Enter comments/descriptions'>$desc_val</textarea>
											</div>
										</div>
										<div class='form-group'>
											<label for='ignore_$i'>Ignore This Person</label>
											<input type='checkbox' name='ignore_$i' id='ignore_$i' >
										</div>
									</div><hr/>";
									$count++;
					}

		if($return != false){
			$return .= "<input type='hidden' name='countRows' value='$count'><input type='hidden' id='step' name='step' value='gmail'><input type='hidden' id='gname' name='gname' value='" . addslashes($google_name) . "'><input type='hidden' id='gemail' name='gemail' value='" . addslashes($google_email) . "'><input type='hidden' id='atkn' name='atkn' value='$access_token'>";
		}
		return $return;
	}

	//Define DB table names as drop down options
	//Param: $table=sc_people, $name=col3, $val=ParentId, $default_val=false, $default_text=-- do not import this field --, $disallowed_fields=array("don't show this column, not this too ...etc)
	private function table_drop_down($table, $name='table', $val=false, $default_val=false, $default_text=false, $disallowed_fields=false) {

		$out = '<select class="form-control" name="'.$name.'" id="'.$name.'" onChange="defaultDupeCheck(\''.$name.'\');">';
		if($default_text) {
			$out.= '<option value="'.$default_val.'">'.$default_text.'</option>';
		}
		$add_company = 0;
		if ($table == "sc_people") {
		$out.= '<option value="company_name">Company Name</option>';	
		}
		
		$fields = $this->db->list_fields($table);

		foreach ($fields as $field){
			$col_name = $field;
			$formatted_name = ucwords(str_replace("_"," ",strtolower($col_name)));
			$match_name = ucwords(str_replace("_"," ",strtolower($val)));

			if(!in_array($col_name,$disallowed_fields)) {
				if(trim($match_name) == trim($formatted_name)) {
					$extra = ' selected="selected"';
				}else{
					$extra = false;
				}
				$out.= '<option value="'.$col_name.'"'.$extra.'>'.$formatted_name.'</option>';
			}elseif($col_name == "sales_stage_id" || $col_name == "priority_id" || $col_name == "status_id"){
				if(trim($match_name) == trim($formatted_name)) {
					$extra = ' selected="selected"';
				}else{
					$extra = false;
				}
				$out.= '<option value="'.$col_name.'"'.$extra.'>'.$formatted_name.'</option>';

			}
		}

		$out.= '</select>';

		return $out;
	}

}

/* End of file people.php */
/* Location: ./application/controllers/people.php */