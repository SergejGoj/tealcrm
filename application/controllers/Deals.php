<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
error_reporting(0);
/**
 * Deals Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Deals extends App_Controller {

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
	 * @url <site>/deals
	 */
	public function index(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$deals = new Deal();

	    // show newest first
	    $deals->order_by('date_entered', 'DESC');

	    // select
	    $deals->select('*,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name, (SELECT CONCAT((first_name),(" "),(last_name)) AS name FROM sc_people WHERE people_id = sc_deals.people_id) AS people_name');

		// show non-deleted
		$deals->group_start()
				->where('deleted','0')
				->group_end();

		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end" && $key != "sales_stage_id" && $key != "assigned_user_id" ){
					$deals->like($key, $value);
				}
				
				if($key == "sales_stage_id" || $key == "assigned_user_id")
				{
					$deals->where_in($key,$value);
				}
				
				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

				if($key == "deal_value_start" || $key == "deal_value_end"){

					// clean up stuff
					$value = str_replace("$", "", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(",", "", $value);

					if(!is_numeric($value)){
						$value = 0;
					}

					switch($key){
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['deals']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['deals']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['deals']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['deals']['people_id'])->get();

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
	    $deals->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	   // echo $deals->check_last_query();

			 $deals->group_start()
				->where('deleted','0')
				->group_end();

	 if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$deals->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

				if($key == "deal_value_start" || $key == "deal_value_end"){

					// clean up stuff
					$value = str_replace("$", "", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(",", "", $value);

					if(!is_numeric($value)){
						$value = 0;
					}

					switch($key){
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['deals']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['deals']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		if(!empty($_SESSION['search']['deals']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['deals']['people_id'])->get();

			// set
			$data['person'] = $acct;

		}

	    // total
	    $total_count = $deals->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'deals');

	    // set
	    $data['deals'] = $deals;
	    
		$this->load->helper('list_views');
		list ($label, $deal_updated_fields, $custom_values) = deal_list_view();		
		
		$data['field_label'] = $label;
		$data['deal_updated_fields'] = $deal_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/deals/index', $data);
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

		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);

			if($post['expected_close_date'] == '__/__/____')
			{
			$post['expected_close_date'] = null;
			}
			else
			{

			$post['expected_close_date'] = gmdate('Y-m-d H:i', strtotime($post['expected_close_date']));
			}



			// now
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');

			$this->form_validation->set_rules('name','Deal Name','max_length[150]');



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



			if ($this->form_validation->run() == TRUE){

				// birthdate

				// new
				$dls = new Deal();

				$id = $this->uuid->v4();
				// Enter values into required fields
				$dls->deal_id = $id;
				$dls->expected_close_date = $post['expected_close_date'];
				$dls->created_by = $user['uacc_uid'];
				$dls->assigned_user_id = $post['assigned_user_id'];
				$dls->name = $post['name'];
				$dls->company_id = $post['company_id'];
				$dls->value = $post['value'];
				$dls->people_id = $post['people_id'];
				$dls->probability = (int)$post['probability'];
				$dls->sales_stage_id = (int)$post['sales_stage_id'];
				$dls->next_step = $post['next_step'];
				$dls->description = $post['description'];


	             //custom data

				$custom_data = array();
				$custom_data['companies_id'] = $id;
				$custom_field_company = $_SESSION['custom_field']['120'];
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
				if( $dls->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new deal.') );

					// redirect
					redirect( 'deals/view/' . $id );
				}
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

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;


		// sales stage
		$sales_stage = dropdownCreator('sales_stage');
		$data['sales_stage'] = $sales_stage;

         //custom field

			if (isset($_SESSION['custom_field']['120']))
		{
		$custom_field_values = $_SESSION['custom_field']['120'];
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
		$this->layout->view('/deals/add', $data);
	}

   /**
	* Edit existing
	*
	* @param varchar $deal_id
	* @return void
	*/
	public function edit( $deal_id ){

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
		$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();
		// set
	    $data['users'] = $users;

		// init
		$dls = new Deal();

		$dls->select('*,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name');

		// find
		$dls->where('deal_id', $deal_id)->get();

		if( isset($dls->deal_id) && $dls->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'deals' );
		}
		else if( ! isset($dls->deal_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'deals' );
		}

		// save
		if( 'save' == $this->input->post('act', true) ){

				// post
				$post = $this->input->post(null, true);

				if($post['expected_close_date'] == '__/__/____')
			{
			$post['expected_close_date'] = null;
			}
			else
			{
			$post['expected_close_date'] = gmdate('Y-m-d H:i', strtotime($post['expected_close_date']));
			}

			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name','Deal Name','max_length[150]');


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

			if ($this->form_validation->run() == TRUE){

				// set array(fields=>values) to update
				$data = array(
					"expected_close_date"=>$post['expected_close_date'],
					"modified_user_id"=>$user['uacc_uid'],
					"assigned_user_id"=>$post['assigned_user_id'],
					"name"=>$post['name'],
					"company_id"=>$company_id,
					"people_id"=>$person_id,
					"value"=>$post['value'],
					"probability"=>(int)$post['probability'],
					"sales_stage_id"=>$post['sales_stage_id'],
					"next_step"=>$post['next_step'],
					"description"=>$post['description']
					);


	            //custom_field update
					$custom_data = array();
				$custom_field_company = $_SESSION['custom_field']['120'];
				foreach($custom_field_company as $custom)
				{
				$field_name = $custom['cf_name'];
				$custom_data['data_value'] = $post[$field_name];
				$custom_value_query = "SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$deal_id."'";
				$query_value = $this->db->query($custom_value_query)->result();

				if(array_key_exists(0,$query_value))
				{
				if($post [$field_name] != "" && $post [$field_name] != " ")
				{
				//$this->db->where(array('custom_fields_id'=>$custom['cf_id'],'companies_id'=>$deal_id));
				$this->db->query("UPDATE sc_custom_fields_data SET data_value ='".$post [$field_name]."' WHERE custom_fields_id = '".$custom['cf_id']."' AND companies_id = '".$deal_id."'");
				}
				else
				{
				$this->db->query("DELETE FROM sc_custom_fields_data WHERE companies_id ='".$deal_id."' and custom_fields_id = '".$custom['cf_id']."' ");
				}
				}
				else
				{
				$field_name = $custom['cf_name'];
				$custom_data['data_value'] = $post [$field_name];
				$custom_data['custom_fields_id'] = $custom['cf_id'];
				$custom_data['companies_id'] = $deal_id;

				if($post [$field_name] !="" && $post [$field_name] !=" "){

				$this->db->insert('sc_custom_fields_data',$custom_data);
				}
				}
				}




				// update
				if( $dls->update($data, NULL, TRUE, array("deal_id"=>$deal_id)) ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully updated deal.') );

					// redirect
					redirect( 'deals/view/' . $deal_id );
				}
			}
		}
		// check(deal_id)
		if( ! isset($dls->deal_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No deal by deal id #%d.', $deal_id) ) );

			// redirect
			redirect( 'deals' );
		}

		// set
		$data['deal'] = $dls;

		// company name
		//$person_names = dropdownpeople();
		//$data['people_names'] = $person_names;

		$this->load->model("general");
		$data['person_name'] = $this->general->getPersonName($dls->people_id);

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		// sales stage
		$sales_stage = dropdownCreator('sales_stage');
		$data['sales_stage'] = $sales_stage;



         //custom_field
		if (isset($_SESSION['custom_field']['120']))
		{

		$custom_field_values = $_SESSION['custom_field']['120'];
		foreach($custom_field_values as $custom)
		{
		if($custom['cf_type'] == 110)
		{
		$custom_field = dropdownCreator($custom['cf_name']);
		$data[$custom['cf_name']] = $custom_field;

		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$deal_id."'")->result();

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
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$deal_id."'")->result();
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
		$this->layout->view('/deals/edit', $data);
	}

   /**
	* View existing
	*
	* @param varchar $deal_id
	* @return void
	*/
	public function view( $deal_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$dls = new Deal();

		$dls->select("*,(SELECT company_name FROM sc_companies WHERE company_id= sc_deals.company_id) as company_name, (SELECT first_name FROM sc_people WHERE people_id= sc_deals.people_id) as person_name, (SELECT last_name FROM sc_people WHERE people_id= sc_deals.people_id) as person_last");

		// find
		$dls->where('deal_id', $deal_id)->get();

		// check
		if( isset($dls->deal_id) && $dls->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'deals' );
		}
		else if( ! isset($dls->deal_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'deals' );
		}

		// set
		$data['deal'] = $dls;

		//fetch activity feed list
		$this->load->model("feed_list");

		//getFeedList($company_id, $category)
		$data['feed_list'] = $this->feed_list->getFeedList($deal_id,3);

		// set last viewed
		//update_last_viewed($deal_id, 3, $dls->name);

		// calculate sales stage TOTAL amount to get us our percentage
		$query = $this->db->query("SELECT (count(*) - 1) as num FROM sc_drop_down_options WHERE related_field_name='sales_stage' ORDER BY order_by");

		//
		$row = $query->first_row();
		$data['total_stages'] = $row->num;

		// calculate percentage for meter
		$data['total_perc'] = ($_SESSION['drop_down_options'][$dls->sales_stage_id]['order_by'] / $row->num) * 100;




		 //custom field
		$check_value = 0;
		$check_field = 0;
		if (isset($_SESSION['custom_field']['120']))
		{
		$data['more_info'] = 1;
		$custom_field_values = $_SESSION['custom_field']['120'];
		$data['custom_field_values'] = $custom_field_values;
		foreach($custom_field_values as $custom)
		{
		$check_field++;
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE companies_id ='".$deal_id."' and custom_fields_id = '".$custom['cf_id']."'")->result();



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

		// GET 5 RELATED MAILS TO THIS DEAL

		$this->db->select('message_id,subject,message,from_name,from_email,timestamp,category,status,relationship_id');
		$this->db->from('sc_messages');
		$this->db->where('relationship_id',$deal_id);
		$this->db->limit(5);
		$query = $this->db->get();

		$related_mail = $query->result();
		$data['related_mail'] = $related_mail;

		// load view
		$this->layout->view('/deals/view', $data);

	}

	public function pipeline(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();


		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$deals = new Deal();
	    // show newest first
	    $deals->order_by('date_entered', 'DESC');
		$deals->where('deleted', 0);
	    // select

	    $deals->select('name,deal_id,date_entered');


		// set data deals variable
		$data['deals'] = $deals;


		// get the sales stages

		$ss_query = "SELECT * from sc_drop_down_options WHERE (related_field_name = 'sales_stage') ORDER BY order_by";
		//print_r($ss_query);
		$sales_stages_result = $this->db->query($ss_query);

		$sales_stages = $sales_stages_result->result();

		foreach ($sales_stages as $ss) {

		if (($ss->name != 'Deal Won') && ($ss->name != 'Deal Lost')) {

			$sales_stage[$ss->drop_down_id] = $ss->name;

			}
		}

		$data['sales_stage'] = $sales_stage;


		// load view
		$this->layout->view('/deals/pipeline', $data);

	}


	public function get_deal_totals() {

		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$sske = $this->input->post('sales_stage_key_edit');
		$ssk  =  $this->input->post('sales_stage_key');
		$ssv = $this->input->post('sales_stage_value');
		$ssp = $this->input->post('ss_prob');

		if (isset($sske)) { $data['sales_stage_key_edit'] = $this->input->post('sales_stage_key_edit');  }
		if (isset($ssk)) { $data['sales_stage_key'] = $this->input->post('sales_stage_key');  }
		if (isset($ssv)) { $data['sales_stage_value'] = $this->input->post('sales_stage_value');  }
//		if (isset($ssp)) { $data['ss_prob'] = $this->input->post('ss_prob'); }


		$this->layout->view('/deals/get_deal_totals', $data);
	}

	public function update_ss() {

		$deal_id = $this->input->post('deal_id');
		$ss_id = $this->input->post('deal_ss');

		$update_deal_query = "UPDATE sc_deals set sales_stage_id='".$ss_id."' WHERE (deal_id = '".$deal_id."')";
		$this->db->query($update_deal_query);

	}


   /**
	* Search
	*
	* @param void
	* @return void
	*/
	public function search($saved_search_id = NULL, $delete = NULL){

		unset($_SESSION['search']['deals']); // kills search session

		$params = array('deals',$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
		$this->load->library('AdvancedSearch', $params); // initiate advancedsearch class

		// check if user is trying to save a search parameter
		if(isset($_POST['saved_search_result'])){
			$this->advancedsearch->search_string = $_POST;
			$this->advancedsearch->Insert_Saved_Search();
			$_SESSION['search']['deals']['search_type'] = "advanced";
		}
		else if($_POST['saved_search_name'] !="")
		{
		$this->advancedsearch->search_string = $_POST;
		$this->advancedsearch->Insert_Saved_Search();
		$_SESSION['search']['deals']['search_type'] = "advanced";
		}

		// did the user hit the CLEAR button, if yes skip everything
		if(!isset($_POST['clear']) && !isset($delete)){
			$this->advancedsearch->Store_Search_Criteria();
		}

		$this->advancedsearch->Set_Search_Type(); // sets what type of search to show

		if(!is_null($delete)){
			$this->advancedsearch->Delete_Saved_Search();
			unset($_SESSION['search']['deals']);
		}

		// store search ID
		$_SESSION['search_id'] = $saved_search_id;

		// done all of our search work, redirect to people view for the magic
		header("Location: ".site_url('deals'));
	}

	 /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $deal_id ){
		// init
		$dls = new Deal();
		// find
		$dls->where('deal_id', $deal_id)->get();

		// soft_delete(array(fields=>values):where clause)
		if( $dls->soft_delete(array("deal_id"=>$deal_id)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted deals.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Deal delete failed.') );
		}

		// redirect
		redirect( 'deals' );
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
			$dls = new Deal();

			// find in
			$dls->where_in('deal_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($dls->all as $dl)
			{
			   	// delete
				if( $dl->soft_delete(array("deal_id"=>$post['ids'][$deleted])) ){
			   		$deleted++;
			   	}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d deal(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Deals delete failed.') );
			}
		}

		// redirect
		redirect( 'deals' );
	}

	public function related_companies($related_company_id){
		// data
		$data = array();

		// init
		$deals = new Deal();

	    // show newest first
	    $deals->order_by('date_entered', 'DESC');

	    // select
	    $deals->select('name,deal_id,value,sales_stage_id,expected_close_date,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name');

		// show non-deleted
		$deals->group_start()
				->where('deleted','0')
				->group_end();

		$deals->where('sc_deals.company_id',$related_company_id);

		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$deals->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

				if($key == "deal_value_start" || $key == "deal_value_end"){

					// clean up stuff
					$value = str_replace("$", "", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(",", "", $value);

					if(!is_numeric($value)){
						$value = 0;
					}

					switch($key){
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['deals']['company_id']=$related_company_id;
		if(!empty($_SESSION['search']['deals']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['deals']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['deals']['people_id']);
		if(!empty($_SESSION['search']['deals']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['deals']['people_id'])->get();

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
	    $deals->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	   // echo $deals->check_last_query();

	    // total
	    $total_count = $deals->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'deals');

		$this->load->helper('list_views');
		list ($label, $deal_updated_fields, $custom_values) = deal_list_view();		
		
		$data['field_label'] = $label;
		$data['deal_updated_fields'] = $deal_updated_fields;
		$data['custom_values'] = $custom_values;

	    // set
	    $data['deals'] = $deals;
	    //var_dump($deals);
	    //exit();


		// load view
		$this->layout->view('/deals/index', $data);
	}

	public function related_people($related_people_id){
		// data
		$data = array();

		// init
		$deals = new Deal();

	    // show newest first
	    $deals->order_by('date_entered', 'DESC');

	    // select
	    $deals->select('name,deal_id,value,sales_stage_id,expected_close_date,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name');

		// show non-deleted
		$deals->group_start()
				->where('deleted','0')
				->group_end();

		$deals->where('people_id',$related_people_id);

		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$deals->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

				if($key == "deal_value_start" || $key == "deal_value_end"){

					// clean up stuff
					$value = str_replace("$", "", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(",", "", $value);

					if(!is_numeric($value)){
						$value = 0;
					}

					switch($key){
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['deals']['company_id']);
		if(!empty($_SESSION['search']['deals']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['deals']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['deals']['people_id']=$related_people_id;
		if(!empty($_SESSION['search']['deals']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['deals']['people_id'])->get();

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
	    $deals->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	   // echo $deals->check_last_query();

	    // total
	    $total_count = $deals->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'deals');

	    // set
	    $data['deals'] = $deals;
	    //var_dump($deals);
	    //exit();

		$this->load->helper('list_views');
		list ($label, $deal_updated_fields, $custom_values) = deal_list_view();		
		
		$data['field_label'] = $label;
		$data['deal_updated_fields'] = $deal_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/deals/index', $data);
	}

	public function export()
	{
	  $deals = new Deal();

	    // show newest first
	    $deals->order_by('date_entered', 'DESC');

	    // select
	    $deals->select('name,deal_id,value,sales_stage_id,expected_close_date,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name');

		// show non-deleted
		$deals->group_start()
				->where('deleted','0')
				->group_end();

		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$deals->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

				if($key == "deal_value_start" || $key == "deal_value_end"){

					// clean up stuff
					$value = str_replace("$", "", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(",", "", $value);

					if(!is_numeric($value)){
						$value = 0;
					}

					switch($key){
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();
	  }

	  $this->index(TRUE);
		// run export

        // load all users
        $deals->get();
        // Output $u->all to /tmp/output.csv, using all database fields.


		$deals->csv_export('../attachments/Deals.csv');

		$this->load->helper('download_helper');
		force_download('Deals.csv', '../attachments/Deals.csv');
	}

	public function export_all()
	{

	$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];

			// init
			$dls = new Deal();

			// find in
			$dls->where_in('deal_id', $ids)->get();

			$dls->csv_export('../attachments/'.$_SERVER['HTTP_HOST'].'/Deals.csv');

		$this->load->helper('download_helper');
		force_download('Deals.csv', '../attachments/'.$_SERVER['HTTP_HOST'].'/Deals.csv');
	    }
         redirect( 'deals' );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */