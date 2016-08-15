<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelTaxivouchers extends JModelList
{
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		$pk = JRequest::getInt('id');
		
		$this->setState('filter.blockid', $pk);
		
		// Get the pagination request variables
		$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);
		//$this->setState('list.limit', 5);

		//$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);
		
		$value = JRequest::getInt('taxi_id',0);
		$this->setState('block.taxi_id',$value);

		$value = trim(JRequest::getString('blockcode'));
		$this->setState('block.code',$value);
						
		$value	= JRequest::getString('date_from');
		$this->setState('block.from',$value);
		
		$value	= JRequest::getString('date_to');		
		$this->setState('block.to',$value);
		
		$value	= JRequest::getString('showall');		
		$this->setState('list.showall',$value);
		
		
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	public function getReservations()
	{
		$items = $this->getItems();
		
		if(count($items))
		{
			$resids = array();
			foreach ($items as $item)
			{
				$resids[] = $item->reservation_id;
			}
			
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*');
			$query->from('#__sfs_taxi_vouchers AS a');			
			$query->where('a.booking_id IN ('.implode(',', $resids).')');
			
			$db->setQuery($query);
		
			$vouchers = $db->loadObjectList();
			
			$result = array();
			
			foreach ($vouchers as $voucher)
			{
				if( ! isset($result[$voucher->booking_id]) )
				{
					$result[$voucher->booking_id] = 0;
				}
				$result[$voucher->booking_id] +=  floatval($voucher->rate);
				if( (int)$voucher->is_return == 1  )
				{
					$result[$voucher->booking_id] +=  floatval($voucher->rate);	
				}
			}
			
			foreach ($items as & $item)
			{
				if( isset($result[$item->reservation_id]) && ! empty($result[$item->reservation_id]) )
				{
					$item->rate_total = $result[$item->reservation_id];
				}
			}
		
		}
		
		return $items;				
	}
	
	public function getListQuery() 
	{		
		$airline = SFactory::getAirline();
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);	

		$query->select('COUNT(a.id) AS total_voucher, SUM(a.rate) AS rate_total');
		$query->select('a.taxi_id, a.booking_id AS reservation_id, e.name AS taxi_company');
		$query->from('#__sfs_taxi_vouchers AS a');
		
		$query->select('b.blockcode, b.hotel_id, d.name AS hotel_name,c.date');
		$query->innerJoin('#__sfs_reservations AS b ON b.id=a.booking_id');
		$query->innerJoin('#__sfs_room_inventory AS c ON c.id=b.room_id');
		$query->innerJoin('#__sfs_hotel AS d ON d.id=b.hotel_id');
		$query->innerJoin('#__sfs_taxi_companies AS e ON e.id=a.taxi_id');

		if($airline->grouptype==3) 
		{
			$showall = (int) $this->getState('list.showall');
			if( $showall != 1 ) {
				$query->innerJoin('#__sfs_gh_reservations AS f ON f.reservation_id=a.booking_id AND f.airline_id='.(int)$airline->iatacode_id);
			} else {			
				$query->select('g.name AS airline_name');	
				$query->innerJoin('#__sfs_gh_reservations AS f ON f.reservation_id=a.booking_id');
				$query->innerJoin('#__sfs_iatacodes AS g ON g.id=f.airline_id AND g.type=1');
			}		
		}
		
		$query->where('b.airline_id = '.(int)$airline->id);		
		
		$taxi_id = $this->getState('block.taxi_id');
			
		if( isset($taxi_id) && (int)$taxi_id > 0 ) {
			$query->where( 'a.taxi_id='.$taxi_id );
		} 
		
		$code = $this->getState('block.code');
			
		if( isset($code) && strlen($code) > 0 ) {
			$query->where( 'b.blockcode='.$db->quote($code) );
		}
		
		$block_from = $this->getState('block.from');
		if( isset($block_from) && strlen($block_from) > 0 ) {
			$block_from .=' 00:00:00';
			$query->where( 'c.date  >= '.$db->quote($block_from) );
		}
		

		$block_to = $this->getState('block.to');
		if( isset($block_to) && strlen($block_to) > 0 ) {
			$block_to .=' 00:00:00';
			$query->where( 'c.date  <= '.$db->quote($block_to) );
		}
		
		$query->where('b.status <> '.$db->quote('D') );
		$query->where('e.published=1');
		
		$query->group('a.booking_id');
		$query->order('c.date DESC');
		
		return $query;
	}
	
	public function getStart()
	{
		return $this->getState('list.start');
	}

}


