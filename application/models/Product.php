<?php
/**
 * Template Class
 *
 * Transforms companies table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link
 */
Class Product extends DataMapper
{
		var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
	// table
	public $table = 'products';
}

/* End of file templates.php */
/* Location: ./application/models/templates.php */