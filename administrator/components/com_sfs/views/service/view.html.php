<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewService extends JView
{
	protected $form;
	protected $item;
	protected $state;

	
	public function display($tpl = null)
	{

		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
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
		JRequest::setVar('hidemainmenu', true);
	
		JToolBarHelper::title('Service Edit');	
		JToolBarHelper::apply('service.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('service.save', 'JTOOLBAR_SAVE');		
		JToolBarHelper::cancel('service.cancel', 'JTOOLBAR_CANCEL');
	}
}