<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelBlock extends JModel
{
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		$value = JRequest::getCmd('blockstatus');
		$this->setState( 'block.status' , $value );

		$value = JRequest::getCmd('blockid');
		$this->setState( 'block.id' , $value );

		$value = JRequest::getVar('airport');
		$this->setState('block.airport', $value);
		
		$this->setState('limit',20);
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

		$value = trim(JRequest::getString('blockcode'));
		$this->setState('block.code',$value);
		
		$value = JRequest::getVar('blockstatus');
		$this->setState('block.status',$value);
				
		$value	= JRequest::getString('date_from');
		$this->setState('block.from',$value);
		
		$value	= JRequest::getString('date_to');		
		$this->setState('block.to',$value);		
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	public function getReservations()
	{		
		$result = null;
		
		if( $this->getState('block.status') || $this->getState('block.code') || $this->getState('block.from') || $this->getState('block.to') ) 
		{		       		
			$hotel = SFactory::getHotel();
					
			$db    = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*');		
			$query->from('#__sfs_reservations AS a');				
			$query->where('a.association_id=0');
			$query->where('a.hotel_id='.$hotel->id);
			
			$this->setConditionQuery($query);				
			
			$query->order('a.blockdate DESC, a.booked_date DESC');
			
			$db->setQuery($query);		
			// echo (string)$query; die();
			$rows = $db->loadObjectList();
			
			foreach ($rows as $row)
			{
				if( ! isset($result[$row->blockdate]) )
				{
					$result[$row->blockdate] = array();
				}
				$result[$row->blockdate][] = $row;
			}
			unset($rows);
						
			$query->clear();
			$query->select('a.*');		
			$query->from('#__sfs_reservation_airport_map AS a');							
			$query->where('a.hotel_id='.$hotel->id);			
			$query->order('a.blockdate DESC');
			
			$this->setConditionQuery($query);
			
			$db->setQuery($query);		
			
		    $rows = $db->loadObjectList();
			
			foreach ($rows as $row)
			{
				if( ! isset($result[$row->blockdate]) )
				{
					$result[$row->blockdate] = array();
				}
				$result[$row->blockdate][] = $row;
			}
					
		}
		return $result;
	}	
	
	protected function setConditionQuery( & $query ) 
	{
		$db = $this->getDbo();
		
		if($this->getState('block.code')) {
        	$query->where( 'a.blockcode='.$db->quote($this->getState('block.code')) );	
		}		
		
		if($this->getState('block.status')) {
			$query->where( 'a.status='.$db->quote($this->getState('block.status')) );	
		}	
						
		$block_from = $this->getState('block.from');
		if( isset($block_from) && strlen($block_from) > 0 ) {
			$query->where( 'a.blockdate  >= '.$db->quote($block_from) );				
		}	
		
		$block_to = $this->getState('block.to');
		if( isset($block_to) && strlen($block_to) > 0 ) {				
			$query->where( 'a.blockdate  <= '.$db->quote($block_to) );				
		}	
	}
	
	public function getBlockCount()
	{
		$result = array();
		
		$hotel  = SFactory::getHotel();
		$status = $this->getState('block.status');
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.status, COUNT(*) AS block_total');		
		$query->from('#__sfs_reservations AS a');							
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('a.association_id=0');
		$query->group('a.status');	
			
		$db->setQuery($query);		
		
		$rows = $db->loadObjectList('status');
		
		//external airports
		$query->clear();		
		$query->select('a.status, COUNT(*) AS block_total');		
		$query->from('#__sfs_reservation_airport_map AS a');							
		$query->where('a.hotel_id='.$hotel->id);		
		$query->group('a.status');	
			
		$db->setQuery($query);				
		$rows2 = $db->loadObjectList('status');
		
		if(count($rows))
		{
			foreach ($rows as $key => $value)
			{
				if( ! isset($result[$key]) ){
					$result[$key] = 0;
				}
				$result[$key] = $result[$key] + $value->block_total;
			}
		}
		if(count($rows2))
		{
			foreach ($rows2 as $key => $value)
			{
				if( ! isset($result[$key]) ){
					$result[$key] = 0;
				}
				$result[$key] = $result[$key] + $value->block_total;
			}
		}	
			
		
		return $result;
	}
	
	public function getBookingAirlines()
	{
		$hotel = $this->getHotel();
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.airline_id, d.name');		
		$query->from('#__sfs_reservations AS a');
		
		$query->leftJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->leftJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');
				
		$query->where('b.hotel_id='.$hotel->id);
		$query->group('a.airline_id');	
		
		$db->setQuery($query);
		
		$rows = $db->loadAssocList('airline_id','name');
			
		return $rows;
	}

	public function getReservation() 
	{
		$pk 	 		 = $this->getState('block.id');
		$externalAirport = $this->getState('block.airport');
		if( (int) $pk > 0 ) 
		{
			$forceLoad = true;
			if( !$externalAirport ){
				$reservation = SReservation::getInstance( $pk, $forceLoad);	
			} else {
				$association = SFactory::getAssociation($externalAirport);					
				$reservation = new SReservation ( $pk , $forceLoad , 'id', $association->db );	
			}			
			return $reservation;
		}	
		return null;
	}
	
	public function getGuaranteeVoucher() 
	{
		$db = $this->getDbo();
		$reservation = $this->getReservation();
		
		if( !empty($reservation) )
		{
			$query = $db->getQuery(true);
			
			$query->select('a.*');
			$query->from('#__sfs_fake_vouchers AS a');			
	
			$query->where('a.reservation_id='.$reservation->id);
						
			$db->setQuery($query);
			
			$result = $db->loadObject();
						
			if($result) {
				return $result;
			}
		}
		
		return null;
	}
	
	public function getAirline()
	{
		$reservation = $this->getReservation();

		if($reservation)
		{
            $user	= JFactory::getUser((int)$reservation->booked_by);
			$db = $reservation->getDbo();
			$query = $db->getQuery(true);
            if(SFSAccess::check( $user, 'gh' )) {
                $query->select('a.*,b.name AS airline_name');
                $query->from('#__sfs_airline_details AS a');
                $query->innerJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id');
                $query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.airline_id=b.id');
                $query->where('ghr.reservation_id='.$reservation->id);
            }else{
                $query->select('a.*,b.name AS airline_name');
                $query->from('#__sfs_airline_details AS a');
                $query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id');
                $query->where('a.id='.$reservation->airline_id);
            }


			
			$db->setQuery($query);
			
			$airline = $db->loadObject();

			
			if($db->getErrorNum())
			{
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			// Load billing details
			$query->clear();
			$query->select('a.*'); 
			$query->from('#__sfs_billing_details AS a');
			$query->where('a.id='.(int)$airline->billing_id);
			$db->setQuery($query);
			
			$billing = $db->loadObject();
			
			if($db->getErrorNum())
			{
				$this->setError($db->getErrorMsg());
				return false;
			}
			
			$airline->billing_details = $billing;



			// Load contact detail of booked user
			$query  = 'SELECT a.*,u.email FROM #__sfs_contacts AS a';
			$query .= ' INNER JOIN #__users AS u ON u.id=a.user_id';
            if(SFSAccess::check( $user, 'gh' )) {
                $query .= ' WHERE a.user_id='.$airline->created_by.' AND u.block=0';
            }else{
                $query .= ' WHERE a.user_id='.$reservation->booked_by.' AND u.block=0';
            }

			
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
	
}
