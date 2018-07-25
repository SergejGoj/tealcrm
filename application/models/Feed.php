<?php
/**
 * Contact Class
 *
 * Transforms notes table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Feed extends DataMapper
{
	var $created_field = 'date_entered';
	// table
	public $table = 'feeds';	
}

/* End of file note.php */
/* Location: ./application/models/note.php */