<?php
/**
 * Settings Class
 *
 * Transforms settings table into an object.
 *
 * @license		MIT License
 * @category	Models
 * @author		SecretCRM Team
 * @link		http://www.overzealous.com/dmz/
 */
Class Setting extends DataMapper
{

    var $updated_field = 'date_modified';
    
	// table
	public $table = 'settings';	
}

/* End of file deal.php */
/* Location: ./application/models/deal.php */