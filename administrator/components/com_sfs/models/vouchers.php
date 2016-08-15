<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');


class SfsModelVouchers extends JModelList
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
		
		$filter_voucher_status = $this->getUserStateFromRequest($this->context.'.filter.voucher_status', 'filter_voucher_status');
		$this->setState('filter.voucher_status', $filter_voucher_status);
		
		$filter_voucher_type = $this->getUserStateFromRequest($this->context.'.filter.voucher_type', 'filter_voucher_type');
		$this->setState('filter.voucher_type', $filter_voucher_type);
		
		$filter_airline_id = JRequest::getInt('filter_airline_id',0);
		$this->setState('filter.airline_id', $filter_airline_id);
		
		$filter_hotel_id = JRequest::getInt('filter_hotel_id',0);
		$this->setState('filter.hotel_id', $filter_hotel_id);		
		

		// List state information.
		parent::populateState('a.created', 'desc');
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		//Change airport session
        $session = JFactory::getSession();
        $airport_id = $session->get("airport_current_id");//code
		
		// Select the required fields from the table.
		$query->select('a.*,c.code AS taxi_voucher_code,e.reference_number AS bus_reference_number,r.blockcode');
		$query->from('#__sfs_voucher_codes AS a');
		
		$query->innerJoin('#__sfs_reservations AS r ON r.id=a.booking_id');
		
		$query->select('rf.flight_number AS return_flight_number, rf.flight_date AS return_flight_date');
		$query->leftJoin('#__sfs_voucher_return_flights AS rf ON rf.voucher_id=a.id');
	
		$query->select('fs.flight_code,fs.comment AS flight_comment');
		$query->leftJoin('#__sfs_flights_seats AS fs ON fs.id=a.flight_id');
		
		$query->leftJoin('#__sfs_airline_taxi_voucher_map AS b ON b.voucher_id=a.id');
		$query->leftJoin('#__sfs_taxi_vouchers AS c ON c.id=b.taxi_voucher_id');
		
		$query->leftJoin('#__sfs_voucher_busreservation_map AS d ON d.voucher_id=a.id');
		$query->leftJoin('#__sfs_transportation_reservations AS e ON e.id=d.busreservation_id');
			
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'h:') === 0) {
				$search = $db->Quote('%'.$db->escape(substr($search, 2), true).'%');
				$query->where('(c.name LIKE '.$search.')');
			}
			else if (stripos($search, 'b:') === 0) {
				$search = $db->Quote('%'.$db->escape(substr($search, 2), true).'%');
				$query->where('(b.blockcode LIKE '.$search.')');				
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.code LIKE '.$search.')');
			}
		}	

		$voucher_status = $this->getState('filter.voucher_status');
		
		if( (int) $voucher_status == 1 )
		{
			$query->where('a.status < 3');
		} else if( (int)$voucher_status==3) {
			$query->where('a.status = 3');
		}
		
		$voucher_type = $this->getState('filter.voucher_type', null);
		
		if( $voucher_type !=null )
		{
			$query->where('a.vgroup = '.(int)$voucher_type);
		}
		
		if ( $filter_airline_id = $this->getState('filter.airline_id') ) {						
			$query->where('r.airline_id = '.$filter_airline_id);
		}
		
		if ( $filter_hotel_id = $this->getState('filter.hotel_id') ) {						
			$query->where('r.hotel_id = '.$filter_hotel_id);
		}
		
		if ( $airport_id != "" ) {
			$query->where('r.airport_code="' . $airport_id . '"');
		}
		
		$orderCol	= $this->state->get('list.ordering', 'a.created');
		$orderDirn	= $this->state->get('list.direction', 'DESC');
		
		//$query->order($db->escape($orderCol.' '.$orderDirn));		
		$query->order('a.created DESC');
						
		return $query;
	}
	
	public function getVoucher()
	{
		$voucher_id = JRequest::getInt('voucher_id');
		if( (int)$voucher_id > 0 )
		{
			require_once JPATH_ROOT.'/components/com_sfs/libraries/access.php';
			require_once JPATH_ROOT.'/components/com_sfs/libraries/voucher.php';
			
			$voucher = SVoucher::getInstance($voucher_id,'id');
			if($voucher->id)
			{
				return $voucher;
			}
		}
		return null;
	}
	
	public function getItems()
	{
		$items	= parent::getItems();	
		return $items;
	}	
	
	public function getAirlines()
	{
		$db = $this->getDbo();
		$query = 'SELECT a.id,a.company_name,b.name FROM #__sfs_airline_details AS a';
		$query .= ' LEFT JOIN #__sfs_iatacodes AS b ON b.id=a.iatacode_id';
		$query .= ' WHERE a.block=0 AND a.approved=1';
		$db->setQuery($query);
		
		$airlines = $db->loadObjectList();
		
		if(count($airlines))
		{
			return $airlines;
		}
		return null;
	}
	public function getHotels()
	{
		$db = $this->getDbo();
		$query = 'SELECT * FROM #__sfs_hotel WHERE block=0';
		$db->setQuery($query);
		
		$hotels = $db->loadObjectList();
		
		if(count($hotels))
		{
			return $hotels;
		}
		return null;
	}
		
}
