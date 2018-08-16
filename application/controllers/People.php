<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
error_reporting(0);
/**
 * people Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class people extends App_Controller {

    /**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct("Person","people","person");
	}

	public function related_companies($related_company_id){
		// data
		$data = array();

		// init
		$people = new Person();

	    // show newest first
	    $people->order_by('date_entered', 'DESC');


		// select
	    $people->select('first_name,last_name,job_title,email1,phone_mobile,phone_work,people_id,date_entered,contact_type');

		// show non-deleted
		$people->group_start()
				->where('deleted','0')
				->group_end();

		$people->where('company_id',$related_company_id);

		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['people'])){


			//var_dump($_SESSION['search']['people']);exit();

			$people->group_start();

			foreach($_SESSION['search']['people'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "company_viewer"){
					$people->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$people->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$people->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$people->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$people->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

					}
				}

				if($key == "full_name"){
					$people->like("first_name", $value);
					$people->or_like("last_name", $value);
					$people->or_like("concat(first_name,' ',last_name) ", $value);
				}

			}

			// set display settings
			if(isset($_SESSION['search']['people']['search_type'])){
				if($_SESSION['search']['people']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['people']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$people->group_end();

		}

		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['people']['company_id']=$related_company_id;
		if(!empty($_SESSION['search']['people']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['people']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

	    // row per page
	    $row_per_page = config_item('row_per_page');

	    // uri segment for page
	    $uri_segment = 2;

	    // offset
	    $current_page_offset = $this->uri->segment($uri_segment, 0);

	    // show 10 posts per page
	    // $people->get_paged_iterated(1, 10);

	    // iterated
	    $people->limit($row_per_page, $current_page_offset)->get_iterated();
//		$people->check_last_query();
	    // log query
	    // echo $companies->check_last_query();

	    // total
	    $total_count = $people->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'people');

	    // set
	    $data['people'] = $people;
	    
	    		$this->load->helper('list_views');
		list ($label, $people_updated_fields, $custom_values) = people_list_view();		

		$data['field_label'] = $label;
		$data['people_updated_fields'] = $people_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/people/index', $data);
	}

	public function export()
	{

	$people = new Person();

	    // show newest first
	    $people->order_by('date_entered', 'DESC');


		// select
	    $people->select('first_name,last_name,job_title,email1,phone_mobile,phone_work,people_id,date_entered,contact_type');

		// show non-deleted
		$people->group_start()
				->where('deleted','0')
				->group_end();

		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['people'])){


			//var_dump($_SESSION['search']['people']);exit();

			$people->group_start();

			foreach($_SESSION['search']['people'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "full_name" && $key != "company_viewer"){
					$people->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$people->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$people->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$people->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$people->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;

					}
				}

				if($key == "full_name"){
					$people->like("first_name", $value);
					$people->or_like("last_name", $value);
					$people->or_like("concat(first_name,' ',last_name) ", $value);
				}

			}

			// set display settings
			if(isset($_SESSION['search']['people']['search_type'])){
				if($_SESSION['search']['people']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['people']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$people->group_end();



	}
	$this->index(TRUE);
		// run export

        // load all users
        $people->get();
        // Output $u->all to /tmp/output.csv, using all database fields.
        $people->csv_export('../attachments/people.csv');

	$this->load->helper('download_helper');
		force_download('people.csv', '../attachments/people.csv');
	}





	public function export_all()
	{
	$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) )
		{
			// ids
			$ids = $post['ids'];

			// init
			$person = new Person();

			// find in
			$person->where_in('people_id', $ids)->get();

	      $person->csv_export('../attachments/people.csv');

		$this->load->helper('download_helper');
		force_download('people.csv', '../attachments/people.csv');
	    }
	redirect( 'people' );
	}

}

/* End of file people.php */
/* Location: ./application/controllers/people.php */
