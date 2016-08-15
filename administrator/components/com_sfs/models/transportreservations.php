<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelTransportreservations extends JModelList
{
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		
		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}		
		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$transport 		= JRequest::getVar('transport');		
		$sessTransport	= $session->get('transport_type');
		
		if($transport){
			$sessTransport	= $transport;
			$session->set('transport_type',$sessTransport);
		} else {
			if( !$sessTransport )
			{
				$sessTransport = 'bus';
				$session->set('transport_type',$sessTransport);
			}
		}
		
		$this->setState('transport_type',$sessTransport);
		
		// List state information.
		parent::populateState('a.booked_date', 'desc');
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$transportType = $this->getState('transport_type','bus');
		
		if($transportType == 'bus'){
			$query->select('a.*');
			$query->from('#__sfs_transportation_reservations AS a');
			
			$query->select('b.company_name,c.name AS airline_name');
			$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.airline_id');
			$query->leftJoin('#__sfs_iatacodes AS c ON c.id=b.iatacode_id');
			
			$query->select('d.name AS transport_company_name');
			$query->innerJoin('#__sfs_group_transportations AS d ON d.id=a.transport_company_id');
			
			$query->select('e.name AS terminal');
			$query->innerJoin('#__sfs_iatacodes AS e ON e.id=a.departure');
			
			$query->select('u.name AS booked_name');
			$query->innerJoin('#__users AS u ON u.id=a.user_id');
					
			$orderCol	= $this->state->get('list.ordering', 'a.booked_date');
			$orderDirn	= $this->state->get('list.direction', 'DESC');		
			$query->order($db->escape($orderCol.' '.$orderDirn));
		}
		
		if($transportType == 'taxi'){
			$query->select('a.*, COUNT(p.taxi_reservation_id) AS total_passengers');
			$query->from('#__sfs_taxi_reservations AS a');
			
			$query->innerJoin('#__sfs_taxi_reservation_passengers AS p ON p.taxi_reservation_id=a.id');
			
			$query->select('b.company_name,c.name AS airline_name');
			$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.airline_id');
			$query->leftJoin('#__sfs_iatacodes AS c ON c.id=b.iatacode_id');
			
			$query->select('d.name AS hotel_name');
			$query->innerJoin('#__sfs_hotel AS d ON d.id=a.hotel_id');
			
			$query->select('e.name AS terminal');
			$query->innerJoin('#__sfs_iatacodes AS e ON e.id=a.departure');
			
			$query->select('t.name AS taxi_name');
			$query->innerJoin('#__sfs_taxi_companies AS t ON t.id=a.taxi_id');
			
			
			$query->select('u.name AS booked_name');
			$query->innerJoin('#__users AS u ON u.id=a.booked_by');
			
			$query->group('p.taxi_reservation_id');
					
			$orderCol	= $this->state->get('list.ordering', 'a.booked_date');
			$orderDirn	= $this->state->get('list.direction', 'DESC');		
			$query->order($db->escape($orderCol.' '.$orderDirn));
		}

				
						
		return $query;
	}
	
	public function getItems()
	{
		$items	= parent::getItems();	
		return $items;
	}	
	
	
}
