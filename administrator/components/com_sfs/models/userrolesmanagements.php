<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');


class SfsModelUserrolesmanagements extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();		
		$filter_code = JRequest::getVar('filter_code', "");
		$this->setState('filter.code', $filter_code);
		
		$filter_name = JRequest::getVar('filter_name', "");
		$this->setState('filter.name', $filter_name);
		
		$filter_g_name = JRequest::getVar('filter_g_name', "");
		$this->setState('filter.g_name', $filter_g_name);
		
		$filter_airport_code = JRequest::getVar('filter_airport_code', "");
		$this->setState('filter.airport_code', $filter_airport_code);	
		
		$getchangeairport_id = JRequest::getVar('getchangeairport_id', "");
		$this->setState('filter.getchangeairport_id', $getchangeairport_id);	
			

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
		$query->select('i.code, b.airline_id, c.iatacode_id, a.id, e.group_id, a.name, a.username, a.email, f.title as g_name');
		$query->from('#__users AS a');
		
		$query->innerJoin('#__sfs_airline_user_map AS b ON a.id = b.user_id');
		$query->innerJoin('#__sfs_airline_details AS c ON c.id = b.airline_id');
		$query->innerJoin('#__sfs_contacts AS d ON a.id = d.user_id');		
		$query->innerJoin('#__user_usergroup_map AS e ON a.id = e.user_id');
		$query->innerJoin('#__usergroups AS f ON f.id = e.group_id');
		
		$query->innerJoin('#__sfs_iatacodes AS i ON i.id = c.iatacode_id');
		
		$code = $this->getState('filter.code');
		$name = $this->getState('filter.name');
		$g_name = $this->getState('filter.g_name');
		if (!empty($code)) {
			$code = $db->Quote('%'.$db->escape($code).'%');
			$query->where('(i.code LIKE '.$code.')');
		}
		if (!empty($name)) {
			$name = $db->Quote('%'.$db->escape($name).'%');
			$query->where('(a.name LIKE '.$name.')');
		}
		
		if (!empty($g_name)) {
			$g_name = $db->Quote('%'.$db->escape($g_name).'%');
			$query->where('(f.title LIKE '.$g_name.')');
		}	
		
		$query->where('d.grouptype IN(2,3)');
		$query->group('a.id');	
		///$query->where('b.airline_id IN(6)');		
		return $query;
	}
	
	public function getItems()
	{
		$items	= parent::getItems();	
		foreach($items as $item){
			$item->group = $this->group_user($item->id);
		}
		return $items;
	}
	function group_user($user_id){
		$db = $this->getDbo();	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__user_usergroup_map AS e');
		$query->innerJoin('#__usergroups AS f ON f.id = e.group_id');
		$query->where('e.user_id = "'.$user_id.'"');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	public function getAirportCodes()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id,a.code');
		$query->from('#__sfs_iatacodes AS a');
		
		$airport_code = $this->getState('filter.airport_code');
		if (!empty($airport_code)) {
			$airport_code = $db->Quote('%'.$db->escape($airport_code).'%');
			$query->where('(a.code LIKE '.$airport_code.')');
		}
		
		$query->where('a.type IN(2)');
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		return $result;
	}
	
	public function getAirlineAirports()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_airline_airport AS a');
		$db->setQuery($query);
    	$result = $db->loadObjectList();
		$arr = array();
		foreach ( $result as $item) {
			$arr['k' . $item->user_id.$item->airline_detail_id.$item->airport_id] = $item->airline_detail_id . '-' . $item->airport_id;
		}
		return $arr;
	}
	
	
	public function save($airline_detail_id, $airport_id, $user_id){
		$db = $this->getDbo();
		$trackObject            = new stdClass();
		$trackObject->airline_detail_id      = $airline_detail_id;
		$trackObject->airport_id   = $airport_id;
		$trackObject->user_id   = $user_id;
		$db->insertObject('#__sfs_airline_airport', $trackObject);
		return '';
	}
	
	public function del( $user_id ){
		//Delete old creditcard
		$db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->delete("#__sfs_airline_airport");
        $query->where('user_id='.$user_id);
        $db->setQuery($query);
        $result = $db->execute();
        if(!$result){
            die('Error SQL delete airline_airport');
        }
		return '';
	}
		
}
