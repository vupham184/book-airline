<?php
defined('_JEXEC') or die;

class SfsControllerSearch extends JControllerLegacy
{	
	
	public function search()
	{		
		//JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		$post['rooms']  	= JRequest::getInt('rooms', null, 'post');
		$post['date_start']	= JRequest::getVar('date_start');
		$post['date_end']	= JRequest::getVar('date_end');
		$payment_type		= JRequest::getVar('payment_type');
		$session = JFactory::getSession();
		$session->set("rooms_search", json_encode(JRequest::getVar('room', array() , 'post', 'array')));

		if( $payment_type )
		{
			$session = JFactory::getSession();
			$session->set('payment_type',$payment_type);
		}
		
		
		$transport_included = JRequest::getVar('transport_included');	
			
		if($transport_included) $post['transport_included'] = 1;
		
		$show_all = JRequest::getVar('show_all_rooms');	
			
		if($show_all) $post['show_all_rooms'] = 1;		
		
		$filter_hotel_star = JRequest::getVar('filter_hotel_star');

		
		if($filter_hotel_star) {
			$hotel_star = JRequest::getVar('hotel_star');
			$post['hotel_star'] = $hotel_star;
		}

		$offer_meal_plans = JRequest::getVar('offer_meal_plans');


		if($offer_meal_plans) $post['offer_meal_plans'] = 1;
				
		// set Itemid id for links from menu
		$app	= JFactory::getApplication();
		$menu	= $app->getMenu();
		$items	= $menu->getItems('link', 'index.php?option=com_sfs&view=search');

		if(isset($items[0])) {
			$post['Itemid'] = $items[0]->id;
		} else if (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
			$post['Itemid'] = JRequest::getInt('Itemid');
		}
		unset($post['task']);
		unset($post['submit']);

		$uri = JURI::getInstance();
		$uri->setQuery($post);
		
		$uri->setVar('option', 'com_sfs');
		$uri->setVar('view', 'search');
		
		$msg = '';
		if($post['rooms']==0){			
			//$msg = 'Invalid data';
		}
		$pass_issue_hotel='';
		if(JRequest::getString('pass_issue_hotel')){
			$pass_issue_hotel='&pass_issue_hotel='.JRequest::getString('pass_issue_hotel').'&tb_share_room='.JRequest::getString('tb_share_room').'&room_book='.JRequest::getString('room_book');
		}		
		$pass_detail_hotel='';
		if(JRequest::getString('pass_detail_hotel')){
			$pass_detail_hotel='&pass_detail_hotel='.JRequest::getString('pass_detail_hotel');
		}	
		$this->setRedirect(JRoute::_('index.php'.$uri->toString(array('query', 'fragment')).$pass_issue_hotel.$pass_detail_hotel, false),$msg);
	}
	
	public function getModel($name = 'Search', $prefix = 'SfsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function autoSearch(){
		$m = $this->getModel();
		$m->autoSearch();
	}
	
	public function getAirportLocation( ){
		set_time_limit(0);
		$this->getFile();
		///$dAirportLocationSearch = SfsWs::getAllAirportLocation();
		$str = "
			var AirportCodeArr = Array();
		";
		$arr = array();
		$ik = 0;
		$list = array();
		foreach ( $dAirportLocationSearch as $vk => $v ) {
			$airport_code = $v['AirportCode'];
			if ( !array_key_exists($airport_code, $arr) ) {
				$list[] = $v;
				$str .= "
					AirportCodeArr[$ik] = '$airport_code';
				";
				$ik++;
			}
			$arr[$airport_code] = $airport_code;
		}
		$data[0] = $str;
		$data[1] = $list;
		echo json_encode( $data );
		exit;
	}
	
	public function getFile(){
		$file = JPATH_SITE.DS.'ws'.DS.'lib'.DS.'Ws'.DS.'Adapter'.DS.'data'.DS.'AirportLocation.txt';
		$lines  = @file( $file );
		$i = 0;
		$str = "
			var AirportCodeArr = Array();
		";
		$arr = array();
		$ik = 0;
		$list = array();
		foreach($lines  as $line ){
			if ( trim($line) != '' ) {
				$arrVal = explode("|", $line);
				if ( $i > 0 ){
					$airport_code = trim($arrVal[0]);
					if ( !array_key_exists($airport_code, $arr) ) {
						$list[] = array('AirportCode' => $airport_code);
						$str .= "
							AirportCodeArr[$ik] = '$airport_code';
						";
						$ik++;
					}
					$arr[$airport_code] = $airport_code;			
				}
			}
			$i++;
		}
		$data[0] = $str;
		$data[1] = $list;
		echo json_encode( $data );
		exit;
	}
	
	public function inviteHotelsLoadingRoom(){
        jimport('joomla.mail.helper');
        $user = JFactory::getUser();
        $params = &JComponentHelper::getParams('com_sfs');
        $airportName = $params->get('sfs_system_airport');
        $airline = SFactory::getAirline();
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
            $hotelEmailBody = JString::str_ireplace('{}', $airportName, $bodyE);
            $hotelEmailBody = JString::str_ireplace('{sender_name}', $user->name, $hotelEmailBody);
            $hotelEmailBody = JString::str_ireplace('{sender_email}', $user->email, $hotelEmailBody);
            if($logo)
            {
                $logo = '<img src="'.JURI::base().'/'.$logo.'" />';
            }
            $hotelEmailBody = JString::str_ireplace('{logo}', $logo, $hotelEmailBody);


            foreach ( $hotel_contacts as $hotelContact ) {
                JUtility::sendMail($user->email, 'Stranded Flight Solutions', $hotelContact->email, $hotelEmailSubject, $hotelEmailBody, true);
            }
            //Email for Fax Service
            $faxNumber = trim(SfsHelper::formatPhone( $hotel->fax, 1)).trim(SfsHelper::formatPhone( $hotel->fax, 2));
            JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$faxNumber.'@efaxsend.com', $hotelEmailSubject, $hotelEmailBody, true);

            $hotelBackendSetting = $hotel->getBackendSetting();
            if( $second_fax = $hotelBackendSetting->second_fax )
            {
                JUtility::sendMail('hotel_support@sfs-web.com', 'Stranded Flight Solutions',$second_fax.'@efaxsend.com', $hotelEmailSubject, $hotelEmailBody, true);
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
        exit();
    } 
}
