<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class SfsModelAirlines extends JModelList
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
		parent::populateState('a.created', 'asc');
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		//Change airport session
        $session = JFactory::getSession();
        $airport_id = $session->get("airport_current_id");//code

		// Select the required fields from the table.
		$query->select('a.id, a.created_by AS user_id, u.name AS author_name, a.created_date, a.address, a.city, co.name AS country, a.telephone,
						 a.block AS state, b.name AS title,a.approved');
						
		$query->from('#__sfs_airline_details AS a');
		
		$query->leftJoin('#__sfs_country AS co ON co.id=a.country_id');
		$query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id');
		$query->leftJoin('#__users AS u ON u.id=a.created_by');
		
		$query->where('a.iatacode_id > 0');
		if ( $airport_id != "" ) {
			$query->leftJoin('#__sfs_airline_airport ap ON ap.airline_detail_id=a.id');
			$query->leftJoin('#__sfs_iatacodes ia ON ia.id=ap.airport_id');
			$query->where('ia.code="' . $airport_id . '"');
			///echo (string)$query;die;
		}
		$search = $this->getState('filter.search');
		if (!empty($search)) {			
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('b.name LIKE '.$search);
			
		}
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
