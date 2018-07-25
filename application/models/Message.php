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
Class Message extends DataMapper
{
	// table
	public $table = 'messages';

	// Default to ordering by name
	public $default_order_by = array('timestamp');
}

/* End of file company.php */
/* Location: ./application/models/company.php */