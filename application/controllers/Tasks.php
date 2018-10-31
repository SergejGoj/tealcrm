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
		parent::__construct("Task","tasks","task");
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
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('uacc_id <>' => $user_id);
		// load model
		//$this->load->model('flexi_auth_model');
		$users = $this->flexi_auth->get_users_query(array("id,CONCAT(first_name, ' ', last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();


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
					$task_child->created_by = $user['id'];
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

}

/* End of file tasks.php */
/* Location: ./application/controllers/tasks.php */