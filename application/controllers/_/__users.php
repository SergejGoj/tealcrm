<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//@require(APPPATH.'core/App_Controller.php');

class users extends App_Controller {


	public function login(){	
		// If 'Login' form has been submited, attempt to log the user in.
		if ($this->input->post('login_user')){
			$this->load->model('user_model');
			$this->user_model->login();
		}		
				
		// Get any status message that may have been set.
		//$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->load->view('users/login_view'); //$this->load->view('users/login_view', $this->data);
    }   
   
	
}

