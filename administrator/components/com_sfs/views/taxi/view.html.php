<?php
defined('_JEXEC') or die();

class SfsViewTaxi extends JViewLegacy
{
	 protected $airport;

	public function display($tpl = null)
	{
		// get the Data		
		$taxi  = $this->get('TaxiDetails');

		// Assign the Data
		$this->airline 			= $this->get('Airline');
		$this->item  			= $this->get('TaxiDetails');
		$this->taxiCompanies 	= $this->get('TaxiCompanies');
		$this->airport 			= $this->get('AirPorts');
		
		if( $this->getLayout() == 'rate' )
		{
			$this->taxi 	    = $this->get('TaxiTransport');
			$this->hotels 		= $this->get('HotelRates');
		}
		
		if( $this->getLayout() == 'company' )
		{
			$this->taxi 	= $this->get('Taxi');		
		}
		
		if( $this->getLayout() == 'edittaxi' )
		{
			$this->taxi 	    = $this->get('TaxiTransport');
			if($this->taxi){
				$this->users 	= $this->taxi->users;					
			}
			$this->airlines		= $this->get('Airlines');					
		}
		if( $this->getLayout() == 'edittaxiairport' )
		{
			$this->taxi 	    = $this->get('TaxiTransport');				
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

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JRequest::setVar('hidemainmenu', true);

		JToolBarHelper::title('Edit Taxi');
		
		// We can save the new record
		if( $this->getLayout() == 'edit' ){
			JToolBarHelper::apply('taxi.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('taxi.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('taxi.cancel', 'JTOOLBAR_CLOSE');	
		}
		
		if( $this->getLayout() == 'edittaxi' )
		{
			JToolBarHelper::apply('taxi.applyTaxi', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('taxi.saveTaxi', 'JTOOLBAR_SAVE');
			JToolBarHelper::cancel('taxi.cancel', 'JTOOLBAR_CLOSE');	
		}
		// if( $this->getLayout() == 'edittaxiairport'  )
		// {
		// 	JToolBarHelper::apply('taxi.applyTaxi', 'JTOOLBAR_APPLY');
		// 	JToolBarHelper::save('taxi.saveTaxi', 'JTOOLBAR_SAVE');
		// 	JToolBarHelper::cancel('taxi.cancel', 'JTOOLBAR_CLOSE');	
		// }
		
	}


}


