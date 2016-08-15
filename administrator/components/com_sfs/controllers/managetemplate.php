<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Exchangerate Controller
 */
class SfsControllerManagetemplate extends JControllerForm
{
	protected $text_prefix = 'COM_SFS_CURRENCY';
	
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param	array	An array of input data.
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowAdd($data = array())
	{			
		$this->saveMobile();

		// Initialise variables.
		$user		= JFactory::getUser();
		$country_id	= JArrayHelper::getValue($data, 'country_id', JRequest::getInt('filter_country_id'), 'int');
		$allow		= null;

		if ($country_id) {
			// If the country has been passed in the data or URL check it.
			$allow	= $user->authorise('core.create', 'com_sfs.country.'.$country_id);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}else {
			return $allow;
		}
		
	}
	
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		
		// Initialise variables.
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		
		/*
		// Check general edit permission first.
		if ($user->authorise('core.edit', 'com_sfs.managetemplate.'.$recordId)) {
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', 'com_sfs.managetemplate.'.$recordId)) {
			// Now test the owner is the user.
			$ownerId	= (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId) {
				// Need to do a lookup from the model.
				$record		= $this->getModel()->getItem($recordId);

				if (empty($record)) {
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId) {
				return true;
			}
		}
		*/
		//lchung call
		$this->saveMobile();
		
		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}
	
	/**
	 * Method to run batch opterations.
	 *
	 * @return	void
	 */
	public function batch()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Managetemplate');
		$vars	= JRequest::getVar('batch', array(), 'post', 'array');
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');

		// Preset the redirect
		$this->setRedirect('index.php?option=com_sfs&view=managetemplates');

		// Attempt to run the batch operation.
		if ($model->batch($vars, $cid)) {
			$this->setMessage(JText::_('JGLOBAL_BATCH_SUCCESS'));
			return true;
		}else{
			$this->setMessage(JText::_(JText::sprintf('COM_SFS_ERROR_BATCH_FAILED', $model->getError())));
			return false;
		}
	}

	public function saveMobile(){
		$jform  = JRequest::getVar('jform', array(), 'post', 'array');
		$db = JFactory::getDbo();
		
		$listObj = new stdClass();

		$listObj->logo_header_mobile 		= $jform['logo_header_mobile'];
		$listObj->logo_voucher_mobile 		= $jform['logo_voucher_mobile'];
		$listObj->logo_creditcard_mobile 	= $jform['logo_creditcard_mobile'];
		$listObj->mobile_color_MB 			= $jform['mobile_color_MB'];
		$listObj->mobile_color_MT 			= $jform['mobile_color_MT'];
		$listObj->mobile_color_MVB 			= $jform['mobile_color_MVB'];
		$listObj->mobile_color_MVT 			= $jform['mobile_color_MVT'];
		$listObj->mobile_color_MBB 			= $jform['mobile_color_MBB'];

		if(!empty($jform)){
			$query = 'SELECT b.code'
					. ' FROM  #__sfs_airline_details AS a'
					. ' INNER JOIN #__sfs_iatacodes AS b ON b.id = a.iatacode_id'
					. ' WHERE a.id=' . $jform['name_airline'];

			$db->setQuery($query);
			$result = $db->loadObject();

			$getFile = 0;
			$folderMobile = JPATH_SITE.'/tmp/mobile/templates/';
			$urlFile 	= JPATH_SITE.'/tmp/mobile/templates/'.$jform['name_airline'].'.log';
			$nameFile 	= json_encode($listObj);

			//code writer file to language
			if(!is_dir($folderMobile)){
				mkdir($folderMobile);
			}
			
			file_put_contents($urlFile,$nameFile);
			$fp = @fopen($urlFile, "r");
			if ($fp) {
				$getFile = 1;
			}						

			if($getFile == 1){
				$this->setRedirect('index.php?option=com_sfs&view=managetemplates');
				
				return $this;				
			}
		}
	}
}
