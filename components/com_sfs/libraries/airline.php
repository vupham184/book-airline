<?php
// No direct access
defined('_JEXEC') or die;

class SAirline extends SfsHandlerDecorator
{
	public $id = null;
	public $iatacode_id = null;
	public $logo = null;
	public $affiliation_code = null;
	public $airline_alliance = null;
	public $airport_id = null;
	public $time_zone = null;
	public $partner_limit_for_extra_search = null;

	public $address = null;
	public $address2 = null;
	public $city = null;
	public $state_id = null;
	public $zipcode = null;
	public $country_id = null;
	public $telephone = null;

	public $created_by = null;
	public $created_date = null;
	public $modified_by = null;
	public $modified_date = null;

	public $published = null;
	public $approved = null;

	public $params=null;

	public $country_name = null;

	public $name=null;
	public $code=null;

	public $airport_code=null;
	public $airport_status=null;
	public $airport_name=null;
	
	//lchung
	public $currency_numeric_code=null;
	public $currency_code=null;
	//End lchung

	// Current id is session selected ID than airline_details.airport_id in airline_table
	public $airport_current_id=null;
	public $airplusparams = array();
	
	private $_flights_seats = null;

	//for ground handler only
	private $_servicing_airlines = null;
	private $_selected_airline = null;
	private $_voucher_comment = null;
	private $_contract_details = null;
	private $_taxi_companies = null;
	private $_taxi_details = null;
	
	/**
	 * SAirline Constructor
	 *
	 * @param	int		$identifier	The primary key of the airline to load (optional).
	 *
	 * @return	SAirline
	 * @since	1.0
	 */
	public function __construct($identifier = 0, $userId = 0, $db = null)
	{
		parent::__construct();

		if($userId){
			$user = JFactory::getUser($userId);
		} else {
			$user = JFactory::getUser();
		}

		if( SFSAccess::check($user, 'airline') ) {
			$this->set('grouptype',2);
		}

		if( SFSAccess::check($user, 'gh') ) {
			$this->set('grouptype',3);
		}

		if ( ! empty($identifier) ) {
			$this->load($identifier);
		}
		else {
			//if a airline user is logged in we load the airline
			$session = JFactory::getSession();
			$user = JFactory::getUser();
			if( SFSAccess::isAirline($user) ) {

                $airline_id = (int) $session->get('airline_id',0);


                if( ! empty($airline_id) && $this->grouptype == 2 ) {
                    $this->load($airline_id) ;
                    $currentAirport = $this->getCurrentAirport();
                    $this->airport_code = $currentAirport->code;
                    $this->airport_name = $currentAirport->name;
                    $this->airport_id = $currentAirport->id;
					$this->airport_status = $currentAirport->status;
                } else {
                    $this->load($user->id,'user') ;
                }
                $currentAirport = $this->getCurrentAirport();
                $this->airport_code = $currentAirport->code;
                $this->airport_name = $currentAirport->name;
                $this->airport_id = $currentAirport->id;
			} else {
				$this->id = 0;
			}
		}
	}

	/**
	 * Returns the global airline object, only creating it if it doesn't already exist.
	 *
	 * @param	int	$identifier	The airline to load - Can be an integer or string - If string, it is converted to ID automatically.
	 *
	 * @return	SAirline	The airline object.load
	 * @since	1.0
	 */
	public static function getInstance( $id = 0, $userId=0 )
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty($instances[$id])) {
			$airline = new SAirline( $id, $userId );
			$instances[$id] = $airline;
		}

		return $instances[$id];
	}

	/**
	 * Gets the company name of airline or gh
	 *
	 */
	public function getAirlineName()
	{
		if( empty($this->id) ) {
			return null;
		}

		if( $this->company_name )
		{
			return $this->company_name;
		}

		if( $this->name )
		{
			return $this->name;
		}

		$db = JFactory::getDbo();

		$query = 'SELECT name FROM #__sfs_iatacodes WHERE id = '.(int)$this->iatacode_id.' AND type = 1';

		$db->setQuery($query);

		$result = $db->loadResult();

		return $result;
	}

	public function getAirportName()
	{
		if( empty($this->id) ) {
			return null;
		}

		if( empty($this->airport_name) && $this->airport_id )
		{
			$db = JFactory::getDbo();
			$query = 'SELECT name FROM #__sfs_iatacodes WHERE id = '.(int)$this->airport_id.' AND type = 2';
			$db->setQuery($query);
			$this->airport_name = $db->loadResult();
		}
		return $this->airport_name;
	}

	public  function getCurrentAirport()
	{
        parent::__construct();
        $db = JFactory::getDbo();
		$session = JFactory::getSession();
        $user	= JFactory::getUser();

        $airport_current_id = $session->get("airport_current_id");
        $this->airport_current_id = $airport_current_id;
        //CPhuc
        $currency_code = $session->get("currency_code");
        $this->currency_code = $currency_code;
        
        //end CPhuc
        $input = JFactory::getApplication()->input;
        $view = $input->get('view');
        $layout = $input->get('layout');

        if((int)$airport_current_id == -1)
        {
            if((($view == "handler" && $layout == "search" )|| ($view == "handler" && $layout == "flightform" ) || $view == "search" || $view == "match") || ($user->airport && $view != "passengersimport"))
            {
                $airport_current_id = "";
            }
            else
            {
                $obj = new stdClass();
                $obj->id = -1;
                $obj->code = "All Airports";
                return $obj;
            }
        }


        if(empty($airport_current_id)) {
            $airport_current_id = $this->airport_id;
        }
		$airline_Timezone = '';
		
		$query = 'SELECT airline_id FROM #__sfs_airline_user_map WHERE user_id = '.(int)$user->id;
		$db->setQuery($query);		
		$airline_id = (int)$db->loadObject()->airline_id;
		
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM #__sfs_airline_details WHERE id = '.(int)$airline_id;
		$db->setQuery($query);		
		$dAir = $db->loadObject();
		///print_r( $dAir );die;
		$airline_Timezone = $dAir->time_zone;
		if(empty($airport_current_id )) {
			$airport_current_id =  $dAir->airport_id;
		}
		
		$db = JFactory::getDbo();
		$query = 'SELECT id, name, code, currency_code, status, time_zone FROM #__sfs_iatacodes WHERE id = '.(int)$airport_current_id.' AND type = 2';
		$db->setQuery($query);		
		$result = $db->loadObject();
		//get currency_code
		if (empty($currency_code)) {
			$this->currency_code = $result->currency_code;
		}
		//lay time_zone from airline
		if( $airline_Timezone != '' ){
			$result->time_zone = $airline_Timezone;
		}
		
		if( isset( $result ) && $result->time_zone == '' ) {
			$result->time_zone = self::checkSetTimezoneDefault();
		}
		return $result;
	}

	/**
	 *
	 */
	public function getServicingAirlines($isContract=false,$orderBy=null)
	{
		if( $this->_servicing_airlines === null )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			$query->select('a.airline_id,b.name,b.code');
			if($isContract)
			{
				$query->select('a.contract_details');
			}
			$query->from('#__sfs_groundhandlers_airlines AS a');
			$query->innerJoin('#__sfs_iatacodes AS b ON b.id=a.airline_id');
			$query->where('a.ground_id='.$this->id);

			if($orderBy){
				$query->order($orderBy);
			} else{
				$query->order('b.name ASC');
			}
			$db->setQuery($query);

			$this->_servicing_airlines = $db->loadObjectList();
		}
		return $this->_servicing_airlines;
	}

	/**
	 *
	 */
	public function getSelectedAirline()
	{
		if( (int) $this->iatacode_id > 0 ) {
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.code,a.name');
			$query->from('#__sfs_iatacodes AS a');
			$query->where('a.id='.(int) $this->iatacode_id);

			$db->setQuery($query);
			$this->_selected_airline = $db->loadObject();
		}
		return $this->_selected_airline;
	}

    public function getAirlineAirportData(){
    	$user = JFactory::getUser();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('a.id , a.code, name, geo_lat, geo_lon');
        $query->from('#__sfs_iatacodes AS a');
        $query->innerJoin('#__sfs_airline_airport AS b ON b.airport_id=a.id');
        $query->where('b.airline_detail_id=' . (int)$this->id);
        $query->where('a.type=2');
        $query->where('b.user_id = ' . $user->id);
        $query->order('a.code ASC');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

	public function getFlightsSeats( $dateNight = null )
	{
		$db	= $this->getDbo();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;

		if( $this->_flights_seats == null )
		{
			$query = $db->getQuery(true);
			$query->select('a.*');
			$query->from('#__sfs_flights_seats AS a');

			if( (int)$this->grouptype == 3 && (int)$this->iatacode_id > 0 )
			{
				$query->innerJoin('#__sfs_gh_flights_seats AS b ON b.fligh_id=a.id AND	b.gh_id='.$this->iatacode_id);
			}

			$query->where('a.airport_id='.$airport_current_id);
			$query->where('a.airline_id='.$this->id);

			//$query->where('a.is_expire=0');

			$query->where('a.seats > a.seats_issued');

			if($dateNight)
			{
				$query->where('a.from_date <= '.$db->quote($dateNight));
				$query->where('a.end_date > '.$db->quote($dateNight));
			}

			$query->order('a.created DESC');

			$db->setQuery($query);

			$this->_flights_seats = $db->loadObjectList();

			if( $db->getErrorNum() ) {
				$this->setError($db->getErrorMsg());
				return null;
			}

		}

		return $this->_flights_seats;
	}

	/**
	 * Gets general voucher comment of the airline
	 *
	 */
	public function getVoucherComment()
	{

		if( $this->_voucher_comment == null )
		{
			$db = JFactory::getDbo();

			$query = 'SELECT comment FROM #__sfs_vouchercomments WHERE airline_id='.(int)$this->id;
			$db->setQuery($query);

			$this->_voucher_comment = $db->loadResult();

			if( ! $this->_voucher_comment )
			{
				$query = 'SELECT comment FROM #__sfs_iatacodes WHERE id='.(int)$this->iatacode_id;
				$db->setQuery($query);

				$this->_voucher_comment = $db->loadResult();
			}

		}

		return $this->_voucher_comment;
	}

	/**
	 * Gets contract details
	 * User Type: GH only
	 */
	public function getContractDetails()
	{
		if( $this->grouptype == 3 && (int) $this->iatacode_id > 0 && $this->_contract_details == null ) {
			$db = JFactory::getDbo();

			$query = 'SELECT contract_details FROM #__sfs_groundhandlers_airlines WHERE ground_id='.(int)$this->id.' AND airline_id='.$this->iatacode_id;
			$db->setQuery($query);
			$this->_contract_details = $db->loadResult();
		}
		return $this->_contract_details;
	}

	public function getTaxiDetails()
	{
		if( $this->_taxi_details == null && $this->id ) {
			$db		 = JFactory::getDbo();
			$query   = $db->getQuery(true);

			$query->select('a.*,b.*');
			$query->from('#__sfs_airline_taxidetail_map AS a');
			$query->innerJoin('#__sfs_taxi_details AS b ON b.id=a.taxi_detail_id');

			$query->select('c.name AS state,d.name AS billing_state');
			$query->leftJoin('#__sfs_states AS c ON c.id=b.state_id');
			$query->leftJoin('#__sfs_states AS d ON d.id=b.billing_state_id');

			$query->select('e.name AS country, f.name AS billing_country');
			$query->leftJoin('#__sfs_country AS e ON e.id=b.country_id');
			$query->leftJoin('#__sfs_country AS f ON f.id=b.billing_country_id');


			$query->where('a.airline_id = ' . (int) $this->id );

			$db->setQuery($query);

			$this->_taxi_details = $db->loadObject();
		}
		return $this->_taxi_details;
	}

	/**
	 * Gets Taxi Companies
	 *
	 */
	public function getTaxiCompanies()
	{
		if( $this->_taxi_companies == null && $this->id ) {

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('a.*');
			$query->from('#__sfs_airline_taxicompany_map AS a');

			$query->select('b.name, b.published, b.fare_from, b.fare_until, b.available_days');
			$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');

			$query->where('b.published=1');
			$query->where('a.airline_id='.(int)$this->id);

			$db->setQuery($query);

			$this->_taxi_companies = $db->loadObjectList();
		}
		return $this->_taxi_companies;
	}

	public function getTaxiHotelRate($taxiId, $hotelId, $ring = 0)
	{
		if( empty($taxiId) || empty($hotelId) ) return null;

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.*');
		$query->from('#__sfs_taxi_hotel_rates AS a');

		$query->where('a.taxi_id='.(int)$taxiId);
		$query->where('(a.hotel_id='.(int)$hotelId.' OR a.hotel_id=0)');

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$hotelRate  = null;
		$oneWayRate = null;

		if( count($rows) )
		{
			foreach ($rows as $row)
			{
				if( (int)$row->hotel_id == (int)$hotelId )
				{
					$hotelRate  = new stdClass();
					$hotelRate->ring 			= $row->ring;
					$hotelRate->day_fare 		= $row->day_fare;
					$hotelRate->night_fare 		= $row->night_fare;
					$hotelRate->weekend_fare	= $row->weekend_fare;
					break;
				}
				if( (int)$row->hotel_id == 0 &&  (int)$row->ring == (int) $ring)
				{
					$oneWayRate = new stdClass();
					$oneWayRate->ring 			= $row->ring;
					$oneWayRate->day_fare 		= $row->day_fare;
					$oneWayRate->night_fare 	= $row->night_fare;
					$oneWayRate->weekend_fare	= $row->weekend_fare;
				}
			}
		}

		if($hotelRate)
		{
			return $hotelRate;
		} else {
			return $oneWayRate;
		}


	}

	/**
	 * Method to get the airline table object
	 *
	 */
	public function getTable()
	{
		return JTable::getInstance('Airline', 'JTable');
	}

	/**
	 * Method to load a Airline object
	 *
	 * @param	mixed	$id	The airline id or user id to load
	 * @param	string	$type primary key or user
	 *
	 * @return	boolean	True on success
	 * @since	1.0
	 */
	public function load( $id , $type = 'pk' )
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

        if($this->grouptype == 2 ) {
            $query->select('a.*,b.name,b.code,d.code AS airport_code, d.status AS airport_status,d.name AS airport_name,c.name AS country_n, d.geo_lat, d.geo_lon');
        } else {
            $query->select('a.*,d.code AS airport_code, d.status AS airport_status,d.name AS airport_name,c.name AS country_n, d.geo_lat, d.geo_lon');
        }

        $query->from('#__sfs_airline_details AS a');


        if($this->grouptype == 2 ) {
        	$query->innerJoin('#__sfs_iatacodes AS b ON b.id=a.iatacode_id');
        }

        $query->innerJoin('#__sfs_country AS c ON c.id=a.country_id');


		$currentAirport = $this->getCurrentAirport();

		if($currentAirport && $currentAirport->id!=-1)
        	$query->leftJoin('#__sfs_iatacodes AS d ON d.id='. $currentAirport->id);
        else
			$query->innerJoin('#__sfs_iatacodes AS d ON d.id=a.airport_id');

		//lchung get numeric_code
		$query->select('e.numeric_code as currency_numeric_code, d.currency_code');
		$query->innerJoin('#__sfs_currency AS e ON e.code=d.currency_code');
		//End lchung
		
		$query->where('a.id='. (int) $pk);
		$db->setQuery($query);

		$airline = $db->loadObject();
		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		if (empty($airline)) {
			throw new Exception(JText::_('COM_SFS_AIRLINE_NOT_FOUND'));
		}

		if($this->grouptype == 3 ) {
        	$this->name = $airline->company_name;
        }

		$vars = get_object_vars($airline);

		foreach ($vars as $key => $value) {
			$this->$key = $value;
		}

		$registry = new JRegistry;
		$registry->loadString($this->params);
		$this->params = $registry->toArray();

		$this->airplusparams = array();
		if(@$airline->airline_airplusws_id) {
			$query = 'SELECT * FROM #__sfs_airline_airplusws WHERE id=' . $db->quote($airline->airline_airplusws_id);
			$db->setQuery($query);
			$this->airplusparams = $db->loadAssoc();
		}
		
		return true;
	}

	/**
	 * Method to bind an associative array of data to a airline object
	 *
	 * @param	array	$array	The associative array to bind to the object
	 *
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	public function bind( & $data )
	{

		// Lets check to see if the airline is new or not
		if (empty($this->id)) {
			$this->set( 'created_date' , JFactory::getDate()->toSQL());
			$this->set( 'block', 0 );
			$this->set( 'approved', 0 );
		} else {
			if( empty(JFactory::getUser()->id ) ) {
				$this->setError('Guest : Unable to bind array to airline object');
				return false;
			}
			$this->set( 'modified_by' , JFactory::getUser()->id );
			$this->set( 'modified_date' ,JFactory::getDate()->toSQL() );
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
	 * Method to save the airline object to the database
	 *
	 *
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	public function save( $skipCheck = false )
	{
		$db				= JFactory::getDbo();
		$table			= $this->getTable();
		$table->bind($this->getProperties());

		// Allow an exception to be throw.
		try
		{
			// Check and store the object.
			if( $skipCheck === false ) {
				if (!$table->check()) {
					$this->setError($table->getError());
					return false;
				}
			}
			// Store airline data in the database
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


	public function saveVoucherComment($voucherComment)
	{
		if( is_array($voucherComment) )
		{
			$this->saveGhVoucherComment($voucherComment);
		} else {
			$this->saveAirlineVoucherComment($voucherComment);
		}

		return true;
	}

	private function saveAirlineVoucherComment($voucherComment)
	{
		$voucherComment = trim($voucherComment);

		if( strlen($voucherComment) == 0 )
			return false;

		$db = JFactory::getDbo();
		$query = 'SELECT id FROM #__sfs_vouchercomments WHERE airline_id='.(int)$this->id;
		$db->setQuery($query);

		$voucherCommentId = (int)$db->loadResult();
		if( $voucherCommentId > 0 )
		{
			$query = 'UPDATE #__sfs_vouchercomments SET comment='.$db->quote($voucherComment).' WHERE id='.$voucherCommentId;
			$db->setQuery($query);
			$db->query();
		} else {
			$comment = new stdClass();
			$comment->comment = $voucherComment;
			$comment->airline_id = $this->id;
			$db->insertObject('#__sfs_vouchercomments', $comment);
		}
		return true;
	}

	private function saveGhVoucherComment($voucherComment)
	{
		if( count($voucherComment) )
		{
			$db = JFactory::getDbo();
			$query = 'SELECT id,comment FROM #__sfs_vouchercomments WHERE airline_id='.(int)$this->id;
			$db->setQuery($query);

			$commentObject = $db->loadObject();

			if( $commentObject )
			{
				$registry = new JRegistry();
				$registry->loadString($commentObject->comment);
				$comments = $registry->toArray();
				$voucherComment[0] = $comments[0];
			}

			$registry = new JRegistry();
			$registry->loadArray($voucherComment);

			$voucherComment = $registry->toString();


			if( $commentObject )
			{
				$query = 'UPDATE #__sfs_vouchercomments SET comment='.$db->quote($voucherComment).' WHERE id='.(int)$commentObject->id;
				$db->setQuery($query);
				$db->query();
			} else {
				$comment = new stdClass();
				$comment->comment = $voucherComment;
				$comment->airline_id = $this->id;
				$db->insertObject('#__sfs_vouchercomments', $comment);
			}

		}
		return true;
	}

	/**
	 * Method to determine if the taxi voucher system is available for the airline
	 *
	 * @return  boolean  True if available
	 *
	 */
	public function allowTaxiVoucher()
	{
		if( $this->params && isset($this->params['enable_taxi_voucher']) )
		{
			$enable_taxi_voucher = $this->params['enable_taxi_voucher'];
			if( (int) $enable_taxi_voucher == 1 )
			{
				return true;
			}
		}
		return false;
	}

	public function allowEditTaxiDetails()
	{
		if( $this->params && isset($this->params['can_edit_taxi']) )
		{
			$can_edit_taxi = $this->params['can_edit_taxi'];
			if( (int) $can_edit_taxi == 1 )
			{
				return true;
			}
		}
		return false;
	}

	public function allowGroupTransportation()
	{
		if( $this->params && isset($this->params['enable_group_transport']) )
		{
			$enable_group_transport = $this->params['enable_group_transport'];
			if( (int) $enable_group_transport == 1 )
			{
				return true;
			}
		}
		return false;
	}
	
	//lchung
	public static function getTimezoneInIatacodes( $id_hotel = 0, $user_id = 0 )
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.time_zone');
		$query->from('#__sfs_iatacodes AS a');
		$query->innerJoin('#__sfs_hotel_airports AS b ON a.id=b.airport_id');
		$query->where('b.hotel_id='.$id_hotel);
		$query->where('b.main = 1 ');	
		$db->setQuery($query);	
		//die((string)$query);	
		$rows = $db->loadObject();
		$t = $rows->time_zone;
		return $t;
	}
	
	public static function checkSetTimezoneDefault( $time_zone = '' ){
		if ( $time_zone == '' ) {
			$params = JComponentHelper::getParams('com_sfs');
			$time_zone = trim($params->get('sfs_system_timezone'));	
			if ( $time_zone == '' ) {
				//Set default the not error [500 - DateTimeZone::__construct(): Unknown or bad timezone ()]
				$time_zone = 'America/New_York';
			}
		}
		return $time_zone;
	}
	//End lchung

}


