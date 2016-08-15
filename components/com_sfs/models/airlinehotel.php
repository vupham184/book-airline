<?php
defined('_JEXEC') or die();
// import Joomla modelform library
jimport('joomla.application.component.model');

class SfsModelAirlineHotel extends JModel
{

    protected function populateState()
    {
        $app = JFactory::getApplication('site');
        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);
    }

    /**
     * Method to save the hotel data in to database.
     *
     * This is the first step. Therefore an joomla user account, a hotel and a main contact must be saved.
     *
     * @param	array		The post data.
     * @return	mixed		The hotel id on success, false on failure.
     */
    public function getData()
    {

        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = $db->getQuery(true);
        $airline = SFactory::getAirline();

        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;

        $query->select('h.id as hotel_id,h.name, h.ws_id, h.star,h.address, h.telephone,h.billing_id,h.geo_location_latitude,h.geo_location_longitude');
        $query->from('#__sfs_hotel AS h ');

        if ((int)$airport_current_id != -1) {
            $query->innerJoin('#__sfs_hotel_airports AS a ON a.hotel_id=h.id AND a.airport_id=' . $airport_current_id);
        }

        $query->leftJoin('#__sfs_hotel_taxes AS t ON t.hotel_id=h.id');

        //join to currency
        $query->select('c.symbol AS currency_symbol');
        $query->leftJoin('#__sfs_currency AS c ON c.id=t.currency_id');


        // join to sfs_hotel_mealplans
        $query->select('m.bf_standard_price, m.bf_layover_price, m.course_1, m.course_2, m.course_3, m.service_hour, m.service_opentime, m.service_closetime, m.stop_selling_time, m.bf_service_hour, m.bf_opentime, m.bf_closetime, m.available_days, m.lunch_available_days');
        $query->select('m.lunch_standard_price, m.lunch_service_hour, m.lunch_opentime, m.lunch_closetime');

        $query->leftJoin('#__sfs_hotel_mealplans AS m ON m.hotel_id=h.id');

        //join to sfs_hotel_backend_params
        $query->select('hbp.single_room_available, hbp.quad_room_available');
        $query->leftJoin('#__sfs_hotel_backend_params AS hbp ON hbp.hotel_id=h.id');
        if ($airline->grouptype == 3)
        {
            $query->innerJoin('#__sfs_airline_user_map AS aum ON aum.user_id=h.created_by');
            $query->where('aum.airline_id='.(int)$airline->id);
        }else{
            $query->where('h.created_by='.$user->id);
        }
        $query->where('h.block=0');


        $db->setQuery($query);
//        echo $query; die();
        $hotels = $db->loadObjectList();
//        var_dump($hotels);die();
        return $hotels;

    }

    public function getHotel(){
        $hotelId = $this->state->get('hotel.id');
        $hotel = SFactory::getHotel($hotelId);
        $airline = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
        $date = SfsHelperDate::getDate('now','Y-m-d', $time_zone);

        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('s_rate, sd_rate, t_rate, q_rate');
        $query->from('#__sfs_contractedrates');
        $query->where('airline_id ='.(int)$airline->id." AND hotel_id=".$hotelId." AND date= ".$db->quote($date));

        $db->setQuery($query);
        $contractedRates = $db->loadObject();
        if($contractedRates){
            $hotel->isContractedRates = true;
            $hotel->s_rate = $contractedRates->s_rate;
            $hotel->sd_rate = $contractedRates->sd_rate;
            $hotel->t_rate = $contractedRates->t_rate;
            $hotel->q_rate = $contractedRates->q_rate;
            $hotel->currency_symbol = $hotel->getTaxes()->currency_symbol;
        }
        return $hotel;
    }

    public function addHotel( $hotel_data, $rooms, $mealplan )
    {
        $app	= JFactory::getApplication();
        $user = JFactory::getUser();
        $db = $this->getDbo();
        // Store hotel
        $hotel = new SHotel();
        $hotelData = array();
        $hotelData['name']		    = $hotel_data['name'];
        $hotelData['address']	    = $hotel_data['address'];
        $hotelData['city']	        = $hotel_data['city'];
        $hotelData['telephone']	    = $hotel_data['phone'];
        $hotelData['fax']	        = SfsHelper::getPhoneString($hotel_data['fax_code'],$hotel_data['fax_number']);
        $hotelData['star']		    = $hotel_data['star'];
        $hotelData['systemEmails']= '{"0":"'.$hotel_data['email'].'"}';
        $hotelData['created_by']    = $user->id;
        $hotelData['modified_by']   = $user->id;
        $hotelData['block']		    = 0;

        $address = $hotel_data['address']." ".$hotel_data['city'];
        $latlng = SfsUtil::getLatLng($address);
        if($latlng)
        {
            $hotelData['geo_location_latitude'] = $latlng['lat'];
            $hotelData['geo_location_longitude'] = $latlng['lng'];
        }

        if ( ! $hotel->bind( $hotelData ) ) {
            $this->setError($hotel->getError());
            return false;
        }

        if( ! $hotel->save() ) {
            $this->setError($hotel->getError());
            return false;
        }

        //Store taxes
        $taxesData = array();
        $taxesData['hotel_id'] = $hotel->id;
        $taxesTable = JTable::getInstance('HotelTax', 'JTable');

        if (!$taxesTable->bind($taxesData)) {
            $this->setError('Hotel Finance bind failed');
            return false;
        }

        if (!$taxesTable->check($taxesData)) {
            $this->setError('Hotel Finance check failed');
            return false;
        }

        if (!$taxesTable->store()) {
            $this->setError('Hotel Finance store failed');
            return false;
        }


        //Store hotel_airport
        $hotelAirport 			= JTable::getInstance('HotelAirport', 'JTable');
        $hotelAirportData = array();

        $airline_current = SAirline::getInstance()->getCurrentAirport();
        $airport_current_id = $airline_current->id;

        $hotelAirportData['hotel_id'] = $hotel->id;
        $hotelAirportData['airport_id'] = $airport_current_id;

        if( !$hotelAirport->bind($hotelAirportData) ){
            $this->setError($hotelAirport->getError());
            return false;
        }

        if( !$hotelAirport->store() ){
            $this->setError($hotelAirport->getError());
            return false;
        }

        //Store Mealplan
        $mealplanTable = JTable::getInstance('HotelMealplan', 'JTable');
        $mealplanData = array();
        $mealplanData['hotel_id'] = $hotel->id;

        if($mealplan['bf_standard_price'] > 0)
        {
            $mealplanData['bf_standard_price'] = $mealplan['bf_standard_price'];
            $mealplanData['bf_layover_price'] = $mealplan['bf_standard_price'];
            $mealplanData['bf_tax'] = $mealplan['bf_standard_price'];
        }

        if($mealplan['lunch_standard_price'] > 0)
        {
            $mealplanData['lunch_standard_price'] = $mealplan['lunch_standard_price'];
            $mealplanData['lunch_service_hour'] = 0;
        }


        if($mealplan['dinner_standard_price'] > 0)
        {
            $mealplanData['course_1'] = $mealplan['dinner_standard_price'];
        }
        $mealplanData['quoted_menu_price'] = 'gross';
        $mealplanData['service_hour'] = 0;
        $mealplanData['available_days'] = '1,2,3,4,5,6,7';
        $mealplanData['lunch_available_days'] = '1,2,3,4,5,6,7';

        if( !$mealplanTable->bind($mealplanData) ){
            $this->setError($mealplanTable->getError());
            return false;
        }

        if( ! $mealplanTable->store()) {
            $this->setError('Hotel mealplan save failed');
            return false;
        }

        //make a reservation
        if(!((int)$rooms['sroom'] == 0 && (int)$rooms['sdroom'] == 0 && (int)$rooms['troom'] == 0 && (int)$rooms['qroom'] == 0) )
        {
            $this->reservation($hotel->id, $rooms);
        }

        $db->setQuery(
            'INSERT INTO #__sfs_hotel_backend_params (hotel_id, ring, single_room_available, quad_room_available)' .
            ' VALUES ('.$hotel->id.', 1, 1, 1)'
        );
        $db->query();

        $db->setQuery(
            'INSERT INTO #__sfs_hotel_user_map (hotel_id, user_id)' .
            ' VALUES ('.$hotel->id.', '.$user->id.')'
        );
        $db->query();


        //None of the above When none of the above is selected the SFS administrator
        //should receive a warning per email to check the loading on this hotel on chain affiliation.
        $sfsParams 	 = JComponentHelper::getParams('com_sfs');
        $adminEmails = $sfsParams->get('sfs_system_emails');
        $config = JFactory::getConfig();
        $data['fromname']	= $config->get('fromname');
        $data['mailfrom']	= $config->get('mailfrom');
        $data['sitename']	= $config->get('sitename');
        if( strlen($adminEmails) )
        {
            $sendEmails = explode(';', $adminEmails);
            if (count($sendEmails) > 0) {
                $emailSubject	= JText::sprintf(
                    'COM_SFS_ADMIN_NOTIFICALTION_HOTEL_REGISTER_SUBJECT',
                    $hotelData['name'],
                    $data['sitename']
                );
                $hotelAdminUrl = JURI::base().'administrator/index.php?option=com_sfs&task=hotel.edit&id='.(int)$hotel->id;
                $emailBody = JText::sprintf(
                    'COM_SFS_ADMIN_NOTIFICALTION_HOTEL_REGISTER_BODY',
                    $hotelAdminUrl
                );
                foreach ($sendEmails as $adminEmail) {
                    JUtility::sendMail( $data['fromname'], $data['mailfrom'], $adminEmail, $emailSubject , $emailBody );
                }
            }
        }

        return true;
    }

    public function getContact()
    {
        $user = JFactory::getUser();
        $db	= $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('c.name, c.surname, c.telephone');
        $query->from('#__sfs_contacts AS c');
        $query->where('c.user_id = '.(int)$user->id);
        $db->setQuery($query);
        $contact = $db->loadObject();

        if ($error = $db->getErrorMsg()) {
            $this->setError('Sql Error or User was not found');
            return false;
        }

        return $contact;
    }

    public function getMealplan($hotelId)
    {
        $db = $this->getDbo();
        $query = 'SELECT * FROM #__sfs_hotel_mealplans WHERE hotel_id='.$hotelId;
        $db->setQuery($query);
        $result = $db->loadObject();

        return $result;
    }

    public function reservation($hotelId, $rooms)
    {
        $user 			    = JFactory::getUser();
        $hotel              = SFactory::getHotel($hotelId);
        $hotelSetting   = $hotel->getBackendSetting();
        $tmpRegistry        = new JRegistry();
        $tmpRegistry->loadString($hotel->systemEmails);
        $emails = $tmpRegistry->toArray();
        $airline            = SFactory::getAirline();
        $mealplan           = $this->getMealplan($hotelId);
		
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
		
        //Create new inventory
        $inventory 			= JTable::getInstance('Inventory', 'JTable');
        $reservation 	    = JTable::getInstance('Reservation', 'JTable');

        $reservationData = array();
        $inventoryData = array();

        $inventoryData['hotel_id'] = $hotelId;

        if( $rooms['srate'] > 0 ) {
            $reservationData['s_room']          = (int)$rooms['sroom'];
            $inventoryData['booked_sroom']      = (int)$rooms['sroom'];
        } else {
            $reservationData['s_room']          = 0;
            $inventoryData['booked_sroom']      = 0;
        }
        if( $rooms['sdrate'] > 0 ) {
            $reservationData['sd_room']         = (int)$rooms['sdroom'];
            $inventoryData['booked_sdroom']     = (int)$rooms['sdroom'];
        } else {
            $reservationData['sd_room']         = 0;
            $inventoryData['booked_sdroom']     = 0;
        }
        if( $rooms['trate'] > 0 ) {
            $reservationData['t_room']          = (int)$rooms['troom'];
            $inventoryData['booked_troom']      = (int)$rooms['troom'];
        } else {
            $reservationData['t_room']          = 0 ;
            $inventoryData['booked_troom']      = 0;
        }
        if( $rooms['qrate'] > 0 ) {
            $reservationData['q_room']          = (int)$rooms['qroom'];
            $inventoryData['booked_qroom']      = (int)$rooms['qroom'];
        } else {
            $reservationData['q_room']          = 0 ;
            $inventoryData['booked_qroom']      = 0;
        }



        $reservationData['s_rate']      = floatval($rooms['srate']);
        $reservationData['sd_rate']     = floatval($rooms['sdrate']);
        $reservationData['t_rate']      = floatval($rooms['trate']);
        $reservationData['q_rate']      = floatval($rooms['qrate']);

        $inventoryData['s_room_rate']            = floatval($rooms['srate']);
        $inventoryData['s_room_rate_modified']   = floatval($rooms['srate']);
        $inventoryData['sd_room_rate']           = floatval($rooms['sdrate']);
        $inventoryData['sd_room_rate_modified']  = floatval($rooms['sdrate']);
        $inventoryData['t_room_rate']            = floatval($rooms['trate']);
        $inventoryData['t_room_rate_modified']   = floatval($rooms['trate']);
        $inventoryData['q_room_rate']            = floatval($rooms['qrate']);
        $inventoryData['q_room_rate_modified']   = floatval($rooms['qrate']);

        $inventoryData['date'] = date("Y-m-d");
        $inventoryData['created_by'] = $user->id;
        $inventoryData['created'] = JFactory::getDate()->toSql();

        if( !$inventory->bind($inventoryData) ){
            $this->setError($inventory->getError());
            return false;
        }

        if( !$inventory->store() ){
            $this->setError($inventory->getError());
            return false;
        }

        $reservationData['room_id']     = $inventory->id;
        $reservationData['s_rate']      = floatval($rooms['srate']);
        $reservationData['sd_rate']     = floatval($rooms['sdrate']);
        $reservationData['t_rate']      = floatval($rooms['trate']);
        $reservationData['q_rate']      = floatval($rooms['qrate']);

        $reservationData['booked_date'] = JFactory::getDate()->toSql();
        $reservationData['blockdate']   = date("Y-m-d");
        $reservationData['booked_by']   = $user->id;
        $reservationData['hotel_id']    = $hotelId;
        $reservationData['airline_id']  = $airline->id;
        $start_date = SfsHelperDate::getDate('now','dmy', $time_zone);
        $reservationData['blockcode']   = SReservation::generateBlockCode($airline->code, $hotel->name, $start_date);
        $reservationData['airport_code'] = $airline->airport_code;
        $reservationData['payment_type'] = "airline";
        $reservationData['status']      = "O";
        $reservationData['breakfast']   = $mealplan->bf_layover_price;
        $reservationData['lunch']       = $mealplan->lunch_standard_price;
        $reservationData['mealplan']    = $mealplan->course_1;
        if((int)$mealplan->course_1)
        {
            $reservationData['course_type']    = 1;
        }


        if( !$reservation->bind($reservationData) ){
            $this->setError($reservation->getError());
            return false;
        }

        if( !$reservation->store() ){
            $this->setError($reservation->getError());
            return false;
        }

        $params = JComponentHelper::getParams('com_sfs');

        $mail_communication = (int)$params->get('mail_communication' , 1);
        $fax_communication  = (int)$params->get('fax_communication' , 1);

        if($mail_communication == 0 && $fax_communication == 0)
        {
            return true;
        }



        jimport('joomla.filesystem.file');



        // send block confirm email
        $currency = $hotel->getTaxes()->currency_symbol;
        $sroom_number = $reservationData['s_room'];
        $sroom_rate = $currency . ' ' . $reservationData['s_rate'];
        $sdroom_number = $reservationData['sd_room'];
        $sdroom_rate = $currency . ' ' . $reservationData['sd_rate'];
        $troom_number = $reservationData['t_room'];
        $troom_rate = $currency . ' ' . $reservationData['t_rate'];
        $qroom_number = $reservationData['q_room'];
        $qroom_rate = $currency . ' ' . $reservationData['q_rate'];
        $room_number = $sroom_number + $sdroom_number + $troom_number + $qroom_number;

        $breakfast = $reservationData['breakfast'];
        $breakfast_price = $currency . ' ' . $reservationData['breakfast'];
        $lunch = $reservationData['lunch'];
        $lunch_price = $currency . ' ' . $reservationData['lunch'];
        $mealplan = $reservationData['mealplan'];
        $dinner_price = $currency . ' ' . $reservationData['mealplan'];
        $course_menu = $reservationData['course_type'] . ' course';

        $booked_contact = SFactory::getContact((int)$user->id);
        $booked_name = $booked_contact->name . ' ' . $booked_contact->surname;
        $booked_title = $booked_contact->job_title;

        $airline_name = $airline->name;
        $airline_contact_name = $booked_contact->name . ' ' . $booked_contact->surname;
        $airline_contact_title = $booked_contact->job_title;
        $airline_contact_telephone = $booked_contact->telephone;
        $airline_contact_email = $booked_contact->email;

        $is_ws = 'Partner hotel';

        ob_start();
        require_once JPATH_COMPONENT . '/libraries/emails/addhotelblockconfirm.php';
        $bodyE = ob_get_clean();

        $hotelEmailBody = JString::str_ireplace('[^]', '', $bodyE);
        $hotelEmailBody = JString::str_ireplace('{date}', '', $hotelEmailBody);
        $hotelContactName = $hotel->name;
        $hotelEmailBody1 = JString::str_ireplace('{hotelcontact}', $hotelContactName, $hotelEmailBody);
        JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $emails[0], 'SHORT TERM AIRPORT ROOM BLOCK RESERVATION  BY SFS-WEB', $hotelEmailBody1, true);

        $params = JComponentHelper::getParams('com_sfs');
        // Email for SFS Admins
        $adminEmails = $params->get('sfs_system_emails');

        if (strlen($adminEmails)) {
            $data = $reservation;

            $adminSubject = ' New Blockcode: ' . $room_number . ' rooms ' . $airline->airport_code . ' Hotel ' . $hotel->name . ' ' . $reservationData['blockcode'] . ' Created';

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


        // Email for Fax Service
        $faxEmail = JString::str_ireplace('{hotelcontact}', $hotelContactName, $bodyE);
        $faxNumber = trim(SfsHelper::formatPhone($hotel->fax, 1)) . trim(SfsHelper::formatPhone($hotel->fax, 2));
        $faxAtt = JPATH_SITE . DS . 'media' . DS . 'sfs' . DS . 'attachments' . DS . 'faxblock' . $reservation->id . '.html';
        $bodyE = JString::str_ireplace('{date}', JHtml::_('date', JFactory::getDate(), "d-M-Y"), $faxEmail);
        JFile::write($faxAtt, $bodyE);



        if ($fax_communication == 1) {
            JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions', $faxNumber . '@efaxsend.com', 'SFS-web SHORT TERM ROOMBLOCK Reservation ' . $reservationData['blockcode'], $bodyE, true, null, null, $faxAtt);
        }

        return true;
    }
}
