<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
class SfsViewPassengersimport extends JViewLegacy
{    
	protected $titleariline;
	protected $airlinetrains;
	protected $distanceHotel;
	protected $airline, $group_transport_company, $airport_group, $airport_of_airline;
	protected $taxiCompany;
    public function display($tpl = null)
    {
    	$app  = JFactory::getApplication();
		$this->user		   = JFactory::getUser();
		if( !$this->user->id ){
			$app->redirect( JRoute::_('index.php?option=com_sfsuser&view=login&Itemid=104',false) );
            return false;
		}
		
		$this->airline = SFactory::getAirline();
		$this->titleariline = $this->get('TitleAriline');
		$this->airlinetrains = $this->get('AirlineTrains');
		$this->taxiCompany = $this->get('TaxiCompany');

		if( ! SFSAccess::isAirline($this->user) ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;					
		} 
		
		if( SFSAccess::check($this->user, 'gh') ) {							
			if( (int)$airline->iatacode_id == 0 ) {
				$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=changeairline&Itemid=128',false) );
            	return false;	
			}	
		}
		//$model = $this->getModel('Passengersimport');
		//$this->rentalcars=$model->getRentalcar($this->airline->airport_id,$this->airline->iatacode_id);	
		$this->rentalcars=$this->get('Rentalcar');
		$this->distanceHotel = $this->get('DistanceHotel');
		$this->passengers = $this->get('Passengers');
		$this->services = $this->get('Services');
		$this->airport_of_airline = $this->get('airport_of_airline');
		$this->airport_group = $this->get('changeAirportGroup');
		$this->group_transport_company = $this->get("GroupTransportCompany");
		$this->airportBus = $this->get("AirportBus");
		
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        if(JRequest::getInt('reservation_id',0)){
        	$this->detail_reservation = $this->get("DetailReservation");
        	//print_r($this->detail_reservation);die;
        }
        parent::display($tpl); 
		
    }
    
    
    
}
