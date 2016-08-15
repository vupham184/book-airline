<?php
defined('_JEXEC') or die;

class SfsModelGrouptransport extends JModelLegacy
{

	protected $_grouptransport = null;
		
	protected function populateState()
	{
		$value = JRequest::getInt('id');
		$this->setState('grouptransport.id',$value);
		
		$value = JRequest::getInt('type_id');
		$this->setState('grouptransport.type_id',$value);
	}
	
	public function getGrouptransport()
	{		
		$pk = (int) $this->getState('grouptransport.id');

		if( $pk && $this->_grouptranspor == null ) 
		{			
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.*');
			$query->from('#__sfs_group_transportations AS a');
			$query->where('a.id='.$pk);
			
			$db->setQuery($query);
			
			$result = $db->loadObject();
			
			$this->_grouptransport = $result;

			
			if($this->_grouptransport->id) 
			{
				$query = 'SELECT a.airline_id, b.name  FROM #__sfs_group_transportation_airlines AS a';
				$query .= ' INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airline_id  ' ;
				$query .= ' WHERE a.group_transportation_id='.$this->_grouptransport->id;
				$db->setQuery($query);
				$airlines = $db->loadObjectList();
				$this->_grouptransport->airlines = $airlines;

				$query = 'SELECT a.airport_id, b.name  FROM #__sfs_group_transportation_airports AS a';
				$query .= ' INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airport_id  ' ;
				$query .= ' WHERE a.group_transportation_id='.$this->_grouptransport->id;
				$db->setQuery($query);
				$airports = $db->loadObjectList();
				$this->_grouptransport->airports = $airports;


				
				$query = 'SELECT * FROM #__sfs_group_transportation_types WHERE group_transportation_id='.$pk. ' AND rate_fixed = "" ';
				$db->setQuery($query);
				$this->_grouptransport->types = $db->loadObjectList();	

			}	

		}
		
		return $this->_grouptransport;
	}

	public function getGrouptransportFixed()
	{		
		$pk = (int) $this->getState('grouptransport.id');

		if( $pk && $this->_grouptranspor == null ) 
		{			
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.*');
			$query->from('#__sfs_group_transportations AS a');
			$query->where('a.id='.$pk);
			
			$db->setQuery($query);
			
			$result = $db->loadObject();
			
			$this->_grouptransport = $result;

			
			if($this->_grouptransport->id) 
			{
				$query = 'SELECT a.airline_id, b.name  FROM #__sfs_group_transportation_airlines AS a';
				$query .= ' INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airline_id  ' ;
				$query .= ' WHERE a.group_transportation_id='.$this->_grouptransport->id;
				$db->setQuery($query);
				$airlines = $db->loadObjectList();
				$this->_grouptransport->airlines = $airlines;

				$query = 'SELECT a.airport_id, b.name  FROM #__sfs_group_transportation_airports AS a';
				$query .= ' INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airport_id  ' ;
				$query .= ' WHERE a.group_transportation_id='.$this->_grouptransport->id;
				$db->setQuery($query);
				$airports = $db->loadObjectList();
				$this->_grouptransport->airports = $airports;


				
				$query = 'SELECT * FROM #__sfs_group_transportation_types WHERE group_transportation_id='.$pk. ' AND rate = "" ';
				$db->setQuery($query);
				$this->_grouptransport->types = $db->loadObjectList();	

			}	

		}
		
		return $this->_grouptransport;
	}
	
	public function getGroupTransportType()
	{
		$item = $this->getGrouptransport();
		if($item && count($item->types))
		{
			$typeId = (int) $this->getState('grouptransport.type_id');
			if($typeId)
			{
				foreach ($item->types as $type)
				{
					if( (int)$type->id == (int)$typeId)
					{
						return $type;
					}
				}
			}
		}
		return null;
	}

	public function getGroupTransportTypeFixed()
	{
		$item = $this->getGrouptransportFixed();
		if($item && count($item->types))
		{
			$typeId = (int) $this->getState('grouptransport.type_id');
			if($typeId)
			{
				foreach ($item->types as $type)
				{
					if( (int)$type->id == (int)$typeId)
					{
						return $type;
					}
				}
			}
		}
		return null;
	}
	
	public function getHotelRates()
	{		
		$typeId  = (int) $this->getState('grouptransport.type_id');
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
		$query->where('a.group_transportation_type_id = ' . $typeId );	
		
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
					}
					
					$result[$row->ring]->hotels = array();
				}	
				
				if( isset($taxiRates[$row->ring]) && isset($taxiRates[$row->ring][$row->hotel_id]) )
				{
					$row->day_fare 		= $taxiRates[$row->ring][$row->hotel_id]->day_fare;					
				}
				
				$result[$row->ring]->hotels[] = $row;
				
			}
		}
		
		return $result;
	}
	
	public function getUsers()
	{
		$item = $this->getGrouptransport();
		$db   = $this->getDbo();
		if($item)
		{
			$query = 'SELECT a.*,u.name FROM #__sfs_group_transportation_user_map AS a';
			$query .= ' INNER JOIN #__users AS u ON u.id = a.user_id';
			$query .= ' WHERE a.group_transportation_id='.$item->id;
			
			$db->setQuery($query);
			$users = $db->loadObjectList();
			if(count($users))
			{
				return $users;	
			}			
		}
		return null;
	}
	
	public function saveRate()
	{
		$db		 = $this->getDbo();
		$arrRate = array();
		$id = JRequest::getInt('id');
		$group_type_id = JRequest::getInt('group_transportation_type_id');
		$viewRate = JRequest::getVar('viewRate', array(), 'post', 'array');
		
		if( $id && $group_type_id )
		{			
			$name  = JRequest::getVar('name');
			$seats = JRequest::getVar('seats');	
			$rate  = JRequest::getVar('rate');				
			
			if( $viewRate['rate_0'] > 0){
				$row = new stdClass();
				$row->rate_first = '0';
				$row->rate_second = '50';
				$row->rate_three = $viewRate['rate_0'];
				array_push($arrRate, $row );
			}
			if($viewRate['rate_1'] > 0){
				$row_1 = new stdClass();
				$row_1->rate_first = '50';
				$row_1->rate_second = '100';
				$row_1->rate_three = $viewRate['rate_1'];
				array_push($arrRate, $row_1 );
			}
			if($viewRate['rate_2'] > 0){
				$row_2 = new stdClass();
				$row_2->rate_first = '100';
				$row_2->rate_second = '150';
				$row_2->rate_three = $viewRate['rate_2'];
				array_push($arrRate, $row_2 );
			}

						
			$query  = 'UPDATE #__sfs_group_transportation_types SET name='.$db->quote($name).',seats='.(int)$seats.',rate='. "'" .json_encode($arrRate) . "'";
			$query .= ' WHERE id='.$group_type_id.' AND group_transportation_id='.$id;
			
			$db->setQuery($query);
			$db->query();
				
			// Update hotel rates			
			$ringRates = JRequest::getVar('ringrates', array(), 'post', 'array');
			
			//empty old data
			$query = 'DELETE FROM #__sfs_group_transportation_rates WHERE group_transportation_type_id='.(int)$group_type_id;
			$db->setQuery($query);
			$db->query();
			
			$insertQueryValues = array();
			
			foreach ($ringRates as $ring => $items)
			{
				foreach ($items as $hotelId => $rate)
				{
					if( floatval($rate['day_fare']) > 0 )
					{
						$tempQuery = '('.$group_type_id.','.(int)$hotelId.','.(int)$ring;
						
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
			
		} else{
			$this->setError('Type Id was invalid');
		}
		
		return false;
	}

	public function saveRateFixededit()
	{
		$arrFixed = array();
		$db		 = $this->getDbo();

			

		$query  = 'UPDATE #__sfs_group_transportation_types SET name="'.$_POST['name'].'",seats='.$_POST['seats'].',rate_fixed='. "'" .json_encode($_POST['rate_fixed']) . "'";

		$query .= ' WHERE id='.$_POST['id'].' AND group_transportation_id='.$_POST['group_id'];	
		$db->setQuery($query);
		$db->query();

		$success = array("status" => "ok");
		echo json_encode($success); die();
	}

	public function saveRateFixed()
	{		
		$db		 = $this->getDbo();
		$arrRate = array();
		$id = JRequest::getInt('id');
		$group_type_id = JRequest::getInt('group_transportation_type_id');
		$viewRate = JRequest::getVar('viewRate', array(), 'post', 'array');			
		
		if( $id && $group_type_id )
		{			
			$name  = JRequest::getVar('name');
			$seats = JRequest::getVar('seats');	
			$rate  = JRequest::getVar('rate');				
			
			for ($i=0; $i < count($viewRate); $i++) {
				$row = new stdClass();
				$row->airport_from = $viewRate[$i]["airport_from"];
				$row->airport_to = $viewRate[$i]["airport_to"];
				$row->rate = $viewRate[$i]["rate"];
				array_push($arrRate, $row);
			}

						
			$query  = 'UPDATE #__sfs_group_transportation_types SET name='.$db->quote($name).',seats='.(int)$seats.',rate_fixed='. "'" .json_encode($arrRate) . "'";
			$query .= ' WHERE id='.$group_type_id.' AND group_transportation_id='.$id;
			
			$db->setQuery($query);
			$db->query();
				
			// Update hotel rates			
			$ringRates = JRequest::getVar('ringrates', array(), 'post', 'array');
			
			//empty old data
			$query = 'DELETE FROM #__sfs_group_transportation_rates WHERE group_transportation_type_id='.(int)$group_type_id;
			$db->setQuery($query);
			$db->query();
			
			$insertQueryValues = array();
			
			foreach ($ringRates as $ring => $items)
			{
				foreach ($items as $hotelId => $rate)
				{
					if( floatval($rate['day_fare']) > 0 )
					{
						$tempQuery = '('.$group_type_id.','.(int)$hotelId.','.(int)$ring;
						
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
			
		} else{
			$this->setError('Type Id was invalid');
		}
		
		return false;
	}
	
	
	
	public function save()
	{
		JTable::addIncludePath(JPATH_ROOT.'/components/com_sfs/tables');
				
		$db		 = $this->getDbo();
		$user 	 = JFactory::getUser();
		$date	 = JFactory::getDate();
		$table   = JTable::getInstance('Bus','JTable');
		
		$busDetails  = JRequest::getVar('busdetails', array() , 'post' , 'array');
		$id = JRequest::getInt('id',0);

		if($id)
		{
			$table->load($id);	
		} else {
			$busDetails['approved'] = 1;
		}
		
    	
    	$ignore      = array();
    	foreach ($busDetails as $key => $value)
    	{
    		$value = trim($value);
    		if( empty($value) )
    		{
    			$ignore[] = $key;
    		}
    	}
    	
		$busDetails['sendMail']  = JRequest::getInt('sendMail',0);
		$busDetails['sendFax']   = JRequest::getInt('sendFax',0);
		$busDetails['sendSMS']   = JRequest::getInt('sendSMS',0);
		$busDetails['published'] = JRequest::getInt('published',0);
		
		$notifications = JRequest::getVar('notifications', array(), 'post', 'array');
		foreach ($notifications as $key => $notification)
		{
			$notifications[$key] = explode(';', $notification);
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
		
		$airlines		= JRequest::getVar('airlines', array(), 'post', 'array');
		$airports		= JRequest::getVar('airports', array(), 'post', 'array');
		
		$busId = (int)$table->get('id');
		
		if( count($airlines) && $busId > 0 )
		{
			// $query = 'DELETE FROM #__sfs_group_transportation_airlines WHERE group_transportation_id='.(int)$busId;
			// $db->setQuery($query);
			// $db->query();	
			


			foreach ($airlines as $airlineId)
			{
				$query = "SELECT * FROM #__sfs_group_transportation_airlines WHERE group_transportation_id = " . $busId . " AND airline_id = " . $airlineId;
				$db->setQuery($query);
				$result = $db->loadObjectList();

				if(empty($result)){
					$query = 'INSERT INTO #__sfs_group_transportation_airlines(group_transportation_id,airline_id) VALUES';
					$query .= '('.$busId.','.$airlineId.')';
					$db->setQuery($query);
					$db->query();
				}				
			}
		}		

		if( count($airports) && $busId > 0 )
		{					
			foreach ($airports as $airportId)
			{
				$query = "SELECT * FROM #__sfs_group_transportation_airports WHERE group_transportation_id = " . $busId . " AND airport_id = " . $airportId;
				$db->setQuery($query);
				$result = $db->loadObjectList();

				if(empty($result)){
					$query_ = 'INSERT INTO #__sfs_group_transportation_airports(group_transportation_id,airport_id) VALUES';
					$query_ .= '('.$busId.','.$airportId.')';
					$db->setQuery($query_);
					$db->query();
				}				
			}
		}		
		
		return $busId;
	}
	
	public function getIataAirlines()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_iatacodes AS a');
		$query->where('a.type=1');
		$query->order('a.name ASC');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		return $rows;		
	}
	
	public function getIataAirports()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_iatacodes AS a');
		$query->where('a.type=2');
		$query->order('a.name ASC');
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		return $rows;		
	}

	public function newUser()
	{
		jimport('joomla.mail.helper');
		require_once JPATH_ROOT . '/components/com_sfs/tables/sfscontact.php';
		
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_users');
		
		$date = JFactory::getDate();
		$db = $this->getDbo();
		
		$userData = array();		
		$user = new JUser;
		
		$busId  = JRequest::getInt('id');
		if( !$busId ) {
			$this->setError('Bus company was not found');
			return false;
		}
		$data = JRequest::getVar('contact',array(),'array'); 	
			
		$db = $this->getDbo();		

		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Bus'));
		$group_id = $db->loadResult();
		
		if( ! $group_id ) {
			$this->setError('No Bus Group created');
			return false;	
		}

		if ( !JMailHelper::isEmailAddress($data['email']) ) {
			$this->setError(JText::_('Email invalid'));
			return false;
		}
		
		if($data['password'] != $data['password2'] ) {
			$this->setError(JText::_('Password does not match'));
			return false;			
		}
		
		$userData['groups']   = array((int)$group_id);
		$userData['name']     = $data['name'].' '.$data['surname'];
		$userData['username'] = $data['username'];
		$userData['password'] = $data['password'];			
		$userData['email']	  = $data['email'];		
		
		if (!$user->bind($userData)) {
			$this->setError($user->getError());
			return false;
		}		
		
		$config = JFactory::getConfig();
		
		$user->setParam('timezone', $config->get('offset') );
		
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}
			
		$db->setQuery(
			'INSERT INTO #__sfs_group_transportation_user_map(group_transportation_id, user_id)' .
			' VALUES ('.$busId.', '.$user->id.')'
		);
		
		$db->query();	
		
		//Store main contact		
		$contactData = array();
		$contactData['user_id'] 	= $user->id;
		$contactData['grouptype']   = 4;
		$contactData['group_id']  	= $busId;
		$contactData['is_admin'] 	= 1;
		$contactData['gender'] 		= $data['gender'];
		$contactData['name'] 		= $data['name'];
		$contactData['surname'] 	= $data['surname'];
		$contactData['job_title'] 	= $data['job_title'];
		$contactData['telephone'] 	= sfsHelper::getPhoneString($data['tel_code'],$data['tel_number'] ) ;
		$contactData['fax'] 		= sfsHelper::getPhoneString($data['fax_code'],$data['fax_number']);
		$contactData['mobile'] 		= sfsHelper::getPhoneString($data['mobile_code'],$data['mobile_number']);
		
		$contactTable = JTable::getInstance('Sfscontact','JTable');
		
		if( ! $contactTable->bind($contactData) ) {
			$this->setError($contactTable->getError());
			return false;
		}
		
		if( ! $contactTable->check() ) {
			$this->setError($contactTable->getError());			
			return false;
		}
		
		if( ! $contactTable->store() ) {
			$this->setError($contactTable->getError());			
			return false;
		}	
				
		
		return true;
	}
	
	public function addType()
	{
		$db		 = $this->getDbo();
		$arrRate = array();
		$id 			= JRequest::getInt('id');
		$name  			= JRequest::getVar('name');
		$seats 			= JRequest::getVar('seats');	
		$rate_first  	= JRequest::getVar('rate_first');
		$rate_second  	= JRequest::getVar('rate_second');
		$rate_three  	= JRequest::getVar('rate_three');
		$published  	= 1;
		
		$row = new stdClass();

		if($rate_first != ""){
			$row->rate_first = '0';
			$row->rate_second = '50';
			$row->rate_three = $rate_first;
			array_push($arrRate, $row);
		}
		if($rate_second != ""){
			$row->rate_first = '50';
			$row->rate_second = '100';
			$row->rate_three = $rate_first;
			array_push($arrRate, $row);
		}
		if($rate_three != ""){
			$row->rate_first = '100';
			$row->rate_second = '150';
			$row->rate_three = $rate_first;
			array_push($arrRate, $row);
		}
		
		if( $id )
		{			
			//rate_first,rate_second,rate_three,
			//"'.$rate_first.'", "'.$rate_second.'", "'.$rate_three.'",
			$insertQuery = 'INSERT INTO #__sfs_group_transportation_types(group_transportation_id,name,seats,rate,rate_fixed,published)'.
			' VALUES ("'.$id.'", "'.$name.'", "'.$seats.'",'."'".json_encode($arrRate)."'".', "", "'.$published.'")';
						
			$db->setQuery($insertQuery);
			$db->query();	
			
			return true;
			
		} else{
			$this->setError('Type Id was invalid');
		}
		
		return false;
	}

	public function getRateEdit(){		
		$id = JRequest::getInt('id');

		$db		 = $this->getDbo();
		$query   = $db->getQuery(true);		
		$result = array();
				
		// Gets Hotels
		$query->select('a.id, b.hotel_id, b.ring, b.day_fare');
		$query->from('#__sfs_group_transportation_types AS a');
		$query->innerJoin('#__sfs_group_transportation_rates AS b ON b.group_transportation_type_id = a.id');
		
		$query->where('a.group_transportation_id = ' . $id );				
		
		$db->setQuery($query);		
		$resultRate = $db->loadObjectList();
		$query->clear();
		
		$items = array();

		foreach ($resultRate as $rate) {
			if($rate->hotel_id == 0 && $rate->ring == 1){
				$objectList =  new stdClass;			
				$objectList->day_fare = $rate->day_fare;
				$objectList->hotelname = "One way rate for all first ring hotels is";
				$items[]= $objectList ;
			}

			if($rate->hotel_id == 0 && $rate->ring == 2){
				$objectList =  new stdClass;			
				$objectList->day_fare = $rate->day_fare;
				$objectList->hotelname = "One way rate for all second ring hotels is";
				$items[]= $objectList ;
			}

			if($rate->hotel_id > 0){
				$query->select('name');
				$query->from('#__sfs_hotel');
				$query->where('id = ' . $rate->hotel_id );
				$db->setQuery($query);		
				$result = $db->loadObjectList();
				$query->clear();
				$objectList =  new stdClass;			
				$objectList->day_fare = $rate->day_fare;
				$objectList->hotelname = $result[0]->name;					  
							 
				$items[]= $objectList ;
			}
			
		}
		
		return $items;
	}
	
	public function getListAirportTo(){
    	$db = $this->getDbo();

    	$id = (int) $this->getState('grouptransport.id');

    	$query = 'SELECT b.id, b.code FROM #__sfs_group_transportation_airports as a';
    	$query .= ' LEFT JOIN #__sfs_iatacodes as b ON b.id = a.airport_id';
    	$query .= ' WHERE a.group_transportation_id = ' . $id;

    	$db->setQuery($query);			
			
		$result = $db->loadObjectList();

    	//print_r($result); die();

    	return $result;
    }

    public function getListAirport(){
    	$db = $this->getDbo();    	

    	$query = 'SELECT id, code FROM #__sfs_iatacodes';    	
    	$query .= ' WHERE type = 2 ORDER BY code ASC';

    	$db->setQuery($query);			
			
		$result = $db->loadObjectList();

    	//print_r($result); die();

    	return $result;
    }

    public function addTypeFixed()
	{
		$db		 = $this->getDbo();
		$arrRate = array();
		$id 			= JRequest::getInt('id');
		$name  			= JRequest::getVar('name');
		$seats 			= JRequest::getVar('seats');
		$from  			= JRequest::getVar('airport_from');	
		$to  			= JRequest::getVar('airport_to');		
		$rate  			= JRequest::getInt('rate');
		$published  	= 1;
		
		$row = new stdClass();
		
		$row->airport_from 	= $from;
		$row->airport_to 	= $to;
		$row->rate 			= $rate;
		array_push($arrRate, $row);

		if( $id )
		{			
			
			$insertQuery = 'INSERT INTO #__sfs_group_transportation_types(group_transportation_id,name,seats,rate,rate_fixed,published)'.
			' VALUES ("'.$id.'", "'.$name.'", "'.$seats.'", "" ,'."'".json_encode($arrRate)."'".', "'.$published.'")';
						
			$db->setQuery($insertQuery);
			$db->query();	
			
			return true;
			
		} else{
			$this->setError('Type Id was invalid');
		}
		
		return false;
	}
}

