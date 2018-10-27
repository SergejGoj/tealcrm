<?php 


if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// -----------------------------------------------------------------------
/**
 * Companies Controller
 *
 * @package Projects
 * @subpackage Controller
 * @author TealCRM Team
 * @since 1.0
 * @version 1.0
 *
 
 Project Details
 - Project Name
 - Related Module
 - Related Record
 - Start Date
 - End Date
 - Category
 - Time Estimate (Hrs)
 
 
 */
 
class Projects extends App_Controller {
	 
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
	 * View all
	 *
	 * @url <site>/companies
	 */
	public function index(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$projects = new Project();

		// set
		$projects->select('project_id,parent_id,date_entered,date_modified,modified_user_id,assigned_user_id,created_by,company_id,project_name,description,time_estimate,start_date,end_date,last_viewed,deleted');
		
		// show one with highest due date first
		$projects->order_by('project_name', 'ASC');	
		
		$projects->where("archived", 0);
		
		$projects->where("deleted", 0)->get();
		
		$total_count = $projects->count();
		
		$data['total_count'] = $total_count;
		
		$data["asc_project"] = $projects;
		
		foreach($projects as $proj)
		{
			
			$task_id = array();
		
			$this->db->select('sc_project_task_id,project_id,task_id')
					->where('project_id',$proj->project_id);
			
			$relat_tabl = $this->db->get('sc_project_tasks')->result();
			
			if(count($relat_tabl) > 0)
			{
			
				foreach($relat_tabl as $rela)
				{
					$task_id[$rela->task_id] = $rela->task_id;
					$task_id_all[$rela->task_id] = $rela->task_id;
				}
				$tasks_due_overdue = new Task(); 
				
				$tasks_due_overdue->order_by('due_date', 'ASC');
				
				$tasks_due_overdue->where_in('task_id',$task_id);
				
				$tasks_due_overdue->where('deleted',0);
				
				$tasks_due_overdue->where('status_id !=',103);
				
				// $_SESSION['timezone']
				// CONVERT_TZ(date_entered,'UTC','America/Vancouver')
				$tasks_due_overdue->where("CONVERT_TZ(date(due_date),'UTC','".$_SESSION['timezone']."') > CONVERT_TZ(UTC_DATE(),'UTC','".$_SESSION['timezone']."')",'',FALSE)->get();

				if(($tasks_due_overdue->count()) > 0)
				{
					$over_due_task[$proj->project_id] = $tasks_due_overdue;
					
					$data['over_due_task'] = $over_due_task;
				}
			
				$tasks_due_today = new Task(); 
				
				$tasks_due_today->order_by('due_date', 'ASC');
				
				$tasks_due_today->where_in('task_id',$task_id);
				
				$tasks_due_today->where('deleted',0);
				
				$tasks_due_today->where('status_id !=',103);
				
				$tasks_due_today->where("CONVERT_TZ(date(due_date),'UTC','".$_SESSION['timezone']."') = date(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."'))",'',FALSE)->get();
				
				if(($tasks_due_today->count()) > 0)
				{
					$today_due_task[$proj->project_id] = $tasks_due_today;
					
					$data['today_due_task'] = $today_due_task;
				}
				
				$tasks_next_sev_due = new Task();

				$tasks_next_sev_due->order_by('due_date', 'ASC');
				
				$tasks_next_sev_due->where_in('task_id',$task_id);
				
				$tasks_next_sev_due->where('deleted',0);
				
				$tasks_next_sev_due->where('status_id !=',103);
				
				$tasks_next_sev_due->where("CONVERT_TZ(date(due_date),'UTC','".$_SESSION['timezone']."') != date(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."')");
				
				$tasks_next_sev_due->where("due_date BETWEEN date(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."')) AND date_add(date(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."')) , INTERVAL 7 DAY))",'',FALSE)->get();
				
				if(($tasks_next_sev_due->count()) > 0)
				{
					$next_sev_due[$proj->project_id] = $tasks_next_sev_due;
					
					$data['next_sev_due'] = $next_sev_due;
				}


				$tasks_no_due_date = new Task();
				
				$tasks_no_due_date->order_by('due_date', 'ASC');
				
				$tasks_no_due_date->where_in('task_id',$task_id);
				
				$tasks_no_due_date->where('deleted',0);
				
			    $tasks_no_due_date->where('status_id !=',103);

			    $tasks_no_due_date->group_start();
			    $tasks_no_due_date->where('due_date IS NULL');
			    $tasks_no_due_date->or_where("due_date > date_add(date(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."')),INTERVAL 1 WEEK)");			    
			    $tasks_no_due_date->group_end();
			    $tasks_no_due_date->get();
			    				
				if(($tasks_no_due_date->count()) > 0)
				{
					$no_due_date[$proj->project_id] = $tasks_no_due_date;
					
					$data['tasks_no_due_date'] = $no_due_date;
				}





				$tasks_completed = new Task();
				
				$tasks_completed->order_by('due_date', 'ASC');
				
				$tasks_completed->where_in('task_id',$task_id);
				
				$tasks_completed->where('deleted',0);
				
			    $tasks_completed->where('status_id',103);
			    $tasks_completed->get();
			    				
				if(($tasks_completed->count()) > 0)
				{
					$completed[$proj->project_id] = $tasks_completed;
					$completed["count"] = $tasks_completed->count();
					
					$data['tasks_completed'] = $completed;
				}


							
			}
			
		}
		
		if(isset($task_id_all))
		{
		
			$tasks_due_overdue_all = new Task(); 
					
			$tasks_due_overdue_all->order_by('due_date', 'ASC');
					
			$tasks_due_overdue_all->where_in('task_id',$task_id_all);
					
			$tasks_due_overdue_all->where('deleted',0);
			
			$tasks_due_overdue_all->where('status_id !=',103);
					
			$tasks_due_overdue_all->where("CONVERT_TZ(due_date,'UTC','".$_SESSION['timezone']."') < date(CONVERT_TZ(UTC_DATE(),'UTC','".$_SESSION['timezone']."'))",'',FALSE)->get();
							
			if(($tasks_due_overdue_all->count()) > 0)
			{
				$over_due_task_all = $tasks_due_overdue_all;
			}
			
			$tasks_due_today_all = new Task(); 
					
			$tasks_due_today_all->order_by('due_date', 'ASC');
			
			$tasks_due_today_all->where_in('task_id',$task_id_all);
			
			$tasks_due_today_all->where('deleted',0);
			
			$tasks_due_today_all->where('status_id !=',103);
			
			$tasks_due_today_all->where("date(CONVERT_TZ(due_date,'UTC','".$_SESSION['timezone']."')) = date(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."'))",'',FALSE)->get();
			
			if(($tasks_due_today_all->count()) > 0)
			{
				$today_due_task_all = $tasks_due_today_all;
			}
						
			$tasks_next_sev_due_all = new Task();
			
			$tasks_next_sev_due_all->order_by('due_date', 'ASC');
			
			$tasks_next_sev_due_all->where_in('task_id',$task_id_all);
			
			$tasks_next_sev_due_all->where('deleted',0);
			
			$tasks_next_sev_due_all->where('status_id !=',103);
			
			$tasks_next_sev_due_all->where("CONVERT_TZ(due_date,'UTC','".$_SESSION['timezone']."') != date(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."'))");
			
			$tasks_next_sev_due_all->where("CONVERT_TZ(due_date,'UTC','".$_SESSION['timezone']."') BETWEEN CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."') AND date_add(CONVERT_TZ(UTC_TIMESTAMP(),'UTC','".$_SESSION['timezone']."') , INTERVAL 7 DAY)",'',FALSE)->get();
			
			if(($tasks_next_sev_due_all->count()) > 0)
			{
				$next_sev_due_all = $tasks_next_sev_due_all;
			}
			
			$data['over_due_task_all'] = $over_due_task_all;
			
			$data['today_due_task_all'] = $today_due_task_all;
			
			$data['next_sev_due_all'] = $next_sev_due_all; 
		
		}
		
		//ASSIGNED USER FOR ADDING TASK
		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;
		
		// PRIORITY TYPE FOR ADDING TASK
		$priority_ids = dropdownCreator('priority_id');
		$data['priority_ids'] = $priority_ids;

		// TASK TYPE FOR ADDING TASK
		$status_ids = dropdownCreator('status_id');
		$data['status_ids'] = $status_ids;
		
		// load view
		$this->layout->view('/projects/index', $data);
	}	 
	 
	/**
	 * View Individual Project
	 *
	 * @url <site>/companies
	 */
	
	public function add(){
		
	$CI =& get_instance();
	$CI->teal_global_vars->set_all_global_vars();
	
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
	// set
	$data['users'] = $users;
	
	if( 'save' == $this->input->post('act', true) )
	{
		
		$post = $this->input->post(null, true);

		if($post['start_date'] == '__/__/____')
		{
			$post['start_date'] = null;
		}
		else
		{
			$post['start_date'] = gmdate('Y-m-d H:i:s', strtotime($post['start_date']));
		}
	
		if($post['end_date'] == '__/__/____')
		{
			$post['end_date'] = null;
		}
		else
		{
			$post['end_date'] = gmdate('Y-m-d H:i:s', strtotime($post['end_date']));
		}
		
		$proj = new Project();
		
		$id = $this->uuid->v4();
		
		$proj->project_id = $id;
		$proj->assigned_user_id = $post['assigned_user_id'];
		$proj->created_by = $user['id'];
		$proj->company_id = $post['company'];
		$proj->project_name = $post['project_name'];
		$proj->description = $post['description'];
		$proj->time_estimate = $post['time_estimate'];
		$proj->start_date = $post['start_date'];
		$proj->end_date = $post['end_date'];
		
		if( $proj->save() )
		{
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully created new project.') );

			// redirect
			redirect( '/projects/index');
		}
	}
			
	$assignedusers1 = getAssignedUsers1();
	$data['assignedusers1'] = $assignedusers1;

	$this->layout->view('/projects/add',$data);
	}
	
	//EDIT VIEW FOR Project
	public function edit($project_id , $red_page = null)
	{
		
		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		//logedin user
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();
		
		$projects = new Project();

		// set
		$projects->select('project_id,parent_id,date_entered,date_modified,modified_user_id,assigned_user_id,created_by,company_id,project_name,description,time_estimate,start_date,end_date,last_viewed,deleted');	
		
		$projects->where("project_id", $project_id)->get();
		
		if( isset($projects->project_id) && $projects->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'projects' );
		}
		else if( ! isset($projects->project_id) )
		{
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'projects' );
		}
		if( 'save' == $this->input->post('act', true) )
		{
				
				// post
				$post = $this->input->post(null, true);
				// now				
				if($post['start_date'] == '__/__/____')
				{
					$post['start_date'] = null;
				}
				else
				{
					$post['start_date'] = gmdate('Y-m-d H:i:s', strtotime($post['start_date']));
				}
			
				if($post['end_date'] == '__/__/____')
				{
					$post['end_date'] = null;
				}
				else
				{
					$post['end_date'] = gmdate('Y-m-d H:i:s', strtotime($post['end_date']));
				}
				
				//SET VALUE FOR UPDATE Project
				$data = array(
					"modified_user_id" => $user['id'],
					"assigned_user_id" => $post['assigned_user_id'],
					"company_id" => $post['company'],
					"project_name" => $post['project_name'],
					"description" => $post['description'],
					"time_estimate" => $post['time_estimate'],
					"start_date" => $post['start_date'],
					"end_date" => $post['end_date']
				);
				
				// update
				if( $projects->update($data, NULL, TRUE, array("project_id"=>$project_id)) )
				{
					
					$_SESSION['project_tab'] = $project_id;
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully updated Project.') );

					if($post['redir'] != "archived")
					{
						// redirect
						redirect('projects');
					}
					else 
					{
						redirect('projects/archived');
					}
				}
				
		
		}
		
		// SET PROJECT
		$data['project'] = $projects;
		
		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;
		
		// company name
		$this->load->model("general");
		$data['company_name'] = $this->general->getAccountName($projects->company_id);
		
		$data['redir'] = $red_page;
		
		$this->layout->view('/projects/edit', $data);
	}
	 
	public function view(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();
		
		
		// load view -- same index view but different parameters
		$this->layout->view('/projects/index', $data);
	}	 
	 
	public function task_add()
	{
		
		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// get the GUID for the logged in user
		$user_id = $_SESSION['user']->id;

		$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row_array();

		$taks = new Task();
		
		$post = $this->input->post(null, true);
		
		if( 'save' == $this->input->post('act', true) )
		{
			if($post['due_date'] == '__/__/____ __:__')
			{
				$post['due_date'] = null;
			}
			else
			{
				$due_date = gmdate('Y-m-d H:i:s', strtotime($post['due_date']));
			}
			// now
			
			
			$task_id = $this->uuid->v4();
			
			$taks->task_id = $task_id;
			$taks->due_date = $due_date;
			$taks->created_by = $user['id'];
			$taks->assigned_user_id = $post['assigned_user_id'];
			$taks->subject = $post['subject'];
			$taks->company_id = $post['company_id'];
			$taks->people_id = $post['people_id'];
			$taks->priority_id = (int)$post['priority_id'];
			$taks->status_id = (int)$post['status_id'];
			$taks->description = $post['description'];
			
			if( $taks->save() )
			{
				
				$id = $this->uuid->v4();
				
				$data_rel = array(
				"sc_project_task_id" => $id,
				"project_id" => $post['project_id'],
				"task_id" => $task_id
				);
				
				$this->db->insert('sc_project_tasks',$data_rel);
				
				$_SESSION['project_tab'] = $post['project_id'];
				
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully created new task.') );

				if($post['redir'] != "archived")
				{
					// redirect
					redirect('projects');
				}
				else 
				{
					redirect('projects/archived');
				}
				
			}
		}
	}
	
	//LIST OF ARCHIVED PROJECTS
	
	public function archived()
	{
		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$projects = new Project();

		// set
		$projects->select('project_id,parent_id,date_entered,date_modified,modified_user_id,assigned_user_id,created_by,company_id,project_name,description,time_estimate,start_date,end_date,last_viewed,deleted');	
		
		// show one with highest due date first
		$projects->order_by('project_name', 'ASC');	
		
		$projects->where("archived", 1);
		
		$projects->where("deleted", 0)->get();
		
		$total_count = $projects->count();
		
		$data['total_count'] = $total_count;
		
		$data["asc_project"] = $projects;
				
		// load view
		$this->layout->view('/projects/archived', $data);
		
	}
	
	// SET PROJECT ARCHIVED STATUS IS 1
	
	public function add_archiveproject($project_id)
	{
		
		$projects = new Project();
		
		$projects->where("project_id", $project_id)->get();
		
		$data = array(
		"archived" => 1
		);
		
		if( $projects->update($data, NULL, TRUE, array("project_id"=>$project_id)) )
		{
					
			
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully Project Archived.') );

			// redirect
			redirect('projects');
		}
		else
		{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Failed To Archive Project.') );

			// redirect
			redirect('projects');
		}
	}
	
	public function updatetask($task_id,$project_id,$time_spent,$redir = null)
	{
			
			// init
			$tks = new Task();

			// find
			$tks->where('task_id', $task_id)->get();

			$now = gmdate('Y-m-d H:i:s');
		
			$data = array(
				"status_id"=>103,
				"completed_date"=>"$now",
				"time_used"=>"$time_spent",
				);

			 //update
			if( $tks->update($data, NULL, TRUE, array("task_id"=>$task_id)) ){

				if($project_id != "undefined")
				{
					$_SESSION['project_tab'] = $project_id;
					
					
				}
				if($redir == 'archived')
				{
					redirect('projects/archived');
				}
				if ($redir == 'tasks')
				{
					redirect('tasks');
				}
				// redirect
				redirect( 'projects' );
			}
	
	}
	
	public function unarchive_all()
	{
	
		// post
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];
		}
		
		$archive = 0;
		
		foreach($ids as $id)
		{
			$projects = new Project();
		
			$projects->where("project_id", $id)->get();
			
			$data = array(
			"archived" => 0
			);
			
			if( $projects->update($data, NULL, TRUE, array("project_id"=>$id)) )
			{
				$archive++;
			}
			
		}
		if($archive > 0)
		{
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully '.$archive.' Project(s) UnArchived.') );

			// redirect
			redirect('projects');
		}
		else
		{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Failed To Project UnArchiv.') );

			// redirect
			redirect('projects');
		}
	
	}
	 
} // end Projects