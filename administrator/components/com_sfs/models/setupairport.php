<?php

// No direct access 
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class SfsModelSetupairport extends JModelAdmin
{

	protected $text_prefix = 'com_sfs';


	protected function canDelete($record)
	{
		return true;
	}


	protected function canEditState($record)
	{
		return true;
	}

	protected function prepareTable(&$table)
	{
		
	}

	public function getTable($type = 'Setupairport', $prefix = 'SfsTable', $config = array())
	{
		return array();//JTable::getInstance($type, $prefix, $config);
	}

	public function getItem($pk = null)
	{
		$item = array();// parent::getItem($pk);
		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sfs.setupairport', 'setupairport', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		return array();
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sfs.setup.setupairport.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}

		return $data;
	}

	public function save($data)
	{
		print_r( $data );die;
		if (parent::save($data)) {			
			return true;
		}

		return false;
	}

	protected function getReorderConditions($table)
	{
	}
	
	
}