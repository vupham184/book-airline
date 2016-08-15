<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class SfsControllerApiforuserbyairlines extends JController {

	public function APILogin()
	{
		$file_name = "apilogin.xml";
		$file =  'tmp/apiforuserbyairlines/' . $file_name;
		$url = JUri::base() . $file;
		$xml_content = self::getDataFile( $url );
		$rawpostxml = new Api_Rawpostxml();
		$xml = new SimpleXMLElement($xml_content);
		//print_r( $xml );die;
		$method = $xml->method;
		if ( $method != 'access/request' ) {
			$this->getError('400');
		}
		$unique = trim( $xml->api_token );
		
		$airline_id = 0;
		$iatacode_id = 0;
		$dAir = self::checkAirlineID( $unique );
		if( $dAir == '' ){
			self::getError('01');
		}
		
		$data = $xml->users->user;
		$airportsAPI = "";
		$objDate =  new stdClass();
		foreach ( $data as $vk => $v ){
			$user_id = $v->user_id;
			$username = $v->username;
			$name = $v->name;
			$user_role = $v->user_role;
			$airportsAPI = $v->airports;
			$emailAddress = $v->emailAddress;
			
			$objDate->user_id = (string)$user_id;
			$objDate->username = (string)$username;
			$objDate->name = (string)$name;
			$objDate->user_role = (string)$user_role;
			$objDate->airports = (string)$airportsAPI;
			$objDate->emailAddress = (string)$emailAddress;
		}
		$dataUser = self::checkUserID($user_id, $username);
		$checkRole = self::checkRole( $user_id, $user_role );
		if( $dataUser == '' ) {
			//call insert user new
			self::isertNotFindUserID($objDate, $dAir);
			//callback get user
			$dataUser = self::checkUserID($user_id, $username);
			//print_r( $dataUser );
			self::login( $dataUser, $airportsAPI);
			//print_r( $checkRole );die;
			///self::getError("02");
		}
		elseif( $checkRole == 0 ){
			self::getError("03");
		}
		else{
			//call update user
			self::UpdateUserID($objDate, $dAir);
			$dataUser = self::checkUserID($user_id, $username);
			//call login
			self::login( $dataUser, $airportsAPI);
		}
	}
	
	public function getDataFile( $url )
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		 //$curl_errno= curl_errno($ch);
		if( $http_status != 200 ){
			$this->getError( $http_status );
		}
		curl_close($ch);
		return $data;
	}
	
	public function checkAirlineID( $unique_token = '' )
	{
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.* FROM #__sfs_airline_details AS a';
        $query .= ' WHERE a.unique_token="' . $unique_token . '"';
        $db->setQuery($query);
        $d = $db->loadObject();
		if( !empty( $d ) ){
			return $d;
		}
		return '';
	}
	
	public function checkUserID( $user_id, $username = '' )
	{
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.* FROM #__users AS a';
       	$query .= " WHERE a.id = $user_id And a.username='$username' And block = 0";
        $db->setQuery($query);
        $d = $db->loadObject();
		if( !empty( $d ) ){
			return $d;
		}
		return '';
	}
	
	public function checkRole( $user_id, $user_role = '' )
	{
		$user_roleA = explode(",", $user_role);
		$user_roleStr  = implode(",", $user_roleA);
		$c = count( $user_roleA );
		$db 		= JFactory::getDbo();
		$query  = 'SELECT a.group_id FROM #__user_usergroup_map AS a';
       	$query .= " WHERE a.user_id = $user_id And group_id IN( $user_roleStr ) ";
        $db->setQuery($query);
        $d = $db->loadObjectList();
		$t = 0;
		foreach ( $d as $v ) {
			$t = 1;
			break;
		}
		return $t;
	}
	
	/**
	 * Method to log in a user.
	 *
	 */
	public function login( $dataLogin = array(), $airportsAPI )
	{
        $app = JFactory::getApplication();
        $user = JFactory::getUser( $dataLogin->id );
		
		$session = JFactory::getSession();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		///echo (string)$query;die;
		// Populate the data array:
		$data = array();
		$data['return'] = "";//base64_decode(JRequest::getVar('return', '', 'POST', 'BASE64'));
		$data['username'] = $dataLogin->username;
		$data['password'] = $dataLogin->password;

		// Set the return URL if empty.
		if (empty($data['return'])) {
			$data['return'] = 'index.php?option=com_sfs&view=dashboard&Itemid=103';
		}

		// Set the return URL in the user state to allow modification by plugins
		$app->setUserState('users.login.form.return', $data['return']);

		// Get the log in options.
		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $data['password'];
		
		// OK, the credentials are authenticated and user is authorised.  Let's fire the onLogin event.
		$ok = self::onUserLogin( $credentials, $options );//triggerEvent('onUserLogin', array((array) $response, $options));
			
			
		if (true === $ok ) {
			// Success
			$app->setUserState('users.login.form.data', array());
			if( (int)$is_popup == 1 ) {
				$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=close&tmpl=component&loginerror=0', false));
			} else {
				$airportsAPI_Arr = explode(",", $airportsAPI);
				$session->set('airportsAPI_Arr', $airportsAPI_Arr);
				$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
			}

		} else {
			// Login failed !
			$data['remember'] = (int)$options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->setUserState('sfsLoginStatus', 1);
			if( (int)$is_popup == 1 ) {
				$app->redirect(JRoute::_('index.php?option=com_sfsuser&view=close&tmpl=component&loginerror=1', false));
			} else {
				$app->redirect(JRoute::_('index.php?option=com_content&view=featured&Itemid=101', false));
			}
		}
	}
	
	
	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param   array  $user     Holds the user data
	 * @param   array  $options  Array holding options (remember, autoregister, group)
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.5
	 */
	public function onUserLogin($user, $options = array())
	{
		$app = JFactory::getApplication();
		$instance = self::_getUser($user, $options);
		
		$db = JFactory::getDbo();
		$db->setQuery('DELETE FROM #__session WHERE username="' . $instance->username . '"' );
        $db->query();
		
		// If _getUser returned an error, then pass it back.
		if ($instance instanceof Exception)
		{
			return false;
		}

		// If the user is blocked, redirect with an error
		if ($instance->get('block') == 1)
		{
			$app->enqueueMessage(JText::_('JERROR_NOLOGIN_BLOCKED'), 'warning');

			return false;
		}

		// Authorise the user based on the group information
		if (!isset($options['group']))
		{
			$options['group'] = 'USERS';
		}

		// Mark the user as logged in
		$instance->set('guest', 0);

		// Register the needed session variables
		$session = JFactory::getSession();
		$session->set('user', $instance);

		// Check to see the the session already exists.
		$app->checkSession();
		
		// Update the user related fields for the Joomla sessions table.
		$query = $db->getQuery(true)
			->update($db->quoteName('#__session'))
			->set($db->quoteName('guest') . ' = ' . $db->quote($instance->guest))
			->set($db->quoteName('username') . ' = ' . $db->quote($instance->username))
			->set($db->quoteName('userid') . ' = ' . (int) $instance->id)
			->where($db->quoteName('session_id') . ' = ' . $db->quote($session->getId()));
		try
		{
			$db->setQuery($query)->execute();
		}
		catch (RuntimeException $e)
		{
			return false;
		}

		// Hit the user last visit field
		$instance->setLastVisit();

		return true;
	}

	/**
	 * This method will return a user object
	 *
	 * If options['autoregister'] is true, if the user doesn't exist yet he will be created
	 *
	 * @param   array  $user     Holds the user data.
	 * @param   array  $options  Array holding options (remember, autoregister, group).
	 *
	 * @return  object  A JUser object
	 *
	 * @since   1.5
	 */
	protected function _getUser($user, $options = array())
	{
		$instance = JUser::getInstance();
		$id = (int) JUserHelper::getUserId($user['username']);

		if ($id)
		{
			$instance->load($id);

			return $instance;
		}

		// TODO : move this out of the plugin
		$config = JComponentHelper::getParams('com_users');

		// Hard coded default to match the default value from com_users.
		$defaultUserGroup = $config->get('new_usertype', 2);

		$instance->set('id', 0);
		$instance->set('name', $user['fullname']);
		$instance->set('username', $user['username']);
		$instance->set('password_clear', $user['password_clear']);

		// Result should contain an email (check).
		$instance->set('email', $user['email']);
		$instance->set('groups', array($defaultUserGroup));

		// If autoregister is set let's register the user
		$autoregister = isset($options['autoregister']) ? $options['autoregister'] : $this->params->get('autoregister', 1);

		if ($autoregister)
		{
			if (!$instance->save())
			{
				JLog::add('Error in autoregistration for user ' . $user['username'] . '.', JLog::WARNING, 'error');
			}
		}
		else
		{
			// No existing user and autoregister off, this is a temporary user.
			$instance->set('tmp_user', true);
		}

		return $instance;
	}
	
	
	public function isertNotFindUserID( $objDate, $dataAirline ){
		
		$db = JFactory::getDbo();
		
		$userNew = new stdClass();
		$userNew->id      = $objDate->user_id;
		$userNew->name             = $objDate->name;
		$userNew->email             = $objDate->emailAddress;
		$userNew->username             = $objDate->username;
		if (!$db->insertObject('#__users', $userNew)) {
			self::getError( "-01" );
		}
		
		$user_roleArr = explode(",", $objDate->user_role );
		foreach( $user_roleArr as $v){
			$user_usergroup_mapNew = new stdClass();
			$user_usergroup_mapNew->user_id      = $objDate->user_id;
			$user_usergroup_mapNew->group_id             = $v;
			if (!$db->insertObject('#__user_usergroup_map', $user_usergroup_mapNew)) {
				self::getError( "-02" );
			}
		}
		
		/*$db->setQuery('DELETE FROM #__sfs_airline_airport WHERE user_id="' . $objDate->user_id . '" And airline_detail_id = ' . $dataAirline->id);
        $db->query();*/
		
		$airportsArr = explode(",", $objDate->airports );
		foreach( $airportsArr as $v){
			
			$query  = 'SELECT a.id FROM #__sfs_iatacodes AS a';
			$query .= " WHERE a.code = '$v' And a.type = 2 ";
			
			$db->setQuery($query);
			$d = $db->loadObject();
			
			if ( $d ) {
				$airline_airportNew = new stdClass();
				$airline_airportNew->airline_detail_id 	= $dataAirline->id;
				$airline_airportNew->user_id			= $objDate->user_id;
				$airline_airportNew->airport_id			= $d->id;
				
				if (!$db->insertObject('#__sfs_airline_airport', $airline_airportNew)) {
					self::getError( "-03" );
				}
			}
		}

		$contactsNew = new stdClass();
		$contactsNew->grouptype             = 2;
		$contactsNew->user_id      = $objDate->user_id;
		$contactsNew->group_id             = $dataAirline->id;
		$contactsNew->name = $objDate->name;
		if (!$db->insertObject('#__sfs_contacts', $contactsNew)) {
		 	self::getError( "-04" );
		}
		
		$airline_user_mapNew = new stdClass();
		$airline_user_mapNew->user_id      = $objDate->user_id;
		$airline_user_mapNew->airline_id             = $dataAirline->id;
		if (!$db->insertObject('#__sfs_airline_user_map', $airline_user_mapNew)) {
		 	self::getError( "-05" );
		}
		
		
		
	}
	
	//Update user whent isset user_id
	public function UpdateUserID( $objDate, $dataAirline ){

		$db = JFactory::getDbo();
		
		$airline_detail_id = (int)$dataAirline->id;
		$user_id = (int)$objDate->user_id;
			
		$query = $db->getQuery(true)
			->update($db->quoteName('#__users'))
			->set($db->quoteName('email') . ' = ' . $db->quote($objDate->emailAddress))
			->set($db->quoteName('username') . ' = ' . $db->quote($objDate->username))
			->set($db->quoteName('name') . ' = ' . $db->quote($objDate->name) )
			->where($db->quoteName('id') . ' = ' . $db->quote($user_id));
		$db->setQuery($query)->execute();
		
		$user_roleArr = explode(",", $objDate->user_role );
		foreach( $user_roleArr as $v){
			$query  = 'SELECT a.user_id FROM #__user_usergroup_map AS a';
			$query .= " WHERE a.user_id = $user_id And a.group_id = $v ";
			$db->setQuery($query);
			$d_Check = $db->loadObject();
			if ( empty( $d_Check ) ) {
				$user_usergroup_mapNew = new stdClass();
				$user_usergroup_mapNew->user_id      = $user_id;
				$user_usergroup_mapNew->group_id     = $v;
				if (!$db->insertObject('#__user_usergroup_map', $user_usergroup_mapNew)) {
					self::getError( "-02" );
				}
			}//End if $d_Check
			
		}//End foreach
		
		$airportsArr = explode(",", $objDate->airports );
		foreach( $airportsArr as $v){
			
			$query  = 'SELECT a.id FROM #__sfs_iatacodes AS a';
			$query .= " WHERE a.code = '$v' And a.type = 2 ";
			$db->setQuery($query);
			$dIaticode = $db->loadObject();
			$airport_id = (int)$dIaticode->id;
			
			$query  = 'SELECT a.user_id FROM #__sfs_airline_airport AS a';
			$query .= " WHERE a.airline_detail_id = $airline_detail_id And a.user_id = $user_id And a.airport_id = $airport_id";
			
			$db->setQuery($query);
			$dCheck = $db->loadObject();
			if ( empty( $dCheck ) ) {
				$query  = 'SELECT a.id FROM #__sfs_iatacodes AS a';
				$query .= " WHERE a.code = '$v' And a.type = 2 ";
				$db->setQuery($query);
				$d = $db->loadObject();
				if ( $d ) {
					$airline_airportNew = new stdClass();
					$airline_airportNew->airline_detail_id 	= $airline_detail_id;
					$airline_airportNew->user_id			= $user_id;
					$airline_airportNew->airport_id			= $d->id;
					
					if (!$db->insertObject('#__sfs_airline_airport', $airline_airportNew)) {
						self::getError( "-03" );
					}
				}
			}//End if $dCheck
			
		}//End foreach
		
	}
	
	/*
	* Method to get error a access/request.
	 *
	*/
	public function getError( $type_err = 0 ){
		$rawpostxml = new Api_Rawpostxml();

		switch( $type_err ) {
			
			case"-01"://use
				$xmlError = $rawpostxml->rawpostxmlError('-01', 'Could not create new user');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"-02"://use
				$xmlError = $rawpostxml->rawpostxmlError('-02', 'Could not insert new user usergroup map');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"-03"://use
				$xmlError = $rawpostxml->rawpostxmlError('-03', 'Could not create new airline airport');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"-04"://use
				$xmlError = $rawpostxml->rawpostxmlError('-04', 'Could not create new contacts');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"-05"://use
				$xmlError = $rawpostxml->rawpostxmlError('-05', 'Could not insert new airline user');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"01"://use
				$xmlError = $rawpostxml->rawpostxmlError('01', 'Not find Airline user');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"02"://use
				$xmlError = $rawpostxml->rawpostxmlError('02', 'Not find user');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"03"://use
				$xmlError = $rawpostxml->rawpostxmlError('03', 'Not find user role');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"200"://use
				$xmlError = $rawpostxml->rawpostxmlError('200', 'OK Success!');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"304"://use
				$xmlError = $rawpostxml->rawpostxmlError('304', 'Not Modified There was no new data to return.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"400"://use
				$xmlError = $rawpostxml->rawpostxmlError('400', 'Bad Request The request was invalid or cannot be otherwise served, requests without authentication are considered invalid and will yield this response');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"401":
				$xmlError = $rawpostxml->rawpostxmlError('401', 'Unauthorized Authentication credentials were missing or incorrect.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"403"://use
				$xmlError = $rawpostxml->rawpostxmlError('403', 'Forbidden The request is understood, but it has been refused also returned when the requested format is not supported by the requested method. An accompanying error message will explain why.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"404":
				$xmlError = $rawpostxml->rawpostxmlError('404', 'Not Found The URI requested is invalid or the resource requested.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"406":
				$xmlError = $rawpostxml->rawpostxmlError('406', 'Not Acceptable Returned by the Search API when an invalid format is specified in the request.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"410":
				$xmlError = $rawpostxml->rawpostxmlError('410', 'Gone This resource is gone. Used to indicate that an API endpoint has been turned off. For example: “The REST API v1 will soon stop functioning. Please migrate to API v1.1.”');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"429":
				$xmlError = $rawpostxml->rawpostxmlError('429', 'Too Many Requests Returned in API v1.1 when a request cannot be served due to the application’s rate limit having been exhausted for the resource.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"500":
				$xmlError = $rawpostxml->rawpostxmlError('500', ' Internal Server Error Something is broken. Please send an email to the SFS dev team so we can investigate.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"502":
				$xmlError = $rawpostxml->rawpostxmlError('502', 'Bad Gateway Service is down or being upgraded.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			
			case"503":
				$xmlError = $rawpostxml->rawpostxmlError('503', 'Service Unavailable The servers are up, but overloaded with requests. Try again later.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
			
			case"504"://use
				$xmlError = $rawpostxml->rawpostxmlError('504', 'Gateway timeout The servers are up, but the request couldn’t be serviced due to some failure within our stack. Try again later.');
				$rawpostxml->fwriteFile(JPATH_BASE . '/tmp/apiforuserbyairlines/apilogin-error.xml', $xmlError);
				header("Content-type: text/xml");
				die( $xmlError );
			break;
		}
	}
	
}

class Api_Rawpostxml{

	protected $_method = 'access/request';
	protected $_unique = '';
	public $fileds = array();
	
	public function __construct() {
		
	}
	
	public function rawpostxmlError( $errno = 01, $error_message = 'Error message', $method = 'passenger/push' )
	{
		$str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
		<response>
			<method>$method</method>
			<errno>$errno</errno>
			<error>$error_message</error>
		</response>";
		
		return $str;
	}
	
	public function fwriteFile( $filename = '', $response ){
		$fp = fopen($filename, 'w');
		fwrite($fp, $response);
		fclose($fp);
	}
}
