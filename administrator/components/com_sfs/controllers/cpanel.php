<?php
defined('_JEXEC') or die();

class SfsControllerCpanel extends JControllerLegacy
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function sends()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$user = JFactory::getUser();

		$config = JFactory::getConfig();

		$mailFrom = $config->get('mailfrom');

		$fromName = $config->get('fromname');

		$params = JComponentHelper::getParams('com_sfs');

		$airportName = $params->get('sfs_system_airport');

		ob_start();
		require_once JPATH_ROOT.'/components/com_sfs/libraries/emails/hotels/cron_rooms_availability.php';
		$bodyE = ob_get_clean();

		$bodyE = JString::str_ireplace('{}', $airportName, $bodyE);

		$subject = 'SFS roomloading requested for your hotel';

		$modelHotel = JModel::getInstance('Hotels','SfsModel');
		$hotels = $modelHotel->getItems();

		$cids = JRequest::getVar('cid', array(), 'post', 'array');

		JArrayHelper::toInteger($cids);

		foreach ( $hotels as $hotel)
		{            
			if( !in_array((int)$hotel->id, $cids) ) {
				continue;
			}

			if( isset($hotel->room_id) && (int) $hotel->room_id > 0 && $hotel->total_loaded_room >= 0)
			{
				continue;
			}


			$query->clear();
			$query->select('a.*,u.email');
			$query->from('#__sfs_contacts AS a');
			$query->innerJoin('#__users AS u ON u.id=a.user_id');
			$query->where('a.group_id='.$hotel->id.' AND a.grouptype=1');
			$query->where('u.block=0');

			$db->setQuery($query);

			$contacts = $db->loadObjectList();            

			if( count($contacts) )
			{
				foreach ($contacts as $contact)
				{
					if( !empty($contact->email) && (int)JString::strpos($contact->systemEmails, 'low_availlability') > 0 && empty($hotel->ws_type) ){
						JUtility::sendMail($mailFrom, $fromName, $contact->email, $subject, $bodyE, true);
					}						
				}
				$now = SfsHelper::getATDate('now','Y-m-d H:i:s');
                $date_now = SfsHelper::getATDate('now','Y-m-d');

				$trackObject = new stdClass();
				$trackObject->date = $now;
				$trackObject->user_id = $user->id;
				$trackObject->hotel_id = $hotel->id;

				$hotelObject = new stdClass();
				$hotelObject->id = $hotel->id;
				$hotelObject->last_room_load_request_date = $now;

                $query->clear();
                $query->select('id');
                $query->from('#__sfs_room_inventory');
                $query->where('hotel_id='.$hotel->id.' AND date='.$db->quote($date_now));
                $db->setQuery($query);
                $result = $db->loadResult();

                $inventoryObject = new stdClass();
                $inventoryObject->modified = $now;
                $inventoryObject->modified_by = $user->id;


                if(!count($result) ) {
                    $inventoryObject->hotel_id = $hotel->id;
                    $inventoryObject->created = $now;
                    $inventoryObject->created_by = $user->id;
                    $inventoryObject->date = $date_now;
                    $db->insertObject('#__sfs_room_inventory', $inventoryObject);
                }
                else{
                    $inventoryObject->id = $result->id;
                    $db->updateObject('#__sfs_room_inventory', $inventoryObject, "id");
                }

				$db->insertObject('#__sfs_admin_notification_tracking', $trackObject);
				$db->updateObject('#__sfs_hotel', $hotelObject, "id");
			}

		}

		$this->setRedirect( JRoute::_('index.php?option=com_sfs',false) );
		return;
	}

    public function sendRoomloadingInvitation()
    {
        $time = JRequest::getInt('time');
        $params = JComponentHelper::getParams('com_sfs');
        $minimum_available_rooms = $params->get('minimum_available_rooms',0);

        $modelHotel = JModel::getInstance('Hotels','SfsModel');
        $hotels = $modelHotel->getItems();

        $totalLoadedRooms = $modelHotel->getTotalLoadedRooms();

        if($totalLoadedRooms < $minimum_available_rooms)
        {

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $user = JFactory::getUser();

            $config = JFactory::getConfig();

            $mailFrom = $config->get('mailfrom');

            $fromName = $config->get('fromname');

            $params = JComponentHelper::getParams('com_sfs');

            $airportName = $params->get('sfs_system_airport');

            ob_start();
            require_once JPATH_ROOT.'/components/com_sfs/libraries/emails/hotels/cron_rooms_availability.php';
            $bodyE = ob_get_clean();

            $bodyE = JString::str_ireplace('{}', $airportName, $bodyE);

            $subject = 'SFS roomloading requested for your hotel';

            foreach ( $hotels as $hotel)
            {
                if($time == 1)
                {
                    if( (int) $hotel->ring != 1 )
                    {
                        continue;
                    }
                }
                elseif($time == 2)
                {
                    if( (int) $hotel->ring != 1 && (int) $hotel->ring != 2 )
                    {
                        continue;
                    }
                }

                if( isset($hotel->room_id) && (int) $hotel->room_id > 0 && $hotel->total_loaded_room != 0 )
                {
                    continue;
                }

                $query->clear();
                $query->select('a.*,u.email');
                $query->from('#__sfs_contacts AS a');
                $query->innerJoin('#__users AS u ON u.id=a.user_id');
                $query->where('a.group_id='.$hotel->id.' AND a.grouptype=1');
                $query->where('u.block=0');

                $db->setQuery($query);

                $contacts = $db->loadObjectList();


                if( count($contacts) )
                {
                    foreach ($contacts as $contact)
                    {
                        if( !empty($contact->email) &&  empty($hotel->ws_type) ){

                            JUtility::sendMail($mailFrom, $fromName, $contact->email, $subject, $bodyE, true);
                        }
                    }

                    $now = SfsHelper::getATDate('now','Y-m-d H:i:s');
                    $date_now = SfsHelper::getATDate('now','Y-m-d');

                    $trackObject = new stdClass();
                    $trackObject->date = $now;
                    $trackObject->user_id = $user->id;
                    $trackObject->hotel_id = $hotel->id;

                    $hotelObject = new stdClass();
                    $hotelObject->id = $hotel->id;
                    $hotelObject->last_room_load_request_date = $now;

                    $query->clear();
                    $query->select('id');
                    $query->from('#__sfs_room_inventory');
                    $query->where('hotel_id='.$hotel->id.' AND date='.$db->quote($date_now));
                    $db->setQuery($query);
                    $result = $db->loadResult();

                    $inventoryObject = new stdClass();
                    $inventoryObject->modified = $now;
                    $inventoryObject->modified_by = $user->id;

                    if(!count($result)) {
                        $inventoryObject->hotel_id = $hotel->id;
                        $inventoryObject->created = $now;
                        $inventoryObject->created_by = $user->id;
                        $inventoryObject->date = $date_now;
                        $db->insertObject('#__sfs_room_inventory', $inventoryObject);
                    }
                    else{
                        $inventoryObject->id = $result->id;
                        $db->updateObject('#__sfs_room_inventory', $inventoryObject, "id");
                    }

                    $db->insertObject('#__sfs_admin_notification_tracking', $trackObject);
                    $db->updateObject('#__sfs_hotel', $hotelObject, "id");
                }
            }
        }
        exit(0);
    }
}
