<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
error_reporting(0);
/**
 * Meetings Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Meetings extends App_Controller {

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
	 * @url <site>/meetings
	 */
	public function index(){

		// data
		$data = array();

		// init
		$meetings = new Meeting();

	    // show newest first
	    $meetings->order_by('date_start', 'DESC');

	    // select
	   $meetings->select('*, (SELECT company_name FROM sc_companies WHERE company_id = sc_meetings.company_id) as company_name, (SELECT CONCAT((first_name),(" "),(last_name)) AS name FROM sc_people WHERE people_id = sc_meetings.people_id) AS people_name');

		// show non-deleted
		$meetings->group_start()
				->where('deleted','0')
				->group_end();


        $search_tab = "basic";
        //**** CHECK FOR SEARCH SETTINGS *****/
        // if empty, that means it was cleared out or never there to begin with
        if(!empty($_SESSION['search']['meetings'])){

            $meetings->group_start();

            foreach($_SESSION['search']['meetings'] as $key => $value){
                if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "event_type"){
                    $meetings->like($key, $value);
                }
				if($key == "event_type")
				{
					$meetings->where_in($key, $value);
				}
                if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

                    switch($key){
						case'date_entered_start':$meetings->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$meetings->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$meetings->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$meetings->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
                    }
                }

            }

            // set display settings
            if(isset($_SESSION['search']['meetings']['search_type'])){
                if($_SESSION['search']['meetings']['search_type'] == "adv_search_go"){
                    $search_tab = "advanced";
                }
                elseif($_SESSION['search']['meetings']['search_type'] == "saved"){
                    $search_tab = "saved";
                    $data['search_id'] = '';
                }
            }

            $meetings->group_end();

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
	    $meetings->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $meetings->group_start()
				->where('deleted','0')
				->group_end();

		if(!empty($_SESSION['search']['meetings'])){

            $meetings->group_start();

            foreach($_SESSION['search']['meetings'] as $key => $value){
                if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "event_type"){
                    $meetings->like($key, $value);
                }
				if($key == "event_type")
				{
					$meetings->where_in($key, $value);
				}
                if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

                    switch($key){
						case'date_entered_start':$meetings->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$meetings->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$meetings->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$meetings->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
                    }
                }

            }

            // set display settings
            if(isset($_SESSION['search']['meetings']['search_type'])){
                if($_SESSION['search']['meetings']['search_type'] == "adv_search_go"){
                    $search_tab = "advanced";
                }
                elseif($_SESSION['search']['meetings']['search_type'] == "saved"){
                    $search_tab = "saved";
                    $data['search_id'] = '';
                }
            }

            $meetings->group_end();

        }
	    $total_count = $meetings->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'meetings');

	    // set
	    $data['meetings'] = $meetings;
		//$data['searchables'] = $_SESSION['available_search_options'];
		
		$this->load->helper('list_views');
		list ($label, $meeting_updated_fields, $custom_values) = meeting_list_view();		
		
		$data['field_label'] = $label;
		$data['meeting_updated_fields'] = $meeting_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/meetings/index', $data);
	}

   /**
	* Add new
	*
	* @param void
	* @return void
	*/
	public function add($company_id = NULL, $people_id = Null){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

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
		// set
	    $data['users'] = $users;

		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);

			// date_start

			if($post['date_start'] == '__/__/____ __:__')
			{
			$post['date_start'] = null;
			}
			else
			{
			$post['date_start'] = gmdate('Y-m-d H:i', strtotime($post['date_start']));
			}

			// date_end

			if($post['date_end'] == '__/__/____ __:__')
			{
			$post['date_end'] = null;
			}
			else
			{
			$post['date_end'] = gmdate('Y-m-d H:i', strtotime($post['date_end']));
			}

			// new
			$meetg = new Meeting();

			$id = $this->uuid->v4();
			// Enter values into required fields
			$meetg->meeting_id = $id;
			$meetg->date_start = $post['date_start'];
			$meetg->date_end = $post['date_end'] ;
			$meetg->subject = $post['subject'];
			$meetg->created_by = $user['id'];
			$meetg->assigned_user_id = $post['assigned_user_id'];
			$meetg->location = $post['location'];
			$meetg->event_type = (int)$post['event_type'];
			$meetg->company_id = $post['company_id'];
			$meetg->people_id = $post['people_id'];
			$meetg->status = 100;
			$meetg->description = $post['description'];



			//custom data

				$custom_data = array();
				$custom_data['companies_id'] = $id;
				$custom_field_company = $_SESSION['custom_field']['124'];
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


			// find duplicate
		/*	$subject = $post['subject'];
			$this->db->select('meeting_id')->from('sc_meetings')->where('subject', $subject);
			$rs = $this->db->get();
    		$duplicate_check = $rs->num_rows();		*/

			// Save new user if there were no duplicates
			if($duplicate_check == 0){
				if( $meetg->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new meeting schedule.') );

					// redirect
					redirect( 'meetings/view/' . $id );
				}
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Subject Exists.') );
				// redirect
				redirect( 'meetings' );
			}
		}

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

		// fetch meeting types from sc_drop_down_options
		$event_types = dropdownCreator('event_type');
		$data['event_types'] = $event_types;

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		//custom field

			if (isset($_SESSION['custom_field']['124']))
		{
		$custom_field_values = $_SESSION['custom_field']['124'];
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
		$this->layout->view('/meetings/add', $data);
	}

   /**
	* Edit existing
	*
	* @param varchar $meeting_id
	* @return void
	*/
	public function edit( $meeting_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		//logedin user
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

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


		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('uacc_id <>' => $user_id);
		// load model
		$users = $this->flexi_auth->get_users_query(array("id,CONCAT(first_name, ' ', last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();
		// set
	    $data['users'] = $users;

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		// init
		$meetg = new Meeting();

		//select only company_name from sc_companies and ALL from the other table
	    $meetg->select('sc_companies.company_name, sc_meetings.*');

		//join statement to get the company name
	    $meetg->db->join('sc_companies', 'sc_companies.company_id=sc_meetings.company_id', 'left outer');

		// find
		$meetg->where('meeting_id', $meeting_id)->get();

		if( isset($meetg->meeting_id) && $meetg->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'meetings' );
		}
		else if( ! isset($meetg->meeting_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'meetings' );
		}
		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);
			// now
			// date_start

			if($post['date_start'] == '__/__/____ __:__')
			{
			$post['date_start'] = null;
			}
			else
			{
			$post['date_start'] = gmdate('Y-m-d H:i', strtotime($post['date_start']));
			}

			// date_end

			if($post['date_end'] == '__/__/____ __:__')
			{
			$post['date_end'] = null;
			}
			else
			{
			$post['date_end'] = gmdate('Y-m-d H:i', strtotime($post['date_end']));
			}

			// set array(fields=>values) to update
			$data = array(
				"subject"=>$post['subject'],
				"modified_user_id"=>$user['id'],
				"assigned_user_id"=>$post['assigned_user_id'],
				"location"=>$post['location'],
				"event_type"=>(int)$post['event_type'],
				"company_id"=>$post['company_id'],
				"people_id"=>$post['people_id'],
				"date_start"=>$post['date_start'],
				"date_end"=>$post['date_end'],
				"description"=>$post['description']
				);

				//custom_field update
					$custom_data = array();
				$custom_field_company = $_SESSION['custom_field']['124'];
				foreach($custom_field_company as $custom)
				{
				$field_name = $custom['cf_name'];
				$custom_data['data_value'] = $post [$field_name];
				$custom_value_query = "SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$meeting_id."'";
				$query_value = $this->db->query($custom_value_query)->result();
				if(array_key_exists(0,$query_value))
				{
				if($post [$field_name] != "" && $post [$field_name] != " ")
				{
				//$this->db->where(array('custom_fields_id'=>$custom['cf_id'],'companies_id'=>$meeting_id));
				$this->db->query("UPDATE sc_custom_fields_data SET data_value ='".$post [$field_name]."' WHERE custom_fields_id = '".$custom['cf_id']."' AND companies_id = '".$meeting_id."'");
				}
				else
				{
				$this->db->query("DELETE FROM sc_custom_fields_data WHERE companies_id ='".$meeting_id."' and custom_fields_id = '".$custom['cf_id']."' ");
				}
				}
				else
				{
				$field_name = $custom['cf_name'];
				$custom_data['data_value'] = $post [$field_name];
				$custom_data['custom_fields_id'] = $custom['cf_id'];
				$custom_data['companies_id'] = $meeting_id;
				if($post [$field_name] != "" && $post [$field_name] != " ")
				{
				$this->db->insert('sc_custom_fields_data',$custom_data);
				}
				}
				}


			// update
			if( $meetg->update($data, NULL, TRUE, array("meeting_id"=>$meeting_id)) ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully updated meeting schedule.') );

				// redirect
				redirect( 'meetings/view/' . $meeting_id );
			}
		}

		// check(meeting_id)
		if( ! isset($meetg->meeting_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No meeting schedule by meeting id #%d.', $meeting_id) ) );

			// redirect
			redirect( 'meetings' );
		}

		// set
		$data['meeting'] = $meetg;

		// company name
		//$person_names = dropdownpeople();
		//$data['person_names'] = $person_names;

		$this->load->model("general");
		$data['person_name'] = $this->general->getPersonName($meetg->people_id);

		// fetch meeting types from sc_drop_down_options
		$event_types = dropdownCreator('event_type');
		$data['event_types'] = $event_types;


		//custom_field
		if (isset($_SESSION['custom_field']['124']))
		{

		$custom_field_values = $_SESSION['custom_field']['124'];
		foreach($custom_field_values as $custom)
		{
		if($custom['cf_type'] == "Dropdown")
		{
		$custom_field = dropdownCreator($custom['cf_name']);
		$data[$custom['cf_name']] = $custom_field;

		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$meeting_id."'")->result();

		if(array_key_exists(0,$custom_query))
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
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$meeting_id."'")->result();
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
		$this->layout->view('/meetings/edit', $data);
	}

   /**
	* View existing
	*
	* @param varchar $meeting_id
	* @return void
	*/
	public function view( $meeting_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$meetg = new Meeting();

		$meetg->select("*,(SELECT company_name FROM sc_companies WHERE company_id= sc_meetings.company_id) as company_name, (SELECT first_name FROM sc_people WHERE people_id= sc_meetings.people_id) as person_name, (SELECT last_name FROM sc_people WHERE people_id= sc_meetings.people_id) as person_last");
		// find
		$meetg->where('meeting_id', $meeting_id)->get();

		// check(meeting_id)
		if( isset($meetg->meeting_id) && $meetg->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'meetings' );
		}
		else if( ! isset($meetg->meeting_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'meetings' );
		}

		// set
		$data['meeting'] = $meetg;

		//fetch activity feed list
		$this->load->model("feed_list");

		//getFeedList($company_id, $category)
		$data['feed_list'] = $this->feed_list->getFeedList($meeting_id,6);

		// set last viewed
		//update_last_viewed($meeting_id, 4, $meetg->subject);

		 //custom field
		$check_value = 0;
		$check_field = 0;
		if (isset($_SESSION['custom_field']['124']))
		{
		$data['more_info'] = 1;
		$custom_field_values = $_SESSION['custom_field']['124'];
		$data['custom_field_values'] = $custom_field_values;
		foreach($custom_field_values as $custom)
		{
		$check_field++;
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE companies_id ='".$meeting_id."' and custom_fields_id = '".$custom['cf_id']."'")->result();

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
		$this->layout->view('/meetings/view', $data);
	}

   /**
	* View calendar
	*
	* @param void
	* @return void
	*/
	public function calendar(){
		// data
		$data = array();

		//logedin user
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();


		//get a list of all calendar events to populate javascript
		$this->load->model("general");
		$events = $this->general->getCalendarEvents();
		//echo $events;

	    // set
	    $data['events'] = $events;

		$assignedusers = getAssignedUsers();
		$data['assignedusers'] = $assignedusers;

		// fetch meeting types from sc_drop_down_options
		$event_types = dropdownCreator('event_type');
		$data['event_types'] = $event_types;

		// company name
		$people_names = dropdownpeople();
		$data['people_names'] = $people_names;

		// load view
		$this->layout->view('/meetings/calendar', $data);
	}

   /**
	* Add new event to meetings
	*
	* @param void
	* @return void
	*/
	public function addEventMeeting(){
		// data
		$data = array();

		//logedin user
		$user_id = $_SESSION['user']->id;
		//id
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

		$where_arr  = array('uacc_id <>' => $user_id);
		$users = $this->flexi_auth->get_users_query(array("id,CONCAT(first_name, ' ', last_name) AS name", FALSE), $where_arr)->result_array();

		// set
	    $data['users'] = $users;

		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);

			// date_start
			$post['date_start'] = gmdate('Y-m-d H:i', strtotime($post['date_start']));
			// date_end
			$post['date_end'] = gmdate('Y-m-d H:i', strtotime($post['date_end']));

			// new
			$meetg = new Meeting();

			// Enter values into required fields
			$meetg->meeting_id = $this->uuid->v4();
			$meetg->date_start = $post['date_start'];
			$meetg->date_end = $post['date_end'] ;
			$meetg->subject = $post['subject'];
			$meetg->created_by = $user->id;
			$meetg->assigned_user_id = $post['assigned_user_id'];
			$meetg->location = $post['location'];
			$meetg->event_type = (int)$post['event_type'];
			$meetg->company_id = $post['company_id'];
			$meetg->people_id = $post['people_id'];
			$meetg->status = 1;
			$meetg->description = $post['description'];

			// find duplicate
			$subject = $post['subject'];
			$this->db->select('meeting_id')->from('sc_meetings')->where('subject', $subject);
			$rs = $this->db->get();
    		$duplicate_check = $rs->num_rows();

			// Save new user if there were no duplicates
			if($duplicate_check == 0){
				if( $meetg->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new meeting schedule.') );

					// redirect
					redirect( 'meetings' );
				}
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Subject Exists.') );
				// redirect
				redirect( 'meetings' );
			}
		}

		// fetch company name
		$people_names = dropdownpeople();
		$data['people_names'] = $people_names;

		// load view
		$this->layout->view('/meetings/add', $data);
	}

   /**
	* Edit existing event on calendar by Drag-n-Drop
	*
	* @param void
	* @return 0 on fail, 1 on save success
	*/
	public function editEventMeeting(){

		$return = 0;
		// post
		$post = $this->input->post(null, true);
		$meeting_id = $post['i'];

		// now
		$now = gmdate('Y-m-d H:i:s');

		// init
		$meetg = new Meeting();

		// save passed from input and the meeting_id exists under sc_meetings
		if( 'save' == $this->input->post('act', true) && count($meetg->where('meeting_id', $meeting_id)->get()->all) > 0){
			// update meeting
			// update(array(fields=>values):data to be updated, null, true, array(fields=>values):where clause)
			if( $meetg->update(array("date_modified"=>$now, "date_start"=>date('Y-m-d H:i', strtotime($post['s'])), "date_end"=>date('Y-m-d H:i', strtotime($post['e']))), NULL, TRUE, array("meeting_id"=>$meeting_id)) ){
				$return = 1;
			}
		}else{

			//we are updating a Task
			$task = new Task();

			// save passed from input and the meeting_id exists under sc_task
			if( 'save' == $this->input->post('act', true) && count($task->where('task_id', $task_id)->get()->all) > 0){

				// update task
				// update(array(fields=>values):data to be updated, null, true, array(fields=>values):where clause)
				if( $task->update(array("date_modified"=>$now, "due_date"=>date('Y-m-d H:i', strtotime($post['e']))), NULL, TRUE, array("task_id"=>$task_id)) ){
					$return = 1;
				}
			}
		}
		echo $return;
	}

	/**
	* Delete via calendar
	*
	* @param meeting_id
	* @return 0 on fail, 1 on delete success
	*/

	public function alleventdelete( $meeting_id ){

	$meetg = new Meeting();
	$meetg->where('meeting_id', $meeting_id)->get();
	if(isset($meetg->meeting_id))
	{
	if( $meetg->soft_delete(array("meeting_id"=>$meeting_id)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted meeting schedule.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Meeting schedule delete failed.') );
		}

	}
	else
	{
	$task = new Task();
		if( $task->soft_delete(array("task_id"=>$meeting_id)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted task.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Task delete failed.') );
		}
	}redirect( 'meetings/calendar' );

	}

	public function deleteEventMeeting(){
		$return = 0;
		// post
		$post = $this->input->post(null, true);
		$meeting_id = $post['i'];

		// init
		$meetg = new Meeting();
		// find
		$meetg->where('meeting_id', $meeting_id)->get();

		// Delete
		if( $meetg->delete() ){
			$return = 1;
		}
		echo $return;
	}

	/**
	* View via calendar
	*
	* @param meeting_id
	* @return 0 on fail, 1 on delete success
	*/
	public function viewEventMeeting(){
		// post
		$post = $this->input->post(null, true);
		$meeting_id = $post['i'];

		// data
		$data = array();


		//get popup details for this meeting_id
		$this->load->model("general");
		echo $this->general->viewCalendarEvent($meeting_id);



		//echo $return;
	}

   /**
	* Search
	*
	* @param void
	* @return void
	*/
    public function search($saved_search_id = NULL, $delete = NULL){


        unset($_SESSION['search']['meetings']); // kills search session

        $params = array('meetings',$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
        $this->load->library('AdvancedSearch', $params); // initiate advancedsearch class

        // check if user is trying to save a search parameter
        if(isset($_POST['saved_search_result'])){
            $this->advancedsearch->search_string = $_POST;
            $this->advancedsearch->Insert_Saved_Search();
            $_SESSION['search']['meetings']['search_type'] = "advanced";
        }
		else if($_POST['saved_search_name'] !="")
		{
		$this->advancedsearch->search_string = $_POST;
		$this->advancedsearch->Insert_Saved_Search();
		$_SESSION['search']['meetings']['search_type'] = "advanced";
		}
        // did the user hit the CLEAR button, if yes skip everything
        if(!isset($_POST['clear']) && !isset($delete)){
            $this->advancedsearch->Store_Search_Criteria();
        }

        $this->advancedsearch->Set_Search_Type(); // sets what type of search to show

        if(!is_null($delete)){
            $this->advancedsearch->Delete_Saved_Search();
            unset($_SESSION['search']['meetings']);
        }

        // store search ID
        $_SESSION['search_id'] = $saved_search_id;

        // done all of our search work, redirect to people view for the magic
        header("Location: ".site_url('meetings'));

    }


    /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $meeting_id ){
		// init
		$meetg = new Meeting();

		// soft_delete(array(fields=>values):where clause)
		if( $meetg->soft_delete(array("meeting_id"=>$meeting_id)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted meeting.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Meeting delete failed.') );
		}

		// redirect
		redirect( 'meetings' );
	}
	public function update( $meeting_id ){

		// init
			$meetg = new Meeting();

			// find
			$meetg->where('meeting_id', $meeting_id)->get();

			$data = array(
				"status"=>103
				);

			// update
			if( $meetg->update($data, NULL, TRUE, array("meeting_id"=>$meeting_id)) ){

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
			$meetg = new Meeting();

			// find in
			$meetg->where_in('meeting_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($meetg->all as $meet)
			{
			   	// delete
				if( $meet->soft_delete(array("meeting_id"=>$post['ids'][$deleted])) ){
			   		$deleted++;
			   	}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d meeting schedule(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Meeting schedule delete failed.') );
			}
		}

		// redirect
		redirect( 'meetings' );
	}

	public function related_companies($related_company_id){
		// data
		$data = array();

		// init
		$meetings = new Meeting();

	    // show newest first
	    $meetings->order_by('date_start', 'DESC');

	    // select
	    $meetings->select('subject,location,meeting_id,date_entered,date_start,date_end,company_id, event_type, (SELECT company_name FROM sc_companies WHERE company_id = sc_meetings.company_id) as company_name');

		// show non-deleted
		$meetings->group_start()
				->where('deleted','0')
				->group_end();

		$meetings->where('company_id',$related_company_id);

        $search_tab = "advanced";
        //**** CHECK FOR SEARCH SETTINGS *****/
        // if empty, that means it was cleared out or never there to begin with
        if(!empty($_SESSION['search']['meetings'])){

            $meetings->group_start();

            foreach($_SESSION['search']['meetings'] as $key => $value){
                if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end"){
                    $meetings->like($key, $value);
                }

                if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

                    switch($key){
						case'date_entered_start':$meetings->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$meetings->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$meetings->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$meetings->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

                    }
                }

            }

            // set display settings
            if(isset($_SESSION['search']['meetings']['search_type'])){
                if($_SESSION['search']['meetings']['search_type'] == "adv_search_go"){
                    $search_tab = "advanced";
                }
                elseif($_SESSION['search']['meetings']['search_type'] == "saved"){
                    $search_tab = "saved";
                    $data['search_id'] = '';
                }
            }

            $meetings->group_end();

        }
        $data['search_tab'] = $search_tab;


		$_SESSION['search']['meetings']['company_id']=$related_company_id;
		if(!empty($_SESSION['search']['meetings']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['meetings']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}
		unset($_SESSION['search']['meetings']['people_id']);
		if(!empty($_SESSION['search']['meetings']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['meetings']['people_id'])->get();

			// set
			$data['person'] = $acct;

		}


	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $meetings->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $meetings->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'meetings');

	    // set
	    $data['meetings'] = $meetings;
		//$data['searchables'] = $_SESSION['available_search_options'];


		$this->load->helper('list_views');
		list ($label, $meeting_updated_fields, $custom_values) = meeting_list_view();		
		
		$data['field_label'] = $label;
		$data['meeting_updated_fields'] = $meeting_updated_fields;
		$data['custom_values'] = $custom_values;
		
		// load view
		$this->layout->view('/meetings/index', $data);
	}

	public function related_people($related_people_id){
		// data
		$data = array();

		// init
		$meetings = new Meeting();

	    // show newest first
	    $meetings->order_by('date_start', 'DESC');

	    // select
	    $meetings->select('subject,location,meeting_id,date_entered,date_start,date_end,company_id, event_type, (SELECT company_name FROM sc_companies WHERE company_id = sc_meetings.company_id) as company_name');

		// show non-deleted
		$meetings->group_start()
				->where('deleted','0')
				->group_end();

		$meetings->where('people_id',$related_people_id);

        $search_tab = "advanced";
        //**** CHECK FOR SEARCH SETTINGS *****/
        // if empty, that means it was cleared out or never there to begin with
        if(!empty($_SESSION['search']['meetings'])){

            $meetings->group_start();

            foreach($_SESSION['search']['meetings'] as $key => $value){
                if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end"){
                    $meetings->like($key, $value);
                }

                if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

                    switch($key){
						case'date_entered_start':$meetings->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$meetings->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$meetings->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$meetings->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

                    }
                }

            }

            // set display settings
            if(isset($_SESSION['search']['meetings']['search_type'])){
                if($_SESSION['search']['meetings']['search_type'] == "adv_search_go"){
                    $search_tab = "advanced";
                }
                elseif($_SESSION['search']['meetings']['search_type'] == "saved"){
                    $search_tab = "saved";
                    $data['search_id'] = '';
                }
            }

            $meetings->group_end();

        }
        $data['search_tab'] = $search_tab;

		unset($_SESSION['search']['meetings']['company_id']);
		if(!empty($_SESSION['search']['meetings']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['meetings']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}
		$_SESSION['search']['meetings']['people_id']=$related_people_id;
		if(!empty($_SESSION['search']['meetings']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['meetings']['people_id'])->get();

			// set
			$data['person'] = $acct;

		}

	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $meetings->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $meetings->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'meetings');

	    // set
	    $data['meetings'] = $meetings;
		//$data['searchables'] = $_SESSION['available_search_options'];

		$this->load->helper('list_views');
		list ($label, $meeting_updated_fields, $custom_values) = meeting_list_view();		
		
		$data['field_label'] = $label;
		$data['meeting_updated_fields'] = $meeting_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/meetings/index', $data);
	}


}

/* End of file meetings.php */
/* Location: ./application/controllers/meetings.php */