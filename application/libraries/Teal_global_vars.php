<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class teal_global_vars {

	// this function sets the default variables upon successful login to TEAL
	// we are using the PHP session vars as it can hold a lot more static data than the codeigniter cookie method
	public function set_all_global_vars(){
		
		$CI =& get_instance();

		// standard variables throughout the system

		// SET TRACKER VAR - this is checked everytime we run in case we have to re-execute VARS
		$_SESSION['tealcrm_live'] = true;

		//custom field value
		$custom_field = Array();

		$query = $CI->db->query("SELECT cf_module ,cf_name, cf_type ,cf_label ,cf_id FROM sc_custom_fields where delete_status = 0");

		foreach ($query->result_array() as $row)
		{
		$custom_field[$row['cf_module']][$row['cf_id']] = $row;
		}
		$_SESSION['custom_field'] = $custom_field;
		//custom field end

		// get drop down options model with key being the DROP_DOWN_ID
		$drop_down_options =  Array();

		$query = $CI->db->query("SELECT * FROM sc_drop_down_options where deleted=0 ORDER BY order_by, name");

		foreach ($query->result_array() as $row)
		{
			$drop_down_options[$row['drop_down_id']] = $row;
		}

		$_SESSION['drop_down_options']=$drop_down_options;


		// get user companies

		$query = $CI->db->query("SELECT * FROM sc_users WHERE active = 1");

		foreach ($query->result_array() as $row)
		{
			$user_accounts[$row['id']] = $row;
		}

		$_SESSION['user_accounts']=$user_accounts;

		//*******************************
		// -- SET SIGNED IN USER DETAILS
		///
		//loggedin user
		$user = $CI->ion_auth->user()->row();
		$user_id = $user->id;

		//uacc_email
		$_SESSION['user'] = $user;


		//***************************
		// SET TIME ZONE
		$query = $CI->db->query("SELECT * FROM sc_settings LIMIT 1");
		
		
		foreach ($query->result_array() as $row)
		{

			if(!empty($row['timezone'])){
				$_SESSION['timezone'] = $row['timezone'];
			}
			else{
				$_SESSION['timezone'] = "America/Vancouver";
			}
			
			// set settings variables for site
			$_SESSION['settings']['business_name'] = $row['business_name'];
			$_SESSION['settings']['company_type'] = $row['company_type'];
			$_SESSION['settings']['address1'] = $row['address1'];
			$_SESSION['settings']['address2'] = $row['address2'];
			$_SESSION['settings']['city'] = $row['city'];
			$_SESSION['settings']['province'] = $row['province'];
			$_SESSION['settings']['postal_code'] = $row['postal_code'];
			$_SESSION['settings']['country'] = $row['country'];
			$_SESSION['settings']['billing_email'] = $row['billing_email'];
			
		}
		
		// LOAD all settings
		

		//***************************
		// SET SAVED SEARCH
		// load all saved searches for use in views
		$query = $CI->db->query("SELECT search_id, title, module,search_string FROM sc_saved_search");

		foreach ($query->result_array() as $row)
		{
			$_SESSION['saved_searches'][$row['module']][$row['search_id']] = $row['search_string'];
			$_SESSION['saved_searches_index'][$row['module']][$row['search_id']] = $row['title'];
		}

		//***************************
		// LOAD FIELD DICTIONARY
		// 	

		$query = $CI->db->query("SELECT * from sc_field_dictionary WHERE deleted is null");

		foreach ($query->result_array() as $row)
		{
			$_SESSION['field_dictionary'][$row['module']][$row['field_name']]['field_name'] = $row['field_name'];
			$_SESSION['field_dictionary'][$row['module']][$row['field_name']]['field_type'] = $row['field_type'];
			$_SESSION['field_dictionary'][$row['module']][$row['field_name']]['validation_rules'] = $row['validation_rules'];
			$_SESSION['field_dictionary'][$row['module']][$row['field_name']]['calculation'] = $row['calculation'];
			$_SESSION['field_dictionary'][$row['module']][$row['field_name']]['name_value'] = $row['name_value'];
		}

		//***************************
		// LOAD MODULE RELATIONSHIPS
		// used for displaying related data		

		$query = $CI->db->query("SELECT * from sc_module_relationships WHERE deleted is null");

		foreach ($query->result_array() as $row)
		{
			$_SESSION['module_relationships'][$row['module']][$row['related_module']]['related_module'] = $row['related_module_id'];
			$_SESSION['module_relationships'][$row['module']][$row['related_module']]['related_module_id'] = $row['related_module_id'];
			$_SESSION['module_relationships'][$row['module']][$row['related_module']]['module'] = $row['module'];
			$_SESSION['module_relationships'][$row['module']][$row['related_module']]['module_id'] = $row['module_id'];
		}

		//***************************
		// LOAD MODULE DETAILS
		// used for displaying data related to specific modules	

		$query = $CI->db->query("SELECT * from sc_modules");

		foreach ($query->result_array() as $row)
		{
			$_SESSION['modules'][strtolower($row['module_name'])]['module_name'] = $row['module_name'];
			$_SESSION['modules'][strtolower($row['module_name'])]['directory'] = $row['directory'];
			$_SESSION['modules'][strtolower($row['module_name'])]['db_table'] = $row['db_table'];
			$_SESSION['modules'][strtolower($row['module_name'])]['db_key'] = $row['db_key'];
			$_SESSION['modules'][strtolower($row['module_name'])]['view_layout'] = $row['view_layout'];
			$_SESSION['modules'][strtolower($row['module_name'])]['search_options'] = $row['search_options'];
			$_SESSION['modules'][strtolower($row['module_name'])]['list_layout'] = $row['list_layout'];
			$_SESSION['modules'][strtolower($row['module_name'])]['icon'] = $row['icon'];
		}

		//**************************
		// LOAD LANGUAGE PACK
		// based on the users preferences
		$language = $_SESSION['user']->language;
		$query = $CI->db->query("SELECT * from sc_language WHERE language = '" . $language . "'");

		foreach ($query->result_array() as $row)
		{
			$_SESSION['language'][strtolower($row['module_name'])][$row['field_name']] = $row['value'];
		}

	}

	// PHP sessions are deleted on logout so that settings can be redone.

	public function destory_teal_global_vars_cookie(){

		if(!$_SESSION){
			session_start(); // start cookie session
		}

		$_SESSION = array();

		session_destroy();

	}


}
/* End of file demo_auth_model.php */
/* Location: ./application/models/demo_auth_model.php */