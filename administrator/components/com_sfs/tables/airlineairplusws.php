<?php
defined('_JEXEC') or die();

jimport('joomla.database.table');

class SfsTableAirlineAirplusws extends JTable
{

	function __construct(&$db)
	{
		parent::__construct('#__sfs_airline_airplusws', 'id', $db);
	}
	

	function check()
	{
		return true;
	}
}
