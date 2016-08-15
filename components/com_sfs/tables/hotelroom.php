<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.database.table');

class JTableHotelRoom extends JTable
{	
	var $id = null;
	var $hotel_id = null;
	var $total = null;
	var $standard = null;
	var $standard_size = null;
	var $standard_size_unit = null;	
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_hotel_room_details', 'id', $db);		
	}
		
}
