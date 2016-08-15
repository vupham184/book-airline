<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class SfsTableState extends JTable
{

	function __construct(&$db) 
	{
		parent::__construct('#__sfs_states', 'id', $db);
	}

	function check()
	{

		// Validate some informations
		if (trim($this->name) == '') {
			$this->setError(JText::_('COM_SFS_ERROR_NAME_NOT_FILL'));
			return false;
		}
		
		if ( (int)$this->country_id <= 0 ) {
			$this->setError(JText::_('COM_SFS_STATE_ERROR_SELECT_COUNTRY'));
			return false;
		}

		//Check for existing name
		$query = 'SELECT id'
		. ' FROM #__sfs_states '
		. ' WHERE `name` = ' . $this->_db->Quote($this->name)
		. ' AND `country_id` = '. (int)$this->country_id
		. ' AND `id` != '. (int)$this->id;
		
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError( JText::_('COM_SFS_STATE_ERROR_NAME_ALREALDY_EXIST'));
			return false;
		}
		
		return true;
	}
}
