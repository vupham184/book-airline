<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableHotelTransport extends JTable
{	
	var $id = null;
	var $hotel_id = null;
	var $transport_available = null;
	var $transport_complementary = null;

	var $operating_hour = null;
	var $operating_opentime = null;
	var $operating_closetime = null;
	
	var $frequency_service = null;
	var $pickup_details = null;	
		
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_hotel_transports', 'id', $db);		
	}
	
	public function check()
	{
		if ( !$this->hotel_id ) {
			$this->setError(JText::_('Hotel id invalid'));
			return false;
		}

		return true;
	}
	
}
