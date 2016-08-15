<?php
defined('_JEXEC') or die();
 
class SfsControllerRoomloading extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('rank',			'calculateRanking');
		$this->registerTask('nrank',		'calculateNumberRanking');
		$this->registerTask('rcheck',		'checkRoomsPrices');
	}
	
	/**
	 * Ajax method to calculate hotel marketplace ranking	 
	 * 
	 */
	public function calculateRanking()
	{		
		require_once JPATH_COMPONENT.'/libraries/ranking.php';
		
		$date 		= JRequest::getVar('date');
		$rate 		= JRequest::getVar('rate');
		$rtype 		= JRequest::getVar('rtype');
		$transport  = JRequest::getVar('transport');
				
		$ranking = new SRanking( 
			array(
				'date' =>  $date, 'price' => $rate, 'roomtype' => $rtype, 'transport' => $transport				
			)
		);
		
		$result = $ranking->checkRankingBy('rate');
		
		if( $result !== null ) echo (int) $result;
		
		JFactory::getApplication()->close();				
	}
	
	
	/**
	 * Ajax method to calculate hotel marketplace room total ranking	 
	 * 
	 */
	public function calculateNumberRanking()
	{		
		require_once JPATH_COMPONENT.'/libraries/ranking.php';
		
		$date 		= JRequest::getVar('date');
		$roomNumber = JRequest::getVar('rate');
		$rtype 		= JRequest::getVar('rtype');
		$transport 	= JRequest::getVar('transport');		
		
		$ranking = new SRanking( 
			array(
				'date' =>  $date, 'number_rooms' => $roomNumber, 'roomtype' => $rtype, 'transport' => $transport				
			)
		);
		
		$result = $ranking->checkRankingBy('total');
		
		if( $result !== null ) echo (int) $result;
		
		JFactory::getApplication()->close();		
	}	
	
	
	/**
	 * Ajax method to check the prices are saved or not
	 */
	public function checkRoomsPrices () 
	{		
		// Gets the hotel
		$hotel = SFactory::getHotel();		
		$id = JRequest::getVar('id');
		
		if( $hotel->id == (int)$id ) {
			
			$params = JComponentHelper::getParams('com_sfs');
			$cleanTime = trim($params->get('match_hours'));	
			//lchung
			$setup_airport = (array)JFactory::getSession()->get('setup_airport');
			if ( !empty( $setup_airport ) ) {
				$cleanTime = $setup_airport['hours_on_match_page'];
			}
			//End lchung
			
			$is_next_day = false;
			
			if( strlen($cleanTime) > 0 )
			{
				$cleanTime = explode( ':' , $cleanTime);	
				
				$nowTime = SfsHelperDate::getDate('now','H:i',$hotel->time_zone);	
				if ( trim($hotel->time_zone) == '' ) {
					$hotel->time_zone = SAirline::getTimezoneInIatacodes( $hotel->id );
					$nowTime = SfsHelperDate::getDate('now','H:i',$hotel->time_zone);
				}	
				
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
			
			$now = SfsHelperDate::getDate('now','Y-m-d',$hotel->time_zone);
			
			if( ! $is_next_day )
			{
				$now = SfsHelperDate::getPrevDate('Y-m-d', $now);
			}	
			
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('COUNT(*)');		
			$query->from('#__sfs_room_inventory AS a');		
					
			$query->where('a.hotel_id = '.$id.' AND a.date='.$db->Quote($now));
	
			$db->setQuery($query);
			
			echo (int) $db->loadResult();
			die;		
		} else {
			echo '0';
			die;
		}
		
	}
	
}	