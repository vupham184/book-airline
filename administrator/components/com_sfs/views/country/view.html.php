<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Country view
 */
class SfsViewCountry extends JView
{
	/**
	 * @return void
	 */
	public function display($tpl = null) 
	{
		// get the Data
		$form   = $this->get('Form');
		$item   = $this->get('Item');
		$script = $this->get('Script');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Assign the Data
		$this->form  	= $form;
		$this->item  	= $item;
		$this->script   = $script;

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		JRequest::setVar('hidemainmenu', true);
		$user 			= JFactory::getUser();
		$userId 		= $user->id;
		$isNew 			= $this->item->id == 0;
		$this->canDo 	= sfsHelper::getActions($this->item->id, 'country');
		
		JToolBarHelper::title($isNew ? JText::_('COM_SFS_MANAGER_COUNTRY_NEW') : JText::_('COM_SFS_MANAGER_COUNTRY_EDIT'), 'country');
		
		// Built the actions for new and existing records.
		if ($isNew) 
		{
			// For new records, check the create permission.
			if ($this->canDo->get('core.create')) 
			{
				JToolBarHelper::apply('country.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('country.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('country.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}
			
			JToolBarHelper::cancel('country.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
			{
				// We can save the new record
				JToolBarHelper::apply('country.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('country.save', 'JTOOLBAR_SAVE');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($this->canDo->get('core.create')) 
				{
					JToolBarHelper::custom('country.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				}
			}
			if ($this->canDo->get('core.create')) 
			{
				JToolBarHelper::custom('country.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel('country.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew 		= $this->item->id == 0;
		$document 	= JFactory::getDocument();
		
		#Set page title
		$document->setTitle($isNew ? JText::_('COM_SFS_COUNTRY_CREATING') : JText::_('COM_SFS_COUNTRY_EDITING'));
		
		#Add JS script
		if( !empty($this->script) )
		{
			foreach( $this->script as $script )
			{
				$document->addScript(JURI::root() . $script);
			}
		}
		
		#JText for JS script
		JText::script('COM_SFS_ERROR_UNACCEPTABLE');
	}
}
