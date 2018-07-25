<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
/**
 * AUth Controller
 *
 * @package SecretCRM
 * @subpackage Controller
 * @author SecretCRM Team
 * @since 1.0
 * @version 1.0
 */
class Auth extends App_Controller {
	/**
	 * construct
	 *
	 * @param void
	 */
	function __construct()
	{
		// call parent
		parent::__construct();

		// different layout
		$this->layout->setLayout('/layouts/pre_login');
	}

	/**
	 * remap
	 *
	 * @param string $method
	 */
	function _remap($method)
	{

		// logged in
		if( !  in_array($method, array('logout','logout_session')) )
		{
			if ( $this->flexi_auth->is_logged_in() )
			{
				redirect('users/dashboard');
			}
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
	 * index
	 * Forwards to 'login'.
	 */
	function index()
    {
		$this->login();
	}

	/**
	 * login
	 *
	 * @url <site>/auth/login
	 */
	function login()
	{
		// data
		// $data = array();

		// If 'Login' form has been submited, attempt to log the user in.
		if( 'login_user' == $this->input->post('act', true) )
		{
			// load model
			$this->load->model('auth_model');
			// trigger
			$this->auth_model->login();
		}

		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];

		// load view
		$this->layout->view('/auth/login', $this->data);
	}

	/**
	 * logout
	 * This example logs the user out of all sessions on all computers they may be logged into.
	 * In this demo, this page is accessed via a link on the demo header once a user is logged in.
	 *
	 * @url <site>/auth/logout
	 */
	function logout()
	{
		// data
		// $data = array();
		// echo 'OK: pending';
		$this->db->where('status', 'Not Archived');
		$this->db->where('category !=', 'SENT');
		$this->db->delete('sc_messages');

		// By setting the logout functions argument as 'TRUE', all browser sessions are logged out.
		$this->flexi_auth->logout(TRUE);

		// destory static data for teal
		$CI =& get_instance();
		$CI->load->model('teal_global_vars');
		$CI->teal_global_vars->destory_teal_global_vars_cookie();

		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

		redirect('auth');
	}

	/**
	 * logout_session
	 * This example logs the user only out of their CURRENT browser session (e.g. Internet Cafe), but no other logged in sessions (e.g. Home and Work).
	 * In this demo, this controller method is actually not linked to. It is included here as an example of logging a user out of only their current session.
	 */
	function logout_session()
	{
		// By setting the logout functions argument as 'FALSE', only the current browser session is logged out.
		$this->flexi_auth->logout(FALSE);

		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

		redirect('auth');
    }

	/**
	 * forgotten_password
	 * Send user an email to verify their identity. Via a unique link in this email, the user is redirected to the site so they can then reset their password.
	 * In this demo, this page is accessed via a link on the login page.
	 *
	 * Note: This is step 1 of an example of allowing users to reset a forgotten password manually.
	 * See the auto_reset_forgotten_password() function below for an example of directly emailing the user a new randomised password.
	 *
	 * @url <site>/auth/forgotten_password
	 */
	function forgotten_password()
	{
		// data
		// $data = array();

		// If 'reset_password' form has been submited
		if( 'reset_password' == $this->input->post('act', true) )
		{
			// load model
			$this->load->model('auth_model');
			// trigger
			$this->auth_model->forgotten_password();
		}

		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];

		// load view
		$this->layout->view('/auth/forgotten_password', $this->data);
	}

	/**
	 * manual_reset_forgotten_password
	 * This is step 2 (The last step) of an example of allowing users to reset a forgotten password manually.
	 * See the auto_reset_forgotten_password() function below for an example of directly emailing the user a new randomised password.
	 * In this demo, this page is accessed via a link in the 'views/includes/email/forgot_password.tpl.php' email template, which must be set to 'auth/manual_reset_forgotten_password/...'.
	 *
	 * @url <site>/auth/manual_reset_forgotten_password
	 */
	function manual_reset_forgotten_password($user_id = FALSE, $token = FALSE)
	{
		// data
		// $data = array();
		
		// Feb 1, 2017 - DM
		// ADD RESET FUNCTION - sometimes we have an issue with password resets
		
		$myquery ="UPDATE sc_user_accounts SET uacc_suspend = 0, uacc_fail_login_attempts ='', uacc_fail_login_ip_address='', uacc_date_fail_login_ban='' WHERE uacc_id = '".$user_id."'";

		$CI =& get_instance();	
		$query = $CI->db->query($myquery);

		// If 'reset_password' form has been submited
		if( 'change_forgotten_password' == $this->input->post('act', true) )
		{
			// load model
			$this->load->model('auth_model');
			// trigger
			$this->auth_model->manual_reset_forgotten_password($user_id, $token);
		}

		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];

		// load view
		$this->layout->view('/auth/forgot_password_update', $this->data);
	}

	/**
	 * auto_reset_forgotten_password
	 * This is an example of automatically reseting a users password as a randomised string that is then emailed to the user.
	 * See the manual_reset_forgotten_password() function above for the manual method of changing a forgotten password.
	 * In this demo, this page is accessed via a link in the 'views/includes/email/forgot_password.tpl.php' email template, which must be set to 'auth/auto_reset_forgotten_password/...'.
	 */
	function auto_reset_forgotten_password($user_id = FALSE, $token = FALSE)
	{
		// forgotten_password_complete() will validate the token exists and reset the password.
		// To ensure the new password is emailed to the user, set the 4th argument of forgotten_password_complete() to 'TRUE' (The 3rd arg manually sets a new password so set as 'FALSE').
		// If successful, the password will be reset and emailed to the user.
		$this->flexi_auth->forgotten_password_complete($user_id, $token, FALSE, TRUE);

		// Set a message to the CI flashdata so that it is available after the page redirect.
		$this->session->set_flashdata('message', $this->flexi_auth->get_messages());

		redirect('auth');
	}

	/**
	 * resend_activation_token
	 *
	 * @url <site>/auth/resend_activation_token
	 */
	function resend_activation_token()
	{
		// data
		$data = array();

		// echo 'OK: pending';
		// If the 'Resend Activation Token' form has been submitted, resend the user an company activation email.
		if( 'send_activation_token' == $this->input->post('act', true) )
		{
			// load model
			$this->load->model('auth_model');
			// trigger
			$this->auth_model->resend_activation_token();
		}

		// Get any status message that may have been set.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];


		// load view
		$this->layout->view('/auth/resend_activation_token', $data);
	}

	/**
	 * lockout
	 *
	 * @url <site>/auth/lockout
	 */
	function lockout()
	{
		// data
		$data = array();

		// load view
		$this->layout->view('/auth/lockout', $data);
	}

	/**
	 * test
	 */
	function test(){

		/*$this->auth = new stdClass;

		$this->load->model('flexi_auth_model');


		pr($this->flexi_auth_model->auth->auth_security);
		die;
		$store_database_salt = TRUE;//$this->flexi_auth_model->auth->auth_security['store_database_salt'];



	    $database_salt = $store_database_salt ? $this->flexi_auth_model->generate_token($this->flexi_auth_model->auth->auth_security['database_salt_length']) : FALSE;



		$password = '123456';

		echo '<br>password: '.$password;

		echo '<br>database_salt: '.$database_salt;

		$hash_password = $this->flexi_auth_model->generate_hash_token($password, $database_salt, TRUE);


		echo '<br>hash_password: '.$hash_password;*/

		//$this->flexi_auth->change_password();
	}

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */