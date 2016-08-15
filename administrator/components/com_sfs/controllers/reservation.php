<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once JPATH_SITE.'/components/com_sfs/helpers/date.php';
require_once JPATH_SITE.'/components/com_sfs/helpers/sfs.php';
require_once JPATH_SITE.'/components/com_sfs/libraries/reservation.php';
require_once JPATH_SITE.'/components/com_sfs/libraries/voucher.php';
require_once JPATH_SITE.'/components/com_sfs/libraries/core.php';
require_once JPATH_SITE.'/components/com_sfs/models/handler.php';
class SfsControllerReservation extends JController
{

	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('issuevoucher', 'issueVoucher');
		$this->registerTask('anote', 'addNote');
		$this->registerTask('rnote', 'removeNote');		
	}

	public function issueVoucher()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model		= $this->getModel('Reservation','SfsModel');
		$link  = 'index.php?option=com_sfs&view=reservation&layout=issuevoucher&id='.JRequest::getInt('id');		
		$link .='&tmpl=component';
		
		if( $model->issueVoucher() ) {
			$this->setRedirect($link);	
		} else {
			$msg = $model->getError();
			$this->setRedirect($link,$msg);
		}		
		
		return true;		
	}
	
	public function changestatus()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$model		= $this->getModel('Reservation','SfsModel');
		
		$link = 'index.php?option=com_sfs&view=reservation&layout=editstatus&id='.JRequest::getInt('id');
		
		if ( JRequest::getVar('tmpl') =='component' ) {
			$link .='&tmpl=component';
		}
		if( $model->changeStatus() ) {
			$this->setRedirect($link);	
		} else {
			$msg = $model->getError();
			$this->setRedirect($link,$msg);
		}		
						
		return true;		
	}
	
	public function addNote()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$reservationId = (int)JRequest::getInt('reservation_id');

		if( $reservationId > 0 )
		{			 
			$db 	= JFactory::getDbo();
			$user 	= JFactory::getUser();
			$now	= JFactory::getDate()->toSql();
			
			$note 	= JRequest::getString('notes', '', 'post', JREQUEST_ALLOWHTML);	
			
			if(strlen($note) > 0)
			{
				$row = new stdClass();
				$row->notes = $note;
				$row->reservation_id = $reservationId;
				$row->created = $now;
				$row->created_by = $user->id;
				
				if( ! $db->insertObject('#__sfs_reservation_notes', $row) )
				{
					JError::raiseError('500', $db->getErrorMsg());
					return false;
				}
			}
			
		}
		$this->setRedirect('index.php?option=com_sfs&view=reservation&layout=notes&tmpl=component&id='.$reservationId);
		return;		
	}
	
	public function removeNote()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$reservationId = (int)JRequest::getInt('reservation_id');

		if( $reservationId > 0 )
		{			 
			$db 	= JFactory::getDbo();
			$noteid = JRequest::getInt('noteid');
			if($noteid)	{
				$query = 'DELETE FROM #__sfs_reservation_notes WHERE id='.$noteid;
				$db->setQuery($query);
				$db->query();
			}		
		}
		$this->setRedirect('index.php?option=com_sfs&view=reservation&layout=notes&tmpl=component&id='.$reservationId);
		return;		
	}
	
	public function sendFax()
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$reservationId = (int)JRequest::getInt('reservation_id');

		if( $reservationId > 0 )
		{			 
			$db = JFactory::getDbo();
			$user = JFactory::getUser();

			$row = new stdClass();
			$row->user_id  			= $user->id;
			$row->date	   			= JFactory::getDate()->toSql();
			$row->reservation_id   	= $reservationId;
			$row->status   			= 1;
			
			$query = 'SELECT a.hotel_id,a.blockcode,b.fax FROM #__sfs_reservations AS a INNER JOIN #__sfs_hotel AS b ON b.id=a.hotel_id WHERE a.id='.$row->reservation_id;
			$db->setQuery($query);
			$data = $db->loadObject();

			if($data)
			{
				$faxNumber 	= trim(sfsHelper::formatPhone( $data->fax, 1)).trim(sfsHelper::formatPhone( $data->fax, 2));
				if(!$faxNumber)
				{
					$faxNumber = $data->fax;
				}
				
				$faxAtt = JPATH_SITE.DS.'media'.DS.'sfs'.DS.'attachments'.DS.'faxblock'.$row->reservation_id.'.html';	

				ob_start();
				require_once $faxAtt;
				$bodyE = ob_get_clean();
												
				$sent = JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$faxNumber.'@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation '.$data->blockcode, $bodyE, true,null,null,$faxAtt);
				if($sent){
					$db->insertObject('#__sfs_admin_fax_tracks', $row);	
				}								
			}
			
		}
		$this->setRedirect('index.php?option=com_sfs&view=close&tmpl=component&reload=1');
		return;		
	}
	
	//lchung
	public function addvoucher(){
		
		$db = JFactory::getDbo();
		$session = JFactory::getSession();
		
		$reservationId = (int)JRequest::getInt('reservation_id');
		$flight_code = JRequest::getVar('flight_code');
		$iata_stranged_code = JRequest::getVar('iata_stranged_code');
		$stranded_seats = 0;
		$str_err = '';
		$session->set('datapost', JRequest::get() );
		if ( $flight_code == '' ) {
			$str_err = '&erroraddvoucher=error-flight-number';
		}
		elseif ( $iata_stranged_code == '' ) {
			$str_err = '&erroraddvoucher=error-iata-stranded-code';
		}
		else {
			$str_err = '&erroraddvoucher=';		
			$dRe = $this->getReservations( $db, $reservationId );
			$voucher_code = '';
			//single room
			if( (int)$dRe->s_room > 0 )
			{
				$stranded_seats = 1;
				for($i=0;$i<$dRe->s_room;$i++)
				{
					$voucher_code = $this->createCode(1, $reservationId);
				}
			}
			//single/double room
			if( (int)$dRe->sd_room > 0 )
			{
				$stranded_seats = 2;
				for($i=0;$i<$dRe->sd_room;$i++)
				{
					$voucher_code = $this->createCode(2, $reservationId);
				}
			}
			//triple room
			if( (int)$dRe->t_room > 0 )
			{
				$stranded_seats = 3;
				for($i=0;$i<$dRe->t_room;$i++)
				{
					$voucher_code = $this->createCode(3, $reservationId);
				}
			}
			//quad room
			if( (int)$dRe->q_room > 0 )
			{
				$stranded_seats = 4;
				for($i=0;$i<$this->qroom;$i++)
				{
					$voucher_code = $this->createCode(4, $reservationId);
				}
			}

			$this->saveVoucher( $voucher_code, $reservationId, $stranded_seats );
			$session->set('datapost', array() );
		}
		
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId . $str_err );
	}
	
	private function createCode($roomType=null, $booking_id)
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
                $query .= ' AND booking_id='.(int)$booking_id;
                $db->setQuery($query);
				if( ! $db->loadResult() ){
					$result = $tmpVoucher;
					break;
				}
            }
		}
		return $result;
	}
	
	public function saveVoucher( $voucher_code = '', $reservationId, $stranded_seats )
    {
        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        $user		= JFactory::getUser();
        $app		= JFactory::getApplication();
        $db 		= JFactory::getDbo();
        $date 	 	= JFactory::getDate();
        $reservation   = null;
        if( $reservationId ) {
            $reservation = SReservation::getInstance((int)$reservationId);
            if( ! $reservation->id ) {
                JFactory::getApplication()->close();
            }
        } else {
            JFactory::getApplication()->close();
        }
		
        $query  = 'SELECT a.id FROM #__sfs_voucher_codes AS a';
        $query .= ' WHERE a.booking_id='.(int) $reservation->id;
        $db->setQuery($query);

        $voucherId = $db->loadResult();
       
		//Step 1: Flight Seats
		$flight = new stdClass();

		$flight->seats 			= (int) $stranded_seats;//JRequest::getInt('stranded_seats');
		$flight->flight_code	= JRequest::getString('flight_code');
		$flight->delay_code 	= JRequest::getString('iata_stranged_code');
		$flight->airline_id   	= $reservation->airline_id;
		$flight->created_by  	= $user->id;
		$flight->created 		= $date->toSql();
		$flight->from_date		= $reservation->blockdate;
		$flight->end_date	 	= SfsHelperDate::getNextDate('Y-m-d', $flight->from_date);
		
		$db->insertObject('#__sfs_flights_seats', $flight) ;
		$flight->id = $db->insertId();
	 
		//Step 2: Voucher
		$voucherData = new stdClass();

		$voucherData->booking_id	= $reservationId;
		$voucherData->flight_id 	= $flight->id;
		$voucherData->created 		= $date->toSql();
		$voucherData->created_by 	= $user->id;

		if(floatval($reservation->breakfast) > 0) {
			$voucherData->breakfast = 1;
		}
		if(floatval($reservation->lunch) > 0) {
			$voucherData->lunch = 1;
		}
		if(floatval($reservation->mealplan) > 0) {
			$voucherData->mealplan = 1;
		}

		$voucherData->sroom   	= $reservation->s_room;
		$voucherData->sdroom  	= $reservation->sd_room;
		$voucherData->troom   	= $reservation->t_room;
		$voucherData->qroom   	= $reservation->q_room;
		$voucherData->room_type = $flight->seats;
		$voucherData->seats	 	= $flight->seats;
		$voucherData->vgroup 	= 0;
		$voucherData->code = $voucher_code;//JRequest::getString('voucher_code');
		
		
		//insert vourcher
		if( !$db->insertObject('#__sfs_voucher_codes',$voucherData) ) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$voucherId = $db->insertid();
		
		$query  = 'UPDATE #__sfs_reservations SET ';
		$query .= 'sd_room_issued=sd_room_issued+'.$voucherData->sdroom;
		$query .= ',t_room_issued=t_room_issued+'.$voucherData->troom;
		$query .= ',s_room_issued=s_room_issued+'.$voucherData->sroom;
		$query .= ',q_room_issued=q_room_issued+'.$voucherData->qroom;
		$query .= ' WHERE id='.$voucherData->booking_id;			
		$db->setQuery($query);
		$db->query();
		
        $mobileNumberExt = JRequest::getVar('passenger_mobile_ext');
        $mobileNumber 	= "+".$mobileNumberExt . JRequest::getVar('passenger_mobile');
        $comment		= JRequest::getString('comment');
        $comment = trim($comment);

        $updateFieldArray = array();

        $updateFieldArray[] = 'handled_date='.$db->quote(JFactory::getDate()->toSql());

        if( strlen($comment) > 0 )
        {
            $updateFieldArray[] = 'comment='.$db->Quote($comment);
        }

        if(count($updateFieldArray))
        {
            $query = 'UPDATE #__sfs_voucher_codes SET '.implode(',', $updateFieldArray).' WHERE id='.$voucherId;
            $db->setQuery($query);
            $db->query();
        }

        $returnflight 		= JRequest::getVar('returnflight');
        $returnflightdate 	= JRequest::getVar('returnflightdate');

        if($returnflight)
        {
            $row2 = new stdClass();
            $row2->voucher_id 		= $voucherId;
            $row2->flight_number 	= $returnflight;
            $row2->flight_date 		= $returnflightdate;            
           	$db->insertObject('#__sfs_voucher_return_flights', $row2);

        }
    }
	
	public function editpassengers(){
		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		$first_name = JRequest::getVar('first_name');
		$last_name = JRequest::getVar('last_name');
		$passengersId = (int)JRequest::getInt('id');
		$title = JRequest::getVar('title');
		$query = 'UPDATE #__sfs_passengers SET 
			title=' . $db->quote($title). ',
			first_name=' . $db->quote($first_name).',
			last_name=' . $db->quote($last_name).' WHERE id='.(int)$passengersId;
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function addnewpassengers(){
		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		$voucher_id = (int)JRequest::getInt('voucher_id');
		$d = $this->getVoucherCodes($db, $voucher_id);
		if ( $d ) {
			$voucher_id = $d->id;
			$room_type = $d->room_type;
			
		}
		else {
			$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
		}
		$VoucherRooms = $this->getVoucherRoomsId( $db, $voucher_id );
		$voucher_room_id = 0;
		$voucher_room_id = $VoucherRooms->voucher_room_id;
		
		$first_name = JRequest::getVar('first_name');
		$last_name = JRequest::getVar('last_name');
		$title = JRequest::getVar('title');
		
		$query = 'INSERT INTO #__sfs_passengers SET 
			voucher_id=' . $db->quote($voucher_id). ',
			room_type=' . $db->quote($room_type). ',
			voucher_room_id=' . $db->quote($voucher_room_id). ',
			title=' . $db->quote($title). ',
			first_name=' . $db->quote($first_name).',
			last_name=' . $db->quote($last_name);
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function addnewtracepassengers(){

		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		///$d = $this->getVoucherCodes($db, $reservationId);
		$voucher_id = (int)JRequest::getInt('voucher_id');
		$d = $this->getVoucherCodes($db, $voucher_id);
		if ( $d ) {
			$voucher_id = $d->id;
			$room_type = $d->room_type;
			
		}
		else {
			$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
		}
		$VoucherRooms = $this->getVoucherRoomsId( $db, $voucher_id );
		$voucher_room_id = 0;
		$voucher_room_id = $VoucherRooms->voucher_room_id;
		
		$first_name = JRequest::getVar('first_name');
		$last_name = JRequest::getVar('last_name');
		$title = JRequest::getVar('title');
		$phone_number = JRequest::getVar('phone_number');
		
		$query = 'INSERT INTO #__sfs_trace_passengers SET 
			voucher_id=' . $db->quote($voucher_id). ',
			room_type=' . $db->quote($room_type). ',
			voucher_room_id=' . $db->quote($voucher_room_id). ',
			title=' . $db->quote($title). ',
			first_name=' . $db->quote($first_name).',
			last_name=' . $db->quote($last_name) . ',
			phone_number=' . $db->quote($phone_number);
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function edittracepassengers(){
		
		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		$title = JRequest::getVar('title');
		$first_name = JRequest::getVar('first_name');
		$last_name = JRequest::getVar('last_name');
		$phone_number = JRequest::getVar('phone_number');
		$date 	 	= JFactory::getDate();
		
		$tracepassengersId = (int)JRequest::getInt('id');
		$query = 'UPDATE #__sfs_trace_passengers SET 
			title=' . $db->quote($title). ',
			first_name=' . $db->quote($first_name) . ',
			last_name=' . $db->quote($last_name) . ', 
			created_date=' . $db->quote($date->toSql()) . ', 
			created=' . $db->quote($date->toSql()) . ', 
			phone_number=' . $db->quote($phone_number) . ' WHERE id='.(int)$tracepassengersId;
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function editvouchercomments(){
		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		$comment = JRequest::getVar('comment');
		$voucher_codesId = (int)JRequest::getInt('id');
		$query = 'UPDATE #__sfs_voucher_codes SET 
			comment=' . $db->quote($comment) . ' WHERE id='.(int)$voucher_codesId;
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function delpassengers(){
		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		$passengersId = (int)JRequest::getInt('id');
		$query = 'DELETE FROM #__sfs_passengers WHERE id='.(int)$passengersId;
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function deltracepassengers(){
		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		$passengersId = (int)JRequest::getInt('id');
		$query = 'DELETE FROM #__sfs_trace_passengers WHERE id='.(int)$passengersId;
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function delvouchercomments(){
		$db = JFactory::getDbo();
		$reservationId = (int)JRequest::getInt('reservation_id');
		$comment = JRequest::getVar('comment');
		$voucher_codesId = (int)JRequest::getInt('id');
		$query = 'UPDATE #__sfs_voucher_codes SET comment="" WHERE id='.(int)$voucher_codesId;
		$db->setQuery($query);
		$db->query();
		$this->setRedirect('index.php?option=com_sfs&view=reservation&id=' . $reservationId);
	}
	
	public function getVoucherCodes( $db, $id ){
		$query = 'SELECT a.* FROM #__sfs_voucher_codes AS a';			
		///$query .= ' WHERE a.booking_id=' . $booking_id;	
		$query .= ' WHERE a.id=' . $id;				 
		$db->setQuery($query);	
		return $db->loadObject();
	}
	
	public function getVoucherRoomsId( $db, $voucher_id ){
		$query = 'SELECT a.* FROM #__sfs_voucher_rooms AS a';			
		$query .= ' WHERE a.voucher_id=' . $voucher_id;					 
		$db->setQuery($query);	
		return $db->loadObject();
	}
	
	public function getReservations( $db, $id ){
		$query = 'SELECT a.* FROM #__sfs_reservations AS a';			
		$query .= ' WHERE a.id=' . $id;					 
		$db->setQuery($query);	
		return $db->loadObject();
	}
	//End lchung
	 
}
