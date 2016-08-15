<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewServices extends JView
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
		JToolBarHelper::title(JText::_('Services Manager'));
		$toolbar = JToolBar::getInstance('toolbar');	
		JToolBarHelper::addNew('service.add','JTOOLBAR_NEW');			
	}
}
