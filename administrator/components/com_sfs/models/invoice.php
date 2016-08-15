<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.model');
require_once JPATH_SITE.'/components/com_sfs/libraries/reservation.php';
require_once JPATH_SITE.'/components/com_sfs/libraries/core.php';
require_once JPATH_ROOT . '/components/com_sfs/libraries/access.php';
require_once JPATH_ROOT . '/components/com_sfs/libraries/hotel.php';

class SfsModelInvoice extends JModel
{

	protected function populateState()
	{
		$hotelId = JRequest::getInt('hotel_id');
		$this->setState('hotelId',$hotelId);
		
		$date_start  = JRequest::getVar('date_start');
		$this->setState('invoice.date_start',$date_start);
		
		$date_end  = JRequest::getVar('date_end');
		$this->setState('invoice.date_end',$date_end);
	}
	
	public function getHotel()
	{
		$hotelId = $this->getState('hotelId');
		if($hotelId)
		{
			$hotel = SHotel::getInstance($hotelId);
			return $hotel;
		}
		return null;
	}
	
	public function getMerchantFee( $hotelId = null )
	{
		if( empty($hotelId) ){
			$hotel = $this->getHotel();
			$hotelId = $hotel->id;
		}
		
		if( $hotelId )
		{
			$db = $this->getDbo();
			$query = 'SELECT * FROM #__sfs_hotel_merchant_fee WHERE hotel_id='.$hotelId;
			$db->setQuery($query);
			
			$result = $db->loadObject();
			
			return $result;
		}
		return null;
	}
	
	public function getReservations()
	{
		$hotelId = $this->getState('hotelId');
		$date_start = $this->getState('invoice.date_start');
		$date_end   = $this->getState('invoice.date_end');
		
		if( $hotelId && $date_start && $date_end  ) {
			
			$hotel	 		= $this->getHotel();
			$merchantFee 	= $this->getMerchantFee();	

			$tax = $hotel->getTaxes();
			$mealplanTax    = $hotel->getMealPlan();
			
						
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			
			$query->select('a.hotel_id,a.id,a.blockcode,a.status,a.sd_room,t_room,a.claimed_rooms,a.sd_rate,a.t_rate,a.revenue_booked');			
			$query->from('#__sfs_reservations AS a');
			
			$query->innerJoin('#__sfs_room_inventory AS inv ON inv.id=a.room_id');
									
			$query->select('b.name AS hotel_name');
			$query->innerJoin('#__sfs_hotel AS b ON b.id=a.hotel_id');
			
			$query->select('d.symbol AS currency');
			$query->innerJoin('#__sfs_hotel_taxes AS c ON c.hotel_id=a.hotel_id');
			$query->innerJoin('#__sfs_currency AS d ON d.id=c.currency_id');
			
			$query->select('rn.notes AS block_note');
			$query->leftJoin('#__sfs_reservation_notes AS rn ON rn.reservation_id=a.id');
			
			$query->where('a.hotel_id='.$hotelId);
			//$query->where('a.status='.$db->quote('A'));
			
			
			$query->where( 'inv.date  >= '.$db->quote($date_start) );
			
			$query->where( 'inv.date  <= '.$db->quote($date_end) );
			
			
			$query->order('a.booked_date DESC');
			
			$db->setQuery($query);
			
			$rows = $db->loadObjectList();
			
						
			$results =  array();
			
			if( count($rows) ){
			
				foreach ($rows as $row){
					
					$resv =  new SReservation($row->id);
					
					$picked_rooms  = $resv->getPickedRooms();
					$resv->totalRooms = $picked_rooms[1]+$picked_rooms[2]+$picked_rooms[3];
					
					$resv->block_note = $row->block_note;
									
					// Calculate Net Price based
					if( $tax->percent_total_taxes )
					{
						// Based on room
						$a = ( floatval($row->sd_rate) * floatval($tax->percent_total_taxes) ) / 100;
						$b = floatval($row->sd_rate) - $a;
						$resv->room_net_price = $b;
						
						// Based on breakfast
						$netPrice = ( floatval($resv->breakfast) * floatval($mealplanTax->bf_tax) ) / 100;
						$netPrice = floatval($resv->breakfast) - $netPrice;
						$resv->breakfast_net_price = $netPrice;
						
						// Based on lunch
						$netPrice = ( floatval($resv->lunch) * floatval($mealplanTax->lunch_tax) ) / 100;
						$netPrice = floatval($resv->lunch) - $netPrice;
						$resv->lunch_net_price = $netPrice;
						
						// Based on dinner
						$netPrice = ( floatval($resv->mealplan) * floatval($mealplanTax->tax) ) / 100;
						$netPrice = floatval($resv->mealplan) - $netPrice;
						$resv->dinner_net_price = $netPrice;
					} else {
						$resv->room_net_price = $row->sd_rate;
						$resv->breakfast_net_price = $resv->breakfast;
						$resv->lunch_net_price = $resv->lunch;
						$resv->dinner_net_price = $resv->mealplan;
					}			
	
					// Calculate Total Merchant Fee Room
					if( $merchantFee->merchant_fee )
					{
						if( (int)$merchantFee->merchant_fee_type == 1 ) {
							$totalMerchatFee = ( $resv->room_net_price * $resv->totalRooms * $merchantFee->merchant_fee ) / 100;
							$resv->totalMerchatFeeRoom = $totalMerchatFee;
						} else {
							$resv->totalMerchatFeeRoom = $resv->totalRooms * $merchantFee->merchant_fee;
						}
					}
					
					// Calculate Total Merchant Fee Breakfast
					if( $merchantFee->breakfast_merchant_fee )
					{
						$totalBreakfast  = $resv->calculateTotalBreakfast();					
						$totalMerchatFee = ( $resv->breakfast_net_price * $totalBreakfast * $merchantFee->breakfast_merchant_fee ) / 100;
						$resv->totalMerchatFeeBreakfast = $totalMerchatFee;					
					}
					
					// Calculate Total Merchant Fee Lunch
					if( $merchantFee->lunch_merchant_fee )
					{
						$totalLunch  = $resv->calculateTotalLunch();					
						$totalMerchatFee = ( $resv->lunch_net_price * $totalLunch * $merchantFee->lunch_merchant_fee ) / 100;
						$resv->totalMerchatFeeLunch = $totalMerchatFee;					
					}
					
					// Calculate Total Merchant Fee Dinner
					if( $merchantFee->dinner_merchant_fee )
					{
						$totalDinner  = $resv->calculateTotalMealplan();					
						$totalMerchatFee = ( $resv->dinner_net_price * $totalDinner * $merchantFee->dinner_merchant_fee ) / 100;
						$resv->totalMerchatFeeDinner = $totalMerchatFee;	
					}
					
					$resv->grandTotal = floatval($resv->totalMerchatFeeRoom ) + floatval($resv->totalMerchatFeeBreakfast ) + floatval($resv->totalMerchatFeeLunch ) + floatval($resv->totalMerchatFeeDinner );
					
									
					$results[$row->id] = $resv;				
				}
			
			}
			
			return $results;
		}
		return null;
	}
	
}

