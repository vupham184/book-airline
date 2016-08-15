<?php
// No direct access
defined('_JEXEC') or die;

class SfsModelTaxibooking extends JModelLegacy
{
	
	protected $_terminals = null;
	protected $_taxi_companies  = null;

	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);		
	}
	
	public function booking()
	{
		$app	 = JFactory::getApplication();	
		$user 	 = JFactory::getUser();
		$airline = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		
		$db 	 = $this->getDbo();
		$query   = $db->getQuery(true);
		
		$taxi_reservation_id = JRequest::getInt('taxi_reservation_id');
		
		if($taxi_reservation_id)
		{
			return false;
		}
		
		$taxi_id 				= JRequest::getInt('taxi_id');
		$hotel_id 				= JRequest::getInt('hotel_id');
		$departure 				= JRequest::getInt('departure');		
		$flight_number 			= JRequest::getVar('flight_number');
		$comment 				= JRequest::getString('comment', '', 'default', JREQUEST_ALLOWRAW);
		$return_comment			= JRequest::getString('return_comment', '', 'default', JREQUEST_ALLOWRAW);
		$transfer_type 			= JRequest::getInt('transfer_type');
		$tmpPassengers 			= JRequest::getVar('passengers', array(),'post','array');
		
		$passengers = array();
		
		if(count($tmpPassengers))
		{
			foreach ($tmpPassengers as $row)
			{
				if( $row['firstname'] || $row['lastname']  )
				{
					$passenger = new stdClass();
					$passenger->first_name   = $row['firstname'];
					$passenger->last_name    = $row['lastname'];
					$passenger->phone_number = $row['phonenumber'];
					
					$passengers[] = $passenger;
				}
			}
		}
				
		if( $taxi_id < 1 )
		{
			$this->setError('Taxi company was not found');
			return false;
		}	
		if( $hotel_id < 1 )
		{
			$this->setError('Please select your accommodation');
			return false;
		}
		if( strlen(trim($flight_number)) == 0 )
		{
			$this->setError('Please enter flight number');
			return false;
		}
		if( $departure < 1 )
		{
			$this->setError('Please select departure');
			return false;
		}		
		if( $transfer_type < 1 )
		{
			$this->setError('Please select taxi transfers');
			return false;
		}
		if( count($passengers) == 0 )
		{
			$this->setError('Please enter passengers');
			return false;
		}			
		
		$reservation = new stdClass();
		
		$reservation->airline_id		= $airline->id;		
		$reservation->taxi_id			= $taxi_id;
		$reservation->hotel_id			= $hotel_id;
		$reservation->transfer_type		= $transfer_type;
		$reservation->flight_number		= $flight_number;
		$reservation->terminal_id		= $departure;
		$reservation->comment			= $comment;
		$reservation->return_comment	= $return_comment;
		$reservation->booked_date		= JFactory::getDate()->toSql();
		$reservation->booked_by		 	= $user->id;
				
		$requested_time = JRequest::getVar('requested_time');
		
		if($requested_time) {
			$reservation->requested_time = '0';
		} else {
			$requested_time_h = JRequest::getVar('requested_time_h');
			$requested_time_m = JRequest::getVar('requested_time_m');			
			$reservation->requested_time = $requested_time_h.':'.$requested_time_m;
		}
		
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
			
			$reference_number .= '-T'.count($passengers);
			
			$reference_number = JString::strtoupper($reference_number);	
			
			$query = 'SELECT COUNT(*) FROM #__sfs_taxi_vouchers WHERE reference_number='.$db->quote($reference_number);
			$db->setQuery($query);
			$count = $db->loadResult();
			if( ! $count )
			{
				$reservation->reference_number = $reference_number;
				break;	
			}
		}
		
		$reservation->code = $reservation->reference_number;
		
		// get rate		
		$query = 'SELECT * FROM #__sfs_taxi_hotel_rates WHERE taxi_id='.$taxi_id.' AND hotel_id='.$hotel_id;
		$db->setQuery($query);
		$hotelRate = $db->loadObject();
		
		if($hotelRate)
		{
			$reservation->rate =  $hotelRate->day_fare;		 
		} else {
			$query  = 'SELECT a.ring,b.day_fare FROM #__sfs_hotel_backend_params AS a';
			$query .= ' INNER JOIN #__sfs_taxi_hotel_rates AS b ON b.ring=a.ring AND b.hotel_id=0';
			$query .= ' WHERE b.taxi_id='.$taxi_id;
			$db->setQuery($query);
			$hotelRate = $db->loadObject();
			if($hotelRate)
			{
				$reservation->rate = $hotelRate->day_fare;	
			}
		}
				
		if( ! $db->insertObject('#__sfs_taxi_vouchers',$reservation) )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$taxi_reservation_id = $db->insertid();

		
		foreach ($passengers as $passenger)
		{
			$passenger->taxi_reservation_id = $taxi_reservation_id;
			$db->insertObject('#__sfs_taxi_reservation_passengers',$passenger);
		}
					
		$this->setState('taxi_reservation_id',$taxi_reservation_id);		
		$app->setUserState('com_sfs.taxibooking.reservation', $reservation);
		
		return true;
	}
	
	public function printVoucher()
	{
		$app	 = JFactory::getApplication();	
		$user 	 = JFactory::getUser();
		$airline = SFactory::getAirline();
		$db 	 = $this->getDbo();
		$query   = $db->getQuery(true);
		
		$taxi_reservation_id = JRequest::getInt('taxi_reservation_id');
		$printtype			 = JRequest::getVar('printtype');	
		
		if(!$taxi_reservation_id)
		{
			return false;
		}
		// Get taxi res
		$taxiReservation = $this->getTaxiReservation();
		
		if( !$taxiReservation )
		{
			return false;
		}
		
		$comment 				= JRequest::getString('comment', '', 'default', JREQUEST_ALLOWRAW);
		$return_comment			= JRequest::getString('return_comment', '', 'default', JREQUEST_ALLOWRAW);	
				
		$reservation = new stdClass();
		
		$reservation->id		= $taxiReservation->id;
		$reservation->comment 	= $comment;
		$reservation->return_comment = $return_comment;
		
		if($printtype=='outgoing')
		{
			$reservation->printed = 1;
			$reservation->printed_date = JFactory::getDate()->toSql();			
		}
		if($printtype=='returntrip')
		{
			$reservation->return_printed = 1;
			$reservation->return_printed_date = JFactory::getDate()->toSql();			
		}
			
		if((int)$taxiReservation->printed == 0 && $printtype=='outgoing')
		{
			// Process Email, Fax
			$taxiReservation->comment 	= $comment;
			$taxiReservation->return_comment 	= $return_comment;
			SEmail::emailAndFaxToTaxi2($taxiReservation);						
		}
		
		if((int)$taxiReservation->sendSMS == 0 && $printtype=='outgoing')
		{
			$params = JComponentHelper::getParams('com_sfs');
			$sms_taxi_text  = $params->get('sms_taxi_text');
			$smsPhoneNumber = $params->get('sms_taxi_phone_number');		
			
			if($sms_taxi_text && $smsPhoneNumber) {
				$airportCode = $airline->airport_code;
				$airlineName = $airline->getAirlineName();				
				$sms_taxi_text = JString::str_ireplace('{airline}', $airportCode.', '.$airlineName, $sms_taxi_text);
				if( $taxiReservation->requested_time == '0' )
				{
					$taxiReservation->arrival_time = 'asap';	
				} else {
					$taxiReservation->arrival_time = $taxiReservation->requested_time;
				}
				$sms_taxi_text = JString::str_ireplace('{departuretime}', $taxiReservation->arrival_time, $sms_taxi_text);
				$seats = count($taxiReservation->passengers);
				$sms_taxi_text = JString::str_ireplace('{numberpassenger}', $seats, $sms_taxi_text);
				$sms_taxi_text = JString::str_ireplace('{vouchernumber}', $taxiReservation->reference_number, $sms_taxi_text);
				
				
				$query = 'SELECT * FROM #__sfs_taxi_companies WHERE id='.$taxiReservation->taxi_id;				
				$db->setQuery($query);
				$taxiCompany = $db->loadObject();
				
				if($taxiCompany)
				{
					$sms_taxi_text = JString::str_ireplace('{taxiname}',$taxiCompany->name, $sms_taxi_text);
					$sms_taxi_text = JString::str_ireplace('{taxiphone}','+'.$taxiCompany->telephone, $sms_taxi_text);						
					SEmail::SMS('taxi', $sms_taxi_text);
					$reservation->sendSMS = 1;
				}				
			}
			
		}
			
		$db->updateObject('#__sfs_taxi_vouchers', $reservation,'id');	
				
		return;		
	}
	
	public function cancelVoucher()
	{
		$taxi_reservation_id = JRequest::getInt('taxi_reservation_id');
				
		$airline = SFactory::getAirline();
		$db 	 = $this->getDbo();
		
		if($taxi_reservation_id)
		{
					
		}
		return true;
	}
	
	public function getTaxiReservation()
	{
		$airline = SFactory::getAirline();
		$taxi_reservation_id = JRequest::getInt('taxi_reservation_id');
		
		if(!$taxi_reservation_id)
		{
			return null;
		}
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.*,b.name AS taxi_name,b.telephone AS taxi_telephone,b.email AS taxi_email,b.fax AS taxi_fax,b.sendMail,b.sendFax');
		$query->from('#__sfs_taxi_vouchers AS a');
		$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
		$query->where('a.id='.$taxi_reservation_id);
		$query->where('a.airline_id='.$airline->id);		
		$db->setQuery($query);
		$result = $db->loadObject();
		
		if( $result )
		{
			$query->clear();
			$query->select('a.*');
			$query->from('#__sfs_taxi_reservation_passengers AS a');
			$query->where('a.taxi_reservation_id='.$taxi_reservation_id);
			$db->setQuery($query);
			$passengers = $db->loadObjectList();
			
			$result->passengers = $passengers;
			
			if( $result->hotel_id )
			{
				$hotel = SFactory::getHotel($result->hotel_id);
				if($hotel)
				{
					$result->hotel = $hotel;
				}
			}
			
		}
		
		return $result;		
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
	
	public function getTaxiCompanies()
	{
		$airline = SFactory::getAirline();
		
		if( $this->_taxi_companies == null && SFSAccess::isAirline() ){
			
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('a.*,b.*');
			$query->from('#__sfs_airline_taxicompany_map AS a');
			$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
			
			$query->where('b.published = 1');
			$query->where('a.airline_id = ' . (int) $airline->id );
			
			$db->setQuery($query);
			
			$this->_taxi_companies = $db->loadObjectList();
		}
		
		return $this->_taxi_companies;		
	}
	
	public function getHotels()
	{
		$db = $this->getDbo();
		$query = 'SELECT id,name FROM #__sfs_hotel WHERE block=0';
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		return $rows; 
	}
	

}


