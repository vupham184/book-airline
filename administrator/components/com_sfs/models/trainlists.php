<?php // No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelTrainlists extends JModelList
{
	public function getListQuery()
	{	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$this->getState(
				'list.select',
				'a.*,b.name AS airlineName'
			)
		);
		$query->from('#__sfs_airline_trains AS a');
		$query->leftJoin('#__sfs_iatacodes AS b on a.iata_airportcode = b.code');
		//return $query;
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.iata_airportcode LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.id');
		$orderDirn	= $this->state->get('list.direction', 'ASC');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));
		$query->where("b.type = 2");
		return $query;
	}
	public function deleteAirlineTrain($arr){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__sfs_airline_trains');
		$query->where('id IN ('.$arr.')');
		$db->setQuery($query);
		return $db->query();
	}
}
?>