<?php
defined('_JEXEC') or die;

class SfsModelMatch extends JModelLegacy
{
	private $_flights_seats = null;	
	private $_booked_rooms = null;
	private $_airline = null;
	private $_totalRooms = 0;
	private $_totalSeats = 0;
	
	private $_reservations = null;
		
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		$nightdate = JRequest::getVar('nightdate');
						
		$this->setState('match.nightdate',$nightdate);
				
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	
	public function match()
	{	
		$airline = SFactory::getAirline();
		$user  	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		
		//reservationid
		$reservationId 	= JRequest::getInt('reservationid');
		$flightData 	= JRequest::getVar('flight', array(), 'post', 'array');
		
		if($reservationId == 0){
			$this->setError( JText::_('COM_SFS_MATCH_SELECT_HOTEL') );
			return false;
		} 
		
		if( count($flightData) == 0 ) {
			$this->setError(JText::_('COM_SFS_MATCH_SELECT_SEATS'));
			return false;			
		}
		
		$reservations	= JRequest::getVar('reservations', array(), 'post', 'array');
				
		$voucherResult = null;
						
		//get reservation
		$reservation = SReservation::getInstance($reservationId);
		
		if($reservation->airline_id != $airline->id) {
			$this->setError('Restricted access');
			return false;
		}
				
		//assign and generate vouchers for the passenger
		$data = new stdClass();
		
		$data->mealplan 	= JRequest::getInt('mealplan'.$reservationId);
		$data->lunch 		= JRequest::getInt('lunch'.$reservationId);
		$data->breakfast 	= JRequest::getInt('breakfast'.$reservationId);
		
		$data->booking_id	= $reservationId;
		$data->created 		= $date->toSql();												
		$data->created_by 	= $user->id;
		
		$data->taxi_id = 0;
		//Taxi Voucher
		if( isset($reservations[$reservationId]) && ! empty($reservations[$reservationId]['isTaxiVoucher']) )
		{
			if( ! empty($reservations[$reservationId]['taxi_id'] ) && (int) $reservations[$reservationId]['taxi_id'] > 0 ) 
			{
				$data->taxi_id 		=  (int) $reservations[$reservationId]['taxi_id'];
				$data->rate 		=  $reservations[$reservationId][$data->taxi_id]['rate'];
				$data->fare_type 	=  $reservations[$reservationId][$data->taxi_id]['fare_type'];	

				if( $reservations[$reservationId][$data->taxi_id]['is_return'] )
				{
					$data->is_return 	= 1;	
				} else {
					$data->is_return 	= 0;
				}
				
			}
		}
		
		$seat_count = 0 ;
		
		foreach ( $flightData as $key => $value) 
		{
			$seat_count += count($value);
			if( !isset($data->flight_id) )		
				$data->flight_id = (int)$key;
			
		}	
		$this->_totalSeats = $seat_count;
		
		//echo $seat_count;
		//print_r( $flightData );die;
		if($seat_count==0) {
			$this->setError(JText::_('COM_SFS_MATCH_SELECT_SEATS'));
			return false;	
		}
		
		//lchung
		$calback_total_single_rooms 	= JRequest::getInt('calback_total_single_rooms');
		$calback_total_double_rooms 	= JRequest::getInt('calback_total_double_rooms');
		$calback_total_triple_rooms 	= JRequest::getInt('calback_total_triple_rooms');
		$calback_total_quad_rooms 	= JRequest::getInt('calback_total_quad_rooms');
		
		//lay tong so phong theo khach san de kiem tra truong hop. khi chon 1 passenger in a D room vi trong khach san nay co the con phong trong la sdroom chang han
		$s_number_room = JRequest::getInt('s_number_room' . $reservationId);
		$sd_number_room = JRequest::getInt('sd_number_room' . $reservationId);
		$t_number_room = JRequest::getInt('t_number_room' . $reservationId);
		$q_number_room = JRequest::getInt('q_number_room' . $reservationId);
		
		//lay tong so phong book
		$this->_totalRooms += $calback_total_single_rooms + $calback_total_double_rooms + $calback_total_triple_rooms + $calback_total_quad_rooms;

		if ( $s_number_room > 0 && $seat_count == 1 && $calback_total_single_rooms == 0 ) {
			$calback_total_single_rooms = 1;
		}
		elseif ( $sd_number_room > 0 && $seat_count == 1 && $calback_total_double_rooms == 0 ) {
			$calback_total_double_rooms = 1;
		}
		elseif ( $t_number_room > 0 && $seat_count == 1 && $calback_total_triple_rooms == 0 ) {
			$calback_total_triple_rooms = 1;
		}
		elseif ( $q_number_room > 0 && $seat_count == 1 && $calback_total_quad_rooms == 0 ) {
			$calback_total_quad_rooms = 1;
		}
		//End lchung
				
		$availableRooms = array();
		
		$availableRooms[1] = (int) ( $reservation->s_room - $reservation->s_room_issued );
		$availableRooms[2] = (int) ( $reservation->sd_room - $reservation->sd_room_issued );
		$availableRooms[3] = (int) ( $reservation->t_room - $reservation->t_room_issued );
		$availableRooms[4] = (int) ( $reservation->q_room - $reservation->q_room_issued );
		
		$seatLimit = $availableRooms[1] + 2*$availableRooms[2] + 3*$availableRooms[3]+ 4*$availableRooms[4];
		
		$roomTypeShortcutField = array(
			1 => 'sroom',
			2 => 'sdroom',
			3 => 'troom',
			4 => 'qroom'
		);
		
		$data->sroom   =  0;
		$data->sdroom  =  0;
		$data->troom   =  0;
		$data->qroom   =  0;
		
		//lchung
		$reservation = SReservation::getInstance($data->booking_id);
		
		if ( $calback_total_single_rooms == 1 && $calback_total_double_rooms == 1 && $calback_total_triple_rooms == 0 && $calback_total_quad_rooms == 0 ) {
			
			
			$code = $this->generateCode($reservation, $data);	
			$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
			$data->voucher_groups_id   	= $voucher_groups_id;
			
			$data->code = NULL;
			$data->room_type = 1;
			$data->seats	 = 1;
			$data->vgroup 	 = 1;		
			$data->sroom = 1; //update sd_room_issued & s_room_issued so nguoi dua vao phong
			$voucherResult[] = $this->generateVoucher( $data );
			
			$data->code = NULL;
			$data->room_type = 2;
			$data->seats	 = 2;
			$data->vgroup 	 = 1;
			$data->sdroom = 1;
			$data->sroom = 0;
			$voucherResult[] = $this->generateVoucher( $data );
							
			return '&voucher_id='. implode(",", $voucherResult) . '&voucher_groups_id=' . $voucher_groups_id;
		}
		//End lchung
		if($seat_count == 1 && $calback_total_single_rooms == 1 || $seat_count == 1 && $calback_total_single_rooms == 0)
		{
			for($i=1;$i<=4;$i++)
			{
				if( $availableRooms[$i] > 0 )
				{
					$data->room_type = $i;
					$data->seats	 = $seat_count;
					$data->vgroup 	 = 0;		
					$data->$roomTypeShortcutField[$i] = 1;
					
					//Single
					$code = $this->generateCode($reservation, $data);	
					$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
					$data->voucher_groups_id   	= $voucher_groups_id;
					$data->code = $code;
					$voucherResult = $this->generateVoucher( $data );					
					return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;
				}
			}	
			$error = 'Number Single room not enough for '.$seat_count.' seat';		
		}
		if($seat_count == 2 && $calback_total_double_rooms == 1)
		{
			for($i=2;$i<=4;$i++)
			{
				if( $availableRooms[$i] > 0 )
				{
					$data->room_type = $i;
					$data->seats	 = $seat_count;
					$data->vgroup 	 = 0;	
					$data->$roomTypeShortcutField[$i] = 1;
					
					$code = $this->generateCode($reservation, $data);	
					$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
					$data->voucher_groups_id   	= $voucher_groups_id;
					$data->code = $code;
					
					$voucherResult = $this->generateVoucher( $data );					
					return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;				
				}
			}	
			$error = 'Number Single/Double rooms not enough for '.$seat_count.' seats';
			$this->setError($error);				
		}
		
		if($seat_count == 3 && $calback_total_triple_rooms == 1)
		{
			for($i=3;$i<=4;$i++)
			{
				if( $availableRooms[$i] > 0 )
				{
					$data->room_type = $i;
					$data->seats	 = $seat_count;
					$data->vgroup 	 = 0;	
					$data->$roomTypeShortcutField[$i] = 1;
					
					$code = $this->generateCode($reservation, $data);	
					$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
					$data->voucher_groups_id   	= $voucher_groups_id;
					$data->code = $code;
									
					$voucherResult = $this->generateVoucher( $data );
							
					return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;
				}
			}	
			$error = 'Number Triple rooms not enough for '.$seat_count.' seats';
			$this->setError($error);			
		}
		if($seat_count == 4 && $calback_total_quad_rooms == 1 )
		{			
			if( $availableRooms[4] > 0 )
			{
				$data->room_type = 4;
				$data->seats	 = $seat_count;
				$data->vgroup 	 = 0;	
				$data->$roomTypeShortcutField[4] = 1;	
				$code = $this->generateCode($reservation, $data);	
				$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
				$data->voucher_groups_id   	= $voucher_groups_id;
				$data->code = $code;
				$voucherResult = $this->generateVoucher( $data );										
				return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;	
			}				
		}
		
		//process group voucher
		if( $seat_count >= 4 && $seatLimit >= $seat_count || $calback_total_single_rooms > 1 || $calback_total_double_rooms > 1 || $calback_total_triple_rooms > 1 && $calback_total_quad_rooms > 1)
		{
			///$data->seats	 = $seat_count;
			$data->vgroup 	 = 1;
			///$data->room_type = 5;
			
			$total_single_rooms = JRequest::getInt('total_single_rooms',0);
			$total_double_rooms = JRequest::getInt('total_double_rooms',0);
			$total_triple_rooms = JRequest::getInt('total_triple_rooms',0);
			$total_quad_rooms   = JRequest::getInt('total_quad_rooms',0);
			
			$Total_seat_count = (($total_single_rooms*1) + ($total_double_rooms*2) + ($total_triple_rooms * 3) + ($total_quad_rooms*4));
			$code = $this->generateCode($reservation, $data);	
			$voucher_groups_id = $this->InsertVoucherGroups($code, $Total_seat_count);
			$data->voucher_groups_id   	= $voucher_groups_id;
			// $data->code = $code; //truong hop Group nen khg add mac dinh cung code
			
			$ok = true;
			
			if( $total_single_rooms > $availableRooms[1] )
			{
				$ok = false;
			}
			if( $total_double_rooms > $availableRooms[2] )
			{
				$ok = false;
			}
			if( $total_triple_rooms > $availableRooms[3] )
			{
				$ok = false;
			}
			if( $total_quad_rooms > $availableRooms[4] )
			{
				$ok = false;
			}
			$voucherResultArr = array();
			if($ok)
			{
				$isGroupTransport = false;
				if( isset($reservations[$reservationId]['isGroupTransport']) && (int)$reservations[$reservationId]['isGroupTransport'] == 1 )
				{
					$isGroupTransport = true;					
					
				}
				//Case total_single_rooms 
				if ( $total_single_rooms >= 1 ) {
					for( $i = 0; $i<$total_single_rooms; $i++ ){
						$data->sroom  = 1;
						$data->sdroom  = 0;						
						$data->troom  = 0;
						$data->qroom  = 0;
						$data->seats	 = 1;
						$data->code = "";
						$data->room_type = 1;
						$voucherResultArr[] = $this->generateVoucher( $data, $isGroupTransport );
					}
				}
				
				if ( $total_double_rooms >= 1 ) {
					for( $i = 0; $i<$total_double_rooms; $i++ ){
						$data->sroom  = 0;
						$data->sdroom  = 1;						
						$data->troom  = 0;
						$data->qroom  = 0;
						$data->seats	 = 2;
						$data->code = "";
						$data->room_type = 2;
						$voucherResultArr[] = $this->generateVoucher( $data, $isGroupTransport );
					}
				}
				
				if ( $total_triple_rooms >= 1 ) {
					for( $i = 0; $i<$total_triple_rooms; $i++ ){
						$data->sroom  = 0;
						$data->sdroom  = 0;						
						$data->troom  = 1;
						$data->qroom  = 0;
						$data->seats	 = 3;
						$data->code = "";
						$data->room_type = 3;
						$voucherResultArr[] = $this->generateVoucher( $data, $isGroupTransport );
					}
				}
				
				if ( $total_quad_rooms >= 1 ) {
					for( $i = 0; $i<$total_quad_rooms; $i++ ){
						$data->sroom  = 0;
						$data->sdroom  = 0;
						$data->troom  = 0;
						$data->qroom  = 1;						
						$data->seats	 = 4;
						$data->code = "";
						$data->room_type = 4;
						$voucherResultArr[] = $this->generateVoucher( $data, $isGroupTransport );
					}
				}
				
				/*$data->sdroom = (int)$total_double_rooms;
				$data->troom  = (int)$total_triple_rooms;
				$data->sroom  = (int)$total_single_rooms;
				$data->qroom  = (int)$total_quad_rooms;
				
				if( isset($reservations[$reservationId]['isGroupTransport']) && (int)$reservations[$reservationId]['isGroupTransport'] == 1 )
				{
					$voucherResult = $this->generateVoucher( $data, true );					
				} else {
					$voucherResult = $this->generateVoucher( $data );	
				}
					
									
				return $voucherResult;*/
				
				return '&voucher_id='.implode(",", $voucherResultArr ) . '&voucher_groups_id=' . $voucher_groups_id;
			} else {
				$error = 'Number rooms not enough for '.$seat_count.' seats';
				$this->setError($error);
			}	
		} else {
			$error = 'Number rooms not enough for '.$seat_count.' seats';
			$this->setError($error);
		}
					
		return $voucherResult;
	}		
	
	private function generateVoucher( $data, $groupTransportInclude = false )
	{
		$session	 = JFactory::getSession();
		$airline 	 = SFactory::getAirline();
		$reservation = SReservation::getInstance($data->booking_id);
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		
		if( (int)$reservation->association_id > 0 ) {
			$association = SFactory::getAssociation($reservation->association_id);						
			$hotel 		 = new SHotel($reservation->hotel_id,$association->db);	
		} else {
			$hotel 		 = SFactory::getHotel($reservation->hotel_id);	
		}

		$db = $this->getDbo();
		
		$query = 'SELECT flight_code, delay_code FROM #__sfs_flights_seats WHERE id='.$data->flight_id;
		$db->setQuery($query);
		$flightSeat 	= $db->loadObject();
		
		$flight_code 	= $flightSeat->flight_code;	
		$delay_code		= $flightSeat->delay_code;	
	
		$voucherNumber = '';
				
		if( empty($data->code) ) 
		{
			//need to check if the voucher existed
			/*while(true) 
			{												
				$tmpVoucher = SfsHelper::createRandomString(2);			
	
				$numberPerson = $data->room_type;
				
				if( (int)$data->vgroup == 1)
				{
					$numberPerson = 9;
					if( $data->sroom > 0 && $data->sdroom == 0 && $data->troom == 0  && $data->qroom == 0 ) {
						$numberPerson = 1;
					}
					if( $data->sdroom > 0 && $data->sroom == 0 && $data->troom == 0  && $data->qroom == 0 ) {
						$numberPerson = 2;
					}
					if( $data->troom > 0 && $data->sdroom == 0 && $data->sroom == 0  && $data->qroom == 0 ) {
						$numberPerson = 3;
					}
					if( $data->qroom > 0 && $data->sdroom == 0 && $data->troom == 0  && $data->sroom == 0 ) {
						$numberPerson = 4;
					}				
				} 
				
				$tmpVoucher = JString::strtoupper($tmpVoucher.$numberPerson);

				$query  = 'SELECT COUNT(*) FROM #__sfs_voucher_codes WHERE code ='.$db->quote($tmpVoucher);
				$query .= ' AND booking_id='.(int)$reservation->id;
				$db->setQuery($query);
				
				if( ! $db->loadResult() ) {
					$voucherNumber = $tmpVoucher;
					break;
				}
				
			}	*/
			$voucherNumber = $this->generateCode($reservation, $data);	
			
			$data->code = $voucherNumber;	
		}	
		
		$taxiId			 = 0;
		$taxiVoucherRate = 0;
		$fare_type 		 = '';
		$isReturn		 = 0;
		
		if( (int) $data->taxi_id > 0 )
		{
			if( (int)$data->vgroup == 0) {
				$taxiId 		 = $data->taxi_id;	
				$taxiVoucherRate = $data->rate;
				$fare_type		 = $data->fare_type;	
				$isReturn		 = $data->is_return;	
			}
			else {
				$session->set('isTaxiBooked',1);
			}
		}		
		unset($data->taxi_id);
		unset($data->rate);
		unset($data->fare_type);
		unset($data->is_return);
		
		//insert vourcher
		if( !$db->insertObject('#__sfs_voucher_codes',$data) ) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$voucherId = $db->insertid();
		
		if($groupTransportInclude == true)
		{
			$session->set('groupTransportInclude',$voucherId);
		}
		
		$query  = 'UPDATE #__sfs_reservations SET ';
		$query .= 'sd_room_issued=sd_room_issued+'.$data->sdroom;
		$query .= ',t_room_issued=t_room_issued+'.$data->troom;
		$query .= ',s_room_issued=s_room_issued+'.$data->sroom;
		$query .= ',q_room_issued=q_room_issued+'.$data->qroom;
		$query .= ' WHERE id='.$data->booking_id;			
		
		$db->setQuery($query);
		$db->query();
				
		if( $data->vgroup == 1 ) {	
			$code = self::getVoucherGroups( $data->voucher_groups_id )->code;
			$this->set('successMsg',JText::sprintf('COM_SFS_MATCH_GVOUCHER_MSG',$code));
		} else {								
			$this->set('successMsg',JText::sprintf('COM_SFS_MATCH_VOUCHER_MSG',$data->code));			
		}			

		$db->setQuery('UPDATE #__sfs_flights_seats SET seats_issued=seats_issued+'.$data->seats.' WHERE id='.$data->flight_id);
		$db->query();
		
		// Generate Taxi Voucher
		if( (int) $taxiId > 0 )
		{
			$taxiVoucher = new stdClass();
			$taxiVoucher->taxi_id		= (int)$taxiId;
			$taxiVoucher->airline_id	= (int)$airline->id;
			$taxiVoucher->booking_id	= $data->booking_id;
			$taxiVoucher->hotel_id		= $reservation->hotel_id;
			$taxiVoucher->block_date	= $reservation->room_date;
			$taxiVoucher->flight_number	= $flight_code;
			$taxiVoucher->deley_code 	= $delay_code;
			$taxiVoucher->rate 			= $taxiVoucherRate;
			$taxiVoucher->fare_type 	= $fare_type;
			$taxiVoucher->is_return 	= $isReturn;
			$taxiVoucher->booked_by 	= JFactory::getUser()->get('id');
			$taxiVoucher->booked_date 	= JFactory::getDate()->toSql();
			$taxiVoucher->requested_time = 0;
						
			$taxiVoucherCode = $voucherNumber;
			
			if($taxiVoucher->is_return){
				$taxiVoucherCode .= '-T2';
			} else {
				$taxiVoucherCode .= '-T1';
			}
			
			$taxiVoucher->code	= $taxiVoucherCode;
			
			while (true)
			{
				$reference_number = SfsHelperDate::getDate('now','dmy', $time_zone);
			
				$reference_number .= '-'.SfsHelper::createRandomString(2);

				if( (int)$airline->grouptype == 3 ) {
					$ghAirline = $airline->getSelectedAirline();
					$reference_number .= '-'.$ghAirline->code;
				} else {
					$reference_number .= '-'.$airline->code;	
				}
				
				$reference_number .= '-T'.$data->seats;
				
				$reference_number = JString::strtoupper($reference_number);	
				
				$query = 'SELECT COUNT(*) FROM #__sfs_taxi_vouchers WHERE reference_number='.$db->quote($reference_number);
				$db->setQuery($query);
				$count = $db->loadResult();
				if( ! $count )
				{
					$taxiVoucher->reference_number = $reference_number;
					break;	
				}
			}
			
			//insert taxi vourcher
			if( !$db->insertObject('#__sfs_taxi_vouchers',$taxiVoucher) ) 
			{				
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			$taxiVoucherId = $db->insertid();
			
			// insert voucher map
			$query = 'INSERT INTO #__sfs_airline_taxi_voucher_map(voucher_id,taxi_voucher_id) VALUES('.$voucherId.','.$taxiVoucherId.')';
			$db->setQuery($query);
			$db->query();
			
			$params = JComponentHelper::getParams('com_sfs');
			$sms_taxi_text  = $params->get('sms_taxi_text');
			$smsPhoneNumber = $params->get('sms_taxi_phone_number');		
			
			if($sms_taxi_text && $smsPhoneNumber) {
				$airportCode = $airline->airport_code;
				$airlineName = $airline->getAirlineName();				
				$sms_taxi_text = JString::str_ireplace('{airline}', $airportCode.', '.$airlineName, $sms_taxi_text);
				$sms_taxi_text = JString::str_ireplace('{departuretime}', 'asap', $sms_taxi_text);
				$sms_taxi_text = JString::str_ireplace('{numberpassenger}', $data->seats, $sms_taxi_text);
				$sms_taxi_text = JString::str_ireplace('{vouchernumber}', $taxiVoucher->code, $sms_taxi_text);
				
				
				$query = 'SELECT * FROM #__sfs_taxi_companies WHERE id='.$taxiVoucher->taxi_id;				
				$db->setQuery($query);
				$taxiCompany = $db->loadObject();
				
				if($taxiCompany)
				{
					$sms_taxi_text = JString::str_ireplace('{taxiname}',$taxiCompany->name, $sms_taxi_text);
					$sms_taxi_text = JString::str_ireplace('{taxiphone}','+'.$taxiCompany->telephone, $sms_taxi_text);	
					
					SEmail::SMS('taxi', $sms_taxi_text);
				}				
			}
			
		}

		// Email to hotel			
		$fbData = array();
		$fbData['mealplan']  = $data->mealplan;
		$fbData['lunch'] 	 = $data->lunch;
		$fbData['breakfast'] = $data->breakfast;
		
		$fbData['totalRooms'] = '0';
		$fbData['totalRooms'] = $this->_totalRooms;
		if( $fbData['totalRooms'] <= 1 ) {
			$fbData['totalRooms'] = $fbData['totalRooms'] . ' room';
		}
		elseif ( $fbData['totalRooms'] > 1 ) {
			$fbData['totalRooms'] = $fbData['totalRooms'] . ' rooms';
		}
		
		$fbData['totalSeats'] = $this->_totalSeats;
		if( $this->_totalSeats <= 1 ){
			$fbData['totalSeats'] = $fbData['totalSeats'] . ' passenger';
		}
		else {
			$fbData['totalSeats'] = $fbData['totalSeats'] . ' passenger(s)';
		}
		
		$this->_voucherEmail($hotel, $data->seats, $reservation, $data->code, $fbData);	
					
		return $voucherId;
	}
	
	//lchung
	public function generateCode( $reservation, $data = array() ){
		//need to check if the voucher existed
		$voucherNumber = '';
		$db = $this->getDbo();
		while(true) 
		{												
			$tmpVoucher = SfsHelper::createRandomString(2);			

			$numberPerson = $data->room_type;
			
			if( (int)$data->vgroup == 1)
			{
				$numberPerson = 9;
				if( $data->sroom > 0 && $data->sdroom == 0 && $data->troom == 0  && $data->qroom == 0 ) {
					$numberPerson = 1;
				}
				if( $data->sdroom > 0 && $data->sroom == 0 && $data->troom == 0  && $data->qroom == 0 ) {
					$numberPerson = 2;
				}
				if( $data->troom > 0 && $data->sdroom == 0 && $data->sroom == 0  && $data->qroom == 0 ) {
					$numberPerson = 3;
				}
				if( $data->qroom > 0 && $data->sdroom == 0 && $data->troom == 0  && $data->sroom == 0 ) {
					$numberPerson = 4;
				}				
			} 
			
			$tmpVoucher = JString::strtoupper($tmpVoucher.$numberPerson);

			$query  = 'SELECT COUNT(*) FROM #__sfs_voucher_codes WHERE code ='.$db->quote($tmpVoucher);
			$query .= ' AND booking_id='.(int)$reservation->id;
			$db->setQuery($query);
			
			if( ! $db->loadResult() ) {
				$voucherNumber = $tmpVoucher;
				break;
			}
			
		}
		
		return $voucherNumber;
	}
	//End lchung
	
	public function generateSingleVoucher($voucherData)
	{
		$user		= JFactory::getUser();		
		$db 		= $this->getDbo();
		$date 	 	= JFactory::getDate();
		$airline 	= SFactory::getAirline();	
		
		// generate voucher
		$result 	= $this->generateVoucher($voucherData);
			
		return $result;
	}
	
	public function getReservations()
	{
        $airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;

		// For ground hander: do not nothing if user does not select an airline
		if( (int) $airline->grouptype==3 ) {
			if( (int) $airline->iatacode_id <= 0 ) {
				return null;
			}
		}		
		$db = $this->getDbo();
				
		if( $this->_reservations == null )
		{
			$dateNight = $this->getNightDate();
			$query = $db->getQuery(true);
			
			/*NEW*/
			$query->select('a.*, h.name,h.address, h.city');
			$query->from('#__sfs_reservations AS a');
			$query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
//			if($airline->grouptype==3) {
//				$query->innerJoin('#__sfs_gh_reservations AS d ON d.reservation_id=a.id AND d.airline_id='.(int)$airline->iatacode_id);
//			}

            if((int)$airport_current_id != -1)
            {
                $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
            }
				
			$query->where('a.airline_id = '.$airline->id);
			
			$query->where('a.blockdate = '.$db->quote($dateNight));
			
			#$query->where('a.status <> '.$db->quote('A') );
			$query->where('a.status <> '.$db->quote('R') );
			$query->where('a.status <> '.$db->quote('D') );
			
			$query->order('a.blockdate DESC,a.booked_date DESC');
				
			$db->setQuery($query);		
							
			$reservations = $db->loadObjectList('id');
			if( $db->getErrorNum() ) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			if( count($reservations) == 0 ) {
				return $this->_reservations;
			}

			$localReservIds = array();
			foreach ($reservations as & $reservation)
			{									
				if( (int) $reservation->association_id == 0)
				{
					$db = JFactory::getDbo();
				} else {
					$association = SFactory::getAssociation($reservation->association_id);	
					$db = $association->db;
				}					
				
				$query = $db->getQuery(true);		
							
				$query->select('a.name,a.star,a.address, a.city');
				$query->from('#__sfs_hotel AS a');
				$query->select('m.stop_selling_time, m.bf_service_hour, m.bf_opentime, m.bf_closetime,m.service_hour, m.service_opentime, m.service_closetime, m.lunch_service_hour, m.lunch_opentime, m.lunch_closetime');
				$query->innerJoin('#__sfs_hotel_mealplans AS m ON m.hotel_id=a.id');
		
				$query->select('tp.transport_available, tp.transport_complementary, tp.operating_hour, tp.operating_opentime, tp.operating_closetime, tp.frequency_service, tp.pickup_details');
				$query->leftJoin('#__sfs_hotel_transports AS tp ON tp.hotel_id=a.id');

				
				if( $airline->allowTaxiVoucher() ) {
					$query->select('hbp.ring AS hotel_ring');
					$query->leftJoin('#__sfs_hotel_backend_params AS hbp ON hbp.hotel_id=a.id');
				}
				
				$query->where('a.id = '.$reservation->hotel_id);
				$db->setQuery($query);
				$hotelInfo = $db->loadObject(); 
				
				if( $hotelInfo )
				{
					$vars = get_object_vars($hotelInfo);		
					foreach ($vars as $key => $value) {
						$reservation->$key = $value;				
					}
				}										
				
			}

			$this->_reservations = $reservations;			
		}
		return $this->_reservations;
	}
	
	public function getHotelBackendParams( $hotel_id = 0 )
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_hotel_backend_params AS a');
		$query->where('a.hotel_id = '.$hotel_id);
		$db->setQuery($query);
		$hotelInfo = $db->loadObject(); 
		return $hotelInfo;
	}
	
	public function getHotel()
	{
		$hotel = null;
		$association_id = JRequest::getInt('association_id',0);
		$hotelId		= JRequest::getInt('hotelid',0);
		if( $hotelId > 0 )
		{
			if( $association_id > 0 )
			{
				$association = SFactory::getAssociation($association_id);				
				$hotel = new SHotel($hotelId,$association->db);	
			} else {
				$hotel = SFactory::getHotel($hotelId);	
			}							
		}
		return $hotel;
	}
	
	
	public function getVouchers( $reservationId = null , $flightId = null , $roomType = null )	
	{						
		$reservationId = ! $reservationId ? (int) JRequest::getInt('reservationid') : $reservationId ;
		
		$reservations =  $this->getReservations();
				
		if( $reservationId || count($reservations) ) {
			
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*,f.flight_code,u.name AS created_name,h.name,h.telephone,r.blockcode,r.payment_type,r.course_type,r.ws_booking,r.status as reservation_status');
			$query->from('#__sfs_voucher_codes AS a');

			$query->innerJoin('#__sfs_reservations AS r ON r.id=a.booking_id');
			$query->innerJoin('#__sfs_hotel AS h ON h.id=r.hotel_id');
			
			$query->leftJoin('#__sfs_flights_seats AS f ON f.id=a.flight_id');
			$query->innerJoin('#__users AS u ON u.id=a.created_by');
			
			$query->select('vm.taxi_voucher_id, tv.taxi_id,tv.printed AS taxi_printed,tv.return_printed AS taxi_return_printed');
			$query->leftJoin('#__sfs_airline_taxi_voucher_map AS vm ON vm.voucher_id=a.id');
			$query->leftJoin('#__sfs_taxi_vouchers AS tv ON tv.id=vm.taxi_voucher_id');
			
			$query->select('bm.busreservation_id');			
			$query->leftJoin('#__sfs_voucher_busreservation_map AS bm ON bm.voucher_id=a.id');
			
			
			if($reservationId)
			{
				$query->where('a.booking_id='.$reservationId);	
			} 
			else 
			{
				$res = array();
				foreach ($reservations as $r)
				{
					$res[] = $r->id;
				}
				$query->where('a.booking_id IN ('.implode(',', $res).')');	
			}
			
			
			if($flightId){
				$query->where('a.flight_id='.$flightId);	
			}	
			
			if( $roomType > 0 && $roomType < 4){
				if($roomType < 3) {
					$query->where('a.room_type=1 OR a.room_type=2');	
				} else {
					$query->where('a.room_type='.$roomType );
				}
					
			}
			$query->order('a.created DESC');
			
			$db->setQuery($query);
			
			$rows = $db->loadObjectList();
			
			if( $db->getErrorNum() ) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			if(count($rows))
			{
				$voucherIds = array();
				foreach ($rows as & $row)
				{
					$voucherIds[] = $row->id;
					$row->passengers = array();
					if( (int)$row->vgroup == 1 )
					{
						$row->individualVouchers  = array();	
					}
				}				
				$query = 'SELECT * FROM #__sfs_trace_passengers WHERE voucher_id IN ('.implode(',', $voucherIds).')';
				$db->setQuery($query);			
				$passengers = $db->loadObjectList();
				if(count($passengers))
				{
					foreach ($rows as & $row)
					{
						foreach ($passengers as $passenger)
						{
							if( (int)$row->id == $passenger->voucher_id )
							{
								$row->passengers[] = $passenger;
							}														
						}
					}
				}
				
				
			    $query = 'SELECT * FROM #__sfs_voucher_groups WHERE voucher_group_id IN ('.implode(',', $voucherIds).')';
				$db->setQuery($query);			
				$individualVouchers = $db->loadObjectList();
				
				if(count($individualVouchers))
				{
					foreach ($rows as & $row)
					{
						if( (int)$row->vgroup == 1 )
						{
							foreach ($individualVouchers as $individualVoucher)
							{
								if( (int)$row->id == (int)$individualVoucher->voucher_group_id )
								{
									$row->individualVouchers[] = $individualVoucher;
								}														
							}
						}
					}
				}
				
			}

			
			return $rows;
			
		} 
		
		return false;		
	}
	
	public function getIssuedVouchers()
	{
		$reservationId = ! $reservationId ? (int) JRequest::getInt('reservationid') : $reservationId ;
		if( $reservationId ) {
			
			$db = $this->getDbo();

			$query = $db->getQuery(true);
			
			$query->select('a.passengers');
			$query->from('#__sfs_reservation_passengers AS a');
			$query->where('a.reservation_id=' . (int) $reservationId);
		
			$db->setQuery($query);
			$result = $db->loadResult();

			if( $db->getErrorNum() ) {
				$this->setError($db->getErrorMsg());						
			}							

			$registry = new JRegistry;
			$registry->loadString($result);
			
			$data = $registry->toArray();
			
			return $data;
		}
		return false;				
	}
	
	public function getVoucher() 
	{
		$voucherId 				= JRequest::getInt('voucherid');
		$individualVoucherId 	= JRequest::getInt('individual_voucher');		
		$blockcode 				= JRequest::getVar('blockcode');

		
		if( $voucherId > 0 )
		{	
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.*,b.blockcode,b.blockdate, b.url_code, h.name, h.city, p.phone_number');
			$query->from('#__sfs_voucher_codes AS a');
			$query->innerJoin('#__sfs_reservations AS b ON b.blockcode='.$db->quote($blockcode));
            $query->innerJoin('#__sfs_hotel AS h ON h.id=b.hotel_id');
            $query->leftJoin('#__sfs_trace_passengers AS p ON p.voucher_id=a.id');

			$query->where('a.id='.$voucherId);	
			
			$db->setQuery($query);
			
			$voucher = $db->loadObject();

			if( $db->getErrorNum() ) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			if( $voucher->vgroup && $individualVoucherId > 0 )
			{
				$query = 'SELECT * FROM #__sfs_voucher_groups WHERE voucher_id='.$individualVoucherId.' AND voucher_group_id='.$voucher->id;
				$db->setQuery($query);			
				$voucher->individualVoucher = $db->loadObject();
			}

						
			return $voucher;	
		}
	}
		
	/**
	 * Gets an array of the flights/seats for the airline
	 *
	 * @return  array
	 *
	 */
	public function getFlightsSeats() 
	{		
		$airline 		= SFactory::getAirline();		
		$night			= $this->getNightDate();
		
		$flight_seats 	= $airline->getFlightsSeats($night);
		
		return $flight_seats;		
	}
	
	
	/**
	 * Gets an array of the flight codes for the airline
	 *
	 * @return  array
	 *
	 */	
	public function getFlightCodes()
	{
		$result = array();
		$rows = $this->getFlightsSeats();
		
		if(count($rows)) {
			foreach ($rows as $row) {
				$result[$row->flight_code] = $row;	
			}
		}
		
		return $result;	
	}
	
	/**
	 * Gets the current date. The reset hours used
	 *  
	 */
	public function getTodayDate()
	{
		$airline 	= SFactory::getAirline();
		$params		= JComponentHelper::getParams('com_sfs');
		$cleanTime 	= trim($params->get('match_hours'));

		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$cleanTime = $setup_airport['hours_on_match_page'];
		}
		//End lchung	

        $session = JFactory::getSession();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
        //$session = JFactory::getSession();
        //$time_zone = $session->get("time_zone");
		//lchung
		$time_zone = $airline_current->time_zone;
		//End lchung


		$db = $this->getDbo();
		
		$is_next_day = false;
						
		if( strlen($cleanTime) > 0 )
		{
			$cleanTime = explode( ':' , $cleanTime);
			
			$nowTime = SfsHelperDate::getDate('now','H:i',$time_zone);
						
			$nowTime = explode(':', $nowTime);
						
			JArrayHelper::toInteger($nowTime);
			JArrayHelper::toInteger($cleanTime);
								
			if( $nowTime[0] >= $cleanTime[0] ){
				if( $nowTime[0] == $cleanTime[0]) {
					if( $nowTime[1] >= $cleanTime[1]  ) {
						$is_next_day = true;
					}	
				} else {
					$is_next_day = true;
				}						
			}	
		}
										
		$today = SfsHelperDate::getDate('now','Y-m-d',$time_zone);
		
		if( ! $is_next_day )
		{
			$today = SfsHelperDate::getPrevDate('Y-m-d', $today);
		}
		return $today;
	}
	
	/**
	 * Gets the night by request
	 * 
	 */
	public function getNightDate()
	{
		$airline = SFactory::getAirline();
		
		$dateNight = $this->getState('match.nightdate');
		
		if( ! $dateNight )
		{
			$dateNight = $this->getTodayDate();
		}
		
		return $dateNight;
	}
	
	/**
	 * Get Next Night
	 */

	public function getNextNight()
	{		
		$dateNight = $this->getNightDate();
				
		$nextNight = SfsHelperDate::getNextDate('Y-m-d', $dateNight);
			
		return $nextNight;
	}
	
	/**
	 * Get Prev Night
	 */

	public function getPrevNight()
	{
		$prevNight = '';
		
		$dateNight = $this->getNightDate();			
		
		if( !$dateNight )
		{
			return null;			
		} 
		else 
		{
			
			$prevNight = SfsHelperDate::getPrevDate('Y-m-d', $dateNight);
			
			$db = $this->getDbo();
			
			$today = $this->getTodayDate();
			
			$db->setQuery('SELECT DATEDIFF('.$db->quote($today).','.$db->quote($prevNight).') AS diffdate');
			
			$diff  = $db->loadResult();
			
			if($diff > 1)
			{
				return null;
			}
			
		}	
		
		return $prevNight;
	}	
	
	/**
	 * Email the voucher to hotel members
	 *
	 * @param   int     $hotelId       ID of hotel
	 * @param   string  $hotelName     Name of the blocked hotel
	 * @param   int     $seats         Number seats of the voucher
	 * @param   object  $reservation   Reservation of the blockcode	 
	 *
	 * @return  boolean  True on success
	 *
	 */	
	protected function _voucherEmail( $hotel, $seats, $reservation, $code, $fbData )
	{		
		$user	 = JFactory::getUser();
		$airline = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
			
		$hotelId   = $hotel->id;
		$hotelName = $hotel->name;
						
		//Gets hotel contacts
		$hotel_contacts = $hotel->getContacts();
			
		$blockcode 		= $reservation->blockcode;
		
		if($fbData['mealplan']){
			$course_type	= (int)$reservation->mealplan ? $reservation->course_type : 0;
		} else {
			$course_type	= 0;
		}
		
		if($fbData['breakfast']){
			$breakfast		= $reservation->breakfast;
		} else {
			$breakfast		= 0;
		}
		
		$totalRooms = $fbData['totalRooms'];
		$totalSeats = $fbData['totalSeats'];
		
		$lunch = $fbData['lunch']; 
		
		
		$created_date 	= SfsHelperDate::getDate('now',JText::_('DATE_FORMAT_LC3'), $time_zone);
		
		$airline_name 	= $airline->name;
		$hotel_name   	= $hotelName;
		
		$hotel_contacts_str = '';
		foreach ($hotel_contacts as $contact) {
			$hotel_contacts_str .= '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
		}
        $nameText = "";
        $comment		= JRequest::getString('comment');
        $passengers 	= JRequest::getVar('passengers', array() , 'post', 'array');
        if( count($passengers) )
        {

            $nameArray = array();
            foreach($passengers as $passenger) {
                if( strlen($passenger['firstname']) || strlen($passenger['lastname']) )
                {
                    $name = $passenger['firstname']." ".$passenger['lastname'] ;
                    $nameArray[] = trim($name);
                    $nameText = implode(', ', $nameArray);
                }
            }
        }

		ob_start();


        require_once JPATH_COMPONENT.'/libraries/emails/voucheremail.php';
		$voucherBody = ob_get_clean();
		
		$emailSubject = JText::sprintf('COM_SFS_MATCH_VOUCHER_EMAIL_SUBJECT',$blockcode);
		
		foreach ($hotel_contacts as $contact){
			$contact_name = $contact->name.' '.$contact->surname;			
			$emailBody	  = JString::str_ireplace('{hotelcontact}', $contact_name, $voucherBody);	
			if( !empty($contact->systemEmails) && (int)JString::strpos($contact->systemEmails, 'voucher') > 0 ){						
				JFactory::getMailer()->sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $contact->email, $emailSubject , $emailBody, true);
			}
		}	
		
		//private email to specify person		
		if( SFSAccess::check($user, 'gh') && is_array($airline->params) ){
			jimport('joomla.mail.helper'); 
			if( isset($airline->params['private_email']) ){								
				$ghGeneralEmail = $airline->params['private_email'];
				if( JMailHelper::isEmailAddress($ghGeneralEmail) ){
					ob_start(); 
					require_once JPATH_COMPONENT.'/libraries/emails/privatevoucher.php';			
					$voucherBody = ob_get_clean();	
					$emailSubject_ = JText::sprintf('COM_SFS_MATCH_VOUCHER_EMAIL_SUBJECT_PRIVATE',$blockcode);	
					JFactory::getMailer()->sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $ghGeneralEmail, $emailSubject_ , $voucherBody, true);
				}
						
			}
		}		
		
		return true;	
	}
	
	protected $_terminals = null;
	protected $_transport_company = null;
	
	public function getTerminals()
	{
		if( $this->_terminals == null )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('*');
			$query->from('#__sfs_iatacodes');
			$query->where('type=3');
			
			$db->setQuery($query);
			$this->_terminals = $db->loadObjectList();
		}
		return $this->_terminals;
	}
	
	public function getTransportCompany()
	{		
		if( $this->_transport_company == null )
		{
			$airline = SFactory::getAirline();
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*,b.*');
			$query->from('#__sfs_group_transportation_airlines AS a');
			$query->innerJoin('#__sfs_group_transportations AS b ON b.id=a.group_transportation_id');
			$query->where('a.airline_id='.(int)$airline->iatacode_id);
			
			$db->setQuery($query);
			$this->_transport_company = $db->loadObject();
		}
		return $this->_transport_company;
	}
	
	//lchung
	//jos_sfs_voucher_groups
	function InsertVoucherGroups( $code, $seats ){
		
		$db = $this->getDbo();		
		$data = new stdClass();
		$data->code	= $code;
		$data->seats 	= $seats;
		$data->handled_date 		= date('Y-m-d H:i:s');				
		//insert vourcher
		if( !$db->insertObject('#__sfs_voucher_groups',$data) ) {
			$this->setError($db->getErrorMsg());
			return false;
		}		
		$Id = $db->insertid();
		return $Id;		
	}
	
	public function getVoucherGroups( $voucher_groups_id = 0 )
	{		
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.*');
			$query->from('#__sfs_voucher_groups AS a');
			if ( $voucher_groups_id > 0 ) 
				$query->where('a.id=' . $voucher_groups_id );
			else
				$query->where('a.id='.(int)JRequest::getVar('voucher_groups_id', 0) );
			$db->setQuery($query);
			return $db->loadObject();
	}
	//End lchung
	//Minh Tran
	public function matchIssueVoucherHotel()
	{	
		$airline = SFactory::getAirline();
		$user  	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		
		//reservationid
		$reservationId 	= JRequest::getInt('reservationid');
		//$flightData 	= JRequest::getVar('flight', array(), 'post', 'array');
		$share_room     = JRequest::getVar('share_room', array(), 'post', 'array');
		$pass_ids     = JRequest::getVar('pass_ids', array(), 'post', 'array');
		
		if($reservationId == 0){
			$this->setError( JText::_('COM_SFS_MATCH_SELECT_HOTEL') );
			return false;
		} 
		
		if( count($share_room) == 0 ) {
			$this->setError(JText::_('COM_SFS_MATCH_SELECT_SEATS'));
			return false;			
		}
		
		$reservations	= JRequest::getVar('reservations', array(), 'post', 'array');
				
		$voucherResult = null;
						
		//get reservation
		$reservation = SReservation::getInstance($reservationId);
		
		if($reservation->airline_id != $airline->id) {
			$this->setError('Restricted access');
			return false;
		}
				
		//assign and generate vouchers for the passenger
		$data = new stdClass();
		
		$data->mealplan 	= JRequest::getInt('mealplan');
		$data->lunch 		= JRequest::getInt('lunch');
		$data->breakfast 	= JRequest::getInt('breakfast');
		
		$data->booking_id	= $reservationId;
		$data->created 		= $date->toSql();												
		$data->created_by 	= $user->id;
		
		$data->taxi_id = 0;
		//Taxi Voucher
		if( isset($reservations[$reservationId]) && ! empty($reservations[$reservationId]['isTaxiVoucher']) )
		{
			if( ! empty($reservations[$reservationId]['taxi_id'] ) && (int) $reservations[$reservationId]['taxi_id'] > 0 ) 
			{
				$data->taxi_id 		=  (int) $reservations[$reservationId]['taxi_id'];
				$data->rate 		=  $reservations[$reservationId][$data->taxi_id]['rate'];
				$data->fare_type 	=  $reservations[$reservationId][$data->taxi_id]['fare_type'];	

				if( $reservations[$reservationId][$data->taxi_id]['is_return'] )
				{
					$data->is_return 	= 1;	
				} else {
					$data->is_return 	= 0;
				}
				
			}
		}
		//print_r($data);die();
		$seat_count = 0 ;
		
		/*foreach ( $flightData as $key => $value) 
		{
			$seat_count += count($value);
			if( !isset($data->flight_id) )		
				$data->flight_id = (int)$key;
			
		}	*/
		//lay so phong hien tai còn
		$s_number_room_current = 0;
		if($reservation->s_room>0){
			$s_number_room_current = $reservation->s_room - $reservation->s_room_issued;	
		}
		$sd_number_room_current = 0;
		if($reservation->sd_room > 0){
			$sd_number_room_current = $reservation->sd_room - $reservation->sd_room_issued;
		}
		$t_number_room_current = 0;
		if($reservation->t_room > 0){
			$t_number_room_current = $reservation->t_room - $reservation->t_room_issued;
		}
		$q_current_number_room = 0;
		if($reservation->q_room > 0){
			$q_number_room_current = $reservation->q_room - $reservation->q_room_issued;
		}

		$calback_total_single_rooms=0;
		$calback_total_double_rooms=0;
		$calback_total_triple_rooms=0;
		$calback_total_quad_rooms=0;
		foreach($share_room as $s){			
			if($s){
				$seat_count+=count($s);				
				if(count($s)==1){
					$calback_total_single_rooms++;
				}
				if(count($s)==2){
					$calback_total_double_rooms++;
				}
				if(count($s)==3){
					$calback_total_triple_rooms++;
				}
				if(count($s)==4){
					$calback_total_quad_rooms++;
				}
			}			
		}	
		if($s_number_room_current == 0){
			// kiem tra neu so phong doi du cho so nguoi dat phong rieng, thi cho phép nguoi dat phong rieng book phong doi
			if($sd_number_room_current > $calback_total_single_rooms){
				$calback_total_double_rooms = $calback_total_double_rooms + $calback_total_single_rooms;
				$calback_total_single_rooms = 0;
			}
		}
		$this->_totalSeats = $seat_count;		
		
		if($seat_count==0) {
			$this->setError(JText::_('COM_SFS_MATCH_SELECT_SEATS'));
			return false;	
		}
		
		//lchung
		//$calback_total_single_rooms 	= JRequest::getInt('calback_total_single_rooms');
		//$calback_total_double_rooms 	= JRequest::getInt('calback_total_double_rooms');
		//$calback_total_triple_rooms 	= JRequest::getInt('calback_total_triple_rooms');
		//$calback_total_quad_rooms 	= JRequest::getInt('calback_total_quad_rooms');
		
		//lay tong so phong theo khach san de kiem tra truong hop. khi chon 1 passenger in a D room vi trong khach san nay co the con phong trong la sdroom chang han
		$s_number_room = JRequest::getInt('s_number_room');
		$sd_number_room = JRequest::getInt('sd_number_room');
		$t_number_room = JRequest::getInt('t_number_room');
		$q_number_room = JRequest::getInt('q_number_room');
		
		//lay tong so phong book
		$this->_totalRooms += $calback_total_single_rooms + $calback_total_double_rooms + $calback_total_triple_rooms + $calback_total_quad_rooms;

		if ( $s_number_room > 0 && $seat_count == 1 && $calback_total_single_rooms == 0 ) {
			$calback_total_single_rooms = 1;
		}
		elseif ( $sd_number_room > 0 && $seat_count == 1 && $calback_total_double_rooms == 0 ) {
			$calback_total_double_rooms = 1;
		}
		elseif ( $t_number_room > 0 && $seat_count == 1 && $calback_total_triple_rooms == 0 ) {
			$calback_total_triple_rooms = 1;
		}
		elseif ( $q_number_room > 0 && $seat_count == 1 && $calback_total_quad_rooms == 0 ) {
			$calback_total_quad_rooms = 1;
		}
		//End lchung
				
		$availableRooms = array();
		
		$availableRooms[1] = (int) ( $reservation->s_room - $reservation->s_room_issued );
		$availableRooms[2] = (int) ( $reservation->sd_room - $reservation->sd_room_issued );
		$availableRooms[3] = (int) ( $reservation->t_room - $reservation->t_room_issued );
		$availableRooms[4] = (int) ( $reservation->q_room - $reservation->q_room_issued );
		
		$seatLimit = $availableRooms[1] + 2*$availableRooms[2] + 3*$availableRooms[3]+ 4*$availableRooms[4];
		
		$roomTypeShortcutField = array(
			1 => 'sroom',
			2 => 'sdroom',
			3 => 'troom',
			4 => 'qroom'
		);
		
		$data->sroom   =  0;
		$data->sdroom  =  0;
		$data->troom   =  0;
		$data->qroom   =  0;
		
		//lchung
		$reservation = SReservation::getInstance($data->booking_id);
		
		if ( $calback_total_single_rooms == 1 && $calback_total_double_rooms == 1 && $calback_total_triple_rooms == 0 && $calback_total_quad_rooms == 0 ) {			
			$code = $this->generateCode($reservation, $data);	
			$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
			$data->voucher_groups_id   	= $voucher_groups_id;
			
			$data->code = NULL;
			$data->room_type = 1;
			$data->seats	 = 1;
			$data->vgroup 	 = 1;		
			$data->sroom = 1; //update sd_room_issued & s_room_issued so nguoi dua vao phong
			foreach($share_room as $s){			
				if($s){								
					if(count($s)==1){
						$voucherResult[] = $this->generateIssueVoucherHotel( $data, false, $s );
					}					
				}				
			}	
			
			
			$data->code = NULL;
			$data->room_type = 2;
			$data->seats	 = 2;
			$data->vgroup 	 = 1;
			$data->sdroom = 1;
			$data->sroom = 0;
			foreach($share_room as $s){			
				if($s){								
					if(count($s)==2){
						$voucherResult[] = $this->generateIssueVoucherHotel( $data, false , $s  );
					}					
				}				
			}	
			
			return array('hotel_id'=>$reservation->hotel_id,'voucher_id'=>implode(",", $voucherResult),'voucher_groups_id'=>$voucher_groups_id,'reservationid'=>$reservation->id,'blockdate'=>$reservation->blockdate,'name_hotel'=>$reservation->name);
			//return '&voucher_id='. implode(",", $voucherResult) . '&voucher_groups_id=' . $voucher_groups_id;
		}
		//End lchung
		if($seat_count == 1 && $calback_total_single_rooms == 1 || $seat_count == 1 && $calback_total_single_rooms == 0)
		{
			for($i=1;$i<=4;$i++)
			{
				if( $availableRooms[$i] > 0 )
				{
					$data->room_type = $i;
					$data->seats	 = $seat_count;
					$data->vgroup 	 = 0;		
					$data->$roomTypeShortcutField[$i] = 1;
					
					//Single
					$code = $this->generateCode($reservation, $data);	
					$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
					$data->voucher_groups_id   	= $voucher_groups_id;
					$data->code = $code;
					foreach($share_room as $s){			
						if($s){								
							if(count($s)==$seat_count){
								$voucherResult[] = $this->generateIssueVoucherHotel( $data, false , $s );
							}					
						}				
					}	
									
					//return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;
					return array('hotel_id'=>$reservation->hotel_id,'voucher_id'=>implode(",", $voucherResult),'voucher_groups_id'=>$voucher_groups_id,'blockdate'=>$reservation->blockdate,'reservationid'=>$reservation->id,'name_hotel'=>$reservation->name);
				}
			}	
			$error = 'Number Single room not enough for '.$seat_count.' seat';		
		}
		if($seat_count == 2 && $calback_total_double_rooms == 1)
		{	
			for($i=2;$i<=4;$i++)
			{
				if( $availableRooms[$i] > 0 )
				{
					$data->room_type = $i;
					$data->seats	 = $seat_count;
					$data->vgroup 	 = 0;	
					$data->$roomTypeShortcutField[$i] = 1;
					
					$code = $this->generateCode($reservation, $data);	
					$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
					$data->voucher_groups_id   	= $voucher_groups_id;
					$data->code = $code;
					foreach($share_room as $s){			
						if($s){								
							if(count($s)==$seat_count){
								$voucherResult[] = $this->generateIssueVoucherHotel( $data, false , $s );
							}					
						}				
					}	
									
					//return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;				
					return array('hotel_id'=>$reservation->hotel_id,'voucher_id'=>implode(",", $voucherResult),'voucher_groups_id'=>$voucher_groups_id,'blockdate'=>$reservation->blockdate,'reservationid'=>$reservation->id,'name_hotel'=>$reservation->name);
				}
			}	
			$error = 'Number Single/Double rooms not enough for '.$seat_count.' seats';
			$this->setError($error);				
		}
		
		if($seat_count == 3 && $calback_total_triple_rooms == 1)
		{		
			for($i=3;$i<=4;$i++)
			{
				if( $availableRooms[$i] > 0 )
				{
					$data->room_type = $i;
					$data->seats	 = $seat_count;
					$data->vgroup 	 = 0;	
					$data->$roomTypeShortcutField[$i] = 1;				
					
					$code = $this->generateCode($reservation, $data);	
					$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
					$data->voucher_groups_id   	= $voucher_groups_id;
					$data->code = $code;
					foreach($share_room as $s){			
						if($s){								
							if(count($s)==$seat_count){
								$voucherResult[] = $this->generateIssueVoucherHotel( $data, false , $s );
							}					
						}				
					}			
					//return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;
					return array('hotel_id'=>$reservation->hotel_id,'voucher_id'=>implode(",", $voucherResult),'voucher_groups_id'=>$voucher_groups_id,'blockdate'=>$reservation->blockdate,'reservationid'=>$reservation->id,'name_hotel'=>$reservation->name);
				}
			}	
			$error = 'Number Triple rooms not enough for '.$seat_count.' seats';
			$this->setError($error);			
		}
		if($seat_count == 4 && $calback_total_quad_rooms == 1 )
		{	
			if( $availableRooms[4] > 0 )
			{
				$data->room_type = 4;
				$data->seats	 = $seat_count;
				$data->vgroup 	 = 0;	
				$data->$roomTypeShortcutField[4] = 1;	
				$code = $this->generateCode($reservation, $data);	
				$voucher_groups_id = $this->InsertVoucherGroups($code, $seat_count);
				$data->voucher_groups_id   	= $voucher_groups_id;
				$data->code = $code;
				foreach($share_room as $s){			
						if($s){								
							if(count($s)==$seat_count){
								$voucherResult[] = $this->generateIssueVoucherHotel( $data, false , $s );
							}					
						}				
					}	
													
				//return '&voucher_id='.$voucherResult . '&voucher_groups_id=' . $voucher_groups_id;	
				return array('hotel_id'=>$reservation->hotel_id,'voucher_id'=>implode(",", $voucherResult),'voucher_groups_id'=>$voucher_groups_id,'blockdate'=>$reservation->blockdate,'reservationid'=>$reservation->id,'name_hotel'=>$reservation->name);
			}				
		}
		
		//process group voucher
		if( $seat_count >= 4 && $seatLimit >= $seat_count || $calback_total_single_rooms > 1 || $calback_total_double_rooms > 1 || $calback_total_triple_rooms > 1 && $calback_total_quad_rooms > 1)
		{			
			///$data->seats	 = $seat_count;
			$data->vgroup 	 = 1;
			///$data->room_type = 5;
			
			//$total_single_rooms = JRequest::getInt('total_single_rooms',0);
			//$total_double_rooms = JRequest::getInt('total_double_rooms',0);
			//$total_triple_rooms = JRequest::getInt('total_triple_rooms',0);
			//$total_quad_rooms   = JRequest::getInt('total_quad_rooms',0);
			

			$total_single_rooms = $calback_total_single_rooms;
			
			$total_double_rooms = $calback_total_double_rooms;
			
			$total_triple_rooms = $calback_total_triple_rooms;
			
			$total_quad_rooms   = $calback_total_quad_rooms;
			
			$Total_seat_count = (($total_single_rooms*1) + ($total_double_rooms*2) + ($total_triple_rooms * 3) + ($total_quad_rooms*4));
			$code = $this->generateCode($reservation, $data);	
			$voucher_groups_id = $this->InsertVoucherGroups($code, $Total_seat_count);
			$data->voucher_groups_id   	= $voucher_groups_id;
			// $data->code = $code; //truong hop Group nen khg add mac dinh cung code
			
			$ok = true;
			
			if( $total_single_rooms > $availableRooms[1] )
			{
				$ok = false;
			}
			if( $total_double_rooms > $availableRooms[2] )
			{
				$ok = false;
			}
			if( $total_triple_rooms > $availableRooms[3] )
			{
				$ok = false;
			}
			if( $total_quad_rooms > $availableRooms[4] )
			{
				$ok = false;
			}
			$voucherResultArr = array();
			if($ok)
			{
				$isGroupTransport = false;
				if( isset($reservations[$reservationId]['isGroupTransport']) && (int)$reservations[$reservationId]['isGroupTransport'] == 1 )
				{
					$isGroupTransport = true;					
					
				}
				//Case total_single_rooms 
				if ( $total_single_rooms >= 1 ) {
					//$flag=1;
					//echo count($share_room);
					//print_r($share_room);die();
					//for( $i = 0; $i<$total_single_rooms; $i++ ){
					for( $i = 1; $i<=count($share_room); $i++ ){
							if(count($share_room[$i])==1){
								$data->sroom  = 1;
								$data->sdroom  = 0;						
								$data->troom  = 0;
								$data->qroom  = 0;
								$data->seats	 = 1;
								$data->code = "";
								$data->room_type = 1;
								$voucherResult[] = $this->generateIssueVoucherHotel( $data, false , $share_room[$i] );							
							}							
										
						//$voucherResultArr[] = $this->generateIssueVoucherHotel( $data, $isGroupTransport, $share_room[$data->room_type] );
					}
				}
				
				if ( $total_double_rooms >= 1 ) {
					
					//for( $i = 0; $i<$total_double_rooms; $i++ ){
					for( $i = 1; $i<=count($share_room); $i++ ){
						if(count($share_room[$i])==2){
								$data->sroom  = 0;
								$data->sdroom  = 1;						
								$data->troom  = 0;
								$data->qroom  = 0;
								$data->seats	 = 2;
								$data->code = "";
								$data->room_type = 2;
								$voucherResultArr[] = $this->generateIssueVoucherHotel( $data, $isGroupTransport , $share_room[$i]);
							}elseif(count($share_room[$i])==1){
								if($total_single_rooms==0){
									$data->sroom  = 0;
									$data->sdroom  = 1;						
									$data->troom  = 0;
									$data->qroom  = 0;
									$data->seats  = 2;
									$data->code = "";
									$data->room_type = 2;
									$voucherResultArr[] = $this->generateIssueVoucherHotel( $data, $isGroupTransport , $share_room[$i]);
								}
							}	
					}
				}
				
				if ( $total_triple_rooms >= 1 ) {					
					//for( $i = 0; $i<$total_triple_rooms; $i++ ){
					for( $i = 1; $i<=count($share_room); $i++ ){
						if(count($share_room[$i])==3){
								$data->sroom  = 0;
								$data->sdroom  = 0;						
								$data->troom  = 1;
								$data->qroom  = 0;
								$data->seats	 = 3;
								$data->code = "";
								$data->room_type = 3;
						//for($f=$flag;$flag<=count($share_room);$f++){					
								$voucherResultArr[] = $this->generateIssueVoucherHotel( $data, $isGroupTransport , $share_room[$i]);
								
							}							
						//}	
						//$voucherResultArr[] = $this->generateIssueVoucherHotel( $data, $isGroupTransport , $share_room[$data->room_type]);
					}
				}
				
				if ( $total_quad_rooms >= 1 ) {					
					for( $i = 1; $i<=count($share_room); $i++ ){
							if(count($share_room[$i])==4){
								$data->sroom  = 0;
								$data->sdroom  = 0;
								$data->troom  = 0;
								$data->qroom  = 1;						
								$data->seats	 = 4;
								$data->code = "";
								$data->room_type = 4;
								$voucherResultArr[] = $this->generateIssueVoucherHotel( $data, $isGroupTransport , $share_room[$i]);
							}		
					}
				}
				
				/*$data->sdroom = (int)$total_double_rooms;
				$data->troom  = (int)$total_triple_rooms;
				$data->sroom  = (int)$total_single_rooms;
				$data->qroom  = (int)$total_quad_rooms;
				
				if( isset($reservations[$reservationId]['isGroupTransport']) && (int)$reservations[$reservationId]['isGroupTransport'] == 1 )
				{
					$voucherResult = $this->generateIssueVoucherHotel( $data, true );					
				} else {
					$voucherResult = $this->generateIssueVoucherHotel( $data );	
				}
					
									
				return $voucherResult;*/
				
				//return '&voucher_id='.implode(",", $voucherResultArr ) . '&voucher_groups_id=' . $voucher_groups_id;
				return array('hotel_id'=>$reservation->hotel_id,'voucher_id'=>implode(",", $voucherResultArr),'voucher_groups_id'=>$voucher_groups_id,'blockdate'=>$reservation->blockdate,'reservationid'=>$reservation->id,'name_hotel'=>$reservation->name);
			} else {
				$error = 'Number rooms not enough for '.$seat_count.' seats';
				$this->setError($error);
			}	
		} else {
			$error = 'Number rooms not enough for '.$seat_count.' seats';
			$this->setError($error);
		}
					
		return $voucherResult;
	}	
	private function generateIssueVoucherHotel( $data, $groupTransportInclude = false,$share_room = '' )
	{
		$session	 = JFactory::getSession();
		$airline 	 = SFactory::getAirline();
		$reservation = SReservation::getInstance($data->booking_id);
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		
		if( (int)$reservation->association_id > 0 ) {
			$association = SFactory::getAssociation($reservation->association_id);						
			$hotel 		 = new SHotel($reservation->hotel_id,$association->db);	
		} else {
			$hotel 		 = SFactory::getHotel($reservation->hotel_id);	
		}

		$db = $this->getDbo();
		
		//$query = 'SELECT flight_code, delay_code FROM #__sfs_flights_seats WHERE id='.$data->flight_id;
		//$db->setQuery($query);
		$flightSeat 	= $db->loadObject();
		
		//$flight_code 	= $flightSeat->flight_code;	
		//$delay_code		= $flightSeat->delay_code;	
	
		$voucherNumber = '';
				
		if( empty($data->code) ) 
		{
			//need to check if the voucher existed
			/*while(true) 
			{												
				$tmpVoucher = SfsHelper::createRandomString(2);			
	
				$numberPerson = $data->room_type;
				
				if( (int)$data->vgroup == 1)
				{
					$numberPerson = 9;
					if( $data->sroom > 0 && $data->sdroom == 0 && $data->troom == 0  && $data->qroom == 0 ) {
						$numberPerson = 1;
					}
					if( $data->sdroom > 0 && $data->sroom == 0 && $data->troom == 0  && $data->qroom == 0 ) {
						$numberPerson = 2;
					}
					if( $data->troom > 0 && $data->sdroom == 0 && $data->sroom == 0  && $data->qroom == 0 ) {
						$numberPerson = 3;
					}
					if( $data->qroom > 0 && $data->sdroom == 0 && $data->troom == 0  && $data->sroom == 0 ) {
						$numberPerson = 4;
					}				
				} 
				
				$tmpVoucher = JString::strtoupper($tmpVoucher.$numberPerson);

				$query  = 'SELECT COUNT(*) FROM #__sfs_voucher_codes WHERE code ='.$db->quote($tmpVoucher);
				$query .= ' AND booking_id='.(int)$reservation->id;
				$db->setQuery($query);
				
				if( ! $db->loadResult() ) {
					$voucherNumber = $tmpVoucher;
					break;
				}
				
			}	*/
			$voucherNumber = $this->generateCode($reservation, $data);	
			
			$data->code = $voucherNumber;	
		}	
		
		$taxiId			 = 0;
		$taxiVoucherRate = 0;
		$fare_type 		 = '';
		$isReturn		 = 0;
		
		if( (int) $data->taxi_id > 0 )
		{
			if( (int)$data->vgroup == 0) {
				$taxiId 		 = $data->taxi_id;	
				$taxiVoucherRate = $data->rate;
				$fare_type		 = $data->fare_type;	
				$isReturn		 = $data->is_return;	
			}
			else {
				$session->set('isTaxiBooked',1);
			}
		}		
		unset($data->taxi_id);
		unset($data->rate);
		unset($data->fare_type);
		unset($data->is_return);
		
		//insert vourcher
		if( !$db->insertObject('#__sfs_voucher_codes',$data) ) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$voucherId = $db->insertid();
		
		if($share_room){
				foreach($share_room as $pass_id){
					$query  = 'UPDATE #__sfs_trace_passengers SET ';
					$query .= 'voucher_id='.$voucherId;
					$query .= ', room_type='.$data->room_type;
					$query .= ' WHERE id='.$pass_id;	
					$db->setQuery($query);
					$db->query();
					$this->updatePassengersAirplus($pass_id,$voucherId);

				}
			}		
		if($groupTransportInclude == true)
		{
			$session->set('groupTransportInclude',$voucherId);
		}
		
		$query  = 'UPDATE #__sfs_reservations SET ';
		$query .= 'sd_room_issued=sd_room_issued+'.$data->sdroom;
		$query .= ',t_room_issued=t_room_issued+'.$data->troom;
		$query .= ',s_room_issued=s_room_issued+'.$data->sroom;
		$query .= ',q_room_issued=q_room_issued+'.$data->qroom;
		$query .= ' WHERE id='.$data->booking_id;			
		
		$db->setQuery($query);
		$db->query();
				
		if( $data->vgroup == 1 ) {	
			$code = self::getVoucherGroups( $data->voucher_groups_id )->code;
			$this->set('successMsg',JText::sprintf('COM_SFS_MATCH_GVOUCHER_MSG',$code));
		} else {								
			$this->set('successMsg',JText::sprintf('COM_SFS_MATCH_VOUCHER_MSG',$data->code));			
		}			

		//$db->setQuery('UPDATE #__sfs_flights_seats SET seats_issued=seats_issued+'.$data->seats.' WHERE id='.$data->flight_id);
		//$db->query();
		
		// Generate Taxi Voucher
		if( (int) $taxiId > 0 )
		{
			$taxiVoucher = new stdClass();
			$taxiVoucher->taxi_id		= (int)$taxiId;
			$taxiVoucher->airline_id	= (int)$airline->id;
			$taxiVoucher->booking_id	= $data->booking_id;
			$taxiVoucher->hotel_id		= $reservation->hotel_id;
			$taxiVoucher->block_date	= $reservation->room_date;
			$taxiVoucher->flight_number	= $flight_code;
			$taxiVoucher->deley_code 	= $delay_code;
			$taxiVoucher->rate 			= $taxiVoucherRate;
			$taxiVoucher->fare_type 	= $fare_type;
			$taxiVoucher->is_return 	= $isReturn;
			$taxiVoucher->booked_by 	= JFactory::getUser()->get('id');
			$taxiVoucher->booked_date 	= JFactory::getDate()->toSql();
			$taxiVoucher->requested_time = 0;
						
			$taxiVoucherCode = $voucherNumber;
			
			if($taxiVoucher->is_return){
				$taxiVoucherCode .= '-T2';
			} else {
				$taxiVoucherCode .= '-T1';
			}
			
			$taxiVoucher->code	= $taxiVoucherCode;
			
			while (true)
			{
				$reference_number = SfsHelperDate::getDate('now','dmy', $time_zone);
			
				$reference_number .= '-'.SfsHelper::createRandomString(2);

				if( (int)$airline->grouptype == 3 ) {
					$ghAirline = $airline->getSelectedAirline();
					$reference_number .= '-'.$ghAirline->code;
				} else {
					$reference_number .= '-'.$airline->code;	
				}
				
				$reference_number .= '-T'.$data->seats;
				
				$reference_number = JString::strtoupper($reference_number);	
				
				$query = 'SELECT COUNT(*) FROM #__sfs_taxi_vouchers WHERE reference_number='.$db->quote($reference_number);
				$db->setQuery($query);
				$count = $db->loadResult();
				if( ! $count )
				{
					$taxiVoucher->reference_number = $reference_number;
					break;	
				}
			}
			
			//insert taxi vourcher
			if( !$db->insertObject('#__sfs_taxi_vouchers',$taxiVoucher) ) 
			{				
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			$taxiVoucherId = $db->insertid();
			
			// insert voucher map
			$query = 'INSERT INTO #__sfs_airline_taxi_voucher_map(voucher_id,taxi_voucher_id) VALUES('.$voucherId.','.$taxiVoucherId.')';
			$db->setQuery($query);
			$db->query();
			
			$params = JComponentHelper::getParams('com_sfs');
			$sms_taxi_text  = $params->get('sms_taxi_text');
			$smsPhoneNumber = $params->get('sms_taxi_phone_number');		
			
			if($sms_taxi_text && $smsPhoneNumber) {
				$airportCode = $airline->airport_code;
				$airlineName = $airline->getAirlineName();				
				$sms_taxi_text = JString::str_ireplace('{airline}', $airportCode.', '.$airlineName, $sms_taxi_text);
				$sms_taxi_text = JString::str_ireplace('{departuretime}', 'asap', $sms_taxi_text);
				$sms_taxi_text = JString::str_ireplace('{numberpassenger}', $data->seats, $sms_taxi_text);
				$sms_taxi_text = JString::str_ireplace('{vouchernumber}', $taxiVoucher->code, $sms_taxi_text);
				
				
				$query = 'SELECT * FROM #__sfs_taxi_companies WHERE id='.$taxiVoucher->taxi_id;				
				$db->setQuery($query);
				$taxiCompany = $db->loadObject();
				
				if($taxiCompany)
				{
					$sms_taxi_text = JString::str_ireplace('{taxiname}',$taxiCompany->name, $sms_taxi_text);
					$sms_taxi_text = JString::str_ireplace('{taxiphone}','+'.$taxiCompany->telephone, $sms_taxi_text);	
					
					SEmail::SMS('taxi', $sms_taxi_text);
				}				
			}
			
		}

		// Email to hotel			
		$fbData = array();
		$fbData['mealplan']  = $data->mealplan;
		$fbData['lunch'] 	 = $data->lunch;
		$fbData['breakfast'] = $data->breakfast;
		
		$fbData['totalRooms'] = '0';
		$fbData['totalRooms'] = $this->_totalRooms;
		if( $fbData['totalRooms'] <= 1 ) {
			$fbData['totalRooms'] = $fbData['totalRooms'] . ' room';
		}
		elseif ( $fbData['totalRooms'] > 1 ) {
			$fbData['totalRooms'] = $fbData['totalRooms'] . ' rooms';
		}
		
		$fbData['totalSeats'] = $this->_totalSeats;
		if( $this->_totalSeats <= 1 ){
			$fbData['totalSeats'] = $fbData['totalSeats'] . ' passenger';
		}
		else {
			$fbData['totalSeats'] = $fbData['totalSeats'] . ' passenger(s)';
		}
		
		$this->_voucherEmail($hotel, $data->seats, $reservation, $data->code, $fbData);	
					
		return $voucherId;
	}
	function updatePassengersAirplus($pass_id,$voucherId){
		$db = $this->getDbo();
		$passenger = $this->getPassenger($pass_id);
		$query = $db->getQuery(true);
		$query  = 'UPDATE #__sfs_passengers_airplus SET ';
		$query .= 'voucher_id='.$voucherId;		
		$query .= ' WHERE id='.$passenger->airplus_id;	
		$db->setQuery($query);
		$db->query();
	}
	function getPassenger($id){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_trace_passengers AS a');
		$query->where('a.id = '.$id);
		$db->setQuery($query);
		return $db->loadObject();
	}
	public function getReservationsOld($blockdate='',$reservationid){
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;

		// For ground hander: do not nothing if user does not select an airline
		if( (int) $airline->grouptype==3 ) {
			if( (int) $airline->iatacode_id <= 0 ) {
				return null;
			}
		}		
		$db = $this->getDbo();
				
		if( $this->_reservations == null )
		{
			if($blockdate){
				$dateNight = $blockdate;
			}
			//$dateNight = $this->getNightDate();
			$query = $db->getQuery(true);
			
			/*NEW*/
			$query->select('a.*, h.name,h.address, h.city');
			$query->from('#__sfs_reservations AS a');
			$query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
//			if($airline->grouptype==3) {
//				$query->innerJoin('#__sfs_gh_reservations AS d ON d.reservation_id=a.id AND d.airline_id='.(int)$airline->iatacode_id);
//			}

            if((int)$airport_current_id != -1)
            {
                $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
            }
				
			$query->where('a.airline_id = '.$airline->id);
			
			$query->where('a.blockdate = '.$db->quote($dateNight));
			
			#$query->where('a.status <> '.$db->quote('A') );
			$query->where('a.status <> '.$db->quote('R') );
			$query->where('a.status <> '.$db->quote('D') );
			if($reservationid){
				$query->where('a.id = '.$reservationid );
			}
			
			$query->order('a.blockdate DESC,a.booked_date DESC');
				
			$db->setQuery($query);		
							
			$reservations = $db->loadObjectList('id');
			if( $db->getErrorNum() ) {
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			if( count($reservations) == 0 ) {
				return $this->_reservations;
			}

			$localReservIds = array();
			foreach ($reservations as & $reservation)
			{									
				if( (int) $reservation->association_id == 0)
				{
					$db = JFactory::getDbo();
				} else {
					$association = SFactory::getAssociation($reservation->association_id);	
					$db = $association->db;
				}					
				
				$query = $db->getQuery(true);		
							
				$query->select('a.name,a.star,a.address, a.city');
				$query->from('#__sfs_hotel AS a');
				$query->select('m.stop_selling_time, m.bf_service_hour, m.bf_opentime, m.bf_closetime,m.service_hour, m.service_opentime, m.service_closetime, m.lunch_service_hour, m.lunch_opentime, m.lunch_closetime');
				$query->innerJoin('#__sfs_hotel_mealplans AS m ON m.hotel_id=a.id');
		
				$query->select('tp.transport_available, tp.transport_complementary, tp.operating_hour, tp.operating_opentime, tp.operating_closetime, tp.frequency_service, tp.pickup_details');
				$query->leftJoin('#__sfs_hotel_transports AS tp ON tp.hotel_id=a.id');

				
				if( $airline->allowTaxiVoucher() ) {
					$query->select('hbp.ring AS hotel_ring');
					$query->leftJoin('#__sfs_hotel_backend_params AS hbp ON hbp.hotel_id=a.id');
				}
				
				$query->where('a.id = '.$reservation->hotel_id);
				$db->setQuery($query);
				$hotelInfo = $db->loadObject(); 
				
				if( $hotelInfo )
				{
					$vars = get_object_vars($hotelInfo);		
					foreach ($vars as $key => $value) {
						$reservation->$key = $value;				
					}
				}										
				
			}

			$this->_reservations = $reservations;			
		}
		return $this->_reservations;
	}
	//End Minh Tran
	
}

