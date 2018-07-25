<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
error_reporting(0);
/**
 * people Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class people extends App_Controller {

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
	 * @url <site>/people
	 */
	public function index(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$people = new Person();

	    // show newest first
	    $people->order_by('date_entered', 'DESC');


		// select
	    $people->select('*,(SELECT company_name FROM sc_companies WHERE company_id = sc_people.company_id) as company_name');

		// show non-deleted
		$people->group_start()
				->where('deleted','0')
				->group_end();

		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['people'])){


			//var_dump($_SESSION['search']['people']);exit();

			$people->group_start();

			foreach($_SESSION['search']['people'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "company_viewer" && $key != "contact_type" && $key != "assigned_user_id" && $key != "lead_source_id" && $key != "lead_status_id"){
					$people->like($key, $value);
				}
				
				if($key == "contact_type" || $key == "assigned_user_id" || $key == "lead_source_id" || $key == "lead_status_id")
				{
					$people->where_in($key , $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$people->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$people->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$people->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$people->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

					}
				}

				if($key == "full_name"){
					$people->like("first_name", $value);
					$people->or_like("last_name", $value);
					$people->or_like("concat(first_name,' ',last_name) ", $value);
				}

			}

			// set display settings
			if(isset($_SESSION['search']['people']['search_type'])){
				if($_SESSION['search']['people']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['people']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$people->group_end();

		}

		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['people']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['people']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

	    // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $people->get_paged_iterated(1, 10);

	    // iterated
	    $people->limit($row_per_page, $current_page_offset)->get_iterated();
//		$people->check_last_query();
	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $people->group_start()
				->where('deleted','0')
				->group_end();

	    // total
 	if(!empty($_SESSION['search']['people'])){


			//var_dump($_SESSION['search']['people']);exit();

			$people->group_start();

			foreach($_SESSION['search']['people'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "company_viewer" && $key != "contact_type" && $key != "assigned_user_id" && $key != "lead_source_id" && $key != "lead_status_id"){
					$people->like($key, $value);
				}
				
				if($key == "contact_type" || $key == "assigned_user_id" || $key == "lead_source_id" || $key == "lead_status_id")
				{
					$people->where_in($key , $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$people->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$people->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$people->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$people->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

					}
				}

				if($key == "full_name"){
					$people->like("first_name", $value);
					$people->or_like("last_name", $value);
					$people->or_like("concat(first_name,' ',last_name) ", $value);
				}

			}

			// set display settings
			if(isset($_SESSION['search']['people']['search_type'])){
				if($_SESSION['search']['people']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['people']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$people->group_end();

	}
		// row per page

	     $total_count = $people->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'people');

	    // set
	    $data['people'] = $people;

		$this->load->helper('list_views');
		list ($label, $people_updated_fields, $custom_values) = people_list_view();		

		$data['field_label'] = $label;
		$data['people_updated_fields'] = $people_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/people/index', $data);
	}

   /**
	* Add new
	*
	* @param void
	* @return void
	*/
	public function add($company_id = NULL){


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

		// init
		$cont = new Person();

		// save
		if( 'save' == $this->input->post('act', true) ){



			// post
			$post = $this->input->post(null, true);


			if($post['birthdate'] == '__/__/____')
			{

			$post['birthdate'] = null;

			}
			else
			{

			$post['birthdate'] = gmdate('Y-m-d H:i', strtotime($post['birthdate']));
			}

			// validation
			// field validation
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			// company checking
			// if company ID is blank and we have text in the hidden field, error out
			$this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[50]');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[50]');
            $this->form_validation->set_rules('company_viewer', 'Company Viewer', '');
            $this->form_validation->set_rules('company', 'Company', 'max_length[36]');
            $this->form_validation->set_rules('job_title', 'Job Title', 'max_length[50]');
            $this->form_validation->set_rules('birthdate', 'Birthdate', '');

			$this->form_validation->set_rules('address1', 'Address 1', 'max_length[255]');
			$this->form_validation->set_rules('address2', 'Address 2', 'max_length[255]');
			$this->form_validation->set_rules('city', 'City', 'max_length[50]');
            $this->form_validation->set_rules('province','Province','max_length[50]');
			$this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[10]');
			$this->form_validation->set_rules('country','Country','max_length[100]');

			$this->form_validation->set_rules('phone_work','Phone Work','max_length[50]');
			$this->form_validation->set_rules('phone_home','Phone Home','max_length[50]');
            $this->form_validation->set_rules('phone_mobile','Phone Mobile','max_length[50]');
			$this->form_validation->set_rules('email1','Email 1','max_length[120]');
			$this->form_validation->set_rules('email2','Email 2','max_length[120]');
			//Commented web page out because it is currently not a field in the add form
            //$this->form_validation->set_rules('webpage','Web Page','max_length[150]');

			$this->form_validation->set_rules('do_not_call', 'Do Not Call', '');
			$this->form_validation->set_rules('email_opt_out', 'Email Opt Out', '');
            $this->form_validation->set_rules('description', 'Description', '');

			if ($this->form_validation->run() == TRUE){


				// new
				$cont = new Person();
				$now = gmdate('Y-m-d H:i:s');
				$id = $this->uuid->v4();
				// Enter values into required fields
				$cont->people_id = $id;
				$cont->created_by = $user['uacc_uid'];
				$cont->assigned_user_id = $post['assigned_user_id'];
				$cont->lead_source_id = (int)$post['lead_source_id'];
				$cont->job_title = $post['job_title'];
				$cont->company_id = $post['company'];
				$cont->birthdate = $post['birthdate'];
				$cont->first_name = $post['first_name'];
				$cont->last_name = $post['last_name'];
				$cont->phone_work = $post['phone_work'];
				$cont->phone_home = $post['phone_home'];
				$cont->phone_mobile = $post['phone_mobile'];
				$cont->email1 = $post['email1'];
				$cont->email2 = $post['email2'];
				$cont->contact_type = $post['contact_type'];
				$cont->address1 = $post['address1'];
				$cont->address2 = $post['address2'];
				$cont->city = $post['city'];
				$cont->province = $post['province'];
				$cont->do_not_call = $post['do_not_call'];
				$cont->email_opt_out = $post['email_opt_out'];
				$cont->postal_code = $post['postal_code'];
				$cont->country = $post['country'];
				$cont->description = $post['description'];


				//company insert
				if($post['is_company'] == 1)
				{
				$id_company = $this->uuid->v4();
				$data_account['company_id'] = $id_company;
				$data_account['created_by'] = $user['uacc_uid'];
				$data_account['assigned_user_id'] = $post['assigned_user_id'];
				$data_account['date_entered'] = $now;
				$data_account['company_name'] = $post['company_viewer'];
				$data_account['lead_source_id'] = (int)$post['lead_source_id'];
				$data_account['company_type'] = $post['contact_type'];
				$data_account['phone_work'] = $post['phone_work'];
				$data_account['email1'] = $post['email1'];
				$data_account['email2'] = $post['email2'];
				$data_account['address1'] = $post['address1'];
				$data_account['address2'] = $post['address2'];
				$data_account['city'] = $post['city'];
				$data_account['province'] = $post['province'];
				$data_account['postal_code'] = $post['postal_code'];
				$data_account['do_not_call'] = $post['do_not_call'];
				$data_account['email_opt_out'] = $post['email_opt_out'];
				$data_account['country'] = $post['country'];

				$this->db->insert('sc_companies',$data_account);

				$cont->company_id = $id_company;
				}

				//custom data

				$custom_data = array();
				$custom_data['companies_id'] = $id;
				$custom_field_company = $_SESSION['custom_field']['119'];
				foreach($custom_field_company as $custom)
				{
				$field_name = $custom['cf_name'];
				$custom_data['custom_fields_id'] = $custom['cf_id'];
				$custom_data['data_value'] = $post [$field_name];

				if($post [$field_name] != "" ){

				$this->db->insert('sc_custom_fields_data',$custom_data);
				}
				}



				// Save new user
				if( $cont->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new person.') );

					// redirect
					redirect( 'people/view/'.$id );
				}
			}
		}

		//if the user clicked on Add New Person from the companies view page
		if($company_id != NULL){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $company_id)->get();

			// set
			$data['company'] = $acct;

		}

		// pass an company_id if we have one
		$data['company_id'] = $company_id;

		//default assigned user for new person to the admin user of this company
		$cont->assigned_user_id = $user['uacc_uid'];

		// set
		$data['person'] = $cont;

		$contact_type = dropdownCreator('account_type');
		$data['contact_type'] = $contact_type;

		// lead source
		$lead_sources = dropdownCreator('lead_source');
		$data['lead_sources'] = $lead_sources;


		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;



		//custom field

			if (isset($_SESSION['custom_field']['119']))
		{
		$custom_field_values = $_SESSION['custom_field']['119'];
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
		$this->layout->view('/people/add', $data);
	}

   /**
	* Edit existing
	*
	* @param int $id
	* @return void
	*/
	public function edit( $people_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
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
		$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();
		// set
	    $data['users'] = $users;

		// init
		$cont = new Person();

		//select only company_name from sc_companies and ALL from the other table
	    $cont->select('sc_companies.company_name, sc_people.*');

		//join statement to get the company name
	    $cont->db->join('sc_companies', 'sc_companies.company_id=sc_people.company_id', 'left outer');

		// find
		$cont->where('people_id', $people_id)->get();

		if( isset($cont->people_id) && $cont->deleted!=0){
			// set flash

			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'people' );
		}
		else if( ! isset($cont->people_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'people' );
		}
		// save
		if( 'save' == $this->input->post('act', true) ){

            // validation
            // field validation
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            // company checking
            // if company ID is blank and we have text in the hidden field, error out
            $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[50]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[50]');
            $this->form_validation->set_rules('company_viewer', 'Company Viewer', '');
            $this->form_validation->set_rules('company', 'Company', 'max_length[36]');
            $this->form_validation->set_rules('job_title', 'Job Title', 'max_length[50]');
            $this->form_validation->set_rules('birthdate', 'Birthdate', '');

            $this->form_validation->set_rules('address1', 'Address 1', 'max_length[255]');
            $this->form_validation->set_rules('address2', 'Address 2', 'max_length[255]');
            $this->form_validation->set_rules('city', 'City', 'max_length[50]');
            $this->form_validation->set_rules('province','Province','max_length[50]');
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[10]');
            $this->form_validation->set_rules('country','Country','max_length[100]');

            $this->form_validation->set_rules('phone_work','Phone Work','max_length[50]');
            $this->form_validation->set_rules('phone_home','Phone Home','max_length[50]');
            $this->form_validation->set_rules('phone_mobile','Phone Mobile','max_length[50]');
            $this->form_validation->set_rules('email1','Email 1','max_length[120]');
            $this->form_validation->set_rules('email2','Email 2','max_length[120]');

            $this->form_validation->set_rules('do_not_call', 'Do Not Call', '');
            $this->form_validation->set_rules('email_opt_out', 'Email Opt Out', '');
            $this->form_validation->set_rules('description', 'Description', '');

            if ($this->form_validation->run() == TRUE) {
                $post = $this->input->post(null, true);

                // check if we company or person deleted
                $company_id = "";
                if (!empty($post['company_viewer'])) {
                    if (empty($post['company'])) {
                        $this->form_validation->set_rules('company', 'company', 'required');
                        $this->form_validation->set_message('required', 'Please select a valid %1$s name from the list or create a new %1$s.');
                    } else {
                        $company_id = $post['company'];
                    }
                }


                // post

                // now

                $now = gmdate('Y-m-d H:i:s');

                if ($post['birthdate'] == '__/__/____') {
                    $post['birthdate'] = null;
                } else {
                    $post['birthdate'] = gmdate('Y-m-d H:i', strtotime($post['birthdate']));

                }


                // set array(fields=>values) to update
                if (isset($post['email_opt_out'])) {
                } else {
                    $post['email_opt_out'] = 'N';
                }
                if (isset($post['do_not_call'])) {
                } else {
                    $post['do_not_call'] = 'N';
                }
                $data = array(
                    "date_modified" => $now,
                    "modified_user_id" => $user['uacc_uid'],
                    "birthdate" => $post['birthdate'],
                    "assigned_user_id" => $post['assigned_user_id'],
                    "lead_source_id" => (int)$post['lead_source_id'],
                    "job_title" => $post['job_title'],
                    "company_id" => $company_id,
                    "first_name" => $post['first_name'],
                    "last_name" => $post['last_name'],
                    "phone_work" => $post['phone_work'],
                    "phone_home" => $post['phone_home'],
                    "phone_mobile" => $post['phone_mobile'],
                    "email1" => $post['email1'],
                    "email2" => $post['email2'],
                    "contact_type" => $post['contact_type'],
                    "address1" => $post['address1'],
                    "address2" => $post['address2'],
                    "city" => $post['city'],
                    "province" => $post['province'],
                    "do_not_call" => $post['do_not_call'],
                    "email_opt_out" => $post['email_opt_out'],
                    "postal_code" => $post['postal_code'],
                    "country" => $post['country'],
                    "description" => $post['description']
                );

                //company insert
                if ($post['is_company'] == 1) {
                    $id_company = $this->uuid->v4();
                    $data_account['company_id'] = $id_company;
                    $data_account['created_by'] = $user['uacc_uid'];
                    $data_account['assigned_user_id'] = $post['assigned_user_id'];
                    $data_account['date_entered'] = $now;
                    $data_account['company_name'] = $post['company_viewer'];
                    $data_account['lead_source_id'] = (int)$post['lead_source_id'];
                    $data_account['company_type'] = $post['contact_type'];
                    $data_account['phone_work'] = $post['phone_work'];
                    $data_account['email1'] = $post['email1'];
                    $data_account['email2'] = $post['email2'];
                    $data_account['address1'] = $post['address1'];
                    $data_account['address2'] = $post['address2'];
                    $data_account['city'] = $post['city'];
                    $data_account['province'] = $post['province'];
                    $data_account['postal_code'] = $post['postal_code'];
                    $data_account['do_not_call'] = $post['do_not_call'];
                    $data_account['email_opt_out'] = $post['email_opt_out'];
                    $data_account['country'] = $post['country'];

                    $this->db->insert('sc_companies', $data_account);

                    $data['company_id'] = $id_company;
                }


                //custom_field update
                $custom_data = array();
                $custom_field_company = $_SESSION['custom_field']['119'];
                foreach ($custom_field_company as $custom) {
                    $field_name = $custom['cf_name'];
                    $custom_data['data_value'] = $post [$field_name];
                    $custom_value_query = "SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='" . $custom['cf_id'] . "' and companies_id = '" . $people_id . "'";
                    $query_value = $this->db->query($custom_value_query)->result();


                    if (array_key_exists(0, $query_value)) {
                        if ($post [$field_name] != "" && $post [$field_name] != " ") {
                            //$this->db->where(array('custom_fields_id'=>$custom['cf_id'],'companies_id'=>$people_id));
                            $this->db->query("UPDATE sc_custom_fields_data SET data_value ='" . $post [$field_name] . "' WHERE custom_fields_id = '" . $custom['cf_id'] . "' AND companies_id = '" . $people_id . "'");

                        } else {
                            $this->db->query("DELETE FROM sc_custom_fields_data WHERE companies_id ='" . $people_id . "' and custom_fields_id = '" . $custom['cf_id'] . "' ");
                        }
                    } else {

                        $field_name = $custom['cf_name'];
                        $custom_data['data_value'] = $post [$field_name];
                        $custom_data['custom_fields_id'] = $custom['cf_id'];
                        $custom_data['companies_id'] = $people_id;
                        if ($post [$field_name] != "" && $post [$field_name] != " ") {
                            $this->db->insert('sc_custom_fields_data', $custom_data);
                        }
                    }
                }


                // update
                if ($cont->update($data, NULL, TRUE, array("people_id" => $people_id))) {
                    // set flash
                    notify_set(array('status' => 'success', 'message' => 'Successfully updated person.'));

                    // redirect
                    redirect('people/view/' . $people_id);
                }
            }
		}

		// check(id)
		if( ! isset($cont->people_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No person by that ID #%d.', $people_id) ) );

			// redirect
			redirect( 'people/view/'.$cont->$people_id );
		}

		// set
		$data['person'] = $cont;


		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		$contact_type = dropdownCreator('account_type');
		$data['contact_type'] = $contact_type;

		// lead source
		$lead_sources = dropdownCreator('lead_source');
		$data['lead_sources'] = $lead_sources;

		//custom_field
		if (isset($_SESSION['custom_field']['119']))
		{

		$custom_field_values = $_SESSION['custom_field']['119'];
		foreach($custom_field_values as $custom)
		{
		if($custom['cf_type'] == "Dropdown")
		{
		$custom_field = dropdownCreator($custom['cf_name']);
		$data[$custom['cf_name']] = $custom_field;

		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$people_id."'")->result();

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
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$people_id."'")->result();
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
		$this->layout->view('/people/edit', $data);
	}

   /**
	* View existing
	*
	* @param varchar $people_id
	* @return void
	*/
	public function view( $people_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$cont = new Person();

		//
		$cont->select('*,(SELECT company_name FROM sc_companies WHERE company_id= sc_people.company_id LIMIT 1) as company_name, (SELECT name FROM sc_drop_down_options WHERE drop_down_id= sc_people.lead_source_id LIMIT 1) as lead_source');

		// find
		$cont->where('people_id', $people_id)->get();

		// check
		if( isset($cont->people_id) && $cont->deleted!=0){
			// set flash

			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'people' );
		}
		else if( ! isset($cont->people_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'people' );
		}


		// set
		$data['people'] = $cont;


		//fetch activity feed list
		$this->load->model("feed_list");

		//getFeedList($company_id, $category)
		$data['feed_list'] = $this->feed_list->getFeedList($people_id,2);

		// set last viewed
		$person_name = $cont->first_name." ".$cont->last_name;
		//update_last_viewed($people_id, 2, $person_name);

		//*** Tasks ***/
			// Get 5 Related Tasks to this Person
			$related_tasks = new Task();
			$related_tasks->limit(5);
			$related_tasks->where('deleted',0);
			$related_tasks->where('people_id', $people_id)->get();
			$data['related_tasks'] = $related_tasks;

			// Get Total Related Tasks
			$query = $this->db->query("SELECT * FROM sc_tasks WHERE people_id='".$people_id."' and deleted=0");
			$data['rt_rows'] = $query->num_rows();


		//*** Deals ***/
			// Get 5 Related Deals to this Company
			$related_deals = new Deal();
			$related_deals->limit(5);
			$related_deals->where('deleted',0);
			$related_deals->where('people_id', $people_id)->get();
			$data['related_deals'] = $related_deals;

			// Get Total Related Tasks
			$query = $this->db->query("SELECT * FROM sc_deals WHERE people_id='".$people_id."' and deleted=0");
			$data['rd_rows'] = $query->num_rows();




		//*** Notes ***/

			// Get 5 Related Notes to this Company
			$related_notes = new Note();
			$related_notes->limit(5);
			$related_notes->where('deleted',0);
			$related_notes->where('people_id', $people_id)->get();
			$data['related_notes'] = $related_notes;

			// Get Total Related Notes
			$query = $this->db->query("SELECT * FROM sc_notes WHERE people_id='".$people_id."' and deleted=0");
			$data['rn_rows'] = $query->num_rows();




		//*** Meetings ***/

			// Get 5 Related Meetings to this Company
			$related_meetings = new Meeting();
			$related_meetings->limit(5);
			$related_meetings->where('deleted',0);
			$related_meetings->where('people_id', $people_id)->get();
			$data['related_meetings'] = $related_meetings;

			// Get Total Related Meetings
			$query = $this->db->query("SELECT * FROM sc_meetings WHERE people_id='".$people_id."' and deleted=0");
			$data['rm_rows'] = $query->num_rows();

			// GET 5 RELATED MAILS TO THIS PRESON

			$this->db->select('message_id,subject,message,from_name,from_email,timestamp,category,status,relationship_id');
			$this->db->from('sc_messages');
			$this->db->where('relationship_id',$people_id);
			$this->db->limit(5);
			$query = $this->db->get();

			$related_mail = $query->result();
			$data['related_mail'] = $related_mail;

		  //custom field
		$check_value = 0;
		$check_field = 0;
		if (isset($_SESSION['custom_field']['119']))
		{
		$data['more_info'] = 1;
		$custom_field_values = $_SESSION['custom_field']['119'];
		$data['custom_field_values'] = $custom_field_values;
		foreach($custom_field_values as $custom)
		{
		$check_field++;
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE companies_id ='".$people_id."' and custom_fields_id = '".$custom['cf_id']."'")->result();



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

		// load view
		$this->layout->view('/people/view', $data);
	}

   /**
	* Search
	*
	* @param void
	* @return void
	*/
	public function search($saved_search_id = NULL, $delete = NULL){

        unset($_SESSION['search']['people']); // kills search session

        $params = array('people',$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
        $this->load->library('AdvancedSearch', $params); // initiate advancedsearch class

        // check if user is trying to save a search parameter

		if(isset($_POST['saved_search_result'])){
            $this->advancedsearch->search_string = $_POST;
            $this->advancedsearch->Insert_Saved_Search();
            $_SESSION['search']['people']['search_type'] = "advanced";
        }
		else if($_POST['saved_search_name'] !="")
		{
		$this->advancedsearch->search_string = $_POST;
		$this->advancedsearch->Insert_Saved_Search();
		$_SESSION['search']['people']['search_type'] = "advanced";
		}

        // did the user hit the CLEAR button, if yes skip everything
        if(!isset($_POST['clear']) && !isset($delete)){
            $this->advancedsearch->Store_Search_Criteria();
        }

        $this->advancedsearch->Set_Search_Type(); // sets what type of search to show

        if(!is_null($delete)){
            $this->advancedsearch->Delete_Saved_Search();
            unset($_SESSION['search']['people']);
        }

        // store search ID
        $_SESSION['search_id'] = $saved_search_id;

		// done all of our search work, redirect to people view for the magic
		header("Location: ".site_url('people'));
}


	 /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $people_id ){
		// init
		$cont = new Person();
		// find
		$cont->where('people_id', $people_id)->get();

		// soft_delete(array(fields=>values):where clause)
		if( $cont->soft_delete(array("people_id"=>$people_id)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted person.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Person delete failed.') );
		}

		// redirect
		redirect( 'people' );
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
			$cont = new Person();

			// find in
			$cont->where_in('people_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($cont->all as $cnt)
			{
			   	// delete
				if( $cnt->soft_delete(array("people_id"=>$post['ids'][$deleted])) ){
			   		$deleted++;
			   	}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d person(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Person delete failed.') );
			}
		}

		// redirect
		redirect( 'people' );
	}

	public function related_companies($related_company_id){
		// data
		$data = array();

		// init
		$people = new Person();

	    // show newest first
	    $people->order_by('date_entered', 'DESC');


		// select
	    $people->select('first_name,last_name,job_title,email1,phone_mobile,phone_work,people_id,date_entered,contact_type');

		// show non-deleted
		$people->group_start()
				->where('deleted','0')
				->group_end();

		$people->where('company_id',$related_company_id);

		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['people'])){


			//var_dump($_SESSION['search']['people']);exit();

			$people->group_start();

			foreach($_SESSION['search']['people'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "company_viewer"){
					$people->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$people->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$people->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$people->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$people->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

					}
				}

				if($key == "full_name"){
					$people->like("first_name", $value);
					$people->or_like("last_name", $value);
					$people->or_like("concat(first_name,' ',last_name) ", $value);
				}

			}

			// set display settings
			if(isset($_SESSION['search']['people']['search_type'])){
				if($_SESSION['search']['people']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['people']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$people->group_end();

		}

		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['people']['company_id']=$related_company_id;
		if(!empty($_SESSION['search']['people']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['people']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

	    // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $people->get_paged_iterated(1, 10);

	    // iterated
	    $people->limit($row_per_page, $current_page_offset)->get_iterated();
//		$people->check_last_query();
	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $people->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'people');

	    // set
	    $data['people'] = $people;
	    
	    		$this->load->helper('list_views');
		list ($label, $people_updated_fields, $custom_values) = people_list_view();		

		$data['field_label'] = $label;
		$data['people_updated_fields'] = $people_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/people/index', $data);
	}

	public function export()
	{

	$people = new Person();

	    // show newest first
	    $people->order_by('date_entered', 'DESC');


		// select
	    $people->select('first_name,last_name,job_title,email1,phone_mobile,phone_work,people_id,date_entered,contact_type');

		// show non-deleted
		$people->group_start()
				->where('deleted','0')
				->group_end();

		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['people'])){


			//var_dump($_SESSION['search']['people']);exit();

			$people->group_start();

			foreach($_SESSION['search']['people'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "company_viewer"){
					$people->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$people->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$people->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$people->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$people->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

					}
				}

				if($key == "full_name"){
					$people->like("first_name", $value);
					$people->or_like("last_name", $value);
					$people->or_like("concat(first_name,' ',last_name) ", $value);
				}

			}

			// set display settings
			if(isset($_SESSION['search']['people']['search_type'])){
				if($_SESSION['search']['people']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['people']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$people->group_end();



	}
	$this->index(TRUE);
		// run export

        // load all users
        $people->get();
        // Output $u->all to /tmp/output.csv, using all database fields.
        $people->csv_export('../attachments/people.csv');

	$this->load->helper('download_helper');
		force_download('people.csv', '../attachments/people.csv');
	}





	public function export_all()
	{
	$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) )
		{
			// ids
			$ids = $post['ids'];

			// init
			$person = new Person();

			// find in
			$person->where_in('people_id', $ids)->get();

	      $person->csv_export('../attachments/people.csv');

		$this->load->helper('download_helper');
		force_download('people.csv', '../attachments/people.csv');
	    }
	redirect( 'people' );
	}

}

/* End of file people.php */
/* Location: ./application/controllers/people.php */
