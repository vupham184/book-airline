<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewCodecanyon extends JView
{
	protected $state;

	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
	
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$layout = $this->getLayout();
		JRequest::setVar('hidemainmenu', true);				
		JToolBarHelper::title('Codecanyon : '.$this->item->name);
		
		$toolbar = JToolBar::getInstance('toolbar');		
					
		JToolBarHelper::divider();
		JToolBarHelper::apply('codecanyon.apply');			
		JToolBarHelper::cancel('codecanyon.cancel', 'JTOOLBAR_CLOSE');		
			
	}
}