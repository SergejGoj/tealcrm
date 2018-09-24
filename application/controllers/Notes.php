<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
error_reporting(0);
/**
 * Notes Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Notes extends App_Controller {

   /**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct("Note","notes","note");

	}



   /**
	* Search
	*
	* @param void
	* @return void
	*/
    public function search($saved_search_id = NULL, $delete = NULL){

        unset($_SESSION['search']['notes']); // kills search session

        $params = array('notes',$saved_search_id,$delete,$this->input->post('saved_search_name'),$_POST);
        $this->load->library('AdvancedSearch', $params); // initiate advancedsearch class

        // check if user is trying to save a search parameter
        if(isset($_POST['saved_search_result'])){
            $this->advancedsearch->search_string = $_POST;
            $this->advancedsearch->Insert_Saved_Search();
            $_SESSION['search']['notes']['search_type'] = "advanced";
        }
		else if($_POST['saved_search_name'] !="")
		{
		$this->advancedsearch->search_string = $_POST;
		$this->advancedsearch->Insert_Saved_Search();
		$_SESSION['search']['notes']['search_type'] = "advanced";
		}

        // did the user hit the CLEAR button, if yes skip everything
        if(!isset($_POST['clear']) && !isset($delete)){
            $this->advancedsearch->Store_Search_Criteria();
        }

        $this->advancedsearch->Set_Search_Type(); // sets what type of search to show

        if(!is_null($delete)){
            $this->advancedsearch->Delete_Saved_Search();
            unset($_SESSION['search']['notes']);
        }

        // store search ID
        $_SESSION['search_id'] = $saved_search_id;

        // done all of our search work, redirect to people view for the magic
        header("Location: ".site_url('notes'));

    }

	 /**
	* Delete
	*
	* @param void
	* @return void
	*/
	public function delete( $note_id ){
		// init
		$nts = new Note();
		// find
		$nts->where('note_id', $note_id)->get();

		// soft_delete(array(fields=>values):where clause)
		if( $nts->soft_delete(array("note_id"=>$note_id)) ){
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
			$nts->where_in('note_id', $ids)->get();

			// init
			$deleted = 0;
			// loop
			foreach ($nts->all as $nt)
			{
			   	// delete
				if( $nt->soft_delete(array("note_id"=>$post['ids'][$deleted])) ){
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

	public function related_companies($related_company_id){
		// data
		$data = array();

		// init
		$notes = new Note();

	    // show newest first
	    $notes->order_by('date_entered', 'DESC');

	    // select
	    $notes->select('subject,note_id,date_entered,filename_original,(SELECT company_name FROM sc_companies WHERE company_id= sc_notes.company_id) as company_name, (SELECT first_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_first, (SELECT last_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_last');

		// show non-deleted
		$notes->group_start()
				->where('deleted','0')
				->group_end();

		$notes->where('company_id',$related_company_id);
		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['notes'])){

			$notes->group_start();

			foreach($_SESSION['search']['notes'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$notes->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$notes->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$notes->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$notes->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$notes->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						break;
					}
				}

				if($key == "deal_value_start" || $key == "deal_value_end"){

					// clean up stuff
					$value = str_replace("$", "", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(",", "", $value);

					if(!is_numeric($value)){
						$value = 0;
					}

					switch($key){
						case'deal_value_start':$notes->where('value >=', $value);break;
						case'deal_value_end':$notes->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['notes']['search_type'])){
				if($_SESSION['search']['notes']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$notes->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['notes']['company_id']=$related_company_id;
		if(!empty($_SESSION['search']['notes']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['notes']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['notes']['people_id']);
		if(!empty($_SESSION['search']['notes']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['notes']['people_id'])->get();

			// set
			$data['person'] = $acct;

		}

	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $notes->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $notes->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'notes');

	    // set
	    $data['notes'] = $notes;

		$this->load->helper('list_views');
		list ($label, $note_updated_fields, $custom_values) = notes_list_view();		
		
		$data['field_label'] = $label;
		$data['note_updated_fields'] = $note_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/notes/index', $data);
	}

	public function related_people($related_people_id){
		// data
		$data = array();

		// init
		$notes = new Note();

	    // show newest first
	    $notes->order_by('date_entered', 'DESC');

	    // select
	    $notes->select('subject,note_id,date_entered,filename_original,(SELECT company_name FROM sc_companies WHERE company_id= sc_notes.company_id) as company_name, (SELECT first_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_first, (SELECT last_name FROM sc_people WHERE people_id=sc_notes.people_id) as person_last');

		// show non-deleted
		$notes->group_start()
				->where('deleted','0')
				->group_end();

		$notes->where('people_id',$related_people_id);
		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['notes'])){

			$notes->group_start();

			foreach($_SESSION['search']['notes'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$notes->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$notes->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$notes->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$notes->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$notes->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
				}

				if($key == "deal_value_start" || $key == "deal_value_end"){

					// clean up stuff
					$value = str_replace("$", "", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(",", "", $value);

					if(!is_numeric($value)){
						$value = 0;
					}

					switch($key){
						case'deal_value_start':$notes->where('value >=', $value);break;
						case'deal_value_end':$notes->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['notes']['search_type'])){
				if($_SESSION['search']['notes']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$notes->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['notes']['company_id']);
		if(!empty($_SESSION['search']['notes']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['notes']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['notes']['people_id']=$related_people_id;
		if(!empty($_SESSION['search']['notes']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['notes']['people_id'])->get();

			// set
			$data['person'] = $acct;

		}

	     // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $companies->get_paged_iterated($current_page_offset, $row_per_page);

	    // iterated
	    $notes->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $notes->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'notes');

	    // set
	    $data['notes'] = $notes;

		$this->load->helper('list_views');
		list ($label, $note_updated_fields, $custom_values) = notes_list_view();		
		
		$data['field_label'] = $label;
		$data['note_updated_fields'] = $note_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/notes/index', $data);
	}
	public function download_note($fileid)
	{

		$data = array();
			// init
		$query = $this->db->get_where($this->config->item('db_prefix') . 'file_uploads', array('id' => $fileid, 'deleted' => NULL), 1);

		$nts = $query->row();

		$filename = $nts->filename_original;

		$image_name = $nts->filename_original;
		$image_path = APPPATH.'attachments/'.$image_name;
		header('Content-Type: '.$nts->filename_mimetype);
		header("Content-Disposition: attachment; filename=$filename");
		header('Content-Description: File Transfer');
		header('Content-Transfer-Encoding: binary');
		ob_clean();
		flush();
		readfile($image_path);
		exit();
	}

}

/* End of file notes.php */
/* Location: ./application/controllers/notes.php */