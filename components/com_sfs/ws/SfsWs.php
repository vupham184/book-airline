<?php

/**
 * Reservation status
 *
 * O open
 * P pending
 * D deleted
 * R archived
 * A approved
 * T Tentative
 * C Challenged
 *
 * @author datgs
 *
 */

class SfsWs {


	static function getConfig(){
		include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';
		return $config;
	}
	
	static function getAirplusConfig(){
		include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config-airplus.php';
		return $config;
	}

	static function load(){
		require_once JPATH_BASE . DIRECTORY_SEPARATOR . 'ws'. DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Ws' . DIRECTORY_SEPARATOR . 'Loader.php';
	}

	static function init(){
		self::load();
		Ws_Loader::init();
	}

	static function factory() {
		return Ws_Factory::createWS(self::getConfig());
	}

    static function getAirportNumberOfPriorities()
    {
        return $ws = self::factory()->getAirportNumberOfPriorities();
    }

    static function getCurrencyIdByCurrencyCode($currencyCode)
    {
        return $ws = self::factory()->getCurrencyIdByCurrencyCode($currencyCode);
    }

    static function getAllAirportLocation()
    {
        return $ws = self::factory()->getAllAirportLocation();
    }

	# synchronize hotels from WS to SFS
	static function syncHotels($airportIndex = null) {

		ini_set('max_execution_time', 0);

		$config = self::getConfig();
		$user  = JFactory::getUser();
    	$db    = JFactory::getDbo();
    	/* @var $iatacodeTable JTable  */
    	/* @var $hotelTable JTable  */
    	/* @var $countryTable JTable  */
    	/* @var $stateTable JTable  */
    	/* @var $hotelAirportTable JTable  */
    	/* @var $hotelTransportTable JTable  */
    	/* @var $roomInventory JTable  */
    	$iatacodeTable = JTable::getInstance('IATACode', 'SfsTable');
    	$hotelTable = JTable::getInstance('Hotel', 'JTable');
    	$hotelAirportTable = JTable::getInstance('HotelAirport', 'JTable');
    	$hotelTransportTable = JTable::getInstance('HotelTransport', 'JTable');
    	$countryTable = JTable::getInstance('Country', 'SfsTable');
    	$stateTable = JTable::getInstance('State', 'SfsTable');
    	$roomInventory = JTable::getInstance('Inventory', 'JTable');
		# search airport in db

		$iatacodeTable->load(array('type' => 2));
		$query = $iatacodeTable->getDbo()->getQuery();
		$db->setQuery($query);
		$airports = $db->loadObjectList();

		# find properties nearby airport

		/* @var $ws Ws_Interface  */
		$ws = self::factory();
		$ws_type = strtolower($config['type']);

		if($airportIndex !== null) {
			if(empty($airports[$airportIndex])) {
				throw new Exception('Airport Index Out Of Range');
			}
			$airports = array($airports[$airportIndex]);
		}

		foreach($airports as $airport) {
			$airportIATACode = $airport->code;
			$airportID =  $airport->id;
			$properties = $ws->getPropertiesNearByAirportIATACode($airportIATACode);

			$authCreate = array(
				'block' => 0,
				'step_completed' => 0,
				'systemEmails' => 0,
				'created_by' => 0,
				'created_date' => date('Y-m-d H:i:s'),
				'modified_by' => 0,
				'modified_date' => date('Y-m-d H:i:s'),
			);
			$authModify = array(
				'modified_date' => date('Y-m-d H:i:s')
			);
			/* @var $prop Ws_Do_Base_Property */


			foreach($properties as $prop) {
				$countryTable->load(array('name' => $prop->Country));
				$countryID = (int)$countryTable->id;
				$stateTable->load(array('name' => $prop->Region, 'country_id' => $countryID));
				$stateID = (int)$stateTable->id;
				$locationID = 1; # AIRPORT location
				$hotel = array(
					'ws_type' => $ws_type,
					'ws_id' => $prop->PrimaryID,
					'name' => $prop->Name,
					'alias' => $ws_type . '-' . $prop->PrimaryID,
					'star' => $prop->Rating,
					#'web_address' => '',
					'telephone' => $prop->Telephone,
					'fax' => $prop->Fax,
					'address' => $prop->Address1,
					'address1' => $prop->Address2,
					'zipcode' => $prop->PostcodeZip,
					'city' => $prop->TownCity,
					'state_id' => $stateID,
					'country_id' => $countryID,
					'location_id' => 1, # all air port
					#'time_zone' => '',
					'geo_location_latitude' => $prop->Latitude,
					'geo_location_longitude' => $prop->Longitude,
					'airport_code' => $prop->Airport,
				);

				# update hotel
				$hasHotel = $hotelTable->load(array(
					'ws_type' => $ws_type,
					'ws_id' => $prop->PrimaryID)
				);

				# do update
				if($hasHotel) {
					$hotelTable->bind(array_merge($hotel, $authModify));
				}
				# do create
				else {
					$hotelTable->bind(array_merge($hotel, $authCreate));
					$hotelTable->id = null;
				}

				if($hotelTable->store()) {

					$hotelID = $hotelTable->id;
					$hotelWSID = $hotelTable->ws_id;

					$hasAirportLinked = $hotelAirportTable->load(array(
										'hotel_id' => $hotelID,
										'airport_id' => $airportID));

					if(!$hasAirportLinked) {
						$hotelAirportTable->id = null;
						$hotelAirportTable->bind(array(
							'hotel_id' => $hotelID,
							'airport_id' => $airportID,
							'distance' => 0,
							'distance_unit' => 'km',
							'normal_hours' => 0,
							'rush_hours' => 0,
							'main' => 0
						));
						$hotelAirportTable->store();
					} else {
						# do nothing
					}

					# import hotel transport
					$hasAny = $hotelTransportTable->load(array(
									'hotel_id' => $hotelID
					));
					if(!$hasAny) {
						$hotelTransportTable->id = null;
						$hotelTransportTable->bind(array(
							'hotel_id' => $hotelID,
							'transport_available' => 0, // 0 = no
							'transport_complementary' => 0, // 0 = no
							'operating_hour' => 1, // 24/24
							'operating_opentime' => '00:00:00',
							'operating_closetime' => '00:00:00',
							'frequency_service' => 0, // not set
							'pickup_details' => ''
						));
						$hotelTransportTable->store();
					}
					unset($hasAny);

					/**
					for($i = 0; $i <= 7; $i++) {
						$date = date('Y-m-d', strtotime('+' . $i . ' days'));
						$hasAny = $roomInventory->load(array(
							'hotel_id' => $hotelID,
							'date' => $date
						));

						if(!$hasAny) {
							$roomInventory->id = null;
							$roomInventory->bind(array(
								'hotel_id' => $hotelID,
								'date' => $date,
								'sd_room_total' => 0,
								'sd_room_rate' => 0,
								'sd_room_rate_modified' => 0,
								'sd_room_rank' => 0,
								'sd_num_rank' => 0,
								't_room_total' => 0,
								't_room_rate' => 0,
								't_room_rate_modified' => 0,
								't_room_rank' => 0,
								't_num_rank' => 0,
								's_room_total' => 0,
								's_room_rate' => 0,
								's_room_rate_modified' => 0,
								's_room_rank' => 0,
								's_num_rank' => 0,
								'q_room_total' => 0,
								'q_room_rate' => 0,
								'q_room_rate_modified' => 0,
								'q_room_rank' => 0,
								'q_num_rank' => 0,
								'transport_included' => 0,
								'created' => date('Y-m-d H:i:s'),
								'created_by' => 0,
								'modified' => date('Y-m-d H:i:s'),
								'booked_sdroom' => 0,
								'booked_troom' => 0,
								'booked_sroom' => 0,
								'booked_qroom' => 0
							));
							$roomInventory->store();
						}
						unset($hasAny);
					}
					**/
					# END # import hotel room inventory for a week
				}
			}
		}
	}

	static function cleanSearchHotelsCache(){
		$jUser = JFactory::getUser();
		$cachePrefix = 'user-' . $jUser->id;
		$cacheDir = JPATH_BASE . '/cache/com_sfs/ws-search-result/' . $cachePrefix . '/';

		array_map('unlink', glob($cacheDir . "*"));
	}

	static function searchHotels($airportIATACode, $ArrivalDate, $NumberOfRooms, $roomRequests, $Duration, $cacheonly = false){
		# check webservice-enabled

		$componentParams = &JComponentHelper::getParams('com_sfs');
		$wsEnabled = $componentParams->get('webservice-enabled', 0);
		$configSaleRate = $componentParams->get('ws-sales-rate');
		if(empty($wsEnabled)) {
			return array();
		}

		$jUser = JFactory::getUser();
		$cachePrefix = 'user-' . $jUser->id;

		$args = func_get_args();
		array_pop($args);

        // Check priority
        $priority = JRequest::getInt('priority') ? JRequest::getInt('priority') : 1;

        $airline_current = SAirline::getInstance()->getCurrentAirport(1);
        $airport_current_code = $airline_current->code;

		$cacheKey = $airport_current_code.'-'.$ArrivalDate . '-' . sha1(var_export($args, true).$priority);

        $cacheDir = JPATH_BASE . '/cache/com_sfs/ws-search-result/' . $cachePrefix . '/';

        if(!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true); // make dir recursive
        }

        $cacheFile = $cacheDir . $cacheKey;

        if(!unserialize(file_get_contents($cacheFile)))
            $cacheExpires = 2 * 60; # 2 mins
        else{
            $cacheExpires = 15 * 60; # 15 mins
        }

		#print_r($args);
		#echo $cacheFile, ',', file_exists($cacheFile), ',', (int)filemtime($cacheFile), ',', time() - $cacheExpires;die();
		$cacheValid = file_exists($cacheFile) && filemtime($cacheFile) > time() - $cacheExpires;

		if($cacheValid) {
			return unserialize(file_get_contents($cacheFile));
		}

		# search from cache only, don't need to take next steps
		if($cacheonly) {
			return array();
		}
        if($airline_current->time_zone){
            $time = new \DateTime($ArrivalDate, new DateTimeZone($airline_current->time_zone));
            $timezoneOffset = $time->format('P');
			$timezoneTime = SfsHelperDate::time('now', $airline_current->time_zone);
           	$ArrivalDate = gmdate($ArrivalDate."\T$timezoneTime:00".$timezoneOffset);
        }else{
            $ArrivalDate = gmdate($ArrivalDate."\TH:i:s");
        }

		$request = new Ws_Do_Search_Request();
		$request->ArrivalDate = $ArrivalDate;
		$request->Duration = $Duration; // 1 day
		$request->NumberOfRooms = $NumberOfRooms;
		$request->AirportIATACode = $airportIATACode;
		$request->Rooms = array();

		$allSpecific = true;

		/** @var $rq Ws_Do_Search_RoomRequest*/
		foreach ($roomRequests as $room) {
			$rq = new Ws_Do_Search_RoomRequest();
			if((int)$room['num_adults'] == 0)
			{
				$allSpecific = false;
			}
			else
			{
				$rq->NumAdults = (int)$room['num_adults'];
                $num_children = 0;
                $num_infants = 0;

				if(isset($room['children_ages'])) {
					foreach($room['children_ages'] as $age) {
                        if($age <= 2)
                            $num_infants++;
                        else
                            $num_children++;
						$rqa = new Ws_Do_Search_RequestChildAge();
						$rqa->Age = (int)$age;
						$rq->ChildAges[] = $rqa;
					}
				}
                $rq->NumChildren = $num_children;
                $rq->NumInfants = $num_infants;
			}
			$request->Rooms[] = $rq;
		}


		if(empty($roomRequests))
		{
				$rq = new Ws_Do_Search_RoomRequest();
				$rq->NumAdults = 2;
				$request->Rooms[] = $rq;
				$request->Rooms[] = $rq;
		}



		/* @var $ws Ws_Interface  */
		/* @var $h Ws_Do_Search_Result */
		/* @var $rt Ws_Do_Search_RoomTypeResult */
		/* @var $rt2 Ws_Do_Search_RoomTypeResult */
		/* @var $roomInventory JTable  */
		/* @var $hotelTable JTable  */
		/* @var $blackListItem Ws_Do_Base_Blacklist */
		$ws = self::factory();

		$roomInventory = JTable::getInstance('Inventory', 'JTable');
		$hotelTable = JTable::getInstance('Hotel', 'JTable');
		$config = self::getConfig();
		$wsType = strtolower($config['type']);

		$blackList = $ws->getBlackLists();
		$blackListPropertyReferenceIDs = array();
		foreach($blackList as $blackListItem) {
			$blackListPropertyReferenceIDs[] = $blackListItem->PropertyReferenceID;
		}

		$result = array();

		$maxRequest = 1;
		if(!$allSpecific || $NumberOfRooms != 1) {
			$maxRequest = 2;
		}

		for ($i = 0; $i < $maxRequest; $i++) {
			foreach($request->Rooms as $k => $rq) {
				if((int)$roomRequests[$k]['num_adults'] == 0) {
					$rq->NumAdults = $k + 1;
				}
			}

			try{
				self::log('search hotels around airport: ' . $request->AirportIATACode);
				self::log('search NumberOfRooms: ' . $NumberOfRooms);
				self::log('search NumAdultsPerRoom: ' . $rq->NumAdults);
				$r = $ws->searchHotels($request, 30, 0);
				$result[] = $r;
			} catch (Exception $e) {
				self::log($e->getMessage());
				self::log($e->getTraceAsString());
			}
		}

		$hotels = array();

		# merge results
		foreach($result as $hotelset) {
			if($hotelset) {
				foreach($hotelset as $h) {

					# blacklisted?
					if(in_array($h->PrimaryID, $blackListPropertyReferenceIDs)) {
						continue;
					}

					$key = (string)$h->PrimaryID;
					if(isset($hotels[$key])) {
						$h2 = $hotels[$key];
						foreach($h->RoomTypes as $rt) {
							$found = false;
							foreach($h2->RoomTypes as $rt2) {
                                $PropertyReferenceID = $rt->PropertyReferenceID;
                                $PropertyReferenceID2 = $rt2->PropertyReferenceID;
                                $bookingToken = $rt->BookingToken;
                                $bookingToken2 = $rt2->BookingToken;
                                if($PropertyReferenceID && $PropertyReferenceID == $PropertyReferenceID2) {
                                    $found = true;
                                } else if($bookingToken && $bookingToken == $bookingToken2){
									$found = true;
								}
							}
							if(!$found) {
								$h2->RoomTypes[] = $rt;
								$hotels[$key] = $h2;
							}
						}
					} else {
						$hotels[$key] = $h;
					}
				}
			}
		}

		$hotels = array_values($hotels);
		#echo '<pre>';
		#print_r($hotels);
		#die();

		if($hotels) {
			foreach($hotels as $h) {
				$wsID = $h->PrimaryID;
				$sRate = 0;$total_sRate = 0;
				$sdRate = 0;$total_sdRate = 0;
				$tRate = 0;$total_tRate = 0;
				$qRate = 0;$total_qRate = 0;
				foreach($h->RoomTypes as $rt) {
                    $rt->OriginalTotal = $rt->Total;
                    $rt->Total = ceil($rt->Total*(1+ $configSaleRate/100)); // convert to sale price
                    switch($rt->NumAdultsPerRoom)
                    {
                        case 1:
                            $total_sRate++;
                            if($sRate > $rt->Total || $sRate === 0)
                                $sRate = $rt->Total;
                            break;
                        case 2:
                            $total_sdRate++;
                            if($sdRate > $rt->Total || $sdRate === 0)
                                $sdRate = $rt->Total;
                            break;
                        case 3:
                            $total_qRate++;
                            if($tRate > $rt->Total || $tRate === 0)
                                $tRate = $rt->Total;
                            break;
                        default:
                            $total_tRate++;
                            if($qRate > $rt->Total || $qRate === 0)
                                $qRate = $rt->Total;
                            break;
                    }
				}
                if($qRate != 0)  $h->StandardRate = $qRate;
                if($tRate != 0)  $h->StandardRate = $tRate;
                if($sdRate != 0) $h->StandardRate = $sdRate;
                if($sRate != 0)  $h->StandardRate = $sRate;

				$hasAny = $hotelTable->load(array(
					'ws_id' => $wsID,
					'ws_type' => $wsType
				));

				if($hasAny) {
					$hotelID = $hotelTable->id;
					$hasAny = $roomInventory->load(array(
						'hotel_id' => $hotelID,
						'date' => $ArrivalDate
					));

					$inven = array(
						'hotel_id' => $hotelID,
						'date' => $ArrivalDate,
						's_room_total' => $total_sRate,
						's_room_rate' => $sRate,
						's_room_rate_modified' => $sRate,
						's_room_rank' => 0,
						's_num_rank' => 0,
						'sd_room_total' => $total_sdRate,
						'sd_room_rate' => $sdRate,
						'sd_room_rate_modified' => $sdRate,
						'sd_room_rank' => 0,
						'sd_num_rank' => 0,
						't_room_total' => $total_tRate,
						't_room_rate' => $tRate,
						't_room_rate_modified' => $tRate,
						't_room_rank' => 0,
						't_num_rank' => 0,
						'q_room_total' => $total_qRate,
						'q_room_rate' => $qRate,
						'q_room_rate_modified' => $qRate,
						'q_room_rank' => 0,
						'q_num_rank' => 0,
						'transport_included' => 0,
						'created' => date('Y-m-d H:i:s'),
						'created_by' => 1,
						'modified' => date('Y-m-d H:i:s'),
						'booked_sdroom' => 0,
						'booked_troom' => 0,
						'booked_sroom' => 0,
						'booked_qroom' => 0
					);

					if(!$hasAny) {
						$roomInventory->id = null;
						$roomInventory->bind($inven);
						$roomInventory->store();
					} else {
						$roomInventory->bind($inven);
						$roomInventory->store();
					}
					# END if(!$hasAny) {
				}

				unset($hasAny);
			}
		}

		#write cache
        file_put_contents($cacheFile, serialize($hotels), LOCK_EX);

		return $hotels;
	}

	static function preBook($preBookRoomTypes) {

		# check webservice-enabled
		$componentParams = &JComponentHelper::getParams('com_sfs');
		$wsEnabled = $componentParams->get('webservice-enabled', 0);
		$configSaleRate = $componentParams->get('ws-sales-rate');
		if(empty($wsEnabled)) {
			return array();
		}

		$preBookRoomType = $preBookRoomTypes[0];
		$totalAdults = 0;

		/* @var $preBookRoomType Ws_Do_Search_RoomTypeResult */
		/* @var $prt Ws_Do_PreBook_Request */
		$preBookRequest = new Ws_Do_PreBook_Request();
		$preBookRequest->ArrivalDate = $preBookRoomType->ArrivalDate;
		$preBookRequest->Duration = $preBookRoomType->Duration;
		$preBookRequest->PropertyID = $preBookRoomType->PropertyID;
		$preBookRequest->RoomBookings = array();

		foreach($preBookRoomTypes as $prt) {
			for($i =0; $i < $prt->NumberOfRooms; $i++) {
				$rb = new Ws_Do_PreBook_RoomRequest();
				$rb->BookingToken = $prt->BookingToken;
				$rb->PropertyRoomTypeID = $prt->PropertyRoomTypeID;
				$rb->NumAdults = $prt->NumAdultsPerRoom;
				$rb->NumChildren = $prt->NumChildrenPerRoom;
				$rb->NumInfants = $prt->NumInfantsPerRoom;
				$rb->ChildAges = $prt->ChildAges;
				$rb->MealBasisID = $prt->MealBasisID;
				$rb->MealBasisName = $prt->MealBasisName;
				$totalAdults += $rb->NumAdults;
				$preBookRequest->RoomBookings[] = $rb;
			}
		}

		$preBookResult = null;

		try {
			self::log('pre booking with propertyID: ' . $preBookRequest->PropertyID);
			self::log('Total Adults: ' . $totalAdults);
			$preBookResult = self::factory()->preBook($preBookRequest);
			$preBookResult->OriginalTotalPrice = $preBookResult->TotalPrice;
			$preBookResult->TotalPrice = $preBookResult->TotalPrice* ( 1 + $configSaleRate/100); // convert to sale price
			$preBookResult->TotalPrice = ceil($preBookResult->TotalPrice); // convert to sale price
		} catch (Exception $e) {
			self::log($e->getMessage());
			self::log($e->getTraceAsString());
		}

		if(!$preBookRequest) {
			self::cleanSearchHotelsCache();
		}

		return $preBookResult;
	}

	static function book($wsRoomTypes, $wsPreBook, $roomGuests, $tradeReference){

		# check webservice-enabled
		$componentParams = &JComponentHelper::getParams('com_sfs');
		$wsEnabled = $componentParams->get('webservice-enabled', 0);
		$configSaleRate = $componentParams->get('ws-sales-rate');
		if(empty($wsEnabled)) {
			return array();
		}

		$wsRoomType = $wsRoomTypes[0];

		/* @var $wsRoomType Ws_Do_Search_RoomTypeResult */
		/* @var $wsPreBook Ws_Do_PreBook_Response */
		/* @var $roomGuests array */

		$bookRequest = new Ws_Do_Book_Request();
		$bookRequest->ArrivalDate = $wsRoomType->ArrivalDate;
		$bookRequest->Duration = $wsRoomType->Duration;
		$bookRequest->PropertyID = $wsRoomType->PropertyID;
		$bookRequest->PreBookingToken = $wsPreBook->PreBookingToken;
		$bookRequest->TradeReference = $tradeReference;
		$bookRequest->RoomBookings = array();

		$j = -1;
		foreach($wsRoomTypes as $wsRoomType) {
			for($i = 0 ; $i< $wsRoomType->NumberOfRooms; $i++) {
				$j ++;
				$guests = @$roomGuests[$j];

				$rb = new Ws_Do_Book_RoomRequest();
				$rb->BookingToken = $wsRoomType->BookingToken;
                $rb->PropertyRoomTypeID = $wsRoomType->PropertyRoomTypeID;
				$rb->MealBasisID = $wsRoomType->MealBasisID;
				$rb->MealBasisName = $wsRoomType->MealBasisName;
				$rb->NumAdults = $wsRoomType->NumAdultsPerRoom;
				$rb->NumChildren = $wsRoomType->NumChildrenPerRoom;
				$rb->NumInfants = $wsRoomType->NumInfantsPerRoom;
				$rb->Guests = array();

				foreach($guests as $i => $guest) {
					$gr = new Ws_Do_Book_RoomGuestRequest();

					if(empty($guest['FirstName'])) {
						continue;
					}

					if($i < $rb->NumAdults) {
						$type = 'Adult';
					} else if($i < $rb->NumAdults + $rb->NumChildren) {
						$type = 'Child';
						$childAge = $wsRoomType->ChildAges[$rb->NumAdults + $rb->NumChildren - $i - 1];
					} else {
						$type = 'Infant';
					}
					$gr->Type = $type;
					$gr->Title = $guest['Title'];
					$gr->FirstName = $guest['FirstName'];
					$gr->LastName = $guest['LastName'];
					if($gr->Type == 'Child') {
						$gr->Age = $childAge;
					}


					$rb->Guests[] = $gr;

					if(empty($bookRequest->LeadGuestFirstName)) {
						$bookRequest->LeadGuestFirstName = $gr->FirstName;
						$bookRequest->LeadGuestLastName = $gr->LastName;
						$bookRequest->LeadGuestTitle = $gr->Title;
					}
				}

				//$rb->NumAdults = count($rb->Guests);
				#print_r($rb);
				$bookRequest->RoomBookings[] = $rb;
			}
		}

		$bookResult = null;

		try{
			self::log('book with propertyID: ' . $bookRequest->PropertyID);
			self::log('pre booking token: ' . $bookRequest->PreBookingToken);
			$bookResult = self::factory()->book($bookRequest);
			$bookResult->OriginalTotalPrice = $bookResult->TotalPrice ;
			$bookResult->TotalPrice = $bookResult->TotalPrice*(1 + $configSaleRate/100); // convert to sale price
			$bookResult->TotalPrice = ceil($bookResult->TotalPrice); // convert to sale price
		} catch (Exception $e) {
			self::log($e->getMessage());
			self::log($e->getTraceAsString());
		}

		# force clean cache after book to have full filter
		self::cleanSearchHotelsCache();

		return $bookResult;
	}

	static function getHotelDetail($sfs_hotel_id) {

		# check webservice-enabled
		$componentParams = &JComponentHelper::getParams('com_sfs');
		$wsEnabled = $componentParams->get('webservice-enabled', 0);
		if(empty($wsEnabled)) {
			return array();
		}



		$cacheKey = 'ws-hotel-detail-' . $sfs_hotel_id;
		$cacheExpires = 24 * 60 * 30; # 15 mins
		$cacheDir = JPATH_BASE . '/cache/com_sfs/ws-hotel-detail/';

		if(!is_dir($cacheDir)) {
			mkdir($cacheDir, 0777, true); // make dir recursive
		}

		$cacheFile = $cacheDir . $cacheKey;
		#print_r($args);
		#echo $cacheFile, ',', file_exists($cacheFile), ',', (int)filemtime($cacheFile), ',', time() - $cacheExpires;die();
		$cacheValid = file_exists($cacheFile) && filemtime($cacheFile) > time() - $cacheExpires;

		if($cacheValid) {
			return unserialize(file_get_contents($cacheFile));
		}



		$hotelTable = JTable::getInstance('Hotel', 'JTable');
		$loaded = $hotelTable->load($sfs_hotel_id);

		$detail = null;

		if($loaded) {
			$detail = self::factory()->getPropertyDetail(null, $hotelTable->ws_id);

			#write cache
			file_put_contents($cacheFile, serialize($detail), LOCK_EX);
		}

		return $detail;
	}

	static function log($message) {
		$date = date('Y-m-d');
		$logDir = JPATH_BASE . '/tmp/ws/';
		if(!is_dir($logDir)) {
			mkdir($logDir, 0777, true);
		}
		$file = $logDir . $date . '-ws.log';
		file_put_contents($file, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
	}
	
	static function logAirplus($message) {
		$date = date('Y-m-d');
		$logDir = JPATH_BASE . '/tmp/ws/airplus/';
		if(!is_dir($logDir)) {
			mkdir($logDir, 0777, true);
		}
		$file = $logDir . $date . '-airplus.log';
		file_put_contents($file, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
	}
	
	/**
	 * @param unknown $airlineId
	 * @param unknown $userId
	 * @param unknown $options array()
	 * 				type: meal|taxi
	 * 				reservation_id: int
	 * 				voucher_id: int
	 * 				unique_id: string
	 * 				flightnumber: string
     *              startdate
     *              enddate
	 */
	static function airplusCall($airlineId, $userId, $options){
		
		$type = @$options['type'];
		if(empty($type)) {
			throw new Exception('$options[type] is required');
		}
		
		$reservationID 	= @$options['reservation_id'];
		if($reservationID) {
			$reservation 	= SReservation::getInstance($reservationID);
			$startdate 		= $reservation->blockdate;
		} else if(@$options['startdate']) {
			$startdate 		= $options['startdate'];
		}
		
		$startdate = $startdate ? date('dMy', strtotime($startdate)) :date('dMy');
		
		$voucherID = @$options['voucher_id'];
		if($voucherID) {
			$voucher = SVoucher::getInstance($voucherID);
		}
		
		if(!empty($reservation)) {
			$blockcode = $reservation->blockcode;
		} else {
			$blockcode = @$options['unique_id'];
		}
		
		$airline = SAirline::getInstance($airlineId, $userId);
		$config  = self::getAirplusConfig();
		$airplusApi = new Ws_Airplus_Api($config);
		
		$airplusParams = $airline->airplusparams;
		
		# force DS
		$airplusParams['dbi_ds'] = 'SFS';
		
		// date must be uppercase
		$startdate = strtoupper($startdate);
		
		$replacement = array(
				'|airportcode|' 			=> $airline->airport_code,
				'|blockcode|'				=> $blockcode,
				'|startdate|'				=> $startdate,
				'|originalflightnumber|' 	=> @$options['flightnumber'],
				'|userID|'					=> @$userId,
				'|userId|'					=> @$userId,
				'|PNR|'						=> @$options['pnr'],
				'|destinationIATA|'			=> @$options['destinationIATA']
		);
		
		foreach($airplusParams as $key => &$val) {
			$val = str_replace(array_keys($replacement), array_values($replacement), $val);
		}

		if(@$options['unique_id']) {
			$airplusParams['dbi_au'] = $options['unique_id'];
		} else {
			throw new Exception('Must have unique_id');
		}
		
		# build request
		$request = new Ws_Airplus_Request();
		$request->AE = $airplusParams['dbi_ae'];
		$request->AK = $airplusParams['dbi_ak'];
		$request->AU = $airplusParams['dbi_au'];
        if($airplusParams['dbi_bd']){
            $request->BD = $airplusParams['dbi_bd'];
        }else{
            $request->BD = date("dMy");
        }

		$request->DS = $airplusParams['dbi_ds'];
		$request->IK = $airplusParams['dbi_ik'];
		$request->KS = $airplusParams['dbi_ks'];
		$request->PK = $airplusParams['dbi_pk'];
		$request->PR = $airplusParams['dbi_pr'];
		$request->RZ = $airplusParams['dbi_rz'];

		$request->BookForUserId = $userId;
		$request->BookForUserName = 'SFS';

        if($options == "meal"){
            $request->ChargeFee 	= $airplusParams['meal_fee'];
            $request->NM 	    	= $airplusParams['meal_nm'];
            $request->FT 			= $airplusParams['meal_ft'];
        }elseif($options == "taxi"){
            $request->ChargeFee 	= $airplusParams['taxi_fee'];
            $request->NM 	    	= $airplusParams['taxi_nm'];
            $request->FT 			= $airplusParams['taxi_ft'];
        }

        $request->CurrencyCodes = array($airline->currency_numeric_code);
        
        self::logAirplus("Request with hotel: " . var_export($request, true));
		self::exportToCSV($request);
		
        $result = $airplusApi->request($request);
        $result->TypeOfService = $options['type'];
        if(@$options['enddate']) {
            $result->ValidFrom 		= SfsHelperDate::getDate($options['startdate'],'ymd',$airline->time_zone);
            $result->ValidThru 		= SfsHelperDate::getDate($options['enddate'],'ymd',$airline->time_zone);
        }

		return $result;
	}

	
	//lchung
	static function getHotelDistance( $hotel_id = 0, $taxi_fee = 0 )
	{
		$db    = JFactory::getDbo();
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$query   = $db->getQuery(true);
		$query->from('#__sfs_hotel AS b ');
		$query->select('e.distance, e.distance_unit');
		$query->innerJoin('#__sfs_hotel_airports AS e ON e.hotel_id=b.id AND e.airport_id='.$airport_current_id);
		$query->select('ia.km_rate AS km_rate_ws, ia.starting_tariff AS starting_tariff_ws');
		$query->leftJoin('#__sfs_iatacodes AS ia ON ia.id = e.airport_id');
		$query->where('b.id=' . $hotel_id );
		$db->setQuery($query);
		$row = $db->loadObject();
		if( $row )
		$vdistance = (floatval($row->km_rate_ws)*$row->distance+$row->starting_tariff_ws)*2*1.1 + floatval ( $taxi_fee );
		return ($vdistance > 0 ) ? number_format($vdistance, 2, ".",",") : 0;
	}
	//End lchung
	
	public static function exportToCSV( $request, $attachment = false)
	{
		$logDir = JPATH_SITE . DS . 'tmp' . DS . 'csv';
		if(!is_dir($logDir)) {
			mkdir($logDir, 0777, true);
		}
		$filename = $logDir  . DS . date('Y-m-d-His') . '-test_CSV_v2.csv';
		#if($attachment) {
		#	// send response headers to the browser
		#	header( 'Content-Type: text/csv' );
		#	header( 'Content-Disposition: attachment;filename='.$filename);
		#	$fp = fopen('php://output', 'w');
		#} else {
		#	$fp = fopen($filename, 'w');
		#}
   
		$headers = 'ACCOUNTNUMBER, AMOUNT, AIDANUMBER, CURR, DBIAE, DBIAK, DBIAU, DBIBD, DBIDS, DBIIK, DBIKS, DBIPK, DBIPR, DBIRZ, IATANUMBER, PASSENGER, PORTALUSERID, PURCHASEDATE, TRAVELDATE, SERVICEDESC, SUPPLIER, TICKETNUMBER, TRAVELAGENCY, TRANSACTION_TYPE, AMOUNT_CONV, AMOUNT_CURR, FE_AMOUNT, FE_CURR, START_DATE, END_DATE, DAYS_NIGHTS_COUNT, DEPT_CITY, DEST_CITY, PARTICIPANT_COUNT, CARRIER_CODE, SERVICE_CLASS, ROUTING';
		$headersA = explode(",", $headers);
		foreach($headersA as &$val) {
			$val = trim($val);
		}
		
		$data['account_number'] = '';
		$data['amount'] = '0';
		$data['aida_number'] = '';
		$data['curr'] = '';
		$data['dbi_ae'] = $request->AE;
		$data['dbi_ak'] = $request->AK;
		$data['dbi_au'] = $request->AU;
		$data['dbi_bd'] = $request->BD;
		$data['dbi_ds'] = $request->DS;
		$data['dbi_ik'] = $request->IK;
		$data['dbi_ks'] = $request->KS;
		$data['dbi_pk'] = $request->PK;
		$data['dbi_pr'] = $request->PR;
		$data['dbi_rz'] = $request->RZ;
		$data['iata_number'] = '';
		$data['passenger'] = '';
		$data['portal_user_id'] = '';
		$data['purchase_date'] = '';
		$data['travel_date'] = '';
		$data['service_desc'] = '';
		$data['supplier'] = '';
		$data['ticket_number'] = '';
		$data['travel_agency'] = '';
		$data['transaction_type'] = $request->TransType;
		$data['amount_conv'] = '';
		$data['amount_curr'] = '';
		$data['fe_amount'] = '';
		$data['fe_curr'] = '';
		$data['start_date'] = '';
		$data['end_date'] = '';
		$data['days_nights_count'] = '';
		$data['dept_city'] = '';
		$data['dest_city'] = '';
		$data['participant_count'] = '';
		$data['carrier_code'] = '';
		$data['service_class'] = '';
		$data['routing'] = '';
		
		$datavalue = array_values( $data );
		#fputcsv($fp, $datavalue );
		#fclose($fp);
		$content = implode(",", $datavalue );
		$file = $filename . '.log';		
		file_put_contents($file, implode(',', $headersA) . "\n", FILE_APPEND);
		file_put_contents($file, $content . "\n", FILE_APPEND);
	}
}

