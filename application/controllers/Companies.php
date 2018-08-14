<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Companies Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Companies extends App_Controller {

	/**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct("Company","companies","company");
	}

	/**
	 * Export CSV File
	 *
	 * @param void
	 * @return void
	 */
	public function export()
	{
		$companies = new Company();

		$companies->select('company_name,email1,company_id,date_entered,company_type,city');


		// show newest first
		$companies->order_by('date_entered', 'DESC');

		// show non-deleted
		$companies->group_start()
		->where('deleted','0')
		->group_end();

		$companies->where("deleted", 0);
		if(!empty($_SESSION['search']['companies'])){

			$companies->group_start();

			foreach($_SESSION['search']['companies'] as $key => $value){
				if($key != "search_type" && $key != "date_entered_start" && $key != "date_entered_end" && $key != "date_modified_start" && $key != "date_modified_end"){
					$companies->like($key, $value);
				}

				if($key == "date_entered_start" || $key == "date_entered_end" || $key == "date_modified_start" || $key == "date_modified_end"){

					switch($key){
						case'date_entered_start':$companies->where('date_entered >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_entered_end':$companies->where('date_entered <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
						case'date_modified_start':$companies->where('date_modified >=', gmdate('Y-m-d 00:00:00', strtotime($value)));break;
						case'date_modified_end':$companies->where('date_modified <=', gmdate('Y-m-d 23:59:59', strtotime($value)));break;
					}
					
				}

			}

			// set display settings
			if(isset($_SESSION['search']['companies']['search_type'])){
				if($_SESSION['search']['companies']['search_type'] == "adv_search_go"){
					$search_tab = "advanced";
				}
				elseif($_SESSION['search']['companies']['search_type'] == "saved"){
					$search_tab = "saved";
					$data['search_id'] = '';
				}
			}

			$companies->group_end();

		}

		// check for session variables related to search
		$this->index(TRUE);
		// run export

		// load all users
		$companies->get();
		// Output $u->all to /tmp/output.csv, using all database fields.
		$companies->csv_export('../attachments/Companies.csv');

		$this->load->helper('download_helper');
		force_download('Companies.csv', '../attachments/Companies.csv');

	}

	public function export_all()
	{
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) ){
			// ids
			$ids = $post['ids'];

			// init
			$accts = new Company();

			// find in
			$accts->where_in('company_id', $ids)->get();

			$accts->csv_export('../attachments/'.$_SERVER['HTTP_HOST'].'/Companies.csv');

			$this->load->helper('download_helper');
			force_download('Companies.csv', '../attachments/'.$_SERVER['HTTP_HOST'].'/Companies.csv');
		}
		redirect( 'companies' );
	}
}

/* End of file companies.php */
/* Location: ./application/controllers/companies.php */