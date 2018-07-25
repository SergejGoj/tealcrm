<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//-------------------------------------------------------------------------
// [cache]
$config['cache_enable']   = 'on';
$config['cache_storage']  = 'file';// apc/memcached/file/dummy

// [date]
$config['date_display_short'] = 'm/d/Y';

// [date]
$config['date_display_time'] = 'm/d/Y H:i';

// [perpage]
$config['row_per_page'] = '20';

# [pager]										
$config['pager'] = array('num_links'=>5,'full_tag_open'=>'<ul class="pagination">','full_tag_close'=>'</ul>',
	                     'first_link'=>false,'last_link'=>false, 'next_link'=>'Next','prev_link'=>'Previous',
	                     'cur_tag_open'=>'<li class="active"><a>','cur_tag_close'=>'</a></li>','next_tag_open'=>'<li>',
	                     'next_tag_close'=>'</li>','prev_tag_open'=>'<li>','prev_tag_close'=>'</li>','num_tag_open'=>'<li>',
	                     'num_tag_close'=>'</li>'); 

/* End of file app.php */
/* Location: ./application/config/app.php */