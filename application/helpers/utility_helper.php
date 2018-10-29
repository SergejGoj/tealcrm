<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------
/**
 * is debug check by IP
 *
 * @param void
 * @return bool
 */
function is_debug_ip(){
	// ci instance
  	$CI =& get_instance();	
	// ip
	$debug_ips = array('116.202.215.127','116.203.169.2');
	$current_ip = $CI->input->ip_address();
	// check
	if( in_array( $current_ip, $debug_ips ) ){
		return true;
	}
	// return
	return false;
} 

/**
 * recursive dump
 *
 * @param mixed $var
 * @param bool $return
 * @return string
 */
function pr($var, $return=false){	
	// output
	$output = sprintf( '<pre>%s</pre>', print_r($var, 1) );
	// return 
	if($return) return $output;
	// print
	echo $output;
}

/**
 * debug log
 * 
 * @param string $content
 * @param string $name
 * @param string $ext
 */    
function debug_log($content, $name='debug', $ext='log'){
	// convert
	if(is_array($content) || is_object($content)){
		$content = pr($content, true);
	}
	// content
	$content .= "\n\n";
	// difine time
	if(!defined('LOG_TIMESTAMP')) define('LOG_TIMESTAMP', gmdate('Ymd_His'));
	// write
	return write_file(sprintf('tmp/logs/debug/%s_%s.%s', $name, LOG_TIMESTAMP, $ext), $content, 'a+');	
}

/**
 * logger
 * 
 * @param string $content
 * @param string $group
 * @param bool $log
 */  
function logger($content, $group='debug', $log=false){	
	// web 
	if(defined('CRON') || get_instance()->input->is_cli_request() || $log == TRUE) {
		// in file
		return debug_log($content, $group);
	}
	// print on browser
	printf('<br> [%s]: %s', $group, pr($content,true));			
}

/**
 * unique number helper
 * 
 * @param string $type
 * @return string
 */ 
function get_unique_id($type='id'){
	switch($type){
		case 'username':
		case 'password':
			return substr(microtime(),2,8);
		break;
		case 'id':
		default:
			return md5(uniqid(rand(), true));
		break;
	}	
}

/**
 * unique serial helper
 * 
 * @param none
 * @return string
 */ 
function get_serial(){  
  	return substr(microtime(),2,8);	
}

/**
 * session get
 * 
 * @param string $name
 * @return mixed
 */ 
function get_session($name){	
	// return 
	return get_instance()->session->userdata($name);
}

/**
 * session get
 * 
 * @param string $name
 * @return mixed
 */
// session set
function set_session($name, $value=''){	
	// set
	get_instance()->session->set_userdata($name, $value);	
}

/**
 * session get
 * 
 * @param string $name
 * @return mixed
 */
// session set once
function set_session_once($name, $value=''){	
	// unset
	unset_session( is_array($name) ? array_keys($name) : $name );
	// set
	get_instance()->session->set_userdata($name, $value);	
}

/**
 * session get
 * 
 * @param string $name
 * @return mixed
 */
// session unset
function unset_session($name){		
	// unset
	get_instance()->session->unset_userdata($name);	
}

/**
 * session get
 * 
 * @param string $name
 * @return mixed
 */
// flashdata get
function get_flashdata($name){	
	// return 
	return get_instance()->session->flashdata($name);
}

/**
 * session get
 * 
 * @param string $name
 * @return mixed
 */
// flashdata set
function set_flashdata($name, $value=''){	
	// set
	get_instance()->session->set_flashdata($name, $value);	
}

/**
 * session get
 * 
 * @param string $name
 * @return mixed
 */
// uri segment helper
function uri_segment($index,$default=''){
	$CI =& get_instance();
	return $CI->uri->segment($index, $default);
}

/**
 * uri segment
 * 
 * @param string $name
 * @return mixed
 */
// uri rsegment helper
function uri_rsegment($index,$default=''){
	$CI =& get_instance();
	return $CI->uri->rsegment($index, $default);
}

/**
 * cache load
 * 
 * @param string $name
 * @return mixed
 */
function cache_load(){
	// if cache enabled
	if(config_item('cache_enable') == 'on'){
		// ci instance
	  	$CI =& get_instance();		
		// storage
		$cache_storage = config_item('cache_storage');		
		// load cache driver
		$CI->load->driver('cache', array('adapter' => $cache_storage, 'backup' => 'file'), 'cache'); 
		// supported
		if ($CI->cache->is_supported($cache_storage)){
			// do stuff 
			log_message('debug', $cache_storage.' cache loaded');			
		}
	}	
}

/**
 * cache get
 * 
 * @param string $item
 * @return mixed
 */
function cache_get($item){		
	// check
	if(config_item('cache_enable') == 'on'){		
		// ci instance
	  	$CI =& get_instance();		
		// return		
		if(is_object($CI->cache)){
			return $CI->cache->get($item);		
		}
	}	
	// return 
	return false;	
}

/**
 * cache set
 * 
 * @param string $item
 * @param mixed $value
 * @param int $expire
 * @return mixed
 */
// cache set
function cache_set($item, $value, $expire=3600){
	// check
	if(config_item('cache_enable') == 'on'){
		// ci instance
	  	$CI =& get_instance();	
		// return
		if(is_object($CI->cache)){
			// expire
			$expire = time2second($expire);							
			// return		
			return $CI->cache->save($item, $value, $expire);
		}
	}	
	// return 
	return false;	
}

/**
 * cache delete
 * 
 * @param string $item
 * @return bool
 */
function cache_delete($item=''){		
	// check
	if(config_item('cache_enable') == 'on'){	
		// ci instance
	  	$CI =& get_instance();			
		// return		
		if($item){
			// check
			if($CI->cache->get_metadata($item) !== FALSE){
				return $CI->cache->delete($item);
			}
		}else{
			// all
			return $CI->cache->clean();
		}	
	}	
	// return 
	return false;	
}

/**
 * get cache info
 * 
 * @param none
 * @return array
 */
// cache info
function cache_info(){	
	// check
	if(config_item('cache_enable') == 'on'){	
		// ci instance	  		
		return get_instance()->cache->cache_info();		
	}	
	// return 
	return array();
}


/**
 * get time2second
 * 
 * @param string $time
 * @return string $time
 */
function time2second($time){
	// expire in term
	if(preg_match('/\d+ (HOUR|DAY|WEEK|MONTH|YEAR)/', $time)){
		// list
		list($unit, $period) = explode(' ',$time);
		// periods
		$periods = array('HOUR'=> 1, 'DAY'=> 24, 'WEEK'=> 168, 'MONTH'=> 720, 'YEAR'=> 8760);
		// set 
		$time = (60*60) * $unit * $periods[$period];
	}
	// return
	return $time;
}

/**
 * time2day
 * 
 * @param string $time
 * @return string $time
 */
function time2day($time){
	// expire in term
	if(preg_match('/\d+ (DAY|WEEK|MONTH|YEAR)/', $time)){
		// list
		list($unit, $period) = explode(' ',$time);
		// periods
		$periods = array('DAY'=> 1, 'WEEK'=> 7, 'MONTH'=> 30, 'YEAR'=> 365);
		// set 
		$time = $unit * $periods[$period];
	}
	// return
	return $time;
}

/**
 * get notify key
 * 
 * @param array $notify
 * @param string $group
 * @return void
 */
function notify_key( $group=null ){
	// group
	if( ! $group ){
		$key = 'SESS_NOTIFY';
	}else{
		$key = 'SESS_NOTIFY_' . strtoupper($group);
	}

	return $key;
}

/**
 * set notify
 * 
 * @param array $notify
 * @param string $group
 * @return void
 */
function notify_set( $notify, $group=null ){	
	// key 
	$key = notify_key( $group ) ;
	// set
	set_flashdata( $key, $notify );
}

/**
 * get notify
 * 
 * @param string $group
 * @return array $notify
 */
function notify_get( $group=null ){
	// key 
	$key = notify_key($group) ;

	// notify
	$notify = get_flashdata( $key );
	
	// check
	if( isset($notify['status']) )
		return $notify;
	
	// return
	return false;
}

/**
 * time2day
 * 
 * @param string $time
 * @return string $time
 */
function display_notify( $group=null ){	
	// check
	if( $notify = notify_get( $group ) ){
		// ci instance
		$CI =& get_instance();	
		// classed
		$classes = array('error'=>'danger','success'=>'success','info'=>'info','warning'=>'warning');
		// labels
		$labels  = array('error'=>'Oh snap!','success'=>'Well done!','info'=>'Heads up!','warning'=>'Warning!');
		// latout
		$layout = $CI->layout->getTheme() . '/layouts/alert';
		$data = array('class'=>$classes[$notify['status']], 'label'=>$labels[$notify['status']], 'message'=>$notify['message']);
		// message
		$message = $CI->parser->parse($layout, $data, TRUE);
		// parse		   
		echo $message;		   
	}
}

/**
 * time2day
 * 
 * @param string $time
 * @return string $time
 */
function get_dbprefix(){
	// ci instance
	$CI =& get_instance();	

	// check
	if( empty($CI->db->dbprefix) ){
		// Load config settings
		$CI->config->load('datamapper', TRUE, TRUE);
		// get
		$datamapper = $CI->config->item('datamapper');
		// set
		$CI->db->dbprefix = $datamapper['prefix'];
	}

	return $CI->db->dbprefix;		
}

/**
 * check environment
 * 
 * @param string $env
 * @return boolean
 */
function environment_is($env){
	return (ENVIRONMENT == $env);
}

/**
 * check release
 * 
 * @param string $rel
 * @return boolean
 */
function release_is($rel){
	return (RELEASE == $rel);
}


/**
 * check active menu item
 * 
 * @param dynamic
 * @return bool
 */
function is_active_item(){
	// init
	$active = false;
	// uri
	$uri_string = get_instance()->uri->uri_string();
	// args
	$args = func_get_args();	
	// check
	if( !empty($args) ){
		// loop
		foreach( $args as $arg ){
			if( preg_match('|^' . $arg . '|i', $uri_string) ){
				$active = true;
			}
		}
	}	
	// return
	return $active;
}

/**
 * logs a last view for given record ID and module
 * 
 * @param string UID, string module_name
 * @return bool
 */
function update_last_viewed($UID, $module_id, $description){
	// ci instance

	$CI =& get_instance();	
	
	$id = $CI->uuid->v4();
	
	$data = array(
			   'user_id' => $_SESSION['user']['id'],
			   'record_view_id' => $id,
			   'description' => $description,
			   'module_id' => $module_id,
			   'record_id' => $UID,
               'view_time_stamp' => gmdate('Y-m-d H:i:s')
            );
	
	$CI->db->insert('record_views', $data); 

	return true;	
	
}


/**
 * returns an array with drop down values (key, value) for drop down lists from the $_SESSION['drop_down_options']
 * Typically used for search list boxes
 * 
 * @param string UID, string module_name
 * @return bool
 */
function lookupDropDownValues($drop_down_name) {
		
		$results_array = Array(); // stores results

        foreach ($_SESSION['drop_down_options'] as $ddname => $ddnames) {
                if (in_array($drop_down_name, $ddnames)) {
                        $results_array[] = $ddname;
                }
        }
        
        return $results_array;
}


/* End of file utility_helper.php */
/* Location: ./application/helpers/utility_helper.php */