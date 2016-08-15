<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class SfsModelContact extends JModel
{
	protected $_item = null;
	
	protected $_items = null;
		
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	/**
	 * Method to get a contact data.
	 *
	 * @param	integer	The user id of contact.	 
	 *
	 * @return	mixed item data object on success, false on failure.
	 */	
	public function &getItem ( $user_id = 0 )
	{
		$user_id = (!empty($user_id)) ? $user_id : (int) JFactory::getUser()->id;

		if ($this->_item === null) {
			$this->_item = array();
		}		
		
		if (!isset($this->_item[$user_id])) {			
			$db   = $this->getDbo();
			
			$query  = 'SELECT a.*,u.email FROM #__sfs_contacts AS a';
			$query .= ' INNER JOIN #__users AS u ON u.id=a.user_id';
			$query .= ' WHERE a.user_id='.$user_id.' AND u.block=0';
			
			$db->setQuery($query,0,1);
			$this->_item[$user_id] = $db->loadObject();
			
			if( empty($this->_item[$user_id]) ) {
				throw new Exception( $db->getErrorMsg() );
			}		
				
		}
		return $this->_item[$user_id] ;
	}	

	//get all contacts of airline or hotel
	public function &getItems($group_type,$group_id)
	{		
		if( $group_type && $group_id){		
			if ($this->_items === null) {
				$this->_items = array();
			}			
			if (!isset($this->_items[$group_type][$group_id])) {		
				$db   = $this->getDbo();				
				$query  = 'SELECT a.*,u.email FROM #__sfs_contacts AS a';
				$query .= ' INNER JOIN #__users AS u ON u.id=a.user_id';			
				$query .= ' WHERE a.grouptype='.(int)$group_type.' AND a.group_id='.$group_id.' AND u.block=0';												
				$db->setQuery($query);
				
				$this->_items[$group_type][$group_id] = $db->loadObjectList();
			}
			return $this->_items[$group_type][$group_id] ;		
		}
		return null;
	}
	
	public function save($contact) 
	{
		if( ! isset($contact) ) return false;

		$table = JTable::getInstance('SFSContact','JTable');
		
		if( (int) $contact['id'] > 0 ) {			
			$table->load($contact['id']);		
					
			$table->job_title	= $contact['job_title'];
			$table->name 		= $contact['name'];
			$table->surname 	= $contact['surname'];
			$table->gender 		= $contact['gender'];
			$table->telephone   = SfsHelper::getPhoneString( $contact['phone_code'], $contact['phone_number']);
			$table->fax 		= SfsHelper::getPhoneString( $contact['fax_code'], $contact['fax_number']);
			$table->mobile 		= SfsHelper::getPhoneString( $contact['mobile_code'], $contact['mobile_number']);
			
			if( !$table->store() ) {				
				return false;
			}		
				
			if($table->user_id) {
				$user = JUser::getInstance($table->user_id);
				if( isset($user) ) {
					$user->name = $table->name.' '.$table->surname;
					$user->email = $contact['email'];
					if( !$user->save() ) {
						//$msg = 'E-Mail in use';
					}						
				}
			}
		} else {		
			$airline  = SFactory::getAirline();	
			$contactUserId = $this->saveAccount($contact);
			if ( ! $contactUserId ) {
				return false;
			}	
			$db = JFactory::getDbo();
			// Set the new user group maps.
			
			$db->setQuery(
				'INSERT INTO #__sfs_airline_user_map (airline_id, user_id)' .
				' VALUES ('.$airline->id.', '.$contactUserId.')'
			);
			$db->query();

			$data = new stdClass();
			
			if($airline->grouptype ==2 ) {
				$data->grouptype 	= constant('SFSCore::AIRLINE_GROUP');	
			} else {
				$data->grouptype 	= constant('SFSCore::GROUND_HANDLER_GROUP');
			}
						
			$data->group_id 	= $airline->id;
			$data->user_id		= $contactUserId;
			$data->is_admin 	= $contact['main_contact'];
			$data->gender 		= $contact['gender'];		
			$data->name 		= $contact['name'];
			$data->surname 		= $contact['surname'];
			$data->job_title 	= $contact['job_title'];
			
			$data->telephone 	= SfsHelper::getPhoneString($contact['phone_code'] , $contact['phone_number'] );
			$data->fax 			= SfsHelper::getPhoneString($contact['fax_code'] , $contact['fax_number'] );
			$data->mobile 		= SfsHelper::getPhoneString($contact['mobile_code'] , $contact['mobile_number'] );
			
			if ( ! $table->bind( $data ) ) 
			{ 
				$this->setError('Can not bind contact data');
				return false;
			}
			
			if( ! $table->check() ) {
				$this->setError( $table->getError() );
				return false;
			}
			
			if( ! $table->store() ) {
				$this->setDbo($table->getError());
				return false;
			}														
		}
		return true;
	}
	
	private function saveAccount( $contact )
	{
		$date = JFactory::getDate();
		$session = JFactory::getSession();
		
		$airline  = SFactory::getAirline();		
		
		$db = $this->getDbo();
		
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_users');
		
		$userData = array();		
		$user = new JUser;
				
		//get user group id
		if($airline->grouptype ==2 ) {
			$airlineAdminGroup = 'Airline Administrator';
			$airlineStaffGroup = 'Airline Staff';	
		} else {
			$airlineAdminGroup = 'GH Administrator';
			$airlineStaffGroup = 'GH Staff';
		}
		
		$groupId = 0;				
		$contact['main_contact'] = (int) $contact['main_contact'];
		
		if( $contact['main_contact'] == 1 ) {
			$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote($airlineAdminGroup));	
		} else if( $contact['main_contact']==0 ) {
			$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote($airlineStaffGroup));
		}
		
		$groupId = (int) $db->loadResult();
		
		if( ! $groupId ) $groupId = 2;
			
		$userData['groups']   = array( (int) $groupId );
		$userData['name']     = $contact['name'].' '.$contact['surname'];		
		$userData['password'] = SfsHelper::createRandomPassword();
		$userData['email']	  = $contact['email'];
		
		$userData['username'] = null;
			
		jimport('joomla.user.helper');
		
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
		
		// Check if the user needs to activate their account.
		if (($useractivation == 1) || ($useractivation == 2)) {			        			
			jimport('joomla.user.helper');	
			$userData['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());
			$userData['block'] = 1;
		}
				
		// Bind the data.
		if (!$user->bind($userData)) {
			$this->setError(JText::sprintf('COM_SFS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}		
		
		$user->setParam('timezone', $airline->time_zone);
		$user->setParam('editor', '');
		$user->setParam('language', '');
		
		// Store the data.
		if (!$user->save()) {
			$this->setError(JText::sprintf('COM_SFS_REGISTRATION_SAVE_FAILED', $user->getError()));
			return false;
		}		
				
	    // Compile the notification mail values.
		$config = JFactory::getConfig();
						
		$data = $user->getProperties();
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['siteurl']	= JUri::base();	
		
		
		// Handle account activation/confirmation emails.
		if ($useractivation == 2)
		{
			// Set the link to confirm the user email.
			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);

			$emailSubject	= JText::sprintf(
				'COM_SFS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_SFS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
				$data['name'],
				$data['sitename'],
				$data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$data['activation'],
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);
		}
		else if ($useractivation == 1)
		{
			// Set the link to activate the user account.
			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);

			$emailSubject	= JText::sprintf(
				'COM_SFS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_SFS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
				$data['name'],
				$data['sitename'],
				$data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$data['activation'],
				$data['siteurl'],
				$data['username'],
				$data['password_clear']
			);
		} else {

			$emailSubject	= JText::sprintf(
				'COM_SFS_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_SFS_EMAIL_REGISTERED_BODY',
				$data['name'],
				$data['sitename'],
				$data['siteurl']
			);
		}		
		
		$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
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
	
}

