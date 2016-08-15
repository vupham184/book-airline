<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class SfsModelStates extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',				
				'ordering', 'a.ordering',				
				'country_id', 'a.country_id', 'country_name'
			);
		}
		parent::__construct($config);
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
		$query->from('#__sfs_states AS a');

		// Join over the country
		$query->select('cc.name AS country_name');
		$query->join('LEFT', '#__sfs_country AS cc ON cc.id = a.country_id');


		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR cc.name LIKE '.$search.')');
			}
		}

		// Filter by country.
		$countryId = $this->getState('filter.country_id');
		if ( is_numeric($countryId) ) {
			$query->where('a.country_id = '.(int) $countryId);
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.name');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
		return $query;
	}

	public function getTable($type = 'State', $prefix = 'SfsTable', $config = array())
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

		
		$countryId =& JRequest::getVar('country_id', 0, 'GET', 'int');
		$countryId = (int)$countryId > 0 ? $countryId : $this->getUserStateFromRequest($this->context.'.filter.country_id', 'filter_country_id', '');
		$this->setState('filter.country_id', $countryId);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_sfs');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}
}