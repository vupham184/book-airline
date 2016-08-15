<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class SfsModelBooking extends JModelList
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

		// List state information.
		parent::populateState('a.booked_date', 'desc');
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*, u.name AS booked_name, h.name AS hotel_name, h.id AS hotel_id');
						
		$query->from('#__sfs_reservations AS a');
		
		$query->select('b.transport_included,b.date AS room_date,b.sd_room_total,b.t_room_total');
		$query->leftJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->leftJoin('#__sfs_hotel AS h ON h.id=b.hotel_id');
				
		$query->select('ic.name AS airline_name, ic.code AS airline_code, ad.city');
		$query->leftJoin('#__sfs_airline_details AS ad ON ad.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS ic ON ic.id=ad.iatacode_id');		

		$query->select('d.name AS country_name');
		$query->leftJoin('#__sfs_country AS d ON d.id=ad.country_id');
		
		$query->leftJoin('#__users AS u ON u.id=a.booked_by');
				
		$orderCol	= $this->state->get('list.ordering', 'a.booked_date');
		$orderDirn	= $this->state->get('list.direction', 'DESC');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));		
						
		return $query;
	}
	
	public function getItems()
	{
		$items	= parent::getItems();	
		return $items;
	}	
	
	function publish(&$pks, $value = 1)
	{
		// Initialise variables.
		$db = $this->getDbo();		
			// Access checks.
		foreach ($pks as $i => $pk) {
			$db->setQuery('UPDATE #__sfs_airline_details SET published='.$value);
			if( !$db->query() ) {
				throw new JException($db->getErrorMsg());
			}
		}

		return true;
	}
	
}
