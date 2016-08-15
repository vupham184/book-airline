<?php
// no direct access
defined('_JEXEC') or die;
require_once JPATH_SITE.'/components/com_sfs/libraries/access.php';

abstract class modSfsChangeCurrencyHelper
{
	static function getAirlineCurrencyData()
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
            $query->select('a.id ,a.code, a.name, c.flag');
            $query->from('#__sfs_currency AS a');
            $query->leftJoin('#__sfs_airline_currency AS b ON b.currency_id = a.id');
            $query->leftJoin('#__sfs_country AS c ON c.default_currency = a.code');
			$query->where('b.user_id = '.$user->id);
			$query->group('a.code');

			$airportsAPI_Arr = $session->get('airportsAPI_Arr');
			if( count( $airportsAPI_Arr ) > 0 ){ //kiem tra truong hop khi login as API
				$query->where("a.code IN('" . implode("','", $airportsAPI_Arr ) . "')" );
			}
			
            
		}
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}
