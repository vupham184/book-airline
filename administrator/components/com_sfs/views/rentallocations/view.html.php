<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewRentallocations extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		// Set the toolbar
		$this->addToolBar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{			
		JToolBarHelper::title(JText::_('Rental Car Location Manager'));
		$toolbar = JToolBar::getInstance('toolbar');	
		JToolBarHelper::addNew('rentallocation.add','JTOOLBAR_NEW');
		//$toolbar->appendButton('Popup', 'new', 'New', 'index.php?option=com_sfs&view=rentalcar');		
	}
}
