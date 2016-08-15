<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelpointprioritys extends JModelList
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
		$query->from('#__sfs_point_priority AS a');
		
		// Filter by search in title
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.')');
			}
		}
		if(JRequest::getInt('type_group')){
			$query->where('(a.type_group = '.JRequest::getInt('type_group').')');
		}
		return $query;
	}
	public function getTable($type = 'Pointpriority', $prefix = 'SfsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
}
