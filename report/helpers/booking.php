<?php
defined('_JEXEC') or die;

abstract class bookingReportHelper
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
	
	public static function getReservations($start_period,$end_period,$airline_id=0)
	{						
		$result = array();
		
    	$db 	= JFactory::getDbo();    	
    	$query  = $db->getQuery(true);

    	$query->select('a.*, c.name as airline_name, c.code as airline_code
    	, d.name as hotel_name');
    	$query->from('#__sfs_reservations AS a');
        $query->innerJoin('#__sfs_airline_details AS b ON b.id = a.airline_id');
        $query->innerJoin('#__sfs_iatacodes AS c ON c.id = b.iatacode_id');
        $query->innerJoin('#__sfs_hotel AS d ON d.id = a.hotel_id');

        if ($airline_id > 0)
        {
            $query->where('a.airline_id ='.$airline_id);
        }
    	$query->where('a.blockdate >='.$db->Quote($start_period));
		$query->where('a.blockdate <='.$db->Quote($end_period));
        $query->order('a.id DESC');
    	$db->setQuery($query);

    	$rows = $db->loadObjectList();

        $result = $rows;

    	return $result;
	}
	
	public static function getDates($start_period, $end_period)
	{				
    	$db 	= JFactory::getDbo();    	
    	$query  = $db->getQuery(true);
    	
    	$query->select('DISTINCT a.date');    	
    	$query->from('#__sfs_room_inventory AS a');
    	
    	$query->where('a.date >='.$db->Quote($start_period));
		$query->where('a.date <='.$db->Quote($end_period));		 

		$query->order('a.date ASC');

        $db->setQuery($query);
    	
    	$rows = $db->loadResultArray();
        
    	return $rows;
	}
	
	public static function getTotalRoomsByDates($inventories, $roomType = 'SD' )
	{
		$result = array();
		if( count($inventories) )
		{
			foreach ($inventories as $inventory)
			{
				if( isset($inventory->dates) && count($inventory->dates) )
				{
					foreach ( $inventory->dates as $date=>$item )
					{
						if( ! isset($result[$date]) )
						{
							$result[$date] = 0;
						}
						if($roomType == 'SD')
						{
							$result[$date] = $result[$date] + (int)$item->sd_room_total + (int)$item->booked_sdroom;	
						} else{
							$result[$date] = $result[$date] + (int)$item->t_room_total + (int)$item->booked_troom;
						}
						
					}
				}
			}
		}
		
		return $result;
	}
	
	public static function getAvgRateByDates($inventories, $roomType = 'SD' )
	{		
		$result = array();
		if( count($inventories) )
		{
			$rateTotal = array();
			$rateCount = array();
			foreach ($inventories as $inventory)
			{
				if( isset($inventory->dates) && count($inventory->dates) )
				{
					foreach ( $inventory->dates as $date=>$item )
					{			
						if( ! $rateTotal[$date] )
						{
							$rateTotal[$date] = 0;
							$rateCount[$date] = 0;
						}			
						if($roomType == 'SD' && $item->sd_room_rate > 0)
						{
							$rateTotal[$date] = $rateTotal[$date] + $item->sd_room_rate;
							$rateCount[$date]++;
						} 
						
						if($roomType == 'T' && $item->t_room_rate > 0)
						{
							$rateTotal[$date] = $rateTotal[$date] + $item->t_room_rate;
							$rateCount[$date]++;
						} 
					}
				}
			}
			
			if( count($rateTotal) )
			{
				foreach ($rateTotal as $date=>$value)
				{
					$result[$date] = number_format( $value / $rateCount[$date] ,2);
				}
			}
			
		}
		
		return $result;
	}
	
	//lchung
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
	//End lchung
	
}


