<?php

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelIatacodes extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		
		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);	

			

		$type = JRequest::getInt('type',0);
		if($type) $app->setUserState($this->context.'.filter.type', $type);			
			
		
		$type = $this->getUserStateFromRequest($this->context.'.filter.type', 'filter_type');

		
		$this->setState('filter.type', $type);


		// List state information.
		parent::populateState('a.name', 'asc');
	}

	protected function getListQuery()
	{
		//Change airport session
		$session = JFactory::getSession();
		$airport_id = $session->get("airport_current_id");//code
		
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*,b.name AS country'
			)
		);
		$query->from('#__sfs_iatacodes AS a');

		$query->leftJoin('#__sfs_country AS b ON b.id=a.country_id');

		$type= $this->getState('filter.type');
		if($type) {
			$query->where('a.type = ' . (int) $type);
			
		}
		
		if ( $airport_id != "" ) {
			$query->where('a.code LIKE "' . $airport_id . '%"');
		}
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		}
		else if ($published === '') {
			$query->where('(a.state = 0 OR a.state = 1)');
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}						
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR a.code LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		$query->order($db->escape($orderCol.' '.$orderDirn));


		return $query;
	}


	public function getItems()
	{
		$items	= parent::getItems();
		return $items;
	}	
}
