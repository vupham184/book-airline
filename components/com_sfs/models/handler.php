<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelHandler extends JModel
{
	private $_booked_rooms = null;
	private $_airline = null;

	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		$value = JRequest::getInt('bookingid');
		$this->setState('bookingid',$value);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

	}

	/**
	 * Method to set the booking parameters
	 *
	 * @access	protected
	 */
	protected function setBooking()
	{
		// Load state from the request.
		$value = JRequest::getInt('hotel_id');
		$this->setState('booking.hotel_id', $value);

		$value = JRequest::getInt('room_id');
		$this->setState('booking.room_id', $value);

		$value = JRequest::getInt('sd_room',0);
		$this->setState('booking.sdroom', $value);

		$value = JRequest::getInt('t_room',0);
		$this->setState('booking.troom', $value);
	}

	/**
	 * Process the booking made by the airline
	 *
	 * @return	boolean true if success
	 * @since	1.0
	 */
	public function booking()
	{
		$db		 = $this->getDbo();
		$user 	 = JFactory::getUser();
		$date 	 = JFactory::getDate();

		$airline = SFactory::getAirline();


		if( ! SFSAccess::check($user, 'a.admin') )  {
			$this->setError('restricted access');
			return false;
		}

		if( ! $airline->id ) {
			$this->setError('restricted access');
			return false;
		}

		//set parameters
		$this->setBooking();
		$sRoom    = (int) $this->getState('booking.sroom');
		$sdRoom   = (int) $this->getState('booking.sdroom');
		$tRoom    = (int) $this->getState('booking.troom');
		$qRoom    = (int) $this->getState('booking.qroom');

		if ( (int) $sdRoom == 0 && (int)$tRoom == 0  ) {
			$this->setError('Invalid data');
			return false;
		}

		try {
			//make sure rooms >= booked rooms

			$room_id	 = (int) $this->getState('booking.room_id');

			$query  = 'SELECT a.date, a.transport_included, a.s_room_total,a.sd_room_total,a.t_room_total, a.q_room_total,
			a.s_room_rate_modified AS s_rate, a.sd_room_rate_modified AS sd_rate, a.t_room_rate_modified AS t_rate, a.q_room_rate_modified AS q_rate,
			c.name AS hotel_name,d.course_1,d.course_2,d.course_3,d.bf_standard_price,d.bf_layover_price,d.lunch_standard_price FROM #__sfs_room_inventory AS a';
			$query .= ' INNER JOIN #__sfs_hotel AS c ON c.id=a.hotel_id';
			$query .= ' INNER JOIN #__sfs_hotel_mealplans AS d ON d.hotel_id=a.hotel_id';
			$query .= ' WHERE a.id='.$room_id.' AND a.hotel_id='. (int) $this->getState('booking.hotel_id');

			$db->setQuery($query);

			$inventory = $db->loadObject();

			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}

			if (empty($inventory)) {
				return JError::raiseError(404,JText::_('COM_SFS_BOOKING_HOTEL_NOT_FOUND'));
			}
			if( $sRoom > 0 ) {
				if( ($inventory->s_room_total ) < $sRoom ) {
					$this->setError('COM_SFS_BOOKING_S_ROOM_NOT_ENOUGH');
					return false;
				}
			}

			if( $sdRoom > 0 ) {
				if( ($inventory->sd_room_total ) < $sdRoom ) {
					$this->setError('COM_SFS_BOOKING_SD_ROOM_NOT_ENOUGH');
					return false;
				}
			}
			if( $tRoom > 0 ) {
				if( ($inventory->t_room_total) < $tRoom ) {
					$this->setError('COM_SFS_BOOKING_T_ROOM_NOT_ENOUGH');
					return false;
				}
			}
			if( $qRoom > 0 ) {
				if( ($inventory->q_room_total) < $qRoom ) {
					$this->setError('COM_SFS_BOOKING_Q_ROOM_NOT_ENOUGH');
					return false;
				}
			}

			//process booking

			$breakfast	= JRequest::getVar('breakfast');
			$lunch		= JRequest::getVar('lunch');
			$mealplan 	= JRequest::getVar('mealplan');
			$course 	= JRequest::getVar('course');

			$percent_release_policy = JRequest::getInt('percent_release_policy');

			$now = $date->toSql();

			$data = new stdClass();
			$airline_current = SAirline::getInstance()->getCurrentAirport();
			$time_zone = $airline_current->time_zone;
			$start_date = SfsHelperDate::getDate('now','dmy', $time_zone);

			//generate blockcode
			if ( $airline->grouptype == 2 ) {
				$data->blockcode  =  SReservation::generateBlockCode($airline->code, $inventory->hotel_name, $start_date) ;
			} else if($airline->grouptype == 3){
				if( (int)$airline->iatacode_id ) {
					$db->setQuery('SELECT code FROM #__sfs_iatacodes WHERE id='.$airline->iatacode_id);
					$aCode = $db->loadResult();
					if($aCode)
						$data->blockcode  =  SReservation::generateBlockCode($aCode, $inventory->hotel_name, $start_date) ;
				} else {
					return false;
				}
			} else {
				return false;
			}

			$data->airline_id   =  $airline->id;

			$data->booked_by	=  $user->id;
			$data->room_id	  	=  $room_id;
			$data->hotel_id		=  $this->getState('booking.hotel_id');
			$data->booked_date	=  $now;

			$data->sd_room 	 =  $sdRoom;
			$data->t_room 	 =  $tRoom;

			$data->s_rate 	 =  $inventory->s_rate;
			$data->sd_rate 	 =  $inventory->sd_rate;
			$data->t_rate 	 =  $inventory->t_rate;
			$data->q_rate 	 =  $inventory->q_rate;
			$data->transport =  $inventory->transport_included;

			// first booking the roomblock status should be OPEN
			$data->status 	 =  'O';

			$data->breakfast = 0;
			if($breakfast) {
				$data->breakfast = $inventory->bf_layover_price;
			}

			$data->lunch = 0;
			if($lunch) {
				$data->lunch = $inventory->lunch_standard_price;
			}

			$data->course_type = 0;
			$data->mealplan = 0;

			if( ! empty($mealplan) ) {
				$data->course_type=(int)$course;
				switch ($data->course_type) {
					case 1:
					$data->mealplan = $inventory->course_1;
					break;
					case 2:
					$data->mealplan = $inventory->course_2;
					break;
					case 3:
					$data->mealplan = $inventory->course_3;
					break;
					default:
					break;
				}
			}

			$data->percent_release_policy = $percent_release_policy;

			if ( ! $db->insertObject('#__sfs_reservations',$data) )
			{
				throw new Exception( JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg()) );
			}

			$reservationId = $db->insertid();

			if( SFSAccess::check($user, 'gh') )
			{
				if( (int)$airline->iatacode_id ) {
					$query = 'INSERT INTO #__sfs_gh_reservations(airline_id,reservation_id) VALUES('.(int)$airline->iatacode_id.','.$reservationId.')';
					$db->setQuery($query);
					if( ! $db->query() ) {
						throw new Exception( 'GH: '.JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg()) );
					}
				}
			}

			//update number of room of the hotel
			$db->setQuery('UPDATE #__sfs_room_inventory SET sd_room_total=sd_room_total-'.(int)$data->sd_room.',t_room_total=t_room_total-'.(int)$data->t_room.',booked_sdroom=booked_sdroom+'.(int)$data->sd_room.',booked_troom=booked_troom+'.(int)$data->t_room.' WHERE id='.$data->room_id);
			if( !$db->query() ) {
				throw new Exception( JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg()) );
			}
			//send notification email to hotel
			$hotel = SFactory::getHotel($this->getState('booking.hotel_id'));
			$currency = $hotel->getTaxes()->currency_symbol;

			$blockcode = $data->blockcode;
			$arrival_date = $inventory->date;
			$room_number = $sRoom+$sdRoom+$tRoom+$qRoom;
			$sroom_number = $sRoom;
			$sroom_rate = $currency.' '.$data->s_rate;
			$sdroom_number = $sdRoom;
			$sdroom_rate = $currency.' '.$data->sd_rate;
			$troom_number = $tRoom;
			$troom_rate = $currency.' '.$data->t_rate;
			$qroom_number = $qRoom;
			$qroom_rate = $currency.' '.$data->q_rate;
			$breakfast_price = $currency.' '.$data->breakfast;
			$lunch_price = $currency.' '.$data->lunch;
			$dinner_price = $currency.' '.$data->mealplan;
			$course_menu = $course.' course';

			$booked_contact = SFactory::getContact($data->booked_by);

			$booked_name = $booked_contact->name.' '.$booked_contact->surname;
			$booked_title = $booked_contact->job_title;

			$airline_name = $airline->name;

			if($booked_contact->is_admin) {
				$airline_contact_name = $booked_contact->name.' '.$booked_contact->surname;
				$airline_contact_title = $booked_contact->job_title;
				$airline_contact_telephone = $booked_contact->telephone;
				$airline_contact_email = $booked_contact->email;
			} else {
				// TODO late. Get main contact from db
				$airline_contact_name = $booked_contact->name.' '.$booked_contact->surname;
				$airline_contact_title = $booked_contact->job_title;
				$airline_contact_telephone = $booked_contact->telephone;
				$airline_contact_email = $booked_contact->email;
			}


			// Email to Hotel contacts
			$hotel_contacts = SFactory::getContacts(1, $this->getState('booking.hotel_id'));

			ob_start();
			require_once JPATH_COMPONENT.'/libraries/emails/hotelblockconfirm.php';
			$bodyE = ob_get_clean();

			$hotelEmailBody = JString::str_ireplace('[^]', '', $bodyE);
			$hotelEmailBody = JString::str_ireplace('{date}', '', $hotelEmailBody);

			$faxEmail = '';

			foreach ( $hotel_contacts as $hotelContact ) {
				$hotelContactName = $hotelContact->name .' '.$hotelContact->surname;
				$hotelEmailBody1 = JString::str_ireplace('{hotelcontact}', $hotelContactName, $hotelEmailBody);

				if($hotelContact->is_admin) {
					$faxEmail = JString::str_ireplace('{hotelcontact}', $hotelContactName, $bodyE);
				}
				if( !empty($hotelContact->systemEmails) && (int)JString::strpos($hotelContact->systemEmails, 'booking') > 0 ){
					JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $hotelContact->email, 'SHORT TERM AIRPORT ROOM BLOCK RESERVATION  BY SFS-WEB', $hotelEmailBody1, true);
				}
			}

			// Email for Fax Service
			$faxNumber = trim(SfsHelper::formatPhone( $hotel->fax, 1)).trim(SfsHelper::formatPhone( $hotel->fax, 2));
			$faxAtt = JPATH_SITE.DS.'media'.DS.'sfs'.DS.'attachments'.DS.'faxblock'.$reservationId.'.html';
			$bodyE = JString::str_ireplace('{date}', JHtml::_('date',JFactory::getDate(),"d-M-Y"), $faxEmail);
			JFile::write($faxAtt, $bodyE);

			JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$faxNumber.'@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation '.$blockcode, $bodyE, true,null,null,$faxAtt);

			$hotelBackendSetting = $hotel->getBackendSetting();
			if( $second_fax = $hotelBackendSetting->second_fax )
			{
				JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$second_fax.'@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation '.$blockcode, $bodyE, true,null,null,$faxAtt);
			}

			// Email for Airline
			$hotel_name = $hotel->name;
			$airline_contacts = SFactory::getContacts($airline->grouptype,$airline->id);
			ob_start();
			require_once JPATH_COMPONENT.'/libraries/emails/airlineblockconfirm.php';
			$bodyE = ob_get_clean();

			JUtility::sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions', $user->email, 'Your SFS-web reservations details of '.$arrival_date.' booking '.$hotel->name.' '.$blockcode, $bodyE, true);

			$params = JComponentHelper::getParams('com_sfs');

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
				$adminSubject = JText::sprintf('COM_SFS_BLOCK_CONFIRM_ADMINISTRATOR_SUBJECT',$room_number." COM_SFS_LABLE_ROOMS ".$blockcode);
				$adminBlockcodeUrl = JURI::root().'administrator/index.php?option=com_sfs&view=reservation&id='.$reservationId;

				$adminBlockcodeUrl = str_replace('https', ' http', $adminBlockcodeUrl);

				ob_start();
				require_once JPATH_COMPONENT.'/libraries/emails/admin/blockconfirm.php';
				$adminBody = ob_get_clean();

				//Test
				//echo $adminBody;die;
				//JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', 'linh_013@yahoo.com', $adminSubject, $adminBody, true);

				$adminEmails = explode(';', $adminEmails);
				if( count($adminEmails) )
				{
					foreach ($adminEmails as $am) {
						JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', $am, $adminSubject, $adminBody, true);
					}
				}
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

	public function addFlightsSeats($data = null)
	{
		$db		 = $this->getDbo();
		$user 	 = JFactory::getUser();
		$date 	 = JFactory::getDate();
		$sess 	 = JFactory::getSession();
		
		//get airline, ground handler id
		
		$strandedHandler = SFactory::getAirline();
		
		$airline = $strandedHandler;

		$needValidation = true;

		if( empty($data) )
		{
			$data = new stdClass();


			$data->seats 			= (int) JRequest::getInt('stranded_seats');
			
			if(empty($data->seats)) {
				$needValidation		= false;
				$data->seats 		= (int) JRequest::getInt('passengers');
			}
			
			$data->flight_code		=  JRequest::getString('flight_code');
			$data->flight_class 	=  JRequest::getVar('flight_class');
			$data->delay_code 		=  JRequest::getString('iata_stranged_code');
			$data->airline_id   	= $strandedHandler->id;
			$data->airport_id   	= (int) JRequest::getInt('airport');
			$data->created_by  		= $user->id;
			$data->created 			= $date->toSql();

			$data->comment 			= JRequest::getString('comment');

			$data->from_date		= JRequest::getVar('date_start_prev');
			$data->end_date			= JRequest::getVar('date_end_prev');
		}

		$validate = true;
		
		if( $data->seats <= 0 ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_1'));
			$validate = false;
		}
		
		if( strlen($data->flight_code) == 0 ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_2'));
			$validate = false;
		}
		
		if( strlen($data->delay_code) == 0 ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_3'));
			$validate = false;
		}
		
		//check delay code
		$query = 'SELECT COUNT(*) FROM #__sfs_delaycodes WHERE code='.$db->Quote($data->delay_code);
		$db->setQuery($query);
		
		if( ! $db->loadResult() ) {
			$this->setError(JText::_('COM_SFS_AIRLINE_FLIGHT_ERROR_3'));
			$validate = false;
		}
		
		$query = 'SELECT DATEDIFF('.$db->quote($data->end_date).','.$db->quote($data->from_date).')';
		$db->setQuery($query);
		
		$diffdate = (int)$db->loadResult();
		if($diffdate < 1)
		{
			$this->setError('The period date you selected invalid');
			$validate = false;
		}
		
		if ( $needValidation && ! $validate ) {
			$sess->set('flightform_data',$data);
			return false;
		}
		
		#print_r($data);die();
		if ( ! $db->insertObject('#__sfs_flights_seats', $data) ) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		$insertId = $db->insertId();
		
		if( $needValidation && (int)$strandedHandler->get('grouptype') == 3  )
		{
			if( (int)$strandedHandler->iatacode_id > 0 ) {
				$query = 'INSERT INTO #__sfs_gh_flights_seats(fligh_id,gh_id) VALUES('.$insertId.','.$strandedHandler->iatacode_id.')';
				$db->setQuery($query);
				if( ! $db->query() ) {
					$this->setError($db->getErrorMsg());
					return false;
				}
			}
		}
		
		$sess->clear('flightform_data');
		return $insertId;
	}

	public function deleteSeats()
	{
		$db		 = $this->getDbo();
		$user 	 = JFactory::getUser();
		$date 	 = JFactory::getDate();

		$strandedHandler = SFactory::getAirline();

		$flightIDs = JRequest::getVar('flightids', array(), 'post', 'array');

		if ( count($flightIDs) == 0 ) {
			$this->setError(JText::_('COM_SFS_NONE_SEATS_FLIGHTS_ARE_SELECTED'));
			return false;
		}

		$seats = array();

		foreach ($flightIDs as $value) {
			if( ! isset( $seats[(int)$value] ) ) {
				$seats[(int)$value] = 0;
			}
			$seats[(int)$value] = $seats[(int)$value] + 1;
		}

		foreach ( $seats as $k => $v ) {
			$query = 'UPDATE #__sfs_flights_seats SET seats=seats-'.$v.' WHERE id='.$k.' AND airline_id='.$strandedHandler->id;
			$db->setQuery($query);
			if( !$db->query() ) {
				return false;
			}
		}

		return true;
	}


	public function getBookedRooms( $booking_id = null )
	{
		$airline = SFactory::getAirline();

		if( (int) $airline->grouptype==3 ) {
			if( (int) $airline->iatacode_id <= 0 ) {
				return null;
			}
		}

		if( $this->_booked_rooms === null )
		{

			$matchModel = JModel::getInstance('Match','SfsModel');

			$nightdate = JRequest::getVar('nightdate');

			$matchModel->setState('match.nightdate',$nightdate);

			$dateNight = $matchModel->getNightDate();

			$db = $this->getDbo();

			$query = $db->getQuery(true);

			$query->select('a.*,c.name,b.date AS room_date');
			$query->from('#__sfs_reservations AS a');
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
			$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');

			if($airline->grouptype==3)
			{
				$query->innerJoin('#__sfs_gh_reservations AS d ON d.reservation_id=a.id AND d.airline_id='.(int)$airline->iatacode_id);
			}

			$query->where('a.airline_id = '.$airline->id);

			$query->where('b.date='.$db->quote($dateNight));
			//$query->where('a.expired = 0');

			$query->where('a.status <> '.$db->quote('A') );
			$query->where('a.status <> '.$db->quote('R') );
			$query->where('a.status <> '.$db->quote('D') );

			$query->order('a.booked_date DESC');

			if( $booking_id ) {
				$query->where('a.id='.$booking_id);
				$db->setQuery($query);
				return $db->loadObject();
			}
			if( (int) $this->getState('bookingid') ) {
				$query->where('a.id='.$this->getState('bookingid'));
				$db->setQuery($query);
				return $db->loadObject();
			}

			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$this->_booked_rooms = $rows;
		}

		if( $booking_id && count($this->_booked_rooms) ) {
			foreach ( $this->_booked_rooms as $value  ) {
				if( (int)$value->id == $booking_id ) {
					return $value;
					break;
				}
			}
		}

		return $this->_booked_rooms;
	}

	public function getVouchers( $bookingId = null , $flightId = null , $roomType = null )
	{
		if( !$bookingId ) {
			$bookingId = (int) $this->getState('bookingid');
		}

		if( $bookingId ) {
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			$query->select('a.*,f.flight_code,u.name AS created_name');
			$query->from('#__sfs_voucher_codes AS a');

			$query->leftJoin('#__sfs_flights_seats AS f ON f.id=a.flight_id');
			$query->innerJoin('#__users AS u ON u.id=a.created_by');

			$query->where('a.booking_id='.$bookingId);

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
			$db->setQuery($query);

			$rows = $db->loadObjectList();

			return $rows;
		}
		return false;
	}

	public function getFlightsSeats()
	{
		$airline 		= SFactory::getAirline();

		$matchModel = JModel::getInstance('Match','SfsModel');

		$nightdate = JRequest::getVar('nightdate');

		$matchModel->setState('match.nightdate',$nightdate);

		$dateNight = $matchModel->getNightDate();

		$flight_seats 	= $airline->getFlightsSeats($dateNight);
		return $flight_seats;
	}

	public function getFlightCodes()
	{
		$airline = SFactory::getAirline();
		$db = $this->getDbo();
		$user = JFactory::getUser();
		$query = 'SELECT id,flight_code FROM #__sfs_flights_seats WHERE airline_id='.$airline->id.' AND is_expire=0';
		$db->setQuery($query);
		$rows = $db->loadAssocList('id','flight_code');
		return $rows;
	}
	public function getAirlineTrains()
	{
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$airline = SFactory::getAirline();

		$query->select('id,stationname,cityname,country');
		$query->from('#__sfs_airline_trains');
		$query->where("iata_airportcode = '".$airline->airport_code."' AND status = 1");
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

}

