<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class SfsModelGhs extends JModelList
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

		// Select the required fields from the table.
		$query->select('a.id, a.company_name, a.created_by AS user_id, u.name AS author_name, a.created_date, a.address, a.city, co.name AS country, a.telephone,
						 a.block AS state,a.approved');
						
		$query->from('#__sfs_airline_details AS a');
		
		$query->leftJoin('#__sfs_country AS co ON co.id=a.country_id');		
		$query->leftJoin('#__users AS u ON u.id=a.created_by');
		
		$query->where('a.iatacode_id = 0');
			
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
