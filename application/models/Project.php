<?php
/**
 * Project Class
 *
 * Transforms projects table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		TealCRM
 * @link		
 */
Class Project extends DataMapper
{
		var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
	// table
	public $table = 'projects';

	// Default to ordering by name
	public $default_order_by = array('project_name');
}

/* End of file company.php */
/* Location: ./application/models/company.php */