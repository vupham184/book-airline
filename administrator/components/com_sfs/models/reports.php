<?php
defined('_JEXEC') or die;
		
class SfsModelReports extends JModelLegacy
{
	
	protected $hotels = null;
	protected $airlines= null;
	protected $ghs = null;
	
	protected $_airline = null;

	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication();
	
		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}
		
		$value = JRequest::getInt('hotel_id');		
		$this->setState('report.hotel_id', $value);
		
		$value = JRequest::getInt('airline_id');		
		$this->setState('report.airline_id', $value);
	}
	
	
	public function getHotels()
	{
		if( $this->hotels === null )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('*');
			$query->from('#__sfs_hotel');
			$query->where('block=0');
			
			$query->order('name ASC');
			
			$db->setQuery($query);
			
			$this->hotels = $db->loadObjectList();
		}
		return $this->hotels;
	}
	
	public function getAirlines()
	{
		if( $this->airlines === null )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
			$query->select('a.*,b.name AS airline_name,b.code AS airline_code,c.code AS airport_code');
			$query->from('#__sfs_airline_details AS a');
			$query->innerJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id AND b.type=1');
			
			$query->innerJoin('#__sfs_iatacodes AS c ON c.id=a.airport_id AND c.type=2');
			
			
			if ( $airport_id != "" ) {
				$query->leftJoin('#__sfs_airline_airport ap ON ap.airline_detail_id=a.id');
				$query->leftJoin('#__sfs_iatacodes ia ON ia.id=ap.airport_id');
				$query->where('ia.code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
			$query->where('a.iatacode_id > 0');
							
			
			$query->order('b.name ASC');
			
			$db->setQuery($query);
			
			$this->airlines = $db->loadObjectList();
		}
		return $this->airlines;
	}
	
	public function getGhs()
	{
		if( $this->ghs === null )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
			$query->select('a.*,c.code AS airport_code');
			$query->from('#__sfs_airline_details AS a');
			
			$query->innerJoin('#__sfs_iatacodes AS c ON c.id=a.airport_id AND c.type=2');
			
			$query->where('a.iatacode_id = 0');
							
			if ( $airport_id != "" ) {
				$query->leftJoin('#__sfs_airline_airport ap ON ap.airline_detail_id=a.id');
				$query->leftJoin('#__sfs_iatacodes ia ON ia.id=ap.airport_id');
				$query->where('ia.code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
			$query->order('a.company_name ASC');
			
			$db->setQuery($query);
			
			$this->ghs = $db->loadObjectList();
		}
		return $this->ghs;
	}
	
	
	public function getHotel()
	{
		$pk = 0;
		if( (int)$this->getState('report.hotel_id') )
		{
			$pk = $this->getState('report.hotel_id');
		}
		if( ! $pk ){
			$pk = $this->getState('filter.hotel_id');
		}
				
		if( $pk )
		{						
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*,c.code AS currency');
			$query->from('#__sfs_hotel AS a');
			
			$query->leftJoin('#__sfs_hotel_taxes AS b ON b.hotel_id=a.id');
			$query->leftJoin('#__sfs_currency AS c ON b.currency_id=c.id');
			
			$query->where('a.id='.$pk);
			
			$db->setQuery($query);
			
			$hotel = $db->loadObject();
								
			return $hotel;
			
		}
		return null;
	}
	
	public function getAirline()
	{
		if( (int)$this->getState('report.airline_id') )
		{
			if( $this->_airline == null ) {
				
				//Change airport session
				$session = JFactory::getSession();
				$airport_id = $session->get("airport_current_id");//code
				
				$pk = $this->getState('report.airline_id');
				
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				
				$query->select('a.*,b.name AS airline_name');
				$query->from('#__sfs_airline_details AS a');
				
				$query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id AND b.type=1');
				
				$query->where('a.id='.$pk);
				
				if ( $airport_id != "" ) {
					$query->leftJoin('#__sfs_airline_airport ap ON ap.airline_detail_id=a.id');
					$query->leftJoin('#__sfs_iatacodes ia ON ia.id=ap.airport_id');
					$query->where('ia.code="' . $airport_id . '"');
					///echo (string)$query;die;
				}
				
				$db->setQuery($query);
				
				//echo(string)$query;
				
				$this->_airline = $db->loadObject();		

				if( (int) $this->_airline->iatacode_id == 0)
				{
					$query->clear();
					$query->select('a.airline_id,b.name,b.code');
					$query->from('#__sfs_groundhandlers_airlines AS a');
					$query->innerJoin('#__sfs_iatacodes AS b ON b.id=a.airline_id');
					$query->where('a.ground_id='.$pk);
					
					$query->order('b.name ASC');
					
					$db->setQuery($query);
					
					$servicing_airlines = $db->loadObjectList();
					
					$this->_airline->airlines = $servicing_airlines;
				}
				
			}
			
		}
		return $this->_airline;
	}
		
	public function getHotelReportData()
	{
		if( $this->getState('filter.month_from') && $this->getState('filter.year_from')
    		 && $this->getState('filter.month_to') && $this->getState('filter.year_to') )
    	{
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
				 
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$fromStr = ( $this->getState('filter.month_from') < 10 ) ? $this->getState('filter.year_from').'-0'.$this->getState('filter.month_from').'-01' : $this->getState('filter.year_from').'-'.$this->getState('filter.month_from').'-01';
			$tStr = ( $this->getState('filter.month_to') < 10 ) ? $this->getState('filter.year_to').'-0'.$this->getState('filter.month_to').'-01' : $this->getState('filter.year_to').'-'.$this->getState('filter.month_to').'-01';
			
			$query->select('LAST_DAY('.$db->Quote($tStr).')');
			$db->setQuery($query);			
			$tStr = $db->loadResult();
			
			$query->clear();
			$query->select('a.*,b.date');
			$query->from('#__sfs_reservations AS a');
			
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
						
			$query->where('b.date >= '.$db->Quote($fromStr));
			$query->where('b.date <= '.$db->Quote($tStr));
			
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
	    	$hotelId = (int)$this->getState('filter.hotel_id');
	    	if($hotelId > 0)
	    	{
	    		$query->where('a.hotel_id = '.$hotelId);
	    	}				
			$query->order('b.date ASC');
		
			$db->setQuery($query);
			
			$data = $db->loadObjectList();
								
			$result = array();
			foreach ($data as $row)
			{
				$ym = JString::substr($row->date, 0,7);
				if( !isset( $result[$ym] ) )
				{
					$stdObject = new stdClass();
					$stdObject->number_booking = 0;
					$stdObject->number_rooms = 0;					 
					$stdObject->revenue_booked = 0;
					$stdObject->room_price_total = 0;
					$stdObject->date = $row->date;
					$result[$ym] = $stdObject;					
				}
				
				$number_rooms = $row->sd_room + $row->t_room + $row->s_room + $row->q_room;
				
				$result[$ym]->number_booking   = $result[$ym]->number_booking + 1;
				$result[$ym]->room_price_total = $result[$ym]->room_price_total + $row->sd_rate;
				$result[$ym]->number_rooms 	   = $result[$ym]->number_rooms + $number_rooms;
				$result[$ym]->revenue_booked   = $result[$ym]->revenue_booked + $row->revenue_booked;
			}												
			
			if(count($result)) {
				foreach ($result as &$value) {
					$value->average_price = floatval( $value->room_price_total / $value->number_booking );
					$value->average_price = number_format($value->average_price,2);
				}						
				return $result;
			}					
    	}
    	return null;
	}
	
	public function getRoomnights()
    {       	
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();	    	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	
	    	   
	    	$query->select('SUM(a.claimed_rooms) AS roomnights,c.name');
	    	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);
	    	
	    	if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
	    	
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$query->group('b.hotel_id');  

	    	$query->order('roomnights DESC');		
	    		    		    	    	
	    	$db->setQuery($query);
	    	$result = $db->loadObjectList();
	    	
	    	return $result;
    	}
    	return null;
    }
    
	public function getAverages()
    {       	
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();	   
	    	 	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);

	    	$query->select('SUM(a.sd_rate) AS rate_total, COUNT(a.id) AS booked_total, c.name');
	    
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$query->group('b.hotel_id');  

	    	$query->order('booked_total DESC');
	    		    		    	    	
	    	$db->setQuery($query);
	    	
	    	//echo (string)$query;
	    	$result = $db->loadObjectList();
	    	
	    	return $result;
    	}
    	
    	return null;
    }
    
	public function getRevenues()
    {       	
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();	    	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);

	    	$query->select('SUM(a.revenue_booked) AS revenue_booked,c.name');
	    	
	    	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$query->group('b.hotel_id');  

			$query->order('revenue_booked DESC');
				    		    		    	    	
	    	$db->setQuery($query);
	    	$result = $db->loadObjectList();
	    	
	    	return $result;
    	}
    	return null;
    }
    
 	public function getRoomnightsChartData() 
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();	    
	    		
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	
    	
	    	$query->select('SUM(a.claimed_rooms) AS roomnights,c.name,b.date');	
	    	 	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$query->group('b.date');    	
	    	$query->order('b.date ASC');
	    		    	    	
	    	$db->setQuery($query);
	    	$result = $db->loadObjectList();
	    	
	    	return $result;
    	}
    	return FALSE;
    }
    
	public function getAverageChartData() 
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();	    	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	
	    	$query->select('SUM(a.sd_rate) AS rate_total, COUNT(a.id) AS booked_total, c.name,b.date');	
	    	 	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$query->group('b.date');    	
	    	$query->order('b.date ASC');
	    		    	    	
	    	$db->setQuery($query);
	    	$result = $db->loadObjectList();
	    	
	    	return $result;
    	}
    	return FALSE;
    }
    
	public function getRevenueChartData() 
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();	    	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);	    		   

	    	$query->select('SUM(a.revenue_booked) AS revenue_booked,c.name,b.date');
	    	 	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$query->group('b.date');    	
	    	$query->order('b.date ASC');
	    		    	    	
	    	$db->setQuery($query);
	    	$result = $db->loadObjectList();
	    	
	    	return $result;
    	}
    	return FALSE;
    }
    
    
    
	public function drawHotelChart()
    {
    	$db = JFactory::getDbo();
    	include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pData.class.php');
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pDraw.class.php');
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pImage.class.php');   
		
 		$points = JRequest::getVar('points');
 		$dates  = JRequest::getVar('dates');
 		$type 	= JRequest::getInt('type');
		
 		$chartData = new pData();		
 		
 		$hotelId = JRequest::getInt('hotel_id');
 		
 		
		$params = JComponentHelper::getParams('com_sfs');
		$sfs_system_currency = $params->get('sfs_system_currency','EUR');
 		
		switch ($type) {
			case 1:
				$chartData->setAxisName(0,"Number of room");
				break;
			case 2:
				if($hotelId){
					$query = 'SELECT b.code FROM #__sfs_hotel_taxes AS a';
					$query .= ' INNER JOIN #__sfs_currency AS b ON b.id=a.currency_id';
					$query .= ' WHERE a.hotel_id='.$hotelId;
					
					$db->setQuery($query);
					
					$currency = $db->loadResult(); 
					
					$chartData->setAxisName(0,"Average room price in ".$currency);	
				} else {
					$chartData->setAxisName(0,"Average room price in ".$sfs_system_currency);	
				}
				
				break;
			case 3:
				if($hotelId){
					$query = 'SELECT b.code FROM #__sfs_hotel_taxes AS a';
					$query .= ' INNER JOIN #__sfs_currency AS b ON b.id=a.currency_id';
					$query .= ' WHERE a.hotel_id='.$hotelId;
					
					$db->setQuery($query);
					
					$currency = $db->loadResult(); 
					$chartData->setAxisName(0,"Revenue in ".$currency);
				} else {
					$chartData->setAxisName(0,"Revenue in ".$sfs_system_currency);
				}	
				break;		
		}
		 		
 		$points = explode(',', $points);
    	if(count($points)==1) {
			$points[] = $points[0];
		} 
				
		$chartData->addPoints( $points,'Hotel');
		$chartData->setPalette( 'Hotel' ,array("R"=>98, "G"=>163, "B"=>221,"Dash"=>TRUE,"DashR"=>170,"DashG"=>220,"DashB"=>190));
		
 		$dates = explode(',', $dates);
    	if(count($dates)==1) {
			$dates[] = $dates[0];
		} 		
		foreach ($dates as &$value) {
			$value = JHtml::_('date',$value,JText::_('F\nY'));
		}
		
 		$chartData->addPoints($dates,"Labels");
 		
 		$chartData->setSerieDescription("Labels","Months");
 		$chartData->setAbscissa("Labels"); 		

		$m = count($points);
		
		$XSize = 254 ;
		$YSize = 254 ;
		$GraphAreaX1 = 250;
		
		if ( $m >= 7) {
			for( $i=6 ; $i<$m ; $i++)  {
				$XSize +=50;
				$GraphAreaX1 +=50;
			}
		}
		
		$storePicture = new pImage($XSize,$YSize,$chartData);
		
		$storePicture->Antialias = TRUE; 
		
		$max_value = max($points);
		
		if( $max_value < 100 ) {
			if($type==2){
				$storePicture->setGraphArea(45,5,$GraphAreaX1,190);
			} else {
				$storePicture->setGraphArea(30,5,$GraphAreaX1,190);
			}	
		} else if ( $max_value >=100 && $max_value < 1000) {
			$storePicture->setGraphArea(37,5,$GraphAreaX1,190);
		} else if($max_value >=1000 && $max_value < 10000){
			$storePicture->setGraphArea(40,5,$GraphAreaX1,190);
		} else if($max_value >=10000 && $max_value < 100000){
			$storePicture->setGraphArea(46,5,$GraphAreaX1,190);
		} else if($max_value >=100000 && $max_value < 1000000){
			$storePicture->setGraphArea(52,5,$GraphAreaX1,190);
		} else {
			$storePicture->setGraphArea(62,5,$GraphAreaX1,190);
		}
				
		$storePicture->setFontProperties(array("FontName"=>JPATH_ROOT.'/components/com_sfs/libraries/chart/fonts/calibri.ttf',"FontSize"=>8));
	
		$scaleSettings = array(
			"XMargin"=>10,
			"YMargin"=>10,
			"Floating"=>FALSE,
			"GridR"=>200,
			"GridG"=>200,
			"GridB"=>200,
			"DrawSubTicks"=>FALSE,
			"CycleBackground"=>FALSE,
			"AutoAxisLabels"=>FALSE,
			"DrawArrows"=>FALSE,
			"LabelRotation"=>90
		);
		
		$storePicture->drawScale($scaleSettings);
	
		$storePicture->Antialias = TRUE; 
		 
		
		$storePicture->drawLineChart();
		$storePicture->drawPlotChart(); 
		
		$user = JFactory::getUser();
    	/* Render the picture (choose the best way) */
    	$storePicture->autoOutput(JURI::base()."images/ahotelChart".$user->id.".png");   	
    }
    
    
    public function drawAirlineChart()
    {		
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pData.class.php');
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pDraw.class.php');
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pImage.class.php');   
		
 		$points = JRequest::getVar('points');
 		$dates  =  JRequest::getVar('dates');
 		$type = JRequest::getInt('type');
		
 		$chartData = new pData();		
 		
 		$params = JComponentHelper::getParams('com_sfs');
		$sfs_system_currency = $params->get('sfs_system_currency','EUR');
 		
		switch ($type) {
			case 1:
				$chartData->setAxisName(0,"Number of room");
				break;
			case 2:								
				$chartData->setAxisName(0,"Average room prices in ".$sfs_system_currency);
				break;
			case 3:				
				$chartData->setAxisName(0,"Revenue booked ".$sfs_system_currency);
				break;		
		}
		 		
 		$points = explode(',', $points);
    	if(count($points)==1) {
			$points[] = $points[0];
		} 
				
		$chartData->addPoints( $points,'Airline');
		$chartData->setPalette( 'Airline' ,array("R"=>98, "G"=>163, "B"=>221,"Dash"=>TRUE,"DashR"=>170,"DashG"=>220,"DashB"=>190));
		
 		$dates = explode(',', $dates);
    	if(count($dates)==1) {
			$dates[] = $dates[0];
		} 		
		foreach ($dates as &$value) {
			$value = substr( (string)JHtml::_('date',$value,JText::_('d-F')) ,0,6);
		}
		
 		$chartData->addPoints($dates,"Labels");
 		
 		$chartData->setSerieDescription("Labels","Months");
 		$chartData->setAbscissa("Labels"); 		

		$m = count($points);
		
		$XSize = 254 ;
		$YSize = 254 ;
		$GraphAreaX1 = 250;
		
		if ( $m >= 7) {
			for( $i=6 ; $i<$m ; $i++)  {
				$XSize +=50;
				$GraphAreaX1 +=50;
			}
		}
		
		$storePicture = new pImage($XSize,$YSize,$chartData);
		
		$storePicture->Antialias = TRUE; 
		
		$max_value = max($points);
		
		if( $max_value < 100 ) {
			$storePicture->setGraphArea(30,5,$GraphAreaX1,190);	
		} else if ( $max_value >=100 && $max_value < 1000) {
			$storePicture->setGraphArea(37,5,$GraphAreaX1,190);
		} else if($max_value >=1000 && $max_value < 10000){
			$storePicture->setGraphArea(40,5,$GraphAreaX1,190);
		} else if($max_value >=10000 && $max_value < 100000){
			$storePicture->setGraphArea(46,5,$GraphAreaX1,190);
		} else if($max_value >=100000 && $max_value < 1000000){
			$storePicture->setGraphArea(52,5,$GraphAreaX1,190);
		} else {
			$storePicture->setGraphArea(62,5,$GraphAreaX1,190);
		}
				
		$storePicture->setFontProperties(array("FontName"=>JPATH_ROOT.'/components/com_sfs/libraries/chart/fonts/calibri.ttf',"FontSize"=>8));
	
		$scaleSettings = array(
			"XMargin"=>10,
			"YMargin"=>10,
			"Floating"=>FALSE,
			"GridR"=>200,
			"GridG"=>200,
			"GridB"=>200,
			"DrawSubTicks"=>FALSE,
			"CycleBackground"=>FALSE,
			"AutoAxisLabels"=>FALSE,
			"DrawArrows"=>FALSE,
			"LabelRotation"=>270
		);
		
		$storePicture->drawScale($scaleSettings);
	
		$storePicture->Antialias = TRUE; 
		 
		
		$storePicture->drawLineChart();
		$storePicture->drawPlotChart(); 
		
		$user = JFactory::getUser();
    	/* Render the picture (choose the best way) */
    	$storePicture->autoOutput(JURI::base()."images/aairlineChart".$user->id.".png");   	
    }   

    
	public function getIataCodePercentages()
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
    		$airline = $this->getAirline();
    		$db = $this->getDbo();
    		
    		$query = $db->getQuery(true);
    		$query->select('b.code, b.description, SUM(a.seats_issued) AS seat_total');
    		$query->from('#__sfs_flights_seats AS a');
			$query->innerJoin('#__sfs_delaycodes AS b ON b.code=a.delay_code');
			
			$query->where('a.airline_id='.$airline->id);
			
    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
			
			$query->where('a.created >='.$db->Quote($this->getState('filter.date_from').' 00:00:00'));
			$query->where('a.created <='.$db->Quote($this->getState('filter.date_to').' 23:59:59'));			
			
			$query->group('a.delay_code');
			$query->order('seat_total DESC');	   	    	
			
	    	$db->setQuery($query);
	    	//echo (string)$query;die;
	    	$rows = $db->loadObjectList();
	    	
	    	return $rows;
    	}
    	return null;    	    
    }
    
    public function getMarketPercentages()    
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();
	    	$db = $this->getDbo();
	    	
	    	$result = array();
	    	
	    	// calculate total of loaded rooms of all hotels which is connected to the airline who was logged in to the system 
	    	$query = $db->getQuery(true);   
	    	 	
	    	$query->select('SUM(a.sd_room_total+a.t_room_total) AS total');
	    	$query->from('#__sfs_room_inventory AS a');	    	    	    	
	    	$query->innerJoin('#__sfs_hotel_airports AS b ON b.hotel_id=a.hotel_id');
	    	
	    	$query->where('b.airport_id='.$airline->airport_id);
	    	
			$query->where('a.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('a.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$db->setQuery($query);
	    	
	    	$result[0] = (int)$db->loadResult();
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	$query->select('SUM(a.sd_room+a.t_room) AS total');
	    	$query->from('#__sfs_reservations AS a');
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);

    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));		    	
	    	
			
    		$statuses = $this->getState('filter.statuses');
	    	
	    	if( count($statuses) )
	    	{
	    		$subWhere = array();
	    		foreach ($statuses as $status)
	    		{
	    			$subWhere[] = 'a.status = '	. $db->quote($status);
	    		}
	    		$subWhere = implode(' OR ', $subWhere);
	    		$query->where('('.$subWhere.')');
	    	}
	    	
	    	$db->setQuery($query);
	    	
	    	$result[1] = $db->loadResult();   
	    	
	    	return $result;    
    	}	 
    	return null; 
    }
    
    public function getTransportationPercentages()    
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
	    	$airline = $this->getAirline();
	    	$db = $this->getDbo();
	       	
	    	$result = array();
	    	
			$query = $db->getQuery(true);
	
			$query->select('SUM(a.sd_room+a.t_room) AS total_rooms');
					
			$query->from('#__sfs_reservations AS a');
	
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
			
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
			$query->where('a.airline_id='.$airline->id);

    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
			
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));
			$query->where('a.transport=1');
						
			$db->setQuery($query);
			$result[0] = (int)$db->loadResult();
			
			$query = $db->getQuery(true);
	
			$query->select('SUM(a.sd_room+a.t_room) AS total_rooms');
					
			$query->from('#__sfs_reservations AS a');
	
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
			
			$query->where('a.airline_id='.$airline->id);	

    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
			
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));
			$query->where('a.transport=0');
						
			$db->setQuery($query);
			$result[1] = (int)$db->loadResult();
		
	    	
	    	return $result;
    	}
    	return null;
    }    
    
    
    public function getInitialPercentages()
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
			//Change airport session
			$session = JFactory::getSession();
			$airport_id = $session->get("airport_current_id");//code
			
			$airline = $this->getAirline();
	    	$db = $this->getDbo();
	       	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	$query->select('SUM(a.sd_room+a.t_room) AS initial_rooms,SUM(a.claimed_rooms) AS claimed_rooms');
	    	$query->from('#__sfs_reservations AS a');    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	
			if ( $airport_id != "" ) {
				$query->where('a.airport_code="' . $airport_id . '"');
				///echo (string)$query;die;
			}
			
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( (int)$airline->iatacode_id==0 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}
				$query->select('ic.name AS gh_airline');
	    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');	    		
	    	}
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));
				    	
	    	$db->setQuery($query);
	    	$result = $db->loadObject();
	    	
	    	return $result;     	
    	}
    	return null;
    }    
    
	public function drawPie()
    {
 		
 		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pData.class.php');
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pDraw.class.php');
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pImage.class.php');
		include(JPATH_ROOT.'/components/com_sfs/libraries/chart/class/pPie.class.php');   

 		/* Create and populate the pData object */
 		$points = JRequest::getVar('points');
 		$points = explode(',', $points);
 		$chartData = new pData();
 		$chartData->addPoints($points,"ScoreA");
 		$chartData->addPoints(array("A0","B1"),"Labels");
 		$chartData->setAbscissa("Labels"); 
 		
 		if(count($points)>2) {
 			$chartData->loadPalette(JPATH_ROOT.'/components/com_sfs/libraries/chart/'.'palettes'.DS.'sfs.color',true);
 		} else {
 			$chartData->loadPalette(JPATH_ROOT.'/components/com_sfs/libraries/chart/'.'palettes'.DS.'sfs2.color',true);
 		}
 	
 		/* Define the absissa serie */
 		$chartData->addPoints(array("<10","10<>20","20<>40","40<>60","60<>80",">80"),"Labels");
 		$chartData->setAbscissa("Labels");

 		/* Create the pChart object */
 		$storePicture = new pImage(150,150,$chartData);
 		 		
		$storePicture->setFontProperties(array("FontName"=>JPATH_ROOT.'/components/com_sfs/libraries/chart/fonts/calibri.ttf',"FontSize"=>8));
 		
		$storePicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>255,"G"=>255,"B"=>255,"Alpha"=>0)); 
 		/* Create the pPie object */
 		$PieChart = new pPie($storePicture,$chartData);
 		 		
 		/* Draw a simple pie chart */
 		$PieChart->draw2DPie(74,75,array("Border"=>TRUE));
 				
    	/* Render the picture (choose the best way) */
    	$storePicture->autoOutput(JURI::base()."images/adraw2DPie.labels.png");   	
    }  
    
}



