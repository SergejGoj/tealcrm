<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mask extends App_Controller {

	public function index(){

	}


	/*
	*
	* full documentation (source): http://michael.theirwinfamily.net/articles/csshtml/protecting-images-using-php-and-htaccess
	* full documentatoin for masking link (source) http://hashmode.com/hide-image-path-php/9
	*/

	 /**
	* Echo out the user's profile image
	* @param $_GET['f'] contains random md5(uniqid())
	* @return image with mime-type set to x/jpg for example
	*/

	
	public function maskImg(){
		$post = $this->input->post(null, true);
		$this->load->library('session');
		
		//sample link: http://tealcrm.localhost.com/assets/imgmask.php?f=default.png
		
		$file_name = "./../attachments/" . $this->session->userdata($_GET['f']);
		if(is_file($file_name)){
			$type = $this->getFileType($file_name);
			if($this->acceptableType($type)) {
				header("Content-type: $type");
				echo file_get_contents($file_name);
				$this->session->unset_userdata($_GET['f']);
				exit;
			}
		}else{
			$file_name = "./../attachments/default.png";
			echo file_get_contents($file_name);
			$this->session->unset_userdata($_GET['f']);
			exit;		
		}
		header('HTTP/1.1 403 Forbidden');
		exit;

	}

	// returns file type
	private function getFileType($file) {
		//Deprecated, but still works if defined...
		if (function_exists("mime_content_type"))
			return mime_content_type($file);
			
		 //New way to get file type, but not supported by all yet.
		else if (function_exists("finfo_open")) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$type = finfo_file($finfo, $file);
			finfo_close($finfo);
			return $type;
		}
		
		 //Otherwise...just use the file extension
		else {
			$types = array(
				'jpg' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'png' => 'image/png',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp'
			);
			$ext = substr($file, strrpos($file, '.') + 1);
			if (key_exists($ext, $types))
				return $types[$ext];
			return "unknown";
		}
	}

	// this function has a list of mime-type that WE allow.
	// return True if the file type provided is allowed or False if not
	private function acceptableType($type){
		$array = array("image/jpeg", "image/jpg", "image/png", "image/png");
		if (in_array($type, $array))
			return true;
		return false;
	}
}