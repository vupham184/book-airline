<?php
defined('_JEXEC') or die();

// Taxi View for airline

class SfsViewTaxi extends JViewLegacy
{
	protected $state;
	protected $params;
	protected $user;
	
	function display($tpl = null) 
	{				
		$app	= JFactory::getApplication();
		
		$this->state	=  $this->get('state');
		$this->params	= $this->state->get('params');						
      	$this->user 	= JFactory::getUser();		
      	
		if( ! SFSAccess::isAirline($this->user) ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;					
		} 
		
		$airline = SFactory::getAirline();
		
		if( ! $airline->allowTaxiVoucher() )
		{
			$msg = 'The taxi service is not available for your account. Contact SFS Administrator for more details.';
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false), $msg );
            return false;			
		}
		
		$this->airline 			 = $airline;		
		$this->item   			 = $this->get('TaxiDetails');
		$this->taxiCompanies 	 = $this->get('TaxiCompanies');
		
		if( $this->getLayout() == 'voucher' )
		{
			$transportation = $this->state->get('taxivoucher.transportation', 0);
			
			if((int)$transportation==1)
			{
				$this->taxiVoucher 	= $this->get('TaxiTransportationVoucher');
				$this->hotel 	    = SHotel::getInstance($this->taxiVoucher->hotel_id);
				$this->passengers   = $this->taxiVoucher->passengers;
			} else {
				$this->taxiVoucher 	= $this->get('TaxiVoucher');
				$this->hotelVoucher = $this->get('HotelVoucher');							
				if($this->hotelVoucher->association_id==0){
					$this->hotel 	= SHotel::getInstance($this->taxiVoucher->hotel_id);	
				} else {
					$association = SFactory::getAssociation($this->hotelVoucher->association_id);					
					$this->hotel =  new SHotel($this->taxiVoucher->hotel_id,$association->db);					
				}											
				$this->passengers   = $this->hotelVoucher->getPassengers();	
			}	
			
			if($this->taxiVoucher->taxi_id)
			{
				$this->taxiCompany = new STaxi($this->taxiVoucher->taxi_id);
				//print_r($this->taxiCompany);
			}
					
		} else {
			if( empty($this->item) )
			{
				$this->setLayout('edit');
			}
			
			if( $this->getLayout() == 'rate' )
			{
				$this->taxi 	= $this->get('Taxi');
				$this->hotels 	= $this->get('HotelRates');
			}
		}
		
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

