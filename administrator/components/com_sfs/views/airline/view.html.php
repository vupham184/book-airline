<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


class SfsViewAirline extends JView
{	
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form			= $this->get('Form');
		$this->item			= $this->get('Item');
		$this->state		= $this->get('State');
		$this->reservations	= $this->get('Reservations');
		$this->admins       = $this->get('Admins');
        $this->stationUsers = $this->get('stationUsers');
		$this->taxiDetails	= $this->get('TaxiDetails');
		$this->taxiCompanies	= $this->get('TaxiCompanies');
		
						
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}	

		if( $this->getLayout() != 'contacts' )
		{				
			$this->addToolbar();	
		}
		
		if ( $this->getLayout() =='systememails' ) {
			$this->contacts = $this->get('Contacts');		
		}
				
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);			
		JToolBarHelper::title('Airline Manager: Edit', 'user-edit.png');

		$layout = $this->getLayout();
		$toolbar = JToolBar::getInstance('toolbar');
	
		if($layout=='edit') {
			//$toolbar->appendButton('Link', 'taxi', 'Taxi Details', 'index.php?option=com_sfs&view=taxi&layout=edit&airline_id='.$this->item->id);
			//JToolBarHelper::divider();
			$toolbar->appendButton('Link', 'systememail', 'System Emails', 'index.php?option=com_sfs&view=airline&layout=systememails&id='.$this->item->id);
			JToolBarHelper::divider();
			JToolBarHelper::apply('airline.apply');
			JToolBarHelper::save('airline.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('airline.cancel', 'JTOOLBAR_CLOSE');		
		}
		
		if($layout=='systememails') {
			JToolBarHelper::title('Airline : '.$this->item->name.' - Receive System emails');
			$toolbar->appendButton('Link', 'back', 'Back', 'index.php?option=com_sfs&view=airline&layout=edit&id='.$this->item->id);				
		}
		
		
		
	}
}