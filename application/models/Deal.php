<?php
/**
 * People Class
 *
 * Transforms companies table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Deal extends DataMapper
{
		var $created_field = 'date_entered';
    var $updated_field = 'date_modified';
	// table
	public $table = 'deals';
}

/* End of file deal.php */
/* Location: ./application/models/deal.php */