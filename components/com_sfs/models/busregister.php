<?php
defined('_JEXEC') or die;

jimport('joomla.user.helper');
jimport('joomla.mail.helper');

class SfsModelBusregister extends JModelLegacy
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
		
		$busDetails  = $app->getUserState('bus.register.data.busdetails', null);
		$userData    = $app->getUserState('bus.register.data.accountdetails', null);
		
		if( ! $this->validate($busDetails,$userData) )
		{						
			return false;
		}		
				 					
		$userId = $this->saveAccount($userData);	
	
		if ( !$userId ) {
			return false;
		}	
	
		$busDetails['email'] = $userData['email'];
		$busId = $this->saveBus($userId, $busDetails);
				
		if ( !$busId ) 
		{
			$this->setError('Store data failed');
			return false;
		}		
		
		$contactId = $this->saveContact($userId,$busId,$userData);
				
		if( ! $contactId ) {
			$this->setError('Store contact failed');
			return false;				
		}				
		
		$this->_adminEmail();		
		return true;
	}
	
	public function validate($busDetails,$userData)
	{				
		
		$db = $this->getDbo();
		if( empty($busDetails['name']) )
		{
			$this->setError('Company name is required field');			
		}
		if( empty($busDetails['address']) )
		{
			$this->setError('Address is required field');			
		}
		if( empty($busDetails['city']) )
		{
			$this->setError('City is required field');			
		}
		if( empty($busDetails['country_id']) )
		{
			$this->setError('Country is required field');			
		}
		if( empty($busDetails['phone_code']) || empty($busDetails['phone_number']) )
		{
			$this->setError('Telephone is required field');			
		}
		if( empty($busDetails['fax_code']) || empty($busDetails['fax_number']) )
		{
			$this->setError('Fax is required field');			
		}		
		if( empty($busDetails['billing_name']) )
		{
			$this->setError('Name of head of accounting is required field');			
		}
		if( empty($busDetails['billing_address']) )
		{
			$this->setError('Billing address is required field');			
		}
		if( empty($busDetails['billing_city']) )
		{
			$this->setError('Billing city is required field');			
		}
		if( empty($busDetails['billing_country_id']) )
		{
			$this->setError('Billing country is required field');			
		}
		if( empty($busDetails['billing_phone_code']) || empty($busDetails['billing_phone_number']) )
		{
			$this->setError('Billing telephone is required field');			
		}
		if( empty($busDetails['billing_fax_code']) || empty($busDetails['billing_fax_number']) )
		{
			$this->setError('Billing fax is required field');			
		}
		if( empty($busDetails['billing_tva_number']) )
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

	private function saveBus( $userId , & $busDetails )
	{				
		$db  = $this->getDbo();	
		
		$busTable = JTable::getInstance('Bus','JTable');


		$busDetails['telephone'] = $busDetails['phone_code'].$busDetails['phone_number'];
		$busDetails['fax']		 = $busDetails['fax_code'].$busDetails['fax_number'];
		$busDetails['mobile']		 = $busDetails['mobile_code'].$busDetails['mobile_number'];
		$busDetails['billing_telephone'] = $busDetails['billing_phone_code'].$busDetails['billing_phone_number'];
		$busDetails['billing_fax']		 = $busDetails['billing_fax_code'].$busDetails['billing_fax_number'];
		
		$busDetails['created_by'] = $userId;
		$busDetails['created'] = JFactory::getDate()->toSql();
		
		$notification['email'][]  = $busDetails['email'];
		$notification['fax'][]    = $busDetails['fax'];
		$notification['mobile'][] = $busDetails['mobile'];
		$busDetails['airport_id'] = $busDetails['airport'];
		$busDetails['currency_id'] = $busDetails['currency'];

		$registry = new JRegistry;
		$registry->loadArray($notification);	
		$busDetails['notification'] = $registry->toString();		
		
		if( !$busTable->bind($busDetails) ) {
			$this->setError($busTable->getError());
			return false;					
		}				
		if( ! $busTable->check() ) {
			$this->setError($busTable->getError());
			return false;					
		}
		if( ! $busTable->store() ) {
			$this->setError($busTable->getError());
			return false;					
		}

		$busDetails['id'] = $busTable->get('id');
			
		return $busDetails['id'];
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
				
		$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Bus'));
		$groupId = (int) $db->loadResult();
		
		if( ! $groupId ) $groupId = 2;
		
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
			'COM_SFS_EMAIL_BUS_REGISTERED_BODY',
			$data['name'],
			$data['sitename'],				
			$data['siteurl'],
			$data['username'],
			$data['password_clear']
		);
		$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);				
			
		
		return (int)$user->id;			
	}
	
	private function saveContact( $userId , $busId , $contact )
	{				
		$db = $this->getDbo();
		$db->setQuery(
			'INSERT INTO #__sfs_group_transportation_user_map(group_transportation_id, user_id)' .
			' VALUES ('.$busId.', '.$userId.')'
		);
		
		if( !$db->query() )
		{
			$this->setError($db->getErrorMsg());
			return false;	
		}		
		
		$row = JTable::getInstance('SFSContact', 'JTable');
		
		$data = new stdClass();
		
		//4 for bus, 5 for taxi
		$data->grouptype 	= 4;
		$data->group_id 	= $busId;
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
		
	private function _adminEmail($busId)
	{
		// Compile the notification mail values.
		$config = JFactory::getConfig();
		$data = array();
						
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['approveurl']	= JUri::base().'administrator/index.php?option=com_sfs&view=grouptransportlist';		
		
						
		$emailSubject	= JText::sprintf(
			'COM_SFS_ADMIN_NOTIFICALTION_BUS_REGISTER_SUBJECT',
			$data['sitename']			
		);
				
		$emailBody = JText::sprintf(
			'COM_SFS_ADMIN_NOTIFICALTION_BUS_REGISTER_BODY',
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

