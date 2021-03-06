<?php
defined('_JEXEC') or die;

class SVoucher extends JObject
{
    public $id = null;

    private $_total_charged = null;
    public $_trace_passengers = null;
    public $_passengers = null;

    public function __construct( $identifier = null, $key = 'code' )
    {
        if ( ! empty($identifier) )
        {
            $this->load($identifier, $key);
        }
        else {
            $this->id = 0;
        }
    }

    public static function getInstance( $identifier, $key = 'code' )
    {
        static $instances;

        if (!isset ($instances)) {
            $instances = array ();
        }
        if( !empty($identifier) ){
            if (empty($instances[$identifier])) {
                $voucher = new SVoucher($identifier,$key);
                $instances[$identifier] = $voucher;
            }
            return $instances[$identifier];
        }
        return null;
    }


    public function getTotalCharged()
    {
        if( $this->_total_charged == null )
        {
            $this->_total_charged = $this->calculateEstimatedCharge();
        }
        return $this->_total_charged;
    }

    public function calculateEstimatedCharge()
    {
        $totalAmount = 0;
        if( (int)$this->vgroup == 0)
        {
            switch ((int)$this->room_type) {
                case 1:
                    $totalAmount = $this->s_rate;
                    break;
                case 2:
                    $totalAmount = $this->sd_rate;
                    break;
                case 3:
                    $totalAmount = $this->t_rate;
                    break;
                case 4:
                    $totalAmount = $this->q_rate;
                    break;
                default:
                    break;
            }
            $totalAmount = floatval($totalAmount);
        } else {
            if( (int)$this->room_type < 3)
            {
                $totalAmount = floatval($this->sd_rate) * (int)$this->seats;
            } else {
                $totalAmount = floatval($this->t_rate) * (int)$this->seats;
            }
        }

        if((int)$this->breakfast && (int)$this->v_breakfast ) {
            $totalAmount += (int)$this->seats * $this->breakfast;
        }
        if((int)$this->lunch && (int)$this->v_lunch) {
            $totalAmount += (int)$this->seats * $this->lunch;
        }
        if( (int)$this->mealplan && (int)$this->v_mealplan ) {
            $totalAmount += (int)$this->seats * $this->mealplan;
        }

        return $totalAmount;
    }

    public function load($identifier = null, $key = 'code')
    {
        $user	= JFactory::getUser();
        $db 	= JFactory::getDbo();
        $query	= $db->getQuery(true);

        $query->select('a.id, a.voucher_groups_id, a.code, a.booking_id, a.flight_id, a.room_type, a.vgroup, a.sdroom,a.troom,a.sroom,a.qroom,a.seats, a.id AS voucher_id, a.mealplan AS v_mealplan, a.lunch AS v_lunch, a.breakfast AS v_breakfast,a.comment,a.created_by,a.printed,a.printed_date');
        $query->from('#__sfs_voucher_codes AS a');

        $query->select('
			b.blockcode, b.airline_id, b.booked_by, b.hotel_id, b.room_id, b.sd_room, b.t_room, b.s_room,b.q_room,
			b.sd_room_issued, b.t_room_issued,b.s_room_issued, b.q_room_issued, b.claimed_rooms, b.sd_rate, b.t_rate, b.s_rate, b.q_rate, b.revenue_booked, b.transport,
			b.breakfast, b.lunch, b.mealplan, b.percent_release_policy, b.course_type, b.status, b.expired,
			b.payment_type,b.association_id,b.blockdate AS date
		');
        $query->innerJoin('#__sfs_reservations AS b ON b.id=a.booking_id');

        $query->select('d.flight_number AS return_flight_number, d.flight_date AS return_flight_date');
        $query->leftJoin('#__sfs_voucher_return_flights AS d ON d.voucher_id=a.id');

        $query->select('e.flight_code,e.comment AS flight_comment,e.delay_code as flight_delay_code');
        $query->leftJoin('#__sfs_flights_seats AS e ON e.id=a.flight_id');

        $query->select('f.taxi_voucher_id,tv.taxi_id,tv.is_return,tc.name AS taxi_name');
        $query->leftJoin('#__sfs_airline_taxi_voucher_map AS f ON f.voucher_id=a.id');
        $query->leftJoin('#__sfs_taxi_vouchers AS tv ON tv.id=f.taxi_voucher_id');
        $query->leftJoin('#__sfs_taxi_companies AS tc ON tc.id=tv.taxi_id');

        if($key=='id'){
            $query->where('a.id='.$db->quote($identifier));
        }

        if($key=='code'){
            $query->where('a.code='.$db->quote($identifier));
        }

        if(SFSAccess::isAirline($user)) {
            $airline = SFactory::getAirline();
            $query->where(' b.airline_id = '.$airline->id);
        }

        if( $blockId )
        {
            $query->where(' b.id = '. (int)$blockId );
        }

        $db->setQuery($query);

        if( $voucher = $db->loadObject() )
        {
            $this->setProperties($voucher);
            $this->voucher_code = $this->code;
        } else {
            $this->id = 0;
        }
    }

    public function getPassengers()
    {
        if( $this->_passengers == null && $this->id > 0 )
        {
            $db = JFactory::getDbo();
            $query = 'SELECT * FROM #__sfs_passengers WHERE voucher_id='.$this->id;
            $db->setQuery($query);

            $this->_passengers = $db->loadObjectList();
        }
        return $this->_passengers;
    }

    public function getTracePassengers()
    {
        if( $this->_trace_passengers == null && $this->id > 0 )
        {
            $db = JFactory::getDbo();
            $query = 'SELECT * FROM #__sfs_trace_passengers WHERE voucher_id='.$this->id;
            $db->setQuery($query);

            $this->_trace_passengers = $db->loadObjectList();
        }
        return $this->_trace_passengers;
    }


    public function getIndividualVouchers($countEmpty = false)

    {

        $db = JFactory::getDbo();
        if(  $this->_individual_vouchers == null && $this->id )
        {
            $query = 'SELECT * FROM #__sfs_voucher_groups WHERE id='.$this->id;
            $db->setQuery($query);

            $individualVouchers = (array)$db->loadObjectList('id');

            if($countEmpty) {
	            foreach($individualVouchers as $iv) {
	            	$iv->trace_passengers = array();
	            }
            }

            if(count($individualVouchers))
            {
                $passengers = $this->getTracePassengers();
                if(count($passengers))
                {
                    foreach ($passengers as $p)
                    {
                        if( (int)$p->individual_voucher > 0 && isset($individualVouchers[$p->individual_voucher]) )
                        {
                            $individualVouchers[$p->individual_voucher]->trace_passengers[] = $p->first_name.' '. $p->last_name;
                        }
                    }
                }
            }

            $this->_individual_vouchers = $individualVouchers;
        }

        return $this->_individual_vouchers;
    }
    public function getIndividualVoucher($individualVoucherId)
    {
        $data = $this->getIndividualVouchers();
        if (count($data) && (int)$individualVoucherId > 0)
        {
            foreach ($data as $v)
            {
                if( $v->voucher_id == $individualVoucherId )
                {
                    return $v;
                }
            }
        }
        return null;
    }

    // only for group voucher
    public function createIndividualVouchers()
    {
        if( $this->id && $this->vgroup )
        {
            $db = JFactory::getDbo();
            $query = 'SELECT COUNT(*) FROM #__sfs_voucher_groups WHERE id='.$this->id;
            $db->setQuery($query);
            $count = $db->loadResult();

            if( !$count )
            {
                //single room
                if( (int)$this->sroom > 0 )
                {
                    for($i=0;$i<$this->sroom;$i++)
                    {
                        $this->createCode(1);
                    }
                }
                //single/double room
                if( (int)$this->sdroom > 0 )
                {
                    for($i=0;$i<$this->sdroom;$i++)
                    {
                        $this->createCode(2);
                    }
                }
                //triple room
                if( (int)$this->troom > 0 )
                {
                    for($i=0;$i<$this->troom;$i++)
                    {
                        $this->createCode(3);
                    }
                }
                //quad room
                if( (int)$this->qroom > 0 )
                {
                    for($i=0;$i<$this->qroom;$i++)
                    {
                        $this->createCode(4);
                    }
                }
            }

            return true;
        }
        return false;
    }

    private function createCode($roomType=null)
    {
        $result = null;
        if( $roomType !== null && (int)$roomType > 0 && (int)$roomType < 5 )
        { 
            $db = JFactory::getDbo();
            while(true)
            {
                $tmpVoucher = SfsHelper::createRandomString(2);
                $tmpVoucher = JString::strtoupper($tmpVoucher.$roomType);

                $query  = 'SELECT COUNT(*) FROM #__sfs_voucher_codes WHERE code LIKE'.$db->quote('%'.$tmpVoucher.'%');
                $query .= ' AND booking_id='.(int)$this->booking_id;
                $db->setQuery($query);

                if( ! $db->loadResult() ) {

                    $query  = 'SELECT COUNT(*) FROM #__sfs_voucher_groups WHERE code LIKE'.$db->quote('%'.$tmpVoucher.'%');
                    $query .= ' AND voucher_group_id='.(int)$this->id;
                    $db->setQuery($query);

                    if( ! $db->loadResult() ){
                        $result = $tmpVoucher;
                        break;
                    }
                }
            }

            $row = new stdClass();
            $row->code = $result;
            $row->voucher_group_id = $this->id;
            $row->room_type = $roomType;

            $db->insertObject('#__sfs_voucher_groups', $row, 'voucher_id');

        }
        return $result;
    }

    // only for group voucher
    public function updateIndividualVoucherForPassengers()
    {
        $passengers 	= $this->getTracePassengers();


        $roomPassengers = array();

        if( count($passengers) )
        {
            foreach ($passengers as $p)
            {
                $roomPassengers[$p->room_type][$p->voucher_room_id][] = $p;
            }

            $db = JFactory::getDbo();
            $query = 'SELECT * FROM #__sfs_voucher_groups WHERE voucher_group_id='.$this->id;
            $db->setQuery($query);

            $individualVouchers = $db->loadObjectList();

            if(!count($individualVouchers))
            {
                return;
            }

            $updatePassengers = array();

            foreach ($individualVouchers as $inVoucher)
            {
                if( isset($roomPassengers[$inVoucher->room_type]) && count($roomPassengers[$inVoucher->room_type]) )
                {
                    foreach ( $roomPassengers[$inVoucher->room_type] as $index => $room )
                    {
                        if(count($room))
                        {
                            foreach ($room as $p)
                            {
                                $p->individual_voucher = $inVoucher->voucher_id;
                                $updatePassengers[] = $p;
                            }
                            unset($roomPassengers[$inVoucher->room_type][$index]);
                        }
                        break;
                    }
                }
            }

            if(count($updatePassengers))
            {
                $query = 'DELETE FROM #__sfs_trace_passengers WHERE voucher_id='.$this->id;
                $db->setQuery($query);
                $db->query();

                foreach ($updatePassengers as $p)
                {
                    $db->insertObject('#__sfs_trace_passengers', $p);
                }
            }

        }

    }
	
	//lchung
	public function getCardAirplusws()
    {
		$db = JFactory::getDbo();
        $darr = array();
		$query = 'SELECT
                        d.cvc as CVC, d.card_number as CardNumber,
						d.valid_thru as ValidThru,
						d.type_of_service as TypeOfService,
						d.valid_from as ValidFrom,
						d.passenger_name AS PassengerName,
						d.value AS Value
						FROM #__sfs_airplusws_creditcard_detail d
						INNER JOIN #__sfs_passengers_airplus a ON a.id = d.airplus_id
						WHERE a.voucher_id='. $this->id .' AND d.type_of_service="meal"';
		$db->setQuery($query);

		$mealplan = (array)$db->loadObjectList();
        $darr['info_of_card_ap_meal'] = $mealplan;

        $query = 'SELECT
                        d.cvc as CVC, d.card_number as CardNumber,
						d.valid_thru as ValidThru,
						d.type_of_service as TypeOfService,
						d.valid_from as ValidFrom,
						d.passenger_name AS PassengerName,
						d.value AS Value
						FROM #__sfs_airplusws_creditcard_detail d
						INNER JOIN #__sfs_passengers_airplus a ON a.id = d.airplus_id
						WHERE a.voucher_id='. $this->id .' AND d.type_of_service="taxi"';
        $db->setQuery($query);

        $taxi = (array)$db->loadObjectList();
        $darr['info_of_card_ap_taxi'] = $taxi;

        return $darr;
	}
	//End lchung
}

