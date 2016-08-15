<?php
// no direct access
defined('_JEXEC') or die;
require_once JPATH_SITE.'/components/com_sfs/libraries/access.php';

abstract class modSfsChangeAirportHelper
{
	static function getAirlineAirportData()
	{
        $user	= JFactory::getUser();
		$result = null;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$session = JFactory::getSession();

		if( SFSAccess::isAirline($user) ) {

            $airline = SFactory::getAirline();
            if ($airline->grouptype == 3) {
                $session = JFactory::getSession();
                $airline_id = $session->get("airline_id");

                $query->select("id");
                $query->from("#__sfs_airline_details");
                $query->where("iatacode_id=" . (int)$airline_id);
                $db->setQuery($query);
                $result = $db->loadObject();

                $airline->id = $result->id;
            }
            $query->clear();
            $query->select('a.id ,a.code, a.name ');
            $query->from('#__sfs_iatacodes AS a');
            $query->innerJoin('#__sfs_airline_airport AS b ON b.airport_id=a.id');
            $query->where('b.airline_detail_id=' . (int)$airline->id);
            $query->where('a.type=2');
			$query->where('b.user_id=' . $user->id);
			
			$airportsAPI_Arr = $session->get('airportsAPI_Arr');
			if( count( $airportsAPI_Arr ) > 0 ){ //kiem tra truong hop khi login as API
				$query->where("a.code IN('" . implode("','", $airportsAPI_Arr ) . "')" );
			}
			//print_r($user);die;
            // if ($user->airport)
            // {
            //     $registry  = new JRegistry();
            //     $registry->loadString($user->airport);
            //     $airportIDs = $registry->toArray();
            //     $airportIdList = implode(",", $airportIDs);
            //     $query->where('a.id IN ('. $airportIdList .")");
            // }
			$query->order('a.code ASC');
		}

		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}
