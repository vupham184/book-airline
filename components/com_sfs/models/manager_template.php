<?php
defined('_JEXEC') or die;

class SfsModelMessage extends JModelLegacy
{

	public function getTemplate(){
		$user = JFactory::getUser();
		$data = array();
		if ( $user->id != '0' ) {
			$airline = SFactory::getAirline();    
	
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
	
			$query->select('*');
			$query->from("#__sfs_managetemplate");
			$query->where('name_airline="' . $airline->id . '"');
			//$query->where('name_airline="airline2"');
	
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
	    return $data;
	}

}
