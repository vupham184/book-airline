<?php
defined('_JEXEC') or die;

class SfsModelVoucher extends JModelLegacy
{
	private $_voucher = null;
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		//$voucherId = JRequest::getVar('voucher_id');//
		$voucherId =  JRequest::getInt('voucher_id');
		$this->setState('voucher.voucher_id', $voucherId);
		$this->setState('voucher.voucher_ids', JRequest::getVar('voucher_id'));
		
		$voucher_groups_id = JRequest::getVar('voucher_groups_id');//JRequest::getInt('voucher_id');
		$this->setState('voucher_groups_id', $voucher_groups_id);
		
		$individualVoucherId = JRequest::getInt('individual_voucher_id');
		$this->setState('voucher.individual_voucher_id', $individualVoucherId);
										
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	public function getVoucher()
	{
		if( $this->_voucher == null )
		{
			$voucherId = $this->getState('voucher.voucher_id');
			$reservation_id = JRequest::getVar('reservation_id');
			if($voucherId == 1)
			{
                if($reservation_id == -1)
                {
                    $session = JFactory::getSession();
                    $reservation = unserialize($session->get("reservation_temp"));
                    $reservation_id = $reservation->id;
                }
				if($reservation_id)
				{
					$db = $this->getDbo();
					$query = 'SELECT id FROM #__sfs_voucher_codes WHERE booking_id = '.(int)$reservation_id;
					$db->setQuery($query);
					$voucherId = $db->loadResult();
				}
			}
			
			if( $voucherId > 1 )
			{
				$voucher = SVoucher::getInstance($voucherId,'id');	
				if( (int) $voucher->id > 0 )
				{
					$this->_voucher = $voucher;
				}			
			}			
		}
		
		return $this->_voucher;
	}
	
	//lchung
	public function getVouchers()
	{
		$voucherIds = $this->getState('voucher.voucher_ids');
		$vouchers = array();
		if( $voucherIds == '' ) { //Truong hop book 1 And use add Passenger truc tiep
			$reservation_id = JRequest::getInt('reservation_id');
			if($reservation_id == -1)
			{
				$session = JFactory::getSession();
				$reservation = unserialize($session->get("reservation_temp"));
				$reservation_id = $reservation->id;
			}
			if($reservation_id)
			{
				$db = $this->getDbo();
				$query = 'SELECT id FROM #__sfs_voucher_codes WHERE booking_id = '.(int)$reservation_id;
				$db->setQuery($query);
				$voucherIds = $db->loadResult();
			}
		}
		if( $voucherIds != '' )
		{
			$voucherIdA = explode(",", $voucherIds);
			foreach ( $voucherIdA as $voucherId ) {
			$voucher = SVoucher::getInstance($voucherId,'id');	
			if( (int) $voucher->id > 0 )
			{
				$vouchers[] = $voucher;
			}
			}
		}	
		return $vouchers;
	}
	
	public function getPassengers()
    {
		$db = $this->getDbo();
        $voucherIds = $this->getState('voucher.voucher_ids');
		if( $voucherIds != '' )
		{
			$voucherIdA = explode(",", $voucherIds);
			foreach ( $voucherIdA as $voucherId ) {
				$query = 'SELECT * FROM #__sfs_passengers WHERE voucher_id='.$voucherId;
				$db->setQuery($query);
				$passengers[] = $db->loadObjectList();
			}
		}
        return $passengers;
    }

    public function getTracePassengers()
    {
        $voucherIds = $this->getState('voucher.voucher_ids');
		$db = $this->getDbo();
		
		if( $voucherIds == '' ) {
			$voucherIds = $this->getVoucher()->id;
		}
		
		if( $voucherIds != '' )
		{
			$voucherIdA = explode(",", $voucherIds);
			foreach ( $voucherIdA as $voucherId ) {
				$query = 'SELECT * FROM #__sfs_trace_passengers WHERE voucher_id='.$voucherId;
				$db->setQuery($query);
				$trace_passengers[] = $db->loadObjectList();
			}
        }
        return $trace_passengers;
    }
	
	public function getCardAirplusws()
    {
		$voucherIds = $this->getState('voucher.voucher_ids');
		$db = $this->getDbo();
		$data = array();
		if( $voucherIds == '' ) {
			$reservation_id = JRequest::getInt('reservation_id');
			if($reservation_id == -1)
			{
				$session = JFactory::getSession();
				$reservation = unserialize($session->get("reservation_temp"));
				$reservation_id = $reservation->id;
			}
			if($reservation_id)
			{
				$db = $this->getDbo();
				$query = 'SELECT id FROM #__sfs_voucher_codes WHERE booking_id = '.(int)$reservation_id;
				$db->setQuery($query);
				$voucherIds = $db->loadResult();
			}
		}
		if( $voucherIds != '' )
		{
			$voucherIdA = explode(",", $voucherIds);
			foreach ( $voucherIdA as $voucherId ) {
				
				$query = 'SELECT
								d.cvc as CVC, d.card_number as CardNumber,
								d.valid_thru as ValidThru,
								d.type_of_service as TypeOfService,
								d.valid_from as ValidFrom,
								d.passenger_name AS PassengerName,
								d.value AS Value
								FROM #__sfs_airplusws_creditcard_detail d
								INNER JOIN #__sfs_passengers_airplus a ON a.id = d.airplus_id
								WHERE a.voucher_id='. $voucherId .' AND d.type_of_service="meal"';
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
								WHERE a.voucher_id='. $voucherId .' AND d.type_of_service="taxi"';
				$db->setQuery($query);
				$taxi = (array)$db->loadObjectList();
				$darr['info_of_card_ap_taxi'] = $taxi;
				$data[] = $darr;
			}
		}
		return $data;
	}
	//End lchung
	
	public function getIndividualVoucher()
	{
		$trace_passengers = array();
		$db = $this->getDbo();
		$getVouchers = $this->getVouchers();
		if(count( $getVouchers ) > 0 )
		{
			foreach ( $getVouchers as $v ) {
				
				$query = 'SELECT a.* FROM #__sfs_trace_passengers as a WHERE voucher_id='.$v->voucher_id;
				$db->setQuery($query);
				$d = $db->loadObjectList();
				$tracepassengers = array();
				foreach ( $d as $vs ) {
					$tracepassengers[] = $vs->first_name . ' '. $vs->last_name;
				}
				$v->trace_passengers = $tracepassengers;
			}
        }
        return $getVouchers;
	}
	
	public function getVoucherHotel()
	{
		$hotel = null;
		$voucher = $this->getVoucher();
		if( $voucher && $voucher->hotel_id )
		{
			if($voucher->association_id==0){
				$hotel = SHotel::getInstance($voucher->hotel_id );	
			} else {
				$association = SFactory::getAssociation($voucher->association_id);
				if($association)
				{
					$hotel =  new SHotel($voucher->hotel_id,$association->db);
				}
			}						
		}

        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $db = $this->getDbo();
        $query   = $db->getQuery(true);
        $query->select('distance, distance_unit');
        $query->from('#__sfs_hotel_airports');
        $query->where('hotel_id='.(int)$hotel->id." AND airport_id=".(int)$airline_current->id);
        $db->setQuery($query);

        $result = $db->loadObject();
        $hotel->distance = $result->distance;
        $hotel->distance_unit = $result->distance_unit;

		return $hotel;
	}
	
	//get search data
	public function getDataVoucher()
	{
		
		$db = $this->getDbo();
		$query = $this->getListQuerys($db);
		$db->setQuery($query);
		$row = $db->loadObject();
		if ($error = $db->getErrorMsg()) {
			throw new Exception($error);
		}
		return $row;
	}

	protected function getListQuerys( $db)
	{
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
        $airport_current_code = $airline_current->code;

		$query   = $db->getQuery(true);
		
		///$voucherId = $this->getState('voucher.voucher_id');
		$voucher_groups_id = $this->getState('voucher_groups_id');
		
		//join to rooms
		$query->select('b.id as hotel_id,b.name as hotel_name, b.ws_id, b.star,b.address, b.web_address,b.billing_id,b.geo_location_latitude,b.geo_location_longitude, b.telephone');
		$query->from('#__sfs_hotel AS b ');
		$query->select('e.distance, e.distance_unit');
		$query->innerJoin('#__sfs_hotel_airports AS e ON e.hotel_id=b.id AND e.airport_id='.$airport_current_id);
		$query->select('ia.km_rate AS km_rate_ws, ia.starting_tariff AS starting_tariff_ws');
		$query->leftJoin('#__sfs_iatacodes AS ia ON ia.id = e.airport_id');
		
		$query->innerJoin('#__sfs_reservations AS r ON r.hotel_id=b.id ');
		
		if ( $voucher_groups_id <= 0) { //dung cho truong hop show card when booking
			$reservation_id = JRequest::getInt('reservation_id');
			$query->innerJoin('#__sfs_voucher_codes AS v ON v.booking_id=r.id');
			$query->where('r.id=' . $reservation_id );
		}
		else {
			///$query->innerJoin('#__sfs_voucher_codes AS v ON v.booking_id=r.id AND v.id='.$voucherId);
			$query->innerJoin('#__sfs_voucher_codes AS v ON v.booking_id=r.id AND v.voucher_groups_id='.$voucher_groups_id);
		}
	
		//print_r($_POST);die($_POST);
		///echo $query;die();
		return $query;
	}
	
	public function getCarddetail(){
		echo $reservation_id = JRequest::getInt('reservation_id');
	}
	
	//lchung
	public function getVoucherGroups()
	{		
		$voucher_groups_id = (int)JRequest::getVar('voucher_groups_id', 0);
		if( !$voucher_groups_id ) {
			$reservation_id = JRequest::getInt('reservation_id');
			if($reservation_id == -1)
			{
				$session = JFactory::getSession();
				$reservation = unserialize($session->get("reservation_temp"));
				$reservation_id = $reservation->id;
			}
			if($reservation_id)
			{
				$db = $this->getDbo();
				$query = 'SELECT voucher_groups_id FROM #__sfs_voucher_codes WHERE booking_id = '.(int)$reservation_id;
				$db->setQuery($query);
				$voucher_groups_id = $db->loadResult();
			}
		}
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_voucher_groups AS a');
		$query->where('a.id='. $voucher_groups_id);
		$db->setQuery($query);
		return $db->loadObject();
	}
	//End lchung
	

	public function getDataPassengerService(){		
		require_once JPATH_SITE.'/components/com_sfs/models/passengersimport.php';

		$id = JRequest::getVar('id', 0);		
		$data = new SfsModelPassengersimport();
		$result = $data->getPassengers($id);
		
		return $result;
	}
	
	public function getDataForRental()
	{		
		$data = $this->getDataPassengerService();

		foreach ($data[0]->services as $key => $value) {
			if(isset($value->service_id) && (int)$value->service_id == 6){
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query = 'SELECT b.logo, a.blockdate, a.pick_up, a.drop_off, a.group_id FROM #__sfs_service_rental_car AS a';
				$query .= ' LEFT JOIN #__sfs_company_rental_car AS b ON b.id = a.rental_id';		
				$query .= ' WHERE a.passenger_id = ' . $data[0]->passenger_id;
				$db->setQuery($query);
				$result = $db->loadObject();

				$query = 'SELECT b.code, a.id FROM #__sfs_rental_car_location AS a';
				$query .= ' LEFT JOIN #__sfs_iatacodes AS b ON b.id = a.airportcode';
				$query .= ' WHERE a.id IN ('.$result->pick_up.','.$result->drop_off.')';
				$db->setQuery($query);
				$result->location = $db->loadObjectList();

				return $result;
			}			
		}

	}	

	//begin minhtran
	public function getListVoucherByResid(){
		$reservation_id = JRequest::getInt('reservation_id');
		if(JRequest::getInt('reservation_id')){
			$db = $this->getDbo();
			$query = 'SELECT * FROM #__sfs_voucher_codes WHERE booking_id = '.(int)$reservation_id;
			$db->setQuery($query);
			$result = $db->loadObjectList();	
			$list = array();
			if($result){
				foreach ($result as $key => $value) {

					$list[$value->id] =SVoucher::getInstance($value->id,'id');	
				}
				return $list;

			}
		}
		
	}
	//begin minhtran
}