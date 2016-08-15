<?php
defined('_JEXEC') or die;
		
class SfsModelReportforhotelavailability extends JModelLegacy
{
	
	public function getData( $from, $until )
	{
		
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*, b.distance, c.code,c.type, d.ws_id ');
			
			$query->from('#__sfs_reservations as a');
			///$query->leftJoin('...');
			$query->innerJoin('#__sfs_hotel_airports as b On a.hotel_id = b.hotel_id ');
			$query->innerJoin('#__sfs_iatacodes as c On c.id = b.airport_id And c.type = 2 And a.airport_code = c.code ');
			$query->innerJoin('#__sfs_hotel as d On d.id = a.hotel_id ');
			
			if ( $until != '' && $from != '' ) {
				$until = date('Y-m-d');
			}
			if ( $from != '' ) { 
				$query->where('a.blockdate >='.$db->Quote($from) );
				$query->where('a.blockdate <='.$db->Quote($until));
			}
			
			///$query->order('name ASC');
			
			$db->setQuery($query);
			
			$d = $db->loadObjectList();
		return $d;
	}
	
	public function getAirportCode( $from, $until )
	{
		
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.airport_code');
			
			$query->from('#__sfs_reservations as a');
			///$query->leftJoin('...');
			$query->innerJoin('#__sfs_hotel_airports as b On a.hotel_id = b.hotel_id ');
			$query->innerJoin('#__sfs_iatacodes as c On c.id = b.airport_id And c.type = 2 And a.airport_code = c.code ');
			$query->innerJoin('#__sfs_hotel as d On d.id = a.hotel_id ');
			
			if ( $until != '' && $from != '' ) {
				$until = date('Y-m-d');
			}
			if ( $from != '' ) { 
				$query->where('a.blockdate >='.$db->Quote($from) );
				$query->where('a.blockdate <='.$db->Quote($until));
			}
			$query->group('a.airport_code');
			
			//echo (string)$query;
			//die;
			$db->setQuery($query);
			
			$d = $db->loadObjectList();
		return $d;
	}
	
	
	public function getLowestHotelRateWS( $from, $until, $hotel_id )
	{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('MIN(a.price) as price ');
			$query->from('#__availability_totalstay_hotel_searches as a');
			if ( $until != '' && $from != '' ) {
				$until = date('Y-m-d');
			}
			if ( $from != '' ) { 
				$query->where('a.date >='.$db->Quote($from) );
				$query->where('a.date <='.$db->Quote($until));
			}
			$query->where('a.hotel_id IN('. $hotel_id . ')' );
			//echo (string)$query.'<br>';
			//die;
			$db->setQuery($query);
			$d = $db->loadObject();
		return $d;
	}
	
	public function getHighestHotelRateWS( $from, $until, $hotel_id, $minmax = "MAX" )
	{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select("$minmax(a.sd_rate) as sd_rate, $minmax(a.s_rate) as s_rate");
			$query->from('#__sfs_reservations as a');
			if ( $until != '' && $from != '' ) {
				$until = date('Y-m-d');
			}
			if ( $from != '' ) { 
				$query->where('a.date >='.$db->Quote($from) );
				$query->where('a.date <='.$db->Quote($until));
			}
			$query->where('a.hotel_id IN('. $hotel_id . ')' );
			//echo (string)$query.'<br>';
			//die;
			$db->setQuery($query);
			$d = $db->loadObject();
		return $d;
	}
	
	
}