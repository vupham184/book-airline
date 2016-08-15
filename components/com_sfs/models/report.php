<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class SfsModelReport extends JModel
{   
	private $_data = null;
	
    protected function populateState()
    {
        // Get the application object.
        $app    = JFactory::getApplication();        
        $params = $app->getParams('com_sfs');   		
        // Load the parameters.
        $this->setState('params', $params);
        
        //requests from hotel
    	$value = JRequest::getInt('m_from');
    	$this->setState('filter.month_from',$value);
    	
    	$value = JRequest::getInt('y_from');
    	$this->setState('filter.year_from',$value);
    	
    	$value = JRequest::getInt('m_to');
    	$this->setState('filter.month_to',$value);
    	
    	$value = JRequest::getInt('y_to');
    	$this->setState('filter.year_to',$value);
    	
    	//requests from airline
    	$value = JRequest::getVar('date_from');
    	$this->setState('filter.date_from',$value);
    	
    	$value = JRequest::getVar('date_to');
    	$this->setState('filter.date_to',$value);    	
    }   
           
    protected function loadData() 
    {
    	if( empty($this->_data) ) {
    		$db = JFactory::getDbo();
    		$hotel = SFactory::getHotel();
            $query = $db->getQuery(true);
            $query->select('a.*');
            $query->from('#__sfs_hotel_reporting AS a');
            $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
    		
	    	$fromStr = ( $this->getState('filter.month_from') < 10 ) ? $this->getState('filter.year_from').'-0'.$this->getState('filter.month_from').'-01' : $this->getState('filter.year_from').'-'.$this->getState('filter.month_from').'-01';
	    	$tStr = ( $this->getState('filter.month_to') < 10 ) ? $this->getState('filter.year_to').'-0'.$this->getState('filter.month_to').'-01' : $this->getState('filter.year_to').'-'.$this->getState('filter.month_to').'-01';

            $query->where('a.hotel_id = '.$hotel->id);
            $query->where('a.date >= '.$db->Quote($fromStr));
            $query->where('a.date <= '.$db->Quote($tStr));
            $query->order('a.date ASC');
	    	
	    	$db->setQuery($query);
	    	$this->_data = $db->loadObjectList();    
	    	if(count($this->_data)) {
	    		foreach ($this->_data as &$value) {
	    			$value->average_price = floatval( $value->room_price_total / $value->number_booking );
	    		}
	    	}		
    	}
    }
    
    public function getData(){        	
    	if( $this->getState('filter.month_from') && $this->getState('filter.year_from')
    		 && $this->getState('filter.month_to') && $this->getState('filter.year_to') ){    		 	
    		if( empty($this->_data) ) {
    			$this->loadData();
    		}    		 		
    	}    	
    	return $this->_data;
    }
    
	public function getHotelReportData($drawType=1)
	{
	    $data = $this->getData();
    	if( empty($data) ) return null;   	
    	return $data;
	}    
	
	public function getDataCount() {
    	if( $this->getState('filter.month_from') && $this->getState('filter.year_from')
    		 && $this->getState('filter.month_to') && $this->getState('filter.year_to') ){    		 	
    		$db = $this->getDbo();
    		$hotel = SFactory::getHotel();
    		$query = 'SELECT COUNT(*) FROM #__sfs_hotel_reporting';
    		
	    	$fromStr = ( $this->getState('filter.month_from') < 10 ) ? $this->getState('filter.year_from').'-0'.$this->getState('filter.month_from').'-01' : $this->getState('filter.year_from').'-'.$this->getState('filter.month_from').'-01';
	    	$tStr = ( $this->getState('filter.month_to') < 10 ) ? $this->getState('filter.year_to').'-0'.$this->getState('filter.month_to').'-01' : $this->getState('filter.year_to').'-'.$this->getState('filter.month_to').'-01';
	    
	    	$query .= ' WHERE hotel_id='.$hotel->id;
	    	$query .= ' AND date >= '.$db->Quote($fromStr);
	    	$query .= ' AND date <= '.$db->Quote($tStr);    	    						
	    	
	    	$db->setQuery($query);	 		
	    	
	    	$result = $db->loadResult();
	    	return (int) $result;
    	}  
    	return FALSE;		
	}
    
    public function getTopHotelData( $drawType = 1 )
    {       	
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) 
    	{
            $airline = SFactory::getAirline();
            $airline_current = SAirline::getInstance()->getCurrentAirport();
            $airport_current_id = $airline_current->id;
            $airport_current_code = $airline_current->code;
	    	
    		$db = $this->getDbo();	    	
	    	$query = $db->getQuery(true);
	    	
	    	if($drawType==1) {
	    		$query->select('SUM(a.claimed_rooms) AS roomnights,c.name,SUM(a.sd_room+a.t_room+a.s_room+a.q_room) AS blocked_rooms');
	    		$query->order('roomnights DESC');	
	    	} elseif ($drawType==2) {
	    		$query->select('SUM(a.sd_rate + a.s_rate + a.t_rate + a.q_rate + a.breakfast + a.lunch + a.	mealplan) AS rate_total, COUNT(a.id) AS booked_total, c.name');
	    		$query->order('booked_total DESC');
	    	} elseif ($drawType==3){
	    		$query->select('SUM(a.revenue_booked) AS revenue_booked,c.name');
	    		$query->order('revenue_booked DESC');
	    	}    	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
	    	$query->select('e.code AS currency_code,e.symbol AS currency_symbol');
	    	$query->leftJoin('#__sfs_hotel_taxes AS d ON d.hotel_id=b.hotel_id');
	    	$query->leftJoin('#__sfs_currency AS e ON e.id=d.currency_id');
	    	
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( $airline->grouptype==3 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}		    	
	    	}

            if((int)$airport_current_id != -1)
            {
                $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=c.id AND ha.airport_id='.$airport_current_id);
            }
	    	
	    	$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').' OR a.status='.$db->Quote('T').' OR a.status='.$db->Quote('P').')');
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$query->group('b.hotel_id');    	
	    	    	    	
	    	$db->setQuery($query);

	    	$result = $db->loadObjectList();
	    	
	    	return $result;
    	}
    	return null;
    }

    public function getAirlineChartData($drawType=1) 
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			$db = $this->getDbo();
	    	$airline = SFactory::getAirline();
            $airline_current = SAirline::getInstance()->getCurrentAirport();
            $airport_current_id = $airline_current->id;
	    	$db = $this->getDbo();    		
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	
    		if($drawType==1){
	    		$query->select('SUM(a.claimed_rooms) AS roomnights,h.name,b.date,SUM(a.sd_room+a.t_room) AS blocked_rooms');
	    	} elseif ($drawType==2){
	    		$query->select('SUM(a.sd_rate) AS rate_total, COUNT(a.id) AS booked_total, h.name,b.date');
	    	} elseif ($drawType==3){
	    		$query->select('SUM(a.revenue_booked) AS revenue_booked,h.name,b.date');
	    	}  	    	
	    	 	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');
	    	
	    	$query->where('a.airline_id='.$airline->id);
	    	
	    	if( $airline->grouptype==3 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}	
	    	}
            if((int)$airport_current_id != -1)
            {
                $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
            }
	    	
	    	$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').' OR a.status='.$db->Quote('T').' OR a.status='.$db->Quote('P').')');
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
    
    public function getPercentages($drawType=1)
    {
    	$result = null;
    	switch ($drawType) 
    	{
    		case 1:
    			$result = $this->getIataCodePercentages();
    			break;
    		case 2:
    			$result = $this->getMarketPercentages();
    			break;
    		case 3:
    			$result = $this->getTransportationPercentages();   
    			break;
    		case 4:
    			$result = $this->getInitialPercentages();
    			break;				
    		default:    			
    			break;	
    	}
    	return $result;
    }
    
    protected function getIataCodePercentages()
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
    		$airline = SFactory::getAirline();
            $airline_current = SAirline::getInstance()->getCurrentAirport();
            $airport_current_id = $airline_current->id;
    		$db = $this->getDbo();
    		
    		$query = $db->getQuery(true);
    		$query->select('b.code, b.description, SUM(a.seats_issued) AS seat_total');
    		$query->from('#__sfs_flights_seats AS a');
			$query->innerJoin('#__sfs_delaycodes AS b ON b.code=a.delay_code');
			
			$query->where('a.airline_id='.$airline->id);
            if((int)$airport_current_id != -1)
            {
                $query->where('a.airport_id='.$airport_current_id);
            }

    		if( $airline->grouptype==3 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}	
	    	}
			
			$query->where('a.created >='.$db->Quote($this->getState('filter.date_from').' 00:00:00'));
			$query->where('a.created <='.$db->Quote($this->getState('filter.date_to').' 23:59:59'));			
			
			$query->group('a.delay_code');
			$query->order('seat_total DESC');	   	    	
			
	    	$db->setQuery($query);
//	    	echo (string)$query;die;
	    	$rows = $db->loadObjectList();
	    	return $rows;
    	}
    	return null;    	    
    }

    
    protected function getMarketPercentages()    
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	$airline = SFactory::getAirline();
            $airline_current = SAirline::getInstance()->getCurrentAirport();
            $airport_current_id = $airline_current->id;
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
            $query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');
	    	
	    	$query->where('a.airline_id='.$airline->id);

    		if( $airline->grouptype==3 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}	
	    	}
            if((int)$airport_current_id != -1)
            {
                $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
            }
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));		    	
	    	$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');
	    	
	    	$db->setQuery($query);
	    	
	    	$result[1] = $db->loadResult();   
	    	
	    	return $result;    
    	}	 
    	return null; 
    }
    
    public function getTransportationPercentages()    
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	$airline = SFactory::getAirline();
            $airline_current = SAirline::getInstance()->getCurrentAirport();
            $airport_current_id = $airline_current->id;
	    	$db = $this->getDbo();
	       	
	    	$result = array();
	    	
			$query = $db->getQuery(true);
	
			$query->select('SUM(a.sd_room+a.t_room) AS total_rooms');
					
			$query->from('#__sfs_reservations AS a');
	
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');

            $query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');
			
			$query->where('a.airline_id='.$airline->id);	

    		if( $airline->grouptype==3 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}	
	    	}
            if((int)$airport_current_id != -1)
            {
                $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
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
			$airline = SFactory::getAirline();
            $airline_current = SAirline::getInstance()->getCurrentAirport();
            $airport_current_id = $airline_current->id;
	    	$db = $this->getDbo();
	       	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	$query->select('SUM(a.sd_room+a.t_room+a.s_room+a.q_room) AS initial_rooms,SUM(a.claimed_rooms) AS claimed_rooms');
	    	$query->from('#__sfs_reservations AS a');    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
            $query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');
	    	
	    	$query->where('a.airline_id='.$airline->id);
	    	
    		if( $airline->grouptype==3 )
	    	{	    		
	    		$gh_airline = (int)JRequest::getInt('gh_airline');
	    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
	    		
	    		if($gh_airline > 0){
	    			$query->where('ghr.airline_id='.$gh_airline);
	    		}	
	    	}
            if((int)$airport_current_id != -1)
            {
                $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
            }
	    	
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));
				    	
	    	$db->setQuery($query);
	    	$result = $db->loadObject();

	    	return $result;     	
    	}
    	return null;
    }    
    
	public function getMarketHotels() 
	{
		$result = array();

    	$airline = SFactory::getAirline();
    	$db = $this->getDbo();
    	
    	$market_calendar = JRequest::getVar('market_calendar');
    	$distance 		 = JRequest::getInt('distance');

        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$time_zone = $airline_current->time_zone;

        if((int)$airport_current_id == -1)
        {
            require_once JPATH_SITE.'/modules/mod_sfs_change_airport/helper.php';
            require_once JPATH_SITE.'/components/com_sfs/libraries/core.php';
            foreach(modSfsChangeAirportHelper::getAirlineAirportData() as $airport) {
                $list_airports[] = $airport->id;
            }
            $list_airports = implode(",", $list_airports);
        }
        else
        {
            $list_airports = $airport_current_id;
        }



		for( $i=1; $i<=5; $i++ ) {
			
			$query = $db->getQuery(true);
			
			$query->select('a.hotel_id,
			        a.s_room_rate, a.s_room_total,
			        a.sd_room_rate, a.sd_room_total,
			        a.t_room_rate, a.t_room_total,
			        a.q_room_rate, a.q_room_total
			        ');
			$query->from('#__sfs_room_inventory AS a');
            $query->innerJoin('#__sfs_hotel_airports AS d ON d.hotel_id=a.hotel_id AND d.airport_id IN ('.$list_airports.')');
			$query->innerJoin('#__sfs_hotel AS c ON c.id=a.hotel_id');			
			
			if ( $market_calendar && strlen($market_calendar) == 10 ) {
				$query->where( 'a.date='.$db->quote($market_calendar) );
			} else {
				$now = SfsHelperDate::getDate('now','Y-m-d', $time_zone);    			    			
				$query->where( 'a.date='.$db->quote($now) );
			}

			$query->where( 'c.block=0' );			
			$query->where( 'c.star='.$i );
			$query->where( 'a.sd_room_total > 0' );
						
			switch ($distance) {
				case 1:
					$query->where('d.distance<=5');
					break;
				case 2:
					$query->where('d.distance<=10');
					break;
				case 3:
					$query->where('d.distance<=20');
					break;
				case 4:
					$query->where('d.distance<=40');
					break;
				case 5:
					$query->where('d.distance>40');
					break;
				default:
					break;				
			}			
			$db->setQuery($query);
			$result[$i] = $db->loadObjectList();
		}
    	return $result;
    }

    public function drawPie()
    {
    	include(SFS_PATH_CHART.DS."class/pData.class.php");
		include(SFS_PATH_CHART.DS."class/pDraw.class.php");
		include(SFS_PATH_CHART.DS."class/pImage.class.php");   
 		include(SFS_PATH_CHART.DS."class/pPie.class.php");

 		/* Create and populate the pData object */
 		$points = JRequest::getVar('points');
 		$points = explode(',', $points);
 		$chartData = new pData();
 		$chartData->addPoints($points,"ScoreA");
 		$chartData->addPoints(array("A0","B1"),"Labels");
 		$chartData->setAbscissa("Labels"); 
 		
 		if(count($points)>2) {
 			$chartData->loadPalette(SFS_PATH_CHART.DS.'palettes'.DS.'sfs.color',true);
 		} else {
 			$chartData->loadPalette(SFS_PATH_CHART.DS.'palettes'.DS.'sfs2.color',true);
 		}
 	
 		/* Define the absissa serie */
 		$chartData->addPoints(array("<10","10<>20","20<>40","40<>60","60<>80",">80"),"Labels");
 		$chartData->setAbscissa("Labels");

 		/* Create the pChart object */
 		$storePicture = new pImage(150,150,$chartData);
 		 		
		$storePicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/calibri.ttf","FontSize"=>8));
		$storePicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>255,"G"=>255,"B"=>255,"Alpha"=>0)); 
 		/* Create the pPie object */
 		$PieChart = new pPie($storePicture,$chartData);
 		 		
 		/* Draw a simple pie chart */
 		$PieChart->draw2DPie(74,75,array("Border"=>TRUE));
 		
		
    	/* Render the picture (choose the best way) */
    	$storePicture->autoOutput(JURI::base()."images/draw2DPie.labels.png");   	
    }
    
    public function drawLine()
    {
    	include(SFS_PATH_CHART.DS."class/pData.class.php");
		include(SFS_PATH_CHART.DS."class/pDraw.class.php");
		include(SFS_PATH_CHART.DS."class/pImage.class.php");   
 		
 		/* Create and populate the pData object */
 		$points = JRequest::getVar('points');
 		$points = explode(',', $points);
    	if(count($points)==1) {
			$points[] = $points[0];
		} 		
		
 		$chartData = new pData();		
 		
 		 
		$chartData->addPoints( $points,'Top Hotels');
		
 		//$chartData->addPoints( array(12,24,20,35,50,20,60),'Top Hotels');
		
		$chartData->setPalette( 'Top Hotels' ,array("R"=>98, "G"=>163, "B"=>221,"Dash"=>TRUE,"DashR"=>170,"DashG"=>220,"DashB"=>190));
		
		
 		$chartData->addPoints(array("January\n2011","February\n2011","March\n2011"),"Labels");
 		
 		$chartData->setSerieDescription("Labels","Months");
 		$chartData->setAbscissa("Labels"); 		

		$storePicture = new pImage(254,254,$chartData);
		$storePicture->Antialias = TRUE; 
		$storePicture->setGraphArea(20,5,250,190);
		
		$storePicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>8));
	
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
		
    	/* Render the picture (choose the best way) */
    	$storePicture->autoOutput(JURI::base()."images/tophotels.png");   	
    }    
    
	public function drawHotelChart()
    {
    	include(SFS_PATH_CHART.DS."class/pData.class.php");
		include(SFS_PATH_CHART.DS."class/pDraw.class.php");
		include(SFS_PATH_CHART.DS."class/pImage.class.php");   
		
 		$points = JRequest::getVar('points');
 		$dates  =  JRequest::getVar('dates');
 		$type = JRequest::getInt('type');
		
 		$chartData = new pData();		
 		
		switch ($type) {
			case 1:
				$chartData->setAxisName(0,"Number of room");
				break;
			case 2:
				$hotel = SFactory::getHotel();
				$currency = $hotel->getTaxes()->currency_name; 
				$chartData->setAxisName(0,"Average room price in ".$currency);
				break;
			case 3:
				$hotel = SFactory::getHotel();
				$currency = $hotel->getTaxes()->currency_name; 
				$chartData->setAxisName(0,"Revenue in ".$currency);
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
				
		$storePicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/calibri.ttf","FontSize"=>8));
	
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
    	$storePicture->autoOutput(JURI::base()."images/hotelChart".$user->id.".png");   	
    }        
    
	public function drawAirlineChart()
    {
    	include(SFS_PATH_CHART.DS."class/pData.class.php");
		include(SFS_PATH_CHART.DS."class/pDraw.class.php");
		include(SFS_PATH_CHART.DS."class/pImage.class.php");   
		
 		$points = JRequest::getVar('points');
 		$dates  =  JRequest::getVar('dates');
 		$type = JRequest::getInt('type');
		
 		$chartData = new pData();	

 		$params = JComponentHelper::getParams('com_sfs');
 		
 		$currency = $params->get('sfs_system_currency','EUR');
 		
		switch ($type) {
			case 1:
				$chartData->setAxisName(0,"Number of room");
				break;
			case 2:								
				$chartData->setAxisName(0,"Average room prices in ".$currency);
				break;
			case 3:				
				$chartData->setAxisName(0,"Revenue booked in ".$currency);
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
				
		$storePicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/calibri.ttf","FontSize"=>8));
	
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
    	$storePicture->autoOutput(JURI::base()."images/airlineChart".$user->id.".png");   	
    }    
	
	//lchung 
	function getTotalbookingoverview()
	{
		require_once JPATH_COMPONENT.'/libraries/report.php';
		$reportObject = AirlineReport::getInstance();
		
		$check_userkey   = $this->getCheckUserkey();
		return $reportObject->getDataNewReportAirline( $check_userkey, true );
	}
	
	public function getCheckUserkey() {
		$userkey = JRequest::getVar('uk');
		if ( $userkey == '' )
			return array();
		$db 	= JFactory::getDbo();
        $query	= $db->getQuery(true);
        $query->select('a.*, b.secret_key, d.airport_id, e.name, c.airline_id, e.code as airport_code, e.currency_code');
        $query->from('#__users AS a');
        $query->innerJoin('#__sfs_contacts AS b ON b.user_id=a.id');
		
		$query->innerJoin('#__sfs_airline_user_map AS c ON c.user_id=a.id');
		$query->innerJoin('#__sfs_airline_details AS d ON c.airline_id=d.id');
		$query->innerJoin('#__sfs_iatacodes AS e ON e.id=d.airport_id');
		
		
        $query->where(' b.secret_key = "' . $userkey . '"');
		$query->where(' a.block = 0');
		//echo (string)$query;die;
        $db->setQuery($query);
        $d = $db->loadObject();
       return $d;
	}
	
	public function drawTotalbookingoverviewChart()
    {
		$session = JFactory::getSession();
		$report_points_dates = $session->get("report_points_dates");
		
    	include(SFS_PATH_CHART.DS."class/pData.class.php");
		include(SFS_PATH_CHART.DS."class/pDraw.class.php");
		include(SFS_PATH_CHART.DS."class/pImage.class.php");   
		
		///$points = JRequest::getVar('points');
 		///$dates  =  JRequest::getVar('dates');
		$points = '';
		if(isset( $report_points_dates['points'] ))
			$points = $report_points_dates['points'];
		$dates = '';
		if(isset( $report_points_dates['dates'] ))
			$dates = $report_points_dates['dates'];
			
 		$type = JRequest::getInt('type');
		
 		$chartData = new pData();	

 		$params = JComponentHelper::getParams('com_sfs');
 		
 		$currency = $params->get('sfs_system_currency','EUR');
 		
		/*switch ($type) {
			case 1:
				$chartData->setAxisName(0,"Number of room");
				break;
			case 2:								
				$chartData->setAxisName(0,"Average room prices in ".$currency);
				break;
			case 3:				
				$chartData->setAxisName(0,"Revenue booked in ".$currency);
				break;		
		}*/
		 	
		$chartData->setAxisName(0,"Number of room");	
 		$points = explode(',', $points);
    	if(count($points)==1) {
			$points[] = $points[0];
		} 
				
		$chartData->addPoints( $points,'Total booking overview');
		$chartData->setPalette( 'Total booking overview' ,array("R"=>98, "G"=>163, "B"=>221,"Dash"=>TRUE,"DashR"=>170,"DashG"=>220,"DashB"=>190));
		
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
				
		$storePicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/calibri.ttf","FontSize"=>8));
	
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
		
		$check_userkey   = $this->getCheckUserkey();
		if ( count( $check_userkey ) ) {
			$user_id = $check_userkey->id;
		}
		else {
			$user = JFactory::getUser();
			$user_id = $user->id;
		}
    	/* Render the picture (choose the best way) */
    	$storePicture->autoOutput(JURI::base()."images/totalbookingoverviewChart".$user_id.".png");   	
    } 
	//End lchung
    
}

