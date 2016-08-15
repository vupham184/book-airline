<?php
// No direct access
defined('_JEXEC') or die;

class SfsModelBooking extends JModelLegacy
{

	protected $_reservation = null;
	protected $_hotel		= null;

	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}

	/**
	 * Initialize Booking
	 *
	 */
	public function initialize()
	{
		$user 	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		$airline = SFactory::getAirline();

		// Load state from the request.
		$value = JRequest::getInt('association_id');
		$this->setState('booking.association_id', $value);

		$value = JRequest::getInt('room_id');
		$this->setState('booking.room_id', $value);

		$value = JRequest::getInt('hotel_id');
		$this->setState('booking.hotel_id', $value);

		$value = JRequest::getInt('sd_room',0);
		$this->setState('booking.sdroom', $value);

		$value = JRequest::getInt('t_room',0);
		$this->setState('booking.troom', $value);

		$value = JRequest::getInt('s_room',0);
		$this->setState('booking.sroom', $value);

		$value = JRequest::getInt('q_room',0);
		$this->setState('booking.qroom', $value);

		$value	= JRequest::getVar('breakfast');
		$this->setState('booking.breakfast', $value);

		$value	= JRequest::getVar('lunch');
		$this->setState('booking.lunch', $value);

		$value 	= JRequest::getVar('mealplan');
		$this->setState('booking.dinner', $value);

		$value 	= JRequest::getVar('course');
		$this->setState('booking.course', $value);

		$value = JRequest::getInt('percent_release_policy');
		$this->setState('booking.percent_release_policy', $value);


		$value = JRequest::getVar('rooms');
		$this->setState('filter.rooms', $value);

		$value = JRequest::getVar('date_start');
		$this->setState('filter.date_start', $value);

		$value = JRequest::getVar('date_end');
		$this->setState('filter.date_end', $value);

		$roomsString = JRequest::getVar('ws_rooms');
		$rooms = unserialize($roomsString);
		$wsPreBooking = JRequest::getVar('ws_prebooking');
		$isWS = !empty($rooms);
		$wsRoomTypes = array();
		$wsNumRooms = 0;
		$this->setState('is_ws', $isWS);
		if($isWS) {
			foreach($rooms as $r) {
				$roomType = Ws_Do_Search_RoomTypeResult::fromString($r['roomType']);
				$wsRoomTypes[] = $roomType;
				$wsNumRooms += $roomType->NumberOfRooms;
			}
			$this->setState('ws_num_room', $wsNumRooms);
			$this->setState('ws_room_types', $wsRoomTypes);
			$this->setState('ws_pre_booking', Ws_Do_PreBook_Response::fromString($wsPreBooking));
			$this->setState('ws_room_type_string', $roomsString);
			$this->setState('ws_pre_booking_string', $wsPreBooking);
		}

		$sdRoom = (int) $this->getState('booking.sdroom');
		$tRoom  = (int) $this->getState('booking.troom');
		$sRoom  = (int) $this->getState('booking.sroom');
		$qRoom  = (int) $this->getState('booking.qroom');

		if ( $sdRoom == 0 && $tRoom == 0 && $sRoom == 0 && $qRoom == 0 && !$isWS) {
			$this->setError('Invalid data');
			return false;
		}

		$associationId	= (int) $this->getState('booking.association_id');
		$inventoryId	= (int) $this->getState('booking.room_id');
		$hotelId		= (int) $this->getState('booking.hotel_id');

		// Store booking data
		$this->_reservation = new stdClass();

		$this->_reservation->association_id	=  $associationId;
		$this->_reservation->airline_id     =  $airline->id;
		$this->_reservation->booked_by		=  $user->id;
		$this->_reservation->room_id	  	=  $inventoryId;
		$this->_reservation->hotel_id		=  $hotelId;
		$this->_reservation->booked_date	=  $date->toSql();
		$this->_reservation->sd_room 	 	=  $sdRoom;
		$this->_reservation->t_room 	 	=  $tRoom;
		$this->_reservation->s_room 	 	=  $sRoom;
		$this->_reservation->q_room 	 	=  $qRoom;

		$session = JFactory::getSession();
		$payment_type = $session->get('payment_type');
		if( $payment_type )
		{
			$this->_reservation->payment_type =  $payment_type;
		}

		return true;
	}

	/**
	 * Process Booking
	 *
	 * @throws Exception
	 */
	public function process()
	{
		// Initialise some variables
		$user 	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		$airline = SFactory::getAirline();

		try {
			$associationId	= (int) $this->getState('booking.association_id');
			$inventoryId	= (int) $this->getState('booking.room_id');
			$hotelId		= (int) $this->getState('booking.hotel_id');

			// Gets inventory data
			$inventory  	= $this->getInventory($inventoryId, $hotelId,$associationId);

			if( ! $inventory )
			{
				throw new Exception('Booking Error: '.$this->getError());
			}
            $sRoom    = (int) $this->getState('booking.sroom');
            $sdRoom   = (int) $this->getState('booking.sdroom');
			$tRoom    = (int) $this->getState('booking.troom');
			$qRoom    = (int) $this->getState('booking.qroom');

            if( $sRoom > 0 ) {
                if( $inventory->s_room_total < $sRoom ) {
                    $this->setError(JText::_('COM_SFS_BOOKING_SD_ROOM_NOT_ENOUGH'));
                    return false;
                }
            }
			if( $sdRoom > 0 ) {
				if( $inventory->sd_room_total < $sdRoom ) {
					$this->setError(JText::_('COM_SFS_BOOKING_SD_ROOM_NOT_ENOUGH'));
					return false;
				}
			}
			if( $tRoom > 0 ) {
				if( $inventory->t_room_total < $tRoom ) {
					$this->setError( JText::_('COM_SFS_BOOKING_T_ROOM_NOT_ENOUGH') );
					return false;
				}
			}
			if( $qRoom > 0 ) {
				if( $inventory->q_room_total < $qRoom ) {
					$this->setError( JText::_('COM_SFS_BOOKING_T_ROOM_NOT_ENOUGH') );
					return false;
				}
			}

			/*
			 * Save Reservation
			 *
			 */
			if( !$this->saveReservation($airline, $inventory) )
			{
				return false;
			}

			$reservation = $this->getReservation();

			if( (int) $reservation->id ==0 )
			{
				$this->setError('Booking failed!!! Contact SFS Administrator for more information');
				return false;
			}

			$session = JFactory::getSession();
			$session->clear('payment_type');

			/*
			 * Update Hotel Inventory
			 */
			$query  = 'UPDATE #__sfs_room_inventory SET';
            $query .= ' s_room_total=s_room_total-'.(int) $reservation->s_room;
			$query .= ',sd_room_total=sd_room_total-'.(int) $reservation->sd_room;
			$query .= ',t_room_total=t_room_total-'.(int) $reservation->t_room;
			$query .= ',q_room_total=q_room_total-'.(int) $reservation->q_room;
            $query .= ',booked_sroom=booked_sroom+'.(int)$reservation->s_room;
			$query .= ',booked_sdroom=booked_sdroom+'.(int)$reservation->sd_room;
			$query .= ',booked_troom=booked_troom+'.(int)$reservation->t_room;
			$query .= ',booked_qroom=booked_qroom+'.(int)$reservation->q_room;
			$query .= ' WHERE id='.$reservation->room_id;

			if( $associationId==0 )
			{
				$db	= $this->getDbo();
			} else {
				// Gets external database
				$association = SFactory::getAssociation($associationId);
				$db	= $association->db;
			}

			$db->setQuery($query);

			if( !$db->query() ) {
				throw new Exception( JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg()) );
			}

			return true;
		}
		catch (JException $e)
		{
			if ($e->getCode() == 404) {
				// Need to go thru the error handler to allow Redirect to work.
				JError::raiseError(404, $e->getMessage());
			}
			else {
				$this->setError($e);
				return false;
			}
		}
	}

	protected function saveReservation($airline, $inventory)
	{		
		// Make sure that the external airport is available
		$association = null;
		if( $inventory->association_id > 0 )
		{
			$association = SFactory::getAssociation($inventory->association_id);
			$query = 'SELECT * FROM #__sfs_airport_associations WHERE code='.$association->db->quote($airline->airport_code);
			$association->db->setQuery($query);
			$airportAssociation = $association->db->loadObject();
			if( !$airportAssociation )
			{
				$this->setError('External airport was not available.');
				return false;
			}
		}

		$db			 = $this->getDbo();
        $start_date  = $this->getState('filter.date_start');
		$blockcode   = $this->generateBlockcode($airline, $inventory, $start_date);

		if ( ! $blockcode )
		{
			throw new Exception('Booking Failed: Contact Administrator for more details please!!!');
		}

		$this->setReservationProperty('blockcode', $blockcode);
		$this->setReservationProperty('blockdate', $inventory->date);

        // Airport code
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $this->setReservationProperty('airport_code', $airline_current->code);



		# WS special
		if($this->getState('is_ws')) {
			$this->setReservationProperty('ws_room' , $this->getState('ws_num_room'));
			$this->setReservationProperty('ws_room_type', $this->getState('ws_room_type_string'));
			$this->setReservationProperty('ws_prebooking', $this->getState('ws_pre_booking_string'));
			// Saving the rate
			$roomsString = JRequest::getVar('ws_rooms');
			$rooms = unserialize($roomsString);
			$sRate = 0;$sdRate = 0;$tRate = 0;$qRate = 0;
			$ws_sRate = 0;$ws_sdRate = 0;$ws_tRate = 0;$ws_qRate = 0;
			foreach($rooms as $r) {
				$roomType = Ws_Do_Search_RoomTypeResult::fromString($r['roomType']);
				switch($roomType->NumAdultsPerRoom)
				{
					case 1:
						$sRate     += $roomType->Total;
						$ws_sRate  += $roomType->OriginalTotal;
						break;
					case 2:
						$sdRate    += $roomType->Total;
						$ws_sdRate += $roomType->OriginalTotal;
						break;
					case 3:
						$tRate     += $roomType->Total;
						$ws_tRate  += $roomType->OriginalTotal;
						break;
					default:
						$qRate     += $roomType->Total;
						$ws_qRate  += $roomType->OriginalTotal;
				}
			}
            $this->setReservationProperty('ws_s_rate' , $ws_sRate);
			$this->setReservationProperty('ws_sd_rate', $ws_sdRate);
			$this->setReservationProperty('ws_t_rate' , $ws_tRate);
			$this->setReservationProperty('ws_q_rate' , $ws_qRate);
		}
		else{
			// Saving the rate
            $sRate 		= $inventory->s_rate;
			$sdRate 	= $inventory->sd_rate;
			$tRate 		= $inventory->t_rate;
			$qRate 		= $inventory->q_rate;

			$query 		= $db->getQuery(true);

			// Make sure the contracted rate avaiable or not
			// Just used the contacted rates on local
			if( $inventory->association_id == 0 )
			{
				$query->select('a.sd_rate,a.t_rate,a.s_rate,a.q_rate');
				$query->from('#__sfs_contractedrates AS a');

				$query->select('b.hotel_id AS ex_hotel_id');
				$query->leftJoin('#__sfs_contractedrates_exclusions AS b ON b.airline_id=a.airline_id AND b.hotel_id=a.hotel_id AND b.date=a.date');

				$query->where('a.airline_id='.$airline->id);
				$query->where('a.hotel_id='.$this->_reservation->hotel_id);
				$query->where('a.date='. $db->quote($inventory->date));

				$db->setQuery($query);

				$contactedRate = $db->loadObject();

				if( $contactedRate && empty($contactedRate->ex_hotel_id) )
				{
					if( floatval($contactedRate->sd_rate) > 0 )
					{
						$sdRate = floatval($contactedRate->sd_rate);
					}
					if( floatval($contactedRate->t_rate) > 0 )
					{
						$tRate = floatval($contactedRate->t_rate);
					}
					if( floatval($contactedRate->s_rate) > 0 )
					{
						$sRate = floatval($contactedRate->s_rate);
					}
					if( floatval($contactedRate->q_rate) > 0 )
					{
						$qRate = floatval($contactedRate->q_rate);
					}
				}
			}
		}
        $this->setReservationProperty('s_rate' , $sRate);
		$this->setReservationProperty('sd_rate', $sdRate);
		$this->setReservationProperty('t_rate' , $tRate);
		$this->setReservationProperty('q_rate' , $qRate);



		if($inventory->association_id == 0)
		{
			$this->setReservationProperty('transport' , $inventory->transport_included);
		}



		$breakfast = $this->getState('booking.breakfast',0);
		if($breakfast) {
			$this->setReservationProperty('breakfast' , $inventory->bf_layover_price);
		}

		$lunch = $this->getState('booking.lunch',0);
		if($lunch) {
			$this->setReservationProperty('lunch' , $inventory->lunch_standard_price);
		}

		$dinner = $this->getState('booking.dinner',0);
		$course = $this->getState('booking.course',0);

		if( ! empty($dinner) ) {

			$this->setReservationProperty('course_type' , (int)$course);

			switch ( (int)$course ) {
				case 1:
					$this->setReservationProperty('mealplan' , $inventory->course_1);
					break;
				case 2:
					$this->setReservationProperty('mealplan' , $inventory->course_2);
					break;
				case 3:
					$this->setReservationProperty('mealplan' , $inventory->course_3);
					break;
				default:
					break;
			}

		}

		$percent_release_policy = $this->getState('booking.percent_release_policy');

		if($percent_release_policy)
		{
			$this->setReservationProperty('percent_release_policy' , $percent_release_policy);
		}
        if($this->getState('is_ws')) {
            $this->setReservationProperty('status' , 'A');
            $session = JFactory::getSession();
            $reservation = new SReservation();
            $this->_reservation->id = -1;
            $reservation->loadData($this->_reservation);
            $session->set("reservation_temp", serialize($reservation));

        }
        else{
            // Make block state Open
            $this->setReservationProperty('status' , 'O');
            if ( ! $db->insertObject('#__sfs_reservations', $this->_reservation ) )
            {
                $error =  JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg());
                $this->setError($error);
                return false;
            }

            $this->_reservation->id = $db->insertid();

            /*
			 * Send mails
			 */
            if( ! ($result = $this->reservationNotification()) )
            {
                return false;
            }
        }

		$user = JFactory::getUser();
		if( SFSAccess::check($user, 'gh') )
		{
			if( (int)$airline->iatacode_id ) {
				$query = 'INSERT INTO #__sfs_gh_reservations(airline_id,reservation_id) VALUES('.(int)$airline->iatacode_id.','.$this->_reservation->id.')';
				$db->setQuery($query);

				if( ! $db->query() ) {
					$error =  'GH: '.JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg());
					$this->setError($error);
					return false;
				}

			}
		}

		$this->setReservationProperty('date' , $inventory->date);


		if($association)
		{
			$row = new stdClass();
			$row->reservation_id = $this->_reservation->id;
			$row->blockcode	 	 = $blockcode;
			$row->blockdate	 	 = $inventory->date;
			$row->airport		 = $airline->airport_code;
			$row->hotel_id		 = $this->_reservation->hotel_id;
			$row->status		 = 'O';

			$row->s_room		 = $this->_reservation->s_room;
			$row->sd_room		 = $this->_reservation->sd_room;
			$row->t_room		 = $this->_reservation->t_room;
			$row->q_room		 = $this->_reservation->q_room;


			$row->s_rate		 = $this->_reservation->s_rate;
			$row->sd_rate		 = $this->_reservation->sd_rate;
			$row->t_rate		 = $this->_reservation->t_rate;
			$row->q_rate		 = $this->_reservation->q_rate;

			if( ! $association->db->insertObject('#__sfs_reservation_airport_map',$row,'reservation_id') ) {
				$this->setError($association->db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	protected function generateBlockcode( $airline, $inventory, $date_start)
	{
		$db			 = $this->getDbo();
		$blockcode 	 = '';

		// If Airline
		if ( $airline->grouptype == 2 ) {
			$blockcode  =  SReservation::generateBlockCode($airline->code, $inventory->hotel_name, $date_start) ;
		}

		// If Ground handler
		if($airline->grouptype == 3){

			if( (int)$airline->iatacode_id ) {

				$db->setQuery('SELECT code FROM #__sfs_iatacodes WHERE id='.$airline->iatacode_id);
				$airlineCode = $db->loadResult();

				if($airlineCode){
					$blockcode  =  SReservation::generateBlockCode($airlineCode, $inventory->hotel_name,$date_start) ;
				}

			} else {
				return false;
			}
		}

		return $blockcode;
	}

	protected function getInventory($inventoryId, $hotelId,$associationId)
	{
		$db = null;
		if( $associationId==0 )
		{
			$db	= $this->getDbo();
		} else {
			// Gets external database
			$association = SFactory::getAssociation($associationId);
			if($association)
			{
				$db	= $association->db;
			} else {
				$this->setError('Sql Error or Inventory was not found');
				return false;
			}
		}

		$query = $db->getQuery(true);

		$query->select('a.date, a.transport_included, a.sd_room_total, a.t_room_total, a.sd_room_rate_modified AS sd_rate, a.t_room_rate_modified AS t_rate');
		$query->select('a.s_room_total, a.q_room_total, a.s_room_rate_modified AS s_rate, a.q_room_rate_modified AS q_rate');
		$query->from('#__sfs_room_inventory AS a');

		$query->select('c.name AS hotel_name');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=a.hotel_id');

		$query->select('d.course_1,d.course_2,d.course_3,d.bf_standard_price,d.bf_layover_price,d.lunch_standard_price');
		$query->leftJoin('#__sfs_hotel_mealplans AS d ON d.hotel_id=a.hotel_id');

		$query->where('a.id = '.(int)$inventoryId);
		$query->where('a.hotel_id = '.(int)$hotelId);

		$db->setQuery($query);

		$inventory = $db->loadObject();

		if ($error = $db->getErrorMsg()) {
			$this->setError('Sql Error or Inventory was not found');
			return false;
		}

		$inventory->association_id = $associationId;

		$query->clear();
		$query->select('a.*');
		$query->from('#__sfs_hotel_transports AS a');
		$query->where('a.hotel_id='. $hotelId );

		$db->setQuery($query);

		$hotelTransport = $db->loadObject();

		if( isset($hotelTransport) && (int)$hotelTransport->transport_available == 0 )
		{
			$inventory->transport_included = 0;
		}
		if( empty($hotelTransport) )
		{
			$inventory->transport_included = 0;
		}
		
		//lchung
		$data_ContractedRates = $this->getContractedRates();
		$breakfast = floatval( $data_ContractedRates[$hotelId]->breakfast );
		$lunch = floatval( $data_ContractedRates[$hotelId]->lunch );
		$dinner = floatval( $data_ContractedRates[$hotelId]->dinner );
		if ( $breakfast > 0 ) {
			$inventory->bf_layover_price = $breakfast;
		}
		if ( $lunch > 0 ) {
			$inventory->lunch_standard_price = $lunch;
		}
		if ( $dinner > 0 ) {
			$inventory->course_1 = $dinner;
			$inventory->course_2 = $dinner*2;
			$inventory->course_3 = $dinner*3;
		}
		//End lchung
		
		return $inventory;
	}
	
	//lchung
	public function getContractedRates()
	{
		$user	 = JFactory::getUser();
		$db 	 = $this->getDbo();
		$airline = SFactory::getAirline();

		$query =  $db->getQuery(true);

		$date_start = date("Y-m-d");

		$query->select('a.hotel_id,a.sd_rate,a.t_rate,a.s_rate,a.q_rate,a.breakfast,a.lunch,a.dinner');
		$query->from('#__sfs_contractedrates AS a');

		$query->select('b.hotel_id AS ex_hotel_id');
		$query->leftJoin('#__sfs_contractedrates_exclusions AS b ON b.airline_id=a.airline_id AND b.hotel_id=a.hotel_id AND b.date=a.date');



		$query->where('a.airline_id='.$airline->id);
		$query->where('a.date='. $db->quote($date_start));

		$db->setQuery($query);

		$rows = $db->loadObjectList('hotel_id');

		if( count($rows) )
		{
			return $rows;
		}

		return false;
	}
	//End lchung


	protected function setReservationProperty( $property, $value = null )
	{
		if($property)
		{
			$this->_reservation->$property = $value ;
		}

		return $value;
	}

	public function getReservation()
	{
		return $this->_reservation;
	}

	public function getHotel()
	{
		$reservation = $this->getReservation();
		if( $reservation && $this->_hotel == null )
		{
			if( $reservation->association_id > 0 )
			{
				$association  = SFactory::getAssociation($reservation->association_id);
				$this->_hotel = new SHotel($reservation->hotel_id,$association->db);
			} else {
				$this->_hotel = SFactory::getHotel($reservation->hotel_id);
			}
		}
		return $this->_hotel;
	}

	protected function reservationNotification()
	{
		
		$params = JComponentHelper::getParams('com_sfs');

		$mail_communication = (int)$params->get('mail_communication' , 1);
		$fax_communication  = (int)$params->get('fax_communication' , 1);
		
		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$mail_communication = $setup_airport['mail_communication'];
			$fax_communication = $setup_airport['fax_communication'];
		}
		//End lchung
		
		// if($mail_communication == 0 && $fax_communication==0)
		// {
		// 	return true;
		// }

		$user			= JFactory::getUser();
		$airline 		= SFactory::getAirline();
		$reservation 	= $this->getReservation();
		$hotel 		 	= $this->getHotel();

		if( $hotel && $reservation )
		{			
			$currency 			= $hotel->getTaxes()->currency_symbol;
			$blockcode 			= $reservation->blockcode;
			$arrival_date 		= $reservation->blockdate;

			$date_con = date_create($arrival_date);
			date_modify($date_con, '+1 day');
			$date_tomorrow = date_format($date_con, 'Y-m-d');

			$room_number 		= $reservation->sd_room + $reservation->t_room + $reservation->s_room + $reservation->q_room;
			$sdroom_number 		= $reservation->sd_room;
			$sdroom_rate 		= $currency.' '.$reservation->sd_rate;
			$troom_number 		= $reservation->t_room;
			$troom_rate 		= $currency.' '.$reservation->t_rate;
			$sroom_number 		= $reservation->s_room;
			$sroom_rate 		= $currency.' '.$reservation->s_rate;
			$qroom_number 		= $reservation->q_room;
			$qroom_rate 		= $currency.' '.$reservation->q_rate;

			$breakfast			= $reservation->breakfast;
			$breakfast_price 	= $currency.' '.$reservation->breakfast;
			$lunch				= $reservation->lunch;
			$lunch_price 		= $currency.' '.$reservation->lunch;
			$mealplan			= $reservation->mealplan;
			$dinner_price 		= $currency.' '.$reservation->mealplan;
			$course_menu		= $reservation->course_type.' course';

			$booked_contact 	= SFactory::getContact((int)$reservation->booked_by);
			$booked_name 		= $booked_contact->name.' '.$booked_contact->surname;
			$booked_title 		= $booked_contact->job_title;

			$airline_name				= $airline->name;
			$airline_contact_name 		= $booked_contact->name.' '.$booked_contact->surname;
			$airline_contact_title 		= $booked_contact->job_title;
			$airline_contact_telephone 	= $booked_contact->telephone;
			$airline_contact_email 		= $booked_contact->email;

            $data_ws = '';
            $is_ws = '';
            if ($this->getState('is_ws') == 1){
                $data_ws = $this->getState('ws_room_types');
                $data_ws = $data_ws[0];
            }
            $is_ws = $this->getState('is_ws') == 1 ? 'WS hotel' : 'Partner hotel';

			// hotel contacts
			$hotel_contacts = $hotel->getContacts();
            if(count($hotel_contacts))
            {
                ob_start();
                require_once JPATH_COMPONENT.'/libraries/emails/hotelblockconfirm.php';
                $bodyE = ob_get_clean();

                $hotelEmailBody = JString::str_ireplace('[^]', '', $bodyE);
                $hotelEmailBody = JString::str_ireplace('{date}', $arrival_date, $hotelEmailBody);
                
                $faxEmail = '';

                foreach ( $hotel_contacts as $hotelContact ) {
                    $hotelContactName = $hotelContact->name .' '.$hotelContact->surname;
                    $hotelEmailBody1 = JString::str_ireplace('{hotelcontact}', $hotelContactName, $hotelEmailBody);

                    if($hotelContact->is_admin) {
                        $faxEmail = JString::str_ireplace('{hotelcontact}', $hotelContactName, $bodyE);
                    }
                    if( !empty($hotelContact->systemEmails) )
                    {
                        $tmpRegistry = new JRegistry();
                        $tmpRegistry->loadString($hotelContact->systemEmails);

                        $sendBooking = (int)$tmpRegistry->get('booking', 0);

                        if($sendBooking == 1)
                        {
                            JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $hotelContact->email, 'SHORT TERM AIRPORT ROOM BLOCK RESERVATION  BY SFS-WEB', $hotelEmailBody1, true);                            
                        }
                    }
                }

                jimport('joomla.filesystem.file');

                // Email for Fax Service
                $faxNumber 	= trim(SfsHelper::formatPhone( $hotel->fax, 1)).trim(SfsHelper::formatPhone( $hotel->fax, 2));
                $faxAtt 	= JPATH_SITE.DS.'media'.DS.'sfs'.DS.'attachments'.DS.'faxblock'.$reservation->id.'.html';
                $bodyE 		= JString::str_ireplace('{date}', JHtml::_('date',JFactory::getDate(),"d-M-Y"), $faxEmail);
                JFile::write($faxAtt, $bodyE);

                $hotelBackendSetting = $hotel->getBackendSetting();

                if($fax_communication == 1) {
                    JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$faxNumber.'@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation '.$blockcode, $bodyE, true,null,null,$faxAtt);
                    if( $second_fax = $hotelBackendSetting->second_fax )
                    {
                        JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$second_fax.'@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation '.$blockcode, $bodyE, true,null,null,$faxAtt);
                    }
                }
            }

			if($mail_communication == 1)
			{
				// Email for Airline
				$hotel_name = $hotel->name;
				$airline_contacts = SFactory::getContacts($airline->grouptype,$airline->id);

				ob_start();
				require_once JPATH_COMPONENT.'/libraries/emails/airlineblockconfirm.php';
				$bodyE = ob_get_clean();
				
				JUtility::sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions', $user->email, 'Your SFS-web reservations details of '.$arrival_date.' booking '.$hotel->name.' '.$blockcode, $bodyE, true);

				// private email for all correspondance
				if( SFSAccess::check($user, 'gh') && is_array($airline->params) )
				{
					if( isset($airline->params['private_email']) ){
						jimport('joomla.mail.helper');
						$ghGeneralEmail = $airline->params['private_email'];

						if( JMailHelper::isEmailAddress($ghGeneralEmail)  )
						{
							ob_start();
							require_once JPATH_COMPONENT.'/libraries/emails/ghblock.php';
							$bodyE = ob_get_clean();
							JUtility::sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions', $ghGeneralEmail, 'A new block '.$blockcode.' made at SFS', $bodyE, true);
						}
					}
				}

				// Email for SFS Admins
				$adminEmails = $params->get('sfs_system_emails');
				//lchung
				$setup_airport = (array)JFactory::getSession()->get('setup_airport');
				if ( !empty( $setup_airport ) ) {
					$adminEmails = $setup_airport['system_smails'];
				}
				//End lchung

				if( strlen($adminEmails) )
				{
					$data = $reservation ;

					$adminSubject = ' New Blockcode: '.$room_number.' rooms '.$airline->airport_code.' Hotel '.$hotel_name.' '.$blockcode.' Created';

					$adminBlockcodeUrl = JURI::root().'administrator/index.php?option=com_sfs&view=reservation&id='.$reservation->id;
					$adminBlockcodeUrl = str_replace('https', ' http', $adminBlockcodeUrl);

					ob_start();
					require_once JPATH_COMPONENT.'/libraries/emails/admin/blockconfirm.php';
					$adminBody = ob_get_clean();


					$adminEmails = explode(';', $adminEmails);
					if( count($adminEmails) )
					{
						foreach ($adminEmails as $am) {
							JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', $am, $adminSubject, $adminBody, true);
						}
					}
				}

			}

			return true;
		}

		return false;
	}
	//minhtran
	public function CreateGroupPassenger($seat,$passenger_idArr){				
		try {
			
			$reservationId=$this->CreatGroupReservation();
			if($reservationId){							
				$id_voucher_groups=$this->CreateGroupMatch($reservationId,$seat);
			}			
			if($reservationId && $id_voucher_groups){
				foreach ( $passenger_idArr as $v ) {
                    if( $v != '' ) {
                    	$flight_number=$this->getFlightnumber($v);   

                    	$voucher_id= $this->createSingleVoucher($reservationId,$id_voucher_groups,$flight_number);
                    	if($voucher_id){
                    		$this->updateVoucherid($v,$voucher_id);
                    	}
                        die($v);
                    }
                }    
			}
		}catch (JException $e){
		}
	}
	function getFlightnumber($passenger_id){
		$db	= $this->getDbo();
		$query = "SELECT * FROM #__sfs_trace_passengers WHERE id='".$passenger_id."'";
		$db->setQuery($query);
		$result=$db->loadObject();
		if($result)
			return $result->flight_number;
		return '';
	}

	// update voucher id for trace passenger
	function updateVoucherid($passenger_id,$voucher_id){
		$db	= $this->getDbo();
		$query = "UPDATE #__sfs_trace_passengers
SET voucher_id=".$voucher_id." WHERE 	id=".$passenger_id;
		$db->setQuery($query);
		$db->query();
	}

	function createSingleVoucher($reservationId,$voucher_groups_id,$flight_number) {
		$user 	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		$airline = SFactory::getAirline();
		$db	= $this->getDbo();
		$voucherData = new stdClass();

        $voucherData->booking_id	= $reservationId;
        $voucherData->flight_id 	= $flight_number;
        $voucherData->created 		= $date->toSql();
        $voucherData->created_by 	= $user->id;          			
		$voucherData->voucher_groups_id   	= $voucher_groups_id;            
        $voucherData->vgroup 	= 0;
        //$voucherData->code = $code;     

        //insert vourcher
		if( !$db->insertObject('#__sfs_voucher_codes',$voucherData) ) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		return $db->insertid();
	}
	//create reservation for group
	function CreatGroupReservation(){		
		$user 	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		$airline = SFactory::getAirline();
		$db			 = $this->getDbo();
		// Airport code
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $this->setReservationProperty('airport_code', $airline_current->code);
        $this->setReservationProperty('status' , 'O');
        $this->_reservation->airline_id     =  $airline->id;
		$this->_reservation->booked_by		=  $user->id;
		$this->_reservation->booked_date	=  $date->toSql();		

        if ( ! $db->insertObject('#__sfs_reservations', $this->_reservation ) )
        {
            $error =  JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg());
            $this->setError($error);
            return false;
        }
        return $db->insertid();
	}
	//create voucher groups for group
	function CreateGroupMatch($reservationId,$seat){

		$airline = SFactory::getAirline();
		$user  	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		$db			 = $this->getDbo();
		//assign and generate vouchers for the passenger
		$data = new stdClass();		
		$data->seats 	= $seats;
		$data->handled_date 		= date('Y-m-d H:i:s');	
		if( !$db->insertObject('#__sfs_voucher_groups',$data) ) {

			$this->setError($db->getErrorMsg());
			return false;
		}		
		$Id = $db->insertid();
		return $Id;		
	}

	public function generateCode( $reservation, $numberPerson ){
		//need to check if the voucher existed
		$voucherNumber = '';
		$db = $this->getDbo();
		while(true) 
		{												
			$tmpVoucher = SfsHelper::createRandomString(2);		

			$numberPerson = 9;	

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
	//End minhtran
}


