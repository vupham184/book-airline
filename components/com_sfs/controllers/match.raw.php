<?php
defined('_JEXEC') or die;

class SfsControllerMatch extends JControllerLegacy
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getModel($name = 'Match', $prefix = 'SfsModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function processPrintVoucher()
    {
        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        $user		= JFactory::getUser();
        $userId		= $user->get('id');

        // Make sure that only the airline can handle on the voucher printing
        if( ! SFSAccess::isAirline($user) ) {
            JFactory::getApplication()->close();
        }

        $app		= JFactory::getApplication();
        $db 		= JFactory::getDbo();
        $date 	 	= JFactory::getDate();
        $airline 	= SFactory::getAirline();
        $airport_current = $airline->getCurrentAirport();
        $airport_id = $airport_current->id;
        
        $voucherCode 	= JRequest::getVar('vouchercode');
        $voucherId	 	= JRequest::getInt('voucher_id');
        $passengers 	= JRequest::getVar('passengers', array() , 'post', 'array');
        $mobileNumberExt = JRequest::getVar('passenger_mobile_ext');
        $mobileNumber 	= "+".$mobileNumberExt . JRequest::getVar('passenger_mobile');
        $comment		= JRequest::getString('comment');

        $printtype 		= JRequest::getVar('printtype');
        $email	 		= JRequest::getVar('email');

        $separatevoucher  = JRequest::getInt('separatevoucher',0);

        $returnflight 		= JRequest::getVar('returnflight');
        $returnflightdate 	= JRequest::getVar('returnflightdate');

        $voucher 		= SVoucher::getInstance($voucherId,'id');
        $reservation = SReservation::getInstance((int)$voucher->booking_id);
        $isHotelAirline = SfsHelper::isHotelCreatedByAirline($reservation->hotel_id);

        if( ! $voucherId )
        {
            JFactory::getApplication()->close();
        }

        if($isHotelAirline){
            $claimed_rooms = (int)$reservation->s_room + (int)$reservation->sd_room + (int)$reservation->t_room + (int)$reservation->q_room;
            //$query = 'UPDATE #__sfs_reservations SET claimed_rooms='.$claimed_rooms.', status="T" WHERE id='.$reservation->id;
            $query = 'UPDATE #__sfs_reservations SET claimed_rooms='.$claimed_rooms.', status="O" WHERE id='.$reservation->id;
            $db->setQuery($query);
            $db->query();
        }


        //add comment for voucher
        $comment = trim($comment);

        $updateFieldArray = array();

        $updateFieldArray[] = 'handled_date='.$db->quote(JFactory::getDate()->toSql());

        if( strlen($comment) > 0 )
        {
            $updateFieldArray[] = 'comment='.$db->Quote($comment);
        }

        if( $printtype == 'print' )
        {
            $updateFieldArray[] = 'printed=1';
            $updateFieldArray[] = 'printed_date='.$db->quote(JFactory::getDate()->toSql());
        }
        if(count($updateFieldArray))
        {
            $query = 'UPDATE #__sfs_voucher_codes SET '.implode(',', $updateFieldArray).' WHERE id='.$voucher->id;
            $db->setQuery($query);
            $db->query();
        }

        if($returnflight){
            $expired_date = date('Y-m-d', strtotime($returnflightdate));
        }else{
            $expired_date = date('Y-m-d', strtotime($reservation->blockdate) + 24 * 60 * 60);
        }

        if( count($passengers) ) {
            $ap_meal_first  = JRequest::getVar('ap-meal-first');
            $ap_meal_second = JRequest::getVar('ap-meal-second');
            $ap_taxi = JRequest::getVar('ap-taxi');
            $ap_meal_first  = ($ap_meal_first)?1:0;
            $ap_meal_second = ($ap_meal_second)?1:0;
            if($ap_meal_first){
                $ap_meal = 1;
                $ap_meal_value = SfsHelper::calculateMealplanValue(1);
            }elseif($ap_meal_second){
                $ap_meal = 1;
                $ap_meal_value = SfsHelper::calculateMealplanValue(2);
            }else{
                $ap_meal = 0;
                $ap_meal_value = 0;
            }
            $ap_taxi = ($ap_taxi)?1:0;
            $ap_taxi_value = SfsHelper::calculateTaxiValue($reservation->hotel_id );

            $db->setQuery('DELETE cd, pa FROM #__sfs_airplusws_creditcard_detail AS cd
                        INNER JOIN #__sfs_passengers_airplus AS pa ON pa.id = cd.airplus_id
                        WHERE pa.voucher_id='.$voucher->id);
            $db->query();

            $db->setQuery('DELETE FROM #__sfs_trace_passengers WHERE voucher_id='.$voucher->id);
            $db->query();
            if($isHotelAirline){
                $db->setQuery('DELETE FROM #__sfs_passengers WHERE voucher_id='.$voucher->id);
                $db->query();
            }
            
            foreach ($passengers as $passenger)
            {
                if( !strlen($passenger['firstname']) && !strlen($passenger['lastname']) ) {
                    $passenger['firstname'] = "no names provided";
                }
                $passenger_ap = new stdClass();
                if(empty($airplus_id)) {
                    $airplus_id = $this->InsertPassengersAirplus(
                        $passenger_ap, $db, $airline, $reservation, $voucher, $userId,
                        $ap_meal, $ap_taxi, $expired_date
                    );
                }

                if($ap_meal)
                {
                    $ap_meal_unique_id = SfsHelper::generateVoucherUniqueID( $voucher->id );
                    $options = array(
                            'type' => 'meal',
                            'startdate' 	=> $passenger_ap->startdate,
                            'enddate' 	    => $passenger_ap->expiredate,
                            'flightnumber'	=> $passenger_ap->flight_number,
                            'pnr' 			=> $passenger_ap->pnr,
                            'unique_id'		=> $ap_meal_unique_id,
                            'reservation_id' => $reservation->id
                    );
                    $mealplan_card = SfsWs::airplusCall($reservation->airline_id, $userId, $options );

                    $airplus_voucher = $mealplan_card;
                    $creditcard_detail = new stdClass();
                    $creditcard_detail->airplus_id      = $airplus_id;
                    $creditcard_detail->cvc             = $airplus_voucher->CVC;
                    $creditcard_detail->card_number     = base64_encode($airplus_voucher->CardNumber);
                    $creditcard_detail->session_id      = $airplus_voucher->SessionId;
                    $creditcard_detail->type_of_service = "meal";
                    $creditcard_detail->valid_thru      = $airplus_voucher->ValidThru;
                    $creditcard_detail->valid_from      = $airplus_voucher->ValidFrom;
                    $creditcard_detail->passenger_name  = $passenger['firstname']." ".$passenger['lastname'];
                    $creditcard_detail->value           = $ap_meal_value;
                    $creditcard_detail->unique_id		= $ap_meal_unique_id;
                    if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $creditcard_detail)) {
                        exit('Could not create air+ meal');
                    }
                }

                if($ap_taxi && empty($airplus_card_taxi_id))
                {
                    $ap_taxi_unique_id = SfsHelper::generateVoucherUniqueID( $voucher->id );
                    $options = array(
                            'type' => 'taxi',
                            'startdate' 	=> $passenger_ap->startdate,
                            'enddate' 	    => $passenger_ap->expiredate,
                            'flightnumber'	=> $passenger_ap->flight_number,
                            'pnr' 			=> $passenger_ap->pnr,
                            'unique_id'		=> $ap_taxi_unique_id
                    );
                    $taxi_card = SfsWs::airplusCall($reservation->airline_id, $userId, $options);

                    $airplus_voucher = $taxi_card;
                    $creditcard_detail = new stdClass();
                    $creditcard_detail->airplus_id      = $airplus_id;
                    $creditcard_detail->cvc             = $airplus_voucher->CVC;
                    $creditcard_detail->card_number     = base64_encode($airplus_voucher->CardNumber);
                    $creditcard_detail->session_id      = $airplus_voucher->SessionId;
                    $creditcard_detail->type_of_service = "taxi";
                    $creditcard_detail->valid_thru      = $airplus_voucher->ValidThru;
                    $creditcard_detail->valid_from      = $airplus_voucher->ValidFrom;
                    $creditcard_detail->passenger_name  = $passenger['firstname']." ".$passenger['lastname'];
                    $creditcard_detail->value           = $ap_taxi_value;
                    $creditcard_detail->unique_id		= $ap_taxi_unique_id;
                    if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $creditcard_detail)) {
                        exit('Could not create taxi credit');
                    } else {
                        $airplus_card_taxi_id = $db->insertid();
                    }
                }
                $row = new stdClass();

                $row->voucher_id 	    = $voucher->id;
                $row->flight_number 	= $voucher->flight_code;
                $row->airport_id     	= $airport_id;
                $row->room_type 	    = $voucher->room_type;
                $row->first_name 	    = $passenger['firstname'];
                $row->last_name  	    = $passenger['lastname'];
                $row->type  		    = $passenger['type'];
                $row->title  		    = $passenger['title'];
                $row->phone_number 	    = $mobileNumber;
                $row->airplus_id 	    = $airplus_id;
                $row->created_date      = $date->toSql();

                $db->insertObject('#__sfs_trace_passengers', $row);
                if($isHotelAirline){
                    $db->insertObject('#__sfs_passengers', $row);
                }
            }
        } else {
        	# all passengers
        	if(!$separatevoucher) {
        		$query = $db->getQuery(true);
        		$query->select('p.*')
        			->from('#__sfs_trace_passengers as p')
        			->where('p.voucher_id='. (int)$voucherId);
        		
        		
        	}
        }

        if($separatevoucher == 1)
        {
            //create individual vouchers for group voucher
            $voucher->createIndividualVouchers();
            $voucher->updateIndividualVoucherForPassengers();
        }

        if($returnflight)
        {
            $row2 = new stdClass();
            $row2->voucher_id 		= $voucher->id;
            $row2->flight_number 	= $returnflight;
            $row2->flight_date 		= $returnflightdate;

            if( $voucher->return_flight_number )
            {
                $db->updateObject('#__sfs_voucher_return_flights', $row2, 'voucher_id');
            } else
            {
                $db->insertObject('#__sfs_voucher_return_flights', $row2, 'voucher_id');
            }

        }


        if($printtype =='email')
        {
            jimport('joomla.mail.helper');
            // Make sure the mail is correct
            if ( ! JMailHelper::isEmailAddress($email) )
            {
                echo '<div class="uk-alert uk-alert-danger">';
                echo 'Invalid Email';
                echo '</div>';
                JFactory::getApplication()->close();
            }
            // Send the voucher
            $sent = SEmail::guestVoucher( $email , $voucher );
            // Check for an error.
            if ( !$sent ) {
                echo '<div class="uk-alert uk-alert-danger">';
                echo JText::_('COM_SFS_MATCH_PASSENGER_SEND_MAIL_FAILED');
                echo '</div>';
                JFactory::getApplication()->close();
            }
            echo '<div class="uk-alert uk-alert-success">';
            echo JText::sprintf('COM_SFS_MATCH_PASSENGER_SEND_MAIL_SUSCCESS',$voucherCode,$email);
            echo '</div>';
        }

        if($printtype == 'taxi' || $printtype == 'returntaxi')
        {
            $taxicomment			= JRequest::getString('taxicomment');
            $taxireturncomment		= JRequest::getString('taxireturncomment');
            $taxi_voucher_id		= JRequest::getInt('taxi_voucher_id');
            $taxi_id				= JRequest::getInt('taxi_id');

            if( $taxicomment || $taxireturncomment )
            {
                $query = 'UPDATE #__sfs_taxi_vouchers SET comment='.$db->quote($taxicomment).', return_comment='.$db->quote($taxireturncomment);
                $query .= ' WHERE id='.(int)$taxi_voucher_id.' AND taxi_id='.(int)$taxi_id;
                $db->setQuery($query);
                $db->query();
            }

            if($printtype == 'taxi' && $taxi_voucher_id)
            {
                SEmail::emailAndFaxToTaxi($taxi_voucher_id);
            }

            if( $printtype == 'returntaxi' )
            {
                $query = 'UPDATE #__sfs_taxi_vouchers SET return_printed = 1, return_printed_date='.$db->quote(JFactory::getDate()->toSql());
            } else {
                $query = 'UPDATE #__sfs_taxi_vouchers SET printed = 1, printed_date='.$db->quote(JFactory::getDate()->toSql());
            }

            $query .= ' WHERE id='.(int)$taxi_voucher_id.' AND taxi_id='.(int)$taxi_id;
            $db->setQuery($query);
            $db->query();
        }

        JFactory::getApplication()->close();
    }


    public function processPrintSingleVoucher()
    {
        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        $user		= JFactory::getUser();

        // Make sure that only the airline can handle on the voucher printing
        if( ! SFSAccess::isAirline($user) ) {
            JFactory::getApplication()->close();
        }

        $userId		= $user->get('id');
        $app		= JFactory::getApplication();
        $db 		= JFactory::getDbo();
        $date 	 	= JFactory::getDate();
        $airline 	= SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;

        $url_code 		= JRequest::getVar('url_code');
        $reservationId = JRequest::getInt('reservation_id');
        $reservation   = null;

        if( $reservationId ) {
            $reservation = SReservation::getInstance((int)$reservationId);
            if( ! $reservation->id ) {
                JFactory::getApplication()->close();
            }
        } else {
            JFactory::getApplication()->close();
        }

        if($reservation->airline_id != $airline->id) {
            JFactory::getApplication()->close();
        }

        $query  = 'SELECT a.id FROM #__sfs_voucher_codes AS a';
        $query .= ' WHERE a.booking_id='.(int) $reservation->id;
        $db->setQuery($query);
        $voucherId = $db->loadResult();

        /* @var $wsRoomType Ws_Do_Search_RoomTypeResult */
        /* @var $wsPreBook Ws_Do_PreBook_Response */
        /* @var $pc Ws_Do_PreBook_CancellationResponse */
        $wsRoomTypes = $reservation->getWsRoomTypes();
        $wsPreBooking = Ws_Do_PreBook_Response::fromString($reservation->ws_prebooking);

        if(!empty($wsRoomTypes)) {
            if(empty($wsPreBooking)) {
                JFactory::getApplication()->close();
            }

            $timeout = 15 * 60; // 15 mins pending only
            $checkTime = gmdate('Y-m-d H:i:s', time() - $timeout);
            if(empty($wsPreBooking->IssueTime) || $wsPreBooking->IssueTime < $checkTime) {
                echo '<div class="uk-alert uk-alert-danger">';
                echo 'Your pre-booking is pending too long. Please start booking again from search result list.';
                echo '</div>';
                JFactory::getApplication()->close();
            }
        }

        if( ! $voucherId )
        {
            //Step 1: Flight Seats
            $flight = new stdClass();

            $flight->seats 			= (int) JRequest::getInt('stranded_seats');
            $flight->flight_code	= JRequest::getString('flight_code');
            $flight->delay_code 	= JRequest::getString('iata_stranged_code');
            $flight->airline_id   	= $airline->id;
            $flight->created_by  	= $user->id;
            $flight->created 		= $date->toSql();
            $flight->from_date		= $reservation->blockdate;
            $flight->end_date	 	= SfsHelperDate::getNextDate('Y-m-d', $flight->from_date);

            $handlerModel = JModelLegacy::getInstance('Handler','SfsModel',array('ignore_request' => true));
            $flight->id   = (int) $handlerModel->addFlightsSeats($flight);

            if( $flight->id < 1 )
            {
                echo '<div class="uk-alert uk-alert-danger">';
                echo $handlerModel->getError();
                echo '</div>';
                JFactory::getApplication()->close();
            }

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
			$code = JRequest::getString('voucher_code');
			$model  	= $this->getModel();
			$voucher_groups_id = $model->InsertVoucherGroups($code, $flight->seats);
			$voucherData->voucher_groups_id   	= $voucher_groups_id;
            $voucherData->sroom   	= $reservation->s_room;
            $voucherData->sdroom  	= $reservation->sd_room;
            $voucherData->troom   	= $reservation->t_room;
            $voucherData->qroom   	= $reservation->q_room;
            $voucherData->room_type = $flight->seats;
            $voucherData->seats	 	= $flight->seats;
            $voucherData->vgroup 	= 0;
            $voucherData->code = $code;            
            $voucherId 	= $model->generateSingleVoucher($voucherData);

        }

        if( ! $voucherId )
        {
            echo '<div class="uk-alert uk-alert-danger">';
            echo 'Error while inserting voucher';
            echo '</div>';
            JFactory::getApplication()->close();
        }

        $passengers 	= JRequest::getVar('passengers', array() , 'post', 'array');
        $mobileNumberExt = JRequest::getVar('passenger_mobile_ext');
        $mobileNumber 	= "+".$mobileNumberExt . JRequest::getVar('passenger_mobile');
        $comment		= JRequest::getString('comment');

        $printtype 		= JRequest::getVar('printtype');
        $email	 		= JRequest::getVar('email');

        $returnflight 		= JRequest::getVar('returnflight');
        $returnflightdate 	= JRequest::getVar('returnflightdate');

        $voucher 		= SVoucher::getInstance($voucherId,'id');

        if( ! $voucher->id )
        {
            echo '<div class="uk-alert uk-alert-danger">';
            echo 'Error while inserting voucher';
            echo '</div>';
            JFactory::getApplication()->close();
        }

        $voucher->id = (int)$voucher->id;

        $comment = trim($comment);

        $updateFieldArray = array();

        $updateFieldArray[] = 'handled_date='.$db->quote(JFactory::getDate()->toSql());

        if( strlen($comment) > 0 )
        {
            $updateFieldArray[] = 'comment='.$db->Quote($comment);
        }

        if( $printtype == 'print' )
        {
            jimport('joomla.mail.helper');
            $mailPrint = "exampleHD@gmail.com";
            // Send the voucher
			
            $sent = SEmail::guestVoucher( $mailPrint , $voucher ); 
            // Check for an error.
            if ( !$sent ) {               
                JFactory::getApplication()->close();
            }            

            $updateFieldArray[] = 'printed=1';
            $updateFieldArray[] = 'printed_date='.$db->quote(JFactory::getDate()->toSql());
        }

        if( $printtype == 'sendSMS' )
        {
            jimport('joomla.mail.helper');
            $mailPrint = "exampleHD@gmail.com";
            // Send the voucher
            $sent = SEmail::guestVoucher( $mailPrint , $voucher );            
            // Check for an error.
            if ( !$sent ) {               
                JFactory::getApplication()->close();
            }                      
        }

        if(count($updateFieldArray))
        {
            $query = 'UPDATE #__sfs_voucher_codes SET '.implode(',', $updateFieldArray).' WHERE id='.$voucher->id;
            $db->setQuery($query);
            $db->query();
        }

        if( count($passengers) ) {

            $ap_meal_first  = JRequest::getVar('ap-meal-first');
            $ap_meal_second = JRequest::getVar('ap-meal-second');
            $ap_taxi = JRequest::getVar('ap-taxi');
            $ap_meal_first  = ($ap_meal_first)?1:0;
            $ap_meal_second = ($ap_meal_second)?1:0;
            if($ap_meal_first){
                $ap_meal = 1;
                $ap_meal_value = SfsHelper::calculateMealplanValue(1);
            }elseif($ap_meal_second){
                $ap_meal = 1;
                $ap_meal_value = SfsHelper::calculateMealplanValue(2);
            }else{
                $ap_meal = 0;
                $ap_meal_value = 0;
            }
            $ap_taxi = ($ap_taxi)?1:0;
            $ap_taxi_value = SfsHelper::calculateTaxiValue($reservation->hotel_id );
			
			//lchung
			if(!empty($wsRoomTypes)) {
            	$db->setQuery('DELETE FROM #__sfs_passengers WHERE voucher_id='.$voucher->id);
            	$db->query();
			}
			//End lchung
			
			$db->setQuery('DELETE FROM #__sfs_trace_passengers WHERE voucher_id='.$voucher->id);
            $db->query();
			

            $row3 = new stdClass();
            $row3->voucher_id = $voucher->id;
            $db->insertObject('#__sfs_voucher_rooms', $row3);
            $voucher_room_id = $db->insertid();
            if($returnflight){
                $expired_date = date('Y-m-d', strtotime($returnflightdate));
            }else{
                $expired_date = date('Y-m-d', strtotime($reservation->blockdate) + 24 * 60 * 60);
            }
            foreach ($passengers as $passenger)
            {
                if( strlen($passenger['firstname']) || strlen($passenger['lastname']) )
                {

                    ///if($ap_meal || $ap_taxi){
						$passenger_ap = new stdClass();
                    	if(empty($airplus_id)) {
							$airplus_id = $this->InsertPassengersAirplus(
								$passenger_ap, $db, $airline, $reservation, $voucher, $userId,
								$ap_meal, $ap_taxi, $expired_date
							);
                    	}
                    	
                        if($ap_meal)
                        {
                        	$ap_meal_unique_id = SfsHelper::generateVoucherUniqueID( $voucher->id );
                        	$options = array(
                        			'type' => 'meal',
                        			'startdate' 	=> $passenger_ap->startdate,
                        			'enddate' 	    => $passenger_ap->expiredate,
                        			'flightnumber'	=> $passenger_ap->flight_number,
                        			'pnr' 			=> $passenger_ap->pnr,
                        			'unique_id'		=> $ap_meal_unique_id,
                        			'reservation_id' => $reservation->id
                        	);
                        	$mealplan_card = SfsWs::airplusCall($reservation->airline_id, $userId, $options );
                        	
                            $airplus_voucher = $mealplan_card;
                            $creditcard_detail = new stdClass();
                            $creditcard_detail->airplus_id      = $airplus_id;
                            $creditcard_detail->cvc             = $airplus_voucher->CVC;
                            $creditcard_detail->card_number     = base64_encode($airplus_voucher->CardNumber);
                            $creditcard_detail->session_id      = $airplus_voucher->SessionId;
                            $creditcard_detail->type_of_service = "meal";
                            $creditcard_detail->valid_thru      = $airplus_voucher->ValidThru;
                            $creditcard_detail->valid_from      = $airplus_voucher->ValidFrom;
                            $creditcard_detail->passenger_name  = $passenger['firstname']." ".$passenger['lastname'];
                            $creditcard_detail->value           = $ap_meal_value;
                            $creditcard_detail->unique_id		= $ap_meal_unique_id;
                            if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $creditcard_detail)) {
                                exit('Could not create air+ meal');
                            }
                        }
                        if($ap_taxi && empty($airplus_card_taxi_id))
                        {
                        	$ap_taxi_unique_id = SfsHelper::generateVoucherUniqueID( $voucher->id );
                        	$options = array(
                        			'type' => 'taxi',
                        			'startdate' 	=> $passenger_ap->startdate,
                                    'enddate' 	    => $passenger_ap->expiredate,
                        			'flightnumber'	=> $passenger_ap->flight_number,
                        			'pnr' 			=> $passenger_ap->pnr,
                        			'unique_id'		=> $ap_taxi_unique_id
                        	);
                        	$taxi_card = SfsWs::airplusCall($reservation->airline_id, $userId, $options);
                        	
                            $airplus_voucher = $taxi_card;
                            $creditcard_detail = new stdClass();
                            $creditcard_detail->airplus_id      = $airplus_id;
                            $creditcard_detail->cvc             = $airplus_voucher->CVC;
                            $creditcard_detail->card_number     = base64_encode($airplus_voucher->CardNumber);
                            $creditcard_detail->session_id      = $airplus_voucher->SessionId;
                            $creditcard_detail->type_of_service = "taxi";
                            $creditcard_detail->valid_thru      = $airplus_voucher->ValidThru;
                            $creditcard_detail->valid_from      = $airplus_voucher->ValidFrom;
                            $creditcard_detail->passenger_name  = $passenger['firstname']." ".$passenger['lastname'];
                            $creditcard_detail->value           = $ap_taxi_value;
                            $creditcard_detail->unique_id		= $ap_taxi_unique_id;
                            if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $creditcard_detail)) {
                                exit('Could not create taxi credit');
                            } else {
                            	$airplus_card_taxi_id = $db->insertid();
                            }
                        }

                    /*}else{
                        $airplus_id = 0;
                    }*/

                    $row = new stdClass();

                    $row->voucher_id 	    = $voucher->id;
                    $row->flight_number 	= JRequest::getString('flight_code');
                    $row->airport_id     	= $airport_current_id;
                    $row->room_type 	    = $voucher->room_type;
                    $row->first_name 	    = $passenger['firstname'];
                    $row->last_name  	    = $passenger['lastname'];
                    $row->type  		    = $passenger['type'];
                    $row->title  		    = $passenger['title'];
                    $row->voucher_room_id  	= $voucher_room_id;
                    $row->phone_number 	    = $mobileNumber;
                    $row->airplus_id 	    = $airplus_id;
                    $row->created_date      = $date->toSql();
					$row->created			= $date->toSql();
					///print_r( $row );die;
                    $db->insertObject('#__sfs_trace_passengers', $row);

					//lchung
					if(!empty($wsRoomTypes)) {
						$db->insertObject('#__sfs_passengers', $row);
					}
					//End lchung
					
                }
            }
        }

        if($returnflight)
        {
            $row2 = new stdClass();
            $row2->voucher_id 		= $voucher->id;
            $row2->flight_number 	= $returnflight;
            $row2->flight_date 		= $returnflightdate;

            if( $voucher->return_flight_number )
            {
                $db->updateObject('#__sfs_voucher_return_flights', $row2, 'voucher_id');
            } else
            {
                $db->insertObject('#__sfs_voucher_return_flights', $row2, 'voucher_id');
            }

        }

        # ws booking process --------------------------------------------

        if(!empty($wsRoomTypes)) {

            $passengers = JRequest::getVar('passengers', array());
            $guests = array();
            $j = -1;
            $i = -1;
            $sroom = 0;
            $sdroom = 0;
            $troom = 0;
            $qroom = 0;
            foreach($wsRoomTypes as $wsRoomType) {
                switch($wsRoomType->NumAdultsPerRoom)
                {
                    case 4: $qroom += 1; break;
                    case 3: $troom += 1; break;
                    case 2: $sdroom += 1; break;
                    default: $sroom += 1;
                }
                for($k =0; $k < $wsRoomType->NumberOfRooms; $k++) {
                    $i++;
                    $guests[$i] = array();
                    for($t = 0; $t < $wsRoomType->NumAdultsPerRoom; $t++) {
                        $j++;
                        $guests[$i][] = array(
                            'FirstName' => $passengers[$j]['firstname'],
                            'LastName' => $passengers[$j]['lastname'],
                            'Title' => $passengers[$j]['title'],
                        );
                    }
                }
            }
            $blockCode = $reservation->blockcode;
            $bookResult = SfsWs::book($wsRoomTypes, $wsPreBooking, $guests, $blockCode);
            #print_r($bookResult);die();
            if(empty($bookResult)){
                echo '<div class="uk-alert uk-alert-danger">';
                echo 'Error while booking webservice. Please try again or contact your administrator!';
                echo '</div>';
                JFactory::getApplication()->close();
            }

            $reservation->ws_booking = $bookResult->toString();
            $reservation->status = 'A'; # change reservation status to Approved

            /* @var $reservation SReservation */
            # update status
            $updateQuery  = 'UPDATE #__sfs_reservations SET ';
            $updateQuery .= 'status=' . $db->quote($reservation->status);
            $updateQuery .= ',s_room='.$sroom;
            $updateQuery .= ',sd_room='.$sdroom;
            $updateQuery .= ',t_room='.$troom;
            $updateQuery .= ',q_room='.$qroom;
            $updateQuery .= ',ws_booking='. $db->quote($reservation->ws_booking);
            $updateQuery .= ' WHERE id='.$reservation->id;
            #die($updateQuery);
            $db->setQuery($updateQuery);
            if(!$db->query()){
                throw new Exception($db->getErrorMsg());
            }
        }
        else
        {
            # update url_code
            $updateQuery  = 'UPDATE #__sfs_reservations SET ';
            $updateQuery .= 'url_code='. $db->quote($url_code);
            $updateQuery .= ' WHERE id='.$reservation->id;

            $db->setQuery($updateQuery);
            if(!$db->query()){
                throw new Exception($db->getErrorMsg());
            }
        }
        # ws booking process --------------------------------------------

        if($printtype =='email')
        {
            jimport('joomla.mail.helper');
            // Make sure the mail is correct
            if ( ! JMailHelper::isEmailAddress($email) )
            {
                echo '<div class="uk-alert uk-alert-danger">';
                echo 'Invalid Email';
                echo '</div>';
                JFactory::getApplication()->close();
            }
            // Send the voucher
            $sent = SEmail::guestVoucher( $email , $voucher );            
            // Check for an error.
            if ( !$sent ) {
                echo '<div class="uk-alert uk-alert-danger">';
                echo JText::_('COM_SFS_MATCH_PASSENGER_SEND_MAIL_FAILED');
                echo '</div>';
                JFactory::getApplication()->close();
            }
            echo '<div class="uk-alert uk-alert-success">';
            echo JText::sprintf('COM_SFS_MATCH_PASSENGER_SEND_MAIL_SUSCCESS',$voucher->code,$email);
            echo '</div>';
        }

        if($printtype =='ws') {            
            echo '<div class="uk-alert uk-alert-success">';
            echo 'You have sucessfully booked for the rooms';
            echo '<br/>';
            echo 'Here is the booking reference number: ' . $bookResult->BookingReference;
            echo '</div>';
        }
        $sess 	 = JFactory::getSession();
        $sess->clear('single_voucher');

        //Create voucher type html

        $model = parent::getModel( 'Voucher', 'SfsModel' );//JModel::getInstance( 'Voucher', 'SfsModel' );
        $model->setState('voucher.voucher_id', $voucherId);

        $voucher 		= $model->getVoucher();
        $hotel = $model->getVoucherHotel();
        $trace_passengers   = $voucher->getTracePassengers();


        if( count($trace_passengers) )
        {
            $nameArray = array();
            foreach($trace_passengers as $trace_passenger) {
                $name = $trace_passenger->first_name." ".$trace_passenger->last_name ;
                $nameArray[] = trim($name);
                $nameText = implode(', ', $nameArray);
            }
        }

        $params = JComponentHelper::getParams('com_sfs');
        $system_currency = $params->get('sfs_system_currency','EUR');

        $airline = SFactory::getAirline();

        jimport('joomla.filesystem.file');

        ob_start();
        require_once JPATH_COMPONENT.'/libraries/vouchersms.php';
        $voucherBody = ob_get_clean();
        $voucherAtt 		= JPATH_SITE.DS.'v'.DS.$url_code.'.html';
        JFile::write($voucherAtt, $voucherBody);
        chmod($voucherAtt, 0777);

        JFactory::getApplication()->close();
    }

    public function processPrintWsVoucher()
    {

        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        $user		= JFactory::getUser();
		$userId		= $user->get('id');
        $airline    = SFactory::getAirline();
        $session    = JFactory::getSession();

        // Make sure that only the airline can handle on the voucher printing
        if( ! SFSAccess::isAirline($user) ) {
            JFactory::getApplication()->close();
        }

        $app		= JFactory::getApplication();
        $db 		= JFactory::getDbo();
        $date 	 	= JFactory::getDate();
        $airline 	= SFactory::getAirline();
        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;

        $reservationId = JRequest::getInt('reservation_id');
        $url_code	 = JRequest::getVar('url_code');

        if($reservationId == -1)
        {
            $reservation = unserialize($session->get("reservation_temp"));
            $reservation_object = $reservation;
            $session->clear("reservation_temp");
            unset($reservation_object->room_date);

            if ( ! $db->insertObject('#__sfs_reservations', $reservation_object) )
            {
                $error =  JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg());
                $this->setError($error);
                return false;
            }
            $reservationId = $db->insertid();
            $reservation->id = $reservationId;
            $session->set("reservation_temp", serialize($reservation));
        }
        else{
            $reservation   = null;

            if( $reservationId ) {
                $reservation = SReservation::getInstance((int)$reservationId);
                if( ! $reservation->id ) {
                    JFactory::getApplication()->close();
                }
            } else {
                JFactory::getApplication()->close();
            }
        }




        if($reservation->airline_id != $airline->id) {
            JFactory::getApplication()->close();
        }

        if( SFSAccess::check($user, 'gh') )
        {
            if( (int)$airline->iatacode_id ) {
                $query = 'INSERT INTO #__sfs_gh_reservations(airline_id,reservation_id) VALUES('.(int)$airline->iatacode_id.','.$reservationId.')';
                $db->setQuery($query);

                if( ! $db->query() ) {
                    $error =  'GH: '.JText::sprintf('COM_SFS_BOOKING_PROCESSING_ERROR',$db->getErrorMsg());
                    $this->setError($error);
                    return false;
                }

            }
        }

        $query  = 'SELECT a.id FROM #__sfs_voucher_codes AS a';
        $query .= ' WHERE a.booking_id='.(int) $reservation->id;
        $db->setQuery($query);

        $voucherId = $db->loadResult();

        /* @var $wsRoomType Ws_Do_Search_RoomTypeResult */
        /* @var $wsPreBook Ws_Do_PreBook_Response */
        /* @var $pc Ws_Do_PreBook_CancellationResponse */
        $wsRoomTypes = $reservation->getWsRoomTypes();
        $wsPreBooking = Ws_Do_PreBook_Response::fromString($reservation->ws_prebooking);

        if(!empty($wsRoomTypes)) {
            if(empty($wsPreBooking)) {
                JFactory::getApplication()->close();
            }

            $timeout = 15 * 60; // 15 mins pending only
            $checkTime = gmdate('Y-m-d H:i:s', time() - $timeout);
            if(empty($wsPreBooking->IssueTime) || $wsPreBooking->IssueTime < $checkTime) {
                echo '<div class="uk-alert uk-alert-danger">';
                echo 'Your pre-booking is pending too long. Please start booking again from search result list.';
                echo '</div>';
                JFactory::getApplication()->close();
            }
        }

        if( ! $voucherId )
        {
            //Step 1: Flight Seats
            $flight = new stdClass();

            $flight->seats 			= (int) JRequest::getInt('stranded_seats');
            $flight->flight_code	= JRequest::getString('flight_code');
            $flight->delay_code 	= JRequest::getString('iata_stranged_code');
            $flight->airline_id   	= $airline->id;
            $flight->created_by  	= $user->id;
            $flight->created 		= $date->toSql();
            $flight->from_date		= $reservation->blockdate;
            $flight->end_date	 	= SfsHelperDate::getNextDate('Y-m-d', $flight->from_date);

            $handlerModel = JModelLegacy::getInstance('Handler','SfsModel',array('ignore_request' => true));
            $flight->id   = (int) $handlerModel->addFlightsSeats($flight);

            if( $flight->id < 1 )
            {
                echo '<div class="uk-alert uk-alert-danger">';
                echo $handlerModel->getError();
                echo '</div>';
                JFactory::getApplication()->close();
            }

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
			
			$code = JRequest::getString('voucher_code');
			$model  	= $this->getModel();
			$voucher_groups_id = $model->InsertVoucherGroups($code, $flight->seats);
			$voucherData->voucher_groups_id   	= $voucher_groups_id;
            $voucherData->sroom   	= $reservation->s_room;
            $voucherData->sdroom  	= $reservation->sd_room;
            $voucherData->troom   	= $reservation->t_room;
            $voucherData->qroom   	= $reservation->q_room;
            $voucherData->room_type = $flight->seats;
            $voucherData->seats	 	= $flight->seats;
            $voucherData->vgroup 	= 0;
            $voucherData->code = $code;

            $model  	= $this->getModel();
            $voucherId 	= $model->generateSingleVoucher($voucherData);
			
			if ( $code == '' ) {
				$query  = 'SELECT a.code FROM #__sfs_voucher_codes AS a';
				$query .= ' WHERE a.id='.(int) $voucherId;
				$db->setQuery($query);
				$code = $db->loadResult();
				
				$query  = 'UPDATE #__sfs_voucher_groups SET ';
				$query .= 'code="' . $code . '" WHERE id='.$voucher_groups_id;			
				$db->setQuery($query);
				$db->query();
			}

        }

        if( ! $voucherId )
        {
            echo '<div class="uk-alert uk-alert-danger">';
            echo 'Error while inserting voucher';
            echo '</div>';
            JFactory::getApplication()->close();
        }

        $passengers 	= JRequest::getVar('passengers', array() , 'post', 'array');
        $mobileNumberExt = JRequest::getVar('passenger_mobile_ext', array() , 'post', 'array');
        $mobileNumber 	= JRequest::getVar('passenger_mobile', array() , 'post', 'array');
        $comment		= JRequest::getString('comment');

        $printtype 		= JRequest::getVar('printtype');
        $email	 		= JRequest::getVar('email');

        $voucher 		= SVoucher::getInstance($voucherId,'id');

        if( ! $voucher->id )
        {
            echo '<div class="uk-alert uk-alert-danger">';
            echo 'Error while inserting voucher';
            echo '</div>';
            JFactory::getApplication()->close();
        }

        $voucher->id = (int)$voucher->id;

        $comment = trim($comment);

        $updateFieldArray = array();

        $updateFieldArray[] = 'handled_date='.$db->quote(JFactory::getDate()->toSql());

        if( strlen($comment) > 0 )
        {
            $updateFieldArray[] = 'comment='.$db->Quote($comment);
        }

        if( $printtype == 'print' )
        {
            $updateFieldArray[] = 'printed=1';
            $updateFieldArray[] = 'printed_date='.$db->quote(JFactory::getDate()->toSql());
        }
        if(count($updateFieldArray))
        {
            $query = 'UPDATE #__sfs_voucher_codes SET '.implode(',', $updateFieldArray).' WHERE id='.$voucher->id;
            $db->setQuery($query);
            $db->query();
        }

        if( count($passengers) ) {


            $db->setQuery('DELETE FROM #__sfs_trace_passengers WHERE voucher_id='.$voucher->id);
            $db->query();
			
			//lchung
			if(!empty($wsRoomTypes)) {
				$db->setQuery('DELETE FROM #__sfs_passengers WHERE voucher_id='.$voucher->id);
           		$db->query();
			}
			//End lchung
			
            for ($i = 0 ; $i < count($passengers) ; $i++)
            {
                $row3 = new stdClass();
                $row3->voucher_id = $voucher->id;
                $db->insertObject('#__sfs_voucher_rooms', $row3);
                $voucher_room_id = $db->insertid();

                $mobileNumber = "+".$mobileNumberExt[$i].$mobileNumber[$i];
                for($j = 0; $j < count($passengers[$i]); $j++)
                {
                    $passenger = $passengers[$i][$j];
                    if( strlen($passenger['firstname']) || strlen($passenger['lastname']) )
                    {
						$passenger_ap = new stdClass();
						if(empty($airplus_id)) {	
							$airplus_id = $this->InsertPassengersAirplus($passenger_ap, $db, $airline, $reservation, $voucher, $userId);
						}
                        $row = new stdClass();
                        $row->voucher_id 	= $voucher->id;
                        $row->flight_number = JRequest::getString('flight_code');
                        $row->airport_id 	= $airport_current_id;
                        $row->room_type 	= $voucher->room_type;
                        $row->first_name 	= $passenger['firstname'];
                        $row->last_name  	= $passenger['lastname'];
                        $row->type  		= $passenger['type'];
                        $row->title  		= $passenger['title'];
                        $row->phone_number 	= $mobileNumber;
                        $row->voucher_room_id 	= $voucher_room_id;
						$row->airplus_id      = $airplus_id;
						$row->created_date      = $date->toSql();
                        $db->insertObject('#__sfs_trace_passengers', $row);
						//lchung
						if(!empty($wsRoomTypes)) {
							$db->insertObject('#__sfs_passengers', $row);
						}
						//End lchung
                    }
                }
            }
        }

        $returnflight 		= JRequest::getVar('returnflight');
        $returnflightdate 	= JRequest::getVar('returnflightdate');

        if($returnflight)
        {
            $row2 = new stdClass();
            $row2->voucher_id 		= $voucher->id;
            $row2->flight_number 	= $returnflight;
            $row2->flight_date 		= $returnflightdate;

            if( $voucher->return_flight_number )
            {
                $db->updateObject('#__sfs_voucher_return_flights', $row2, 'voucher_id');
            } else
            {
                $db->insertObject('#__sfs_voucher_return_flights', $row2, 'voucher_id');
            }

        }

        # ws booking process --------------------------------------------

        if(!empty($wsRoomTypes)) {

            $passengers = JRequest::getVar('passengers', array());
            $guests = array();
            $sroom = 0;
            $sdroom = 0;
            $troom = 0;
            $qroom = 0;
            foreach($wsRoomTypes as $wsRoomType) {
                for($i =0; $i < $wsRoomType->NumberOfRooms; $i++) {
                    switch($wsRoomType->NumAdultsPerRoom)
                    {
                        case 4: $qroom += 1; break;
                        case 3: $troom += 1; break;
                        case 2: $sdroom += 1; break;
                        default: $sroom += 1;
                    }
                    $guests[$i] = array();
                    for($j = 0; $j < $wsRoomType->NumAdultsPerRoom; $j++) {
                        $guests[$i][] = array(
                            'FirstName' => $passengers[$i][$j]['firstname'],
                            'LastName' => $passengers[$i][$j]['lastname'],
                            'Title' => $passengers[$i][$j]['title'],
                        );
                    }
                }
            }
            $reservation->s_room = $sroom;
            $reservation->sd_room = $sdroom;
            $reservation->t_room = $troom;
            $reservation->q_room = $qroom;
            $claimed_rooms = $qroom + $troom + $sdroom + $sroom;
            $blockCode = $reservation->blockcode;
            $bookResult = SfsWs::book($wsRoomTypes, $wsPreBooking, $guests, $blockCode);
            #print_r($bookResult);die();
            if(empty($bookResult)){
                echo '<div class="uk-alert uk-alert-danger">';
                echo 'Error while booking webservice. Please try again or contact your administrator!';
                echo '</div>';
                JFactory::getApplication()->close();
            }



            $reservation->ws_booking = $bookResult->toString();
            $reservation->status = 'A'; # change reservation status to Approved

            /* @var $reservation SReservation */
            # update status
            $updateQuery  = 'UPDATE #__sfs_reservations SET ';
            $updateQuery .= 'status=' . $db->quote($reservation->status);
            $updateQuery .= ',s_room='.$sroom;
            $updateQuery .= ',sd_room='.$sdroom;
            $updateQuery .= ',t_room='.$troom;
            $updateQuery .= ',q_room='.$qroom;
            $updateQuery .= ',claimed_rooms='.$claimed_rooms;
            $updateQuery .= ',ws_booking='. $db->quote($reservation->ws_booking);
            $updateQuery .= ',url_code='. $db->quote($url_code);
            $updateQuery .= ' WHERE id='.$reservation->id;
            #die($updateQuery);
            $db->setQuery($updateQuery);
            if(!$db->query()){
                throw new Exception($db->getErrorMsg());
            }
			
			//lchung
			/* @var $reservation SReservation */
            # update status
            $updateQueryVc  = 'UPDATE #__sfs_voucher_codes SET ';
            $updateQueryVc .= 'sroom='.$sroom;
            $updateQueryVc .= ',sdroom='.$sdroom;
            $updateQueryVc .= ',troom='.$troom;
            $updateQueryVc .= ',qroom='.$qroom;
            $updateQueryVc .= ' WHERE booking_id='.$reservation->id;
            #die($updateQuery);
			$db->setQuery($updateQueryVc);
            if(!$db->query()){
                throw new Exception($db->getErrorMsg());
            }
			//End lchung
        }
        # ws booking process --------------------------------------------

        if($printtype =='email')
        {
            jimport('joomla.mail.helper');
            // Make sure the mail is correct
            if ( ! JMailHelper::isEmailAddress($email) )
            {
                echo '<div class="uk-alert uk-alert-danger">';
                echo 'Invalid Email';
                echo '</div>';
                JFactory::getApplication()->close();
            }
            // Send the voucher
            $sent = SEmail::guestVoucher( $email , $voucher );
            // Check for an error.
            if ( !$sent ) {
                echo '<div class="uk-alert uk-alert-danger">';
                echo JText::_('COM_SFS_MATCH_PASSENGER_SEND_MAIL_FAILED');
                echo '</div>';
                JFactory::getApplication()->close();
            }
            echo '<div class="uk-alert uk-alert-success">';
            echo JText::sprintf('COM_SFS_MATCH_PASSENGER_SEND_MAIL_SUSCCESS',$voucher->code,$email);
            echo '</div>';
        }

        if($printtype =='ws') {
            echo '<div class="uk-alert uk-alert-success">';
            echo 'You have sucessfully booked for the rooms';
            echo '<br/>';
            echo 'Here is the booking reference number: ' . $bookResult->BookingReference;
            echo '</div>';
        }
        $session->clear('single_voucher');

        //Create voucher type html

        $voucher_model = parent::getModel( 'Voucher', 'SfsModel' );//JModel::getInstance( 'Voucher', 'SfsModel' );
        $voucher_model->setState('voucher.voucher_id', $voucherId);

        $voucher 		= $voucher_model->getVoucher();
        $hotel          = $voucher_model->getVoucherHotel();
        $passengers     = $voucher->getPassengers();

        if( ! ($result = $this->reservationNotification($reservation)) )
        {
            return false;
        }

        if( count($passengers) )
        {
            $nameArray = array();
            foreach($passengers as $passenger) {
                $name = $passenger->first_name." ".$passenger->last_name ;
                $nameArray[] = trim($name);
                $nameText = implode(', ', $nameArray);
            }
        }

        $params = JComponentHelper::getParams('com_sfs');
        $system_currency = $params->get('sfs_system_currency','EUR');
        $wsBooking = $reservation->ws_booking;
        if(!empty($wsBooking)) {
            $wsBooking = Ws_Do_Book_Response::fromString($wsBooking);
        }


        jimport('joomla.filesystem.file');
        ob_start();
        require_once JPATH_COMPONENT.'/libraries/vouchersms_ws.php';
        $voucherBody = ob_get_clean();
        $voucherAtt  = JPATH_SITE.DS.'v'.DS.$url_code.'.html';
        JFile::write($voucherAtt, $voucherBody);
        chmod($voucherAtt, 0777);

        JFactory::getApplication()->close();

    }
	
	public function InsertPassengersAirplus( $passenger_ap, $db, $airline, $reservation, $voucher, $userId, $airplus_mealplan = 0, $airplus_taxi = 0, $expired_date = NULL )
	{
		$airplus_id = 0;					
		$passenger_ap->airplus_mealplan    = $airplus_mealplan;
		$passenger_ap->airplus_taxi        = $airplus_taxi;
		$passenger_ap->airline_id			= $airline->id;
		$passenger_ap->voucher_id			= $voucher->id;
		$passenger_ap->startdate			= $reservation->blockdate;
		$passenger_ap->expiredate			= $expired_date;
		$passenger_ap->user_id				= $userId;
		$passenger_ap->pnr					= $reservation->url_code;
		$passenger_ap->blockcode			= $reservation->blockcode;
		$passenger_ap->airport_code			= $reservation->airport_code;
		$passenger_ap->hotel_id				= $reservation->hotel_id;
		$passenger_ap->flight_number		= $voucher->flight_code;
		
		if (!$db->insertObject('#__sfs_passengers_airplus', $passenger_ap)) {
			exit('Could not create airplus');
		}else{
			$airplus_id = $db->insertid();
		}
		return $airplus_id;
	}
	
    public function bookBusTransport()
    {
        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        // Initialise some variables
        $app		= JFactory::getApplication();
        $user		= JFactory::getUser();

        //make sure that only the airline group can book the hotel.
        if( ! SFSAccess::isAirline($user) ) {
            JError::raiseError(403, JText::_('Sorry you can not access to this page'));
        }

        $db = JFactory::getDbo();

        $voucherId	 = JRequest::getInt('voucher_id');
        $voucher 	 = SVoucher::getInstance($voucherId,'id');

        if($voucher->id)
        {
            $query = 'SELECT COUNT(*) FROM #__sfs_voucher_busreservation_map WHERE voucher_id='.$voucher->id;
            $db->setQuery($query);
            $count = $db->loadResult();
            if( (int)$count > 0 )
            {
                echo '<div class="uk-alert uk-alert-danger">You have already booked Group Transport</div>';
                JFactory::getApplication()->close();
            }
            $model = parent::getModel( 'Transportbooking', 'SfsModel' );//JModel::getInstance('Transportbooking','SfsModel');
            if( !$model->booking($voucher->id) )
            {
                $error = $model->getError();
                echo '<div class="uk-alert uk-alert-danger">'.$error.'</div>';
            }
            echo '<div class="uk-alert uk-alert-success">You have booked group bus transportation for '.$voucher->seats.' persons</div>';
        } else {
            echo '<div class="uk-alert uk-alert-danger">Voucher does not available</div>';
        }
        JFactory::getApplication()->close();
    }

    public function savePassengerNames()
    {
        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        // Initialise some variables
        $app		= JFactory::getApplication();
        $user		= JFactory::getUser();

        //make sure that only the airline group can book the hotel.
        if( ! SFSAccess::isAirline($user) ) {
            JError::raiseError(403, JText::_('Sorry you can not access to this page'));
        }

        $db = JFactory::getDbo();

        ///$voucherId	 = JRequest::getInt('voucher_id');
		$voucherIds	 = JRequest::getVar('voucher_id');
		$voucherA = array();
		$voucherIdA = explode(",", $voucherIds);
		foreach ( $voucherIdA as $voucherId) {
        	$voucherA[]	 = SVoucher::getInstance($voucherId,'id');
		}

		$i = 0;
		$passengers = JRequest::getVar('passengers', array(), 'post', 'array');
		foreach ( $voucherA as $voucher ) {
			if($voucher->id)
			{            
				if( count($passengers) ) {
	
					$query = 'DELETE FROM #__sfs_trace_passengers WHERE voucher_id='.$voucher->id;
					$db->setQuery($query);
					$db->query();

                    $query = 'DELETE FROM #__sfs_voucher_rooms WHERE voucher_id='.$voucher->id;
	
					$db->setQuery($query);
					$db->query();
	
					///for ($i = 0 ; $i < count($passengers) ; $i++)
					///{
						$row3 = new stdClass();
						$row3->voucher_id = $voucher->id;
						$db->insertObject('#__sfs_voucher_rooms', $row3);
						$voucher_room_id = $db->insertid();
						$room_type 	= $passengers[$i]['room_type'];
						$phone_number 	= $passengers[$i]['phone_number'];
	
	
						for($j = 0; $j < count($passengers[$i]); $j++)
						{
							$passenger = $passengers[$i][$j];
							if( strlen($passenger['first_name']) || strlen($passenger['last_name']) )
							{
								$row = new stdClass();
	
								$row->voucher_id 	= $voucher->id;
								$row->room_type 	= $room_type;
								$row->first_name 	= $passenger['first_name'];
								$row->last_name  	= $passenger['last_name'];
								$row->type  		= $passenger['type'];
								$row->phone_number 	= $phone_number;
								$row->voucher_room_id 	= $voucher_room_id;
                                $result = $db->insertObject('#__sfs_trace_passengers', $row);
								
							}
						}
				   ///}
				}
				$i++;
			}//End foreach
        }
        JFactory::getApplication()->close();
    }

    protected function reservationNotification($reservation)
    {
        $params = JComponentHelper::getParams('com_sfs');

        $mail_communication = (int)$params->get('mail_communication' , 1);
        $fax_communication  = (int)$params->get('fax_communication' , 1);

        if($mail_communication == 0 && $fax_communication==0)
        {
            return true;
        }

        $user			= JFactory::getUser();
        $airline 		= SFactory::getAirline();


        if( $reservation ) {
            if( $reservation->association_id > 0 )
            {
                $association  = SFactory::getAssociation($reservation->association_id);
                $hotel = new SHotel($reservation->hotel_id,$association->db);
            } else {
                $hotel = SFactory::getHotel($reservation->hotel_id);
            }

            $currency = $hotel->getTaxes()->currency_symbol;
            $blockcode = $reservation->blockcode;
            $arrival_date = $reservation->date;
            $room_number = (int)$reservation->s_room +  (int)$reservation->sd_room + (int)$reservation->t_room + (int)$reservation->q_room;
            $sdroom_number = (int)$reservation->sd_room;
            $sdroom_rate = $currency . ' ' . floatval($reservation->sd_rate);
            $troom_number = (int)$reservation->t_room;
            $troom_rate = $currency . ' ' . floatval($reservation->t_rate);
            $sroom_number = (int)$reservation->s_room;
            $sroom_rate = $currency . ' ' . floatval($reservation->s_rate);
            $qroom_number = (int)$reservation->q_room;
            $qroom_rate = $currency . ' ' . floatval($reservation->q_rate);

            $breakfast = $reservation->breakfast;
            $breakfast_price = $currency . ' ' . $reservation->breakfast;
            $lunch = $reservation->lunch;
            $lunch_price = $currency . ' ' . $reservation->lunch;
            $mealplan = $reservation->mealplan;
            $dinner_price = $currency . ' ' . $reservation->mealplan;
            $course_menu = $reservation->course_type . ' course';

            $booked_contact = SFactory::getContact((int)$reservation->booked_by);
            $booked_name = $booked_contact->name . ' ' . $booked_contact->surname;
            $booked_title = $booked_contact->job_title;

            $airline_name = $airline->name;
            $airline_contact_name = $booked_contact->name . ' ' . $booked_contact->surname;
            $airline_contact_title = $booked_contact->job_title;
            $airline_contact_telephone = $booked_contact->telephone;
            $airline_contact_email = $booked_contact->email;

            $data_ws = $reservation->getWsRoomTypes();
            $data_ws = $data_ws[0];
            $is_ws = 'WS hotel';

            // hotel contacts
            $hotel_contacts = $hotel->getContacts();

            if (count($hotel_contacts)) {
                ob_start();
                require_once JPATH_COMPONENT . '/libraries/emails/hotelblockconfirm.php';
                $bodyE = ob_get_clean();

                $hotelEmailBody = JString::str_ireplace('[^]', '', $bodyE);
                $hotelEmailBody = JString::str_ireplace('{date}', '', $hotelEmailBody);

                $faxEmail = '';

                foreach ($hotel_contacts as $hotelContact) {
                    $hotelContactName = $hotelContact->name . ' ' . $hotelContact->surname;
                    $hotelEmailBody1 = JString::str_ireplace('{hotelcontact}', $hotelContactName, $hotelEmailBody);

                    if ($hotelContact->is_admin) {
                        $faxEmail = JString::str_ireplace('{hotelcontact}', $hotelContactName, $bodyE);
                    }
                    if (!empty($hotelContact->systemEmails)) {
                        $tmpRegistry = new JRegistry();
                        $tmpRegistry->loadString($hotelContact->systemEmails);

                        $sendBooking = (int)$tmpRegistry->get('booking', 0);

                        if ($sendBooking == 1) {
                            JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $hotelContact->email, 'SHORT TERM AIRPORT ROOM BLOCK RESERVATION  BY SFS-WEB', $hotelEmailBody1, true);
                        }
                    }
                }

                jimport('joomla.filesystem.file');

                // Email for Fax Service
                $faxNumber = trim(SfsHelper::formatPhone($hotel->fax, 1)) . trim(SfsHelper::formatPhone($hotel->fax, 2));
                $faxAtt = JPATH_SITE . DS . 'media' . DS . 'sfs' . DS . 'attachments' . DS . 'faxblock' . $reservation->id . '.html';
                $bodyE = JString::str_ireplace('{date}', JHtml::_('date', JFactory::getDate(), "d-M-Y"), $faxEmail);
                JFile::write($faxAtt, $bodyE);

                $hotelBackendSetting = $hotel->getBackendSetting();

                if ($fax_communication == 1) {
                    JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $faxNumber . '@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation ' . $blockcode, $bodyE, true, null, null, $faxAtt);
                    if ($second_fax = $hotelBackendSetting->second_fax) {
                        JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $second_fax . '@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation ' . $blockcode, $bodyE, true, null, null, $faxAtt);
                    }
                }
            }

            if ($mail_communication == 1) {

                // Email for Airline
                $hotel_name = $hotel->name;
                $airline_contacts = SFactory::getContacts($airline->grouptype, $airline->id);

                ob_start();
                require_once JPATH_COMPONENT . '/libraries/emails/airlineblockconfirm.php';
                $bodyE = ob_get_clean();

                JUtility::sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions', $user->email, 'Your SFS-web reservations details of ' . $arrival_date . ' booking ' . $hotel->name . ' ' . $blockcode, $bodyE, true);

                // private email for all correspondance
                if (SFSAccess::check($user, 'gh') && is_array($airline->params)) {
                    if (isset($airline->params['private_email'])) {
                        jimport('joomla.mail.helper');
                        $ghGeneralEmail = $airline->params['private_email'];

                        if (JMailHelper::isEmailAddress($ghGeneralEmail)) {
                            ob_start();
                            require_once JPATH_COMPONENT . '/libraries/emails/ghblock.php';
                            $bodyE = ob_get_clean();
                            JUtility::sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions', $ghGeneralEmail, 'A new block ' . $blockcode . ' made at SFS', $bodyE, true);
                        }
                    }
                }

                // Email for SFS Admins
                $adminEmails = $params->get('sfs_system_emails');

                if (strlen($adminEmails)) {

                    $data = $reservation;

                    $adminSubject = ' New Blockcode: ' . $room_number . ' rooms ' . $airline->airport_code . ' Hotel ' . $hotel_name . ' ' . $blockcode . ' Created';

                    $adminBlockcodeUrl = JURI::root() . 'administrator/index.php?option=com_sfs&view=reservation&id=' . $reservation->id;
                    $adminBlockcodeUrl = str_replace('https', ' http', $adminBlockcodeUrl);

                    ob_start();
                    require_once JPATH_COMPONENT . '/libraries/emails/admin/blockconfirm.php';
                    $adminBody = ob_get_clean();


                    $adminEmails = explode(';', $adminEmails);
                    if (count($adminEmails)) {
                        foreach ($adminEmails as $am) {
                            JUtility::sendMail('noreply@sfs-web.com', 'Stranded Flight Solutions', $am, $adminSubject, $adminBody, true);
                        }
                    }
                }

            }

            return true;
        }


        return false;
    }


}

