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
	 * remap
	 *
	 * @param string $method
	 */
	function _remap($method)
	{
		// auth check
		if ( ! $this->flexi_auth->is_logged_in() )
		{
			redirect('auth/login');
		}

		// check method exists again
		if(method_exists($this, $method)){
			// remove classname and method name form uri
			call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
		}else{
		    // error
			show_404(sprintf('controller method [%s] not implemented!', $method));
		}
	}

	/**
	 * View all
	 *
	 * @url <site>/feeds
	 */
	public function index(){
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

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();
		//uacc_uid
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_id, uacc_uid')->row();

		//get first and last name of the person who posted this
		$this->load->model("general");
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
				//notify_set( array('status'=>'success', 'message'=>'Successfully created new feed.') );

				// redirect
				//redirect( 'notes' );
				echo '
							<div class="feed-item feed-item-bookmark">
								<div class="feed-icon">
									'.$feed_user_icon.'
								</div>
								<!-- /.feed-icon -->
							<div class="feed-headline">
								<span class="feed-subject">' . $user_name->upro_first_name . " " . $user_name->upro_last_name . '</span><br/>

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
		$user_id = $this->flexi_auth->get_user_id();
		//uacc_uid
		$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		//$where_str = '`uacc_id` <> ' . $user_id;
		$where_arr  = array('uacc_id <>' => $user_id);
		// load model
		$users = $this->flexi_auth->get_users_query(array("uacc_uid,CONCAT(upro_first_name, ' ', upro_last_name) AS name", FALSE), $where_arr)->result_array();//$this->flexi_auth_model->get_users()->result_array();
		// set
	    $data['users'] = $users;

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
			$nts->modified_user_id = $user->uacc_uid;
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

   /**
	* Search
	*
	* @param void
	* @return void
	*/
	public function search(){
		// view data init
		$data = array();

		// load view
		$this->layout->view('/notes/search', $data);
	}

	 /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $id ){
		// init
		$nts = new Note();
		// find
		$nts->where('id', $id)->get();

		// Delete
		if( $nts->delete() ){
			// set flash
			notify_set( array('status'=>'success', 'message'=>'Successfully deleted note.') );
		}else{
			// set flash
			notify_set( array('status'=>'error', 'message'=>'Note delete failed.') );
		}

		// redirect
		redirect( 'notes' );
	}

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