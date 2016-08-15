<?php
// No direct access
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableBilling extends JTable
{	
	var $id = null;	
	var $name = null;
	var $address = null;
	var $address1 = null;
	var $address2 = null;
	var $city = null;
	var $state_id = null;
	var $zipcode = null;
	var $country_id = null;
	var $tva_number = null;
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_billing_details', 'id', $db);		
	}
	
}
