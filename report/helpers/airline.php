<?php
defined('_JEXEC') or die;

abstract class airlineReportHelper
{		
	
	public static function getDate( $input = 'now' , $format = 'Y-m-d', $timeZone )
	{		
		$date = JFactory::getDate($input, 'UTC');
				
		$date->setTimeZone(new DateTimeZone($timeZone));			
			
		return $date->format($format, true);
	}
	
	public static function getVar($input=null, $config)
	{	
		$result = null;
		
		if( empty($input) )
		{
			return $result;
		}
		
		$input = trim($input);
		
		$current_day_pos = JString::strpos($input, 'current_day');
		
		$now = self::getDate('now','Y-m-d',$config->offset);

		if( is_int($current_day_pos) )
		{
			if( $input == 'current_day' )
			{				
				$result =  $now;
			} 
			else
			{
				$array = explode('-', $input);
				if( $array[1] )
				{
					$result = strtotime($now);
					$result = date( 'Y-m-d' , strtotime('-'.(int)$array[1].' day', $result) );
				}
			}
		} 
		else
		{
			if( is_numeric($input) && strlen($input)==8)
			{
				$y = JString::substr($input, 0,4);
				$m = JString::substr($input, 4,2);
				$d = JString::substr($input, 6,2);
				$result = $y.'-'.$m.'-'.$d;
			}
		}
		return $result;		
	}
	
	public function getDataNewReportAirline( $dateFrom, $dateTo, $airport_code, $user )
	{
		
		$airline = self::getAirline( $user->id );
		$airline_id = $airline->id;
		$currency_code = self::getCurrencyCode( $airline->airport_id )->currency_code;
		
		$db 	= JFactory::getDbo();	
		$result = null;
		$query = $db->getQuery(true);
		
		$query->select(
		'vc.sroom as s_room,vc.sdroom as sd_room,vc.troom as t_room,vc.qroom as q_room, a.ws_room, 
		a.sd_rate,a.t_rate,a.s_rate,a.q_rate,
		a.blockdate as date, a.breakfast,a.lunch,a.mealplan,
		a.blockcode,
		a.airport_code,
		a.status,
		h.name as hotel_name'
		);
				
		$query->from('#__sfs_reservations AS a');

		//$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');

        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
		
		$query->select(
		'fl.flight_code as flight_number'
		);
		
		$query->select(
		'(
		SELECT COUNT(tra.id) From #__sfs_trace_passengers as tra Where tra.voucher_id = vc.id Group BY tra.voucher_id) AS people_num 
		 '
		);
		
		///$query->innerJoin('#__sfs_voucher_codes AS vc ON a.id = vc.booking_id');
		$query->leftJoin('#__sfs_voucher_codes AS vc ON a.id = vc.booking_id');
		$query->leftJoin('#__sfs_flights_seats AS fl ON vc.flight_id = fl.id');
		///$query->innerJoin('#__sfs_flights_seats AS fl ON vc.flight_id = fl.id');
		
		/*$query->select(
		'hmea.lunch_standard_price as gross_price_lunch,
		hmea.bf_standard_price as gross_price_bfst,
		hmea.course_1 as gross_price_dinner'
		);
		$query->leftJoin('#__sfs_hotel_mealplans AS hmea ON hmea.hotel_id = h.id');
		*/
		///$query->innerJoin('#__sfs_hotel_mealplans AS hmea ON hmea.hotel_id = h.id');
		
		
		///echo (string)$query;die;
		$query->where('a.airline_id='.$airline_id);
		$query->where('a.status !="D"');
		/*if( $airline->grouptype==3 )
    	{	    		
    		$gh_airline = (int)JRequest::getInt('gh_airline');
    		$query->innerJoin('#__sfs_gh_reservations AS ghr ON ghr.reservation_id=a.id');
    		
    		if($gh_airline > 0){
    			$query->where('ghr.airline_id='.$gh_airline);
    		}
			$query->select('ic.name AS gh_airline');
    		$query->innerJoin('#__sfs_iatacodes AS ic ON ic.id=ghr.airline_id');
    		
    	}*/
        /*if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }*/
		
		//$query->where('b.date >='.$db->Quote($dateFrom) );
		///$query->where('b.date <='.$db->Quote($dateTo));
		$query->where('a.blockdate >='.$db->Quote($dateFrom) );
		$query->where('a.blockdate <='.$db->Quote($dateTo));
		if ( !empty( $airport_code ) ) {
			$query->where('a.airport_code="' . strtoupper( trim($airport_code) ) . '"');	
		}
		//$query->group('a.blockcode');
		$query->order('a.id ASC');
		///echo (string)$query;die;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;	
		
		/*$query->select(
		'SUM(
		a.s_room+a.sd_room+a.t_room+a.q_room
		) 
		AS rooms, 
		SUM(
		a.sd_rate+a.t_rate+a.s_rate+a.q_rate
		) 
		AS gross_price,
		a.airport_code, 
		b.date,
		a.blockcode,
		a.status,
		h.name as hotel_name'
		);
				
		$query->from('#__sfs_reservations AS a');

		$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');

        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
		
		$query->select(
		'fl.flight_code as flight_number'
		);
		
		$query->select(
		'(
		SELECT COUNT(tra.id) From #__sfs_trace_passengers as tra Where tra.voucher_id = vc.id Group BY tra.voucher_id) AS people_num 
		 '
		);
		
		$query->innerJoin('#__sfs_voucher_codes AS vc ON a.id = vc.booking_id');
		$query->innerJoin('#__sfs_flights_seats AS fl ON vc.flight_id = fl.id');
		
		$query->select(
		'hmea.lunch_standard_price as gross_price_lunch,
		hmea.bf_standard_price as gross_price_bfst,
		hmea.course_1 as gross_price_dinner'
		);
		$query->innerJoin('#__sfs_hotel_mealplans AS hmea ON hmea.hotel_id = h.id');
		
		
		
		
		$query->where('a.airline_id='.$airline_id);	
		
		if ( !empty( $airport_code ) ) {
			$query->where('a.airport_code="' . strtoupper( trim($airport_code) ) . '"');	
		}
		
		$query->where('b.date>='.$db->Quote($dateFrom) );
		$query->where('b.date<='.$db->Quote($dateTo));
				
		$query->group('a.id');
		$query->order('a.id ASC');
		//echo (string)$query;die;
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;	*/
	}
	
	public static function getCurrencyCode( $airline_id = 0 )
	{
		$db 		= JFactory::getDbo();		
		$result = null;
		$query = $db->getQuery(true);
		$query->select('currency_code');
		$query->from('#__sfs_iatacodes AS a');
		$query->where('a.id='.$airline_id);	
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;	
	}
	
	public static function FormatCurrency( $objPHPExcel, $xy, $FORMAT_CURRENCY){
		$objPHPExcel->getActiveSheet()
			->getStyle($xy)
			->getNumberFormat()
			->setFormatCode(
				$FORMAT_CURRENCY
			);
	}
	
	public static function getAirline($id) {
    	$db 	= JFactory::getDbo(); 
    	$query = $db->getQuery(true);
    	$query->select('a.*, c.name, c.code, c.currency_code');
    	$query->from('#__sfs_airline_details AS a ');
		$query->innerJoin('#__sfs_airline_user_map AS b ON b.airline_id=a.id');
		$query->innerJoin('#__sfs_iatacodes AS c ON c.id=a.airport_id');
    	$query->where('b.user_id="'.$id . '"');
    	$db->setQuery($query);
		$d = $db->loadObject();
		$AirlineName = self::getAirlineName( $d->iatacode_id );
		$d->name = $AirlineName;
    	return $d;
    	
    }
	
	public static function getAirlineName( $iatacode_id )
	{
		if( empty($iatacode_id) ) {
			return null;
		}


		$db = JFactory::getDbo();

		$query = 'SELECT name FROM #__sfs_iatacodes WHERE id = '.(int)$iatacode_id.' AND type = 1';

		$db->setQuery($query);

		$result = $db->loadResult();
		return $result;
	}
	
	public static function numberformat( $num = 0 ) {
		return number_format( $num, 2, ".",",");
	}
	//End lchung
	
}


