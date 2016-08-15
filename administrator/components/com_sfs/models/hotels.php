<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelHotels extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'name', 'a.name',                                                           
                'created_by', 'a.created_by', 'iat.code' 
            );
        }
        parent::__construct($config);
    }

    public function getItems()
    {							
		$now = SfsHelper::getATDate('now','Y-m-d');
		
		$begin = $now.' 00:00:00';
		$end   = $now.' 23:59:59';
						
    	$db = $this->getDbo();
    	$query = $db->getQuery(true);
    	$items = parent::getItems();

    	foreach ($items as & $item)
    	{
    		$query->clear();
    		$query->select('a.user_id,a.date, u.name,a.hotel_id');
    		$query->from('#__sfs_admin_notification_tracking AS a');
    		
    		$query->innerJoin('#__users AS u ON u.id=a.user_id');
    		
    		$query->where('a.hotel_id='.$item->id);
    		    		
    		$query->where('a.date <= ' . $db->quote($end) );
			$query->where('a.date >= ' . $db->quote($begin) );
				
    		$db->setQuery($query);
    		
    		$rows = $db->loadObjectList();
    		
    		if ( count($rows) )
    		{    	
    			$item->sender = array();
    			foreach ($rows as $row)
    			{    				    				
    				$item->sender[] = $row->name.' at '. JFactory::getDate($row->date)->format('H:i');
    			}
    			$item->sender = implode('<br />', $item->sender);
    		}
    	}
    	
    	return $items;
    }

    protected function getListQuery()
    {
        // Initialise variables.
        $db        = $this->getDbo();
        $query    = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id AS id, a.name AS name, a.address, a.city, a.telephone,'.                                
                'a.block, a.ordering AS ordering,a.star,'.
                'a.created_by,a.created_date, '.
            	'a.ws_type, a.ws_id, iat.code'
            )
        );
        $query->from('#__sfs_hotel AS a');
		
		//lchung
		$query->join('LEFT', '#__sfs_hotel_airports AS ha ON ha.hotel_id=a.id');
		$query->join('LEFT', '#__sfs_iatacodes AS iat ON iat.id=ha.airport_id');
		//$query->join('LEFT', '#__sfs_reservations AS res ON res.hotel_id=a.id');
		//End lchung
		
        
                       
        $query->join('LEFT','#__sfs_contacts AS c ON c.user_id=a.created_by');
        
        $query->select('u.name AS fullname');
        $query->join('LEFT', '#__users AS u ON u.id = c.user_id');
        
        
        $query->select('d.name AS country');
        $query->join('LEFT', '#__sfs_country AS d ON d.id = a.country_id');

        $query->select('hbp.ring');
        $query->join('LEFT', '#__sfs_hotel_backend_params AS hbp ON hbp.hotel_id = a.id');  

        
        $params = JComponentHelper::getParams('com_sfs');
		$cleanTime = trim($params->get('match_hours'));	
		$cleanTime = explode( ':' , $cleanTime);	
		
		$nowHour = SfsHelper::getATDate('now','H');							
		$now = SfsHelper::getATDate('now','Y-m-d');
		
		if( (int)$nowHour < (int)$cleanTime[0] ) {
			$now = SfsHelper::getATPrevDate('Y-m-d', $now);	
		}		
        
		$query->select('inv.id AS room_id');
        $query->select('inv.sd_room_total+inv.t_room_total+inv.s_room_total+inv.q_room_total AS total_loaded_room');
        $query->select('inv.booked_sdroom+inv.booked_troom+inv.booked_sroom+inv.booked_qroom AS total_booked_room');
                
        $query->join('LEFT', '#__sfs_room_inventory AS inv ON inv.hotel_id = a.id AND inv.date='.$db->quote($now));       
        

        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
        	if($published==1)
        	{
        		$query->where('a.block = '.(int) 0);	
        	}
			elseif ( $published == -4 ) {
        		$query->join('LEFT', '#__sfs_airline_notification_tracking AS ant ON ant.hotel_id = a.id');
				$query->where('ant.hotel_id = a.id');
        	}
			else {
        		$query->where('a.block = '.(int) 1);
        	}
            
        } else if ($published === '') {
            $query->where('(a.block IN (0, 1))');
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = '.(int) substr($search, 3));
            } else {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('(a.name LIKE '.$search.' OR a.alias LIKE '.$search.')');
            }
        }
        
    	$ring = $this->getState('filter.ring');
   	    if (is_numeric($ring)) {
            $query->where('hbp.ring = '.(int) $ring);
        }
		
		//lchung add
		$code = $this->getState('filter.code');
   	    if ( $code != '' ) {
            $query->where("iat.code = '$code'");
        }
		
		if ( JRequest::getVar('view') == 'hotels' ) { //user case in view = hotels ~~ list hotels
		
			$filter_ws_room = $this->getState('filter.ws_room');
			if ( $filter_ws_room == "Partner" || $filter_ws_room == "" ) {
				$query->where("a.ws_id IS NULL");
			}
			elseif ( $filter_ws_room == 'WS' ) {
				$query->where("a.ws_id > 0 ");
			}
		}
		else {//case Cpanel
			$query->where("a.ws_id IS NULL");
			//$limitstart = JRequest::getVar('limitstart');
			//echo $limitstart;
		}
		//End lchung
		
        //minhtran
        $country = $this->getState('filter.country');
        if ( $country != '' ) {
            $query->where("d.id = '$country'");
        }
        
        $city = $this->getState('filter.city');
        if ( $city != '' ) {
            $query->where("a.city = '$city'");
        }
        //minhtran
        // Add the list ordering clause.
        $orderCol    = $this->state->get('list.ordering', 'a.create_date');
        $orderDirn    = $this->state->get('list.direction', 'DESC');
        
        $query->group('a.id');
        
        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;
    }

    public function getTable($type = 'Hotel', $prefix = 'SfsTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

   
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);
        
        $ring = $this->getUserStateFromRequest($this->context.'.filter.ring', 'filter_ring', '', 'string');
        $this->setState('filter.ring', $ring);
		
		$code = $this->getUserStateFromRequest($this->context.'.filter.code', 'filter_code', '', 'string');
        $this->setState('filter.code', $code);
		
		$code = $this->getUserStateFromRequest($this->context.'.filter.ws_room', 'filter_ws_room', '', 'string');
        $this->setState('filter.ws_room', $code);

        $country = $this->getUserStateFromRequest($this->context.'.filter.country', 'filter_country', '', 'string');
        $this->setState('filter.country', $country);

        $city = $this->getUserStateFromRequest($this->context.'.filter.city', 'filter_city', '', 'string');
        $this->setState('filter.city', $city);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_sfs');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.name', 'asc');
    }
    
	function publish(&$pks, $value = 1)
	{
		// Initialise variables.
		$db = $this->getDbo();		
			// Access checks.
		foreach ($pks as $i => $pk) {
			$db->setQuery('UPDATE #__sfs_hotel SET state='.$value);
			if( !$db->query() ) {
				throw new JException($db->getErrorMsg());
			}
		}

		return true;
	}    
    
	public function getTotalLoadedRooms()
	{
		
		$db       = $this->getDbo();
        $query    = $db->getQuery(true);
        
        $params = JComponentHelper::getParams('com_sfs');
		$cleanTime = trim($params->get('match_hours'));	
		$cleanTime = explode( ':' , $cleanTime);	
		

		$nowHour = SfsHelper::getATDate('now','H');							
		$now = SfsHelper::getATDate('now','Y-m-d');
		
		if( (int)$nowHour < (int)$cleanTime[0] ) {
			$prevNow = SfsHelper::getATPrevDate('Y-m-d', $now);	
		}		
 	
        // Select the required fields from the table.        
		$query->select('a.sd_room_total+a.t_room_total+a.s_room_total+a.q_room_total AS total_loaded_room');
		$query->select('a.booked_sdroom+a.booked_troom+a.booked_sroom+a.booked_qroom AS total_booked_room');
				
		$query->from('#__sfs_room_inventory AS a');
        $query->where('a.date='.$db->quote($now).' OR a.date='.$db->quote($prevNow));

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        $result = 0;
        
        if(count($rows)){
        	foreach ($rows as $row){
        		$result += $row->total_loaded_room + $row->total_booked_room;
        	}
        }
        return $result;
	}
    public function getTotalInvitedLoadedRooms()
    {
        $hotels = $this->getItems();
        $result = 0;
        if(count($hotels)){
            foreach ($hotels as $hotel){
                    $result += (int)$hotel->total_loaded_room + $hotel->total_booked_room;
            }
        }
        return $result;
    }
    
	
	//lchung
	public function getHotels()
    {							
		$now = SfsHelper::getATDate('now','Y-m-d');
		$begin = $now.' 00:00:00';
		$end   = $now.' 23:59:59';
    	$db = $this->getDbo();
		
		$query = $this->getListQuery();
		$db->setQuery($query);
		$items = $db->loadObjectList();
		
    	$query = $db->getQuery(true);
		
    	foreach ($items as & $item)
    	{
    		$query->clear();
    		$query->select('a.user_id,a.date, u.name,a.hotel_id');
    		$query->from('#__sfs_admin_notification_tracking AS a');
    		$query->innerJoin('#__users AS u ON u.id=a.user_id');
    		$query->where('a.hotel_id='.$item->id);
    		$query->where('a.date <= ' . $db->quote($end) );
			$query->where('a.date >= ' . $db->quote($begin) );
    		$db->setQuery($query);
    		$rows = $db->loadObjectList();
    		if ( count($rows) )
    		{    	
    			$item->sender = array();
    			foreach ($rows as $row)
    			{    				    				
    				$item->sender[] = $row->name.' at '. JFactory::getDate($row->date)->format('H:i');
    			}
    			$item->sender = implode('<br />', $item->sender);
    		}
    	}
    	return $items;
    }
	//End lchung
    //minhtran
    public function updateairport($cid,$airport_id){
        $db = $this->getDbo();
        $query='UPDATE #__sfs_hotel_airports SET airport_id="'.$airport_id.'"
WHERE hotel_id in ('.implode(",",$cid).')';
        $db->setQuery($query);
        $db->execute();
    }
    //minhtran
}


