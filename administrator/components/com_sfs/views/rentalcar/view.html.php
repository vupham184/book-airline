<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class SfsViewRentalcar extends JView
{
	protected $form;
	protected $item;
	protected $state;

	
	public function display($tpl = null)
	{

		// Initialiase variables.
		
		$this->code_airports=$this->get('CodeAirports');
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
	
		JToolBarHelper::title('Rental Edit');	
		JToolBarHelper::apply('rentalcar.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('rentalcar.save', 'JTOOLBAR_SAVE');		
		JToolBarHelper::cancel('rentalcar.cancel', 'JTOOLBAR_CANCEL');
	}
}