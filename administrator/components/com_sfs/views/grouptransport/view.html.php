<?php
defined('_JEXEC') or die();

class SfsViewGrouptransport extends JViewLegacy
{

	public function display($tpl = null)
	{	
		$this->state		  = $this->get('State');
		
		$this->iataAirlines       		= $this->get('IataAirlines');			
		$this->groupTransport     		= $this->get('GroupTransport');
		$this->groupTransportType 		= $this->get('GroupTransportType');
		$this->groupTransportTypeFixed 	= $this->get('GroupTransportTypeFixed');
		$this->users			  		= $this->get('Users');
		$this->hotels		  			= $this->get('HotelRates');
		$this->iataAirports       		= $this->get('IataAirports');
		$this->groupTransportFixed 		= $this->get('GrouptransportFixed');
		$this->listAirportTo 			= $this->get('ListAirportTo');
		$this->listAirport 				= $this->get('ListAirport');
		
		
		if( $this->getLayout()=='edit'){
			$this->rateEdit		  = $this->get('RateEdit');			
		}

		if( $this->getLayout()=='rates'){
			$this->hotels		  = $this->get('HotelRates');	
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$this->addToolBar();

		// Display the template
		parent::display($tpl);
	}

	
	protected function addToolBar()
	{
		JRequest::setVar('hidemainmenu', true);

		JToolBarHelper::title('Edit Transport');
		
		// We can save the new record
		JToolBarHelper::apply('grouptransport.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('grouptransport.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::cancel('grouptransport.cancel', 'JTOOLBAR_CLOSE');
		
	}


}


