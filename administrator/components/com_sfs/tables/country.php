<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');


class SfsTableCountry extends JTable
{

	function __construct(&$db) 
	{
		parent::__construct('#__sfs_country', 'id', $db);
	}
	
	public function bind($array, $ignore = '') 
	{
		if (isset($array['params']) && is_array($array['params'])) 
		{
			// Convert the params field to a string.
			$parameter = new JRegistry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}
				
		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$rules = new JRules($array['rules']);
			$this->setRules($rules);
		}
		
		return parent::bind($array, $ignore);
	}


	// public function load($pk = null, $reset = true) 
	// {
	// 	if (parent::load($pk, $reset)) 
	// 	{
	// 		// Convert the params field to a registry.
	// 		$params = new JRegistry;
	// 		$params->loadJSON($this->params);
	// 		$this->params = $params;
	// 		return true;
	// 	}
	// 	else
	// 	{
	// 		return false;
	// 	}
	// }
	

	function check()
	{
		
		// Validate country information
		if (trim($this->name) == '') {
			$this->setError(JText::_('COM_SFS_ERROR_NAME_NOT_FILL'));
			return false;
		}

		if (trim($this->code) == '') {
			$this->setError(JText::_('COM_SFS_COUNTRY_ERROR_CODE_NOT_FILL'));
			return false;
		}

		//Check for existing name
		$query = 'SELECT id'
		. ' FROM #__sfs_country '
		. ' WHERE `name` = ' . $this->_db->Quote($this->name)
		. ' AND `id` != '. (int) $this->id;
		
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError( JText::_('COM_SFS_ERROR_NAME_ALREALDY_EXIST'));
			return false;
		}
		
		//Check for existing code
		$query = 'SELECT id'
		. ' FROM #__sfs_country '
		. ' WHERE `code` = ' . $this->_db->Quote($this->code)
		. ' AND `id` != '. (int) $this->id;
		
		$this->_db->setQuery($query);
		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError( JText::_('COM_SFS_COUNTRY_ERROR_CODE_ALREALDY_EXIST'));
			return false;
		}
	
		
		return true;
	}
}
