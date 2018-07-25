<?php
class Module_search extends CI_Model {

	/* Search modules for a provided term
	* @version 1: search tables directly excluding related fields ie lead status
	* @param $mod the module number 1,2,3,4,5, or 6
	* @param $term the term we are searching for
	* @return styled html search result
	*/
	public function seachModules($mod, $term, $filter_by, $filter_value, $filter_value2, $field_type){

		$filter_where = "";
		if($filter_by != "" && $filter_value != ""){
			$term = $filter_value;
			$filter_where = "  `" . $filter_by . "` LIKE '" . $filter_value ."' ";

			//speciall case for Y/N fields (email_opt_out and do_not_call)
			if($filter_by == "email_opt_out" || $filter_by == "do_not_call"){
				if($filter_value == "89"){
					$filter_where = " `do_not_call`='Y' ";
				}elseif($filter_value == "90"){
					$filter_where = " `email_opt_out`='Y' ";
				}elseif($filter_value == "93"){
					$filter_where = " `do_not_call`='N' ";
				}elseif($filter_value == "94"){
					$filter_where = " `email_opt_out`='N' ";
				}
			}

			//speciall case company id autocomplete
			if($field_type == "autocomplete" && $filter_by == "company_id"){
				$filter_where = " `company_id`='" . $filter_value . "' ";
			}

		}

		//if field_type is datetime
		if($field_type == "datetime"){
			
			// Issue #14 - Timezone issue			
			if($filter_value != "" && $filter_value2 != ""){
				$filter_where = "  `" . $filter_by . "` BETWEEN '" . gmdate( "Y-m-d",strtotime($filter_value)) ."' AND '" . gmdate( "Y-m-d",strtotime($filter_value2)) ."' ";
			}elseif($filter_value != "" && $filter_value2 == ""){
				if($filter_by == "date_start")
					$filter_where = "  `" . $filter_by . "` >= '" . gmdate( "Y-m-d",strtotime($filter_value)) ."' ";
				if($filter_by == "date_end" || $filter_by == "expected_close_date")
					$filter_where = "  `" . $filter_by . "` <= '" . gmdate( "Y-m-d",strtotime($filter_value)) ."' ";

			}
		}

		//var special for searching date type fields
		$strToTime = strtotime($term);

		//if provided term is not a date then strToTime is 1913-01-05
		if($strToTime == false) $strToTime = -53337960000;

		//arrays of searchable fields of every table in the DB
		$accounts_searchable_fields = array("company_name"=>$term, "address1"=>$term, "address2"=>$term, "city"=>$term, "province"=>$term, "country"=>$term);
		$people_searchable_fields = array("first_name"=>$term, "last_name"=>$term, "job_title"=>$term, "phone_home"=>$term, "phone_work"=>$term, "phone_mobile"=>$term, "address1"=>$term, "address2"=>$term, "city"=>$term, "province"=>$term, "country"=>$term);
		$deals_searchable_fields = array("name"=>$term, "next_step"=>$term);
		$notes_searchable_fields = array("subject"=>$term);
		$tasks_searchable_fields = array("subject"=>$term);
		$meetings_searchable_fields = array("subject"=>$term, "location"=>$term);

		$extra_where = array();
		switch($mod){
			case 1: $search_fields = $accounts_searchable_fields; $search_table="sc_companies"; break;
			case 2: $search_fields = $people_searchable_fields; $search_table="sc_people"; break;
			case 3: $search_fields = $deals_searchable_fields; $search_table="sc_deals"; break;
			case 4: $search_fields = $meetings_searchable_fields; $search_table="sc_meetings"; break;
			case 5: $search_fields = $notes_searchable_fields; $search_table="sc_notes"; break;
			case 6: $search_fields = $tasks_searchable_fields; $search_table="sc_tasks"; $extra_where=array("parent_id", '0'); break;
		}

		//order by the provided filter, otherwise order by the first element of the array search_fields
		if($filter_by != "" && $filter_value != "")
			$filter = $filter_by;
		else $filter = key($search_fields);

		$body = "";

		if( $filter_where == "" ){
			//find all results that maches the term

			//show non-deleted records
			$this->db->where("deleted", 0);

			//if we passed extra where, $extra_where[0]=column name,$extra_where[1]= column value
			if(!empty($extra_where))
				$this->db->where($extra_where[0],$extra_where[1]);

			//or_like BY CI DOES NOT GROUP LIKE IN PARENTHESES
			//THIS IS OUR CUSTOM LIKE STATEMENT
			$where_array = array();
			foreach ($search_fields as $k => $v){
				$where_array[] = "`" . $k . "` LIKE '%" . $v . "%' ";
			}
			$where = "(".implode("OR ", $where_array).")";

			//pass the LIKE statement to where
			$this->db
				->where($where, NULL, FALSE)

				//ordery by the first key in the search_fields array; ie for $accounts_searchable_fields key will be company_name
				//->order_by(key($search_fields));
				->order_by($filter, "desc");
			//get the results
			$query = $this->db->get($search_table);
			//SELECT * FROM (`sc_meetings`) WHERE `deleted` = 0 AND (`subject` LIKE '%vavava%' OR `date_start` LIKE '%1970-01-01%' OR `date_end` LIKE '%1970-01-01%' OR `location` LIKE '%vavava%' ) ORDER BY `subject`
		}else{
			$query = $this->db->query("SELECT * FROM (`$search_table`) WHERE $filter_where");
		}

		//var_dump($this->db->last_query());
	    if ($query->num_rows() > 0){
			$results = $query->result();
			switch($mod){
				case 1:
					$body = "<tr class='search_result_tr'><td colspan='6' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
								<tr class='search_result_tr'>
									<td><input type='checkbox' name='ids[]' value='" . $row->company_id . "'></td>
									<td class='valign-middle'><a href='" . site_url("companies/view/" . $row->company_id) . "'>" . $row->company_name . "</a></td>
												<td class='valign-middle'>". $row->city . "</td>

												<td class='valign-middle'>". $_SESSION['drop_down_options'][75] . "</td>

									<td class='valign-middle'>
										<a href='" . site_url("companies/edit/" . $row->company_id) . "'><i class='btn btn-xs btn-secondary fa fa-pencil'></i></a>&nbsp;
										<a href='javascript:delete_one(\"" . $row->company_id . "\" )'><i class='btn btn-xs btn-secondary fa fa-times'></i></a>
									</td>
								</tr>";
					}
					$body .= "<tr class='search_result_tr'><td colspan='6' align='center'></td></tr>";
				break;
				case 2:
					$body = "<tr class='search_result_tr'><td colspan='6' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
							<tr class='search_result_tr'>
								<td><input type='checkbox' name='ids[]' value='" . $row->people_id . "'></td>
								<td><a href='" . site_url("people/view/" . $row->people_id) . "'>" . $row->first_name . " " . $row->last_name . "</a></td>
								<td>Type here</td>
								<td>" . $row->email1 . "</td>
								<td class='valign-middle'>
									<a href='" . site_url("people/view/" . $row->people_id) . "'><i class='btn btn-xs btn-secondary fa fa-pencil'></i></a>&nbsp;
									<a href=\"javascript:delete_one( '" . $row->people_id . "' )\"><i class='btn btn-xs btn-secondary fa fa-times'></i></a>
								</td>
							</tr>";
					}
					$body .= "<tr class='search_result_tr'><td colspan='6' align='center'></td></tr>";
				break;
				case 3:
					$body = "<tr class='search_result_tr'><td colspan='6' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr'>
							<td><input type='checkbox' name='ids[]' value='" . $row->deal_id . "'</td>
							<td>" . $row->deal_id . "</td>
							<td>" . $row->name . "</td>
							<td>" . date( config_item('date_display_short'), strtotime($row->date_entered.' UTC')) . "</td>
							<td>
								<div class='btn-group'>
								<button type='button' class='btn btn-default'>Select Action</button>
								<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
								<span class='caret'></span>
								<span class='sr-only'>Toggle Dropdown</span>
								</button>
								<ul class='dropdown-menu' role='menu'>
								<li><a href='" . site_url('deals/edit/' . $row->deal_id) . "'>Edit</a></li>
								<li><a href='" . site_url('deals/view/' . $row->deal_id) . "'>View</a></li>
								<li><a href=\"javascript:delete_one( '" . $row->deal_id . "' )\">Delete</a></li>
								</ul>
								</div><!-- /btn-group -->
							</td>
						</tr>";
					}
					$body .= "<tr class='search_result_tr'><td colspan='6' align='center'></td></tr>";
				break;
				case 5:
					$body = "<tr class='search_result_tr'><td colspan='6' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr'>
							<td><input type='checkbox' name='ids[]' value='" .  $row->note_id . "'></td>
							<td>" . $row->subject . "</td>
							<td>" . $row->note_id . "</td>
							<td>" . date( config_item('date_display_short'), strtotime($row->date_entered.' UTC')) . "</td>
							<td>
								<div class='btn-group'>
									<button type='button' class='btn btn-default'>Select Action</button>
									<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
										<span class='caret'></span>
										<span class='sr-only'>Toggle Dropdown</span>
									</button>
									<ul class='dropdown-menu' role='menu'>
										<li><a href='" . site_url('notes/edit/' . $row->note_id) . "'>Edit</a></li>
										<li><a href='" . site_url('notes/view/' . $row->note_id) . "'>View</a></li>
										<li><a href=\"javascript:delete_one( '" . $row->note_id . "' )\">Delete</a></li>
									</ul>
								</div><!-- /btn-group -->
							</td>
						</tr>";
					}
					$body .= "<tr class='search_result_tr'><td colspan='6' align='center'></td></tr>";
				break;
				case 6:
					$body = "<tr class='search_result_tr'><td colspan='6' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr'>
							<td><input type='checkbox' name='ids[]' value='" . $row->task_id . "'></td>
							<td>" . $row->subject . "</td>
							<td>" . $row->task_id . "</td>
							<td>" . date( config_item('date_display_short'), strtotime($row->date_entered.' UTC')) . "</td>
							<td>
								<div class='btn-group'>
									<button type='button' class='btn btn-default'>Select Action</button>
									<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
										<span class='caret'></span>
										<span class='sr-only'>Toggle Dropdown</span>
									</button>
									<ul class='dropdown-menu' role='menu'>
										<li><a href='" . site_url('tasks/edit/' . $row->task_id) . "'>Edit</a></li>
										<li><a href='" . site_url('tasks/view/' . $row->task_id) . "'>View</a></li>
										<li><a href=\"javascript:delete_one( '" . $row->task_id . "' )\">Delete</a></li>
									</ul>
								</div><!-- /btn-group -->
							</td>
						</tr>";
					}
					$body .= "<tr class='search_result_tr'><td colspan='6' align='center'></td></tr>";
				break;
				case 4:
					$body = "<tr class='search_result_tr'><td colspan='6' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr'>
							<td><input type='checkbox' name='ids[]' value='" . $row->task_id . "'></td>
							<td>" . $row->subject . "</td>
							<td>" . $row->location . "</td>
							<td>" . $row->task_id . "</td>
							<td>" . date( config_item('date_display_short'), strtotime($row->date_entered.' UTC')) . "</td>
							<td>
								<div class='btn-group'>
									<button type='button' class='btn btn-default'>Select Action</button>
									<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
										<span class='caret'></span>
										<span class='sr-only'>Toggle Dropdown</span>
									</button>
									<ul class='dropdown-menu' role='menu'>
										<li><a href='" . site_url('meetings/edit/' . $row->task_id) . "'>Edit</a></li>
										<li><a href='" . site_url('meetings/view/' . $row->task_id) . "'>View</a></li>
										<li><a href=\"javascript:delete_one( '" . $row->task_id . "' )\">Delete</a></li>
									</ul>
								</div><!-- /btn-group -->
							</td>
						</tr>";

					}
					$body .= "<tr class='search_result_tr'><td colspan='6' align='center'></td></tr>";
				break;
			}

		}else{
			$body = "<tr class='search_result_tr'><td colspan='6' align='center'>No Results</td></tr>";
		}
		return array($body, $query->num_rows());
	}
}
