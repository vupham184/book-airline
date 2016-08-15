<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class SfsViewContact extends JView
{
	/**
	 * @return void
	 */
	public function display($tpl = null) 
	{		
		$item   = $this->get('Item');		

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}		
		$this->item  	= $item;
		
		// Set the toolbar
		$this->addToolBar();
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		$user 	= JFactory::getUser();
		$userId = $user->id;
		$isNew 	= $this->item->id == 0;
		
		JToolBarHelper::title($isNew ? JText::_('COM_SFS_MANAGER_CONTACT_NEW') : JText::_('COM_SFS_MANAGER_CONTACT_EDIT'), 'contact');
		
		// We can save the new record		
		JToolBarHelper::save('contact.save', 'JTOOLBAR_SAVE');

		JToolBarHelper::cancel('contact.cancel', 'JTOOLBAR_CLOSE');
		
	}
	

}
