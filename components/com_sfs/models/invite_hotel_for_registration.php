<?php
defined('_JEXEC') or die();
// import Joomla modelform library
jimport('joomla.application.component.model');

class SfsModelInvite_Hotel_For_Registration extends JModel
{			
		
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);		
	}	
	
	function getHotels( )
	{
		$db 	 = $this->getDbo();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
        $query =  $db->getQuery(true);
		
		$query->select("h.id as hid, h.name as hname, u.name as fullname,u.id as uid, u.email as uemail, c.* ");
		$query->from("#__sfs_hotel AS h");
		$query->innerJoin('#__sfs_hotel_airports AS e ON e.hotel_id=h.id AND e.airport_id='.$airport_current_id);
		$query->innerJoin('#__users AS u ON u.username=h.alias AND u.block=0');
		$query->innerJoin('#__sfs_contacts AS c ON c.user_id=u.id');
		$query->where("h.ws_id IS NULL");
		$query->where("NOT EXISTS(
			SELECT *
			FROM #__sfs_room_inventory AS i
			WHERE i.date = CURDATE() AND i.hotel_id = h.id
		)");
		$db->setQuery($query);
	
		$result = $db->loadObjectList();
		return $result;
	}
}