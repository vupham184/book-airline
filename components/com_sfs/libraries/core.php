<?php
// No direct access
defined('_JEXEC') or die;

/**
 * SFactory class
 *
 */
abstract class SFactory 
{
		
	public static $associations  = null;
	public static $systemtooltip = null;
	
	public static function getHotel( $id = null )
	{		
		if (is_null($id)) {
			$session = JFactory::getSession();
			$instance = $session->get('hotel');					
			if ( !($instance instanceof SHotel) ) {
				$instance = SHotel::getInstance();				
			}				
		}
		else {
			$instance = SHotel::getInstance($id);
		}
		return $instance;
	}
	
	public static function getAirline( $id = null , $userId = 0)
	{
		if (is_null($id)) {
			$instance = JFactory::getSession()->get('airline');
			if ( !($instance instanceof SAirline) ) {
				$instance = SAirline::getInstance();
			}
		}
		else {
			$instance = SAirline::getInstance($id,$userId);
		}
		
		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$instance->time_zone = $setup_airport['time_zone'];
			$instance->params['send_sms_message'] = $setup_airport['enabel_send_sms'];
		}
		//End lchung
		return $instance;
	}	
	
	public function getCurrency(){ 
  		$db = JFactory::getDbo();

  		$query = "SELECT * FROM #__sfs_currency";
  		$db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
  	}

	public static function getBus( $id = null )
	{		
		if ( $id === null ) {
			$instance = SBus::getInstance();						
		}
		else {
			$instance = SBus::getInstance($id);	
		}
		return $instance;
	}
	
	public static function getTaxi( $id = null )
	{		
		if ( $id === null ) {
			$instance = STaxi::getInstance();						
		}
		else {
			$instance = STaxi::getInstance($id);	
		}
		return $instance;
	}
	
	public static function getAssociations()
	{
		if (self::$associations === null)
		{
			self::$associations = array();
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*');
			$query->from('#__sfs_airport_associations AS a');
			$query->where('a.state=1');
			
			$db->setQuery($query);
			
			$associations = $db->loadObjectList();
			
			if(count($associations))
			{
				foreach ($associations as & $association)
				{
					$option = array(); 
					$option['driver']   = 'mysql';           
					$option['host']     = $association->airport_host;     
					$option['user']     = $association->airport_user;     
					$option['password'] = base64_decode($association->airport_password); 
					$option['database'] = $association->airport_database; 
					$option['prefix']   = 'jos_';            
																
					$association->db = JDatabase::getInstance( $option );
				}
			}
			
			self::$associations = $associations;
			
		}

		return self::$associations;
	}
	
	public static function getAssociation($id)
	{
		if($id){
			$associations = self::getAssociations();
			if($associations)
			{
				foreach ($associations as $association)
				{					
					if( $association->id == $id )
					{
						return $association;
					}
					if( $association->code == $id )
					{						
						return $association;
					}
				}
			}	
		}
		return null;
	}
	
	public static function getTooltips($type=null)
	{
		if (self::$systemtooltip === null)
		{			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*');
			$query->from('#__sfs_options AS a');
						
			$db->setQuery($query);
			
			$rows = $db->loadObjectList('option_name');	

			if(count($rows))
			{
				foreach ($rows as & $row)
				{
					$registry = new JRegistry();
					$registry->loadString($row->option_value);	
					$row->option_value = $registry->toArray();
				}				
				self::$systemtooltip = $rows;				
			}
		}
		
		if( self::$systemtooltip !== null && $type == 'airline' && self::$systemtooltip['airline_tooltips'] )
		{
			return self::$systemtooltip['airline_tooltips']->option_value;
		}
		
		if( self::$systemtooltip !== null && $type == 'hotel' && self::$systemtooltip['hotel_tooltips'] )
		{
			return self::$systemtooltip['hotel_tooltips']->option_value;
		}

		return self::$systemtooltip;
	}
	
	public static function getContact( $user_id )
	{
		jimport('joomla.application.component.model');		
		JModel::addIncludePath(JPATH_SITE.'/components/com_sfs/models', 'SfsModel');	

		$model = JModel::getInstance('Contact', 'SfsModel', array('ignore_request' => true));
		$contact = $model->getItem( $user_id );
		return $contact;
	}
	
	public static function getContacts( $group_type, $group_id, $db = null )
	{
		jimport('joomla.application.component.model');		
		JModel::addIncludePath(JPATH_SITE.'/components/com_sfs/models', 'SfsModel');	
		
		$model = JModel::getInstance('Contact', 'SfsModel', array('ignore_request' => true));
		if($db!==null)
		{
			$model->setDbo($db);	
		}		
		$contacts = $model->getItems($group_type,$group_id);
		return $contacts;
	}	
}

interface SfsHandler 
{
	public function load($user_id);
	public function bind(&$data);
	public function save();	
}

abstract class SfsHandlerDecorator extends JObject implements SfsHandler
{
	
	public $id = null;
	public $grouptype = null;
	public $billing_id = null;
	
	private $_contacts = null;
	private $_billing_detail = null;
	
	public $_db = null;
	
	/**
	 * Class constructor.	 	
	 * 	 
	 */
	public function __construct( $config = array() )
	{
		if( empty($this->_db) ) {
			$this->_db = JFactory::getDbo();
		}		
	}
	
	public function setDbo($db)
	{
		$this->_db = $db;
	}
	
	public function getDbo()
	{
		return $this->_db;
	}
	
	public function getContacts ( ) 
	{
		if( (int) $this->id > 0 && is_null($this->_contacts)  )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.*,u.email');
			$query->from('#__sfs_contacts AS a');			
			$query->leftJoin('#__users AS u ON u.id=a.user_id');			
			$query->where( 'a.group_id='.(int) $this->id.' AND a.grouptype='.(int)$this->grouptype);			
			if($this->grouptype == 1) {
				$query->where( 'u.block=0');
			}					
			$query->order('a.contact_type ASC,a.id ASC');			
			$db->setQuery($query);
			
			$this->_contacts = $db->loadObjectList('user_id');			
		}		 		
		return $this->_contacts;
	}
	
	public function getBillingDetail()
	{
		if ( (int) $this->billing_id > 0 && is_null($this->_billing_detail) ) {

			$db = $this->getDbo();
			$query = $db->getQuery(true);

            if($this->grouptype == 3){
                $session  	  = JFactory::getSession();
                $airline_id = $session->get('airline_id',0);
                $query->select('a.*, b.name AS country_name, c.name AS state_name');
                $query->from('#__sfs_billing_details AS a');
                $query->innerJoin('#__sfs_country AS b ON b.id=a.country_id');
                $query->innerJoin('#__sfs_airline_details d ON d.billing_id=a.id');
                $query->leftJoin('#__sfs_states AS c ON c.id=a.state_id');
                $query->where('d.iatacode_id='.$airline_id);
            }else{
                $query->select('a.*, b.name AS country_name, c.name AS state_name');
                $query->from('#__sfs_billing_details AS a');
                $query->innerJoin('#__sfs_country AS b ON b.id=a.country_id');
                $query->leftJoin('#__sfs_states AS c ON c.id=a.state_id');
                $query->where('a.id='.$this->billing_id);
            }
			$db->setQuery($query);
			
			if( $error = $db->getErrorMsg() ) {
				throw new Exception($error);
			}			
			
			$this->_billing_detail = $db->loadObject();			
		}		
		return $this->_billing_detail;
	}		
	
	public function saveBillingDetail ( & $data, $updateOnly = false ) {
		
		if( empty($data['id']) && $updateOnly  ) return false;
		
		$table = JTable::getInstance('Billing','JTable');
										
		if( !$table->bind( $data ) ) {
			$this->setError($table->getError());
			return false;					
		}				
		if( !$table->check() ) {
			$this->setError( $table->getError() );
			return false;					
		}
		if( !$table->store() ) {
			$this->setError( $table->getError() );
			return false;					
		}				
		return (int) $table->id;
	}
			
	/*abstract methods*/	
	abstract public function getTable();
	
}

/**
 * Core class for SFS Application.
 *
 * 
 * @package		SFS Application
 * @subpackage	Component
 * @since		1.0
 */
final class SFSCore 
{

    //define block status
    public static $blockStatus = null;
    
    public static $instance = null;

    //const
    const HOTEL_GROUP = 1;
    const AIRLINE_GROUP = 2;
    const GROUND_HANDLER_GROUP = 3;

    protected $_loaded = false;
    
    public static function getInstance() 
    {
        if (!self::$instance) {
            self::$instance = new SFSCore;
        }
        return self::$instance;
    }

    /**
     * Render application
     */
    public function render( $importStyle = true ) 
    {
        if ( ! $this->_loaded ) {                	
            $this->_loaded = true;
            $this->import($importStyle);
            $this->initialize();
        }
    }


    /**
     * Include files
     */
    protected function import($importStyle = true) 
    {
    	// import library
    	require_once JPATH_SITE . '/components/com_sfs/libraries/access.php';

    	require_once JPATH_SITE . '/components/com_sfs/libraries/hotel.php';
    	require_once JPATH_SITE . '/components/com_sfs/libraries/airline.php';
    	require_once JPATH_SITE . '/components/com_sfs/libraries/bus.php';
    	require_once JPATH_SITE . '/components/com_sfs/libraries/taxi.php';
    	require_once JPATH_SITE . '/components/com_sfs/libraries/email.php';
    	require_once JPATH_SITE . '/components/com_sfs/libraries/reservation.php';
    	require_once JPATH_SITE . '/components/com_sfs/libraries/voucher.php';
    	
    	// import helper
    	require_once JPATH_SITE . '/components/com_sfs/helpers/sfs.php';
    	require_once JPATH_SITE . '/components/com_sfs/helpers/date.php';
    	require_once JPATH_SITE . '/components/com_sfs/helpers/field.php';
    	require_once JPATH_SITE . '/components/com_sfs/helpers/route.php';
    	
    	//include tables
    	JTable::addIncludePath(JPATH_SITE . '/components/com_sfs/tables');

    	if($importStyle) {
	    	//add stylesheet
	    	JHTML::_('stylesheet', JURI::root() . 'components/com_sfs/assets/css/style.gzip.php');
	    	//JHTML::_('stylesheet', JURI::root() . 'components/com_sfs/assets/css/validate.css');   
	
	    	//add mootools
			JHtml::_('behavior.framework', true);
	
			JHtml::_('script', JURI::root() . 'components/com_sfs/assets/js/sfs.js' );
    	}
    }
    
    public static function includePath($path)
    {
    	if($path)
    	{
    		require_once JPATH_ROOT . '/components/com_sfs/libraries/'.$path.'.php';
    	}
    }

    /**
     *      
     * Initialize application
     */
    protected function initialize () { 
    	
    	define('SFS_PATH_CHART', JPATH_COMPONENT.DS.'libraries'.DS.'chart');
    	
    	self::$blockStatus = array(
            	'O' => 'Open',
                'P' => 'Pending',
                'T' => 'Tentative',
                'C' => 'Challenged',
                'A' => 'Approved',
                'R' => 'Archived'
                );
    	    	
    	$user  = JFactory::getUser();
    	
    	if( $user->get('guest')) {
    		return;	
    	}

    	$uri = JURI::getInstance();
    	$params = JComponentHelper::getParams('com_sfs');
    	$enableSSL = $params->get('enable_ssl',0);

        if ( (int)$enableSSL == 1 && !$uri->isSSL() ) {
        	$app = JFactory::getApplication();           	    	        	        	
        	$uri->setScheme('https');
			$app->redirect((string)$uri);
        }
    	
 		
    	if( SFSAccess::check($user,'hotel') ) {    		    	
			SFactory::getHotel();
			$this->initializeHotelProfile();
    	}	
    		    	    
    	if( SFSAccess::check($user,'airline') && ! SFSAccess::check($user,'gh') ) {      		
			SFactory::getAirline();
			$this->initializeAirlineProfile();
    	}    

    	if( SFSAccess::check($user,'gh') ) {			
			$this->initializeGHProfile();
    	}   
    	
    	if( SFSAccess::check($user,'a.admin') ){
    		$document = JFactory::getDocument();
    		$document->addStyleDeclaration(
    			'ul.menu-mainmenu li.item-134{ display:block !important}'
    		);
    	}
    	
    	// Clear expired blocks and flights/seats for Airline
    	if( SFSAccess::check($user,'airline')) {
    		$this->cleanUp();
    	}
    	
    	return true;  	
    }    
    
    private function initializeHotelProfile ()
    {
    	$session  = JFactory::getSession();    	
    	$hotelProfile = $session->get('hotel_profile');
    	
    	if( empty( $hotelProfile ) ) {
    		$hotelProfile = $this->getProfiles();
    		$session->set('hotel_profile' , $hotelProfile);
    	}
    	
    	$hotel_id = $session->get('hotel_id',0);

    	if ( empty($hotel_id) ) {
    		$n = count($hotelProfile);
    		if( $n == 1 ) {
    			//single hotel so must be set the id into a session object.
    			foreach ($hotelProfile AS $k => $v) {
    				$session->set( 'hotel_id' , $k );
    				break;
    			}
    		}
    	}
    }
    
	private function initializeAirlineProfile ()
	{
		$session  = JFactory::getSession();			
		$airlineProfile = $session->get('airline_profile');
						
		if( empty( $airlineProfile ) ) {
			$airlineProfile = $this->getProfiles('airline');
			$session->set('airline_profile' , $airlineProfile);
		}
		
		$airline_id = $session->get('airline_id',0);
					
		if ( empty($airline_id) ) {
			$n = count($airlineProfile);
			if( $n == 1 ) {				
				//single airline so must be set the id into a session object.
				foreach ($airlineProfile AS $k => $v) {
					$session->set( 'airline_id' , $k );
					break;
				}
			}
		}
	}   
    
	private function initializeGHProfile ()
	{
		$session  = JFactory::getSession();			
		$airlineProfile = $session->get('airline_profile');
								
		if( empty( $airlineProfile ) ) {
			$airlineProfile = $this->getServicingAirlines();
			$session->set('airline_profile' , $airlineProfile);
		}
		
		$airline_id = $session->get('airline_id',0);
		
		if( (int) $airline_id ) {
			$airline = SFactory::getAirline();
			$airline->iatacode_id = $airline_id;
		}
		
	} 
	
	protected function getServicingAirlines()
	{
		$airline = SFactory::getAirline();
		
		$db    = JFactory::getDbo();	    
	    $query = $db->getQuery(true);
	    $query->select('a.airline_id,b.name');
	    $query->from('#__sfs_groundhandlers_airlines AS a');
	    $query->innerJoin('#__sfs_iatacodes AS b ON b.id=a.airline_id');
	    $query->where('a.ground_id = ' . $airline->id);
	    
	    $query->order('b.name ASC');
	    
	    
	    $db->setQuery($query);
	    $result = $db->loadAssocList('airline_id','name');	 
	    return $result; 
	}
	
    protected function getProfiles( $type='hotel' )
    {
    	$session  	  = JFactory::getSession();    	
    	$profiles = $session->get($type.'_profile',null);

    	if( empty($profiles) ) {
	    	$user  = JFactory::getUser();
	    	$db    = JFactory::getDbo();	    
	    	$query = $db->getQuery(true);	    	
	    	$query->select('a.'.$type.'_id');
	    	$query->from('#__sfs_'.$type.'_user_map AS a');
	    	
	    	if($type=='hotel') {
	    		$query->select('b.name');
	    		$query->innerJoin('#__sfs_hotel AS b ON b.id=a.hotel_id');
	    	} else {
	    		$query->select('c.name');
	    		$query->innerJoin('#__sfs_airline_details AS b ON b.id=a.airline_id');
	    		$query->innerJoin('#__sfs_iatacodes AS c ON c.id=b.iatacode_id');	    		
	    	}
	    	
	    	$query->where('a.user_id='.(int)$user->id);
	    	$db->setQuery($query);
	    	$profiles = $db->loadAssocList($type.'_id','name');	    		    	
    	}    	    	
    	return $profiles; 
    }

    /**
     * Clear expired blocks and flights/seats for Airline
     */
    private function cleanUp()
    {
    	$airline = SFactory::getAirline();
    	$params = JComponentHelper::getParams('com_sfs');
    	
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		
    	$allowClean = false;
    	
    	$cleanTime = trim($params->get('match_hours'));	
		//lchung
		$setup_airport = (array)JFactory::getSession()->get('setup_airport');
		if ( !empty( $setup_airport ) ) {
			$cleanTime = $setup_airport['hours_on_match_page'];
		}
		//End lchung
    	    	    	
    	if( isset($airline) && (int)$airline->id > 0 ) {
	    	
			if( strlen($cleanTime) <= 0  ) {
				return;
			}
			if ( ! JString::strpos($cleanTime, ':') ){
				return;
			}
			
	    	$now = SfsHelperDate::getDate('now','H:i', $time_zone);		
			
			$now = explode(':', $now);
			
			$cleanTime = explode(':', $cleanTime);
			
			JArrayHelper::toInteger($now);
			JArrayHelper::toInteger($cleanTime);
			
			//echo $now[0].' - '.$cleanTime[0].'<br />';
			//echo $now[1].' - '.$cleanTime[1];
					
			if( $now[0] >= $cleanTime[0] ){
				if( $now[0] == $cleanTime[0]) {
					if( $now[1] >= $cleanTime[1]  ) {
						$allowClean = true;
					}	
				} else {
					$allowClean = true;
				}						
			}			
    	}
    	
    	if( $allowClean === true ) 
    	{    	
    		
    		$db = JFactory::getDbo();
			
    		$now = SfsHelperDate::getDate('now','Y-m-d', $time_zone);
								
			$query = 'SELECT COUNT(*) FROM #__sfs_time_tracking WHERE airline_id='.$airline->id.' AND date='.$db->quote($now);
			$db->setQuery($query);
			$result = $db->loadResult();
			
			if( (int)$result > 0  ) {
				return;
			}
			
			
			
    		//We make all reservations be expired	
			$query = $db->getQuery(true);
			
			$query->select('a.id');
			$query->from('#__sfs_reservations AS a');
			$query->innerJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
			$query->where('a.airline_id = '.$airline->id);			
			$query->where('b.date<'.$db->quote($now));				
			$query->where('a.expired = 0');
			
			$db->setQuery($query);
			
			$reservations = $db->loadResultArray();
			
			
			if( count($reservations)) {
				JArrayHelper::toInteger($reservations);				
				$query = 'UPDATE #__sfs_reservations SET expired=1 WHERE id IN ('.implode(',',$reservations).')';
				$db->setQuery($query);
				$db->query();									
			}
					
			//Get Flights/Seats
			$query = $db->getQuery(true);
			$query->select('a.id,a.created');
			$query->from('#__sfs_flights_seats AS a');
			$query->where('a.airline_id = '.$airline->id);	
			$query->where('a.is_expire = 0');
			$query->where('DATEDIFF(a.end_date,'.$db->quote($now).') <= 0');
			
			$db->setQuery($query);
			
			$flights = $db->loadAssocList('id','created');
			
			//process clean up flights/seats
			if( count($flights) ) 
			{
				$expiredIDs  = array();
				$nowTime     = SfsHelperDate::getDate('now','H:i', $time_zone);
				foreach ($flights as $id => $date) 
				{
					$created = SfsHelperDate::getDate($date,'Y-m-d H:i', $time_zone);
					$createdDate = JString::substr($created, 0, 10);
					$createdDate = trim($createdDate);

					if( $createdDate == $now ) {
						$createdTime = trim(JString::substr($created, 10));
						$createdTime = explode(':', $createdTime);

						//Convert to integer
						JArrayHelper::toInteger($createdTime);
												
						if( $createdTime[0] <= $cleanTime[0] ){
							if( $createdTime[0] == $cleanTime[0]) {
								if( $createdTime[1] < $cleanTime[1]  ) {
									$expiredIDs[] = $id;
								}	
							} else {
								$expiredIDs[] = $id;
							}						
						}
						
					} else {
						$expiredIDs[] = $id;
					}					
				}
				if( count($expiredIDs) ){
					//Make flights to be expired
					$query = 'UPDATE #__sfs_flights_seats SET is_expire=1 WHERE id IN ('.implode(',',$expiredIDs).')';					
					$db->setQuery($query);
					$db->query();
				}
			}					
					
			//Insert tracking date
			$query = 'INSERT INTO #__sfs_time_tracking(airline_id,date) VALUES('.$airline->id.','.$db->quote($now).')';
			$db->setQuery($query);
			$db->query();			
    	}

    	return true;    	
    }

    public static function updateBlockStatus($blockId, $status, $updated_by = null) 
    {
        if (isset(self::$blockStatus[$status])) {     
        	       
            $user = JFactory::getUser();
            
            $db = JFactory::getDbo();
           
            $block = null;

            // Process when hotel change status
            if (SFSAccess::isHotel($user)) {                
                $hotel = SFactory::getHotel();                
				$query = 'SELECT a.* FROM #__sfs_reservations AS a';
                $query .= ' INNER JOIN #__sfs_room_inventory AS b ON b.id = a.room_id';
                $query .= ' WHERE a.id = ' . $blockId . ' AND b.hotel_id=' . $hotel->id;

                $db->setQuery($query);

                if (!$db->Query()) {
                    throw new JException('Hotel Update status to: ' . $status . ' failed');
                }

                $block = $db->loadObject();
            } 
            // Process when airline change status
            else if (SFSAccess::isAirline($user)) {                
                $airline = SFactory::getAirline();
                $query = 'SELECT a.*,b.hotel_id,MONTH(b.date) AS d_month, YEAR(b.date) AS d_year FROM #__sfs_reservations AS a';
                if ($status == 'A') {
                	$query .= ' INNER JOIN #__sfs_room_inventory AS b ON b.id = a.room_id';
                }
                $query .= ' WHERE a.id = ' . $blockId . ' AND a.airline_id = ' . $airline->id;

                $db->setQuery($query);

                if (!$db->Query()) {
                    throw new JException('Update status to: ' . $status . ' failed');
                }

                $block = $db->loadObject();
            } else {
                return false;
            }

            if (isset($block)) {

                if ($status == 'R' && $block->status != 'A') {
                    return false;
                }

                $query = 'UPDATE #__sfs_reservations SET status=' . $db->quote($status);

                if ($updated_by) {
                    $query .= ',hotel_user_id=' . (int) $updated_by;
                }
                        	                            

                if ($status == 'A') {
                    $now = JFactory::getDate()->toSQL();
                    $query .= ',approved_date=' . $db->quote($now);
                }

                $query .= ' WHERE id=' . $blockId;
                $db->setQuery($query);

                if (!$db->Query()) {
                    throw new JException('Update status to: ' . $status . ' failed' . $db->getErrorMsg());
                }
                
				//When airline approve the rooming list we need to update cache for hotel reporting table for implementing perfomance
                if ($status == 'A') {
                	
                	//calculate revenue                	
                	$forceLoad = true;
					$reservation = SReservation::getInstance( $block->id, $forceLoad);
						
                	$revenue_booked = 0;                 	
                	
                	$total_room_charge = $reservation->getTotalRoomCharge();
				
		        	$picked_mealplans  = $reservation->calculateTotalMealplan();
					$picked_breakfasts = $reservation->calculateTotalBreakfast();
					$picked_lunchs 	   = $reservation->calculateTotalLunch();
					
					$total_mealplan_charge = $reservation->getTotalMealplanCharge() + ( $picked_breakfasts * $reservation->breakfast) + ($picked_lunchs * $reservation->lunch);
					
					$revenue_booked = $total_room_charge + $total_mealplan_charge;

                	$query = 'UPDATE #__sfs_reservations SET revenue_booked='.$revenue_booked.' WHERE id='.$block->id;
                	$db->setQuery($query);
                	if (!$db->Query()) {
                    	throw new Exception($db->getErrorMsg());
                	}
										
                	$query = 'SELECT * FROM #__sfs_hotel_reporting WHERE hotel_id='.$block->hotel_id.' AND YEAR(date)='.$db->quote($block->d_year).' AND MONTH(date)='.$db->quote($block->d_month);
                	$db->setQuery($query);
                	$result = (int)$db->loadObject();
                	
                	if( !empty($result) ) {
                		$query = 'UPDATE #__sfs_hotel_reporting SET number_booking=number_booking+1,number_rooms=number_rooms+'.(int)$block->claimed_rooms.',room_price_total=room_price_total+'.$block->sd_rate.',revenue_booked=revenue_booked+'.$revenue_booked;
              			$query .= ' WHERE hotel_id='.$block->hotel_id.' AND YEAR(date)='.$db->quote($block->d_year).' AND MONTH(date)='.$db->quote($block->d_month);
                	} else {                		
                		$query = 'INSERT INTO #__sfs_hotel_reporting(id,hotel_id,date,number_booking,number_rooms,room_price_total,revenue_booked) VALUES';
                		$insertDate = ((int)$block->d_month < 10) ? $block->d_year.'-0'.$block->d_month.'-01'  : $block->d_year.'-'.$block->d_month.'-01' ;
                		$query .= '(0,'.$block->hotel_id.','.$db->quote($insertDate).',1,'.(int)$block->claimed_rooms.','.$block->sd_rate.','.$revenue_booked.')';                                		
                	}
                	
                	$db->setQuery($query);
                	if (!$db->Query()) {
                    	throw new Exception($db->getErrorMsg());
                	}                  	
                	
                }                   
                
            }

            return true;
        }
        
        return false;
    }
    

    
}





