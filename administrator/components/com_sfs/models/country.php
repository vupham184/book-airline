<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Country Model
 */
class SfsModelCountry extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_SFS_COUNTRY';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	$record	A record object.
	 * @return	boolean	True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canDelete($record)
	{
		$user = JFactory::getUser();
		return $user->authorise('core.delete', 'com_sfs.country.'.(int) $record->id);
	}
	
	/**
	 * Method to test whether a record can be changed stated.
	 *
	 * @param	object	$record	A record object.
	 * @return	boolean	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since	1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing country.
		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', 'com_sfs.country.'.(int) $record->id);
		}
		// Default to component settings if neither country nor category known.
		else {
			return parent::canEditState($record);
		}
	}
	
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Country', $prefix = 'SfsTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_sfs.country', 'country', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)){
			return false;
		}
		return $form;
	}
	
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return array Script files
	 */
	public function getScript() 
	{
		$script   = array();
		$script[] = 'administrator/components/com_sfs/models/forms/js/global.js';
		$script[] = 'administrator/components/com_sfs/models/forms/js/country.js';
		
		return $script;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sfs.edit.country.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}
	
	/**
	 * Method to perform batch operations on a category or a set of countries.
	 *
	 * @param	array	An array of commands to perform.
	 * @param	array	An array of category ids.
	 * @return	boolean	Returns true on success, false on failure.
	 * @since	1.6
	 */
	function batch($commands, $pks)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks)) {
			$this->setError(JText::_('COM_SFS_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		if (!empty($commands['assetgroup_id'])) {
			if (!$this->batchAccess($commands['assetgroup_id'], $pks)) {
				return false;
			}
			$done = true;
		}

		if (!$done) {
			$this->setError(JText::_('JGLOBAL_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		return true;
	}

	/**
	 * Batch access level changes for a group of rows.
	 *
	 * @param	int		The new value matching an Asset Group ID.
	 * @param	array	An array of row IDs.
	 * @return	booelan	True if successful, false otherwise and internal error is set.
	 * @since	1.6
	 */
	protected function batchAccess($value, $pks)
	{
		// Check that user has edit permission for every country being changed
		// Note that the entire batch operation fails if any country lacks edit permission
		$user	= JFactory::getUser();
		
		foreach ($pks as $pk) {
			if (!$user->authorise('core.edit', 'com_sfs.country.'.$pk)) {
				// Error since user cannot edit this country
				$this->setError(JText::_('COM_SFS_BATCH_ACCESS_CANNOT_EDIT'));
				return false;
			}
		}
		
		$table = $this->getTable();
		foreach ($pks as $pk) {
			$table->reset();
			$table->load($pk);
			$table->access = (int) $value;
			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}
}
