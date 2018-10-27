<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// -----------------------------------------------------------------------
// defaults

$config['protocol']  = 'sendmail';
$config['mailpath']  = '/usr/sbin/sendmail';	
$config['crlf']      = '\r\n';
$config['newline']   = '\r\n';		
$config['charset']   = 'utf-8';
$config['wordwrap']  = TRUE;
$config['mailtype']  = 'html';	
$config['useragent'] = sprintf('%s Mailer 1.0',SITE_NAME);


/* End of file email.php */
/* Location: ./application/config/email.php */
