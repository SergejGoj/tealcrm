<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Ajax extends App_Controller {


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

	public function index(){
		//$this->load->view('welcome_message');
		//echo "Hello World";
		//$this->test();

		//$email = trim($_POST['email']);
		//$msg = trim($_POST['msg']);
		//$this->processForm($email, $msg);
		//$this->processForm($_POST['email'], $_POST['msg']);
	}


	public function buildPersonDropDownRelatedToAccount(){
		$post = $this->input->post(null, true);

		$related_ddlist = form_dropdown('person_id', dropdownPersons($post['act']), '',"class='form-control' id='people_id'");
		echo $related_ddlist;
	}

   /**
	* Add new note
	*
	* @param void
	* @return void
	*/
	public function addNote(){

		// field validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'User Id', 'trim|xss_clean|required|max_length[150]');
		$this->form_validation->set_rules('subject', 'Note Body', 'max_length[255]');

		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();
		//uacc_uid
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$assignedusers = getAssignedUsers();

		$data['assignedusers'] = $assignedusers;

		// save
		if( $this->input->post('company_id', true) ){


			if ($this->form_validation->run() == TRUE){

				// post
				$post = $this->input->post(null, true);
				// now
				$now = gmdate('Y-m-d H:i:s');
				// new
				$acct = new Feeds();



				// Enter values into required fields
				$acct->company_id = $post['company_id'];
				//$acct->note_id = $this->uuid->v4();
				$acct->created_by = $use;
				$acct->description = $post['description'];

				// Save new user
				if( $acct->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully posted new note.') );

					// redirect
					//redirect( 'companies' );
					return true;
				}
			}else{
				// we have errors
					return false;
			}
		}
		return false;
	}

   /**
	* Return a json list of companies provided at least three characters of the company name
	*
	* @param $q
	* @return json with list of IDs and Names that match the provided hint
	*/
	public function accountsAutocomplete(){

		$user_id = $this->flexi_auth->get_user_id();
		//uacc_uid
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$hint = $this->input->get('q');


		//load id, label and name for the jQuery Autocomplete from the model fields_autocomplete
		$this->load->model("fields_autocomplete");
		$companies_list = $this->fields_autocomplete->getAccountsList($hint, '');
		echo json_encode($companies_list);
	}

	public function personsAutocomplete(){

			$user_id = $this->flexi_auth->get_user_id();
			//uacc_uid
			$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

			$hint = $this->input->get('q');


			//load id, label and name for the jQuery Autocomplete from the model fields_autocomplete
			$this->load->model("fields_autocomplete");
			$persons_list = $this->fields_autocomplete->getPersonsList($hint, '');
			echo json_encode($persons_list);
	}

	public function dealsAutocomplete(){

			$user_id = $this->flexi_auth->get_user_id();
			//uacc_uid
			$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

			$hint = $this->input->get('q');


			//load id, label and name for the jQuery Autocomplete from the model fields_autocomplete
			$this->load->model("fields_autocomplete");
			$deals_list = $this->fields_autocomplete->getListnew($hint, '');
			echo json_encode($deals_list);
	}

	public function projectsAutocomplete(){

			$user_id = $this->flexi_auth->get_user_id();
			//uacc_uid
			$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

			$hint = $this->input->get('q');


			//load id, label and name for the jQuery Autocomplete from the model fields_autocomplete
			$this->load->model("fields_autocomplete");
			$projects_list = $this->fields_autocomplete->getProjectsList($hint, '');
			echo json_encode($projects_list);
	}

	public function productsAutocomplete(){

			$user_id = $this->flexi_auth->get_user_id();
			//uacc_uid
			$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

			$hint = $this->input->get('q');


			//load id, label and name for the jQuery Autocomplete from the model fields_autocomplete
			$this->load->model("fields_autocomplete");
			$products_list = $this->fields_autocomplete->getProductsList($hint, '');
			echo json_encode($products_list);
	}
	public function tasksAutocomplete(){

		$user_id = $this->flexi_auth->get_user_id();
		//uacc_uid
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$hint = $this->input->get('q');


		//load id, label and name for the jQuery Autocomplete from the model fields_autocomplete
		$this->load->model("fields_autocomplete");
		$tasks_list = $this->fields_autocomplete->getTasksList($hint, '');
		echo json_encode($tasks_list);
	}

	public function templatesAutocomplete(){

			$user_id = $this->flexi_auth->get_user_id();
			//uacc_uid
			$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

			$hint = $this->input->get('q');


			//load id, label and name for the jQuery Autocomplete from the model fields_autocomplete
			$this->load->model("fields_autocomplete");
			$templates_list = $this->fields_autocomplete->getTemplatesList($hint, '');
			echo json_encode($templates_list);
	}


   /**
	* Return a json list of fields for a given selected drop down
	* This is for the TEMPLATES editor for Proposals
	*
	* @param $q
	* @return json with list of IDs and Names that match the provided hint
	*/
	public function TemplatesFieldList($module){

		// fields to exclude from pre-population
		if($module == 'proposals')
		{
			$dont_show_fields = array(
				'proposal_id',
				'deal_id',
				'company_id',
				'people_id');
		}
		else
		{
			$dont_show_fields = array(
				'csv_file_name',
				'google_id',
				'google_access_token',
				'mailchimp_id',
				'import_datetime',
				'last_viewed',
				'deleted');
		}

		// get schema based on module selected
		if($module == 'products')
		{
		echo '<select>';
		echo '<option value = {products_list}>All Product Info</option> ';
		echo '<option value = {products_list_no_cost}>All Product Info without Costs</option> ';
		echo '</select>';
		}
		if($module != 'products')
		{
		$fields = $this->db->list_fields($module);
		}
		echo '<select>';

		foreach ($fields as $field)
		{
			if(!in_array($field, $dont_show_fields)) {
				echo "<option value='{".$field."}'>".str_replace("_id", "", $field)."</option>";
			}
		}
		'</select>';

	}


   /**
	* Build the 2nd element for filter
	*
	* @param void
	* @return an html element (input or drop down list) or empty string on fail
	*/
	public function getFilterElement(){
		$return = "";
		$post = $this->input->post(null, true);
		if( !isset($post['type']) ) return $return;

		//could be datetime, ddlist, assignedUsers, check teal_global_vars.php
		$filter_type = $post['type'];

		//the value of the filter the user selected, could be lead_source_id, event_type, start_date ... etc
		$filter_value = $post['val'];

		//exception of fields that have diffferent name from the table/module where it belongs to and sc_drop_down_options under related field name
		//fix database table sc_drop_down_options
		if($filter_value == "lead_source_id") $filter_value = "lead_source";
		if($filter_value == "sales_stage_id") $filter_value = "sales_stage";
		if($filter_value == "lead_status_id") $filter_value = "lead_status";

		$user_id = $this->flexi_auth->get_user_id();
		//uacc_uid
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();


		//if datetime then return two fields for start and end date
		if( $filter_type == "datetime" ){
			$return = '
				<div class="form-group col-sm-6">
					<input type="text" name="date_start_dynamic" id="filtervalue1" class="form-control datetime filter_data_value"  placeholder="Enter start date">
				</div>
				<div class="form-group col-sm-6">
					<input type="text" name="date_end_dynamic" id="filtervalue2" class="form-control datetime filter_data_value" placeholder="Enter end date">
				</div>';
		}

		//if drop down list, fetch the values from the session drop_down_options
		if( $filter_type == "ddlist" ){
			//var_dump($_SESSION['drop_down_options']);
			$return = '<select id="filtervalue1" name="drop_down_options" class="form-control filter_data_value" size="1">';
			foreach($_SESSION['drop_down_options'] as $row){

				//show list of options of the related name passed from javascript in $filter_value
				if( $row['related_field_name'] == $filter_value ){
					$return .= "<option value='" . $row['drop_down_id'] . "'>" . $row['name'] . "</option>";
				}
			}
			$return .= "</select>";
		}

		// drop down list for assigned users has to be accessed from a different table
		if( $filter_type == "assignedUsers" ){

			// THIS SHOULD BE ADDED AS A SESSION INSTEAD OF DOING A DB CALL
			$where_arr  = array('uacc_id <>' => $user_id);
			$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();

			$return = '<select id="filtervalue1" name="assigned_user_id" class="form-control filter_data_value" size="1">';
			foreach($users as $user){
				$return .= '<option value="' . $user['uacc_uid'] . '">' . $user['name'] . '</option>';
			}
			$return .= "</select>";
		}


		//if datetime then return two fields for start and end date
		if( $filter_type == "autocomplete" ){
			$return = '
				<div class="form-group col-sm-12">
					<input type="text" name="company_viewer" id="company_viewer" value="" class="form-control ui-autocomplete-input valid" autocomplete="off" aria-invalid="false">
					<input type="hidden" name="company" id="filtervalue1" value="">
				</div>';
		}

		echo $return;


	}

   /**
	* Sync MailChimp
	*
	* @param $key
	* @return 0 on succes, 1 on fail
	*/
	public function syncMailChimpAPI(){
		//fetch activity feed list
		$post = $this->input->post(null, true);
		$key = $post['key'];

		/*
		$user_id = $this->flexi_auth->get_user_id();
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_id')->row();
		$this->db->select("upro_mailchimp_apikey");
		$this->db->where("upro_id", $user->uacc_id);
		*/
		$config = array(
			'apikey' => $key,      // Insert your api key
			'secure' => FALSE   // Optional (defaults to FALSE)
		);

		//$merge_vars = array('FNAME'=>'Test', 'LNAME'=>'Company');
		$this->load->library('MCAPI', $config, 'mail_chimp');
		/*
        if($this->mail_chimp->listSubscribe($list_id, $email, $merge_vars)) {
            // $email is now subscribed to list with id: $list_id
        }
		*/

		/** full documentation: http://apidocs.mailchimp.com/api/1.3/lists.func.php **/
		//get Lists
		$retval = $this->mail_chimp->lists();

		$list_size = $retval['total']; //int
		$list_data = $retval['data']; //array

		//First Import Persons

		//Second Export Persons


		echo json_encode($retval);
	}


   /**
	* Update MailChimp API Key
	*
	* @param $key
	* @return 0 on succes, 1 on fail

	MailChimp is a global integration synchronizing PERSONS.  It currently only uses the API KEY field in the integration table.

	*/
	public function updateMailChimpAPI(){

		//fetch activity feed list
		$post = $this->input->post(null, true);
		$key = $post['key'];

	//	$key = "1";

		$config = array(
			'apikey' => $key,      // Insert your api key
			'secure' => FALSE   // Optional (defaults to FALSE)
		);

		$this->load->library('MCAPI', $config, 'mail_chimp');

		$retval = $this->mail_chimp->ping($key);



		$data = array(
               'api_key' => $key
            );
		$this->db->where("application_id", 1);

		// attempt to connect API key

		if( $this->db->update("sc_integrations", $data) )
			echo 0;
		else {
			echo 1;
			}

	}


   /**
	* View more feeds
	*
	* @param int $id=company, $lastfetchedfeedid
	* @return styled html feeds
	*/
	public function more(){
		//fetch activity feed list
		$post = $this->input->post(null, true);
		 $company = $post['id'];
		 $lastfetchedfeedid = $post['last'];
		 $category = $post['cat'];

		$this->load->model("feed_list");
		list($feeds,$rows) = $this->feed_list->getMoreFeedList($company, $lastfetchedfeedid, $category);
		echo json_encode(array("value"=>$feeds, "last"=>$lastfetchedfeedid+$rows));
	}

   /**
	* Search a specific module for the term provided
	*
	* @param $mod = $post['mod'], the module number we are searching
	* @param $term = $post['term'], the term we are searching for
	* @return styled html search result pulled from the module module_search
	*/
	public function search(){
		$post = $this->input->post(null, true);
		 $mod = $post['mod'];
		 $term = $post['term'];
		 if(isset($post['type'])){
			 $field_type = $post['type'];
			 $filter_by = $post['filt'];
			 $filter_val1 = $post['filtval_a'];
			 $filter_val2 = $post['filtval_b'];
		}else{
			$field_type = $filter_by = $filter_val1 = $filter_val2 = "";
		}
		$this->load->model("module_search");
		list($results,$rows) = $this->module_search->seachModules($mod, $term, $filter_by, $filter_val1, $filter_val2, $field_type);
		echo json_encode(array("value"=>$results, "rows"=>$rows));
	}

   /**
	* On user change/click on check mark to indicate a task being done or not, update database based on entry
	*
	* @param int $id=task_id, $stat
	* @return void
	*/
	public function projectTaskStatusUpdate(){
		//fetch activity feed list
		$post = $this->input->post(null, true);
		$parent_id = $post['pid'];
		$task_id = $post['tid'];
		$status_id = $post['stat'];
		$completed_time = gmdate('Y-m-d H:i:s');

		//$task = new Task();
		$data = array('status_id' => $status_id, 'completed_date'=>$completed_time);
		$this->db->where("parent_id", $parent_id);
		$this->db->where("task_id", $task_id);
		$this->db->update("sc_tasks", $data);

	}

   /**
	* User submit new tasks (additional)
	*
	* @param int $id=task_id, $array(tasks)
	* @return 0 on success, or > 0 on fail
	*/
	public function additionalTasks(){
		$return = 0;
		//fetch activity feed list
		$post = $this->input->post(null, true);
		$parent_id = $post['pid'];
		$tasks_array = $post['ntsk'];
		$due_dates_array = $post['ddate'];

		$parent_task = new Task();
	    // show newest first
	    $parent_task->order_by('due_date', 'DESC');

		$parent_task->where("task_id", $parent_id)->get();
		//var_dump($parent_task->task_id);
		$user_id = $this->flexi_auth->get_user_id();

		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row_array();

		for($i=0; $i<sizeof($tasks_array); $i++){
			$new_task = new Task();

			$now = gmdate('Y-m-d H:i:s');
			//endtime
			$due_date = $parent_task->due_date;

			$id = $this->uuid->v4();
			// Enter values into required fields
			$new_task->task_id = $id;
			$new_task->parent_id = $parent_id;
			//$taks->date_modified = $now;

			//due date is same as parent if not provided
			if($due_dates_array[$i] == "")
				$new_task->due_date = $due_date;
			else
				$new_task->due_date = gmdate('Y-m-d H:i:s', strtotime($due_dates_array[$i]));

			$new_task->created_by = $user['uacc_uid'];
			$new_task->assigned_user_id = $parent_task->assigned_user_id;
			$new_task->subject = trim($tasks_array[$i]);
			$new_task->company_id = $parent_task->company_id;
			$new_task->people_id = $parent_task->people_id;
			$new_task->priority_id = (int)$parent_task->priority_id;
			$new_task->status_id = (int)$parent_task->status_id;
			$new_task->description = $parent_task->description;

			// Save new user
			if( $new_task->save() ){
			}else{
				$return++;
			}

			//echo $task;
		}
		echo $return;

	}

	public function processPerson(){
		/*
		$this->load->library("form_validation");

		//first validate email
		$this->form_validation->set_rules("email", "Email Address", "trim|xss_clean|valid_email|required");
		if( $this->form_validation->run() == FALSE){
			echo -1;
		}else{

			//2nd validate message
			$this->form_validation->set_rules("msg", "Message", "trim|xss_clean|required");
			if( $this->form_validation->run() == FALSE ){
				echo -2;
			}else{
				$email = $this->form_validation->set_value('email');
				$msg = $this->form_validation->set_value('msg');

				$this->load->model("put_db");
				$result = $this->put_db->sendMessage($email, $msg);

				if($result > 0) echo 1;
				else echo 0;

			}
		}
		*/
		if($this->addNote() == FALSE){
			echo -1;
		}else{
			echo '
							<div class="feed-item feed-item-idea">
								<div class="feed-icon">
									<i class="fa fa-paste"></i>
								</div>
								<!-- /.feed-icon -->
								<div class="feed-content">I put a note in here</div>
								<!-- /.feed-content -->
								<br/>
								<p class="feed-subject pull-left">Posted by: <a href="javascript:;">Nikita Williams</a>
								</p>
								<div class="feed-actions">
									<p class="pull-right"><i class="fa fa-clock-o"></i> 2 days ago</p>
								</div>
								<!-- /.feed-actions -->
							</div>';
		}

	}

	//handle ajax call to process admin login
	public function processLogin(){
		$this->load->library("form_validation");
		$this->load->library('encrypt');


		//first validate username
		$this->form_validation->set_rules("user", "Username", "trim|xss_clean|required");
		if( $this->form_validation->run() == FALSE){
			echo -1;
		}else{

			//2nd validate message
			$this->form_validation->set_rules("pass", "Password", "trim|xss_clean|required");
			if( $this->form_validation->run() == FALSE ){
				echo -2;

			//if all valid
			}else{

				$user = $this->form_validation->set_value('user');
				$pass = $this->form_validation->set_value('pass');

				//confirm admin identity by matching input against db
				$this->load->model("get_db");
				$result = $this->get_db->loginAdmin($user, $pass);

				//if matching is valid
				if($result > 0){
					//set a new php session
					$msg = $user . rand() . time() . rand();
					$key = 'super-secret-session';
					$encrypted_string = $this->encrypt->encode($msg, $key);

					$_SESSION['ci_admin_in'] = $encrypted_string;

					//update database with new session id
					$this->load->model("put_db");
					$result = $this->put_db->renewSession($user, $encrypted_string);

					echo 1;
				}
				else echo 0;

			}
		}
	}

	//handle ajax call to process admin logout
	public function processLogout(){
		$this->load->library("form_validation");
		$this->load->library('encrypt');

		$this->form_validation->set_rules("user", "Username", "trim|xss_clean|required");
		if( $this->form_validation->run() == FALSE){
			echo -1;
		}else{
			$user = $this->form_validation->set_value('user');

			if($user == "admin"){
				//set a new php session
				$msg = $user . rand() . time() . rand();
				$key = 'super-secret-session';
				$encrypted_string = $this->encrypt->encode($msg, $key);

				$_SESSION['ci_admin_in'] = rand() . time();

				//update database with new session id
				$this->load->model("put_db");
				$result = $this->put_db->renewSession($user, $encrypted_string);

				echo 1;
			}else
				echo 0 . " = " . $user;
		}
	}
	//dashboard feeds
	public function loadmorefeeds()
	{
	$post = $this->input->post(null, true);
		 $limit = $post['q'];

		$this->load->model("feed_list");
		$feeds = $this->feed_list->morefeedsload($limit);
		echo json_encode($feeds);
	}

	public function getCompanyIdByName() {
        $company_name = $this->input->get('q');
        $get_company_query = "SELECT * FROM sc_companies WHERE company_name='" . $company_name . "'";
        $result = $this->db->query($get_company_query)->row();
        $company_id = $result->company_id;
		echo $company_id;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */