<?php
/**
 * Notes Class
 *
 * Transforms notes table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Note extends DataMapper
{
		var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
	// table
	public $table = 'notes';	
}

/* End of file note.php */
/* Location: ./application/models/note.php */