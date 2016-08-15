<?php
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_sfs/libraries/reservation.php';
require_once JPATH_SITE.'/components/com_sfs/libraries/core.php';
jimport('joomla.mail.helper');
class SfsModelReservation extends JModelLegacy
{

	private $airline = null;
	private $airline_type = null;
	
	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication();
	
		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}
		$value = JRequest::getInt('id');
		
		$this->setState('reservation.id', $value);
	}

	public function getReservation()
	{		
		$reservation = SReservation::getInstance( (int) $this->getState('reservation.id') );
		return $reservation;
	}
	
	public function getFaxTrack()
	{
		$reservation = $this->getReservation();
		if( !empty($reservation) ) 
		{
			$db = $this->getDbo();
			$db->setQuery('SELECT a.*,u.name FROM #__sfs_admin_fax_tracks AS a INNER JOIN #__users AS u ON u.id=a.user_id WHERE a.reservation_id='.$reservation->id.' ORDER BY a.date DESC');
			$result = $db->loadObject();
			
			if($result) {
				return $result;	
			}
		}
		return null;
	}
	
	public function getHotel()
	{
		$reservation = $this->getReservation();

		if( !empty($reservation) ) 
		{			
			if( (int) $reservation->association_id > 0 )
			{
				$association = SFactory::getAssociation($reservation->association_id);
				$db = $association->db;	
			} else {
				$db = $this->getDbo();	
			}
			
			$query = $db->getQuery(true);
			
			$query->select('a.*, b.name AS country');
			$query->from('#__sfs_hotel AS a');
			
			$query->leftJoin('#__sfs_country AS b ON b.id=a.country_id');
			
			$query->leftJoin('#__sfs_hotel_taxes AS t ON t.hotel_id=a.id');
			
			$query->select('d.code AS currency');
			$query->leftJoin('#__sfs_currency AS d ON d.id=t.currency_id');
			
			$query->select('e.name AS billing_name,e.tva_number');
			$query->leftJoin('#__sfs_billing_details AS e ON e.id=a.billing_id');
			
			$query->where('a.id='.$reservation->hotel_id);
			
			$db->setQuery($query);
			
			$hotel = $db->loadObject();
			
			if( !$hotel ) {			
				JError::raiseError(400, 'Hotel was not found');
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
			if( (int) $reservation->association_id > 0 )
			{
				$association = SFactory::getAssociation($reservation->association_id);
				$db = $association->db;	
			} else {
				$db = $this->getDbo();	
			}			
			$contacts = SFactory::getContacts(1, $reservation->hotel_id,$db);		
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

		return null;
	}
	
	
	public function getAirline()
	{
		$reservation = $this->getReservation();
		
		if( !empty($reservation) && empty($this->airline) ) {			
			$db = $this->getDbo();						
			
			$query = 'SELECT c.name AS airline_name,a.billing_id,a.telephone FROM #__sfs_airline_details AS a';			
			$query .= ' INNER JOIN #__sfs_iatacodes AS c ON c.id=a.iatacode_id';
			$query .= ' WHERE a.id=' .$reservation->airline_id;					 
					 			
			$db->setQuery($query);	
					
			$airline = $db->loadObject();
			
			if( !$airline ) {
				$this->airline_type = 3;
				$query = 'SELECT ia.name AS airline_name,a.billing_id,a.telephone
                  FROM #__sfs_airline_details AS a
                  INNER JOIN #__sfs_gh_reservations AS ghr ON ghr.reservation_id='.$reservation->id.'
                  INNER JOIN #__sfs_iatacodes AS ia ON ia.id = ghr.airline_id
                  ';
				$query .= ' WHERE a.id=' .$reservation->airline_id;
				$db->setQuery($query);										
			} else {
				$this->airline_type = 2;
			}
			
			if( $airline = $db->loadObject() ) {
				
				$bTable = JTable::getInstance('Adminbilling','SfsTable');
				$bTable->load($airline->billing_id);
				
				$db = $this->getDbo();
				$query = 'SELECT name FROM #__sfs_country WHERE id='.$bTable->country_id;
				$db->setQuery($query);
				
				if ( $country = $db->loadResult()){
					$bTable->country = $country;
				} 
				
				$airline->billing = $bTable;
				
				$this->airline = $airline;
			}										
		}
		return $this->airline;		
	}	

	public function getAirlineContact()
	{				
		$reservation = $this->getReservation();		
		
		if( !empty($reservation) ) {
			$airline = $this->getAirline();
			$contacts = SFactory::getContacts($this->airline_type, $reservation->airline_id);
		
			if(count($contacts)) {
				foreach ($contacts as $contact) {
					if($contact->is_admin) {		
						return $contact;
					}
				}
			}
			/*$db = $this->getDbo();
			$query = 'SELECT a.*,u.name,u.email FROM #__sfs_contacts AS a INNER JOIN #__users AS u on u.id=a.user_id WHERE a.user_id='.(int)$reservation->hotel_user_id;
			$db->setQuery($query);
			$result = $db->loadObject();	
		
			if( !$result ) {
				throw new JException( $db->getErrorMsg() );
			}	
			return $result;*/		
		}	

		return null;
	}
	
	public function getMessages()
	{
		$db = $this->getDbo();
		$reservation = $this->getReservation();	
		
		if( !empty($reservation) ) {
			$query = $db->getQuery(true);
			$query->select('a.*');
			$query->from('#__sfs_messages AS a');
			$query->where('a.block_id='.$reservation->id);
			
			$query->order('a.posted_date DESC');
			
			$db->setQuery($query);
			
			$result = $db->loadObjectList();
			
			return $result;
		}
		return null;	
	}
	
	public function getNotes()
	{
		$db = $this->getDbo();
		$reservation = $this->getReservation();	
		
		if( !empty($reservation) ) {
			$query = $db->getQuery(true);
			$query->select('a.*,u.name AS author');
			$query->from('#__sfs_reservation_notes AS a');
			$query->innerJoin('#__users AS u ON u.id=a.created_by');
			
			$query->where('a.reservation_id='.$reservation->id);
						
			$query->order('a.created DESC');
			
			$db->setQuery($query);
			
			$result = $db->loadObjectList();
			
			return $result;
		}
		return null;		
	}
	
	public function getFakeVoucher()
	{
		$reservation = $this->getReservation();		
		
		if( !empty($reservation) ) {
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__sfs_fake_vouchers');
			$query->where('reservation_id='.$reservation->id);
			
			$db->setQuery($query);
			
			$voucher = $db->loadObject();
			
			return $voucher;
		}
		return null;
	}
	
	public function getTracePassengers()
    {
        $rev = $this->getReservation();
        if($rev){
            $db = $this->getDbo();

            $query 	= $db->getQuery(true);
            $query->select('a.*, g.code AS individual_code');
            $query->from('#__sfs_trace_passengers AS a');

            $query->select('b.code AS voucher_code,c.flight_code,b.breakfast,b.lunch,b.mealplan,d.course_type');
            $query->leftJoin('#__sfs_voucher_codes AS b ON b.id=a.voucher_id');
            $query->leftJoin('#__sfs_voucher_groups as g ON g.id = b.voucher_groups_id');
            $query->leftJoin('#__sfs_flights_seats AS c ON c.id=b.flight_id');

            $query->leftJoin('#__sfs_reservations AS d ON d.id=b.booking_id');

            $query->select('h.name AS hotel_name,h.telephone AS hotel_phone');
            $query->leftJoin('#__sfs_hotel AS h ON h.id=d.hotel_id');

            $query->where('b.booking_id='.$rev->id);

            $query->where('b.status < 3');

            $db->setQuery($query);

            $rows = $db->loadObjectList();

            return $rows;
        }
    }
	
	public function issueVoucher()
	{

		$user = JFactory::getUser();
		
		$db = $this->getDbo();
						
		
		$reservation = $this->getReservation();		
		
		if( !empty($reservation) ) {
			//get Airline			
			$airline = $this->getAirline();			
			
			//get Picked rooms
            $db = $this->getDbo();
            $query = $db->getQuery(true);

            $query->select('p.first_name,p.last_name,p.voucher_id,b.*,f.flight_code');
            $query->from('#__sfs_trace_passengers AS p');
            $query->innerJoin('#__sfs_voucher_codes AS b ON b.id=p.voucher_id');
            $query->innerJoin('#__sfs_flights_seats AS f ON f.id=b.flight_id');           

            $query->where('b.booking_id='.(int)$reservation->id);
            $query->where('b.status < 3');

            $db->setQuery($query);
            $passengers = $db->loadObjectList();
			
			$numberIssuedVoucher = 0;

			if( count($passengers))
			{

				$this->passengers = $passengers;
				
				$issuedVouchers = array();
				
				foreach ( $this->passengers as $passenger)
				{
					if( !isset( $issuedVouchers[ $passenger->individual_voucher ] ) )
					{
						$issuedVouchers[ $passenger->individual_voucher ] = 0;
						$numberIssuedVoucher++;
					}
					$issuedVouchers[ $passenger->individual_voucher ]++;
				}
			}

			$remainingRooms = (int)JRequest::getVar('rooms');

			// Create Voucher
			$voucher = new stdClass();


			$code = $this->createRandomString(4);
			if( (int) $reservation->id < 10) {
				$code .= '-00'.$reservation->id;	
			} else if((int) $reservation->id < 100){
				$code .= '-0'.$reservation->id;
			} else {
				$code .= '-'.$reservation->id;
			}

			$code .= '-'.$this->createRandomString(2);
			
			
						
			$voucher->reservation_id = $reservation->id;
			$voucher->rooms = $remainingRooms;
			
			if( (int)$remainingRooms == 0 ) return true;

			if($voucher->rooms) {
				$code .='-'.$voucher->rooms;
			}

			$voucher->code = JString::strtoupper($code);
			$voucher->created_by = $user->id;
			$voucher->created = JFactory::getDate()->toSql();

			$contacts = SFactory::getContacts(1, $reservation->hotel_id);
		
			$hotelContacts = '';
			
			$hotelAdmin = null;
			if(count($contacts)) {
				foreach ($contacts as $contact) {
					if($contact->is_admin) {		
						$hotelAdmin = $contact;						
					}
					$hotelContacts[] = $contact->name.' '.$contact->surname;
				}
				$hotelContacts = implode('<br>', $hotelContacts);
			}

			ob_start(); 
				
			$contact_name = $hotelAdmin->name.' '.$hotelAdmin->surname;
			require_once JPATH_COMPONENT.'/helpers/voucheremail.php';	
					
			$bodyE = ob_get_clean();	
			
			$bodyE = JString::str_ireplace('{contactname}', $contact_name, $bodyE);

			$subject = 'SFS minimum guarantee voucher for '.$reservation->blockcode;

			if( $db->insertObject('#__sfs_fake_vouchers',$voucher)) {

//				$hotelAdmin->email = 'lttin0912@gmail.com';
				JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $hotelAdmin->email, $subject , $bodyE, true);			
				return true;				
			} else {
				die('Error: '.$db->getErrorMsg());
			}
		}		
	}
	
	
	public function changeStatus()
	{
		ob_start();		
		$emailBody = ob_get_clean();
		$reservation = $this->getReservation();
		
		if( ! isset($reservation) ) return false;
		
		$blockstatus = JRequest::getVar('blockstatus');
		
		$status_array = array(
		      'O' => 'Open',
		      'P' => 'Pending',
		      'T' => 'Definite',
		      'C' => 'Challenged',
		      'A' => 'Approved',
		      'R' => 'Archived',
			  'D' => 'Deleted'
		);
		
		if( ! isset($status_array[$blockstatus]) )
		{
			$this->setError('Block Status Invalid');
			return false;
		}
		
		if( $reservation->status == $blockstatus) {
			$this->setError('You must select new status');
			return false;
		}
		
		$db = JFactory::getDbo();
		
		$query = 'UPDATE #__sfs_reservations SET status='.$db->quote($blockstatus).' WHERE id='.(int)$reservation->id;
		
		$db->setQuery($query);
		
		if( !$db->query() )
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}
		
		if($blockstatus=='T'){
			$this->definiteEmail($blockstatus,$status_array);	
		}
		
		if($blockstatus=='C'){
			$this->challengedEmail($blockstatus,$status_array);	
		}

		
		$getData   = JRequest::getVar('arrList', array(), 'post', 'array');

		$dbb =& JFactory::getDBO();
		$sql = "SELECT info_email FROM #__sfs_managemail WHERE id= 3"; 
		$dbb->setQuery($sql);
		$options = $dbb->loadObjectList();
				
		$emailBody = $options[0]->info_email;

		$db_ =& JFactory::getDBO();
		$sql = "SELECT ia.name FROM #__sfs_airline_details as a"; 
		$sql .= " INNER JOIN #__sfs_iatacodes as ia ON ia.id = a.iatacode_id ";
		$sql .= " WHERE a.id = " . $getData['airline'];
		$db_->setQuery($sql);
		$resultVal = $db_->loadObjectList();
		$dateFormet = JHTML::_('date', $getData['date'], JText::_('DATE_FORMAT_LC3') );

		$sql_ = "SELECT b.email, c.systemEmails, c.name FROM #__sfs_hotel_user_map as a"; 
		$sql_ .= " INNER JOIN #__users as b ON b.id = a.user_id ";
		$sql_ .= " INNER JOIN #__sfs_contacts as c ON c.user_id = a.user_id ";
		$sql_ .= " WHERE a.hotel_id = " . $getData['hotel_id'] . " AND is_admin = 1 AND c.systemEmails != '' " ;
		$db_->setQuery($sql_);
		$resultEmail = $db_->loadObjectList();
		print_r($sql_); die();
	

		$arrList = array("{hotelcontact}","{airline}","{blockcode}","{date}","{status}");
		$arrVal = array($resultEmail[0]->name,$resultVal[0]->name,$getData['blockcode'],$dateFormet,$status_array[$blockstatus]);

		$emailBody = str_replace($arrList, $arrVal, $emailBody);	
		
		//JFactory::getMailer()->sendMail($user->email, $user->name, "phamvu180485@gmail.com", "Changing the status of a blockcode", $emailBody);

		foreach ($resultEmail as $key => $value) {			
			JFactory::getMailer()->sendMail($user->email, $user->name, $value->email, "Changing the status of a blockcode", $emailBody);
		}
		
		return true;
	}
	
	protected function definiteEmail($blockstatus,$status_array)
	{
		$reservation = $this->getReservation();
		
		$hotel 		= $this->getHotel();
		$airline 	= $this->getAirline();
		
		$hotelContacts 	 = SFactory::getContacts(1, $reservation->hotel_id);
		$airlineContacts = SFactory::getContacts($this->airline_type, $reservation->airline_id);	

				
		$emailSubject = 'Rooms Sold on Stranded Flight Solutions';
		
					
		$airline_contacts_str = '';
		foreach ($airlineContacts as $contact)
		{
			$airline_contacts_str .= '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
		}
		
		ob_start();
		
		require_once JPATH_COMPONENT.'/helpers/emails/hoteldefinite.php';
		
		$notificationBody = ob_get_clean();
		
		foreach ($airlineContacts as $contact)
		{
			$contact_name = $contact->name.' '.$contact->surname;			
			$emailBody	  = JString::str_ireplace('{contactname}', $contact_name, $notificationBody);
			
			JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', $contact->email, $emailSubject , $emailBody, true);
		}	
		
		return true;	
	}
	
	public function challengedEmail()
	{
		$reservation = $this->getReservation();
		
		$hotel 		= $this->getHotel();
		$airline 	= $this->getAirline();
		
		$hotelContacts 	 = SFactory::getContacts(1, $reservation->hotel_id);
		$airlineContacts = SFactory::getContacts($this->airline_type, $reservation->airline_id);	

				
		$emailSubject = 'Rooms Sold on Stranded Flight Solutions';
		
					
		$airline_contacts_str = '';
		foreach ($airlineContacts as $contact)
		{
			$airline_contacts_str .= '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
		}
		
		ob_start();
		
		require_once JPATH_COMPONENT.'/helpers/emails/hoteldefinite.php';
		
		$notificationBody = ob_get_clean();
		
		foreach ($airlineContacts as $contact)
		{
			$contact_name = $contact->name.' '.$contact->surname;			
			$emailBody	  = JString::str_ireplace('{contactname}', $contact_name, $notificationBody);
			
			JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', $contact->email, $emailSubject , $emailBody, true);
		}	
		
		return true;			
	}
	
	private function createRandomString( $length = 0 ) 
    {
        $chars = "abcdefghijkmnpqrstuvwxyz0123456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';

        $n = 7;        
        if( $length > 0  ) $n = $length;
        
        while ($i <= $n) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
	
	//lchung
	public function getListVoucherCode(){
		$db = $this->getDbo();
        $query = $db->getQuery(true);
		$booking_id = (int)JRequest::getInt('id');
		$query = 'SELECT a.* FROM #__sfs_voucher_codes AS a';			
		$query .= ' WHERE a.booking_id=' . $booking_id;					 
		$db->setQuery($query);	
		return $db->loadObjectList();
	}
	
	public function getReservation_sub(){
		$db = $this->getDbo();
        $query = $db->getQuery(true);
		$id = (int)JRequest::getInt('id');
		$query = 'SELECT a.* FROM #__sfs_reservations AS a';			
		$query .= ' WHERE a.id=' . $id;					 
		$db->setQuery($query);	
		return $db->loadObject();
	}	
	//End lchung
	

	//begin CPhuc

	public function getPassengers()
    {
        $rev = $this->getReservation();
        if($rev){
            $db = $this->getDbo();

            $query 	= $db->getQuery(true);
            $query->select('a.*, g.code AS individual_code, b.code as v_code');
            $query->from('#__sfs_passengers AS a');

            $query->select('b.code AS voucher_code,c.flight_code,b.breakfast,b.lunch,b.mealplan,d.course_type');
            $query->leftJoin('#__sfs_voucher_codes AS b ON b.id=a.voucher_id');
            $query->leftJoin('#__sfs_voucher_groups as g ON g.id = b.voucher_groups_id');
            $query->leftJoin('#__sfs_flights_seats AS c ON c.id=b.flight_id');

            $query->leftJoin('#__sfs_reservations AS d ON d.id=b.booking_id');

            $query->select('h.name AS hotel_name,h.telephone AS hotel_phone');
            $query->leftJoin('#__sfs_hotel AS h ON h.id=d.hotel_id');

            $query->where('b.booking_id='.$rev->id);

            $query->where('b.status < 3');

            $db->setQuery($query);

            $rows = $db->loadObjectList();

            return $rows;
        }
    }

	//end CPhuc
}
