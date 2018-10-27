<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This function handles all search related objects
//
//
//


class AdvancedSearch {

	public $module; // name of module for advanced search
	public $saved_search_id; // saved search id if passed
	public $delete; // flag if we are deleting the given search id
	public $saved_search_name; // name of a new saved_search
	public $search_string; // json string from form
	private $CI;

	public $search_ignore = Array();

	//$module, $saved_search_id = null, $delete = null, $saved_search_name = null, $search_json_string = null
    function __construct($params)
    {
    	// set vars
    	$this->module = $params[0];
    	$this->saved_search_id = $params[1];
    	$this->delete = $params[2];
    	$this->saved_search_name = $params[3];
    	$this->search_string = $params[4];

    	$this->CI =& get_instance();
    	$this->CI->load->database();

    	$this->search_ignore = array(
    							'__/__/____',
    							'company_viewer',
    							'person_viewer',
    							'saved_search_name',
    							'Clear',
    							'saved_search_result',
    							'Save Search'
								);

    }

    // adds a saved search given by a user
    public function Insert_Saved_Search(){

		$search_params = json_encode($this->search_string);
		// combile query into database and add to SESSIONS
		//echo var_dump($_SESSION['search']['persons'])."<br/>";

		// store in database
		$now = gmdate('Y-m-d H:i:s');

		$data = array(
			'date_entered' => $now,
			'created_by' => $_SESSION['user']['id'],
			'title' => $this->saved_search_name,
			'module' => $this->module,
			'search_string' => $search_params);

		$this->CI->db->insert('saved_search',$data);

		// add search to SESSION vars for speed

		$this->saved_search_id = $this->CI->db->insert_id();

		$saved_search_id = $this->saved_search_id;

		// add to sessions
		$_SESSION['saved_searches'][$this->module][$saved_search_id] = $search_params;
		$_SESSION['saved_searches_index'][$this->module][$saved_search_id] = $_POST['saved_search_name'];

    }

    // permanently deletes a saved search and updates session
    public function Delete_Saved_Search(){
    	$this->CI->db->query("DELETE FROM sc_saved_search WHERE search_id = '". $this->saved_search_id . "'");
		unset($_SESSION['saved_searches'][$this->module][$this->saved_search_id]); //unset entry from sessions
		unset($_SESSION['saved_searches_index'][$this->module][$this->saved_search_id]); // unset entry from session
    }

    // set Search type (one of basic, advanced or saved
    public function Set_Search_type(){
		if(isset($_SESSION['search_go']))
			$_SESSION['search'][$this->module]['search_type'] = "basic";
		if(isset($_SESSION['adv_search_go']))
			$_SESSION['search'][$this->module]['search_type'] = "advanced";
		if(isset($this->saved_search_id))
			$_SESSION['search'][$this->module]['search_type'] = "saved";
    }

    // automatically cycles through post to store a JSON record inside session
    public function Store_Search_Criteria(){

    	unset($_SESSION['search'][$this->module]); // remove previous search info and get ready to replace

		$search_data = Array();

		// are we loading a saved search or just an adhoc
		if(isset($this->saved_search_id) && $this->saved_search_id != NULL){
			// parse JSON into array
			$search_data = json_decode($_SESSION['saved_searches'][$this->module][$this->saved_search_id]);
		}
		else{
			// store POST into array
			$search_data = $_POST;
		}

		// parse through post and store in sessions
		$param_count = 0; // if this stays 0, then we had none and need to cancel the criteria store
		foreach($search_data as $key => $value){
				if(in_array($key, $this->search_ignore) || in_array($value, $this->search_ignore) || empty($value) || $key == 'saved_search_name'){

				}
				else{
					if($value == "Search")
						$_SESSION['search'][$this->module]['search_type'] = $key;
					else{
						$_SESSION['search'][$this->module][$key] = $value;
						$param_count++;
					}
				}
		}

		if($param_count == 0){
    		unset($_SESSION['search'][$this->module]); // remove previous search info and get ready to replace
		}
    }





}