<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

		
class SfsModelAirlinereporting extends JModel
{
	
	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication();
	
		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}		
	}
	
	
	public function getRoomnights()
    {       	
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	
	    	    	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	
	    	   
	    	$query->select('SUM(a.claimed_rooms) AS roomnights,c.name');
	    	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');

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
    
	public function getRoomnightsChartData() 
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
	    	$db = $this->getDbo();    		
	    	
	    	$query = $db->getQuery(true);
	    	   
	    	$query->select('SUM(a.claimed_rooms) AS roomnights,c.name,b.date');		    	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    		 	    	
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
    
    public function getRevenues()
    {       	
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    		    	    	
	    	$db = $this->getDbo();    		
	    		    	
	    	$query = $db->getQuery(true);

	    	$query->select('SUM(a.revenue_booked) AS revenue_booked,c.name');
	    	
	    	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    		    	
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
	public function getRevenueChartData() 
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
				    	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);	    		   

	    	$query->select('SUM(a.revenue_booked) AS revenue_booked,c.name,b.date');
	    	 	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    		    	
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
    
    
	public function getAverages()
    {       	
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	 	
	    	$db = $this->getDbo();    		
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);

	    	$query->select('SUM(a.sd_rate) AS rate_total, COUNT(a.id) AS booked_total, c.name');
	    
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    
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
    
	public function getAverageChartData() 
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
				    	
	    	$db = $this->getDbo();    		
	    	
	    	$query = $db->getQuery(true);
	
	    	$query->select('SUM(a.sd_rate) AS rate_total, COUNT(a.id) AS booked_total, c.name,b.date');	
	    	 	
	    	$query->from('#__sfs_reservations AS a');
	    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	$query->innerJoin('#__sfs_hotel AS c ON c.id=b.hotel_id');
	    	
	    	
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
    
    
    public function getIataCodePercentages()
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
    		
    		$db = $this->getDbo();
    		
    		$query = $db->getQuery(true);
    		$query->select('b.code, b.description, SUM(a.seats_issued) AS seat_total');
    		$query->from('#__sfs_flights_seats AS a');
			$query->innerJoin('#__sfs_delaycodes AS b ON b.code=a.delay_code');
									
			$query->where('a.created >='.$db->Quote($this->getState('filter.date_from').' 00:00:00'));
			$query->where('a.created <='.$db->Quote($this->getState('filter.date_to').' 23:59:59'));			
			
			$query->group('a.delay_code');
			$query->order('seat_total DESC');	   	    	
			
	    	$db->setQuery($query);	    	
	    	$rows = $db->loadObjectList();
	    	
	    	return $rows;
    	}
    	return null;    	    
    }
    
    
    public function getMarketPercentages()    
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	
	    	$db = $this->getDbo();
	    	
	    	$result = array();
	    	
	    	// calculate total of loaded rooms of all hotels which is connected to the airline who was logged in to the system 
	    	$query = $db->getQuery(true);   
	    	 	
	    	$query->select('SUM(a.sd_room_total+a.t_room_total) AS total');
	    	$query->from('#__sfs_room_inventory AS a');	    	    	    	
	    	$query->innerJoin('#__sfs_hotel_airports AS b ON b.hotel_id=a.hotel_id');	    		    	
	    	
			$query->where('a.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('a.date <='.$db->Quote($this->getState('filter.date_to')));	    	
	    	
	    	$db->setQuery($query);
	    	
	    	$result[0] = (int)$db->loadResult();
	    	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	$query->select('SUM(a.sd_room+a.t_room) AS total');
	    	$query->from('#__sfs_reservations AS a');
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');	  

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
	    		    	
	    	$db->setQuery($query);
	    	
	    	$result[1] = $db->loadResult();   
	    	
	    	return $result;    
    	}	 
    	return null; 
    }
    
    public function getTransportationPercentages()    
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
	    	
	    	$db = $this->getDbo();
	       	
	    	$result = array();
	    	
			$query = $db->getQuery(true);
	
			$query->select('SUM(a.sd_room+a.t_room) AS total_rooms');
					
			$query->from('#__sfs_reservations AS a');
	
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
									
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));
			$query->where('a.transport=1');
			
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
			
			$result[0] = (int)$db->loadResult();
			
			$query = $db->getQuery(true);
	
			$query->select('SUM(a.sd_room+a.t_room) AS total_rooms');
					
			$query->from('#__sfs_reservations AS a');
	
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
								
			$query->where('b.date >='.$db->Quote($this->getState('filter.date_from')));
			$query->where('b.date <='.$db->Quote($this->getState('filter.date_to')));
			
			$query->where('a.transport=0');
			
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
			$result[1] = (int)$db->loadResult();
		
	    	
	    	return $result;
    	}
    	return null;
    }    
    
    
    public function getInitialPercentages()
    {
    	if( $this->getState('filter.date_from') && $this->getState('filter.date_to') ) {
			
	    	$db = $this->getDbo();
	       	
	    	// calculate total of booked rooms of airline
	    	$query = $db->getQuery(true);
	    	$query->select('SUM(a.sd_room+a.t_room) AS initial_rooms,SUM(a.claimed_rooms) AS claimed_rooms');
	    	$query->from('#__sfs_reservations AS a');    	
	    	$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
	    	
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
   
    public function drawChart()
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
				$chartData->setAxisName(0,"Revenue booked in ".$sfs_system_currency);
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
    
    
    /**
     * Export
     */
	
    public function exportRoomnights($from,$until)
    {
    	
    	if( empty($from) && empty($until) ) return false;

		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('SUM(a.claimed_rooms) AS total_rooms, b.date,a.airline_id,c.company_name,c.iatacode_id');				
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');			
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
		
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

		$query->where('b.date >='.$db->Quote($from) );
		$query->where('b.date <='.$db->Quote($until));
		
		$query->group('a.airline_id,b.date');
		
		$query->order('b.date DESC');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
		if( count($rows) )
		{
			$airlines = $this->filterAirlines($rows);
			
			foreach ($rows as & $row )
			{
				if( isset($airlines[$row->airline_id]) )
				{
					$row->company_name = $airlines[$row->airline_id];
				}
				
				if( ! isset($result[$row->airline_id]) )
				{
					$result[$row->airline_id] = new stdClass();
					$result[$row->airline_id]->name = $row->company_name;
					$result[$row->airline_id]->dates = array();					
				}
				
				if((int)$row->total_rooms > 0){				
					if( !isset( $result[$row->airline_id]->dates[$row->date] ) )
					{
						$result[$row->airline_id]->dates[$row->date] = 0;
					}
					
					$result[$row->airline_id]->dates[$row->date] += $row->total_rooms;
				}
			}	

		}
					
		return $result;
    	
    }
        
	public function exportRevenues( $from,$until )
	{
		if( empty($from) && empty($until) ) return false;
				
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);
				
		$query->select('SUM(a.revenue_booked) AS total_revenue, b.date, a.airline_id,c.company_name,c.iatacode_id');
				
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
				    	
		$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');
		
		$query->where('b.date >='.$db->Quote($from) );
		$query->where('b.date <='.$db->Quote($until));
		
		$query->group('a.airline_id,b.date');
		
		$query->order('b.date DESC');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
		if( count($rows) )
		{
			$airlines = $this->filterAirlines($rows);
			
			foreach ($rows as & $row )
			{
				if( isset($airlines[$row->airline_id]) )
				{
					$row->company_name = $airlines[$row->airline_id];
				}
				
				if( ! isset($result[$row->airline_id]) )
				{
					$result[$row->airline_id] = new stdClass();
					$result[$row->airline_id]->name = $row->company_name;
					$result[$row->airline_id]->dates = array();					
				}
				
				if((int)$row->total_revenue > 0){				
					if( !isset( $result[$row->airline_id]->dates[$row->date] ) )
					{
						$result[$row->airline_id]->dates[$row->date] = 0;
					}
					
					$result[$row->airline_id]->dates[$row->date] += $row->total_revenue;
				}
			}	

		}
				
		return $result;
	}
	
	public function exportAverages( $dateFrom, $dateTo )
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;
						
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('a.sd_rate, a.t_rate, b.date, b.hotel_id,a.airline_id,c.company_name,c.iatacode_id');

		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
		    	
		//$query->where('(a.status='.$db->Quote('A').' OR a.status='.$db->Quote('R').')');
		
		$query->where('b.date >='.$db->Quote($dateFrom) );
		$query->where('b.date <='.$db->Quote($dateTo));
		
		$query->order('b.date DESC');
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		
		$result = array();
		
		if( count($rows) )
		{
			$airlines = $this->filterAirlines($rows);
			
			foreach ($rows as & $row )
			{
				if( isset($airlines[$row->airline_id]) )
				{
					$row->company_name = $airlines[$row->airline_id];
				}
				
				if( ! isset($result[$row->airline_id]) )
				{
					$result[$row->airline_id] = new stdClass();
					$result[$row->airline_id]->name = $row->company_name;
					$result[$row->airline_id]->dates = array();					
				}
				
				if($row->sd_rate > 0 || $row->t_rate){	
								
					if( !isset( $result[$row->airline_id]->dates[$row->date] ) )
					{
						$result[$row->airline_id]->dates[$row->date] = new stdClass();
						$result[$row->airline_id]->dates[$row->date]->sd_rate = array();
						$result[$row->airline_id]->dates[$row->date]->t_rate  = array();
					}
					if( $row->sd_rate  > 0 )
						$result[$row->airline_id]->dates[$row->date]->sd_rate[] = $row->sd_rate;
					if( $row->t_rate  > 0 )
						$result[$row->airline_id]->dates[$row->date]->t_rate[] = $row->t_rate;
				}
			}	

		}
				
		return $result;
	}
	
	public function exportIATACodeReason( $dateFrom, $dateTo )
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;
				
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);
		
		$query->select('b.code, b.description, a.seats_issued, a.created, a.flight_code, a.flight_class, a.airline_id, c.company_name,c.iatacode_id');
		$query->from('#__sfs_flights_seats AS a');
		$query->innerJoin('#__sfs_delaycodes AS b ON b.code=a.delay_code');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
		
		    	
		$query->where('a.created >='.$db->Quote($dateFrom.' 00:00:00'));
		$query->where('a.created <='.$db->Quote($dateTo.' 23:59:59'));
		
		$query->order('a.created DESC');
									
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
		
		if( count($rows) )
		{
			$airlines = $this->filterAirlines($rows);
			
			foreach ($rows as & $row )
			{
				if( isset($airlines[$row->airline_id]) )
				{
					$row->company_name = $airlines[$row->airline_id];
				}
				
				if( ! isset($result[$row->airline_id]) )
				{
					$result[$row->airline_id] = new stdClass();
					$result[$row->airline_id]->name = $row->company_name;
					$result[$row->airline_id]->dates = array();					
				}
				
				$created = JString::substr($row->created, 0, 10);
				
						
				if( !isset( $result[$row->airline_id]->dates[$created] ) )
				{
					$result[$row->airline_id]->dates[$created] = array();
				}
				
				if( ! in_array($row->code, $result[$row->airline_id]->dates[$created]) )
					$result[$row->airline_id]->dates[$created][] = $row->code;
			
			}	

		}
				
		
		return $result;		
	}
	
	
	public function getHeaderDates($from,$until)
    {
    	
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('b.date, SUM(a.claimed_rooms) AS total_rooms');				
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');		
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
		
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

		$query->where('b.date >='.$db->Quote($from) );
		$query->where('b.date <='.$db->Quote($until));
		
		$query->group('b.date');
		
		$query->order('b.date DESC');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
							
		foreach ($rows as $r){
			if($r->total_rooms) $result[] = $r->date;	
		}
		
		return $result;
    }
    
	public function getRevenueHeaderDates($from,$until)
    {
    	
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('b.date, SUM(a.revenue_booked) AS total_revenue');				
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');		
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');

		$query->where('b.date >='.$db->Quote($from) );
		$query->where('b.date <='.$db->Quote($until));
		
		$query->group('b.date');
		
		$query->order('b.date DESC');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
							
		foreach ($rows as $r){
			if($r->total_revenue) $result[] = $r->date;	
		}
		
		return $result;
    }
    
	public function getAverageHeaderDates($from,$until)
    {
    	
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);

		$query->select('b.date');				
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');		
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');

		$query->where('b.date >='.$db->Quote($from) );
		$query->where('b.date <='.$db->Quote($until));
		
		$query->group('b.date');
		
		$query->order('b.date DESC');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
							
		foreach ($rows as $r){
			$result[] = $r->date;	
		}
		
		return $result;
    }
    
	public function getIATAHeaderDates($from,$until)
    {
    	
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);
		
		$query->select('b.code, b.description, a.seats_issued, a.created, a.flight_code, a.flight_class, a.airline_id, c.company_name,c.iatacode_id');
		$query->from('#__sfs_flights_seats AS a');
		$query->innerJoin('#__sfs_delaycodes AS b ON b.code=a.delay_code');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
		
		    	
		$query->where('a.created >='.$db->Quote($from.' 00:00:00'));
		$query->where('a.created <='.$db->Quote($until.' 23:59:59'));
		
		$query->order('a.created DESC');
									
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
							
		foreach ($rows as $r){
			$created = JString::substr($r->created, 0, 10);
			
			if( ! in_array($created, $result) )
			{
				$result[] = $created;
			}
		}
		
		return $result;
    }
    
    
    public function getTopIATACodes( $dateFrom, $dateTo )
	{
		if( empty($dateFrom) && empty($dateTo) ) return false;
				
		$db = JFactory::getDbo();				

		$query = $db->getQuery(true);
		
		$query->select('b.code, a.seats_issued,a.seats, a.flight_code, a.flight_class, a.airline_id, c.company_name,c.iatacode_id');
		$query->from('#__sfs_flights_seats AS a');
		$query->innerJoin('#__sfs_delaycodes AS b ON b.code=a.delay_code');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id=a.airline_id');
		
		    	
		$query->where('a.created >='.$db->Quote($dateFrom.' 00:00:00'));
		$query->where('a.created <='.$db->Quote($dateTo.' 23:59:59'));
		
		$query->order('a.created DESC');
									
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$result = array();
		
		foreach ($rows as $row)
		{
			if( ! isset($result[$row->code]) )
			{
				$result[$row->code] = new stdClass();				
				$result[$row->code]->code = $row->code;	
				$result[$row->code]->seats = 0;
				$result[$row->code]->seats_issued = 0;			
			}
			$result[$row->code]->seats += $row->seats;
			$result[$row->code]->seats_issued += $row->seats_issued;
		}
		
		return $result;
	}
    
    protected function filterAirlines($data)
    {
    	if( count($data))
    	{    		
    		$iataCodes = $this->getIataCodes();
    		$airlines = array();
    		foreach ($data as $row)
    		{
    			if( (int)$row->iatacode_id > 0 )
    			{
    				$airlines[$row->airline_id] = $iataCodes[$row->iatacode_id];
    			}
    		}	
    		
    		return $airlines;
    	}
    	return null;
    }
    
    protected function getIataCodes()
    {
    	$db = $this->getDbo();
    	$query = 'SELECT id,name FROM #__sfs_iatacodes WHERE type=1';
    	$db->setQuery($query);
    	
    	$codes = $db->loadAssocList('id','name');
    	    	
    	return $codes;
    }
    
}



