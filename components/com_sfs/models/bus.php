<?php
defined('_JEXEC') or die;

class SfsModelBus extends JModelLegacy
{	
	protected $_bus = null;
	protected $_pagination = null;
		
    protected function populateState()
    {
        $app    = JFactory::getApplication();     

		// Get the pagination request variables
		$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		//$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);

		$value = trim(JRequest::getString('reference_number'));
		$this->setState('block.reference_number',$value);
		
		$value = JRequest::getVar('blockstatus');
		$this->setState('block.status',$value);
				
		$value	= JRequest::getString('date_from');
		$this->setState('block.from',$value);
		
		$value	= JRequest::getString('date_to');		
		$this->setState('block.to',$value);
		
		$value	= JRequest::getString('showall');		
		$this->setState('list.showall',$value);        
        
        $params    = $app->getParams('com_sfs');  	        	
        $this->setState('params', $params);
    }   
    
    public function getBus()
    {
    	if( $this->_bus === null )
    	{
    		$this->_bus = SFactory::getBus();	
    	}    	    	    	
    	return $this->_bus;
    }

    public function getProfiles()
    {
    	$bus = $this->getBus();
    	if($bus->id)
    	{
    		$profiles = $bus->getProfiles();
    		return $profiles;
    	}    	
    	return null;
    }
    
	public function getRates()
	{		
		$bus 		= $this->getBus();
		$profiles   = $this->getProfiles();	
		
		$profile 	= null;	
		$profile_id = JRequest::getInt('profile_id');
						
		foreach ($profiles as $row)
		{
			if( (int)$row->id == $profile_id )
			{
				$profile = $row;
				break; 
			}
		}
		
		if( $profile == null )
		{
			$this->setError('Bus profile id: '.$profile_id.' was not found');
			return false;
		}
		
		$db		 = $this->getDbo();
		$query   = $db->getQuery(true);				
		
		$result = array();
				
		// Gets Hotels
		$query->select('a.id AS hotel_id, a.name, b.ring');
		$query->from('#__sfs_hotel AS a');
		$query->innerJoin('#__sfs_hotel_backend_params AS b ON b.hotel_id=a.id');
		
		$query->where('a.block = 0' );		
		$query->order('b.ring ASC');
		
		$db->setQuery($query);		
		$hotels = $db->loadObjectList();
		
		// Gets fare rate
		$query->clear();
		$query->select('a.*');
		$query->from('#__sfs_group_transportation_rates AS a');		
		$query->where('a.group_transportation_type_id = ' . $profile_id );	
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$profileRates = array();
		
		if( count($rows) )
		{
			foreach ($rows as $row)
			{
				if( ! isset( $profileRates[$row->ring] ) )
				{
					$profileRates[$row->ring] = array();					
				}	
				$profileRates[$row->ring][$row->hotel_id] = $row;
			}
		}
		
		if( count($hotels) )
		{
			foreach ($hotels as $row)
			{
				if( ! isset( $result[$row->ring] ) )
				{
					$result[$row->ring] = new stdClass();
					$result[$row->ring]->ring   = $row->ring;
					
					// One way rate for all ring hotels 
					if( isset($profileRates[$row->ring]) && isset($profileRates[$row->ring][0]) )
					{
						$result[$row->ring]->day_fare 		= $profileRates[$row->ring][0]->day_fare;
					}
					
					$result[$row->ring]->hotels = array();
				}	
				
				if( isset($profileRates[$row->ring]) && isset($profileRates[$row->ring][$row->hotel_id]) )
				{
					$row->day_fare 		= $profileRates[$row->ring][$row->hotel_id]->day_fare;					
				}
				
				$result[$row->ring]->hotels[] = $row;
				
			}
		}
		
		return $result;
	}
	
	public function getBookings()
	{
		$bus 		= $this->getBus();	
			
		$db 		= $this->getDbo();
		$query   	= $db->getQuery(true);			
		
		$query->select('a.*,u.name AS booked_name,h.name AS hotel_name,d.name AS terminal');
		$query->from('#__sfs_transportation_reservations AS a');
		
		$query->innerJoin('#__users AS u ON u.id=a.user_id');		
		$query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');		
		$query->leftJoin('#__sfs_iatacodes as d ON d.id=a.departure');
		
		$query->select('b.company_name,c.name AS airline_name');
		$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes as c ON c.id=b.iatacode_id');			
		
		$query->where('a.transport_company_id = '.(int)$bus->id);
		
		$query->order('a.booked_date DESC');
		
		$db->setQuery($query);		
		$db->execute();
		
		$total = $db->getNumRows();
		
		jimport('joomla.html.pagination');
		$limit = (int) $this->getState('list.limit');		
		$this->_pagination = new JPagination($total, $this->getStart(), $limit);
		
		$db->setQuery($query,$this->getStart(), $limit);
		$result = $db->loadObjectList();
		
		if( $db->getErrorNum() )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		return $result;	
	}
	
	public function getStart()
	{
		return $this->getState('list.start');
	}
	
	public function getPagination()
	{				
		return $this->_pagination;
	}
	
    
    /**
     * 
     * Save bus company details
     */
    public function save()
    {
    	$table = JTable::getInstance('Bus','JTable');
    	
    	$id = JRequest::getInt('id',0);    	    	
    	$table->load($id);
    	
    	if($table->get('id'))
    	{
    		$busDetails  = JRequest::getVar('busdetails', array() , 'post' , 'array');
    		$ignore      = array();
    		foreach ($busDetails as $key => $value)
    		{
    			$value = trim($value);
    			if( empty($value) )
    			{
    				$ignore[] = $key;    				
    			}    			
    		}
    		
    		$notifications = JRequest::getVar('notifications', array() , 'post' , 'array');	
			
    		foreach ($notifications as &$rows )
    		{
    			if(count($rows))
    			{
    				foreach ($rows as $offset => $value)
    				{
    					$value = trim($value);
		    			if(empty($value))
		    			{
		    				array_splice($rows, $offset,1);    				
		    			}
    				}
    			}
    		}
    		
			$registry = new JRegistry;
			$registry->loadArray($notifications);
			$busDetails['notification'] = $registry->toString();
    		
	    	if( !$table->bind($busDetails, $ignore) ) {
				$this->setError($table->getError());
				return false;					
			}
						
			if( !$table->store() ) {
				$this->setError( $table->getError() );
				return false;					
			}
			
    		return true;
    	}
    	
    	$this->setError('Bus was not found');
    	return false;
    }
    
	public function saveRates()
	{
		$db		 	= $this->getDbo();		
		$bus 		= $this->getBus();
		$profiles   = $this->getProfiles();	
		
		$profile 	= null;	
		$profile_id = JRequest::getInt('profile_id');
						
		foreach ($profiles as $row)
		{
			if( (int)$row->id == $profile_id )
			{
				$profile = $row;
				break; 
			}
		}
		
		if( $profile == null )
		{
			$this->setError('Bus profile id: '.$profile_id.' was not found');
			return false;
		}
						
		// Update hotel rates			
		$ringRates = JRequest::getVar('ringrates', array(), 'post', 'array');
		
		//empty old data
		$query = 'DELETE FROM #__sfs_group_transportation_rates WHERE group_transportation_type_id='.(int)$profile_id;
		$db->setQuery($query);
		$db->query();
		
		$insertQueryValues = array();
		
		foreach ($ringRates as $ring => $items)
		{
			foreach ($items as $hotelId => $rate)
			{
				if( floatval($rate['day_fare']) > 0 )
				{
					$tempQuery = '('.$profile_id.','.(int)$hotelId.','.(int)$ring;
					
					$tempQuery .= ','.floatval($rate['day_fare']);						
					$tempQuery .= ')';
					
					$insertQueryValues[] = $tempQuery;
				}
			}
		}
		
		if(count($insertQueryValues))
		{
			$insertQuery = 'INSERT INTO #__sfs_group_transportation_rates(group_transportation_type_id,hotel_id,ring,day_fare) VALUES';
			$insertQuery .= implode(',', $insertQueryValues);
		
			$db->setQuery($insertQuery);
			$db->query();				
		}
		
		return true;		
	}    
    
    public function saveProfiles()
    {
    	$db  		= $this->getDbo();
    	$bus 		= $this->getBus();
    	$profiles 	= JRequest::getVar('profiles' , array() , 'post' ,  'array');
    	$id 		= JRequest::getVar('id');
    	$count 		= JRequest::getVar('count');    	
    	$arrRate = array();
    	
    	for($i = 0; $i <= $count; $i++){
    		$row = new stdClass();

    		if( JRequest::getVar('rate_three_'.$i) == ""){
    			$row->airport_from = JRequest::getVar('airport_from_'.$i);
    			$row->airport_to = JRequest::getVar('airport_to_'.$i);
    			$row->rate = JRequest::getVar('rate_'.$i);

    		}else{
    			$row->rate_first 	= JRequest::getVar('rate_first_'.$i);
	    		$row->rate_second 	= JRequest::getVar('rate_second_'.$i);
	    		$row->rate_three 	= JRequest::getVar('rate_three_'.$i);
    		}
    		
    		array_push($arrRate , $row);
    	}
    	
    	if($id){
    		if(JRequest::getVar('rate_three_0') != "" ){    			
    			$query = "UPDATE #__sfs_group_transportation_types SET name = '".$profiles[$id]['name']."', seats='".$profiles[$id]['seats']."',rate='" .json_encode($arrRate). "'   WHERE id=".$id;
    			$db->setQuery($query);
    			$db->query();
    		}else{
    			$query = "UPDATE #__sfs_group_transportation_types SET name = '".$profiles[$id]['name']."', seats='".$profiles[$id]['seats']."',rate_fixed='" .json_encode($arrRate). "'   WHERE id=".$id;
    			$db->setQuery($query);
    			$db->query();
    		}
    		
    	}else{
    		if(JRequest::getVar('rate_three_0') != "" ){
    			$insertQuery = "INSERT INTO #__sfs_group_transportation_types(group_transportation_id,name,seats,rate,rate_fixed,published) VALUES('".$bus->id."','".$profiles[0]['name']."','".$profiles[0]['seats']."','" .json_encode($arrRate). "','','1')";
    		}else{
    			$insertQuery = "INSERT INTO #__sfs_group_transportation_types(group_transportation_id,name,seats,rate,rate_fixed,published) VALUES('".$bus->id."','".$profiles[0]['name']."','".$profiles[0]['seats']."','','" .json_encode($arrRate). "','1')";
    		}
    				
	
			$db->setQuery($insertQuery);
			$db->query();	
    	}	

    	
    	
    	return true;
    }    
    
    public function removeProfile()
    {    	
    	$db = $this->getDbo();
    	$profile_id = JRequest::getInt('profile_id');
    	
    	$profiles = $this->getProfiles();
    	
    	foreach ($profiles as $profile)
    	{
    		if( (int)$profile->id == $profile_id )
    		{
    			$query = 'UPDATE #__sfs_group_transportation_types SET published=0 WHERE id='.$profile_id;
    			$db->setQuery($query);
    			if(!$db->query()){
    				$this->setError($db->getErrorMsg());
    				return false;
    			}
    			return true;
    		}
    	}
    	
    	return false;
    }
    
    public function removeLineRate()
    {    	
    	$arrRate = array();
    	$first = JRequest::getInt('rate_first');
    	$profile_id = JRequest::getInt('profile_id');    	
    	$profiles = $this->getProfiles();    	       
    	$rate = json_decode($profiles[0]->rate);
    	
    	foreach ($rate as $key => $value) {
    		$listRate = new stdClass();
    		if((int)$value->rate_first != $first){
		    	$listRate->rate_first = $value->rate_first;
		    	$listRate->rate_second = $value->rate_second;
		    	$listRate->rate_three = $value->rate_three;
		    	array_push($arrRate, $listRate);
    		}
    	}

    	$db = $this->getDbo();    	

		$query = "UPDATE #__sfs_group_transportation_types SET rate = '".json_encode($arrRate). "'  WHERE id=".$profile_id;
		$db->setQuery($query);
		if(!$db->query()){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;    	
    }

    public function removeLineRateFixed(){
    	$db = $this->getDbo();
    	$arrRate = array();
    	$profile_id = JRequest::getInt('profile_id'); 
    	$to = JRequest::getVar('airport_to'); 	
    	$profiles = $this->getProfiles();
    	echo $to;
    	foreach ($profiles as $key => $value) {
    		if((int)$value->id == $profile_id){
    			foreach (json_decode($value->rate_fixed) as $k => $val) {
    				$listRate = new stdClass();
    				if($val->airport_to != $to){
    					$listRate->airport_from = $val->airport_from;
				    	$listRate->airport_to = $val->airport_to;
				    	$listRate->rate = $val->rate;
				    	array_push($arrRate, $listRate);
    				}
    			}
    		}
    	}

    	$query = "UPDATE #__sfs_group_transportation_types SET rate_fixed = '".json_encode($arrRate). "'  WHERE id=".$profile_id;
		$db->setQuery($query);
		if(!$db->query()){
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;  
    }

    public function getListAirport(){
    	$db = $this->getDbo();

    	$bus = SFactory::getBus();

    	$query = 'SELECT b.id, b.code FROM #__sfs_group_transportation_airports as a';
    	$query .= ' LEFT JOIN #__sfs_iatacodes as b ON b.id = a.airport_id';
    	$query .= ' WHERE a.group_transportation_id = ' . $bus->id;

    	$db->setQuery($query);			
			
		$result = $db->loadObjectList();

    	//print_r($result); die();

    	return $result;
    }

}
