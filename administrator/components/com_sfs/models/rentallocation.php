<?php
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class SfsModelRentallocation extends JModelAdmin{
protected $text_prefix = 'com_sfs';
	public function getTable($type = 'Rentallocation', $prefix = 'SfsTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getCodeAirports(){		
		$db = $this->getDbo();			
		$query = 'SELECT  * FROM #__sfs_iatacodes';						
		$query .= ' WHERE type=2';
		$db->setQuery($query);
		$result = $db->loadObjectList();
		if( $error = $db->getErrorMsg() ){
				throw new Exception($error);
		}
		return $result;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sfs.rentallocation', 'rentallocation', array('control' => 'jform', 'load_data' => $loadData));
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
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sfs.edit.rentallocation.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}

		return $data;
	}

	
	public function save($data)
	{		
		
		if (parent::save($data)) {						
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_sfs&view=rentallocations');
			//return true;
		}
		return false;
	}
}
