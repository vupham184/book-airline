<?php
defined('_JEXEC') or die;

class SfsLog extends JObject
{			
	
	public function __construct( $properties = null )
	{
		parent::__construct($properties);
	}	
	
	public static function insert($type, $group_id, $userId = null)
	{	
		if( $userId == null )
		{
			$userId = JFactory::getUser()->id;
		}
		$db = JFactory::getDbo();
		$query  = 'INSERT INTO #__sfs_logs(type,group_id,user_id,date)';
		$query .= ' VALUES('.$db->quote($type).','.(int)$group_id.','.$userId.','.$db->quote(JFactory::getDate()->toSql()).')';
		
		$db->setQuery($query);
		$db->query();
	}
	
	public static function printf($type, $hotel = null)
	{		
		if($hotelId == null)
		{
			$hotel 	 = SFactory::getHotel();
			///$hotelId = $hotel->id;	
		}		
		$db 	= JFactory::getDbo();
		
		$query 	= 'SELECT a.*, u.name FROM #__sfs_logs AS a';
		$query .= ' INNER JOIN #__users AS u ON u.id = a.user_id';
		$query .= ' WHERE a.type='.$db->quote($type).' AND a.group_id='. $hotel->id;
		$query .= ' ORDER BY a.date DESC';
		
		$db->setQuery($query,0,1);
		
		$data = $db->loadObject();
					
		switch ($type)
		{
			case 'roomloading':
				self::roomloading($data, $hotel);
				break;
			default:
				break;
		}
				
	}
	
	private static function roomloading($data, $hotel)
	{
		if( is_object($data) )
		{
			$time_zone = $hotel->time_zone;
			if ( $time_zone == '' ) {
				$time_zone = SAirline::getTimezoneInIatacodes( $hotel->id );
			}
			$date = SfsHelperDate::getDate('now', "d/m/Y", $time_zone);
			$time = SfsHelperDate::time('now', $time_zone);
			//echo $data->date;
			$d = $date . ' ' . $time;
			//echo '<p style="text-align:right;">Last change made by '.$data->name.' on '.JHtml::_('date',$data->date,'d/m/Y H:i').'</p>';
			echo '<p style="text-align:right;">Last change made by '.$data->name.' on '. $d .'</p>';
			
		}
	}
		
}

