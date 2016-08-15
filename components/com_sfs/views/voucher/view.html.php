<?php
defined('_JEXEC') or die();

class SfsViewVoucher extends JViewLegacy
{
	protected $state;
	protected $params;
	protected $user, $DataVoucher, $card_airplusws;	
	
	function display($tpl = null) 
	{				
		$app	= JFactory::getApplication();
		
		$this->state	=  $this->get('state');
		$this->params	= $this->state->get('params');						
      	$this->user 	= JFactory::getUser();	
		
		$this->DataVoucher	=  $this->get('DataVoucher');	
      	
      	if( $this->getLayout()=='sample' && SFSAccess::isHotel($this->user) ) 
      	{
      		$this->prepareSampleVoucher();
      		if (count($errors = $this->get('Errors')))
			{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
			}					
			parent::display($tpl);
			return;	
		}       	
      	
		if( ! SFSAccess::isAirline($this->user) ) {			
			$app->redirect( JRoute::_('index.php?option=com_sfs&view=dashboard&Itemid='.JRequest::getInt('Itemid'),false) );
            return false;					
		} 
		
		$airline = SFactory::getAirline();
		
		if( SFSAccess::check($this->user, 'gh') ) {							
			if( (int)$airline->iatacode_id == 0 ) {
				$app->redirect( JRoute::_('index.php?option=com_sfs&view=airlineprofile&layout=changeairline&Itemid=128',false) );
            	return false;	
			}	
		}		
		
		$this->airline 		= $airline;
		$this->voucher 		= $this->get('Voucher');
		$this->hotel   		= $this->get('VoucherHotel');
		$this->vouchers 		= $this->get('Vouchers');
		$this->voucher_groups = $this->get('VoucherGroups');
		$this->listvoucher = $this->get('ListVoucherByResid');
		$this->passengers   = $this->get('Passengers');;
		$this->trace_passengers   = $this->get('TracePassengers');
		$this->card_airplusws = $this->get('CardAirplusws');
		$this->dataPassengerService = $this->get('DataPassengerService');		

		$this->dataForRental = $this->get('DataForRental');
		
		/*$this->passengers   = $this->voucher->getPassengers();
		$this->trace_passengers   = $this->voucher->getTracePassengers();
		$this->card_airplusws = $this->voucher->getCardAirplusws();*/
		
		$this->reservation 	= SReservation::getInstance($this->voucher->booking_id);
		$wsBooking = $this->reservation->ws_booking;
		if(!empty($wsBooking)) {
			$this->wsRoomTypes = $this->reservation->getWsRoomTypes();
			$this->wsBooking = Ws_Do_Book_Response::fromString($wsBooking);
			$tpl = 'ws';
		}
		
		$this->individualVouchers  = $this->get('IndividualVoucher');
		
		$this->formatPassengerNames = array();
		$this->formatTracePassengerNames = array();
		
		if( count($this->passengers) )
		{
			$passengers = $this->passengers;
			
			foreach($passengers as $passenger) {
				$room = $passenger->voucher_room_id;
				$name = $passenger->first_name." ".$passenger->last_name ;
				$this->formatPassengerNames[$room][] = trim($name);
			}
		}
		
		if( count($this->trace_passengers) )
		{
			$passengers = $this->trace_passengers;
				
			foreach($passengers as $passengerA) {
				foreach($passengerA as $passenger) {
					$room = $passenger->voucher_room_id;
					$name = $passenger->first_name." ".$passenger->last_name ;
					$this->formatTracePassengerNames[$room][] = trim($name);
				}
			}
		}
		
		$this->system_currency = $this->params->get('sfs_system_currency','EUR');
		
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
				
		parent::display($tpl);		
	}
	
	protected function prepareSampleVoucher()
	{
		$this->hotel = SFactory::getHotel();
	}
		
}
?>

