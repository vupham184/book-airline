<?php
defined('JPATH_BASE') or die;

jimport('joomla.database.table');

class JTableBus extends JTable
{	
	public function __construct(&$db)
	{
		parent::__construct('#__sfs_group_transportations', 'id', $db);		
	}
	
}
