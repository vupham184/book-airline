<?php

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');



class SfsControllerPassengersimport extends JController {



	public function __construct($config = array())

	{

		parent::__construct($config);



	}	

	



	//begin CPhuc


	public function saveTrainTicket(){

		$model                  		= $this->getModel('Passengersimport','SfsModel');

		$data['id_from_trainstation']	= JRequest::getVar('id_from_trainstation');

		$data['id_to_trainstation']		= JRequest::getVar('id_to_trainstation');

		$data['travel_date']			= JRequest::getVar('travel_date');

		$data['type']					= JRequest::getVar('type');

		$data_passenger 				= JRequest::getVar('data_passenger');

		foreach($data_passenger as $value){

			$value1['passenger_id'] = $value;

            $result[] = array_merge($data, $value1);

        } 



        $info = $model->saveTrain($result); 

		print_r($info); die();

	}

	//end CPhuc

	public function addTileIssueVoucher(){

		$object				= new stdClass();

		$object->title 		= $_POST['title'];

		$object->id_ariline	= $_POST['id_ariline'];

		$model 				= $this->getModel('Passengersimport','SfsModel');

		$result				= $model->addTileIssueVoucher($object);	

		echo $result;

		die();

	}



	public function saveservicerefreshment(){

		$data['amount'] 	= JRequest::getVar('amount'); 

		$data['currency'] 	= JRequest::getVar('currency');

		$data['textfresh'] 	= trim(JRequest::getVar('textfresh'));

		$data['delaytime'] 	= JRequest::getVar('delayrefesh');

		$passenger_ids = JRequest::getVar('passenger_id');

		foreach ($passenger_ids as $value) {

			$value1['passenger_id'] = $value;

			

			$result[] = array_merge($data, $value1);

		}

		$model 		= $this->getModel('Passengersimport','SfsModel');

		$total 		= $model->saveIssueRefreshment($result);
		echo $total; die();
	}



	public function loadservicerefreshment(){

		$passenger_ids = JRequest::getVar('passenger_id');

		$model 		= $this->getModel('Passengersimport','SfsModel');

		$total 		= $model->loadIssueRefreshment($passenger_ids);

		print_r($total); exit();

	}



	public function dataCkeckIssueVoucher(){      

		$app = JFactory::getApplication();



		$strIdGroup =  implode(", ",$_POST['pas_id_group']);



		$model   = $this->getModel('Passengersimport','SfsModel'); 

		$result  = $model->getPassengers($strIdGroup);



		echo json_encode($result); die();

	}



	public function changeAirportGroup(){  



		$model   = $this->getModel('Passengersimport','SfsModel');

		$result = $model->getchangeAirportGroup( $_POST['id'] );   

		echo json_encode($result);

		die();

	}

	

	//sfs_passenger_group_transport_company_map

	public function insertGroupTransportCompany(){

		$data = JRequest::getVar('data', array());

		$model   = $this->getModel('Passengersimport','SfsModel');

		$model->addGroupTransportCompany( $data );

		echo json_encode( array( 'mess' => 'success' ) );

		exit;

	}

	

	public function insertPassengerGroupTransportCompanyMap(){

	}

	

	public function insertGroupTransportCompanyOtherPrice(){

		$data = JRequest::getVar('data', array());

		$model   = $this->getModel('Passengersimport','SfsModel');

		$result = $model->addGroupTransportCompanyOtherPrice( $data );
		

		// echo json_encode( array( 'mess' => 'success' ) );
		if ($result) {
			 $info	 = $model->savePricePerPersonOfBus($data );
		}
		print_r($result);

		 die();
		

	}



	/*beginCPhuc*/

	public function loadDistanceTaxi(){

		$model 		= $this->getModel('Passengersimport','SfsModel');
		$result 	= '';
		$result 	=json_encode($model->getDistanceHotel());

		echo $result; die();

	}

	public function saveIssueTaxi()

	{

		$data['airport_id'] = JRequest::getVar('airport_id');

		switch (JRequest::getVar('data_option_taxi')) {

			case 'option1':

			$data['option_taxi'] = 1;

			break;

			case 'option2':

			$data['option_taxi'] = 2;

			break;

			case 'option3':

			$data['option_taxi'] = 3;

			break;

			case 'option4':

			$data['option_taxi'] = 4;

			break;

			default:

			$data['option_taxi'] = 0;

			break;

		}


		$data['total_price'] = str_replace(',', '', JRequest::getVar('total_price'));

		$data['taxi_selected'] = JRequest::getVar('taxi_selected');

		$data['distance'] = JRequest::getVar('distance');

		$data['from_andress'] = JRequest::getVar('from_andress');

		$data['to_address'] = JRequest::getVar('to_address');
		$data['way_option'] = JRequest::getVar('way');

		if (isset($data['taxi_selected']) && !empty($data['taxi_selected'])) {

			$data['hotel_id'] = JRequest::getVar('hotel_id');

			$id_passenger = json_decode('['.str_replace("'", '"', JRequest::getVar('id_passengers')).']');

			foreach ($id_passenger as $value) {

				$value1['passenger_id'] = $value;

				$result[] = array_merge($data, $value1); 

			}

			$model 		= $this->getModel('Passengersimport','SfsModel');

			$total 		= $model->saveIssueTaxi($result,$id_passenger);

			print_r($total);

			exit;
		}

		else

			echo 0; exit(0);



	}

	/*endCPhuc*/

	

	//lchung

	public function updateStatusVoucher() {

		$model 		= $this->getModel('Passengersimport','SfsModel');

		$model->updateStatusVoucher();

		echo json_encode( array('successful' => 1, 'errormessage' => 'successful') );

		exit;

	}

	

	public function sendemailvoucher(){

		$pass_ids  = JRequest::getVar('pass_ids', array() , 'post', 'array');
		$list_email='';
		if(count($pass_ids)>0){
			$model   = $this->getModel('Passengersimport','SfsModel');
			$passengers = $model->getListPassengerByIds(implode(",",$pass_ids),'email_address');
			foreach ($passengers as $key => $value) {
				//$email	 		= $value->email_address;
				$email = 'lchung.ph@gmail.com';
				$voucher_id		= $value->voucher_id;

				$passenger_id		= $value->id;
				$voucher 		= SVoucher::getInstance($voucher_id,'id');

		        $reservation = SReservation::getInstance((int)$voucher->booking_id);

		        $isHotelAirline = SfsHelper::isHotelCreatedByAirline($reservation->hotel_id);

				jimport('joomla.mail.helper');

				// Make sure the mail is correct

				if ( ! JMailHelper::isEmailAddress($email) )
				{
					echo json_encode( array('successful' => 0, 'errormessage' => 'Invalid Email') );

					exit;

				}

				// Send the voucher

				$sent = SEmail::guestVoucher( $email , $voucher );

				 // Check for an error.

				if ( !$sent ) {

					echo json_encode( array('successful' => 0, 'errormessage' => JText::_('COM_SFS_MATCH_PASSENGER_SEND_MAIL_FAILED')) );

					exit;

				}
				if($list_email!=''){
					$list_email.=', '.$email;
				}else{
					$list_email.=$email;
				}
				sleep(1);
			}
		}
		else{
			/// gui cho 1 passenger
			//$email	 		= JRequest::getVar('email');
			$email = 'lchung.ph@gmail.com';

			$voucher_id		= JRequest::getInt('voucher_id');

			$passenger_id		= JRequest::getInt('passenger_id');

			

			$voucher 		= SVoucher::getInstance($voucher_id,'id');

	        $reservation = SReservation::getInstance((int)$voucher->booking_id);

	        $isHotelAirline = SfsHelper::isHotelCreatedByAirline($reservation->hotel_id);

			

			jimport('joomla.mail.helper');

			// Make sure the mail is correct

			if ( ! JMailHelper::isEmailAddress($email) )

			{

				echo json_encode( array('successful' => 0, 'errormessage' => 'Invalid Email') );

				exit;

			}

			// Send the voucher

			$sent = SEmail::guestVoucher( $email , $voucher );

			 // Check for an error.

			if ( !$sent ) {

				echo json_encode( array('successful' => 0, 'errormessage' => JText::_('COM_SFS_MATCH_PASSENGER_SEND_MAIL_FAILED')) );

				exit;

			}
			$list_email = $email;
		}
		
		echo json_encode( array('successful' => 1, 'errormessage' => JText::sprintf('COM_SFS_MATCH_PASSENGER_SEND_MAIL_SUSCCESS',$voucherCode,$list_email) ) );

		exit;

	}

	//End lchung


	public function getLocationHotel() {
		$model 	= $this->getModel('Passengersimport','SfsModel');
		$result = $model->getLocationHotel();

		echo $result;
		exit;
	}

	//begin CPhuc
	public function saveIrregReason(){
		$ids 					= json_decode('['.str_replace("'", '"', JRequest::getVar('ids')).']');
		$id_irreg_reason		= (int)JRequest::getVar('irreg_reason');
		if ($id_irreg_reason == 8) {
			$data['irreg_reason'] 	= 'MAAS';
		}
		if ($id_irreg_reason == 9) {
			$data['irreg_reason'] 	= 'WAAS';
		}
		
		foreach($ids as $value){
			$value1['id'] = $value;
            $result[] = array_merge($data, $value1);
        } 
		$model 	= $this->getModel('Passengersimport','SfsModel');
        $info 	= $model->saveIrregReason($result);

        print_r($info); die();
	}



	public function saveSSMaas(){
		$ids 					= json_decode('['.str_replace("'", '"', JRequest::getVar('ids')).']');
		$data['comment'] 		= trim(JRequest::getVar('commnet'));
		$data['user_id'] 		= JRequest::getVar('user_id');
		$data['airline_id'] 	= JRequest::getVar('airline_id');
		$data['created_date'] 	= date('Y-m-d');
		foreach($ids as $value){
			$value1['id'] = $value;
            $result[] = array_merge($data, $value1);
        } 
        $ids = '('.implode(',', $ids).')';

        $model 	= $this->getModel('Passengersimport','SfsModel');
        $info 	= $model->saveSSMaas($result,$ids);

		print_r($info); die();
	}



	public function saveOtherServices(){
		
		$passenger_ids 			= json_decode('['.str_replace("'", '"', JRequest::getVar('passenger_ids')).']');
		$data['sub_service_id'] = (int)JRequest::getVar('sub_service_id');
		$data['content'] 		= trim('['.json_encode(JRequest::getVar('data_content')).']');
		$ids 					= [];

		foreach($passenger_ids as $value){
			$ids[] 					= $value;
			$value1['passenger_id'] = $value;
            $result[] = array_merge($data, $value1);
        }

        $ids 	= '('.implode(',', $ids).')';

        $model 	= $this->getModel('Passengersimport','SfsModel');
        $info 	= $model->saveOtherServices($result,$ids);

		print_r($info);
		die();
	}
	
	/*
	* Function send mail if the voucher ID not exist
	*/
	public function sendMail(){
		$user = JFactory::getUser();
		$airline = SFactory::getAirline();
        $airlineName = $airline->name;
        $logo = $airline->logo;

		$mailAddress = JRequest::getVar('mailAddress');
		$emailSubject = 'Notice of taxi service';
		ob_start();
		require_once JPATH_COMPONENT.'/libraries/emails/taxis/taxi_mail_content.php';
		$bodyE = ob_get_clean();

		$hotelEmailBody = JString::str_ireplace('{}', $airlineName, $bodyE);
		if($logo)
	        {
	            $logo = '<img src="'.JURI::base().'/'.$logo.'" />';
	        }
	    $hotelEmailBody = JString::str_ireplace('{logo}', $logo, $hotelEmailBody);

		$result = JFactory::getMailer()->sendMail($user->email, 'Notice of taxi service', $mailAddress, $emailSubject, $hotelEmailBody, true);
		echo $result;
		die();
	}
	//end CPhuc

}