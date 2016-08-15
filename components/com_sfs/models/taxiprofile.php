<?php
defined('_JEXEC') or die;

class SfsModelTaxiprofile extends JModelLegacy
{	
	protected $_taxi = null;
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
    
    public function getTaxi()
    {
    	if( $this->_taxi === null )
    	{
    		$this->_taxi = SFactory::getTaxi();	
    	}    	    	    	
    	return $this->_taxi;
    }

	public function getRates()
	{
		$taxi    = $this->getTaxi();
		
		if ( ! $taxi )
		{
			return null;
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
		$query->from('#__sfs_taxi_hotel_rates AS a');
		
		$query->where('a.taxi_id = ' . (int) $taxi->id );	
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$taxiRates = array();
		
		if( count($rows) )
		{
			foreach ($rows as $row)
			{
				if( ! isset( $taxiRates[$row->ring] ) )
				{
					$taxiRates[$row->ring] = array();					
				}	
				$taxiRates[$row->ring][$row->hotel_id] = $row;
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
					if( isset($taxiRates[$row->ring]) && isset($taxiRates[$row->ring][0]) )
					{
						$result[$row->ring]->day_fare 		= $taxiRates[$row->ring][0]->day_fare;
						$result[$row->ring]->night_fare		= $taxiRates[$row->ring][0]->night_fare;
						$result[$row->ring]->weekend_fare	= $taxiRates[$row->ring][0]->weekend_fare;
					}
					
					$result[$row->ring]->hotels = array();
				}	
				
				if( isset($taxiRates[$row->ring]) && isset($taxiRates[$row->ring][$row->hotel_id]) )
				{
					$row->day_fare 		= $taxiRates[$row->ring][$row->hotel_id]->day_fare;
					$row->night_fare		= $taxiRates[$row->ring][$row->hotel_id]->night_fare;
					$row->weekend_fare	= $taxiRates[$row->ring][$row->hotel_id]->weekend_fare;
				}
				
				$result[$row->ring]->hotels[] = $row;
				
			}
		}
		
		return $result;
	}
	
	public function getBookings()
	{
		$taxi 		= $this->getTaxi();	
			
		$db 		= $this->getDbo();
		$query   	= $db->getQuery(true);			
		
		$query->select('a.*,u.name AS booked_name,h.name AS hotel_name,d.name AS terminal');
		$query->from('#__sfs_taxi_vouchers AS a');
		
		$query->innerJoin('#__users AS u ON u.id=a.booked_by');		
		$query->innerJoin('#__sfs_hotel AS h ON h.id=a.hotel_id');		
		$query->leftJoin('#__sfs_iatacodes as d ON d.id=a.terminal_id');
		
		$query->select('b.company_name,c.name AS airline_name');
		$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes as c ON c.id=b.iatacode_id');			
		
		$query->where('a.taxi_id = '.(int)$taxi->id);
		
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
    	$table = JTable::getInstance('Taxi','JTable');
    	
    	$id = JRequest::getInt('id',0);    	    	
    	$table->load($id);
    	
    	if($table->get('id'))
    	{
    		$taxiDetails  = JRequest::getVar('taxidetails', array() , 'post' , 'array');
    		$ignore      = array();
    		foreach ($taxiDetails as $key => $value)
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
			$taxiDetails['notification'] = $registry->toString();
    		
	    	if( !$table->bind($taxiDetails, $ignore) ) {
				$this->setError($table->getError());
				return false;					
			}
						
			if( !$table->store() ) {
				$this->setError( $table->getError() );
				return false;					
			}
			
    		return true;
    	}
    	
    	$this->setError('Taxi was not found');
    	return false;
    }
    
	public function saveRates()
	{
		$db		 	= $this->getDbo();		
		$taxi 		= $this->getTaxi();
		
		if ( ! $taxi )
		{
			return false;
		}
		
		$updateObject = new stdClass();
		$updateObject->id = $taxi->id;
		
		if( $taxi->getParam('enable_night_fare') == 1 ) {
			$fare_from_h 	= JRequest::getVar('fare_from_h');
			$fare_from_m 	= JRequest::getVar('fare_from_m');
			$fare_until_h 	= JRequest::getVar('fare_until_h');
			$fare_until_m 	= JRequest::getVar('fare_until_m');			
			$updateObject->fare_from  = $fare_from_h.':'.$fare_from_m;
			$updateObject->fare_until = $fare_until_h.':'.$fare_until_m;	
		}
		
		if( $taxi->getParam('enable_weekend_fare') == 1 ) {
			$wdays = JRequest::getVar('wdays', array(), 'post', 'array');
		  	
	    	if( is_array($wdays) && count($wdays) )
	    	{
	    		JArrayHelper::toInteger($wdays);
	    		$updateObject->available_days = implode(',', $wdays);
	    	} else {
	    		$updateObject->available_days = '';
	    	}			
		}
		
		if( $taxi->getParam('enable_night_fare') == 1 || $taxi->getParam('enable_weekend_fare') == 1) {		
	    	if( ! $db->updateObject('#__sfs_taxi_companies', $updateObject, 'id') )
	    	{
	    		$this->setError($db->getErrorMsg());
	    		return false;
	    	}
		}
		
    	// Update hotel rates			
		$ringRates = JRequest::getVar('ringrates', array(), 'post', 'array');
		
		//empty old data
		$query = 'DELETE FROM #__sfs_taxi_hotel_rates WHERE taxi_id='.$taxi->id;
		$db->setQuery($query);
		$db->query();
		
		$insertQueryValues = array();
		
		foreach ($ringRates as $ring => $items)
		{
			foreach ($items as $hotelId => $rate)
			{
				if( floatval($rate['day_fare']) > 0 || floatval($rate['night_fare']) > 0 || floatval($rate['weekend_fare']) > 0 )
				{
					$tempQuery = '('.$taxi->id.','.(int)$hotelId.','.(int)$ring;
					
					$tempQuery .= ','.floatval($rate['day_fare']);
					$tempQuery .= ','.floatval($rate['night_fare']);
					$tempQuery .= ','.floatval($rate['weekend_fare']);
					$tempQuery .= ')';
					
					$insertQueryValues[] = $tempQuery;
				}
			}
		}
		
		$insertQuery = 'INSERT INTO #__sfs_taxi_hotel_rates(taxi_id,hotel_id,ring,day_fare,night_fare,weekend_fare) VALUES';
		$insertQuery .= implode(',', $insertQueryValues);
		
		$db->setQuery($insertQuery);
		
		if( !$db->query() ) {
			$this->setError($db->getErrorMsg());
    		return false;
		}
    	
    	
		return true;	
	}    
    
}

