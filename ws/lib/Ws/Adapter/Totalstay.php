<?php

class Ws_Adapter_Totalstay extends Ws_Abstract
{
	protected $_soap = null;
	protected $_staticDataDir = '';
	protected $_logDir = '';
	protected $_url;
	protected $_username;
	protected $_password;

	public function __construct($config) {

		if(empty($config['url'])) {
			throw new Exception('url must be set');
		}

		if(!empty($config['static_data_dir'])) {
			$this->_staticDataDir = $config['static_data_dir'];
		} else {
			$this->_staticDataDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data';
		}

		if(!empty($config['log_dir'])) {
			$this->_logDir = $config['log_dir'];
		}

		$this->_username = $config['username'];
		$this->_password = $config['password'];
		$this->_url = $config['url'];

		parent::__construct($config);
	}

	# interface required
	public function getAirports($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('Airport'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_Airport();
			$obj->Name = $row['Airport'];
			$obj->UniqueID = $row['IATACode'];
			$obj->PrimaryID = $row['AirportID'];
			$obj->Terminal = $row['AirportTerminal'];
			$obj->TerminalID = $row['AirportTerminalID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}

	public function getBookingSources($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('BookingSource'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_BookingSource();
			$obj->Name = $row['BookingSource'];
			$obj->UniqueID = $row['BookingSource'];
			$obj->PrimaryID = $row['BookingSourceID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}

	public function getCardTypes($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('CardType'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_CardType();
			$obj->Name = $row['BookingSource'];
			$obj->UniqueID = $row['BookingSource'];
			$obj->PrimaryID = $row['BookingSourceID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getCurrencies($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('Currency'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_Currency();
			$obj->Name = $row['Currency'];
			$obj->UniqueID = $row['CurrencyID'];
			$obj->PrimaryID = $row['CurrencyID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getExtraTypes($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('ExtraType'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_ExtraType();
			$obj->Name = $row['ExtraType'];
			$obj->UniqueID = $row['ExtraTypeID'];
			$obj->PrimaryID = $row['ExtraTypeID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getFacilities($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('Facilities'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_Facility();
			$obj->Name = $row['Facility'];
			$obj->GroupID = $row['FacilityGroupID'];
			$obj->GroupName = $row['FacilityGroup'];
			$obj->Type = $row['FacilityType'];
			$obj->UniqueID = $row['FacilityID'];
			$obj->PrimaryID = $row['FacilityID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getProperties($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('Properties'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = $this->_buildPropertyDoFromRow($row);
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
    public function getNewProperties(){
        $list = new Ws_Csv_Collection($this->_getStaticFilePath('NewProperties'), '|');
        $page = 0;
        $limit = 5000;
        $rows = $list->fetchArrayMapHeader(2);
        unset($rows[0]['GeographyLevel3ID'], $rows[0]['GeographyLevel2ID'],  $rows[0]['County'], $rows[0]['Country1'], $rows[0]['Image3URL'],$rows[0]['Description']);

        # write header to new file
        $destDir = JPATH_ROOT . '/ws/lib/Ws/Adapter/data/OptimizeProperties/';
        $destFile = $destDir . date('Y-m-d-H-i-s') . '-OptimizeProperties.txt';
        if(!is_dir($destDir)) {
            mkdir($destDir, 0777, true);
        }
        $f = fopen($destFile, 'w');
        fputcsv($f, array_keys($rows[0]), '|');
        $cacheKey = 'airport-location';
        $cacheDir = JPATH_BASE . '/cache/com_sfs/ws-airport-location/';
        $cacheFile = $cacheDir . $cacheKey;
        if(file_exists($cacheFile)){
            $airportLocations = unserialize(file_get_contents($cacheFile));
        }else{
            $airportLocations = array();
        }
        while(true) {

            $rows = $list->fetchArrayMapHeader($limit, $limit * $page);
            if(empty($rows)) {
                break;
            }
            $page++;

            foreach($rows as $row) {
                if(array_filter($row)){
                    $tmp = array();
                    $tmp['AirportCode'] = $row['Airport'];
                    $tmp['Region'] = $row['Region'];
                    if(in_array($tmp, $airportLocations))
                    {
                        unset($row['GeographyLevel3ID'], $row['GeographyLevel2ID'], $row['County'], $row['Country1'], $row['Image3URL'], $row['Description']);
                        fputcsv($f, array_values($row), '|');
                    }
                }
            }
        }
        fclose($f);
    }
	public function getPropertiesNearByAirportIATACode($codes, $limit = 30, $offset = 0, $options = array()){
		if(!is_array($codes)) {
			$codes = array($codes);
		}
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('Properties'), '|');
		$page = 0;
		$limit = 5000;
		$buildRows = array();
		while(true) {
			$rows = $list->fetchArrayMapHeader($limit, $limit * $page, $options);
			if(empty($rows)) {
				break;
			}
			$page++;

			foreach($rows as $row) {
				$obj = $this->_buildPropertyDoFromRow($row);
				if(in_array($obj->Airport, $codes)) {
					$buildRows[] = $obj;
				}
			}
		}

		$list = null;

		return $buildRows;
	}
	public function getPropertyByPreferenceID($propertyReferenceID)
	{
		$loginDetails = $this->_getLoginDetails();
		$request = array(
			'PropertyDetailsRequest' => array(
				'LoginDetails' => $loginDetails,
				'PropertyID' => 0,
				'PropertyReferenceID' => $propertyReferenceID
			),
		);
		$xmlString = $this->_buildWsXml($request);
		$json = $this->_execWsCall($xmlString);
		return $json;
	}
	public function getLocations($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('Location'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_Location();
			$obj->CountryName = $row['Country'];
			$obj->RegionID = $row['RegionID'];
			$obj->RegionName = $row['Region'];
			$obj->ResortID = $row['ResortID'];
			$obj->ResortName = $row['Resort'];
			$obj->UniqueID = sha1(var_export($row, true));
			$obj->PrimaryID = $obj->UniqueID;
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getMealBasis($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('MealBasis'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_MealBasis();
			$obj->Name = $row['MealBasis'];
			$obj->UniqueID = $row['MealBasisID'];
			$obj->PrimaryID = $row['MealBasisID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getProductAttributes($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('ProductAttribute'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_ProductAttribute();
			$obj->Name = $row['ProductAttribute'];
			$obj->UniqueID = $row['ProductAttributeID'];
			$obj->PrimaryID = $row['ProductAttributeID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getRoomTypes($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('RoomType'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_RoomType();
			$obj->Name = $row['RoomType'];
			$obj->UniqueID = $row['PropertyRoomTypeID'];
			$obj->PrimaryID = $row['PropertyRoomTypeID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getStarRatings($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('StarRating'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_StarRating();
			$obj->Code = $row['Code'];
			$obj->Description = $row['Description'];
			$obj->UniqueID = $row['Code'];
			$obj->PrimaryID = $row['Code'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	public function getBlackLists($limit = 30, $offset = 0, $options = array()){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('Blacklist'), '|');
		$rows = $list->fetchArrayMapHeader($limit, $offset, $options);
		$buildRows = array();
		foreach($rows as $row) {
			$obj = new Ws_Do_Base_Blacklist();
			$obj->UniqueID = $row['PropertyReferenceID'];
			$obj->PrimaryID = $row['PropertyReferenceID'];
			$obj->PropertyID = $row['PropertyID'];
			$obj->PropertyReferenceID = $row['PropertyReferenceID'];
			$buildRows[] = $obj;
		}
		return $buildRows;
	}
	/**
	 * (non-PHPdoc)
	 * @see ws/lib/Ws/Ws_Interface::searchHotels()
	 */

    public function searchHotels(Ws_Do_Search_Request $request, $limit = 30, $offset = 0)
    {
        $hotels = array();
        $locs = $this->_getAllAirportLocationByAirportCode($request->AirportIATACode);
        foreach($locs as $loc)
        {
            $moreHotels = $this->_searchHotels($request, $loc);
            if($moreHotels)
            {
                $hotels = array_merge($hotels, $moreHotels);
            }
        }

        return $hotels;
    }

	public function _searchHotels(Ws_Do_Search_Request $request, $loc)
	{


		#log
		$this->log('searchHotels ====================================');

		$loginDetails = $this->_getLoginDetails();

		$rooms = array();
		/** @var $roomRequest Ws_Do_Search_RoomRequest */
		/** @var $obj Ws_Do_Search_RequestChildAge */

		foreach ($request->Rooms as $roomRequest) {
			$r = array(
				'Adults' => $roomRequest->NumAdults,
				'Children' => $roomRequest->NumChildren,
				'Infants' => $roomRequest->NumInfants,
			);


			if ($roomRequest->NumChildren) {
				$r['ChildAges'] = array();
				foreach ($roomRequest->ChildAges as $obj) {
					$r['ChildAges'][] = array(
						'ChildAge' => array(
							'Age' => $obj->Age
						)
					);
				}
			}

			$rooms[] = array(
				'RoomRequest' => $r
			);
		}
//		var_dump($r);exit;




		//$loc = $this->_getAirportLocationByAirportCode($request->AirportIATACode);
		$locRequest = array();

		$this->log('airport ' . $request->AirportIATACode);
		$this->log('location ' . json_encode($loc));

		if(empty($loc)) {
			$locRequest['AirportCode'] = $request->AirportIATACode;
		} else {
			$locRequest['RegionID'] = $loc['RegionID'];
			if(!empty($locRequest['ResortID'])) {
				$locRequest['ResortID'] = $loc['ResortID'];
			}
		}


		$req = array(
			'SearchRequest' => array(
				'LoginDetails' => $loginDetails,
				'SearchDetails' => array_merge(
					array(
						'ArrivalDate' => $request->ArrivalDate,
						'Duration' => $request->Duration,
						'RoomRequests' => $rooms
					),
					$locRequest
				)
			)
		);

		/*$req = array(
			'MultiBookingSearchRequest' => array(
				'LoginDetails' => $loginDetails,
				'AllComponents' => true,
				'ArrivalStartDate' => $request->ArrivalDate,
				'ArrivalEndDate' => $request->ArrivalDate
			)
		);*/

		$xmlString = $this->_buildWsXml($req);

		#log
		$this->log($xmlString);

		$json = $this->_execWsCall($xmlString);

		if(!$json['ReturnStatus']['Success'] || $json['ReturnStatus']['Success'] == 'false') {
			#log
			$this->log(json_encode($json));
            return false;
			throw new Exception($json['ReturnStatus']['Exception']);
		} else {
			$hotels = array();
			if(@($json['PropertyResults']['PropertyResult']['PropertyID'])) {
				$propResults = array($json['PropertyResults']['PropertyResult']);
			} else {
				$propResults = $json['PropertyResults']['PropertyResult'];
			}
			if($propResults) {
				foreach($propResults as $propResult) {
					$r = new Ws_Do_Search_Result();
					$r->RoomTypes = array();
					$r->PropertyID = $propResult['PropertyID'];
					$r->Name = $propResult['PropertyName'];
					$r->Rating = $propResult['Rating'];
					$r->Region = $propResult['Region'];
					$r->Resort = $propResult['Resort'];
					$r->PrimaryID = $propResult['PropertyReferenceID'];
					$r->UniqueID = $propResult['PropertyReferenceID'];

					# wont count bad $r
					if(empty($r->PrimaryID)) {
						continue;
					}

					#print_r($propResult);die();
					if(!isset($propResult['RoomTypes']['RoomType'][0])) {
						$roomTypes = array($propResult['RoomTypes']['RoomType']);
					} else {
						$roomTypes = $propResult['RoomTypes']['RoomType'];
					}
					foreach($roomTypes as $roomType) {
						$rt = new Ws_Do_Search_RoomTypeResult();
						$rt->PropertyID = $r->PropertyID;
						$rt->PropertyReferenceID = $r->PrimaryID;
						$rt->PropertyRoomTypeID = $roomType['PropertyRoomTypeID'];
						$rt->ArrivalDate = $request->ArrivalDate;
						$rt->NumberOfRooms = $request->NumberOfRooms;
						$rt->Duration = $request->Duration;
						$rt->TotalRoomAvailable = $request->NumberOfRooms;

						$rt->NumAdultsPerRoom = $roomType['Adults'];
						$rt->NumChildrenPerRoom = $roomType['Children'];
						$rt->NumInfantsPerRoom = $roomType['Infants'];
						$rt->ChildAges = $request->Rooms[$roomType['Seq']]->ChildAges;

//                        if(isset($roomType['Errata']['Erratum']['Subject'])) {
//                            $erratums = array($roomType['Errata']['Erratum']);
//                        } else {
//                            $erratums = $roomType['Errata']['Erratum'];
//                        }

                        if(is_array($roomType['Errata'])) {
                            $erratums = array($roomType['Errata']['Erratum']);
                        } else {
                            $erratums = array();
                        }

                        $rt->Errata = array();
                        foreach($erratums as $erratum)
                        {
                            /** @var $errat Ws_Do_Search_ResponseErratum */

                            $errat = new Ws_Do_Search_ResponseErratum();
                            $errat->Subject = $erratum['Subject'];
                            $errat->Description = $erratum['Description'];
                            $rt->Errata[] = $errat;
                        }

						$rt->Name = $roomType['RoomType'];
						$rt->BookingToken = $roomType['BookingToken'];
						$rt->MealBasisName = $roomType['MealBasis'];
						$rt->MealBasisID = $roomType['MealBasisID'];
                        $rt->SpecialOfferApplied = $roomType['SpecialOfferApplied'];
						$rt->SubTotal = $roomType['SubTotal'];
						$rt->Total = $roomType['Total'];
						$r->RoomTypes[] = $rt;
					}
					$hotels[] = $r;
				}
			}
  #          print_r($hotels);exit;
			return $hotels;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see ws/lib/Ws/Ws_Interface::preBook()
	 */
	public function preBook(Ws_Do_PreBook_Request $request){
		#log
		$this->log('preBook ====================================');

		$loginDetails = $this->_getLoginDetails();


		$rooms = array();
		/* @var $rb Ws_Do_PreBook_RoomRequest */
		foreach($request->RoomBookings as $rb) {
			$r = array(
				'MealBasisID' 	 => $rb->MealBasisID,
				'MealBasis'		 => $rb->MealBasisName,
				'Adults' 		 => $rb->NumAdults,
				'Children'		 => $rb->NumChildren,
				'Infants' 		 => $rb->NumInfants
			);

			if($rb->PropertyRoomTypeID) {
				$r['PropertyRoomTypeID'] = $rb->PropertyRoomTypeID;
			} else {
				$r['BookingToken'] = $rb->BookingToken;
			}

			if($rb->NumChildren) {
				$r['ChildAges'] = array();
				foreach($rb->ChildAges as $obj) {
					$r['ChildAges'][] = array(
						'ChildAge' => array(
							'Age' => $obj->Age
						)
					);
				}
			}

			$rooms[] = array(
				'RoomBooking' => $r
			);
		}

		$req = array(
			'PreBookRequest' => array(
				'LoginDetails' => $loginDetails,
				'BookingDetails' => array(
					'PropertyID' => $request->PropertyID,
					'ArrivalDate' => $request->ArrivalDate,
					'Duration' => $request->Duration,
					'RoomBookings' => $rooms
				)
			)
		);

		$xmlString = $this->_buildWsXml($req);

		#log
		$this->log($xmlString);

		$json = $this->_execWsCall($xmlString);

		#log
		$this->log(json_encode($json));

		if(!$json['ReturnStatus']['Success'] || $json['ReturnStatus']['Success'] == 'false') {
			throw new Exception($json['ReturnStatus']['Exception']);
		} else {

			$res = new Ws_Do_PreBook_Response();
			$res->PreBookingToken = $json['PreBookingToken'];
			$res->TotalPrice = $json['TotalPrice'];
			$res->TotalCommission = $json['TotalCommission'];
			$res->VATOnCommission = $json['VATOnCommission'];
			$res->Cancellations = array();
			$res->IssueTime = gmdate('Y-m-d H:i:s');

			if(isset($json['Cancellations']['Cancellation']['StartDate'])) {
				$cancels = array($json['Cancellations']['Cancellation']);
			} else {
				$cancels = @$json['Cancellations']['Cancellation'];
			}

			if($cancels) {
				foreach($cancels as $c) {
					$rc = new Ws_Do_PreBook_CancellationResponse();
					$rc->EndDate = $c['EndDate'];
					$rc->StartDate = $c['StartDate'];
					$rc->Penalty = $c['Penalty'];

					$res->Cancellations[] = $rc;
				}
			}

			return $res;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see ws/lib/Ws/Ws_Interface::book()
	 */
	public function book(Ws_Do_Book_Request $request){

		#log
		$this->log('book ====================================');

		$loginDetails = $this->_getLoginDetails();
		$rooms = array();

		/* @var $rbc Ws_Do_Book_RoomRequest */
		/* @var $gs Ws_Do_Book_RoomGuestRequest */
		foreach($request->RoomBookings as $rbc) {
			$guests = array();
			foreach($rbc->Guests as $gs) {
				$guestXml = array(
					'Type' => $gs->Type,
					'Title' => $gs->Title,
					'FirstName' => $gs->FirstName,
					'LastName' => $gs->LastName,

				);
				if($gs->Age) {
					$guestXml['Age'] = $gs->Age;
				}
				$guests[] = array(
					'Guest' => $guestXml
				);
			}

			$r = array(
				'MealBasisID' => $rbc->MealBasisID,
				'MealBasis' => $rbc->MealBasisName,
				'Adults' => $rbc->NumAdults,
				'Children' => $rbc->NumChildren,
				'Infants' => $rbc->NumInfants,
				'Guests' => $guests
			);

            if($rbc->PropertyRoomTypeID) {
                $r['PropertyRoomTypeID'] = $rbc->PropertyRoomTypeID;
            }
            else
            {
                $r['BookingToken'] = $rbc->BookingToken;
            }


			$rooms[] = array(
				'RoomBooking' => $r
			);
		}
		$req = array(
			'BookRequest' => array(
				'LoginDetails' => $loginDetails,
				'BookingDetails' => array(
					'PreBookingToken' => $request->PreBookingToken,
					'PropertyID' => $request->PropertyID,
					'ArrivalDate' => $request->ArrivalDate,
					'Duration' => $request->Duration,
					'LeadGuestTitle' => $request->LeadGuestTitle,
					'LeadGuestFirstName' => $request->LeadGuestFirstName,
					'LeadGuestLastName' => $request->LeadGuestLastName,
					'TradeReference' => $request->TradeReference,
					'RoomBookings' => $rooms
				)
			)
		);

		$xmlString = $this->_buildWsXml($req);

		#log
		$this->log($xmlString);

		$json = $this->_execWsCall($xmlString);

		#log
		$this->log(json_encode($json));

		#echo '<pre>';
		#echo htmlspecialchars($xmlString);die();
		#print_r($json);die();
		if(!$json['ReturnStatus']['Success'] || $json['ReturnStatus']['Success'] == 'false') {
			throw new Exception($json['ReturnStatus']['Exception']);
		} else {

			$res = new Ws_Do_Book_Response();
			$res->BookingReference = $json['BookingReference'];
			$res->TotalPrice = $json['TotalPrice'];
			$res->TotalCommission = $json['TotalCommission'];
			$res->CustomerTotalPrice = $json['CustomerTotalPrice'];
			$res->PropertyBookings = array();

			if(isset($json['PropertyBookings']['PropertyBooking']['PropertyBookingReference'])) {
				$props = array($json['PropertyBookings']['PropertyBooking']);
			} else {
				$props = @$json['PropertyBooking']['PropertyBooking'];
			}

			if($props) {
				foreach($props as $p) {
					$rc = new Ws_Do_Book_PropertyBookingResponse();
					$rc->Supplier = $p['Supplier'];
					$rc->SupplierReference = $p['SupplierReference'];
					$rc->Penalty = $p['Penalty'];
					$rc->PropertyBookingReference = $p['PropertyBookingReference'];
					$res->PropertyBookings[] = $rc;
				}
			}

			return $res;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see ws/lib/Ws/Ws_Interface::cancel()
	 */
	public function cancel($request){
		$loginDetails = $this->_getLoginDetails();
		$req = array(

		);
	}

	/**
	 * (non-PHPdoc)
	 * @see ws/lib/Ws/Ws_Interface::getPropertyDetail()
	 */
	public function getPropertyDetail($propertyID, $propertyReferenceID) {
		#log
		$this->log('getPropertyDetail ====================================');

		$loginDetails = $this->_getLoginDetails();
		$req = array(
			'PropertyDetailsRequest' => array(
				'LoginDetails' => $loginDetails,
			)
		);

		if($propertyID) {
			$req['PropertyDetailsRequest']['PropertyID'] = $propertyID;
		}
		if($propertyReferenceID) {
			$req['PropertyDetailsRequest']['PropertyReferenceID'] = $propertyReferenceID;
		}

		$xmlString = $this->_buildWsXml($req);

		#log
		$this->log($xmlString);

		$json = $this->_execWsCall($xmlString);

		if(!$json['ReturnStatus']['Success'] || $json['ReturnStatus']['Success'] == 'false') {
			throw new Exception($json['ReturnStatus']['Exception']);
		} else {
			$obj = new Ws_Do_PropertyDetail_Response();
			foreach($json as $key => $value) {
				$obj->{$key} = $value;
			}

			return $obj;
		}
	}

	# interface required

	private function _getStaticFilePath($name) {
		return $this->_staticDataDir . DIRECTORY_SEPARATOR . $name . '.txt';
	}

	private function _buildPropertyDoFromRow($row) {
		$obj = new Ws_Do_Base_Property();
		$obj->Name = $row['PropertyName'];
		$obj->Airport = $row['Airport'];
		#$obj->Description = $row['Description'];
		$obj->Facilities = $row['Facilities'];
		$obj->PropertyGroup = $row['PropertyGroup'];
		$obj->PropertyType = $row['PropertyType'];
		$obj->Rating = $row['Rating'];
		$obj->Telephone = $row['Telephone'];
		$obj->Fax = $row['Fax'];
		$obj->Address1 = $row['Address1'];
		$obj->Address2 = $row['Address2'];
		$obj->Country = $row['Country'];
		$obj->Image1URL = $row['Image1URL'];
		$obj->Image2URL = $row['Image2URL'];
		$obj->Latitude = $row['Latitude'];
		$obj->Longitude = $row['Longitude'];
		$obj->PostcodeZip = $row['PostcodeZip'];
		$obj->ProductAttributes = $row['ProductAttributes'];
		$obj->PropertyGroup = $row['PropertyGroup'];
		$obj->PropertyType = $row['PropertyType'];
		$obj->Region = $row['Region'];
		$obj->Resort = $row['Resort'];
		$obj->ThumbnailURL = $row['ThumbnailURL'];
		$obj->TownCity = $row['TownCity'];

		$obj->UniqueID = $row['PropertyReferenceID'];
		$obj->PrimaryID = $row['PropertyReferenceID'];

		return $obj;
	}

	private function _buildWsXml($array) {
		foreach($array as $key => $value) {
			$xml = new SimpleXMLElement("<{$key}></{$key}>");
			$this->_arrayToXML($value, $xml);
			return $xml->saveXML();
		}
		return '';
	}

	public function _execWsCall($xmlString) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'Data' => $xmlString
		));
		curl_setopt($ch, CURLOPT_HEADER,  0);
		curl_setopt( $ch, CURLOPT_ENCODING,'gzip,deflate'  );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, Array (
		            "Content-Encoding: gzip",
					"Accept-encoding: gzip",
					"Keep-Alive: timeout=100",
					"Connection: Keep-Alive",
					"Proxy-Connection: Keep-Alive",
					"Expect: 100-continue"
		            ) );
		$xml = curl_exec($ch);
		curl_close($ch);
		return $this->_xmlToArray(new SimpleXMLElement($xml));
	}

	private function _xmlToArray($xml) {
		$array = array();
		$tmp = array();
	    foreach ($xml as $name => $element) {
	    	if(!isset($tmp[$name])) {
	    		$tmp[$name] = array();
	    	}
	    	$tmp[$name][] = $element;
	    }
	    foreach($tmp as $name => $elements) {
	    	if(count($elements) == 1) {
	    		$element = $elements[0];
	    		$array[$name] = $element->count() ? $this->_xmlToArray($element) : trim($element);
	    	} else {
	    		foreach($elements as $element) {
	    			$array[$name][] = $element->count() ? $this->_xmlToArray($element) : trim($element);
	    		}
	    	}
	    }
	    return $array;
	}

	private function _arrayToXML($array, $xml) {
	    foreach($array as $key => $value) {
	        if(is_array($value)) {
	            if(!is_numeric($key)){
	                $subnode = $xml->addChild("$key");
	                $this->_arrayToXML($value, $subnode);
	            }
	            else{
	                $this->_arrayToXML($value, $xml);
	            }
	        }
	        else {
	            $xml->addChild("$key",htmlspecialchars("$value"));
	        }
	    }
	}

	private function _getLoginDetails(){
		$l = array();
		$l['Login'] = $this->_username;
		$l['Password'] = $this->_password;
		$l['Locale'] = '';
		$l['AgentReference'] = '';
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_currency = $airline_current->currency_code;
        if(!$airport_currency)
        {
            $airport_currency = SfsHelper::getCurrency();
        }

        $currency = $this->getCurrencyIdByCurrencyCode($airport_currency);
        if($currency)
        {
            $l['CurrencyID'] = $currency['CurrencyID'];
        }
		return $l;
	}

	private function _getAirportLocationByAirportCode($airportCode){
		$list = new Ws_Csv_Collection($this->_getStaticFilePath('AirportLocation'), '|');
		$rows = $list->fetchArrayMapHeader(1500, 0);
		$this->log(json_encode($rows));
		foreach($rows as $row) {
			if($row['AirportCode'] == $airportCode) {
				return $row;
			}
		}
		return null;
	}

    public function getAllAirportLocation(){

        $list = new Ws_Csv_Collection($this->_getStaticFilePath('AirportLocation'), '|');
        $rows = $list->fetchArrayMapHeader(1500, 0);
        $result = array();
        if(count($rows)){
            foreach($rows as $row)
            {
                $row['Region'] = $this->getRegionCodeByRegionID($row['RegionID']);
                unset($row['ResortID']);
                unset($row['Priority']);
                unset($row['RegionID']);
                $result[] = $row;
            }
            return $result;
        }
        return null;
    }

    private function _getAllAirportLocationByAirportCode($airportCode)
    {
        $list = new Ws_Csv_Collection($this->_getStaticFilePath('AirportLocation'), '|');

        $priority = JRequest::getInt('priority') ? JRequest::getInt('priority') : 1;

        $rows = $list->fetchArrayMapHeader(1500, 0);
        $this->log(json_encode($rows));

        $result_all = array();

        foreach($rows as $row)
        {
            if($row['AirportCode'] == $airportCode && $row['Priority'] == $priority)
            {
                $result_all[] = $row;
            }
        }

        if(count($result_all) > 0)
        {
            return $result_all;
        }

        return null;
    }

    public function getCurrencyIdByCurrencyCode($currencyCode)
    {
        $list = new Ws_Csv_Collection($this->_getStaticFilePath('CurrencyNew'), '|');
        $rows = $list->fetchArrayMapHeader(1000, 0);
        $this->log(json_encode($rows));

        foreach($rows as $row) {
            if($row['CurrencyCode'] == $currencyCode) {
                return $row;
            }
        }
        return null;
    }

    public function getRegionCodeByRegionID($regionID)
    {
        $list = new Ws_Csv_Collection($this->_getStaticFilePath('Location'), '|');
        $rows = $list->fetchArrayMapHeader(7000, 0);
        $this->log(json_encode($rows));

        foreach($rows as $row) {
            if($row['RegionID'] == $regionID) {
                return $row['Region'];
            }
        }
        return null;
    }

    function getAirportNumberOfPriorities(){
        $airline = SFactory::getAirline();
        $airportCode = $airline->airport_code;

        $list = new Ws_Csv_Collection($this->_getStaticFilePath('AirportLocation'), '|');

        $rows = $list->fetchArrayMapHeader(1500, 0);

        $result_priority_limit = array();

        foreach($rows as $row)
        {
            // Get Priority Limit
            if($row['AirportCode'] == $airportCode)
            {
                $result_priority_limit[] = $row['Priority'];
            }
        }

        return max($result_priority_limit);
    }

	function log($message) {
		if(empty($this->_logDir)) {
			return;
		}
		$date = date('Y-m-d');
		$file = $this->_logDir . '/' . $date . '-ws-totalstay.log';
		file_put_contents($file, date('Y-m-d H:i:s') . ' ' . $message . "\n", FILE_APPEND);
	}
}