<?php
defined('_JEXEC') or die();

class SfsViewMatch extends JViewLegacy
{
	protected $state;
	protected $params;
	protected $user;
	protected $flights_seats = null;
	protected $reservations = null;
	
	function display($tpl = null) 
	{				
		$app	= JFactory::getApplication();
		
		$this->state	=  $this->get('state');
		$this->params	= $this->state->get('params');						
      	$this->user = JFactory::getUser();		
      	
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
		
		if( $this->getLayout() == 'default' ) {
			
			$this->todayDate  		  = $this->get('TodayDate');
			$this->night		 	  = $this->get('NightDate');			
			//echo $this->todayDate.' - '.$this->night;
			$this->nextNight		  = $this->get('NextNight');
			$this->nextNightUrl		  = 'index.php?option=com_sfs&view=match&nightdate='.$this->nextNight.'&Itemid='.JRequest::getInt('Itemid');
			
			$this->prevNight		  = $this->get('PrevNight');
			if($this->prevNight)
			{
				$this->prevNightUrl		  = 'index.php?option=com_sfs&view=match&nightdate='.$this->prevNight.'&Itemid='.JRequest::getInt('Itemid');	
			}
						
			$this->reservations	  = $this->get('Reservations');
			$this->flights_seats  = $this->get('FlightsSeats');
			
			$voucherIdArr = array();
			///$voucherId			  = JRequest::getInt('voucher_id');
			if ( JRequest::getVar('voucher_id') != '' )
			$voucherIdArr = explode(",", JRequest::getVar('voucher_id') );
			$this->transportCompany  = $this->get('TransportCompany');
			if( !empty( $voucherIdArr ) )
			{
				foreach ( $voucherIdArr as $vk => $voucherId ){
					$this->voucher[]    = SVoucher::getInstance($voucherId,'id');
				}
				$this->voucher_groups = $this->get('VoucherGroups');
				if( count( $this->voucher ) > 1 ){
					$this->terminals  	= $this->get('Terminals');										
				}								
			}
		}
		
		if( $this->getLayout() == 'vouchers' ) {
			$this->night		 	= $this->get('NightDate');	
			$this->hotel		  	= $this->get('Hotel');			
			$this->reservations	  	= $this->get('Reservations');	
			$this->vouchers  		= $this->get('Vouchers');
		}
		if( $this->getLayout() == 'cancelvoucher' ) {
			$this->voucher_id = JRequest::getInt('id',0);
			$this->individual_voucher_id = JRequest::getInt('individual_voucher',0);
			$this->blockcode  = JRequest::getVar('blockcode');
		}	
		if( $this->getLayout() == 'sendform' ) {
			$this->voucher = $this->get('Voucher');			
		}	
		
		if( $this->getLayout() == 'singlevoucher' ) {
			$reservation_id = JRequest::getInt('reservation_id');
			if($reservation_id)
			{
                if($reservation_id == -1){
                    $session = JFactory::getSession();
                    $this->reservation = unserialize($session->get("reservation_temp"));
                }else{
                    $this->reservation = SReservation::getInstance($reservation_id);
                }

				if(!empty($this->reservation->ws_room))
				{
					$this->wsRoomTypes = $this->reservation->getWsRoomTypes();
					$this->wsPreBook = $this->reservation->getWsPreBooking();
					$tpl = 'ws';
				}
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

