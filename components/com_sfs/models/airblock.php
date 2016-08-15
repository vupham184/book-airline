<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SfsModelAirblock extends JModelList
{		
	protected $_reservations=null;

	protected function populateState()
	{
		$app = JFactory::getApplication('site');
		
		$pk = JRequest::getInt('id');
		
		$this->setState('filter.blockid', $pk);
		
		// Get the pagination request variables
		$value = JRequest::getInt('limit', $app->getCfg('list_limit', 0));
		$this->setState('list.limit', $value);

		//$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$value = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $value);

		$value = trim(JRequest::getString('blockcode'));
		$this->setState('block.code',$value);
		
		$value = JRequest::getVar('blockstatus');
		$this->setState('block.status',$value);
				
		$value	= JRequest::getString('date_from');
		$this->setState('block.from',$value);
		
		$value	= JRequest::getString('date_to');		
		$this->setState('block.to',$value);
		
		$value	= JRequest::getString('showall');		
		$this->setState('list.showall',$value);
				
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		
		//lchung
		$value = trim(JRequest::getString('flightcode'));
		$this->setState('block.flightcode',$value);
		//End lchung
	}
		
	function getListQuery() 
	{
		$airline = SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;
		$db = $this->getDbo();
		$query = $db->getQuery(true);	
			
		$query->select('DISTINCT a.*, c.flight_code');
		$query->from('#__sfs_reservations AS a');
        $query->innerJoin('#__sfs_hotel AS h ON a.hotel_id = h.id');
		
		//lchung the select more flight_code
		$query->innerJoin('#__sfs_voucher_codes AS b ON b.booking_id=a.id');
		$query->innerJoin('#__sfs_flights_seats AS c ON c.id=b.flight_id');
		//End lchung	

		if($airline->grouptype==3) 
		{
			$showall = (int) $this->getState('list.showall');
			if( $showall != 1 ) {
				$query->innerJoin('#__sfs_gh_reservations AS d ON d.reservation_id=a.id AND d.airline_id='.(int)$airline->iatacode_id);
			} else {			
				$query->select('e.name AS airline_name');	
				$query->innerJoin('#__sfs_gh_reservations AS d ON d.reservation_id=a.id');
				$query->innerJoin('#__sfs_iatacodes AS e ON e.id=d.airline_id AND e.type=1');
			}		
		}
        if((int)$airport_current_id != -1)
        {
            $query->innerJoin('#__sfs_hotel_airports AS ha ON ha.hotel_id=h.id AND ha.airport_id='.$airport_current_id);
        }
		
		$query->where('a.airline_id = '.$airline->id);
		
		$query->where('a.status <> '.$db->quote('D') );

		$code = $this->getState('block.code');
		
		//lchung
		$flightcode = $this->getState('block.flightcode');
		if( isset($flightcode) && strlen($flightcode) > 0 ) {
			$query->where( 'c.flight_code LIKE "%'.$flightcode.'%"' );
		}
		//End lchung
		
		if( isset($code) && strlen($code) > 0 ) {
			$query->where( 'a.blockcode LIKE "%'.$code.'%"' );
		}

		$status = $this->getState('block.status');
		if( isset($status) && isset( SFSCore::$blockStatus[$status] ) ) {
			if( $status == 'O' ) {
				$query->where('(a.status='.$db->quote($status).' OR a.status='.$db->quote('P').')');
			} else {
				$query->where( 'a.status='.$db->quote($status));
			}
		}
			
		$block_from = $this->getState('block.from');
		if( isset($block_from) && strlen($block_from) > 0 ) {
			///$block_from .=' 00:00:00';
			$query->where( 'a.blockdate  >= '.$db->quote($block_from) );
		}
		

		$block_to = $this->getState('block.to');
		if( isset($block_to) && strlen($block_to) > 0 ) {
			///$block_to .=' 00:00:00';
			$query->where( 'a.blockdate  <= '.$db->quote($block_to) );
		}
			
		$query->order('a.blockdate DESC,a.booked_date DESC');
		//echo (string)$query;die;
		return $query;
	}
	
	public function getReservations()
	{			
		$items = $this->getItems();
		
		if(count($items))
		{
			$localHotels 	= array();
			$externalHotels = array();
			
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			// Gets local hotel
			$query->select('a.id, a.name AS hotel_name');
			$query->from('#__sfs_hotel AS a');
			$query->where('a.block=0');
			$db->setQuery($query);
			$localHotels = $db->loadObjectList('id');
						
			foreach ($items as & $item)
			{
				if( (int)$item->association_id == 0 && isset($localHotels[$item->hotel_id]) )
				{
					$item->hotel_name = $localHotels[$item->hotel_id]->hotel_name;
				} else {
					if( !isset($externalHotels[$item->association_id]) )
					{
						if( (int) $item->association_id == 0 )
						{
							$db = $this->getDbo();
						} else {
							$association = SFactory::getAssociation($item->association_id);
							$db = $association->db;
						}
						$query = 'SELECT a.id, a.name AS hotel_name FROM #__sfs_hotel AS a WHERE a.block = 0';
						$db->setQuery($query);
						$externalHotels[$item->association_id] = $db->loadObjectList('id');
					}					
					if( isset($externalHotels[$item->association_id][$item->hotel_id]) )
					{
						$item->hotel_name = $externalHotels[$item->association_id][$item->hotel_id]->hotel_name;
					}
				}
			}
		}

		return $items;
	}
	
	
	public function getReservation() {
		$pk = $this->getState('filter.blockid');
		if( (int)$pk ) {
			$forceLoad = true;
			$reservation = SReservation::getInstance( $pk, $forceLoad);
			return $reservation;
		}	
		return null;
	}
	
	public function getHotel()
	{
		$reservation = $this->getReservation();		
		//if block is exist
		if( !empty($reservation) ) {
			
			if( (int) $reservation->association_id == 0 )
			{
				$db = $this->getDbo();	
			} else {
				$association = SFactory::getAssociation($reservation->association_id);
				$db = $association->db;
			}		
			
			$query = $db->getQuery(true);
			
			$query->select('a.*, b.name AS billing_country');
			$query->from('#__sfs_hotel AS a');
									
			$query->leftJoin('#__sfs_hotel_taxes AS t ON t.hotel_id=a.id');
			
			$query->select('d.code AS currency');
			
			$query->leftJoin('#__sfs_currency AS d ON d.id=t.currency_id');
			
			$query->select('e.name AS billing_name,e.tva_number,e.address AS billing_address,e.city AS billing_city,e.zipcode AS billing_zipcode');
			
			$query->leftJoin('#__sfs_billing_details AS e ON e.id=a.billing_id');
			
			$query->leftJoin('#__sfs_country AS b ON b.id=e.country_id');
			
			$query->select('st.name AS billing_state');
			$query->leftJoin('#__sfs_states AS st ON st.id=e.state_id');
			
			$query->where('a.id='.$reservation->hotel_id);

			$db->setQuery($query);
			
			$hotel = $db->loadObject();

			if(empty($hotel->currency))
            {
                $system_currency = SfsHelper::getCurrency();
                $currency = SfsWs::getCurrencyIdByCurrencyCode($system_currency);
                $hotel->currency = $currency['CurrencySymbol'];
            }

			if( !$hotel ) {
				$this->setError('Hotel was not found');
				return false;				
			}
			
			return $hotel;
		}
		return null;
	}
	
	public function getHotelContact()
	{				
		$reservation = $this->getReservation();

		if( !empty($reservation) ) {
			
			$externaldb = null;
			
			if( (int) $reservation->association_id > 0 )
			{
				$association = SFactory::getAssociation($reservation->association_id);
				$externaldb  = $association->db;
			}
            $user	= JFactory::getUser((int)$reservation->booked_by);
            if(SFSAccess::check( $user, 'gh' )) {
                $model = JModel::getInstance('Contact', 'SfsModel', array('ignore_request' => true));
                $contact = $model->getItem((int)$user->id);
                return $contact;
            }else{
                $contacts = SFactory::getContacts(1, $reservation->hotel_id, $externaldb);
                if(count($contacts)) {
                    foreach ($contacts as $contact) {
                        if($contact->contact_type==2) {
                            return $contact;
                        }
                    }
                    foreach ($contacts as $contact) {
                        if($contact->is_admin) {
                            return $contact;
                        }
                    }
                }
            }
		}
		return null;
	}
	
	public function getGuaranteeVoucher() 
	{
		$db = $this->getDbo();
		$reservation = $this->getReservation();
		
		if( !empty($reservation) )
		{
			$query = $db->getQuery(true);
			
			$query->select('a.*');
			$query->from('#__sfs_fake_vouchers AS a');			
	
			$query->where('a.reservation_id='.$reservation->id);
						
			$db->setQuery($query);
			
			$result = $db->loadObject();

			if($result) {
				return $result;
			}
		}
		
		return null;
	}

	public function getPassengers()
	{
		$reservation = $this->getReservation();
		$this->_data = array();

		if( !empty($reservation) )
		{
			if( (int) $reservation->association_id == 0 )
			{
				$db = $this->getDbo();
			} else {
				$association = SFactory::getAssociation($reservation->association_id);
				$db = $association->db;
			}
			$query = $db->getQuery(true);

			$query->select('p.*, v.code, g.code AS individual_code, r.breakfast, r.lunch, r.mealplan');
			///$query->from('#__sfs_passengers AS p');
			$query->from('#__sfs_trace_passengers AS p');
			$query->innerJoin('#__sfs_voucher_codes AS v ON p.voucher_id = v.id');
			$query->innerJoin('#__sfs_reservations AS r ON r.id = v.booking_id');
            ///$query->leftJoin('#__sfs_voucher_groups as g ON g.voucher_id = p.individual_voucher');
			$query->innerJoin('#__sfs_voucher_groups as g ON g.id = v.voucher_groups_id');
			//$query->group('g.id');
			
			$query->where('r.id='.$reservation->id);
			///echo (string)$query;die;
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			$this->_data = $rows;
			return $this->_data;
		}

		return null;
	}

	public function getStart()
	{
		return $this->getState('list.start');
	}
	
	//lchung
	
	public function getPassenger( $id )
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('p.*');
		$query->from('#__sfs_trace_passengers AS p');
		$query->where('p.id='.$id);
		$db->setQuery($query);
		$row = $db->loadObject();
		return $row;
	}
	
	public function saveInvoiceNumberComment( $data )
	{
		$invoice_number = $data['invoice_number'];
		$comment = $data['comment'];
		$insurance = ( isset( $data['insurance'] ) ) ? '1 ': '0';
		$touroperator_client = ( isset( $data['touroperator_client'] ) ) ? '1 ': '0';
		$id = $data['passenger_id'];
		$box = $data['box'];
		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		if ( $box == 1 )
		$query = "UPDATE #__sfs_trace_passengers SET invoice_number='$invoice_number',comment='$comment',insurance=$insurance,touroperator_client=$touroperator_client WHERE id=$id";
		else
			$query = "UPDATE #__sfs_trace_passengers SET invoice_number='$invoice_number',comment='$comment' WHERE id=$id";
			
        $db->setQuery($query);
        $db->query();
		return 1;
	}
	
	public function saveAllInvoiceNumberComment( $data ){
		$invoice_number = $data['invoice_number'];
		$comment = $data['comment'];
		$insurance = ( isset( $data['insurance'] ) ) ? '1 ': '0';
		$touroperator_client = ( isset( $data['touroperator_client'] ) ) ? '1 ': '0';
		$id = $data['passenger_id'];
		$box = $data['box'];		
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query = "UPDATE #__sfs_trace_passengers SET invoice_number='$invoice_number',comment='$comment' WHERE comment =''";
		
        $db->setQuery($query);
        $db->query();
		return 1;
	}
	
	public function saveInvoiceStatus( $data )
	{
		$invoice_status = $data['invoice_status'];
		$id = $data['passenger_id'];
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query = "UPDATE #__sfs_trace_passengers SET invoice_status=$invoice_status WHERE id=$id";
        $db->setQuery($query);
        $db->query();
		return 1;
	}
	
	public function saveMarkSelectionStatus( $data )
	{
		$colum = $data['colum'];
		$value = $data['value'];
		if ( $colum == '' ) {
			return 0;
		}
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query = "UPDATE #__sfs_trace_passengers SET $colum=$value WHERE $colum=3";//3 la gia tri default
        $db->setQuery($query);
        $db->query();
		return 1;
	}
	//End lchung
	
}

