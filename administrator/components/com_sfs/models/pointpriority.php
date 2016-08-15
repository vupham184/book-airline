<?php
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

//class SfsModelRentalcar extends JModelLegacy{
class SfsModelPointpriority extends JModelAdmin{
protected $text_prefix = 'com_sfs';
	public function getTable($type = 'Pointpriority', $prefix = 'SfsTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_sfs.pointpriority', 'pointpriority', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_sfs.edit.pointpriority.data', array());

		if (empty($data)) {
			$data = $this->getItem();			
		}

		return $data;
	}
	
	public function save($data)
	{	
		if (parent::save($data)) {						
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_sfs&view=pointprioritys');
			//return true;
		}
		return false;
	}
}
