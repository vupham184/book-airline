<?php
// No direct access
defined('_JEXEC') or die;


jimport('joomla.application.component.model');

class SfsModelPassengersimport extends JModel
{
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		$airline = SFactory::getAirline();
		$filter_date = JRequest::getVar('filter_date');
		$service_type = JRequest::getVar('service_type');
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		if( !$filter_date )
		{
			$filter_date = SfsHelperDate::getDate('now','Y-m-d', $time_zone);		
		}
		$this->setState('filter_date', $filter_date);
		$this->setState('service_type', $service_type);
		
		$filter_until_date= JRequest::getVar('filter_until_date');
		if( !$filter_until_date )
		{
			$filter_until_date = SfsHelperDate::getNextDate('Y-m-d', $filter_date);		
		}
		$this->setState('filter_until_date', $filter_until_date);
		
		$value = JRequest::getVar('filter_lastname');
		$this->setState('filter_lastname', $value);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	
	public function getPassengers( $export_withID = NULL )
	{		
		$db 	= $this->getDbo();
		$airline = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$airport_current_id = $airline_current->id;
		$airport_current_code = $airline_current->code;
		// kiem tra da tao group cho passenger chưa
		$this->getPassengerNotGroup();
		$sortby = JRequest::getVar('sortby','');
		//echo $airline->airport_code;
		$filter_date 		=  ($this->getState('filter_date') == '') ? date('Y-m-d') : $this->getState('filter_date');
		$filter_until_date 	=  ( $this->getState('filter_until_date') == '' ) ? date('Y-m-d 23:59:59') : $this->getState('filter_until_date') . ' 23:59:59';

		$service_type 	=  $this->getState('service_type');
		
		$query 	= $db->getQuery(true);
		
		$query->select('p.voucher_id, p.comment, p.created_date, p.id AS passenger_id, p.title, p.first_name, p.last_name, p.phone_number,p.rebooked_fltref,p.fltref,p.email_address,p.status_issuevoucher,p.connections,p.irreg_reason as irreg_reason_pass,p.priority');
		
		$query->from('#__sfs_trace_passengers AS p');
		$query->select('pa.id, pa.airline_id, pa.blockcode, pa.airport_code, pa.flight_number,
			e.hotel_id, pa.pnr, pa.airplus_id, pa.airplus_mealplan, pa.airplus_taxi, pa.airplus_cash, pa.airplus_phone
			');
		$query->innerJoin('#__sfs_passengers_airplus AS pa ON pa.id=p.airplus_id');
		///$query->leftJoin('#__sfs_passengers_airplus AS pa ON pa.id=p.airplus_id');
		
		$query->select('car.cvc AS CVC_car, car.card_number AS card_number, car.value as car_value, car.valid_thru as car_valid_thru, car.unique_id');
		$query->leftJoin('#__sfs_airplusws_creditcard_detail AS car ON car.airplus_id=pa.id ');
		
		$query->select('e.status as reservations_status,e.sd_room,e.t_room,e.s_room,e.q_room, e.sd_rate, e.t_rate, e.s_rate, e.q_rate,e.ws_booking');
		$query->select('d.status, e.blockcode, d.code AS voucher_code,d.breakfast,d.lunch,d.mealplan, d.flight_id,e.id as reservationid,e.blockdate, d.voucher_groups_id');
		$query->leftJoin('#__sfs_voucher_codes AS d ON d.id=p.voucher_id');
		$query->leftJoin('#__sfs_reservations AS e ON e.id=d.booking_id');

		
		$query->select('h.name AS hotel_name,h.telephone AS hotel_phone, h.ws_id');
		$query->leftJoin('#__sfs_hotel AS h ON h.id=e.hotel_id');
		$query->select('pad.purchase_date, pad.service_desc, pad.supplier, pad.ticket_number');
		$query->leftJoin('#__sfs_passengers_airplus_data AS pad ON pad.dbi_au=car.unique_id');
		
		$query->where('pa.airline_id='.(int)$airline->id . " And p.pnr !='' ");
		$query->select("fi.*");
		$query->leftJoin('#__sfs_flightinfo AS fi ON fi.fltref=p.fltref');
		$query->select('gs.group_id');
		$query->leftJoin('#__sfs_detail_group_share_room AS gs ON gs.passenger_id=p.id');
		$query->select('rc.rental_id,rc.pick_up,rc.drop_off,rc.blockcode as rental_blockcode');
		$query->leftJoin('#__sfs_service_rental_car AS rc ON rc.passenger_id=p.id');
		//lchung
		$query->select('gtcm.passenger_group_transport_company_id');
		$query->leftJoin('#__sfs_passenger_group_transport_company_map AS gtcm ON gtcm.passenger_id=p.id');

		$query->select('gtc.group_transportation_types_id,gtc.date_expire_time, gtc.airline_airport_id, gtc.airport_id as gtc_airport_id, gtc.id as gtc_id, gtc.comment as gtc_comment, gtc.hotel_address, gtc.amount_address,gtc.price, gtst.name AS gtc_company_name');

		$query->leftJoin('#__sfs_group_transport_company AS gtc ON gtc.id=gtcm.passenger_group_transport_company_id');

		$query->leftJoin('#__sfs_group_transportation_types AS gtt ON gtc.group_transportation_types_id = gtt.id');
		$query->leftJoin('#__sfs_group_transportations AS gtst ON gtst.id = gtt.group_transportation_id');

		
		$query->select('biTax.option_taxi, biTax.taxi_id,biTax.from_andress AS tax_from_andress,biTax.to_address AS tax_to_address,biTax.distance AS taxDistance, biTax.total_price AS taxTotalPrice, biTax.way_option AS taxWayOption,biTax.hotel_id AS taxHotelId,taxCPN.name AS taxCpnName');
		$query->leftJoin('#__sfs_book_issue_taxi AS biTax ON biTax.passenger_id=p.id');
		$query->leftJoin('#__sfs_taxi_companies AS taxCPN ON taxCPN.id = biTax.taxi_id');
		
		
		
		//End lchung
		
		//phuc
		$query->select('issuerefreshment.valamount as refreshment_amount, issuerefreshment.currency AS refreshment_currency');
		$query->leftJoin('#__sfs_issue_refreshment AS issuerefreshment ON issuerefreshment.passenger_id=p.id');

		
		
		//endphuc
		//phuc
		$query->select('issuetrain.id_to_trainstation as to_trainstation,issuetrain.id_from_trainstation as from_trainstation,issuetrain.travel_date as train_travel_date');
		$query->leftJoin('#__sfs_book_issue_trains AS issuetrain ON issuetrain.passenger_id=p.id');
		$query->select('stationairline.stationname as stationname');
		$query->leftJoin('#__sfs_airline_trains AS stationairline ON issuetrain.id_to_trainstation=stationairline.id');
		//endphuc
		
		if ( $export_withID != NULL ) {
			$query->where("p.id IN( $export_withID )");
		}
		if($sortby=='name')
			$query->order('p.first_name ASC');
		if($sortby=='date')
			$query->order('p.created_date DESC');
		if($sortby=='flightn')
			$query->order('pa.flight_number ASC');
		if($sortby=='std'){			
			//$query->leftJoin('#__sfs_flightinfo AS fi ON fi.flight_no=pa.flight_number');
			$query->select("Replace(fi.std, 'T', ' ') as sort_std");
			$query->order('sort_std ASC');			
		}		
		if($sortby=='atd'){
			$query->select("Replace(fi.atd, 'T', ' ') as sort_atd");
			$query->order('sort_atd ASC');				
		}		
		if($airport_current_id>0){
			$query->where("(fi.dep = '".$airport_current_code."' OR fi.arr = '".$airport_current_code."')");
		}

		$query->where("DATE(fi.flight_date) >= '".$this->getTodayDate()."'");
		//$query->group('p.phone_number');		
		$query->group('p.pnr');	
		$query->group('p.first_name');		
		$query->group('p.last_name');	
		$query->group('p.gender');	
		//$query->group('p.airplus_id');	
		$query->order('gs.group_id DESC');
		$query->order('p.priority DESC');
		$query->order('p.pnr DESC');	
		if($sortby==''){
			$query->order('gs.id DESC');				
		}	
		
		///$query->group('p.voucher_id');
		//echo (string)$query;die;

		$db->setQuery($query);
		$rows = $db->loadObjectList();			
		//print_r($rows);
		//die();
		// Begin minhtran
		$group=array();
		$pnr_group=array();
		foreach ( $rows as $vk => $v ) {		

			// add group for passenger pnr
			if(empty($v->group_id)){		
				if(empty($pnr_group[$v->pnr])){
					$group_id=$this->getGroupbyPnr($v->pnr);
					if($group_id){
						$pnr_group[$v->pnr]=$group_id;
						if($this->createGroupDetail( $pnr_group[$v->pnr], $v->passenger_id )){
							$v->group_id = $pnr_group[$v->pnr];
						}
					}else{
						$group_id=$this->createGroupPnr($v->pnr);
						$pnr_group[$v->pnr]=$group_id;
						if($this->createGroupDetail( $pnr_group[$v->pnr], $v->passenger_id )){
							$v->group_id = $pnr_group[$v->pnr];
						}
					}
				}
				else{
					if($this->createGroupDetail( $pnr_group[$v->pnr], $v->passenger_id )){
						$v->group_id = $pnr_group[$v->pnr];
					}
				}
			}
			$v->rebook=$this->getRebook($v->pnr,$v->first_name,$v->last_name,$v->phone_number);			
			$services = $this->getPassengerService($v->passenger_id);
			$v->services  = $services;

			$info_other_service = $this->getOtherServiceByPassId($v->passenger_id);
			$v->info_other_service = $info_other_service;

			if($v->group_id!='')	{
				$group[$v->group_id][]=$v->passenger_id;	
				$refreshment_amount[]=$v->refreshment_amount;
			}	
			else{				
				//$group[$v->pnr][]=$v->passenger_id;
				$refreshment_amount[]=$v->refreshment_amount;
			}	
			$v->group_partner=$this->getListPartnerByPassenger($v->passenger_id);	

			// get info ws hotel

			if($v->ws_booking){
				$v->info_ws=Ws_Do_Book_Response::fromString($v->ws_booking);
			}
		}
		$rows['group']=$group;
		$rows['refreshment_amount']=$refreshment_amount;
		// $rows['to_trainstation'] = $to_trainstation;
		foreach ( $rows as $vk => $v ) {
			if($v->passenger_id){
				$v->comment_passenger = $this->getCommentPassenger($v->passenger_id);
			}			
		}
		

		// print_r($rows);die();
		// End minhtran
		return $rows;
	}
	
	public function getTracePassengerCount( $voucher_id, $airplus_id, $is_not_user_sevice ){
		$db = $this->getDbo();
		$query = $db->getQuery(true);

			if ( $is_not_user_sevice == 1 ) { //khong co dung dich vu nao
				$query->select(' b.first_name, b.last_name, b.phone_number');
				$query->from('#__sfs_trace_passengers AS b ');			
				$query->where('b.voucher_id='.$voucher_id);
				$db->setQuery($query);
				return $db->loadObjectList();//loadObject();
			}
			
			$query->select('a.*, b.id AS passenger_id, b.first_name, b.last_name, b.phone_number');
			$query->from('#__sfs_passengers_airplus AS a ');			

			$query->leftJoin('#__sfs_trace_passengers AS b ON a.id=b.airplus_id');
			
			$query->select('vc.reason');			
			$query->leftJoin('#__sfs_voucher_cancels AS vc ON vc.voucher_id=b.voucher_id');
			
			$query->select('pa1.cvc AS CVC_meal, pa1.card_number AS card_number_meal, pa1.value as meal_value, pa1.valid_thru as meal_valid_thru, pa1.unique_id as meal_unique_id');
			$query->leftJoin('#__sfs_airplusws_creditcard_detail AS pa1 ON pa1.airplus_id=a.id AND pa1.type_of_service="meal"');

			$query->select('pa2.cvc AS CVC_taxi, pa2.card_number AS card_number_taxi, pa2.value as taxi_value, pa2.valid_thru as taxi_valid_thru, pa2.unique_id as taxi_unique_id');
			$query->leftJoin('#__sfs_airplusws_creditcard_detail AS pa2 ON pa2.airplus_id=a.id AND pa2.type_of_service="taxi"');
			
			
			$query->select('vm.taxi_voucher_id, tv.taxi_id,tv.printed,tv.return_printed');
			$query->leftJoin('#__sfs_airline_taxi_voucher_map AS vm ON vm.voucher_id=a.voucher_id');
			$query->leftJoin('#__sfs_taxi_vouchers AS tv ON tv.id=vm.taxi_voucher_id');
			if ( $voucher_id > 0 )
				$query->where('b.voucher_id='.$voucher_id);
			else
				$query->where('a.id='.$airplus_id);
			
			$query->group('b.id');
			$db->setQuery($query);
			//echo (string)$query;die;
			return $rows = $db->loadObjectList();//loadObject();
			/*$rowsN = array();
			foreach ( $rows as $vk => $v ) {
				$rowsN['k' . $v->passenger_id] = $v;
			}
			return $rowsN;*/

		}

		public function getAmount(&$count_amount, $unique_id ){
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.amount');
			$query->from('#__sfs_passengers_airplus_data AS a ');
			$query->where('a.dbi_au="'.$unique_id . '"');
			$db->setQuery($query);
		$d = $db->loadObjectList();//loadObject();
		$amount = '';
		foreach ( $d as $vk => $v ) {
			$count_amount++;
			$amount += floatval( $v->amount );
		}
		return $amount;
	}

	public function getExpectedAirplusAmount($airplus_id) {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.value');
		$query->from('#__sfs_airplusws_creditcard_detail AS a ');
		$query->where('a.airplus_id="'.$airplus_id . '"');
		$db->setQuery($query);
    	$d = $db->loadObjectList();//loadObject();
    	$amount = '';
    	foreach ( $d as $vk => $v ) {
    		$amount += floatval( $v->value );
    	}
    	return $amount;
    }

    public function getFlightsCode($id) {
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.flight_code');
    	$query->from('#__sfs_flights_seats AS a ');
    	$query->where('a.id="'.$id . '"');
    	$db->setQuery($query);
    	return $db->loadObject()->flight_code;
    	
    }
	//End lchung
	// Begin minhtran
    public function createGroupPnr($pnr){
    	$user = JFactory::getUser();  
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$data = new stdClass();				
    	$data->created_date 		= date('Y-m-d H:i:s');	
    	$data->created_by 		= $user->id;	
    	$data->pnr = $pnr;
    	if( !$db->insertObject('#__sfs_group_share_room',$data) ) {
    		$this->setError($db->getErrorMsg());
    		return false;
    	}
    	return $db->insertid();
    }
    public function getGroupbyPnr($pnr){
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.id');
    	$query->from('#__sfs_group_share_room AS a ');
    	$query->where('a.pnr="'. $pnr . '"');
    	$db->setQuery($query);
    	return $db->loadObject()->id;
    }

    public function getRebook($pnr,$first_name,$last_name,$phone_number){
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
    public function getPassengerCurrent($phone_number){
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.id');
    	$query->from('#__sfs_trace_passengers AS a ');
    	$query->where('a.phone_number="'.$phone_number.'"');
    	//$query->where('a.created >= DATE(NOW())');
    	$query->order('a.id ASC');
    	$db->setQuery($query,0,1);
    	return $db->loadObject()->id;
    }
    public function getPassengerService($passenger_id){
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.*,b.parent_id,b.name_service,b.icon_service');
    	$query->from('#__sfs_passenger_service AS a ');
    	$query->leftJoin('#__sfs_services AS b ON b.id = a.service_id');
    	$query->where('passenger_id="'.$passenger_id . '"');
    	$query->order('service_id ASC');
    	$db->setQuery($query);
    	return $db->loadObjectList();
    }

    public function getFlightInfo($flight_no){
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$query->select('*');
    	$query->from('#__sfs_flightinfo AS a ');
    	$query->where('fltref="'.$flight_no . '"');
    	$db->setQuery($query);
    	return $db->loadObjectList();
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
    public function bookServiceRental(){
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$airline            = SFactory::getAirline();
    	$passengers             = JRequest::getVar('pass_ids', array() , 'post', 'array');
    	$user = JFactory::getUser();        
    	$date = JFactory::getDate();
    	$rental_id=JRequest::getVar('rental_id');
    	$group_id=JRequest::getVar('group_id');
    	$pick_up_id=JRequest::getVar('pick_up_id');     
    	$drop_off_id=JRequest::getVar('drop_off_id');     
    	$airportcode_id=JRequest::getVar('rental_airportcode_id');     
    	$airline_id=JRequest::getVar('airline_id'); 
    	$start_date = SfsHelperDate::getDate('now','dmy', $time_zone);  
    	$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SRC', $start_date);
    	if($passengers && $group_id){
    		$info_rental = $this->getRentalById($rental_id);
    		foreach ($passengers as $key => $value) {    
    		if($value!=''){    			
    			if($this->checkbookservice($value)==0){
					        	$query="INSERT INTO #__sfs_service_rental_car(blockcode,blockdate,rental_id,airline_id,booked_by,booked_date,airport_code,pick_up,drop_off,group_id,passenger_id)
					        	VALUES ('".$blockcode."','".date("Y-m-d")."',".$rental_id.",'".$airline_id."','".$user->get('id')."',".$db->Quote($date->toSQL()).",'".$airportcode_id."','".$pick_up_id."','".$drop_off_id."','".$group_id."','".$value."');";
					        	$db->setQuery($query);
					        	$db->execute();					        	      
					        }   
					        else{
					        	$this->deletebookServiceRental($value);
					        	$query="INSERT INTO #__sfs_service_rental_car(blockcode,blockdate,rental_id,airline_id,booked_by,booked_date,airport_code,pick_up,drop_off,group_id,passenger_id)
					        	VALUES ('".$blockcode."','".date("Y-m-d")."',".$rental_id.",'".$airline_id."','".$user->get('id')."',".$db->Quote($date->toSQL()).",'".$airportcode_id."','".$pick_up_id."','".$drop_off_id."','".$group_id."','".$value."');";
					        	$db->setQuery($query);
					        	$db->execute();	
					        }
					    }
					}
					$info_rental = $this->getRentalById($rental_id);
					$price_rental_car = floatval($info_rental->price_default/count($passengers));
					$this->updatePricePerPerson($group_id,$price_rental_car,$info_rental->price_default);
				}
					return 1;
				}
				function updatePricePerPerson($group_id,$price_rental_car,$total_price){
					$db = JFactory::getDbo();
    				$query = $db->getQuery(true);
					$query = "UPDATE #__sfs_service_rental_car
SET price_per_person='".$price_rental_car."', price='".$total_price."' WHERE group_id='".$group_id."'";					
    				$db->setQuery($query);
					$db->execute();
				}
				public function deletebookServiceRental($passenger_id){
					$db = JFactory::getDbo();
					$user = JFactory::getUser();        
					$date = JFactory::getDate();
					$rental_id=JRequest::getVar('rental_id');
					$group_id=JRequest::getVar('group_id');
					$pick_up_id=JRequest::getVar('pick_up_id');     
					$drop_off_id=JRequest::getVar('drop_off_id');     
					$airportcode_id=JRequest::getVar('rental_airportcode_id');    
					$airline_id=JRequest::getVar('airline_id');      
					$query="DELETE FROM #__sfs_service_rental_car WHERE passenger_id=".$passenger_id;
					$db->setQuery($query);
					return $result = $db->execute();
				}
				public function checkbookservice($passenger_id){
					$db = JFactory::getDbo();
					$user = JFactory::getUser();        
					$date = JFactory::getDate();
					$rental_id=JRequest::getVar('rental_id');
					$group_id=JRequest::getVar('group_id');
					$pick_up_id=JRequest::getVar('pick_up_id');     
					$drop_off_id=JRequest::getVar('drop_off_id');     
					$airportcode_id=JRequest::getVar('rental_airportcode_id');    
					$airline_id=JRequest::getVar('airline_id');   
					$query = $db->getQuery(true);
					$query='select count(*) from #__sfs_service_rental_car where rental_id='.$rental_id." and blockdate='".date("Y-m-d")."' and booked_by='".$user->get('id')."' and pick_up='".$pick_up_id."' and drop_off='".$drop_off_id."' and airline_id='".$airline_id."' and passenger_id=".$passenger_id;
					$db->setQuery($query);
					return $db->loadResult();
				}

				function getCommentPassenger($passenger_id){
					$db 		= $this->getDbo();
					$user = JFactory::getUser();       
					$query 	 	= $db->getQuery(true);
					$query->select('*');
					$query->from('#__sfs_internal_comment AS a');	
					$query->select('b.name');
					$query->leftJoin('#__users AS b ON b.id=a.user_id');	
					$query->where("passenger_id='".$passenger_id."'");
					$db->setQuery($query);	
					return $db->loadObjectList();		
				}

	// create group passenger
				function createGroupPassengerTravel(){
					$db 		= $this->getDbo();
					$user = JFactory::getUser();      
					$query 	 	= $db->getQuery(true);
					$data = new stdClass();				
					$data->created_date 		= date('Y-m-d H:i:s');	
					$data->created_by 		= $user->id;	
					if( !$db->insertObject('#__sfs_group_share_room',$data) ) {
						$this->setError($db->getErrorMsg());
						return false;
					}
					return $resultid 	= $db->insertid();
				}
	// create group passenger detail
				function createGroupDetail( $group_id, $passenger_id ){
					$db = $this->getDbo();
					$user = JFactory::getUser();      
					$query 	 	= $db->getQuery(true);
					$passenger_info = $this->getPassengerInfo( $passenger_id );
					$passenger_group_old=$this->checkPassengerGroupOld($passenger_id,$passenger_info->pnr);
					if(count($passenger_group_old)>0){
						$this->removePassengerInGroup($passenger_group_old->passenger_id,$passenger_group_old->group_id);
						$this->checkPassengerGroup($passenger_group_old->group_id);
					}
					$data = new stdClass();				
					$data->passenger_id 	= $passenger_id;	
					$data->pnr 				= $passenger_info->pnr;				
					$data->first_name	= $passenger_info->first_name ;
					$data->last_name=$passenger_info->last_name;	
					$data->group_id			= $group_id;
					$data->status			= 1;
			//$data->share_room_id	= $this->createStatusShareRoom($group_id);			
					if( !$db->insertObject('#__sfs_detail_group_share_room',$data)) {
						$this->setError($db->getErrorMsg());
						return false;
					}
					return $db->insertid();

				}
				function checkPassengerGroupOld( $passenger_id ,$pnr ){
					$db = $this->getDbo();
					$user = JFactory::getUser();      
					$query 	 	= $db->getQuery(true);
					$query="select * from #__sfs_detail_group_share_room where passenger_id='".$passenger_id."' and pnr='".$pnr."'";
					$db->setQuery($query);
					return $result= $db->loadObject();
				}
	// insert share room
				function createStatusShareRoom($group_id){
					$db = $this->getDbo();
					$user = JFactory::getUser();      
					$query = $db->getQuery(true);
					$data = new stdClass();				
					$data->type_room = 0;	
					$data->status_share_room = 0;		
					$data->group_id = $group_id;	
					$data->created_date	= date('Y-m-d H:i:s');				
					if( !$db->insertObject('#__sfs_status_share_room',$data)) {
						$this->setError($db->getErrorMsg());
						return false;
					}
					return $db->insertid();
				}
	// process travel together share room
				function travelTogetherShareRoom($passenger_ids_sha,$passenger_ids_group,$group_id){
					$db 		= $this->getDbo();
					$user = JFactory::getUser();  		
					if($passenger_ids_group){
						foreach($passenger_ids_group as $pg){
							$type_room = count($passenger_ids_group)-count($passenger_ids_sha);
							$travel_together = 0;
							$status_share_room=0;
							foreach($passenger_ids_sha as $ps){
								if($ps==$pg){
									$type_room=count($passenger_ids_sha);
									$status_share_room=1;
								}
							}
							$detailgroup=$this->getDetailgroup($pg,$group_id);						
							$query="UPDATE #__sfs_status_share_room SET status_share_room='".$status_share_room."', type_room='".$type_room."' where id='".$detailgroup->share_room_id."'";		
							$db->setQuery($query);
							if( !$db->execute()) {
								$this->setError($db->getErrorMsg());
								return false;
							}				
						}
					}
					return 1;
				}
	// process travel together share room
				function travelTogetherSeperateRoom($passenger_ids_sep,$passenger_ids_group,$group_id){
					$db 		= $this->getDbo();
					$user = JFactory::getUser();  		
					if($passenger_ids_group){
						foreach($passenger_ids_group as $pg){
							$type_room = count($passenger_ids_group)-count($passenger_ids_sep);
							$travel_together = 0;
							$status_share_room=0;
							foreach($passenger_ids_sep as $ps){
								if($ps==$pg){
									$type_room=1;
									$status_share_room=0;
								}
							}
							$detailgroup=$this->getDetailgroup($pg,$group_id);						
							$query="UPDATE #__sfs_status_share_room SET status_share_room='".$status_share_room."', type_room='".$type_room."' where id='".$detailgroup->share_room_id."'";		

							$db->setQuery($query);
							$db->execute();		
						}
					}
				}

	// not share room
				function travelNotShareRoom($passenger_ids_not_sha,$passenger_ids_group,$group_id){
					$db 		= $this->getDbo();
					$user = JFactory::getUser();  
					if($passenger_ids_group){
						foreach($passenger_ids_group as $pg){
							$type_room = count($passenger_ids_group)-count($passenger_ids_not_sha);
							$travel_together = 0;
							$status_share_room=0;
							foreach($passenger_ids_not_sha as $ps){
								if($ps==$pg){
									$type_room=1;
									$status_share_room=0;						
								}
							}

							$detailgroup=$this->getDetailgroup($pg,$group_id);						
							$query="UPDATE #__sfs_status_share_room SET status_share_room='".$status_share_room."', type_room='".$type_room."' where id='".$detailgroup->share_room_id."'";
							$db->setQuery($query);
							$db->execute();		
						}
					}
				}
	// get detail group of passenger
				function getDetailgroup($passenger_id,$group_id){
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query->select('*');
					$query->from('#__sfs_detail_group_share_room AS a');		
					$query->where("passenger_id = '".$passenger_id."'");
					$query->where("group_id = '".$group_id."'");
					$db->setQuery($query);
					return $db->loadObject();		
				}
	//get info of passenger
				function getPassengerInfo($passenger_id){
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query->select('*');
					$query->from('#__sfs_trace_passengers AS a');		
					$query->where("id = '".$passenger_id."'");
					$db->setQuery($query);
					return $db->loadObject();
				}
	// Remove passenger in group
				function removePassengerInGroup($passenger_id,$group_id){
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query 		="DELETE FROM #__sfs_detail_group_share_room WHERE passenger_id='".$passenger_id."'";		
					$db->setQuery($query);
					return $result = $db->execute();
				}
	// check passenger group empty
				function checkPassengerGroup($group_id){
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query 		="Select count(*) FROM #__sfs_status_share_room WHERE group_id='".$group_id."'";	
					$db->setQuery($query);
					$result= $db->loadResult();
					if($result==0){			
						$query_delete_group	="DELETE FROM #__sfs_group_share_room WHERE id='".$group_id."'";
						$db->setQuery($query_delete_group);
						if(!$db->execute()) {
							$this->setError($db->getErrorMsg());
							return false;
						}
					}
				}
	//get list passenger by group
				public function getListByGroup($group_id){
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query 		="Select * FROM #__sfs_detail_group_share_room WHERE group_id='".$group_id."'";
					$db->setQuery($query);
					$result= $db->loadObjectList();
					return $result;
				}

	// get list services
				public function getServices(){
					$airline = SFactory::getAirline();	
					$airline_current = SAirline::getInstance()->getCurrentAirport();
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query->select('a.*');
					$query->from('#__sfs_services as a');		
					if($airline->params['airline_ws_status']==1){
						$query->leftJoin('#__sfs_airport_services AS b ON b.service_id=a.id');
						$query->where("b.airport_id = '".$airline_current->id."'");	
					}
					$db->setQuery($query);
					return $db->loadObjectList();
				}

	// add service for passenger
				public function addPassengerService( $passenger_id, $service_id, $status ){
					$check_insert = $this->checkPassengerService( $passenger_id, $service_id );
					
					$db = $this->getDbo();
					$user = JFactory::getUser();
					$query = $db->getQuery(true);
					if($check_insert==0){

						if ($service_id > 7 ) {
							
							$query	="DELETE FROM #__sfs_passenger_service WHERE passenger_id='" . $passenger_id . "' AND service_id > 7";
							$db->setQuery($query);
							$db->execute();

							$data = new stdClass();		
							$data->passenger_id = $passenger_id;							
							$data->service_id = $service_id;
								

							if( !$db->insertObject('#__sfs_passenger_service',$data)) {
								$this->setError($db->getErrorMsg());
								return false;
							}
							return $db->insertid();
						}   
						else{
							$data = new stdClass();		
							$data->passenger_id = $passenger_id;							
							$data->service_id = $service_id;
									
							if( !$db->insertObject('#__sfs_passenger_service',$data)) {
								$this->setError($db->getErrorMsg());
								return false;
							}
							return $db->insertid();
						}
					}		
					else
					{
						if ((int)$service_id > 7) {
							$query->clear();
							$query	="DELETE FROM #__sfs_passenger_service WHERE passenger_id='" . $passenger_id . "' AND service_id > 7";
							$db->setQuery($query);
							$db->execute();

							$data = new stdClass();		
							$data->passenger_id = $passenger_id;							
							$data->service_id = $service_id;
							
							if( !$db->insertObject('#__sfs_passenger_service',$data)) {
								$this->setError($db->getErrorMsg());
								return false;
							}
							return $db->insertid();
						}
						else{
							return 0;
						}

					}
				}
	// check service for passenger
				function checkPassengerService( $passenger_id, $service_id ){

					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query = "select count(*) from #__sfs_passenger_service where passenger_id='" .$passenger_id. "' and service_id='".$service_id."'";			
					$db->setQuery($query);
					return $db->loadResult();

				}
	// remove service for passenger
				public function removePassengerService( $passenger_id, $service_id, $status ){
					$db = $this->getDbo();
					$user = JFactory::getUser();      
					$query_delete = $db->getQuery(true);
					$query_delete	="DELETE FROM #__sfs_passenger_service WHERE passenger_id='" . $passenger_id . "' and service_id='" . $service_id . "'";
					$db->setQuery($query_delete);
					if(!$db->execute()) {
						$this->setError($db->getErrorMsg());
						return 0;
					}
					return 1;
				}
	// get list account local station	
	public function getListPartner($search_key){
	    $user = JFactory::getUser();
	    $db = JFactory::getDbo();
	    $airline = SFactory::getAirline();
	    $query = 'SELECT a.*,u.username FROM #__sfs_communication_partners as a INNER JOIN #__users AS u ON u.id=a.user_id AND u.airport IS NOT NULL INNER JOIN #__user_usergroup_map AS um ON um.user_id=a.user_id AND um.group_id=18 WHERE a.airlineid =' . $airline->id .' AND a.sitamessage != "" ';   
	    if($search_key!=''){
	    	$query .= " AND (a.companyname LIKE '%".$search_key."%' OR a.name LIKE '%".$search_key."%')";
	    }
	    $db->setQuery($query);	    
	    $result = $db->loadObjectList();
	    return $result;
	}		
		//save passenger and partner local
	public function savePassengerPartner($passenger_id,$user_id){		
	    $airline = SFactory::getAirline();
	    $db = $this->getDbo();
		$query = $db->getQuery(true);
		$data = new stdClass();		
		$data->passenger_id = $passenger_id;							
		$data->user_id = $user_id;		
		if( !$db->insertObject('#__sfs_assign_station_airline_add_issue',$data)) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $db->insertid();
	}	
	//check passenger and partner local
	public function checkPassengerPartner($passenger_id,$user_id){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('count(a.id)');
		$query->from('#__sfs_assign_station_airline_add_issue AS a');
		$query->where("a.user_id = '".$user_id."' ");	
		$query->where("a.passenger_id = '".$passenger_id."'");
		$db->setQuery($query);
		$result = $db->loadResult();
	    return $result;
	}
		//get list partner by passenger
	public function getListPartnerByPassenger($passenger_id){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.user_id');
		$query->from('#__sfs_assign_station_airline_add_issue AS a');		
		$query->where("a.passenger_id = '".$passenger_id."'");
		$db->setQuery($query);
		$result = $db->loadObjectList();
	    return $result;
	}
	
	//get Rental by id
	public function getRentalById($company_id){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_company_rental_car AS a');		
		$query->where("a.id = '".$company_id."'");
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	//get date today
	public function getTodayDate()
	{
		$airline 	= SFactory::getAirline();
		$params		= JComponentHelper::getParams('com_sfs');
		$cleanTime 	= trim($params->get('match_hours'));

		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$cleanTime = $setup_airport['hours_on_match_page'];
		}
		//End lchung	

        $session = JFactory::getSession();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
        //$session = JFactory::getSession();
        //$time_zone = $session->get("time_zone");
		//lchung
		$time_zone = $airline_current->time_zone;
		//End lchung


		$db = $this->getDbo();
		
		$is_next_day = false;
						
		if( strlen($cleanTime) > 0 )
		{
			$cleanTime = explode( ':' , $cleanTime);
			
			$nowTime = SfsHelperDate::getDate('now','H:i',$time_zone);
						
			$nowTime = explode(':', $nowTime);
						
			JArrayHelper::toInteger($nowTime);
			JArrayHelper::toInteger($cleanTime);
								
			if( $nowTime[0] >= $cleanTime[0] ){
				if( $nowTime[0] == $cleanTime[0]) {
					if( $nowTime[1] >= $cleanTime[1]  ) {
						$is_next_day = true;
					}	
				} else {
					$is_next_day = true;
				}						
			}	
		}
										
		$today = SfsHelperDate::getDate('now','Y-m-d',$time_zone);
		
		if( ! $is_next_day )
		{
			$today = SfsHelperDate::getPrevDate('Y-m-d', $today);
		}
		return $today;
	}
	function getDetailReservation(){
		$reservation_id = JRequest::getInt('reservation_id',0);
		if($reservation_id){
			$db 		= $this->getDbo();
			$query 	 	= $db->getQuery(true);
			$query->select('*');
			$query->from('#__sfs_reservations AS a');
			$query->where('	id = '.$reservation_id);
			$db->setQuery($query);
			return $db->loadObject();	
		}
	}
	function getPassengerNotGroup(){
		$airline = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$airport_current_id = $airline_current->id;
		$airport_current_code = $airline_current->code;
		$db 		= $this->getDbo();
		$query 	 	= $db->getQuery(true);
		$query->select('p.*');		
		$query->from('#__sfs_trace_passengers AS p');
		$query->leftJoin('#__sfs_flightinfo AS fi ON fi.fltref=p.fltref');	
		$query->innerJoin('#__sfs_passengers_airplus AS pa ON pa.id=p.airplus_id');
		$query->select('gs.group_id');
		$query->where('pa.airline_id='.(int)$airline->id . " And p.pnr !='' ");
		$query->leftJoin('#__sfs_detail_group_share_room AS gs ON gs.passenger_id=p.id');	
		$query->where("DATE(fi.flight_date) >= '".$this->getTodayDate()."'");
		if($airport_current_id>0){
			$query->where("(fi.dep = '".$airport_current_code."' OR fi.arr = '".$airport_current_code."')");
		}
		//$query->where("p.status_group = 0");
		$query->group('p.pnr');	
		$query->group('p.first_name');		
		$query->group('p.last_name');	
		$query->group('p.gender');	
		$sql='select m.* from ('.$query.') as m where m.status_group = 0';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();
		//print_r($rows);
		//echo count($rows);
		//echo '=========================================================';
		if($rows){
			//print_r($rows);
			//die;
			foreach ($rows as $vk => $v) {
				// add group for passenger pnr
				if(empty($v->group_id)){		
					$this->createGroupDetail( $group_id, $v->id );
					$this->updateStatusGroupPassenger($v->id);					
				}
			}
		}		
	}
	function updateStatusGroupPassenger($passenger_id){
		$db 		= $this->getDbo();
		$query 	 	= $db->getQuery(true);
		$query = 'UPDATE #__sfs_trace_passengers SET status_group = 1 WHERE id='.$passenger_id;
		$db->setQuery($query);
		$db->query();
	}
	public function updateNameFlag($passenger_id,$nameflag){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('p.*');		
		$query->from('#__sfs_trace_passengers AS p');
		$query->where('p.id='.(int)$passenger_id);
		$db->setQuery($query);
		$row = $db->loadObject();
		$invoicing_flag = $row->invoicing_flag;
		$f = true;
		if($invoicing_flag == ''){
			$invoicing_flag .= $nameflag;
		}else{
			$flags = explode("_",$invoicing_flag);	
			foreach ($flags as $flag) {
				if($flag==$nameflag){
					$f=false;
					echo '1'	;
	        		die;
				}
			}
			if($f==true){
				$invoicing_flag .= '_'.$nameflag;	
			}
			
		}
		
		if($f==true){
			$query 	 	= $db->getQuery(true);
			$query = 'UPDATE #__sfs_trace_passengers SET invoicing_flag = "'.$invoicing_flag.'" WHERE id='.$passenger_id;
			$db->setQuery($query);
			if($db->query()){
				echo '1'	;
	        		die;	
			}
		}
		echo '0';
        	die();
		
	}

	public function updateNameFlagFi($fi_id,$nameflag){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('p.*');		
		$query->from('#__sfs_flightinfo AS p');
		$query->where('p.id='.(int)$fi_id);
		$db->setQuery($query);
		$row = $db->loadObject();
		$invoicing_flag = $row->invoicing_flag;		
		if($invoicing_flag == ''){
			$invoicing_flag .= $nameflag;
			$query 	 	= $db->getQuery(true);
			$query = 'UPDATE #__sfs_flightinfo SET invoicing_flag = "'.$invoicing_flag.'" WHERE id='.$fi_id;
			$db->setQuery($query);
			if($db->query()){
				echo '1'	;
	        		die;	
			}

		}else{
			$flags = explode(",",$invoicing_flag);	
			$nameflags = explode(",",$nameflag);	
			if($nameflag){
				$fi_add = '';
				foreach ($nameflags as $key => $value) {
					$fl = true;
					foreach ($flags as $flag) {
						if($flag==$value){
							$fl = false;
							break;
						}
					}
					if($fl==true){
						if($fi_add!=''){
							$fi_add.=','.$value;
						}else{
							$fi_add.=$value;
						}
					}
				}
				if($fi_add!=''){
					if($invoicing_flag!=''){
						$invoicing_flag .= ','.$fi_add;
					}else{
						$invoicing_flag .= ','.$fi_add;
					}					
					$query 	 	= $db->getQuery(true);
					$query = 'UPDATE #__sfs_flightinfo SET invoicing_flag = "'.$invoicing_flag.'" WHERE id='.$fi_id;
					$db->setQuery($query);
					if($db->query()){
						echo '1'	;
			        		die;	
					}
				}
				
				die();
			}

		}
	}
	public function getSameAircraft($date,$aircraft_id){
		$db 	= $this->getDbo();
    	$query 	= $db->getQuery(true);
		$query->select('*');
		$query->from('#__sfs_flightinfo as fi');
		$query->where('date_timestamp="'.$date.'"');
		$query->where('registration="'.$aircraft_id.'"');
		$db->setQuery($query);
    	return $db->loadObjectList();
	}

	public function getListPassengerByIds($pass_ids,$groupby)
	{		
		$db 	= $this->getDbo();
    	$query 	= $db->getQuery(true);
		$query->select('p.*');
		$query->from('#__sfs_trace_passengers as p');
		$query->select('pa.airline_id, pa.blockcode, pa.airport_code, pa.flight_number,
			 pa.pnr, pa.airplus_id, pa.airplus_mealplan, pa.airplus_taxi, pa.airplus_cash, pa.airplus_phone');
		$query->innerJoin('#__sfs_passengers_airplus AS pa ON pa.id=p.airplus_id');
		$query->select("fi.std,fi.etd,fi.dep,fi.arr");
		$query->leftJoin('#__sfs_flightinfo AS fi ON fi.fltref=p.fltref');		
		$query->select('v.code AS voucher_code, v.comment as vc_comment, v.breakfast, v.lunch,v.mealplan');
        $query->leftJoin('#__sfs_voucher_codes AS v ON v.id=pa.voucher_id');
		$query->where('p.id in ('.$pass_ids.')');
		if($groupby!='')
			$query->group('p.'.$groupby);	
		$db->setQuery($query);
    	return $db->loadObjectList();
	}
	// End minhtran

	//Begin nguyencongphuc
				public function getTitleAriline(){
					$airline 	= SFactory::getAirline();
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$query->select('a.id,a.title,b.value');
					$query->from('#__sfs_title_of_airline AS a');
					$query->leftJoin('#__sfs_option_title_airline AS b ON b.id = a.id_option');
					$query->where('	id_ariline = '.$airline->iatacode_id." AND status = 1");
					$db->setQuery($query);
					return $db->loadObjectList();
				}

				public function addTileIssueVoucher($title){
					$db 		= $this->getDbo();
					$query 	 	= $db->getQuery(true);
					$result 	= $db->insertObject('#__sfs_title_of_airline', $title);
					$resultid 	= $db->insertid();
					return $resultid;
				}
				public function getAirlineTrains()
				{

					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$airline = SFactory::getAirline();

					$query->select('id,stationname,cityname,country');
					$query->from('#__sfs_airline_trains');
					$query->where("country = '".$airline->country_n."' AND status = 1");
					$db->setQuery($query);
					$rows = $db->loadObjectList();
					return $rows;
				}
				public function saveTrain($arr){
					foreach ($arr as $key) {
						$arr_passenger_id[] = $key['passenger_id'];
					}
					$passenger_ids = implode(',', $arr_passenger_id) ;
					if (!empty($passenger_ids)) {
						$db = $this->getDbo();
						$query1 = $db->getQuery(true);
						$query1->delete('#__sfs_book_issue_trains');
						$query1->where('passenger_id IN ('.$passenger_ids.')');
						$db->setQuery($query1);
						$db->execute();
					}
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->insert($db->quoteName('#__sfs_book_issue_trains'));
					$columns = array('id_from_trainstation', 'id_to_trainstation', 'travel_date', 'type','passenger_id');
					$query->columns($columns);
					foreach ($arr as $value) {
						$query->values('"'.implode('","', $value).'"');
					}
					$db->setQuery($query);
					$db->query();
					$result = $db->insertid();
					return $result;
				}
	//end nguyencongphuc


				/*begin CPhuc*/
				public function getDistanceHotel(){
					$airline = SFactory::getAirline();
					$airport_id = $airline->airport_id;
					$hotelId 	= JRequest::getVar('hotelId');
					if (isset($hotelId) || !empty($hotelId)) {
						$db = $this->getDbo();
						$query = $db->getQuery(true);
						$query->select('ha.distance,ha.distance_unit,h.name,i.starting_tariff,i.km_rate');
						$query->from('#__sfs_hotel_airports AS ha');
						$query->leftJoin('#__sfs_hotel AS h ON h.id = ha.hotel_id');
						$query->leftJoin('#__sfs_iatacodes AS i ON ha.airport_id = i.id');
						$query->where('ha.airport_id = '.$airport_id.' AND ha.hotel_id ='.(int)$hotelId);
						$db->setQuery($query);
			// return $query;
						return $db->loadObject();
					}
					else{
						return null;
					}
				}
				/*end CPhuc*/


	//lchung

				public function getairport_of_airline(){

					$app = JFactory::getApplication();
					$user	= JFactory::getUser();
					$db = $this->getDbo();
					$query = $db->getQuery(true);
					$airline = SFactory::getAirline();
		//print_r( $airline );die;
					$session = JFactory::getSession();
					$query->select('a.id ,a.code, a.name, a.geo_lat, a.geo_lon ');
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
	
	public function getchangeAirportGroup( $id = NULL ){      
		$app = JFactory::getApplication();
		$db = $this->getDbo();
		$query = "SELECT b.id,b.name FROM #__sfs_hotel_airports as a";
		$query .= " INNER JOIN  #__sfs_hotel as b ON b.id = a.hotel_id";
		if( $id != NULL )
			$query .= " WHERE a.airport_id = " . $id;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}


	
	// add service for passenger
	public function addGroupTransportCompany( $dataA ){
		$db = $this->getDbo();
		$user = JFactory::getUser(); 
		$airline = SFactory::getAirline();		
		
		$dataGTran['sendSMS'] = $dataA['booked_by_phone'];
		$dataGTran['name'] = $dataA['name_company'];
		$dataGTran['mobile'] = $dataA['phone'];		
		$dataGTran['description'] = $dataA['comment'];
		$dataGTran['published'] = 1;
		$dataGTran['approved'] = 1;
		$dataGTran['created'] = date('Y-m-d H:i:s');
		$dataGTran['created_by'] = $user->id;		
		$dataGTran['address'] = "";
		$dataGTran['city'] = "";
		$dataGTran['country_id'] = 0;
		$dataGTran['telephone'] = $dataA['phone'];	
		$dataGTran['fax'] = $dataA['phone'];	
		$dataGTran['currency_id'] = $dataA['currency_id'];
		$dataGTran['airport_id'] = $dataA['airline_airport_id'];;
		$dataGTran['billing_name'] = '';
		$dataGTran['billing_address'] = '';		
		$dataGTran['billing_address'] = '';
		$dataGTran['billing_city'] = '';
		$dataGTran['billing_country_id'] = '';
		$dataGTran['billing_telephone'] = '';
		$dataGTran['billing_fax'] = '';
		$dataGTran['billing_tva_number'] = '';
		$dataGTran['sendMail'] = 0;		
		$dataGTran['sendFax'] = 0;
		$dataGTran['notification'] = '';
		$dataGTran = (object)$dataGTran;
		
		if( !$db->insertObject('#__sfs_group_transportations', $dataGTran )) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		$query = "SELECT LAST_INSERT_ID() as count";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		$newID = $db->insertid();
		
		$db = $this->getDbo();
		$dataGTranType['group_transportation_id'] = $result->count;
		$dataGTranType['seats'] = $dataA['numseater'];
		$dataGTranType['rate'] = $dataA['priceseater'];
		$dataGTranType['name'] = $dataA['numseater'] . ' seater';
		$dataGTranType['published'] = 1;
		$dataGTranType = (object)$dataGTranType;
		if( !$db->insertObject('#__sfs_group_transportation_types', $dataGTranType )) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		$db = $this->getDbo();
		$dataGAline['group_transportation_id'] = $result->count;
		$dataGAline['airline_id'] = $airline->iatacode_id;		
		$dataGAline = (object)$dataGAline;
		if( !$db->insertObject('#__sfs_group_transportation_airlines', $dataGAline )) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		$db = $this->getDbo();
		$dataGAport['group_transportation_id'] = $result->count;
		$dataGAport['airport_id'] = $airline->airport_id;		
		$dataGAport = (object)$dataGAport;
		if( !$db->insertObject('#__sfs_group_transportation_airports', $dataGAport )) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		$db = $this->getDbo();
		$dataGMap['group_transportation_id'] = $result->count;
		$dataGMap['user_id'] = $user->id;		
		$dataGMap = (object)$dataGMap;
		if( !$db->insertObject('#__sfs_group_transportation_user_map', $dataGMap )) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		/*$db = $this->getDbo();
		$dataGTranAir['airport_id'] = $dataA['airline_airport_id'];
		$dataGTranAir['group_transportation_id'] = $newID;
		$dataGTranAir = (object)$dataGTranAir;
		if( !$db->insertObject('#__sfs_group_transportation_airports', $dataGTranAir )) {
			$this->setError($db->getErrorMsg());
			return false;
		}*/
		///print_r( $dataGTranAir );die;
		return $newID;
	}
	
	public function addGroupTransport( $dataA ){
		$db = $this->getDbo();
		$user = JFactory::getUser();      
		$query = $db->getQuery(true);

		$ser_id = 3;
		$blockcode = $this->switchBlockCode($ser_id);
		$dataA['block_code'] = $blockcode;		
		//print_r($dataA); die();
		$passenger_idArr = $dataA['passenger_idArr'];
		unset( $dataA['passenger_idArr'] );
		$data = (object)$dataA;	
		if( !$db->insertObject('#__sfs_group_transport_company',$data)) {
			
			return false;
		}			

		$newID = $db->insertid();
		
		$row = new stdClass();
		$row->passenger_id = $passenger_idArr;
		$row->block_code = $blockcode;
		$row->vouchercodes = $this->randomString(2);
		if(!$db->updateObject('#__sfs_passenger_service', $row, 'passenger_id')){
			$this->setError($db->getErrorMsg());
			return fasle;
		}

		// up date seat_used in company type
		$passenger_idArr = explode(",", $dataA['passenger_idArr']);

		$query->update('#__sfs_group_transportation_types')
			->set('seat_used = seat_used + ' . count($passenger_idArr))
			->where('id = ' . $dataA['group_transportation_types_id']);
		$db->setQuery($query);
		$result = $db->execute();
		
		//print_r($blockcode);die();
		//add new not insert passenger map
		/*
		$passenger_idArr = explode(",", $passenger_idArr);
		foreach ( $passenger_idArr as $v ) {
			$dataN = new stdClass();
			$dataN->passenger_id = $v;
			$dataN->passenger_group_transport_company_id = $newID;
			$this->addPassengerGroupTransportCompanyMap( $dataN );
		}
		*/
		return $newID;

	}
	
	public function addPassengerGroupTransportCompanyMap( $dataObj ){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		if( !$db->insertObject('#__sfs_passenger_group_transport_company_map', $dataObj)) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return $db->insertid();
	}
	
	public function getGroupTransportCompany(){
		
		$airline = SFactory::getAirline();
		$airport_id = $airline->airport_id;
		$db = JFactory::getDbo();
		$item = new stdClass();
		$query = 'SELECT group_transportation_id FROM #__sfs_group_transportation_airports WHERE airport_id='.$airport_id;
		$db->setQuery($query);
		$itemG = $db->loadObjectList();
		$group_transportation_id_arr  = array();
		foreach ($itemG as $value) {
			$group_transportation_id_arr[] = $value->group_transportation_id;
		}

		if( !empty( $itemG ) ) {

			$query = 'SELECT * FROM #__sfs_group_transportations WHERE id IN ('.implode(',', $group_transportation_id_arr).')';

			$db->setQuery($query);
			$item->group_transportations = $db->loadObjectList();	

			$query = 'SELECT * FROM #__sfs_group_transportation_types';
			$query .= ' WHERE group_transportation_id IN ('.implode(',', $group_transportation_id_arr).')';
			$db->setQuery($query);
			$item->types = $db->loadObjectList();	
			
			$i = 0;
			foreach ($item->types as $key => $value) {
				$query = 'SELECT airport_id,airline_airport_id FROM #__sfs_group_transport_company';
				$query .= ' WHERE group_transportation_types_id = '.$value->id;
				$query .= ' ORDER BY group_transportation_types_id DESC';

				$db->setQuery($query);
				$dsub = $db->loadObject();
				$item->types[$i]->airport_id = $dsub->airport_id;
				$item->types[$i]->airline_airport_id = $dsub->airline_airport_id;
				$i++;
			}
			// print_r($item->types); die();


			$query_ = "SELECT a.group_transportation_id, b.code FROM #__sfs_group_transportation_airports AS a";
			$query_ .= " INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airport_id";
			$query_ .= " WHERE a.group_transportation_id IN (".implode(',', $group_transportation_id_arr).")";
			$db->setQuery($query);
			$item->airport = $db->loadObjectList();		
		}
			//print_r( $item );die;
		return $item;
		
	}
	
	public function getPassengerGroupTransportCompanyMap(){
		$airline 	= SFactory::getAirline();
		$db 		= $this->getDbo();
		$query 	 	= $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_passenger_group_transport_company_map AS a');
		$query->where('	ariline_id = '.$airline->id);
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public function addGroupTransportCompanyOtherPrice( $dataA ){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$id_new = 0;
		//print_r( $dataA );die;
		if ( $dataA['passenger_group_transport_company_id'] > 0 ) {
			$this->removePassengerGroupTransportCompany( $dataA['passenger_group_transport_company_id'] );
		}
		unset( $dataA['passenger_group_transport_company_id'] );
		$id_new = $this->addGroupTransport( $dataA );
		unset( $dataA['airline_id'] );		
		$dataA['group_transport_company_id'] = $id_new;
		$passenger_idArr = $dataA['passenger_idArr'];
		if( $passenger_idArr != '' )
			$this->removePassengerGroupTransportCompanyMap( $passenger_idArr, $id_new);
		
		$passenger_idArr = explode(",", $passenger_idArr);

		$flat = 0;
		foreach ( $passenger_idArr as $v ) {
			$dataN = new stdClass();
			$dataN->passenger_id = $v;
			$dataN->passenger_group_transport_company_id = $dataA['group_transport_company_id'];
			$flat += ((int)$this->addPassengerGroupTransportCompanyMap( $dataN ) > 0) ? 1 : 0;
		}

		//update Block code
		if ($flat == count($passenger_idArr)) {
			$serviceId = 3;
			//$this->updateBlockCode($serviceId,$passenger_idArr);
		}
		return $id_new;
	}
	
	// remove Passenger Group Transport Company Map
	public function removePassengerGroupTransportCompanyMap( $str_passenger_id, $group_transport_company_id){
		$db = $this->getDbo();
		$query_delete = $db->getQuery(true);
		$query_delete = "DELETE FROM #__sfs_passenger_group_transport_company_map 
		WHERE passenger_id IN($str_passenger_id) ";
		//AND passenger_group_transport_company_id='" . $group_transport_company_id . "'
		$db->setQuery($query_delete);
		if(!$db->execute()) {
			$this->setError($db->getErrorMsg());
			return 0;
		}
		return 1;
	}
	
	// remove Passenger Group Transport Company Map
	public function removePassengerGroupTransportCompany( $id ){
		$db = $this->getDbo();
		$query_delete = $db->getQuery(true);
		$query_delete = "DELETE FROM #__sfs_group_transport_company WHERE id = $id ";
		$db->setQuery($query_delete);
		if(!$db->execute()) {
			$this->setError($db->getErrorMsg());
			return 0;
		}
		return 1;
	}
	//End lchung
	/*begin CPhuc*/
	public function saveIssueTaxi($arr,$id_passenger){
		
		$passenger_ids = implode(',', $id_passenger);
		$price = 0;
		if (!empty($passenger_ids)) {
			$db = $this->getDbo();
			$query1 = $db->getQuery(true);
			$query1->delete('#__sfs_book_issue_taxi');
			$query1->where('passenger_id IN ('.$passenger_ids.')');
			$db->setQuery($query1);
			$db->execute();
		}
		
		$columns = array('airport_id', 'option_taxi', 'total_price', 'taxi_id','distance','from_andress','to_address','way_option', 'hotel_id', 'passenger_id');
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->insert('#__sfs_book_issue_taxi');
		$query->columns($columns);
		foreach ($arr as $value) {
			$price = (float)$value['total_price'];
			$query->values('"'.implode('","', $value).'"');

		}
		$db->setQuery($query);
		$result 	= $db->execute();
		$price 		= (float)$price/count($id_passenger);
		$service_id = 4;
		$vouchercodes 	= $this->randomString(2);
		foreach ($arr as $value) {
			$result1 	= $this->savePricePerPerson($price,$value['passenger_id'],$service_id,$vouchercodes);
		}
		return $result1 && $result;
	}

	public function loadIssueRefreshment($arr){
		$result = 0;
		$passenger_ids = implode(',', $arr) ;
		if (!empty($passenger_ids)) {
			$db = $this->getDbo();
			$query1 = $db->getQuery(true);
			$query1->select('valamount');
			$query1->from('#__sfs_issue_refreshment');
			$query1->where('passenger_id IN ('.$passenger_ids.')');
			$db->setQuery($query1);
			$result = $db->loadResult();
		}
		
		return $result;

	}
	/*end CPhuc*/
	public function saveIssueRefreshment($arr){
		
		foreach ($arr as $key) {
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('valamount');
			$query->from('#__sfs_issue_refreshment');
			$query->where('passenger_id = '.$key['passenger_id']);
			$db->setQuery($query);
			$result = $db->loadResult();
			$is_set = count($result);
			if ($is_set >0 ) {
				$arr_passenger_id[] = $key['passenger_id'];
			}	
		}


		$passenger_ids = implode(',', $arr_passenger_id) ;


		if (!empty($passenger_ids)) {
			$db = $this->getDbo();
			$query1 = $db->getQuery(true);
			$query1->delete('#__sfs_issue_refreshment');
			$query1->where('passenger_id IN ('.$passenger_ids.')');
			$db->setQuery($query1);
			$db->execute();
		}


		$columns = array('valamount', 'currency','textfresh', 'delaytime', 'passenger_id');
		$db = $this->getDbo();
		$query2 = $db->getQuery(true);
		$valamount = 0;
		$passenger_id;
		$passenger_ids = array();
		$query2->insert('#__sfs_issue_refreshment');
		$query2->columns($columns);

		foreach ($arr as $value) {
			$query2->values('"'.implode('","', $value).'"');
			array_push($passenger_ids, $value['passenger_id']);
			$valamount = $value['amount'];
		}
		if ($valamount != 0) {
			$db->setQuery($query2);
			$result = $db->execute();
			if ($result) {
				$passenger_ids 	= implode(',', $passenger_ids); 
				$service_id 	= 2;
				$info 			= $this->savePricePerPerson($valamount,$passenger_ids,$service_id);
				return $info;
			}
			else{
				return 0;
			}

		}
		else
			return 0;

	}
	
	//lchung
	public function updateStatusVoucher() {
		
		$db = $this->getDbo();
		$printtype 		= JRequest::getVar('printtype');
		$email	 		= JRequest::getVar('email');
		$voucher_id		= JRequest::getInt('voucher_id');
		$passenger_id		= JRequest::getInt('passenger_id');
		$passenger_id		= JRequest::getInt('passenger_id');
		$pass_ids		= JRequest::getVar('pass_ids', array(), 'post', 'array');
		$updateFieldArray = array();
		$status_issuevoucher = 2;
		
		if( $printtype == 'print' )
		{
			$status_issuevoucher = 3;
			$updateFieldArray[] = 'printed=1';
			$updateFieldArray[] = 'printed_date='.$db->quote(JFactory::getDate()->toSql());
		}
		else if( $printtype == 'email' ){
			$status_issuevoucher = 1;
			$updateFieldArray[] = 'passenger_email = '.$db->quote( $email );
			$updateFieldArray[] = 'handled_date = '.$db->quote(JFactory::getDate()->toSql());
		}
		if(count($updateFieldArray))
		{
			$query = 'UPDATE #__sfs_voucher_codes SET ' . implode(',', $updateFieldArray) . ' WHERE id='.$voucher_id;
			$db->setQuery($query);
			$db->query();
		}
		
		$updateFieldArray = array();
		$updateFieldArray[] = "status_issuevoucher = $status_issuevoucher";
		$query = 'UPDATE #__sfs_trace_passengers SET ' . implode( ',', $updateFieldArray );
		if($printtype == 'print'){
			if(count($pass_ids)>1){
				$query .=' WHERE id in ('.implode( ',', $pass_ids ).')';	
			}else{
				$query .=' WHERE id=' . $passenger_id;
			}
			
		}else{
			$query .=' WHERE id=' . $passenger_id;
		}
		
		$db->setQuery($query);
		$db->query();
		
	}
	
	
	//End lchung

	// begin CPhuc
	public function getTaxiCompany(){
		$airline = SFactory::getAirline();		
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		$query->select('tax.id,tax.name,tax.telephone,tax.mobile_phone');
		$query->from('#__sfs_taxi_companies AS tax');
		$query->leftJoin('#__sfs_airline_taxicompany_map AS airlineTaxi ON airlineTaxi.taxi_id = tax.id');
		$query->leftJoin('#__sfs_airport_taxicompany_map AS airportTaxi ON airportTaxi.taxi_id = tax.id');
		$query->where('airportTaxi.airport_id = '.$airline->airport_id);
		// $query->where('airportTaxi.airport_id = '.$airline->airport_id.' OR airlineTaxi.airline_id = '.$airline->id);
		$db->setQuery($query);
		$resutl = $db->loadObjectList();
		return $resutl;
	}
	// end CPhuc
	

	public function getLocationHotel(){		
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);		

		if( (int)$_POST['id'] * 2 == 0){
			$query = "SELECT id,geo_lat as geo_location_latitude,geo_lon as geo_location_longitude FROM #__sfs_iatacodes WHERE code = '" . $_POST['id'] . "'";	
		}else{
			$query = "SELECT id,name,geo_location_latitude,geo_location_longitude FROM #__sfs_hotel WHERE id = " . $_POST['id'];
		}
		
		$db->setQuery($query);
		$result = $db->loadObject();

		$lat = $result->geo_location_latitude; 
		$lon = $result->geo_location_longitude;
		$dataOrg = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&sensor=true");
		$jsondata = json_decode($dataOrg);
		$valueData = $jsondata->results[0]->formatted_address;
		
		return $valueData;
	}
	

	// begin CPhuc
	public function saveIrregReason($arr){
		$temp = array();
		$irreg_reason = '';
		foreach ($arr as $key => $value) {
			$irreg_reason = $value['irreg_reason'];
			$temp[] = $value['id']; 

		}
		$ids = '('.implode(',',$temp).')';
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$columns = array('id','irreg_reason');
		$query->update('#__sfs_trace_passengers');

		$query->set('irreg_reason = "'. (string)$irreg_reason.'"');
		$query->where('id IN'.$ids);

		$db->setQuery($query);
		$result = $db->execute();

		return $result;
	}


	public function saveSSMaas($arr){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$columns = array('comment', 'user_id','airline_id', 'created_date', 'passenger_id');
		$query->insert('#__sfs_internal_comment');
		$query->columns($columns);
		foreach ($arr as $value) {
			$query->values('"'.implode('","', $value).'"');
		}
		$db->setQuery($query);
		$result = $db->execute();

		return $result;
	}


	public function saveOtherServices($arr,$ids){
		
		$passenger_ids = array();
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		//delete passenger if exist
		$query->delete('#__sfs_info_other_services');
		$query->where('passenger_id IN '.$ids);
		$db->setQuery($query);
		$db->execute();

		//insert passenger
		$query->clear();
		$columns = array('sub_service_id','content','passenger_id');
		$query->insert('#__sfs_info_other_services');
		$query->columns($columns);
		foreach ($arr as $value) {
			$content 		= $value['content'];
			$service_id 	= $value['sub_service_id'];
			array_push($passenger_ids, $value['passenger_id']);
			$query->values("'".implode("','", $value)."'");
		}


		//get Info for table passengerService
		$arrContent 	= array();
		$arrContent 	= json_decode((string)$content); 
		$price 			= (float)$arrContent[0]->inputamount;
		$passenger_ids 	= implode(',',$passenger_ids);
		//end getInfo
		$db->setQuery($query);
		$result = $db->execute();
		if ($result) {
			$info = $this->savePricePerPerson($price,$passenger_ids,(int)$service_id);
			return $info;
		}
		else{
			return 0;
		}

	}

	public function getOtherServiceByPassId($passenger_id){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('other_services.sub_service_id AS other_sub_service_id, other_services.content AS other_service_content')
		->from('#__sfs_info_other_services AS other_services')
		->where('passenger_id = '.(int)$passenger_id);
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
	//end CPhuc

	//begin Phuc
	public function switchBlockCode($serviceId){
		$airline        = SFactory::getAirline();
		$start_date 	= SfsHelperDate::getDate('now','dmy', $time_zone);
		$blockcode 		= '';
		switch ((int)$serviceId) {
			case 2:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SRF', $start_date);
				break;
			case 3:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SGT', $start_date);
				break;
			case 4:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'STX', $start_date);
				break;
			case 6:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SRC', $start_date);
				break;
			case 10:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SSB', $start_date);
				break;
			case 11:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SPH', $start_date);
				break;
			case 12:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SCH', $start_date);
				break;
			case 13:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SRB', $start_date);
				break;
			case 14:
				$blockcode   = SReservation::generateBlockCodeRental($airline->code,'SMC', $start_date);
				break;
			default:
				$blockcode = '';
				break;
		}

		return $blockcode;
	}
	//end Phuc

	/*
		Note vouchercodes able Null
		
	*/
	public function savePricePerPerson($price,$passenger_ids,$service_id,$vouchercodes){
		
		$blockcode   	= $this->switchBlockCode((int)$service_id);

		$db 			= $this->getDbo();
		$query 			= $db->getQuery(true);
		$query->update('#__sfs_passenger_service')
			->set('price_per_person = "'.$price.'", block_code = "'.$blockcode.'"'.', vouchercodes = "'.$vouchercodes.'"')
			->where('passenger_id IN ('.$passenger_ids.') AND service_id = '.(int)$service_id);
		$db->setQuery($query);
		$result = $db->execute();
		return $result;
	}

	// begin CPhuc
	public function savePricePerPersonOfBus($data){
		$db 			= $this->getDbo();
		$query 			= $db->getQuery(true);

		$airport_id 					= $data['airport_id'];
		$group_transportation_types_id 	= $data['group_transportation_types_id'];
		$distance 						= $data['distance_comp'];
		$temp1 							= (int)$distance;
		$temp2 							= number_format((float)$distance,3) - $temp1;
		$price 							= $data['price'];

		$info_rate 						= $this->getRateBus($group_transportation_types_id);
		// $rate 							= (float)$info_rate->rate;

		if((int)$price == 0){
			// $rate = json_decode((string)$info_rate->rate);
			// $rate_case	= count($rate);
			// $flat 		= 1;
			// foreach ($rate as $key => $value) {
			// 	$temp3 = (int)$value->rate_second - (int)$value->rate_first;
			// 	if ($temp1 > $temp3) {
			// 		$price += ($temp1 - $temp3)*(float)$value->rate_three;
			// 		$temp1 -= $temp3;
			// 		if ($flat == $rate_case) {
			// 			$price += ($temp1 + $temp2)*(float)$value->rate_three;
			// 			break;
			// 		}
			// 	}
			// 	else if(($temp1 < $temp3) && $flat > 1){
			// 		$price += ($temp1 + $temp2)*(float)$value->rate_three;
			// 	}
			// 	else{
			// 		$price += $temp1 * (float)$value->rate_three;
			// 		break;
			// 	}
			// 	$flat++;
			// }
					
			$db_ 			= $this->getDbo();
			$query_ 			= $db->getQuery(true);	

			$query_ = 'SELECT rate, rate_fixed FROM #__sfs_group_transportation_types WHERE id = ' . $group_transportation_types_id;
			$db_->setQuery($query_);
			$result = $db_->loadObject();

			if( is_numeric($airport_id) == true && !empty($result->rate) ){
				
				foreach (json_decode($result->rate) as $key => $value) {
					if( 
						(int)$value->rate_first < (int)$distance &&
						(int)$value->rate_second > 	(int)$distance
					){
						
						$price = (int)$value->rate_three;
					}
				}
			}

			if( is_numeric($airport_id) == false && !empty($result->rate_fixed) ){		
				foreach (json_decode($result->rate_fixed) as $k => $val) {
					if($val->airport_to == $airport_id){	
						$price = (int)$val->rate;
					}
				}
			}
			
		}
		else{
			$price = (float)$distance * $price;
		}

		$passenger_ids 		= '('.(string)$data['passenger_idArr'].')';
		// $numbers			= count(explode(',', (string)$data['passenger_idArr']));
		//service_id = 3 is bus service in table #__sfs_services
		
		$query->update('#__sfs_passenger_service')
			->set('price_per_person = ' .$price)
			->where('passenger_id IN '.$passenger_ids.' AND service_id = 3');
		$db->setQuery($query);
		$result = $db->execute();

		return $price;

	}



	public function getRateBus($group_transportation_types_id){
		$db 			= $this->getDbo();
		$query 			= $db->getQuery(true);
		$query->select('rate')
			->from('#__sfs_group_transportation_types')
			->where('id = '.(int)$group_transportation_types_id);

		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	public function updateBlockCode($serviceId,$passenger_idArr){

		$blockcode 		= $this->switchBlockCode((int)$serviceId);

		$db 			= $this->getDbo();
		$query 			= $db->getQuery(true);
		$result = 0;


		foreach ($passenger_idArr as  $value) {
			$query->clear();
			$query->update('#__sfs_passenger_service')
				->set('vouchercodes = "'.$this->randomString(2).'"')
				->where('service_id = '.$serviceId.' AND passenger_id = '.$value);
			$db->setQuery($query);
			$result = $db->execute();
		}
		
		return $result;	
		
	}

	public function randomString($length = 6) {
		$str = "";
		$characters = array_merge(range('A','Z'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}
	
	// end CPhuc

	public function getAirportBus(){
		$airlile = SFactory::getAirline();

		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);

		$query = 'SELECT b.rate_fixed FROM #__sfs_group_transportation_airports AS a ';
		$query  .= ' LEFT JOIN #__sfs_group_transportation_types AS b ON b.group_transportation_id = a.group_transportation_id';
		$query .= ' WHERE a.airport_id = '. $airlile->airport_id . ' AND b.rate_fixed != "" ';
		$db->setQuery($query);
		$result = $db->loadObjectList();

		$arr = array();
		foreach ($result as $key => $value) {
			$airportBus = $value->rate_fixed;
			
			foreach (json_decode($airportBus) as $k => $val) {
				$row = new stdClass();
				if( $val->airport_from == $airlile->airport_code){
					$row->airport = $val->airport_to;

					if(!empty($arr)){
						foreach ($arr as $key => $value) {
							if($value->airport != $row->airport){
								array_push($arr, $row);
							}
						}
					}else{
						array_push($arr, $row);
					}										
				}
			}
		}
		 // print_r($arr);
		 // die();

		return $arr;
	}
}