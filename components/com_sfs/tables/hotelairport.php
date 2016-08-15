<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableHotelAirport extends JTable
{	
	var $id = null;
	var $hotel_id = null;
	var $airport_id = null;
	var $distance = null;
	var $distance_unit = null;
	var $normal_hours = null;
	var $rush_hours = null;
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_hotel_airports', 'id', $db);		
	}
	
	public function check()
	{
		if ( !$this->hotel_id ) {
			$this->setError(JText::_('Hotel id invalid'));
			return false;
		}		
		// check for valid name
		if ( !$this->airport_id ) {
			$this->setError(JText::_('Airport is invalid'));
			return false;
		}

		return true;
	}
	
}
