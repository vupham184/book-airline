<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controllers/sfscontroller.php';

class SfsControllerMatch extends SfsController
{		
	public function __construct($config = array())
	{
		parent::__construct($config);								
	}	
		
	public function match() 
	{//print_r($_POST);
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise some variables
		$app		= JFactory::getApplication();	
		$user		= JFactory::getUser();
			
		//make sure that only the airline group can book the hotel.
		if( ! SFSAccess::isAirline($user) ) {
			JError::raiseError(403, JText::_('Sorry you can not access to this page'));
		}
		
		$model = $this->getModel('Match','SfsModel');	
			
		$result = $model->match();
		
		$nightdate = JRequest::getVar('nightdate');
		$url = 'index.php?option=com_sfs&view=match';
		
		//lchung
		$s_rooms = JRequest::getInt('total_single_rooms');
		$sd_rooms = JRequest::getInt('total_double_rooms');
		$t_rooms = JRequest::getInt('total_triple_rooms');
		$q_rooms = JRequest::getInt('total_quad_rooms');
		
		//lay tong so phong theo khach san de kiem tra truong hop. khi chon 1 passenger in a D room vi trong khach san nay cho con phong trong la sdroom chang han
		//reservationid
		$reservationId 	= JRequest::getInt('reservationid');
		$flightData 	= JRequest::getVar('flight', array(), 'post', 'array');
		$s_number_room = JRequest::getInt('s_number_room' . $reservationId);
		$sd_number_room = JRequest::getInt('sd_number_room' . $reservationId);
		$t_number_room = JRequest::getInt('t_number_room' . $reservationId);
		$q_number_room = JRequest::getInt('q_number_room' . $reservationId);
		$seat_count = 0;
		foreach ( $flightData as $key => $value) 
		{
			$seat_count += count($value);
		}
		if ( $s_number_room > 0 && $seat_count == 1 && $s_rooms == 0 ) {
			$s_rooms = 1;
		}
		elseif ( $sd_number_room > 0 && $seat_count == 1 && $sd_rooms == 0 ) {
			$sd_rooms = 1;
		}
		elseif ( $t_number_room > 0 && $seat_count == 1 && $t_rooms == 0 ) {
			$t_rooms = 1;
		}
		elseif ( $q_number_room > 0 && $seat_count == 1 && $q_rooms == 0 ) {
			$q_rooms = 1;
		}
		$str_url = "&s_rooms=$s_rooms&sd_rooms=$sd_rooms&t_rooms=$t_rooms&q_rooms=$q_rooms";
		
		if($result) {
			$msg = $model->get('successMsg');
						
			///$url .= '&voucher_id='.$result;
			$url .= $result;
			if($nightdate){
				$url .= '&nightdate='.$nightdate;	
			}
			$url .= '&Itemid='.JRequest::getInt('Itemid') . $str_url . '&clcss=none';	
								
			$url = JRoute::_($url, false);
			$this->setRedirect($url, $msg);
			return true;		
		} else {
			echo $msg = (string)$model->getError();	
			die;
			if($nightdate){
				$url .= '&nightdate='.$nightdate;	
			}
			$url .= '&Itemid='.JRequest::getInt('Itemid');	
								
			$url = JRoute::_($url, false);
			
			$this->setRedirect( $url , $msg);
			return false;			
		} 				
	}	
	
	/**
	 * Method to resend the voucher to passenger 
	 *
	 */
	public function sendVoucher()
	{		
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$user = JFactory::getUser();

		// Make sure that is airline group
		if( ! SFSAccess::isAirline($user) ) {
			return false;	
		}
		
		$db 		= JFactory::getDbo();		
		$airline 	= SFactory::getAirline();
				
		// Get the data from POST		
		$passengerEmail = JRequest::getVar('email');
		$vouchercode 	= JRequest::getVar('vouchercode');	
		$blockCode		= JRequest::getVar('blockcode');
		$voucherid 		= JRequest::getInt('voucherid');
		$individualVoucher	= JRequest::getInt('individual_voucher');
		
		$voucher 		= SVoucher::getInstance($voucherid,'id');
		
		// Send the voucher		
		$sent = SEmail::guestVoucher( $passengerEmail , $voucher, $individualVoucher);
		
		if ( !$sent ) 
		{
			$msg = 'Email or Voucher invalid';	
			$url = 'index.php?option=com_sfs&view=match&layout=sendform&voucherid='.$voucherid.'&blockcode='.$blockCode.'&tmpl=component';
			if($individualVoucher){
				$url .= '&individual_voucher='.$individualVoucher;
			}
			$this->setRedirect(JRoute::_($url, false),$msg);
			return;		
		}
				
		$msg  = JText::sprintf('COM_SFS_MATCH_PASSENGER_SEND_MAIL_SUSCCESS',$vouchercode,$passengerEmail);
		$url = 'index.php?option=com_sfs&view=match&layout=sendform&voucherid='.$voucherid.'&blockcode='.$blockCode.'&tmpl=component';
		if($individualVoucher){
			$url .= '&individual_voucher='.$individualVoucher;
		}
		
		$session = JFactory::getSession();
		$session->set('vemailsuccess',1);
		
		$this->setRedirect(JRoute::_($url, false));
		return true;		
	}
	
	/**
	 * Method to resend the voucher to passenger 
	 *
	 */
	public function sendVoucherJSON()
	{		
		$user = JFactory::getUser();

		// Make sure that is airline group
		if( ! SFSAccess::isAirline($user) ) {
			return false;	
		}
		
		$db 		= JFactory::getDbo();		
		$airline 	= SFactory::getAirline();
				
		// Get the data from POST		
		$passengerEmail = JRequest::getVar('email');
		$vouchercode 	= JRequest::getVar('vouchercode');	
		$blockCode		= JRequest::getVar('blockcode');
		$voucherid 		= JRequest::getInt('voucherid');
		$individualVoucher	= JRequest::getInt('individual_voucher');
		
		$voucher 		= SVoucher::getInstance($voucherid,'id');
		
		// Send the voucher		
		$sent = SEmail::guestVoucher( $passengerEmail , $voucher, $individualVoucher);
		$code = 0;
		if ( !$sent ) 
		{
			$code = 1;
			$msg = 'Email or Voucher invalid';	
		}
		else 
		{
			$msg  = JText::sprintf('COM_SFS_MATCH_PASSENGER_SEND_MAIL_SUSCCESS',$vouchercode,$passengerEmail);
		}
		
		echo json_encode(array(
			'message' => $msg,
			'code' => $code
		));
		exit(0);
	}
	
	/**
	 * Method to allow the airline print a voucher.	  
	 */	
	public function printvoucher()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		if( ! SFSAccess::isAirline(JFactory::getUser())) {
			return false;
		}
		
		$id = JRequest::getInt('id');
		
		if($id) {			
			$airline = SFactory::getAirline();
			$db = JFactory::getDbo();
			$query = 'SELECT a.*,b.airline_id FROM #__sfs_voucher_codes AS a';
			$query .=' INNER JOIN #__sfs_reservations AS b ON b.id=a.booking_id';
			$query .=' WHERE a.id='.$id.' AND b.airline_id='.$airline->id;
			
			$db->setQuery($query);
			$result = $db->loadObject();
			
			if (isset($result)) {
				$query = 'UPDATE #__sfs_voucher_codes SET status=1 WHERE id='.$id;
				$db->setQuery($query);
				$db->query();
				$this->setRedirect(JRoute::_('index.php?option=com_sfs&view=match&layout=vouchers&bookingid='.$result->booking_id.'&Itemid='.JRequest::getInt('Itemid'),false));				
			}
						
		}				
	}
	
	/**
	 * Method to allow the airline cancel a voucher.	  
	 */	
	public function cancelVoucher()
	{
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));
		
		$airline = SFactory::getAirline();
		
		if( SFSAccess::isAirline() && $airline->id ) {
		
			$id 						= JRequest::getInt('voucher_id');
			$individual_voucher_id		= JRequest::getInt('individual_voucher_id', 0);			
			$blockcode 					= JRequest::getVar('blockcode');
			
			$reservation = SReservation::getInstance($blockcode,false,'code');
						
			if($reservation->airline_id != $airline->id ) {
				return false;
			}			
			if( (int) $id == 0 ) {
				return false;	
			}
			
			$db 	= JFactory::getDbo();
			$date 	= JFactory::getDate();		

			$voucher = $reservation->getVoucher($id);
			$individualVoucher = null;
			
			if( $voucher && $individual_voucher_id > 0 )
			{
				$query = 'SELECT * FROM #__sfs_voucher_groups WHERE voucher_id='.$individual_voucher_id.' AND voucher_group_id='.$voucher->id;
				$db->setQuery($query);
				
				$individualVoucher = $db->loadObject();
				
				if(!$individualVoucher)
				{					
					return false;	
				}
			}
			
			
			if (isset($voucher) && $voucher->id) {	
						
				$comment = JRequest::getString('comment');
				
				if( $individualVoucher ) {
					$query = 'UPDATE #__sfs_voucher_groups SET status=3, cancel_reason='.$db->quote($comment).',handled_date='.$db->Quote($date->toSql()).' WHERE voucher_id='.$individual_voucher_id;
					$db->setQuery($query);
					if(!$db->query()){
						throw new Exception($db->getErrorMsg());
					}		
					// reduce room for group voucher
					$query  = 'UPDATE #__sfs_voucher_codes SET';
					$query2 = 'UPDATE #__sfs_reservations SET';
					
					switch ( (int)$individualVoucher->room_type)
					{
						case 1:
							$query  .= ' sroom = sroom - 1, seats=seats-1';
							$query2 .= ' s_room_issued = s_room_issued-1';
							break;
						case 2:
							$query  .= ' sdroom = sdroom - 1, seats=seats-1';
							$query2 .= ' sd_room_issued = sd_room_issued-1';
							break;
						case 3:
							$query  .= ' troom = troom - 1, seats=seats-1';
							$query2 .= ' t_room_issued = t_room_issued-1';
							break;
						case 4:
							$query  .= ' qroom = qroom - 1, seats=seats-1';
							$query2 .= ' q_room_issued = q_room_issued-1';
							break;
						default:
							break;			
					}					
					$query  .= ' WHERE id='.$voucher->id;
					$query2 .= ' WHERE id='.$reservation->id.' AND airline_id='.$airline->id;
					
										
					$db->setQuery($query);
					if(!$db->query()){
						throw new Exception($db->getErrorMsg());
					}
					
					$db->setQuery($query2);
					if(!$db->query()){
						throw new Exception($db->getErrorMsg());
					}
					
				} else {
					$query = 'UPDATE #__sfs_voucher_codes SET status=3,handled_date='.$db->Quote($date->toSql()).' WHERE id='.$voucher->id;	
					$db->setQuery($query);
					if(!$db->query()){
						throw new Exception($db->getErrorMsg());
					}	
					if($comment)
					{
						$query = 'INSERT INTO #__sfs_voucher_cancels(voucher_id,reason) VALUES('.$voucher->id.','.$db->quote($comment).')';
						$db->setQuery($query);
						
						if(!$db->query()){
							throw new Exception($db->getErrorMsg());
						}
					}
					$query = 'UPDATE #__sfs_reservations SET t_room_issued=t_room_issued-'.$voucher->troom.',sd_room_issued=sd_room_issued-'.$voucher->sdroom.',s_room_issued=s_room_issued-'.$voucher->sroom.',q_room_issued=q_room_issued-'.$voucher->qroom.' WHERE id='.$reservation->id.' AND airline_id='.$airline->id;
					$db->setQuery($query);
					if(!$db->query()){
						throw new Exception($db->getErrorMsg());
					}			
				}

				//SEmail::cancelVoucher($reservation,$voucher);
				//SEmail::cancelTaxiVoucher($voucher->id);
				
				$url  = 'index.php?option=com_sfs&view=close&tmpl=component&closetype=cancelvoucher';
				$url .= '&bookingid='.$reservation->id;
				$url .= '&hotelid='.$reservation->hotel_id;
				$url .= '&nightdate='.$reservation->room_date;
				$url .= '&association_id='.$reservation->association_id;				
				$url .= '&Itemid='.JRequest::getInt('Itemid');
								
				$this->setRedirect($url);
			}
						
		} else {
			JFactory::getApplication()->close();
		}
	}
	
}

