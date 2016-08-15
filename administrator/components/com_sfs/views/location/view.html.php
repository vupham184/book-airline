<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class SfsViewLocation extends JView
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
		$this->canDo 	= sfsHelper::getActions($this->item->id, 'location');
		
		JToolBarHelper::title($isNew ? JText::_('COM_SFS_MANAGER_LOCATION_NEW') : JText::_('COM_SFS_MANAGER_LOCATION_EDIT'), 'location');
		
		// Built the actions for new and existing records.
		if ($isNew) 
		{
			// For new records, check the create permission.
			if ($this->canDo->get('core.create')) 
			{
				JToolBarHelper::apply('location.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('location.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('location.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}
			
			JToolBarHelper::cancel('location.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own'))
			{
				// We can save the new record
				JToolBarHelper::apply('location.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('location.save', 'JTOOLBAR_SAVE');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($this->canDo->get('core.create')) 
				{
					JToolBarHelper::custom('location.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				}
			}
			if ($this->canDo->get('core.create')) 
			{
				JToolBarHelper::custom('location.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel('location.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	

}
