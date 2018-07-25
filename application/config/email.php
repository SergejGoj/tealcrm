<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
// defaults
/*
$config['protocol']  = 'sendmail';
$config['mailpath']  = '/usr/sbin/sendmail';	
$config['crlf']      = '\r\n';
$config['newline']   = '\r\n';		
$config['charset']   = 'utf-8';
$config['wordwrap']  = TRUE;
$config['mailtype']  = 'html';	
$config['useragent'] = sprintf('%s Mailer 1.0',SITE_NAME);
*/

// mandrill SMTP
$config['useragent'] = sprintf('%s TealCRM');
$config['protocol']  = 'smtp';
$config['smtp_host'] = 'smtp.mandrillapp.com';
$config['smtp_port'] = 587;
$config['smtp_user'] = 'derek@eligeo.com';
$config['smtp_pass'] = '7bvSYg6RJPD5VzADPupIBA';
$config['mailtype']  = 'html';
$config['crlf']      = '\r\n';
$config['newline']   = '\r\n';		
$config['charset']   = 'utf-8';
$config['wordwrap']  = TRUE;

/* End of file email.php */
/* Location: ./application/config/email.php */
