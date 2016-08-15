<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class SfsViewTrainlists extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state		= $this->get('State');			
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		parent::display($tpl);
	}

	
	protected function addToolbar()
	{				
		JToolBarHelper::title(JText::_('Train Station Manager'));
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->appendButton('Popup', 'new', 'New', 'index.php?option=com_sfs&view=trainlist&layout=company&tmpl=component','450','350','','','');
		/*$toolbar->deleteList('', 'index.php?option=com_sfs&task=trainlist.deleteAirlineTrain', 'string caption');*/
		JToolBarHelper::deleteList('', 'trainlist.deleteAirlineTrain');
	}
}
