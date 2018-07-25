<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends App_Controller {

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
	 * Index Page for this controller.
	 *
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$user_id = $this->flexi_auth->get_user_id();

		//uacc_email
		$user = $this->flexi_auth->get_user_by_id_query($user_id)->row_array();
		//$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row();
		
		$data = Array();

		$messages = new Message();

		// set
		$messages->select('message_id,subject,message,from_name,from_email,timestamp,category,status,relationship_id');

		$messages->where('created_by',$user['uacc_uid']);
		
		// show newest first
		$messages->order_by('timestamp', 'DESC');
		// show non-deleted

		// row per page
		$row_per_page = config_item('row_per_page');
		// uri segment for page
		$uri_segment = 2;
		// offset
		$current_page_offset = $this->uri->segment($uri_segment, 0);

		// show regular index page
		$messages->limit($row_per_page, $current_page_offset)->get_iterated();
		// log query
		
		// total
		$cont = $messages->count();
		
		if(isset($_SESSION["gmail"]))
		{
			$cont = count($_SESSION["gmail"]);
		}
		$total_count = $cont;

		// links
		$data['pager_links'] = $this->_create_pager_links($row_per_page, $uri_segment, $total_count, 'messages');

		if(isset($_SESSION["gmail"]))
		{
			if(count($_SESSION["gmail"]) < $current_page_offset+20)
			{
				$limit = count($_SESSION["gmail"]);
			}
			else 
			{
				$limit = $current_page_offset+20;
			}
			
			$start = $current_page_offset+1;
			
			for($i = $start; $i<=$limit; $i++)
			{
				$emessage[$i] = $_SESSION["gmail"][$i];
			}
			//set
			$data['start'] = $start;
			$data['messages'] = $emessage;
			
			$relat_rec = array();
						
			foreach($_SESSION["gmail"] as $msg)
			{
				
				if($msg['status'] == 'Company')
				{
					$relat_company = new Company();
				
					$relat_company->where('deleted', 0);
					$relat_company->where('company_id', $msg["relationship_id"])->get();
					
					$relat_rec[$msg["relationship_id"]] = $relat_company->company_name;
					
					$data['relat_rec'] = $relat_rec;
					
				}
				else if($msg['status'] == 'Person')
				{
					$relat_person = new Person();
					
					$relat_person->where('deleted', 0);
					$relat_person->where('people_id', $msg["relationship_id"])->get();
					
					$relat_rec[$msg["relationship_id"]] = $relat_person->first_name." ".$relat_person->last_name;
					
					$data['relat_rec'] = $relat_rec;
					
				}
				else if($msg['status'] == 'Deal')
				{
					$relat_deal = new Deal();
				
					$relat_deal->where('deleted', 0);
					$relat_deal->where('deal_id', $msg["relationship_id"])->get();
					
					$relat_rec[$msg["relationship_id"]] = $relat_deal->name;
					
					$data['relat_rec'] = $relat_rec;
					
				}
				
			}
		}
		$this->layout->view('/messages/index', $data);

	}
	
	
	public function send($module = null, $module_id = null)
	{
		// check to see if IMAP is setup otherwise redirect user:
		if($_SESSION['user']['imap_active'] != 1){
			
				notify_set( array('status'=>'warning', 'message'=>'IMAP Email must be setup before sending emails out using TealCRM.') );

					// redirect
				redirect( 'users/settings' );				
		}
		
		
		//if($module != "people"){
			// not ready for anything but people
			//	notify_set( array('status'=>'error', 'message'=>'Only People Module is supported for messages at this time.') );

					// redirect
				//redirect( );			
			
			
		//}else{	
		
		if(isset($_SESSION['gmail_sync']) == 1 && $this->input->post('act', true) == "true")
		{
			require('../application/third_party/GoogleAPI/blockspring.php');
				
				$send_to = $this->input->post('to_email');
				
				$subject = $this->input->post('subject');
				
				$message = $_REQUEST['html_content'];
				
				$temp = json_decode($_SESSION['token'],true);
				
				$mail = Blockspring::runParsed("send-gmail", array("address" => $send_to, "subject" => $subject, "message" => $message, "html" => true, "google_token" => $temp['access_token']))->params;
				
				if($mail['response'] == "SENT")
				{
					$user_id = $this->flexi_auth->get_user_id();		

					$user = $this->flexi_auth->get_user_by_id_query($user_id,'uacc_uid')->row_array();
					
					$now = gmdate('Y-m-d H:i:s');
					
					$id = $this->uuid->v4();
					
					$ids = $this->uuid->v4();
					
				  if($this->input->post('company_id') != null)
				  {
					$status = "Company";
					$relationship_id = $this->input->post('company_id');
				  }
				  else if($this->input->post('person_id') != null)
				  {
					$status = "Person";
					$relationship_id = $this->input->post('person_id');
				  }
				   else if($this->input->post('deal_id') != null)
				  {
					$status = "Deal";
					$relationship_id = $this->input->post('deal_id');
				  }
				  else
				  {
					$status = "Not Archived";
					$relationship_id = "";
				  }
					
					//INSERT GMAIL				
					$data = array(
					"mail_id" => $id,
					"message_id" => $ids,
					"subject" => $this->input->post('subject'),
					"message" => $_REQUEST['html_content'],
					"from_email" => $this->input->post('to_email'),
					"timestamp" => $now,
					"category" => "SENT",
					"created_by" => $user['uacc_uid'],
					"status" => $status,
					"relationship_id" => $relationship_id
					);
					
					$this->db->insert('sc_messages',$data);
					
					notify_set( array('status'=>'success', 'message'=>'Successfully sent an email.') );
					
					if(isset($module_id))
					{
						redirect( 'people/view/' . $module_id );
					}
					else
					{
						redirect('messages/index');	
					}
				}
				else
				{
					notify_set( array('status'=>'error', 'message'=>' Email Sending Failed.') );
					
					redirect('messages/index');	
				}
			}
			else
			{
			if($this->input->post('act', true) == "true"){

				  $this->load->library('email');
				  
				  $config['protocol']    = 'smtp';
				  
  		  		  $config['smtp_host']    = $_SESSION['user']['imap_address'];			  

				  $config['smtp_port']    = $_SESSION['user']['mail_server_port'];
				  $config['smtp_timeout'] = '7';
				  
				  $config['smtp_user']    = $_SESSION['user']['username'];
				  
				  $this->load->library('encrypt');
				    
				  $config['smtp_pass'] = $this->encrypt->decode($_SESSION['user']['password']);

				  $config['charset']    = 'utf-8';
				  $config['newline']    = "\r\n";
				  $config['mailtype'] = 'html'; // or html
				  $config['validation'] = TRUE; // bool whether to validate email or not      
		
				  $this->email->initialize($config);
		
				  $this->email->from($_SESSION['user']['uacc_email'], $_SESSION['user']['upro_first_name']." ".$_SESSION['user']['upro_last_name']);
				  $this->email->to($this->input->post('to_email')); 
				  
				  // check for bcc
				  if($this->input->post('bcc_email') != null)
				  	$this->email->bcc($this->input->post('bcc_email')); 
				  
				  // check for cc
				  if($this->input->post('cc_email') != null)
				  	$this->email->cc($this->input->post('cc_email')); 
		
				  $this->email->subject($this->input->post('subject'));
				  $this->email->message($_REQUEST['html_content']);  

				  if($this->email->send()){
 
					  // FUTURE? add message to database
					  	// set flash
					  	notify_set( array('status'=>'success', 'message'=>'Successfully sent an email.') );

					// redirect
						redirect( 'people/view/' . $module_id );

					}
					
					else{
					  	// set flash
					  	notify_set( array('status'=>'error', 'message'=>'There was an issue sending an email with your SMTP settings. Please re-connect to your SMTP server. If you are still having issues please contact us.') );

					// redirect
						redirect( 'users/settings' );
					}
				
			}
			else{
				// no email sent at this point
				// init
				$cont = new Person();
		
				//
				$cont->select();
				// find
				$cont->where('people_id', $module_id)->get();
				
				$CI =& get_instance();
				$CI->teal_global_vars->set_all_global_vars();
		
				$data = array();
				
				$data['person'] = $cont;
				
				$this->layout->view('/messages/send', $data);
			}
		}
		
	}
		
	public function view($mail_id){
		
		$data = Array();
			
		$Gmail = $_SESSION["gmail"][$mail_id];
				
		$data['Gmail'] = $Gmail;
		
		$data['index_id'] = $mail_id;
		
		if($Gmail['status'] == 'Company')
		{
			
			$relat_company = new Company();
			
			$relat_company->where('deleted', 0);
			$relat_company->where('company_id', $Gmail['relationship_id'])->get();
			
			$data['relat_rec'] = $relat_company;
		
		}
		else if($Gmail['status'] == 'Person')
		{
			
			$relat_person = new Person();
			
			$relat_person->where('deleted', 0);
			$relat_person->where('people_id', $Gmail['relationship_id'])->get();
			
			$data['relat_rec'] = $relat_person;
				
		}
		else if($Gmail['status'] == 'Deal')
		{
			
			$relat_deal = new Deal();
			
			$relat_deal->where('deleted', 0);
			$relat_deal->where('deal_id', $Gmail['relationship_id'])->get();
			
			$data['relat_rec'] = $relat_deal;
				
		}
	
		$this->layout->view('/messages/view', $data);
		
	}
	
	public function addrelationship($index_id,$relship_id,$module_type)
	{
		$data = array(
		"relationship_id"=>$relship_id,
		"status"=>$module_type
		);
		
		$this->db->select('mail_id,message_id,subject,message,from_name,from_email,timestamp,category,status,relationship_id');
		$this->db->from('sc_messages');
		$this->db->where('message_id',$_SESSION['gmail'][$index_id]['message_id']);
		$query = $this->db->get();
			
		$Gmail = $query->result();
		
		if(count($Gmail[0]) != 0)
		{
			$this->db->where("message_id",$_SESSION['gmail'][$index_id]['message_id']);
			if($this->db->update("sc_messages",$data))
			{
				$_SESSION['gmail'][$index_id]['status']=$module_type;
				$_SESSION['gmail'][$index_id]['relationship_id']=$relship_id;
				
				notify_set(array('status'=>'success', 'message'=>'  Mail successfully Associated to '.$module_type.'.'));
				redirect('/messages/view/'.$index_id);
			}
		}
		else
		{
			$data1 = array(
			"mail_id" => $this->uuid->v4(),
			"message_id" => $_SESSION['gmail'][$index_id]['message_id'],
			"subject" => $_SESSION['gmail'][$index_id]['subject'],
			"message" => $_SESSION['gmail'][$index_id]['message'],
			"from_email" => $_SESSION['gmail'][$index_id]['from_email'],
			"from_name" => $_SESSION['gmail'][$index_id]['from_name'],
			"timestamp" => $_SESSION['gmail'][$index_id]['timestamp'],
			"category" => $_SESSION['gmail'][$index_id]['category'],
			"created_by" => $_SESSION['gmail'][$index_id]['created_by'],
			"status" => $module_type,
			"relationship_id" => $relship_id
			);
						
			if($this->db->insert('sc_messages',$data1))
			{
				$_SESSION['gmail'][$index_id]['status']=$module_type;
				$_SESSION['gmail'][$index_id]['relationship_id']=$relship_id;
				
				notify_set(array('status'=>'success', 'message'=>'  Mail successfully Associated to '.$module_type.'.'));
				redirect('/messages/view/'.$index_id);
			}
			
		}
		
		notify_set(array('status'=>'error', 'message'=>' Failed to Associate Email.'));
		redirect('/messages/index');
	}
	public function archive_all($relship_id,$module_type)
	{
		
		$rel_count = 0;
		$post = $this->input->post(null, true);
		// check
		if( isset($post['ids']) && ! empty($post['ids']) )
		{
			// ids
			$ids = $post['ids'];
		}
		foreach($ids as $id)
		{
					
			//$msg_id = $_SESSION["gmail"][$id]['message_id'];
			$data = array(
			"mail_id" => $this->uuid->v4(),
			"message_id" => $_SESSION['gmail'][$id]['message_id'],
			"subject" => $_SESSION['gmail'][$id]['subject'],
			"message" => $_SESSION['gmail'][$id]['message'],
			"from_email" => $_SESSION['gmail'][$id]['from_email'],
			"from_name" => $_SESSION['gmail'][$id]['from_name'],
			"timestamp" => $_SESSION['gmail'][$id]['timestamp'],
			"category" => $_SESSION['gmail'][$id]['category'],
			"created_by" => $_SESSION['gmail'][$id]['created_by'],
			"status" => $module_type,
			"relationship_id" => $relship_id
			);
			$this->db->select('mail_id,message_id,subject,message,from_name,from_email,timestamp,category,status,relationship_id');
			$this->db->from('sc_messages');
			$this->db->where('message_id',$_SESSION['gmail'][$id]['message_id']);
			$query = $this->db->get();
			
			$Gmail = $query->result();
			
			if(count($Gmail[0]) != 0)
			{
				$data1 = array(
				"status" => $module_type,
				"relationship_id" => $relship_id
				);
				$rel_count++;
				$this->db->where('message_id',$Gmail[0]->message_id);
				$this->db->update('sc_messages',$data1);
				
				$_SESSION['gmail'][$id]['status']=$module_type;
				$_SESSION['gmail'][$id]['relationship_id']=$relship_id;
			}
			else if($this->db->insert('sc_messages',$data))
			{
				$rel_count++;
				$_SESSION['gmail'][$id]['status']=$module_type;
				$_SESSION['gmail'][$id]['relationship_id']=$relship_id;
				
			}				
		}
		if($rel_count > 0)
		{
			notify_set(array('status'=>'success', 'message'=>'  '.$rel_count.' Mail(s) successfully Associated.'));
			redirect('/messages/index');
		}
		else
		{
			notify_set(array('status'=>'error', 'message'=>'  Failed'));
			redirect('/messages/index');
		}
	}
		
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */