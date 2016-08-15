<?php
// No direct access
defined('_JEXEC') or die;

class SfsModelTaxi extends JModelLegacy
{
	protected $_taxi_details 	= null;
	protected $_taxi_companies  = null;
	protected $_taxi_voucher = null;
	
	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		$transportation = JRequest::getInt('transportation');
		$this->setState('taxivoucher.transportation', $transportation);
				
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}
	
	public function getTaxiDetails()
	{
		$airline = SFactory::getAirline();
		
		if( $this->_taxi_details == null && SFSAccess::isAirline() ){
			
			$db		 = $this->getDbo();
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
			
			
			$query->where('a.airline_id = ' . (int) $airline->id );
			
			$db->setQuery($query);
			
			$this->_taxi_details = $db->loadObject();
		}
		
		return $this->_taxi_details;		
	}
	
	public function getTaxiCompanies()
	{
		$airline = SFactory::getAirline();
		
		if( $this->_taxi_companies == null && SFSAccess::isAirline() ){
			
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('a.*,b.*');
			$query->from('#__sfs_airline_taxicompany_map AS a');
			$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
			
			$query->where('b.published = 1');
			$query->where('a.airline_id = ' . (int) $airline->id );
			
			$db->setQuery($query);
			
			$this->_taxi_companies = $db->loadObjectList();
		}
		
		return $this->_taxi_companies;		
	}
	
	public function getTaxi($id = null)
	{
		$pk = ! empty($id) ? $id : JRequest::getInt('taxi_id');
		
		$items = $this->getTaxiCompanies();
		
		$result = null;
		foreach ($items as $item)
		{
			if( (int)$item->taxi_id == (int)$pk )
			{
				$result = & $item;
				break;
			}
		}
		
		return $result;
	}
	
	public function saveDetails()
	{		
		$airline = SFactory::getAirline();
		$db		 = $this->getDbo();
		$table 	 = JTable::getInstance('Taxidetail' , 'SfsTable');
		
		$taxidetails = JRequest::getVar('taxidetails' , array() , 'post' ,  'array');
		
		$taxidetails['telephone'] = SfsHelper::getPhoneString($taxidetails['phone_code'],$taxidetails['phone_number']);
		
		if( !$table->bind($taxidetails) )
		{
			$this->setError( $table->getError() );
			return false;
		}
		
		if( !$table->store() )
		{
			$this->setError( $table->getError() );
			return false;
		}
		
		$item = $this->getTaxiDetails();
		
		if( empty($item) )
		{
			$query = 'INSERT INTO #__sfs_airline_taxidetail_map(airline_id,taxi_detail_id) VALUES('.(int)$airline->id.','.$table->id.')';
			$db->setQuery($query);
			if( !$db->query() ) 
			{
				$this->setError( $db->getErrorMsg() );
				return false;
			}
		}
		
		// Add new taxi company
		$companies = JRequest::getVar('companies' , array() , 'post' ,  'array');		
		if(count($companies))
		{
			foreach ($companies as $key => $company)
			{								
				if( isset($company['name']) && strlen(trim($company['name'])) )
				{
					$row = new stdClass();
					$row->name 		= trim($company['name']);
					$row->email 	= trim($company['email']);
					$row->telephone = trim($company['telephone']);
					$row->fax 		= trim($company['fax']);
					$row->published = 1;
					
					if( $db->insertObject( '#__sfs_taxi_companies' , $row) ) 
					{
						$insertId = $db->insertid();
						
						$rowMap = new stdClass();
						$rowMap->airline_id = (int)$airline->id;
						$rowMap->taxi_id 	= (int)$insertId;
						if( ! $db->insertObject( '#__sfs_airline_taxicompany_map' , $rowMap) )
						{
							$this->setError( $db->getErrorMsg() );
							return false;
						}
					} 
					else
					{
						$this->setError( $db->getErrorMsg() );
						return false;
					}
					
				}
				
			}
		}
		
		return true;
	}
	
	public function removeTaxi()
	{
		$airline = SFactory::getAirline();
		$db		 = $this->getDbo();
		
		$taxi_id = JRequest::getInt('taxi_id');
		
		if( (int) $taxi_id > 0 )
		{
			$allowRemove = false;
			$companies = $this->getTaxiCompanies();
			foreach ($companies as $company)
			{
				if( (int)$company->taxi_id == (int) $taxi_id )
				{
					$allowRemove = true;
					break;
				}
			}
			
			if( $allowRemove )
			{
				$query = 'UPDATE #__sfs_taxi_companies SET published = -2 WHERE id='.$taxi_id;
				$db->setQuery($query);
			    $db->query();								
			}
			
		}
		
		return true;
		
	}
	
	public function saveRate()
	{
		$airline = SFactory::getAirline();
		$db		 = $this->getDbo();
		
		$taxi_id = JRequest::getInt('taxi_id');
		$taxi 	 = $this->getTaxi($taxi_id);
		
		if( $taxi )
		{
			// update period rate
			$updateObject = new stdClass();
			$updateObject->id = $taxi_id;
			
			$fare_from_h 	= JRequest::getVar('fare_from_h');
			$fare_from_m 	= JRequest::getVar('fare_from_m');
			$fare_until_h 	= JRequest::getVar('fare_until_h');
			$fare_until_m 	= JRequest::getVar('fare_until_m');
			
			$updateObject->fare_from  = $fare_from_h.':'.$fare_from_m;
			$updateObject->fare_until = $fare_until_h.':'.$fare_until_m;
			
		  	$wdays = JRequest::getVar('wdays', array(), 'post', 'array');
		  	
	    	if( is_array($wdays) && count($wdays) )
	    	{
	    		JArrayHelper::toInteger($wdays);
	    		$updateObject->available_days = implode(',', $wdays);
	    	} else {
	    		$updateObject->available_days = '';
	    	}
			
			$db->updateObject('#__sfs_taxi_companies', $updateObject, 'id');
			
			// Update hotel rates			
			$ringRates = JRequest::getVar('ringrates', array(), 'post', 'array');
			
			//empty old data
			$query = 'DELETE FROM #__sfs_taxi_hotel_rates WHERE taxi_id='.(int)$taxi_id;
			$db->setQuery($query);
			$db->query();
			
			$insertQueryValues = array();
			
			foreach ($ringRates as $ring => $items)
			{
				foreach ($items as $hotelId => $rate)
				{
					if( floatval($rate['day_fare']) > 0 || floatval($rate['night_fare']) > 0 || floatval($rate['weekend_fare']) > 0 )
					{
						$tempQuery = '('.$taxi_id.','.(int)$hotelId.','.(int)$ring;
						
						$tempQuery .= ','.floatval($rate['day_fare']);
						$tempQuery .= ','.floatval($rate['night_fare']);
						$tempQuery .= ','.floatval($rate['weekend_fare']);
						$tempQuery .= ')';
						
						$insertQueryValues[] = $tempQuery;
					}
				}
			}
			
			$insertQuery = 'INSERT INTO #__sfs_taxi_hotel_rates(taxi_id,hotel_id,ring,day_fare,night_fare,weekend_fare) VALUES';
			$insertQuery .= implode(',', $insertQueryValues);
			
			$db->setQuery($insertQuery);
			$db->query();
			
			
			return true;
			
		} else{
			$this->setError('Taxi id: '.(int)$taxi_id.' was not found');
		}
		
		return false;
	}
	
	public function getHotelRates()
	{
		$airline = SFactory::getAirline();
		$db		 = $this->getDbo();
		$query   = $db->getQuery(true);
		
		$result = array();
		
		$taxi_id = JRequest::getInt('taxi_id');
		$taxi 	 = $this->getTaxi($taxi_id);
		
		if( empty($taxi) ) return $result;
		
		// Gets Hotels
		$query->select('a.id AS hotel_id, a.name, b.ring');
		$query->from('#__sfs_hotel AS a');
		$query->innerJoin('#__sfs_hotel_backend_params AS b ON b.hotel_id=a.id');
		
		//$query->innerJoin('#__sfs_hotel_airports AS c ON c.hotel_id=a.id');
		//$query->where('c.airport_id = ' . (int) $airline->airport_id );
		
		$query->where('a.block = 0' );		
		$query->order('b.ring ASC');
		
		$db->setQuery($query);		
		$hotels = $db->loadObjectList();
		
		// Gets fare rate
		$query->clear();
		$query->select('a.*');
		$query->from('#__sfs_taxi_hotel_rates AS a');
		
		$query->where('a.taxi_id = ' . (int) $taxi->taxi_id );	
		
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		$taxiRates = array();
		
		if( count($rows) )
		{
			foreach ($rows as $row)
			{
				if( ! isset( $taxiRates[$row->ring] ) )
				{
					$taxiRates[$row->ring] = array();					
				}	
				$taxiRates[$row->ring][$row->hotel_id] = $row;
			}
		}
		
		if( count($hotels) )
		{
			foreach ($hotels as $row)
			{
				if( ! isset( $result[$row->ring] ) )
				{
					$result[$row->ring] = new stdClass();
					$result[$row->ring]->ring   = $row->ring;
					
					// One way rate for all ring hotels 
					if( isset($taxiRates[$row->ring]) && isset($taxiRates[$row->ring][0]) )
					{
						$result[$row->ring]->day_fare 		= $taxiRates[$row->ring][0]->day_fare;
						$result[$row->ring]->night_fare		= $taxiRates[$row->ring][0]->night_fare;
						$result[$row->ring]->weekend_fare	= $taxiRates[$row->ring][0]->weekend_fare;
					}
					
					$result[$row->ring]->hotels = array();
				}	
				
				if( isset($taxiRates[$row->ring]) && isset($taxiRates[$row->ring][$row->hotel_id]) )
				{
					$row->day_fare 		= $taxiRates[$row->ring][$row->hotel_id]->day_fare;
					$row->night_fare		= $taxiRates[$row->ring][$row->hotel_id]->night_fare;
					$row->weekend_fare	= $taxiRates[$row->ring][$row->hotel_id]->weekend_fare;
				}
				
				$result[$row->ring]->hotels[] = $row;
				
			}
		}
		
		return $result;
	}
	
	
	public function getTaxiVoucher()
	{
		if( $this->_taxi_voucher == null  ){
			
			$taxi_id 			= JRequest::getInt('taxi_id');
			$taxi_voucher_id	= JRequest::getInt('taxi_voucher_id');	
				
			if( $taxi_id > 0 && $taxi_voucher_id > 0 )
			{
				$airline = SFactory::getAirline();
				$db		 = $this->getDbo();
				$query   = $db->getQuery(true);
				
				$query->select('a.*,b.name AS taxi_name');
				$query->from('#__sfs_taxi_vouchers AS a');
				
				$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
				
				$query->select('c.description AS reason');
				$query->innerJoin('#__sfs_delaycodes AS c ON c.code=a.deley_code');
				
				$query->select('d.voucher_id AS hotel_voucher_id');
				$query->innerJoin('#__sfs_airline_taxi_voucher_map AS d ON d.taxi_voucher_id=a.id');
				$query->innerJoin('#__sfs_voucher_codes AS f ON f.id=d.voucher_id');
				$query->innerJoin('#__sfs_reservations AS r ON r.id=f.booking_id');										
				
				$query->where('a.id='.$taxi_voucher_id);
				$query->where('a.taxi_id='.$taxi_id);
				$query->where('r.airline_id='.(int)$airline->id);
				
				$db->setQuery($query);
			
				$this->_taxi_voucher = $db->loadObject();								
			}
		}
		
		return $this->_taxi_voucher;
	}
	
	public function getTaxiTransportationVoucher()
	{
		$taxi_id 			= JRequest::getInt('taxi_id');
		$taxi_voucher_id	= JRequest::getInt('taxi_voucher_id');	
			
		if( $taxi_id > 0 && $taxi_voucher_id > 0 )
		{
			$airline = SFactory::getAirline();
			$db		 = $this->getDbo();
			$query   = $db->getQuery(true);
			
			$query->select('a.*,b.name AS taxi_name');
			$query->from('#__sfs_taxi_vouchers AS a');
			
			$query->innerJoin('#__sfs_taxi_companies AS b ON b.id=a.taxi_id');
			
			$query->select('c.name AS terminal_name');
			$query->leftJoin('#__sfs_iatacodes AS c ON c.id=a.terminal_id AND c.type=3');
			
			$query->where('a.id='.$taxi_voucher_id);
			$query->where('a.taxi_id='.$taxi_id);
			$query->where('a.airline_id='.$airline->id);
			
			$db->setQuery($query);
		
			$taxiVoucher = $db->loadObject();
			
			$taxiVoucher->created_by = $taxiVoucher->booked_by;
			$taxiVoucher->code		 = $taxiVoucher->reference_number;
			$taxiVoucher->block_date = $taxiVoucher->booked_date;
			
			$query->clear();
			$query->select('a.*');
			$query->from('#__sfs_taxi_reservation_passengers AS a');
			$query->where('a.taxi_reservation_id='.$taxi_voucher_id);
			$db->setQuery($query);
			$passengers = $db->loadObjectList();
			
			$taxiVoucher->passengers = $passengers;
			
			
			return $taxiVoucher;
		}
		
		return null;
	}
	
	public function getHotelVoucher()
	{
		$taxiVoucher = $this->getTaxiVoucher();
		if($taxiVoucher && $taxiVoucher->hotel_voucher_id)
		{
			$hotelVoucher = SVoucher::getInstance($taxiVoucher->hotel_voucher_id,'id');
			return $hotelVoucher;
		}		
		return null;											
	}
	
}


