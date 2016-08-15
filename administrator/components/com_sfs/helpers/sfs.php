<?php
// No direct access to this file
defined('_JEXEC') or die;

abstract class SfsHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{	
		//lchung
		$zone_show_permision = self::checkIsGroupAdmin();
		//End lchung

		JSubMenuHelper::addEntry(JText::_('Cpanel'), 'index.php?option=com_sfs&view=cpanel', $submenu == 'cpanel');	
		JSubMenuHelper::addEntry(JText::_('Airlines'), 'index.php?option=com_sfs&view=airlines', $submenu == 'airlines');
		JSubMenuHelper::addEntry(JText::_('Ground Handlers'), 'index.php?option=com_sfs&view=ghs', $submenu == 'ghs');		
		JSubMenuHelper::addEntry(JText::_('Hotels'), 'index.php?option=com_sfs&view=hotels', $submenu == 'hotels');		
		JSubMenuHelper::addEntry(JText::_('Users'), 'index.php?option=com_sfs&view=contacts', $submenu == 'contacts');	
		JSubMenuHelper::addEntry(JText::_('Booked, Blocked Rooms'), 'index.php?option=com_sfs&view=reservations', $submenu == 'reservations');		
		JSubMenuHelper::addEntry(JText::_('Vouchers'), 'index.php?option=com_sfs&view=vouchers', $submenu == 'vouchers');		
		if( $zone_show_permision ) {//Group administrator not show
			JSubMenuHelper::addEntry(JText::_('Taxi'), 'index.php?option=com_sfs&view=taxilist', $submenu == 'taxilist');
			JSubMenuHelper::addEntry(JText::_('Group Transports'), 'index.php?option=com_sfs&view=grouptransportlist', $submenu == 'grouptransportlist');
			JSubMenuHelper::addEntry(JText::_('Transport Reservations'), 'index.php?option=com_sfs&view=transportreservations', $submenu == 'transportreservations');
		}
		JSubMenuHelper::addEntry(JText::_('Reports'), 'index.php?option=com_sfs&view=reports', $submenu == 'reports');
		JSubMenuHelper::addEntry(JText::_('Rental Cars'), 'index.php?option=com_sfs&view=rentalcars', $submenu == 'Rental Cars');
		JSubMenuHelper::addEntry(JText::_('Rental Location'), 'index.php?option=com_sfs&view=rentallocations', $submenu == 'Rental Location');
		JSubMenuHelper::addEntry(JText::_('Services'), 'index.php?option=com_sfs&view=services', $submenu == 'Services');
		JSubMenuHelper::addEntry(JText::_('Airport Services'), 'index.php?option=com_sfs&view=airportservices', $submenu == 'Airport Services');
		JSubMenuHelper::addEntry(JText::_('Users roles management'), 'index.php?option=com_sfs&view=userrolesmanagements', $submenu == 'userrolesmanagements');		
		JSubMenuHelper::addEntry(JText::_('User roles'), 'index.php?option=com_sfs&view=userroles', $submenu == 'userroles');
		JSubMenuHelper::addEntry(JText::_('Point Priority'), 'index.php?option=com_sfs&view=pointprioritys', $submenu == 'Point prioritys');
		///JSubMenuHelper::addEntry(JText::_('Setup airport'), 'index.php?option=com_sfs&view=setupairport&layout=setup', $submenu == 'setup');
	}
	
	/**
	 * Get the actions
	 */
	public static function getActions($item_id = 0, $type = null)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if( empty($item_id) || empty($type) )
		{
			$assetName = 'com_sfs';
		}
		else
		{
			$assetName = 'com_sfs.'.$type.'.'.(int) $item_id;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete', 'core.edit.own'
		);
		
		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	public static function getPhoneString($code, $number) {
    	if( $code && $number ) {
    		return '(+' . $code . ') ' . $number;	
    	} else {
    		return '';	
    	}    	
    }
    
	public static function formatPhone($number, $return = 1) {
    	
    	if(empty($number)) return '';
    	
        if ($return == 2) {
            return trim(JString::substr($number, strpos($number, ')') + 1));
        } else {
            $offset = strpos($number, '+') + 1;
            $length = strpos($number, ')') - $offset;
            return trim(JString::substr($number, $offset, $length));
        }        
    }
    
    public static function getAirlineField( $name , $value , $country_id = 0, $attribs = 'class="required validate-custom-required emptyValue:0"' ) {			
		$db = JFactory::getDbo();			
		$db->setQuery('SELECT id AS value, code AS text FROM #__sfs_iatacodes WHERE type=1 ORDER BY name ASC');
		
		$rows = $db->loadObjectList();
		
		$airlinelist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airline Code' ), 'value', 'text' );
		$airlinelist	= array_merge( $airlinelist, $rows);		
		
		$html = JHTML::_('select.genericlist', $airlinelist, $name, $attribs, 'value', 'text', $value );		
		return $html;			
	}

    public static function getIatacodeCopyAirlineField( $name , $value , $country_id = 0, $attribs = 'class="required validate-custom-required emptyValue:0"' ) {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT ia.id AS value, ia.code AS text
                  FROM #__sfs_iatacodes AS ia
                  LEFT JOIN #__sfs_airline_details AS a ON ia.id = a.iatacode_id
                  WHERE ia.type=1 AND a.iatacode_id IS NULL
                  ORDER BY ia.code ASC');

        $rows = $db->loadObjectList();

        $airlinelist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airline Code' ), 'value', 'text' );
        $airlinelist	= array_merge( $airlinelist, $rows);

        $html = JHTML::_('select.genericlist', $airlinelist, $name, $attribs, 'value', 'text', $value );
        return $html;
    }

    public static function getCountryOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
				
		$query->select('id As value, name As text');
		$query->from('#__sfs_country AS a');
		$query->order('a.name');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		// Merge any additional options in the XML definition.
		//$options = array_merge(parent::getOptions(), $options);

		//array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_BANNERS_NO_CLIENT')));

		return $options;
	}
	
	public static function getStateOptions()
	{		
		
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
				
		$query->select('id As value, name As text');
		$query->from('#__sfs_states AS a');
		$query->order('a.name');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		// Merge any additional options in the XML definition.
		//$options = array_merge(parent::getOptions(), $options);

		//array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_BANNERS_NO_CLIENT')));

		return $options;
	
	}
	
	public static function getRingOptions()
	{
		// Initialize variables.
		$options = array();

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('DISTINCT ring As value');
		$query->from('#__sfs_hotel_backend_params AS a');
		$query->order('a.ring ASC');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();
		
		foreach ($options as & $opt)
		{
			$opt->text = $opt->value;
		}

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	
	public static function getStarOptions()
	{		
		
		// Initialize variables.
		$options = array();		
		$options[0] = new stdClass();
		$options[0]->value = 0;
		$options[0]->text = 'no rating';
		$options[1] = new stdClass();
		$options[1]->value = 1;
		$options[1]->text = '1 star';
		$options[2] = new stdClass();
		$options[2]->value = 2;
		$options[2]->text = '2 stars';

		$options[3] = new stdClass();
		$options[3]->value = 3;
		$options[3]->text = '3 stars';
		
		$options[4] = new stdClass();
		$options[4]->value = 4;
		$options[4]->text = '4 stars';
		
		$options[5] = new stdClass();
		$options[5]->value = 5;
		$options[5]->text = '5 stars';		
		// 
		return $options;
	
	}
	
	public function getSendNotification($hotelId)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT a.*,u.name FROM #__sfs_admin_notification_tracking AS a INNER JOIN #__users AS u ON u.id=a.user_id WHERE a.hotel_id='.$hotelId.' AND DATEDIFF(now(),a.date)=0';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}
	
	public function getAirlineSendNotification($hotelId)
	{
		$db = JFactory::getDbo();
		$query = 'SELECT a.*, u.id as uid, u.name, u.username, ad.airline_alliance FROM #__sfs_airline_notification_tracking AS a 
		INNER JOIN #__users AS u ON u.id=a.user_id 
		INNER JOIN #__sfs_airline_user_map AS am ON u.id=am.user_id 
		INNER JOIN #__sfs_airline_details AS ad ON ad.id=am.airline_id  
		WHERE a.hotel_id='.$hotelId.' 
		Group by a.hotel_id, a.user_id  ';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}
	
	public static function getATDate( $input = 'now' , $format = 'Y-m-d H:i', $timezone = null )
	{
		if ( empty($format)) {
			$format = 'Y-m-d H:i';
		}
		$config = JFactory::getConfig();
		$date = JFactory::getDate($input, 'UTC');
		if (  $timezone  ) {					
			$date->setTimeZone( new DateTimeZone($timezone) );						
		} else {			
			$date->setTimeZone(new DateTimeZone($config->get('offset')));			
		}			
		
		return $date->format($format, true);
	}	
	
	public static function getATPrevDate ( $format,$inputDate ) {
				 
		$result = strtotime($inputDate);
		
		if($result !== false){
	  		return date( $format , strtotime('-1 day', $result) );
		}			
		return null;			
	}
	
	public static function getATNextDate ( $format,$inputDate ) {
				 
		$result = strtotime($inputDate);
		
		if($result !== false){
	  		return date( $format , strtotime('+1 day', $result) );
		}			
		return null;
	}
	
	//lchung
	public static function checkIsGroupAdmin(){
		$user = JFactory::getUser();
		$groups = $user->groups;
		$allow = true;
		if( in_array(7, $groups) ) //Group Administrator
		{
			$allow = false;
		}
		return $allow;
	}
	//End lchung
	
}
