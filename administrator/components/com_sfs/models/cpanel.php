<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class SfsModelCpanel extends JModel
{

	protected function populateState()
	{
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        $ring = JRequest::getVar('filter_ring');
        $this->setState('filter.ring', $ring);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_sfs');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.name', 'asc');
	}
	
	public function getReservations()
	{
		//Change airport session
        $session = JFactory::getSession();
        $airport_id = $session->get("airport_current_id");//code
		
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*, u.name AS booked_name, h.name AS hotel_name, h.id AS hotel_id');
						
		$query->from('#__sfs_reservations AS a');
		
		$query->select('b.transport_included,b.date AS room_date,b.sd_room_total,b.t_room_total');
		$query->leftJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->leftJoin('#__sfs_hotel AS h ON h.id=b.hotel_id');
				
		$query->select('ad.company_name,ic.name AS airline_name, ic.code AS airline_code, ad.city');
		$query->leftJoin('#__sfs_airline_details AS ad ON ad.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS ic ON ic.id=ad.iatacode_id');		

		$query->select('d.name AS country_name');
		$query->leftJoin('#__sfs_country AS d ON d.id=ad.country_id');
		
		$query->leftJoin('#__users AS u ON u.id=a.booked_by');
		
		if ( $airport_id != "" ) {
			$query->where('a.airport_code="' . $airport_id . '"');	
			//echo (string)$query;
		}
		
		$params = JComponentHelper::getParams('com_sfs');
		$cleanTime = trim($params->get('match_hours'));	
		$cleanTime = explode( ':' , $cleanTime);	
		

		$nowHour = SfsHelper::getATDate('now','H');							
		$now = SfsHelper::getATDate('now','Y-m-d');
		
		if( (int)$nowHour < (int)$cleanTime[0] ) {
			$now = SfsHelper::getATPrevDate('Y-m-d', $now);	
		}
		
		$query->where('b.date='.$db->quote($now));
		
		$db->setQuery($query);
		
		$reservations = $db->loadObjectList();
		
		return $reservations;
	}
	
}
