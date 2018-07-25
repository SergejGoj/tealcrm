<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Search Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Search extends App_Controller {		
	 
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
	 * View search results all
	 *
	 * @url <site>/people	
	 */
	public function index(){
		$post = $this->input->post(null, true);
		$term = $post['term'];
		
		if($term == "")
			redirect( 'users/dashboard' );
			
		
		// data
		$data = array();	

		//logedin user	
		$user_id = $this->flexi_auth->get_user_id();
		
		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();	
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();

		//pass a random string to mask user's profile image
		//ie: 3dbc33def75830b6bb32ee30b1b5ec1e
		$data['hashImg_navbar'] = md5(uniqid());

		$this->load->library('session');
		$this->session->set_userdata( 
				array(
					//store the random string in a session as key
					$data['hashImg_navbar']=>
						//store the user profile image ( from upro_filename_original ) as the value for the session
						//if the user has not uploaded an image use the system default image default.png
						(empty($user['upro_filename_original']) ? 'default.png' : $user['upro_filename_original'])
				)
			);
		
		
		$this->load->model("global_search");
		list($results,$rows) = $this->global_search->seachAll($term);
		$data['values'] = $results;
		$data["rows"] = $rows;
		$data["term"] = $term;
		

		// load view
		$this->layout->view('/search/index', $data);
	}

}

/* End of file people.php */
/* Location: ./application/controllers/people.php */