<?php
defined('_JEXEC') or die();

jimport('joomla.database.table');

class JTableAirline extends JTable
{
	
	var $id = null;
	var $iatacode_id = null;
	var $company_name = null;
	var $affiliation_code = null;
	var $airline_alliance = null;
	var $airport_id = null;
	var $time_zone = null;
	
	var $address = null;
	var $address2 = null;
	var $city = null;
	var $state_id = null;
	var $zipcode = null;
	var $country_id = null;
	var $telephone = null;
	
	var $billing_id = null;
	
	var $created_by = null;
	var $created_date = null;
	var $modified_by = null;
	var $modified_date = null;
	var $block = null;
	var $approved = null;
	var $params = null;
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_airline_details', 'id', $db);		
	}

	public function check()
	{
		if( (int) $this->id == 0 ) {
			if( (int) $this->iatacode_id && (int) $this->airport_id ) {
				$db = JFactory::getDbo();
				$query = 'SELECT COUNT(*) FROM #__sfs_airline_details WHERE iatacode_id='.$this->iatacode_id.' AND airport_id='.(int)$this->airport_id;
				$db->setQuery($query);
				if( $db->loadResult() ) {
					$this->setError('This Airline code combined with this station is already active, please contact <a href="mailto:helpdeks@sfs-web.com">helpdeks@sfs-web.com</a> to obtain more info.');
					return false;
				}
			} else {
				$this->setError('Data invalid');
				return false;
			}
		}
		
		return true;
	}		
	
}
