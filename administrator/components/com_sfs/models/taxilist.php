<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelTaxilist extends JModelList
{
	
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*,apd.code'				
			)
		);
		$query->from('#__sfs_taxi_companies AS a');
		
		$query->select('b.airline_id');
		$query->leftJoin('#__sfs_airline_taxicompany_map AS b ON b.taxi_id=a.id');
		
		$query->select('ap.airport_id, apd.code AS airport_code');
		$query->leftJoin('#__sfs_airport_taxicompany_map AS ap ON ap.taxi_id = a.id');
		$query->leftJoin('#__sfs_iatacodes AS apd ON ap.airport_id = apd.id AND apd.type=2');

		$query->select('c.company_name,d.name AS airline_name');
		$query->leftJoin('#__sfs_airline_details AS c ON c.id=b.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS d ON c.iatacode_id=d.id AND d.type=1');

		
			
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Filter by airport state
		$code = $this->getState('filter.code');
   	    if ( $code != '' ) {
            $query->where("apd.code = '$code'");
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

	public function getTable($type = 'Taxicompany', $prefix = 'SfsTable', $config = array())
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

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.published', $state);

		$code = $this->getUserStateFromRequest($this->context.'.filter.code', 'filter_code', '', 'string');
        $this->setState('filter.code', $code);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_sfs');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}
	
}
