<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Dropdown helper
 *
 * @package TealCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0


 Purpose: all system generated drop down's are handled here.  Common ones are countries, user settings..etc.

 */


// function is designed for finding duplicate custom fields
// provided $field_name is what we are looking for
// false: didn't find an existing custom field
// true: found an existing custom field

function find_duplicate_custom_field($field_name){
	$CI =& get_instance();

	$query = $CI->db->query("SELECT cf_name FROM sc_custom_fields WHERE cf_name='{$field_name}'");

	if ($query->num_rows() > 0){
		return true;
	}
	else{
		return false;
	}
}

// creates an array with values for a drop down to be stored inside of an array
// used for custom drop down items or custom drop down objects
// dropdownCreator (FieldName)
function dropdownCreator($fieldname){

	if(empty($fieldname))
	{
			// error, we didn't get a field name
			log_message('error', 'File name: dropdown_helper.php failed as there was no fieldname variable set.');
			show_error('Oops, something broke.');

			return false;

	}
	else{
			$CI =& get_instance();

			$ddItemList = array();

			// $_SESSION['drop_down_options']

			foreach($_SESSION['drop_down_options'] as $row){

				if($row['related_field_name'] == $fieldname){
					$ddItemList['0'] = '';
					$ddItemList[$row['drop_down_id']] = $row['name'];
				}

			}

			return $ddItemList;
	}

}

// returns the value of the drop down item in a VIEW mode
// GetDropDownDisplayValue (FieldName, SelectedValue)

// DEPCRECATED - SEPT 17 / 2014
// Use the $_SESSION['drop_down_options'] going forward to get these values
function GetDropDownDisplayValue($fieldname, $value){

			log_message('error', 'Deprecated function used in dropdown_helper');
			show_error('Oops, something broke.');

			return false;
			/*
	if(empty($fieldname) or empty($value))
	{
			// error, we didn't get a field name
			log_message('error', 'File name: dropdown_helper.php failed as there was no fieldname variable set.');
			show_error('Oops, something broke.');

			return false;

	}
	else{
			$CI =& get_instance();

			$ddItemList = array();

			$query = $CI->db->query("SELECT drop_down_id, name FROM sc_drop_down_options WHERE related_field_name='".$fieldname."' and drop_down_id = '".$value."' ORDER BY name LIMIT 1");

			if ($query->num_rows() > 0)
			{
				$row = $query->result();
				$result_name = $row->name;
			}
			else{
				$result_name = "Not Selected";
			}

			return $result_name;
	}
	*/

}


// provides an array containing a list of all companies in the database
function dropdownAccounts(){
	$CI =& get_instance();

	$accountlist = array('0' => 'Select Company');

	$query = $CI->db->query('SELECT company_id, name FROM sc_companies ORDER BY name ASC');

	foreach ($query->result() as $row)
	    $accountlist[$row->company_id] = $row->name;

	return $accountlist;
}

// provides an array containing a list of all contacts in the database
function dropdownpeople($related_account=''){
	$CI =& get_instance();

	if($related_account != '') $related_account = "WHERE company_id='" . $related_account . "'";
	$contactlist = array('0' => 'Select Person');

	$query = $CI->db->query('SELECT people_id, CONCAT(first_name, " ", last_name) AS name FROM sc_people ' . $related_account . ' ORDER BY name ASC');

	foreach ($query->result() as $row)
	    $contactlist[$row->people_id] = $row->name;

	return $contactlist;
}


// provides an array containing a list of all countries in the database
function dropdownCountries()
{
	$CI =& get_instance();

	$countrylist = array('0' => 'Select One');

	$query = $CI->db->query('SELECT idCountry, countryName FROM sc_countries ORDER BY countryName');

	foreach ($query->result() as $row)
	    $countrylist[$row->countryName] = $row->countryName;

	return $countrylist;
}

// provides an array containing a list of all provinces/states in the database
function dropdownProvinces()
{
	$CI =& get_instance();

	$countrylist = array('0' => 'Select One');

	$query = $CI->db->query('SELECT idProvince, provinceName FROM sc_provinces ORDER BY countryCode, provinceName');

	foreach ($query->result() as $row)
	    $countrylist[$row->provinceName] = $row->provinceName;

	return $countrylist;
}



// get a list of active users for drop downs

function getAssignedUsers(){

		$assignedusers = array();

		foreach($_SESSION['user_accounts'] as $row){

				if (!empty($row['first_name']) || !empty($row['last_name'])) {
				$assignedusers[$row['id']] = $row['first_name']." ".$row['last_name'];	
				}

				else {
				$assignedusers[$row['id']] = $row['username'];		
				}

				
			}

	    return($assignedusers);
}
function getAssignedUsers1(){

$assignedusers1 = array();

		foreach($_SESSION['user_accounts'] as $row1){

			if (!empty($row1['first_name']) || !empty($row1['last_name'])) {
					$assignedusers1[$row1['id']] = $row1['first_name']." ".$row1['last_name'];
				}

			else {
					$assignedusers1[$row1['id']] = $row1['username'];
			}

			
			}

	    return($assignedusers1);


}















