<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableHotelMealplan extends JTable
{	
	var $id = null;
	var $hotel_id = null;
	
	var $course_1 = null;
	var $course_2 = null;
	var $course_3 = null;
	var $tax = null;
	
	var $stop_selling_time = null;
	var $service_hour = null;
	var $service_opentime = null;
	var $service_closetime = null;
	var $service_outside= null;
	
	//For breakfast
	var $bf_service_hour = null;
	var $bf_standard_price = null;
	var $bf_layover_price = null;
	var $bf_tax = null;	
	var $bf_opentime = null;
	var $bf_closetime = null;
	var $bf_outside	 = null;
	
	//For Lunch
	var $lunch_service_hour = null;
	var $lunch_standard_price = null;	
	var $lunch_tax = null;	
	var $lunch_opentime = null;
	var $lunch_closetime = null;
	
	var $available_days	 = null;
	var $lunch_available_days	 = null;
		
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_hotel_mealplans', 'id', $db);		
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
