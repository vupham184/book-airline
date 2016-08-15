<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.view');

class SfsViewTracepassenger extends JView
{
	protected $state;
	protected $params;
	protected $user;
	protected $item, $itemNex;
	protected $taxiCompany;
	function display($tpl = null) 
	{				
		$app	= JFactory::getApplication();
		$this->user 	= JFactory::getUser();   
		if($this->user->id==0){
      		$app->redirect( JURI::base(),false );
            return false;					
      		
      	}
		$this->state	=  $this->get('state');
		$this->params	= $this->state->get('params');
		$this->taxiCompany = $this->get('TaxiCompany');

      	
        if($this->getLayout() == 'default_individualpassengerpage' || $this->getLayout() == 'mealplanvoucher' || $this->getLayout() == 'additional_services')
        {
            $this->item  = $this->get('PassengerDetail');
            $model = $this->getModel();
            $this->itemNex = $model->getPassengersWith($this->item->voucher_id, $this->item->id);
            $this->airline = SFactory::getAirline();
            $model = JModel::getInstance('Passengersimport', 'SfsModel');
			$this->rentalcars = $model->getRentalcar ();
			$this->titleariline = $model->getTitleAriline();
			$this->airlinetrains = $model->getAirlineTrains();

			$this->airport_of_airline = $model->getairport_of_airline();
			$this->AirportGroup=$model->getchangeAirportGroup();
			$this->list_aircraft = $model->getSameAircraft($this->item->rebooked_fltno[0]->date_timestamp,$this->item->rebooked_fltno[0]->registration);
        }

		if( ! SFSAccess::isAirline($this->user) ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;					
		} 
		
		if( SFSAccess::check($this->user, 'gh') ) {
			$airline = SFactory::getAirline();
							
			if( (int)$airline->iatacode_id == 0 ) {
				$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=changeairline&Itemid=128',false) );
            	return false;	
			}	
			
		}
		
		$this->trace_passengers = $this->get('TracePassengers');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}			
				
		parent::display($tpl);		
	}
		
}
?>

