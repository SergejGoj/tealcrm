<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Custom Constants
|--------------------------------------------------------------------------
|
| These modes are used throughout the application, updatable once, by code
|
*/
define('VALUE_ONLY'           , 1);
define('KEY_VALUE'            , 2);

// godaddy subdomain doc root, daddy meets grandpa :)
define('_DOCUMENT_ROOT'       , (isset($_SERVER['SUBDOMAIN_DOCUMENT_ROOT']) ? str_replace('/var/chroot','',$_SERVER['SUBDOMAIN_DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT']));
// base path
if(PHP_OS == 'WINNT'):
	$_base_path = str_replace(array('index.php',str_replace('/','\\',_DOCUMENT_ROOT),'\\'),array('','','/'),FCPATH);
else:
	$_base_path = str_replace(array('index.php',_DOCUMENT_ROOT),'',FCPATH);
endif;
// set
define('_BASE_PATH'           , (empty($_base_path)) ? '/' : $_base_path);
// uploads and other paths
define('UPLOAD_PATH'          , 'uploads/');
define('UPLOAD_URL'           , UPLOAD_PATH);

define('TMP_PATH'             , 'tmp/');
define('THUMB_PATH'           , 'thumbs/');
define('VENDOR_PATH'          , APPPATH . 'third_party/');
// site name
define('SITE_NAME'            , 'TealCRM');
// site/domain
define('SITE_URL'             , 'tealcrm.com');
// cookie path
define('SITE_COOKIE_PATH'     , ((ENVIRONMENT == 'production') ? '/' : _BASE_PATH));
// admin path, for admin, content-developer
define('ADMIN_PATH'           , 'admincrm');
// company path, for recruiter, recruiter-user
define('ACCOUNT_PATH'         , 'companies');
// api path
define('API_PATH'             , 'api');
// version
define('APP_VERSION'          , '1.0.0');













/* End of file constants.php */
/* Location: ./application/config/constants.php */