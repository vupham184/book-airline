<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
///require_once JPATH_SITE.'/components/com_sfs/libraries/reservation.php';
///require_once JPATH_SITE.'/components/com_sfs/libraries/core.php';
require_once JPATH_SITE.'/ws/lib/Ws/Do/Book/Response.php';
require_once JPATH_SITE.'/ws/lib/Ws/Do/PreBook/Response.php';
class SfsModelMakereport extends JModelList
{

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
	

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$filter_block_status = $this->getUserStateFromRequest($this->context.'.filter.blockstatus', 'filter_block_status');
		$this->setState('filter.blockstatus', $filter_block_status);
		
		$filter_airline_id = JRequest::getInt('filter_airline_id',0);
		$this->setState('filter.airline_id', $filter_airline_id);
		
		$filter_hotel_id = JRequest::getInt('filter_hotel_id',0);
		$this->setState('filter.hotel_id', $filter_hotel_id);		
		
		//lchung add
		$filter_ws_room = JRequest::getString('filter_ws_room',"");
		$this->setState('filter.ws_room', $filter_ws_room);
		
		$filter_fromdate = JRequest::getString('date_start',"");
		$this->setState('filter.fromdate', $filter_fromdate);
		$filter_todate = JRequest::getString('date_end',"");
		$this->setState('filter.todate', $filter_todate);
		//End lchung
		
		// List state information.
		parent::populateState('a.booked_date', 'desc');
	}

	protected function getListQuery()
	{
		
		$filter_search = JRequest::getVar('filter_search');
		$filter_fromdate  = JRequest::getVar('date_start');
		$filter_todate  = JRequest::getVar('date_end');
		$filter_ws_room = JRequest::getVar('ws_room');
		$filter_airline_id = (int)JRequest::getVar('airline_id');
		$filter_hotel_id = (int)JRequest::getVar('hotel_id');
		$filter_block_status = JRequest::getVar('block_status');
		
		$rtype = $this->getState('filter.rtype');
		$gid = $this->getState('filter.gid');
		
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*, u.name AS booked_name, h.name AS hotel_name, h.id AS hotel_id,
		vc.vgroup as vc_vgroup, vc.room_type as vc_room_type, vc.seats as vc_seats, vc.breakfast as vc_breakfast,
		vc.lunch as vc_lunch, vc.mealplan as vc_mealplan');
						
		$query->from('#__sfs_reservations AS a');
		
		$query->select('b.transport_included,b.date AS room_date,b.sd_room_total,b.t_room_total');
		$query->leftJoin('#__sfs_room_inventory AS b ON b.id=a.room_id');
		$query->leftJoin('#__sfs_hotel AS h ON h.id=b.hotel_id');
				
		$query->select('ad.company_name,ic.name AS airline_name, ic.code AS airline_code, ad.city');
		$query->leftJoin('#__sfs_airline_details AS ad ON ad.id=a.airline_id');
		$query->leftJoin('#__sfs_iatacodes AS ic ON ic.id=ad.iatacode_id');		

		$query->select('d.name AS country_name');
		$query->leftJoin('#__sfs_country AS d ON d.id=ad.country_id');
		
		$query->leftJoin('#__sfs_voucher_codes AS vc ON a.id=vc.booking_id');
		
		$query->select('fs.flight_code, fs.delay_code');
		$query->leftJoin('#__sfs_flights_seats AS fs ON fs.id=vc.flight_id');
		
		$query->leftJoin('#__users AS u ON u.id=a.booked_by');
			
		if ( $filter_search != "" ) {			
			$filter_search = $db->Quote('%'.$db->escape($filter_search, true).'%');
			$query->where('a.blockcode LIKE '.$filter_search);
		}
		
		if ( $filter_block_status != "" ) {						
			$query->where('a.status = '.$db->quote($filter_block_status));
		}
		
		if ( $filter_airline_id > 0 ) {						
			$query->where('a.airline_id = '.$filter_airline_id);
		}
		
		if ( $filter_hotel_id > 0 ) {						
			$query->where('a.hotel_id = '.$filter_hotel_id);
		}
		
		//lchung add
		if ( $filter_ws_room != "" ) {
			if ( $filter_ws_room == "Partner" ) {
				$query->where('(a.ws_room IS NULL OR a.ws_room = 0)');
			}
			elseif ( $filter_ws_room == 'WS' ) {
				$query->where('a.ws_room >=1 ');
			}
		}
		
		if ( $filter_fromdate != "" ) {
			$query->where("Date(a.booked_date) >= '$filter_fromdate'");
		}
		if ( $filter_todate != "" ) {
			$query->where("Date(a.booked_date) <= '$filter_todate'");
		}
		//End lchung
				
		$orderCol	= $this->state->get('list.ordering', 'a.booked_date');
		$orderDirn	= $this->state->get('list.direction', 'DESC');
		$orderDirn  = 'DESC';
		$query->group('a.id');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo (string)$query;die;
						
		return $query;
	}
	
	public function getList(){
		$db = $this->getDbo();
		$db->setQuery($this->getListQuery() );		
		$result = $db->loadObjectList();
		$data_r = array();
		$data = array();
		$t = 0;
		$re = array();
		foreach ( $result as $vk => $v ) {			
			
			$vouchers = $this->loadVouchers( $v->id );
			$trace_passengers = $this->loadTracePassengers( $v->id );
			$PickedRooms = $this->getPickedRooms( $vouchers, $trace_passengers);
			$total_room_charge = $this->getTotalRoomCharge($PickedRooms, $v->s_rate, $v->sd_rate, $v->t_rate, $v->q_rate);
			
			$picked_mealplans  = $this->calculateTotalMealplan( $trace_passengers );
			$picked_breakfasts = $this->calculateTotalBreakfast( $trace_passengers );
			$picked_lunchs 	 = $this->calculateTotalLunch( $trace_passengers );
			
			$mealplan = $v->mealplan;
			$lunch = $v->lunch;
			$breakfast = $v->breakfast;
			
			$total_nett_charges = $v->ws_sd_rate + $v->ws_t_rate + $v->ws_s_rate + $v->ws_q_rate;
			
			$getTotalMealplanCharge = $picked_mealplans * $mealplan;
			
			$total_mealplan_charge = $getTotalMealplanCharge + ($picked_breakfasts * $breakfast) + ($picked_lunchs * $lunch);
			
			$total_invoice_charge = $total_room_charge + $total_mealplan_charge;			
			if ( $total_invoice_charge == 0 || $total_nett_charges == 0 ) {
				
				if($v->ws_booking != '' ) {
					$book = Ws_Do_Book_Response::fromString($v->ws_booking);
					$total_mealplan_charge = $book->TotalPrice;
					$total_nett_charges = $book->OriginalTotalPrice;
				}
				elseif( $v->ws_prebooking != '' ) {
					$preBooking = Ws_Do_PreBook_Response::fromString($v->ws_prebooking);
					$total_mealplan_charge = $preBooking->TotalPrice;
					$total_nett_charges = $preBooking->OriginalTotalPrice;
					
				}
				elseif( $v->ws_room_type != '' ){
					$rooms = unserialize($v->ws_room_type);
					//print_r( $rooms );
				}
				else{
					//echo $v->id .'===='. $v->ws_prebooking . '<br>';
				}
			}
			$data['k' . $v->id]['total_room_charge'] = $total_invoice_charge;			
			$data['k' . $v->id]['total_nett_charges'] = $total_nett_charges;
			
		}//die;
		$data_r['result'] = $result;
		$data_r['reservation'] = $data;
		//print_r( $data );
		return $data_r;
	}
	
	//load all vouchers that made by the airlines
	public function loadVouchers( $id )
	{
		if( (int) $id > 0 )
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

			$query->where('a.booking_id='.(int) $id);

			$db->setQuery($query);
			return $db->loadObjectList();
		}
		return true;
	}
	
	public function getPickedRooms( $_vouchers, $trace_passengers )
	{
		$_picked_rooms = array(1=>0,2=>0,3=>0,4=>0);
		foreach ($_vouchers as $v) {


			//calculate the passengers loaded by the hotel
			$number_passenger = 0;
			if(count($trace_passengers)) {
				foreach ($trace_passengers as $p) {
					if($p->id==$v->id) {
						$number_passenger++;
					}
				}
			}

			// calculate the picked rooms with group voucher
			// only calculate if the passengers are existed
			if( $number_passenger && (int)$v->status < 3) {
				if((int)$v->sroom ){
					$_picked_rooms[1] = $_picked_rooms[1] + (int)$v->sroom;
				}
				if((int)$v->sdroom ){
					$_picked_rooms[2] = $_picked_rooms[2] + (int)$v->sdroom;
				}
				if((int)$v->troom ){
					$_picked_rooms[3] = $_picked_rooms[3] + (int)$v->troom;
				}
				if((int)$v->qroom ){
					$_picked_rooms[4] = $_picked_rooms[4] + (int)$v->qroom;
				}
			}

		}


		return $_picked_rooms;
	}
	
	// load all detailed arrivals in the hotels
    private function loadTracePassengers( $id )
    {
		$db = $this->getDbo();

		$query = $db->getQuery(true);

		$query->select('p.first_name,p.last_name,b.*,f.flight_code');
		$query->from('#__sfs_trace_passengers AS p');
		$query->innerJoin('#__sfs_voucher_codes AS b ON b.id=p.voucher_id');
		$query->innerJoin('#__sfs_flights_seats AS f ON f.id=b.flight_id');
		
		$query->where('b.booking_id='.(int)$id);
		$query->where('b.status < 3');


		$db->setQuery($query);

		return $db->loadObjectList();
        
    }
	
	/**
	 * Method to calculate Estimated total nett room charge
	 *
	 */
	public function getTotalRoomCharge( $pickedRooms, $s_rate, $sd_rate, $t_rate, $q_rate )
	{
		$price = $pickedRooms[1] * $s_rate + $pickedRooms[2] * $sd_rate + $pickedRooms[3] * $t_rate + $pickedRooms[4] * $q_rate;

		return $price;
	}
	
	/**
	 * Method to calculate total mealplan(Dinner)
	 * New Function added 11-03-13 (D-m-y)
	 */
	public function calculateTotalMealplan( $passengers )
	{
		$result = 0;

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
	public function calculateTotalBreakfast( $passengers )
	{
		$result = 0;
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
	public function calculateTotalLunch( $passengers )
	{
		$result = 0;

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
	
	public function getItems()
	{
		$items	= parent::getItems();	
		return $items;
	}	
}