<?php
defined('_JEXEC') or die;

jimport('joomla.user.helper');
jimport('joomla.mail.helper');

class SfsModelTaxiregister extends JModelLegacy
{
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);		
	}	
	
	public function register()
	{
		$app = JFactory::getApplication('site');
		
		$taxiDetails 		= $app->getUserState('taxi.register.data.taxidetails', null);
		$userData		 	= $app->getUserState('taxi.register.data.accountdetails', null);	
		
		if( ! $this->validate($taxiDetails,$userData) )
		{						
			return false;
		}		
				 					
		$userId = $this->saveAccount($userData);	
	
		if ( !$userId ) {
			return false;
		}	
		
		$taxiDetails['email'] = $userData['email'];
		$taxiId = $this->saveTaxi($userId, $taxiDetails);
				
		if ( !$taxiId ) 
		{
			$this->setError('Store data failed');
			return false;
		}		
		
		$contactId = $this->saveContact($userId,$taxiId,$userData);
				
		if( ! $contactId ) {
			$this->setError('Store contact failed');
			return false;				
		}				
		
		$this->_adminEmail($taxiId);		
		return true;
	}
	
	public function validate($taxiDetails,$userData)
	{		
		$db =  $this->getDbo();
		
		if( empty($taxiDetails['name']) )
		{
			$this->setError('Company name is required field');			
		}
		if( empty($taxiDetails['address']) )
		{
			$this->setError('Address is required field');			
		}
		if( empty($taxiDetails['city']) )
		{
			$this->setError('City is required field');			
		}
		if( empty($taxiDetails['country_id']) )
		{
			$this->setError('Country is required field');			
		}
		if( empty($taxiDetails['phone_code']) || empty($taxiDetails['phone_number']) )
		{
			$this->setError('Telephone is required field');			
		}
		if( empty($taxiDetails['fax_code']) || empty($taxiDetails['fax_number']) )
		{
			$this->setError('Fax is required field');			
		}		
		if( empty($taxiDetails['billing_registed_name']) )
		{
			$this->setError('Name of head of accounting is required field');			
		}
		if( empty($taxiDetails['billing_address']) )
		{
			$this->setError('Billing address is required field');			
		}
		if( empty($taxiDetails['billing_city']) )
		{
			$this->setError('Billing city is required field');			
		}
		if( empty($taxiDetails['billing_country_id']) )
		{
			$this->setError('Billing country is required field');			
		}
		if( empty($taxiDetails['billing_phone_code']) || empty($taxiDetails['billing_phone_number']) )
		{
			$this->setError('Billing telephone is required field');			
		}
		if( empty($taxiDetails['billing_fax_code']) || empty($taxiDetails['billing_fax_number']) )
		{
			$this->setError('Billing fax is required field');			
		}
		if( empty($taxiDetails['billing_vat_number']) )
		{
			$this->setError('VAT number is required field');			
		}	
		if( empty($userData['first_name']) )
		{
			$this->setError('Please enter your first name');			
		}
		if( empty($userData['last_name']) )
		{
			$this->setError('Please enter your last name');			
		}
		if( empty($userData['email']) )
		{
			$this->setError('Please enter your email');			
		}
		if( empty($userData['username']) )
		{
			$this->setError('Please enter your username');			
		}
		if( empty($userData['password']) )
		{
			$this->setError('Please enter your password');			
		}		

		if($userData['password'] != $userData['password2'] ) {
			$this->setError(JText::_('COM_SFS_REGISTRATION_PASSWORD_DOES_NOT_MATCH'));
			return false;			
		}
		
		$query = 'SELECT COUNT(*) FROM #__users WHERE username='.$db->quote($userData['username']);
		$db->setQuery($query);
		$count = $db->loadResult();
		
		if( $count )
		{
			$this->setError('Username was not available please retry');
		}
		
		$errors = $this->getErrors();
		if(count($errors))
		{				
			return false;
		}
				
		return true;
	}

	private function saveTaxi( $userId , & $taxiDetails )
	{				
		$db  = $this->getDbo();	
		
		$table = JTable::getInstance('Taxi','JTable');
		
		$taxiDetails['telephone'] 			= $taxiDetails['phone_code'].$taxiDetails['phone_number'];
		$taxiDetails['fax']		  			= $taxiDetails['fax_code'].$taxiDetails['fax_number'];
		$taxiDetails['mobile']				= $taxiDetails['mobile_code'].$taxiDetails['mobile_number'];
		$taxiDetails['billing_telephone'] 	= $taxiDetails['billing_phone_code'].$taxiDetails['billing_phone_number'];
		$taxiDetails['billing_fax']			= $taxiDetails['billing_fax_code'].$taxiDetails['billing_fax_number'];
				
		$taxiDetails['profile_type'] = 'taxi';
		$taxiDetails['created'] 	 = JFactory::getDate()->toSql();
		$taxiDetails['created_by']  = $userId;
		
		$notification['email'][]  = $taxiDetails['email'];
		$notification['fax'][]    = $taxiDetails['fax'];
		$notification['mobile'][] = $taxiDetails['mobile'];

		$registry = new JRegistry;
		$registry->loadArray($notification);	
		$taxiDetails['notification'] = $registry->toString();	
		
		 
		if( !$table->bind($taxiDetails) ) {
			$this->setError($table->getError());
			return false;					
		}				
		if( !$table->check() ) {
			$this->setError($table->getError());
			return false;					
		}
		if( !$table->store() ) {
			$this->setError($table->getError());
			return false;					
		}
		
		$taxiDetails['id'] = $table->get('id');
			
		return $taxiDetails['id'];
	}
	
	private function saveAccount( $accountDetails )
	{
		$date 		= JFactory::getDate();
		$session 	= JFactory::getSession();
				
		$db = $this->getDbo();
		
		$app	= JFactory::getApplication();
		$params	= JComponentHelper::getParams('com_users');
		$config = JFactory::getConfig();
		
		$userData = array();		
		$user = new JUser;
				
		//get user group id
		$groupId = 0;				
				
		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Taxi'));
		$groupId = (int) $db->loadResult();
		
		if( ! $groupId ) {
			$this->setError('Taxi group was not found');
			return false;			
		}
		
		if($accountDetails['password'] != $accountDetails['password2'] ) {
			$this->setError(JText::_('COM_SFS_REGISTRATION_PASSWORD_DOES_NOT_MATCH'));
			return false;			
		}
			
		$userData['groups']   = array( (int) $groupId );
		$userData['name']     = $accountDetails['first_name'].' '.$accountDetails['last_name'];		
		$userData['password'] = $accountDetails['password'];		
		$userData['email']	  = $accountDetails['email'];		
		$userData['username'] = $accountDetails['username'];		
	
		$useractivation = $params->get('useractivation');
					        			
		jimport('joomla.user.helper');	
		$userData['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());
		$userData['block'] = 1;
		
		// Bind the data.
		if (!$user->bind($userData)) {
			$this->setError(JText::sprintf('COM_SFS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}
		
		$user->setParam('timezone', $config->get('offset') );
		$user->setParam('editor', '');
		$user->setParam('language', '');
		
		// Store the data.
		if ( !$user->save() ) {
			$this->setError(JText::sprintf('COM_SFS_REGISTRATION_SAVE_FAILED', $user->getError()));
			return false;
		}		
		
	    // Compile the notification mail values.								
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
				
		$emailBody = JText::sprintf(
			'COM_SFS_EMAIL_TAXI_REGISTERED_BODY',
			$data['name'],
			$data['sitename'],				
			$data['siteurl'],
			$data['username'],
			$data['password_clear']
		);
		$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);				
			
		
		return (int)$user->id;			
	}
	
	private function saveContact( $userId , $taxiId , $contact )
	{				
		$db = $this->getDbo();
		$db->setQuery(
			'INSERT INTO #__sfs_taxi_user_map(taxi_id, user_id)' .
			' VALUES ('.$taxiId.', '.$userId.')'
		);
		
		if( !$db->query() )
		{
			$this->setError($db->getErrorMsg());
			return false;	
		}		
		
		$row = JTable::getInstance('SFSContact', 'JTable');
		
		$data = new stdClass();
		
		//4 for bus, 5 for taxi
		$data->grouptype 	= 5;
		$data->group_id 	= $taxiId;
		$data->user_id		= $userId;
		$data->is_admin 	= 1;		
		$data->name 		= $contact['first_name'];
		$data->surname 		= $contact['last_name'];
					
		if ( ! $row->bind( $data ) ) 
		{ 
			$this->setError('Can not bind user data');
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
		
	private function _adminEmail($taxiId)
	{
		// Compile the notification mail values.
		$config = JFactory::getConfig();
		$data = array();
						
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['approveurl']	= JUri::base().'administrator/index.php?option=com_sfs&view=taxi&layout=edittaxi&taxi_id='.$taxiId;		
		
						
		$emailSubject	= JText::sprintf(
			'COM_SFS_ADMIN_NOTIFICALTION_TAXI_REGISTER_SUBJECT',
			$data['sitename']			
		);
				
		$emailBody = JText::sprintf(
			'COM_SFS_ADMIN_NOTIFICALTION_TAXI_REGISTER_BODY',
			$data['sitename'],
			$data['approveurl']			
		);				
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('u.email');
		$query->from('#__users AS u');
		$query->innerJoin('#__user_usergroup_map AS um ON um.user_id=u.id AND (um.group_id=8 OR um.group_id=7)');
		
		$db->setQuery($query);
		
		$admins = $db->loadObjectList();
		
		foreach ($admins as $admin)
		{
			JUtility::sendMail($data['mailfrom'], $data['fromname'], $admin->email, $emailSubject, $emailBody);	
		}								
	}
	
}

