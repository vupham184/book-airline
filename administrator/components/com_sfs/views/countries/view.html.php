<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Countries View
 */
class SfsViewCountries extends JView
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
		JToolBarHelper::title(JText::_('COM_SFS_MANAGER_COUNTRIES'));
		JToolBarHelper::addNew('country.add','JTOOLBAR_NEW');

		JToolBarHelper::editList('country.edit','JTOOLBAR_EDIT');

		if ($this->state->get('filter.state') == -2) {
			JToolBarHelper::deleteList('', 'countries.delete','JTOOLBAR_EMPTY_TRASH');
		} else  {
			JToolBarHelper::trash('countries.trash','JTOOLBAR_TRASH');
		}

	}
	

}
