<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');


class SfsViewStates extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	
	/**
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Extra layouts
		$layout =& JRequest::getVar('layout', null, 'GET', 'string');
		if( is_readable( dirname(__FILE__).DS.'tmpl'.DS.$layout.'.php' ) ){
			$this->setLayout($layout);
		}
	
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

		require_once JPATH_COMPONENT .'/models/fields/countryselectlist.php';
		
		// Display the template
		parent::display($tpl);
	
	}

	protected function addToolBar() 
	{		
		JToolBarHelper::title(JText::_('States Manager'));		
		JToolBarHelper::addNew('state.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('state.edit','JTOOLBAR_EDIT');
	}
	
}
