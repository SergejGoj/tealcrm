<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Notes Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Feeds extends App_Controller {

   /**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct();
	}

	/**
	 * View all
	 *
	 * @url <site>/feeds
	 */
	public function index(){
		
		echo "hi";	
		// data
		$data = array();

		// init
		$feeds = new Feed();

	    // show newest first
	    $feeds->order_by('date_entered', 'DESC');

	    // select
	    $feeds->select('id,company_id,date_entered,created_by,description');

	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $feeds->limit(5,0)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $feeds->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'feeds');

	    // set
	    $data['feeds'] = $feeds;

		// load view
		//$this->layout->view('/feeds/index', $data);
	}

   /**
	* Add new
	*
	* @param void
	* @return void
	*/
	public function add(){
		// data
		$data = array();

		$user_name = $this->general->getFirstLastName($user_id);

		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('by_uacc_id <>' => $user_id);

		// save
		if( $this->input->post('id', true) ){
			// post
			$post = $this->input->post(null, true);

			// now
			$now = date('Y-m-d H:i:s');
			// new
			$nts = new Feed();

			// Enter values into required fields
			$nts->note_id = $this->uuid->v4();
			//$nts->date_modified = $now;
			$nts->company_id = $post['id'];
			$nts->by_uacc_id = $user->uacc_id;
			$nts->description = nl2br($post['description']);
			$nts->category = $post['cat'];

			//format datetime into "time ago"
			$this->load->model("general");
			$date_entered = $this->general->timeAgo($now);
			$feed_user_image_name = $this->general->getProfilePictureName($user_id);
			$feed_user_icon = $this->general->DisplayOtherUserIcon($feed_user_image_name->upro_filename_original);


			// Save new user
			if( $nts->save() ){
				// set flash

				if(!isset($user_name->first_name) && !isset($user_name->last_name)){
					$name = 'User';
				}
				elseif (isset($user_name->first_name) && !isset($user_name->last_name)){
					$name = $user_name->first_name;
				}
				elseif (!isset($user_name->first_name) && isset($user_name->last_name)){
					$name = $user_name->last_name;
				}
				else{
					$name = $user_name->upro_firstname . ' ' . $user_name->last_name;
				}
				
				echo '
							<div class="feed-item feed-item-bookmark">
								<div class="feed-icon">
									'.$feed_user_icon.'
								</div>
								<!-- /.feed-icon -->
							<div class="feed-headline">
								<span class="feed-subject">' . $name . '</span><br/>

								<i class="fa fa-clock-o"></i> <span class="feed-time">' . $date_entered . '</span>

							</div>
								<div class="feed-content">' . nl2br($post['description']) . '</div>
								<!-- /.feed-content -->
								<!-- /.feed-actions -->
							</div>';

			}
		}

		// load view
		//$this->layout->view('/notes/add', $data);
	}

   /**
	* Edit existing
	*
	* @param int $id
	* @return void
	*/
	public function edit( $id ){
		// data
		$data = array();

		//logedin user(id)
		$user_id = $_SESSION['user']->id;
		//id
		$user = $_SESSION['user'];

	    $data['users'] = $_SESSION['user_accounts'];

		// init
		$nts = new Note();

		// find
		$nts->get_where('id', $id)->get();

		// save
		if( 'save' == $this->input->post('act', true) ){
			// post
			$post = $this->input->post(null, true);
			// now
			// set
			$nts->modified_user_id = $user->id;
			$nts->assigned_user_id = $post['assigned_user_id'];
			$nts->subject = $post['subject'];
			$nts->company_id = $post['company_id'];
			$nts->people_id = $post['people_id'];
			$nts->country = $post['country'];
			$nts->description = $post['description'];

			// Save new user
			if( $nts->save() ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>'Successfully updated note.') );

				// redirect
				redirect( 'notes' );
			}
		}
		// check(id)
		if( ! isset($nts->id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No note by Id #%d.', $id) ) );

			// redirect
			redirect( 'notes' );
		}

		// set
		$data['note'] = $nts;

		// load view
		$this->layout->view('/notes/edit', $data);
	}

   /**
	* View existing
	*
	* @param int $id
	* @return void
	*/
	public function view( $id ){
		// data
		$data = array();

		// init
		$nts = new Note();

		// find
		$nts->where('id', $id)->get();

		// check(id)
		if( ! isset($nts->id) ){
			// set flash
			notify_set( array('status'=>'error', 'message'=>sprintf('No note by Id #%d.', $id) ) );

			// redirect
			redirect( 'notes' );
		}

		// set
		$data['note'] = $nts;

		// load view
		$this->layout->view('/notes/view', $data);
	}

//    /**
// 	* Search
// 	*
// 	* @param void
// 	* @return void
// 	*/
// 	public function search(){
// 		// view data init
// 		$data = array();

// 		// load view
// 		$this->layout->view('/notes/search', $data);
// 	}

// 	 /**
// 	* Delete
// 	*
// 	* @param void
// 	* @return void
// 	*/
// 	public function delete( $id ){
// 		// init
// 		$nts = new Note();
// 		// find
// 		$nts->where('id', $id)->get();

// 		// Delete
// 		if( $nts->delete() ){
// 			// set flash
// 			notify_set( array('status'=>'success', 'message'=>'Successfully deleted note.') );
// 		}else{
// 			// set flash
// 			notify_set( array('status'=>'error', 'message'=>'Note delete failed.') );
// 		}

// 		// redirect
// 		redirect( 'notes' );
// 	}

	/**
	* Delete all
	*
	* @param void
	* @return void
	*/
	public function delete_all( ){
		// post
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];

			// init
			$nts = new Note();

			// find in
			$nts->where_in('id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($nts->all as $nt)
			{
			   	// delete
			   	if( $nt->delete() ){
			   		$deleted++;
			   	}
			}

			// message
			if( $deleted ){
				// set flash
				notify_set( array('status'=>'success', 'message'=>sprintf('Successfully deleted %d note(s).', $deleted) ) );
			}else{
				// set flash
				notify_set( array('status'=>'error', 'message'=>'Note delete failed.') );
			}
		}

		// redirect
		redirect( 'notes' );
	}


}

/* End of file notes.php */
/* Location: ./application/controllers/notes.php */