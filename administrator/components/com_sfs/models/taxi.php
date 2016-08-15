<?php
defined('_JEXEC') or die;

class SfsModelTaxi extends JModelLegacy
{

	protected $_taxi = null;
	protected $_taxi_companies = null;
	
	protected function populateState()
	{
		$airlineId = JRequest::getInt('airline_id');
		$this->setState('filter.airline_id',$airlineId);
	}
	
	public function getAirline()
	{
		$airlineId = $this->getState('filter.airline_id');
		
		if( $airlineId )
		{
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('c.company_name,d.name AS airline_name');
			$query->from('#__sfs_airline_details AS c');			
			$query->leftJoin('#__sfs_iatacodes AS d ON c.iatacode_id=d.id AND d.type=1');
			$query->where('c.id='.$airlineId);
			
			$db->setQuery($query);
			$airline = $db->loadObject();
			return $airline;
		}
		return null;
	}
	
	public function getTaxiDetails()
	{
		$airlineId = $this->getState('filter.airline_id');
		
		if( $airlineId && $this->_taxi == null )
		{			
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('a.*,b.*');
			$query->from('#__sfs_airline_taxidetail_map AS a');
			$query->innerJoin('#__sfs_taxi_details AS b ON b.id=a.taxi_detail_id');
			
			$query->where('a.airline_id = ' . (int) $airlineId);
			
			$db->setQuery($query);
			
			$this->_taxi = $db->loadObject();
		}
		return $this->_taxi;
	}
	
	public function getTaxiCompanies()
	{
		$airlineId = $this->getState('filter.airline_id');
		
		if( $this->_taxi_companies == null && $airlineId  ){
			
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('a.*,b.*');
			$query->from('#__sfs_airline_taxicompany_map AS a');
			$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
			
			$query->where('a.airline_id = ' . (int) $airlineId );
			
			$db->setQuery($query);
			
			$this->_taxi_companies = $db->loadObjectList();
		}
		
		return $this->_taxi_companies;
		
	}
	
	public function getTaxi($id = null)
	{
		$pk = ! empty($id) ? $id : JRequest::getInt('taxi_id');
		
		$items = $this->getTaxiCompanies();
		
		$result = null;
		
		foreach ($items as $item)
		{
			if( (int)$item->taxi_id == (int)$pk )
			{
				$result = & $item;
				break;
			}
		}
		
		return $result;
	}
	
	public function getHotelRates()
	{
		$airlineId = $this->getState('filter.airline_id');
		
		$db		 = $this->getDbo();
		$query   = $db->getQuery(true);
		
		$result = array();
		
		$taxi_id = JRequest::getInt('taxi_id');
		
		if( empty($taxi_id) ) return $result;
		
		// Gets Hotels
		$query->select('a.id AS hotel_id, a.name, b.ring');
		$query->from('#__sfs_hotel AS a');
		$query->innerJoin('#__sfs_hotel_backend_params AS b ON b.hotel_id=a.id');
		
		//$query->innerJoin('#__sfs_hotel_airports AS c ON c.hotel_id=a.id');
		//$query->where('c.airport_id = ' . (int) $airline->airport_id );
		
		$query->where('a.block = 0' );		
		$query->order('b.ring ASC');
		
		$db->setQuery($query);		
		$hotels = $db->loadObjectList();
		
		// Gets fare rate
		$query->clear();
		$query->select('a.*');
		$query->from('#__sfs_taxi_hotel_rates AS a');
		
		$query->where('a.taxi_id = ' . (int) $taxi_id );	
		
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
						$result[$row->ring]->day_fare 			= $taxiRates[$row->ring][0]->day_fare;
						$result[$row->ring]->night_fare			= $taxiRates[$row->ring][0]->night_fare;
						$result[$row->ring]->weekend_fare		= $taxiRates[$row->ring][0]->weekend_fare;
						$result[$row->ring]->km_rate			= $taxiRates[$row->ring][0]->km_rate;
						$result[$row->ring]->starting_tariff	= $taxiRates[$row->ring][0]->starting_tariff;
					}
					
					$result[$row->ring]->hotels = array();
				}	
				
				if( isset($taxiRates[$row->ring]) && isset($taxiRates[$row->ring][$row->hotel_id]) )
				{
					$row->day_fare 			= $taxiRates[$row->ring][$row->hotel_id]->day_fare;
					$row->night_fare		= $taxiRates[$row->ring][$row->hotel_id]->night_fare;
					$row->weekend_fare		= $taxiRates[$row->ring][$row->hotel_id]->weekend_fare;
					$row->km_rate			= $taxiRates[$row->ring][$row->hotel_id]->km_rate;
					$row->starting_tariff	= $taxiRates[$row->ring][$row->hotel_id]->starting_tariff;
				}
				
				$result[$row->ring]->hotels[] = $row;
				
			}
		} else {
			for($i=1;$i<=3;$i++){
				$result[$i] = new stdClass(); 
				$result[$i]->ring = $i;
				if( isset($taxiRates[$i]) && isset($taxiRates[$i][0]) )
				{
					$result[$i]->day_fare 			= $taxiRates[$i][0]->day_fare;
					$result[$i]->night_fare			= $taxiRates[$i][0]->night_fare;
					$result[$i]->weekend_fare		= $taxiRates[$i][0]->weekend_fare;
					$result[$i]->km_rate			= $taxiRates[$i][0]->km_rate;
					$result[$i]->starting_tariff	= $taxiRates[$i][0]->starting_tariff;
				}
			}
		}
		
		return $result;
	}
	
	
	public function save()
	{
		$db		 = $this->getDbo();
		JTable::addIncludePath(JPATH_ROOT.DS.'components'.DS.'com_sfs'.DS.'tables');
		$table 	 = JTable::getInstance('Taxidetail' , 'SfsTable');
		
		$taxidetails = JRequest::getVar('taxidetails' , array() , 'post' ,  'array');
		
		$taxidetails['telephone'] = sfsHelper::getPhoneString($taxidetails['phone_code'],$taxidetails['phone_number']);
		
		if( !$table->bind($taxidetails) )
		{
			$this->setError( $table->getError() );
			return false;
		}
		
		if( !$table->store(true) )
		{
			$this->setError( $table->getError() );
			return false;
		}
		
		return true;
	}
	
	public function saveRate()
	{
		$db		 = $this->getDbo();
		
		$taxi_id = JRequest::getInt('taxi_id');
				
		if( $taxi_id > 0 )
		{
			// update period rate
			$updateObject = new stdClass();
			$updateObject->id = $taxi_id;
			
			$fare_from_h 	= JRequest::getVar('fare_from_h');
			$fare_from_m 	= JRequest::getVar('fare_from_m');
			$fare_until_h 	= JRequest::getVar('fare_until_h');
			$fare_until_m 	= JRequest::getVar('fare_until_m');
			
			$updateObject->fare_from  = $fare_from_h.':'.$fare_from_m;
			$updateObject->fare_until = $fare_until_h.':'.$fare_until_m;
			
		  	$wdays = JRequest::getVar('wdays', array(), 'post', 'array');
		  	
	    	if( is_array($wdays) && count($wdays) )
	    	{
	    		JArrayHelper::toInteger($wdays);
	    		$updateObject->available_days = implode(',', $wdays);
	    	} else {
	    		$updateObject->available_days = '';
	    	}
			
			$db->updateObject('#__sfs_taxi_companies', $updateObject, 'id');
			
			// Update hotel rates			
			$ringRates = JRequest::getVar('ringrates', array(), 'post', 'array');
			
			//empty old data
			$query = 'DELETE FROM #__sfs_taxi_hotel_rates WHERE taxi_id='.(int)$taxi_id;
			$db->setQuery($query);
			$db->query();
			
			$insertQueryValues = array();
			
			foreach ($ringRates as $ring => $items)
			{
				foreach ($items as $hotelId => $rate)
				{
					if( floatval($rate['day_fare']) > 0 || floatval($rate['night_fare']) > 0 || floatval($rate['weekend_fare']) > 0 || floatval($rate['km_rate']) > 0 || floatval($rate['starting_tariff']) > 0 )
					{
						$tempQuery = '('.$taxi_id.','.(int)$hotelId.','.(int)$ring;
						
						$tempQuery .= ','.floatval($rate['day_fare']);
						$tempQuery .= ','.floatval($rate['night_fare']);
						$tempQuery .= ','.floatval($rate['weekend_fare']);
						$tempQuery .= ','.floatval($rate['km_rate']);
						$tempQuery .= ','.floatval($rate['starting_tariff']);
						$tempQuery .= ')';
						
						$insertQueryValues[] = $tempQuery;
					}
				}
			}
			
			$insertQuery = 'INSERT INTO #__sfs_taxi_hotel_rates(taxi_id,hotel_id,ring,day_fare,night_fare,weekend_fare,km_rate,starting_tariff) VALUES';
			$insertQuery .= implode(',', $insertQueryValues);
			
			$db->setQuery($insertQuery);
			$db->query();
			
			
			return true;
			
		} else{
			$this->setError('Taxi id: '.(int)$taxi_id.' was not found');
		}
		
		return false;
		
	}
	
	
	public function saveCompany()
	{	
		$db		 = $this->getDbo();
		$user 	 = JFactory::getUser();
		$date	 = JFactory::getDate();
		
		$name 			= JRequest::getString('name');
		$email 			= JRequest::getString('email');
		$telephone 		= JRequest::getString('telephone');
		$fax 			= JRequest::getString('fax');
		$sendMail 		= JRequest::getInt('sendMail',0);
		$sendFax 		= JRequest::getInt('sendFax',0);
		$sendSMS 		= JRequest::getInt('sendSMS',0);
		$published 		= JRequest::getInt('published',0);
		$airport 		= JRequest::getInt('airport_id',0);
		$taxi_id 		= JRequest::getInt('taxi_id', 0);
				
		if( $taxi_id )
		{
			$taxi 	 = $this->getTaxi($taxi_id);
			
			if( $taxi ) {
				$row = new stdClass();
				
				$row->id = $taxi_id;
				$row->name = $name;
				$row->email = $email;
				$row->telephone = $telephone;
				$row->fax = $fax;
				$row->sendMail = $sendMail;
				$row->sendFax = $sendFax;
				$row->sendSMS = $sendSMS;
				$row->published = $published;
							
				if( ! $db->updateObject('#__sfs_taxi_companies', $row, 'id') ) {
					$this->setError('Can not update object');
					return false;
				}
				else{
					$rowMap1 = new stdClass();
					$rowMap1->airport_id = (int)$airport;
					$rowMap1->taxi_id 	= (int)$taxi_id;
					$query  = $db->getQuery(true);
					$query->delete('#__sfs_airport_taxicompany_map');
					$query->where('taxi_id = '.$taxi_id);
					$db->setQuery($query);
					$db->execute();
					$query->clear();
					$result = $db->insertObject( '#__sfs_airport_taxicompany_map' , $rowMap1);
					
					return $result;
				} 
				
			} else {
				$this->setError('Taxi was not found!');
				return false;
			}
			
		} else {
			
			$airlineId = $this->getState('filter.airline_id');
			
			if( $name && $airlineId ) {
				$row = new stdClass();	
							
				$row->id = $taxi_id;
				$row->name = $name;
				$row->email = $email;
				$row->telephone = $telephone;
				$row->fax = $fax;
				$row->sendMail = $sendMail;
				$row->sendFax = $sendFax;
				$row->sendSMS = $sendSMS;
				$row->published = $published;
				$row->created = $date->toSql();
				$row->created_by = $user->id;
				
				if( $db->insertObject( '#__sfs_taxi_companies' , $row) ) 
				{
					$insertId = $db->insertid();
					// insert Airline
					$rowMap = new stdClass();
					$rowMap->airline_id = (int)$airlineId;
					$rowMap->taxi_id 	= (int)$insertId;
					if( ! $db->insertObject( '#__sfs_airline_taxicompany_map' , $rowMap) )
					{
						$this->setError( $db->getErrorMsg() );
						return false;
					}
					// insert Airport
					$rowMap1 = new stdClass();
					$rowMap1->airport_id = (int)$airport;
					$rowMap1->taxi_id 	= (int)$insertId;
					if( ! $db->insertObject( '#__sfs_airport_taxicompany_map' , $rowMap1) )
					{
						$this->setError( $db->getErrorMsg() );
						// return false;
					}
					
					
				} 
				else
				{
					$this->setError( $db->getErrorMsg() );
					return false;
				}
			
			} else {
				$this->setError('Name or missing airline');
				return false;
			}
				
		}
		
		return true;
	}
		
	
	public function getAirlines()
	{
		$db = $this->getDbo();
		
		$query = $db->getQuery(true);
		$query->select('a.*,b.name AS airline_name');
		$query->from('#__sfs_airline_details AS a');		
		$query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id');
		$query->where('a.block=0');
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}
	
	public function getTaxiTransport()
	{
		$taxi_id = JRequest::getInt('taxi_id');
		$db = $this->getDbo();
		
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_taxi_companies AS a');
		$query->where('a.id='.(int)$taxi_id);		
		$db->setQuery($query);
		
		$taxi = $db->loadObject();
		// $taxi->airport = loadAirport($taxi_id);
		if($taxi)
		{
			$registry = new JRegistry;
			$registry->loadString($taxi->params);
			$taxi->params = $registry->toArray();
			
			$query = 'SELECT a.*,u.name FROM #__sfs_taxi_user_map AS a';
			$query .= ' INNER JOIN #__users AS u ON u.id = a.user_id';
			$query .= ' WHERE a.taxi_id ='.$taxi->id;
			
			$db->setQuery($query);
			$taxi->users = $db->loadObjectList();
	
			$query = 'SELECT airline_id FROM #__sfs_airline_taxicompany_map WHERE taxi_id='.$taxi->id;
			$db->setQuery($query);
			$airlines = $db->loadObject();
			$taxi->airlines = $airlines;


			$db = $this->getDbo();
			$query = 'SELECT airport_id FROM #__sfs_airport_taxicompany_map WHERE taxi_id='.$taxi->id;
			$db->setQuery($query);
			$airport_id = $db->loadObject();
			$taxi->airport = $airport_id;	
				
		}
			
		return $taxi;
	}
	
	public function saveTaxi()
	{
		JTable::addIncludePath(JPATH_ROOT.'/components/com_sfs/tables');
				
		$db		 = $this->getDbo();
		$user 	 = JFactory::getUser();
		$date	 = JFactory::getDate();
		$table   = JTable::getInstance('Taxi','JTable');
		
		$taxiDetails  = JRequest::getVar('taxidetails', array() , 'post' , 'array');
		$id = JRequest::getInt('taxi_id',0);
		if($id)
		{
			$table->load($id);	
		} else {
			$taxiDetails['approved'] = 1;
		}
		
    	
    	$ignore   = array();
    	foreach ($taxiDetails as $key => $value)
    	{
    		$value = trim($value);
    		if( empty($value) )
    		{
    			$ignore[] = $key;
    		}
    	}
    	
		$taxiDetails['sendMail']  = JRequest::getInt('sendMail',0);
		$taxiDetails['sendFax']   = JRequest::getInt('sendFax',0);
		$taxiDetails['sendSMS']   = JRequest::getInt('sendSMS',0);
		$taxiDetails['published'] = JRequest::getInt('published',0);
		
		$notifications = JRequest::getVar('notifications', array(), 'post', 'array');
		foreach ($notifications as $key => $notification)
		{
			$notifications[$key] = explode(';', $notification);
		}
		
		$registry = new JRegistry;
		$registry->loadArray($notifications);
		$taxiDetails['notification'] = $registry->toString();	

		$params = JRequest::getVar('params', array(), 'post', 'array');
		$registry = new JRegistry;
		$registry->loadArray($params);
		$taxiDetails['params'] = $registry->toString();	
    	
		if( !$table->bind($taxiDetails, $ignore) ) {
			$this->setError($table->getError());
			return false;					
		}
					
		if( !$table->store() ) {
			$this->setError( $table->getError() );
			return false;					
		}
		
		$taxiId = (int)$table->get('id');
		
		$airlines = JRequest::getVar('airlines', array(), 'post', 'array');
		$airports = JRequest::getVar('airport', array(), 'post', 'array');
		
		if( count($airlines) && $taxiId > 0 )
		{
			$query = 'DELETE FROM #__sfs_airline_taxicompany_map WHERE taxi_id='.(int)$taxiId;
			$db->setQuery($query);
			$db->query();
			
			foreach ($airlines as $airlineId)
			{
				$query = 'INSERT INTO #__sfs_airline_taxicompany_map(airline_id,taxi_id ) VALUES';
				$query .= '('.$airlineId.','.$taxiId.')';
				$db->setQuery($query);
				$db->query();
			}
		}
		if( count($airports) && $taxiId > 0 )
		{
			$query = 'DELETE FROM #__sfs_airport_taxicompany_map WHERE taxi_id='.(int)$taxiId;
			$db->setQuery($query);
			$db->query();
			
			foreach ($airports as $airportId)
			{
				$query = 'INSERT INTO #__sfs_airport_taxicompany_map(airport_id,taxi_id ) VALUES';
				$query .= '('.$airportId.','.$taxiId.')';
				$db->setQuery($query);
				$db->query();
			}
		}	
		
		return $taxiId;		
	}
	// begin CPhuc
	public function getAirPorts(){
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('id,code,name');
		$query->from('#__sfs_iatacodes');
		$query->where('type = 2');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	public function saveTaxiAirport($profile,$airportID){
		
		$db = $this->getDbo();
		$db->insertObject('#__sfs_taxi_companies', $profile,'id');
		$result = $db->insertID();
		$data 				= new stdClass();
		$data->airport_id 	= (int)$airportID;
		$data->taxi_id 		= (int)$result;
		$total = $db->insertObject('#__sfs_airport_taxicompany_map', $data);

		return $total;
	}
	// end CPhuc
	
}

