<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

require_once JPATH_ROOT . '/components/com_sfs/libraries/core.php';
require_once JPATH_ROOT . '/components/com_sfs/libraries/access.php';
require_once JPATH_ROOT . '/components/com_sfs/libraries/hotel.php';
require_once JPATH_ROOT . '/components/com_sfs/helpers/field.php';
require_once JPATH_ROOT . '/components/com_sfs/helpers/date.php';
require_once JPATH_ROOT . '/components/com_sfs/tables/hotelroom.php';
require_once JPATH_ROOT . '/components/com_sfs/libraries/airline.php';

class SfsModelHotel extends JModelItem
{
	protected $text_prefix = 'com_sfs';
	
	protected $_contacts = null;
	
	protected function populateState()
	{
		$pk = JRequest::getInt('id');
		$this->setState('hotel.id',$pk);		
	}	
		
	public function getItem(){		
		$hotel = SHotel::getInstance($this->getState('hotel.id'));				
		return $hotel;			
	}	
	
	public function getRoomsPrices()
	{
        $params = JComponentHelper::getParams('com_sfs');
        $cleanTime = trim($params->get('match_hours'));

        $db = $this->getDbo();
		$hotel = $this->getItem();

        $is_next_day = false;

        if( strlen($cleanTime) > 0 )
        {
            $cleanTime = explode( ':' , $cleanTime);
			if ( trim($hotel->time_zone) == '' ) {
				$hotel->time_zone = SAirline::getTimezoneInIatacodes( $hotel->id );
			}
            $nowTime = SfsHelperDate::getDate('now','H:i',$hotel->time_zone);

            $nowTime = explode(':', $nowTime);
            JArrayHelper::toInteger($nowTime);
            JArrayHelper::toInteger($cleanTime);

            if( $nowTime[0] >= $cleanTime[0] ){
                if( $nowTime[0] == $cleanTime[0]) {
                    if( $nowTime[1] >= $cleanTime[1]  ) {
                        $is_next_day = true;
                    }
                    else{
                        $is_next_day = false;
                    }
                } else {
                    $is_next_day = true;
                }
            }
            else{
                $is_next_day = false;
            }
        }

        $now = SfsHelperDate::getDate('now','Y-m-d',$hotel->time_zone);

        if( ! $is_next_day )
        {
            $now = SfsHelperDate::getPrevDate('Y-m-d', $now);
        }
		
		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_room_inventory AS a');
				
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('a.date >= '.$db->quote($now) );
		$query->order('a.date ASC');
						
		$db->setQuery($query,0,30);		
		$rows = $db->loadObjectList();
				
		$result = $this->getNextDates($now,30);

		if(count($rows)) {
			foreach ($rows as $row) {
				$result [ $row->date ] = $row;	
			}
		}
		return $result;		
	}
	
	public function getTodayInventory()
	{
		$db = $this->getDbo();
		$hotel = $this->getItem();	
			
		$now = $this->getDate('now','Y-m-d',$hotel->time_zone);
		
		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_room_inventory AS a');
						
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('date='.$db->quote($now) );
		$query->order('a.date ASC');
						
		$db->setQuery($query);		
		$result = $db->loadObject();
		
		return $result;		
	}
	
	public function getServicingAirports()
	{
		$result = null;
		$item = $this->getItem();
		
		if($item) {		
			$db = $this->getDbo();
			
			$query = 'SELECT a.*, b.code AS airport_code, b.name AS airport_name FROM #__sfs_hotel_airports AS a';			
			$query .= ' LEFT JOIN #__sfs_iatacodes AS b ON b.id=a.airport_id AND b.type=2';
			$query .= ' WHERE a.hotel_id='.$item->id;
			$query .= ' ORDER BY a.distance ASC';
			
			$db->setQuery($query);
			
			$result = $db->loadObjectList();

			if( $error = $db->getErrorMsg() ){
				throw new Exception($error);
			}
			
		}
		
		return $result;
	}	
	public function getHotelRoom()
	{
		$result = null;
		$item = $this->getItem();
		
		return $item->getRoomDetail();
	}	
	public function getHotelTaxes()
	{
		$result = null;
		$item = $this->getItem();
		
		return $item->getTaxes();
	}		
	
	public function getMerchantFee($hotelId=null)
	{
		if( empty($hotelId) ){
			$hotel = $this->getItem();
			$hotelId = $hotel->id;
		}
		
		if( $hotelId )
		{
			$db = $this->getDbo();
			$query = 'SELECT * FROM #__sfs_hotel_merchant_fee WHERE hotel_id='.$hotelId;
			$db->setQuery($query);
			
			$result = $db->loadObject();
			
			return $result;
		}
		return null;
	}
	
	public function getAdminSetting($hotelId=null)
	{
		if( empty($hotelId) ){
			$hotel = $this->getItem();
			$hotelId = $hotel->id;
		}
		
		if( $hotelId )
		{
			$db = $this->getDbo();
			$query = 'SELECT * FROM #__sfs_hotel_backend_params WHERE hotel_id='.$hotelId;
			$db->setQuery($query);
			
			$result = $db->loadObject();
			
			return $result;
		}
		return null;
	}

	public function getAdmins()
	{
		$item = $this->getItem();
		$db = $this->getDbo();
		$db->setQuery(
			'SELECT a.user_id, u.username FROM #__sfs_hotel_user_map AS a'.			
			' INNER JOIN #__users AS u ON u.id=a.user_id'.
			' INNER JOIN #__user_usergroup_map AS um ON um.user_id=a.user_id AND um.group_id=13'.		
			' WHERE a.hotel_id='.(int)$item->id
		);
		$result = $db->loadObjectList();
		return $result;
	}	
	
	public function saveRooms()
	{
		require_once JPATH_ROOT . '/components/com_sfs/tables/inventory.php';
		
		$app    = JFactory::getApplication();                        		
		$date = JFactory::getDate();
		$db = $this->getDbo();
		$hotel = $this->getItem();
		
		$hotelSetting   = $hotel->getBackendSetting();
		
		$rooms = JRequest::getVar('rooms', array(), 'post', 'array');
					
		$n = count($rooms);
		
		
		$loadedPrices = $this->getRoomsPrices();
		
		for ( $i = 0 ; $i < $n ;  $i++ )
		{
			$is_ready = false;			
			if( is_object( $loadedPrices[$rooms[$i]['rdate']] ) )
			{
				$is_ready = true;
			}			
			if( strlen($rooms[$i]['sdroom']) >= 1 || strlen($rooms[$i]['sdrate']) >= 1  )
			{
				$is_ready = true;
			}		
			if( strlen($rooms[$i]['troom']) >= 1 || strlen($rooms[$i]['trate']) >= 1  )
			{
				$is_ready = true;
			}
			if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) {
				if( strlen($rooms[$i]['sroom']) >= 1 || strlen($rooms[$i]['srate']) >= 1  )
				{
					$is_ready = true;
				}
			}			
			if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) {
				if( strlen($rooms[$i]['qroom']) >= 1 || strlen($rooms[$i]['qrate']) >= 1  )
				{
					$is_ready = true;
				}
			}
			
			if ( $is_ready )  
			{
				$rooms[$i]['sdrate'] = floatval($rooms[$i]['sdrate']);
				$rooms[$i]['trate']  = floatval($rooms[$i]['trate']);
				$rooms[$i]['sdroom'] = (int) $rooms[$i]['sdroom'] ;
				$rooms[$i]['troom']  = (int) $rooms[$i]['troom'];
				
				if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) {
					$rooms[$i]['srate'] = floatval($rooms[$i]['srate']);
					$rooms[$i]['sroom'] = (int) $rooms[$i]['sroom'] ;	
				}
				
				if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) {
					$rooms[$i]['qrate'] = floatval($rooms[$i]['qrate']);
					$rooms[$i]['qroom'] = (int) $rooms[$i]['qroom'] ;	
				}
				
				if( isset($rooms[$i]['transport']) && (int) $rooms[$i]['transport'] == 1 ) {
					$transport = 1;
				} else {
					$transport = 0;
				}
				if( is_object( $loadedPrices[$rooms[$i]['rdate']] ) ) {
					$loaded = $loadedPrices[$rooms[$i]['rdate']];
										
					$loaded->sd_room_rate = (float)$loaded->sd_room_rate;
					$loaded->t_room_rate  = (float)$loaded->t_room_rate;
					$loaded->sd_room_total = (int) $loaded->sd_room_total;
					$loaded->t_room_total = (int) $loaded->t_room_total;	
					
					if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) {
						$loaded->s_room_rate  = (float)$loaded->s_room_rate;
						$loaded->s_room_total = (int) $loaded->s_room_total;
					}
					
					if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) {
						$loaded->q_room_rate  = (float)$loaded->q_room_rate;
						$loaded->q_room_total = (int) $loaded->q_room_total;	
					}
									
					$rooms[$i]['transport'] = $transport;
									
					$this->updateInventory($loaded,$rooms[$i]);
				} else {
					$this->insertInventory($rooms[$i], $hotel->id, $transport);						
				}
			}
		}
		
		
		
		return true;
						
	}
	
	protected function updateInventory( $loaded ,$data )
	{			
		$hotel 			= $this->getItem();		
		$hotelSetting   = $hotel->getBackendSetting();
		$user 			= JFactory::getUser();
		$table 			= JTable::getInstance('Inventory', 'JTable');
							
		$loaded->sd_room_rate = $data['sdrate'];
		$loaded->sd_room_rate_modified = $data['sdrate'];
			
		$loaded->t_room_rate = $data['trate'];
		$loaded->t_room_rate_modified = $data['trate'];
		
		$loaded->sd_room_total = $data['sdroom'];
		$loaded->t_room_total = $data['troom'];
		 
		if( (int)$loaded->transport_included != (int)$data['transport']){			
			$loaded->transport_included = $data['transport'];
		}
		
		if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) {		
			$loaded->s_room_rate = $data['srate'];
			$loaded->s_room_rate_modified = $data['srate'];				
			$loaded->s_room_total = $data['sroom'];		
		}		
		if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) {
			$loaded->q_room_rate = $data['qrate'];
			$loaded->q_room_rate_modified = $data['qrate'];				
			$loaded->q_room_total = $data['qroom'];
		}		
		
		$loaded->modified 		= JFactory::getDate()->toSql();
		$loaded->modified_by	= JFactory::getUser()->id;		
				
				
		if( !$table->bind($loaded) ){
			return false;
		}		
		if( !$table->store() ){
			return false;
		}				
		
		return true;		
	}
	
	protected function insertInventory( & $data , $hotel_id, $transport)
	{		
		$hotel 			= $this->getItem();		
		$hotelSetting   = $hotel->getBackendSetting();
		$user 			= JFactory::getUser();
		$table 			= JTable::getInstance('Inventory', 'JTable');
		
		$bindData = array();
		
		$data['sdrate'] = floatval($data['sdrate']);
		$data['trate']  = floatval($data['trate']);
						

		if( $data['sdrate'] > 0 ) {
			$data['sdroom'] = (int)$data['sdroom'];	
		} else {
			$data['sdroom'] = 0;
		}
		if( $data['trate'] > 0 ) {
			$data['troom'] = (int)$data['troom'];		
		} else {
			$data['troom'] = 0 ;
		}	

		$bindData['hotel_id'] = $hotel_id;
		$bindData['sd_room_total'] = $data['sdroom'];
		$bindData['sd_room_rate'] = $data['sdrate'];
		$bindData['sd_room_rate_modified'] = $data['sdrate'];
		$bindData['t_room_total'] = $data['troom'];
		$bindData['t_room_rate'] = $data['trate'];
		$bindData['t_room_rate_modified'] = $data['trate'];		
		
		if( isset($hotelSetting) && (int)$hotelSetting->single_room_available == 1 ) {		
			$data['srate'] = floatval($data['srate']);
			if( $data['srate'] > 0 ) {
				$data['sroom'] = (int)$data['sroom'];	
			} else {
				$data['sroom'] = 0;
			}	
			$bindData['s_room_total'] = $data['sroom'];
			$bindData['s_room_rate']  = $data['srate'];
			$bindData['s_room_rate_modified'] = $data['srate'];
		}		
		if( isset($hotelSetting) && (int)$hotelSetting->quad_room_available == 1 ) {
			$data['qrate'] = floatval($data['qrate']);
			if( $data['qrate'] > 0 ) {
				$data['qroom'] = (int)$data['qroom'];	
			} else {
				$data['qroom'] = 0;
			}
			$bindData['q_room_total'] = $data['qroom'];
			$bindData['q_room_rate']  = $data['qrate'];
			$bindData['q_room_rate_modified'] = $data['qrate'];	
		}
		
		$bindData['date'] = $data['rdate'];
		$bindData['transport_included'] = $transport;
		$bindData['created_by'] = $user->id;
		$bindData['created'] = JFactory::getDate()->toSql();

		if( !$table->bind($bindData) ){
			return false;
		}
		
		if( !$table->store() ){
			return false;
		}		
		
		return true;
	}
	
	public function saveFreeRelease()
	{
		$hotel_id = (int)JRequest::getVar('id');
		$percent_release_policy = (int)JRequest::getVar('percent_release_policy');
		if( $hotel_id > 0 && $percent_release_policy > 0 ){
			$db = $this->getDbo();
			$db->setQuery('UPDATE #__sfs_hotel_taxes SET percent_release_policy='.$percent_release_policy.' WHERE hotel_id='.$hotel_id);
			$db->query();
		}		
		return true;
	}
	
	public function saveHotel()
	{
		
		$id = JRequest::getInt('id');
		
		if($id <=0) {
			$this->setError('Hotel invalid');
			return false;
		}	
			
		$address = JRequest::getVar('address', array(), 'post', 'array');
		$billing = JRequest::getVar('billing', array(), 'post', 'array');
		$merchantfee = JRequest::getVar('merchantfee', array(), 'post', 'array');
		$tax = JRequest::getVar('tax', array(), 'post', 'array');
		$fb = JRequest::getVar('fb', array(), 'post', 'array');
		
		
		$block = JRequest::getInt('block');
				
		$db = $this->getDbo();
		
		
		if( count($billing)  ) 
		{
			$billingObject = new stdClass();
			foreach ($billing as $key => $value) {
				$billingObject->$key = 	$value;
			}
						
			$db->updateObject('#__sfs_billing_details', $billingObject, 'id');			
		}
		
		if(  count($address) )
		{
			$addressObject = new stdClass();
			
			foreach ($address as $key => $value) {
				$addressObject->$key = 	$value;
			}
			
			$addressObject->id = $id;
			
			$hotel = $this->getItem();
			
			//if($hotel->step_completed==1) {
				//$addressObject->step_completed = 2;
			//}
			
			$addressObject->block = $block;									
			
			$db->updateObject('#__sfs_hotel', $addressObject, 'id');			
		}
		
		if(  count($tax) )
		{
			$taxObject = new stdClass();
			
			foreach ($tax as $key => $value) {
				$taxObject->$key = $value;
			}
			
			$taxObject->hotel_id = $id;
									
			$db->updateObject('#__sfs_hotel_taxes', $taxObject, 'hotel_id');			
		}
		
		
		if( count($merchantfee)  ) 
		{
			$hotel = $this->getItem();
			
			$merchantfeeObject = new stdClass();
			foreach ($merchantfee as $key => $value) {
				$merchantfeeObject->$key = 	$value;
			}
			$merchantfeeObject->hotel_id = $id;
			$merchantfee = $this->getMerchantFee($id);
			
			if( $merchantfee )
			{
				$db->updateObject('#__sfs_hotel_merchant_fee', $merchantfeeObject, 'hotel_id');
			} else{
				$db->insertObject('#__sfs_hotel_merchant_fee', $merchantfeeObject, 'hotel_id');
			}
			
						
		}
				
		if ( count($fb) )
		{
			$hotel = $this->getItem();
			$mealPlan = $hotel->getMealPlan();
			foreach ($fb as $key => $value) {				
				$mealPlan->$key = $value;
			}
			$mealPlan->hotel_id = $hotel->id;
			$db->updateObject('#__sfs_hotel_mealplans', $mealPlan, 'hotel_id');
		}
		
		if($id)
		{
			$ring = JRequest::getInt('hotel_ring');
			$second_fax = JRequest::getVar('second_fax');
			$merchant_fee_enable = JRequest::getInt('merchant_fixed_fee_enable');
			$merchant_register_note = JRequest::getVar('merchant_register_note');
			
			$single_room_available 	= JRequest::getInt('single_room_available');
			$quad_room_available   	= JRequest::getInt('quad_room_available');
			$hotel_invited   		= JRequest::getInt('hotel_invited');
			
			$hAdmin = new stdClass();
			$hAdmin->ring = $ring;
			$hAdmin->second_fax = $second_fax;
			$hAdmin->hotel_id = $id;
			$hAdmin->merchant_fixed_fee_enable = $merchant_fee_enable;
			$hAdmin->merchant_register_note	   = $merchant_register_note;
			
			$hAdmin->single_room_available	   = $single_room_available;
			$hAdmin->quad_room_available	   = $quad_room_available;
			$hAdmin->hotel_invited	   		   = $hotel_invited;
			
			$query = 'SELECT COUNT(*) FROM #__sfs_hotel_backend_params WHERE hotel_id='.$id;
			$db->setQuery($query);
			
			$count = $db->loadResult();
			
			if( ! $count )
			{
				$db->insertObject('#__sfs_hotel_backend_params', $hAdmin, 'hotel_id');
			} else {
				$db->updateObject('#__sfs_hotel_backend_params', $hAdmin, 'hotel_id');
				//$query = 'UPDATE #__sfs_hotel_airports SET airport_id="'.JRequest::getInt('airport').'"';
				//$db->setQuery($query);
				$db->execute();
			}
			
		}
		
		
		if( $db->getErrorNum() )
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		return true;
	}
	
	public function newAdmin()
	{
		jimport('joomla.mail.helper');
		require_once JPATH_ROOT . '/components/com_sfs/tables/sfscontact.php';
		
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_users');
		
		$date = JFactory::getDate();
		$db = $this->getDbo();
		
		$userData = array();		
		$user = new JUser;
		
		$hotelId  = JRequest::getInt('id');
		$data = JRequest::getVar('contact',array(),'array'); 	
			
		$db = $this->getDbo();		

		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Hotel Administrator'));
		$group_id = $db->loadResult();
		
		if( ! $group_id ) $group_id = 2;

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
		
		
		$db->setQuery('SELECT time_zone FROM #__sfs_hotel WHERE id='.$hotelId);
		$timezone = $db->loadResult();
		$user->setParam('timezone',$timezone );
		
		if (!$user->save()) {
			$this->setError($user->getError());
			return false;
		}
			
		$db->setQuery(
				'INSERT INTO #__sfs_hotel_user_map (hotel_id, user_id)' .
				' VALUES ('.$hotelId.', '.$user->id.')'
		);

		$db->query();	

		$sys = new stdClass();
		$sys->booking = 1;
		$sys->voucher = 1;
		$sys->roomloading = 1;
		$sys->low_availlability = 1;

		//Store main contact		
		$contactData = array();
		$contactData['user_id'] 	= $user->id;
		$contactData['grouptype']   = constant('SFSCore::HOTEL_GROUP');
		$contactData['group_id']  	= $hotelId;
		$contactData['is_admin'] 	= 1;
		$contactData['gender'] 		= $data['gender'];
		$contactData['name'] 		= $data['name'];
		$contactData['surname'] 	= $data['surname'];
		$contactData['job_title'] 	= $data['job_title'];
		$contactData['telephone'] 	= sfsHelper::getPhoneString($data['tel_code'],$data['tel_number'] ) ;
		$contactData['fax'] 		= sfsHelper::getPhoneString($data['fax_code'],$data['fax_number']);
		$contactData['mobile'] 		= sfsHelper::getPhoneString($data['mobile_code'],$data['mobile_number']);
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
	
	public function getContacts()
	{
		$hotel = $this->getItem();
		if( isset($hotel) && (int)$hotel->id > 0 )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*,u.email');
			$query->from('#__sfs_contacts AS a');
			$query->innerJoin('#__users AS u ON u.id=a.user_id');
			$query->where('a.group_id='.$hotel->id.' AND a.grouptype=1');
			$query->where('u.block=0');
			
			$db->setQuery($query);
			
			$contacts = $db->loadObjectList();
			return $contacts;
		}
		return null;
	}
	
	private function getDate( $input = 'now' , $format = 'Y-m-d H:i', $timezone = null )
	{
		$config = JFactory::getConfig();
		$date = JFactory::getDate($input, 'UTC');
		if (  $timezone  ) {					
			$date->setTimeZone( new DateTimeZone($timezone) );						
		} else {			
			$date->setTimeZone(new DateTimeZone($config->get('offset')));			
		}			
		
		return $date->format($format, true);
	}
	
	private function getNextDate ( $format,$inputDate ) {
				 
		$result = strtotime($inputDate);
		
		if($result !== false){
	  		return date( $format , strtotime('+1 day', $result) );
		}			
		return null;			
	}
	
	private function getNextDates($date,$days)
	{
		$result = array();		
		
		$result[(string)$date] = $date;
		$prev = $date;
		
		for ($i=1;$i<$days;$i++) {
			$value = $key = (string) $this->getNextDate( JText::_('DATE_FORMAT_LC4'),$prev);
			$result[$key] = $value;
			$prev = $value;
		}		
		
		return $result;
	}
	
	public function getCreatedUser()
	{
		$hotel = $this->getItem();
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('u.*');
		$query->from('#__users AS u');
		$query->innerJoin('#__sfs_hotel AS h ON h.created_by=u.id');		
		$query->where('h.id='.(int)$hotel->id);
		
		$db->setQuery($query);
		
		$row = $db->loadObject();
		
		if( $row )
		{
			return $row;	
		} 

		return null;
	}
	
	public function getAirlines()
	{
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('a.*, b.name AS airline_name');
		$query->from('#__sfs_airline_details AS a');
		$query->leftJoin('#__sfs_iatacodes AS b ON b.id = a.iatacode_id');		
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		if(count($rows))
		{
			return $rows;
		} else {
			return array();
		}
	} 
	
	public function saveContractedRates()
	{
		$hotelId 	= JRequest::getInt('id');
		$airlineId 	= JRequest::getInt('airline_id');


		
		if( !$hotelId )
		{
			$this->setError('Hotel Invalid');
			return false;
		}
		if( !$airlineId )
		{
			$this->setError('Airline Invalid');
			return false;
		}  
		
		$db = JFactory::getDbo();
		
		$roomRates  = JRequest::getVar('crooms', array(), 'post', 'array');		
		$start_date = JRequest::getVar('start_date');	
		$checkBox = JRequest::getVar('checkBox', array(), 'post', 'array');	
		$max_rate['srate'] = "false";
		$max_rate['sdrate'] = "false";
		$max_rate['trate'] = "false";
		$max_rate['qrate'] = "false";

		foreach ($checkBox as $key => $value) {
			if($key == 'srate'){ $max_rate['srate'] = "true";}
			if($key == 'sdrate'){ $max_rate['sdrate'] = "true";}
			if($key == 'trate'){ $max_rate['trate'] = "true";}
			if($key == 'qrate'){ $max_rate['qrate'] = "true";}
		}


		
		/*echo '<per>';
		print_r( $roomRates );
		echo '<per>';die;*/
		// get loaded contracted rates
		$query = 'SELECT * FROM #__sfs_contractedrates WHERE hotel_id='.$hotelId.' AND airline_id='.$airlineId.' AND date >= '.$db->quote($start_date);		
		$db->setQuery($query);
		$loadedContractRates = $db->loadObjectList('date');
		//print_r( $loadedContractRates );die;
		if(count($roomRates))
		{			
			$insertQueryValues = array();
			foreach ($roomRates as $roomRate) {
				
				//lchung
				$custom_comma_decimal['sd_rate'] = '.';
				$custom_comma_decimal['t_rate'] = '.';
				$custom_comma_decimal['s_rate'] = '.';
				$custom_comma_decimal['q_rate'] = '.';
				$custom_comma_decimal['breakfast'] = '.';
				$custom_comma_decimal['lunch'] = '.';
				$custom_comma_decimal['dinner'] = '.';
				$custom_comma_decimal['two_course_dinner'] = '.';
				$custom_comma_decimal['three_course_dinner'] = '.';
				
				if ( count( explode(",", $roomRate['sdrate'] ) ) > 1 ) {
					$roomRate['sdrate'] = str_replace(",", ".", $roomRate['sdrate']);
					$custom_comma_decimal['sd_rate'] = ',';
				}
				if ( count( explode(",", $roomRate['trate'] ) ) > 1 ) {
					$roomRate['trate'] = str_replace(",", ".", $roomRate['trate']);
					$custom_comma_decimal['t_rate'] = ',';
				}
				if ( count( explode(",", $roomRate['srate'] ) ) > 1 ) {
					$roomRate['srate'] = str_replace(",", ".", $roomRate['srate']);
					$custom_comma_decimal['s_rate'] = ',';
				}
				if ( count( explode(",", $roomRate['qrate'] ) ) > 1 ) {
					$roomRate['qrate'] = str_replace(",", ".", $roomRate['qrate']);
					$custom_comma_decimal['q_rate'] = ',';
				}
				if ( count( explode(",", $roomRate['breakfast'] ) ) > 1 ) {
					$roomRate['breakfast'] = str_replace(",", ".", $roomRate['breakfast']);
					$custom_comma_decimal['breakfast'] = ',';
				}
				if ( count( explode(",", $roomRate['lunch'] ) ) > 1 ) {
					$roomRate['lunch'] = str_replace(",", ".", $roomRate['lunch']);
					$custom_comma_decimal['lunch'] = ',';
				}
				if ( count( explode(",", $roomRate['dinner'] ) ) > 1 ) {
					$roomRate['dinner'] = str_replace(",", ".", $roomRate['dinner']);
					$custom_comma_decimal['dinner'] = ',';
				}
				if ( count( explode(",", $roomRate['two_course_dinner'] ) ) > 1 ) {
					$roomRate['two_course_dinner'] = str_replace(",", ".", $roomRate['two_course_dinner']);
					$custom_comma_decimal['two_course_dinner'] = ',';
				}
				if ( count( explode(",", $roomRate['three_course_dinner'] ) ) > 1 ) {
					$roomRate['three_course_dinner'] = str_replace(",", ".", $roomRate['three_course_dinner']);
					$custom_comma_decimal['three_course_dinner'] = ',';
				}
				//End lchung
				
				$roomRate['sdrate'] = floatval($roomRate['sdrate']);
				$roomRate['trate']  = floatval($roomRate['trate']);
				
				$roomRate['srate'] = floatval($roomRate['srate']);
				$roomRate['qrate']  = floatval($roomRate['qrate']);
				
				//lchung
				$roomRate['breakfast']  = floatval($roomRate['breakfast']);
				$roomRate['lunch']  = floatval($roomRate['lunch']);
				$roomRate['dinner']  = floatval($roomRate['dinner']);
				$roomRate['two_course_dinner']  = floatval($roomRate['two_course_dinner']);
				$roomRate['three_course_dinner']  = floatval($roomRate['three_course_dinner']);
				
				$roomRate['custom_comma_decimal'] = '\''.json_encode( $custom_comma_decimal ).'\'';
				///print_r( $roomRate );die;
				//End lchung
				
				// Update max rate
				$query = "UPDATE #__sfs_contractedrates SET max_rate='".json_encode($max_rate) . "'";
				$query .= ' WHERE hotel_id='.$hotelId.' AND airline_id='.$airlineId.' AND date='.$db->quote($roomRate['rdate']);
				$db->setQuery($query);
				$db->query();


				//if( $roomRate['sdrate'] > 0 || $roomRate['trate'] > 0 )
				//if( $roomRate['sdrate'] > 0 || $roomRate['trate'] > 0 || $roomRate['srate'] > 0 || $roomRate['qrate'] > 0 || $roomRate['breakfast'] > 0 || $roomRate['lunch'] > 0 || $roomRate['dinner'] > 0)
				//{
					if( isset($loadedContractRates[$roomRate['rdate']]) ) {
						//update
						$query = 'UPDATE #__sfs_contractedrates SET sd_rate='.$roomRate['sdrate'].',t_rate='.$roomRate['trate'].',s_rate='.$roomRate['srate'].',q_rate='.$roomRate['qrate'].',breakfast='.$roomRate['breakfast'].',lunch='.$roomRate['lunch'].',dinner='.$roomRate['dinner']. ', two_course_dinner=' . $roomRate['two_course_dinner']. ', three_course_dinner=' . $roomRate['three_course_dinner'] . ', custom_comma_decimal=' . $roomRate['custom_comma_decimal'];
						$query .= ' WHERE hotel_id='.$hotelId.' AND airline_id='.$airlineId.' AND date='.$db->quote($roomRate['rdate']);
						//echo (string)$query;die;
						$db->setQuery($query);
						$db->query();
					} else {
						//insert
						$insertQueryValues[] = '('.$airlineId.','.$hotelId.','.$db->quote($roomRate['rdate']).','.$roomRate['sdrate'].','.$roomRate['trate'].','.$roomRate['srate'].','.$roomRate['qrate'].','.$roomRate['breakfast'].','.$roomRate['lunch'].','.$roomRate['dinner'].','.$roomRate['two_course_dinner'].','.$roomRate['three_course_dinner'].', ' . $roomRate['custom_comma_decimal'] . ')';
					}
				}
			//}
			
			if( count($insertQueryValues) )
			{
				$query = 'INSERT INTO #__sfs_contractedrates(airline_id, hotel_id, date, sd_rate, t_rate, s_rate, q_rate, breakfast, lunch, dinner,two_course_dinner,three_course_dinner, custom_comma_decimal) VALUES ';
				$query .= implode(',', $insertQueryValues);
				///echo (string)$query;die;
				$db->setQuery($query);
				
				if( $db->query() ) 
				{					
					$query = 'SELECT * FROM #__sfs_contractedrates_map WHERE hotel_id='.$hotelId.' AND airline_id='.$airlineId;
					$db->setQuery($query);			
					$contractedMap = $db->loadObject();
					
					if( !$contractedMap )
					{
						$query = 'INSERT INTO #__sfs_contractedrates_map(hotel_id,airline_id) VALUES('.$hotelId.','.$airlineId.')';
						$db->setQuery($query);
						$db->query();
					}					
				}				
			}
			
		}
		
		return true;		
	}
	
	
	public function getContractedRates()
	{
		$result = array();
		
		$db 	= $this->getDbo();
		$hotel  = $this->getItem();	
			
		$now = $this->getDate('now','Y-m-d',$hotel->time_zone);
		
		$query = $db->getQuery(true);
		
		$query->select('a.*,b.company_name,c.name AS airline_name');
		$query->from('#__sfs_contractedrates AS a');

		$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS c ON c.id=b.iatacode_id');
		
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('a.date >= '.$db->quote($now) );
		
		$query->order('a.date ASC');
						
		$db->setQuery($query);		
		$rows = $db->loadObjectList();
		
		if(count($rows))
		{
			foreach ($rows as $row)
			{
				if( ! isset($result[$row->airline_id]) )
				{
					$result[$row->airline_id] = new stdClass();
					$result[$row->airline_id]->airline_id = $row->airline_id;
					if($row->airline_name)
					{
						$result[$row->airline_id]->airline_name = $row->airline_name;	
					} else {
						$result[$row->airline_id]->airline_name = $row->company_name;
					}
					$result[$row->airline_id]->rates = array();
				}
				$result[$row->airline_id]->rates[$row->date] = $row;
			}
		}
		
		return $result;		
	}
	
	public function getCurrency(){
		$hotel = $this->getItem();

		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);

		$query->select('code');
		$query->from('#__sfs_currency');
		$query->where('id='.$hotel->currency_id);

		$db->setQuery($query);		
		$result = $db->loadObject();
		
		return $result;
	}
	/*minhtran*/
	public function getAirport(){
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		$query->select('id,code');
		$query->from('#__sfs_iatacodes');
		$query->where('type=2');
		$db->setQuery($query);		
		$result = $db->loadObjectList();
		return $result;
	}
	/*minhtran*/
}


