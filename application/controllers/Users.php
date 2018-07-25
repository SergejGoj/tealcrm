<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//session_start();
// -----------------------------------------------------------------------
/**
 * Users Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Users extends App_Controller {
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

	/**
	 * remap
	 *
	 * @param string $method
	 */
	function _remap($method)
	{
		// auth check
		if ( ! $this->flexi_auth->is_logged_in() )
		{
			redirect('auth/login');
		}

		// check method exists again
		if(method_exists($this, $method)){
			// remove classname and method name form uri
			call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
		}else{
		    // error
			show_404(sprintf('controller method [%s] not implemented!', $method));
		}
	}

	/**
	 * index, redirect to dashboard, user session protected
	 *
	 * @url <site>/company
	 */
	function subscription()
	{
		// redirect
		$data = array();

		// load view
		$this->layout->view('/users/subscription', $data);
//		redirect( '/users/dashboard' );
	}

	/**
	 * index, redirect to dashboard, user session protected
	 *
	 * @url <site>/company
	 */
	function index()
	{
		// redirect
		$data = array();

		// load view
		$this->layout->view('/users/dashboard', $data);
//		redirect( '/users/dashboard' );
	}

	/**
	 * dashboard,user session protected
	 *
	 * @url <site>/users/dashboard
	 */
	function dashboard()
	{
		// data
		$data = array();

	    // **************************
	    // GET STATISTICS

	    // - total value of open deals in pipeline
	    $result = $this->db->query("SELECT sum(value) as total FROM sc_deals WHERE sales_stage_id != 87 and sales_stage_id != 88 and deleted = 0")->result();
		$data['total_value_pipeline'] = $result[0]->total;

	    // - total closed deals this month
	    $result = $this->db->query("SELECT sum(value) as total FROM sc_deals WHERE sales_stage_id = 87 and month(expected_close_date) = month(now())  and deleted = 0")->result();
		$data['total_closed_deals'] = $result[0]->total;

	    // - new people this month
	    $result = $this->db->query("SELECT count(*) as total FROM sc_people WHERE month(date_entered) = month(now())  and deleted = 0")->result();
		$data['new_people'] = $result[0]->total;

	    // - new companies this month
	    $result = $this->db->query("SELECT count(*) as total FROM sc_companies WHERE month(date_entered) = month(now())  and deleted = 0")->result();
		$data['new_accounts'] = $result[0]->total;


		// **************************
		// GET ACTIVITIES

		//************* Meetings
		$meetings = new Meeting();

	    // show newest first
	    $meetings->order_by('date_start', 'DESC');

	    // limit 5 records
	    $meetings->limit(5);

		// where clause
		$array = array('deleted' => 0, 'status !=' => 103, 'event_type' => 93,'date_start <=' => date('Y-m-d 23:59:59',now()));

		// show only meetings that are MEETINGS
		$meetings->where($array);

	    // select
	    $meetings->select('subject,meeting_id,date_start,date_end,status')->get();


		//echo $this->db->last_query();
		$data['meetings'] = $meetings;


		//************* Calls
		$calls = new Meeting();

	    // show newest first
	    $calls->order_by('date_start', 'DESC');

	    $calls->limit(5);

		// where clause
		$array = array('deleted' => 0, 'status !=' => 103, 'event_type' => 95, 'date_start <=' => date('Y-m-d 23:59:59',now()));

		// show only meetings that are MEETINGS
		$calls->where($array);

	    // select
	    $calls->select('subject,meeting_id,date_start,date_end,event_type,status')->get();
	//echo $this->db->last_query();
		$data['calls'] = $calls;

		//************* Tasks
		$tasks = new Task();

	    // show newest first
	    $tasks->order_by('due_date', 'DESC');

	    $tasks->limit(5);

		// where clause
		$array = array('deleted' => 0, 'status_id !=' => 103, 'due_date <=' => date('Y-m-d 23:59:59',now()));

		// show only meetings that are MEETINGS
		$tasks->where($array);

	    // select
	    $tasks->select('subject,task_id,due_date,status_id,priority_id')->get();
	//echo $this->db->last_query();
		$data['tasks'] = $tasks;

		// **************************
		// GET CHART DATA

	//	$query = "SELECT sales_stage_id, value FROM sc_deals WHERE month(expected_close_date) = month(NOW()) and sales_stage_id = 61";

		//feeds value
		$this->load->model("feed_list");
		//$feed_list =
		list($data['feed_list'],$data['feedcount']) = $this->feed_list->morefeedsload("0");



// Get the Deal Won list and populate the JSON
		$sql = "SELECT MONTH(expected_close_date) as month, MONTHNAME(expected_close_date) as name_month, YEAR(expected_close_date), sum(value) as value
				FROM sc_deals
				WHERE expected_close_date >= CURDATE() - INTERVAL 1 YEAR AND sales_stage_id = 87
				GROUP BY MONTHNAME(expected_close_date),YEAR(expected_close_date),MONTH(expected_close_date)
				ORDER BY MONTH(expected_close_date)";

		$query = $this->db->query($sql);

		$dealswon_set = array("1"=>0, "2"=>0, "3"=>0, "4"=>0, "5"=>0, "6"=>0, "7"=>0, "8"=>0, "9"=>0, "10"=>0, "11"=>0, "12"=>0);
		$deals_set = "";
		foreach ($query->result() as $row)
		{
			$dealswon_set[$row->month] = $row->value;
		}

		foreach($dealswon_set as $key => $value){
			$deals_set =  $deals_set.'['.$key.', '.$value.'],';
		}
		$dealswon_set = array_values($dealswon_set);

		$arr = $deals_set;
		//$arr = json_encode($dealswon_set);

		$deals_set = rtrim($deals_set, ",");
		$data['deals_lost'] = $deals_set;

		// PRODUCT PRODUCT DATA


		$sql = "select lead_source_id, count(*) as num FROM sc_companies GROUP BY lead_source_id";

		$query = $this->db->query($sql);

		$leadcompanies = "";
		foreach ($query->result() as $row)
		{
			$leadcompanies = $leadcompanies.'{ label:"' . $_SESSION['drop_down_options'][$row->lead_source_id]['name'] . '", data: '.$row->num.', color: "#'. $this->random_color() .'"},';
		}

		$data['pie_chart'] = $leadcompanies;


		// load view
		$this->layout->view('/users/dashboard', $data);

	}

	/**
	 * profile,user session protected
	 *
	 * @url <site>/users/profile
	 */
	function profile()
	{
		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();

		//pass a random string to mask user's profile image
		//ie: 3dbc33def75830b6bb32ee30b1b5ec1e
		$data['hashImg_navbar'] = md5(uniqid());

		$this->load->library('session');
		$this->session->set_userdata(
				array(
					//store the random string in a session as key
					$data['hashImg_navbar']=>
						//store the user profile image ( from upro_filename_original ) as the value for the session
						//if the user has not uploaded an image use the system default image default.png
						(empty($user['upro_filename_original']) ? 'default.png' : $user['upro_filename_original'])
				)
			);

		// load view
		$this->layout->view('/users/profile', $data);
	}



public function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

public function random_color() {
    return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
}



	 /**
	* import
	*
	* @param void
	* @return void
	*/
	public function import(){

		$data = array();
		$this->layout->view('/users/import', $data);



	}

	public function importcsv(){

/*
		// get POST values
		$objPost = (object) $this->input->post('arr_of_fields');

		$fh_csv = fopen('YOUR CSV FILE');
		$fh_tmp = fopen('YOUR TEMP FILE');

		while( false !== ($line = fgets($fh_csv)) )

		    $enclosed = ''; // or whatever your field is enclosed with
		    $delimiter = ','; // or whatever your delimiter is

		    $columns  = explode($enclosed.$delimiter.$enclosed, trim($line, $enclosed));

		    // the $objPost->field_X signifies the index for that field [0,1,2,3,+++]
		    $data = array(
		       'field_1' => trim(@$columns[@$objPost->field_1], $enclosed),
		       'field_2' => trim(@$columns[@$objPost->field_2], $enclosed),
		       'field_3' => trim(@$columns[@$objPost->field_3], $enclosed),
		       'field_4' => trim(@$columns[@$objPost->field_4], $enclosed),
		    );

		    // write line to temp csv file, tab delimited with new line
		    $str = implode("\t", $data) . "\n";

		    @fwrite($fh_tmp, $str); // write line to temp file
		}
		@fclose($fh_csv);
		@fclose($fh_tmp);

		// import from temp csv file into database
		$sql    = sprintf("LOAD DATA LOCAL INFILE '%s'
		            INTO TABLE `%s`
		            FIELDS TERMINATED BY '\\t'
		            LINES TERMINATED BY '\\n'
		            (field_1, field_2, field_3, field_4)",
		            "TEMP FILE",
		            "DATABASE TABLE NAME");

		$query  = $this->db->query( $sql );

		// delete temp file
		@unlink("TEMP FILE");
*/

	}



	private function step_one_table($upload_path, $delimiter="", $drop_first_row) {
		//loop through the file and generate the step 2 form arrays
		if($handle = fopen($upload_path, "r")){
			$count = 0;
			$columns = 0;
			$data_arr = array();

			if($delimiter != "") $data = fgetcsv($handle, 0, $delimiter);
			else $data = fgetcsv($handle, 0);

			//loop through all the values of the file to make sure it's not corrupted text filed. If so change new lines with breaks and check
			foreach($data as $value){
				$new_line_break = strpos(nl2br($value), "<br />");
				if($new_line_break > 0){
					$value = trim(substr($value,0,$new_line_break));
					$data_arr[] = $value;
					$columns++;
					break;
				}else{
					if(strlen($value) > 1)
					$data_arr[] = $value;
					$columns++;
				}
			}

			//Build drop down menus for building association information
			$drop_downs = array();
			$dont_show = array('id', 'people_id', 'date_entered', 'date_modified', 'modified_user_id', 'assigned_user_id', 'created_by', 'salutation_id', 'lead_source_id', 'reports_to_id', 'do_not_call', 'email_opt_out', 'import_datetime', 'csv_file_name', 'google_id', 'google_access_token');
			for($i=0; $i<sizeof($data_arr); $i++) {
				$drop_name = 'col'.$i;
				$dropdown = $this->table_drop_down('sc_people', $drop_name, $data_arr[$i], false, '-- do not import this field --', $dont_show);
				$drop_downs[] = $dropdown;
			}

			//Loop through data to create table rows
			for($i=0; $i<sizeof($data_arr); $i++) {
				$tmp = array('drop_down' => $drop_downs[$i], 'col1' => $data_arr[$i]);
				$result[$i] = $tmp;
			}
			return array($result, $columns);
		} else {
			return array(false, false);
		}
	}

	private function step_two_table($upload_path, $delimiter="", $drop_first_row, $users) {
		$sync_total = 0;
		$sync_success = 0;
		$sync_fail = 0;
		$sync_fail_fields = array();
		$sync_fail_errors = array();


		$row = 1;
		$return = false;
		$this->load->library("session");


		//FOR people, REQUIRED FIELDS ARE LAST NAME AND EMAIL 1

		$num_clmns = $this->session->userdata('csv_colmns_session');
		$count = 0;
		$i = $num_clmns;
		//var_dump($num_clmns);
		$values_to_be_shown = array();
		if (($handle = fopen($upload_path, "r")) !== FALSE) {
			//read entire file
			$contents = fread($handle, filesize($upload_path));
			$contents = nl2br($contents);

			//break lines
			$rows = explode("<br />", $contents);

			//loop through the lines
			$sync_total = sizeof($rows);
			for($i=0; $i<sizeof($rows); $i++){
				$error_string = "";
				$error = 0;
				$fields_string = "";
				$id_val = $fname_val = $lname_val = $job_val = $acc_val = $birth_val = $hphone_val = $wphone_val = $mphone_val = $email1_val = $email2_val = $add1_val = $add2_val = $city_val = $prov_val = $postal_val = $country_val = $desc_val = "";

				//explode line by , to loop through columns
				$line = explode(",", $rows[$i]);

				//match submitted columns with preset (our) columns
				//if not 16 columns then the file is corrupted
				if($i == 0 && sizeof($line) == 16){
					//echo "<h2>";

					for($j=0; $j<sizeof($line); $j++){
						if(isset($_POST['col'.$j]) && $_POST['col'.$j] != ""){
							$values_to_be_shown[] = $_POST['col'.$j];
						}else{
							$values_to_be_shown[] = "";
						}
					}

				//if not 16 columns then the file is corrupted
				}elseif($i > 0 && sizeof($line) == 16){
					//var_dump($line);
					for($j=0; $j<sizeof($line); $j++){
						if($line[$j] != "") $fields_string .= $line[$j] . ", ";
						switch($values_to_be_shown[$j]){
							//case "id": $id_val = 16; break; //Other
							case "first_name": $fname_val = $line[$j]; break;
							case "last_name": $lname_val = $line[$j]; break;
							case "job_title": $job_val = $line[$j]; break;
							case "company": $acc_val = $line[$j]; break;
							case "birthdate": $birth_val = $line[$j]; break;
							case "phone_home": $hphone_val = $line[$j]; break;
							case "phone_work": $wphone_val = $line[$j]; break;
							case "phone_mobile": $mphone_val = $line[$j]; break;
							case "email1": $email1_val = $line[$j]; break;
							case "email2": $email2_val = $line[$j]; break;
							case "address1": $add1_val = $line[$j]; break;
							case "address2": $add2_val = $line[$j]; break;
							case "city": $city_val = $line[$j]; break;
							case "province": $prov_val = $line[$j]; break;
							case "postal_code": $postal_val = $line[$j]; break;
							case "country": $country_val = $line[$j]; break;
							case "description": $desc_val = $line[$j]; break;
							case "": break;
						}
					}
					if( $fields_string != "")  $fields_string = rtrim( $fields_string, ", " );
					$sync_fail_fields[] = $fields_string;

					if($lname_val == ""){
						$error = 1;
						$error_string = "Last Name, ";
					}

					if($job_val == ""){
						$error = 1;
						$error_string .= "Job Title, ";
					}

					if($email1_val == ""){
						$error = 1;
						$error_string .= "Email1, ";
					}

					// save if there were no errors
					if( $error == 0 ){
						// now
						$now = date('Y-m-d H:i:s');

						// birthdate
						$birth_val = date('Y-m-d', strtotime($birth_val));

						$user_id = $this->flexi_auth->get_user_id();
						//uacc_uid
						$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

						$split = explode("/", $upload_path);
						$csv_file_name = end($split);

						$cont = new Person();

						// Enter values into required fields
						$cont->people_id = $this->uuid->v4();
						//$cont->date_modified = $now;
						$cont->created_by = $user->uacc_uid;
						$cont->assigned_user_id = $user->uacc_uid;
						$cont->lead_source_id = 16; //Other
						$cont->job_title = $job_val;
						$cont->company = $acc_val;
						$cont->birthdate = $birth_val;
						$cont->first_name = $fname_val;
						$cont->last_name = $lname_val;
						$cont->phone_work = $wphone_val;
						$cont->phone_home = $hphone_val;
						$cont->phone_mobile = $mphone_val;
						$cont->email1 = $email1_val;
						$cont->email2 = $email2_val;
						$cont->address1 = $add1_val;
						$cont->address2 = $add2_val;
						$cont->city = $city_val;
						$cont->province = $prov_val;
						//$cont->do_not_call = $post['do_not_call'];
						//$cont->email_opt_out = $post['email_opt_out'];
						$cont->company = "NA";
						$cont->postal_code = $postal_val;
						$cont->country = $country_val;
						$cont->description = $desc_val;
						$cont->import_datetime = $now;
						$cont->csv_file_name = $csv_file_name;

						// Save new user
						if( $cont->save() ){
							$sync_success++;
						}else{
							$sync_fail++;
							$error_string .= " Not able to save record!";
							$sync_fail_errors[] = $error_string;

						}
					}elseif($error == 1){
						$sync_fail++;
						$error_string = rtrim($error_string, ", ");
						$sync_fail_errors[] = $error_string;
					}
				}
			}

			if($sync_success > 0)
				$return = "<p class='bg-success' style='padding:15px;'><strong>" . $sync_success . "</strong> records synced successfully</p>";
			if($sync_fail > 0)
				$return .= "<p class='bg-danger' style='padding:15px;'><strong>" . $sync_fail . "</strong> records failed to sync! <span class=\"ui-btn pull-right\" onclick=\"javascript:$('.table-striped').toggle(); return false;\" style=\"text-decoration: underline;color: rgb(107, 107, 223); cursor:pointer;\">Show Errors</span></p>";

			if($return != "" && sizeof($sync_fail_errors) > 0) $return .= "<table class='table table-striped' style='display:none;'><thead><tr><th>#</th><th>Values Provided</th><th>Error</th></tr></thead><tbody>";
			for($q=0; $q<sizeof($sync_fail_errors); $q++){
				$return .= "<tr><td>" . ($q+1) . "</td><td>" . $sync_fail_fields[$q] . "</td><td><strong>Missing:</strong> " . $sync_fail_errors[$q] . "</td></tr>";
			}
			if($return != "" && sizeof($sync_fail_errors) > 0) $return .= "</tbody></table>";
		}

		fclose($handle);
		return $return;
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
				$now = date('Y-m-d H:i:s');

				//uacc_uid
				$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

				$cont = new Person();

				// Enter values into required fields
				$cont->people_id = $this->uuid->v4();
				//$cont->date_modified = $now;
				$cont->created_by = $user->uacc_uid;
				$cont->assigned_user_id = $user->uacc_uid;
				$cont->lead_source_id = 16; //Other
				$cont->job_title = "NA";
				$cont->company = "NA";
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
					->where('created_by', $user->uacc_uid)
					->where('email1', $email1_val)
					->where('first_name', $fname_val)
					->where('last_name', $lname_val);
				$rs = $this->db->get();
				$duplicate_check = $rs->num_rows();


				// Save new contact
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
			$return .= "<p class='bg-success' style='padding:15px;'><strong>" . $sync_success . "</strong> people records synced successfully</p>";
		if($sync_duplicate > 0)
			$return .= "<p class='bg-info' style='padding:15px;'><strong>" . $sync_duplicate . "</strong> people records skipped (duplicate)</p>";
		if($sync_fail > 0)
			$return .= "<p class='bg-danger' style='padding:15px;'><strong>" . $sync_fail . "</strong> people records failed to sync! <span class=\"ui-btn pull-right\" onclick=\"javascript:$('.table-striped').toggle(); return false;\" style=\"text-decoration: underline;color: rgb(107, 107, 223); cursor:pointer;\">Show Errors</span></p>";

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
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

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
				$meetg->date_start = date('Y-m-d H:i:s', strtotime($json['items'][$i]['start']['dateTime']));
				$meetg->date_end = date('Y-m-d H:i:s', strtotime($json['items'][$i]['end']['dateTime']));
				$meetg->subject = $json['items'][$i]['summary'];
				$meetg->created_by = $user->uacc_uid;
				$meetg->assigned_user_id = $user->uacc_uid;
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
			$return .= "<p class='bg-success' style='padding:15px;'><strong>" . $sync_success . "</strong> calendar records synced successfully</p>";
		if($sync_duplicate > 0)
			$return .= "<p class='bg-info' style='padding:15px;'><strong>" . $sync_duplicate . "</strong> calendar records skipped (duplicates) to sync! </p>";
		if($sync_fail > 0)
			$return .= "<p class='bg-danger' style='padding:15px;'><strong>" . $sync_fail . "</strong> calendar records failed to sync! </p>";

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
													<option value="' . $user['uacc_uid'] . '">
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

		$out = '<select class="form-control" name="'.$name.'" id="'.$name.'">';
		if($default_text) {
			$out.= '<option value="'.$default_val.'">'.$default_text.'</option>';
		}

		$fields = $this->db->list_fields($table);
		foreach ($fields as $field){
			$col_name = $field;
			$formatted_name = ucwords(str_replace("_"," ",strtolower($col_name)));
			$match_name = ucwords(str_replace("_"," ",strtolower($val)));

			if(!strpos($col_name,"_id") && !in_array($col_name,$disallowed_fields)) {
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



function email_inbox()
{

$data = array();

$this->layout->view('/users/email_inbox', $data);
}

	/**
	 * settings,user session protected
	 *
	 * @url <site>/users/settings
	 */
	function settings( $section='update-profile' )
	{
		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		// set
		$data['user'] = $user;



		// init
		//$usr = new User();

		// find
		//$usr->where('upro_id', $user_id)->get();


		// save
		if( $section == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);

			// act
			switch( $section ) {
				case 'update-profile':
					// data
					/*$user_data = array('upro_first_name'=>$post['first_name'],'upro_last_name'=>$post['last_name'],
						               'uacc_email'=>$post['email']);*/


					$user_email_option = $post['user_email_option'];
					if($user_email_option == 115){
					$user_email_option = 1;}
					else{
					$user_email_option = 0;}

					$profile_data = array(
						'upro_uacc_fk' => $user_id,
						'upro_first_name' => $post['first_name'],
						'upro_last_name' => $post['last_name'],
						'email_sending_option' => $user_email_option
					);

					//handle image upload
					//go down public directory by one

					$user_data = $post['email'];


					$config['upload_path'] = './../attachments/';
					$config['allowed_types'] = 'jpg|png';
					$config['max_size']	= '5120';//5mb
					//$config['max_width']  = '251';
					//$config['max_height']  = '251';

					$this->load->library('upload', $config);

					//if the image passed JS validation
					if($post['profile_img_valid'] == "1"){
						if ( $this->upload->do_upload("profile_img_file") ){
							$data = $this->upload->data();
							$uploaded_file_name = $data['file_name'];

							//$this->image_lib->clear();
							$config1['image_library'] = 'gd2';
							$config1['source_image'] = './../attachments/'.$uploaded_file_name;
							$config1['new_image'] = './../attachments/'.$uploaded_file_name;
							$config1['quality'] = '100%';
							$config1['create_thumb'] = TRUE;
							$config1['maintain_ratio'] = false;
							$config1['thumb_marker'] = '';
							$config1['width'] = 250;
							$config1['height'] = 250;
							//$this->image_lib->initialize($config1);
							$this->load->library('image_lib', $config1);
							$this->image_lib->resize();

							//add to the array $profile_data without invoking a new key
							$profile_data = array_merge($profile_data, array("upro_filename_original"=>$data['file_name'], "upro_filename_mimetype"=>$data['file_type']));

						}
					}

					$id = $this->flexi_auth->get_custom_user_data("uacc_id", array("uacc_uid"=>$user_id), FALSE)->row();

					/*,
						'uacc_email' => $post['email'],
						'uacc_username' => $post['username']*/

						$result = $this->db->query( "update sc_user_accounts set uacc_email = '".$user_data."' where uacc_id = '".$user_id."'");

						$this->db->query("UPDATE sc_user_profiles SET email_sending_option = '".$user_email_option."' WHERE upro_id = '".$user_id."'");
					// profile update

					if( $this->flexi_auth->update_custom_user_data("user_profiles", $id->uacc_id, $profile_data) ){
					//if( $usr->update($profile_data, NULL, TRUE, array("upro_id"=>$user_id)) ){
//					if( $this->flexi_auth->update_user($user_id, $profile_data) ){
						// Get any status message that may have been set.
	//					$message = $this->flexi_auth->get_messages();
						// set flash
						notify_set( array('status'=>'success', 'message'=>'Settings Updated Successfully' ) );
					}else{
						// Get any status message that may have been set.
						//$message = $this->flexi_auth->get_messages();
						// set flash
						notify_set( array('status'=>'error', 'message'=>'Settings Updated Failed!' ) );
					}

					// redirect
					redirect( 'users/settings/update-profile' );
				break;
				case 'change-password':
					// password change
					if($post['new_password'] != $post['confirm_new_password'] || trim($post['confirm_new_password']) == "")
					{
						notify_set( array('status'=>'error', 'message'=>'New Password and New Password Confirm must match and cannot be empty.' ) );
					}
					elseif
					( $this->flexi_auth->change_password($user['uacc_email'], $post['old_password'], $post['new_password']) ){
						// Get any status message that may have been set.
						$message = $this->flexi_auth->get_messages();
						// set flash
						//notify_set( array('status'=>'success', 'message'=>$message), 'Password Change Successful' );
						notify_set( array('status'=>'success', 'message'=>$message) );
					}
					else{
						// Get any status message that may have been set.
						$message = $this->flexi_auth->get_messages();
						// set flash
						//notify_set( array('status'=>'error', 'message'=>$message), 'Password Change Failed!' );
						//notify_set( array('status'=>'success', 'message'=>'Password Change Failed') );
						notify_set( array('status'=>'error', 'message'=>$message) );
					}

					// redirect
					redirect( 'users/settings/change-password' );
				break;


				case 'update_imap':

				  $this->load->library('email');
				  $config['protocol']    = 'smtp';


				  if($this->input->post('encryption_type') == "SSL"){
				  		$config['smtp_host']    = "ssl://".$this->input->post('email_imap');
				  }
				  elseif($this->input->post('encryption_type') == "TLS"){
				  		$config['smtp_host']    = "tls://".$this->input->post('email_imap');
				  }
				  else{
				  		$config['smtp_host']    = $this->input->post('email_imap');
				  }

				  $config['smtp_port']    = $this->input->post('server_port');
				  $config['smtp_timeout'] = '7';
				  $config['smtp_user']    = $this->input->post('user_name');
				  $config['smtp_pass']    = $this->input->post('email_password');
				  $config['charset']    = 'utf-8';
				  $config['newline']    = "\r\n";
				  $config['mailtype'] = 'html'; // or html
				  $config['validation'] = TRUE; // bool whether to validate email or not

				  $this->email->initialize($config);

				  $this->email->from($_SESSION['user']['uacc_email'], $_SESSION['user']['upro_first_name']." ".$_SESSION['user']['upro_last_name']);
				  $this->email->to($_SESSION['user']['uacc_email']);

				  $this->email->subject('TealCRM Email Test');
				  $this->email->message('This is a test to make sure that your TealCRM Email configuration is successful.  You may delete this at anytime.');

				  if($this->email->send()){

					  $this->load->library('encrypt');

					  // update user profile to confirm IMAP active and test email sent
					  $data = array(
					  	'imap_active' => 1,
					  	'username' => $this->input->post('user_name'),
					  	'password' => $this->encrypt->encode($this->input->post('email_password')),
					  	'imap_address' => $config['smtp_host'],
					  	'mail_server_port' => $this->input->post('server_port'),
					  	'ssl_value' => $this->input->post('server_port')
					  	);

					  $this->db->where('upro_uacc_fk', $_SESSION['user']['upro_uacc_fk']);
					  $this->db->update('user_profiles', $data);

					  notify_set( array('status'=>'success', 'message'=>'Successfully configured SMTP Settings.  You can now send emails.') );


					  $this->teal_global_vars->set_all_global_vars();

					  // redirect
					  redirect( 'users/settings' );

				  }
				  else{
					  // cause error
					  notify_set( array('status'=>'error', 'message'=>'There was an issue connecting to your SMTP server.  Please check your settings and try again.') );

					  // redirect
					  redirect( 'users/settings' );

				  }

				break;
			}
			//pr($post);die;
		}

		// set
		$data['section'] = $section;


		// setup imap address



		$data['user_email_option'] = dropdownCreator("user_email_option");

		$this->layout->view('/users/settings', $data);


	}

}

/* End of file users.php */
/* Location: ./application/controllers/users.php */