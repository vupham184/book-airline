<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class SfsTablePointpriority extends JTable
{
	
	function __construct(&$db) 
	{
		parent::__construct('#__sfs_point_priority', 'id', $db);
	}
	
}
