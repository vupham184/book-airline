<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelTracepassenger extends JModel
{
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
        $airline = SFactory::getAirline();

		$filter_date = JRequest::getVar('filter_date');
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		
		if( !$filter_date )
		{
			$filter_date = SfsHelperDate::getDate('now','Y-m-d', $time_zone);		
		}
		$this->setState('filter_date', $filter_date);
		
		$filter_until_date= JRequest::getVar('filter_until_date');
		if( !$filter_until_date )
		{
			$filter_until_date = SfsHelperDate::getNextDate('Y-m-d', $filter_date);		
		}
		$this->setState('filter_until_date', $filter_until_date);
		if(JRequest::getVar('filter_guest_relations'))
			$filter_guest_relations= JRequest::getVar('filter_guest_relations');		
		$this->setState('filter_guest_relations', $filter_guest_relations);
		
		$value = JRequest::getVar('filter_lastname');
		$this->setState('filter_lastname', $value);
				
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	
	public function getTracePassengers()
	{
		$db 	            = $this->getDbo();
		$airline            = SFactory::getAirline();
		$filter_date 		=  $this->getState('filter_date');
		$filter_until_date 	=  $this->getState('filter_until_date');
		$filter_guest_relations 	=  $this->getState('filter_guest_relations');
        $result             = array();

        $query 	= $db->getQuery(true);
		$query->select('p.id as passenger_id, p.voucher_id, p.flight_number,
						p.airport_id, p.title, p.individual_voucher, 
						p.first_name, p.last_name, p.type, p.phone_number,p.room_type, p.voucher_room_id,
						p.created_date'
		);
		
        $query->from('#__sfs_trace_passengers AS p');
		$query->select('pa.*');
		///$query->leftJoin('#__sfs_passengers_airplus AS pa ON pa.id=p.airplus_id');
		$query->innerJoin('#__sfs_passengers_airplus AS pa ON pa.id=p.airplus_id');
		

		$query->select('car.cvc AS CVC_car, car.card_number AS card_number, car.value as car_value, car.valid_thru as car_valid_thru, car.unique_id');
		$query->leftJoin('#__sfs_airplusws_creditcard_detail AS car ON car.airplus_id=pa.id ');
		
		$query->select('d.status, e.blockcode, d.code AS voucher_code,d.comment,d.breakfast,d.lunch,d.mealplan, d.flight_id');
		$query->leftJoin('#__sfs_voucher_codes AS d ON d.id=p.voucher_id');
		$query->leftJoin('#__sfs_reservations AS e ON e.id=d.booking_id');

        $query->select('h.name AS hotel_name,h.telephone AS hotel_phone');
        $query->leftJoin('#__sfs_hotel AS h ON h.id=e.hotel_id');
        
		$query->select("fi.*");
		$query->leftJoin('#__sfs_flightinfo AS fi ON fi.fltref=p.fltref');

		$query->where('pa.airline_id='.(int)$airline->id." AND p.created_date >= ".$db->quote($filter_date)." AND p.created_date <=".$db->quote($filter_until_date));
		if($filter_guest_relations){
			$query->where('p.guest_relations_id='.$filter_guest_relations);
		}
		$query->group('p.pnr');	
		$query->group('p.first_name');		
		$query->group('p.last_name');	
		$query->group('p.gender');
		//$query->group('p.id');
        $db->setQuery($query);
//		echo (string)$query;die;
        $rows = $db->loadObjectList();
		foreach ( $rows as $vk => $v ) {
			
			if( $v->airplus_mealplan == 0 && $v->airplus_taxi == 0 && $v->airplus_cash == 0 && $v->airplus_phone == 0 ) {
				$is_not_user_sevice = 1;
				$v->airport_code = $airline->airport_code;
				$v->flight_number = $this->getFlightsCode( $v->flight_id );
			}
			$v->pax = $this->getTracePassengerCount( $v->voucher_id, $v->id);
			$v->rebook=$this->getRebook($v->pnr,$v->first_name,$v->last_name,$v->phone_number);	
			$v->services = $this->getPassengerService($v->passenger_id);
		}
		return $rows;
	}
	
	public function getTracePassengerCount( $voucher_id, $airplus_id ){
            $db = $this->getDbo();
            $query = $db->getQuery(true);

			$query->select('a.*, b.first_name, b.last_name, b.phone_number');
			$query->from('#__sfs_passengers_airplus AS a ');
		
			$query->innerJoin('#__sfs_trace_passengers AS b ON a.id=b.airplus_id');
			
			$query->select('vc.reason');			
			$query->leftJoin('#__sfs_voucher_cancels AS vc ON vc.voucher_id=a.voucher_id');
			
			$query->select('pa1.cvc AS CVC_meal, pa1.card_number AS card_number_meal, pa1.value as meal_value, pa1.valid_thru as meal_valid_thru, pa1.unique_id as meal_unique_id');
			$query->leftJoin('#__sfs_airplusws_creditcard_detail AS pa1 ON pa1.airplus_id=a.id AND pa1.type_of_service="meal"');

            $query->select('pa2.cvc AS CVC_taxi, pa2.card_number AS card_number_taxi, pa2.value as taxi_value, pa2.valid_thru as taxi_valid_thru, pa2.unique_id as taxi_unique_id');
            $query->leftJoin('#__sfs_airplusws_creditcard_detail AS pa2 ON pa2.airplus_id=a.id AND pa2.type_of_service="taxi"');
			
			
			$query->select('vm.taxi_voucher_id, tv.taxi_id,tv.printed,tv.return_printed');
			$query->leftJoin('#__sfs_airline_taxi_voucher_map AS vm ON vm.voucher_id=a.voucher_id');
			$query->leftJoin('#__sfs_taxi_vouchers AS tv ON tv.id=vm.taxi_voucher_id');
			if ( $voucher_id > 0 )
            	$query->where('a.voucher_id='.$voucher_id);
			else
				$query->where('a.id='.(int)$airplus_id);
			$query->group('b.id');
            $db->setQuery($query);
			//echo (string)$query;die;
            return $db->loadObjectList();//loadObject();
      
    }

	public function getPassengerDetail( $pnr = '' )
	{
		$db 	= $this->getDbo();
		$passenger_id = JRequest::getInt('passenger_id');
		if ( $passenger_id <= 0 ){
			return null;
		}
	
		$query 	= $db->getQuery(true);

        $query->select('pa.id AS airplus_id,pa.airplus_mealplan, pa.airplus_taxi,pa.airplus_cash,pa.airplus_phone');
        $query->from('#__sfs_passengers_airplus AS pa');
        ///$query->select('p.id, p.first_name, p.last_name, p.flight_number, p.title as sex, p.phone_number, p.voucher_id, p.room_type, p.voucher_id, p.created_date');
		$query->select('p.*, if (p.pnr != "", p.pnr, r.url_code )as  pnrN');
        $query->innerJoin('#__sfs_trace_passengers AS p ON p.airplus_id=pa.id');

        $query->select('d1.id AS airplus_meal_id, d1.cvc AS CVC_meal, d1.card_number AS card_number_meal');
        $query->leftJoin('#__sfs_airplusws_creditcard_detail AS d1 ON d1.airplus_id=pa.id AND d1.type_of_service="meal"');

        $query->select('d2.id AS airplus_taxi_id, d2.cvc AS CVC_taxi, d2.card_number AS card_number_taxi');
        $query->leftJoin('#__sfs_airplusws_creditcard_detail AS d2 ON d2.airplus_id=pa.id AND d2.type_of_service="taxi"');

        $query->select('vc.reason');
        $query->leftJoin('#__sfs_voucher_cancels AS vc ON vc.voucher_id=p.voucher_id');

        $query->select('v.code AS voucher_code, v.comment as vc_comment, v.breakfast, v.lunch,v.mealplan');
        $query->leftJoin('#__sfs_voucher_codes AS v ON v.id=pa.voucher_id');

        $query->select('fs.flight_code,fs.from_date, fs.end_date');
        $query->leftJoin('#__sfs_flights_seats AS fs ON fs.id=v.flight_id');


        $query->select('r.airport_code,r.blockcode, r.url_code,r.id as reservationid,r.blockdate');
        $query->leftJoin('#__sfs_reservations AS r ON r.id=v.booking_id');

        $query->select('h.name AS hotel_name,h.telephone AS hotel_phone');
        $query->leftJoin('#__sfs_hotel AS h ON h.id=r.hotel_id');

        $query->select('rf.flight_number AS return_flight_number, rf.flight_date AS return_flight_date');
        $query->leftJoin('#__sfs_voucher_return_flights AS rf ON rf.voucher_id=v.id');

        $query->select('hai.distance,hai.distance_unit,hai.hotel_id');
        $query->leftJoin('#__sfs_hotel_airports AS hai ON h.id=hai.hotel_id');

    	$query->select('fi.dep,fi.std,fi.etd,fi.sta,fi.eta,fi.carrier,fi.flight_no,fi.arr,fi.ata, fi.delay, fi.irreg_message, fi.gate_info,fi.irreg_reason as irreg_reason_fi');
       	$query->leftJoin('#__sfs_flightinfo AS fi ON fi.fltref=p.fltref');
		
		//lchung
		$query->select('gtcm.passenger_group_transport_company_id');
		$query->leftJoin('#__sfs_passenger_group_transport_company_map AS gtcm ON gtcm.passenger_id=p.id');
		$query->select('gtc.group_transportation_types_id,gtc.date_expire_time, gtc.airline_airport_id, gtc.airport_id as gtc_airport_id, gtc.comment as gtc_comment');
		$query->leftJoin('#__sfs_group_transport_company AS gtc ON gtc.id=gtcm.passenger_group_transport_company_id');		
		//End lchung
		
		// get info taxi
		$query->select('taxi.option_taxi,taxi.taxi_id,taxi.distance  AS taxDistance, taxi.total_price AS taxTotalPrice, taxi.way_option AS taxWayOption, taxi.to_address AS taxToAddress, taxi.from_andress AS taxFromAndress,taxCPN.name AS taxCpnName,taxCPN.telephone AS taxCpnTelephone,taxCPN.mobile_phone AS taxCpnMobile_phone');
        $query->leftJoin('#__sfs_book_issue_taxi AS taxi ON p.id=taxi.passenger_id');
        $query->leftJoin('#__sfs_taxi_companies AS taxCPN ON taxCPN.id = taxi.taxi_id');

        //get info refreshment
        $query->select('ref.valamount as refreshment_amount,ref.currency as refreshment_currency,ref.delaytime');
        $query->leftJoin('#__sfs_issue_refreshment AS ref ON p.id=ref.passenger_id');

        
		
        // get info train
        $query->select('bt.travel_date,bt.id_from_trainstation,bt.id_to_trainstation,bt.type');
        $query->leftJoin('#__sfs_book_issue_trains AS bt ON p.id=bt.passenger_id');
        $query->where('p.id='.(int)$passenger_id);
		// get info rental car
		$query->select('rc.rental_id,rc.pick_up,rc.drop_off');
		$query->leftJoin('#__sfs_service_rental_car AS rc ON rc.passenger_id=p.id');
		$query->select('crc.company as name_company,crc.location_name as location_name_company,crc.address as address_company,crc.zipcode as zipcode_company, crc.city as city_company, crc.telephone as telephone_company');
		$query->leftJoin('#__sfs_company_rental_car AS crc ON rc.rental_id=crc.id');

		// $query->select('biTax.option_taxi, biTax.taxi_id,biTax.from_andress AS tax_from_andress,biTax.to_address AS tax_to_address,biTax.distance AS taxDistance, biTax.total_price AS taxTotalPrice');
		// $query->leftJoin('#__sfs_book_issue_taxi AS biTax ON biTax.passenger_id=p.id');
		//echo (string)$query;die;
		$db->setQuery($query);
		$rows = $db->loadObject();
        $rows->info_of_card_ap_meal = $this->getCardnumberByID($rows->airplus_meal_id, "meal");
        $rows->info_of_card_ap_taxi = $this->getCardnumberByID($rows->airplus_taxi_id, "taxi");

//        var_dump($rows);die();

		//lay uu tien co tao nhom
		$pG = $this->getPassengerInGroup( $passenger_id );
		if ( $pG ) {
			$rows->isgroupPNR = $pG;
		}
		else { //truong hop 2 la khg thuoc trong khi tao nhom nhung cung pnrN
			$rows->isgroupPNR = $this->getNexPassenger($passenger_id, $rows->pnrN);
		}
		$rows->flightinfo = $this->getFlightinfoPassenger( $rows->flight_code );//flight_code		
		//$rows->rebooked_fltno = $this->getFlightOfPassenger($passenger_id);
		$rows->rebooked_fltno = $this->getListRebook($rows);
		$rows->services_passenger =  $this->getServicesOfPassenger( 'all' );
		$rows->services_of_passenger =  $this->getServicesOfPassenger( $passenger_id );
		$rows->internal_comment = $this->getInternalComment( $passenger_id );
		//lchung
		$rows->group_transport_company = $this->getGroupTransportCompany();
		$rows->airport_of_airline  = $this->getairport_of_airline();
		$rows->rebook=$this->getRebook($rows->pnr,$rows->first_name,$rows->last_name,$rows->phone_number);	
		//End lchung

		// begin CPhuc
		$rows->subServiceOther 	= $this->getSubServiceOther($passenger_id);
		$rows->idSubService 	= $this->getIdSubServiceAssign($passenger_id);
		// end CPhuc
		return $rows;
		
	}
	
	//lchung
	public function getGroupTransportCompany(){
		
		$airline = SFactory::getAirline();
		$airport_id = $airline->airport_id;
		$db = JFactory::getDbo();
		
		$query = 'SELECT group_transportation_id FROM #__sfs_group_transportation_airports WHERE airport_id='.$airport_id;
		$db->setQuery($query);
		$item = $db->loadObject();	
		if( !empty( $item ) ) {
			$query = 'SELECT * FROM #__sfs_group_transportations WHERE id='.$item->group_transportation_id;
			$db->setQuery($query);
			$item->group_transportations = $db->loadObject();	
				
			$query = 'SELECT * FROM #__sfs_group_transportation_types WHERE group_transportation_id='.$item->group_transportation_id;
			$db->setQuery($query);
			$item->types = $db->loadObjectList();	
	
			$query_ = "SELECT a.group_transportation_id, b.code FROM #__sfs_group_transportation_airports AS a";
			$query_ .= " INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airport_id";
			$query_ .= " WHERE a.group_transportation_id = " . $item->group_transportation_id;
			$db->setQuery($query_);
			$item->airport = $db->loadObjectList();		
		}
			//print_r( $item );die;
		return $item;
	}
	
	public function getairport_of_airline(){
		
		$app = JFactory::getApplication();
		$user	= JFactory::getUser();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$airline = SFactory::getAirline();
		//print_r( $airline );die;
		$session = JFactory::getSession();
		$query->select('a.id ,a.code, a.name ');
		$query->from('#__sfs_iatacodes AS a');
		$query->innerJoin('#__sfs_airline_airport AS b ON b.airport_id=a.id');
		$query->where('b.airline_detail_id=' . (int)$airline->id);
		$query->where('b.airport_id=' . (int)$airline->airport_id);
		$query->where('a.type=2');
		$query->where('b.user_id=' . $user->id);

		$airportsAPI_Arr = $session->get('airportsAPI_Arr');
		if( count( $airportsAPI_Arr ) > 0 ){ //kiem tra truong hop khi login as API
			$query->where("a.code IN('" . implode("','", $airportsAPI_Arr ) . "')" );
		}
		else {
			$query->where("a.code IN('" . $airline->airport_code . "')" );
		}
		
		
		$query->order('a.code ASC');
		///echo (string)$query;die;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		//print_r( $result );die;
		return $result;
	}
	//End lchung
	
	//begin CPhuc
	public function getFlightOfPassenger($passenger_id = '')
	{
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		$query->select('p.*');
		$query->from('#__sfs_trace_passengers AS p ');
    	$query->select('fi.dep,fi.std,fi.etd,fi.sta,fi.eta,fi.carrier,fi.flight_no,fi.arr, fi.ata');
    	$query->leftJoin('#__sfs_flightinfo AS fi ON fi.fltref=p.rebooked_fltref');        
       	$query->where('p.id='.$passenger_id);
       	$db->setQuery($query);
		$rows = $db->loadObject();
		return $rows;

	}
	//end CPhuc

	public function getNexPassenger( $passenger_id, $pnr = '' )
	{
		$db 	= $this->getDbo();
		
		$query 	= $db->getQuery(true);

        $query->select('pa.id AS airplus_id,pa.airplus_mealplan, pa.airplus_taxi,pa.airplus_cash,pa.airplus_phone');
        $query->from('#__sfs_passengers_airplus AS pa');
		$query->select('p.*, if (p.pnr != "", p.pnr, r.url_code )as  pnrN');
        $query->innerJoin('#__sfs_trace_passengers AS p ON p.airplus_id=pa.id');

        $query->select('d1.id AS airplus_meal_id, d1.cvc AS CVC_meal, d1.card_number AS card_number_meal');
        $query->leftJoin('#__sfs_airplusws_creditcard_detail AS d1 ON d1.airplus_id=pa.id AND d1.type_of_service="meal"');

        $query->select('d2.id AS airplus_taxi_id, d2.cvc AS CVC_taxi, d2.card_number AS card_number_taxi');
        $query->leftJoin('#__sfs_airplusws_creditcard_detail AS d2 ON d2.airplus_id=pa.id AND d2.type_of_service="taxi"');

        $query->select('vc.reason');
        $query->leftJoin('#__sfs_voucher_cancels AS vc ON vc.voucher_id=p.voucher_id');

        $query->select('v.code AS voucher_code, v.comment, v.breakfast, v.lunch,v.mealplan');
        $query->leftJoin('#__sfs_voucher_codes AS v ON v.id=pa.voucher_id');

        $query->select('fs.flight_code,fs.from_date, fs.end_date');
        $query->leftJoin('#__sfs_flights_seats AS fs ON fs.id=v.flight_id');


        $query->select('r.airport_code,r.blockcode, r.url_code');
        $query->leftJoin('#__sfs_reservations AS r ON r.id=v.booking_id');

        $query->select('h.name AS hotel_name,h.telephone AS hotel_phone');
        $query->leftJoin('#__sfs_hotel AS h ON h.id=r.hotel_id');

        $query->select('rf.flight_number AS return_flight_number, rf.flight_date AS return_flight_date');
        $query->leftJoin('#__sfs_voucher_return_flights AS rf ON rf.voucher_id=v.id');

        $query->select('hai.distance,hai.distance_unit,hai.hotel_id');
        $query->leftJoin('#__sfs_hotel_airports AS hai ON h.id=hai.hotel_id');

        $query->where('(p.pnr='.$db->Quote( $pnr ).' OR r.url_code='.$db->Quote($pnr).')');

		$query->where('p.id !='.$passenger_id);
		$query->group('p.id');
		$query->setLimit('1');
		///echo (string)$query;die;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	public function getFlightinfoPassenger( $flight_no = '' )
	{
		$airline            = SFactory::getAirline();
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
        $query->select('a.*');
        $query->from('#__sfs_flightinfo AS a');
        $query->where('a.flight_no='.$db->Quote( $flight_no ));
		$query->where('a.airline_id='.$db->Quote( $airline->id ));
		$db->setQuery($query);
		$rows = $db->loadObject();
		return $rows;
	}
	
    public function getCardnumberByID( $airplus_detail_id = 0, $types ) {
        if ( $airplus_detail_id == 0 ) {
            return array();
        }
        $db 	= $this->getDbo();
        $query 	= $db->getQuery(true);
        $query->select('
		cvc as CVC, card_number as CardNumber,
		type_of_service as TypeOfService,
		valid_from as ValidFrom,valid_thru as ValidThru,
		passenger_name as PassengerName, value');
        $query->from('#__sfs_airplusws_creditcard_detail');
        $query->where('id=' . $airplus_detail_id);
        $db->setQuery($query);
        if($types == 'meal'){
            $rows[] = $db->loadObject();
        }elseif($types == 'taxi'){
            $rows = $db->loadObject();
        }
        return json_encode($rows);
    }

    public function getPassengersWith($voucher_id, $passenger_id)
    {
        $db 	= $this->getDbo();
        $query 	= $db->getQuery(true);
        $query->select('id, first_name, last_name, title');
        $query->from('#__sfs_trace_passengers');
        $query->where('voucher_id='.$voucher_id." AND id <>". $passenger_id);
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }
	
	//lchung
	public function getFlightsCode($id) {
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.flight_code');
    	$query->from('#__sfs_flights_seats AS a ');
    	$query->where('a.id="'.$id . '"');
    	$db->setQuery($query);
    	return $db->loadObject()->flight_code;
    	
    }
	
	public function saveInternalcomment( $data ){
		$db = $this->getDbo();
    	$query = $db->getQuery(true);
        $query->update("#__sfs_trace_passengers AS a");
        $query->set("a.comment='".$data['internal_comment'] . "'");
        $query->where('a.id='. $data['passenger_id'] );
        $db->setQuery($query);
        $result = $db->execute();
        return $result;
	}
	
	public function getServicesOfPassenger( $passenger_id ){
		$airline = SFactory::getAirline();	
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
        $query->select('a.*');
        $query->from('#__sfs_services AS a');
		if( $passenger_id != 'all' ) {		
			$query->select('b.block_code,b.price_per_person');
			$query->innerJoin('#__sfs_passenger_service AS b ON b.service_id=a.id');
       		$query->where('b.passenger_id='.$db->Quote( $passenger_id ));
		}
		if($airline->params['airline_ws_status']==1){
						$query->leftJoin('#__sfs_airport_services AS c ON c.service_id=a.id');
						$query->where("c.airport_id = '".$airline_current->id."'");	
		}
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	public function getPassengerInGroup( $passenger_id ){
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
        $query->select('a.group_id');
        $query->from('#__sfs_detail_group_share_room AS a');
       	$query->where('a.passenger_id='.$db->Quote( $passenger_id ));
		$db->setQuery($query);
		$row = $db->loadObject();
		
		$query 	= $db->getQuery(true);
        $query->select('a.passenger_id as id, a.last_name, a.first_name, a.pnr');
        $query->from('#__sfs_detail_group_share_room AS a');
       	$query->where('a.group_id ='.$db->Quote( $row->group_id ));
		$query->where('a.passenger_id !='.$db->Quote( $passenger_id ));
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	public function getInternalComment( $passenger_id ){
		$db 		= $this->getDbo();
		$user = JFactory::getUser();       
		$query 	 	= $db->getQuery(true);
		$query->select('*');
		$query->from('#__sfs_internal_comment AS a');	
		$query->select('b.name');
		$query->leftJoin('#__users AS b ON b.id=a.user_id');	
		$query->where("passenger_id='" . $passenger_id . "'");
		$db->setQuery($query);	
		return $db->loadObjectList();
	}
	
	//End lchung

	//begin CPhuc
	public function updatePassenger($profile){
		$db = $this->getDbo();
    	$query  = $db->getQuery(true);
		$result = $db->updateObject('#__sfs_trace_passengers', $profile, 'id');
		return $result;
	}
	//end CPhuc
	//Minh tran
	function getRebook($pnr,$first_name,$last_name,$phone_number){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id as passenger_id');
		$query->from('#__sfs_trace_passengers AS a ');
    	$query->where('a.phone_number="'.$phone_number.'"');
    	$query->where('a.first_name="'.$first_name.'"');
    	$query->where('a.last_name="'.$last_name.'"');    	
    	$query->select('f.*');
		$query->leftJoin('#__sfs_flightinfo AS f ON a.fltref=f.fltref');
    	//$query->where('a.created >= DATE(NOW())');
    	$query->group('f.fltref');
    	$query->order('a.id DESC');
    	$db->setQuery($query);    	
    	return $db->loadObjectList();
	}
	
	function getPassengerService($passenger_id){
		$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.*,b.parent_id,b.name_service,b.icon_service');
    	$query->from('#__sfs_passenger_service AS a ');
    	$query->leftJoin('#__sfs_services AS b ON b.id = a.service_id');
    	$query->where('a.passenger_id="'.(int)$passenger_id . '"');
    	$query->group('a.service_id');
    	$query->order('service_id ASC');
    	$db->setQuery($query);
    	return $db->loadObjectList();
	}

	public function saveInternalcommentAjax($data){
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);    
    	$user = JFactory::getUser();	    	
        $query="INSERT INTO #__sfs_internal_comment(passenger_id,comment,airline_id,user_id,created_date)
VALUES ('".$data['passenger_id']."','".$data['internal_comment']."','".$data['airline_id']."','".$user->get('id')."','".date("Y-m-d")."');";
        $db->setQuery($query);                  
        $result = $db->execute(); 
        return $db->insertid();  
	}

	public function getRentalcar(){
		$airline = SFactory::getAirline();
		$airport_id = $airline->airport_id;
		$iatacode_id = $airline->iatacode_id;
		$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.*');
    	$query->from('#__sfs_company_rental_car AS a ');
    	$query->select('b.code as name_code');
		$query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.airport_code');
    	$query->where('airport_code="'.$airport_id. '"');
    	$db->setQuery($query);
    	$rows=$db->loadObjectList();
    	foreach ( $rows as $vk => $v ) {
			$location_info = $this->getRentallocation($airport_id,$v->id);
			$v->rentallocation  = $location_info;
			$v->airline_id = $iatacode_id;
		}
    	return $rows;
	}

	public function getRentallocation( $airport_id, $company_id ){
		$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.*,b.code');
    	$query->from('#__sfs_rental_car_location AS a ');
    	$query->select('b.code as name_code,b.name as name_airport');
		$query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.airportcode');
    	//$query->where('a.airportcode="' . $airport_id . '"');
    	$query->where('a.agency="' . $company_id . '"');
    	$db->setQuery($query);
    	return $db->loadObjectList();
	}
	public function getListRebook($passenger){
		$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('b.*');
    	$query->from('#__sfs_trace_passengers AS a ');    	
		$query->leftJoin('#__sfs_flightinfo AS b ON a.fltref = b.fltref');    	
    	$query->where('a.pnr = "' . $passenger->pnr . '" and a.first_name = "'. $passenger->first_name .'" and a.last_name = "'. $passenger->last_name .'" and b.fltref!="'. $passenger->fltref .'"');
    	$query->group('b.fltref');	
    	$db->setQuery($query);
    	return $db->loadObjectList();
	}
	//End Minh tran

	// begin CPhuc
	public function getTaxiCompany(){
		$airline = SFactory::getAirline();		
		$db 	= $this->getDbo();
    	$query 	= $db->getQuery(true);
    	$query->select('tax.id,tax.name,tax.telephone,tax.mobile_phone');
    	$query->from('#__sfs_taxi_companies AS tax');
    	$query->leftJoin('#__sfs_airline_taxicompany_map AS airlineTaxi ON airlineTaxi.taxi_id = tax.id');
    	$query->leftJoin('#__sfs_airport_taxicompany_map AS airportTaxi ON airportTaxi.taxi_id = tax.id');
    	$query->where('airportTaxi.airport_id = '.$airline->airport_id.' OR airlineTaxi.airline_id = '.$airline->id);
    	$db->setQuery($query);
    	$resutl = $db->loadObjectList();
    	return $resutl;
	}

	public function getSubServiceOther($passenger_id){
		$db 	= $this->getDbo();
    	$query 	= $db->getQuery(true);
    	$query->select('b.*');
    	$query->from('#__sfs_trace_passengers AS a');
    	$query->leftJoin('#__sfs_info_other_services AS b ON b.passenger_id = a.id');
    	$query->where('a.id = '.(int)$passenger_id);
    	$db->setQuery($query);
    	$resutl = $db->loadObject();
    	return $resutl;
	}

	public function getIdSubServiceAssign($passenger_id){
		$db 	= $this->getDbo();
    	$query 	= $db->getQuery(true);
    	$query->select('service_id');
    	$query->from('#__sfs_passenger_service');
    	$query->where('service_id > 7 AND passenger_id = '.(int)$passenger_id);
    	$db->setQuery($query);
    	$resutl = $db->loadObject();
    	return $resutl;
	}
	// end CPhuc
}