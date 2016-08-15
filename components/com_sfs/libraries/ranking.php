<?php
// No direct access
defined('_JEXEC') or die;

class SRanking extends JObject
{
	
	protected $date = null;
	
	protected $price = null;
	
	protected $number_rooms = null;
	
	protected $roomtype = null;
	
	protected $transport = null;
	
	protected $created = null;
	
	public function __construct( $properties = null ){		
		parent::__construct($properties);		
	}
	
	
	public function checkRankingBy( $type = 'rate' )
	{
		$user = JFactory::getUser();
		
		if( ! SFSAccess::isHotel($user) )
		{
			return null;
		}
		
		$ranking = $this->calculateRanking($type);
		
		
		return $ranking;
	}
	
	protected function calculateRanking($type)
	{			
		$hotel = SFactory::getHotel();		
		$db = JFactory::getDBO();
		
		$params = JComponentHelper::getParams('com_sfs');
		
		$db->setQuery('SELECT created FROM #__sfs_room_inventory WHERE hotel_id = '.$hotel->id.' AND date = '.$db->quote($this->date));

		$this->created = $db->loadResult();
		
		$query = $db->getQuery(true);
		
		$query->select('COUNT(*)');		
		$query->from('#__sfs_room_inventory AS a');		
		$query->innerJoin('#__sfs_hotel AS h ON h.id = a.hotel_id');
										
		$levelOption = (int) $params->get('rangking_level_option');
		
		$this->setStarLevelQuery( $query, $hotel->star, $levelOption);
		
		$query->where('a.date='.$db->Quote($this->date));
		
		if( (int)$params->get('ranking_location') ){
			$query->where('h.location_id='.(int)$hotel->location_id);
		}
		
		if( (int)$params->get('ranking_transport') ){
			$query->where('a.transport_included='.(int)$this->transport);	
		}				
		
		$airport = $hotel->getAirportIDs(true);
				
		if((int)$airport > 0 ) {
			$query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id = a.hotel_id AND airport_id='.$airport);			
		}
		
		$query->where('a.hotel_id <> '.$hotel->id);
		
		if( $type == 'rate' ){
			$this->conditionRateQuery($query);	
		} else {
			$this->conditionTotalQuery($query);
		}
		
		$db->setQuery($query);		
		$result = (int)$db->loadResult() + 1;
				
		$this->updateRanking($type, $result);
									
		return $result;				
	}
	
	protected function conditionRateQuery( & $query )
	{
		$db = JFactory::getDbo();
		if( $this->roomtype == 'sd' ) 
		{
			$query->where('a.sd_room_total > 0');			
			$query->where('(a.sd_room_rate_modified < '. floatval($this->price).' OR a.sd_room_rate_modified = '.floatval($this->price).' )' );
		} 
		if( $this->roomtype == 't' ) 
		{
			$query->where('a.t_room_total > 0');			
			$query->where('(a.t_room_rate_modified < '. floatval($this->price).' OR a.t_room_rate_modified = '.floatval($this->price).' )' );
		}
		if( $this->roomtype == 's' ) 
		{
			$query->where('a.s_room_total > 0');			
			$query->where('(a.s_room_rate_modified < '. floatval($this->price).' OR a.s_room_rate_modified = '.floatval($this->price).' )' );
		} 
		if( $this->roomtype == 'q' ) 
		{
			$query->where('a.q_room_total > 0');			
			$query->where('(a.q_room_rate_modified < '. floatval($this->price).' OR a.t_room_rate_modified = '.floatval($this->price).' )' );
		}							
	}
	
	protected function conditionTotalQuery( & $query )
	{
		$db = JFactory::getDbo();
		if( $this->roomtype == 'sd' ) {
			$query->where('a.sd_room_total > 0');			
			$query->where('(a.sd_room_total > '. $this->number_rooms.' OR a.sd_room_total='.$this->number_rooms.' )');
		}
		if( $this->roomtype == 't' )
		{
			$query->where('a.t_room_total > 0');			
			$query->where('(a.t_room_total > '. $this->number_rooms.' OR a.t_room_total='.$this->number_rooms.' )');
		}	
		if( $this->roomtype == 's' ) {
			$query->where('a.s_room_total > 0');			
			$query->where('(a.s_room_total > '. $this->number_rooms.' OR a.s_room_total='.$this->number_rooms.' )');
		}
		if( $this->roomtype == 'q' )
		{
			$query->where('a.q_room_total > 0');			
			$query->where('(a.q_room_total > '. $this->number_rooms.' OR a.q_room_total='.$this->number_rooms.' )');
		}	
	}
	
	/**
	 * 
	 * Update cache for ranking	 
	 */
	protected function updateRanking($type, $ranking)
	{
		$hotel = SFactory::getHotel();
		$db    = JFactory::getDbo();
		if($type=='rate'){
			if( $this->roomtype == 'sd' ) 
			{
				$query = 'UPDATE #__sfs_room_inventory SET sd_room_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);
				$db->setQuery($query);
				$db->query();
			} 
			if( $this->roomtype == 't' )  
			{
				$query = 'UPDATE #__sfs_room_inventory SET t_room_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);
				$db->setQuery($query);
				$db->query();
			}
			if( $this->roomtype == 's' ) 
			{
				$query = 'UPDATE #__sfs_room_inventory SET s_room_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);
				$db->setQuery($query);
				$db->query();
			} 
			if( $this->roomtype == 'q' )  
			{
				$query = 'UPDATE #__sfs_room_inventory SET q_room_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);
				$db->setQuery($query);
				$db->query();
			}		
			
		} else {
			
			if( $this->roomtype=='sd' ) {
				$query = 'UPDATE #__sfs_room_inventory SET sd_num_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);			
				$db->setQuery($query);
				$db->query();
			}
			if( $this->roomtype=='t' ) {
				$query = 'UPDATE #__sfs_room_inventory SET t_num_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);
				$db->setQuery($query);
				$db->query();
			}
			if( $this->roomtype=='s' ) {
				$query = 'UPDATE #__sfs_room_inventory SET s_num_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);			
				$db->setQuery($query);
				$db->query();
			}
			if( $this->roomtype=='q' ) {
				$query = 'UPDATE #__sfs_room_inventory SET q_num_rank='.$ranking.' WHERE hotel_id='.$hotel->id.' AND date='.$db->Quote($this->date);
				$db->setQuery($query);
				$db->query();
			}
						
		}
							
	}
	protected function setStarLevelQuery( & $query, $star, $levelOption )
	{
		// Make sure it's an integer
		$star = (int) $star;
		switch ($levelOption) {
			case 1:
				// Level 1: Star 1
				// Level 2: Star 2
				// Level 3: Star 3
				// Level 4: Star 4
				// Level 5: Star 5								
				$query->where('h.star = '.$star);
				break;
			case 2:
				// Level 1: Star 1,2 
				// Level 2: Star 3
				// Level 3: Star 4
				// Level 4: Star 5
				if( $star < 3 ) {
					$query->where('h.star < 3');	
				} else {
					$query->where('h.star = '.$star);					
				}				
				break;
			case 3:
				// Level 1: star 1,2
				// Level 2: star 3,4
				// Level 3: star 5
				if( $star < 3 ) {
					$query->where('h.star < 3');	
				} else if( $star < 5 ) {
					$query->where('h.star > 2 AND h.star < 5');					
				} else {
					$query->where('h.star = '.$star);
				}
				break;
			case 4:
				// Level 1: Star 1,2
				// Level 2: Star 3
				// Level 3: Star 4,5
				if( $star < 3 ) {
					$query->where('h.star < 3');	
				} else if( $star == 3 ) {
					$query->where('h.star = '.$star);						
				} else {
					$query->where('h.star > 3');
				}				
				break;
			case 5:
				// Level 1: Star 1
				// Level 2: Star 2,3
				// Level 3: Star 4,5
				if( $star == 1  ) {
					$query->where('h.star = 1');	
				} else if( $star < 4 ) {
					$query->where('h.star > 1 AND h.star < 4');					
				} else {
					$query->where('h.star > 3 ');
				}
				break;
			case 6:
				$query->where('h.star > 0 ');
				break;
			default:
				// Level 1: star 1,2
				// Level 2: star 3,4
				// Level 3: star 5
				if( $star < 3 ) {
					$query->where('h.star < 3');	
				} else if( $star < 5 ) {
					$query->where('h.star > 2 AND h.star < 5');					
				} else {
					$query->where('h.star = '.$star);
				}				
				break;							
		}
	}	

	
}




