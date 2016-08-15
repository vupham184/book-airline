<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class SfsControllerAjax extends JController
{
    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->registerTask('iataname',		'getIataName');
        $this->registerTask('checkstation',	'checkStation');
        $this->registerTask('sendvoucher',  'sendVoucherToPassenger');
        $this->registerTask('freprint',  	'getRePrintForm');
    }
    /*
     * Ajax method to get the company name of the airline
     *
     */
    public function getIataName()
    {
        $id = (int)JRequest::getVar('id');
        $db   = JFactory::getDbo();
        /*
        $query = 'SELECT COUNT(*) FROM #__sfs_airline_details WHERE iatacode_id='.$id;

        $db->setQuery($query);

        if( $db->loadResult() ) {
            echo '<span style="color:red;">This code has been used.</span>';
            exit();
        }*/
        $query = 'SELECT name FROM #__sfs_iatacodes WHERE id = '.$id;
        $db->setQuery($query);
        echo $db->loadResult();
        exit();
    }
    /*
     * Ajax method to get state field
     */
    public function getStates()
    {
        $id = (int) JRequest::getVar('id');
        echo SfsHelperField::getStateField( 'state_id' , 0 , $id );
        exit();
    }
    /*
     * Ajax method to get billing state field
     */
    public function getBStates()
    {
        $id = (int) JRequest::getVar('id');
        echo SfsHelperField::getStateField( 'billing[state_id]' , 0 , $id );
        exit();
    }

    /**
     * Ajax method to display time based on timezone of user
     *
     */
    public function getTime()
    {
        $timezone = JRequest::getVar('id');
        echo SfsHelperDate::time('now',$timezone);
        exit();
    }


    public function getAdditionalContact(){
        $session = JFactory::getSession();
        $contact = $session->get('tmpHotelContact');

        if( isset($contact) && !empty($contact) ) {
            // Get the document object.
            $document	= JFactory::getDocument();
            $vName		= 'hotelregister';
            $vFormat	= 'raw';

            if ($view = $this->getView($vName, $vFormat)) {
                // Push document object into the view.
                $view->assignRef('document', $document);
                $view->setLayout('ajaxcontact');
                $view->display();
            }
        }
        JFactory::getApplication()->close();
    }

    public function getAirlineAdditionalContact(){
        $session = JFactory::getSession();
        $contacts = $session->get('airline_additional_contact');

        if( isset($contacts) && count($contacts) ) {
            // Get the document object.
            $document	= JFactory::getDocument();
            $vName		= 'airlineregister';
            $vFormat	= 'raw';

            if ($view = $this->getView($vName, $vFormat)) {
                // Push document object into the view.
                $view->assignRef('document', $document);
                $view->setLayout('ajaxcontact');
                $view->display();
            }
        }
        JFactory::getApplication()->close();
    }

    public function getGhAdditionalContact(){

        $session = JFactory::getSession();
        $contacts = $session->get('gh_additional_contact');

        if( isset($contacts) && count($contacts) ) {
            // Get the document object.
            $document	= JFactory::getDocument();
            $vName		= 'ghregister';
            $vFormat	= 'raw';

            if ($view = $this->getView($vName, $vFormat)) {
                // Push document object into the view.
                $view->assignRef('document', $document);
                $view->setLayout('ajaxcontact');
                $view->display();
            }
        }
        JFactory::getApplication()->close();
    }

    public function checkStation() {
        $airport_id = JRequest::getInt('id');
        $airline_code = JRequest::getInt('iatacode_id');
        $db = JFactory::getDbo();
        $query = 'SELECT * FROM #__sfs_airline_details WHERE iatacode_id='.(int)$airline_code.' AND airport_id='.$airport_id;
        $db->setQuery($query,0,1);
        $result = $db->loadObject();
        if($result) {
            echo '1';
        } else {
            echo '0';
        }
        exit();
    }

    /**
     * Ajax method for airline to send the voucher code to Passenger
     *
     */
    public function sendVoucherToPassenger()
    {
        JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

        $user = JFactory::getUser();

        if( ! $user->get('id') ) return false;

        if( ! SFSAccess::isAirline($user) ) return false;

        $db = JFactory::getDbo();
        $airline = SFactory::getAirline();

        $passengerEmail = JRequest::getVar('email');
        $vouchercode 	= JRequest::getVar('vouchercode');

        // Send the voucher
        $sent = SEmail::guestVoucher( $passengerEmail , $vouchercode, '');

        // Check for an error.
        if ( !$sent ) {
            echo JText::_('COM_SFS_MATCH_PASSENGER_SEND_MAIL_FAILED');
            return false;
        }

        echo JText::sprintf('COM_SFS_MATCH_PASSENGER_SEND_MAIL_SUSCCESS',$vouchercode,$passengerEmail);

        return true;
    }

    public function drawvoucher()
    {
        // import p chart libraries
        include(SFS_PATH_CHART.DS."class/pData.class.php");
        include(SFS_PATH_CHART.DS."class/pDraw.class.php");
        include(SFS_PATH_CHART.DS."class/pImage.class.php");


        $user = JFactory::getUser();

        if( ! SFSAccess::isAirline($user) ) {
            return false;
        }

        //initialize
        $db = JFactory::getDbo();
        $airline = SFactory::getAirline();

        //gets voucher code from request
        $voucherCode = JRequest::getVar('voucher');


        //load master voucher object
        $query = 'SELECT a.code AS voucher_code, a.flight_id, a.room_type, a.vgroup, a.sdroom,a.troom, b.*, c.date,a.seats, a.id AS voucher_id, a.mealplan AS v_mealplan, a.lunch AS v_lunch, a.breakfast AS v_breakfast,a.comment FROM #__sfs_voucher_codes AS a';
        $query .=' INNER JOIN #__sfs_reservations AS b ON b.id=a.booking_id';
        $query .=' INNER JOIN #__sfs_room_inventory AS c ON c.id=b.room_id';
        $query .=' WHERE a.code='.$db->quote($voucherCode).' AND b.airline_id='.$airline->id;

        $db->setQuery($query);

        $result = $db->loadObject();

        if( empty($result) ) {
            return false;
        }

        $params = JComponentHelper::getParams('com_sfs');
        $system_currency = $params->get('sfs_system_currency','EUR');

        $totalAmount = 0;

        if( (int)$result->vgroup == 0)
        {
            if( (int)$result->room_type < 3)
            {
                $totalAmount = floatval($result->sd_rate);
            } else {
                $totalAmount = floatval($result->t_rate);
            }
        } else {
            if( (int)$result->room_type < 3)
            {
                $totalAmount = floatval($result->sd_rate) * (int)$result->seats;
            } else {
                $totalAmount = floatval($result->t_rate) * (int)$result->seats;
            }
        }

        if((int)$result->lunch && (int)$result->v_lunch) {
            $totalAmount += (int)$result->seats * $result->lunch;
        }
        if( (int)$result->mealplan && (int)$result->v_mealplan ) {
            $totalAmount += (int)$result->seats * $result->mealplan;
        }
        if( (int)$result->breakfast && (int)$result->v_breakfast ) {
            $totalAmount += (int)$result->seats * $result->breakfast;
        }

        $query = 'SELECT * FROM #__sfs_trace_passengers WHERE voucher_id='.$result->voucher_id;

        $db->setQuery($query);

        $tracePassenger = $db->loadObject();


        //load hotel data
        $hotel = SFactory::getHotel($result->hotel_id);

        $blockCode = $result->blockcode;

        /* Create the pChart object */
        $voucherPicture = new pImage(723,822);

        $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>11));

        //draw voucher image
        $voucherPicture->drawFromJPG(0,0,SFS_PATH_CHART.DS.'images'.DS.'voucher.jpg');

        $voucherPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>204,"G"=>204,"B"=>204,"Alpha"=>20));

        $TextSettings = array("R"=>191,"G"=>191,"B"=>191,"Angle"=>90,"Align"=>TEXT_ALIGN_MIDDLELEFT);

        $voucherPicture->drawText(66,395,$voucherCode,$TextSettings);

        $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPLEFT);

        $voucherPicture->setShadow(FALSE);

        $y_point = 10;

        if( $result->payment_type == 'passenger' )
        {
            $voucherPicture->drawText(109,$y_point,"NON PREPAID VOUCHER,",$TextSettings);
            $y_point+=15;
            $voucherPicture->drawText(109,$y_point,"Your total estimated charges will be ".$totalAmount." ".$system_currency,$TextSettings);
            $y_point+=25;
        }


        if($tracePassenger){
            $namesArray = array();

            if( $tracePassenger->firstname1 && $tracePassenger->lastname1 ) {
                $namesArray[] = $tracePassenger->firstname1.' '.$tracePassenger->lastname1;
            } else if($tracePassenger->firstname1) {
                $namesArray[] = $tracePassenger->firstname1;
            } else if($tracePassenger->lastname1){
                $namesArray[] = $tracePassenger->lastname1;
            }

            if( $tracePassenger->firstname2 && $tracePassenger->lastname2 ) {
                $namesArray[] = $tracePassenger->firstname2.' '.$tracePassenger->lastname2;
            } else if($tracePassenger->firstname2) {
                $namesArray[] = $tracePassenger->firstname2;
            } else if($tracePassenger->lastname2){
                $namesArray[] = $tracePassenger->lastname2;
            }

            if( $tracePassenger->firstname3 && $tracePassenger->lastname3 ) {
                $namesArray[] = $tracePassenger->firstname3.' '.$tracePassenger->lastname3;
            } else if($tracePassenger->firstname3) {
                $namesArray[] = $tracePassenger->firstname3;
            } else if($tracePassenger->lastname3){
                $namesArray[] = $tracePassenger->lastname3;
            }

            $namesText = implode(', ', $namesArray);

            $voucherPicture->drawText(109,$y_point,"Voucher issued for: ".$namesText,$TextSettings);
        }

        $y_point += 20;

        if( (int)$result->vgroup == 1 ) {
            $voucherPicture->drawText(109,$y_point,"The holder(s) of this group voucher entitles ".$result->seats." person to have:",$TextSettings);
        } else {
            $voucherPicture->drawText(109,$y_point,"The holder(s) of this voucher entitles ".$result->seats." person to have:",$TextSettings);
        }


        $date = JFactory::getDate($result->date);

        $dayFrom = (int)$date->format('d');
        $dayFromText = SfsHelper::addOrdinalNumberSuffix( $dayFrom );

        $dateTo = SfsHelperDate::getNextDate('d', $date);

        $dateToText = SfsHelper::addOrdinalNumberSuffix( (int) $dateTo).' of '.SfsHelperDate::getNextDate('F Y', $date);

        $y_point += 30;

        if( (int) $result->vgroup == 0 ) {
            if((int) $result->room_type == 3) {
                $voucherPicture->drawText(109,$y_point,"- One night accommodation in a triple room",$TextSettings);
            } else {
                $voucherPicture->drawText(109,$y_point,"- One night accommodation",$TextSettings);
            }
        } else {
            $voucherPicture->drawText(109,$y_point,"- One night accommodation in ".$result->seats." different rooms",$TextSettings);
        }

        $y_point += 20;

        $voucherPicture->drawText(150,$y_point,'starting '.$dayFromText.'   ending '.$dateToText,$TextSettings);

        //get FB details of the hotel
        $mealplan = $hotel->getMealPlan();

        $y_point += 20;

        if((int)$result->lunch && (int)$result->v_lunch) {
            $service_hour = $mealplan->lunch_service_hour;
            if( (int) $service_hour == 1 ) {
                $voucherPicture->drawText(109,$y_point,"- Pre arranged lunch available 24 hours",$TextSettings);
            } else {
                $lunchText=' available between '.str_replace(':','h',$mealplan->lunch_opentime).' and '.str_replace(':','h',$mealplan->lunch_closetime);
                $voucherPicture->drawText(109,$y_point,"- Pre arranged lunch".$lunchText,$TextSettings);
            }
            $y_point = $y_point + 20;
        }

        if( (int)$result->mealplan && (int)$result->v_mealplan ) {
            $stop_selling_time = $mealplan->stop_selling_time;
            if( (int) $stop_selling_time == 24 ) {
                $voucherPicture->drawText(109,$y_point,"- Pre arranged dinner available 24 hours",$TextSettings);
            } else {
                $voucherPicture->drawText(109,$y_point,"- Pre arranged dinner available until ".str_replace(':','h',$mealplan->stop_selling_time),$TextSettings);
            }
            $y_point = $y_point + 20;
        }

        if( (int)$result->breakfast && (int)$result->v_breakfast ) {
            $breakfastText = '';
            if( (int)$mealplan->bf_service_hour == 1 ) {
                $breakfastText=' available 24 hours';
            } else {
                $breakfastText=' available between '.str_replace(':','h',$mealplan->bf_opentime).' and '.str_replace(':','h',$mealplan->bf_closetime) ;
            }
            $voucherPicture->drawText(109,$y_point,"- Pre arranged breakfast".$breakfastText,$TextSettings);
        }

        $y_point = $y_point + 30;

        $voucherPicture->drawFilledRectangle(109,$y_point,650,$y_point+110,array("R"=>211,"G"=>238,"B"=>245));
        $y_point = $y_point + 10;
        $voucherPicture->drawText(116,$y_point,"Your accommodation details:",$TextSettings);
        $y_point = $y_point + 20;
        $voucherPicture->drawText(116,$y_point,$hotel->name,$TextSettings);
        $y_point = $y_point + 20;
        $voucherPicture->drawText(116,$y_point,$hotel->address.', '.$hotel->city,$TextSettings);
        $y_point = $y_point + 20;
        $voucherPicture->drawText(116,$y_point,$hotel->country_name.', '.$hotel->zipcode,$TextSettings);
        $y_point = $y_point + 20;
        $voucherPicture->drawText(116,$y_point,'Tel: '.$hotel->telephone,$TextSettings);

        $transport = $hotel->getTransportDetail();

        $transport_y = $y_point + 30;

        if( !empty($result->transport) && !empty($transport) ) {

            // Transport title
            $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>11));
            $transportTitle = 'Transportation to hotel';
            $TextSettings = array("R"=>44,"G"=>62,"B"=>2,"Align"=>TEXT_ALIGN_TOPLEFT);
            $voucherPicture->drawText(109,$transport_y,$transportTitle,$TextSettings);


            $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPLEFT);
            $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>8));
            $transportText = 'Transport to accommodation included: ';
            switch ( (int)$transport->transport_available ) {
                case 1:
                    $transportText .= 'Yes';
                    break;
                case 2:
                    $transportText.='Not necessary (walking distance)';
                    break;
                default :
                    $transportText .= 'No';
                    break;
            }
            $transportText .= (int)$transport->transport_complementary == 1 ? '   Complimentary: Yes':'   Complimentary: No';

            $transport_y += 20;
            $voucherPicture->drawText(109,$transport_y,$transportText,$TextSettings);
            $transportText = '';

            $transport->operating_hour = (int)$transport->operating_hour;
            if($transport->operating_hour == 0 ){
                $transportText .='Operation hours: Not available';
            } else if($transport->operating_hour == 1) {
                $transportText .='Operation hours: 24 hours';
            } else if($transport->operating_hour == 2) {
                $transportText .='Operation hours: From '.str_replace(':','h',$transport->operating_opentime).' till '.str_replace(':','h',$transport->operating_closetime);
            }
            $transportText .='   Every: '.$transport->frequency_service.' minutes';

            $transport_y += 15;
            $voucherPicture->drawText(109,$transport_y,$transportText,$TextSettings);

            $transport_y += 20;
            $voucherPicture->drawText(109,$transport_y,'Transport details: '.$transport->pickup_details,$TextSettings);

        } else {
            $transportText = 'Transport to accommodation included: No';
            $voucherPicture->drawText(109,$transport_y,$transportText,$TextSettings);
        }

        if($result->comment) {

            $comment_y = $transport_y+110;
            // Comment title
            $TextSettings = array("R"=>44,"G"=>62,"B"=>2,"Align"=>TEXT_ALIGN_TOPLEFT);
            $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>11));
            $commentTitle = 'General comments';

            $voucherPicture->drawText(109,$comment_y,$commentTitle,$TextSettings);

            $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>8));
            $comment_y +=25;

            $results = array();
            $ok = true;
            $defaultOffset = 95;
            $text = $result->comment;
            $text = str_ireplace("\n", " ", $text);
            while($ok){

                $text = trim($text);

                $splitCharacter = JString::substr( $text , $defaultOffset, 1 );

                if( strlen($splitCharacter) == 0 ){
                    $results[]=$text;
                    $ok = false;
                }

                if($splitCharacter == " "){
                    $results[] = JString::substr( $text , 0,$defaultOffset );
                    $text = JString::substr( $text , $defaultOffset );
                    $defaultOffset = 95;
                } else {
                    $defaultOffset++;
                }
            }
            $ii=0;
            foreach ($results as $r){
                $ii++;
                $voucherPicture->drawText(109,$comment_y,$r,$TextSettings);
                $comment_y += 18;
                if($ii==7) break;
            }

        }

        // Footer
        $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPLEFT);
        $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>11));

        $billing_y = 680;
        $voucherPicture->drawText(115,$billing_y,'Hotel billing information:',$TextSettings);

        if( $result->payment_type == 'passenger' ){
            $voucherPicture->drawText(480,$billing_y+8,"NON PREPAID VOUCHER,",$TextSettings);
            $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>10));
            $voucherPicture->drawText(465,$billing_y+23,"this client will pay directly to hotel.",$TextSettings);
            $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>11));
        }

        $billing_y += 20;
        $voucherPicture->drawText(115,$billing_y,'Block code:   '.$blockCode,$TextSettings);
        $billing_y += 20;
        $voucherPicture->drawText(115,$billing_y,'Voucher code:   '.$voucherCode,$TextSettings);
        $billing_y += 20;
        $voucherPicture->drawText(115,$billing_y,'Voucher '.$result->seats.' person',$TextSettings);

        $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>9));
        $billing_y += 20;

        if( $result->payment_type != 'passenger' ){
            if( isset($airline->params['voucher_vat_comment_line']) && strlen(trim($airline->params['voucher_vat_comment_line'])) > 0 )
            {
                $voucher_vat_comment_line = $airline->params['voucher_vat_comment_line'];
            } else {
                $voucher_vat_comment_line = '*Bestelbon - vrijstelling van de BTW art 42 para 2 alinea 5 W.BTW';
            }
            $voucherPicture->drawText(115,$billing_y,$voucher_vat_comment_line,$TextSettings);
        }

        if(isset($airline->params['show_general_comment']) && (int)$airline->params['show_general_comment'] == 1) {
            $generalComment = $airline->getVoucherComment();
            if($generalComment)
            {
                $billing_y += 25;
                $voucherPicture->drawText(125,$billing_y,$generalComment,$TextSettings);
            }
        }

        if( $result->payment_type != 'passenger' ){
            $voucherPicture->drawText(125,802,'Booking guaranteed by '.$airline->name.' as agreed with your hotel on SFS-web.com',$TextSettings);
        }

        /* Render the picture (choose the best way) */
        $voucherPicture->autoOutput(JURI::base()."images/".$voucherCode.".png");

        //update voucher status to printed
        $date = JFactory::getDate();
        $query = 'UPDATE #__sfs_voucher_codes SET status=1,handled_date='.$db->Quote($date->toSQL()).' WHERE code='.$db->quote($voucherCode);
        $db->setQuery($query);
        $db->query();

        jexit();
    }

    public function drawsamplevoucher()
    {
        // import p chart libraries
        include(SFS_PATH_CHART.DS."class/pData.class.php");
        include(SFS_PATH_CHART.DS."class/pDraw.class.php");
        include(SFS_PATH_CHART.DS."class/pImage.class.php");

        $user = JFactory::getUser();

        if( ! SFSAccess::check($user,'h.admin') ) {
            JFactory::getApplication()->close();
        }

        $hotel = SFactory::getHotel();

        $voucherPicture = new pImage(723,657);

        $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>11));

        //draw voucher image
        $voucherPicture->drawFromJPG(0,0,SFS_PATH_CHART.DS.'images'.DS.'voucher_2.jpg');

        $voucherPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>204,"G"=>204,"B"=>204,"Alpha"=>20));

        $TextSettings = array("R"=>191,"G"=>191,"B"=>191,"Angle"=>90,"Align"=>TEXT_ALIGN_MIDDLELEFT);

        $voucherPicture->drawText(66,295,'XXXXX-XXX-X-XX',$TextSettings);

        $TextSettings = array("R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPLEFT);

        $voucherPicture->setShadow(FALSE);


        $voucherPicture->drawText(109,30,"The holder(s) of this voucher entitles xx person to have:",$TextSettings);


        $voucherPicture->drawText(109,70,"- One night accommodation in a xx room",$TextSettings);


        $voucherPicture->drawText(150,90,'starting xxx   ending xxx',$TextSettings);


        $voucherPicture->drawText(109,110,"- Pre arranged dinner available 24 hours",$TextSettings);

        $voucherPicture->drawText(109,130,"- Pre arranged breakfast available 24 hours",$TextSettings);


        $voucherPicture->drawFilledRectangle(109,180,650,290,array("R"=>211,"G"=>238,"B"=>245));
        $voucherPicture->drawText(116,190,"Your accommodation details:",$TextSettings);
        $voucherPicture->drawText(116,210,$hotel->name,$TextSettings);
        $voucherPicture->drawText(116,230,$hotel->address.', '.$hotel->city,$TextSettings);
        $voucherPicture->drawText(116,250,$hotel->country_name.', '.$hotel->zipcode,$TextSettings);
        $voucherPicture->drawText(116,270,'Tel: '.$hotel->telephone,$TextSettings);

        $transport = $hotel->getTransportDetail();

        if( !empty($transport) ) {
            $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>8));
            $transportText = 'Transport to accommodation included: ';
            switch ( (int)$transport->transport_available ) {
                case 1:
                    $transportText .= 'Yes';
                    break;
                case 2:
                    $transportText.='Not necessary (walking distance)';
                    break;
                default :
                    $transportText .= 'No';
                    break;
            }
            $transportText .= (int)$transport->transport_complementary == 1 ? '   Complementary: Yes':'   Complementary: No';
            $voucherPicture->drawText(109,300,$transportText,$TextSettings);
            $transportText = '';
            $transport->operating_hour = (int)$transport->operating_hour;
            if($transport->operating_hour == 0 ){
                $transportText .='Operation hours: Not available';
            } else if($transport->operating_hour == 1) {
                $transportText .='Operation hours: 24 hours';
            } else if($transport->operating_hour == 2) {
                $transportText .='Operation hours: From '.str_replace(':','h',$transport->operating_opentime).' till '.str_replace(':','h',$transport->operating_closetime);
            }
            $transportText .='   Every: '.$transport->frequency_service.' minutes';
            $voucherPicture->drawText(109,315,$transportText,$TextSettings);

            //$voucherPicture->drawText(116,310,'Details:',$TextSettings);

            $voucherPicture->drawText(109,355,'Transport details: '.$transport->pickup_details,$TextSettings);

        }

        $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>11));

        $voucherPicture->drawText(115,547,'Hotel billing information:',$TextSettings);
        $voucherPicture->drawText(115,567,'Block code:   XXXX-XX-XXX-X-XX',$TextSettings);
        $voucherPicture->drawText(115,587,'Voucher code:   XXXXX-XXX-X-XX',$TextSettings);
        $voucherPicture->drawText(115,607,'Voucher xx person',$TextSettings);


        $voucherPicture->setFontProperties(array("FontName"=>SFS_PATH_CHART.DS."fonts/lucida.ttf","FontSize"=>9));
        $voucherPicture->drawText(125,637,'Booking guaranteed by airline as agreed with your hotel on SFS-web.com',$TextSettings);

        /* Render the picture (choose the best way) */
        $voucherPicture->autoOutput(JURI::base().'images/samplevoucher'.$hotel->id.'.png');



    }

    public function getRePrintForm() {
        $db = JFactory::getDbo();
        $voucherId = JRequest::getInt('id');
        $query = 'SELECT code FROM #__sfs_voucher_codes WHERE id='.$voucherId;
        $db->setQuery($query);
        $voucherCode = $db->loadResult();

        if($voucherCode) :
            ?>
            <div id="sfs-voucher-print-form" class="sfs-main-wrapper sfs-voucher-print-box" style="position:absolute;">
                <div class="sfs-white-wrapper floatbox">
                    <div class=""><?php echo JText::_('COM_SFS_MATCH_VOUCHER_PRINT_BOX_TITLE');?></div>
                    <script>
                        <!--
                        function PopupCenter(pageURL, title,w,h) {
                            var left = (screen.width/2)-(w/2);
                            var top = (screen.height/2)-(h/2);
                            var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
                        }
                        -->
                    </script>

                    <form id="voucherPrintForm" action="<?php echo JRoute::_('index.php?option=com_sfs&task=ajax.sendvoucher&format=raw')?>" method="post">
                        <table cellpadding="5" cellspacing="5">
                            <tr>
                                <td><input type="text" name="email" value="@" class="required validate-email" /></td>
                                <td><div class="s-button"><button type="submit" id="voucherEmailButton" class="s-button" >Email</button></div></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="vouchercode" value="<?php echo $voucherCode;?>" /></td>
                                <td>
                                    <div class="s-button"><a href="#" id="print-voucher-button" class="s-button" style="width:41px;">Print</a>
                                    </div>
                                    <?php
                                    $printUrl =  JRoute::_('index.php?option=com_sfs&view=match&layout=printvoucher&voucher='.$voucherCode.'&tmpl=component');
                                    ?>
                                    <a href="javascript:void(0);" onclick="PopupCenter('<?php echo $printUrl;?>', 'Print Voucher',745,600);">Print</a>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="blockcode" value="" />
                    </form>
                    <div id="v-spinner"></div>
                    <div id="voucherPrintFormResult"></div>
                    <div class="s-button" style="margin-top: 5px;">
                        <button type="button" id="closeVoucherPrintForm" style="margin-top:0;" class="s-button">Close</button>
                    </div>
                </div>
            </div>
        <?php
        endif;
    }

    public function sendMessage()
    {
        $url = "http://klanten.bizzsms.nl/api/send?username=sfs-web&code=55df53a5e9407f1627f02eae35ecde37";
        $phone = JRequest::getVar('phone');
        $text = (string)JRequest::getString('text');
        $sender_title = (string)JRequest::getString('sender');
		$fields = JRequest::getVar('fields');
		$code = JRequest::getVar('code');
		if ( $code != '' ) {
			$firstname = '';
			$lastname = '';
			foreach( $fields as $v ){
				if( $v['name'] == 'passengers[0][firstname]' ){
					$firstname = $v['value'];
				}
				if( $v['name'] == 'passengers[0][lastname]' ){
					$lastname = $v['value'];
				}
			}
			$DS = '\\';
			if( DS != '' )
				$DS = DS;
				
			$wfilePath = JPATH_SITE.$DS .'tmp' . $DS . 'mobile' . $DS . $code.'.log';
			$array = array('firstname' => $firstname, 'lastname' => $lastname );
			$this->fwriteJson(  $wfilePath, $array );
		}
		
        $url .= "&phonenumbers=".$phone;
        $url .= "&text=".urlencode($text);
        $url .= "&sendertitle=".urlencode($sender_title);

        echo file_get_contents($url);

        exit();
    }

	public function fwriteJson( $filename = '', $response ){
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
	}
	
    public function inviteHotelsLoadingRoom(){
        jimport('joomla.mail.helper');
        $user = JFactory::getUser();
        $params = &JComponentHelper::getParams('com_sfs');
        $airline = SFactory::getAirline();
        $airlineName = $airline->name;
        $logo = $airline->logo;
        
        //Send the email and fax request to hotel for asking to load rooms
        $hotelsList = JRequest::getVar('hotels', array() , 'post', 'array');

        foreach($hotelsList as $hotel_id)
        {
            $hotel = SFactory::getHotel($hotel_id);
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            //Save track notification

            $now                    = JFactory::getDate('now', 'UTC')->format("Y-m-d H:i:s", false, false);
            $trackObject            = new stdClass();
            $trackObject->date      = $now;
            $trackObject->user_id   = $user->id;
            $trackObject->hotel_id  = $hotel_id;

            $query->clear();
            $query->select('id');
            $query->from('#__sfs_airline_notification_tracking');
            $query->where('hotel_id='.$hotel_id.' AND user_id='.$user->id.' AND DATE(date)=CURDATE()');
            $db->setQuery($query);
            $result = $db->loadObject();

            if(!count($result) ) {
                $db->insertObject('#__sfs_airline_notification_tracking', $trackObject);
            }
            else{
                $trackObject->id = $result->id;
                $db->updateObject('#__sfs_airline_notification_tracking', $trackObject, "id");
            }

            // Email to Hotel
            $hotel_contacts = SFactory::getContacts(1, $hotel_id);

            ob_start();
            require_once JPATH_COMPONENT.'/libraries/emails/hotels/invitehotelloadroom.php';
            $bodyE = ob_get_clean();
            $hotelEmailSubject =  'SFS ROOMLOADING';
            $hotelEmailBody = JString::str_ireplace('{}', $airlineName, $bodyE);
            $hotelEmailBody = JString::str_ireplace('{sender_name}', $user->name, $hotelEmailBody);
            $hotelEmailBody = JString::str_ireplace('{sender_email}', $user->email, $hotelEmailBody);
            if($logo)
            {
                $logo = '<img src="'.JURI::base().'/'.$logo.'" />';
            }
            $hotelEmailBody = JString::str_ireplace('{logo}', $logo, $hotelEmailBody);
            //Code test Email
                #JFactory::getMailer()->sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions','nguyencongphuc.dev@gmail.com', $hotelEmailSubject, $hotelEmailBody, true);
            // end Code test Email
            foreach ( $hotel_contacts as $hotelContact ) {
                JFactory::getMailer()->sendMail($user->email, 'Stranded Flight Solutions', $hotelContact->email, $hotelEmailSubject, $hotelEmailBody, true);
            }
            //Email for Fax Service
            $faxNumber = trim(SfsHelper::formatPhone( $hotel->fax, 1)).trim(SfsHelper::formatPhone( $hotel->fax, 2));
                JFactory::getMailer()->sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$faxNumber.'@efaxsend.com', $hotelEmailSubject, $hotelEmailBody, true);

            $hotelBackendSetting = $hotel->getBackendSetting();
            if( $second_fax = $hotelBackendSetting->second_fax )
            {
                JFactory::getMailer()->sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$second_fax.'@efaxsend.com', $hotelEmailSubject, $hotelEmailBody, true);
            }
        }
        //send SMS text message for the SFS administrator
        /*$phoneNumbers = $params->get('sfs_system_phone_numbers', 0);
        $text = $user->name." has just sent request to hotels to load rooms";
        $url = "http://klanten.bizzsms.nl/api/send?username=sfs-web&code=55df53a5e9407f1627f02eae35ecde37";
        $url .= "&text=".urlencode($text);
        $url .= "&sendertitle=SFS";
        $phones = str_replace(";", ",", $phoneNumbers);
        $url .= "&phonenumbers=".$phones;
        echo file_get_contents($url);*/
        echo 1; die();
    }

    public function  updateAirplusServicesIndividual(){
        $db = JFactory::getDbo();
        $user 			= JFactory::getUser();
        $airline        = SFactory::getAirline();
        $airlineId 		= $airline->id;
        $userId 		= $user->get('id');

        $airplus_id     = JRequest::getInt('airplus_id');
        $hotel_id         = JRequest::getVar('hotel_id');

        $apTaxi         = JRequest::getVar('ap-taxi');
        $apMeal         = JRequest::getVar('ap-meal');
        $apCash         = JRequest::getVar('ap-cash');
		$enddate = JRequest::getVar('enddate') . ' 00:00:00';

        $apTaxi 		= $apTaxi ? 1 : 0;
        $apMeal			= $apMeal ? 1 : 0;
		$apCash         = $apCash ? 1 : 0;
		
		$query = $db->getQuery(true);
        $query->select('pa.*');
        $query->from('#__sfs_passengers_airplus AS pa');
        $query->where('pa.id=' . (int)$airplus_id);
        $db->setQuery($query);
        $passenger_ap			= $db->loadObject();
		$expiredate = ($passenger_ap && $passenger_ap->expiredate != '') ? $passenger_ap->expiredate : $enddate;
		
		
        //Update `jos_sfs_passengers_airplus`
        $query = $db->getQuery(true);
        $query->update("#__sfs_passengers_airplus AS pa");
        $query->set("pa.airplus_taxi=".(int)$apTaxi);
        $query->set("pa.airplus_mealplan=".(int)$apMeal);
        $query->set("pa.airplus_cash=".(int)$apCash);
		//update when case booking hotel WS
		$query->set("pa.expiredate='" . $expiredate . "'");
        $query->where('pa.id='.$airplus_id);
        $db->setQuery($query);
        $result = $db->execute();
        if(!$result){
            die('Not update airplus data');
        }

        //Delete old creditcard
        $query = $db->getQuery(true);
        $query->delete("#__sfs_airplusws_creditcard_detail");
        $query->where('airplus_id='.$airplus_id);
        $db->setQuery($query);
        $result = $db->execute();
        if(!$result){
            die('Error SQL get cc');
        }

        $query = $db->getQuery(true);
        $query->select('pa.*, p.first_name, p.last_name');
        $query->from('#__sfs_passengers_airplus pa');
        $query->innerJoin('#__sfs_trace_passengers AS p ON p.airplus_id = pa.id');
        $query->where('p.id=' . (int)$airplus_id);
        $db->setQuery($query);
        $passenger	= $db->loadObject();

        if($apMeal || $apTaxi){

            //Insert new taxi creditcard
            if($apTaxi && empty($taxi_card_id))
            {
                $ap_taxi_unique_id = SfsHelper::generateVoucherUniqueID(0);

                $options = array(
                    'type' => 'taxi',
                    'startdate' 	=> $passenger->startdate,
                    'flightnumber'	=> $passenger->flight_number,
                    'pnr' 			=> $passenger->pnr,
                    'unique_id'		=> $ap_taxi_unique_id
                );

                $taxi_card = SfsWs::airplusCall($airlineId, $userId, $options);

                $passenger_taxi_ap = new stdClass();
                $passenger_taxi_ap->airplus_id = $airplus_id;
                $passenger_taxi_ap->cvc = $taxi_card->CVC;
                $passenger_taxi_ap->card_number = base64_encode($taxi_card->CardNumber);
                $passenger_taxi_ap->session_id = $taxi_card->SessionId;
                $passenger_taxi_ap->type_of_service = 'taxi';
                $passenger_taxi_ap->valid_thru = $taxi_card->ValidThru;
                $passenger_taxi_ap->valid_from = $taxi_card->ValidFrom;
                $passenger_taxi_ap->passenger_name = $passenger->first_name." ".$passenger->last_name;
                $passenger_taxi_ap->value = SfsHelper::calculateTaxiValue($hotel_id);
                $passenger_taxi_ap->unique_id = $ap_taxi_unique_id;

                if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $passenger_taxi_ap)) {
                    die('Could not create taxi ap');
                } else {
                    $taxi_card_id = $db->insertid();
                }
            }
            //Insert new mealplan creditcard

            if($apMeal) {

                $ap_meal_unique_id = SfsHelper::generateVoucherUniqueID(0);

                $options = array(
                    'type' => 'meal',
                    'startdate' 	=> $passenger->startdate,
                    'flightnumber'	=> $passenger->flight_number,
                    'pnr' 			=> $passenger->pnr,
                    'unique_id'		=> $ap_meal_unique_id
                );

                $mealplan_card = SfsWs::airplusCall($airlineId, $userId, $options);

                $passenger_meal_ap = new stdClass();
                $passenger_meal_ap->airplus_id = $airplus_id;
                $passenger_meal_ap->cvc = $mealplan_card->CVC;
                $passenger_meal_ap->card_number = base64_encode($mealplan_card->CardNumber);
                $passenger_meal_ap->session_id = $mealplan_card->SessionId;
                $passenger_meal_ap->type_of_service = 'meal';
                $passenger_meal_ap->valid_thru = $mealplan_card->ValidThru;
                $passenger_meal_ap->valid_from = $mealplan_card->ValidFrom;
                $passenger_meal_ap->passenger_name = $passenger->first_name . " " . $passenger->last_name;
                $passenger_meal_ap->value = SfsHelper::calculateMealplanValue(1);
                $passenger_meal_ap->unique_id = $ap_meal_unique_id;

                if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $passenger_meal_ap)) {
                    die('Could not create meal ap');
                }
            }
        }
        die("1");
    }

    public function  updateAirplusServices(){
        $db = JFactory::getDbo();
        ///$voucher_id     = JRequest::getInt('voucher_id');
		$voucher_idA     = explode(",", JRequest::getVar('voucher_id') );
		foreach ($voucher_idA as $voucher_id ) {
			$ap_taxi         = JRequest::getVar('ap-taxi');
			$ap_meal         = JRequest::getVar('ap-meal');
			$enddate         = JRequest::getVar('enddate');
	
			$voucher = SVoucher::getInstance($voucher_id, 'id');
			if(empty($voucher->id)) {
				die('Invalid voucher');
			}
			
			$user 			= JFactory::getUser();
			
			$airline 		= SAirline::getInstance($voucher->airline_id);
			$reservation 	= SReservation::getInstance($voucher->booking_id, true);
			
			$airlineId 		= $airline->id;
			$userId 		= $user->get('id');
			
			if(empty($userId)) {
				die('Not authorized');
			}
	
			if($ap_meal == 1){
				$ap_meal = 1;
				$ap_meal_value = SfsHelper::calculateMealplanValue(1);
			}elseif($ap_meal == 2){
				$ap_meal = 1;
				$ap_meal_value = SfsHelper::calculateMealplanValue(2);
			}else{
				$ap_meal = 0;
				$ap_meal_value = 0;
			}
	
			$ap_taxi 		= $ap_taxi ? 1 : 0;
			$ap_taxi_value   = SfsHelper::calculateTaxiValue($reservation->hotel_id);
			
			$query = $db->getQuery(true);
			$query->select('pa.*');
			$query->from('#__sfs_passengers_airplus AS pa');
			$query->where('pa.voucher_id=' . (int)$voucher_id);
			$db->setQuery($query);
			$passenger_ap			= $db->loadObject();
			$airplus_id				= $passenger_ap ? $passenger_ap->id : 0;		
			$expiredate = ($passenger_ap && $passenger_ap->expiredate != '' ) ? $passenger_ap->expiredate : $enddate;
			
			$query = $db->getQuery(true);
			$query->select("id, first_name, last_name");
			$query->from("#__sfs_trace_passengers");
			$query->where('voucher_id='.$voucher_id);
			$db->setQuery($query);
			$passengers = $db->loadObjectList();
			$passenger_first = $passengers[0];
	
			if($airplus_id != 0){
				//Update `jos_sfs_passengers_airplus`
				$query = $db->getQuery(true);
				$query->update("#__sfs_passengers_airplus AS pa");
				$query->set("pa.airplus_taxi=".(int)$ap_taxi);
				$query->set("pa.airplus_mealplan=".(int)$ap_meal);
				$query->set("pa.expiredate='" . $expiredate . "'");
			
				$query->where('pa.id='.$airplus_id);
				$db->setQuery($query);
				$result = $db->execute();
				if(!$result){
					die('Not update airplus data');
				}
				//Delete old creditcard
				$query = $db->getQuery(true);
				$query->delete("#__sfs_airplusws_creditcard_detail");
				$query->where('airplus_id='.$airplus_id);
				$db->setQuery($query);
				$result = $db->execute();
				if(!$result){
					die('Error SQL get cc');
				}
			} else {
				$passenger_ap                      = new stdClass();
				$passenger_ap->airplus_mealplan    = $ap_meal;
				$passenger_ap->airplus_taxi        = $ap_taxi;
				$passenger_ap->airline_id			= $airline->id;
				$passenger_ap->voucher_id			= $voucher->id;
				$passenger_ap->startdate			= $reservation->blockdate;
				$passenger_ap->expiredate			= $enddate;
				$passenger_ap->user_id				= $userId;
				$passenger_ap->pnr					= $reservation->url_code;
				$passenger_ap->blockcode			= $reservation->blockcode;
				$passenger_ap->airport_code			= $reservation->airport_code;
				$passenger_ap->hotel_id				= $reservation->hotel_id;
				$passenger_ap->flight_number		= $voucher->flight_code;
				
				if (!$db->insertObject('#__sfs_passengers_airplus', $passenger_ap)) {
					exit('Could not create AP record');
				} else {
					$airplus_id = $db->insertid();
				}
			}
				
			foreach($passengers as $passenger){
				if(empty($passenger->first_name) || empty($passenger->last_name)) {
					continue;
				}
				# update airplus id
				$query = $db->getQuery(true);
				$query->update('#__sfs_trace_passengers as tp');
				$query->set('tp.airplus_id = ' . (int)$airplus_id);
				$query->where('tp.id=' . (int)$passenger->id);
				$db->setQuery($query);
				$db->execute();
				if($ap_meal || $ap_taxi){
					
					//Insert new taxi creditcard
					if($ap_taxi && empty($taxi_card_id))
					{
						$ap_taxi_unique_id = SfsHelper::generateVoucherUniqueID($voucher_id);
							
						$options = array(
								'type' => 'taxi',
								'startdate' 	=> $passenger_ap->startdate,
								'enddate' 	    => $passenger_ap->expiredate,
								'flightnumber'	=> $passenger_ap->flight_number,
								'pnr' 			=> $passenger_ap->pnr,
								'unique_id'		=> $ap_taxi_unique_id
						);
							
						$taxi_card = SfsWs::airplusCall($airlineId, $userId, $options);
							
						$passenger_taxi_ap = new stdClass();
						$passenger_taxi_ap->airplus_id = $airplus_id;
						$passenger_taxi_ap->cvc = $taxi_card->CVC;
						$passenger_taxi_ap->card_number = base64_encode($taxi_card->CardNumber);
						$passenger_taxi_ap->session_id = $taxi_card->SessionId;
						$passenger_taxi_ap->type_of_service = 'taxi';
						$passenger_taxi_ap->valid_thru = $taxi_card->ValidThru;
						$passenger_taxi_ap->valid_from = $taxi_card->ValidFrom;
						$passenger_taxi_ap->passenger_name = $passenger_first->first_name." ".$passenger_first->last_name;
						$passenger_taxi_ap->value = $ap_taxi_value;
						$passenger_taxi_ap->unique_id = $ap_taxi_unique_id;
	
						if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $passenger_taxi_ap)) {
							die('Could not create taxi ap');
						} else {
							$taxi_card_id = $db->insertid();
						}
					}
					//Insert new mealplan creditcard
					if($ap_meal) {
						
						$ap_meal_unique_id = SfsHelper::generateVoucherUniqueID($voucher_id);
						
						$options = array(
								'type' => 'meal',
								'startdate' 	=> $passenger_ap->startdate,
								'enddate' 	    => $passenger_ap->expiredate,
								'flightnumber'	=> $passenger_ap->flight_number,
								'pnr' 			=> $passenger_ap->pnr,
								'unique_id'		=> $ap_meal_unique_id
						);
							 
						$mealplan_card = SfsWs::airplusCall($airlineId, $userId, $options);
							
						$passenger_meal_ap = new stdClass();
						$passenger_meal_ap->airplus_id = $airplus_id;
						$passenger_meal_ap->cvc = $mealplan_card->CVC;
						$passenger_meal_ap->card_number = base64_encode($mealplan_card->CardNumber);
						$passenger_meal_ap->session_id = $mealplan_card->SessionId;
						$passenger_meal_ap->type_of_service = 'meal';
						$passenger_meal_ap->valid_thru = $mealplan_card->ValidThru;
						$passenger_meal_ap->valid_from = $mealplan_card->ValidFrom;
						$passenger_meal_ap->passenger_name = $passenger->first_name . " " . $passenger->last_name;
						$passenger_meal_ap->value = $ap_meal_value;
						$passenger_meal_ap->unique_id = $ap_meal_unique_id;
	
						if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $passenger_meal_ap)) {
							die('Could not create meal ap');
						}
					}
				}
			}
		}//End foreach
        die('1');
    }

    public function issueMealVouchers(){
        $db         = JFactory::getDbo();
        $date 	 	= JFactory::getDate();
        $airline 	= SFactory::getAirline();
        $airlineId	= $airline->id;
        $user 		= JFactory::getUser();
        if( ! $user->get('id') ) exit('Not login');
        $userId		= $user->get('id');
        
        $number_passengers      = JRequest::getInt('passengers');
        $flight_number          = JRequest::getVar('flight-number');
        $airport_id             = JRequest::getVar('airport_id');
        $value                  = JRequest::getVar('value');
        $passengers             = JRequest::getVar('passenger', array() , 'post', 'array');
        $date_expire			= JRequest::getVar('date_expire', null);
        $type					= 'meal';
		
		#lchung !@important don't translate timezone this point because it's translated before
        #$start_date 			= SfsHelperDate::getDate($date_expire,'Y-m-d 00:00:00',$airline->time_zone);
		$start_date = 			$date_expire;
        $end_date   			= SfsHelperDate::getNextDate('Y-m-d 00:00:00',$start_date);
        
        $options                = array(
        		'type' => 'meal',
        		'startdate' 	=> $start_date,
        		'enddate' 	    => $end_date,
        		'flightnumber'	=> $flight_number,
        		'pnr' 			=> '', # TODO
        );
        
        if($number_passengers){
            $result = array();
            foreach($passengers as $key => $p)
            {
                if($p['first_name'] && $p['last_name']){

                    $passenger_meal_ap                      = new stdClass();
                    $passenger_meal_ap->airplus_mealplan    = 1;
                    $passenger_meal_ap->flight_number		= $flight_number;
                    $passenger_meal_ap->airline_id			= $airline->id;
                    $passenger_meal_ap->startdate			= $date->format('Y-m-d 00:00:00');
                    $passenger_meal_ap->user_id				= $userId;
                    # TODO: rename the input to a correct one
                    $passenger_meal_ap->expiredate 			= $date_expire;
                    # TODO: pnr?
                    $passenger_meal_ap->pnr					= '';
                    $passenger_meal_ap->airport_code		= $airline->airport_code;
                    if (!$db->insertObject('#__sfs_passengers_airplus', $passenger_meal_ap)) {
                        exit('Not Insert 1');
                    }else{
                        $airplus_id = $db->insertid();
                    }
					$creditcard_detail = new stdClass();
					$unique_id = SfsHelper::generateVoucherUniqueID();
					$options['unique_id'] 		= $unique_id;
                    $airplus_voucher = SfsWs::airplusCall($airlineId, $userId, $options);
                    
                    $creditcard_detail->airplus_id      = $airplus_id;
                    $creditcard_detail->cvc             = $airplus_voucher->CVC;
                    $creditcard_detail->card_number     = base64_encode($airplus_voucher->CardNumber);
                    $creditcard_detail->session_id      = $airplus_voucher->SessionId;
                    $creditcard_detail->type_of_service = 'meal';
                    $creditcard_detail->valid_thru      = $airplus_voucher->ValidThru;
                    $creditcard_detail->valid_from      = $airplus_voucher->ValidFrom;
                    $creditcard_detail->passenger_name  = $p['first_name']." ".$p['last_name'];
                    $creditcard_detail->value           = $value;
					$creditcard_detail->unique_id       = $unique_id;
					
                    if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $creditcard_detail)) {
                        exit('Not Insert 2');
                    }

                    $airplus_voucher->PassengerName = $p['first_name']." ".$p['last_name'];
                    $airplus_voucher->value         = $value;
                    $airplus_voucher->TypeOfService = 'meal plan';


                    $passenger = new stdClass();
                    $passenger->flight_number           = $flight_number;
                    $passenger->airport_id              = $airport_id;
                    $passenger->first_name              = $p['first_name'];
                    $passenger->last_name               = $p['last_name'];
                    $passenger->airplus_id              = $airplus_id;
                    $passenger->created_date            = $date->toSql();
                    if (!$db->insertObject('#__sfs_trace_passengers', $passenger)) {
                        exit('Not Insert 3');
                    }

                    $result[] = $airplus_voucher;
                }
            }
            echo json_encode($result);
            exit(0);
        }else{
            exit(0);
        }
    }

    public function issueTaxiVouchers(){
        $db         = JFactory::getDbo();
        $airline    = SFactory::getAirline();
		$airline_current = SAirline::getInstance()->getCurrentAirport();
		$time_zone = $airline_current->time_zone;
        $date 	 	= JFactory::getDate();
        $airlineId	= $airline->id;
        $user 		= JFactory::getUser();
        if( ! $user->get('id') ) exit('Not login');
        $userId		= $user->get('id');


        $airplusparams= $airline->airplusparams;
        $number_passengers      = JRequest::getInt('passengers');
        $number_way             = JRequest::getInt('way');
        $flight_number          = JRequest::getVar('flight-number');
        $airport_id             = JRequest::getVar('airport_id');
        $distance               = JRequest::getVar('distance');
        $passengers             = JRequest::getVar('passenger', array() , 'post', 'array');
        $start_date 			= SfsHelperDate::getDate('now','Y-m-d 00:00:00', $time_zone);
        $end_date   			= SfsHelperDate::getNextDate('Y-m-d 00:00:00',$start_date);
        if($number_way == 2){
            $end_date   			= SfsHelperDate::getNextDate('Y-m-d 00:00:00',$end_date);
        }
        $options                = array(
        		'type' 			=> 'taxi',
        		'startdate' 	=> $start_date,
        		'enddate' 	    => $end_date,
        		'flightnumber'	=> $flight_number,
        		'pnr' 			=> '', # TODO
        );
        if($number_passengers){
            $airplus_voucher = array();
            $i = 0;
            $query = $db->getQuery(true);
            $query->select("starting_tariff, km_rate, code");
            $query->from("#__sfs_iatacodes");
            $query->where("id=".(int)$airport_id);
            
            /** @var $db JDatabase */
            $db->setQuery($query);
            $airport_taxi = $db->loadObject();
            $airport_code = $airport_taxi->code;
            
            $value = (floatval($airport_taxi->km_rate)*$distance+$airport_taxi->starting_tariff)*$number_way*1.1 + floatval ( $airplusparams['taxi_fee'] );
            foreach($passengers as $key => $p)
            {
                if($p['first_name'] && $p['last_name']){
                    if($i == 0){
                    	
                    	# create passengers_airplus
                        $passenger_taxi_ap                      = new stdClass();
                        $passenger_taxi_ap->airplus_taxi        = 1;
                        $passenger_taxi_ap->flight_number		= $flight_number;
                        $passenger_taxi_ap->airline_id			= $airline->id;
                        $passenger_taxi_ap->startdate			= $start_date;
                        # TODO: rename the input to a correct one
                        $passenger_taxi_ap->expiredate 			= $end_date;
                        $passenger_taxi_ap->user_id				= $userId;
                        $passenger_taxi_ap->airport_code		= $airport_code;
                        
                        if (!$db->insertObject('#__sfs_passengers_airplus', $passenger_taxi_ap)) {
                            exit('Could not create passenger airplus records');
                        }else{
                            $airplus_id = $db->insertid();
                        }
						
						$unique_id = SfsHelper::generateVoucherUniqueID();
						$options['unique_id'] 		= $unique_id;
						
                        $airplus_voucher = SfsWs::airplusCall($airlineId, $userId, $options);
                        $creditcard_detail = new stdClass();
                        $creditcard_detail->airplus_id      = $airplus_id;
                        $creditcard_detail->cvc             = $airplus_voucher->CVC;
                        $creditcard_detail->card_number     = base64_encode($airplus_voucher->CardNumber);
                        $creditcard_detail->session_id      = $airplus_voucher->SessionId;
                        $creditcard_detail->type_of_service = 'taxi';
                        $creditcard_detail->valid_thru      = $airplus_voucher->ValidThru;
                        $creditcard_detail->valid_from      = $airplus_voucher->ValidFrom;
                        $creditcard_detail->passenger_name  = $p['first_name']." ".$p['last_name'];
                        $creditcard_detail->value           = $value;
						$creditcard_detail->unique_id       = $unique_id;

                        if (!$db->insertObject('#__sfs_airplusws_creditcard_detail', $creditcard_detail)) {
                            exit('Could not create airplus credit card');
                        }
                        $airplus_voucher->PassengerName = $p['first_name']." ".$p['last_name'];
                        $airplus_voucher->value         = $value;
                        $airplus_voucher->TypeOfService = 'taxi';
                    }

                    $passenger = new stdClass();
                    $passenger->flight_number           = $flight_number;
                    $passenger->airport_id              = $airport_id;
                    $passenger->first_name              = $p['first_name'];
                    $passenger->last_name               = $p['last_name'];
                    $passenger->airplus_id              = $airplus_id;
                    $passenger->created_date            = $date->toSql();
                    if (!$db->insertObject('#__sfs_trace_passengers', $passenger)) {
                        exit(0);
                    }
                }
                $i++;
            }
            echo json_encode($airplus_voucher);
            exit(0);
        }else{
            exit(0);
        }
    }
	
	//lchung
	public function CalculatorValueTaxi( $voucher_id  = 0 )
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("a.hotel_id");
		$query->from("#__sfs_reservations as a");
		$query->innerJoin('#__sfs_voucher_codes AS b ON b.booking_id=a.id');
		$query->where("b.id=".(int)$voucher_id);
		$db->setQuery($query);
		$hotel_id = $db->loadObject()->hotel_id;

		if( $hotel_id > 0  ) {
			$airline = SFactory::getAirline();
			$airplusparams = $airline->airplusparams;
			return SfsWs::getHotelDistance($hotel_id, $airplusparams['taxi_fee']);
		}
		
		return 0;
	}
	//End lchung

    public function estimateTotalOneWayTaxiFee(){
        $airport_id = JRequest::getVar('airport_id');
        $distance   = JRequest::getVar('distance');
        $airline = SFactory::getAirline();
        $airplusparams= $airline->airplusparams;
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("starting_tariff, km_rate");
        $query->from("#__sfs_iatacodes");
        $query->where("id=".(int)$airport_id);
        $db->setQuery($query);
        $airport_taxi = $db->loadObject();
        $value = (floatval($airport_taxi->km_rate)*$distance+$airport_taxi->starting_tariff)*1.1 + floatval ( $airplusparams['taxi_fee'] );
        echo number_format($value, 2, '.', ',');
        exit(0);
    }

    public function updateMealplanService(){
        $ap_voucher_id = JRequest::getInt('ap_voucher_id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update("#__sfs_airplusws_creditcard_detail");
        $query->set("value=value+10");
        $query->where('id='.$ap_voucher_id);
        $db->setQuery($query);
        $result = $db->execute();
        if($result)
        {
            echo "1";
        }
        else{
            echo "0";
        }
        exit();
    }
    //Begin Minh Tran
    public function bookServiceRental(){
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! $user->get('id') ) exit('Not login');
        $userId     = $user->get('id');
        $model =& $this->getModel( 'passengersimport' );
        $lastinsertid=$model->bookServiceRental();        
        
        if($lastinsertid)
        { 
            echo json_encode( array("id"=>$lastinsertid));            
        }
        else{
            echo json_encode(array('id' =>0));            
        }
        exit();
    }
    public function deletebookServiceRental(){
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! $user->get('id') ) exit('Not login');
        $userId     = $user->get('id');

        $model =& $this->getModel( 'passengersimport' );
        $passengers             = JRequest::getVar('pass_ids', array() , 'post', 'array');
        foreach ($passengers as $key => $value) {  
        $result=$model->deletebookServiceRental($value);    
        }    
        if($result)
        {
            echo "1";
        }
        else{
            echo "0";
        }

    }   

    public function createGroupPassenger(){
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        }

        $model =& $this->getModel( 'passengersimport' );
        $passenger_ids = JRequest::getVar('pas_id_group',array(), 'post', 'array');
        if($passenger_ids){            
            $group_id = $model->createGroupPassengerTravel();
            if($group_id){                      
                    foreach ($passenger_ids as $key => $value) {    
                        if($value!=''){
                            $model->createGroupDetail($group_id,$value);
                        }
                    }                                
            }
        }
        $arr = array('successful' => "1", 'errorcode' => 'maxl', 'group_id' => $group_id);
            echo json_encode( $arr );
            exit;
    } 
    // Remove Passenger in group
    function removePassengerInGroup(){
        $app     = JFactory::getApplication();              
        $user    = JFactory::getUser();                     
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        }
        $passenger_ids_rm = JRequest::getVar('pas_id_rm',array(), 'post', 'array');
        $group_id = JRequest::getInt('group_id');
        if( $passenger_ids_rm ){            
            $model =& $this->getModel( 'passengersimport' );            
            foreach ($passenger_ids_rm as $value) {         

                    $result = $model->removePassengerInGroup($value,$group_id);
                    if(!$result){
                        $arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Delete error');
                        echo json_encode( $arr );
                        exit;
                    }                
            }  
            // check group have passenger
            $model->checkPassengerGroup($group_id);
            $arr = array('successful' => "1", 'errorcode' => 'maxl', 'errormessage' => '');
            echo json_encode( $arr );
            exit;
        }        
    }
    //function process share room
    public function processTravelTogetherShareRoom(){          
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        }


        $passenger_ids_sha = JRequest::getVar('pas_id_sha',array(), 'post', 'array');
        $passenger_ids_group = JRequest::getVar('pas_id_group',array(), 'post', 'array');
        $group_id = JRequest::getInt('group_id');

        if($passenger_ids_sha && $passenger_ids_group){    
            $model =& $this->getModel( 'passengersimport' );        
            $model->travelTogetherShareRoom($passenger_ids_sha,$passenger_ids_group,$group_id);

            $arr = array('successful' => "1", 'errorcode' => 'maxl', 'errormessage' => 'Update successful');
            echo json_encode( $arr );
        }else{
            $arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Update error');
            echo json_encode( $arr );
        }
        
        die();
    }
    public function processTravelTogetherSeperateRoom(){
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        }
        $passenger_ids_sep = JRequest::getVar('pas_id_sep',array(), 'post', 'array');
        $passenger_ids_group = JRequest::getVar('pas_id_group',array(), 'post', 'array');
        $group_id = JRequest::getInt('group_id');              
        if($passenger_ids_sep && $passenger_ids_group){  
            $model =& $this->getModel( 'passengersimport' );        
            $model->travelTogetherSeperateRoom($passenger_ids_sep,$passenger_ids_group,$group_id);
            $arr = array('successful' => "1", 'errorcode' => 'maxl', 'errormessage' => 'Update successful');
            echo json_encode( $arr );
        }else{
            $arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Update error');
            echo json_encode( $arr );
        }
        die();
    }
    public function processNotShareRoom(){
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        } 
        $passenger_ids_not_sha = JRequest::getVar('pas_id_not_sha',array(), 'post', 'array');
        $passenger_ids_group = JRequest::getVar('pas_id_group',array(), 'post', 'array');
        $group_id = JRequest::getInt('group_id');              
        if($passenger_ids_not_sha && $passenger_ids_group){  
            $model =& $this->getModel( 'passengersimport' );        
            $model->travelNotShareRoom($passenger_ids_not_sha,$passenger_ids_group,$group_id);
            $arr = array('successful' => "1", 'errorcode' => 'maxl', 'errormessage' => 'Update successful');
            echo json_encode( $arr );
        }else{
            $arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Update error');
            echo json_encode( $arr );
        }
        die();
    }
    public function addServicePassenger(){
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        } 
        $passenger_ids = JRequest::getVar('pas_ids',array(), 'post', 'array');
        $passenger_ids_str = implode(',', $passenger_ids);
        $service_id = JRequest::getInt('service_id');
        $status = JRequest::getInt('status');
        $group_id = JRequest::getInt('group_id');
        $list_pass='';
        if($passenger_ids && $service_id){
            $model =& $this->getModel( 'passengersimport' );   
            if($status==1 && count($passenger_ids)>0){
                
                foreach($passenger_ids as $passenger_id){                
                    $result = $model->addPassengerService( $passenger_id, $service_id, $status );
                    if(!$result){
                        $arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Update error');
            echo json_encode( $arr );
                        die();
                    }
                } 
                if ($result) {
                    $price = $vouchercodes = NULL;
                    $dt = $model->savePricePerPerson($price,$passenger_ids_str,$service_id,$vouchercodes);
                }
                
                 
            }
            else{
                if($group_id)
                    $list_pass= $model->getListByGroup($group_id);  
                if(count($list_pass)>0 && $group_id>0 ){
                    foreach($list_pass as $l){
                        $result = $model->removePassengerService( $l->passenger_id, $service_id, $status );
                        if(!$result){
                            $arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Update error');
                            echo json_encode( $arr );
                            die();
                        }
                    }
                }else{
                    foreach($passenger_ids as $passenger_id){
                        $result = $model->removePassengerService( $passenger_id, $service_id, $status );
                        if(!$result){
                            $arr = array('successful' => "0", 'errorcode' => 'maxl', 'errormessage' => 'Update error');
                            echo json_encode( $arr );
                            die();
                        }
                    }   
                }
                
            }
             
        }        
        $arr = array('successful' => "1", 'errorcode' => 'maxl', 'errormessage' => 'Update successful');
        echo json_encode( $arr );        
        die();
    }

    public function searchHotel(){
        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        } 
        //$model =& $this->getModel( 'search' );
        $reservationid = JRequest::getInt('reservationid');
        $blockdate = JRequest::getVar('blockdate');        
        $airline = SFactory::getAirline();
        $sd_room_total = 0;
        $t_room_total = 0;
        $s_room_total = 0;  
        $q_room_total = 0;

        $countS  = false;
        $countSD = false;
        $countT  = false;   
        $countQ  = false;
        $single_room_available = 0;
        $quad_room_available = 0;
        $t = 0;

        $model =& $this->getModel( 'match' );   
        if($blockdate){
            $reservations = $model ->getReservationsOld($blockdate,$reservationid);   
        }else{
            $reservations = $model ->getReservations();            
        }
        
        $transportCompany = $model ->getTransportCompany();
        if($blockdate){
            $night = $blockdate;
        }
        else{
            $night = $model->getNightDate();
        }        
        if( count($reservations) ){
                foreach ( $reservations as $item )
                {
                    if ( $t == 0 ) {
                        $t = 1;
                        $HotelBackendParams = $model->getHotelBackendParams( $item->hotel_id );
                        $single_room_available = (int)$HotelBackendParams->single_room_available;
                        $quad_room_available = (int)$HotelBackendParams->quad_room_available;
                    }
                    
                    if( (int)$item->s_room > 0 )
                    {
                        $countS = true;
                    }
                    if( (int)$item->sd_room > 0 )
                    {
                        $countSD = true;
                    }
                    if( (int)$item->t_room > 0 )
                    {
                        $countT = true;
                    }
                    if( (int)$item->q_room > 0 )
                    {
                        $countQ = true;
                    }
                }
            }
            $html='';
        if( count($reservations) ){
            if( count($reservations) ) {
                $usedBlocks = array();
                $jquery='';
                $html='<input type="hidden" id="nightdate" name="nightdate" value="'.$night.'" /><table class="tbl-list-hotel" cellpadding="0" cellspacing="0" border="0">';
                foreach ( $reservations as $item ){
                    if(!empty($item->ws_room_type) && $blockdate=='') {
                            $usedBlocks[$item->association_id][$item->hotel_id] = $item->name;
                            continue;
                        }
                        $availableSD = $item->sd_room - $item->sd_room_issued;
                        $availableT  = $item->t_room - $item->t_room_issued;
                        $availableS  = $item->s_room - $item->s_room_issued;
                        $availableQ  = $item->q_room - $item->q_room_issued;
                        
                        $totalAvailableRooms = $availableSD + $availableS + $availableT + $availableQ;
                        
                        $sd_room_total += $availableSD;
                        $t_room_total  += $availableT;
                        $s_room_total  += $availableS;
                        $q_room_total  += $availableQ;

                        if( (int)$totalAvailableRooms == 0  && $blockdate=='')
                        {
                            if( !isset($usedBlocks[$item->association_id]) )
                            {
                                $usedBlocks[$item->association_id] = array();
                            }
                            
                            if( !isset($usedBlocks[$item->association_id][$item->hotel_id]) )
                            {
                                $usedBlocks[$item->association_id][$item->hotel_id] = $item->name;
                            }
                            continue;
                        }

                        $link  = 'index.php?option=com_sfs&view=match&layout=vouchers&hotelid='.$item->hotel_id;
                        $link .= '&reservationid='.$item->id;
                        $link .= '&nightdate='.$this->night;
                        if($item->association_id) {
                            $link .= '&association_id='.$item->association_id;
                        }
                        $html.='<tr>';
                        $checked='';
                        $hotel_active='';
                        $style="";
                        if($reservationid==$item->id){                            
                           $style="style='display:none;'";
                        }
                        $html.='<td width="10%" '.$style.'><div id="reservation-toggle-'.$item->id.'" class="ui toggle checkbox '.$checked.'"><input data-hotel="'.$item->hotel_id.'" name="reservation-'.$item->id.'" type="checkbox" id="reservation-'.$item->id.'" value="'.$item->id.'" data-resid="'.$item->id.'" tabindex="0" class="hidden"><label></label></div></td>';
                        $html.='<td><div class="title-hotel"><span style="float:left;margin-right:3px;">'.$item->name.'</span><span style="float:left;margin-top:3px;" class="star star'.$item->star.'"></span></div><br/><div class="transportation"><span style="float:left">Transportation ';
                            if( (int) $item->transport > 0 ) {
                                $transportTooltip = 'Transport to accommodation included: ';

                                            switch ( (int)$item->transport_available ) {
                                                case 1:
                                                    $transportTooltip .= 'Yes';
                                                    break;
                                                case 2:
                                                    $transportTooltip.='Not necessary (walking distance)';
                                                    break;
                                                default :
                                                    $transportTooltip .= 'No';
                                                    break;
                                            }

                                            $transportTooltip .='<br />';
                                            $transportTooltip .= (int)$item->transport_complementary == 1 ? 'Complimentary: Yes':'Complimentary: No';
                                            $transportTooltip .='<br />';
                                            $item->operating_hour = (int)$item->operating_hour  ;
                                            if($item->operating_hour == 0 ){
                                                $transportTooltip .='Operation hours: Not available';
                                            } else if($item->operating_hour == 1) {
                                                $transportTooltip .='Operation hours: 24-24 for stranded';
                                            } else if($item->operating_hour == 2) {
                                                $transportTooltip .='Operation hours: From '.str_replace(':','h',$item->operating_opentime).' till '.str_replace(':','h',$item->operating_closetime);
                                            }
                                            $transportTooltip .='<br />';
                                            $transportTooltip .='Every: '.$item->frequency_service.' minutes';
                                            if($item->pickup_details){
                                                $transportTooltip .='<br /><br />Details:<br />'.$item->pickup_details;
                                            }
                            }
                            $html.=JText::_('COM_SFS_TRANSPORTATION').'&lt;';
                            $html.=(int)$item->transport ? 'Yes' : 'No';
                            $html.='&gt;';
                            if( (int) $item->transport > 0 ){
                                $html.='<img src="components/com_sfs/assets/images/info16.png" alt="" class="hasTip" title="'.$transportTooltip.'" />';
                            }
                        /*if($item->transport>0){
                            $html.=' yes ></span>';    
                            $html.='<span style="float:left;"><img src="'.JURI::base().'media/media/images/select-pass-icons/icon-i.png" /></span>';
                        }
                        else{
                            $html.=' no >';                             
                        } */       
                        $html.='</span>';

                        $html.='<div style="clear:both;"></div><span>Address: '.$item->address.', '.$item->city.'</span> ';              
                        //$html.='<input type="radio" name="reservations['.$item->id.'][group_transport_id]" value="'.$transportCompany->id.'" checked="checked" />';
                        $html.='</div>';
                        if ( floatval($item->mealplan) > 0 ){
                            $item->stop_selling_time = ( $item->stop_selling_time == '24' ) ? JText::_('COM_SFS_24_HOURS'): str_replace(':','h',$item->stop_selling_time);
                            $html.='<div style="padding-left:20px;font-weight: normal;">';
                            if($reservationid!=$item->id){
                                 $html.='<input type="checkbox" name="mealplan'.$item->id .'" id="mealplan'.$item->id .'" value="1" checked="checked" />';
                             }
                            $html.=JText::_('COM_SFS_DINNER').'&lt;'.$item->course_type.' '.JText::_('COM_SFS_COURSE').' - '.$item->stop_selling_time.'&gt;</div>';
                        }
                        if ( floatval($item->breakfast) > 0 ){
                            $html.='<div style="padding-left:20px;font-weight: normal;">';
                            if( (int) $item->bf_service_hour==1){
                                                $item->breakfastTime = "<".JText::_('COM_SFS_24_HOURS').">;";
                                            } else if((int) $item->bf_service_hour==2){
                                                $item->breakfastTime = "<".str_replace(':','h',$item->bf_opentime).'-'.str_replace(':','h',$item->bf_closetime).">";
                                            } else {
                                                $item->breakfastTime ='';
                                            }
                            if($reservationid!=$item->id){
                                $html.='<input type="checkbox" name="breakfast'.$item->id.'" id="breakfast'.$item->id.'" value="1" checked="checked" />';
                            }
                            
                            $html.=JText::_('COM_SFS_BREAKFAST').$item->breakfastTime.'</div>';
                        }
                        if( floatval($item->lunch) > 0 ){
                            $html.='<div style="padding-left:20px;font-weight: normal;">';
                            if( (int) $item->lunch_service_hour==1){
                                                $item->lunchTime = "<".JText::_('COM_SFS_24_HOURS').">";
                                            } else if((int) $item->lunch_service_hour==2){
                                                $item->lunchTime = "<".str_replace(':','h',$item->lunch_opentime).'-'.str_replace(':','h',$item->lunch_closetime).">";
                                            } else {
                                                $item->lunchTime ='';
                                            }
                            if($reservationid!=$item->id){
                                $html.='<input type="checkbox" name="lunch'.$item->id.'" id="lunch'.$item->id.'" value="1" checked="checked" />';
                            }
                            $html.=JText::_('COM_SFS_LUNCH').$item->lunchTime;
                            $html.='</div>';
                        }
                        $html.='</td>';
                        $html.='<td width="10%" '.$style.'>';
                        if($countS  && $single_room_available == 1 ){
                            $html.='S '.$availableS;  
                            $html.='<input type="hidden" id="s_number_room'.$item->id .'" name="s_number_room'.$item->id .'" value="'.$availableS.'" />';
                        }                                   
                        $html.='</td>';
                        $html.='<td width="10%" '.$style.'>';
                        if($countSD){
                            $html.='S/D '.$availableSD;  
                            $html.='<input type="hidden" id="sd_number_room'.$item->id .'" name="sd_number_room'.$item->id.'" value="'.$availableSD.'" />';
                        }                        
                        $html.='</td>';
                        $html.='<td width="10%" '.$style.'>';
                        if($countT){
                            $html.='T '.$availableT;  
                            $html.= '<input type="hidden" id="t_number_room'.$item->id .'" name="t_number_room'.$item->id.'" value="'.$availableT.'" />';
                        }  
                        $html.='</td>';
                        $html.='<td width="10%" '.$style.'>';
                        if($countQ && $quad_room_available == 1 ){
                            $html.='Q '.$availableQ;   
                            $html.='<input type="hidden" id="q_number_room'.$item->id .'" name="q_number_room'.$item->id.'" value="'.$availableQ.'" />';
                        }  
                        $html.='</td>';  
                        // add meal
                        // $checked='';
                        // if ( floatval($item->mealplan) > 0 ){
                        //     $checked='checked="checked"';
                        // }
                        // $html.='<input style="display:none;" type="checkbox" name="mealplan'.$item->id.'" id="mealplan'.$item->id.'" value="1" '.$checked.' />';  
                        // $checked='';
                        // if ( floatval($item->breakfast) > 0 ) {
                        //     $checked = 'checked="checked"';
                        // }
                        // $html.='<input style="display:none;" type="checkbox" name="breakfast'.$item->id .'" id="breakfast'.$item->id.'" value="1" '.$checked.' />';
                        // $checked='';
                        // if( floatval($item->lunch) > 0 ){
                        //     $checked = 'checked="checked"';
                        // }
                        // $html.='<input style="display:none;" type="checkbox" name="lunch'.$item->id.'" id="lunch'.$item->id.'" value="1" '.$checked.' />';
                        $html.='</tr>';
                        /*$html.='<tr><td>';
                        $html.='total_single_rooms<input type="text" name="total_single_rooms'.$item->hotel_id.'" value="" />';
                        $html.='total_double_rooms<input type="text" name="total_double_rooms'.$item->hotel_id.'" value="" />';
                        $html.='total_triple_rooms<input type="text" name="total_triple_rooms'.$item->hotel_id.'" value="" />';
                        $html.='total_quad_rooms<input type="text" name="total_quad_rooms'.$item->hotel_id.'" value="" />';
                        $html.='</td></tr>';*/
                        if($blockdate==''){
                            $jquery.="jQuery('#reservation-toggle-".$item->id."').click(function(){
                                jQuery('.tbl-list-hotel .ui.toggle.checkbox').removeClass('checked');
                                jQuery('.tbl-list-hotel .ui.toggle.checkbox input[type=checkbox]').prop('checked', false);          
                                jQuery(this).addClass('checked');   
                                jQuery('#'+jQuery(this).attr('id')+' input[type=checkbox]').prop('checked', true); ";
                            if($blockdate=='')
                                $jquery.="createlistpassroom(".$item->id.");";
                            $jquery.="});".$hotel_active;    
                        }
                        
                }
                $html.='</table><script type="text/javascript">jQuery( document ).ready(function() {jQuery(".ui.checkbox").checkbox(); jQuery(".hasTip").each(function() {
                var title = jQuery(this).attr("title");
                if (title) {
                    var parts = title.split("::", 2);
                    var mtelement = document.id(this);
                    mtelement.store("tip:title", parts[0]);
                    mtelement.store("tip:text", parts[1]);
                }';
                $html.='});';
                if($blockdate==''){
                    if(JRequest::getInt('reservationid_book')){
                        $html.='setTimeout(function(){
                    jQuery("#reservation-toggle-'.JRequest::getInt('reservationid_book').'").trigger("click");
                }, 1500);';
                    }
                }
                
            $html.='var JTooltips = new Tips(jQuery(".hasTip").get(), {"maxTitleChars": 50,"fixed": false});

'.$jquery.'});</script>';
            }                
        }
        if($html!='')
            echo $html;
        else
            echo $html='No Data';
        die();
    }
    public function addHotelPassenger(){
        $app = JFactory::getApplication();
        $postData = $app->input->post;        
        if( ! SFSAccess::isAirline($user) ) {
            JError::raiseError(403, JText::_('Sorry you can not access to this page'));
        }
        
        $model = $this->getModel('Match','SfsModel');   
            
        $result = $model->matchIssueVoucherHotel();
        
        $share_room     = JRequest::getVar('share_room', array(), 'post', 'array');

        echo json_encode($result);
        die(); 

    }
    public function updateNameFlag(){
        $user    = JFactory::getUser();
        if( ! SFSAccess::isAirline($user) ) {
            JError::raiseError(403, JText::_('Sorry you can not access to this page'));
        }
        $passenger_id=JRequest::getInt('pass_id');
        $nameflag=JRequest::getVar('name_flag');
        $model = $this->getModel('Passengersimport','SfsModel');   
        $model ->updateNameFlag($passenger_id,$nameflag);
        die();
    }
    public function updateNameFlagFi(){
        $user    = JFactory::getUser();
        if( ! SFSAccess::isAirline($user) ) {
            JError::raiseError(403, JText::_('Sorry you can not access to this page'));
        }
        $fi_id=JRequest::getInt('fi_id');
        $name_flag=JRequest::getVar('name_flag');
        $model = $this->getModel('Passengersimport','SfsModel');   
        $model ->updateNameFlagFi($fi_id,$name_flag);
    }
    //End Minh Tran

    //------Begin Train(CPhuc_code)
    public function issueTrainVoucher(){
        $airline    = SFactory::getAirline();
        $airlineId  = $airline->id;
        $model                  = $this->getModel('Trains','SfsModel');
        $data['flight_number']  = JRequest::getVar('flight-number');
        $num_passengers         = JRequest::getInt('passengers');
        $passengers             = JRequest::getVar('passenger', array() , 'post', 'array');
        $data['from_address']   = JRequest::getInt('from-address');
        $data['to_address']     = JRequest::getInt('to-address');
        $data['date_expire']    = JRequest::getVar('date_expire');
        $data['type']           = 1;
        
        foreach($passengers as $value){
            $result[] = array_merge($data, $value);
        }
        $info = $model->saveTrain($result); 
        if ($info) {
            $data = new stdClass();
            $data->name         = $passengers['0']['first_name'].' '.$passengers['0']['last_name'];
            echo json_encode($data);
            exit(0);
        }
        else{
            exit(0);
        }
        
    }
    //------End Train(CPhuc_code)

    //begin CPhuc
    public function updatePassenger(){
        $profile = new stdClass();
        $profile->email_address  = trim(JRequest::getVar('email_val'));
        $profile->phone_number   = trim(JRequest::getVar('phone_val'));
        $profile->id             =JRequest::getVar('passenger_id');
        $model = $this->getModel('Tracepassenger','SfsModel');
        $result = $model->updatePassenger($profile);
        print_r($result);
        die();
    }
    //end CPhuc


    public function searchPartner(){

        $app     = JFactory::getApplication();      
        $user    = JFactory::getUser();
        if( ! SFSAccess::check($user, 'a.admin') )  {           
            JError::raiseError(404, JText::_('Restricted Access'));
            return false;   
        } 
        $search_key = JRequest::getVar('search_key','');
        
        $model = $this->getModel('Passengersimport','SfsModel'); 
        $results = $model->getListPartner($search_key);

        if($results){
            $arr = array('successful' => "1",'data' => $results, 'errorcode' => 'maxl', 'errormessage' => 'Update Error');
            echo json_encode( $arr );   
        }else{
            $arr = array('successful' => "0",'data' => '', 'errorcode' => 'maxl', 'errormessage' => 'No data');
            echo json_encode( $arr );   
        }
        die;
    }

    public function SavePassengerPartner(){
        $user = JFactory::getUser();  
        $list_partner     = JRequest::getVar('list_partner', array(), 'post', 'array');
        $pas_id     = JRequest::getVar('pas_id', array(), 'post', 'array');
        
        if(count($list_partner)>0){
            $model = $this->getModel('Passengersimport','SfsModel'); 
            if(count($pas_id)>0 && count($list_partner)>0){                
                foreach ($pas_id as $key => $value) {
                    // foreach ($list_partner as $partner) {
                    
                    $check = $model->checkPassengerPartner($value,$list_partner[0]['user_id']);

                    if($check==0){
                        $result = $model->savePassengerPartner($value,$list_partner[0]['user_id']);
                        $db = JFactory::getDbo();

                        $query = 'SELECT name FROM #__users WHERE id = ' .$list_partner[0]['user_id'];
                        $db->setQuery($query);
                        $nameUser = $db->loadObject();                          

                        if(empty($result)){
                            $arr = array('successful' => "0",'errorcode' => 'maxl', 'errormessage' => 'Update Error');
                            echo json_encode( $arr );  
                            die;
                        }else{

                            ob_start();
                            require_once JPATH_COMPONENT.'/libraries/emails/sendmail_assign_passengerimport.php';
                            $bodyData = ob_get_clean();

                            $bodyData  = str_replace('{name}', $nameUser->name, $bodyData);
                            $bodyData  = str_replace('{user}', $user->name, $bodyData);

                            if( strtolower($partner[0]->typ) == "email" ){
                                // JFactory::getMailer()->sendMail($user->email, 'Stranded Flight Solutions', $list_partner[0]['email'], 'Assign Passenger', $bodyData, $mode = 1,true);
                            }else{
                                $numberFax = trim(SfsHelper::formatPhone( $partner[0]->fax, 2));
                                // JFactory::getMailer()->sendMail('airline_support@sfs-web.com', 'Stranded Flight Solutions',$numberFax.'@efaxsend.com', 'Assign Passenger', $bodyData, true);
                            }                            
                        }  
                    }                        
                    // }
                }
                $arr = array('successful' => "1",'data' => '', 'errorcode' => 'maxl', 'errormessage' => 'No data');
                echo json_encode( $arr );    
                die; 
            }
            
        }
        die;
    }
}

