<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableHotel extends JTable
{	
	var $id  = null;
	var $name  = null;
	var $alias  = null;
	var $star  = null;
	var $chain_id  = null;
	var $billing_id  = null;
	var $web_address  = null;
	var $telephone  = null;
	var $fax  = null;
	var $address  = null;
	var $address1  = null;
	var $address2  = null;
	var $zipcode  = null;
	var $city  = null;
	var $state_id  = null;
	var $country_id  = null;
	var $location_id  = null;
	var $time_zone  = null;
	var $geo_location_latitude  = null;
	var $geo_location_longitude  = null;
	var $block  = null;
	var $ordering  = null;
	var $created_by  = null;
	var $created_date  = null;
	var $modified_by  = null;
	var $modified_date  = null;
	var $step_completed  = null;
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_hotel', 'id', $db);		
	}
	/**
	 * Overloaded check function.
	 *
	 * @return	boolean	True if the object is ok
	 */
	public function check()
	{
		if (trim($this->name) == '') {
			$this->setError(JText::_('Hotel name is invalid'));
			return false;
		}

		if( (int) $this->created_by <= 0 ) {
			$this->setError( 'The hotel owner is invalid' );
			return false;
		}

		return true;
	}
	
}
