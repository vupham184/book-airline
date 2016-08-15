<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.database.table');

class SfsTableState extends JTable
{	
	public function __construct(& $db)
	{
		parent::__construct('#__sfs_states', 'id', $db);
	}
}
