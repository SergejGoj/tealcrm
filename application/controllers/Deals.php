<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
error_reporting(0);
/**
 * Deals Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Deals extends App_Controller {

   /**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct("Deal","deals","deal");

	}

	public function pipeline(){

		$CI =& get_instance();
		$CI->teal_global_vars->set_all_global_vars();


		// data
		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$deals = new Deal();
	    // show newest first
	    $deals->order_by('date_entered', 'DESC');
		$deals->where('deleted', 0);
	    // select

	    $deals->select('name,deal_id,date_entered');


		// set data deals variable
		$data['deals'] = $deals;


		// get the sales stages

		$ss_query = "SELECT * from sc_drop_down_options WHERE (related_field_name = 'sales_stage_id') ORDER BY order_by";
		//print_r($ss_query);
		$sales_stages_result = $this->db->query($ss_query);

		$sales_stages = $sales_stages_result->result();

		foreach ($sales_stages as $ss) {

		if (($ss->name != 'Deal Won') && ($ss->name != 'Deal Lost')) {

			$sales_stage[$ss->drop_down_id] = $ss->name;

			}
		}

		$data['sales_stage'] = $sales_stage;


		// load view
		$this->layout->view('/deals/pipeline', $data);

	}


	public function get_deal_totals() {

		$data = array();

		//logedin user
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		$sske = $this->input->post('sales_stage_key_edit');
		$ssk  =  $this->input->post('sales_stage_key');
		$ssv = $this->input->post('sales_stage_value');
		$ssp = $this->input->post('ss_prob');

		if (isset($sske)) { $data['sales_stage_key_edit'] = $this->input->post('sales_stage_key_edit');  }
		if (isset($ssk)) { $data['sales_stage_key'] = $this->input->post('sales_stage_key');  }
		if (isset($ssv)) { $data['sales_stage_value'] = $this->input->post('sales_stage_value');  }
//		if (isset($ssp)) { $data['ss_prob'] = $this->input->post('ss_prob'); }


		$this->layout->view('/deals/get_deal_totals', $data);
	}

	public function update_ss() {

		$deal_id = $this->input->post('deal_id');
		$ss_id = $this->input->post('deal_ss');

		$update_deal_query = "UPDATE sc_deals set sales_stage_id='".$ss_id."' WHERE (deal_id = '".$deal_id."')";
		$this->db->query($update_deal_query);

	}


	public function related_companies($related_company_id){
		// data
		$data = array();

		// init
		$deals = new Deal();

	    // show newest first
	    $deals->order_by('date_entered', 'DESC');

	    // select
	    $deals->select('name,deal_id,value,sales_stage_id,expected_close_date,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name');

		// show non-deleted
		$deals->group_start()
				->where('deleted','0')
				->group_end();

		$deals->where('sc_deals.company_id',$related_company_id);

		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$deals->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
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
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['deals']['company_id']=$related_company_id;
		if(!empty($_SESSION['search']['deals']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['deals']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['deals']['people_id']);
		if(!empty($_SESSION['search']['deals']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['deals']['people_id'])->get();

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
	    $deals->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	   // echo $deals->check_last_query();

	    // total
	    $total_count = $deals->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'deals');

		$this->load->helper('list_views');
		list ($label, $deal_updated_fields, $custom_values) = deal_list_view();		
		
		$data['field_label'] = $label;
		$data['deal_updated_fields'] = $deal_updated_fields;
		$data['custom_values'] = $custom_values;

	    // set
	    $data['deals'] = $deals;
	    //var_dump($deals);
	    //exit();


		// load view
		$this->layout->view('/deals/index', $data);
	}

	public function related_people($related_people_id){
		// data
		$data = array();

		// init
		$deals = new Deal();

	    // show newest first
	    $deals->order_by('date_entered', 'DESC');

	    // select
	    $deals->select('name,deal_id,value,sales_stage_id,expected_close_date,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name');

		// show non-deleted
		$deals->group_start()
				->where('deleted','0')
				->group_end();

		$deals->where('people_id',$related_people_id);

		$search_tab = "advanced";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$deals->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
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
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();

		}
		$data['search_tab'] = $search_tab;

		//if the user clicked on Add New Person from the companies view page
		unset($_SESSION['search']['deals']['company_id']);
		if(!empty($_SESSION['search']['deals']['company_id'])){
			// init
			$acct = new Company();

			// find
			$acct->where('company_id', $_SESSION['search']['deals']['company_id'])->get();

			// set
			$data['company'] = $acct;

		}

		//if the user clicked on Add New Person from the companies view page
		$_SESSION['search']['deals']['people_id']=$related_people_id;
		if(!empty($_SESSION['search']['deals']['people_id'])){
			// init
			$acct = new Person();

			// find
			$acct->where('people_id', $_SESSION['search']['deals']['people_id'])->get();

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
	    $deals->limit($row_per_page, $current_page_offset)->get_iterated();

	    // log query
	   // echo $deals->check_last_query();

	    // total
	    $total_count = $deals->count();

	    // links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'deals');

	    // set
	    $data['deals'] = $deals;
	    //var_dump($deals);
	    //exit();

		$this->load->helper('list_views');
		list ($label, $deal_updated_fields, $custom_values) = deal_list_view();		
		
		$data['field_label'] = $label;
		$data['deal_updated_fields'] = $deal_updated_fields;
		$data['custom_values'] = $custom_values;

		// load view
		$this->layout->view('/deals/index', $data);
	}

	public function export()
	{
	  $deals = new Deal();

	    // show newest first
	    $deals->order_by('date_entered', 'DESC');

	    // select
	    $deals->select('name,deal_id,value,sales_stage_id,expected_close_date,(SELECT company_name FROM sc_companies WHERE company_id = sc_deals.company_id) as company_name');

		// show non-deleted
		$deals->group_start()
				->where('deleted','0')
				->group_end();

		$search_tab = "basic";
		//**** CHECK FOR SEARCH SETTINGS *****/
		// if empty, that means it was cleared out or never there to begin with
		if(!empty($_SESSION['search']['deals'])){

			$deals->group_start();

			foreach($_SESSION['search']['deals'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end" && $key != "company_viewer" && $key != "person_viewer" && $key != "deal_value_start" && $key != "deal_value_end" && $key != "expected_close_date_start" && $key != "expected_close_date_end"){
					$deals->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end" || $key == "expected_close_date_start" || $key == "expected_close_date_end"){

					switch($key){
						case'date_entered_start':$deals->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$deals->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$deals->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$deals->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case 'expected_close_date_start':$deals->where('expected_close_date >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case 'expected_close_date_end':$deals->where('expected_close_date <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
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
						case'deal_value_start':$deals->where('value >=', $value);break;
						case'deal_value_end':$deals->where('value <=', $value);break;
					}
				}

			}

			// set display settings
			if(isset($_SESSION['search']['deals']['search_type'])){
				if($_SESSION['search']['deals']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
			}

			$deals->group_end();
	  }

	  $this->index(TRUE);
		// run export

        // load all users
        $deals->get();
        // Output $u->all to /tmp/output.csv, using all database fields.


		$deals->csv_export('../attachments/Deals.csv');

		$this->load->helper('download_helper');
		force_download('Deals.csv', '../attachments/Deals.csv');
	}

	public function export_all()
	{

	$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];

			// init
			$dls = new Deal();

			// find in
			$dls->where_in('deal_id', $ids)->get();

			$dls->csv_export('../attachments/'.$_SERVER['HTTP_HOST'].'/Deals.csv');

		$this->load->helper('download_helper');
		force_download('Deals.csv', '../attachments/'.$_SERVER['HTTP_HOST'].'/Deals.csv');
	    }
         redirect( 'deals' );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */