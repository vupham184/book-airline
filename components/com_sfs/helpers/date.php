<?php
defined('_JEXEC') or die();

abstract class SfsHelperDate {

	//for hotel search page
	public static function getSearchDate( $offset='start' , $style='class="inputbox"', $id = null, $textFormat = null )
	{
		$airline = SFactory::getAirline();
		
		$params = JComponentHelper::getParams('com_sfs');
    	
    	$allowClean = false;
    	
    	$cleanTime = trim($params->get('match_hours'));	
		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$cleanTime = $setup_airport['hours_on_match_page'];
		}
		//End lchung
    	    	
		$is_next_day = false;
		
		$airline_current = SAirline::getInstance()->getCurrentAirport();
        //$session = JFactory::getSession();
        //$time_zone = $session->get("time_zone");
		//lchung
		$time_zone = $airline_current->time_zone;
		//End lchung
		if( strlen($cleanTime) > 0 )
		{
			$nowTime = SfsHelperDate::getDate('now','H:i',$time_zone);
			
			$nowTime = explode(':', $nowTime);
			
			$cleanTime = explode(':', $cleanTime);
			
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
		
		$now = SfsHelperDate::getDate('now','Y-m-d',$time_zone);
		
		if( ! $is_next_day )
		{
			$now = self::getPrevDate('Y-m-d', $now);
		}
		
		
		// current month,year
		
		list($year,$month,$day) = explode('-', $now);
		//total day of this current month
		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		$date = JFactory::getDate('now',$time_zone);
		
		$selectedValue = '';
		$dateList = null;

		if($offset=='start') {
			$selectedValue = JHTML::_('date',$now,JText::_('DATE_FORMAT_LC3') );
			$dateList = self::getNextDates($now, 30);
		} else if($offset=='start_prev'){
			$prevDate = self::getPrevDate( JText::_('DATE_FORMAT_LC4'), $now );
			$dateList = self::getNextDates( $prevDate, 30);
			$selectedValue = JHTML::_('date',$now,JText::_('DATE_FORMAT_LC3') );
		}else if($offset=='end'){
			$nextDate = self::getNextDate( JText::_('DATE_FORMAT_LC4'), $now );
			$dateList = self::getNextDates( $nextDate, 30);
			$selectedValue = SfsHelperDate::getNextDate(JText::_('DATE_FORMAT_LC3'),$now);
		}else if($offset=='end_prev'){
			$dateList = self::getNextDates($now, 30);
			$selectedValue = SfsHelperDate::getNextDate(JText::_('DATE_FORMAT_LC3'),$now);
		} else if($offset == 'expire') {
			$selectedValue = JHTML::_('date',$now,JText::_('DATE_FORMAT_LC3') );
			$dateList = self::getNextDates($now, 30);
		}


		if( $id ) {
			$return = '<select id="'.$id.'" name="'.$id.'" '.$style.'>';	
		} else {
			$return = '<select id="date_'.$offset.'" name="date_'.$offset.'" '.$style.'>';
		}	
					
		foreach ($dateList as $key => $value) {
			$selected = '';
			$dateStr = JHTML::_('date', $value , JText::_('DATE_FORMAT_LC3'), false );
			
			if( $dateStr == $selectedValue ) $selected = ' selected="selected"';
				
			$return .= '<option value="'.$key.'"'.$selected.'>';
			if($textFormat==null)
			{
				$return .= $dateStr;	
			} else {
				$return .= JHTML::_('date', $value , $textFormat, false );
			}
			
			$return .= '</option>';						
		}
		$return .= '</select>';	
				
		
		
		return $return;
	}

	public static function getNextDate ( $format,$inputDate ) {
				 
		$result = strtotime($inputDate);
		
		if($result !== false){
	  		return date( $format , strtotime('+1 day', $result) );
		}			
		return null;			
	}
	
	public static function getPrevDate ( $format,$inputDate ) {
				 
		$result = strtotime($inputDate);
		
		if($result !== false){
	  		return date( $format , strtotime('-1 day', $result) );
		}			
		return null;			
	}
	
	public static function getNextDates($date,$days)
	{
		$result = array();		
		
		$result[(string)$date] = $date;
		$prev = $date;
		
		for ($i=1;$i<$days;$i++) {
			$value = $key = (string) self::getNextDate( JText::_('DATE_FORMAT_LC4'),$prev);
			$result[$key] = $value;
			$prev = $value;
		}		
		
		return $result;
	}
		
		
	public static function getSelectTimeField($hSelected=null,$mSeleced=null,$apSelected=null)
	{
		$result = array();
		
		for ($i=0;$i<3;$i++) {
			$result[$i] = new stdClass();
			$result[0]->html = '';
		}
		
		for($i=1;$i<=24;$i++) {		
			if( $i == $hSelected ){
				$result[0]->html .= '<option value="'.$i.'" selected="selected">'.$i.'</option>' ;
			}else{
				$ii = $i;
				if( $i < 10 )
					$ii = '0'.$i;
				$result[0]->html .= '<option value="'.$ii.'">'.$ii.'</option>' ;
			}
		}
		$i=0;
		while ( $i <= 59 ){			
			$current = ( $i < 10 ) ? '0'.$i : $i;
			if( (string)$current == $mSeleced ){
				$result[1]->html .= ( $i < 10 )? '<option value="0'.$i.'" selected="selected">0'.$i.'</option>' : '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			} else {
				$result[1]->html .= ( $i < 10 )? '<option value="0'.$i.'">0'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>' ;
			}								
			$i += 1;
		}
		if( $apSelected == 'AM' ) {
			$result[2]->html = '<option value="AM" selected="selected">AM</option><option value="PM">PM</option>';	
		} else {
			$result[2]->html = '<option value="AM">AM</option><option value="PM" selected="selected">PM</option>';
		}
		
		return $result;
							
	}
	
	public static function getSelect24TimeField($hSelected=null,$mSeleced=null)
	{
		$result = array();
		
		for ($i=0;$i<2;$i++) {
			$result[$i] = new stdClass();
			$result[0]->html = '';
		}
		
		for($i=0;$i<=23;$i++) {		
			if( $i == $hSelected ){
				$result[0]->html .= '<option value="'.$i.'" selected="selected">'.$i.'</option>' ;
			}else{
				$result[0]->html .= '<option value="'.$i.'">'.$i.'</option>' ;
			}
		}
		$i=0;
		while ( $i <= 59 ){			
			$current = ( $i < 10 ) ? '0'.$i : $i;
			if( (string)$current == $mSeleced ){
				$result[1]->html .= ( $i < 10 )? '<option value="0'.$i.'" selected="selected">0'.$i.'</option>' : '<option value="'.$i.'" selected="selected">'.$i.'</option>';
			} else {
				$result[1]->html .= ( $i < 10 )? '<option value="0'.$i.'">0'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>' ;
			}								
			$i += 1;
		}		
		return $result;
							
	}	
	
	public static function time( $input = 'now' , $timezone = null )
	{
		$config = JFactory::getConfig();
		$date = JFactory::getDate($input, 'UTC');
		if (  $timezone  ) {			
			$date->setTimeZone( new DateTimeZone($timezone) );						
		} else {			
			$date->setTimeZone(new DateTimeZone($config->get('offset')));			
		}			
		
		return $date->format('H:i', true);
	}
	
	public static function getDate( $input = 'now' , $format = 'Y-m-d H:i', $timezone = null )
	{
		if ( empty($format)) {
			$format = 'Y-m-d H:i';
		}
		$config = JFactory::getConfig();
		$date = JFactory::getDate($input, 'UTC');
		if (  $timezone  ) {					
			$date->setTimeZone( new DateTimeZone($timezone) );						
		} else {			
			$date->setTimeZone(new DateTimeZone($config->get('offset')));			
		}			
		
		return $date->format($format, true);
	}	
	public static function getMySqlDate( $input = 'now',$timezone = null )
	{
		$format = 'Y-m-d H:i:s';
		$config = JFactory::getConfig();
		$date = JFactory::getDate($input, 'UTC');
		if (  $timezone  ) {					
			$date->setTimeZone( new DateTimeZone($timezone) );						
		} else {			
			$date->setTimeZone(new DateTimeZone($config->get('offset')));			
		}			
		
		return $date->format($format, true);
	}		
		
}

