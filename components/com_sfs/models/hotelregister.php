<?php
defined('_JEXEC') or die();
// import Joomla modelform library
jimport('joomla.application.component.model');

class SfsModelHotelRegister extends JModel
{			
		
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);		
	}	
	
	/**
	 * Method to save the hotel data in to database. 
	 * 
	 * This is the first step. Therefore an joomla user account, a hotel and a main contact must be saved.
	 *
	 * @param	array		The post data.
	 * @return	mixed		The hotel id on success, false on failure.
	 */	
	public function register( $data ) 
	{
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_users');
		
		$date = JFactory::getDate();
		$db = $this->getDbo();
		
		$userData = array();		
		$user = new JUser;
		
		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Hotel Administrator'));
		$group_id = $db->loadResult();
		
		if( ! $group_id ) $group_id = 2;

		if($data['password'] != $data['password2'] ) {
			$this->setError(JText::_('COM_SFS_REGISTRATION_PASSWORD_DOES_NOT_MATCH'));
			return false;			
		}
		if($data['email'] != $data['email2'] ) {
			$this->setError(JText::_('COM_SFS_REGISTRATION_EMAIL_DOES_NOT_MATCH'));
			return false;			
		}
		
		$userData['groups']   = array((int)$group_id);
		$userData['name']     = $data['name'].' '.$data['surname'];
		$userData['username'] = $data['username'];
		$userData['password'] = $data['password'];			
		$userData['email']	  = $data['email'];
		
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
		
		// Store the data.
		if (!$user->save()) {
			$this->setError(JText::sprintf('COM_SFS_REGISTRATION_SAVE_FAILED', $user->getError()));
			return false;
		}
		
		// Store hotel
		$hotel = new SHotel();
		
		$hotelData = array();
		
		$hotelData['name']		 = $data['hotel_name'];		
		$hotelData['chain_id']   = $data['chain_id'];		
		$hotelData['created_by'] = $user->id;
		
		if ( ! $hotel->bind( $hotelData ) ) {
			$this->setError($hotel->getError());
			return false;
		}
		
		if( ! $hotel->save() ) {
			$this->setError($hotel->getError());
			return false;
		}		
				
		$db->setQuery(
				'INSERT INTO #__sfs_hotel_user_map (hotel_id, user_id)' .
				' VALUES ('.$hotel->id.', '.$user->id.')'
		);
		$db->query();		
		
		//Store main contact		
		$contactData = array();
		$contactData['user_id'] 	= $user->id;
		$contactData['grouptype']   = constant('SFSCore::HOTEL_GROUP');
		$contactData['group_id']  	= $hotel->id;
		$contactData['is_admin'] 	= 1;
		$contactData['gender'] 		= $data['gender'];
		$contactData['name'] 		= $data['name'];
		$contactData['surname'] 	= $data['surname'];
		$contactData['job_title'] 	= $data['job_title'];
		$contactData['telephone'] 	= SfsHelper::getPhoneString($data['tel_code'],$data['tel_number'] ) ;
		$contactData['fax'] 		= SfsHelper::getPhoneString($data['fax_code'],$data['fax_number']);
		$contactData['mobile'] 		= SfsHelper::getPhoneString($data['mobile_code'],$data['mobile_number']);
		$contactData['systemEmails']= '{"booking":"1","voucher":"1","roomloading":"1","low_availlability":"1"}';
		
		$contactTable = JTable::getInstance('Sfscontact','JTable');
		
		if( ! $contactTable->bind($contactData) ) {
			$this->setError($contactTable->getError());
			return false;
		}
		
		if( ! $contactTable->check() ) {
			$this->setError($contactTable->getError());			
			return false;
		}
		
		if( ! $contactTable->store() ) {
			$this->setError($contactTable->getError());			
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
				'COM_SFS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
				$data['name'],
				$data['sitename'],				
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
		
		// Send the registration email.
		$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

		// Check for an error.
		if ($return !== true) {
			$this->setError(JText::_('COM_SFS_REGISTRATION_SEND_MAIL_FAILED'));

			// Send a system message to administrators receiving system mails			
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
			//return false;
		}		
						
		
		//None of the above When none of the above is selected the SFS administrator 
		//should receive a warning per email to check the loading on this hotel on chain affiliation.		
		$sfsParams 	 = JComponentHelper::getParams('com_sfs');	
		$adminEmails = $sfsParams->get('sfs_system_emails');	
		if( strlen($adminEmails) )
		{						
			$sendEmails = explode(';', $adminEmails);
			if (count($sendEmails) > 0) {	
				$emailSubject	= JText::sprintf(
					'COM_SFS_ADMIN_NOTIFICALTION_HOTEL_REGISTER_SUBJECT',
					$hotelData['name'],
					$data['sitename']
				);
				$hotelAdminUrl = JURI::base().'administrator/index.php?option=com_sfs&task=hotel.edit&id='.(int)$hotel->id;
				$emailBody = JText::sprintf(
					'COM_SFS_ADMIN_NOTIFICALTION_HOTEL_REGISTER_BODY',
					$hotelAdminUrl				
				);					
				foreach ($sendEmails as $adminEmail) {									
					if( (int)$hotel->chain_id == 0 ) {	
						JUtility::sendMail($user->email, $user->name, $adminEmail, JText::_('COM_SFS_NONE_OF_ABOVE_WARNING_SUBJECT') , JText::sprintf('COM_SFS_NONE_OF_ABOVE_WARNING', $hotel->name, JURI::base().'administrator/index.php?option=com_sfs&task=hotel.edit&id='.$hotel->id) );
					}
					JUtility::sendMail($data['mailfrom'], $data['fromname'], $adminEmail, $emailSubject , $emailBody );				
				}			
			}			
		}
		
		return "useractivate";
	}
	
	
	public function saveHotel( $data )
	{				
		$hotel = SFactory::getHotel();
		
		if( $hotel->id ) {
			if ( (int) $hotel->id  == (int)$data['hotel_id'] ) {
				$data['telephone'] =  SfsHelper::getPhoneString( $data['tel_code'] , $data['tel_number'] );		
				$data['fax'] 	   =  SfsHelper::getPhoneString( $data['fax_code'] , $data['fax_number'] );
				
				if( (int)$hotel->step_completed < 7 ) $hotel->step_completed = 7;	
				
				// Save billing
				$billingTable = JTable::getInstance('Billing');
				
				$billingData  = JRequest::getVar('billing',array(),'post','array');
				
				//var_dump($billingTable);die;
								
				if( ! $billingTable->bind($billingData) ) {
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
				
				if( empty($hotel->billing_id) ) {
					$hotel->billing_id = $billingTable->get('id');
				}
												
				if ( ! $hotel->bind( $data ) ) {
					$this->setError($hotel->getError());
					return false;
				}								
				if( ! $hotel->save() ) {
					$this->setError($hotel->getError());
					return false;
				}		

				//update timezone for JUser object
				$user = JFactory::getUser();				
				$user->setParam('timezone', $data['time_zone']);
				$user->setParam('editor', '');
				$user->setParam('language', '');			
				
				// Store the data.
				if (!$user->save(true)) {
					$this->setError(JText::sprintf('COM_SFS_REGISTRATION_SAVE_FAILED', $user->getError()));
					return false;
				}			

				$contacts = JRequest::getVar('contacts',array(), 'post', 'array');
							
				$this->saveContacts($contacts);				
				
				return true;
			}
		}

		return false;
	}
	
	protected function saveContacts($data) {
		$hotel = SFactory::getHotel();
				 
		if( ! $hotel->id ) return false;
		
		if( !SFSAccess::check( JFactory::getUser() , 'h.admin') ) 
		{
			$this->setError('Hotel Error Access');
			return false;
		}
		
		$db = $this->getDbo();		
		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Hotel Staff'));
		$group_id = $db->loadResult();
		
		if( ! $group_id ) $group_id = 2;		
		
		foreach ( $data as $key => $contact) {
			$hotel->saveContact($contact,$group_id);						
		}			
				
	}
		
}
