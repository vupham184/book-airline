<?php
// No direct access
defined('_JEXEC') or die;

class SEmail
{	
	public static function airlineChangeStatusTo( $status , $reservation ) 
	{
		if( $status =='A' || $status ='C' ) 
		{
			$user = JFactory::getUser();
			$airline = SFactory::getAirline();
			
			if($airline->id && SFSAccess::check($user, 'a.admin')) 
			{			
				if($airline->id != $reservation->airline_id){
					jexit('Airline Status Change: Restricted Access');
				}		

				if( (int) $reservation->association_id > 0 )
				{
					$association    = SFactory::getAssociation($reservation->association_id);
					$hotel_contacts = SFactory::getContacts(1, $reservation->hotel_id, $association->db);	
				} else {
					$hotel_contacts = SFactory::getContacts(1, $reservation->hotel_id);	
				}
				
				$hotel_contact_name = '';
				
				foreach ($hotel_contacts as $hotel_contact){
					if($hotel_contact->is_admin) {
						$hotel_contact_name = $hotel_contact->name.' '.$hotel_contact->surname;
						break;
					}
				}		
				$airline_name = $airline->name;
				$blockcode = $reservation->blockcode;
				$arrival_date = SfsHelperDate::getDate( $reservation->date, JText::_('DATE_FORMAT_LC3') );
				if($status=='A'){
					$current_status = 'Accepted';
				} else if($status='C') {
					$current_status = 'Challenged';
				}				
								
				$hotel_contacts_str = '';
				foreach ($hotel_contacts as $contact) {
					$hotel_contacts_str .= '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
				}				
				
				ob_start(); 
				require_once JPATH_COMPONENT.'/libraries/emails/airlineaccepted.php';			
				$bodyE = ob_get_clean();					
				
				foreach ($hotel_contacts as $hotel_contact){
					if($hotel_contact->is_admin) {						
						JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $hotel_contact->email, 'Rooms Sold on Stranded Flight Solutions', $bodyE, true);
						break;
					}
				}
				
			} else {
				return false;
			}			
		} 
		return true;
	}	

	
	public static function hotelChangeStatusTo( $status , $reservation ) 
	{
		$user  = JFactory::getUser();
		$hotel = SFactory::getHotel();
		
		if( $hotel->id ) 
		{						
			if( $hotel->id != $reservation->hotel_id ) {
				JFactory::getApplication()->close('Hotel Status Change: Restricted Access');
			}		

			$db = $reservation->getDbo();			
			$airline_contacts = SFactory::getContacts(2, $reservation->airline_id, $db);			
			$airline_contact_name = '';
			
			foreach ($airline_contacts as $airline_contact){
				if($airline_contact->is_admin) {
					$airline_contact_name = $airline_contact->name.' '.$airline_contact->surname;
					break;
				}
			}		
						
			$hotel_name = $hotel->name;
			$blockcode  = $reservation->blockcode;
			$arrival_date = SfsHelperDate::getDate( $reservation->date, JText::_('DATE_FORMAT_LC3') );
			
			if($status=='T'){
				$current_status = 'Definite';
			} else if($status='C') {
				$current_status = 'Challenged';
			}				
							
			$airline_contacts_str = '';
			foreach ($airline_contacts as $contact) {
				$airline_contacts_str .= '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
			}				
			
			ob_start(); 
			require_once JPATH_COMPONENT.'/libraries/emails/hoteldefinite.php';			
			$bodyE = ob_get_clean();					
			
			foreach ($airline_contacts as $airline_contact)
			{
				if($airline_contact->is_admin) {						
					JUtility::sendMail('airline_support@SFS-web.com', 'Stranded Flight Solutions', $airline_contact->email, 'Rooms Sold on Stranded Flight Solutions', $bodyE, true);					
					break;
				}
			}
			
		} else {
			return false;
		}			
		
		return true;
	}	
	
	public static function messageToHotel( $status , $reservation, $message ) 
	{		
		
		$user    = JFactory::getUser();
		$airline = SFactory::getAirline();
		
		if($airline->id && SFSAccess::check($user, 'a.admin')) 
		{						
			if($airline->id != $reservation->airline_id){
				jexit('Airline message sent faild');
			}	

			if( (int) $reservation->association_id > 0 )
			{
				$association = SFactory::getAssociation($reservation->association_id);
				$hotel_contacts = SFactory::getContacts(1, $reservation->hotel_id, $association->db);	
			} else {
				$hotel_contacts = SFactory::getContacts(1, $reservation->hotel_id);	
			}
						
			$hotel_contact_name = '';
			
			foreach ($hotel_contacts as $hotel_contact){
				if($hotel_contact->is_admin) {
					$hotel_contact_name = $hotel_contact->name.' '.$hotel_contact->surname;
					break;
				}
			}		
			
			$airline_name = $airline->name;			
			$blockcode    = $reservation->blockcode;
			
			$arrival_date = SfsHelperDate::getDate( $reservation->date, JText::_('DATE_FORMAT_LC3') );
			
			if($status=='T'){
				$current_status = 'Definite';
			} else if($status='C') {
				$current_status = 'Challenged';
			}				
							
			$hotel_contacts_str = '';
			foreach ($hotel_contacts as $contact) {
				$hotel_contacts_str .= '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
			}				
			
			ob_start(); 
			require_once JPATH_COMPONENT.'/libraries/emails/messagetohotel.php';			
			$bodyE = ob_get_clean();					
			
			foreach ($hotel_contacts as $hotel_contact){
				if($hotel_contact->is_admin) {						
					JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $hotel_contact->email, 'Rooms Sold on Stranded Flight Solutions', $bodyE, true);
					break;
				}
			}
			
		} else {
			return false;
		}			
	
		return true;
	}	
	
	public static function messageToAirline( $status , $reservation, $message ) 
	{
		$user  = JFactory::getUser();
		$hotel = SFactory::getHotel();
		
		if( $hotel->id ) 
		{			
			if( $hotel->id != $reservation->hotel_id){
				jexit('Hotel Status Change: Restricted Access');
			}

			$db = $reservation->getDbo();	
			
			$airline_contacts = SFactory::getContacts(2, $reservation->airline_id, $db);
			$airline_contact_name = '';
			
			foreach ($airline_contacts as $airline_contact){
				if($airline_contact->is_admin) {
					$airline_contact_name = $airline_contact->name.' '.$airline_contact->surname;
					break;
				}
			}	
						
			$hotel_name   = $hotel->name;
			$blockcode    = $reservation->blockcode;
			$arrival_date = SfsHelperDate::getDate( $reservation->date, JText::_('DATE_FORMAT_LC3') );
			
			if($status=='T'){
				$current_status = 'Definite';
			} else if($status='C') {
				$current_status = 'Challenged';
			}				
							
			$airline_contacts_str = '';
			foreach ($airline_contacts as $contact) {
				$airline_contacts_str .= '- '.$contact->name.' '.$contact->surname.', '.$contact->job_title.'<br />';
			}				
			
			ob_start(); 
			require_once JPATH_COMPONENT.'/libraries/emails/messagetoairline.php';			
			$bodyE = ob_get_clean();					
			
			foreach ($airline_contacts as $airline_contact){
				if($airline_contact->is_admin) {						
					JUtility::sendMail('airline_support@SFS-web.com', 'Stranded Flight Solutions', $airline_contact->email, 'Rooms Sold on Stranded Flight Solutions', $bodyE, true);					
					break;
				}
			}
			
		} else {
			return false;
		}			
		
		return true;		
	}
	
	public static function cancelVoucher($reservation,$voucher)
	{
		$airline      = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		$airline_name = $airline->name;
		$blockcode    = $reservation->blockcode;
		$voucher_code = $voucher->code;
		$booked_date  = JHTML::_('date', $reservation->room_date, JText::_('DATE_FORMAT_LC3'));
		
		if($time_zone != '' ) {				
			$now = SfsHelperDate::getMySqlDate('now', $time_zone);		
		} else {
			$now = JFactory::getDate()->toSql();	
		}	
		$cancel_date = JHTML::_('date',$now, JText::_('DATE_FORMAT_LC2'));	
		$seats = $voucher->seats;
		$breakfast = $reservation->breakfast;
		$course_type = $reservation->course_type;
		
		if( (int) $reservation->association_id > 0 )
		{
			$association = SFactory::getAssociation($reservation->association_id);
			$hotel_contacts = SFactory::getContacts(1, $reservation->hotel_id, $association->db);	
		} else {
			$hotel_contacts = SFactory::getContacts(1, $reservation->hotel_id);	
		}
		
		$contact_name = '';
		$hotel_contacts_str = '';
		
		foreach ($hotel_contacts as $hotel_contact){
			$hotel_contacts_str .= '- '.$hotel_contact->name.' '.$hotel_contact->surname.', '.$hotel_contact->job_title.'<br />';
			if($hotel_contact->is_admin) {
				$contact_name = $hotel_contact->name.' '.$hotel_contact->surname;		
			}																
		}
		
		ob_start(); 
		require_once JPATH_COMPONENT.'/libraries/emails/cancelvoucheremail.php';			
		$bodyE = ob_get_clean();					
		
		foreach ($hotel_contacts as $hotel_contact){
			if($hotel_contact->is_admin) {						
				JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $hotel_contact->email, 'SFS-web voucher cancelled', $bodyE, true);
				break;
			}
		}		
		
	}
	
	public static function cancelTaxiVoucher($hotelVoucherId)
	{
		$airline     = SFactory::getAirline();
		$db			 = JFactory::getDbo();
		$query 		 = $db->getQuery(true);
		
		$query->select('a.*,b.code AS taxi_voucher_code,b.block_date,b.hotel_id');	
		$query->from('#__sfs_airline_taxi_voucher_map AS a');
		
		$query->innerJoin('#__sfs_taxi_vouchers AS b ON b.id=a.taxi_voucher_id');
		
		$query->select('t.name AS taxi_name,t.telephone AS taxi_telephone,t.fax AS taxi_fax,t.email AS taxi_email,t.sendMail,t.sendFax');
		$query->innerJoin('#__sfs_taxi_companies AS t ON t.id=b.taxi_id');		
		 			
		$query->where('a.voucher_id='.(int)$hotelVoucherId);

		$db->setQuery($query);
		
		$taxiVoucher = $db->loadObject();
		
		if($taxiVoucher)
		{
			$taxiPassengers  = array();
						
			$query->clear();
			$query->select('a.*');
			$query->from('#__sfs_passengers AS a');
			$query->where('a.voucher_id='.(int)$hotelVoucherId);
			$db->setQuery($query);
		
			$tracePassengers = $db->loadObjectList();
						
			if( count($tracePassengers) )
			{			
				$passengerMobile = array();	
				foreach ($tracePassengers as $tracePassenger)
				{
					$name = $tracePassenger->first_name.' '.$tracePassenger->last_name;
					$taxiPassengers[] = trim($name);					
				}					
			}
			
			$hotel = null;
			
			if($taxiVoucher->hotel_id)
			{
				$hotel = SFactory::getHotel($taxiVoucher->hotel_id);				
			}
			
			ob_start();
			
			require_once JPATH_COMPONENT.'/libraries/emails/canceltaxivoucher.php';
			
			$faxEmailBody = ob_get_clean();
						
			$taxiEmailBody = JString::str_ireplace('[^]', '', $faxEmailBody);
			$taxiEmailBody = JString::str_ireplace('{date}', '', $taxiEmailBody);
			
			$mailFrom = 'airline_support@sfs-web.com';
			$fromName = 'Stranded Flight Solutions';
			$subject  = 'SFS TAXI VOUCHER CANCELLED '.$taxiVoucher->taxi_voucher_code;
				
			if( $taxiVoucher->sendMail && $taxiVoucher->taxi_email )
			{	 						
				$emails = explode(';', $taxiVoucher->taxi_email);			
				if(count($emails)){
					foreach ($emails as $email)
					{
						$email = trim($email);
						JUtility::sendMail($mailFrom, $fromName, $email, $subject, $taxiEmailBody, true);		
					}	
				}
			}
			
			jimport('joomla.filesystem.file');
			
			$faxAtt 		= JPATH_SITE.DS.'media'.DS.'sfs'.DS.'taxireservations'.DS.'cancelfaxblock'.$taxiVoucher->taxi_voucher_id.'.html';						
			$faxEmailBody 	= JString::str_ireplace('{date}', SfsHelperDate::getDate(), $faxEmailBody);			
			JFile::write($faxAtt, $faxEmailBody);
			
	
			$faxNumber 	= $taxiVoucher->taxi_fax;	
			if($taxiVoucher->sendFax && $faxNumber)
			{
				JUtility::sendMail($mailFrom, $fromName ,$faxNumber.'@efaxsend.com', $subject, $faxEmailBody, true,null,null,$faxAtt);	
			}			
			
		}
	}
	
	/**
	 * 
	 * Send the voucher to guest.
	 * 
	 * @param string $email
	 * @param SVoucher $voucher
	 */
	public static function guestVoucher($email, $voucher)
	{
		jimport('joomla.mail.helper');
		
		// Make sure the mail is correct
		if ( ! JMailHelper::isEmailAddress($email) ) 
		{
			return false;
		}
		// Initialise variables.
		$db 		 = JFactory::getDbo();		
				
		$totalAmount = $voucher->getTotalCharged();
				
		// Gets hotel
		if( (int)$voucher->association_id > 0)
		{
			$association = SFactory::getAssociation($voucher->association_id);
			$hotel  = new SHotel($voucher->hotel_id, $association->db);
		} else {
			$hotel  = SFactory::getHotel($voucher->hotel_id);	
		}
				
		$blockCode  = $voucher->blockcode;	

		$namesArray = array();
		$namesText  = '';
		
		///$passengers = $voucher->getTracePassengers();
		$passengers = self::getTracePassengers();
		// info_of_card_ap_meal
		// info_of_card_ap_taxi
		///$cards = $voucher->getCardAirplusws();
		$card_airplusws =  self::getCardAirplusws();
		$namesArray = array();
		if( count($passengers) ){
            $namesArray = array();
			foreach ($passengers as $passenger)
			{

				//$room = $passenger->voucher_room_id;
				$name = $passenger->first_name." ".$passenger->last_name ;
				//$namesArray[$room][] =  trim($name);
				$namesArray[] =  trim($name);
				//$namesText = implode(', ', $namesArray[$room]);
			}
		}

		$namesText = implode(', ', $namesArray);
		$dg = self::getVoucherGroups( $voucher->voucher_groups_id);
		$vouchercode = $dg->code;// $voucher->voucher_code;
		if ( $voucher->comment == '' ) {
			$voG = self::getVouchersIdGroup( $voucher->voucher_groups_id );
			foreach ( $voG as $vG ) {
				if( $vG->comment != '' ) {
					$voucher->comment = $vG->comment;
					break;
				}
			}
		}
		
		$airline = SFactory::getAirline();
        $logo = $airline->logo;
		
		ob_start();
		if(empty($hotel->ws_id)) {
			require_once JPATH_COMPONENT.'/libraries/emails/guestvoucher.php';
			$emailBodyCard = '';
			foreach( $card_airplusws as $cards){
				
				if ( !empty($cards['info_of_card_ap_taxi']) ) {
					foreach($cards['info_of_card_ap_taxi'] as $info_of_card) {
						$info_of_card = json_encode($info_of_card);
						///include JPATH_COMPONENT.'/libraries/emails/sendemail-card.php';
						include JPATH_COMPONENT.'/libraries/emails/sendemail-card-text.php';
					}
				}
				if ( !empty($cards['info_of_card_ap_meal']) ) {
					foreach($cards['info_of_card_ap_meal'] as $info_of_card_meal) {
						$info_of_card_meal = json_encode($info_of_card_meal);
						///include JPATH_COMPONENT.'/libraries/emails/sendemail-card-meal.php';
						include JPATH_COMPONENT.'/libraries/emails/sendemail-card-meal-text.php';
					}
				}
			}//End foreach $card_airplusws
		}
		else {
			require_once JPATH_COMPONENT.'/libraries/emails/guestvoucher_ws.php';
		$airline = SFactory::getAirline();
			$emailBodyCard = '';
			foreach( $card_airplusws as $cards){
				if ( !empty($cards['info_of_card_ap_taxi']) ) {
					foreach($cards['info_of_card_ap_taxi'] as $info_of_card) {
						$info_of_card = json_encode($info_of_card);
						///include JPATH_COMPONENT.'/libraries/emails/sendemail-card.php';
						include JPATH_COMPONENT.'/libraries/emails/sendemail-card-text.php';
					}
				}
				if ( !empty($cards['info_of_card_ap_meal']) ) {
					foreach($cards['info_of_card_ap_meal'] as $info_of_card_meal) {
						$info_of_card_meal = json_encode($info_of_card_meal);
						///include JPATH_COMPONENT.'/libraries/emails/sendemail-card-meal.php';
						include JPATH_COMPONENT.'/libraries/emails/sendemail-card-meal-text.php';
					}
				}
			}//End foreach $card_airplusws
			echo '<p align="center">Share your experience on www.strandedexperience.com</p>';
		}
		$emailBody = ob_get_clean();
		#die($emailBody);
		if($logo)
		{
			$logo = '<img style="margin:10px 10px 0px;" src="'.JURI::base().'/'.$logo.'"  height="45" />';
		}
		$emailBody = JString::str_ireplace('{logo}', $logo, $emailBody);
		
		$config = JFactory::getConfig();
		$data['fromname'] = $config->get('fromname');
		$data['mailfrom'] = $config->get('mailfrom');
		
		$cc = '';
		if( isset( $airline->params["email_copy_voucher"] ) )
		$cc = $airline->params["email_copy_voucher"];
			
		$emailSubject  = JText::_('COM_SFS_MATCH_PASSENGER_SUBJECT');
		
		// Send email
		$sent = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $email, $emailSubject, $emailBody,true,$cc);		

		if($sent) {
			// update voucher status		
			$updateEmails = array();
			if( !empty($voucher->passenger_email)) {
				$updateEmails = explode(',', $voucher->passenger_email);
			}
			$updateEmails[] = $email;
			
			$emailField = implode(',', $updateEmails);
						
			$query = 'UPDATE #__sfs_voucher_codes SET passenger_email = '.$db->quote($emailField).',status=2 WHERE id='.$voucher->id;
			$db->setQuery($query);
			if( !$db->query() ) 
			{
				return false;
			}				
		} else {
			return false;	
		}
		
		return true;
	}

	//lchung
	public function getVoucher()
	{
		
		$voucherId = JRequest::getVar('voucher_id', "");
		$voucher = array();
		if( !$voucherId )
		{
			$reservation_id = JRequest::getInt('reservation_id');
			if($reservation_id == -1)
			{
				$session = JFactory::getSession();
				$reservation = unserialize($session->get("reservation_temp"));
				$reservation_id = $reservation->id;
			}
			if($reservation_id)
			{
				$db = JFactory::getDbo();
				$query = 'SELECT id FROM #__sfs_voucher_codes WHERE booking_id = '.(int)$reservation_id;
				$db->setQuery($query);
				$voucherId = $db->loadResult();
			}
		}
		
		if( $voucherId > 0 )
		{
			$voucher = SVoucher::getInstance($voucherId,'id');	
			if( (int) $voucher->id > 0 )
			{
				$voucher = $voucher;
			}			
		}
		return $voucher;
	}
	
	public function getVouchersIdGroup( $voucher_groups_id = 0 )
	{
		$d = array();
		
		if($voucher_groups_id > 0 )
		{
			$db = JFactory::getDbo();
			$query = 'SELECT * FROM #__sfs_voucher_codes WHERE voucher_groups_id = '.(int)$voucher_groups_id;
			$db->setQuery($query);
			$d = $db->loadObjectList();
		}
		return $d;
	}
	
	public function getTracePassengers()
    {
		$voucherIds = JRequest::getVar('voucher_id', "");
		$db = JFactory::getDbo();
		
		if( $voucherIds == '' ) {
			$voucherIds =  self::getVoucher()->id;
		}
		
		$query = 'SELECT * FROM #__sfs_trace_passengers WHERE voucher_id IN(' . $voucherIds . ')';
		$db->setQuery($query);
		$trace_passengers = $db->loadObjectList();
        return $trace_passengers;
    }
	
	public function getVoucherGroups( $voucher_groups_id )
	{		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__sfs_voucher_groups AS a');
		$query->where('a.id='. $voucher_groups_id);
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	public function getCardAirplusws()
    {
		$voucherIds = JRequest::getVar('voucher_id', "");
		$db = JFactory::getDbo();
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
	
	public static function emailAndFaxToTaxi($taxi_voucher_id)
	{
		$airline 	= SFactory::getAirline();
		$db 		= JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$query->select('a.*,v.voucher_id,b.booked_by');
		$query->from('#__sfs_taxi_vouchers AS a');
		$query->innerJoin('#__sfs_reservations AS b ON b.id=a.booking_id');
		$query->select('t.name AS taxi_name,t.telephone AS taxi_telephone,t.fax AS taxi_fax,t.email AS taxi_email,t.sendMail,t.sendFax');
		$query->innerJoin('#__sfs_taxi_companies AS t ON t.id=a.taxi_id');
		$query->innerJoin('#__sfs_airline_taxi_voucher_map AS v ON v.taxi_voucher_id=a.id');
				
		$query->where('a.id='.(int)$taxi_voucher_id);
		
		$db->setQuery($query);
		
		$taxiReservation = $db->loadObject();
		
		if( !$taxiReservation ) {
			return false;	
		}
		
		if( (int) $taxiReservation->printed == 1 )
		{
			return;
		}
		
		$booked_contact 	= SFactory::getContact((int)$taxiReservation->booked_by);									
		$booked_name 		= $booked_contact->name.' '.$booked_contact->surname;
		$booked_title 		= $booked_contact->job_title;
		
		$airline_name				= $airline->name;						
		$airline_contact_name 		= $booked_contact->name.' '.$booked_contact->surname;
		$airline_contact_title 		= $booked_contact->job_title;	
		$airline_contact_telephone 	= $booked_contact->telephone;
		$airline_contact_email 		= $booked_contact->email;	
		
		$taxiReservation->arrival_time = 'As soon as possible'; 
		
		$taxiPassengers  = array();
		$passengerMobile = null;
		
		if($taxiReservation->voucher_id)
		{
			$query->clear();
			$query->select('a.*');
			$query->from('#__sfs_passengers AS a');
			$query->where('a.voucher_id='.(int)$taxiReservation->voucher_id);
			$db->setQuery($query);
		
			$tracePassengers = $db->loadObjectList();
						
			if( count($tracePassengers) )
			{			
				$passengerMobile = array();	
				foreach ($tracePassengers as $tracePassenger)
				{
					$name = $tracePassenger->first_name.' '.$tracePassenger->last_name;
					$taxiPassengers[] = trim($name);
					if ($tracePassenger->phone_number)
					{
						$tracePassenger->phone_number = trim($tracePassenger->phone_number);
						if( ! in_array($tracePassenger->phone_number, $passengerMobile) ){
							$passengerMobile[] = $tracePassenger->phone_number;	
						}						
					}
				}					

				if( count($passengerMobile) == 0 ) $passengerMobile = null;			
			}
			
			$taxiReservation->reference_number = $taxiReservation->code; 
			
		}
		
		$billingDetail = $airline->getBillingDetail();
		
		ob_start();
		
		require_once JPATH_COMPONENT.'/libraries/emails/taxireservation.php';
		
		$faxEmailBody = ob_get_clean();
		
		$taxiEmailBody = JString::str_ireplace('[^]', '', $faxEmailBody);
		$taxiEmailBody = JString::str_ireplace('{date}', '', $taxiEmailBody);
		
		$mailFrom = 'airline_support@sfs-web.com';
		$fromName = 'Stranded Flight Solutions';
		$subject  = 'SFS-web TAXI TRANSPORTATION Reservation '.$taxiReservation->reference_number;
			
		if( $taxiReservation->sendMail && $taxiReservation->taxi_email )
		{	 						
			$emails = explode(';', $taxiReservation->taxi_email);			
			if(count($emails)){
				foreach ($emails as $email)
				{
					$email = trim($email);
					JUtility::sendMail($mailFrom, $fromName, $email, $subject, $taxiEmailBody, true);		
				}	
			}
		}
		
		jimport('joomla.filesystem.file');
		
		$faxAtt 		= JPATH_SITE.DS.'media'.DS.'sfs'.DS.'taxireservations'.DS.'faxblock'.$taxi_voucher_id.'.html';			
		$faxEmailBody 	= JString::str_ireplace('{date}', SfsHelperDate::getDate(), $faxEmailBody);			
		JFile::write($faxAtt, $faxEmailBody);

		$faxNumber 	= $taxiReservation->taxi_fax;	
		if($taxiReservation->sendFax && $faxNumber)
		{
			JUtility::sendMail($mailFrom, $fromName ,$faxNumber.'@efaxsend.com', $subject, $faxEmailBody, true,null,null,$faxAtt);	
		}
		
		return true;
	}
	
	
	public static function emailAndFaxToTaxi2($taxiReservation=null)
	{				
		if( empty($taxiReservation) ) {
			return false;	
		}
		
		$airline 	= SFactory::getAirline();
		$db 		= JFactory::getDbo();
		
		$query = $db->getQuery(true);
		
		$booked_contact 	= SFactory::getContact((int)$taxiReservation->booked_by);									
		$booked_name 		= $booked_contact->name.' '.$booked_contact->surname;
		$booked_title 		= $booked_contact->job_title;
		
		$airline_name				= $airline->name;						
		$airline_contact_name 		= $booked_contact->name.' '.$booked_contact->surname;
		$airline_contact_title 		= $booked_contact->job_title;	
		$airline_contact_telephone 	= $booked_contact->telephone;
		$airline_contact_email 		= $booked_contact->email;	
		
		if( $taxiReservation->requested_time == '0' )
		{
			$taxiReservation->arrival_time = 'As soon as possible';	
		} else {
			$taxiReservation->arrival_time = $taxiReservation->requested_time;
		}
		 
		
		$taxiPassengers  = array();		
		$passengerMobile = null;
		
		if(count($taxiReservation->passengers))
		{
			$passengerMobile = array();
			foreach ($taxiReservation->passengers as $p){				
				$name = $p->first_name.' '.$p->last_name;
				$taxiPassengers[] = trim($name);	
				if($p->phone_number)
				{
					$passengerMobile[] = $p->phone_number;
				}									
			}			
		}
		
		$billingDetail = $airline->getBillingDetail();
		
		$taxiReservation->block_date = SfsHelperDate::getDate($taxiReservation->booked_date,'Y-m-d');
		
		ob_start();
		
		require_once JPATH_COMPONENT.'/libraries/emails/taxireservation.php';
		
		$faxEmailBody = ob_get_clean();
				
		$taxiEmailBody = JString::str_ireplace('[^]', '', $faxEmailBody);
		$taxiEmailBody = JString::str_ireplace('{date}', '', $taxiEmailBody);
		
		$mailFrom = 'airline_support@sfs-web.com';
		$fromName = 'Stranded Flight Solutions';
		$subject  = 'SFS-web TAXI TRANSPORTATION Reservation '.$taxiReservation->reference_number;
			
		if( $taxiReservation->sendMail && $taxiReservation->taxi_email )
		{	 						
			$emails = explode(';', $taxiReservation->taxi_email);			
			if(count($emails)){
				foreach ($emails as $email)
				{
					$email = trim($email);
					JUtility::sendMail($mailFrom, $fromName, $email, $subject, $taxiEmailBody, true);		
				}	
			}
		}
		
		jimport('joomla.filesystem.file');
		
		$faxAtt 		= JPATH_SITE.DS.'media'.DS.'sfs'.DS.'taxireservations'.DS.'tfaxblock'.$taxiReservation->id.'.html';			
		$faxEmailBody 	= JString::str_ireplace('{date}', SfsHelperDate::getDate(), $faxEmailBody);			
		JFile::write($faxAtt, $faxEmailBody);

		$faxNumber 	= $taxiReservation->taxi_fax;	
		if($taxiReservation->sendFax && $faxNumber)
		{
			JUtility::sendMail($mailFrom, $fromName ,$faxNumber.'@efaxsend.com', $subject, $faxEmailBody, true,null,null,$faxAtt);	
		}
		
		return true;
	}
	
	
	public static function SMS($type,$text,$additionPhones=array())
	{
		$phoneNumber = null;
		$params = JComponentHelper::getParams('com_sfs');
		$subject = '';	
		
		if($type=='taxi'){
			$phoneNumber = $params->get('sms_taxi_phone_number');			
		}
		if($type=='bus'){
			$phoneNumber = $params->get('sms_bus_phone_number');			
		}
		
		if($phoneNumber && $text)
		{
			$username 	= 'sfs-web';
			$code 		= '55df53a5e9407f1627f02eae35ecde37';
			//84912858384;31638319078
			$phones = explode(';', $phoneNumber);
			
			if(count($additionPhones))
			{
				$phones = array_merge($phones,$additionPhones);
			}
			
			if( count($phones) )
			{
				$fromName   = 'SFS WEB';
				$mailFrom   = 'info@sfs-web.com';	
				$text		= (string)$text;
				foreach ($phones as $phone)
				{
					//phonenumber_username_code@smssend.bizzsms.nl 
					$phone 		= trim($phone);
					$recipient	= $phone.'_'.$username.'_'.$code.'@smssend.bizzsms.nl';																			
					JUtility::sendMail($mailFrom, $fromName, $recipient, $text, $text);
				}					
			}
			
		}
		
	}
	
	
}


