<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

		
class SfsModelHotelreporting extends JModel
{
	protected $hotel = null;
	
	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication();
	
		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}		
		
		$pk = JRequest::getInt('id');
		$this->setState('hotel.id',$pk);					
	}
	
	public function getHotel($pk = null)
	{
		if( $this->hotel == null ){
			$db = $this->getDbo();
	    	
	    	$query = $db->getQuery(true);
	    	$query->select('*');
	    	$query->from('#__sfs_hotel');
	    	
	    	if( ! $pk ) $pk = $this->getState('hotel.id');
	    	
	    	$query->where('id='.$pk);
	    	
	    	$db->setQuery($query);
	    	
	    	$this->hotel  = $db->loadObject();
		}
		
    	return $this->hotel;
	}
	
	public function getInventoryData()
	{
		$hotelId	= JRequest::getInt('hotel_id');
		$from 		= JRequest::getVar('date_from');
    	$until  	= JRequest::getVar('date_to');   

    	$db = $this->getDbo();    	
    	$query = $db->getQuery(true);

    	$query->select('a.*');    	
    	$query->from('#__sfs_room_inventory AS a');
    	
    	$query->where('a.date >='.$db->Quote($from));
		$query->where('a.date <='.$db->Quote($until));
		$query->where('a.hotel_id ='.$hotelId);	 

		$query->order('a.date ASC');	
    	
    	$db->setQuery($query);
    	
    	$rows = $db->loadObjectList();
    
    	
    	$marketData = $this->getMarketData();
    	
    	foreach ($rows as & $row)
    	{
    		foreach ($marketData as $md)
    		{
    			if( $row->date == $md->date )
    			{
    				$row->market_sd_room_total = $md->market_sd_room_total;
    				$row->market_t_room_total = $md->market_t_room_total;
    				
    				$row->sd_room_percentage = number_format( ($row->sd_room_total/$md->market_sd_room_total)*100, 2);
    				$row->t_room_percentage = number_format( ($row->t_room_total/$md->market_t_room_total)*100, 2);
    				
    				$row->avg_rate_sd = number_format( $md->market_sd_room_rate/ $md->total_loaded, 2);
    				$row->avg_rate_t   = number_format( $md->market_t_room_rate/ $md->total_loaded, 2);
    			}
    		}
    	}
    	
    	return $rows;
	}
	
	public function getMarketData()
	{		
		$from 		= JRequest::getVar('date_from');
    	$until  	= JRequest::getVar('date_to');   

    	$db = $this->getDbo();    	
    	$query = $db->getQuery(true);

    	$query->select('a.date, COUNT(id) AS total_loaded');
    	$query->select('SUM(a.sd_room_total) AS market_sd_room_total');
    	$query->select('SUM(a.t_room_total) AS market_t_room_total');
    	$query->select('SUM(a.sd_room_rate) AS market_sd_room_rate');
    	$query->select('SUM(a.t_room_rate) AS market_t_room_rate');
    	 	
    	
    	$query->from('#__sfs_room_inventory AS a');
    	
    	$query->where('a.date >='.$db->Quote($from));
		$query->where('a.date <='.$db->Quote($until));
		

		$query->order('a.date ASC');

		$query->group('a.date');
    	
    	$db->setQuery($query);
    	
    	$rows = $db->loadObjectList();
    	    	  	
    	
    	return $rows;
	}
	
	
	public function getInventories()
	{		
		$result = array();
		
		$from 		= JRequest::getVar('date_from');
    	$until  	= JRequest::getVar('date_to');   

    	$db = $this->getDbo();    	
    	$query = $db->getQuery(true);

    	$query->select('a.*,b.name AS hotel_name,d.code AS currency, e.ring');    	
    	$query->from('#__sfs_room_inventory AS a');
    	
    	$query->innerJoin('#__sfs_hotel AS b ON b.id = a.hotel_id');
    	$query->innerJoin('#__sfs_hotel_taxes AS c ON c.hotel_id = a.hotel_id');
    	$query->innerJoin('#__sfs_currency AS d ON d.id = c.currency_id');    	
    	$query->leftJoin('#__sfs_hotel_backend_params AS e ON e.hotel_id = a.hotel_id');  	
    	
    	
    	$query->where('a.date >='.$db->Quote($from));
		$query->where('a.date <='.$db->Quote($until));		 

		$query->order('b.name ASC');	
    	
    	$db->setQuery($query);
    	
    	$rows = $db->loadObjectList();
    	    	        
    	if( count($rows) )
    	{
	    	foreach ($rows as & $row)
	    	{
	    		if( ! isset($result[$row->hotel_id]) )
	    		{
	    			$result[$row->hotel_id] = new stdClass();
	    			$result[$row->hotel_id]->name 		= $row->hotel_name;
	    			$result[$row->hotel_id]->currency 	= $row->currency;
	    			$result[$row->hotel_id]->ring 		= $row->ring;
	    			$result[$row->hotel_id]->dates		= array();
	    		}
	    		$result[$row->hotel_id]->dates[$row->date] = $row;
	    	}    		
    	}
    	return $result;
	}
	
	public function getDates()
	{		
		$from 		= JRequest::getVar('date_from');
    	$until  	= JRequest::getVar('date_to');   

    	$db = $this->getDbo();    	
    	$query = $db->getQuery(true);

    	$query->select('DISTINCT a.date');    	
    	$query->from('#__sfs_room_inventory AS a');
    	
    	$query->where('a.date >='.$db->Quote($from));
		$query->where('a.date <='.$db->Quote($until));		 

		$query->order('a.date ASC');	
    	
    	$db->setQuery($query);
    	
    	$rows = $db->loadResultArray();
        
    	
    	return $rows;
	}
	
	
}

