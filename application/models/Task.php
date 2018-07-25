<?php
/**
 * Task Class
 *
 * Transforms companies table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Task extends DataMapper
{
		var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
    
	// table
	public $table = 'tasks';

    // Default to ordering by id
	//public $default_order_by = array('id');
}

/* End of file task.php */
/* Location: ./application/models/task.php */