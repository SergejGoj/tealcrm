<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * Pages Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Pages extends App_Controller {
	
	/**
	 * construct
	 * 
	 * @param void
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * index, content, all page routed here if 404 /not found
	 * 
	 * @param void
	 */ 
	function index( $page = '404')
	{			
		/*// get args form uri
		$args = get_content_args();				
		// sanitize
		$type = xss_clean($args['type']);
		$guid = xss_clean($args['guid']);					
		// template data
		$data = array('base_url'=>base_url(),'site_liveurl'=>config_item('site_liveurl'),
				      'site_copyright'=>config_item('site_copyright'),'site_url'=>site_url(),
					  'site_title'=>config_item('site_name'),'site_name'=>config_item('site_name'),
					  'site'=>SITE);	*/	
		/*// get page data
		$data['content'] = get_content(array('guid'=>$guid,'type'=>$type), $data);// page/post or custom type	
		
		// get message
		$this->_get_message();	
		// crumbs, except post,page and title made from guid/slug
		if(!in_array($data['content']['type'], array('post','page','article')) && $data['content']['type'] != $data['content']['title']){
			// if not 404
			if($data['content']['is_404']){
				$crumbs = $data['content']['type'];
			}else{
				$crumbs = array(sprintf('<a href="%s">%s</a>',site_url($data['content']['type']),$data['content']['type']), strtolower($data['content']['title']));
			}
		}else{		
			$crumbs = strtolower($data['content']['title']);
		}			
		// set breadcrumb	
		$this->breadcrumb->set_crumb($crumbs);
		// meta keywords
		if(isset($data['content']['id']) && $keywords = get_content_meta($data['content']['id'],'keywords')){
			$this->breadcrumb->set_meta('keywords', $keywords);
		}
		// meta description
		if(isset($data['content']['id']) && $description = get_content_meta($data['content']['id'],'description')){
			$this->breadcrumb->set_meta('description', $description);
		}
		// view template
		$view_template = sprintf('pages/type/%s', $data['content']['layout']);	*/	

		$data = array();
		
		// 404
		if( 404 == $page || 500 == $page ){
			$this->layout->setLayout('/layouts/error');
		}	
		
		// load view
		$this->layout->view('/pages/'. $page, $data);	
	}	
		
	/**
	 * person us
	 * 
	 * @param void
	 */ 
	function contact_us(){
		// data
		$data = array();		
		// submit
		if($this->input->post('process') == 'true'){
			// errors
			$errors = array();		
			// submit
			$name    = $this->input->post('name');
			$email   = $this->input->post('email');
			$message = $this->input->post('message');
					
			// send 
			$status = mail_contact_info(array('name'=>$name,'email'=>$email,'message'=>$message));
			// check
			if($status){
			// set message
				$this->_set_message(array('message'=>'Your contact request submitted successfully.','status'=>'success'));
				// redirect
				redirect('contact-us');
			}else{	
				$GLOBALS['data']['message'] = 'Contact request submission failed, please try again!';
				$GLOBALS['data']['status']  = 'error';	
			}	
			
			// other wise show other validation errors
			$GLOBALS['data']['message'] = 'Contact request submission failed, email could not be sent!';
			$GLOBALS['data']['status']  = 'error';
			$GLOBALS['data']['errors']  = $errors;			
		}			
				
		// get message
		$this->_get_message();	
		// set breadcrumb	
		$this->breadcrumb->set_crumb('contact us');
		// view
		$view = '/pages/contact_us' . ((user_is_logged_in(array('recruiter','recruiter-user'))) ? '_account' : '');
		// view
		$this->layout->view($view, array('data'=>$data));	
	}
	
	/**
	 * offline
	 * 
	 * @param void
	 */ 
	function offline()
	{
		// check
		if(config_item('site_offline') == 'no')
			redirect('');		
		// view
		$this->layout->view('/offline');		
	}
	
	/**
	 * offline bypass
	 * 
	 * @param void
	 */ 
	function offline_bypass()
	{
		// code match
		if($this->uri->segment(3) == config_item('site_offline_bypass')){
			// set cookie
			set_cookie('_BYPASS', '1', 0);
			// redirect
			redirect('');
		}
		// default
		redirect('offline');
	}	
	
}

/* End of file pages.php */
/* Location: ./application/controllers/pages.php */