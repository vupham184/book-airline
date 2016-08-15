<?php
defined('_JEXEC') or die;

class SfsModelMessage extends JModelLegacy
{

	protected function populateState()
	{
		$app = JFactory::getApplication();
		
		$value = JRequest::getInt('bookingid');
		$this->setState('filter.bookingid',$value);
		
		$value = JRequest::getVar('airport');
		$this->setState('filter.airport',$value);
								
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	public function getReservation() 
	{
		$reservationId = $this->getState('filter.bookingid');
		if( (int)$reservationId > 0 ) 
		{			
			$externalAirport = $this->getState('filter.airport');
			
			if( !$externalAirport ){
				$reservation = SReservation::getInstance( $reservationId );	
			} else {
				
				$association = SFactory::getAssociation($externalAirport);					
				$reservation = new SReservation ( $reservationId , false , 'id', $association->db );	
			}	
			
			return $reservation;
		}	
		return null;
	}
	
	public function getAirline()
	{
		$reservation = $this->getReservation();		
		if($reservation)
		{
			$db = $reservation->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*,b.name');
			$query->from('#__sfs_airline_details AS a');
			$query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id');			
			$query->where('a.id='.$reservation->airline_id);
			
			$db->setQuery($query);
			
			$airline = $db->loadObject();
			
			if($db->getErrorNum())
			{
				$this->setError($db->getErrorMsg());
				return false;
			}
			if( $airline->company_name )
			{
				$airline->name = $airline->company_name;
			}
			
			// Load contact detail of booked user
			$query  = 'SELECT a.*,u.email FROM #__sfs_contacts AS a';
			$query .= ' INNER JOIN #__users AS u ON u.id=a.user_id';
			$query .= ' WHERE a.user_id='.$reservation->booked_by.' AND u.block=0';
			
			$db->setQuery($query);

			$contact = $db->loadObject();
			
			if($db->getErrorNum())
			{
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			$airline->contact_details = $contact;
			
			return $airline;
		}
		return null;
	}

	public function getMessageCount()
	{
		$reservation = $this->getReservation();
		if(isset($reservation)) 
		{
			$db = $reservation->getDbo();
			$query = 'SELECT COUNT(*) FROM #__sfs_messages WHERE block_id='.$reservation->id;
			$db->setQuery($query);
			$result = $db->loadResult();			
			return $result;	
		}
		
		return null;				
	}
	
	public function getLatestMessage()
	{
		$reservation = $this->getReservation();
		if(isset($reservation)) {		
			$db = $reservation->getDbo();
			$query = 'SELECT * FROM #__sfs_messages';
			$query .= ' WHERE type=2 AND block_id='.$reservation->id.' ORDER BY posted_date DESC';
			$db->setQuery($query,0,1);
			
			$result = $db->loadObject();
	
			return $result;
		}
		return null;
	}
	
	public function send()
	{
		$bookingId 		 = (int)$this->getState('filter.bookingid');
		$externalAirport = $this->getState('filter.airport');
		
		if( !$bookingId ) {
			$this->setError('Missing reservation id');	
			return false;
		}			
			
		$user = JFactory::getUser();		
		
		// make sure this booking is valid with current logged in
		$reservation = $this->getReservation();
		
		if( $reservation ) {						
			$message_count = $this->getMessageCount();
													
			//insert message
			$text = JRequest::getVar('message');
						
			$message = new stdClass();
			
			$message->block_id 	 = $bookingId;
			$message->from     	 = $user->id;
			if( SFSAccess::isAirline($user) ){
				$airline = SFactory::getAirline();
				$message->type    	 = 1;					
				if ($reservation->airline_id != $airline->id ) {	
					$this->setError('Incorrect blockcode');
					return false;
				}
			} else if( SFSAccess::isHotel($user) ) {
				$hotel = SFactory::getHotel();
				$message->type    	 = 2;
				if ($reservation->hotel_id != $hotel->id ) {	
					$this->setError('Incorrect blockcode');
					return false;
				}
			}
			
			$message->from_name  = $user->name;
			$message->posted_date = JFactory::getDate()->toSQL();
			$message->body = str_replace(array("\n"), '<br />',$text);
			
			$db = $reservation->getDbo();
			
			if( ! $db->insertObject('#__sfs_messages', $message) ) {				
				JError::raiseError(500, $db->getErrorMsg());
			}
			
			//send email			
			if( SFSAccess::isAirline($user) ){
				SEmail::messageToHotel($reservation->status, $reservation, str_replace(array("\n"), '<br />',$text));
			} else if( SFSAccess::isHotel($user) ) {
				SEmail::messageToAirline($reservation->status, $reservation, str_replace(array("\n"), '<br />',$text));
			}
							
			return true;	
		}	
		return false;				
	}

}
