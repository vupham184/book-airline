<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class SfsTableRentallocation extends JTable
{
	
	function __construct(&$db) 
	{
		parent::__construct('#__sfs_rental_car_location', 'id', $db);
	}
	
}
