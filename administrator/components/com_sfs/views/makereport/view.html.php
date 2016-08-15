<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewMakereport extends JView
{
	protected $items;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('List');
		$this->state		= $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{	
		JToolBarHelper::title('Booked, Blocked rooms');	
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->appendButton('Link', 'back', 'Back', 'index.php?option=com_sfs&view=reservations');
		JToolBarHelper::save('makereport.export', 'Export to Excel');		
	}
}
