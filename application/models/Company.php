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
Class Company extends DataMapper
{
	var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
    
	// table
	public $table = 'companies';

	// Default to ordering by name
	public $default_order_by = array('company_name');
}

/* End of file company.php */
/* Location: ./application/models/company.php */