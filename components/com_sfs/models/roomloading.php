<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelRoomloading extends JModel
{   

	protected $currrentStartDate = null;
	
    protected function populateState()
    {
        // Get the application object.
        $app    = JFactory::getApplication();        
        $params = $app->getParams('com_sfs');
   		
        // Load the parameters.
        $this->setState('params', $params);        
    }   

	public function getHotel()
	{
		$hotel = SFactory::getHotel();
		return $hotel;
	}
	
	public function getRoomsPrices()
	{
		$params = JComponentHelper::getParams('com_sfs');
		$cleanTime = trim($params->get('match_hours'));	
		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$cleanTime = $setup_airport['hours_on_match_page'];
		}
		//End lchung
			
		$db = $this->getDbo();
		$hotel = $this->getHotel();
		
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

//			if( $nowTime[0] >= $cleanTime[0] ){
//				if( $nowTime[0] == $cleanTime[0]) {
//					if( $nowTime[1] >= $cleanTime[1]  ) {
//						$is_next_day = true;
//					}
//				} else {
//					$is_next_day = true;
//				}
//			}


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
		
		$this->currrentStartDate = $now;
				
		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_room_inventory AS a');
				
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('a.date >= '.$db->quote($now) );
		$query->order('a.date ASC');
						
		$db->setQuery($query,0,30);		
		$rows = $db->loadObjectList();

        $result = SfsHelperDate::getNextDates($now,30);
			
		if(count($rows)) {
			foreach ($rows as $row) {
				$result [ $row->date ] = $row;	
			}
		}
		return $result;		
	}
	
	public function getAirlineContractedRates()
	{
		$hotel = $this->getHotel();
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		
		$query->select('DISTINCT a.airline_id');
		$query->from('#__sfs_contractedrates AS a');
		
		$query->select('b.company_name,c.name AS airline_name');
		$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS c ON c.id=b.iatacode_id');
		
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('a.date >= '.$db->quote($this->currrentStartDate) );
				
		$db->setQuery($query);
		
		$result = $db->loadObjectList('airline_id');
		
		$query->clear();
		$query->select('a.airline_id,a.date');
		$query->from('#__sfs_contractedrates_exclusions AS a');
		$query->where('a.hotel_id='.$hotel->id);
		$query->where('a.date >= '.$db->quote($this->currrentStartDate) );
		$db->setQuery($query);
		
		$exclusions = $db->loadObjectList();
		
		
		foreach ($result as & $airline) 
		{
			if( ! isset($airline->exclude_dates) )
			{
				$airline->exclude_dates = array();	
			}
			
			foreach ($exclusions as $exclusion)
			{
				if($exclusion->airline_id == $airline->airline_id )
				{
					$airline->exclude_dates[] = $exclusion->date;
				}
			}
			
		}
		return $result;
	}
	
	public function savePrices($post)
	{			
		SFSCore::includePath('log');
				
		$user = JFactory::getUser();	
		
		if ( ! SFSAccess::check($user, 'h.admin' ) ) {
			$this->setError('Restricted Access');
			return false;
		}		
		
        $app    = JFactory::getApplication();                        		
		$date 	= JFactory::getDate();
		$db 	= $this->getDbo();
		$hotel 	= $this->getHotel();
		
		$hotelSetting   = $hotel->getBackendSetting();
		
		$params = $app->getParams('com_sfs');
        $rule = (int) $params->get('rule25',25);
        
        $enable_rule = (int) $params->get('enable_rule25',1);
		
		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$rule = ((int)$setup_airport['rule25']) ? (int)$setup_airport['rule25'] : 25;
			$enable_rule = (int)$setup_airport['enabel_25rule'];
		}
		//End lchung
        		
		$rooms = JRequest::getVar('rooms', array(), 'post', 'array');				
		
		$transportall = JRequest::getCmd('transportall');		
				
		if($transportall=='yes'){
			$transportall = true;
			$db->setQuery('UPDATE #__sfs_hotel_taxes SET transport=1 WHERE hotel_id='.(int)$hotel->id);
		} else {
			$transportall = false;
			$db->setQuery('UPDATE #__sfs_hotel_taxes SET transport=0 WHERE hotel_id='.(int)$hotel->id);
		}	
		
		$db->query();
			
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
				if($transportall) {
					$transport = 1;
				} else{
					if( isset($rooms[$i]['transport']) && (int) $rooms[$i]['transport'] == 1 ) {
						$transport = 1;
					} else {
						$transport = 0;
					}
				}	
				
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
				
				// if exist then update room price
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
										
					if( ( $i == 0 && $enable_rule == 0 ) || $i > 0 ) {
						$rooms[$i]['transport'] = $transport;
						$this->updateInventory($loaded,$rooms[$i]);
						continue;
					}
								
					//If is today, we need to check and make sure the results do not outside the range of 25%
					if( $i==0 && (  $loaded->sd_room_rate > 0 ||  $loaded->t_room_rate > 0  ) ) {							
						$hasChange = false;
						if( $loaded->sd_room_rate != $rooms[$i]['sdrate']) {
							$hasChange = true;								
						} else if( $loaded->t_room_rate != $rooms[$i]['trate']) {
							$hasChange = true;								
						} else if( $loaded->sd_room_total != $rooms[$i]['sdroom']){
							$hasChange = true;							
						} else if( $loaded->t_room_total != $rooms[$i]['troom']){
							$hasChange = true;							
						} 
						
						if( !$hasChange ) continue;
						
						$loadingErrorText = JText::sprintf('COM_SFS_ROOMLOADING_SAVE_ERROR',$rule.'%');						
						
						if( $this->checkRule( $loaded->sd_room_rate , floatval( $rooms[$i]['sdrate']) , $rule, $loadingErrorText) ){
							$loaded->sd_room_rate_modified = $rooms[$i]['sdrate'];	
						} else {
							continue;
						}
						
						if( $this->checkRule( $loaded->t_room_rate , floatval( $rooms[$i]['trate']) , $rule, $loadingErrorText) ){
							$loaded->t_room_rate_modified = $rooms[$i]['trate'];	
						} else {
							continue;
						}	
												
						if( $this->checkRule( $loaded->sd_room_total ,(int)$rooms[$i]['sdroom'] , $rule, JText::sprintf('COM_SFS_ROOMLOADING_SAVEROOM_ERROR',$rule.'%'),'room') ){
							$loaded->sd_room_total = $rooms[$i]['sdroom'];	
						} else {
							continue;
						}
						
						if( $this->checkRule( $loaded->t_room_total , (int)$rooms[$i]['troom'] , $rule, JText::sprintf('COM_SFS_ROOMLOADING_SAVEROOM_ERROR',$rule.'%'),'room') ){
							$loaded->t_room_total = $rooms[$i]['troom'];	
						} else {
							continue;
						}				
													
						$table = JTable::getInstance('Inventory', 'JTable');
		
						$loaded->transport_included = $transport;
						$loaded->modified = JFactory::getDate()->toSql();
						$loaded->modified_by = JFactory::getUser()->id;		
						
						if( !$table->bind($loaded) ){
							return false;
						}		
						if( !$table->store() ){
							return false;
						}		

						$this->track($loaded, $rooms[$i]);
						
					}					
					
				} else {	
					// insert new room and price 													
					$this->insertInventory($rooms[$i], $hotel->id, $transport);					
				}				
			}
			
		}
		
		// save contracted rate
		$airlineContractedRates = JRequest::getVar('airlineContractedRates', array(), 'post', 'array');
		if( count($airlineContractedRates) ){	
					
			$firstDate = $rooms[0]['rdate'];
			$query = 'DELETE FROM #__sfs_contractedrates_exclusions WHERE hotel_id='.$hotel->id.' AND date >='.$db->quote($firstDate);
			$db->setQuery($query);
			$db->query();
			
			$tmpQueryArray = array();
			
			foreach ($airlineContractedRates as $airlineId => $availableDates  )
			{
				foreach ($rooms as $room)
				{					
					if( ! in_array($room['rdate'], $availableDates) )
					{
						$tmpQueryArray[] = '('.$airlineId.','.$hotel->id.','.$db->quote($room['rdate']).')';						
					}	
				}
			}	
			if( count($tmpQueryArray) )
			{
				$query = 'INSERT INTO #__sfs_contractedrates_exclusions(airline_id,hotel_id,date) VALUES '.implode(',', $tmpQueryArray);
				$db->setQuery($query);
				$db->query();
			}			
		}	
		
		SfsLog::insert('roomloading',$hotel->id);
							
		return true;
	}
	
	protected function updateInventory( $loaded ,$data )
	{			
		$hotel 			= $this->getHotel();		
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
		
		$this->track($loaded, $data);		
		
		return true;		
	}
	
	/**	 
	 * If some roomblocks made by the airline we track the changes.
	 * @param $loaded
	 * @param $data
	 */
	protected function track($loaded ,$data)
	{
		if( (int)$loaded->booked_sdroom + (int)$loaded->booked_troom > 0 ) {
			$db = $this->getDbo();
			$query = 'SELECT COUNT(*) FROM #__sfs_reservations WHERE room_id='.(int)$loaded->id;
			$db->setQuery($query);
			$number_blocks = (int) $db->loadResult();

			if( $number_blocks > 0 ) {
				$query = 'SELECT COUNT(*) FROM #__sfs_room_inventory_tracking WHERE room_id='.(int)$loaded->id.' AND block_order='.$number_blocks;
				$db->setQuery($query);
				$count_track = $db->loadResult();
				
				if( (int)$count_track == 0 )
				{					
					$query = 'INSERT INTO #__sfs_room_inventory_tracking(room_id,block_order,sdroom,sdrate,troom,trate,user_id,modified) VALUES (';
					$query .=(int)$loaded->id.','.(int)$number_blocks.','.(int)$data['sdroom'].','.floatval($data['sdrate']).',';
					$query .= (int)$data['troom'].','.floatval($data['trate']).','.JFactory::getUser()->id.','.$db->quote(JFactory::getDate()->toSql()).')';
					$db->setQuery($query);
					$db->query();
				}
			}
		}
	}
	
	protected function insertInventory( & $data , $hotel_id, $transport)
	{		
		$hotel 			= $this->getHotel();		
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
	
	private function checkRule( $source , $destination, $rule , $loadingErrorText, $type = 'rate' ) {	
		
		if ( $source > 0 && $destination > 0 ) {
			if($type=='rate'){	
				if( ($source < $destination) && ( $destination > ( $source + ( $source * $rule ) / 100) ) ) {								
					$this->setError( $loadingErrorText );
					return false;			
				}
			}
			if( ($source > $destination) && ( $destination < ( $source - ( $source * $rule ) / 100) )  ) {
				$this->setError( $loadingErrorText );
				return false;
			}	
			
		}
		
		return true;	
	}
    
}




