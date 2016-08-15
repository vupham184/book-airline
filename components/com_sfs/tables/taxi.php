<?php
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableTaxi extends JTable
{	
	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_taxi_companies', 'id', $db);		
	}
		
}
