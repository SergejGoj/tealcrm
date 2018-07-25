<?php
/**
 * Company Class
 *
 * Transforms companies table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Record_Views extends DataMapper
{
	// table
	public $table = 'record_views';

	// Default to ordering by name
	public $default_order_by = array('view_time_stamp');
}

/* End of file company.php */
/* Location: ./application/models/company.php */