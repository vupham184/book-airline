<?php
defined('_JEXEC') or die();

jimport('joomla.database.table');

class SfsTableGrouptransport extends JTable
{

	public function __construct(&$db) 
	{
		parent::__construct('#__sfs_group_transportations', 'id', $db);
	}
	
}
