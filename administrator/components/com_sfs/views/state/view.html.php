<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * State view
 */
class SfsViewState extends JView
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

		JToolBarHelper::title($isNew ? JText::_('COM_SFS_MANAGER_STATE_NEW') : JText::_('COM_SFS_MANAGER_STATE_EDIT'), 'state');
		// Buit the actions for new and existing records.
		if ($isNew)
		{
			JToolBarHelper::apply('state.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('state.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('state.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::cancel('state.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			// We can save the new record
			JToolBarHelper::apply('state.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('state.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::custom('state.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			JToolBarHelper::custom('state.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			JToolBarHelper::cancel('state.cancel', 'JTOOLBAR_CLOSE');
		}
	}


}
