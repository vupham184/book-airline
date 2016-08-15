<?php
defined('_JEXEC') or die;

class SfsViewGrouptransportlist extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->airport 		= $this->get('ListAirport');
		$this->FilterAirport 		= $this->get('FilterAirport');
				
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
		JToolBarHelper::title(JText::_('Group Transport Manager'));
		$toolbar = JToolBar::getInstance('toolbar');	
		$toolbar->appendButton('Link', 'new', 'New', 'index.php?option=com_sfs&view=grouptransport&layout=edit');		
	}
}
