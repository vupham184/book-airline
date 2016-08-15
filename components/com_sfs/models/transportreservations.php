<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelTransportreservations extends JModelList
{
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
				
		// Get the pagination request variables
		$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);
		
		//$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);

		$value = trim(JRequest::getString('reference_number'));
		$this->setState('block.reference_number',$value);
						
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
		return $items;				
	}
	
	public function getListQuery() 
	{		
		$airline = SFactory::getAirline();
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);	

		$query->select('a.*,b.name AS bus_company,b.telephone,u.name AS created_by');		
		$query->from('#__sfs_transportation_reservations AS a');
		
		$query->innerJoin('#__sfs_group_transportations AS b ON b.id=a.transport_company_id');
		$query->innerJoin('#__users AS u ON u.id=a.user_id');
		
		$query->where('a.airline_id = '.(int)$airline->id);	

		$code = $this->getState('block.reference_number');
			
		if( isset($code) && strlen($code) > 0 ) {
			$query->where( 'a.reference_number='.$db->quote($code) );
		}
		
		$block_from = $this->getState('block.from');
		if( isset($block_from) && strlen($block_from) > 0 ) {
			$block_from .=' 00:00:00';
			$query->where( 'a.booked_date  >= '.$db->quote($block_from) );
		}
		

		$block_to = $this->getState('block.to');
		if( isset($block_to) && strlen($block_to) > 0 ) {
			$block_to .=' 00:00:00';
			$query->where( 'a.booked_date  <= '.$db->quote($block_to) );
		}
		
		$query->order('a.booked_date DESC');
		
		return $query;
	}
	
	public function getStart()
	{
		return $this->getState('list.start');
	}

}


