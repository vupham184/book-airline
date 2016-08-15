<?php
defined('_JEXEC') or die();

jimport('joomla.database.table');

class SfsTableTaxicompany extends JTable
{

	public function __construct(&$db) 
	{
		parent::__construct('#__sfs_taxi_companies', 'id', $db);
	}
	
}
