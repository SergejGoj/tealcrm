<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Tasks Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Tasks extends App_Controller {

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
	 * View all
	 *
	 * @url <site>/tasks
	 */
	public function index(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		$projectTasksQuery = "SELECT
					task_id
					FROM
					sc_project_tasks";


		$list_of_project_tasks = $this->db->query($projectTasksQuery)->result();

		$projectTaskArray = Array();

		foreach ($list_of_project_tasks as $pTask) {
		
			array_push($projectTaskArray,$pTask->task_id);
		}


		// init
		$tasks = new Task();
		$tasks_due_today = new Task();

	    // show newest first
	    $tasks->order_by('date_entered', 'DESC');
	    $tasks->select('*,(SELECT company_name FROM sc_companies WHERE company_id = sc_tasks.company_id) as company_name, (SELECT CONCAT((first_name),(" "),(last_name)) AS name FROM sc_people WHERE people_id = sc_tasks.people_id) AS people_name');
	    
	    if (!empty($projectTaskArray)) {

	    	// show non-deleted
				$tasks->group_start()
				->where('deleted','0')
				->where_not_in('task_id',$projectTaskArray)
				->group_end();
	    } 

	    else {

	    	// show non-deleted
		$tasks->group_start()
				->where('deleted','0')
				->group_end();


	    }
		
		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['tasks'])){


			$tasks->group_start();

			foreach($_SESSION['search']['tasks'] as $key => $value){
			
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "person_viewer" && $key != "company_viewer" && $key != "parent_id" && $key != "due_date_start" && $key != "due_date_end" && $key != "status_id" && $key != "priority_id" && $key != "assigned_user_id"){
					$tasks->like($key, $value);
				}
				if($key == "status_id" || $key == "priority_id" || $key == "assigned_user_id")
				{
					$tasks->where_in($key , $value);
					
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "due_date_start" || $key == "due_date_end"){
				
					$value = date('Y-m-d H:i:s', strtotime($value));

					switch($key){
						case'date_entered_start':$tasks->where('date_entered >=', $value);break;
						case'date_entered_end':$tasks->where('date_entered <=', $value);break;
						case'date_modified_start':$tasks->where('date_modified >=', $value);break;
						case'date_modified_end':$tasks->where('date_modified <=', $value);break;
						case'due_date_start':$tasks->where('due_date >=', $value);break;
						case'due_date_end':$tasks->where('due_date <=', $value);break;
					}
				}
				if($key == "parent_id")
				{
				if($value=='Yes')
				{
				$findprojectsquery = "SELECT task_id
						FROM sc_tasks
						WHERE task_id IN (SELECT *
								FROM (SELECT parent_id
								FROM sc_tasks
								GROUP BY parent_id
								HAVING COUNT(parent_id) > 0)
                        AS a)";
		$findprojects = $this->db->query($findprojectsquery)->result();
		$allprojects = array();
		$num = 0;
		
		foreach($findprojects as $projects)
		{
			$allprojects[]=$projects->task_id;
			$num++;
		}

		$data['projects_id']=$allprojects;
		$tasks->where_in('task_id',$data['projects_id']);
				}

				if($value=='No')
				{
					$tasks->where('parent_id !=', 0);
				}
				}

				/*
				if($key == "full_name"){
					$tasks->like("first_name", $value);
					$tasks->or_like("last_name", $value);
					$tasks->or_like("concat(first_name,' ',last_name) ", $value);
				}
				*/

			}

			// set display settings
			if(isset($_SESSION['search']['tasks']['search_type'])){
				if($_SESSION['search']['tasks']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['tasks']['search_type'] == "saved"){
					$search_tab = "saved";
				}
			}

			$tasks->group_end();

		}

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['tasks']['people_id'])){
			// init
			$acct1 = new Person();

			// find
			$acct1->where('people_id', $_SESSION['search']['tasks']['people_id'])->get();

			// set
			$data['person'] = $acct1;

		}

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['tasks']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['tasks']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		$data['search_tab'] = $search_tab;

	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $tasks->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

		$tasks->group_start()
				->where('deleted','0')
				->group_end();


		if(!empty($_SESSION['search']['tasks'])){


			$tasks->group_start();

			foreach($_SESSION['search']['tasks'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "person_viewer" && $key != "company_viewer" && $key != "parent_id" && $key != "due_date_start" && $key != "due_date_end" && $key != "status_id" && $key != "priority_id" && $key != "assigned_user_id"){
					$tasks->like($key, $value);
					
					
				}
				if($key == "status_id" || $key == "priority_id" || $key == "assigned_user_id")
				{
					$tasks->where_in($key,$value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "due_date_start" || $key == "due_date_end"){

					$value = date('Y-m-d H:i:s', strtotime($value));

					switch($key){
						case'date_entered_start':$tasks->where('date_entered >=', $value);break;
						case'date_entered_end':$tasks->where('date_entered <=', $value);break;
						case'date_modified_start':$tasks->where('date_modified >=', $value);break;
						case'date_modified_end':$tasks->where('date_modified <=', $value);break;
						case'due_date_start':$tasks->where('due_date >=', $value);break;
						case'due_date_end':$tasks->where('due_date <=', $value);break;
					}
					
				}
				if($key == "parent_id")
				{
				if($value=='Yes')
				{
				$findprojectsquery = "SELECT task_id
						FROM sc_tasks
						WHERE task_id IN (SELECT *
								FROM (SELECT parent_id
								FROM sc_tasks
								GROUP BY parent_id
								HAVING COUNT(parent_id) > 0)
                        AS a)";
		$findprojects = $this->db->query($findprojectsquery)->result();
		$allprojects = array();
		$num = 0;
		//print_r($findprojects);
		foreach($findprojects as $projects)
		{
			$allprojects[]=$projects->task_id;
			$num++;
		}

		//print_r($allprojects);
		$data['projects_id']=$allprojects;
		$tasks->where_in('task_id',$data['projects_id']);
				}

				if($value=='No')
				{
				$tasks->where('parent_id !=', 0);
				}
				}

				/*
				if($key == "full_name"){
					$tasks->like("first_name", $value);
					$tasks->or_like("last_name", $value);
					$tasks->or_like("concat(first_name,' ',last_name) ", $value);
				}
				*/

			}

			// set display settings
			if(isset($_SESSION['search']['tasks']['search_type'])){
				if($_SESSION['search']['tasks']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['tasks']['search_type'] == "saved"){
					$search_tab = "saved";
				}
			}

			$tasks->group_end();

		}

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['tasks']['people_id'])){
			// init
			$acct1 = new Person();

			// find
			$acct1->where('people_id', $_SESSION['search']['tasks']['people_id'])->get();

			// set
			$data['person'] = $acct1;

		}

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['tasks']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['tasks']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}


	    $total_count = $tasks->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'tasks');

	    // set
	    $data['tasks'] = $tasks;

	    // get tasks for today's due date
	    $tasks_due_today->order_by('due_date', 'ASC');

	    $tasks_due_today->select('subject,task_id,date_entered,due_date,assigned_user_id');

		// show non-deleted
		$tasks_due_today->where('date(due_date) = date(NOW())','',FALSE);

		// add to code igniter view
		$data['tasks_due_today'] = $tasks_due_today;

		// get list of projects
		$query = "SELECT
					sct.task_id,
					sct.subject,
					sct.due_date
				FROM
					sc_tasks sct
				WHERE
					sct.task_id IN (SELECT parent_id FROM sc_tasks) and sct.status_id!='87' and sct.deleted ='0' and due_date !=''";


		$list_of_projects = $this->db->query($query)->result();

		$data['project_count'] = count($list_of_projects);
		$data['list_of_projects'] = $list_of_projects;



		$findprojectsquery = "SELECT task_id
						FROM sc_tasks
						WHERE task_id IN (SELECT *
								FROM (SELECT parent_id
								FROM sc_tasks
								GROUP BY parent_id
								HAVING COUNT(parent_id) > 0)
                        AS a)";
		$findprojects = $this->db->query($findprojectsquery)->result();
		$allprojects = array();
		$num = 0;
		foreach($findprojects as $projects)
		{
			$allprojects[]=$projects->task_id;
			$num++;
		}


		$data['projects_id']=$allprojects;
		
		$this->load->helper('list_views');
		list ($label, $task_updated_fields, $custom_values) = task_list_view();		
		
		$data['field_label'] = $label;
		$data['task_updated_fields'] = $task_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/tasks/index', $data);
	}



   /**
	* Add new
	*
	* @param void
	* @return void
	*/
	public function add($company_id = NULL, $people_id = NULL){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// get the GUID for the logged in user
		$user_id = $this->flexi_auth->get_user_id();

		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row_array();

		// init
		$taks = new Task();

		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('uacc_id <>' => $user_id);
		// load model
		//$this->load->model('flexi_auth_model');
		$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();
		// set
	    $data['users'] = $users;

			$post = $this->input->post(null, true);


		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			if($post['due_date'] == '__/__/____ __:__')
			{
			$post['due_date'] = null;
			}
			else
			{
			$due_date = gmdate('Y-m-d H:i', strtotime($post['due_date']));
			}

			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			$this->form_validation->set_rules('subject','Subject','max_length[150]');

			if(!empty($post['company_viewer'])){

				if(empty($post['company_id'])){
					$this->form_validation->set_rules('company_id', 'company', 'required');
					$this->form_validation->set_message('required', 'Please select a valid %1$s name from the list or create a new %1$s.');
				}
			}

			if(!empty($post['person_viewer'])){

				if(empty($post['people_id'])){
					$this->form_validation->set_rules('people_id', 'person', 'required');
					$this->form_validation->set_message('required', 'Please select a valid %1$s name from the list or create a new %1$s.');
				}
			}

			if(!empty($post['project_viewer'])){

				if(empty($post['project_id'])){
					$this->form_validation->set_rules('project_id', 'project', 'required');
					$this->form_validation->set_message('required', 'Please select a valid %1$s name from the list or create a new %1$s.');
				}
			}


			if ($this->form_validation->run() == TRUE){
				//endtime
				//$due_date = date('Y-m-d H:i:s', strtotime($post['due_date']));

				$id = $this->uuid->v4();
				// Enter values into required fields
				$taks->task_id = $id;
				//$taks->date_modified = $now;
				$taks->due_date = $due_date;
				$taks->created_by = $user['uacc_uid'];
				$taks->assigned_user_id = $post['assigned_user_id'];
				$taks->subject = $post['subject'];
				$taks->company_id = $post['company_id'];
				$taks->people_id = $post['people_id'];
				$taks->priority_id = (int)$post['priority_id'];
				$taks->status_id = (int)$post['status_id'];
				$taks->description = $post['description'];

				// Delete any previous existing relationship(s) between this task and any project
				

				if (!empty($post['project_viewer'])) {
				// Create the relationship between the new task and the chosen project.
				$relationship_data['sc_project_task_id'] = $this->uuid->v4();
				$relationship_data['project_id'] = $post['project_id'];
				$relationship_data['task_id'] = $id;
				$this->db->insert('sc_project_tasks',$relationship_data);
				}


	            //custom data

				$custom_data = array();
				$custom_data['companies_id'] = $id;
				$custom_field_company = $_SESSION['custom_field']['123'];
				foreach($custom_field_company as $custom)
				{
				$field_name = $custom['cf_name'];
				$custom_data['custom_fields_id'] = $custom['cf_id'];
				$custom_data['data_value'] = $post [$field_name];
				if($post [$field_name] != "" && $post [$field_name] != " ")
				{
				$this->db->insert('sc_custom_fields_data',$custom_data);
				}
				}


				// Save new user
				if( $taks->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new task.') );

					// redirect
					redirect( 'tasks/view/' . $id );
				}
			}
		}

		// pre populate company and person if possible

		//if the user clicked on Add New Person from the companies view page
		// get company
		if(strlen($company_id) == 36){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $company_id)->get();

			// set
			$data['company'] = $acct;
			$data['company_id'] =	$company_id;

		}


		// get person
		if(strlen($people_id) == 36){
			// init
			$ccct = new Person();

			// find
			$ccct->where('people_id', $people_id)->get();

			// set
			$data['person'] = $ccct;
			$data['people_id'] =	$people_id;

		}


		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		// priority types
		$priority_ids = dropdownCreator('priority_id');
		$data['priority_ids'] = $priority_ids;

		// task types
		$status_ids = dropdownCreator('status_id');
		$data['status_ids'] = $status_ids;

		//default assigned user for new person to the admin user of this company
		$data['assigned_user_id'] = $user['uacc_uid'];

		// fetch company name
		$person_names = dropdownpeople();
		$data['person_names'] = $person_names;



		//custom field

			if (isset($_SESSION['custom_field']['123']))
		{
		$custom_field_values = $_SESSION['custom_field']['123'];
		foreach($custom_field_values as $custom)
		{
		if($custom['cf_type'] == "Dropdown")
		{
		$custom_field = dropdownCreator($custom['cf_name']);
		$data[$custom['cf_name']] = $custom_field;
		}
		}
		$data['is_custom_fields'] = 1;
		}
		else
		{
		$data['is_custom_fields'] = 0;
		}





		// load view
		$this->layout->view('/tasks/add', $data);
	}


   /**
	* Add Project Tasks
	*
	* @param void
	* @return void
	*/
	public function project(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('uacc_id <>' => $user_id);
		// load model
		//$this->load->model('flexi_auth_model');
		$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();
		// set
	    $data['users'] = $users;

		$assignedusers = getAssignedUsers();
		$data['assignedusers'] = $assignedusers;

		// priority types
		$priority_ids = dropdownCreator('priority_id');
		$data['priority_ids'] = $priority_ids;

		// task types
		$status_ids = dropdownCreator('status_id');
		$data['status_ids'] = $status_ids;
		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);

			if($post['due_date'] == '__/__/____ __:__')
			{
			$post['due_date'] = null;
			}
			else
			{
			$post['due_date'] = gmdate('Y-m-d H:i', strtotime($post['due_date']));
			}
			// new
			$taks = new Task();

			// now
			$now = gmdate('Y-m-d H:i:s');
			//endtime
			$duration  = time() + (7 * 24 * 60 * 60);
			$due_date = gmdate('Y-m-d H:i:s', $duration);

			//parent_id is set as the task_id for the parent, and as the parent_id for the children tasks
			$parent_id = $this->uuid->v4();

			// Enter values into required fields
			$taks->task_id = $parent_id;
			$taks->date_entered = $now;
			//$taks->date_modified = $now;
			$taks->due_date = $post['due_date'];
			$taks->created_by = $user['uacc_uid'];
			$taks->assigned_user_id = $post['assigned_user_id'];
			$taks->subject = $post['subject'];
			$taks->company_id = $post['company_id'];
			$taks->people_id = $post['people_id'];
			$taks->priority_id = (int)$post['priority_id'];
			$taks->status_id = (int)$post['status_id'];
			//$taks->status_id = 1;
			$taks->description = $post['description'];

			// Save new user
			if( $taks->save() ){
				//go over the array of tasks (children) and save every single one with the parent_id=$parent_id
				for($i=0; $i<sizeof($post['tasks']); $i++){
					// new
					$task_child = new Task();
					if($post['tasks'][$i] !='')
					{
					// Enter values into required fields
					$task_child->task_id = $this->uuid->v4();
					$task_child->parent_id = $parent_id;
					$task_child->date_entered = $now;
					//$taks->date_modified = $now;
					$task_child->due_date = $due_date;
					$task_child->created_by = $user['uacc_uid'];
					$task_child->assigned_user_id = $post['assigned_user_id'];
					$task_child->subject = $post['tasks'][$i];
					$task_child->company_id = $post['company_id'];
					$task_child->people_id = $post['people_id'];
					$task_child->priority_id = (int)$post['priority_id'];
					$task_child->status_id = (int)$post['status_id'];
					//$task_child->status_id = 1;
					$task_child->description = $post['tasks'][$i];
					$task_child->save();
					}
				}
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully created new task.') );

				// redirect
				redirect( 'tasks' );
			}
		}

		// load view
		$this->layout->view('/tasks/project', $data);
	}

   /**
	* Edit existing
	*
	* @param varchar $task_id
	* @return void
	*/
	public function edit( $task_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$data['user_id'] = $user_id;

		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('uacc_id <>' => $user_id);
		// load model
		$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();
		// set
	    $data['users'] = $users;

		// init
		$tks = new Task();

		// find
		$tks->where('task_id', $task_id)->get();

		if( isset($tks->task_id) && $tks->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'tasks' );
		}
		else if( ! isset($tks->task_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'tasks' );
		}
		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);
			// now


			if($post['due_date'] == '__/__/____' || $post['due_date']== '__/__/____ __:__')
			{
			$post['due_date'] = null;
			}
			else
			{
			$post['due_date'] = gmdate('Y-m-d H:i', strtotime($post['due_date']));
			$due_date = gmdate('Y-m-d H:i', strtotime($post['due_date']));

			}

			$now = gmdate('Y-m-d H:i:s');

			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			$this->form_validation->set_rules('subject','Subject','max_length[150]');

			// check if we company or person deleted
			$company_id;
			$person_id;
			if(!empty($post['company_viewer'])){
				if(empty($post['company_id'])){
					$this->form_validation->set_rules('company_id', 'company', 'required');
					$this->form_validation->set_message('required', 'Please select a valid %1$s name from the list or create a new %1$s.');
				}
				else{
					$company_id = $post['company_id'];
				}
			}
			else{
				$company_id = "";
			}

			if(!empty($post['person_viewer'])){
				if(empty($post['people_id'])){
					$this->form_validation->set_rules('people_id', 'person', 'required');
					$this->form_validation->set_message('required', 'Please select a valid %1$s name from the list or create a new %1$s.');
				}
				else{
					$person_id = $post['people_id'];
				}
			}
			else{
				$person_id;
			}


			if(!empty($post['project_viewer'])){
				if(empty($post['project_id'])){
					$this->form_validation->set_rules('project_id', 'project', 'required');
					$this->form_validation->set_message('required', 'Please select a valid %1$s name from the list or create a new %1$s.');
				}
				else{
					$project_id = $post['project_id'];
				}
			}
			else{
				$project_id;
			}



			if ($this->form_validation->run() == TRUE){

				//$due_date = date('Y-m-d H:i:s', strtotime($post['due_date']));
				// set array(fields=>values) to update
				$data = array(
					"date_modified"=>$now,
					"subject"=>$post['subject'],
					"modified_user_id"=>$user['uacc_uid'],
					"assigned_user_id"=>$post['assigned_user_id'],
					"company_id"=>$company_id,
					"people_id"=>$person_id,
					"priority_id"=>(int)$post['priority_id'],
					"status_id"=>(int)$post['status_id'],
					"description"=>$post['description'],
					"due_date"=>$due_date
					);

				// Delete any previous existing relationship(s) between this task and any project
				$this->db->query("DELETE FROM sc_project_tasks WHERE (task_id = '". $task_id . "')");

				if (!empty($post['project_viewer'])) {
				// Create the relationship between the new task and the chosen project.
				$relationship_data['sc_project_task_id'] = $this->uuid->v4();
				$relationship_data['project_id'] = $post['project_id'];
				$relationship_data['task_id'] = $task_id;
				$this->db->insert('sc_project_tasks',$relationship_data);
				}



				//custom_field update
				$custom_data = array();
				$custom_field_company = $_SESSION['custom_field']['123'];
				foreach($custom_field_company as $custom)
				{
					$field_name = $custom['cf_name'];
					$custom_data['data_value'] = $post [$field_name];
					$custom_value_query = "SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$task_id."'";
					$query_value = $this->db->query($custom_value_query)->result();

					if(array_key_exists(0,$query_value))
					{
						if($post [$field_name] != "" && $post [$field_name] != " ")
						{
							//$this->db->where(array('custom_fields_id'=>$custom['cf_id'],'companies_id'=>$task_id));
							$this->db->query("UPDATE sc_custom_fields_data SET data_value ='".$post [$field_name]."' WHERE custom_fields_id = '".$custom['cf_id']."' AND companies_id = '".$task_id."'");
						}
						else
						{
							$this->db->query("DELETE FROM sc_custom_fields_data WHERE companies_id ='".$task_id."' and custom_fields_id = '".$custom['cf_id']."' ");
						}
					}
					else
					{
						$field_name = $custom['cf_name'];
						$custom_data['data_value'] = $post [$field_name];
						$custom_data['custom_fields_id'] = $custom['cf_id'];
						$custom_data['companies_id'] = $task_id;
						if($post [$field_name] != "" && $post [$field_name] != " ")
						{
							if($post [$field_name] != "" && $post [$field_name] != " ")
							{
								$this->db->insert('sc_custom_fields_data',$custom_data);
							}
						}
					}
				}


				// update
				if( $tks->update($data, NULL, TRUE, array("task_id"=>$task_id)) ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully updated task.') );

					// redirect
					redirect( 'tasks/view/' . $task_id );
				}
			}
		}

		// check(task_id)
		if( ! isset($tks->task_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No task by task id #%d.', $task_id) ) );

			// redirect
			redirect( 'tasks' );
		}

		// set
		$data['task'] = $tks;

		// get list of priorities
		$task_priorities = dropdownCreator('priority_id');
		$data['task_priorities'] = $task_priorities;

		// get list of priorities
		$status_ids = dropdownCreator('status_id');
		$data['status_ids'] = $status_ids;

		// get list of user ids
		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		// company name
		$this->load->model("general");
		$data['company_name'] = $this->general->getAccountName($tks->company_id);

		$this->load->model("general");
		$data['person_name'] = $this->general->getPersonName($tks->people_id);

		$this->load->model("general");
		$data['project_name'] = $this->general->getProjectForTask($tks->task_id,"name");



		//custom_field
		if (isset($_SESSION['custom_field']['123']))
		{

		$custom_field_values = $_SESSION['custom_field']['123'];
		foreach($custom_field_values as $custom)
		{
		if($custom['cf_type'] == "Dropdown")
		{
		$custom_field = dropdownCreator($custom['cf_name']);
		$data[$custom['cf_name']] = $custom_field;

		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$task_id."'")->result();

		if(array_key_exists(0,$custom_query) )
		{
		$data['custom_'.$custom['cf_name']] = $custom_query[0]->data_value;
		}
		else
		{
		$data['custom_'.$custom['cf_name']] = " ";
		}
		}
		else if($custom['cf_type'] == "Textbox")
		{
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$task_id."'")->result();
		$countvalue = count($custom_query);
		if(array_key_exists(0,$custom_query))
		{
		$data[$custom['cf_name']] = $custom_query[0]->data_value;
		}
		else
		{
		$data[$custom['cf_name']] = " ";
		}
		}
		}
		$data['is_custom_fields'] = 1;
		}
		else
		{
		$data['is_custom_fields'] = 0;
		}



		// load view
		$this->layout->view('/tasks/edit', $data);
	}


	/**
	  * Mark completed
	  * Marks given task_id as completed then redirects back to view
	*/
	public function mark_completed ($task_id){

		if(strlen($task_id) == 36){

			// init
			$tks = new Task();

			// find
			$tks->where('task_id', $task_id)->get();

			$data = array(
				"status_id"=>103
				);

			// update
			if( $tks->update($data, NULL, TRUE, array("task_id"=>$task_id)) ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully updated task.') );

				// redirect
				redirect( 'tasks/view/' . $task_id );
			}
		}
		else{
				// no id provided or bad formatted id
				redirect( 'tasks');
		}
	}

   /**
	* View existing
	*
	* @param varchar $task_id
	* @return void
	*/
	public function view( $task_id){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// -------------------------------
		// GET INDIVIDUAL TASK INFO
		//
		$query = $this->db->query('SELECT parent_id FROM sc_tasks WHERE task_id = "'.$task_id.'"');
		$pre_id=$query->row();
		$parent_id =$pre_id->parent_id;

		$query = $this->db->query('SELECT *, (SELECT subject FROM sc_tasks WHERE task_id = "'.$parent_id.'") as parent_name, (SELECT company_name FROM sc_companies WHERE company_id= sc_tasks.company_id) as company_name, (SELECT concat(first_name, " ", last_name) FROM sc_people WHERE people_id = sc_tasks.people_id) as person_name FROM sc_tasks WHERE task_id="'.$task_id.'" LIMIT 1');

		$taks = $query->row();

		$this->flexi_auth;

		// check(task_id)
		if( isset($taks->task_id) && $taks->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'tasks' );
		}
		else if( ! isset($taks->task_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'tasks' );
		}

		// priority id
		$data['priority'] = $_SESSION['drop_down_options'][$taks->priority_id]['name'];

		// type id
		$data['status'] = $_SESSION['drop_down_options'][$taks->status_id]['name'];

		// set assigned to
		if(strlen($taks->assigned_user_id) >= 36){
			$data['assigned_user'] = $_SESSION['user_accounts'][$taks->assigned_user_id]['uacc_username'];
		}
		else{
			$data['assigned_user'] = "Not set";
		}

		$parent_due_date = strtotime($taks->due_date.' UTC');

		// --------------------------------------------
		// GET ANY PROJECTS
		// --------------------------------------------
		$lists = new Task();
	    // show newest first
	    $lists->order_by('due_date', 'DESC');

		$lists->where("parent_id", $task_id)->get();

		$total_tasks = 0;
		$done_tasks = 0;
		$percent_complete = 0;
		foreach($lists as $list){
			if($list->status_id == 87) $done_tasks++;
			$total_tasks++;
			if(strtotime($list->due_date.' UTC') > $parent_due_date) $parent_due_date = strtotime($list->due_date.' UTC');
		}
		if($total_tasks != 0){
			$percent_complete = ($done_tasks / $total_tasks) * 100;
		}

		// set
		$data['task'] = $taks;
		$data['lists'] = $lists;
		$data['percent_complete'] = (int)$percent_complete;
		$data['total_tasks'] = $total_tasks; // also used to determine if this is a project or not 0=stand alone project
		$data['done_tasks'] = $done_tasks;

		$data['project_due_date'] = date('F jS, Y',$parent_due_date);

		//fetch activity feed list
		$this->load->model("feed_list");

		// --------------------------------------------
		// END GET ANY PROJECTS
		// --------------------------------------------


		//getFeedList($company_id, $category)
		$data['feed_list'] = $this->feed_list->getFeedList($task_id,5);

		// set last viewed
		//update_last_viewed($task_id, 6, $taks->subject);


		 //custom field
		 $check_value = 0;
		$check_field = 0;
		if (isset($_SESSION['custom_field']['123']))
		{
		$data['more_info'] = 1;
		$custom_field_values = $_SESSION['custom_field']['123'];
		$data['custom_field_values'] = $custom_field_values;
		foreach($custom_field_values as $custom)
		{
		$check_field++;
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE companies_id ='".$task_id."' and custom_fields_id = '".$custom['cf_id']."'")->result();

		if(array_key_exists(0,$custom_query))
		{
		$data[$custom['cf_name']] = $custom_query[0]->data_value;
		}
		else
		{
		$data[$custom['cf_name']] = " ";
		$check_value++;
		}
		}
		$data['is_custom_fields'] = 1;

		}
		else
		{
		$data['is_custom_fields'] = 0;
		}
		if($check_value == $check_field)
		{
		$data['more_info'] = 0;
		}

		//custom field


		// load view
		$this->layout->view('/tasks/view', $data);


	}

   /**
	* Search
	*
	* @param void
	* @return void
	*/
    public function search($saved_search_id = NULL, $delete = NULL){
	
        unset($_SESSION['search']['tasks']); // kills search session

        $params = array('tasks',$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
        $this->load->library('AdvancedSearch', $params); // initiate advancedsearch class

        // check if user is trying to save a search parameter
        if(isset($_POST['saved_search_result'])){
		
            $this->advancedsearch->search_string = $_POST;
            $this->advancedsearch->Insert_Saved_Search();
            $_SESSION['search']['tasks']['search_type'] = "advanced";
        }
		else if($_POST['saved_search_name'] !="")
		{
		
		$this->advancedsearch->search_string = $_POST;
		$this->advancedsearch->Insert_Saved_Search();
		$_SESSION['search']['tasks']['search_type'] = "advanced";
		}

        // did the user hit the CLEAR button, if yes skip everything
        if(!isset($_POST['clear']) && !isset($delete)){
            $this->advancedsearch->Store_Search_Criteria();
        }

        $this->advancedsearch->Set_Search_Type(); // sets what type of search to show

        if(!is_null($delete)){
		
            $this->advancedsearch->Delete_Saved_Search();
            unset($_SESSION['search']['tasks']);
        }

        // store search ID
        $_SESSION['search_id'] = $saved_search_id;

        // done all of our search work, redirect to people view for the magic
        header("Location: ".site_url('tasks'));

    }

	 /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $task_id ){

		// init
		$tks = new Task();
		// find
		$tks->where('task_id', $task_id)->get();

		// soft_delete(array(fields=>values):where clause)
		if( $tks->soft_delete(array("task_id"=>$task_id)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted task.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Task delete failed.') );
		}

		// redirect
		redirect( 'tasks' );
	}
public function update( $task_id ){

		// init
			$tks = new Task();

			// find
			$tks->where('task_id', $task_id)->get();

			$data = array(
				"status_id"=>103
				);

			// update
			if( $tks->update($data, NULL, TRUE, array("task_id"=>$task_id)) ){

				// redirect
				redirect( 'users/dashboard' );
			}

		// redirect

	}

	/**
	* Delete all
	*
	* @param void
	* @return void
	*/
	public function delete_all( ){
		// post
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];

			// init
			$tks = new Task();
			// find in
			$tks->where_in('task_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($tks->all as $tk)
			{
			   	// delete
				if( $tk->soft_delete(array("task_id"=>$post['ids'][$deleted])) ){
			   		$deleted++;
			   	}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d task(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Company delete failed.') );
			}
		}

		// redirect
		redirect( 'tasks' );
	}


	public function related_companies( $related_company_id ){
		$data = array();

		// init
		$tasks = new Task();
		$tasks_due_today = new Task();

	    // show newest first
	    $tasks->order_by('date_entered', 'DESC');

	    $tasks->select('subject,task_id,date_entered,due_date,assigned_user_id, priority_id, status_id, parent_id');

		// show non-deleted
		$tasks->group_start()
				->where('deleted','0')
				->group_end();

		$tasks->where('company_id',$related_company_id);

		$search_tab = "advanced";

		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['tasks'])){


			$tasks->group_start();

			foreach($_SESSION['search']['tasks'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name"){
					$tasks->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					$value = date('Y-m-d H:i:s', strtotime($value));

					switch($key){
						case'date_entered_start':$tasks->where('date_entered >=', $value);break;
						case'date_entered_end':$tasks->where('date_entered <=', $value);break;
						case'date_modified_start':$tasks->where('date_modified >=', $value);break;
						case'date_modified_end':$tasks->where('date_modified <=', $value);break;

					}
				}

				/*
				if($key == "full_name"){
					$tasks->like("first_name", $value);
					$tasks->or_like("last_name", $value);
					$tasks->or_like("concat(first_name,' ',last_name) ", $value);
				}
				*/

			}

			// set display settings
			if(isset($_SESSION['search']['tasks']['search_type'])){
				if($_SESSION['search']['tasks']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['tasks']['search_type'] == "saved"){
					$search_tab = "saved";
				}
			}

			$tasks->group_end();

		}

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['tasks']['people_id']);
		if(!empty($_SESSION['search']['tasks']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['tasks']['people_id'])->get();

			// set
			$data['person'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page

		$_SESSION['search']['tasks']['company_id'] = $related_company_id;
		if(!empty($_SESSION['search']['tasks']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['tasks']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		$data['search_tab'] = $search_tab;

	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $tasks->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $tasks->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'tasks');

	    // set
	    $data['tasks'] = $tasks;

	    // get tasks for today's due date
	    $tasks_due_today->order_by('due_date', 'ASC');

	    $tasks_due_today->select('subject,task_id,date_entered,due_date,assigned_user_id');

		// show non-deleted
		$tasks_due_today->where('date(due_date) = date(NOW())','',FALSE);

		// add to code igniter view
		$data['tasks_due_today'] = $tasks_due_today;

		// get list of projects
		$query = "SELECT
					sct.task_id,
					sct.subject,
					sct.due_date
				FROM
					sc_tasks sct
				WHERE
					sct.task_id IN (SELECT parent_id FROM sc_tasks)";


		$list_of_projects = $this->db->query($query)->result();

		$data['project_count'] = count($list_of_projects);
		$data['list_of_projects'] = $list_of_projects;

		$findprojectsquery = "SELECT task_id
						FROM sc_tasks
						WHERE task_id IN (SELECT *
								FROM (SELECT parent_id
								FROM sc_tasks
								GROUP BY parent_id
								HAVING COUNT(parent_id) > 0)
                        AS a)";
		$findprojects = $this->db->query($findprojectsquery)->result();
		$allprojects = array();
		$num = 0;

		foreach($findprojects as $projects)
		{
			$allprojects[]=$projects->task_id;
			$num++;
		}

		$data['projects_id']=$allprojects;

		$this->load->helper('list_views');
		list ($label, $task_updated_fields, $custom_values) = task_list_view();		
		
		$data['field_label'] = $label;
		$data['task_updated_fields'] = $task_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/tasks/index', $data);

	}

	public function related_people( $related_people_id ){
		$data = array();

		// init
		$tasks = new Task();
		$tasks_due_today = new Task();

	    // show newest first
	    $tasks->order_by('date_entered', 'DESC');

	    $tasks->select('subject,task_id,date_entered,due_date,assigned_user_id, priority_id, status_id, parent_id');

		// show non-deleted
		$tasks->group_start()
				->where('deleted','0')
				->group_end();

		$tasks->where('people_id',$related_people_id);

		$search_tab = "advanced";

		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['tasks'])){


			$tasks->group_start();
		
			foreach($_SESSION['search']['tasks'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name"){
					$tasks->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					$value = date('Y-m-d H:i:s', strtotime($value));

					switch($key){
						case'date_entered_start':$tasks->where('date_entered >=', $value);break;
						case'date_entered_end':$tasks->where('date_entered <=', $value);break;
						case'date_modified_start':$tasks->where('date_modified >=', $value);break;
						case'date_modified_end':$tasks->where('date_modified <=', $value);break;

					}
				}

				/*
				if($key == "full_name"){
					$tasks->like("first_name", $value);
					$tasks->or_like("last_name", $value);
					$tasks->or_like("concat(first_name,' ',last_name) ", $value);
				}
				*/

			}

			// set display settings
			if(isset($_SESSION['search']['tasks']['search_type'])){
				if($_SESSION['search']['tasks']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['tasks']['search_type'] == "saved"){
					$search_tab = "saved";
				}
			}

			$tasks->group_end();

		}

		//if the user clicked on Add New Person from the companies view page

		$_SESSION['search']['tasks']['people_id'] = $related_people_id;
		if(!empty($_SESSION['search']['tasks']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['tasks']['people_id'])->get();

			// set
			$data['person'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['tasks']['company_id']);
		if(!empty($_SESSION['search']['tasks']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['tasks']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		$data['search_tab'] = $search_tab;

	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $tasks->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $tasks->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'tasks');

	    // set
	    $data['tasks'] = $tasks;

	    // get tasks for today's due date
	    $tasks_due_today->order_by('due_date', 'ASC');

	    $tasks_due_today->select('subject,task_id,date_entered,due_date,assigned_user_id');

		// show non-deleted
		$tasks_due_today->where('date(due_date) = date(NOW())','',FALSE);

		// add to code igniter view
		$data['tasks_due_today'] = $tasks_due_today;

		// get list of projects
		$query = "SELECT
					sct.task_id,
					sct.subject,
					sct.due_date
				FROM
					sc_tasks sct
				WHERE
					sct.task_id IN (SELECT parent_id FROM sc_tasks)";


		$list_of_projects = $this->db->query($query)->result();

		$data['project_count'] = count($list_of_projects);
		$data['list_of_projects'] = $list_of_projects;

		$findprojectsquery = "SELECT task_id
						FROM sc_tasks
						WHERE task_id IN (SELECT *
								FROM (SELECT parent_id
								FROM sc_tasks
								GROUP BY parent_id
								HAVING COUNT(parent_id) > 0)
                        AS a)";
		$findprojects = $this->db->query($findprojectsquery)->result();
		$allprojects = array();
		$num = 0;
		foreach($findprojects as $projects)
		{
			$allprojects[]=$projects->task_id;
			$num++;
		}

		$data['projects_id']=$allprojects;
		
		$this->load->helper('list_views');
		list ($label, $task_updated_fields, $custom_values) = task_list_view();		
		
		$data['field_label'] = $label;
		$data['task_updated_fields'] = $task_updated_fields;
		$data['custom_values'] = $custom_values;		
		
		// load view
		$this->layout->view('/tasks/index', $data);

	}

	public function add_more_tasks( $parent_project_id ){


		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('uacc_id <>' => $user_id);
		// load model
		//$this->load->model('flexi_auth_model');
		$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();


				//go over the array of tasks (children) and save every single one with the parent_id=$parent_id

				$post = $this->input->post(null, true);

				$now = date('Y-m-d H:i:s');
				$duration  = time() + (7 * 24 * 60 * 60);
				$due_date = date('Y-m-d H:i:s', $duration);

				$find_parent_project = "SELECT * from sc_tasks where task_id='".$parent_project_id."'";
				$parent_project = $this->db->query($find_parent_project)->result();

			//	print_r($parent_project);
			//	exit;

				for($i=0; $i<sizeof($post['tasks']); $i++){
					// new
					$task_child = new Task();
					if($post['tasks'][$i] !='')
					{
					// Enter values into required fields
					$task_child->task_id = $this->uuid->v4();
					$task_child->parent_id = $parent_project_id;
					$task_child->date_entered = $now;
					//$taks->date_modified = $now;
					$task_child->due_date = $due_date;
					$task_child->created_by = $user['uacc_uid'];
					$task_child->assigned_user_id = $parent_project[0]->assigned_user_id;
					//$task_child->subject = $parent_project[0]->subject;
					$task_child->subject = $post['tasks'][$i];
					$task_child->company_id = $parent_project[0]->company_id;
					$task_child->people_id = $parent_project[0]->people_id;
					$task_child->priority_id = (int)$parent_project[0]->priority_id;
					$task_child->status_id = (int)$parent_project[0]->status_id;
				//	$task_child->status_id = 1;
					$task_child->description = $post['tasks'][$i];
					$task_child->save();
					}
				}
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully added new tasks.') );

				// redirect
				redirect( 'tasks/view/'.$parent_project_id);

		//$this->layout->view('/tasks/project', $data);
	}
	
	// GOOGLE TASK INDEX
	public function googletaskindex()
	{
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();
		
		$data = array();
		
		$this->db->select('google_task_id,date_entered,created_by,due_date,subject,description,status');
		$this->db->from('sc_google_task');
		$this->db->where(array('deleted'=>0, 'created_by'=>$user['uacc_uid']));
		$this->db->order_by('date_entered','desc');
		$query = $this->db->get();
		
		$Gtask = $query->result();
		
		$data['Gtask'] = $Gtask;
		
		$this->layout->view('tasks/googletaskindex', $data);

	}
	
	//GOOGLE TASK VIEW
	public function googletaskview($google_task_id)
	{
	
		$data = array();
		$this->db->select('google_task_id,date_entered,created_by,due_date,subject,description,status');
		$this->db->from('sc_google_task');
		$this->db->where('google_task_id',$google_task_id);
		$query = $this->db->get();
		
		$Gtask = $query->result();
		
		$data['Gtask'] = $Gtask;

		$this->layout->view('/tasks/googletaskview', $data);
	
	}

}

/* End of file tasks.php */
/* Location: ./application/controllers/tasks.php */