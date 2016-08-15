<?php
// No direct access
defined('_JEXEC') or die;

class SHotel extends SfsHandlerDecorator
{				
	public $name = null;
	public $alias = null;
	public $star = null;
	public $chain_id = null;
		
	public $web_address = null;
	public $telephone = null;
	public $fax = null;
	public $address = null;
	public $address1 = null;
	public $address2 = null;
	public $zipcode = null;
	public $city = null;	
	public $state_id = null;
	public $country_id = null;
	public $location_id = null;
		
	public $geo_location_latitude = null;
	public $geo_location_longitude = null;
	
	public $block = null;
	public $time_zone = null;
		
	public $created_by = null;
	public $created_date = null;	
	public $modified_by = null;	
	public $modified_date = null;	
	
	public $step_completed = null;
	
	//public $signup_complete = null;
	
	private $_taxes 	   = null;
	private $_mealplan = null;
	private $_transport = null;
	
	private $_backend_setting = null;
		
	/**
	 * SHotel Constructor
	 *
	 * @param	int		$identifier	The primary key of the hotel to load (optional).
	 *
	 * @return	SHotel
	 * @since	1.0
	 */
	public function __construct( $identifier = 0, $db = null )
	{
		parent::__construct();
		
		if($db !== null)
		{
			$this->setDbo($db);	
		}
		
		$this->set('grouptype',1);
				
		if ( ! empty($identifier) ) {						
			$this->load($identifier);
		}
		else {
			// if a hotel user is logged in then Load the hotel
			$session = JFactory::getSession();			
			$user = JFactory::getUser();
			
			if( SFSAccess::isHotel($user) ) {
				$hotelId = (int) $session->get('hotel_id',0);
				if( ! empty($hotelId) ) {
					$this->load($hotelId) ;
				} else {
					$this->load($user->id,'user') ;	
				}
				
			} else {
				$this->id = 0;	
			}							
		}
	}	
	
	/**
	 * Returns the global Hotel object, only creating it if it doesn't already exist.
	 *
	 * @param	int	$id	The hotel to load - Can be an integer or string
	 *
	 * @return	SHotel	The hotel object.
	 * @since	1.0
	 */		
	public static function getInstance( $id = 0 )
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}
		
		if (empty($instances[$id])) {						
			$hotel = new SHotel($id);
			$instances[$id] = $hotel;			
		}
		
		return $instances[$id];
	}	
	
	/**
	 * Return true if the hotel was registed successfully 9 steps.
	 * 
	 */
	public function isRegisterComplete()
	{		
		$isComplete = false;
		if( (int) $this->step_completed == 9 )
		{
			$isComplete = true;
		}		
		return  $isComplete;
	}
	
	/**
	 * Return true if the hotel is blocked.
	 * 
	 */
	public function isBlock()
	{
		return  ( (int)$this->block == 1 ) ? true : false;
	}
	
	/**
	 * Method to get the hotel room detail
	 *
	 * @return	object	The hotel room detail object.
	 * @since	1.0
	 */	
	public function getAirportIDs( $onlyFirst = false )
	{	
		if( $this->id ) {			
			$db = $this->getDbo();	
			$query = 'SELECT airport_id FROM #__sfs_hotel_airports WHERE hotel_id='.$this->id.' ORDER BY distance ASC';
			$db->setQuery($query);
			if($onlyFirst) {
				$result = $db->loadResult();
			} else {
				$result = $db->loadResultArray();	
			}
			
			return $result;
		}
		return null;
	}	

	/**
	 * Method to get the hotel room detail
	 *
	 * @return	object	The hotel room detail object.
	 * @since	1.0
	 */	
	public function getRoomDetail()
	{	
		if( $this->id ) {			
			$table = JTable::getInstance('HotelRoom','JTable');
			$table->load( array('hotel_id' => $this->id) );
			return $table;			
		}
		return null;
	}
	
	/**
	 * Method to get the hotel tax
	 *
	 * @return	object	The hotel tax object.
	 * @since	1.0
	 */	
	public function getTaxes()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		if(empty($this->ws_id))
		{
			if( empty($this->_taxes) && !empty($this->id) )
			{
				$query->select('a.*, b.code AS currency_name, b.symbol AS currency_symbol');
				$query->from('#__sfs_hotel_taxes AS a');

				$query->leftJoin('#__sfs_currency AS b ON b.id=a.currency_id');

				$query->where('a.hotel_id='. $this->id );

				$db->setQuery($query);

				$result = $db->loadObject();

				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}

				if (empty($result)) {
					return null;
				}

				$this->_taxes = $result;
			}
		}
		else
		{
            $query->select('a.*, b.code AS currency_name, b.symbol AS currency_symbol');
				$query->from('#__sfs_hotel_taxes AS a');

				$query->leftJoin('#__sfs_currency AS b ON b.id=a.currency_id');

				$query->where('a.hotel_id='. $this->id );

				$db->setQuery($query);

				$result = $db->loadObject();

				if ($error = $db->getErrorMsg()) {
					throw new Exception($error);
				}

				if (empty($result)) {
					return null;
				}

				$this->_taxes = $result;

			$this->_taxes = json_decode(json_encode($result));
		}
		return $this->_taxes;
	}
	
	/**
	 * Method to get the hotel fb mealplan
	 *
	 * @return	object	The hotel mealplan object.
	 * @since	1.0
	 */	
	public function getMealPlan()
	{
		if( empty($this->_mealplan) && ! empty($this->id) )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('
				course_1,
				course_2,
				course_3,				
				tax,
				stop_selling_time,
				service_hour,
				service_opentime,
				service_closetime,
				service_outside,
				bf_standard_price,
				bf_layover_price,
				bf_tax,
				bf_service_hour,
				bf_opentime,
				bf_closetime,
				bf_outside,
				lunch_standard_price,				
				lunch_tax,
				lunch_service_hour,
				lunch_opentime,
				lunch_closetime,available_days,lunch_available_days'
			);
						
			$query->from('#__sfs_hotel_mealplans');
			$query->where('hotel_id='. $this->id );
			
			$db->setQuery($query);
			
			$mealplan = $db->loadObject();
			
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}
			
			if (empty($mealplan)) {
				return null;
			}			
			
			$this->_mealplan = $mealplan;
		}	
		return $this->_mealplan;
	}	
	
	
	/**
	 * Method to get the hotel transport detail
	 *
	 * @return	object	The hotel transport object.
	 * @since	1.0
	 */	
	public function getTransportDetail()
	{
		if( empty($this->_transport) && ! empty($this->id) )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
						
			$query->from('#__sfs_hotel_transports');
			$query->where('hotel_id='. $this->id );
			
			$db->setQuery($query);
			
			$transport = $db->loadObject();
			
			if ($error = $db->getErrorMsg()) {
				throw new Exception($error);
			}
			
			if (empty($transport)) {
				return null;
			}			
			
			$this->_transport = $transport;
		}	
		return $this->_transport;
	}	
	
	// Gets hotel setting made SFS administrator
	public function getBackendSetting()
	{
		if( empty($this->_backend_setting ) ) 
		{
			if( $this->id ) {	
									
				$db 	= $this->getDbo();	
				$query  = 'SELECT * FROM #__sfs_hotel_backend_params WHERE hotel_id='.$this->id;
				$db->setQuery($query);
				
				$result = $db->loadObject();
				
				if( $error = $db->getErrorMsg() )
				{
					throw new Exception($error);
				}			
				$this->_backend_setting = $result;				
			}
		}
		
		return $this->_backend_setting;
	}
	
	/**
	 * Method to load a SHotel object by hotel id number
	 *
	 * @param	mixed	$id	The hotel id of the hotel to load
	 *
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	public function load( $id, $type = 'pk' )
	{		
		$pk = $id;
	
		if( $type != 'pk' && $type=='user' ) {
			$contact = SFactory::getContact( $id );
			if( ! empty($contact) ) 
			{
				$pk = $contact->group_id;
			} 			
		} 	
		
		//get database object
		$db = $this->getDbo();
		
		//get new Query Object
		$query = $db->getQuery(true);
		
		$query->select('a.*');
		$query->from('#__sfs_hotel AS a');
		
		//join to country
		$query->select('b.name AS country_name');
		$query->leftJoin('#__sfs_country AS b ON b.id=a.country_id');
			
		//join to chain
		$query->select('c.name AS chain_name');
		$query->leftJoin('#__sfs_hotel_chains AS c ON c.id=a.chain_id');
		
		//join to state
		$query->select('d.name AS state_name');
		$query->leftJoin('#__sfs_states AS d ON d.id=a.state_id');
				
		//join to state
		$query->select('p.airport_id');
		$query->leftJoin('#__sfs_hotel_airports AS p ON p.hotel_id=a.id');

		$query->where('a.id='. (int) $pk);
		
		$db->setQuery($query);
		
		$hotel = $db->loadObject();
		
		
		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}
		
		if (empty($hotel)) {
			return JError::raiseError(404,JText::_('Hotel not found'));
		}
		
		$vars = get_object_vars($hotel);
		
		foreach ($vars as $key => $value) {
			$this->$key = $value;				
		}
				
		return true;
	}	
	
	
	/**
	 * Method to get the hotel table object
	 *
	 */
	public function getTable()
	{
		return JTable::getInstance('Hotel', 'JTable');
	}	

	/**
	 * Method to bind an associative array of data to a hotel object
	 *
	 * @param	array	$array	The associative array to bind to the object
	 *
	 * @return	boolean	True on success
	 * @since	1.5
	 */	
	public function bind( & $data )
	{
		
		// Lets check to see if the hotel is new or not
		if (empty($this->id)) {	
			
			if( empty($data['name']) ) {
				$this->setError('Hotel name invalid');
				return false;
			}					
			$this->set( 'alias' , JApplication::stringURLSafe($data['name']) );
			$this->set( 'created_date' , JFactory::getDate()->toSql());
			$this->set( 'block', 0 );	
			$this->set( 'step_completed', 1);		
						
		} else {
			
			if( empty(JFactory::getUser()->id ) ) {
				$this->setError('Guest : Unable to bind array to hotel object');
				return false;	
			}			
			$this->set( 'modified_by' , JFactory::getUser()->id );
			$this->set( 'modified_date' ,JFactory::getDate()->toSql() );
		}
		
		// Bind the array
		if ( ! $this->setProperties($data) ) {
			$this->setError(JText::_('Unable to bind array to hotel object'));
			return false;
		}

		// Make sure its an integer
		$this->id = (int) $this->id;		
		return true;
	}
	
	/**
	 * Method to save the SHotel object to the database
	 *	 
	 *
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	public function save()
	{
		$db		= JFactory::getDbo();	
		$table	= $this->getTable();

		$table->bind($this->getProperties());
		// Allow an exception to be throw.
		try
		{	
			// Check and store the object.
			if (!$table->check()) {
				$this->setError($table->getError());
				return false;
			}
			
			// Set ordering for the hotel
			if (empty($this->id)) {
				$db->setQuery('SELECT MAX(ordering) FROM #__sfs_hotel');
				$maxOrdering = $db->loadResult();
			
				$table->ordering = $maxOrdering + 1;
			}						
			// Store the hotel data in the database
			if (!($result = $table->store())) {
				throw new Exception($table->getError());
			}
			// Set the id for the SHotel object in case we created a new.
			if (empty($this->id)) {
				$this->id = $table->get('id');
			}	
			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
		return true;
	}
	
	public function saveContact($contact,$groupId = null)
	{	
		jimport('joomla.mail.helper');
		
		$db = $this->getDbo();		
		
		if( empty($contact) ) {
			$this->setError('Contact data empty');
			return false;
		}
		if( (int)$this->id == 0 ){
			return false;	
		}								
		if( trim($contact['name']) == '' ) {
			$this->setError('Hotel Contact: '.JText::_('JLIB_DATABASE_ERROR_PLEASE_ENTER_YOUR_NAME'));
			return false;
		}
		if ( (trim($contact['email']) == '' ) || ! JMailHelper::isEmailAddress($contact['email'])) {
			$this->setError('Hotel Contact: '.JText::_('JLIB_DATABASE_ERROR_VALID_MAIL'));
			return false;
		}
		if( empty($contact['telephone']) )
		{
			$contact['telephone'] = SfsHelper::getPhoneString( $contact['tel_int'], $contact['tel_num'] );	
		}	
		if( trim($contact['telephone']) == '' ) {
			$this->setError('Hotel Contact: Please enter your telephone');
			return false;
		}			
		if( empty($contact['fax']) ){
			$contact['fax'] = SfsHelper::getPhoneString($contact['fax_int'], $contact['fax_num']);	
		}
		if( empty($contact['mobile']) ){
			$contact['mobile'] 	= SfsHelper::getPhoneString($contact['mobile_int'], $contact['mobile_num']);	
		}	

		if( $groupId == null ) {		
			$db->setQuery('SELECT id FROM #__usergroups WHERE title='.$db->quote('Hotel Staff'));
			$groupId = $db->loadResult();		
			if( ! $groupId ) $groupId = 2;				
		}	
			
		$contact['group_id']   	= $this->id;
		$contact['grouptype']   = constant('SFSCore::HOTEL_GROUP');
		
		if($contact['id']){
			if( (int) $contact['user_id'] > 0 ){
				$query = 'UPDATE #__users SET name='.$db->quote($contact['name'].' '.$contact['surname']).',email='.$db->quote($contact['email']).' WHERE id='. $contact['user_id'];
				$db->setQuery($query);
				$db->query();
			}								
		} else {
			
			$contact['systemEmails']= '{"booking":"1","voucher":"1","roomloading":"1","low_availlability":"1"}';
			
			jimport('joomla.user.helper');
						
			$app	= JFactory::getApplication();
			$config = JFactory::getConfig();
			$params	= JComponentHelper::getParams('com_users');
			$useractivation = $params->get('useractivation');			
			
			$userData = array();		
			$user = new JUser;

			$userData['groups']   = array($groupId);
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

			// Set Time Zone for hotel contact
			if( ! empty($this->time_zone) ) {
				$user->setParam('timezone', $this->time_zone );
				$user->setParam('editor', '');
				$user->setParam('language', '');	
			}						
			
			// Store contact user
			if (!$user->save()) {
				$this->setError(JText::sprintf('COM_SFS_REGISTRATION_SAVE_FAILED', $user->getError()));				
				return false;
			}								
			$contact['user_id'] = $user->id;		
			
			// Store hotel user map
			$query = 'INSERT INTO #__sfs_hotel_user_map(hotel_id,user_id) VALUES('.$this->id.','.$user->id.')';
			$db->setQuery($query);			
			if( !$db->query() ) {
				$this->setError( $db->getErrorMsg() );		
				return false;
			}				
					
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
				$hotelAdmin = JFactory::getUser(); 
				$emailBody = JText::sprintf(
					'COM_SFS_CONTACT_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
					$data['name'],
					$hotelAdmin->name,
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
			// Send the registration email.
			JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);			
		}	
		
		$contactTable = JTable::getInstance('SFSContact', 'JTable');	
		if( ! $contactTable->bind ($contact) ) {	
			$this->setError( $contactTable->getError() );
			return false;			
		}		
		if( ! $contactTable->store () ) {
			$this->setError( $contactTable->getError() );
			return false;									
		}

		return true;
		
	}
		
}

