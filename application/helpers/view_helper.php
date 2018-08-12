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
 * display_name
 *
 * This function will decide which is the name_value for the specific module.  Only 1 name value exists for a module.
 *
 * @access    public
 * @param    string    the module we are working with
 * @param    string    the record we are dealing with
 * @return    string
 */
function display_name($module,$data){

    foreach($_SESSION['field_dictionary'][$module] as $row){
        if ( $row['name_value'] == 1 ){

            return $data->{$row['field_name']};

            break;
        }
    }
} // end display_name

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

    $CI =& get_instance();

    switch ($_SESSION['field_dictionary'][$module_name][$field]['field_type']){

        case "Text":
            return $data;
        break; // end text
        case "User": 
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
        case "Dropdown": 
            if($data == 0){
                return "Not Set";
            }
            else{
                return ($_SESSION['drop_down_options'][$data]['name']);
            }
        break;
        case "Radio":
            if($data == "Y")
            { 
                return "Yes";
            } 
            else 
            { 
                return "No";
            }
        break;
        case "Textarea":
            return $data;
        break;
        case "Date":
            if(isset($data)){
                return date("F j, Y", strtotime($data));
            }
            else{
                return "Not Set";
            }
        break; // end date        
        case "Related_Company":
            // fetch name of the company
            $query = $CI->db->get_where('sc_companies', array('company_id' => $data), 1);
            
            $row = $query->row();

            if(isset($row)){
                return "<a href='" . site_url('companies/view') . "/" . $data ."'>" . $row->company_name . "</a>";
            }
            else{
                return "<a href='" . site_url('companies/view') . "/" . $data ."'>Related Company</a>";
            }

        break; // end Related Company

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
 * @param   bool    whether or not we are displaying multi select options
 * @return    string
 */
function format_editable_field($module_name,$field,$data,$adv_search = false){

    if($field == 'created_by' || $field == 'modified_user_id'){

        $assignedusers1 = getAssignedUsers1();

        if($adv_search){
            return form_dropdown($field.'[]', $assignedusers1, $data, "multiple='true' class='form-control chosen-select' id='". $field ."'");
        }
        else{
            return form_dropdown($field, $assignedusers1, $data, "class='form-control' id='". $field ."'");
        }
        
    }
    elseif($field == 'date_modified' || $field == 'date_entered'){

        if($adv_search){
            return '<input class="form-control datetime" id="' . $field . '_start" name="' . $field . '_start" type="text">
            <input class="form-control datetime" id="' . $field . '_end" name="' . $field . '_end" type="text">';
        }

    }
    else{

        switch ($_SESSION['field_dictionary'][$module_name][$field]['field_type']){

            case "Text":

                return '<input name="' . $field . '" type="text" class="form-control" id="' . $field . '" value="' . $data . '">';

            break; // end text
            case "User": 

                $assignedusers1 = getAssignedUsers1();

                if($adv_search){
                    return form_dropdown($field.'[]', $assignedusers1, $data, "multiple='true' class='form-control chosen-select' id='". $field ."'");
                }
                else{
                    return form_dropdown($field, $assignedusers1, $data, "class='form-control' id='". $field ."'");
                }

            break; // end User
            case "Dropdown": 
                if($adv_search){
                    return form_dropdown($field.'[]', dropdownCreator($field), $data, "multiple='true' class='form-control chosen-select' id='". $field ."'");
                }
                else{
                    return form_dropdown($field, dropdownCreator($field), $data, "class='form-control' id='". $field ."'");
                }
            break;
            case "Radio":

                return '
                    <label class="radio">
                    <input id="email_opt_out_1" name="' . $field . '" type="radio"
                        value="Y" ' . set_radio($field, 'Y', $data == 'Y') . '>Yes
                    </label>

                    <div style="clear:both"></div>

                    <label class="radio">
                        <input id="email_opt_out_2" name="' . $field . '" type="radio"
                            value="N" ' . set_radio($field, 'N', $data == 'N') . '>No
                    </label>
                ';

            break; // end Radio
            case "Textarea":
                return '<textarea name="description" id="description" class="form-control" rows="5">' . 
                set_value($field, $data) . '</textarea>';
            break;
            case "Date":
                return '<input class="form-control datetime" id="' . $field . '" name="' . $field . '" type="text">';
            break; // end date        
            case "Related_Company":
                // TBD
            break; // end Related Company
        } // end switch
    } // end if date_modified, created_by, date_entered, modidified_user_id

} // end format_editable_field

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
