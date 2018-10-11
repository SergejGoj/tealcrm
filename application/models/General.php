<?php
/*
* General class is a model that contains different functions to return specific element such as time difference (5 seconds ago) for activity feed and first name and last name of a specific user
*/
Class General extends CI_Model{

	//return an company name from the table sc_companies
	public function getAccountName($company_id){
		$q = $this
				->db
				->select('company_name')
				->where("company_id", $company_id)
				->limit(1)
				->get("sc_companies");
		if( $q ->num_rows > 0 ){
			return $q->row()->company_name;
		}else{
			return "";
		}
	}
	public function getprojectName($project_id){
		$q = $this
				->db
				->select('subject')
				->where(array("task_id"=>$project_id,"deleted"=>0))
				->limit(1)
				->get("sc_tasks");
		if( $q ->num_rows > 0 ){
			return $q->row()->subject;
		}else{
			return "";
		}
	}

	public function getActualProjectName($project_id){
		$q = $this
				->db
				->select('project_name')
				->where(array("project_id"=>$project_id,"deleted"=>0))
				->limit(1)
				->get("sc_projects");
		if( $q ->num_rows > 0 ){

			return $q->row()->project_name;
		}else{
			
			return "";
		}
	}

	public function getProjectForTask($task_id, $return_type){
	$query_value = $this->db->query("SELECT * FROM sc_project_tasks WHERE (task_id = '" . $task_id . "')");
		
		if ($query_value->num_rows > 0) {

			$project_name =  $this->getActualProjectName($query_value->row()->project_id);

			if ($return_type == "name") {
			return $project_name;	
			}

			else {
				return $query_value->row()->project_id;
			}
			
		}
		else { return ""; }

	}

	//return an people first and last name from the table sc_people
	public function getPersonName($people_id){
		$q = $this
				->db
				->select('first_name,last_name')
				->where("people_id", $people_id)
				->limit(1)
				->get("sc_people");
		if( $q ->num_rows > 0 ){
			return $q->row()->first_name . " " . $q->row()->last_name;
		}else{
			return "";
		}
	}

	//return an object containing user's first and last name from the table sc_user_profiles
	public function getFirstLastName($uid){
		$q = $this
				->db
				->select('upro_first_name, upro_last_name')
				->where("upro_uacc_fk", $uid)
				->limit(1)
				->get("sc_user_profiles");
		if( $q ->num_rows > 0 ){
			return $q->row();
		}else{
			return " - ";
		}
	}

	//return an object containing user's profile picture name from the table sc_user_profiles
	public function getProfilePictureName($uuid){
		$q = $this
				->db
				->select('upro_filename_original')
				->where("upro_uacc_fk", $uuid)
				->get("sc_user_profiles");
			return $q->row();
	}

	public function DisplayOtherUserIcon($file){ //not implemented yet

				// loads the profile image
				$profile_image = new SimpleImage();
				$filename = './../application/attachments/'.$file;
				$default = './../application/attachments/default.png';
				if(file_exists($filename) && !empty($file))
					$profile_image->load($filename,IMAGETYPE_JPEG);
				else
					$profile_image->load($default,IMAGETYPE_JPEG);
				$profile_image->resizeToHeight(35);
				$profile_image->resizeToWidth(35);
				//return $profile_image->show();
				return '<img src="'.$profile_image->show().'" alt="" />';
	}

	//return an deal name from the table sc_deals
	public function getDealName($deal_id){
		$q = $this
				->db
				->select('name')
				->where("deal_id", $deal_id)
				->limit(1)
				->get("sc_deals");
		if( $q ->num_rows > 0 ){
			return $q->row()->name;
		}else{
			return " - ";
		}
	}

	//return time difference in the format (5 seconds ago) or 0 on fail
	public function timeAgo($time){
		$this->load->helper('date');
		$date_entered = 0;
		$timeDiff = abs(mysql_to_unix($time) - time());
		switch ($timeDiff){
			case ($timeDiff < 60): $date_entered = $timeDiff.' seconds ago'; break;
			case ($timeDiff > 60 && $timeDiff < 3600): $min = floor($timeDiff/60); $date_entered = $min.' minutes ago'; break;
			case ($timeDiff > 3600 && $timeDiff < 86400): $hour = floor($timeDiff/3600); $date_entered = $hour.' hour(s) ago'; break;
			default: $date_entered = date('F j, Y \a\t h:i A',strtotime($time));
		}
		return $date_entered;
	}

	public function DisplayUserIcon($filename){ //not implemented yet

				// loads the profile image for the logged in user
				$filename = './../application/attachments/'.$user['upro_filename_original'];
				$profile_image = new SimpleImage();
				if(file_exists($filename) && !empty($filename))
					$profile_image->load($filename,IMAGETYPE_JPEG);
				else
					$profile_image->load($filename,IMAGETYPE_JPEG);
				$profile_image->resizeToHeight(30);
				$profile_image->resizeToWidth(30);
				return '<img src="'.$profile_image->show().'" alt="" />';
	}

	/*
	* Update sc_user_profiles with Google's info
	* PARAM: $user_id NOT unique id, ...
	*/
	public function addUserGoogleInfo($user_id, $google_name, $google_email, $access_token){

		$url = "https://www.googleapis.com/calendar/v3/calendars/" . $google_email . "/events?maxResults=250&access_token=" . $access_token;

		//fetch all the content of the page url
		$json = @file_get_contents($url);

		//sometimes it fails, try again
		if($json === false) $json = @file_get_contents($url); //try again

		//json decode the returned text and sort it as an array
		$json = json_decode($json,true);

		$next_sync_token = $json['nextSyncToken'];
		$data = array(
						'upro_google_name' => $google_name,
						'upro_google_email' => $google_email,
						'upro_google_access_token' => $access_token,
						'upro_google_calendar_nextSyncToken' => $next_sync_token
					);

		//update sc_user_profiles
		$this->db->where('upro_id', $user_id);
		if($this->db->update('sc_user_profiles', $data)){
			return $json;
		}else{
			return 0;
		}
	}

	/**
	* Build meeting/task events list to be shown under javascript (views/meetings/calendar.php)
	*
	* @param NULL
	* @return string sample:
	* {
	*		id: 7563b165-ed84-4895-9ecf-4b9671584591',
	*		title: 'This is one very long subject to see how it fits inside the box!!!! Who knows how it would look like after the fact. Short After Edit',
	*		start: '2014-07-05 12:41:27',
	*		color: '#3A87AD',
	*		end: '2014-07-05 12:41:27'
	*	}
	*/
	public function getCalendarEvents(){
		$events = "";

		// init
		$meetings = new Meeting();

		//select events of the current month
		//$meetings->like("date_start", "%-" . date('m') . "-%");
		//$meetings->or_like("date_end", "%-" . date('m') . "-%");

		//FETCH MEETINGS FIRST
	    // show newest first
		$meetings->where("deleted","0");
	    $meetings->order_by('date_entered', 'DESC');

	    // select
	    $meetings->select('subject,location,meeting_id,date_entered,date_start,date_end,event_type')->get();

		foreach($meetings as $meeting){
			$events .= "{
				id: '" . $meeting->meeting_id . "',
				title: '" . trim($meeting->subject) . "',
				start: '" . date('Y-m-d H:i:s', strtotime($meeting->date_start.' UTC')) . "',
				color: '#43AD3A',
				end: '" . date('Y-m-d H:i:s', strtotime($meeting->date_end.' UTC')) . "'
			},";
		}


		//FETCH TASKS SECOND
		// init
		$tasks = new Task();

		//select events of the current month
		//$tasks->like("due_date", "%-" . date('m') . "-%");
		//$tasks->or_like("due_date", "%-" . date('m') . "-%");
		//$tasks->where("parent_id", "0");
		$tasks->where(array("parent_id"=>"0","deleted"=>"0"));


	    // show newest first
	    $tasks->order_by('date_entered', 'DESC');

	    // select
	    $tasks->select('subject,task_id,date_entered,due_date')->get();
		foreach($tasks as $task){
			$events .= "{
				id: '" . $task->task_id . "',
				title: '" . trim($task->subject) . "',
				start: '" . date('Y-m-d H:i:s', strtotime($task->due_date.' UTC')) . "',
				color: '#3A87AD',
				end: '" . date('Y-m-d H:i:s', strtotime($task->due_date.' UTC')) . "'
			},";
		}


		if($events != "") $events = rtrim($events, ",");

		return $events;
	}


	/**
	* Build meeting/task popup window of the current task_id
	*
	* @param task_id
	* @return 0 on fail, popup window HTML content
	*/
	public function viewCalendarEvent($task_id){

		// init
		$meetg = new Meeting();
		// find
		$meetg->where('meeting_id', $task_id)->get();

		//if $meetg->task_id is TRUE then we are viewing a meeting
		if(isset($meetg->meeting_id)){

			//format start and end date to Monday, January 1st, 1970 11:30PM
			$date_start = date('l, F jS, Y h:iA',strtotime($meetg->date_start.' UTC'));
			$date_end = date('l, F jS, Y h:iA',strtotime($meetg->date_end.' UTC'));
			$location = "<strong><i class='icon-li fa fa-map-marker'></i> Where:</strong> " . $meetg->location . "<br/>";
			$description = $meetg->description;
			$id = $meetg->meeting_id;
			$subject = $meetg->subject;
			$type = "mtg";
			$button = "Meeting";
			$url = site_url('meetings/edit');
			
						//echo back the body of the popup
			return json_encode(array("body"=>"
			<div class=''>
				<div>
					<strong><i class='icon-li fa fa-clock-o'></i> When:</strong> " . $date_start . " to " . $date_end . "<br/>
					" . $location . "
				</div>
				<hr>
				<div>
					<p>" . $description . "</p>
				</div>
				<hr>
				<div>
					<a href='" . $url . "/" . $id . "' class='btn btn-tertiary' data-type='" . $type . "'>Edit " . $button . "</a>
					<a href=javascript:delete_one_".$button."('".$id."') class='btn btn-danger' data-id='" . $id . "' data-type='" . $type . "'>Delete " . $button . "</a>
				</div>
			</div><!-- /.col -->", "subject"=>$subject));

		//else we are viewing a task
		}else{
			$task = new Task();
			$task->where('task_id', $task_id)->get();

			//if $task->task_id is TRUE then we are viewing a task
			if(isset($task->task_id)){
				//format start and end date to Monday, January 1st, 1970 11:30PM
				$date_start = date('l, F jS, Y h:iA',strtotime($task->due_date.' UTC'));
				//$location = $task->location;

				//don't show location for Tasks
				$location = "";
				$description = $task->description;
				$id = $task->task_id;
				$subject = $task->subject;
				$type = "tsk";
				$button = "Task";
				$url = site_url('tasks/edit');
				
				//echo back the body of the popup
				return json_encode(array("body"=>"
				<div class=''>
					<div>
						<strong><i class='icon-li fa fa-clock-o'></i> Due Date:</strong> " . $date_start . "
					</div>
					<hr>
					<div>
						<p>" . $description . "</p>
					</div>
					<hr>
					<div>
						<a href='" . $url . "/" . $id . "' class='btn btn-tertiary' data-type='" . $type . "'>Edit " . $button . "</a>
						<a href=javascript:delete_one_".$button."('".$id."') class='btn btn-danger' data-id='" . $id . "' data-type='" . $type . "'>Delete " . $button . "</a>
					</div>
				</div><!-- /.col -->", "subject"=>$subject));				

			//otherwise return 0 = FAIL
			}else{
				return 0;
			}
		}


	}



}
