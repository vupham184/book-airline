<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelRentallocations extends JModelList
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
				'a.*'				
			)
		);
		$query->from('#__sfs_rental_car_location AS a');
		$query->select('b.code as name_code');
		$query->leftJoin('#__sfs_iatacodes AS b ON b.id=a.airportcode');
		$query->select('c.company as name_company');
		$query->leftJoin('#__sfs_company_rental_car AS c ON c.id=a.agency');
		$query->select('d.name as name_country');
		$query->leftJoin('#__sfs_country AS d ON d.id=b.country_id');

		// Filter by search in title
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.locationname LIKE '.$search.')');
			}
		}
		return $query;
	}
	public function getTable($type = 'Rentallocation', $prefix = 'SfsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
}
