<?php
// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class SfsControllerInvite_Hotel_For_Registration extends JController
{	
	
	public function __construct()
	{
		parent::__construct();
		///$this->registerTask('exportr', 'exportRoomingList');
	}
	
	public function send() {
		$user = JFactory::getUser();
		
		$config    = JFactory::getConfig();
		$airline = SFactory::getAirline();
		$ccTo_admin = $config->mailfrom;
        $logo = $airline->logo;
		$airline_name = $airline->name;
		$station_IATA_code = $airline->airport_code;
		$registration_url = 'http://www.sfs-web.org/dev3-map2/index.php?option=com_sfs&view=hotelregister&Itemid=127';
		
		$name_of_hotel = JRequest::getVar('name_of_hotel');
		$sexe = JRequest::getVar('sexe');
		$first_name_last_name = JRequest::getVar('first_name_last_name');
		$email = JRequest::getVar('email');
		$fax = JRequest::getVar('fax');
		$emailSubject =  'Your Participation Is Requested';
		ob_start();
            require_once JPATH_COMPONENT.'/libraries/emails/invite_hotel_for_registration.php';
       $bodyE = ob_get_clean();
            $EmailBody = JString::str_ireplace('{sex}', $sexe, $bodyE);
			$EmailBody = JString::str_ireplace('{lastname}', $first_name_last_name, $EmailBody);
            $EmailBody = JString::str_ireplace('{airline_name}', $airline_name, $EmailBody);
            $EmailBody = JString::str_ireplace('{hotel_name}', $name_of_hotel, $EmailBody);
			$EmailBody = JString::str_ireplace('{station_IATA_code}', $station_IATA_code, $EmailBody);
			$EmailBody = JString::str_ireplace('{fax}', $fax, $EmailBody);
			if ( $registration_url != '' ) {
				$registration_url ='<a href="' . $registration_url . '" target="_blank">' . $registration_url . '</a>';
			}
			$EmailBody = JString::str_ireplace('{registration_url}', $registration_url, $EmailBody);
			
            if($logo)
            {
                $logo = '<img src="'.JURI::base().'/'.$logo.'" />';
            }
            $EmailBody = JString::str_ireplace('{logo}', $logo, $EmailBody);
            ///JUtility::sendMail($user->email, $emailSubject, $email, $emailSubject, $EmailBody, true);
			///$ccTo_admin = 'lecanhhung3000@yahoo.com';
			JUtility::sendMail($user->email, $emailSubject, $email, $emailSubject, $EmailBody, true, $ccTo_admin);
			$this->setRedirect('index.php');
	}
}