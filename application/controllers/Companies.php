<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Companies Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Companies extends App_Controller {

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
	 * @url <site>/companies
	 */
	public function index(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();
		

		// data
		$data = array();

		// init
		$companies = new Company();

		// set
		$companies->select('company_id,date_entered,date_modified,modified_user_id,assigned_user_id,created_by,lead_source_id,lead_status_id,company_name,industry,phone_work,phone_fax,email1,email2,email_opt_out,do_not_call,address1,company_type,address2,city,province,postal_code,country,webpage,description,import_datetime');


		// show newest first
		$companies->order_by('date_entered', 'DESC');

		// show non-deleted
		$companies->group_start()
		->where('deleted','0')
		->group_end();

		$companies->where("deleted", 0);

		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['companies'])){

			$companies->group_start();

			foreach($_SESSION['search']['companies'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_type" && $key != "assigned_user_id" && $key != "industry" && $key != "lead_source_id" && $key != "lead_status_id"){
					$companies->like($key, $value);
				}

				if($key == "assigned_user_id" || $key == "industry" || $key == "lead_source_id" || $key == "lead_status_id" || $key == "company_type")
				{
					$companies->where_in($key , $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$companies->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$companies->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$companies->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$companies->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

			}


			// set display settings
			if(isset($_SESSION['search']['companies']['search_type'])){
				if($_SESSION['search']['companies']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['companies']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$companies->group_end();

		}
		$data['search_tab'] = $search_tab;


		// row per page
		$row_per_page = config_item('row_per_page');

		// uri segment for page
		$uri_segment = 2;

		// offset
		$current_page_offset = $this->uri->segment($uri_segment, 0);

		// iterated

		// show regular index page
		$companies->limit($row_per_page, $current_page_offset)->get_iterated();
		// log query

		$companies->group_start()
		->where('deleted','0')
		->group_end();

		if(!empty($_SESSION['search']['companies'])){

			$companies->group_start();

			foreach($_SESSION['search']['companies'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_type" && $key != "assigned_user_id" && $key != "industry" && $key != "lead_source_id" && $key != "lead_status_id"){
					$companies->like($key, $value);
				}

				if($key == "assigned_user_id" || $key == "industry" || $key == "lead_source_id" || $key == "lead_status_id" || $key == "company_type")
				{
					$companies->where_in($key , $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$companies->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$companies->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$companies->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$companies->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}

				}

			}

			// set display settings
			if(isset($_SESSION['search']['companies']['search_type'])){
				if($_SESSION['search']['companies']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['companies']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$companies->group_end();

		}

		// total


		$total_count = $companies->count();


		// links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'companies');

		// set
		$data['companies'] = $companies;

		//COMPANY FIELD LIST TO DISPLAY
		$this->load->helper('list_views');
		list ($label, $company_updated_fields, $custom_values) = company_list_view();

		$data['field_label'] = $label;
		$data['company_updated_fields'] = $company_updated_fields;
		$data['custom_values'] = $custom_values;
		// load view
		$this->layout->view('/companies/index', $data);



	}

	/**
	 * Add new
	 *
	 * @param void
	 * @return void
	 */
	public function add(){


		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();


		// init
		$acct = new Company();

		// save
		if( 'save' == $this->input->post('act', true) ){


			// field validation
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			$this->form_validation->set_rules('company', 'Company/Company Name', 'required|max_length[150]');
			$this->form_validation->set_rules('address1', 'Address 1', 'max_length[255]');
			$this->form_validation->set_rules('address2', 'Address 2', 'max_length[255]');
			$this->form_validation->set_rules('city', 'City', 'max_length[50]');
			$this->form_validation->set_rules('province', 'Province', 'max_length[255]');
			$this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[10]');
			$this->form_validation->set_rules('country','Country','max_length[100]');
			$this->form_validation->set_rules('province','Province','max_length[50]');

			$this->form_validation->set_rules('phone_work','Work Phone','max_length[50]');
			$this->form_validation->set_rules('phone_fax','Fax Number','max_length[50]');
			$this->form_validation->set_rules('email1','Email 1','max_length[120]');
			$this->form_validation->set_rules('email2','Email 2','max_length[120]');
			$this->form_validation->set_rules('webpage','Web Page','max_length[150]');

			$this->form_validation->set_rules('description', 'Description', '');
            $this->form_validation->set_rules('do_not_call', 'Do Not', '');
            $this->form_validation->set_rules('email_opt_out', 'Email Opt Out', '');

			if ($this->form_validation->run() == TRUE){

				// post
				$post = $this->input->post(null, true);
				// now

				$id = $this->uuid->v4();
				// Enter values into required fields
				$acct->company_id = $id;
				$acct->company_type = $post['company_type'];
				$acct->lead_source_id = (int)$post['lead_sources_id'];
				$acct->lead_status_id = (int)$post['lead_status_id'];
				$acct->created_by = $user['uacc_uid'];
				$acct->assigned_user_id = $post['assigned_user_id'];
				$acct->company_name = $post['company'];
				$acct->phone_work = $post['phone_work'];
				$acct->phone_fax = $post['phone_fax'];
				$acct->email1 = $post['email1'];
				$acct->email2 = $post['email2'];
				$acct->address1 = $post['address1'];
				$acct->address2 = $post['address2'];
				$acct->city = $post['city'];
				$acct->province = $post['province'];
				$acct->do_not_call = $post['do_not_call'];
				$acct->email_opt_out = $post['email_opt_out'];
				$acct->postal_code = $post['postal_code'];
				$acct->country = $post['country'];
				$acct->webpage = $post['webpage'];
				$acct->description = $post['description'];
				$acct->industry = $post['industry'];

				// Save new user
				$custom_data = array();
				$custom_data['companies_id'] = $id;
				$custom_field_company = $_SESSION['custom_field']['118'];
				foreach($custom_field_company as $custom)
				{
					$field_name = $custom['cf_name'];
					$custom_data['custom_fields_id'] = $custom['cf_id'];
					$custom_data['data_value'] = $post [$field_name];
					if($post [$field_name] != "" ){
						$this->db->insert('sc_custom_fields_data',$custom_data);
					}
				}



				if( $acct->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new company.') );

					// redirect
					redirect( 'companies/view/' . $id );
				}
			}
			else{

				// we have errors
			}
		}

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		//default assigned user for new contact to the admin user of this company
		$acct->assigned_user_id = $user['uacc_uid'];

		// set
		$data['company'] = $acct;

		// company type
		$company_types = dropdownCreator('account_type');
		$data['company_types'] = $company_types;

		// lead source
		$lead_sources = dropdownCreator('lead_source');
		$data['lead_sources'] = $lead_sources;

		// lead status
		$lead_statuses = dropdownCreator('lead_status');
		$data['lead_statuses'] = $lead_statuses;

		// industrys - $industry_sources
		$industry_sources = dropdownCreator('industry');
		$data['industry_sources'] = $industry_sources;


		if (isset($_SESSION['custom_field']['118']))
		{
			$custom_field_values = $_SESSION['custom_field']['118'];
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
		$this->layout->view('/companies/add', $data);
	}


	/**
	 * Edit existing
	 *
	 * @param varchar $company_id
	 * @return void
	 */
	public function edit( $company_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$this->load->library('session');

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		// init
		$acct = new Company();

		// find
		$acct->where('company_id', $company_id)->get();
		if( isset($acct->company_id) && $acct->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'companies' );
		}
		else if( ! isset($acct->company_id) ){
				// set flash
				notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

				// redirect, don't continue the code
				redirect( 'companies' );
			}
		// save
		if( 'save' == $this->input->post('act', true) ){

			// field validation
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			$this->form_validation->set_rules('company', 'Company/Company Name', 'required|max_length[150]');
			$this->form_validation->set_rules('address1', 'Address 1', 'max_length[255]');
			$this->form_validation->set_rules('address2', 'Address 2', 'max_length[255]');
			$this->form_validation->set_rules('city', 'City', 'max_length[50]');
			$this->form_validation->set_rules('province', 'Province', 'max_length[255]');
			$this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[10]');
			$this->form_validation->set_rules('country','Country','max_length[100]');
			$this->form_validation->set_rules('province','Province','max_length[50]');

			$this->form_validation->set_rules('phone_work','Work Phone','max_length[50]');
			$this->form_validation->set_rules('phone_fax','Fax Number','max_length[50]');
			$this->form_validation->set_rules('email1','Email 1','max_length[120]');
			$this->form_validation->set_rules('email2','Email 2','max_length[120]');
			$this->form_validation->set_rules('webpage','Web Page','max_length[150]');

            $this->form_validation->set_rules('description', 'Description', '');
            $this->form_validation->set_rules('do_not_call', 'Do Not', '');
            $this->form_validation->set_rules('email_opt_out', 'Email Opt Out', '');

			if ($this->form_validation->run() == TRUE){
				// post
				$post = $this->input->post(null, true);
				// now

				// set array(fields=>values) to update
				$data = array(
					"lead_source_id"=>(int)$post['lead_source_id'],
					"lead_status_id"=>(int)$post['lead_status_id'],
					"modified_user_id"=>$user['uacc_uid'],
					"assigned_user_id"=>$post['assigned_user_id'],
					"company_name"=>$post['company'],
					"phone_work"=>$post['phone_work'],
					"phone_fax"=>$post['phone_fax'],
					"email1"=>$post['email1'],
					"email2"=>$post['email2'],
					"address1"=>$post['address1'],
					"address2"=>$post['address2'],
					"city"=>$post['city'],
					"industry"=>$post['industry'],
					"province"=>$post['province'],
					"do_not_call"=>$post['do_not_call'],
					"email_opt_out"=>$post['email_opt_out'],
					"postal_code"=>$post['postal_code'],
					"country"=>$post['country'],
					"description"=>$post['description'],
					"company_type"=>$post['company_type'],
					"webpage"=>$post['webpage']
				);

				//custom_field update
				$custom_data = array();
				$custom_field_company = $_SESSION['custom_field']['118'];
				foreach($custom_field_company as $custom)
				{
					$field_name = $custom['cf_name'];
					$custom_data['data_value'] = $post [$field_name];
					$custom_value_query = "SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$company_id."'";
					$query_value = $this->db->query($custom_value_query)->result();

					if(array_key_exists(0,$query_value))
					{
						if($post [$field_name] != "" && $post [$field_name] != " ")
						{
							//$this->db->where(array('custom_fields_id'=>$custom['cf_id'],'companies_id'=>$company_id));
							$this->db->query("UPDATE sc_custom_fields_data SET data_value ='".$post [$field_name]."' WHERE custom_fields_id = '".$custom['cf_id']."' AND companies_id = '".$company_id."'");
						}
						else
						{
							$this->db->query("DELETE FROM sc_custom_fields_data WHERE companies_id ='".$company_id."' and custom_fields_id = '".$custom['cf_id']."' ");
						}
					}
					else
					{
						$field_name = $custom['cf_name'];
						$custom_data['data_value'] = $post [$field_name];
						$custom_data['custom_fields_id'] = $custom['cf_id'];
						$custom_data['companies_id'] = $company_id;
						if($post [$field_name] != "" && $post [$field_name] != " ")
						{
							$this->db->insert('sc_custom_fields_data',$custom_data);
						}
					}
				}




				// update
				if( $acct->update($data, NULL, TRUE, array("company_id"=>$company_id)) ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully updated company.') );

					// redirect
					redirect( 'companies/view/' . $company_id );
				}
			}else{

				// we have an error in validation
			}
		}

		// check
		if( ! isset($acct->company_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No company by company id #%d.', $company_id) ) );

			// redirect
			redirect( 'companies' );
		}

		// set
		$data['company'] = $acct;


		// company type
		$company_types = dropdownCreator('account_type');
		$data['company_types'] = $company_types;

		// lead source
		$lead_sources = dropdownCreator('lead_source');
		$data['lead_sources'] = $lead_sources;

		// lead status
		$lead_statuses = dropdownCreator('lead_status');
		$data['lead_statuses'] = $lead_statuses;

		// industrys - $industry_sources
		$industry_sources = dropdownCreator('industry');
		$data['industry_sources'] = $industry_sources;

		//custom_field
		if (isset($_SESSION['custom_field']['118']))
		{

			$custom_field_values = $_SESSION['custom_field']['118'];
			foreach($custom_field_values as $custom)
			{

				if($custom['cf_type'] == "Dropdown")
				{
					$custom_field = dropdownCreator($custom['cf_name']);
					$data[$custom['cf_name']] = $custom_field;

					$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$company_id."'")->result();

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
						$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$company_id."'")->result();
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


		//$data['custom_field'] = $custom_query[0];

		// load view
		$this->layout->view('/companies/edit', $data);
	}

	/**
	 * View existing
	 *
	 * @param varchar $company_id
	 * @return void
	 */
	public function view( $company_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$acct = new Company();

		// find
		$acct->where('company_id', $company_id)->get();

		// check
		if( isset($acct->company_id) && $acct->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'companies' );
		}
		else if( ! isset($acct->company_id) ){
				// set flash
				notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

				// redirect, don't continue the code
				redirect( 'companies' );
			}

		// set
		$data['company'] = $acct;

		//fetch activity feed list
		$this->load->model("feed_list");

		//getFeedList($company_id, $category)
		$data['feed_list'] = $this->feed_list->getFeedList($company_id,1);

		// 8-25-14 - Arthur

		//*** CONTACTS ***/
		// Get 5 Related Contacts to this Company
		$related_people = new Person();
		$related_people->limit(10);
		$related_people->where('deleted',0);
		$related_people->where('company_id', $company_id)->get();
		$data['related_people'] = $related_people;

		// Get Total Related Contacts
		$query = $this->db->query("SELECT * FROM sc_people WHERE company_id='".$company_id."' and deleted=0");
		$data['rc_rows'] = $query->num_rows();

		//*** Tasks ***/
		// Get 5 Related Tasks to this Company
		$related_tasks = new Task();
		$related_tasks->limit(10);
		$related_tasks->where('deleted',0);
		$related_tasks->where('company_id', $company_id)->get();
		$data['related_tasks'] = $related_tasks;

		// Get Total Related Tasks
		$query = $this->db->query("SELECT * FROM sc_tasks WHERE company_id='".$company_id."' and deleted=0");
		$data['rt_rows'] = $query->num_rows();


		//*** Deals ***/
		// Get 5 Related Deals to this Company
		$related_deals = new Deal();
		$related_deals->limit(10);
		$related_deals->where('deleted',0);
		$related_deals->where('company_id', $company_id)->get();
		$data['related_deals'] = $related_deals;

	
		// Get Total Related Tasks
		$query = $this->db->query("SELECT * FROM sc_deals WHERE company_id='".$company_id."' and deleted=0");
		$data['rd_rows'] = $query->num_rows();






		//*** Notes ***/

		// Get 5 Related Notes to this Company
		$related_notes = new Note();
		$related_notes->limit(10);
		$related_notes->where('deleted',0);
		$related_notes->where('company_id', $company_id)->get();
		$data['related_notes'] = $related_notes;

		// Get Total Related Notes
		$query = $this->db->query("SELECT * FROM sc_notes WHERE company_id='".$company_id."' and deleted=0");
		$data['rn_rows'] = $query->num_rows();

		//*** Meetings ***/

		// Get 5 Related Meetings to this Company
		$related_meetings = new Meeting();
		$related_meetings->limit(10);
		$related_meetings->where('deleted',0);
		$related_meetings->where('company_id', $company_id)->get();
		$data['related_meetings'] = $related_meetings;

		// Get Total Related Meetings
		$query = $this->db->query("SELECT * FROM sc_meetings WHERE company_id='".$company_id."' and deleted=0");
		$data['rm_rows'] = $query->num_rows();

		// GET 5 RELATED MAILS TO THIS COMPANY

		$this->db->select('message_id,subject,message,from_name,from_email,timestamp,category,status,relationship_id');
		$this->db->from('sc_messages');
		$this->db->where('relationship_id',$company_id);
		$this->db->limit(10);
		$query = $this->db->get();

		$related_mail = $query->result();
		$data['related_mail'] = $related_mail;

		// End 8-25-14 Arthur

		//custom field
		$check_value = 0;
		$check_field = 0;
		if (isset($_SESSION['custom_field']['118']))
		{
			$data['more_info'] = 1;
			$custom_field_values = $_SESSION['custom_field']['118'];
			$data['custom_field_values'] = $custom_field_values;
			foreach($custom_field_values as $custom)
			{
				$check_field++;
				$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE companies_id ='".$company_id."' and custom_fields_id = '".$custom['cf_id']."'")->result();



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

		// set last viewed
		//update_last_viewed($company_id, 1, $acct->company_name);

		// load view
		$this->layout->view('/companies/view', $data);

	}

	/**
	 * Search
	 *
	 * @param void
	 * @return void
	 */
	public function search($saved_search_id = NULL, $delete = NULL){


		unset($_SESSION['search']['companies']); // kills search session

		$params = array('companies',$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
		$this->load->library('AdvancedSearch', $params); // initiate advancedsearch class


		// check if user is trying to save a search parameter
		if(isset($_POST['saved_search_result'])){
			$this->advancedsearch->search_string = $_POST;
			$this->advancedsearch->Insert_Saved_Search();
			$_SESSION['search']['companies']['search_type'] = "advanced";
		}
		else if($_POST['saved_search_name'] !="")
			{
				$this->advancedsearch->search_string = $_POST;
				$this->advancedsearch->Insert_Saved_Search();
				$_SESSION['search']['companies']['search_type'] = "advanced";
			}

		// did the user hit the CLEAR button, if yes skip everything
		if(!isset($_POST['clear']) && !isset($delete)){
			$this->advancedsearch->Store_Search_Criteria();
		}

		$this->advancedsearch->Set_Search_Type(); // sets what type of search to show

		if(!is_null($delete)){
			$this->advancedsearch->Delete_Saved_Search();
			unset($_SESSION['search']['companies']);
		}

		// store search ID
		$_SESSION['search_id'] = $saved_search_id;

		// done all of our search work, redirect to contacts view for the magic
		header("Location: ".site_url('companies'));

	}

	/**
	 * Delete
	 *
	 * @param void
	 * @return void
	 */
	public function delete( $company_id ){
		// check
		if( isset($company_id) ){
			// init
			$acct = new Company();
			// find
			$acct->where('company_id', $company_id)->get();

			// soft_delete(array(fields=>values):where clause)
			if( $acct->soft_delete(array("company_id"=>$company_id)) ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully deleted company.') );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Company delete failed.') );
			}
		}

		// redirect
		redirect( 'companies' );
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
			$accts = new Company();

			// find in
			$accts->where_in('company_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($accts->all as $acct)
			{
				// delete
				if( $acct->soft_delete(array("company_id"=>$post['ids'][$deleted])) ){
					$deleted++;
				}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d company(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Company delete failed.') );
			}
		}

		// redirect
		redirect( 'companies' );
	}


	/**
	 * Export CSV File
	 *
	 * @param void
	 * @return void
	 */
	public function export()
	{
		$companies = new Company();

		$companies->select('company_name,email1,company_id,date_entered,company_type,city');


		// show newest first
		$companies->order_by('date_entered', 'DESC');

		// show non-deleted
		$companies->group_start()
		->where('deleted','0')
		->group_end();

		$companies->where("deleted", 0);
		if(!empty($_SESSION['search']['companies'])){

			$companies->group_start();

			foreach($_SESSION['search']['companies'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end"){
					$companies->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$companies->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$companies->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$companies->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$companies->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
					
				}

			}

			// set display settings
			if(isset($_SESSION['search']['companies']['search_type'])){
				if($_SESSION['search']['companies']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['companies']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$companies->group_end();

		}

		// check for session variables related to search
		$this->index(TRUE);
		// run export

		// load all users
		$companies->get();
		// Output $u->all to /tmp/output.csv, using all database fields.
		$companies->csv_export('../attachments/Companies.csv');

		$this->load->helper('download_helper');
		force_download('Companies.csv', '../attachments/Companies.csv');

	}

	public function export_all()
	{
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];

			// init
			$accts = new Company();

			// find in
			$accts->where_in('company_id', $ids)->get();

			$accts->csv_export('../attachments/'.$_SERVER['HTTP_HOST'].'/Companies.csv');

			$this->load->helper('download_helper');
			force_download('Companies.csv', '../attachments/'.$_SERVER['HTTP_HOST'].'/Companies.csv');
		}
		redirect( 'companies' );
	}
}

/* End of file companies.php */
/* Location: ./application/controllers/companies.php */