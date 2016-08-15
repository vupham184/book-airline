<?php
// No direct access
defined('_JEXEC') or die;

/**
 * Airline Report Class 
 */
class AReport {
	
	private static $_areport = null;
	
	private $beginDate = null;
	private $endDate = null;
	private $fp = null;
	private $airline = null;
	
	/**
	 * Get a airline report object.
	 *
	 * Returns the global airline report object, only creating it if it doesn't already exist.	 
	 *
	 * @return  AReport object	 
	 */
	public static function getInstance()
	{
		if ( ! self::$_areport ) {
			self::$_areport = new AReport();
		}
		return self::$_areport;
	}
	
	public function setAirline($airlineId)
	{
		if(empty($airlineId))
		{
			die('Missed Airline Id');
		}		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*,b.code AS airport_code');
		$query->from('#__sfs_airline_details AS a');
		$query->innerJoin('#__sfs_iatacodes AS b ON b.id=a.airport_id');
		
		$query->where('a.id='.$airlineId);
		
		$db->setQuery($query);
		
		$this->airline = $db->loadObject();
		
		if( $this->airline )
		{
			if( (int)$this->airline->iatacode_id > 0 )
			{
				$query = $db->getQuery(true);
				
				$query->select('a.name');
				$query->from('#__sfs_iatacodes AS a');
				$query->where('a.id='.$this->airline->iatacode_id);
						
				$db->setQuery($query);
				
				$this->airline->name = $db->loadResult();
				
				$this->airline->grouptype=2;
			} else {
				$this->airline->grouptype=3;
			}
		}
		return true;
	}
	
	public function getAirline()
	{
		return $this->airline;
	}
	
	/**	 
	 * Method to set the period date for report.
	 * 
	 * @param string $beginDate
	 * @param string $endDate
	 */
	public function setPeriodDate( $beginDate, $endDate )
	{
		$this->beginDate = $beginDate;
		$this->endDate   = $endDate;
	}
	
	public function getBeginDate()
	{
		return $this->beginDate;
	}
	public function getEndDate()
	{
		return $this->endDate;
	}
	
	public function getFileOpen()
	{
		$this->fp = fopen('php://output', 'w');
		return $this->fp;
	}
	
	public function closeStream()
	{
		fclose($this->fp);	
	}
	
	/**
	 * Export roomnights details to CSV file
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */
	public function exportRoomnights( $beginDate , $endDate )
	{
		$user = JFactory::getUser();
		
		$airline = $this->getAirline();
	
		$this->setPeriodDate($beginDate, $endDate);
				
		$this->setHeader('Total number roomnights', 'RoomnightsReport_'.$beginDate.'_'.$endDate.'.csv');
						
		//get data
		$data = $this->getRoomnights($beginDate, $endDate);	
		
		$data2 = array();
		$tmpArray = array();
		
		foreach ($data as $v) {
			if( ! isset($tmpArray[$v->hotel_id]) ) {
				$tmpArray[$v->hotel_id] = new stdClass();
				$tmpArray[$v->hotel_id]->hotel_name = $v->hotel_name;
				$tmpArray[$v->hotel_id]->claimed_rooms = 0;
			}
			$tmpArray[$v->hotel_id]->claimed_rooms += $v->claimed_rooms;
		}
		
		$i=0;
		foreach ($tmpArray as $k => $v) {
			$data2[$i] = $v;
			$i++;
		}		
		
		if( $airline->grouptype==3 )
		{
			fputcsv($this->fp, array('','','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','Airline','Hotel Name','Nr of Rooms','','','','Hotel Name','Total Nr of rooms'));
		} else {
			fputcsv($this->fp, array('','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','Hotel Name','Nr of Rooms','','','','Hotel Name','Total Nr of rooms'));	
		}
		
		
		$i = 0;		
		foreach ($data as $v) {		
			if( isset($data2[$i]) ) {
				if( $airline->grouptype==3 )
				{
					fputcsv( $this->fp, array($v->date,$v->gh_airline,$v->hotel_name,$v->claimed_rooms,'','','',$data2[$i]->hotel_name,$data2[$i]->claimed_rooms) );
				} else {
					fputcsv( $this->fp, array($v->date,$v->hotel_name,$v->claimed_rooms,'','','',$data2[$i]->hotel_name,$data2[$i]->claimed_rooms) );	
				}					
			} else {
				if( $airline->grouptype==3 )
				{
					fputcsv( $this->fp, array($v->date,$v->gh_airline,$v->hotel_name,$v->claimed_rooms) );
				} else {
					fputcsv( $this->fp, array($v->date,$v->hotel_name,$v->claimed_rooms) );	
				}					
			}					
			
			$i++;
		}
				
		$this->closeStream();			
	}
	
	
	/**
	 * Export average room prices details to CSV file
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */
	public function exportAveragePrices( $beginDate , $endDate )
	{		
		$this->setPeriodDate($beginDate, $endDate);
		
		$airline = $this->getAirline();
		
		//get data
		$data = $this->getAveragePrices($this->getBeginDate(), $this->getEndDate());
						
		$this->setHeader('Average room prices booked', 'Average_Room_Prices_Report_'.$beginDate.'_'.$endDate.'.csv');	
		
		
		$data2 = array();
		$tmpArray = array();
		
		foreach ($data as $v) {
			if( ! isset($tmpArray[$v->hotel_id]) ) {
				$tmpArray[$v->hotel_id] = new stdClass();
				$tmpArray[$v->hotel_id]->hotel_name = $v->hotel_name;
				$tmpArray[$v->hotel_id]->currency = $v->currency;
				$tmpArray[$v->hotel_id]->booked_count = 0;
				$tmpArray[$v->hotel_id]->sd_rate = 0;
				$tmpArray[$v->hotel_id]->t_rate = 0;
			}
			$tmpArray[$v->hotel_id]->sd_rate += $v->sd_rate;
			$tmpArray[$v->hotel_id]->t_rate += $v->t_rate;
			$tmpArray[$v->hotel_id]->booked_count++;
		}
		
		$i=0;
		foreach ($tmpArray as $k => $v) {
			$data2[$i] = $v;
			$i++;
		}		

		if( $airline->grouptype==3 )
		{
			fputcsv($this->fp, array('','','','','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','Airline','Hotel Name','S/D Room Rate','T Room Rate','Currency','','','','Hotel Name','Average S/D Room Rate','Average T Room Rate','Currency'));
		} else {
			fputcsv($this->fp, array('','','','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','Hotel Name','S/D Room Rate','T Room Rate','Currency','','','','Hotel Name','Average S/D Room Rate','Average T Room Rate','Currency'));	
		}		
		
		$i = 0;		
		foreach ($data as $v) {			
			if( isset($data2[$i]) ) {
				if( $airline->grouptype==3 ){
					fputcsv( $this->fp, array($v->date,$v->gh_airline,$v->hotel_name,$v->sd_rate,$v->t_rate,$v->currency,'','','',$data2[$i]->hotel_name,number_format($data2[$i]->sd_rate / $data2[$i]->booked_count,2),number_format($data2[$i]->t_rate / $data2[$i]->booked_count,2),$data2[$i]->currency) );
				} else {
					fputcsv( $this->fp, array($v->date,$v->hotel_name,$v->sd_rate,$v->t_rate,$v->currency,'','','',$data2[$i]->hotel_name,number_format($data2[$i]->sd_rate / $data2[$i]->booked_count,2),number_format($data2[$i]->t_rate / $data2[$i]->booked_count,2),$data2[$i]->currency) );	
				}
					
			} else {				
				if( $airline->grouptype==3 )
				{
					fputcsv( $this->fp, array($v->date,$v->gh_airline,$v->hotel_name,$v->sd_rate,$v->t_rate,$v->currency) );
				} else {
					fputcsv( $this->fp, array($v->date,$v->hotel_name,$v->sd_rate,$v->t_rate,$v->currency) );
				}	
			}				
			$i++;
		}			
		$this->closeStream();	
	}
	
	
	/**
	 * Export revenue booked details to CSV file
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */
	public function exportRevenueBooked( $beginDate , $endDate )
	{
		
		$airline = $this->getAirline();
				
		$this->setPeriodDate($beginDate, $endDate);
		
		//get data
		$data = $this->getRevenueBooked($this->getBeginDate(), $this->getEndDate());
						
		$this->setHeader('Revenue booked', 'Revenue_Booked_'.$beginDate.'_'.$endDate.'.csv');	
		
		
		$data2 = array();
		$tmpArray = array();
		
		foreach ($data as $v) {
			if( ! isset($tmpArray[$v->hotel_id]) ) {
				$tmpArray[$v->hotel_id] = new stdClass();
				$tmpArray[$v->hotel_id]->hotel_name = $v->hotel_name;
				$tmpArray[$v->hotel_id]->currency = $v->currency;
				$tmpArray[$v->hotel_id]->revenue_booked = 0;				
			}			
			$tmpArray[$v->hotel_id]->revenue_booked += $v->revenue_booked;
		}
		
		$i=0;
		foreach ($tmpArray as $k => $v) {
			$data2[$i] = $v;
			$i++;
		}		

		if( $airline->grouptype==3 )
		{
			fputcsv($this->fp, array('','','','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','Airline','Hotel Name','Revenue booked','Currency','','','','Hotel Name','Total Revenue booked','Currency'));
		} else {
			fputcsv($this->fp, array('','','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','Hotel Name','Revenue booked','Currency','','','','Hotel Name','Total Revenue booked','Currency'));	
		}	
		
		$i = 0;		
		foreach ($data as $v) {
			if( isset($data2[$i]) ) {				
				if( $airline->grouptype==3 )
				{
					fputcsv( $this->fp, array($v->date,$v->gh_airline,$v->hotel_name,$v->revenue_booked,$v->currency,'','','',$data2[$i]->hotel_name,$data2[$i]->revenue_booked,$data2[$i]->currency) );
				} else {
					fputcsv( $this->fp, array($v->date,$v->hotel_name,$v->revenue_booked,$v->currency,'','','',$data2[$i]->hotel_name,$data2[$i]->revenue_booked,$data2[$i]->currency) );
				}		
			} else {				
				if( $airline->grouptype==3 )
				{
					fputcsv( $this->fp, array($v->date,$v->gh_airline,$v->hotel_name,$v->revenue_booked,$v->currency) );
				} else {
					fputcsv( $this->fp, array($v->date,$v->hotel_name,$v->revenue_booked,$v->currency) );
				}	
			}					
			$i++;
		}
				
		$this->closeStream();		
	}
	
	
	/**
	 * Export IATA code reason details to CSV file
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */
	public function exportIATACodeReason( $beginDate , $endDate )
	{			
		$airline = $this->getAirline();
		$this->setPeriodDate($beginDate, $endDate);
		
		//get data
		$data = $this->getTopIATACodes($this->getBeginDate(), $this->getEndDate());
						
		$this->setHeader('IATA code reason', 'IATAcode_reason_'.$beginDate.'_'.$endDate.'.csv');	
		
		
		$data2 = array();
		$tmpArray = array();
		
		foreach ($data as $v) {
			if( ! isset($tmpArray[$v->delay_code]) ) {
				$tmpArray[$v->code] = new stdClass();
				$tmpArray[$v->code]->code = $v->code;
				$tmpArray[$v->code]->description = $v->description;	
				$tmpArray[$v->code]->seats_issued = 0	;									
			}			
			$tmpArray[$v->code]->seats_issued  += $v->seats_issued ;
		}
		
		$i=0;
		foreach ($tmpArray as $k => $v) {
			$data2[$i] = $v;
			$i++;
		}		

		if( $airline->grouptype==3 )
		{
			fputcsv($this->fp, array('','','','','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','Airline','IATA Code','Passengers','Flight Number','Flight Class','','','','Top IATA Code','Total Passengers','Reason'));
		} else {
			fputcsv($this->fp, array('','','','','','','','','From: '.$beginDate.' To: '.$endDate));
			fputcsv($this->fp, array('Date','IATA Code','Passengers','Flight Number','Flight Class','','','','Top IATA Code','Total Passengers','Reason'));
		}	
		
		$i = 0;		
		foreach ($data as $v) {
			$v->created = JString::substr($v->created, 0,10);		
			if( isset($data2[$i]) ) {
				if( $airline->grouptype==3 ){
					fputcsv( $this->fp, array($v->created,$v->gh_airline,$v->code,$v->seats_issued,$v->flight_code,$v->flight_class,'','','',$data2[$i]->code,$data2[$i]->seats_issued,$data2[$i]->description) );
				} else {
					fputcsv( $this->fp, array($v->created,$v->code,$v->seats_issued,$v->flight_code,$v->flight_class,'','','',$data2[$i]->code,$data2[$i]->seats_issued,$data2[$i]->description) );	
				}
					
			} else {				
				if( $airline->grouptype==3 )
				{
					fputcsv( $this->fp, array($v->created,$v->gh_airline,$v->code,$v->seats_issued,$v->flight_code,$v->flight_class) );
				} else {
					fputcsv( $this->fp, array($v->created,$v->code,$v->seats_issued,$v->flight_code,$v->flight_class) );
				}	
			}								
			$i++;
		}
				
		$this->closeStream();	
		
	}
	
	public function exportMarketPickup( $beginDate , $endDate )
	{
			
		$this->setPeriodDate($beginDate, $endDate);
		
		//get data
		$data = $this->getPercentageOfBookedRooms($this->getBeginDate(), $this->getEndDate());
						
		$this->setHeader('Market pick up', 'Market_Pickup_'.$beginDate.'_'.$endDate.'.csv');	
		
		
		
		$tmpArray = array();
		
		$totalBookedRooms=0;
		$totalRoomsInMarket=0;
		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {				
					$totalBookedRooms   += $v;
					$totalRoomsInMarket += $data[0][$k]; 
				}
			}
		}
		
		fputcsv($this->fp, array('','','','','','','','From: '.$beginDate.' To: '.$endDate));
		fputcsv($this->fp, array('Date','Number booked rooms','Number rooms in market','Picked Up(Percentage)','','','','Total Booked Rooms','Total rooms in market','Picked Up(Percentage)'));
		
		$i = 0;		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {				
					$percentage  = number_format( $v/($data[0][$k]+$v)*100, 2);
					if( $i == 0 ) {
						fputcsv( $this->fp, array($k,$v,$data[0][$k],$percentage.'%','','','',$totalBookedRooms,$totalRoomsInMarket,number_format( $totalBookedRooms/($totalBookedRooms+$totalRoomsInMarket)*100, 2).'%') );	
					} else {
						fputcsv( $this->fp, array($k,$v,$data[0][$k],$percentage.'%') );	
					}								
					$i++;
				}
			}
		}
				
		$this->closeStream();	
				
	}
	
	
	public function exportTransportationDetails($beginDate , $endDate)
	{		
			
		$this->setPeriodDate($beginDate, $endDate);
		
		//get data
		$data = $this->getTransportationDetails($this->getBeginDate(), $this->getEndDate());
		//print_r($data);die;						
		$this->setHeader('Transportation Details', 'Transportation_Details_'.$beginDate.'_'.$endDate.'.csv');	
				
		$tmpArray = array();
		
		$totalRoomsWithTransportation = 0;
		$totalBookedRooms 			  = 0;
		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {				
					$totalBookedRooms  				 += $v;
					$totalRoomsWithTransportation	 += $data[0][$k]; 
				}
			}
		}
				
		fputcsv($this->fp, array('','','','','','','From: '.$beginDate.' To: '.$endDate));
		fputcsv($this->fp, array('Date','Transport included','Transport Excluded','','','','Percentage Transport included','Percentage Transport Excluded'));
		
		$i = 0;		
		if( is_array($data[0]) && is_array($data[1]) ){
			if( count($data[0]) && count($data[1]) ) {
				foreach ($data[1] as $k => $v) {				
					if( $i == 0 ) {
						$pti = number_format( $totalRoomsWithTransportation/$totalBookedRooms * 100, 2);
						fputcsv( $this->fp, array($k,(int)$data[0][$k],$v-$data[0][$k],'','','',$pti.'%', (100-$pti).'%' ) );	
					} else {
						fputcsv( $this->fp, array($k,(int)$data[0][$k],$v-$data[0][$k]) );	
					}								
					$i++;
				}
			}
		}
				
		$this->closeStream();	
	}
	
	
	public function exportInitialBlockedPickup($beginDate , $endDate)
	{		
					
		$this->setPeriodDate($beginDate, $endDate);
		
		//get data
		$data = $this->getInitialBlockedDetails($this->getBeginDate(), $this->getEndDate());
		//print_r($data);die;						
		$this->setHeader('Initial blocked pick up', 'Initial_blocked_pick_up_'.$beginDate.'_'.$endDate.'.csv');	
				
		$tmpArray = array();
		
		$totalInitialRooms = 0;
		$totalClaimedRooms = 0;
		
		if(count($data)) {
			foreach ($data as $v) {				
				$totalInitialRooms   += $v->initial_rooms;
				$totalClaimedRooms	 += $v->claimed_rooms; 
			}
		}
		
		
				
		fputcsv($this->fp, array('','','','','','','From: '.$beginDate.' To: '.$endDate));
		fputcsv($this->fp, array('Date','Initial Rooms','Claimed Rooms','','','','Picked Up(Percentage)'));
		
		$i = 0;		
		if(count($data)) {		
			foreach ($data as $v) {				
				if( $i == 0 ) {
					$pti = number_format( $totalClaimedRooms/ $totalInitialRooms * 100, 2);
					fputcsv( $this->fp, array($v->date,(int)$v->initial_rooms,$v->claimed_rooms,'','','',$pti.'%' ) );	
				} else {
					fputcsv( $this->fp, array($v->date,(int)$v->initial_rooms,$v->claimed_rooms) );	
				}								
				$i++;
			}		
		}
				
		$this->closeStream();	
	}
	
	protected function getRoomnights( $dateFrom, $dateTo )
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;

		$user    = JFactory::getUser();
		$airline = $this->getAirline();	
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('a.claimed_rooms, c.name as hotel_name, b.date, b.hotel_id');
		$query->order('a.id ASC');
		
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');

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
		
		$statuses   = JRequest::getVar('blockStatus', array(), 'post', 'array');
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
    	
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
				
		return $result;
	}	
	
	protected function getAveragePrices( $dateFrom, $dateTo )
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;

		$user    = JFactory::getUser();
		$airline = $this->getAirline();
		
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('a.sd_rate, a.t_rate, c.name AS hotel_name, b.date, b.hotel_id,e.code AS currency');
		$query->order('a.id ASC');
		
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
		$query->innerJoin('#__sfs_hotel_taxes AS d ON d.hotel_id=b.hotel_id');
		$query->innerJoin('#__sfs_currency AS e ON e.id=d.currency_id');
		
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
    	
		$statuses   = JRequest::getVar('blockStatus', array(), 'post', 'array');
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
		
    	
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
				
		return $result;
	}
	
	protected function getRevenueBooked( $dateFrom, $dateTo )
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;

		$user    = JFactory::getUser();
		$airline = $this->getAirline();		
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('a.revenue_booked, c.name AS hotel_name, b.date, b.hotel_id,e.code AS currency');
		$query->order('a.id ASC');
		
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
		$query->innerJoin('#__sfs_hotel_taxes AS d ON d.hotel_id=b.hotel_id');
		$query->innerJoin('#__sfs_currency AS e ON e.id=d.currency_id');
		
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
    	
		$statuses   = JRequest::getVar('blockStatus', array(), 'post', 'array');
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
    	
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
				
		return $result;
	}
	
	protected function getTopIATACodes( $dateFrom, $dateTo )
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;
		
		$user    = JFactory::getUser();
		$airline = $this->getAirline();			
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);
		
		$query->select('b.code, b.description, a.seats_issued, a.created, a.flight_code, a.flight_class');
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
    	
		$query->where('a.created >='.$db->Quote($dateFrom.' 00:00:00'));
		$query->where('a.created <='.$db->Quote($dateTo.' 23:59:59'));
		
		$query->order('a.id ASC');
									
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;		
	}
	
	protected function getPercentageOfBookedRooms($dateFrom, $dateTo)
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;
		
		$user    = JFactory::getUser();
		$airline = $this->getAirline();		
		$db = JFactory::getDbo();	

		$result = array();
    	
    	// calculate total of loaded rooms of all hotels which is connected to the airline who was logged in to the system 
    	$query = $db->getQuery(true);   
    	 	
    	$query->select('a.date, SUM(a.sd_room_total+a.t_room_total) AS total');
    	
    	$query->from('#__sfs_room_inventory AS a');	    	
    	    	    	
    	$query->innerJoin('#__sfs_hotel_airports AS b ON b.hotel_id=a.hotel_id');
    	
    	$query->where('b.airport_id='.$airline->airport_id);
    	
		$query->where('a.date >='.$db->Quote($dateFrom));
		$query->where('a.date <='.$db->Quote($dateTo));
		    	
		$query->group('a.date');
		$query->order('a.date ASC');
		
    	$db->setQuery($query);
    	
    	//$result[0] = $db->loadObjectList();
    	$result[0] = $db->loadAssocList('date','total');
    	
        	    	
    	// calculate total of booked rooms of airline
    	$query = $db->getQuery(true);
    	$query->select('b.date, SUM(a.sd_room+a.t_room) AS total');
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
    	
		$query->where('b.date >='.$db->Quote($dateFrom));
		$query->where('b.date <='.$db->Quote($dateTo));		    	
    	    	
    	$query->group('b.date');
    	
    	$db->setQuery($query);
    	
    	$result[1] =  $db->loadAssocList('date','total');
    	    	
    	return $result;    				
	}
	
	protected function getTransportationDetails($dateFrom, $dateTo)
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;

		$user    = JFactory::getUser();
		$airline = $this->getAirline();	
		
		$db = JFactory::getDbo();		
				
		$result = array();

		$query = $db->getQuery(true);

		$query->select('SUM(a.sd_room+a.t_room) AS total_rooms, b.date');
				
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
    		
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		$query->where('a.transport=1');
		
		$query->group('b.date');
		$query->order('a.id ASC');
		
		$db->setQuery($query);
		$result[0] = $db->loadAssocList('date','total_rooms');
		
		$query = $db->getQuery(true);

		$query->select('SUM(a.sd_room+a.t_room) AS total_rooms, b.date');
				
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
    	
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
				
		$query->group('b.date');
		$query->order('a.id ASC');
		
		$db->setQuery($query);
		$result[1] = $db->loadAssocList('date','total_rooms');
				
		return $result;		
	}
	
	protected function getInitialBlockedDetails($dateFrom, $dateTo)
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;

		$user    = JFactory::getUser();
		$airline = $this->getAirline();			
		$db = JFactory::getDbo();		
				
		$result = null;

		$query = $db->getQuery(true);

		$query->select('SUM(a.sd_room+a.t_room) AS initial_rooms, SUM(a.claimed_rooms) AS claimed_rooms, b.date');
				
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
				
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
				
		$query->group('b.date');
		$query->order('a.id ASC');
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
				
		return $result;		
	}
	
	
	/**	 
	 * Set CSV HTTP header
	 */
	private function setHeader( $reportName, $fileName )
	{
		$user = JFactory::getUser();
		$airline = $this->getAirline();
		
		header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename='.$fileName);
        
		$fp = $this->getFileOpen();
				
		fputcsv($fp, array('SFS-web reporting'));
		
		$exportDate = JHTML::_('date',JFactory::getDate()->toSql(), JText::_('DATE_FORMAT_LC3'));
		fputcsv($fp, array('Creation date',$exportDate));
		
		fputcsv($fp, array('Created by',$user->get('username')));
		
		if( (int)$airline->iatacode_id > 0 ) {
			fputcsv($fp, array('Airline',$airline->name));
		} else {
			fputcsv($fp, array('Airline',$airline->company_name));
		}
		fputcsv($fp, array('Airport station',$airline->airport_code));	
			
		
		fputcsv($fp, array('Report Period','From: '.$this->getBeginDate().' To: '.$this->getEndDate()));
		fputcsv($fp, array('Report Name',$reportName));				
	}

}



/**
 * Hotel Report Class 
 */
class HReport {
	
	private static $_hreport = null;
	
	private $beginDate = null;
	private $endDate = null;
	private $fp = null;
	private $hotel = null;

	/**
	 * Get a hotel report object.
	 *
	 * Returns the global hotel report object, only creating it if it doesn't already exist.	 
	 *
	 * @return  HReport object	 
	 */
	public static function getInstance()
	{
		if ( ! self::$_hreport ) {
			self::$_hreport = new HReport();
		}
		return self::$_hreport;
	}
	
	public function setHotel($hotelId)
	{
		if(empty($hotelId))
		{
			die('Missed Hotel Id');
		}		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_hotel AS a');
				
		$query->where('a.id='.$hotelId);
		
		$db->setQuery($query);
		
		$this->hotel = $db->loadObject();
		
		return true;
	}
	
	public function getHotel()
	{
		return $this->hotel;
	}
	
	/**	 
	 * Method to set the period date for report.
	 * 
	 * @param string $beginDate
	 * @param string $endDate
	 */
	public function setPeriodDate( $mFrom, $yFrom,$mTo ,$yTo )
	{
		$this->beginDate = ( (int)$mFrom < 10 ) ? $yFrom.'-0'.$mFrom.'-01' : $yFrom.'-'.$mFrom.'-01';
		$this->endDate   = ( (int)$mTo < 10 ) ? $yTo.'-0'.$mTo.'-' : $yTo.'-'.$mTo.'-';
		
		$num = cal_days_in_month(CAL_GREGORIAN, $mTo , $yTo);
		$this->endDate	= $this->endDate.$num;
	}
	
	public function getBeginDate()
	{
		return $this->beginDate;
	}
	public function getEndDate()
	{
		return $this->endDate;
	}	
	public function getFileOpen()
	{
		$this->fp = fopen('php://output', 'w');
		return $this->fp;
	}	
	public function closeStream()
	{
		fclose($this->fp);	
	}
	
	/**
	 * Export roomnights details to CSV file
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */	
	public function exportRoomnights($mFrom, $yFrom,$mTo ,$yTo){
					
		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
				
		$this->setHeader('Total number roomnights', 'RoomnightsReport_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');
						
		//get data
		$data = $this->getRoomnights($this->getBeginDate(), $this->getEndDate());	
		
		$data2 = array();
		$tmpArray = array();
		
		foreach ($data as $v) {
			$ym = JString::substr($v->room_date, 0,7);
			
			if( ! isset($tmpArray[$ym]) ) {
				$tmpArray[$ym] = new stdClass();								
				$tmpArray[$ym]->room_date = $ym;			
				$tmpArray[$ym]->claimed_rooms = 0;				
			}			
			$tmpArray[$ym]->claimed_rooms += $v->claimed_rooms;
		}
		
		$i=0;
		foreach ($tmpArray as $k => $v) {
			$data2[$i] = $v;
			$i++;
		}		
						
		
		fputcsv($this->fp, array('Date','Airline Name','Nr of Rooms','','','','Date','Total Nr of rooms'));
		
		$i = 0;		
		foreach ($data as $v) {		
			
			$companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;
			
			if( isset($data2[$i]) ) {
				fputcsv( $this->fp, array($v->room_date,$companyName,$v->claimed_rooms,'','','',$data2[$i]->room_date,$data2[$i]->claimed_rooms) );	
			} else {				
				fputcsv( $this->fp, array($v->room_date,$companyName,$v->claimed_rooms) );				
			}					
			
			$i++;
		}
				
		$this->closeStream();
	}
	
	/**
	 * Export average prices details to CSV file
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */	
	public function exportAveragePrices($mFrom, $yFrom,$mTo ,$yTo){
				
		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
				
		$this->setHeader('Average room prices booked', 'Average_Room_Prices_Report_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');
						
		//get data
		$data = $this->getAveragePrices($this->getBeginDate(), $this->getEndDate());	
		
		$data2 = array();
		$tmpArray = array();
		
		foreach ($data as $v) {
			$ym = JString::substr($v->room_date, 0,7);
			
			if( ! isset($tmpArray[$ym]) ) {
				$tmpArray[$ym] = new stdClass();								
				$tmpArray[$ym]->room_date = $ym;			
				$tmpArray[$ym]->booked_count = 0;
				$tmpArray[$ym]->sd_rate = 0;
				$tmpArray[$ym]->t_rate = 0;
			}
			$tmpArray[$ym]->sd_rate += $v->sd_rate;
			$tmpArray[$ym]->t_rate += $v->t_rate;
			$tmpArray[$ym]->booked_count++;
		}
		
		$i=0;
		foreach ($tmpArray as $k => $v) {
			$data2[$i] = $v;
			$i++;
		}		
								
		fputcsv($this->fp, array('Date','Airline Name','SD Rate','T Rate','Nr of Rooms','','','','Date','ADR'));
		
		$i = 0;		
		foreach ($data as $v) {		
			
			$companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;
			
			if( isset($data2[$i]) ) {
				fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms,'','','',$data2[$i]->room_date, number_format($data2[$i]->sd_rate / $data2[$i]->booked_count,2) ) );	
			} else {				
				fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms) );				
			}					
			
			$i++;
		}
				
		$this->closeStream();
	}
	
	/**
	 * Export RevenueBooked details to CSV file
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */	
	public function exportRevenueBooked($mFrom, $yFrom,$mTo ,$yTo){
					
		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
				
		$this->setHeader('Revenue booked', 'Revenue_Booked_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');
						
		//get data
		$data = $this->getRevenueBooked($this->getBeginDate(), $this->getEndDate());	
		
		$data2 = array();
		$tmpArray = array();
		
		foreach ($data as $v) {
			$ym = JString::substr($v->room_date, 0,7);
			
			if( ! isset($tmpArray[$ym]) ) {
				$tmpArray[$ym] = new stdClass();								
				$tmpArray[$ym]->room_date = $ym;			
				$tmpArray[$ym]->revenue_booked = 0;				
			}			
			$tmpArray[$ym]->revenue_booked += $v->revenue_booked;
		}
		
		$i=0;
		foreach ($tmpArray as $k => $v) {
			$data2[$i] = $v;
			$i++;
		}		
								
		fputcsv($this->fp, array('Date','Airline Name','SD Rate','T Rate','Nr of Rooms','Net Revenue','','','','Date','Net revenue'));
		
		$i = 0;		
		foreach ($data as $v) {		
			
			$companyName = empty($v->company_name) ? $v->airline_name : $v->company_name;
			
			if( isset($data2[$i]) ) {
				fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms,$v->revenue_booked,'','','',$data2[$i]->room_date,$data2[$i]->revenue_booked ) );	
			} else {				
				fputcsv( $this->fp, array($v->room_date,$companyName,$v->sd_rate,$v->t_rate,$v->claimed_rooms,$v->revenue_booked) );				
			}					
			
			$i++;
		}
				
		$this->closeStream();
	}
	
	
	protected function getRoomnights($beginDate, $endDate)
	{
		$db 	= JFactory::getDbo();
		$hotel  = $this->getHotel();

		$query = $db->getQuery(true);
		$query->select('a.airline_id,a.claimed_rooms, c.company_name, b.date AS room_date, d.name AS airline_name');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');				
		$query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');
		
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}
	
	protected function getAveragePrices($beginDate, $endDate)
	{
		$db = JFactory::getDbo();
		$hotel  = $this->getHotel();

		$query = $db->getQuery(true);
		$query->select('a.airline_id,a.sd_rate,a.t_rate,a.claimed_rooms, c.company_name, b.date AS room_date, d.name AS airline_name');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');				
		$query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');
		
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		
		return $rows;
	}
	
	protected function getRevenueBooked($beginDate, $endDate)
	{
		$db = JFactory::getDbo();
		$hotel  = $this->getHotel();

		$query = $db->getQuery(true);
		$query->select('a.airline_id,a.sd_rate,a.t_rate,a.claimed_rooms,a.revenue_booked, c.company_name, b.date AS room_date, d.name AS airline_name');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');				
		$query->leftJoin('#__sfs_iatacodes AS d ON d.id=c.iatacode_id');
		
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
	
		return $rows;
	}
	
	
	/**
	 * Export roomnights details of all the hotels
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */	
	public function exportRoomnightsForHotels($mFrom, $yFrom,$mTo ,$yTo){

		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
		//get data
		$data = $this->getRoomnightsForHotels($this->getBeginDate(), $this->getEndDate());	
		
						
		$this->setHeader('Total number roomnights', 'RoomnightsReport_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');						

		$resultHeaders = array('#');
		
		$resultHotels = array();
		
		foreach ($data as $row)
		{						
			$moy = JString::substr($row->room_date,0,7);
			
			if( ! isset($resultHeaders[$moy]) )
			{
				$resultHeaders[$moy] = JHtml::_('date',$row->room_date,'Y-M');				
			}
			
			if( ! isset($resultHotels[$row->hotel_id]) )
			{
				$resultHotels[$row->hotel_id] = new stdClass();
				$resultHotels[$row->hotel_id]->hotel_name = $row->hotel_name;
				$resultHotels[$row->hotel_id]->claimed_rooms = array();
			}
			
			if( ! isset($resultHotels[$row->hotel_id]->claimed_rooms[$moy]) )
			{
				$resultHotels[$row->hotel_id]->claimed_rooms[$moy] = 0;
			}
			
			$resultHotels[$row->hotel_id]->claimed_rooms[$moy] += $row->claimed_rooms;
			
		}
		
		fputcsv($this->fp, array(''));
		fputcsv($this->fp, array(''));
		
		fputcsv($this->fp, $resultHeaders);
		
		$footer = array('Total');
		
		foreach ($resultHotels as $hotel)
		{
			$array = array();
			$array[] = $hotel->hotel_name;
			
			foreach ($resultHeaders as $k=>$v)
			{
				if( $v != '#' )
				{
					if( !isset($footer[$k]) )
					{
						$footer[$k] = 0;
					}
					if( isset($hotel->claimed_rooms[$k]) )
					{
						$array[$k] = $hotel->claimed_rooms[$k];
						$footer[$k] += $array[$k];
					} else {
						$array[$k] = '';
					}
				}
			}

			fputcsv($this->fp, $array);
		}
		
		fputcsv($this->fp, array(''));
		fputcsv($this->fp, array(''));
		fputcsv($this->fp, $footer);
						
		$this->closeStream();
	}
	
	protected function getRoomnightsForHotels($beginDate, $endDate)
	{
		$db 	= JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('a.hotel_id,a.claimed_rooms, b.date AS room_date, c.name AS hotel_name');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=a.hotel_id');				
		
			
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
			
		return $rows;
	}
	
	
	/**
	 * Export revenue booked details of all the hotels
	 *
	 * @param  string  $beginDate report begin date 
	 * @param  string  $endDate  report end date
	 *	 
	 */	
	public function exportRevenuesForHotels($mFrom, $yFrom,$mTo ,$yTo){

		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
		//get data
		$data = $this->getRevenuesForHotels($this->getBeginDate(), $this->getEndDate());	
		
						
		$this->setHeader('Revenue booked', 'Revenue_Booked_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');						

		$resultHeaders = array('#');
		
		$resultHotels = array();
		
		foreach ($data as $row)
		{						
			$moy = JString::substr($row->room_date,0,7);
			
			if( ! isset($resultHeaders[$moy]) )
			{
				$resultHeaders[$moy] = JHtml::_('date',$row->room_date,'Y-M');				
			}
			
			if( ! isset($resultHotels[$row->hotel_id]) )
			{
				$resultHotels[$row->hotel_id] = new stdClass();
				$resultHotels[$row->hotel_id]->hotel_name = $row->hotel_name;
				$resultHotels[$row->hotel_id]->revenue_booked = array();
			}
			
			if( ! isset($resultHotels[$row->hotel_id]->revenue_booked[$moy]) )
			{
				$resultHotels[$row->hotel_id]->revenue_booked[$moy] = 0;
			}
			
			$resultHotels[$row->hotel_id]->revenue_booked[$moy] += $row->revenue_booked;
			
		}
		
		fputcsv($this->fp, array(''));
		fputcsv($this->fp, array(''));
		
		fputcsv($this->fp, $resultHeaders);
		
		$footer = array('Total');
		
		foreach ($resultHotels as $hotel)
		{
			$array = array();
			$array[] = $hotel->hotel_name;
			
			foreach ($resultHeaders as $k=>$v)
			{
				if( $v != '#' )
				{
					if( !isset($footer[$k]) )
					{
						$footer[$k] = 0;
					}
					if( isset($hotel->revenue_booked[$k]) )
					{
						$array[$k] = $hotel->revenue_booked[$k];
						$footer[$k] += $array[$k];
					} else {
						$array[$k] = '';
					}
				}
			}

			fputcsv($this->fp, $array);
		}
		
		fputcsv($this->fp, array(''));
		fputcsv($this->fp, array(''));
		fputcsv($this->fp, $footer);
						
		$this->closeStream();
	}
	
	protected function getRevenuesForHotels($beginDate, $endDate)
	{
		$db = JFactory::getDbo();
		$hotel  = $this->getHotel();

		$query = $db->getQuery(true);
		$query->select('a.hotel_id,a.claimed_rooms,a.revenue_booked, c.name AS hotel_name, b.date AS room_date');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=a.hotel_id');
				
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
	
		return $rows;
	}
	
	
	public function exportAveragesForHotels($mFrom, $yFrom,$mTo ,$yTo){

		$this->setPeriodDate($mFrom, $yFrom, $mTo, $yTo);
		//get data
		$data = $this->getAveragesForHotels($this->getBeginDate(), $this->getEndDate());	
									
		$this->setHeader('Average room prices booked', 'Average_Room_Prices_Report_'.$this->getBeginDate().'_'.$this->getEndDate().'.csv');						

		$resultHeaders = array('#');
		
		$resultHotels = array();
		
		foreach ($data as $row)
		{						
			$moy = JString::substr($row->room_date,0,7);
			
			if( ! isset($resultHeaders[$moy]) )
			{
				$resultHeaders[$moy] = JHtml::_('date',$row->room_date,'Y-M');				
			}
			
			if( ! isset($resultHotels[$row->hotel_id]) )
			{
				$resultHotels[$row->hotel_id] = new stdClass();
				$resultHotels[$row->hotel_id]->hotel_name = $row->hotel_name;
				$resultHotels[$row->hotel_id]->sd_rate = array();
				$resultHotels[$row->hotel_id]->number_booking = array();
			}
			
			if( ! isset($resultHotels[$row->hotel_id]->sd_rate[$moy]) )
			{
				$resultHotels[$row->hotel_id]->sd_rate[$moy] = 0;
			}
			if( ! isset($resultHotels[$row->hotel_id]->number_booking[$moy]) )
			{
				$resultHotels[$row->hotel_id]->number_booking[$moy] = 0;
			}
			
			$resultHotels[$row->hotel_id]->sd_rate[$moy] += floatval($row->sd_rate);
			$resultHotels[$row->hotel_id]->number_booking[$moy] = $resultHotels[$row->hotel_id]->number_booking[$moy] + 1; 
			
		}
		
		fputcsv($this->fp, array(''));
		fputcsv($this->fp, array(''));
		
		fputcsv($this->fp, $resultHeaders);
		
		$footer = array('Total');
		
		foreach ($resultHotels as $hotel)
		{
			$array = array();
			$array[] = $hotel->hotel_name;
			
			foreach ($resultHeaders as $k=>$v)
			{
				if( $v != '#' )
				{
					if( !isset($footer[$k]) )
					{
						$footer[$k] = 0;
					}
					if( isset($hotel->number_booking[$k]))
					{
						$avg = number_format( $hotel->sd_rate[$k] / $hotel->number_booking[$k] ,2);
						$array[$k] = $avg;						
					} else {
						$array[$k] = '';
					}
				}
			}

			fputcsv($this->fp, $array);
		}
		
								
		$this->closeStream();
	}
	
	protected function getAveragesForHotels($beginDate, $endDate)
	{
		$db = JFactory::getDbo();
		$hotel  = $this->getHotel();

		$query = $db->getQuery(true);
		$query->select('a.hotel_id,a.sd_rate,a.t_rate,a.claimed_rooms, c.name AS hotel_name, b.date AS room_date');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_hotel AS c ON c.id=a.hotel_id');		
				
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');		
		$query->where('b.date >='.$db->Quote($beginDate));
		$query->where('b.date <='.$db->Quote($endDate));
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		
		return $rows;
	}
	
	
	/**	 
	 * Set CSV HTTP header
	 */
	private function setHeader( $reportName, $fileName )
	{
		$user = JFactory::getUser();
		$hotel  = $this->getHotel();
		
		header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename='.$fileName);
        
		$fp = $this->getFileOpen();
				
		fputcsv($fp, array('SFS-web reporting'));
		
		$exportDate = JHTML::_('date',JFactory::getDate()->toSql(), JText::_('DATE_FORMAT_LC3'));
		fputcsv($fp, array('Creation date',$exportDate));
		
		fputcsv($fp, array('Created by',$user->get('username')));
		
		if($hotel){
			fputcsv($fp, array('Hotel',$hotel->name));
		}
		
		fputcsv($fp, array('Report Period','From: '.$this->getBeginDate().' To: '.$this->getEndDate()));
		fputcsv($fp, array('Report Name',$reportName));				
	}

}


