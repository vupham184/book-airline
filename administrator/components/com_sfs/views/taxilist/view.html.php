<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class SfsViewTaxilist extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	
	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal') {
			$this->addToolbar();
		}

		parent::display($tpl);
	}

	
	protected function addToolbar()
	{				
		JToolBarHelper::title(JText::_('Taxi Comapny Manager'));
		$toolbar = JToolBar::getInstance('toolbar');	
		$toolbar->appendButton('Popup', 'new', 'New', 'index.php?option=com_sfs&view=taxi&layout=company&tmpl=component','750','550','','','');		
	}
}
