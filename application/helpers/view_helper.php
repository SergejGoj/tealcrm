<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Views helper
 *
 * @package TealCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0


 Purpose: all system generated drop down's are handled here.  Common ones are countries, user settings..etc.

 */

/**
 * format_field
 *
 * The view will pass back the field information and data then present back the HTML specific to that field
 *
 * @access    public
 * @param    string    the module we are working with
 * @param    string    the field we are working with
 * @param    string    the value to display
 * @return    string
 */
function format_field($module_name,$field,$data){

    //$_SESSION['field_dictionary'][$module_name][$row[0]]['field_label'];

    switch ($_SESSION['field_dictionary'][$module_name][$field]['field_type']){

        case "Text"; 
            return $data;
        break; // end text
        case "User"; 
            $first_name = $_SESSION['user_accounts'][$data]['upro_first_name'];
            $last_name = $_SESSION['user_accounts'][$data]['upro_last_name'];
            if(($first_name != NULL) && ($last_name != NULL)) {
                return ( $first_name." ".$last_name );
            } else if($first_name != NULL) {
                return ($first_name);
            } else if($last_name != NULL) {
                return ($last_name);
            } else {
                return ($_SESSION['user_accounts'][$data]['uacc_username']);
            }           
        break; // end User
        case "Dropdown"; 
            if($data == 0){
                return "Not Set";
            }
            else{
                return ($_SESSION['drop_down_options'][$data]['name']);
            }
        break;
        case "Radio"; 
            if($data == "Y")
            { 
                return "Yes";
            } 
            else 
            { 
                return "No";
            }
        break;
        case "Textarea"; 
            return $data;
        break;

    }

return "hello";


}

// for edit/add views
function format_editable_field(){



}
