<?php
/**
 * Meeting Class
 *
 * Transforms companies table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Meeting extends DataMapper
{
		var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
	// table
	public $table = 'meetings';
}

/* End of file meeting.php */
/* Location: ./application/models/meeting.php */