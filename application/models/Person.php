<?php
/**
 * Contact Class
 *
 * Transforms companies table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Person extends DataMapper
{
		var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
    
	// table
	public $table = 'people';

	// Default to ordering by name
	public $default_order_by = array('first_name');
}

/* End of file contact.php */
/* Location: ./application/models/contact.php */