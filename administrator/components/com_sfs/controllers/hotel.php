<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class SfsControllerHotel extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('apply', 'save');
		$this->registerTask('applyWs', 'save_ws');
	}

	public function edit() {
		// Initialise variables.
		$app		= JFactory::getApplication();

		$model		= $this->getModel('Hotel','SfsModel');

		$id = $model->getState('hotel.id');
		$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=edit&id='.$id);
	}

	public function editws() {
		// Initialise variables.
		$app		= JFactory::getApplication();

		$model		= $this->getModel('Hotel','SfsModel');

		$id = $model->getState('hotel.id');
		$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=edit_ws&id='.$id);
	}

	public function cancel() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$this->setRedirect('index.php?option=com_sfs&view=hotels');
	}

	public function save() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model		= $this->getModel('Hotel','SfsModel');
		$msg='';
		if( ! $model->saveHotel() )
		{
			$msg = $model->getError();
		}

		$id = JRequest::getInt('id');

		$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=edit&id='.$id,$msg);
	}

	public function save_ws() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model		= $this->getModel('Hotel','SfsModel');
		$msg='';
		if( ! $model->saveHotel() )
		{
			$msg = $model->getError();
		}

		$id = JRequest::getInt('id');

		$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=edit_ws&id='.$id,$msg);
	}

	public function newAdmin() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model		= $this->getModel('Hotel','SfsModel');

		if( $model->newAdmin() ) {
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'));
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=newadmin&tmpl=component&id='.JRequest::getInt('id'),$msg);
		}

		return true;
	}

	public function saveRooms() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model		= $this->getModel('Hotel','SfsModel');

		if( $model->saveRooms() ) {
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=rooms&id='.JRequest::getInt('id'));
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=rooms&id='.JRequest::getInt('id'),$msg);
		}

		$n = count($rooms);

		return true;
	}

	public function saveFreeRelease() {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model		= $this->getModel('Hotel','SfsModel');

		if( $model->saveFreeRelease() ) {
			$msg ='Successfully Saved';
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=freerelease&tmpl=component&id='.JRequest::getInt('id'),$msg);
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=freerelease&tmpl=component&id='.JRequest::getInt('id'),$msg);
		}

		return true;
	}

	public function saveSystemEmails()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$hotelId = JRequest::getInt('hotel_id');

		$emailSend = JRequest::getVar('emailSend', array(), 'post', 'array');		
		$contacts  = JRequest::getVar('contacts', array(), 'post', 'array');
		$semail    = JRequest::getVar('semail', array(), 'post', 'array');
		$user = JFactory::getUser();
	
	
		$db = JFactory::getDbo();
		
		if( count($contacts) ) {
			foreach ($contacts as $contactId){

				$str = "";
				if (count($semail) && isset($semail[$contactId]) && count($semail[$contactId]))
				{					
					foreach ($semail[$contactId] as $key => $value) {
						$str = $str . ", " . $key;
					}
					JFactory::getMailer()->sendMail($user->email, $user->name, $emailSend[$contactId] , "Send system email", substr($str,1));

					$register = new JRegistry();
					$register->loadArray($semail[$contactId]);
					$emails = (string)$register;
					$query = 'UPDATE #__sfs_contacts SET systemEmails='.$db->quote($emails).' WHERE id='.(int)$contactId;
					$db->setQuery($query);
					$db->query();
				} else {
					$query = 'UPDATE #__sfs_contacts SET systemEmails='.$db->quote('').' WHERE id='.(int)$contactId;
					$db->setQuery($query);
					$db->query();
				}
			}
		}

		
		$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=systememails&id='.$hotelId);
		return $this;
	}

	public function inventoryNotification()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$user = JFactory::getUser();

		$model		= $this->getModel('Hotel','SfsModel');

		$hotel		= $model->getItem();
		$contacts 	= $model->getContacts();

		$db = JFactory::getDbo();

		$query = $db->getQuery(true);

		$query->select('ia.name AS airport_name');
		$query->from('#__sfs_hotel AS a');
		$query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=a.id AND ha.main=1');
		$query->innerJoin('#__sfs_iatacodes AS ia ON ia.id=ha.airport_id');
		$query->where('a.id='.$hotel->id);

		$db->setQuery($query);

		$airportName = $db->loadResult();

		$config = JFactory::getConfig();

		$mailFrom = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		ob_start();
		require_once JPATH_ROOT.'/components/com_sfs/libraries/emails/hotels/cron_rooms_availability.php';
		$bodyE = ob_get_clean();

		$subject = 'SFS roomloading requested for your hotel';

		//if( count($contacts) )
		//{

        $sendBody = JString::str_ireplace('{}', $airportName, $bodyE);
        foreach ($contacts as $contact)
        {
            if( !empty($contact->systemEmails) && (int)JString::strpos($contact->systemEmails, 'low_availlability') > 0 ){
                JUtility::sendMail($mailFrom, $fromName, $contact->email, $subject, $sendBody, true);
            }

        }

        $now = SfsHelper::getATDate('now','Y-m-d H:i:s');

        $trackObject = new stdClass();
        $trackObject->date = $now;
        $trackObject->user_id = $user->id;
        $trackObject->hotel_id = $hotel->id;

        $hotelObject = new stdClass();
        $hotelObject->id = $hotel->id;
        $hotelObject->last_room_load_request_date = $now;

        $inventoryObject = new stdClass();
        $inventoryObject->hotel_id = $hotel->id;
        $inventoryObject->created = $now;
        $inventoryObject->created_by = $user->id;
        $inventoryObject->date = date("Y-m-d");

        $db->insertObject('#__sfs_admin_notification_tracking', $trackObject);
        $db->updateObject('#__sfs_hotel', $hotelObject, "id");

        $query_inventory = $db->getQuery(true);

        $query_inventory->select('*');
		$query_inventory->from('#__sfs_room_inventory');		
		$query_inventory->where('hotel_id =' .$hotel->id. ' AND date ="'. date("Y-m-d") . '"');

		$db->setQuery($query_inventory);

		$result_inventory = $db->loadResult();
		if(!$result_inventory){			
			$db->insertObject('#__sfs_room_inventory', $inventoryObject);
		}
		//}



		$tmpl = JRequest::getVar('tmpl');

		if( $tmpl == 'component' ) {
			$this->setRedirect('index.php?option=com_sfs&view=close&tmpl=component');
		} else {
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=edit&id='.$hotel->id,'Successfully sent to the notification to the hotel.');
		}


		return $this;
	}

	public function activate()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		require_once JPATH_ROOT . '/components/com_sfs/libraries/core.php';
		require_once JPATH_ROOT . '/components/com_sfs/libraries/access.php';
		require_once JPATH_ROOT . '/components/com_sfs/libraries/hotel.php';
		require_once JPATH_ROOT . '/components/com_sfs/helpers/field.php';

		$hotelId = JRequest::getInt('id');

		$db = JFactory::getDbo();

		$hotel = SHotel::getInstance($hotelId);

		if( isset($hotel) && $hotel->id )
		{
			$query = $db->getQuery(true);
			$query->select('u.*');
			$query->from('#__users AS u');
			$query->innerJoin('#__sfs_hotel AS h ON h.created_by=u.id');
			$query->where('h.id='.(int)$hotel->id);

			$db->setQuery($query);

			$user = $db->loadObject();

			if( $user )
			{
				$query = 'UPDATE #__users SET block=0,activation='.$db->quote('').' WHERE id='.(int)$user->id;
				$db->setQuery($query);
				if($db->query())
				{
					$config = JFactory::getConfig();
					$data['fromname']	= $config->get('fromname');
					$data['mailfrom']	= $config->get('mailfrom');
					$data['sitename']	= $config->get('sitename');
					$data['siteurl']	= JUri::root();
					$data['loginurl']	= JUri::root().'index.php?option=com_sfs&view=login&Itemid=104';

					$emailSubject	= JText::_('COM_SFS_HOTEL_APPROVED_SUBJECT');
					$emailBody = JText::sprintf(
						'COM_SFS_HOTEL_APPROVED_BODY',
						$user->name,
						$data['loginurl']
					);

					$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $user->email, $emailSubject, $emailBody);
				}
			}
		}

		$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=edit&id='.$hotel->id,'Successfully activated the hotel');
		return $this;
	}

	public function saveContractedRates()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$model		= $this->getModel('Hotel','SfsModel');

		if( $model->saveContractedRates() ) {
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=rooms&id='.JRequest::getInt('id'));
		} else {
			$msg = $model->getError();
			$this->setRedirect('index.php?option=com_sfs&view=hotel&layout=rooms&id='.JRequest::getInt('id'),$msg);
		}
		return $this;
	}

}
