<?php
class Feed_list extends CI_Model {

	/**
	* function:getFeedList (access all the activity feeds posted on an company_id(business page) and display them under the Activity Feed box
	* param: $company_id which is the business id
	* return: empty if no activity feeds in DB otherwise boxes of activity feeds
	 */
	public function getFeedList($company_id, $category){
		//format datetime into "time ago"
		$body = "";
		//get first and last name of the person who posted this
		$this->load->model("general");
		//$query = $this->db->get_where("sc_accounts_feeds", array("company_id"=>$company_id));
		$query = $this
				->db
				->where("company_id", $company_id)
				->where("category", $category)
				->order_by("id", "desc")
				->get("sc_feeds", 5, 0);

		$num_rows = $query->num_rows();
	    if ($num_rows > 0){
			$results = $query->result();
			foreach($results as $row){
				$date_entered = 0;
				$user_name = $this->general->getFirstLastName($row->by_uacc_id);
				$date_entered = $this->general->timeAgo(date('Y-m-d H:i:s', strtotime($row->date_entered.' UTC')));
				$feed_user_image_name = $this->general->getProfilePictureName($row->by_uacc_id);
				$feed_user_icon = $this->general->DisplayOtherUserIcon($feed_user_image_name->upro_filename_original);
				$body .= '
							<div class="feed-item feed-item-bookmark">

							<div class="feed-icon">
									'.$feed_user_icon.'
								</div>
								<!-- /.feed-icon -->
							<div class="feed-headline">
								<span class="feed-subject">' . $user_name->upro_first_name . " " . $user_name->upro_last_name . '</span><br/>

								<i class="fa fa-clock-o"></i> <span class="feed-time">' . $date_entered . '</span>

							</div>
								<div class="feed-content">' . $row->description . '</div>
								<!-- /.feed-content -->
								<!-- /.feed-actions -->
							</div>';
			}
			

			if($num_rows > 4)
				$body .= '<div class="feed-more feed-item-idea"><span>Load More</span></div>';

		}
		return $body;
	}

	public function getMoreFeedList($company_id, $last_fetched_feed_id, $category){
		//format datetime into "time ago"
		$body = "";
		//get first and last name of the person who posted this
		$this->load->model("general");
		//$query = $this->db->get_where("sc_accounts_feeds", array("company_id"=>$company_id));
		$query = $this
				->db
				->where("company_id", $company_id)
				->where("category", $category)
				->order_by("id", "desc")
				->get("sc_feeds", 5, $last_fetched_feed_id);

	    if ($query->num_rows() > 0){
			$results = $query->result();
			foreach($results as $row){
				$date_entered = 0;
				$user_name = $this->general->getFirstLastName($row->by_uacc_id);
				$date_entered = $this->general->timeAgo(date('Y-m-d H:i:s', strtotime($row->date_entered.' UTC')));
				$feed_user_image_name = $this->general->getProfilePictureName($row->by_uacc_id);
				$feed_user_icon = $this->general->DisplayOtherUserIcon($feed_user_image_name->upro_filename_original);

				$body .= '
							<div class="feed-item feed-item-bookmark">
								<div class="feed-icon">
									'.$feed_user_icon.'
								</div>
								<!-- /.feed-icon -->
							<div class="feed-headline">
								<span class="feed-subject">' . $user_name->upro_first_name . " " . $user_name->upro_last_name . '</span><br/>

								<i class="fa fa-clock-o"></i> <span class="feed-time">' . $date_entered . '</span>

							</div>
								<div class="feed-content">' . $row->description . '</div>
								<!-- /.feed-content -->
								<!-- /.feed-actions -->
							</div>';
			}

		}
		return array($body, $query->num_rows());
	}

	public function morefeedsload($limit){

	$body = "";
		//get first and last name of the person who posted this
		$this->load->model("general");
		//$query = $this->db->get_where("sc_accounts_feeds", array("company_id"=>$company_id));
		$query = $this
				->db
				->order_by("date_entered", "desc")
				->get("sc_feeds", 5, $limit);

	    if ($query->num_rows() > 0){
			$results = $query->result();
			foreach($results as $row){

				if($row->category == 1)
				{
				$record_query = "SELECT company_name FROM sc_companies where company_id ='".$row->company_id."'";
				$record_query_value = $this->db->query($record_query)->result();
				$record_name = $record_query_value[0]->company_name;
				$record_url = site_url('companies/view/'.$row->company_id);
				$record_name = '<a href="'.$record_url.'">Re: (Company) '.$record_name.'</a>';
				}
				else if($row->category == 2)
				{
				$record_query = "SELECT first_name, last_name FROM sc_people where people_id ='".$row->company_id."'";
				$record_query_value = $this->db->query($record_query)->result();
				$record_name = $record_query_value[0]->first_name.' '.$record_query_value[0]->last_name;
				$record_url = site_url('people/view/'.$row->company_id);
				$record_name = '<a href="'.$record_url.'">Re: (Person) '.$record_name.'</a>';
				}
				else if($row->category == 3)
				{
				$record_query = "SELECT name FROM sc_deals where deal_id ='".$row->company_id."'";
				$record_query_value = $this->db->query($record_query)->result();
				$record_name = $record_query_value[0]->name;
				$record_url = site_url('deals/view/'.$row->company_id);
				$record_name = '<a href="'.$record_url.'">Re: (Deal) '.$record_name.'</a>';
				}
				else if($row->category == 4)
				{
				$record_query = "SELECT subject FROM sc_notes where note_id ='".$row->company_id."'";
				$record_query_value = $this->db->query($record_query)->result();
				$record_name = $record_query_value[0]->subject;
				$record_url = site_url('notes/view/'.$row->company_id);
				$record_name = '<a href="'.$record_url.'">Re: (Note) '.$record_name.'</a>';
				}
				else if($row->category == 5)
				{
				$record_query = "SELECT subject FROM sc_tasks where task_id ='".$row->company_id."'";
				$record_query_value = $this->db->query($record_query)->result();
				$record_name = $record_query_value[0]->subject;
				$record_url = site_url('tasks/view/'.$row->company_id);
				$record_name = '<a href="'.$record_url.'">Re: (Task) '.$record_name.'</a>';
				}
				else if($row->category == 6)
				{
				$record_query = "SELECT subject FROM sc_meetings where meeting_id ='".$row->company_id."'";
				$record_query_value = $this->db->query($record_query)->result();
				$record_name = $record_query_value[0]->subject;
				$record_url = site_url('meetings/view/'.$row->company_id);
				$record_name = '<a href="'.$record_url.'">Re: (Meeting) '.$record_name.'</a>';
				}

				$date_entered = 0;
				$user_name = $this->general->getFirstLastName($row->by_uacc_id);
				$date_entered = $this->general->timeAgo(date('Y-m-d H:i:s', strtotime($row->date_entered.' UTC')));
				$feed_user_image_name = $this->general->getProfilePictureName($row->by_uacc_id);
				$feed_user_icon = $this->general->DisplayOtherUserIcon($feed_user_image_name->upro_filename_original);


				$body .= '
				
					<div class="row" style="padding:8px;">
					    <div class="col-md-12" style="color:#227979">
						    <strong>'. $record_name .'</strong>
					    </div>
					    <div class="col-md-6" style="color:#19A3A5;font-weight:bold;font-size:10px">
						    <span class="feed-subject">' . $user_name->upro_first_name . '</span>
					    </div>
					    <div class="col-md-6" style="color:#19A3A5;font-weight:bold;font-size:10px">
						    <i class="fa fa-clock-o"></i> <span class="feed-time">' . $date_entered . '</span>
					    </div>
					    <div class="col-md-12" style="font-size:11px;margin-bottom:10px">
						    <div class="feed-content">' . $row->description . '</div>
					    </div>	      
				    </div>
				
				';
/*
				$body .= '<b>'.$record_name.'<b>
							<div class="feed-item feed-item-bookmark">
								<div class="feed-icon">
									'.$feed_user_icon.'
								</div>
								<!-- /.feed-icon -->
							<div class="feed-headline">
								<span class="feed-subject">' . $user_name->upro_first_name . " " . $user_name->upro_last_name . '</span><br/>

								

							</div>
								<div class="feed-content">' . $row->description . '</div>
								<!-- /.feed-content -->
								<!-- /.feed-actions -->
							</div>';
							
							
							*/
			}

		}
		return array($body, $query->num_rows());
	}
}
