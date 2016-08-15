<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelContacts extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$filter_airline_id = JRequest::getInt('filter_airline_id',0);
		$this->setState('filter.airline_id', $filter_airline_id);
		
		$filter_hotel_id = JRequest::getInt('filter_hotel_id',0);
		$this->setState('filter.hotel_id', $filter_hotel_id);		

		// Load the parameters.
		$params = JComponentHelper::getParams('com_sfs');
		$this->setState('params', $params);
		
		// List state information.
		parent::populateState('u.name', 'asc');
	}
	
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$session = JFactory::getSession();

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__sfs_contacts` AS a');
		
		// Join over the users for the username
		$query->select('u.username, u.email');
		$query->join('LEFT','#__users AS u ON u.id = a.user_id');
		
		if ( $filter_airline_id = $this->getState('filter.airline_id') ) {								
			$query->where('a.group_id = '.$filter_airline_id);
			$query->where('a.grouptype > 1 ');
		}
		
		if ( $filter_hotel_id = $this->getState('filter.hotel_id') ) {									
			$query->where('a.group_id = '.$filter_hotel_id);
			$query->where('a.grouptype = 1 ');
		}
		               
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(u.name LIKE '.$search.' OR u.username LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'id');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		#echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}

	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');		
		return parent::getStoreId($id);
	}
	
	public function getTable($type = 'Contact', $prefix = 'SfsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
	public function getAirlines()
	{
		$db = $this->getDbo();
		$query = 'SELECT a.id,a.company_name,b.name FROM #__sfs_airline_details AS a';
		$query .= ' LEFT JOIN #__sfs_iatacodes AS b ON b.id=a.iatacode_id';
		$query .= ' WHERE a.block=0 AND a.approved=1';
		$db->setQuery($query);
		
		$airlines = $db->loadObjectList();
		
		if(count($airlines))
		{
			return $airlines;
		}
		return null;
	}
	
	public function getHotels()
	{
		$db = $this->getDbo();
		$query = 'SELECT * FROM #__sfs_hotel WHERE block=0';
		$db->setQuery($query);
		
		$hotels = $db->loadObjectList();
		
		if(count($hotels))
		{
			return $hotels;
		}
		return null;
	}
	
}
