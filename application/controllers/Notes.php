<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
error_reporting(0);
/**
 * Notes Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Notes extends App_Controller {

   /**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct("Note","notes","note");

	}


   /**
	* Add new
	*
	* @param void
	* @return void
	*/
	public function add2121($company_id = NULL, $people_id = NULL){

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
			// now
			// new

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

			if ($this->form_validation->run() == TRUE){

				$nts = new Note();

				$id = $this->uuid->v4();
				// Enter values into required fields
				$nts->note_id = $id;
				//$nts->date_modified = $now;
				$nts->created_by = $user['uacc_uid'];
				$nts->assigned_user_id = $post['assigned_user_id'];
				$nts->subject = $post['subject'];
				//$nts->project_id = $post['project_id'];
				$nts->company_id = $post['company_id'];
				$nts->people_id = $post['people_id'];
				$nts->description = $post['description'];

				//handle attachments upload
				//go down public directory by one
				$config['upload_path'] = './../attachments/'.$_SERVER['HTTP_HOST'].'/';
				$config['allowed_types'] = 'jpg|png|doc|docx|xml|pdf|html|txt|csv';
				$config['max_size']	= '25600';//25mb

				$this->load->library('upload', $config);

				$attachment_warning = "none";
				$file_data = $this->upload->data();

				//if the file passed JS validation
				if($post['note_attach_valid'] == "1"){
					if ( $this->upload->note_upload("attach_file",$id) ){
						$file_data = $this->upload->data();
						$nts->filename_original = $file_data['orig_name'];
						$nts->filename_mimetype = $file_data['file_type'];
					}else{
						$attachment_warning = $this->upload->display_errors();
					}
				}

				//custom data

				$custom_data = array();
				$custom_data['companies_id'] = $id;
				$custom_field_company = $_SESSION['custom_field']['121'];
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
				if( $nts->save() ){
					// set flash
					if($attachment_warning == "none")
						notify_set( array('status'=>'success', 'message'=>'Successfully created new note.') );
					else
						notify_set( array('status'=>'warning', 'message'=>$attachment_warning.' Note was created successfully.') );

					// redirect
					redirect( 'notes/view/' .$id );
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

		//custom field

			if (isset($_SESSION['custom_field']['121']))
		{
		$custom_field_values = $_SESSION['custom_field']['121'];
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
		$this->layout->view('/notes/add', $data);
	}

   /**
	* Edit existing
	*
	* @param varchar $note_id
	* @return void
	*/
	public function edit( $note_id ){

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
		$nts = new Note();

		//select only company_name from sc_companies and ALL from the other table
	    $nts->select("*, (SELECT company_name FROM sc_companies WHERE company_id= sc_notes.company_id) as company_name");

		// find
		$nts->where('note_id', $note_id)->get();

		if( isset($nts->note_id) && $nts->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'notes' );
		}
		else if( ! isset($nts->note_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'notes' );
		}
		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);
			// now
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

			if ($this->form_validation->run() == TRUE){


				//handle attachments upload
				//go down public directory by one
				$config['upload_path'] = './../attachments/'.$_SERVER['HTTP_HOST'].'/';
				$config['allowed_types'] = 'jpg|png|doc|docx|csv|pdf|html|txt|csv';
				$config['max_size']	= '25600';//25mb

				$this->load->library('upload', $config);

				$attachment_warning = "none";
				//if the file passed JS validation
				if($post['note_attach_valid'] == "1"){
					if ( $this->upload->note_upload("attach_file",$note_id) ){
						$file_data = $this->upload->data();
						$nts->filename_original = $file_data['orig_name'];
						$nts->filename_mimetype = $file_data['file_type'];
						$file_array = array("filename_original" => $file_data['orig_name'], "filename_mimetype" => $file_data['file_type']);
					}else{

						$attachment_warning = $this->upload->display_errors();

					}
				}

				// set array(fields=>values) to update
				$data = array(
					"date_modified"=>$now,
					"modified_user_id"=>$user['uacc_uid'],
					"assigned_user_id"=>$post['assigned_user_id'],
					"subject"=>$post['subject'],
					"company_id"=>$company_id,
					"people_id"=>$person_id,
					"description"=>$post['description']
					);

				//if the user added new attachment
				if(isset($file_array)){
					$data = array_merge($data, $file_array);
				}


	             //custom_field update
					$custom_data = array();
				$custom_field_company = $_SESSION['custom_field']['121'];
				foreach($custom_field_company as $custom)
				{
				$field_name = $custom['cf_name'];
				$custom_data['data_value'] = $post [$field_name];
				$custom_value_query = "SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$note_id."'";
				$query_value = $this->db->query($custom_value_query)->result();
				if(array_key_exists(0,$query_value))
				{
				if($post [$field_name] != "" && $post [$field_name] != " ")
				{
				//$this->db->where(array('custom_fields_id'=>$custom['cf_id'],'companies_id'=>$note_id));
				$this->db->query("UPDATE sc_custom_fields_data SET data_value ='".$post [$field_name]."' WHERE custom_fields_id = '".$custom['cf_id']."' AND companies_id = '".$note_id."'");
				}
				else
				{
				$this->db->query("DELETE FROM sc_custom_fields_data WHERE companies_id ='".$note_id."' and custom_fields_id = '".$custom['cf_id']."' ");
				}
				}
				else
				{
				$field_name = $custom['cf_name'];
				$custom_data['data_value'] = $post [$field_name];
				$custom_data['custom_fields_id'] = $custom['cf_id'];
				$custom_data['companies_id'] = $note_id;
				if($post [$field_name] != "" && $post [$field_name] != " ")
				{
				$this->db->insert('sc_custom_fields_data',$custom_data);
				}
				}
				}






				// update
				if( $nts->update($data, NULL, TRUE, array("note_id"=>$note_id)) ){
					// set flash
					if($attachment_warning == "none")
						notify_set( array('status'=>'success', 'message'=>'Successfully updated note.') );
					else
						notify_set( array('status'=>'warning', 'message'=>$attachment_warning. ' Note was updated successfully.') );

					// redirect
					redirect( 'notes/view/' . $note_id );
				}
			}
		}
		// check(note_id)
		if( ! isset($nts->note_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No note by note id #%d.', $note_id) ) );

			// redirect
			redirect( 'notes' );
		}

		// set
		$data['note'] = $nts;

		// company name
		//$person_names = dropdownpeople();
		//$data['person_names'] = $person_names;
		$this->load->model("general");
		$data['company_name'] = $this->general->getAccountName($nts->company_id);

		$this->load->model("general");
		$data['person_name'] = $this->general->getPersonName($nts->people_id);

		//$this->load->model("general");
		//$data['subject'] = $this->general->getprojectName($nts->project_id);

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

		//custom_field
		if (isset($_SESSION['custom_field']['121']))
		{

		$custom_field_values = $_SESSION['custom_field']['121'];
		foreach($custom_field_values as $custom)
		{
		if($custom['cf_type'] == 110)
		{
		$custom_field = dropdownCreator($custom['cf_name']);
		$data[$custom['cf_name']] = $custom_field;

		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$note_id."'")->result();

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
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE custom_fields_id ='".$custom['cf_id']."' and companies_id = '".$note_id."'")->result();
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
		$this->layout->view('/notes/edit', $data);
	}

   /**
	* View existing
	*
	* @param varchar $note_id
	* @return void
	*/
	public function view( $note_id ){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();

		// data
		$data = array();

		// init
		$nts = new Note();

		$nts->select("*,(SELECT company_name FROM sc_companies WHERE company_id= sc_notes.company_id) as company_name, (SELECT first_name FROM sc_people WHERE people_id= sc_notes.people_id) as person_name, (SELECT last_name FROM sc_people WHERE people_id= sc_notes.people_id) as person_last");

		// find
		$nts->where('note_id', $note_id)->get();

		// check(note_id)
		if( isset($nts->note_id) && $nts->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'notes' );
		}
		else if( ! isset($nts->note_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'notes' );
		}

		// set
		$data['note'] = $nts;

		//fetch activity feed list
		$this->load->model("feed_list");

		//getFeedList($company_id, $category)
		$data['feed_list'] = $this->feed_list->getFeedList($note_id,4);

		// set last viewed
		//update_last_viewed($note_id, 5, $nts->subject);

		  //custom field
		$check_value = 0;
		$check_field = 0;
		if (isset($_SESSION['custom_field']['121']))
		{
		$data['more_info'] = 1;
		$custom_field_values = $_SESSION['custom_field']['121'];
		$data['custom_field_values'] = $custom_field_values;
		foreach($custom_field_values as $custom)
		{
		$check_field++;
		$custom_query = $this->db->query("SELECT * FROM sc_custom_fields_data WHERE companies_id ='".$note_id."' and custom_fields_id = '".$custom['cf_id']."'")->result();

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
		if($check_value == $check_field)
		{
		$data['more_info'] = 0;
		}


		//custom field



		// load view
		$this->layout->view('/notes/view', $data);
	}

   /**
	* Search
	*
	* @param void
	* @return void
	*/
    public function search($saved_search_id = NULL, $delete = NULL){


        unset($_SESSION['search']['notes']); // kills search session

        $params = array('notes',$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
        $this->load->library('AdvancedSearch', $params); // initiate advancedsearch class

        // check if user is trying to save a search parameter
        if(isset($_POST['saved_search_result'])){
            $this->advancedsearch->search_string = $_POST;
            $this->advancedsearch->Insert_Saved_Search();
            $_SESSION['search']['notes']['search_type'] = "advanced";
        }
		else if($_POST['saved_search_name'] !="")
		{
		$this->advancedsearch->search_string = $_POST;
		$this->advancedsearch->Insert_Saved_Search();
		$_SESSION['search']['notes']['search_type'] = "advanced";
		}

        // did the user hit the CLEAR button, if yes skip everything
        if(!isset($_POST['clear']) && !isset($delete)){
            $this->advancedsearch->Store_Search_Criteria();
        }

        $this->advancedsearch->Set_Search_Type(); // sets what type of search to show

        if(!is_null($delete)){
            $this->advancedsearch->Delete_Saved_Search();
            unset($_SESSION['search']['notes']);
        }

        // store search ID
        $_SESSION['search_id'] = $saved_search_id;

        // done all of our search work, redirect to people view for the magic
        header("Location: ".site_url('notes'));

    }

	 /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $note_id ){
		// init
		$nts = new Note();
		// find
		$nts->where('note_id', $note_id)->get();

		// soft_delete(array(fields=>values):where clause)
		if( $nts->soft_delete(array("note_id"=>$note_id)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted note.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Note delete failed.') );
		}

		// redirect
		redirect( 'notes' );
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
			$nts = new Note();

			// find in
			$nts->where_in('note_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($nts->all as $nt)
			{
			   	// delete
				if( $nt->soft_delete(array("note_id"=>$post['ids'][$deleted])) ){
			   		$deleted++;
			   	}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d note(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Note delete failed.') );
			}
		}

		// redirect
		redirect( 'notes' );
	}

	public function related_companies($related_company_id){
		// data
		$data = array();

		// init
		$notes = new Note();

	    // show newest first
	    $notes->order_by('date_entered', 'DESC');

	    // select
	    $notes->select('subject,note_id,date_entered,filename_original,(SELECT company_name FROM sc_companies WHERE company_id= sc_notes.company_id) as company_name, (SELECT first_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_first, (SELECT last_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_last');

		// show non-deleted
		$notes->group_start()
				->where('deleted','0')
				->group_end();

		$notes->where('company_id',$related_company_id);
		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['notes'])){

			$notes->group_start();

			foreach($_SESSION['search']['notes'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$notes->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$notes->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$notes->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$notes->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$notes->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						break;
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
						case'deal_value_start':$notes->where('value >=', $value);break;
						case'deal_value_end':$notes->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['notes']['search_type'])){
				if($_SESSION['search']['notes']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$notes->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['notes']['company_id']=$related_company_id;
		if(!empty($_SESSION['search']['notes']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['notes']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['notes']['people_id']);
		if(!empty($_SESSION['search']['notes']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['notes']['people_id'])->get();

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
	    $notes->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $notes->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'notes');

	    // set
	    $data['notes'] = $notes;

		$this->load->helper('list_views');
		list ($label, $note_updated_fields, $custom_values) = notes_list_view();		
		
		$data['field_label'] = $label;
		$data['note_updated_fields'] = $note_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/notes/index', $data);
	}

	public function related_people($related_people_id){
		// data
		$data = array();

		// init
		$notes = new Note();

	    // show newest first
	    $notes->order_by('date_entered', 'DESC');

	    // select
	    $notes->select('subject,note_id,date_entered,filename_original,(SELECT company_name FROM sc_companies WHERE company_id= sc_notes.company_id) as company_name, (SELECT first_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_first, (SELECT last_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_last');

		// show non-deleted
		$notes->group_start()
				->where('deleted','0')
				->group_end();

		$notes->where('people_id',$related_people_id);
		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['notes'])){

			$notes->group_start();

			foreach($_SESSION['search']['notes'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$notes->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$notes->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$notes->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$notes->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$notes->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
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
						case'deal_value_start':$notes->where('value >=', $value);break;
						case'deal_value_end':$notes->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['notes']['search_type'])){
				if($_SESSION['search']['notes']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$notes->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['notes']['company_id']);
		if(!empty($_SESSION['search']['notes']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['notes']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['notes']['people_id']=$related_people_id;
		if(!empty($_SESSION['search']['notes']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['notes']['people_id'])->get();

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
	    $notes->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $notes->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'notes');

	    // set
	    $data['notes'] = $notes;

		$this->load->helper('list_views');
		list ($label, $note_updated_fields, $custom_values) = notes_list_view();		
		
		$data['field_label'] = $label;
		$data['note_updated_fields'] = $note_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/notes/index', $data);
	}
	public function download_note($fileid)
	{
	$data = array();
		// init
		$nts = new Note();

		// find
		$nts->where('note_id', $fileid)->get();

	$filename = $nts->filename_original;


	$image_name = $fileid;
	$image_path = $this->config->item('') . "./../attachments/".$_SERVER['HTTP_HOST']."/$image_name";
	header('Content-Type: application/octet-stream');
	header("Content-Disposition: attachment; filename=$filename");
	ob_clean();
	flush();
	readfile($image_path);
	}

}

/* End of file notes.php */
/* Location: ./application/controllers/notes.php */