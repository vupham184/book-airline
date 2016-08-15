<?php
// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class SfsModelTrains extends JModel
{
	public function saveTrain($arr){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert($db->quoteName('#__sfs_book_issue_trains'));
		$columns = array('flight_number', 'id_from_trainstation', 'id_to_trainstation', 'travel_date','type','first_name','last_name');
		$query->columns($columns);
		foreach ($arr as $value) {
			$query->values('"'.implode('","', $value).'"');
		}
		$db->setQuery($query);
		$db->query();
		$result = $db->insertid();
		return $result;
	}
}

