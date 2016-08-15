<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class SfsTablemanagetemplatemobile extends JTable
{
	
	function __construct(&$db) 
	{
		parent::__construct('#__sfs_managetemplate_mobile', 'id', $db);
	}
	
}
