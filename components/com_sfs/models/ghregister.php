<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');
jimport('joomla.user.helper');
jimport('joomla.mail.helper');
class SfsModelGhregister extends JModel
{
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);		
	}	
		
	public function save()
	{				
		$session = JFactory::getSession();
		//get all contacts
		
		$contacts = $session->get('airContacts');							
		$airline  = $session->get('airline');
		$abilling = $session->get('abilling');
		
		if( empty($airline) ) {
			$this->setError('Your registration is expired. Please try again.');
			return false;
		}
		if( empty($abilling) ) {
			$this->setError('Your registration is expired. Please try again.');
			return false;
		}
		if( ! count($contacts) ) {
			$this->setError('Your registration is expired. Please try again.');
		}
		
		$airlineId = 0;
					
		//process for maincontact first		
		foreach ($contacts as $key => $contact)
		{
			$contact['main_contact'] = (int) $contact['main_contact'];
			if( $contact['main_contact'] == 1 ) 
			{							
				$session->set('airlineMainContactName',$contact['gender'].' '.$contact['name'].' '.$contact['surname']);
						 					
				$userId = $this->saveAccount($contact);	
			
				if ( ! $userId ) {
					return false;
				}	
				
				//Important task is if maincontact then we must be store the airline
				$airlineId = $this->saveAirline( $userId, $airline );
				if ( ! $airlineId ) 
				{
					$this->setError('Store Airline failed');
					return false;
				}		

				$contactId = $this->saveContact($userId, $airlineId, $contact);
				
				if( ! $contactId ) {
					$this->setError('Store contact failed');
					return false;				
				}	

				$this->_adminEmail($airline['company_name'],$airlineId);
				
				break;
			}
		}
		
		//process for othercontact	
		foreach ($contacts as $contact)
		{
			$contact['main_contact'] = (int) $contact['main_contact'];
			if( $contact['main_contact'] == 0 ) 
			{
				$userId = $this->saveAccount($contact);
				
				if ( ! $userId ) {
					return false;
				}													
				$contactId = $this->saveContact($userId, $airlineId, $contact);
				
				if( ! $contactId ) {
					$this->setError('Store contact failed');
					return false;				
				}
			}
		}			
		
		
		// clear all sessions
		$session->clear('airline');
		$session->clear('abilling');
		$session->clear('airContacts');		
		
		return true;
	}

	private function saveAirline( $userId , & $airlineData )
	{					
		$session = JFactory::getSession();

		// Save billing
		$billingTable = JTable::getInstance('Billing','JTable');
		
		$billingData  = $session->get('abilling');
						
		if( !$billingTable->bind($billingData) ) {
			$this->setError($billingTable->getError());
			return false;					
		}				
		if( ! $billingTable->check() ) {
			$this->setError( $billingTable->getError() );
			return false;					
		}
		if( ! $billingTable->store() ) {
			$this->setError( $billingTable->getError() );
			return false;					
		}
				
		$airlineData['billing_id'] = $billingTable->get('id');
		//save airline
		$airlineData['created_by'] = $userId;
		$airline = new SAirline();		
		
		if ( ! $airline->bind( $airlineData ) ) 
		{ 
			$this->setError('Unable bind airline data');
			return false;
		}		
		
		$skipCheck = true;
		if( ! $airline->save( $skipCheck ) ) {
			// will work with this later
			$this->setError('Unable save airline data');
			return false;
		}	
		
		//Insert Servicing airline(s)
		if( (int) $airline->id ) 
		{
			$db = $this->getDbo();
			$airline_codes = $airlineData['iatacode'];			
			foreach ( $airline_codes as $key => $value ) {
				$tmpO = new stdClass();
				$tmpO->ground_id  = $airline->id;
				$tmpO->airline_id = $value;
				if( ! $db->insertObject( '#__sfs_groundhandlers_airlines' , $tmpO ) ) {
					$this->setError( $db->getErrorMsg() );
				}				
			}			
		}
			
		return (int) $airline->id;
	}
	
	private function saveAccount( $contact )
	{
		$date = JFactory::getDate();
		$session = JFactory::getSession();
		
		$airline  = $session->get('airline');		
		
		$db = $this->getDbo();
		
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_users');
		
		$userData = array();		
		$user = new JUser;
				
		//get user group id
		$groupId = 0;				
		$contact['main_contact'] = (int) $contact['main_contact'];
		
		if( $contact['main_contact'] == 1 ) {
			$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('GH Administrator'));	
		} else if( $contact['main_contact']==0 ) {
			$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('GH Staff'));
		}
		
		$groupId = (int) $db->loadResult();
		
		if( ! $groupId ) $groupId = 2;
			
		$userData['groups']   = array( (int) $groupId );
		$userData['name']     = $contact['name'].' '.$contact['surname'];		
		$userData['password'] = SfsHelper::createRandomPassword();			
		$userData['email']	  = $contact['email'];
		
		$userData['username'] = null;
			
		// generate username
		while( $userData['username'] === null ) {
			$tmpUN = JString::strtolower( JUserHelper::genRandomPassword(8) );
			$db->setQuery('SELECT COUNT(*) FROM #__users WHERE username = '.$db->Quote($tmpUN));
			$countResult = intval( $db->loadResult() );				
			if ( $countResult == 0 ) {
				$userData['username'] = $tmpUN;
				break;									
			}				
		}	
		
		
		
		$useractivation = $params->get('useractivation');
			        			
		jimport('joomla.user.helper');	
		$userData['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());
		$userData['block'] = 1;	

		// Bind the data.
		if (!$user->bind($userData)) {
			$this->setError(JText::sprintf('COM_SFS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}		
		
		$user->setParam('timezone', $airline['time_zone']);
		$user->setParam('editor', '');
		$user->setParam('language', '');
		
		// Store the data.
		if (!$user->save()) {
			$this->setError(JText::sprintf('COM_SFS_REGISTRATION_SAVE_FAILED', $user->getError()));
			return false;
		}		
		$airlineAdminName = $session->get('airlineMainContactName');
				
		
	    // Compile the notification mail values.
		$config = JFactory::getConfig();
						
		$data = $user->getProperties();
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['siteurl']	= JUri::base();		
					
						
		$emailSubject	= JText::sprintf(
			'COM_SFS_EMAIL_ACCOUNT_DETAILS',
			$data['name'],
			$data['sitename']
		);
		
		if( $contact['main_contact'] == 1 ) {				
			$emailBody = JText::sprintf(
				'COM_SFS_EMAIL_AIRLINE_REGISTERED_BODY',
				$data['name'],
				$data['sitename'],				
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);												
			$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
		} else {
			$emailBody = JText::sprintf(
				'COM_SFS_EMAIL_AIRLINE_STAFF_REGISTERED_BODY',
				$data['name'],
				$airlineAdminName,
				$data['sitename'],				
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);												
			$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
		}
		/*
		// Send the registration email.
		$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

		// Check for an error.
		if ($return !== true) {
			$this->setError(JText::_('COM_SFS_REGISTRATION_SEND_MAIL_FAILED'));

			// Send a system message to administrators receiving system mails
			$db = JFactory::getDBO();
			$q = "SELECT id
				FROM #__users
				WHERE block = 0
				AND sendEmail = 1";
			$db->setQuery($q);
			$sendEmail = $db->loadResultArray();
			if (count($sendEmail) > 0) {
				$jdate = new JDate();
				// Build the query to add the messages
				$q = "INSERT INTO `#__messages` (`user_id_from`, `user_id_to`, `date_time`, `subject`, `message`)
					VALUES ";
				$messages = array();
				foreach ($sendEmail as $userid) {
					$messages[] = "(".$userid.", ".$userid.", '".$jdate->toMySQL()."', '".JText::_('COM_SFS_MAIL_SEND_FAILURE_SUBJECT')."', '".JText::sprintf('COM_SFS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])."')";
				}
				$q .= implode(',', $messages);
				$db->setQuery($q);
				$db->query();
			}
			return false;
		}		
		*/
		
		return (int)$user->id;			
	}
	
	private function saveContact( $userId , $airlineId , $contact )
	{		
		
		$db = JFactory::getDbo();
		$db->setQuery(
			'INSERT INTO #__sfs_airline_user_map (airline_id, user_id)' .
			' VALUES ('.$airlineId.', '.$userId.')'
		);
		$db->query();		
		
		$row = JTable::getInstance('SFSContact', 'JTable');
		
		$data = new stdClass();
		
		$data->grouptype 	= constant('SFSCore::GROUND_HANDLER_GROUP');
		$data->group_id 	= $airlineId;
		$data->user_id		= $userId;
		$data->is_admin 	= $contact['main_contact'];
		$data->gender 		= $contact['gender'];		
		$data->name 		= $contact['name'];
		$data->surname 		= $contact['surname'];
		$data->job_title 	= $contact['job_title'];
		
		$data->telephone 	= SfsHelper::getPhoneString($contact['phone_code'] , $contact['phone_number'] );
		$data->fax 			= SfsHelper::getPhoneString($contact['fax_code'] , $contact['fax_number'] );
		$data->mobile 		= SfsHelper::getPhoneString($contact['mobile_code'] , $contact['mobile_number'] );
		$data->systemEmails	= '{"booking":"1","voucher":"1","blockstatus":"1"}';
		
		if ( ! $row->bind( $data ) ) 
		{ 
			$this->setError('Can not bind contact data');
			return false;
		}
		
		if( ! $row->check() ) {
			$this->setError( $row->getError() );
			return false;
		}
		
		if( ! $row->store() ) {
			$this->setDbo($row->getError());
			return false;
		}	
		
		return (int)$row->id;		
	}
	
	private function _adminEmail($airlineName,$airlineId)
	{
		// Compile the notification mail values.
		$config = JFactory::getConfig();
		$data = array();
						
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['approveurl']	= JUri::base().'administrator/index.php?option=com_sfs&view=gh&layout=edit&id='.$airlineId;		
		
						
		$emailSubject	= JText::sprintf(
			'COM_SFS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT',
			$data['sitename']			
		);
				
		$emailBody = JText::sprintf(
			'COM_SFS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY',
			$data['sitename'],
			$data['approveurl']			
		);				
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('u.email');
		$query->from('#__users AS u');
		$query->innerJoin('#__user_usergroup_map AS um ON um.user_id=u.id AND um.group_id=8');
				
		$db->setQuery($query);
		
		$admins = $db->loadObjectList();
		
		foreach ($admins as $admin)
		{
			JUtility::sendMail($data['mailfrom'], $data['fromname'], $admin->email, $emailSubject, $emailBody);	
		}				
				
	}
		
}

