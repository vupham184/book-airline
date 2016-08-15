<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

// import JSON library
jimport( 'joomla.registry.format.json' );


/**
 * Country Controller
 */
class SfsControllerChainaff extends JControllerForm
{
	protected $text_prefix = 'COM_SFS_CHAIN_AFFILIATION';
	
	/**
	 * Method to check if you can add a new record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param	array	An array of input data.
	 * @param	string	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$edit    =  JFactory::getUser()->authorise('core.edit', $this->option);
		$editOwn = JFactory::getUser()->authorise('core.edit.own', $this->option);
		
		if( $edit || $editOwn ){
			return true;
		}
		return false;
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
		$model	= $this->getModel('Chainaff');
		$vars	= JRequest::getVar('batch', array(), 'post', 'array');
		$cid	= JRequest::getVar('cid', array(), 'post', 'array');

		// Preset the redirect
		$this->setRedirect('index.php?option=com_sfs&view=chainaffs');

		// Attempt to run the batch operation.
		if ($model->batch($vars, $cid)) {
			$this->setMessage(JText::_('JGLOBAL_BATCH_SUCCESS'));
			return true;
		}else{
			$this->setMessage(JText::_(JText::sprintf('COM_SFS_ERROR_BATCH_FAILED', $model->getError())));
			return false;
		}
	}
}
