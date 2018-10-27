<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Settings Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Settings extends App_Controller {

	/**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
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
		if(method_exists($this,$method)){
			// remove classname and method name form uri
			call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
		}else{
			// erro
			show_404(sprintf('controller method [%s] not implemented!', $method));
		}
	}



	/**
	 * index, content, all page routed here if 404 /not found
	 *
	 * @param void
	 */
	function index($section='crm-settings')
	{

		// field validation
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('business_name', 'Business Name', 'required|max_length[255]');
		$this->form_validation->set_rules('address1', 'Address 1', 'max_length[255]');
		$this->form_validation->set_rules('address2', 'Address 2', 'max_length[255]');
		$this->form_validation->set_rules('city', 'City', 'max_length[255]');
		$this->form_validation->set_rules('province', 'Province', 'max_length[255]');
		$this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[255]');
		$this->form_validation->set_rules('country','Country','min_length[2]');
		$this->form_validation->set_rules('province','Province','min_length[2]');
		$this->form_validation->set_message('min_length','Please make a %s selection');
		$this->form_validation->set_rules('billing_email', 'Billing Email Address', 'required');

		// data
		$data = array();

		//logedin user
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();

		// get all of settings data from database
		// init
		$setts = new Setting();
		// find
		$setts->where('id',1)->get();

		// check and see if we have an update
		if( $section == $this->input->post('act', true) ){

			$post = $this->input->post(null, true);

			// act
			switch( $section ) {
				case 'crm-settings':


					if ($this->form_validation->run() == TRUE){

							// post
							$post = $this->input->post(null, true);


							// set
							$setts->business_name = $post['business_name'];
							$setts->address1 = $post['address1'];
							$setts->address2 = $post['address2'];
							$setts->city = $post['city'];
							$setts->province = $post['province'];
							$setts->postal_code = $post['postal_code'];
							$setts->country = $post['country'];
							$setts->timezone = $post['timezone'];
							$setts->billing_email = $post['billing_email'];
							$_SESSION['timezone'] = $post['timezone'];

							// Save new user
							if( $setts->save() ){
								// set flash
								notify_set( array('status'=>'success', 'message'=>'Successfully updated settings.','settings_crm_settings') );
								// redirect
								redirect( 'settings' );
							}

					}
					else{
							// validation errors
							//echo "we have errors";

					}

			break;
			}


		}

		//get all users under sc_user_accounts
		$data['flexi_users'] = $this->flexi_auth->get_users(FALSE, array("uacc_active"=>1))->result();
		$data['flexi_users_inactive'] = $this->flexi_auth->get_users(FALSE, array("uacc_active"=>0))->result();

		//create array that holds the group_id as key and group_name as value
		$flexi_grps = array();
		foreach($this->flexi_auth->get_groups()->result() as $group){
			$flexi_grps[$group->ugrp_id] = $group->ugrp_name;
		}
		$data['flexi_groups'] = $flexi_grps;

		// set drop downs

		// country drop down
		$countries = dropdownCountries();
		$data['countries'] = $countries;

		// province drop down
		$provinces = dropdownProvinces();
		$data['provinces'] = $provinces;

		// set data
		$data['setting'] = $setts;

		// set
		$data['section'] = $section;

		// load plan and usage information on company




		// load view
		$this->layout->view('/settings/index', $data);
	}

	/**
	 * Products
	 * Lists all Products
	 *
	 * @url <site>/products
	 */

	 public function products($action=0, $id=NULL){


		if(is_numeric($action)){

		 	$data = array();

		 	$products = new Product();

		 	// no deleted templates
		 	$products->where("deleted", 0);

			$products->order_by('date_entered', 'DESC');

		    // row per page
		    $row_per_page = config_item('row_per_page');

		    // uri segment for page
		    $uri_segment = 3;

		    // offset
		    $current_page_offset = $this->uri->segment($uri_segment, 0);

		    // iterated
		    $products->limit($row_per_page, $current_page_offset)->get_iterated();

			$products1 = new Product();

		 	// no deleted templates
		 	$products1->where("deleted", 0);

			$products1->order_by('date_entered', 'DESC');

		    // row per page
		    $row_per_page = config_item('row_per_page');

		    // uri segment for page
		    $uri_segment = 3;

		    // offset
		    $current_page_offset = $this->uri->segment($uri_segment, 0);

		    // total
		    $total_count =  $products1->count();

		    // links
			$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'settings/products');


		 	$data['products'] = $products;

			$this->layout->view('/settings/products', $data);

		}

		else{

			switch($action){
				case 'edit': $this->products_edit($id); break;
				case 'add': $this->products_add(); break;
				case 'delete': $this->products_delete($id); break;
			}

		}
	}
	/**
	 * Products : Delete
	 *
	 * @url <site>/products
	 */
	private function products_delete($id){

		// check
		if( isset($id) ){
			// init
			$prod = new Product();
			// find
			$prod->where('product_id', $id)->get();

			// soft_delete(array(fields=>values):where clause)
			if( $prod->soft_delete(array("product_id"=>$id)) ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully deleted product.') );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Product delete failed.') );
			}
		}

		// redirect
		redirect( 'settings/products' );
	}

	/**
	 * Products : Edit
	 *
	 *
	 * @url <site>/products
	 */
	private function products_edit($id){

		 	$data = array();


		 	$products = new Product();

		 	// no deleted templates
		 	$products->where("product_id", $id)->get();

			if( isset($products->product_id) && $products->deleted!=0){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( 'settings/products' );
		}
		else if( ! isset($products->product_id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist.') ) );

			// redirect, don't continue the code
			redirect( 'settings/products' );
		}

		 	if( 'save' == $this->input->post('act', true) ){
				// post
				$post = $this->input->post(null, true);
				// now

				$data = array(
					"modified_user_id" => $_SESSION['user']['id'],
					"product_name" => $post['product_name'],
					"product_type" => $post['product_type'],
					"manufacturer_part_number" => $post['manufacturer_part_number'],
					"vendor_part_number" => $post['vendor_part_number'],
					"cost" => $post['cost'],
					"list_price" => $post['list_price'],
					"tax_percentage" => $post['tax_percentage'],
					"quantity_in_stock" => $post['quantity_in_stock'],
					"description" => $post['description'],
					"active" => $post['active'],
					);

				// Save new user
				if( $products->update($data, NULL, TRUE, array("product_id"=>$id)) ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully updated product.') );

					// redirect
					redirect( 'settings/products');
				}

			}


		 	$data['product'] = $products;
		 	$product_types = dropdownCreator('product_type');
			$data['product_types'] = $product_types;

			$this->layout->view('/settings/products_edit', $data);

	}


	/**
	 * Products : Add
	 * Lists all Products
	 *
	 * @url <site>/products
	 */
	private function products_add(){
		// data
		$data = array();

		// save
		if( 'save' == $this->input->post('act', true) ){

			// field validation
			$this->load->helper(array('form','url'));
			$this->load->library('form_validation');
 			// product name and list price are required
			$this->form_validation->set_rules('product_name','Product Name','required');
			$this->form_validation->set_rules('list_price','List Price','required');

			if ($this->form_validation->run() == TRUE){

				// post
				$post = $this->input->post(null, true);
				// now
				// new
				$dls = new Product();

				$id = $this->uuid->v4();
				// Enter values into required fields
				$dls->product_id = $id;
				//$dls->date_modified = $now;
				$dls->product_name = $post['product_name'];
				$dls->product_type = $post['product_type'];
				$dls->manufacturer_part_number = $post['manufacturer_part_number'];
				$dls->vendor_part_number = $post['vendor_part_number'];
				$dls->cost = $post['cost'];
				$dls->list_price = $post['list_price'];
				$dls->tax_percentage = $post['tax_percentage'];
				$dls->quantity_in_stock = $post['quantity_in_stock'];
				$dls->description = $post['description'];
				$dls->active = $post['active'];
				$dls->created_by = $_SESSION['user']['id'];

				// Save new user
				if( $dls->save() ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new product.') );

					// redirect
					//redirect( 'settings/products/edit/' . $id );
					redirect( 'settings/products');

				}
			}
		}

		$product_types = dropdownCreator('product_type');
		$data['product_types'] = $product_types;

		// load view
		$this->layout->view('/settings/products_add', $data);

	}

	/**
	 * Templates
	 * Lists all Proposal templates
	 *
	 * @url <site>/templates
	 */

	 public function templates($action=NULL, $id=NULL){


		if($action == NULL){

		 	$data = array();

		 	$templates = new Template();

		 	// no deleted templates
		 	$templates->where("deleted", 0);

		    // row per page
		    $row_per_page = config_item('row_per_page');

		    // uri segment for page
		    $uri_segment = 2;

		    // offset
		    $current_page_offset = $this->uri->segment($uri_segment, 0);

		    // iterated
		    $templates->limit($row_per_page, $current_page_offset)->get_iterated();

		    // total
		    $total_count = $templates->count();

		    // links
			$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'settings/templates');


		 	$data['templates'] = $templates;

			$this->layout->view('/settings/templates', $data);

		}

		else{

			switch($action){
				case 'edit': $this->proposal_edit($id); break;
				case 'add': $this->proposal_add(); break;
				case 'delete': $this->proposal_delete($id); break;
			}

		}
	}

	/**
	 * Templates : Edit
	 * Lists all Proposal templates
	 *
	 * @url <site>/templates
	 */
	private function proposal_delete($id){

		// check
		if( isset($id) ){
			// init
			$acct = new Template();
			// find
			$acct->where('template_id', $id)->get();

			// soft_delete(array(fields=>values):where clause)
			if( $acct->soft_delete(array("template_id"=>$id)) ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully deleted template.') );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Template delete failed.') );
			}
		}

		// redirect
		redirect( 'settings/templates' );
	}

	/**
	 * Templates : Edit
	 * Lists all Proposal templates
	 *
	 * @url <site>/templates
	 */
	private function proposal_edit($id){

		 	$data = array();

			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->form_validation->set_rules('html_content', 'Html Body', 'required');

		 	$templates = new Template();

		 	// no deleted templates
		 	$templates->where("template_id", $id)->get();

		 	if( 'save' == $this->input->post('act', true) ){
				// post
				$post = $this->input->post(null, true);
				// now
				if ($this->form_validation->run() == TRUE){
				$data = array(
					"modified_user_id" => $_SESSION['user']['id'],
					"name" => $post['name'],
					//"html_body" => $post['html_content']
					"html_body" => $_REQUEST['html_content']
					);

				// Save new user
				if( $templates->update($data, NULL, TRUE, array("template_id"=>$id)) ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully updated template.') );

					// redirect
					redirect( 'settings/templates/edit/' . $id );
				}
			 	}else{notify_set( array('status'=>'error', 'message'=>' The HTML Template is empty.') );}
		redirect( 'settings/templates');
		}



		 	$data['template'] = $templates;

			$this->layout->view('/settings/templates_edit', $data);

	}

	/**
	 * Templates : Add
	 * Lists all Proposal templates
	 *
	 * @url <site>/templates
	 */
	private function proposal_add(){
		// data
		$data = array();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('html_content', 'Html Body', 'required');

		$dls = new Template();

		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);
			// now
			// new

			if ($this->form_validation->run() == TRUE){
			$id = $this->uuid->v4();
			// Enter values into required fields
			$dls->template_id = $id;
			$dls->name = $post['name'];
			$dls->created_by = $_SESSION['user']['id'];
			//$dls->html_body = $post['html_content'];
			$dls->html_body = $_REQUEST['html_content'];

			// Save new user
			if( $dls->save() ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully created new template.') );

				// redirect
				redirect( 'settings/templates');
			}
		}else{notify_set( array('status'=>'error', 'message'=>' The HTML Template is empty.') );}
		redirect( 'settings/templates');
		}

		// load view
		$this->layout->view('/settings/templates_add', $data);

	}

	/**
	 * syncMailChimp - all this does is setup the API Key and capture the list the user wants to utilize for synchronization
	 *
	 * @param $key MailChimp API Key
	 *
	 * @return void
	 */
	 public function syncMailChimp($key){
	
		$apikey = $key;
		$this->load->helper('MCAPI_helper');
		$api = new MCAPI($apikey);


		$retval = $api->lists();

		$list_size = $retval['total']; //int
		$list_data = $retval['data']; //array

		// if the user has no Lists on MailChimp
		// prompt the user to create a List on MailChimp first
		if( $list_size == 0){
			notify_set( array('status'=>'error', 'message'=>' In order to use the MailChimp Integration you must create a List inside of MailChimp.') );
		redirect( 'settings/integrations');
		}
					
		$return = false;
		$count = 0;
		$i = 0;

		$return = "<select id='list_$i' name='lists' class='form-control'>";
		foreach($list_data as $list){
			$return .= "<option value='" . $list['id'] . "|" . $list['name'] . "'>" . $list['name'] . "</option>";
		}
		$return .= "</select>";
	

		//set the header of the table and add the body to it
		$return = "<table class='table table-striped'><thead><tr><th>Select MailChimp List for Synchronization</th></tr></thead><tbody><tr><td>" . $return . "</td></tr></tbody></table>";
		
		$data['mailchimp_selection'] = $return;
		
		$data['mchimp_apikey'] = $apikey;
		
		$this->layout->view('/settings/integrations', $data);
	
		}


	/**
	 * admin updates a specific user
	 *
	 * @url <site>/users/settings
	 */
	function updateUser()
	{
		// data
		$data = array();
			// reload SESSION variables
		$CI =& get_instance();

		//logedin user
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		// set
		$data['user'] = $user;

		// post
		$post = $this->input->post(null, true);

		//exist email
			if($post['user_status'] != 178 && ($post['email'] != $post['orig_email']))
			{
			$this->db->select('uacc_email');
				$this->db->where(array('uacc_email' => $post['email'],'uacc_active' => 1) );
				$check_email_query = $this->db->get('sc_user_accounts')->result();
				$check_email_value = count($check_email_query);	
				if($check_email_value != 0)
				{
					notify_set(array('status' => 'error', 'message' => ' Please use new email address not already in use in the system.', 'settings_crm_settings'));
					redirect('settings/users/'.$post['uid']);
				}
			}


		// check for info of the user's company, the one we are making changes on
		if( ! $flexi_user = $this->flexi_auth->get_users(FALSE, array("id"=>$post['uid']))->row() ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>'No user by the id ' . $post['uid']) );

			// redirect, don't continue the code
			redirect( 'settings' );
		}

		//get all users under sc_user_accounts
		$data['flexi_users'] = $this->flexi_auth->get_users()->result();

		//create array that holds the group_id as key and group_name as value
		$flexi_grps = array();
		foreach($this->flexi_auth->get_groups()->result() as $group){
			$flexi_grps[$group->ugrp_id] = $group->ugrp_name;
		}
		$data['flexi_groups'] = $flexi_grps;

		// set
		$data['user_info'] = $flexi_user;

		// save
		//if( $post['act'] == "update-user" ){

			// act
			switch( $post['act'] ) {
				case 'update-user':


					// field validation
					$this->load->helper(array('form', 'url'));
					$this->load->library('form_validation');

					$this->form_validation->set_rules('user_group', 'Role', 'required|max_length[150]');
					$this->form_validation->set_rules('username', 'Username', 'max_length[255]');
					$this->form_validation->set_rules('first_name', 'First Name', 'max_length[255]');
					$this->form_validation->set_rules('last_name', 'Last Name', 'max_length[255]');
					$this->form_validation->set_rules('phone_number', 'Phone Number', 'max_length[55]');
					$this->form_validation->set_rules('email', 'Email Address', 'max_length[255]');

					if ($this->form_validation->run() == FALSE){

					}else{
						// data

						##STEP 1: make changes to table sc_user_accounts
						//*NOTE: flexi is using id not uid
						//get the user id from the uuid passed via form.
						$id = $this->flexi_auth->get_custom_user_data("uacc_id", array("id"=>$post['uid']), FALSE)->row();
                        //$u = new User();
                        //$u->where('upro_uacc_fk', $post['uid'])->get();
                        //var_dump($u);

						if($post['user_status'] == 178){
								$status = 0;
								} else {
								$status = 1;}
							$user_data = array(
							'username' => $post['username'],
							'uacc_email' => $post['email'],
							'uacc_group_fk' => $post['user_group'],
							'uacc_active' => $status
						);
						$this->flexi_auth->update_user($id->uacc_id, $user_data);
   						### END STEP ONE

						##STEP 2: make changes to table sc_user_profiles
						$profile_data = array(
							'first_name' => $post['first_name'],
							'last_name' => $post['last_name'],
							'upro_phone' => $post['phone_number']
						);
						$account_data = $post['email'];
						//handle image upload
						//go down public directory by one

						$config['upload_path'] = './../attachments/';
						$config['allowed_types'] = 'jpg|jpeg|png';
						$config['max_size']	= '5120';//5mb
					//	$config['max_width']  = '251';
					//	$config['max_height']  = '251';

						$this->load->library('upload', $config);

						//if the image passed JS validation
						if($post['profile_img_valid'] == "1"){
							if ( !$this->upload->do_upload('profile_img_file') ) {
                                $this->data['error'] = $this->upload->display_errors();
                            }
							else {
                                $file = $this->upload->data();

							$uploaded_file_name = $file['file_name'];
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
								$profile_data = array_merge($profile_data, array("upro_filename_original"=>$file['file_name'], "upro_filename_mimetype"=>$file['file_type']));
							}
						}

                        $user = new User();

						$id=$_SESSION['user_accounts'][$post['uid']]['id'];
						$ss_query1 = "SELECT * from sc_user_accounts WHERE (id = '".$id."')";
						$user_id_result = $this->db->query($ss_query1);
						$user1 = $user_id_result->result();

						$ss_query2 = "SELECT * from sc_user_profiles WHERE (upro_uacc_fk = '".$user1[0]->uacc_id."')";

						$upro_uacc_fk_id_result = $this->db->query($ss_query2);

						$upro_uacc_fk = $upro_uacc_fk_id_result->result();
                        $ids = $upro_uacc_fk[0]->upro_uacc_fk;

                        $user->update($profile_data, NULL, TRUE, array("upro_uacc_fk"=>$ids)) ;
						notify_set( array('status'=>'success', 'message'=>'Settings Updated Successfully' ) );
						//$this->flexi_auth->update_custom_user_data('sc_user_profiles', upro_uacc_fk->$id, $profile_data);
						$query = "update sc_user_accounts set uacc_email ='".$account_data."' where uacc_id = '".$ids."'";
						$updatequery = $this->db->query($query);
						## END STEP 2

					}




					// redirect
					//redirect( 'settings/view/' . $post['uid'] );
					$CI->teal_global_vars->set_all_global_vars();
					redirect( 'settings/users/' . $post['uid'] . '#user-management');
				break;
				case 'change-password':

					$user_data = array('uacc_password' =>$post['new_password']);

					// password change
					if($post['new_password'] != $post['confirm_new_password'] || trim($post['confirm_new_password']) == ""){
						notify_set( array('status'=>'error', 'message'=>'New Password and New Password Confirm must match and cannot be empty.' ) );
					//}elseif( $this->flexi_auth->change_password($post['uacc_email'], FALSE, $post['new_password']) ){
					}elseif( $this->flexi_auth->update_user(intval($post['id']), $user_data) ){

						// Get any status message that may have been set.
						$message = $this->flexi_auth->get_messages();
						// set flash
						//notify_set( array('status'=>'success', 'message'=>$message), 'Password Change Successful' );
						notify_set( array('status'=>'success', 'message'=>$message) );
					}else{
						// Get any status message that may have been set.
						$message = $this->flexi_auth->get_messages();
						// set flash
						//notify_set( array('status'=>'error', 'message'=>$message), 'Password Change Failed!' );
						notify_set( array('status'=>'error', 'message'=>$message) );
					}

					// redirect
					$CI->teal_global_vars->set_all_global_vars();
					redirect( 'settings/users/' . $post['uid'] . '#change-password');
				break;
			}
			//pr($post);die;
		//}

		// set
		$data['section'] = $post['act'];


		$CI->teal_global_vars->set_all_global_vars();
		// load view
		$this->layout->view('/settings', $data);
	}


	/** Deals Settings
		*
		*
		*
		*/

	public function deal_settings(){
		$data = Array();

		// load view
		$this->layout->view('/settings/deal_settings', $data);
	}


   /**
	* Drop Down Editor
	*
	* @param
	* @return void
	*/
   	public function drop_down_editor( $drop_down_item=NULL , $deleted_item = NULL){

	   	$data = Array();
	   	
	   		   	// if user is deleted an option
	   	if($drop_down_item == "delete"){
		   	$this->db->query("UPDATE sc_drop_down_options SET deleted=1 WHERE drop_down_id=" . $deleted_item);
		   	
		   	notify_set( array('status'=>'success', 'message'=>'You have successfully deleted a drop down item.', 'drop_down_editor') );
		   	
		   	redirect('settings/drop_down_editor');
		}
		
		

		//if the user submitted new label, update
		if( 'save' == $this->input->post('act', true) ){
			$post = $this->input->post(null, true);
			//update an existing field
			if($post['new_edit_field'] != "" && $post['edit_field'] != "" && $post['edit_field'] != "add_new_field")
				$this->db->query("UPDATE sc_drop_down_options SET name='" . $post['new_edit_field'] . "' WHERE drop_down_id=" . $post['edit_field'] . " AND editable=1 ");

			//add new field
			elseif($post['new_edit_field'] != "" && $post['edit_field'] != "" && $post['edit_field'] == "add_new_field"){
				$row = $this->db->query("SELECT related_module_id FROM sc_drop_down_options scdd WHERE scdd.related_field_name='" . $drop_down_item . "' LIMIT 1")->row();
				$this->db->query("INSERT INTO sc_drop_down_options (name, related_module_id, related_field_name, editable) VALUES ('" . $post['new_edit_field'] . "', " . $row->related_module_id . ", '$drop_down_item', 1) ");
			}
		}

	   	$drop_downs = Array();

	   	// get drop down data
	   	$drop_down_result = $this->db->query("SELECT scdd.drop_down_id, scdd.name, scdd.related_field_name, scdd.editable, scm.module_name FROM sc_drop_down_options scdd INNER JOIN sc_modules scm ON scdd.related_module_id = scm.module_id  WHERE scdd.editable=1 ORDER BY module_name")->result();

	   	foreach($drop_down_result as $drop_down) {


		   	switch($drop_down->module_name){
		   	    case 'Companies':
			   		$drop_downs['Companies'][] = $drop_down;
		   		break;
		   	    case 'people':
			   		$drop_downs['people'][] = $drop_down;
		   		break;
		   	    case 'Deals':
			   		$drop_downs['Deals'][] = $drop_down;
		   		break;
		   	    case 'Notes':
			   		$drop_downs['Notes'][] = $drop_down;
		   		break;
		   	    case 'Tasks':
			   		$drop_downs['Tasks'][] = $drop_down;
		   		break;
		   	    case 'Meetings':
			   		$drop_downs['Meetings'][] = $drop_down;
			   	break;
		   	    case 'Products':
			   		$drop_downs['Products'][] = $drop_down;
		   		break;

		   	}
	   	}

	   	$data['drop_downs'] = $drop_downs;

	   	//------------------------
	   	// Check if Drop Down Item selected
	   	//------------------------

	   	if(!empty($drop_down_item)){
		   	$data['show_editor'] = true;
		   	$data['drop_down_item'] = $drop_down_item;
	   	}else{
		   	$data['show_editor'] = false;
	   	}

		// load view
		$this->layout->view('/settings/drop_down_editor', $data);
   	}

   /**
	* Remove MailChimp
	*
	* @param
	* @return void
	*/
   	public function removeMailChimp(){
	   	
	   		// removes data for MailChimp
		    $query = "update sc_integrations set api_key ='0', data='', last_sync ='0000-00-00 00:00:00' where application_id = 1";
			$updatequery = $this->db->query($query);
	   	
			// do we empty out the MailChimp Unique ID's?
			
			// delete all MAILCHIMP ID's in TealCRM
			// this is how we sync up.

	   	
	   	}
	   	
   /**
	* Update MailChimp
	*
	* @param
	* @return void
	*
	* DB Notes:
	* mailchimp_id: 0 = normal, 1 = opted out recently, updated mailchimp
	*
	*/
   	public function updateMailChimp(){
	   	
   		// get data on integration
   		$query = $this->db->query('SELECT api_key, data, last_sync FROM sc_integrations WHERE application_id=1 LIMIT 1');

		$row = $query->row();
		
		$pieces = explode("|",$row->data);
		
		$listId = $pieces[0];

		$apikey = $row->api_key;
		
		$this->load->helper('MCAPI_helper');
		$api = new MCAPI($apikey);
		
		// log to database for debugging
		$this->db->query("INSERT INTO sc_log (timestamp, message) values(NOW(),'MailChimp Upload Beginning')");
		
		// Get list of People for Subscribe
		// where email1 isn't empty, isn't deleted, and isn't opted out
		$people = new Person();
	    $people->where('deleted','0');
	    $people->where('mailchimp_id','0');
	    $people->where('email_opt_out','N');
	    $people->where('email1 !=','');
	    $people->select('first_name,last_name,email1');
		$people->get();

		// build up mailchimp array for sending over
		$count = 0;
		foreach($people as $person){

			// load up mailchimp DB
			$batch[] = array('EMAIL'=>$person->email1, 'FNAME'=>$person->first_name, 'LNAME'=>$person->last_name);
			$count++;
		}

		// set parameters for mailchimp
		$optin = false; //yes, send optin emails
		$up_exist = true; // yes, update currently subscribed users
		$replace_int = false; // no, add interest, don't replace	
		
		
		$this->db->query("INSERT INTO sc_log (timestamp, message) values(NOW(),'Mailchimp: Sent ".$count." people over to MC.')");
	
		// send her away!
	    $vals = $api->listBatchSubscribe($listId,$batch,$optin, $up_exist, $replace_int);
			    
		if ($api->errorCode){
			
			$this->db->query("INSERT INTO sc_log (timestamp, message) values(NOW(),'Mailchimp: Failure - ".$api->errorMessage."')");
			
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Yikes, something went wrong. Support has been notified.','settings_integrations') );
								// redirect
			redirect( 'settings/integrations' );

		} else {
			
			$this->db->query("INSERT INTO sc_log (timestamp, message) values(NOW(),'Mailchimp: Sync Done added:".$vals['add_count']." | updated:".$vals['update_count']." | errors:".$vals['error_count']."')");
			// set flash
			
			if($vals['error_count'] == 0){
				notify_set( array('status'=>'success', 'message'=>'Successfully added '.$vals['add_count'].' and updated '.$vals['update_count'].' to MailChimp.','settings_integrations') );
			}
			else{
				$errors="";
				// finished with errors but need to show the user
				foreach($vals['errors'] as $val){
					
					// array for errors so we don't flag them as sync'd
					$error_records[] = 
					
					// error for showing
					$errors .= "<tr><td>".$val['email']."</td><td>".$val['code']."</td><td>".$val['message']."</td></tr>";
				}
				
				$this->load->library('session');
				$_SESSION['mailchimp_errors'] = $errors;
				
				notify_set( array('status'=>'warning', 'message'=>'Successfully added '.$vals['add_count'].' and updated '.$vals['update_count'].' to MailChimp.  We did, however, have some errors. See below.', 'settings_integrations') );

			
			}
			// redirect
			
			// UPDATE DATABASE SETTINGS FOR MODIFIED RECORDS
			foreach($people as $person){
			// load up mailchimp DB
				$batch[] = array('EMAIL'=>$person->email1, 'FNAME'=>$person->first_name, 'LNAME'=>$person->last_name);
				$count++;
			}
			
			redirect( 'settings/integrations' );
		
		}


		// update last sync date for Teal
		
		// update all affected records with mailchimp_id = 1	
			
			
	   	
	   	}	   	
	   	
   /**
	* Integrations
	*
	* @param
	* @return void
	*/
   	public function integrations(){
	   	
	   	//echo $mailchimp_selection;exit();
	   	
	   	// check if we are receiving an update to API and list selection
	   	if(isset($_POST['lists'])){

		   	// save the LIST selected and the API key
		    $query = "update sc_integrations set api_key ='".$_POST['mailchimp_key']."', data='".$_POST['lists']."' where application_id = 1";
			$updatequery = $this->db->query($query);
		   	
	   	}

	   	$data = Array();

		//if the user submitted new label, update
		if( 'save' == $this->input->post('act', true) ){
			$post = $this->input->post(null, true);
		}

		$query = $this->db->query('SELECT api_key, data FROM sc_integrations WHERE application_id=1 LIMIT 1');

		$row = $query->row();

		$data['mchimp_apikey'] = $row->api_key;
		
		
		$pieces = explode("|", $row->data);
		if(count($pieces)>1){
			$data['list_name'] = $pieces[1];
		}
		

		// load view
		$this->layout->view('/settings/integrations', $data);
   	}


   /**
	* View existing
	*
	* @param varchar $usr_uid
	* @return void
	*/
	public function users( $usr_uid, $section='crm-settings' ){


		// field validation
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('business_name', 'Business Name', 'required|max_length[255]');
		$this->form_validation->set_rules('address1', 'Address 1', 'max_length[255]');
		$this->form_validation->set_rules('address2', 'Address 2', 'max_length[255]');
		$this->form_validation->set_rules('city', 'City', 'max_length[255]');
		$this->form_validation->set_rules('province', 'Province', 'max_length[255]');
		$this->form_validation->set_rules('postal_code', 'Postal Code', 'max_length[255]');
		$this->form_validation->set_rules('country','Country','min_length[2]');
		$this->form_validation->set_rules('province','Province','min_length[2]');
		$this->form_validation->set_message('min_length','Please make a %s selection');

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

		// get all of settings data from database
		// init
		$setts = new Setting();
		// find
		$setts->where('id',1)->get();

		// check and see if we have an update
		if( $section == $this->input->post('act', true) ){

			$post = $this->input->post(null, true);

			// act
			switch( $section ) {
				case 'crm-settings':


					if ($this->form_validation->run() == TRUE){

							// post
							$post = $this->input->post(null, true);

							// now

							// set
							$setts->business_name = $post['business_name'];
							$setts->address1 = $post['address1'];
							$setts->address2 = $post['address2'];
							$setts->city = $post['city'];
							$setts->province = $post['province'];
							$setts->postal_code = $post['postal_code'];
							$setts->country = $post['country'];

							// Save new user
							if( $setts->save() ){
								// set flash
								notify_set( array('status'=>'success', 'message'=>'Successfully Added a new company.','settings_crm_settings') );
								// redirect
								redirect( 'settings' );
							}

					}
					else{
							// validation errors
							//echo "we have errors";

					}

			break;
			}


		}


		// check
		if( ! $flexi_user = $this->flexi_auth->get_users(FALSE, array("id"=>$usr_uid))->row() ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>'No user by the id ' . $usr_uid) );

			// redirect, don't continue the code
			redirect( 'settings' );
		}

		//get all users under sc_user_accounts
		$data['flexi_users'] = $this->flexi_auth->get_users()->result();

		//create array that holds the group_id as key and group_name as value
		$flexi_grps = array();
		foreach($this->flexi_auth->get_groups()->result() as $group){
			$flexi_grps[$group->ugrp_id] = $group->ugrp_name;
		}
		$data['flexi_groups'] = $flexi_grps;

		$data['hashImg'] = md5(uniqid());

		$this->session->set_userdata(
				array(
					//store the random string in a session as key
					$data['hashImg']=>
						//store the user profile image ( from upro_filename_original ) as the value for the session
						//if the user has not uploaded an image use the system default image default.png
						(empty($flexi_user->upro_filename_original) ? 'default.png' : $flexi_user->upro_filename_original)
				)
			);

		// set
		$data['user_info'] = $flexi_user;
		//var_dump($flexi_user);


		//fetch activity feed list
		$this->load->model("feed_list");

		//getFeedList($company_id, $category)
		$data['feed_list'] = $this->feed_list->getFeedList($usr_uid,7);


		// country drop down
		$countries = dropdownCountries();
		$data['countries'] = $countries;

		// province drop down
		$provinces = dropdownProvinces();
		$data['provinces'] = $provinces;

		//create array that holds the group_id as key and group_name as value
		$flexi_grps = array();
		foreach($this->flexi_auth->get_groups()->result() as $group){
			$flexi_grps[$group->ugrp_id] = $group->ugrp_name;
		}
		$data['flexi_groups'] = $flexi_grps;

		// set data
		$data['setting'] = $setts;

		// set
		$data['section'] = $section;

		$groups = dropdownCreator('uacc_group_fk');
		$data['groups'] = $groups;
		
		$user_status = dropdownCreator('user_status');
		$data['user_status'] = $user_status;

		// load view
		$this->layout->view('/settings/users', $data);
	}



   /**
	* Add new user
	*
	* @param void
	* @return void
	*/
	public function add(){

		// Outputs: 7
		$query = $this->db->query("SELECT count(*) as count FROM sc_user_accounts WHERE uacc_active = 1");
		$row = $query->row();
		$num_active_users = $row->count;
		$data['num_active_users'] = $num_active_users;

		 //checking to see if somebody is trying to work around the system, if true then we send them back to settings page
		//if($num_active_users >= $this->config->item('total_number_paid_users'))
			//redirect('settings');




		// field validation
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'required|max_length[255]');
		//$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|max_length[255]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|max_length[255]');
		$this->form_validation->set_rules('email', 'Email Address', 'required|max_length[255]');
		$this->form_validation->set_rules('user_group', 'Group', 'max_length[255]');
		$this->form_validation->set_message('min_length','Please make a %s selection');

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


		$post = $this->input->post(null, true);

				//exist email
				$this->db->select('uacc_email');
				$this->db->where(array('uacc_email'=>$post['email'], 'uacc_active'=>1));
				$check_email_query = $this->db->get('sc_user_accounts')->result();
				$check_email_value = count($check_email_query);
				if($check_email_value != 0)
				{
					$_SESSION['new_user']['first_name']=$post['first_name'];
					$_SESSION['new_user']['last_name']=$post['last_name'];
					$_SESSION['new_user']['phone_number']=$post['phone_number'];
					$_SESSION['new_user']['uacc_email']=$post['email'];
					$_SESSION['new_user']['username']=$post['username'];
					$_SESSION['new_user']['uacc_password']=$post['password'];
					$_SESSION['new_user']['uacc_group_fk']=$post['user_group'];
					$_SESSION['new_user']['uacc_active']=1;

				 	notify_set(array('status' => 'error', 'message' => ' Please use new email address not already in use in the system.','settings_crm_settings'));

				 redirect('/settings/add');

				}


	else {


			// check and see if we have an update
			if( "create_user" == $this->input->post('act', true) ){

					if ($this->form_validation->run() == TRUE){

							// post
							$post = $this->input->post(null, true);

							// now
							$uid = $this->uuid->v4();

							// set
							$email = $post['email'];
							$username = $post['username'];
							$password = $post['password'];
							$group_id = $post['user_group'];
                            $first_name = $post['first_name'];
                            $last_name = $post['last_name'];
                            $phone_number = $post['phone_number'];
							$activate = 1;

                        /*$user_data = array(
                            'first_name' => 'last_name',
                            'last_name' => 'first_name'
                        );*/
                        $user_account =array(
                          'uacc_email' => $email,
                          'username' => $username,
                          //'uacc_password' => $password,
                          'uacc_group_fk' => $group_id,
                          'id' =>  $uid,
                           'uacc_active' => $activate
                        );

                        $this->db->insert('sc_user_accounts', $user_account);
                        $id = $this->db->insert_id();

						$user_data = array('uacc_password' =>$password);
						$this->flexi_auth->update_user(intval($id), $user_data);



                        $user_profile = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'upro_phone' => $phone_number,
                            'upro_uacc_fk' => $id
                        );

                        $this->db->insert('sc_user_profiles', $user_profile);
						unset($_SESSION['new_user']);

                        // set flash
                        notify_set(array('status' => 'success', 'message' => 'Successfully Added a new company.', 'settings_crm_settings'));
                        // redirect
                        redirect('settings');


                        /*
                                    // Save new user
                                    if( $new_uacc_id = $this->flexi_auth->insert_user($email, $username, $password, array(), $group_id, $activate) ){

                                        //add a new unique id to the new user
                                        $this->db->query("UPDATE (`sc_user_accounts`) SET `id`='$uid' WHERE `uacc_id`='$new_uacc_id' ");

                                       if($new_upro_id= $this->flexi_auth->insert_profile($first_name, $last_name, $phone_number, array())) {
                                           //add new profile matching the user id
                                          $this->db->query("UPDATE sc_user_profiles SET upro_id = ".$uid." WHERE upro_uacc_fk = ".$new_upro_id.")");

                                           // set flash
                                           notify_set(array('status' => 'success', 'message' => 'Successfully Added a new company.', 'settings_crm_settings'));
                                           // redirect
                                           redirect('settings');
                                       }
                                    }	*/

					}else{
							// validation errors
							//echo "we have errors";

					}


		}


		//get all users under sc_user_accounts
		$data['flexi_users'] = $this->flexi_auth->get_users()->result();

		//create array that holds the group_id as key and group_name as value
		$flexi_grps = array();
		foreach($this->flexi_auth->get_groups()->result() as $group){
			$flexi_grps[$group->ugrp_id] = $group->ugrp_name;
		}
		$data['flexi_groups'] = $flexi_grps;

		// country drop down
		$countries = dropdownCountries();
		$data['countries'] = $countries;

		// province drop down
		$provinces = dropdownProvinces();
		$data['provinces'] = $provinces;

		//create array that holds the group_id as key and group_name as value
		$flexi_grps = array();
		foreach($this->flexi_auth->get_groups()->result() as $group){
			$flexi_grps[$group->ugrp_id] = $group->ugrp_name;
		}
		$data['flexi_groups'] = $flexi_grps;

		$groups = dropdownCreator('uacc_group_fk');
		$data['groups'] = $groups;


		// reload SESSION variables
	$CI =& get_instance();
	$CI->teal_global_vars->set_all_global_vars();
	$data['email_exist'] = 0;
		// load view
		$this->layout->view('/settings/add', $data);
	}
}

   /**
	* Search
	*
	* @param void
	* @return void
	*/
	public function search()
	{
		// view data init
		$data = array();

		// load view
		$this->layout->view('/people/search', $data);
	}


	 /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $company_id ){

		$id = $this->flexi_auth->get_custom_user_data("uacc_id", array("id"=>$company_id), FALSE)->row();


	    $master_admin =$this->db->query("select * from sc_user_accounts where uacc_group_fk = '3' and uacc_active = 1")->result();

       $count_value = count( $master_admin);

		if($count_value <=1)
		{
		  notify_set( array('status'=>'error', 'message'=>'You have no authorization to delete this user.') );
		}
		else
		{
		//set the user to inactive by changing uacc_active from 1 to 0
		if( $this->flexi_auth->update_user(intval($id->uacc_id), array("uacc_active"=>0)) ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully made a user inactive.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'User removal failed.') );
		}
    }
		// redirect
		redirect( 'settings' );
	}

	/**
	* Delete all
	*
	* @param void
	* @return void
	*/
	public function delete_all(){
		// post
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){

		   $ids = $post['ids'];

		   $check = 0;

		    $master_admin =$this->db->query("select * from sc_user_accounts where uacc_group_fk = '3' and uacc_active = 1")->result();

			$count = count($master_admin);


			foreach($ids as $company_id)
			{

			    $active_users = $this->db->query("select uacc_group_fk from sc_user_accounts where id = '".$company_id."'")->result();

			   if($active_users[0]->uacc_group_fk == 3)
			   {
			   $check++;
			   }

			}


			// ids

			if($count > $check)
			{


			// init
			$deleted = 0;
			// loop
			foreach ($ids as $company_id){

				$id = $this->flexi_auth->get_custom_user_data("uacc_id", array("id"=>$company_id), FALSE)->row();

				//set the user to inactive by changing uacc_active from 1 to 0
				if( $this->flexi_auth->update_user(intval($id->uacc_id), array("uacc_active"=>0)) ){
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
			else
			{
			notify_set( array('status'=>'error', 'message'=>'You have no authorization to delete this user.') );
			}


}
		// redirect
		redirect( 'settings' );
	}

	public function active_all(){

	$post = $this->input->post(null, true);
		// check
		if( isset($post['userid']) && ! empty($post['userid']) ){
			// ids
			$userid = $post['userid'];
		//	print_r($userid);
			//exit;
			// init
			$deleted = 0;
			// loop
			foreach ($userid as $company_id){

				$id = $this->flexi_auth->get_custom_user_data("uacc_id", array("id"=>$company_id), FALSE)->row();
				$id = $this->flexi_auth->get_custom_user_data("uacc_id,	uacc_email, username", array("id"=>$company_id), FALSE)->row();
				
				$email = $this->flexi_auth->get_custom_user_data("uacc_id, uacc_email",array("uacc_email"=>$id->uacc_email,"uacc_active"=>1))->row();
				
				
				$count_email = count( $email);
				
				if( $count_email == 0)
				{

				//set the user to inactive by changing uacc_active from 1 to 0
				if( $this->flexi_auth->update_user(intval($id->uacc_id), array("uacc_active"=>1)) ){
			   		$deleted++;
			   	}
				}
				else{
					$exist_user[] = $id->username;
					$exist_email[] = $email->uacc_email;
					
					}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully Active %d users(s).', $deleted) ) );
				foreach($exist_user as $user)
				{
				$count_exist = count($exist_user);
				notify_set( array('status'=>'error', 'message'=>sprintf(' %d email id(s) already exists in active users.', $count_exist) ) );
				}
				redirect( 'settings' );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>' Email id already exists in active users.') );
			}
		}

		redirect( 'settings' );
	}

	public function products_delete_all( ){
		// post
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];

			// init
			$prods = new Product();

			// find in
			$prods->where_in('product_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($prods->all as $prod)
			{
			   	// delete
				if( $prod->soft_delete(array("product_id"=>$post['ids'][$deleted])) ){
			   		$deleted++;
			   	}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d product(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Products delete failed.') );
			}
		}

		// redirect
		redirect( 'settings/products' );
	}


	public function sales_stage_order_editor($action=NULL, $id=NULL){

		if($action == NULL)
		{

		$data = Array();
			$data = $this->db->query("SELECT drop_down_id, name, order_by FROM sc_drop_down_options WHERE related_field_name = 'sales_stage' order by order_by")->result();
			// load view
		$data['sales_stage_order'] = $data;
		//print_r($data);
		$this->layout->view('/settings/sales_stage_order', $data);

		}
		else{
			print_r($post);

		}

	}

	public function sales_stage_order_update()
	{
		$post = $this->input->post(null, true);

		//print_r($post);

		$data = Array();
			$data = $this->db->query("SELECT drop_down_id, name, order_by FROM sc_drop_down_options WHERE related_field_name = 'sales_stage' order by order_by")->result();
			// load view
		foreach ($data as $data1)
		{
			$drop_id = $data1->drop_down_id;

			//echo "UPDATE sc_drop_down_options SET order_by='".$post[$drop_id]."' WHERE drop_down_id='".$drop_id."'";

		//	$this->db->query("UPDATE sc_drop_down_options SET order_by='".$post[$drop_id]."' WHERE drop_down_id='".$drop_id."'")->result();


			$data=array('order_by'=>$post[$drop_id]);
			$this->db->where('drop_down_id',$drop_id);
			$this->db->update('sc_drop_down_options',$data);
		}

		redirect( 'settings/sales_stage_order_editor' );
	}

	//---------------strt05-03-15--------------------------------------
                                                                               //custom_fields

	function custom_fields($section='custom-fields', $drop_down_item=NULL)
	{


		// data
		$data = array();

		//logedin user
		$user_id = $_SESSION['user']->id;

		//uacc_email
		$user = $_SESSION['user'];
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'id')->row();


		$modules = dropdownCreator('module');
		$data['modules'] = $modules;

		$datatypes = dropdownCreator('datatype');
		$data['datatypes'] = $datatypes;


		// get all of settings data from database
		// init
		$setts = new Setting();
		// find
		$setts->where('id',1)->get();



		// check and see if we have an update
		if( $section == $this->input->post('act', true) ){



			$post = $this->input->post(null, true);

			// act
			switch( $section ) {
				case 'custom-fields':



					$this->load->helper(array('form', 'url'));
					$this->load->library('form_validation');

							$post = $this->input->post(null, true);
							$uid = $this->uuid->v4();

							$module = $post['module'];
							$field_name = strtolower($post['field_name']);
							$label_name = $post['label_name'];
							$type = $post['datatype'];
							$data = $post['data'];

							$data =array(
                          'cf_id' => $uid,
                          'cf_module' => $module,
                          'cf_name' => $field_name,
                          'cf_label' => $label_name,
                          'cf_type' =>  $type,
                          'cf_data' => $data
                        );
                        
                        // check for duplicate custom field names
                        if(find_duplicate_custom_field($field_name)){
	                       notify_set( array('status'=>'error', 'message'=>'This field name already exists.','settings_crm_settings') );
	                       
	                       redirect( 'settings/custom_fields' );
                        }


                        $this->db->insert('sc_custom_fields', $data);


					  	// Save new user
							if( $setts->save() )
							{
								// set flash

								notify_set( array('status'=>'success', 'message'=>'Successfully added new field.','settings_crm_settings') );


//----------06-05-2015------------------------------------------------

		         $module = $_SESSION['drop_down_options'][$module]['name'];

						$module = strtolower($module);

						    $field_name = strtolower($field_name);

							/*	$query = "CREATE TABLE IF NOT EXISTS `custom_fields_data` (
                                ` custom_fields_id` varchar(100) NOT NULL
                                )";
                         // $this->db->query ( $query );

					       /* $type = $_SESSION['drop_down_options'][$type]['name'];

							  if($type == "VARCHAR")
							  $type = "VARCHAR(100)";*/

							 /* $custom = "ALTER TABLE custom_fields_data ADD $field_name VARCHAR(100) NOT NULL ";

                          $this->db->query ( $custom );
						*/
						//Drop_down
						//$type = $_SESSION['drop_down_options'][$type]['name'];
					  	$value = $post['data'];
						$module_id = $post['module'];

						if($type == "Dropdown")
							{
							$dropdownvalue = explode(",", $value);
							foreach($dropdownvalue as $dropvalue)
							{
							$dropvalue_query = "INSERT INTO sc_drop_down_options (name, related_module_id, related_field_name ,editable, deleted) values('".$dropvalue."', '".$module_id."', '".$field_name."', 1, 0)";

							 $this->db->query ( $dropvalue_query );
							}
							}
  //----------06-05-2015------------------------------------------------
                        // redirect

	                 redirect( 'settings/custom_fields' );


							}


			break;
			}


		}



		// set data
		$data['setting'] = $setts;

		// set
		$data['section'] = $section;
		//$data['mchimp_apikey'] = $user['upro_mailchimp_apikey'];


		   $accounts_custom = $this->db->query("SELECT 	cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields where cf_module = '118' and delete_status = '0'")->result();

		   $people_custom = $this->db->query("SELECT 	cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields where cf_module = '119' and delete_status = '0'")->result();

		   $deals_custom = $this->db->query("SELECT 	cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields where cf_module = '120' and delete_status = '0'")->result();

		   $notes_custom = $this->db->query("SELECT 	cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields where cf_module = '121' and delete_status = '0'")->result();



		   $tasks_custom = $this->db->query("SELECT 	cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields where cf_module = '123' and delete_status = '0'")->result();

		   $meetings_custom = $this->db->query("SELECT 	cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields where cf_module = '124' and delete_status = '0'")->result();


		    $data['accounts_custom'] = $accounts_custom;

			$data['people_custom'] = $people_custom;

			$data['deals_custom'] = $deals_custom;

			$data['notes_custom'] = $notes_custom;

			$data['tasks_custom'] = $tasks_custom;

			$data['meetings_custom'] = $meetings_custom;

			$drop_downs = Array();

	   	// get drop down data
	   	$drop_down_result = $this->db->query("SELECT 	cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields")->result();


	   	foreach($drop_down_result as $drop_down) {


		   	switch($drop_down->cf_module){
		   	    case 'Companies':
			   		$drop_downs['Companies'][] = $drop_down;
		   		break;
		   	    case 'people':
			   		$drop_downs['people'][] = $drop_down;
		   		break;
		   	    case 'Deals':
			   		$drop_downs['Deals'][] = $drop_down;
		   		break;
		   	    case 'Notes':
			   		$drop_downs['Notes'][] = $drop_down;
		   		break;
		   	    case 'Tasks':
			   		$drop_downs['Tasks'][] = $drop_down;
		   		break;
		   	    case 'Meetings':
			   		$drop_downs['Meetings'][] = $drop_down;
			   	break;
		   	    case 'Products':
			   		$drop_downs['Products'][] = $drop_down;
		   		break;

		   	}
	   	}

	 	$data['drop_downs'] = $drop_downs;


			if(!empty($drop_down_item)){
		   	$data['show_editor'] = true;
		   	$data['drop_down_item'] = $drop_down_item;
	   	}else{
		   	$data['show_editor'] = false;
	   	}
		     echo $data['show_editor'];

		// load view
		$this->layout->view('/settings/custom_fields', $data);
	}



                                                                             //custom_edit





   public function custom_edit($drop_down_item=NULL,$section='custom-edit')
	{

	//print_r($drop_down_item);

	    $data = Array();

		//exit;
         $modules = dropdownCreator('module');
		$data['modules'] = $modules;

		$datatypes = dropdownCreator('datatype');
		$data['datatypes'] = $datatypes;

		$query = $this->db->query("SELECT cf_id,cf_module,cf_name,cf_label,cf_type,cf_data FROM sc_custom_fields where cf_id ='".$drop_down_item."'")->result();



		 $data['custom_field'] = $query;
		 $post = $this->input->post(null, true);
		 
		 

	 if( $section == $this->input->post('act', true) ){

		$post = $this->input->post(null, true);


		     $module_data = array(
							'cf_module' => $post['module'],
							'cf_name'   => $post['old_field'],
							'cf_label'  => $post['label_name'],
							'cf_data'   => $post['data']
						);
				//echo $id = $post['act1'];

	                                                                     	//update query


              $this->db->where('cf_id',$post['act1']);
              $this->db->update('sc_custom_fields',$module_data);

	         notify_set( array('status'=>'success', 'message'=>'Successfully updated new field.') );


 //-----------------------edit-----------
//var_dump($post);exit();

		         $module = $_SESSION['drop_down_options'][$post['module']]['name'];

						  $module = strtolower($module);

                                $field = strtolower($post['old_field']);

                                       $old_field = strtolower($post['old_field']);



							//$query = "CREATE TABLE IF NOT EXISTS `custom_fields_data` (
                              //  `custom_fields_id` varchar(100) NOT NULL
                                //);";
                         //$this->db->query ( $query );

					       /* $datatype = $_SESSION['drop_down_options'][$post['datatype']]['name'];

							   if($datatype == "VARCHAR")
							  $datatype = "VARCHAR(100)";*/

							//  $custom = "ALTER TABLE custom_fields_data CHANGE $old_field $field  VARCHAR(100) NOT NULL ;";

                         //$this->db->query ( $custom );

						 //Drop_down
						$type = $post['type'];
						//echo $type; exit();
					  	$value = $post['data'];
						$module_id = $post['module'];
				
						if($type == "Dropdown")
							{
							$dropdownvalue = explode(",", $value);
							$dropdownvaluecount = count($dropdownvalue);

							$existdropvalue = "SELECT * FROM sc_drop_down_options where related_field_name = '".  $old_field."'";

							$existdropvalue = $this->db->query ($existdropvalue)->result();
							$existdropcount = count($existdropvalue);

							$module_id = $post['module'];


							for($i = 0; $i < $existdropcount; $i++)
							{
							$dropvalue_update = "UPDATE sc_drop_down_options SET name = '".$dropdownvalue[$i]."' where 	drop_down_id = '".$existdropvalue[$i]->	drop_down_id."'";

							$this->db->query ($dropvalue_update);
							}
							for($i = $existdropcount; $i < $dropdownvaluecount; $i++)
							{
							$dropvalue_insert = "INSERT INTO sc_drop_down_options (name, related_module_id, related_field_name ,editable, deleted) VALUES('".$dropdownvalue[$i]."','".$module_id."','".$field."',1,0)";
							$this->db->query ($dropvalue_insert);
							}
							/*foreach($dropdownvalue as $dropvalue)
							{
							$dropvalue_query = "UPDATE sc_drop_down_options SET name = '".$dropvalue."', related_field_name ,editable, deleted) values('".$dropvalue."', '".$module_id."', '".$field."', 1, 0)";

							 *///$this->db->query ( $query );
							//}
							}

		//----------------------------------------


            redirect( 'settings/custom_fields' );



			}

	 $this->layout->view('/settings/custom_edit', $data);
	}

	//-----------end05-03-15-----------------------------------

	public function custom_delete($id)
	{

		$module_data = array(
							  'delete_status' => 1
						    );
		 
	$this->db->where('cf_id',$id);
	$this->db->update('sc_custom_fields',$module_data);


	notify_set( array('status'=>'success', 'message'=>'Successfully deleted the field.') );
		// redirect
		redirect( 'settings/custom_fields' );
	}
	
	public function custom_list_views( $type = null , $field_list = null)
	{
		$data = array();
						
		if($type != "" && $field_list != "")
		{
			$field = array();
			$field = explode("-",$field_list);
			$i = 1;
			
			$this->db->select("id,module_type,field_name,order_by");
			$this->db->from("sc_custom_listview");
			$this->db->where("module_type",$type);
		
			$check_query = $this->db->get()->result();
			
			if(count($check_query) > count($field))
			{
				for($x = count($check_query); $x > count($field); $x--)
				{
					
					$this->db->where("module_type",$type);
					$this->db->where("order_by",$x);
					$this->db->delete("sc_custom_listview");
					
				}
			}
		
			
			foreach($field as $fld)
			{	
				$this->db->select("id,module_type,field_name,order_by");
				$this->db->from("sc_custom_listview");
				$this->db->where("order_by", $i);	
				$this->db->where("module_type",$type);
		
				$company_updated_fields = $this->db->get()->result();
				
				if(count($company_updated_fields[0]) > 0)
				{
					$data2 = array(
					"field_name" => $fld
					);
										
					$this->db->where("module_type",$type);
					$this->db->where("order_by" , $i);
					$this->db->update("sc_custom_listview",$data2);
				
					$i++;
				}
				else
				{
					
					$id = $this->uuid->v4();
					
					$data1 = array(
					"id" => $id,
					"module_type" => $type,
					"field_name" => $fld,
					"order_by" => $i
					);
					$this->db->insert("sc_custom_listview",$data1);
					
					$i++;
				}
				
			}
			
			
		}
		
		//COMPANY FIELDS LIST
		
		$company_fields = $this->db->list_fields('sc_companies');
		$data['company_fields'] = $company_fields;
		
		$this->db->select("id,module_type,field_name,order_by");
		$this->db->from("sc_custom_listview");
		$this->db->where("module_type","company");
		$this->db->order_by("order_by","asc");
		
		$company_updated_fields = $this->db->get()->result();
		$data['company_updated_fields'] = $company_updated_fields;
						
		//PEOPLE FIELDS LIST
		
		$people_fields = $this->db->list_fields('sc_people');
		$data['people_fields'] = $people_fields;
		
		$this->db->select("id,module_type,field_name,order_by");
		$this->db->from("sc_custom_listview");
		$this->db->where("module_type","people");
		$this->db->order_by("order_by","asc");
		
		$people_updated_fields = $this->db->get()->result();
		$data['people_updated_fields'] = $people_updated_fields;		
						
		//DEAL FIELDS LIST
		
		$deal_fields = $this->db->list_fields('sc_deals');
		$data['deal_fields'] = $deal_fields;
		
		$this->db->select("id,module_type,field_name,order_by");
		$this->db->from("sc_custom_listview");
		$this->db->where("module_type","deal");
		$this->db->order_by("order_by","asc");
		
		$deal_updated_fields = $this->db->get()->result();
		$data['deal_updated_fields'] = $deal_updated_fields;
		
		//NOTE FIELDS LIST
		
		$note_fields = $this->db->list_fields('sc_notes');
		$data['note_fields'] = $note_fields;
		
		$this->db->select("id,module_type,field_name,order_by");
		$this->db->from("sc_custom_listview");
		$this->db->where("module_type","note");
		$this->db->order_by("order_by","asc");
		
		$note_updated_fields = $this->db->get()->result();
		$data['note_updated_fields'] = $note_updated_fields;
		

		$this->db->select("id,module_type,field_name,order_by");
		$this->db->from("sc_custom_listview");
		$this->db->where("module_type","proposal");
		$this->db->order_by("order_by","asc");
		
		$proposal_updated_fields = $this->db->get()->result();
		$data['proposal_updated_fields'] = $proposal_updated_fields;
		
		//TASK FIELDS LIST
		
		$task_fields = $this->db->list_fields('sc_tasks');
		$data['task_fields'] = $task_fields;
		
		$this->db->select("id,module_type,field_name,order_by");
		$this->db->from("sc_custom_listview");
		$this->db->where("module_type","task");
		$this->db->order_by("order_by","asc");
		
		$task_updated_fields = $this->db->get()->result();
		$data['task_updated_fields'] = $task_updated_fields;
		
		//MEETTING FIELDS LIST
		
		$meeting_fields = $this->db->list_fields('sc_meetings');
		$data['meeting_fields'] = $meeting_fields;
		
		$this->db->select("id,module_type,field_name,order_by");
		$this->db->from("sc_custom_listview");
		$this->db->where("module_type","meeting");
		$this->db->order_by("order_by","asc");
		
		$meeting_updated_fields = $this->db->get()->result();
		$data['meeting_updated_fields'] = $meeting_updated_fields;
		
		if(isset($i))
		{
			notify_set( array('status'=>'success', 'message'=>'Successfully List View Updated.') );
			
			redirect("/settings/custom_list_views" , $data);
		}
		
		$this->layout->view('/settings/custom_list_views', $data);
	}

}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */