<?php
defined('_JEXEC') or die;

class SfsModelTransportbooking extends JModelLegacy
{
	
	protected $_terminals = null;
	protected $_transport_company = null;

	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);		
	}
	
	public function booking( $voucherId = null )
	{
		$app	 = JFactory::getApplication();	
		$user 	 = JFactory::getUser();
		$airline = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		$db 	 = $this->getDbo();
		$query   = $db->getQuery(true);
		
		$total_passengers 		= JRequest::getInt('total_passengers');
		$departure 				= JRequest::getVar('departure');
		$transport_company_id 	= JRequest::getInt('transport_company_id');		
		$flight_number 			= JRequest::getVar('flight_number');
		$comment 				= JRequest::getString('comment', '', 'default', JREQUEST_ALLOWRAW);
		
		if( $total_passengers < 1 )
		{
			$this->setError('Please enter passengers');
			return false;
		}		
		if( strlen(trim($flight_number)) == 0 )
		{
			$this->setError('Please enter flight number');
			return false;
		}
		
		$departure_type = null;
		$hotel_id		= 0;
		
		if( $departure )
		{
			$tmpArray = explode(',', $departure);					
			if( count($tmpArray) == 2 )
			{				
				if( (int)$tmpArray[1] > 0 )
				{
					if($tmpArray[0]=='a'){
						$departure_type = 'airport';
						$departure = $tmpArray[1];						
						$hotel_id  = JRequest::getInt('hotel_id',0);	
						if($hotel_id < 1)
						{
							$this->setError('Please select an hotel');
							return false;
						}					
					}
					if($tmpArray[0]=='h'){
						$departure_type = 'hotel';
						$hotel_id     = $tmpArray[1];
						$terminal_id  = JRequest::getInt('terminal_id',0);	
						if($terminal_id < 1)
						{
							$this->setError('Please select an airport');
							return false;
						}	
						$departure = $terminal_id;			
					}
				}
			}	
			if($departure_type == null){
				$this->setError('Please select departure');
				return false;
			}
		} else {
			$this->setError('Please select departure');
			return false;
		}
		if( $transport_company_id < 1 )
		{
			$this->setError('Transport company was not found');
			return false;
		}
		
		$reservation = new stdClass();
		
		$reservation->airline_id			= $airline->id;
		$reservation->user_id 				= $user->id;
		$reservation->transport_company_id 	= $transport_company_id;
		$reservation->hotel_id 				= $hotel_id;
		$reservation->total_passengers	 	= $total_passengers;
		$reservation->flight_number		 	= $flight_number;
		$reservation->departure		 		= $departure;
		$reservation->departure_type 		= $departure_type;
		$reservation->comment			 	= $comment;
		$reservation->booked_date		 	= JFactory::getDate()->toSql();
		$reservation->status		 		= 'pending';		
		$reservation->requested_date		= SfsHelperDate::getDate('now','Y-m-d');
		
		
		while (true)
		{
			$reference_number = SfsHelperDate::getDate('now','dmy', $time_zone);
		
			$reference_number .= '-'.SfsHelper::createRandomString(2);
			$reference_number .= '-'.$airline->code;
			$reference_number .= '-B'.$total_passengers;
			
			$reference_number = JString::strtoupper($reference_number);	
			
			$query = 'SELECT COUNT(*) FROM #__sfs_transportation_reservations WHERE airline_id='.$airline->id.' AND reference_number='.$db->quote($reference_number);
			$db->setQuery($query);
			$count = $db->loadResult();
			if( ! $count )
			{
				$reservation->reference_number = $reference_number;
				break;	
			}
		}
		
		$requested_time = JRequest::getVar('requested_time');
		
		if($requested_time) {
			$reservation->requested_time = '0';
		} else {
			$requested_time_h = JRequest::getVar('requested_time_h');
			$requested_time_m = JRequest::getVar('requested_time_m');			
			$reservation->requested_time = $requested_time_h.':'.$requested_time_m;
			$reservation->requested_date = JRequest::getVar('departure_date');
		}
		
		//get rate
		$query = 'SELECT * FROM #__sfs_group_transportation_types WHERE group_transportation_id='.$transport_company_id.' AND seats >= '.$total_passengers.' ORDER BY seats ASC';
		$db->setQuery($query);
		$transportRate = $db->loadObject();
		
		if($transportRate)
		{			
			$reservation->rate = floatval($transportRate->rate);

			if($hotel_id)
			{
				$query  = 'SELECT a.hotel_id, a.day_fare FROM #__sfs_group_transportation_rates AS a';
				$query .= ' WHERE a.hotel_id=0 OR a.hotel_id='.$hotel_id.' AND a.group_transportation_type_id='.$transportRate->id;
				$db->setQuery($query);
				$hotelRates = $db->loadObjectList('hotel_id');
				if(count($hotelRates))
				{
					if( is_object($hotelRates[$hotel_id]) )
					{
						$reservation->rate = $hotelRates[$hotel_id]->day_fare;
					} else {
						if( is_object($hotelRates[0]) )
						{
							$reservation->rate = $hotelRates[0]->day_fare;
						}
					}
				}
			}
			
		}		
		
		if( ! $db->insertObject('#__sfs_transportation_reservations',$reservation) )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$busreservation_id = $db->insertid();
		
		if( (int) $voucherId > 0 )
		{			
			$db->setQuery('INSERT INTO #__sfs_voucher_busreservation_map(voucher_id,busreservation_id) VALUES('.$voucherId.','.$busreservation_id.')');
			$db->query();
		}
		
		$tokenKey = $busreservation_id.':'.$reservation->reference_number;
		$tokenKey = base64_encode($tokenKey);
		
		$acceptLink  = JURI::base().'index.php?option=com_sfs&task=transportbooking.accepted&reference_number='.$reservation->reference_number.'&token='.$tokenKey;
		$declineLink = JURI::base().'index.php?option=com_sfs&task=transportbooking.declined&reference_number='.$reservation->reference_number.'&token='.$tokenKey;
		
		// Email and Fax to the bus company		
		$booked_contact 	= SFactory::getContact((int)$reservation->user_id);									
		$booked_name 		= $booked_contact->name.' '.$booked_contact->surname;
		$booked_title 		= $booked_contact->job_title;
		
		$airline_name				= $airline->name;						
		$airline_contact_name 		= $booked_contact->name.' '.$booked_contact->surname;
		$airline_contact_title 		= $booked_contact->job_title;	
		$airline_contact_telephone 	= $booked_contact->telephone;
		$airline_contact_email 		= $booked_contact->email;	
		
		$db->setQuery('SELECT * FROM #__sfs_group_transportations WHERE id='.$reservation->transport_company_id);
		$busCompany = $db->loadObject();
		
		$reservation->bus_company  = $busCompany->name;
		$reservation->arrival_date = SfsHelperDate::getDate('now','Y-m-d', $time_zone);
		
		$db->setQuery('SELECT name FROM #__sfs_iatacodes WHERE id='.$reservation->departure);
		$reservation->terminal_name = $db->loadResult();
		
		$db->setQuery('SELECT name FROM #__sfs_hotel WHERE id='.$reservation->hotel_id);
		$reservation->hotel_name = $db->loadResult();
		
		if( $reservation->departure_type == 'airport' )
		{
			$reservation->pick_up_location  = $reservation->terminal_name;
			$reservation->drop_off_location = $reservation->hotel_name;
		} else {
			$reservation->pick_up_location  = $reservation->hotel_name;
			$reservation->drop_off_location = $reservation->terminal_name;
		}
		
		
		$registry = new JRegistry();
		$registry->loadString($busCompany->notification);
		$notifications = $registry->toArray();
		
		ob_start(); 
		require_once JPATH_COMPONENT.'/libraries/emails/bustransportconfirm.php';			
		$bodyE = ob_get_clean();	
				
		//Email
		$busEmailBody = JString::str_ireplace('[^]', '', $bodyE);
		$busEmailBody = JString::str_ireplace('{date}', '', $busEmailBody);
		
		if( (int)$busCompany->sendMail == 1 && is_array($notifications['email']) && count($notifications['email']) )
		{			
			foreach ($notifications['email'] as $email) {
				JUtility::sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions', $email, 'SHORT TERM BUS TRANSPORTATION Reservation BY SFS-WEB', $busEmailBody, true);
			}
		}
		
		//Fax
		if( (int)$busCompany->sendFax == 1 && is_array($notifications['fax']) && count($notifications['fax']) )
		{			
			jimport('joomla.filesystem.file');
			
			$faxAtt 		= JPATH_SITE.DS.'media'.DS.'sfs'.DS.'busreservations'.DS.'faxblock'.$busreservation_id.'.html';
			$faxEmailBody 	= JString::str_ireplace('{date}', SfsHelperDate::getDate(), $bodyE);			
			JFile::write($faxAtt, $faxEmailBody);	

			foreach ($notifications['fax'] as $faxNumber) {
				JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions' ,$faxNumber.'@efaxsend.com', $subject, $faxEmailBody, true,null,null,$faxAtt);
			}				
		}
		
		//SMS
		$params = JComponentHelper::getParams('com_sfs');
		$sms_bus_text   = $params->get('sms_bus_text');
		$smsPhoneNumber = $params->get('sms_bus_phone_number');
		
		if($sms_bus_text) {
			//$airportCode = $airline->airport_code;
			$airlineName = $airline->getAirlineName();				
			$sms_bus_text = JString::str_ireplace('{airline}', $reservation->terminal_name.', '.$airlineName, $sms_bus_text);
			
			if( $reservation->requested_time == '0' ){
				$sms_bus_text = JString::str_ireplace('{departuretime}', 'asap', $sms_bus_text);	
			} else {
				$sms_bus_text = JString::str_ireplace('{departuretime}', $reservation->requested_time, $sms_bus_text);
			}
			
			$sms_bus_text = JString::str_ireplace('{numberpassenger}', $reservation->total_passengers, $sms_bus_text);
			$sms_bus_text = JString::str_ireplace('{vouchernumber}', $reservation->reference_number, $sms_bus_text);
		
			$sms_bus_text = JString::str_ireplace('{busname}',$busCompany->name, $sms_bus_text);
			$sms_bus_text = JString::str_ireplace('{busphone}','+'.$busCompany->telephone, $sms_bus_text);

			if((int)$busCompany->sendSMS == 1)
			{								
				SEmail::SMS('bus', $sms_bus_text,$notifications['mobile']);
			} else {
				SEmail::SMS('bus', $sms_bus_text);	
			}							
		}
		
		$reservation = (array)$reservation;
		
		$app->setUserState('com_sfs.transportbooking.status', 'success');
		$app->setUserState('com_sfs.transportbooking.reservation', $reservation);
		
		return true;
	}
	
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
	
	public function getHotels()
	{
		$db = $this->getDbo();
		$query = 'SELECT id,name FROM #__sfs_hotel WHERE block=0';
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		return $rows; 
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
			$query->where('b.published=1');
			
			$db->setQuery($query);
			$this->_transport_company = $db->loadObject();
		}
		return $this->_transport_company;
	}
	
	public function updateStatus($status)
	{		
		$db = $this->getDbo();
		$token = JRequest::getVar('token');
		$reference_number = JRequest::getVar('reference_number');
		if( $reference_number && $token )
		{
			$reservation = $this->getTransportationReservation($reference_number);
			
			if($reservation)
			{			
				$token = base64_decode($token);	
				$token = explode(':', $token);
				if( count($token ) == 2 && (int)$token[0] == (int)$reservation->id && $token[1] == $reference_number ){
					$query = 'UPDATE #__sfs_transportation_reservations SET status='.$db->quote($status).' WHERE id='.$reservation->id;
					$db->setQuery($query);
					
					if( $db->query() ) {
						return true;	
					} else {
						$this->setError($db->getErrorMsg());
						return false;	
					}									
				} else {
					$this->setError('Invalid Token');
					return false;
				}							
			}
			
		}
		return false;
	}
	
	public function getUserKey()
	{
		$db = $this->getDbo();
		$reference_number = JRequest::getVar('reference_number');
		$reservation = $this->getTransportationReservation($reference_number);		
		
		if( (int)$reservation->transport_company_id > 0 ) {
			$query = $db->getQuery(true);
			
			$query->select('a.user_id,c.secret_key');
			$query->from('#__sfs_group_transportation_user_map AS a');
			$query->innerJoin('#__sfs_contacts AS c ON c.user_id=a.user_id');
			
			$query->where('a.group_transportation_id = '.$reservation->transport_company_id);
			$query->where('c.is_admin = 1');
			
			$db->setQuery($query);
			
			$users = $db->loadObjectList();

			if( count($users) )
			{				
				foreach ( $users as $u )
				{
					if( $u->secret_key )
					{
						return $u->secret_key;
					}
				}
			}
				
		}	
		return false;	
	}
	
	public function getTransportationReservation( $identifier = null )
	{
		$result = null;
		if( $identifier == null )
		{
			$identifier = JRequest::getVar('reference_number');
		}
		if($identifier)
		{
			$db = $this->getDbo();
			$query = 'SELECT * FROM #__sfs_transportation_reservations WHERE reference_number='.$db->quote($identifier);
			$db->setQuery($query);
			
			$result = $db->loadObject();
		}
		
		return $result;
	}

}


