<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelGrouptransportlist extends JModelList
{
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.published', $state);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_sfs');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}
	
	public function getItems()
	{
		$items = parent::getItems();
		
		if(count($items))
		{
			$db = JFactory::getDbo();
			foreach ($items as &$item)
			{
				$query = 'SELECT * FROM #__sfs_group_transportation_types WHERE group_transportation_id='.$item->id;
				$db->setQuery($query);
				$item->types = $db->loadObjectList();	

				$query_ = "SELECT a.group_transportation_id, b.code FROM #__sfs_group_transportation_airports AS a";
				$query_ .= " INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airport_id";
				$query_ .= " WHERE a.group_transportation_id = " . $item->id;
				$db->setQuery($query_);
				$item->airport = $db->loadObjectList();		  
			}
		}
		
		return $items;
	}
	
	public function getTable($type = 'Grouptransport', $prefix = 'SfsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'				
			)
		);
		$query->from('#__sfs_group_transportations AS a');
				
			
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.name');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}

	public function getListAirport(){
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query = "SELECT id,code,name FROM #__sfs_iatacodes";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}

	public function getFilterAirport(){
		$id = JRequest::getVar('airportFilter'); 
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		if($id){
			$query = "SELECT b.id, b.name, b.telephone,b.fax,b.published,b.approved,b.sendMail,b.sendFax,b.sendSMS FROM #__sfs_group_transportation_airports AS a";
			$query .= " INNER JOIN #__sfs_group_transportations AS b ON b.id = a.group_transportation_id";		
			$query .= " WHERE a.airport_id=" . $id;
			$db->setQuery($query);
			$result = $db->loadObjectList();
			
			foreach ($result as $rel) {
				$query_1 = "SELECT b.name FROM #__sfs_group_transportation_airports AS a";
				$query_1 .= " INNER JOIN #__sfs_group_transportation_types AS b ON b.group_transportation_id = a.group_transportation_id";		
				$query_1 .= " WHERE a.airport_id=" . $id;
				$db->setQuery($query_1);
				$rel->types = $db->loadObjectList();
				
				$query_ = "SELECT a.group_transportation_id, b.code FROM #__sfs_group_transportation_airports AS a";
				$query_ .= " INNER JOIN #__sfs_iatacodes AS b ON b.id = a.airport_id";
				$query_ .= " WHERE a.group_transportation_id = " . $rel->id;
				$db->setQuery($query_);
				$rel->airport = $db->loadObjectList();	
			}
		}else{			
			$result = array();
		}
				
		return $result;
	}

	
}


