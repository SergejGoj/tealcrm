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
 * Purpose: To assist the displaying of data on the View pages

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

} // end format_field

/**
 * format_editable_field
 *
 * This will assist in displaying the correct HTML depending on the field being requested
 *
 * @access    public
 * @param    string    the module we are working with
 * @param    string    the value to display
 * @return    string
 */
function format_editable_field(){



}

/**
 * format_related_list
 *
 * The view will pass back the field information and data then present back the HTML specific to that field
 *
 * @access    public
 * @param    string    the module we are working with
 * @param    string    the data that we are working with to return back
 * @return    string (HTML)
 * 
 * FUTURE: This will likely get updated in the future to hold custom views of what related lists look like
 * 
 */
function format_related_list($module, $data, $related_key){

    $html = '';
    switch ($module){

        case "people":

            foreach($data as $row){
                $html .= '<a class="list-group-item" href="';
                $html .= site_url($module . '/view/' . $row->$related_key);
                $html .= '"><h5 class="list-group-item-heading">';
                $html .= $row->first_name." ".$row->last_name;
                $html .= '</h5>	<p class="list-group-item-text">';
                $html .= $row->job_title.'</p></a>';

            }

            return $html;

        break; // end people

        case "tasks":

            foreach($data as $row){

                $html .=  '<a class="list-group-item" href="';
                $html .=  site_url($module . '/view/' . $row->$related_key);
                $html .=  '"><h5 class="list-group-item-heading">';
                $html .=  $row->subject;
                $html .=  '</h5>	<p class="list-group-item-text">';
                $html .=  'Due on: '.$row->due_date.'</p></a>';

            }

            return $html;

        break; // end tasks

        case "deals":
        
            foreach($data as $row){
                $html .=   '<a class="list-group-item" href="';
                $html .=   site_url($module . '/view/' . $row->$related_key);
                $html .=   '"><h5 class="list-group-item-heading">';
                $html .=   $row->name;
                $html .=   '</h5>	<p class="list-group-item-text">';
                $html .=   'Value: $'.$row->value.'</p></a>';
            }

            return $html;
        break; // end deals

        case "notes":
        
            foreach($data as $row){

                $html .=  '<a class="list-group-item" href="';
                $html .=  site_url($module . '/view/' . $row->$related_key);
                $html .=  '"><h5 class="list-group-item-heading">';
                $html .=   $row->subject . ' - '.date('Y-m-d H:i:s',strtotime($row->date_entered.' UTC'));
                $html .=  '</h5>	<p class="list-group-item-text">';
                $html .=  $row->description.'</p></a>';

            }

            return $html;
        break; // end notes

        case "meetings":
        
            foreach($data as $row){

                $html .= '<a class="list-group-item" href="';
                $html .= site_url('meetings/view/' . $row->$related_key);
                $html .= '"><h5 class="list-group-item-heading">';
                $html .= $row->subject;
                $html .= '</h5>	<p class="list-group-item-text">';
                $html .= 'Location: '.$row->location.'<br><br>('.date('Y-m-d h:ia',strtotime($row->date_start.' UTC')).' - '.date('Y-m-d h:ia',strtotime($row->date_end.' UTC')).')</p></a>';

            }

            return $html;

        break; // end meetings

    }

}
