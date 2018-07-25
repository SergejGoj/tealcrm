<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Download extends App_Controller {
	public function index(){
		$param = $this->input->get(); // returns all GET items without XSS filtering
		$type = $param['type'];
		header('Content-Description: File Transfer');
		header('Content-Type: $type');
		header('Content-Disposition: attachment; filename='.basename($param['q']));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		readfile(basename($param['q']));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */