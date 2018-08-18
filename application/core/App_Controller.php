<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
// ------------------------------------------------------------------------
/**
 * CodeIgniter CT_Controller Class
 *
 * @package    CodeIgniter
 * @subpackage Libraries
 * @category   Libraries
 * @author     ExpressionEngine Dev Team
 * @link    http://codeigniter.com/user_guide/general/controllers.html
 */
 class App_Controller extends CI_Controller {     
    // module 
    var $module = array();
    var $_data  = array();
    
    // constructor    
    function __construct($model = null,$module = null, $singular = null)
    {
      // load parent
      parent::__construct();     
      // log
      log_message('debug', 'App_Controller Class Initialized');       

      $this->auth = new stdClass;

      $this->load->library('flexi_auth'); 
      
      // Define a global variable to store data that is then used by the end view page.
      $this->data = null;
      
      // set active module
      $this->module['name'] = $module;
      $this->module['model'] = $model; 
      $this->module['singular'] = $singular;
      
    }  
    
    // set message, status, message
    function notify_set($errors){            
      // set      
         set_flashdata('SESS_NOTIFY', $errors);
    }
      
    // get message and set in globals, status, message
    function notify_get($return=false){ 
      // fetch   
        $message = get_flashdata('SESS_NOTIFY');
        // check
        if(isset($message['message'])){ 
           // return
           if($return) return $message;                 
           // set
           $GLOBALS['errors'] = $message;        
        }
    } 
    
	/**
	 * remap
	 *
	 * Functionality to verify that user is authenticated
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
	 * index
	 *
	 * Functionality to display the modules index page
	 *
	 * @param string $method
	 */  
	 
	 function index(){

    // data to display to the view
		$data = array();

    // build query for index
    $this->db->select('*')->from($this->config->item('db_prefix').$this->module['name'])
    ->order_by('date_entered', 'DESC')
    ->where('deleted','0');
    
    $search_tab = "basic";

		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search'][$this->module['name']])){

			$this->db->group_start();

			foreach($_SESSION['search'][$this->module['name']] as $key => $value){

				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_type" && $key != "assigned_user_id" && $key != "industry" && $key != "lead_source_id" && $key != "lead_status_id"){
					$this->db->like($key, $value);
				}

				if($key == "assigned_user_id" || $key == "industry" || $key == "lead_source_id" || $key == "lead_status_id" || $key == "company_type")
				{
          $this->db->where_in($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$this->db->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$this->db->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$this->db->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$this->db->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search'][$this->module['model']]['search_type'])){
				if($_SESSION['search'][$this->module['model']]['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search'][$this->module['model']]['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$this->db->group_end();

		}

    $results = $this->db->get();

		$data['search_tab'] = $search_tab;

		// row per page
		$row_per_page = config_item('row_per_page');

		// uri segment for page
		$uri_segment = 2;

		// offset
		$current_page_offset = $this->uri->segment($uri_segment, 0);

    $total_count = $this->db->affected_rows();
    
		// links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'companies');

		// set
		$data['records'] = $results;

		// grab the list view layout
		$data['list_layout'] = explode(",",$_SESSION['modules'][strtolower($this->module['name'])]['list_layout']);

    // grab advanced search options
    $data['search_options'] = explode(",",$_SESSION['modules'][strtolower($this->module['name'])]['search_options']);


    // set information about module
    $data['module_name'] = $this->module['name'];
    $data['module_singular'] = $this->module['singular'];

		$this->layout->view('/modules/index', $data);
  
    

   } // end display of index page for module

	/**
	 * Add new
	 *
	 * @param void
	 * @return void
	 */
	public function add(){

		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
    $user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		// save
		if( 'save' == $this->input->post('act', true) ){

			// field validation
			$this->load->helper(array('form', 'url'));
      $this->load->library('form_validation');
      
      // sort through field dictionary and set all rules for fields
      foreach ($_SESSION['field_dictionary'][$this->module['name']] as $fields) {
        $this->form_validation->set_rules($fields['field_name'], $fields['field_label'], $fields['validation_rules']);
      }

			if ($this->form_validation->run() == TRUE){

				// grab the post data
        $post = $this->input->post(null, true);

        // array to hold data
        $record = array();

        // set unique id for the record
        $record[$_SESSION['modules'][$this->module['name']]['db_key']] = $this->uuid->v4();

        // cycle through field dictionary and associate appropriate fields
        foreach ($_SESSION['field_dictionary'][$this->module['name']] as $fields) {

          if(isset($post[$fields['field_name']])){

            if($fields['field_type'] == 'Date'){
              $record[$fields['field_name']] = date ('Y-m-d H:i:s', strtotime($post[$fields['field_name']]));
            }
            else{
              $record[$fields['field_name']] = $post[$fields['field_name']];
            }
          }
          else{
            // set default if any
            if(isset($fields['default_value'])){
              $record[$fields['field_name']] = $fields['default_value'];
            }

          }

        }        

        // set additional system fields
				$record['created_by'] = $user['uacc_uid'];
        $record['assigned_user_id'] = $post['assigned_user_id'];
        $record['date_entered'] = gmdate('Y-m-d H:i:s');

        if( $this->db->insert($this->config->item('db_prefix').$this->module['name'], $record) ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully created new '.$this->module['singular'].'.') );

					// redirect
					redirect( $this->module['name'].'/view/' . $record[$_SESSION['modules'][$this->module['name']]['db_key']] );
				}
			}
			else{
        notify_set( array('status'=>'error', 'message'=>'Error creating new '.$this->module['singular'].'.') );

        // redirect
        redirect( $this->module['name'].'/index' );
			}
		}

    // load drop down values for any drop downs
    foreach ($_SESSION['field_dictionary'][$this->module['name']] as $fields) {
      $data[$fields['field_name']] = dropdownCreator($fields['field_name']);
    }   

    // set information about module
    $data['module_name'] = $this->module['name'];
    $data['module_singular'] = $this->module['singular'];
    $data['framework'] = json_decode($_SESSION['modules'][$this->module['name']]['view_layout']);    

		// load view
    $this->layout->view('modules/add', $data);
    
	} // end module add record

	/**
	 * Edit existing
	 *
	 * @param varchar $record_id
	 * @return void
	 */
	public function edit( $record_id ){

		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();

		$assignedusers1 = getAssignedUsers1();
		$data['assignedusers1'] = $assignedusers1;

    // find the record
    $org_record = $this->db->select('*')->from($this->config->item('db_prefix').$this->module['name'])
    ->where('deleted','0')
    ->where($_SESSION['modules'][$this->module['name']]['db_key'],$record_id)->get();

    // let's make sure we can find the record being requested
    if( $this->db->affected_rows() <= 0 ){

      // can't find the record
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( $this->module['name'] );      

    }

    // save
		if( 'save' == $this->input->post('act', true) ){

			// field validation
			$this->load->helper(array('form', 'url'));
      $this->load->library('form_validation');
      
      // sort through field dictionary and set all rules for fields
      foreach ($_SESSION['field_dictionary'][$this->module['name']] as $fields) {
        $this->form_validation->set_rules($fields['field_name'], $fields['field_label'], $fields['validation_rules']);
      }

			if ($this->form_validation->run() == TRUE){
				// post
				$post = $this->input->post(null, true);
				// now

        // array to hold data
        $record = array();

        // set some update parameters to go along with it
        $record['date_modified'] = gmdate('Y-m-d H:i:s');
        $record['modified_user_id'] = $user['uacc_uid'];

        // cycle through field dictionary and associate appropriate fields
        foreach ($_SESSION['field_dictionary'][$this->module['name']] as $fields) {

          if(isset($post[$fields['field_name']])){

            // need to clean up dates
            // eventually we will have a class to scrub this stuff
            if($fields['field_type'] == 'Date'){
              $record[$fields['field_name']] = date ('Y-m-d H:i:s', strtotime($post[$fields['field_name']]));
            }
            else{
              $record[$fields['field_name']] = $post[$fields['field_name']];
            }

          }
          else{
            // set default if any
            if(isset($fields['default_value'])){
              $record[$fields['field_name']] = $fields['default_value'];
            }

          }

        }    

        // update
        $this->db->where($_SESSION['modules'][$this->module['name']]['db_key'],$record_id);
        ;

				if( $this->db->update($this->config->item('db_prefix').$this->module['name'], $record) ){
					// set flash
					notify_set( array('status'=>'success', 'message'=>'Successfully updated '.$this->module['singular'].'.') );

					// redirect
					redirect( $this->module['name'].'/view/' . $record_id );
				}
			}else{

				// we have an error in validation
			}
		}

    // set
		$data['record'] = $org_record->row();

    // load drop down values for any drop downs
    foreach ($_SESSION['field_dictionary'][$this->module['name']] as $fields) {
      $data[$fields['field_name']] = dropdownCreator($fields['field_name']);
    } 

    // set information about module
    $data['module_name'] = $this->module['name'];
    $data['module_singular'] = $this->module['singular'];
    $data['id'] = $record_id;
    $data['framework'] = json_decode($_SESSION['modules'][$this->module['name']]['view_layout']);    

		// load view
		$this->layout->view('/modules/edit', $data);
	} // end edit record

	/**
	 * View existing
	 *
	 * @param varchar $id
	 * @return void
	 */
	public function view( $id ){

		// data
		$data = array();

    $db_key = $_SESSION['modules'][$this->module['name']]['db_key'];

    // find the record
    $org_record = $this->db->select('*')->from($this->config->item('db_prefix').$this->module['name'])
    ->where('deleted','0')
    ->where($db_key,$id)->get();

		// check
		if( $this->db->affected_rows() <= 0 ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('Record does not exist anymore.') ) );

			// redirect, don't continue the code
			redirect( $this->module['name'] );
		
    }
		// set
		$data['record'] = $org_record->row();

		//fetch activity feed list
		$this->load->model("feed_list");

    //getFeedList($company_id, $category)
    
    switch($this->module['singular']){
      case "company": $feed_id = 1; break;
      case "person": $feed_id = 2; break;
      case "deal": $feed_id = 3; break;
      case "note": $feed_id = 4; break;
      case "task": $feed_id = 5; break;
      case "meetings": $feed_id = 6; break;
    }

    $data['feed_list'] = $this->feed_list->getFeedList($id,$feed_id);

    // grab related information
    $relationships = $this->db->select('*')->from($this->config->item('db_prefix').'module_relationships')
    ->where('deleted is null')
    ->where('module',$this->module['name'])->get();   
    
    $data['related_modules'] = array();

    foreach ($relationships->result() as $rel){

      // get top 10 related records
      $related_data = $this->db->select('*')->from($this->config->item('db_prefix').$rel->related_module)
      ->where('deleted','0')
      ->where($rel->module_id,$id)
      ->limit(10)->get();

      // store information regarding related value
      $data['related_modules'][]= 
                array (
                  'module' => $rel->related_module,
                  'module_id' => $rel->related_module_id,
                  'data' => $related_data->result(),
                  'total_rows' => $this->db->affected_rows()
                );
    }

    // set information about module
    $data['module_name'] = $this->module['name'];
    $data['module_singular'] = $this->module['singular'];
    $data['id'] = $id;
    $data['framework'] = json_decode($_SESSION['modules'][$this->module['name']]['view_layout']);

    $this->layout->view('/modules/view', $data);

	} // end view record

	/**
	 * Search
	 *
	 * @param void
	 * @return void
	 */
	public function search($saved_search_id = NULL, $delete = NULL){

    // set search name default
    if(!isset($_POST['saved_search_name'])){
      $saved_search_name = null;
    }
    else{
      $saved_search_name = $_POST['saved_search_name'];
    }

		unset($_SESSION['search'][$this->module['name']]); // kills search session

		$params = array($this->module['name'],$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
		$this->load->library('AdvancedSearch', $params); // initiate advancedsearch class


		// check if user is trying to save a search parameter
		if(isset($_POST['saved_search_result'])){
			$this->advancedsearch->search_string = $_POST;
			$this->advancedsearch->Insert_Saved_Search();
			$_SESSION['search'][$this->module['name']]['search_type'] = "advanced";
		}
		else if($saved_search_name !="")
			{
				$this->advancedsearch->search_string = $_POST;
				$this->advancedsearch->Insert_Saved_Search();
				$_SESSION['search'][$this->module['name']]['search_type'] = "advanced";
			}

		// did the user hit the CLEAR button, if yes skip everything
		if(!isset($_POST['clear']) && !isset($delete)){
			$this->advancedsearch->Store_Search_Criteria();
		}

		$this->advancedsearch->Set_Search_Type(); // sets what type of search to show

		if(!is_null($delete)){
			$this->advancedsearch->Delete_Saved_Search();
			unset($_SESSION['search'][$this->module['name']]);
		}

		// store search ID
		$_SESSION['search_id'] = $saved_search_id;

		// done all of our search work, redirect to contacts view for the magic
		header("Location: ".site_url($this->module['name']));

  } // end search function   
  

  	/**
	 * Delete
	 *
	 * @param void
	 * @return void
	 */
	public function delete( $id ){

		// check
		if( isset($id) ){

      $user_id = $this->flexi_auth->get_user_id();

      //uacc_email
      $user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();

      // soft_delete(array(fields=>values):where clause)
      $this->db->where($_SESSION['modules'][$this->module['name']]['db_key'],$id);
      $data = array (
        'deleted' => 1,
        'date_modified' => gmdate('Y-m-d H:i:s'),
        'modified_user_id' => $user['uacc_uid'],
      );
			if( $this->db->update($this->config->item('db_prefix').$this->module['name'], $data ) ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully deleted record111.') );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Record delete failed111.') );
			}
		}

		// redirect
		redirect( $this->module['name'] );
	} // end delete
 
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


      $user_id = $this->flexi_auth->get_user_id();

      //uacc_email
      $user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
    
			// ids
			$ids = $post['ids'];

      // init
			$deleted = 0;
			// loop
      // delete

      $data = array (
        'deleted' => 1,
        'date_modified' => gmdate('Y-m-d H:i:s'),
        'modified_user_id' => $user['uacc_uid'],
      );

      foreach($post['ids'] as $record){
        $this->db->reset_query();
        $this->db->where($this->module['singular'].'_id',$record);
        if ( $this->db->update($this->config->item('db_prefix').$this->module['name'], $data ) ) {
          $deleted++;
        }
      }

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d record(s)111.', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Record delete failed111.') );
			}
		}

		// redirect
		redirect( $this->module['name'] );
	} // end delete all  

    // set module
    function _set_module_data($module){
      // set
      foreach($module as $key=>$value){
         $this->module[$key] = $value;
      }
    }
    
    // get module
    function _get_module_data($key=NULL){
      // check if key exists
      if(!is_null($key) && isset($this->module[$key])){
         return $this->module[$key];
      }
      
      // check a custom name missing, will take default links
      if(!isset($this->module['name'])){
         return $this->_create_action_links($data);
      }
      // retunr whatever
      return $this->module;
    }
    
    // create action links and titles
    function _create_action_links(&$data){      
      // name, used for access
      if(!isset($this->module['name'])){
         $this->module['name']  = strtolower(get_class($this));
      }
      // url
      if(!isset($this->module['url'])){
         $this->module['url']   = $this->module['name']; 
      }
      // admin url
      $this->module['admin_url'] = admin_path($this->module['url']);
      // title
      $this->module['title']     = preg_replace('/_+/',' ',$this->module['name']);
      // title singular
      $this->module['title_sin'] = singular($this->module['title']);
      // current action
      $this->module['action']    = 'index';
      // set data ref
      $data['module']            = $this->module;  
      // actions
      $data['action_links']      = array('add','view','edit','delete');    
      // hide sidebar
      $data['hide_sidebar']      = true;
      // return 
      return $this->module;
    } 
   
    /**
     *   pager links
     *
     * @param int per page
     * @param int uri segment for pager index
     * @param int total rowset
     * @param string base uri 
     */
    function _create_pager_links($row_per_page, $uri_segment=2, $total_rows, $base_uri=null){   
      // per page
      $config['per_page'] = $row_per_page;
      // uri segment
      if( $uri_segment !== FALSE ){
         $config['uri_segment'] = $uri_segment;
      }else{
      // use query string        
         $config['page_query_string'] = TRUE;   
      }
      // total rows
      $config['total_rows'] = $total_rows;
      // base url
      $config['base_url'] = ( $base_uri ) ? site_url($base_uri) : current_url(); 
      // merge
      $config = array_merge($config, config_item('pager'));
           
      // set   
      $this->pagination->initialize($config);      
      // return links
      return $this->pagination->create_links();
    } 
    
    /**
     * decorate 
    */
    function _decorate_pager_display($links, $total_rows, $limit){
      // init
      $_links = '';
      // check
      if($total_rows>0){   
         $format = 'Displaying <strong>%d - %d</strong> of <strong>%d</strong> Results %s';
         $_links = sprintf($format, ($limit['offset']+1), ($total_rows < $limit['per_page'] ? $total_rows : $limit['per_page']), $total_rows, $links);
      }
      // return
      return $_links;
    }
    
    // set search query
    function _set_search_query($search_key=NULL, $query=array(), $type='general', $redirect=true){
      // key      
      if(!$search_key) $search_key = __CLASS__;       
      // uppercase
      $search_key = strtoupper($search_key);    
      // search_options
      $search_options = $this->input->post('search_options');     
      // init data array
      $data = array();
      // the action links 
      $this->_create_action_links($data); 
      
      // $search
      $search = array();         
      // default search condition         
      switch($type){
         case 'resume_quick':
            // set
            $search['SESS_SEARCH'][$search_key]['QUERY'] = $query;      
         break;
         default: // advanced/quick in admin
            // find option       
            $find_option = (isset($search_options['find'])) ? $search_options['find'] : 'OR';
            // set
            $search['SESS_SEARCH'][$search_key]['QUERY'] = sprintf(' ( %s ) ', implode(" {$find_option} ", $query)); 
         break;
      }                       
      // set type
      $search['SESS_SEARCH'][$search_key]['TYPE'] = $type;  
      // set type
      $this->session->set_userdata($search);       
      // redirect
      if($redirect) redirect($data['module']['admin_url']);          
    }
    
    // get search query
    function _get_search_query($search_key=NULL, $type='general', $clear=false){
      // key      
      if(!$search_key) $search_key = __CLASS__;       
      // uppercase
      $search_key = strtoupper($search_key);    
      // clear
      if($clear){                      
         // reset 
         return $this->_reset_search_query($search_key, $type);
      }else{         
         // get
         $search = $this->session->userdata('SESS_SEARCH');       
         // check
         if(isset($search[$search_key]['QUERY'])){          
            // return to stop next executing
            return $search[$search_key]['QUERY'];
         }
         // reset 
         return $this->_reset_search_query($search_key, $type);         
      }
    } 
    
    // reset search query
    function _reset_search_query($search_key=NULL, $type='general'){       
      // key      
      if(!$search_key) $search_key = __CLASS__; 
      // uppercase
      $search_key = strtoupper($search_key);          
      // get
      $search = $this->session->userdata('SESS_SEARCH');             
      // if set clear 
      if($search){               
         // type match
         if(isset($search[$search_key]) /*&& $search[$search_key]['TYPE'] == $type*/){
            // unset
            return $this->session->unset_userdata('SESS_SEARCH');
         }
      }
      // return
      return '';     
    } 
    
    // json list
   function _json_list($data, $limit){
      // init
      $response = array();
      // print
      $response['sEcho'] = 1;
      // total rows
      $response['iTotalRecords'] = $data['total_rows'];
      // display
      $response['iTotalDisplayRecords'] = $limit['per_page'];
      // action links
      $action_links[] = '<input type="checkbox" class="radio" name="_keys[]" value="%1$d" title="Check to apply action on multiple %2$s"/>';    
      $action_links[] = '<a href="%3$s/edit/%4$d" title="Edit %5$s info"><img src="assets/styles/icons/edit.png" border="0"></a>';     
      $action_links[] = '<a href="javascript:delete_mask(%6$d, \'%7$s\')" title="Delete"><img src="assets/styles/icons/16-em-cross.png" border="0"></a>';
      // format
      $action_format  = '<span class="action-links">' . implode(' ', $action_links) . '</span>';
      // data
      if(count($data['rowset'])){
         foreach($data['rowset'] as $row){
            $id                   = array_shift($row);
            $row['action']        = sprintf($action_format, $id, strtolower($data['module']['title']), site_url($data['module']['admin_url']), $id, strtolower($data['module']['title_sin']), $id, strtolower($data['module']['title_sin']));
            $response['aaData'][] = array_values($row);
         }
      }else{
         $response['aaData'] = array();
      }
      // send now
      print json_encode($response);    
   }  // end json list
   
   // quick search fields
   function _quick_search_fields($fields){
      // post
      $post = array();
      // populate fields
      foreach($fields as $field){
         $post['search_fields'][$field] = $this->input->post('keyword'); 
      }     
      // load search
      $this->search_result('quick', $post);
   } // end quick search fields
   
   // reset method
   function _reset_method($method){       
      // map method to post value mode
      $mode = $this->input->post('mode');
      // check
      if(isset($mode) && !empty($mode) && method_exists($this, $mode)) $method = $mode;         
      
      // pager integer
      if(preg_match('/\d+/', $method)) $method = 'index';      
      
      // return
      return $method;
   } // end reset method
}  

/* End of file App_Controller.php */
/* Location: ./application/core/App_Controller.php */
