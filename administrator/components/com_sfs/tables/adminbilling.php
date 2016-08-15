<?php
defined('_JEXEC') or die();

jimport('joomla.database.table');

class SfsTableAdminbilling extends JTable
{

	function __construct(&$db)
	{
		parent::__construct('#__sfs_billing_details', 'id', $db);
	}
	

	function check()
	{
		if ( (int)$this->id <= 0) {
			$this->setError('Billing ID is required');
			return false;
		}

		if (trim($this->name) == '') {
			$this->setError('Please enter company name');
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
		if (trim($this->tva_number) == '') {
			$this->setError('Please enter tva number');
			return false;
		}
		if ( (int)$this->country_id <= 0) {
			$this->setError('Country is required');
			return false;
		}
		
		
		return true;
	}
}
