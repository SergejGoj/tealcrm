<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// FUNCTION RETURNS LIST VIEW VARIABLES FOR USE INSIDE OF TEALCRM


// COMPANY LIST VIEW
function company_list_view()
{

	$CI =& get_instance();

    $CI->db->select("id,module_type,field_name,order_by");
	$CI->db->from("sc_custom_listview");
	$CI->db->where("module_type","company");
	$CI->db->order_by("order_by","asc");
	
	$company_updated_fields = $CI->db->get()->result();
	$label = array();
	$custom_values = array();

	foreach($company_updated_fields as $field_list)
	{
		$company_fields = $CI->db->list_fields('sc_companies');
		foreach($company_fields as $fld)
		{
			if($fld == $field_list->field_name)
			{
				if($field_list->field_name == "modified_user_id" || $field_list->field_name == "assigned_user_id" || $field_list->field_name == "created_by")
				{
					$label_name = str_replace("id","",$field_list->field_name);
					$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
					
					$display = array(
					"field_name" => $field_list->field_name,
					"label_name" => $label_name,
					"field_type" => "company_special_field"
					);
				}
				else if($field_list->field_name == "lead_source_id" || $field_list->field_name == "lead_status_id" || $field_list->field_name == "company_type" || $field_list->field_name == "industry")
				{
					$label_name = str_replace("id","",$field_list->field_name);
					$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
					
					$display = array(
					"field_name" => $field_list->field_name,
					"label_name" => $label_name,
					"field_type" => "company_drop_field"
					);
					
				}
				else if($field_list->field_name == "date_entered" || $field_list->field_name == "date_modified"){

					$label_name = str_replace("id","",$field_list->field_name);
					$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
					
					$display = array(
					"field_name" => $field_list->field_name,
					"label_name" => $label_name,
					"field_type" => "company_date_field"
					);						
					
				}
				else
				{
					$label_name = ucwords(str_replace("_"," ",$field_list->field_name));
					
					$display = array(
					"field_name" => $field_list->field_name,
					"label_name" => $label_name,
					"field_type" => "company_text_field"
					);
				}
				$label[$field_list->field_name] = $display;
			}
			else
			{
			
				if (isset($_SESSION['custom_field']['118']))
				{
					
					$custom_company_field = $_SESSION['custom_field']['118'];
					
					foreach($custom_company_field as $cust_field)
					{
						if($cust_field['cf_name'] == $field_list->field_name)
						{
							if($cust_field['cf_type'] == "Dropdown")
							{
								$display = array(
								"field_name" => $field_list->field_name,
								"label_name" => $cust_field['cf_label'],
								"field_type" => "custom_drop_field"
								);
							}
							else
							{
								$display = array(
								"field_name" => $field_list->field_name,
								"label_name" => $cust_field['cf_label'],
								"field_type" => "custom_text_field"
								);
							}
							
							$label[$field_list->field_name] = $display;
							//CUSTOM FIELDS VALUE TO DISPLAY
							$CI->db->select("custom_fields_id,companies_id,data_value");
							$CI->db->from("sc_custom_fields_data");
							$CI->db->where("custom_fields_id",$cust_field['cf_id']);
							$custom_fields_value = $CI->db->get()->result();
																					
							foreach($custom_fields_value as $cust_value)
							{
								$custom_values[$cust_field['cf_name']][$cust_value->companies_id] = $cust_value->data_value;
							}
						}															
					}			
				}
			}
		}
	}	
	
	return array($label,$company_updated_fields,$custom_values);
	
}   // end function company_list_view



// PEOPLE LIST VIEW
function people_list_view()
{
		$CI =& get_instance();
		
		//PEOPLE FIELD LIST TO DISPLAY
		$CI->db->select("id,module_type,field_name,order_by");
		$CI->db->from("sc_custom_listview");
		$CI->db->where("module_type","people");
		$CI->db->order_by("order_by","asc");
		
		$people_updated_fields = $CI->db->get()->result();
		$label = array();
		$custom_values = array();
		foreach($people_updated_fields as $field_list)
		{

			$people_fields = $CI->db->list_fields('sc_people');
			
			foreach($people_fields as $fld)
			{
				if($fld == $field_list->field_name)
				{
					if($field_list->field_name == "modified_user_id" || $field_list->field_name == "assigned_user_id" || $field_list->field_name == "created_by")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "people_special_field"
						);
					}
					else if($field_list->field_name == "date_entered" || $field_list->field_name == "date_modified"){

						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "people_date_field"
						);							
					}					
					else if($field_list->field_name == "lead_source_id" || $field_list->field_name == "lead_status_id" || $field_list->field_name == "contact_type")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "people_drop_field"
						);
						
					}
					else if($field_list->field_name == "company_id")
					{
						$label_name = str_replace("id","name",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_display_name,
						"label_name" => $label_name,
						"field_type" => "people_relate_field",
						"relate_path" => "companies/view/"
						);
										
					}
					else
					{
						$label_name = ucwords(str_replace("_"," ",$field_list->field_name));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "people_text_field"
						);
					}
					$label[$field_list->field_name] = $display;
				}
				else
				{
				
					if (isset($_SESSION['custom_field']['119']))
					{
						
						$custom_people_field = $_SESSION['custom_field']['119'];
						
						foreach($custom_people_field as $cust_field)
						{
							if($cust_field['cf_name'] == $field_list->field_name)
							{
								if($cust_field['cf_type'] == "Dropdown")
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_drop_field"
									);
								}
								else
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_text_field"
									);
								}
								
								$label[$field_list->field_name] = $display;
								
								//CUSTOM FIELDS VALUE TO DISPLAY
								$CI->db->select("custom_fields_id,companies_id,data_value");
								$CI->db->from("sc_custom_fields_data");
								$CI->db->where("custom_fields_id",$cust_field['cf_id']);
								$custom_fields_value = $CI->db->get()->result();
																						
								foreach($custom_fields_value as $cust_value)
								{
									$custom_values[$cust_field['cf_name']][$cust_value->companies_id] = $cust_value->data_value;
								}
							}
																				
						}
											
					}
				
				}
			}
		}
	return array($label,$people_updated_fields,$custom_values);
}


function task_list_view()
{

	$CI =& get_instance();

		//TASK FIELD LIST TO DISPLAY
		$CI->db->select("id,module_type,field_name,order_by");
		$CI->db->from("sc_custom_listview");
		$CI->db->where("module_type","task");
		$CI->db->order_by("order_by","asc");
		
		$task_updated_fields = $CI->db->get()->result();
		$label = array();
		$custom_values = array();
		foreach($task_updated_fields as $field_list)
		{
			$task_fields = $CI->db->list_fields('sc_tasks');
			
			foreach($task_fields as $fld)
			{
				if($fld == $field_list->field_name)
				{
					if($field_list->field_name == "modified_user_id" || $field_list->field_name == "assigned_user_id" || $field_list->field_name == "created_by")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "task_special_field"
						);
					}
					else if($field_list->field_name == "date_entered" || $field_list->field_name == "date_modified"|| $field_list->field_name == "due_date"){

						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "task_date_field"
						);							
					}					
					else if($field_list->field_name == "status_id" || $field_list->field_name == "priority_id")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "task_drop_field"
						);
					}
					else if($field_list->field_name == "company_id" || $field_list->field_name == "people_id")
					{
						$label_name = str_replace("id","name",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						switch($field_list->field_name)
						{
							case 'company_id': $path = "companies/view/"; break;
							case 'people_id': $path = "people/view/"; break;
							case 'parent_id': $path = "projects/view/";$label_name = "Project Name";break;
						}
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "task_relate_field",
						"relate_path" => $path
						);
					}
					else
					{
						$label_name = ucwords(str_replace("_"," ",$field_list->field_name));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "task_text_field"
						);
					}
					$label[$field_list->field_name] = $display;
				}
				else
				{
				
					if (isset($_SESSION['custom_field']['123']))
					{
						$custom_task_field = $_SESSION['custom_field']['123'];
						
						foreach($custom_task_field as $cust_field)
						{
							if($cust_field['cf_name'] == $field_list->field_name)
							{
								if($cust_field['cf_type'] == "Dropdown")
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_drop_field"
									);
								}
								else
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_text_field"
									);
								}
								$label[$field_list->field_name] = $display;
								
								//CUSTOM FIELDS VALUE TO DISPLAY
								$CI->db->select("custom_fields_id,companies_id,data_value");
								$CI->db->from("sc_custom_fields_data");
								$CI->db->where("custom_fields_id",$cust_field['cf_id']);
								$custom_fields_value = $CI->db->get()->result();
																						
								foreach($custom_fields_value as $cust_value)
								{
									$custom_values[$cust_field['cf_name']][$cust_value->companies_id] = $cust_value->data_value;
								}
							}
						}
					}
				}
			}
		}	

	return array($label,$task_updated_fields,$custom_values);

	
}

function deal_list_view()
{

	$CI =& get_instance();

		//DEAL FIELD LIST TO DISPLAY
		$CI->db->select("id,module_type,field_name,order_by");
		$CI->db->from("sc_custom_listview");
		$CI->db->where("module_type","deal");
		$CI->db->order_by("order_by","asc");
		
		$deal_updated_fields = $CI->db->get()->result();
		$label = array();
		$custom_values = array();
		foreach($deal_updated_fields as $field_list)
		{
			$deal_fields = $CI->db->list_fields('sc_deals');
			
			foreach($deal_fields as $fld)
			{
				if($fld == $field_list->field_name)
				{
					if($field_list->field_name == "modified_user_id" || $field_list->field_name == "assigned_user_id" || $field_list->field_name == "created_by")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "deal_special_field"
						);
					}
					else if($field_list->field_name == "date_entered" || $field_list->field_name == "date_modified"){

						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "deal_date_field"
						);							
					}
					else if($field_list->field_name == "sales_stage_id")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "deal_drop_field"
						);
					}
					else if($field_list->field_name == "company_id" || $field_list->field_name == "people_id" )
					{
						$label_name = str_replace("id","name",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						switch($field_list->field_name)
						{
							case 'company_id': $path = "companies/view/"; break;
							case 'people_id': $path = "people/view/"; break;
						}
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "deal_relate_field",
						"relate_path" => $path
						);
					}
					else
					{
						$label_name = ucwords(str_replace("_"," ",$field_list->field_name));
					
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "deal_text_field"
						);
					}
					$label[$field_list->field_name] = $display;
				}
				else
				{
					if (isset($_SESSION['custom_field']['120']))
					{
						$custom_deal_field = $_SESSION['custom_field']['120'];
						
						foreach($custom_deal_field as $cust_field)
						{
							if($cust_field['cf_name'] == $field_list->field_name)
							{
								if($cust_field['cf_type'] == "Dropdown")
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_drop_field"
									);
								}
								else
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_text_field"
									);
								}
								$label[$field_list->field_name] = $display;
								
								//CUSTOM FIELDS VALUE TO DISPLAY
								$CI->db->select("custom_fields_id,companies_id,data_value");
								$CI->db->from("sc_custom_fields_data");
								$CI->db->where("custom_fields_id",$cust_field['cf_id']);
								$custom_fields_value = $CI->db->get()->result();
																						
								foreach($custom_fields_value as $cust_value)
								{
									$custom_values[$cust_field['cf_name']][$cust_value->companies_id] = $cust_value->data_value;
								}							
							}
						}
					}
				}
			}
		}	
	
	return array($label,$deal_updated_fields,$custom_values);

}

function notes_list_view()
{

	$CI =& get_instance();
		//NOTE FIELD LIST TO DISPLAY
		
		$CI->db->select("id,module_type,field_name,order_by");
		$CI->db->from("sc_custom_listview");
		$CI->db->where("module_type","note");
		$CI->db->order_by("order_by","asc");
		
		$note_updated_fields = $CI->db->get()->result();
		$label = array();
		$custom_values = array();
		foreach($note_updated_fields as $field_list)
		{
			$note_fields = $CI->db->list_fields('sc_notes');
			
			foreach($note_fields as $fld)
			{
				if($fld == $field_list->field_name)
				{
					if($field_list->field_name == "modified_user_id" || $field_list->field_name == "assigned_user_id" || $field_list->field_name == "created_by")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "note_special_field"
						);
					}
					
					else if($field_list->field_name == "company_id" || $field_list->field_name == "people_id" )
					{
						$label_name = str_replace("id","name",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						switch($field_list->field_name)
						{
							case 'company_id': $path = "companies/view/"; break;
							case 'people_id': $path = "people/view/"; break;
						}
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "note_relate_field",
						"relate_path" => $path
						);
											
					}
					else
					{
						$label_name = ucwords(str_replace("_"," ",$field_list->field_name));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "note_text_field"
						);
					}
					$label[$field_list->field_name] = $display;
				}
				else
				{
				
					if (isset($_SESSION['custom_field']['121']))
					{
						
						$custom_note_field = $_SESSION['custom_field']['121'];
						
						foreach($custom_note_field as $cust_field)
						{
							if($cust_field['cf_name'] == $field_list->field_name)
							{
								if($cust_field['cf_type'] == "Dropdown")
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_drop_field"
									);
								}
								else
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_text_field"
									);
								}
								
								$label[$field_list->field_name] = $display;
								
								//CUSTOM FIELDS VALUE TO DISPLAY
								$CI->db->select("custom_fields_id,companies_id,data_value");
								$CI->db->from("sc_custom_fields_data");
								$CI->db->where("custom_fields_id",$cust_field['cf_id']);
								$custom_fields_value = $CI->db->get()->result();
																						
								foreach($custom_fields_value as $cust_value)
								{
									$custom_values[$cust_field['cf_name']][$cust_value->companies_id] = $cust_value->data_value;
								}	
							}
																				
						}
											
					}
				
				}
			}
		}	

	return array($label,$note_updated_fields,$custom_values);	
	
	
}

function meeting_list_view()
{

	$CI =& get_instance();
		//MEETING FIELD LIST TO DISPLAY
		$CI->db->select("id,module_type,field_name,order_by");
		$CI->db->from("sc_custom_listview");
		$CI->db->where("module_type","meeting");
		$CI->db->order_by("order_by","asc");
		
		$meeting_updated_fields = $CI->db->get()->result();
		$label = array();
		$custom_values = array();
		foreach($meeting_updated_fields as $field_list)
		{
			$meeting_fields = $CI->db->list_fields('sc_meetings');
			
			foreach($meeting_fields as $fld)
			{
				if($fld == $field_list->field_name)
				{
					if($field_list->field_name == "modified_user_id" || $field_list->field_name == "assigned_user_id" || $field_list->field_name == "created_by")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "meeting_special_field"
						);
					}
					else if($field_list->field_name == "date_entered" || $field_list->field_name == "date_modified"|| $field_list->field_name == "date_start"|| $field_list->field_name == "date_end"){

						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "meeting_date_field"
						);							
					}
					else if($field_list->field_name == "event_type")
					{
						$label_name = str_replace("id","",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "meeting_drop_field"
						);
						
					}
					else if($field_list->field_name == "company_id" || $field_list->field_name == "people_id" )
					{
						$label_name = str_replace("id","name",$field_list->field_name);
						$label_name = trim(ucwords(str_replace("_"," ",$label_name)));
						
						switch($field_list->field_name)
						{
							case 'company_id': $path = "companies/view/"; break;
							case 'people_id': $path = "people/view/"; break;
						}
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "meeting_relate_field",
						"relate_path" => $path
						);
											
					}
					else
					{
						$label_name = ucwords(str_replace("_"," ",$field_list->field_name));
						
						$display = array(
						"field_name" => $field_list->field_name,
						"label_name" => $label_name,
						"field_type" => "meeting_text_field"
						);
					}
					$label[$field_list->field_name] = $display;
				}
				else
				{
					if (isset($_SESSION['custom_field']['124']))
					{
						$custom_meeting_field = $_SESSION['custom_field']['124'];
						
						foreach($custom_meeting_field as $cust_field)
						{
							if($cust_field['cf_name'] == $field_list->field_name)
							{
								if($cust_field['cf_type'] == "Dropdown")
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_drop_field"
									);
								}
								else
								{
									$display = array(
									"field_name" => $field_list->field_name,
									"label_name" => $cust_field['cf_label'],
									"field_type" => "custom_text_field"
									);
								}
								$label[$field_list->field_name] = $display;
								
								//CUSTOM FIELDS VALUE TO DISPLAY
								$CI->db->select("custom_fields_id,companies_id,data_value");
								$CI->db->from("sc_custom_fields_data");
								$CI->db->where("custom_fields_id",$cust_field['cf_id']);
								$custom_fields_value = $CI->db->get()->result();
																						
								foreach($custom_fields_value as $cust_value)
								{
									$custom_values[$cust_field['cf_name']][$cust_value->companies_id] = $cust_value->data_value;
								}
							}
						}
					}
				}
			}
		}

	return array($label,$meeting_updated_fields,$custom_values);	
	
	
}
