<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Location View
 */
class SfsViewLocations extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$this->items 		= $this->get('Items');
		$this->pagination   = $this->get('Pagination');
		$this->state		= $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

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
		$canDo = sfsHelper::getActions();
		
		JToolBarHelper::title(JText::_('COM_SFS_MANAGER_LOCATIONS'));				
		JToolBarHelper::addNew('location.add','JTOOLBAR_NEW');		
		JToolBarHelper::editList('location.edit','JTOOLBAR_EDIT');
		
		JToolBarHelper::divider();
		JToolBarHelper::custom('locations.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('locations.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
			
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'locations.delete','JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('locations.trash','JTOOLBAR_TRASH');
			JToolBarHelper::divider();
		}

	}
	

}
