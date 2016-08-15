<?php
defined('_JEXEC') or die();

class SfsViewContacts extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	function display($tpl = null) 
	{				
		// Get data from the model
		$this->items 		= $this->get('Items');
		$this->pagination   = $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$this->airlines 	= $this->get('Airlines');
		$this->hotels    	= $this->get('Hotels');
		
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

	protected function addToolBar() 
	{
		$canDo = sfsHelper::getActions();
		
		JToolBarHelper::title('Users');
		
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('contact.edit','JTOOLBAR_EDIT');
		}
	}		
}
