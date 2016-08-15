<?php
defined('_JEXEC') or die;

class SReservation
{
	public $id = null;
	public $blockcode = null;
	public $blockdate = null;
	public $airline_id = null;
	public $hotel_id = null;
	public $room_id = null;

	public $sd_room = null;
	public $t_room = null;
	public $sd_room_issued = null;
	public $t_room_issued = null;
	public $claimed_rooms = null;

	public $sd_rate = null;
	public $t_rate = null;
	public $revenue_booked = null;
	public $breakfast = null;
	public $lunch = null;
	public $mealplan = null;

	public $status = null;

	public $room_date = null;
	public $booked_date = null;
	public $booked_by = null;
	public $approved_date = null;
	public $payment_type = null;

	public $association_id	= null;

	public $hotel_user_id = null;

	public $ws_room_type = null;
	public $ws_prebooking = null;
	public $ws_booking = null;
	public $ws_room = null;

	public $url_code = null;
	public $airport_code = null;
	
	public  $_vouchers		= null;
	private $_passengers	= null;
	private $_trace_passengers	= null;
	private $_picked_rooms	= null;
	private $_initial_rooms	= null;

	private $_db = null;


	/**
	 * Reservation Constructor
	 *
	 * @param	int		$identifier	The primary key of Reservation to load (optional).
	 *
	 * @return	SHotel
	 * @since	1.0
	 */
	public function __construct($identifier = 0, $forceLoad = false, $loadType = 'id', $db = null)
	{
		if($db === null)
		{
			$db = JFactory::getDbo();
		}

		$this->setDbo($db);

		if ( ! empty($identifier) )
		{
			$this->load($identifier, $loadType);

			// Load all data at the same time
			if($forceLoad)
			{
				$this->loadVouchers();
				$passengers = $this->getPassengers();
			}
		}
		else {
			$this->id = 0;
		}
	}

	public static function getInstance( $id = 0, $forceLoad = false, $loadType = 'id' )
	{
		static $instance;

		if ( empty($instance )) {
			$instance = new SReservation($id,$forceLoad,$loadType);
		}

		return $instance;
	}

	public function setDbo($db)
	{
		$this->_db = $db;
	}

	public function getDbo()
	{
		return $this->_db;
	}


	public function getInitialRooms()
	{
		if( empty($this->_initial_rooms) )
		{
			$this->_initial_rooms = array();
			$this->_initial_rooms[1] = $this->s_room;
			$this->_initial_rooms[2] = $this->sd_room;
			$this->_initial_rooms[3] = $this->t_room;
			$this->_initial_rooms[4] = $this->q_room;
		}

		return $this->_initial_rooms;
	}

    public function getPickedRooms()
    {
        if( empty($this->_picked_rooms) )
        {
            $this->loadVouchers();
            $passengers = $this->getPassengers();
            $this->_picked_rooms = array(1=>0,2=>0,3=>0,4=>0);

            foreach ($this->_vouchers as $v) {


                //calculate the passengers loaded by the hotel
                $number_passenger = 0;
                if(count($passengers)) {
                    foreach ($passengers as $p) {
                        if($p->id==$v->id) {
                            $number_passenger++;
                        }
                    }
                }

                // calculate the picked rooms with group voucher
                // only calculate if the passengers are existed
                if( $number_passenger && (int)$v->status < 3) {
                    if((int)$v->sroom ){
                        $this->_picked_rooms[1] = $this->_picked_rooms[1] + (int)$v->sroom;
                    }
                    if((int)$v->sdroom ){
                        $this->_picked_rooms[2] = $this->_picked_rooms[2] + (int)$v->sdroom;
                    }
                    if((int)$v->troom ){
                        $this->_picked_rooms[3] = $this->_picked_rooms[3] + (int)$v->troom;
                    }
                    if((int)$v->qroom ){
                        $this->_picked_rooms[4] = $this->_picked_rooms[4] + (int)$v->qroom;
                    }
                }

            }

        }

        return $this->_picked_rooms;
    }


	/**
	 * Method to calculate Estimated total nett room charge
	 *
	 */
	public function getTotalRoomCharge()
	{
		$pickedRooms = $this->getPickedRooms();

		$price = $pickedRooms[1] * $this->s_rate + $pickedRooms[2] * $this->sd_rate + $pickedRooms[3] * $this->t_rate + $pickedRooms[4] * $this->q_rate;

		return $price;
	}

	/**
	 * Method to calculate Estimated total nett mealplan charge
	 *
	 */
	public function getTotalMealplanCharge()
	{
		$totalPassengers = $this->calculateTotalMealplan();
		$result = 0;
		if( $this->mealplan ) {
			$result  = $totalPassengers * $this->mealplan;
		}
		return $result;
	}

	// Method to get Passengers
	public function getPassengers()
	{
		if(empty($this->_passengers)){
			$this->loadPassengers();
		}
		return $this->_passengers;
	}

    // Method to get Passengers
    public function getTracePassengers()
    {

        if(empty($this->_trace_passengers)){
            $this->loadTracePassengers();
        }
        return $this->_trace_passengers;
    }

	// Method to get messagess
	public function getMessages()
	{
		$model = JModel::getInstance('Messages', 'SfsModel',  array('ignore_request' => true) );

		$model->setDbo($this->getDbo());
		$model->setState('block.id', $this->id );

		$messages = $model->getItems();

		return $messages;
	}


	/**
	 * Load an reservation from database
	 *
	 * @param int 		$id
	 * @param string 	$loadType
	 * @throws Exception
	 *
	 * @return  True if successful.
	 *
	 */
	public function load( $identifier, $loadType = 'id' )
	{
		$db 	= $this->getDbo();
		$query  = $db->getQuery(true);

		$query->select('a.*, h.name, h.city');
		$query->from('#__sfs_reservations AS a');
		$query->innerJoin('#__sfs_hotel AS h on h.id = a.hotel_id');

		if( $loadType == 'id' )
		{
			$query->where('a.id='. (int) $identifier);
		}
		else if( $loadType == 'code' )
		{
			$query->where('a.blockcode='.$db->quote($identifier) );
		}

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}

		if (empty($result)) {
			JError::raiseError(500,JText::_('SReservation object was not found'));
			return false;
		}

		//extract object to an array
		$vars = get_object_vars($result);

		foreach ($vars as $key => $value) {
			$this->$key = $value;
		}

		$this->room_date = $this->blockdate;

		return true;
	}

    public function loadData( $data )
    {

        if (empty($data)) {
            JError::raiseError(500,JText::_('SReservation object was not found'));
            return false;
        }

        //extract object to an array
        $vars = get_object_vars($data);

        foreach ($vars as $key => $value) {
            $this->$key = $value;
        }

        $this->room_date = $this->blockdate;

        return true;
    }

	public function getVoucher( $voucher_id )
	{
		$db = $this->getDbo();
		$query = 'SELECT a.* FROM #__sfs_voucher_codes AS a';
		$query .= ' WHERE a.id='.(int)$voucher_id.' AND a.booking_id='.(int) $this->id;
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}

	public function getVouchers()
	{
		if( empty($this->_vouchers) ) {
			$this->loadVouchers();
		}
		return $this->_vouchers;
	}

	//load all vouchers that made by the airlines
	private function loadVouchers()
	{
		if( (int) $this->id > 0 && empty($this->_vouchers) )
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			$query->select('a.*,c.code AS taxi_voucher_code,e.reference_number AS bus_reference_number');
			$query->from('#__sfs_voucher_codes AS a');

			$query->select('rf.flight_number AS return_flight_number, rf.flight_date AS return_flight_date');
			$query->leftJoin('#__sfs_voucher_return_flights AS rf ON rf.voucher_id=a.id');

			$query->select('fs.flight_code,fs.comment AS flight_comment');
			$query->leftJoin('#__sfs_flights_seats AS fs ON fs.id=a.flight_id');

			$query->leftJoin('#__sfs_airline_taxi_voucher_map AS b ON b.voucher_id=a.id');
			$query->leftJoin('#__sfs_taxi_vouchers AS c ON c.id=b.taxi_voucher_id');

			$query->leftJoin('#__sfs_voucher_busreservation_map AS d ON d.voucher_id=a.id');
			$query->leftJoin('#__sfs_transportation_reservations AS e ON e.id=d.busreservation_id');

			$query->where('a.booking_id='.(int) $this->id);

			$db->setQuery($query);
			$this->_vouchers = $db->loadObjectList();
		}
		return true;
	}

	// load all detailed arrivals in the hotels
	private function loadPassengers()
	{
		if( empty($this->_passengers) )
		{
			$db = $this->getDbo();

			$query = $db->getQuery(true);

			$query->select('p.first_name,p.last_name,b.*,p.id as pid,f.flight_code');
			$query->from('#__sfs_passengers AS p');
			//$query->from('#__sfs_trace_passengers AS p');
			
			$query->innerJoin('#__sfs_voucher_codes AS b ON b.id=p.voucher_id');
			$query->innerJoin('#__sfs_flights_seats AS f ON f.id=b.flight_id');
			

			$query->where('b.booking_id='.(int)$this->id);
			$query->where('b.status < 3');
			
			$db->setQuery($query);

			$this->_passengers = $db->loadObjectList();
		}
		return true;
	}

    // load all detailed arrivals in the hotels
    private function loadTracePassengers()
    {    
        if( empty($this->_trace_passengers) )
        {
            $db = $this->getDbo();

            $query = $db->getQuery(true);

            $query->select('p.first_name,p.last_name,b.*,f.flight_code');
            $query->from('#__sfs_trace_passengers AS p');
            $query->innerJoin('#__sfs_voucher_codes AS b ON b.id=p.voucher_id');
            $query->innerJoin('#__sfs_flights_seats AS f ON f.id=b.flight_id');           

            $query->where('b.booking_id='.(int)$this->id);
            $query->where('b.status < 3');

            $db->setQuery($query);         
            $this->_trace_passengers = $db->loadObjectList();
        }
        return true;
    }

	/**
	 * Method to generate the blockcode after the roomblock is made.
	 *
	 * @param	string	$hotelName	The hotel name of the roomblock
	 *
	 * @return	string
	 * @since	1.6.0
	 */
	public static function generateBlockCode ( $airlineCode, $hotelName, $date_start )
	{
		$db = JFactory::getDbo();
		$airline = SFactory::getAirline();
		if(isset($airline) && $airline->id)
		{
			
			#lchung !@important don't translate timezone this point because it's translated before
			#$date_start = SfsHelperDate::getDate($date_start,'dmy',$airline->time_zone);
            $date_start =  date('dmy', strtotime($date_start) );
			
			// $blockcode = basename(JURI::base(true));
			$blockcode = $airline->airport_code;
			$blockcode.= '_'.$date_start;
			$blockcode.= '_'.$airlineCode;

			$tempArray = explode(" ", $hotelName);
			$tmpStr = '';

			foreach ( $tempArray as $val ) {
				$val = trim($val);
				if( strlen($val) == 0 ) continue;
				if( count($tempArray) == 1 ){
					$tmpStr = JString::substr($val, 0,2);
					break;
				}
				$tmpStr .= JString::substr($val, 0,1);
				if(strlen($tmpStr)==2) break;
			}

			$blockcode .= '_'.$tmpStr;

			while (true) {
				$tmpStr = $blockcode.'_'.rand(1000, 9999);
				$tmpStr = JString::strtoupper($tmpStr);
				$db->setQuery( 'SELECT COUNT(*) FROM #__sfs_reservations WHERE blockcode='.$db->quote($tmpStr) );

				$bCount = (int)$db->loadResult();
				if($bCount==0) {
					$blockcode = $tmpStr;
					break;
				}
			}
			return $blockcode;
		}

		JFactory::getApplication()->close();
	}

	public function set($property, $value = null)
	{
		$previous = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;
		return $previous;
	}

	public function get($property, $default = null)
	{
		if (isset($this->$property))
		{
			return $this->$property;
		}
		return $default;
	}

	public function getWsRoomTypes() {
		$rooms = unserialize($this->ws_room_type);
		$roomTypes = array();
		if($rooms) {
            foreach($rooms as $key =>  $r) {
                $roomTypes[$key] = Ws_Do_Search_RoomTypeResult::fromString($r['roomType']);
                $roomTypes[$key]->NumberOfRooms = (int)$r['number'];
			}
		}
		return $roomTypes;
	}

	public function getWsPreBooking(){
		$preBooking = null;
		if($this->ws_prebooking) {
			$preBooking = Ws_Do_PreBook_Response::fromString($this->ws_prebooking);
		}
		return $preBooking;
	}

	public function getWsBooking(){
		$book = null;
		if($this->ws_booking) {
			$book = Ws_Do_Book_Response::fromString($this->ws_booking);
		}
		return $book;
	}

	public function getWsTotalRooms() {
		$types = $this->getWsRoomTypes();
		$total = 0;
		/* @var $rt Ws_Do_Search_RoomTypeResult */
		foreach($types as $rt) {
			$total+= $rt->NumberOfRooms;
		}
		return $total;
	}

	public function getWsTotalAdults(){
		$types = $this->getWsRoomTypes();
		$total = 0;
		/* @var $rt Ws_Do_Search_RoomTypeResult */
		foreach($types as $rt) {
			$total+= $rt->NumberOfRooms * $rt->NumAdultsPerRoom;
		}
		return $total;
	}

	/**
	 * Method to calculate total mealplan(Dinner)
	 * New Function added 11-03-13 (D-m-y)
	 */
	public function calculateTotalMealplan()
	{
		$result = 0;

		$passengers = $this->getPassengers();

		if( count($passengers) )
		{
			foreach ($passengers as $passenger)
			{
				if( (int)$passenger->status < 3 )
				{
					if($passenger->mealplan)
					{
						$result++;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Method to calculate total breakfast
	 * New Function added 11-03-13 (D-m-y)
	 */
	public function calculateTotalBreakfast()
	{
		$result = 0;
		$passengers = $this->getPassengers();

		if( count($passengers) )
		{
			foreach ($passengers as $passenger)
			{
				if( (int)$passenger->status < 3 )
				{
					if($passenger->breakfast)
					{
						$result++;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Method to calculate total lunch
	 * New Function added 11-03-13 (D-m-y)
	 */
	public function calculateTotalLunch()
	{
		$result = 0;

		$passengers = $this->getPassengers();

		if( count($passengers) )
		{
			foreach ($passengers as $passenger)
			{
				if( (int)$passenger->status < 3 )
				{
					if($passenger->lunch)
					{
						$result++;
					}
				}
			}
		}


		return $result;
	}

	// Begin minhtran
	public static function generateBlockCodeRental ( $airlineCode, $Name, $date_start )
	{
		$db = JFactory::getDbo();
		$airline = SFactory::getAirline();
		if(isset($airline) && $airline->id)
		{
			
			#lchung !@important don't translate timezone this point because it's translated before
			#$date_start = SfsHelperDate::getDate($date_start,'dmy',$airline->time_zone);
            $date_start =  date('dmy', strtotime($date_start) );
			
			$blockcode = $airline->airport_code;
			$blockcode.= '_'.$date_start;
			$blockcode.= '_'.$airlineCode;

			$tempArray = explode(" ", $Name);
			$tmpStr = '';

			foreach ( $tempArray as $val ) {
				$val = trim($val);
				if( strlen($val) == 0 ) continue;
				if( count($tempArray) == 1 ){
					$tmpStr = JString::substr($val, 0,3);
					break;
				}
				$tmpStr .= JString::substr($val, 0,1);
				if(strlen($tmpStr)==2) break;
			}

			$blockcode .= '_'.$tmpStr;

			while (true) {
				$tmpStr = $blockcode.'_'.rand(1000, 9999);
				$tmpStr = JString::strtoupper($tmpStr);
				$db->setQuery( 'SELECT COUNT(*) FROM #__sfs_service_rental_car WHERE blockcode='.$db->quote($tmpStr) );

				$bCount = (int)$db->loadResult();
				if($bCount==0) {
					$blockcode = $tmpStr;
					break;
				}
			}
			return $blockcode;
		}

		JFactory::getApplication()->close();
	}
	// End minhtran 
}

