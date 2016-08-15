<?php
// No direct access to this file
defined('_JEXEC') or die();

/**
 * Sfs Component Field Helper
 *
 * @static
 * @package		SFS site
 * @subpackage	com_sfs
 * @since		1.6
 */
abstract class SfsHelperField {
	
	protected static $countries = null;
	protected static $chains = null;
	protected static $states = null;
	protected static $airports = null;
	protected static $currencies = null;
	protected static $hotel_locations = null;
	
	protected static $zones = array(
		'Africa', 'America', 'Antarctica', 'Arctic', 'Asia',
		'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific'
	);	
	
	/**
	 * Method to get the airline field options.
	 *
	 * @param	string	$name	The name of the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 * @param	int     $country_id  The country to load
	 * @param	string	$attribs	The attributes of the field.
	 * 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */	
	public static function getAirlineField( $name , $value , $country_id = 0, $attribs = 'class="required validate-custom-required emptyValue:0"' ) 
	{			
		$db = JFactory::getDbo();			
		$db->setQuery('SELECT id AS value, code AS text FROM #__sfs_iatacodes WHERE type=1 ORDER BY code ASC');
		
		$rows = $db->loadObjectList();
		
		$airlinelist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airline Code' ), 'value', 'text' );
		$airlinelist	= array_merge( $airlinelist, $rows);		
		
		$html = JHTML::_('select.genericlist', $airlinelist, $name, $attribs, 'value', 'text', $value );		
		return $html;			
	}	
	
	public static function getGhAirlineField( $name , $value , $country_id = 0, $attribs = 'class="required validate-custom-required emptyValue:0"' ) 
	{			
		$db = JFactory::getDbo();
        $query = 'SELECT ia.id AS value, ia.code AS text, name
                FROM #__sfs_iatacodes AS ia
                INNER JOIN #__sfs_airline_details AS a ON a.iatacode_id = ia.id AND a.gh_airline=1
                WHERE ia.type=1
                ORDER BY ia.code ASC';
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		foreach ($rows as & $row)
		{
			$row->text = $row->name .' - '.$row->text;
		}
		
		$airlinelist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airline Code' ), 'value', 'text' );
		$airlinelist	= array_merge( $airlinelist, $rows);		
		
		$html = JHTML::_('select.genericlist', $airlinelist, $name, $attribs, 'value', 'text', $value );		
		return $html;			
	}	
	
	/**
	 * Method to get the airport field options.
	 *
	 * @param	string	$name	The name of the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 * @param	int     $country_id  The country to load
	 * 	 * 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */		
	public static function getAirportField( $name , $value , $country_id = 0, $attribs = 'class="inputbox"', $exclude = false ) {
		
		if( self::$airports === null ){			
			$db = JFactory::getDbo();	
						
			$query = 'SELECT id AS value, CONCAT_WS('.$db->Quote(', ').',code,name) AS text FROM #__sfs_iatacodes';
			$query .= ' WHERE type=2';	
			if( $country_id > 0 ) {
				$query .= ' AND country_id='.$country_id;
			}
			
			//	just for hotel access only
			if($exclude) {
				$hotel = SFactory::getHotel();
				$airportIds = $hotel->getAirportIDs();	
				$query .= ' AND id NOT IN ('.implode(',', $airportIds).')';
			}			
			
			$query .= ' ORDER BY name ASC';
			
			$db->setQuery($query);
			self::$airports = $db->loadObjectList();				
		}	
		
		$airportlist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Airport' ), 'value', 'text' );
		$airportlist	= array_merge( $airportlist, self::$airports);		
		
		$html = JHTML::_('select.genericlist', $airportlist, $name, $attribs, 'value', 'text', $value );		
		return $html;			
	}
	
	
	public static function getAirportDefaultLocation( $id = NULL ){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('a.id , a.code, a.name, a.geo_lat, a.geo_lon, b.name as country_n');
        $query->from('#__sfs_iatacodes AS a');
		$query->innerJoin('#__sfs_country AS b ON b.id=a.country_id');
        $query->where('a.id=' . (int)$id);
        $query->where('a.type=2');
        $query->order('a.code ASC');
		//echo $query;die;
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }
	
	/**
	 * Method to get the country field options.
	 *
	 * @param	string	$name	The name of the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 * 	 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */			
	public static function getCountryField ( $name , $value = 0 )
	{		
		if( self::$countries === null ){
			$db = JFactory::getDbo();			
			$db->setQuery('SELECT id AS value, name AS text FROM #__sfs_country ORDER BY name ASC');
			self::$countries = $db->loadObjectList();						
		}	
		
		$countrylist[]	= JHTML::_('select.option',  '0', JText::_( 'Select Country' ), 'value', 'text' );
		$countrylist	= array_merge( $countrylist, self::$countries );		
		
		$html = JHTML::_('select.genericlist', $countrylist, $name, 'class="inputbox validate-custom-required emptyValue:0" size="1"', 'value', 'text', $value );		
		return $html;
	}

	/**
	 * Method to get the chain field options.
	 *
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 * 	 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */		
	public static function getChainField( $value = 0)
	{
		if( self::$chains === null ){
			$db = JFactory::getDBO();
			$query = 'SELECT id AS value, name AS text'.
					 ' FROM #__sfs_hotel_chains' .
					 ' ORDER BY name ASC';								
			$db->setQuery($query);			
			self::$chains = $db->loadObjectList();						
		}					
		$chainlist			= self::$chains;	
		$chainlist[]		= JHTML::_('select.option',  '-1', 'No chain affiliation', 'value', 'text' );
		$chainlist[]		= JHTML::_('select.option',  '0', 'None of the above' , 'value', 'text' );					
		$html = JHTML::_('select.genericlist', $chainlist, 'chain_id', 'class="inputbox" size="1"', 'value', 'text', $value );		
		return $html;
	}
	
	/**
	 * Method to get the state field options.
	 *
	 * @param	string	$name	The name of the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 * 	 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */			
	public static function getStateField( $name, $value = 0, $country_id = 0 )
	{
		if( self::$states === null ){
			$db = JFactory::getDBO();		

			$where = '';
			
			if ( (int) $country_id > 0 )  {
				$where = ' WHERE country_id = '.$country_id;
			}
			
			$query = 'SELECT id AS value, name AS text'.
					 ' FROM #__sfs_states' . $where.
					 ' ORDER BY ordering ASC';								
			$db->setQuery($query);			
			self::$states = $db->loadObjectList();
		}	
		
		$statelist[]		= JHTML::_('select.option',  '0', JText::_( 'Select State' ), 'value', 'text' );
		$statelist			= array_merge( $statelist, self::$states  );		
		
		$html = JHTML::_('select.genericlist', $statelist, $name, 'class="inputbox" size="1"', 'value', 'text', $value );
		
		return $html;
	}
	
	/**
	 * Method to get the hotel location field options.
	 *
	 * @param	string	$name	The name of the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 * 	 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */					
	public static function getHotelLocationField( $name, $value = 0)
	{
		if( self::$hotel_locations === null ){
			$db = JFactory::getDBO();
			$query = 'SELECT id AS value, name AS text'.
					 ' FROM #__sfs_hotel_locations' .
					 ' ORDER BY ordering ASC';								
			$db->setQuery($query);			
			self::$hotel_locations = $db->loadObjectList();
		}	
		
		$locationlist[]		= JHTML::_('select.option',  '0', JText::_( 'Select Location' ), 'value', 'text' );
		$locationlist			= array_merge( $locationlist, self::$hotel_locations  );		
		
		$html = JHTML::_('select.genericlist', $locationlist, $name, 'class="required validate-custom-required emptyValue:0"', 'value', 'text', $value );
		
		return $html;
	}
	
	/**
	 * Method to get the currency field options.
	 *
	 * @param	string	$name	The name of the field.
	 * @param	mixed	$value	The optional value to use as the default for the field.
	 * 	 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */			
	public static function getCurrencyField( $name, $value = 0)
	{
		if( self::$currencies === null ){
			$db = JFactory::getDBO();
			$query = 'SELECT id AS value, code AS text'.
					 ' FROM #__sfs_currency' .
					 ' ORDER BY code ASC';								
			$db->setQuery($query);			
			self::$currencies = $db->loadObjectList();
		}	
		
		$currencylist[]		= JHTML::_('select.option',  '0', JText::_( 'Select Currency' ), 'value', 'text' );
		$currencylist			= array_merge( $currencylist, self::$currencies  );		
		
		$html = JHTML::_('select.genericlist', $currencylist, $name, 'class="inputbox" size="1"', 'value', 'text', $value );
		
		return $html;
	}
	
	/**
	 * Method to get the timezone field options.
	 *
	 * @param	mixed	$defaultValue	The optional value to use as the default for the field.
	 * 	 
	 * @return	string	The genericlist for the field.
	 * @since	1.6
	 * 
	 */			
	public static function getTimeZone( $defaultValue = null , $name = null )
	{
		$timezones = array(
			'America/New_York'  => "(GMT-05:00) Eastern Standard Time (EST)",	
			'America/Chicago'  => "(GMT-06:00) Central Standard Time (CST)",		
			'America/Denver'  => "(GMT-07:00) Mountain Standard Time (MST)",
			'America/Los_Angeles'  => "(GMT-08:00) Pacific Standard Time (PST)",
			'America/Anchorage'  => "(GMT-09:00) Alaska Standard Time (AKST)",
			'America/Adak'    => "(GMT-10:00) Hawaii Standard Time (HST)",
		    'Pacific/Midway'    => "(GMT-11:00) Midway Island",
		    'America/Tijuana'   => "(GMT-08:00) Tijuana",		    
		    'America/Chihuahua' => "(GMT-07:00) Chihuahua",
		    'America/Mazatlan'  => "(GMT-07:00) Mazatlan",
		    'America/Mexico_City' => "(GMT-06:00) Mexico City",
		    'America/Monterrey' => "(GMT-06:00) Monterrey",
		    'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
		    'America/Bogota'    => "(GMT-05:00) Bogota",
		    'America/Lima'      => "(GMT-05:00) Lima",
		    'America/Caracas'   => "(GMT-04:30) Caracas",
		    'Canada/Atlantic'   => "(GMT-04:00) Atlantic Time (Canada)",
		    'America/La_Paz'    => "(GMT-04:00) La Paz",
		    'America/Santiago'  => "(GMT-04:00) Santiago",
		    'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
		    'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",		    
		    'Atlantic/Stanley'  => "(GMT-02:00) Stanley",
		    'Atlantic/Azores'   => "(GMT-01:00) Azores",
		    'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
		    'Africa/Casablanca' => "(GMT) Casablanca",
		    'Europe/Dublin'     => "(GMT) Dublin",
		    'Europe/Lisbon'     => "(GMT) Lisbon",
		    'Europe/London'     => "(GMT) London",
		    'Africa/Monrovia'   => "(GMT) Monrovia",
		    'Europe/Amsterdam'  => "(GMT+01:00) Amsterdam",
		    'Europe/Belgrade'   => "(GMT+01:00) Belgrade",
		    'Europe/Berlin'     => "(GMT+01:00) Berlin",
		    'Europe/Bratislava' => "(GMT+01:00) Bratislava",
		    'Europe/Brussels'   => "(GMT+01:00) Brussels",
		    'Europe/Budapest'   => "(GMT+01:00) Budapest",
		    'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
		    'Europe/Ljubljana'  => "(GMT+01:00) Ljubljana",
		    'Europe/Madrid'     => "(GMT+01:00) Madrid",
		    'Europe/Paris'      => "(GMT+01:00) Paris",
		    'Europe/Prague'     => "(GMT+01:00) Prague",
		    'Europe/Rome'       => "(GMT+01:00) Rome",
		    'Europe/Sarajevo'   => "(GMT+01:00) Sarajevo",
		    'Europe/Skopje'     => "(GMT+01:00) Skopje",
		    'Europe/Stockholm'  => "(GMT+01:00) Stockholm",
		    'Europe/Vienna'     => "(GMT+01:00) Vienna",
		    'Europe/Warsaw'     => "(GMT+01:00) Warsaw",
		    'Europe/Zagreb'     => "(GMT+01:00) Zagreb",
		    'Europe/Athens'     => "(GMT+02:00) Athens",
		    'Europe/Bucharest'  => "(GMT+02:00) Bucharest",
		    'Africa/Cairo'      => "(GMT+02:00) Cairo",
		    'Africa/Harare'     => "(GMT+02:00) Harare",
		    'Europe/Helsinki'   => "(GMT+02:00) Helsinki",
		    'Europe/Istanbul'   => "(GMT+02:00) Istanbul",
		    'Asia/Jerusalem'    => "(GMT+02:00) Jerusalem",
		    'Europe/Kiev'       => "(GMT+02:00) Kyiv",
		    'Europe/Minsk'      => "(GMT+02:00) Minsk",
		    'Europe/Riga'       => "(GMT+02:00) Riga",
		    'Europe/Sofia'      => "(GMT+02:00) Sofia",
		    'Europe/Tallinn'    => "(GMT+02:00) Tallinn",
		    'Europe/Vilnius'    => "(GMT+02:00) Vilnius",
		    'Asia/Baghdad'      => "(GMT+03:00) Baghdad",
		    'Asia/Kuwait'       => "(GMT+03:00) Kuwait",
		    'Europe/Moscow'     => "(GMT+03:00) Moscow",
		    'Africa/Nairobi'    => "(GMT+03:00) Nairobi",
		    'Asia/Riyadh'       => "(GMT+03:00) Riyadh",
		    'Europe/Volgograd'  => "(GMT+03:00) Volgograd",
		    'Asia/Tehran'       => "(GMT+03:30) Tehran",
		    'Asia/Baku'         => "(GMT+04:00) Baku",
		    'Asia/Muscat'       => "(GMT+04:00) Muscat",
		    'Asia/Tbilisi'      => "(GMT+04:00) Tbilisi",
		    'Asia/Yerevan'      => "(GMT+04:00) Yerevan",
		    'Asia/Kabul'        => "(GMT+04:30) Kabul",
		    'Asia/Yekaterinburg' => "(GMT+05:00) Ekaterinburg",
		    'Asia/Karachi'      => "(GMT+05:00) Karachi",
		    'Asia/Tashkent'     => "(GMT+05:00) Tashkent",
		    'Asia/Kolkata'      => "(GMT+05:30) Kolkata",
		    'Asia/Kathmandu'    => "(GMT+05:45) Kathmandu",
		    'Asia/Almaty'       => "(GMT+06:00) Almaty",
		    'Asia/Dhaka'        => "(GMT+06:00) Dhaka",
		    'Asia/Novosibirsk'  => "(GMT+06:00) Novosibirsk",
		    'Asia/Bangkok'      => "(GMT+07:00) Bangkok",
			'Asia/Ho_Chi_Minh'  => "(GMT+07:00) HoChiMinh",			
		    'Asia/Jakarta'      => "(GMT+07:00) Jakarta",
		    'Asia/Krasnoyarsk'  => "(GMT+07:00) Krasnoyarsk",
		    'Asia/Chongqing'    => "(GMT+08:00) Chongqing",
		    'Asia/Hong_Kong'    => "(GMT+08:00) Hong Kong",
		    'Asia/Irkutsk'      => "(GMT+08:00) Irkutsk",
		    'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
		    'Australia/Perth'   => "(GMT+08:00) Perth",
		    'Asia/Singapore'    => "(GMT+08:00) Singapore",
		    'Asia/Taipei'       => "(GMT+08:00) Taipei",
		    'Asia/Ulaanbaatar'  => "(GMT+08:00) Ulaan Bataar",
		    'Asia/Urumqi'       => "(GMT+08:00) Urumqi",
		    'Asia/Seoul'        => "(GMT+09:00) Seoul",
		    'Asia/Tokyo'        => "(GMT+09:00) Tokyo",
		    'Asia/Yakutsk'      => "(GMT+09:00) Yakutsk",
		    'Australia/Adelaide' => "(GMT+09:30) Adelaide",
		    'Australia/Darwin'  => "(GMT+09:30) Darwin",
		    'Australia/Brisbane' => "(GMT+10:00) Brisbane",
		    'Australia/Canberra' => "(GMT+10:00) Canberra",
		    'Pacific/Guam'      => "(GMT+10:00) Guam",
		    'Australia/Hobart'  => "(GMT+10:00) Hobart",
		    'Australia/Melbourne' => "(GMT+10:00) Melbourne",
		    'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
		    'Australia/Sydney'  => "(GMT+10:00) Sydney",
		    'Asia/Vladivostok'  => "(GMT+10:00) Vladivostok",
		    'Asia/Magadan'      => "(GMT+11:00) Magadan",
		    'Pacific/Auckland'  => "(GMT+12:00) Auckland",
		    'Pacific/Fiji'      => "(GMT+12:00) Fiji",
		    'Asia/Kamchatka'    => "(GMT+12:00) Kamchatka",
		);
		
		if( $name == null ) $name = 'time_zone';
		
		if( $defaultValue == null )
		{
			$params = JComponentHelper::getParams('com_sfs');
			$sfs_system_timezone = $params->get('sfs_system_timezone');
			if($sfs_system_timezone)
			{
				$defaultValue = $sfs_system_timezone;
			}
		}		
		
		$html = '<select name="'.$name.'" id="time_zone">';
		$html .= '<option value="">- Use Default -</option>';
		foreach($timezones as $key => $value) {			
			if( $defaultValue != $key ) {
				$html .= '<option value="'.$key.'">'.$value.'</option>';
			}else {
				$html .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';	
			}							
		}		
		
		$html .= '</select>';
		
		return $html;
	}	
	
	/**
	 * Method to get chain name.
	 *
	 * @param	int	$id    The chain to load.
	 * 	 
	 * @return	string	chain name
	 * @since	1.6
	 * 
	 */			
	public static function getChainName( $id )
	{				
		if($id > 0 ) {
			$db = JFactory::getDbo();
			$query = 'SELECT name FROM #__sfs_hotel_chains WHERE id='. (int) $id;	
			$db->setQuery($query);	
			return $db->loadResult();		
		} elseif ($id==0) {
			return 'None of the above';
		} elseif ($id==-1) {
			return 'No chain affiliation';
		} elseif ($id==-2) {
			return 'None of the above';
		}		
	}	
	
	/**
	 * Method to get location name.
	 *
	 * @param	int	$id    The location to load.
	 * 	 
	 * @return	string	name of location
	 * @since	1.6
	 * 
	 */			
	public static function getHotelLocationName( $id )
	{		
		$db = JFactory::getDbo();
		$query = 'SELECT name FROM #__sfs_hotel_locations WHERE id='. (int) $id;	
		$db->setQuery($query);
		return $db->loadResult();
	}	
	
	/**
	 * Method to get airport name.
	 *
	 * @param	int	$id    The airport to load.
	 * 	 
	 * @return	string	code,name of the airport
	 * @since	1.6
	 * 
	 */				
	public static function getAirportName( $id )
	{
		$db = JFactory::getDbo();
		$query = 'SELECT code,name FROM #__sfs_iatacodes WHERE id='. (int) $id;
	
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result->code.', '.$result->name;
	}
	
	/**
	 * Method to get country name.
	 *
	 * @param	int	$id    The country to load.
	 * 	 
	 * @return	string	coutry name
	 * @since	1.6
	 * 
	 */				
	public static function getCountryName( $id )
	{
		$id = (int) $id;
		
		if( $id == 0 ) return null;
		
		if( isset(self::$countries) ) 
		{
			foreach ( self::$countries as $country )
			{
				if( $country->value == $id ) {
					return $country->text;
				}
			}
		}
		
		$db = JFactory::getDbo();
		$query = 'SELECT name FROM #__sfs_country WHERE id='. (int) $id;
	
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	/**
	 * Method to get state name.
	 *
	 * @param	int	$id    The state to load.
	 * 	 
	 * @return	string	state name
	 * @since	1.6
	 * 
	 */				
	public static function getStateName( $id )
	{
		$id = (int) $id;
		
		if( $id == 0 ) return null;
			
		$db = JFactory::getDbo();
		$query = 'SELECT name FROM #__sfs_states WHERE id='. (int) $id;
		
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}	
	

	/**
	 * Method to get calendar field.
	 * 	 
	 * @return	string	input field
	 * 
	 */				
	
	public static function getCalendar( $name, $value='', $class='calendar' )
	{
		$document = JFactory::getDocument();
		if (!defined('SFS_CALENDAR')) {
			define('SFS_CALENDAR',1);					
			$document->addScript(JURI::base().'components/com_sfs/assets/calendar/Locale.en-US.DatePicker.js');
			$document->addScript(JURI::base().'components/com_sfs/assets/calendar/Picker.js');	
			$document->addScript(JURI::base().'components/com_sfs/assets/calendar/Picker.Attach.js');	
			$document->addScript(JURI::base().'components/com_sfs/assets/calendar/Picker.Date.js');
			
			$document->addStyleSheet(JURI::base().'components/com_sfs/assets/calendar/datepicker.css');
		}				
		?>
			<input type="text" name="<?php echo $name;?>" value="<?php echo $value?>" id="<?php echo $name;?>" class="<?php echo $class?>">
			<script type="text/javascript">
				<!--
				var picker = new Picker.Date($('<?php echo $name;?>'), {
					
					format: '%Y-%m-%d'

				});
                <?php
                    $input = JFactory::getApplication()->input;
                    $view = $input->get('view');
                    $layout = $input->get('layout');
                    if($view == "report" && $layout == "market"):
                ?>
                    <?php if(isset($_POST[$name])):?>
                        picker.select(new Date('<?php echo $_POST[$name];?>'));
                    <?php else: ?>
                        picker.select(new Date());
                    <?php endif;?>
                <?php endif;?>
				-->
			</script>
		<?php
	}	
	
	public static function getMonthField($name,$value=null)
	{
		$month_array = array(
			1 => JText::_('JANUARY'),
			2 => JText::_('FEBRUARY'),
			3 => JText::_('MARCH'),
			4 => JText::_('APRIL'),
			5 => JText::_('MAY'),
			6 => JText::_('JUNE'),
			7 => JText::_('JULY'),
			8 => JText::_('AUGUST'),
			9 => JText::_('SEPTEMBER'),
			10 => JText::_('OCTOBER'),
			11 => JText::_('NOVEMBER'),
			12 => JText::_('DECEMBER')
		);				
		$defaultValue = !$value ? date('m') : (int) $value; 
		$buffer  = '<select name="'.$name.'" style="width: 110px;">';					
		foreach ( $month_array as $key => $month ) {			
			$buffer .= ($defaultValue==$key) ? '<option value="'.$key.'" selected="selected">' : '<option value="'.$key.'">';
			$buffer .= $month; 
			$buffer .= '</option>';	
		}						
		$buffer .= '</select>';		
		return $buffer;
	}	
	
	public static function getYearField($name,$value=null)
	{	
		$begin = 2011;	
		$end = (int)date('Y');
				
		$defaultValue = !$value ? date('Y') : (int) $value;	
		
		$buffer  = '<select name="'.$name.'" style="width: 70px;">';	
		for( $i = $begin; $i <= $end; $i++ )
		{
			$buffer .= ($defaultValue==$i) ? '<option value="'.$i.'" selected="selected">' : '<option value="'.$i.'">';
			$buffer .= $i; 
			$buffer .= '</option>';				
		}
		$buffer .= '</select>';		
		return $buffer; 	
	}
	
	public static function getFlightClass($value=null)
	{
		$array = array('A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E');
		
		$html = '<select name="flight_class" class="smaller-size" style="padding:2px">';
		
		foreach ($array as $k => $v)
		{
			if( $value && $value == $k )
			{
				$html .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
			} else {
				$html .= '<option value="'.$k.'">'.$v.'</option>';
			}
		}
		
		
		$html .= '</select>';		
		return $html; 	
	}
	
}

