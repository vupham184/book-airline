<?php
// No direct access
defined('_JEXEC') or die;

class SfsModelSearch extends JModelLegacy
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	var $_allowSearch = true;

	function __construct()
	{
		parent::__construct();

		//Get configuration
		$app	= JFactory::getApplication();
		$config = JFactory::getConfig();

		// Get the pagination request variables
		$this->setState('limit', $app->getUserStateFromRequest('com_sfs.limit', 'limit', $config->get('list_limit'), 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));

		// Set the search parameters										
		$this->setSearch();

	}

	function setSearch()
	{
		$this->setState('filter.allow_search', 1);

		$rooms  = JRequest::getInt('rooms');
		$session = JFactory::getSession();
		$roomRequest = json_decode($session->get("rooms_search"), true);

        $airline_current = SAirline::getInstance()->getCurrentAirport();
//        $time = new \DateTime('now', new DateTimeZone($airline_current->time_zone));
//        $timezoneOffset = $time->format('P');

		$date_start = JRequest::getVar('date_start');
		#!@important don't translate timezone this point because it's translated before
		#$date_start = SfsHelperDate::getDate($date_start, "Y-m-d", $airline_current->time_zone);
		$end_date = JRequest::getVar('date_end');
		#!@important don't translate timezone this point because it's translated before
		#$end_date =  SfsHelperDate::getDate($end_date, "Y-m-d", $airline_current->time_zone);

        if(!$date_start) {
            $date_start = SfsHelperDate::getDate('now', "Y-m-d", $airline_current->time_zone);
			$end_date = SfsHelperDate::getNextDate(JText::_('DATE_FORMAT_LC4'), $date_start);
		}

		$transport_included = JRequest::getVar('transport_included');

		if($transport_included) {
			$this->setState('filter.transport_included', (int)$transport_included);
		}

		$show_all = JRequest::getVar('show_all_rooms');

		if($show_all) {
			$this->setState('filter.show_all_rooms', (int)$show_all);
		}

		$offer_meal_plans = JRequest::getVar('offer_meal_plans');

		if($offer_meal_plans) {
			$this->setState('filter.offer_meal_plans', (int)$offer_meal_plans);
		}

		$filter_hotel_star = (int) JRequest::getVar('hotel_star');


		if( $filter_hotel_star > 0 ) {
			$this->setState('filter.hotel_star', $filter_hotel_star);
		}
		if( $filter_hotel_star > 0 ) {
			$this->setState('filter.hotel_star', $filter_hotel_star);
		}


		$this->setState('filter.rooms', (int)$rooms);
		$this->setState('filter.date_start', $date_start);
		$this->setState('filter.date_end', $end_date);

		$wsOnly = JRequest::getInt('ws_only', 0);
		$wsCacheOnly = true;
		$ws = true;
		if($wsOnly) {
			$wsCacheOnly = false;
		}
		$this->setState('filter.ws_only', $wsOnly);
		$this->setState('filter.ws_cacheonly', $wsCacheOnly);

		// WS search impact
		if($ws)
 		{
			$airline = SFactory::getAirline();
			//print_r( $airline );die;
			//Check when airport_status == 0 And airline_ws_status = 0 is not search hotel WS 
			if ( $airline->airport_status == 0 || isset( $airline->params['airline_ws_status'] ) && $airline->params['airline_ws_status'] == 0 ) {
				$this->setState('filter.ws_hotels_map', array() );
				$this->setState('filter.ws_id_list', array() );
				return;
			}
			$wsHotels = SfsWs::searchHotels($airline->airport_code, $date_start, $rooms, $roomRequest , 1, $wsCacheOnly);

			$wsHotelsMap = array();
			$wsIDs = array();

			/* @var $obj Ws_Do_Search_Result */
			if($wsHotels) {
				foreach($wsHotels as $obj) {
					$wsIDs[] = $obj->PrimaryID;
					$wsHotelsMap[$obj->PrimaryID] = $obj;
				}
			}

			$this->setState('filter.ws_hotels_map', $wsHotelsMap);
			$this->setState('filter.ws_id_list', $wsIDs);
		}
	}

	//get search data
	public function getData()
	{
		if( ! $this->_allowSearch){
			return false;
		}
		$airline = SFactory::getAirline();

		// Lets load the data if it doesn't already exist
		if (empty($this->_data)) {
			$this->_data = array();

			$db = $this->getDbo();
			$query = $this->getListQuery($db);
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}


			if (count($rows)) {				
				$contractedRates = $this->getContractedRates();

				if (count($contractedRates)) {
					$wsHotelsMap = $this->getState('filter.ws_hotels_map');

					foreach ($rows as & $row) {

						$rate = $contractedRates[$row->hotel_id];
						$maxrategeo = json_decode($rate->max_rate);
						
						$queryCur = "SELECT exchange_rate FROM #__sfs_currency WHERE id=" . $row->currency_id;
						$db->setQuery($queryCur);
						$curr = $db->loadObject();
						if((int)$rate->s_rate > 0 || (int)$rate->sd_rate > 0 || (int)$rate->t_rate > 0 || (int)$rate->q_rate > 0){
							// s_room
							if(floatval($row->s_room_rate) < floatval($rate->s_rate) && $maxrategeo->srate == "false"){
								$row->convert_s_room_rate 	= round($rate->s_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->s_room_rate) < floatval($rate->s_rate) && $maxrategeo->srate == "true"){
								$row->convert_s_room_rate 	= round($row->s_room_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->s_room_rate) > floatval($rate->s_rate)){
								$row->convert_s_room_rate 	= round($rate->s_rate/floatval($curr->exchange_rate), 2);
							}

							// sd_room
							if(floatval($row->sd_room_rate) < floatval($rate->sd_rate) && $maxrategeo->sdrate == "false"){
								$row->convert_sd_room_rate 	= round($rate->sd_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->sd_room_rate) < floatval($rate->sd_rate) && $maxrategeo->srate == "true"){
								$row->convert_sd_room_rate 	= round($row->sd_room_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->sd_room_rate) > floatval($rate->sd_rate)){
								$row->convert_sd_room_rate 	= round($rate->sd_rate/floatval($curr->exchange_rate), 2);
							}

							// t_room
							if(floatval($row->t_room_rate) < floatval($rate->t_rate) && $maxrategeo->trate == "false"){
								$row->convert_t_room_rate 	= round($rate->t_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->t_room_rate) < floatval($rate->t_rate) && $maxrategeo->trate == "true"){
								$row->convert_t_room_rate 	= round($row->t_room_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->t_room_rate) > floatval($rate->t_rate)){
								$row->convert_t_room_rate 	= round($rate->t_rate/floatval($curr->exchange_rate), 2);
							}

							// q_room
							if(floatval($row->q_room_rate) < floatval($rate->q_rate) && $maxrategeo->qrate == "false"){
								$row->convert_q_room_rate 	= round($rate->q_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->q_room_rate) < floatval($rate->q_rate) && $maxrategeo->qrate == "true"){
								$row->convert_q_room_rate 	= round($row->q_room_rate/floatval($curr->exchange_rate), 2);
							}elseif(floatval($row->q_room_rate) > floatval($rate->q_rate)){
								$row->convert_q_room_rate 	= round($rate->q_rate/floatval($curr->exchange_rate), 2);
							}
						}else{
							$row->convert_s_room_rate 	= round($row->s_room_rate/floatval($curr->exchange_rate), 2);
							$row->convert_sd_room_rate 	= round($row->sd_room_rate/floatval($curr->exchange_rate),2);
							$row->convert_t_room_rate 	= round($row->t_room_rate/floatval($curr->exchange_rate), 2);
							$row->convert_q_room_rate 	= round($row->q_room_rate/floatval($curr->exchange_rate), 2);
						}

						if (empty($row->distance) && !empty($row->geo_location_latitude) && !empty($row->geo_location_longitude)) {
							$distance = SfsUtil::distance($row->geo_location_latitude, $row->geo_location_longitude, $airline->geo_lat, $airline->geo_lon);
							$row->distance = $distance;
							$this->updateDistance($row->hotel_id, $distance);
						}
						if ($contractedRates[$row->hotel_id] && empty($contractedRates[$row->hotel_id]->ex_hotel_id)) {
							$row->isContractedRate = true;
							$row->contracted_sd_rate = floatval($contractedRates[$row->hotel_id]->sd_rate);
							$row->contracted_t_rate = floatval($contractedRates[$row->hotel_id]->t_rate);
							$row->contracted_s_rate = floatval($contractedRates[$row->hotel_id]->s_rate);
							$row->contracted_q_rate = floatval($contractedRates[$row->hotel_id]->q_rate);
							$row->contracted_max_rate = json_decode($contractedRates[$row->hotel_id]->max_rate);
							



							//lchung
							$row->contracted_breakfast = floatval($contractedRates[$row->hotel_id]->breakfast);
							$row->contracted_lunch = floatval($contractedRates[$row->hotel_id]->lunch);
							$row->contracted_dinner = floatval($contractedRates[$row->hotel_id]->dinner);
							//End lchung
						}

						if ($row->ws_id && $wsHotelsMap[$row->ws_id]) {
							$row->wsData = $wsHotelsMap[$row->ws_id];
                            include JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'ws' . DIRECTORY_SEPARATOR . 'config.php';
                            if(strpos($config['params']['url'], 'totalstaytestxml')){
                                $row->name  = "Test " .  $row->name;
                            }
						}

						if (empty($row->currency_symbol)) {
                            $airline_current = SAirline::getInstance()->getCurrentAirport();
                            $airport_currency = $airline_current->currency_code;
                            if(!$airport_currency)
                            {
                                $airport_currency = SfsHelper::getCurrency();
                            }
                            $currency = SfsWs::getCurrencyIdByCurrencyCode($airport_currency);

							//$row->currency_id = $currency['CurrencyID'];
							$row->currency_symbol = $currency['CurrencySymbol'];
						}
					}
				}
				$this->_data = $rows;
			}

			// load the hotels in other database
			$extend = JRequest::getInt('extend', 0);
			if ($extend == 1) {
				$this->loadAirportAssociations();
			}
		}
		
		return $this->_data;
	}

	protected function getListQuery( $db, $type = 'local' )
	{
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
        $airport_current_code = $airline_current->code;

		$query   = $db->getQuery(true);

		$wsIDs = $this->getState('filter.ws_id_list');
		$wsOnly = $this->getState('filter.ws_only');
		//join to rooms
		$query->select('b.id as hotel_id,b.name, b.ws_id, b.star,b.address, b.web_address,b.billing_id,b.geo_location_latitude,b.geo_location_longitude,b.currency_id,t.percent_release_policy');
		$query->from('#__sfs_hotel AS b ');


		$query->select('a.id, a.sd_room_total, a.t_room_total,a.s_room_total, a.q_room_total, a.transport_included, a.date');
		$query->select('a.sd_room_rate_modified AS sd_room_rate, a.t_room_rate_modified AS t_room_rate');
		$query->select('a.s_room_rate_modified AS s_room_rate, a.q_room_rate_modified AS q_room_rate');



		$date_start = $this->getState('filter.date_start');
		$end_date   = $this->getState('filter.date_end');

		$on = array(
			' a.hotel_id=b.id '
		);
		if( $date_start ) {
			$on[] = ' a.date = '.$db->Quote($date_start) . ' ';
		}
		if( $end_date ) {
			$on[] = ' a.date < '.$db->Quote($end_date) . ' ';
		}

		$query->leftJoin('#__sfs_room_inventory AS a ON ' . implode('AND', $on));
		// OR (DATE(b.last_room_load_request_date) = CURDATE() AND NOW() >= DATE_ADD(b.last_room_load_request_date, INTERVAL 30 MINUTE))



		$query->leftJoin('#__sfs_hotel_taxes AS t ON t.hotel_id=b.id');

		//join to currency
		$query->select('c.symbol AS currency_symbol');
		$query->leftJoin('#__sfs_currency AS c ON c.id=t.currency_id');

		// join to sfs_hotel_mealplans
		$query->select('d.bf_standard_price,d.bf_layover_price,d.course_1, d.course_2, d.course_3,d.service_hour,d.service_opentime,d.service_closetime,d.stop_selling_time,d.bf_service_hour,d.bf_opentime,d.bf_closetime,d.available_days,d.lunch_available_days,d.status_break,d.status_lunch,d.status_dinner');
		$query->select('d.lunch_standard_price, d.lunch_service_hour,d.lunch_opentime,d.lunch_closetime');

		$query->leftJoin('#__sfs_hotel_mealplans AS d ON d.hotel_id=b.id');

		// join to transport
		$query->select('tp.transport_available, tp.transport_complementary, tp.operating_hour, tp.operating_opentime, tp.operating_closetime, tp.frequency_service, tp.pickup_details');
		$query->leftJoin('#__sfs_hotel_transports AS tp ON tp.hotel_id=b.id');

		$query->select('thr.km_rate, thr.starting_tariff');
		$query->leftJoin('#__sfs_taxi_hotel_rates AS thr ON thr.hotel_id=d.id');
		//$query->leftJoin('#__sfs_taxi_hotel_rates AS thr ON thr.hotel_id=0 AND thr.taxi_id=11 AND ring=1');

		$query->select('hbp.single_room_available, hbp.quad_room_available');
		$query->leftJoin('#__sfs_hotel_backend_params AS hbp ON hbp.hotel_id=b.id');

		$query->where('b.block=0');
		$query->where("(b.ws_id IS NULL
							AND (a.id IS NOT NULL
								OR (DATE(b.last_room_load_request_date) = CURDATE()
									AND NOW() >= DATE_ADD(b.last_room_load_request_date, INTERVAL 30 MINUTE)
								)
							)
						OR b.ws_id IS NOT NULL)");

        // Load ws vs partner hotel
        if (!JRequest::getInt('select_sort')) {
            if ($wsOnly) {
                $query->where('b.ws_id IS NOT NULL');
                if ($wsIDs) {
                    $wsIDList = implode(',', $wsIDs);
                    $query->where('b.ws_id IN (' . $wsIDList . ')');
                } else {
                    $query->where('FALSE');
                }
            } else {
                if ($wsIDs) {
                    $wsIDList = implode(',', $wsIDs);
                    $query->where('(b.ws_id IN (' . $wsIDList . ') OR b.ws_id IS NULL OR b.ws_id = \'\')');
                } else {
                    $query->where('(b.ws_id IS NULL OR b.ws_id = \'\')');
                }
            }
        }else{ // Load hotel partner vs ws when select sort
            if ($wsIDs) {
                $wsIDList = implode(',', $wsIDs);
                $query->where('(b.ws_id IN (' . $wsIDList . ') OR b.ws_id IS NULL OR b.ws_id = \'\')');
            }else{
                $query->where('(b.ws_id IS NULL OR b.ws_id = \'\')');
            }
        }


		if($type == 'local'){
			$query->select('e.distance, e.distance_unit');
			$query->innerJoin('#__sfs_hotel_airports AS e ON e.hotel_id=b.id AND e.airport_id='.$airport_current_id);
			$query->select('ia.km_rate AS km_rate_ws, ia.starting_tariff AS starting_tariff_ws');
			$query->leftJoin('#__sfs_iatacodes AS ia ON ia.id = e.airport_id');
			#$query->where('b.country_id='.$airline->country_id);
		} else {
			$query->select('e.distance, e.distance_unit');
			$query->leftJoin('#__sfs_hotel_airports AS e ON e.hotel_id=b.id');
			$query->innerJoin('#__sfs_iatacodes AS iata ON iata.id=e.airport_id AND iata.code='.$db->quote($airport_current_code));
		}

		if( $this->getState('filter.hotel_star') ) {
			$condition = JRequest::getInt('condition');
			if($condition) {
				$query->where('b.star ='.(int)$this->getState('filter.hotel_star'));
			} else {
				$query->where('b.star >='.(int)$this->getState('filter.hotel_star'));
			}
		}

		if( $this->getState('filter.transport_included') ) {
			$query->where('a.transport_included = 1');
		}

		if( $this->getState('filter.offer_meal_plans') ) {
			$query->where('INSTR(d.`lunch_available_days`,DAYOFWEEK(NOW())) >0 AND INSTR(d.`available_days`,DAYOFWEEK(NOW())) >0');
		}

		$ordering = (int)JRequest::getInt('ordering');
		switch ($ordering) {
			case 1:
				$query->order('b.star ASC,a.sd_room_rate_modified ASC,a.t_room_rate_modified ASC,a.s_room_rate_modified ASC');
				break;
			case 2:
				$query->order('a.sd_room_rate_modified ASC,a.t_room_rate_modified ASC,a.s_room_rate_modified ASC');//a.s_room_rate_modified
				break;
			case 3:
				if($type == 'local'){
					$query->order('e.distance ASC,a.sd_room_rate_modified ASC,a.t_room_rate_modified ASC,a.s_room_rate_modified ASC');
				}
				break;
			case 4:
				$query->order('a.transport_included DESC,a.sd_room_rate_modified ASC,a.t_room_rate_modified ASC,a.s_room_rate_modified ASC');
				break;
            case 5:
                if (count($_POST['total_sort']) > 0)
                {
                    $array_total = $_POST['total_sort'];
                    $db = JFactory::getDbo();
                    foreach($array_total as $key => $value) {
                        $rowhotel = new stdClass();
                        $rowhotel->id = $key;
                        $rowhotel->total = $value;
                        $db->updateObject('#__sfs_hotel', $rowhotel, 'id');
                    }
                }
                $query->order('b.total ASC');
                break;
			default:
				$query->order('a.sd_room_rate_modified ASC,a.t_room_rate_modified ASC,a.s_room_rate_modified ASC');//a.s_room_rate_modified ASC
				break;
		}

		//print_r($_POST);die($_POST);
		//echo $query;die();
		return $query;
	}

	public function getContractedRates()
	{
		$user	 = JFactory::getUser();
		$db 	 = $this->getDbo();
		$airline = SFactory::getAirline();

        $airlineList = array();

        array_push($airlineList, $airline->id);


        if ($airline->grouptype == 3) {
            $query = $db->getQuery(true);
            $query->select('a.id');
            $query->from('#__sfs_airline_details AS a');
            $query->where('a.iatacode_id=' . (int)$airline->iatacode_id);
            $db->setQuery($query);
            $result = $db->loadObject();
            array_push($airlineList, $result->id);
        }

        $airlineList = implode(",", $airlineList);

		$query =  $db->getQuery(true);

		$date_start = $this->getState('filter.date_start');

		$query->select('a.hotel_id,a.sd_rate,a.t_rate,a.s_rate,a.q_rate,a.breakfast,a.lunch,a.dinner,a.max_rate');
		$query->from('#__sfs_contractedrates AS a');

		$query->select('b.hotel_id AS ex_hotel_id');
		$query->leftJoin('#__sfs_contractedrates_exclusions AS b ON b.airline_id=a.airline_id AND b.hotel_id=a.hotel_id AND b.date=a.date');

		$query->where('a.airline_id IN ('.$airlineList.")");
		$query->where('a.date='. $db->quote($date_start));
        #echo $query;die();

		$db->setQuery($query);
		$rows = $db->loadObjectList('hotel_id');

		if( count($rows) )
		{
			return $rows;
		}

		return false;
	}

	protected function loadAirportAssociations()
	{
		$associations = SFactory::getAssociations();
		if( !$associations ) return;

		foreach ($associations as $association)
		{
			$query = $this->getListQuery($association->db,$association->code);
			$association->db->setQuery($query);
			$rows = $association->db->loadObjectList();

			if(count($rows))
			{
				foreach ($rows as $row)
				{
					$row->association_id = $association->id;
					$this->_data[] = $row;
				}
			}
		}
	}
	protected function updateDistance($hotel_id, $distance)
	{
		$db = $this->getDbo();
		$query   = $db->getQuery(true);
		$query->update("#__sfs_hotel_airports");
		$query->set("distance=".$distance);
		$query->set("distance_unit='km'");
		$query->where("hotel_id=".$hotel_id);
		$db->setQuery($query);
		$db->query();
	}


    public function getHotelsNoRoomLoading()
    {
        $db 	 = $this->getDbo();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
        $query =  $db->getQuery(true);

        $query->select("h.id, h.name, h.star, t.date");
        $query->from("#__sfs_hotel AS h");
        $query->innerJoin('#__sfs_hotel_airports AS e ON e.hotel_id=h.id AND e.airport_id='.$airport_current_id);
        $query->leftJoin('#__sfs_airline_notification_tracking AS t ON t.hotel_id=h.id AND DATE(t.date)=CURDATE()');

        $query->where("h.ws_id IS NULL");
        $query->where("NOT EXISTS(
            SELECT *
            FROM #__sfs_room_inventory AS i
            WHERE i.date = CURDATE() AND i.hotel_id = h.id
        )");
        $db->setQuery($query);

        $result = $db->loadObjectList();

        return $result;
	}
	
	function autoSearch()
	{
		$this->setState('filter.allow_search', 1);

		$rooms  = JRequest::getInt('rooms');
		$session = JFactory::getSession();
		$roomRequest = json_decode($session->get("rooms_search"), true);

		$date_start = JRequest::getVar('date_start');
		$end_date = JRequest::getVar('date_end');

		if(!$date_start) {
			$date_start = date('Y').'-'.date('m').'-'.date('d');
			$end_date = SfsHelperDate::getNextDate(JText::_('DATE_FORMAT_LC4'), $date_start);
		}

		$transport_included = JRequest::getVar('transport_included');

		if($transport_included) {
			$this->setState('filter.transport_included', (int)$transport_included);
		}

		$show_all = JRequest::getVar('show_all_rooms');

		if($show_all) {
			$this->setState('filter.show_all_rooms', (int)$show_all);
		}

		$offer_meal_plans = JRequest::getVar('offer_meal_plans');

		if($offer_meal_plans) {
			$this->setState('filter.offer_meal_plans', (int)$offer_meal_plans);
		}

		$filter_hotel_star = (int) JRequest::getVar('hotel_star');


		if( $filter_hotel_star > 0 ) {
			$this->setState('filter.hotel_star', $filter_hotel_star);
		}
		if( $filter_hotel_star > 0 ) {
			$this->setState('filter.hotel_star', $filter_hotel_star);
		}


		$this->setState('filter.rooms', (int)$rooms);
		$this->setState('filter.date_start', $date_start);
		$this->setState('filter.date_end', $end_date);

		$wsOnly = JRequest::getInt('ws_only', 0);
		$wsCacheOnly = true;
		$ws = true;
		if($wsOnly) {
			$wsCacheOnly = false;
		}
		$this->setState('filter.ws_only', $wsOnly);
		$this->setState('filter.ws_cacheonly', $wsCacheOnly);
		// WS search impact
		if($ws)
 		{
			$roomRequest[] = array('num_adults]' => 2, 'num_children' => 0 );
			$airport_code = JRequest::getVar('AirportCode');
			$wsHotels = SfsWs::searchHotels($airport_code, $date_start, $rooms, $roomRequest , 1, $wsCacheOnly);

			///print_r( $wsHotels );
			///die;
			$wsHotelsMap = array();
			$wsIDs = array();

			/* @var $obj Ws_Do_Search_Result */
			if($wsHotels) {
				foreach($wsHotels as $obj) {
					///print_r( $obj );
					$hotel_id = $obj->PropertyID;
					$RoomTypes = $obj->RoomTypes;
					
					$price_single_room = array();
					$price_single_room_is_asve = array();
					
					$price_double_room  = array();					
					$price_double_room_is_asve  = array();
					
					foreach ( $RoomTypes as $vk => $vobj ) {
						$NumAdultsPerRoom = $vobj->NumAdultsPerRoom;//kieu phong doi or đơn
						if ( $NumAdultsPerRoom == 1 ) {
							$price_single_room[] = $vobj->Total;
							$price_single_room_is_asve[] = $vobj;
						}
						else {
							$price_double_room[] = $vobj->Total;
							$price_double_room_is_asve[] = $vobj;
						}
					}//End foreach ( $RoomTypes
					
					$total_min_single_room = min($price_single_room);
					foreach ( $price_single_room as $k => $v ) {
						if ( $total_min_single_room == $v ) {
							//is cal function save to DB
							$this->executeSaveHotelSearches( $price_single_room_is_asve[$k], 1 );
							break;
						}
					}//End foreach ( $price_single_room
					
					$total_min_double_room = min($price_double_room);
					foreach ( $price_double_room as $k => $v ) {
						if ( $total_min_double_room == $v ) {
							//is cal function save to DB
							$this->executeSaveHotelSearches( $price_double_room_is_asve[$k], 2 );
							break;
						}
					}//End foreach ( $price_double_room
					
				}//End foreach($wsHotels
				
			}//End if($wsHotels) {
			
		}//End if($ws)
		exit;
	}
	
	//function save or update button-to-start-totalstay-hotel-searches in back-end execute
	//administrator/index.php?option=com_sfs&view=starttotalstayhotelsearches
	public function executeSaveHotelSearches( $data,  $roomtype = NULL ){
		
		$hotel_id = $data->PropertyID;
		$d = $this->getItem( $roomtype, $hotel_id );
		if ( empty( $d ) ) {
			$avaTotalstay_hotel_searches = new stdClass();
			$avaTotalstay_hotel_searches->hotel_id = $hotel_id;
			$avaTotalstay_hotel_searches->date = $data->ArrivalDate;
			$avaTotalstay_hotel_searches->roomtype = $roomtype;
			$avaTotalstay_hotel_searches->price = $data->Total;
			$avaTotalstay_hotel_searches->number_of_rooms = 1;
			$this->insertHotelSearches( $avaTotalstay_hotel_searches );
		}
		else {
			$id = $d->id;
			$price = $data->Total;
			$date = $data->ArrivalDate;
			$this->updateHotelSearches( $id, $price, $date);
		}
		return;
	}
	
	//get 
	public function getItem( $roomtype, $hotel_id ){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');						
		$query->from('#__availability_totalstay_hotel_searches AS a');
		$query->where("a.roomtype = $roomtype");
		$query->where("a.hotel_id = $hotel_id");
		$db->setQuery($query);
		$item = $db->loadObject();
		return $item;	
	}
	
	public function getItems()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);		
		$query->select('a.*');
		$query->from('#__availability_totalstay_hotel_searches AS a');		
		$db->setQuery($query);		
		$items = $db->loadObjectList();						
		return $items;
	}
	
	public function insertHotelSearches( $avaTotalstay_hotel_searches )
	{	///print_r( $avaTotalstay_hotel_searches );die;
		$db = $this->getDbo();
		if (!$db->insertObject('#__availability_totalstay_hotel_searches', $avaTotalstay_hotel_searches)) {
			die('Could not create availability totalstay hotel searches');
		} else {
			return $db->insertid();
		}
	}
	
	public function updateHotelSearches( $id, $price, $date ){
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->update("#__availability_totalstay_hotel_searches");
        $query->set("price='$price'");
		$query->set("date='$date'");
        $query->where('id='.$id);
        $db->setQuery($query);
        $result = $db->execute();
        if(!$result)
        {
            die('Could not update availability totalstay hotel searches');
        }
		return;
    }
	
	public function getDistanceOfUser(){
		$user = JFactory::getUser();
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);		
		$query->select('a.distance');
		$query->from('#__users AS a');	
		$query->where('id='.$user->id);	
		$db->setQuery($query);		
		$item = $db->loadObject();	
		if( $item ){
			return $item->distance;
		}
		return 0;
	}
	
}

