<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelAccounting2reports extends JModel
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
		
		$filter_date 		=  ($this->getState('filter_date') == '') ? date('Y-m-d') : $this->getState('filter_date');
		$filter_until_date 	=  ( $this->getState('filter_until_date') == '' ) ? date('Y-m-d 23:59:59') : $this->getState('filter_until_date') . ' 23:59:59';

		$service_type 	=  $this->getState('service_type');
		$query 	= $db->getQuery(true);
		$query->select('p.voucher_id, p.id AS passenger_id, p.first_name, p.last_name, p.phone_number, p.invoice_number,p.comment as p_comment,p.insurance,p.touroperator_client,p.invoice_status' );
		
        $query->from('#__sfs_trace_passengers AS p');
		$query->select('pa.id, pa.airline_id, pa.startdate, pa.expiredate, pa.blockcode, pa.airport_code, pa.flight_number,
		pa.hotel_id, pa.pnr, pa.airplus_id, pa.airplus_mealplan, pa.airplus_taxi, pa.airplus_cash, pa.airplus_phone
		');
		
		$query->leftJoin('#__sfs_passengers_airplus AS pa ON pa.id=p.airplus_id');
		
		$query->select('car.cvc AS CVC_car, car.card_number AS card_number, car.value as car_value, car.valid_thru as car_valid_thru, car.unique_id');
		$query->leftJoin('#__sfs_airplusws_creditcard_detail AS car ON car.airplus_id=pa.id ');
		
		$query->select('e.status as reservations_status,e.sd_room,e.t_room,e.s_room,e.q_room, e.sd_rate, e.t_rate, e.s_rate, e.q_rate,e.url_code');
		$query->select('d.status, e.blockcode, d.code AS voucher_code,d.comment,d.breakfast,d.lunch,d.mealplan, d.flight_id');
		$query->leftJoin('#__sfs_voucher_codes AS d ON d.id=p.voucher_id');
		$query->leftJoin('#__sfs_reservations AS e ON e.id=d.booking_id');

        $query->select('h.name AS hotel_name,h.telephone AS hotel_phone');
        $query->leftJoin('#__sfs_hotel AS h ON h.id=e.hotel_id');
		
		$query->select('pad.purchase_date, pad.service_desc, pad.supplier, pad.ticket_number');
		$query->leftJoin('#__sfs_passengers_airplus_data AS pad ON pad.dbi_au=car.unique_id');
		$query->where('pa.airline_id='.(int)$airline->id." AND p.created_date >= ".$db->quote($filter_date)." AND p.created_date <=".$db->quote($filter_until_date));
		
		switch( $service_type ){
			case"mealplan":
			$query->where('pa.airplus_mealplan = 1 ');
			break;
			case"taxi":
			$query->where('pa.airplus_taxi = 1 ');
			break;
			case"cash":
			$query->where('pa.airplus_cash = 1 ');
			break;
			case"telephone":
			$query->where('pa.airplus_phone = 1 ');
			break;
		}
		
		if ( $export_withID != NULL ) {
			$query->where("p.id IN( $export_withID )");
		}
		$query->where("e.status='A'");//loc theo tinh trang Approved
		$query->where("e.ws_room IS NULL");//loc theo tinh trang khong thuoc WS
		///$query->group('p.airplus_id');
		///$query->group('p.voucher_id');
		$query->group('p.id');
		///echo (string)$query;die;
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$rowsN = array();
		foreach ( $rows as $vk => $v ) {
			$is_not_user_sevice = 0;
			if( $v->airplus_mealplan == 0 && $v->airplus_taxi == 0 && $v->airplus_cash == 0 && $v->airplus_phone == 0 ) {
				$is_not_user_sevice = 1;
				$v->airport_code = $airline->airport_code;
				$v->flight_number = $this->getFlightsCode( $v->flight_id );
			}
			$v->pax = $this->getTracePassengerCount( $v->voucher_id, $v->id, $is_not_user_sevice);
			$value_hotel = ($v->sd_room * floatval( $v->sd_rate) ) + 
								($v->t_room * floatval( $v->t_rate ) ) +
								($v->s_room * floatval( $v->s_rate ) ) +
								($v->q_room * floatval( $v->q_rate ) ) ;
			$v->value_hotel = $value_hotel;
			$v->total_expected 	= $value_hotel;
			$v->total_spent		= $value_hotel;
			$total_airplus_expected = $this->getExpectedAirplusAmount($v->id);
			$v->total_airplus_expected  = $total_airplus_expected;
			$v->total_expected  += $v->total_airplus_expected;
			
			$count_amount = 0;
			$v->claimed = $value_hotel;
			$v->issued = $total_airplus_expected;
			if( (int)$v->voucher_id > 0 ){
				
				$v->value_hotel = $value_hotel;
				
				$meal_amount = 0;
				$taxi_amount = 0;
				$tmpAmount = 0;
				
				$uniqueKeys = array();
				
				foreach($v->pax as $vkS => $vS) {
					$uniqueKeys[] = $vS->meal_unique_id;
					$uniqueKeys[] = $vS->taxi_unique_id;
				}
				
				$uniqueKeys = array_unique($uniqueKeys);
				
				foreach ( $uniqueKeys as $uniqueID ) {
					$tmpAmount += floatval( $this->getAmount($count_amount, $uniqueID) );
				}
				$v->claimed = $tmpAmount;
				$v->value_hotel += $tmpAmount;
				$v->total_spent += $tmpAmount;
			}
			else {
				$val = $this->getAmount($count_amount, $v->unique_id);//->amount;
				$v->claimed = $val;
				$v->value_hotel += $val;
				$v->total_spent += $val;
				//print_r( $airline );
				
			}
			$v->count_amount = $count_amount;
		}
		return $rows;// $rows;
		
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
}