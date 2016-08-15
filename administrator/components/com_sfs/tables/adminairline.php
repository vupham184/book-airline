<?php
defined('_JEXEC') or die();

jimport('joomla.database.table');

class SfsTableAdminairline extends JTable
{

	function __construct(&$db)
	{
		parent::__construct('#__sfs_airline_details', 'id', $db);
	}
	

	function check()
	{
		if ( (int)$this->airport_id <= 0) {
			$this->setError('Airport Code is required');
			return false;
		}
		if ( (int)$this->iatacode_id <= 0 ) {
			$this->setError('Iatacode is missing');
			return false;
		}
		if (trim($this->address) == '') {
			$this->setError('Please enter address');
			return false;
		}
		if (trim($this->city) == '') {
			$this->setError('Please enter city');
			return false;
		}
		if (trim($this->zipcode) == '') {
			$this->setError('Please enter zipcode');
			return false;
		}
		if (trim($this->telephone) == '') {
			$this->setError('Please enter telephone');
			return false;
		}
		if ( (int)$this->country_id <= 0) {
			$this->setError('Country is required');
			return false;
		}
		if ( (int)$this->billing_id <= 0) {
			$this->setError('Please fill billing details');
			return false;
		}
		
		return true;
	}
}
