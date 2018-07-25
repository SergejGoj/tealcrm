<?php
class Global_search extends CI_Model {

	public function seachAll($term){

		$count = 0;
		$body = "";

		list($acc_return, $acc_rows) = $this->seachModules(1, $term);
		$count += $acc_rows;
		$body .= $acc_return;

		list($con_return, $con_rows) = $this->seachModules(2, $term);
		$count += $con_rows;
		$body .= $con_return;

		list($return, $rows) = $this->seachModules(3, $term);
		$count += $rows;
		$body .= $return;

		list($return, $rows) = $this->seachModules(4, $term);
		$count += $rows;
		$body .= $return;

		list($return, $rows) = $this->seachModules(5, $term);
		$count += $rows;
		$body .= $return;

		list($return, $rows) = $this->seachModules(6, $term);
		$count += $rows;
		$body .= $return;

		return array($body, $count);
	}

	/* Search modules for a provided term
	* @version 1: search tables directly excluding related fields ie lead status
	* @param $mod the module number 1,2,3,4,5, or 6
	* @param $term the term we are searching for
	* @return styled html search result
	*/
	private function seachModules($mod, $term){

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
			case 1: $search_fields = $accounts_searchable_fields; $search_table="sc_companies"; $rowClass="tr_accounts"; break;
			case 2: $search_fields = $people_searchable_fields; $search_table="sc_people"; $rowClass="tr_people"; break;
			case 3: $search_fields = $deals_searchable_fields; $search_table="sc_deals"; $rowClass="tr_deals"; break;
			case 4: $search_fields = $notes_searchable_fields; $search_table="sc_notes"; $rowClass="tr_notes"; break;
			case 5: $search_fields = $tasks_searchable_fields; $search_table="sc_tasks"; $rowClass="tr_tasks"; $extra_where=array("parent_id", '0'); break;
			case 6: $search_fields = $meetings_searchable_fields; $search_table="sc_meetings"; $rowClass="tr_meetings"; break;
		}

		$body = "";

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

		//special case for people to search first and last name
		if( $search_table == "sc_people" ){
			$split = explode(" ", $term);
			if(sizeof($split) > 1){
				$where_array[]  = "`first_name` LIKE '%" . $split[0] . "%' ";
				$where_array[]  = "`last_name` LIKE '%" . $split[1] . "%' ";
			}
		}
		$where = "(".implode("OR ", $where_array).")";

		//pass the LIKE statement to where
		$this->db
			->where($where, NULL, FALSE)
			//ordery by the first key in the search_fields array; ie for $accounts_searchable_fields key will be company_name
			->order_by(key($search_fields));

		//get the results
		$query = $this->db->get($search_table);
		//var_dump($this->db->_error_message());

	    if ($query->num_rows() > 0){
			$results = $query->result();
			switch($mod){
				case 1:
					//$body = "<tr class='search_result_tr'><td colspan='2' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
								<tr class='search_result_tr " . $rowClass . "'>
									<td class='text-center'><strong>Companies</strong></td>
									<td class='valign-middle'><a href='" . site_url("companies/view/" . $row->company_id) . "' style='text-decoration:none; color:black;'><div>" . $row->company_name . "<br/>Email address: " . $row->email1 . "</div></a></td>
								</tr>";
					}
					//$body .= "<tr class='search_result_tr'><td colspan='2' align='center'></td></tr>";
				break;
				case 2:
					//$body = "<tr class='search_result_tr'><td colspan='2' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
							<tr class='search_result_tr " . $rowClass . "'>
								<td class='text-center'><strong>People</strong></td>
								<td><a href='" . site_url("people/view/" . $row->people_id) . "' style='text-decoration:none; color:black;'><div>" . $row->first_name . " " . $row->last_name . "<br/>Email address: " . $row->email1 . "</div></a></td>
							</tr>";
					}
					//$body .= "<tr class='search_result_tr'><td colspan='2' align='center'></td></tr>";
				break;
				case 3:
					//$body = "<tr class='search_result_tr'><td colspan='2' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr " . $rowClass . "'>
							<td class='text-center'><strong>Deals</strong></td>
							<td><a href='" . site_url("deals/view/" . $row->deal_id) . "' style='text-decoration:none; color:black;'><div>" . $row->name . "<br/>Description: " . $row->description . "</div></a></td>
						</tr>";
					}
					//$body .= "<tr class='search_result_tr'><td colspan='2' align='center'></td></tr>";
				break;
				case 4:
					//$body = "<tr class='search_result_tr'><td colspan='2' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr " . $rowClass . "'>
							<td class='text-center'><strong>Notes</strong></td>
							<td><a href='" . site_url("notes/view/" . $row->note_id) . "' style='text-decoration:none; color:black;'><div>" . $row->subject . "<br/>Date: " . date('Y-m-d H:i:s',strtotime($row->date_entered.' UTC')) . "</div></a></td>
						</tr>";
					}
					//$body .= "<tr class='search_result_tr'><td colspan='2' align='center'></td></tr>";
				break;
				case 5:
					//$body = "<tr class='search_result_tr'><td colspan='2' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr " . $rowClass . "'>
							<td class='text-center'><strong>Tasks</strong></td>
							<td><a href='" . site_url("tasks/view/" . $row->task_id) . "' style='text-decoration:none; color:black;'><div>" . $row->subject . "<br/>Due Date: " . $row->due_date . "</div></a></td>
						</tr>";
					}
					//$body .= "<tr class='search_result_tr'><td colspan='2' align='center'></td></tr>";
				break;
				case 6:
					//$body = "<tr class='search_result_tr'><td colspan='2' align='center'>Results Found " . $query->num_rows() . "</td></tr>";
					foreach($results as $row){
						$body .="
						<tr class='search_result_tr " . $rowClass . "'>
							<td class='text-center'><strong>Meetings</strong></td>
							<td><a href='" . site_url("tasks/view/" . $row->meeting_id) . "' style='text-decoration:none; color:black;'><div>" . $row->subject . "<br/>Date Start: " . $row->date_start . "</div></a></td>
						</tr>";
					}
					//$body .= "<tr class='search_result_tr'><td colspan='2' align='center'></td></tr>";
				break;
			}

		}else{
			//$body = "<tr class='search_result_tr'><td colspan='2' align='center'>No Results</td></tr>";
		}
		return array($body, $query->num_rows());
	}
}
