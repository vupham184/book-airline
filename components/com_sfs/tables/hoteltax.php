<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableHotelTax extends JTable
{	
	var $id = null;
	var $hotel_id = null;
	var $currency_id = null;
	var $percent_total_taxes = null;
	var $os_fee_per_night = null;
	var $os_fee_per_stay = null;
	var $percent_release_policy = null;
		
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_hotel_taxes', 'id', $db);		
	}
	
	public function check()
	{
		if ( empty( $this->hotel_id) ) {
			$this->setError(JText::_('Hotel id invalid'));
			return false;
		}
		if( empty($this->currency_id) )
		{
			$currency = SfsHelper::getCurrency();
			$currency = trim($currency);
			$db = JFactory::getDbo();
			$query = 'SELECT id FROM #__sfs_currency WHERE code='.$db->quote($currency);
			$db->setQuery($query);
			
			$this->currency_id = (int) $db->loadResult();	
			if( empty($this->currency_id) ) {
				$this->setError('Currency invalid');
				return false;	
			}		
		}
		return true;
	}
	
}
